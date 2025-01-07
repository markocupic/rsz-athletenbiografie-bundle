<?php

declare(strict_types=1);

/*
 * This file is part of RSZ Athletenbiografie Bundle.
 *
 * (c) Marko Cupic 2025 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-athletenbiografie-bundle
 */

namespace Markocupic\RszAthletenbiografieBundle\RszUser;

use Contao\Folder;
use Contao\Message;
use Contao\StringUtil;
use Contao\System;
use Contao\UserModel;

class RszUser
{
    public function __construct(
        private readonly string $projectDir,
    ) {
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

            $arrFunktion = StringUtil::deserialize($objUser->funktion, true);

            if (!empty($arrFunktion)) {
                if (\in_array('Athlet', $arrFunktion, true)) {
                    $folder = System::getContainer()->getParameter('rsz_athletenbiografie.document_dir').'/'.$objUser->username;

                    if (!is_dir($this->projectDir.'/'.$folder)) {
                        new Folder($folder);
                        Message::addInfo(sprintf('Added new folder %s to filesystem.', $folder));
                    }
                }
            }
        }
    }
}
