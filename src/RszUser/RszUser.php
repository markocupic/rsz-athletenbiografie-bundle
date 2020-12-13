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

namespace Markocupic\RszAthletenbiografieBundle\RszUser;

use Contao\Folder;
use Contao\Message;
use Contao\System;
use Contao\UserModel;

/**
 * Class RszUser.
 */
class RszUser
{
    /**
     * @var string
     */
    private $projectDir;

    /**
     * RszUser constructor.
     */
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * Create folders for all athletes
     * This allows to trainers to store files.
     *
     * @throws \Exception
     */
    public function createFolders(): void
    {
        // Synchronize all tl_user.passwords with tl_member.passwords
        $objUser = UserModel::findAll();

        if (null === $objUser) {
            return;
        }

        while ($objUser->next()) {
            if (!$objUser->isRSZ || empty($objUser->username) || empty($objUser->name)) {
                continue;
            }

            $arrFunktion = unserialize($objUser->funktion);

            if (!empty($arrFunktion) && \is_array($arrFunktion)) {
                if (\in_array('Athlet', $arrFunktion, true)) {
                    $folder = System::getContainer()->getParameter('rsz-athletenbiografie-file-directory').'/'.$objUser->username;

                    if (!is_dir($this->projectDir.'/'.$folder)) {
                        new Folder($folder);
                        Message::addInfo(sprintf('Added new folder %s to filesystem.', $folder));
                    }
                }
            }
        }
    }
}
