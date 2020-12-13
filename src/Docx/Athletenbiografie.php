<?php

declare(strict_types=1);

/*
 * This file is part of Contao Bundle Creator Bundle.
 *
 * (c) Marko Cupic 2020 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-athletenbiografie-bundle
 */

namespace Markocupic\RszAthletenbiografieBundle\Docx;

use Contao\Date;
use Contao\Environment;
use Contao\FilesModel;
use Contao\Model\Collection;
use Contao\UserModel;
use Contao\Validator;
use Markocupic\PhpOffice\PhpWord\MsWordTemplateProcessor;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;

/**
 * Class Athletenbiografie.
 */
class Athletenbiografie
{
    /**
     * @var string
     */
    private const TEMPLATE_SRC = 'vendor/markocupic/rsz-athletenbiografie-bundle/src/Resources/contao/templates/docx/athletenbiografie.docx';

    /**
     * @var string
     */
    private const TARGET_FILENAME = 'system/tmp/athletenbiografie_%s_%s.docx';

    /**
     * @var string
     */
    private $projectDir;

    /**
     * Athletenbiografie constructor.
     */
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @throws CopyFileException
     * @throws CreateTemporaryFileException
     */
    public function print(Collection $objAthletenbiografie, UserModel $objUser): void
    {
        $targetFilename = sprintf(static::TARGET_FILENAME, str_replace(' ', '', strtolower($objUser->username)), Date::parse('Y-m-d', time()));

        // Create template processor object
        $objPhpWord = new MsWordTemplateProcessor(static::TEMPLATE_SRC, $targetFilename);

        $objPhpWord->replace('athlete_name', $objUser->name);
        $objPhpWord->replace('athlete_dateOfBirth', Date::parse('d.m.Y', $objUser->dateOfBirth));
        $objPhpWord->replace('date', Date::parse('d.m.Y', time()));

        while ($objAthletenbiografie->next()) {
            // Clone row
            $objPhpWord->createClone('dateAdded');

            $dateAdded = (string) Date::parse('d.m.Y', $objAthletenbiografie->dateAdded);
            $notice = (string) $objAthletenbiografie->notice;
            $title = (string) $objAthletenbiografie->title;

            // Push data to clone
            $objPhpWord->addToClone('dateAdded', 'dateAdded', $dateAdded, ['multiline' => false]);
            $objPhpWord->addToClone('dateAdded', 'title', $title, ['multiline' => true]);
            $objPhpWord->addToClone('dateAdded', 'notice', $notice, ['multiline' => true]);

            $arrLinks = [];
            $countAttachments = 0;

            // Handle attachments
            $arrFiles = deserialize($objAthletenbiografie->multiSRC);

            if (!empty($arrFiles) && \is_array($arrFiles)) {
                foreach ($arrFiles as $uuid) {
                    if (Validator::isUuid($uuid)) {
                        $objFile = FilesModel::findByUuid($uuid);

                        if (is_file($this->projectDir.'/'.$objFile->path)) {
                            $arrLinks[] = [
                                'name' => $objFile->name,
                                'extension' => $objFile->extension,
                                'path' => $objFile->path,
                                'download' => sprintf('%s/contao/popup?src=%s==&download=1', Environment::get('url'), base64_encode($objFile->path)),
                            ];
                            ++$countAttachments;
                        }
                    }
                }
            }

            $strAttachments = '';

            if ($countAttachments) {
                $strAttachments = sprintf($GLOBALS['TL_LANG']['MSC']['downloasRszAthleteAttachmentsFound'], $countAttachments);
            }

            $objPhpWord->addToClone('dateAdded', 'files', $strAttachments, ['multiline' => true]);
        }
        // Generate Docx file from template;
        $objPhpWord->generateUncached(true)
            ->sendToBrowser(true)
            ->generate()
        ;
    }
}
