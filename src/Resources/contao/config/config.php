<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ Athletenbiografie
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-athletenbiografie-bundle
 *
 */

/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['rsz_tools']['rsz_athletenbiografie'] = array(
    'tables' => ['tl_rsz_athletenbiografie']
);

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_rsz_athletenbiografie'] = Markocupic\RszAthletenbiografieBundle\Model\RszAthletenbiografieModel::class;

