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

namespace Markocupic\RszAthletenbiografieBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Markocupic\RszAthletenbiografieBundle\MarkocupicRszAthletenbiografieBundle;
use Markocupic\RszBenutzerverwaltungBundle\MarkocupicRszBenutzerverwaltungBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(MarkocupicRszAthletenbiografieBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
                ->setLoadAfter([MarkocupicRszBenutzerverwaltungBundle::class]),
        ];
    }
}
