<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ Athletenbiografie
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-athletenbiografie-bundle
 *
 */

declare(strict_types=1);

namespace Markocupic\RszAthletenbiografieBundle\RszUser;

use Contao\Folder;
use Contao\Message;
use Contao\UserModel;

/**
 * Class RszUser
 * @package Markocupic\RszAthletenbiografieBundle\RszUser
 */
class RszUser
{
    /**
     * @var string
     */
    private $projectDir;

    /**
     * @var string
     */
    const ATHLETENBIOGRAFIE_DIRECTORY = 'files/Dateiablage/user_dir/athletenbiografie';

    /**
     * RszUser constructor.
     */
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * Create folders for all athletes
     * This allows to trainers to store files
     * @throws \Exception
     */
    public function createFolders()
    {
        // Synchronize all tl_user.passwords with tl_member.passwords
        $objUser = UserModel::findAll();
        if ($objUser === null)
        {
            return;
        }
        while ($objUser->next())
        {
            if (!$objUser->isRSZ || empty($objUser->username) || empty($objUser->name))
            {
                continue;
            }

            $arrFunktion = deserialize($objUser->funktion);
            if (!empty($arrFunktion) && is_array($arrFunktion))
            {
                if (in_array('Athlet', $arrFunktion))
                {
                    $folder = static::ATHLETENBIOGRAFIE_DIRECTORY . '/' . $objUser->username;
                    if (!is_dir($this->projectDir . '/' . $folder))
                    {
                        new Folder($folder);
                        Message::addInfo(sprintf('Added new folder %s to filesystem.', $folder));
                    }
                }
            }
        }
    }

}
