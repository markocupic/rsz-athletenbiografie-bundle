<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ AthletenumfrageBundle
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-athletenumfrage-bundle
 *
 */

/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['Athletenumfragen']['tl_athletenumfrage'] = [
    'tables' => ['tl_athletenumfrage']
];

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_athletenumfrage'] = \Markocupic\RszAthletenumfrageBundle\Model\AthletenumfrageModel::class;


// Maintenance
// Delete unused event-story folders
$GLOBALS['TL_PURGE']['custom']['rsz_athletenumfrage'] = [
    'callback' => [\Markocupic\RszAthletenumfrageBundle\ContaoBackendMaintenance\MaintainModuleAthletenumfrage::class, 'truncateAthletenumfrage'],
];
