<?php

/*
 * This file is part of Contao Bundle Creator Bundle.
 *
 * (c) Marko Cupic 2020 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-athletenbiografie-bundle
 */

use Markocupic\RszAthletenbiografieBundle\Model\RszAthletenbiografieModel;

/**
 * Backend modules
 */
$GLOBALS['BE_MOD']['rsz_tools']['rsz_athletenbiografie'] = array(
	'tables' => array('tl_rsz_athletenbiografie')
);

/**
 * Models
 */
$GLOBALS['TL_MODELS']['tl_rsz_athletenbiografie'] = RszAthletenbiografieModel::class;
