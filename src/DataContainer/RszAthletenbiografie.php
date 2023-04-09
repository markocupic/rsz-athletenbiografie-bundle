<?php

declare(strict_types=1);

/*
 * This file is part of RSZ Athletenbiografie Bundle.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-athletenbiografie-bundle
 */

namespace Markocupic\RszAthletenbiografieBundle\DataContainer;

use Contao\Backend;
use Contao\Controller;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\Database;
use Contao\DataContainer;
use Contao\FilesModel;
use Contao\Message;
use Contao\StringUtil;
use Contao\UserModel;
use Contao\Validator;
use Markocupic\RszAthletenbiografieBundle\Docx\Athletenbiografie;
use Markocupic\RszAthletenbiografieBundle\Model\RszAthletenbiografieModel;
use Markocupic\RszAthletenbiografieBundle\RszUser\RszUser;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RszAthletenbiografie extends Backend
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly Athletenbiografie $athletenbiografie,
        private readonly RszUser $rszUser,
        private readonly TranslatorInterface $translator,
        private readonly string $projectDir,
    ) {
        parent::__construct();
    }

    #[AsCallback(table: 'tl_rsz_athletenbiografie', target: 'fields.athlete.options', priority: 100)]
    public function getAthletes(): array
    {
        $arrUsers = [];
        $objUser = Database::getInstance()
            ->execute('SELECT * FROM tl_user ORDER BY name')
        ;

        while ($objUser->next()) {
            $arrFunktion = StringUtil::deserialize($objUser->funktion, true);

            if (\in_array('Athlet', $arrFunktion, true)) {
                $arrUsers[$objUser->id] = $objUser->name;
            }
        }

        return $arrUsers;
    }

    #[AsCallback(table: 'tl_rsz_athletenbiografie', target: 'fields.author.options', priority: 100)]
    public function getUsers(): array
    {
        $arrUsers = [];
        $objUser = Database::getInstance()
            ->execute('SELECT * FROM tl_user ORDER BY name')
        ;

        while ($objUser->next()) {
            $arrUsers[$objUser->id] = $objUser->name;
        }

        return $arrUsers;
    }

    #[AsCallback(table: 'tl_rsz_athletenbiografie', target: 'config.onload', priority: 100)]
    public function loadAssets(): void
    {
        $GLOBALS['TL_CSS'][] = 'web/bundles/markocupicrszathletenbiografie/backend.css||static';
    }

    #[AsCallback(table: 'tl_rsz_athletenbiografie', target: 'config.onload', priority: 100)]
    public function downloadRszAthletenbiografie(): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if ('rsz_athletenbiografie' === $request->query->get('do') && 'downloadRszAthletenbiografie' === $request->query->get('action')) {
            $blnAllowDownload = false;

            /** @var AttributeBagInterface $bag */
            $bag = $request->getSession()->getBag('contao_backend');

            $bag->all();

            if ($bag->has('filter')) {
                $arrFilter = $bag->get('filter');

                if (isset($arrFilter['tl_rsz_athletenbiografie']['athlete'])) {
                    $blnAllowDownload = true;
                }
            }

            if (!$blnAllowDownload || !isset($arrFilter)) {
                Message::addError($this->translator->trans('ERR.downloadRszAthleteBiographyNotPossible', [], 'contao_default'));
                $this->redirect($this->getReferer());
            }

            $athleteId = $arrFilter['tl_rsz_athletenbiografie']['athlete'];

            $objUser = UserModel::findByPk($athleteId);

            $objRszAthletenbiografieModel = RszAthletenbiografieModel::findByAthlete($athleteId);

            if (null !== $objRszAthletenbiografieModel && null !== $objUser) {
                $this->athletenbiografie->print($objRszAthletenbiografieModel, $objUser);

                return;
            }

            Message::addError('Error while trying to generate biography.');
            $this->redirect($this->getReferer());
        }
    }

    #[AsCallback(table: 'tl_rsz_athletenbiografie', target: 'config.onload', priority: 100)]
    public function downloadAttachment(): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if ('rsz_athletenbiografie' === $request->query->get('do') && 'downloadAttachment' === $request->query->get('action') && '' !== $request->query->get('uuid')) {
            if ('' !== ($uuid = $request->query->get('uuid'))) {
                if (Validator::isStringUuid($uuid)) {
                    $binUuid = StringUtil::uuidToBin($uuid);

                    if (null !== ($objFile = FilesModel::findByUuid($binUuid))) {
                        if (is_file($this->projectDir.'/'.$objFile->path)) {
                            Controller::sendFileToBrowser($objFile->path, true);
                        }
                    }
                }
            }
        }
    }

    #[AsCallback(table: 'tl_rsz_athletenbiografie', target: 'config.onload', priority: 100)]
    public function createAthleteDirs(): void
    {
        $this->rszUser->createFolders();
    }

    #[AsCallback(table: 'tl_rsz_athletenbiografie', target: 'list.label.label', priority: 100)]
    public function labelCallback(array $row, string $label, DataContainer $dc, array $labels): string
    {
        $objUser = UserModel::findByPk($row['athlete']);

        if (null !== $objUser) {
            $label = str_replace('###athlete###', $objUser->name, $label);
        }

        $objUser = UserModel::findByPk($row['author']);

        if (null !== $objUser) {
            $label = str_replace('###author###', $objUser->name, $label);
        }

        // Append attachments
        $arrAttachments = [];
        $src = StringUtil::deserialize($row['multiSRC']);

        if (!empty($src) && \is_array($src)) {
            $i = 0;
            $objFiles = FilesModel::findMultipleByUuids($src);

            if (null !== $objFiles) {
                while ($objFiles->next()) {
                    ++$i;

                    if (is_file($this->projectDir.'/'.$objFiles->path)) {
                        $request = $this->requestStack->getCurrentRequest();

                        $arrAttachments[] = sprintf(
                            '<a href="contao?do=%s&amp;action=downloadAttachment&amp;uuid=%s" class="backend-listing-link" title="%s">LINK_%s</a>',
                            $request->query->get('do'),
                            StringUtil::binToUuid($objFiles->uuid),
                            StringUtil::specialchars($objFiles->name),
                            $i
                        );
                    }
                }
            }
        }

        return str_replace('###attachments###', implode(', ', $arrAttachments), $label);
    }
}
