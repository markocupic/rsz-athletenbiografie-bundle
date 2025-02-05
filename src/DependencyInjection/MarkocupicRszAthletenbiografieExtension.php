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

namespace Markocupic\RszAthletenbiografieBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class MarkocupicRszAthletenbiografieExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../config')
        );

        $loader->load('services.yaml');

        $configuration = new Configuration();
        $rootKey = $this->getAlias();

        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter($rootKey.'.document_dir', $config['document_dir']);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        // Default root key would be markocupic_rsz_athletenbiografie_bundle
        return Configuration::ROOT_KEY;
    }
}
