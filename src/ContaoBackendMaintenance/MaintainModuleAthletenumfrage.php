<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ AthletenumfrageBundle
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-athletenumfrage-bundle
 *
 */

declare(strict_types=1);

namespace Markocupic\RszAthletenumfrageBundle\ContaoBackendMaintenance;

use Contao\Database;

/**
 * Class MaintainModuleAthletenumfrage
 * @package Markocupic\RszAthletenumfrageBundle\ContaoBackendMaintenance
 */
class MaintainModuleAthletenumfrage
{

    /**
     * MaintainModuleAthletenumfrage constructor.
     */
    public function __construct()
    {

    }

    /**
     * Truncate table tl_athletenumfrage
     */
    public function truncateAthletenumfrage(): void
    {
        // Truncate table
        Database::getInstance()->execute('TRUNCATE TABLE tl_athletenumfrage');

    }
}
