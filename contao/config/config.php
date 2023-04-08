<?php

declare(strict_types=1);

/*
 * This file is part of RSZ Athletenbiografie Bundle.
 *
 * (c) Marko Cupic 2022 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-athletenbiographie-bundle
 */

use Markocupic\RszAthletenbiografieBundle\Model\RszAthletenbiografieModel;

/*
 * Backend modules
 */
$GLOBALS['BE_MOD']['rsz_tools']['rsz_athletenbiografie'] = [
    'tables' => ['tl_rsz_athletenbiografie'],
];

/*
 * Models
 */
$GLOBALS['TL_MODELS']['tl_rsz_athletenbiografie'] = RszAthletenbiografieModel::class;
