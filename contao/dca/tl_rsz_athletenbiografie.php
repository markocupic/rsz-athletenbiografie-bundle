<?php

declare(strict_types=1);

/*
 * This file is part of RSZ Athletenbiografie Bundle.
 *
 * (c) Marko Cupic 2023 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-athletenbiographie-bundle
 */

use Contao\DataContainer;
use Contao\DC_Table;
use Contao\System;

$GLOBALS['TL_DCA']['tl_rsz_athletenbiografie'] = [
    'config'   => [
        'dataContainer'    => DC_Table::class,
        'enableVersioning' => true,
        'sql'              => [
            'keys' => [
                'id'      => 'primary',
                'athlete' => 'index',
            ],
        ],
    ],
    'list'     => [
        'sorting'           => [
            'mode'            => DataContainer::MODE_SORTABLE,
            'fields'          => ['athlete, dateAdded'],
            'flag'            => DataContainer::SORT_DAY_ASC,
            'disableGrouping' => true,
            'panelLayout'     => 'filter;sort,search,limit',
        ],
        'label'             => [
            'fields' => ['dateAdded', 'title'],
            'format' => '%s: [Athlet: ###athlete###] <strong>%s</strong> [Autor: ###author###] ###attachments###',
        ],
        'global_operations' => [
            'all'                          => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
            'rsz_athletenbiografie_export' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['rsz_athletenbiografie_export'],
                'href'       => 'action=downloadRszAthletenbiografie',
                'class'      => 'header_icon header_rsz_athletenbiografie_export',
                'title'      => &$GLOBALS['TL_LANG']['MSC']['rsz_athletenbiografie_export'],
                'icon'       => 'bundles/markocupicrszathletenbiografie/word_icon.svg',
                'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="i"',
            ],
        ],
        'operations'        => [
            'edit'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_sample_table']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif',
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_sample_table']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\''.($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? null).'\'))return false;Backend.getScrollOffset()"',
            ],
            'show'   => [
                'label'      => &$GLOBALS['TL_LANG']['tl_sample_table']['show'],
                'href'       => 'act=show',
                'icon'       => 'show.gif',
                'attributes' => 'style="margin-right:3px"',
            ],
        ],
    ],
    'palettes' => [
        'default' => '{first_legend},author,title,athlete,dateAdded,notice;{attachment_legend},multiSRC',
    ],
    'fields'   => [
        'id'        => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'tstamp'    => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'title'     => [
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => false,
            'sorting'   => false,
            'flag'      => DataContainer::SORT_INITIAL_LETTER_ASC,
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''",
        ],
        'author'    => [
            'inputType'  => 'select',
            'exclude'    => true,
            'default'    => System::getContainer()->get('security.helper')->getUser() ? System::getContainer()->get('security.helper')->getUser()->id : null,
            'search'     => false,
            'filter'     => true,
            'sorting'    => true,
            'foreignKey' => 'tl_user.name',
            'eval'       => ['mandatory' => true, 'readonly' => true, 'doNotShow' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'        => "varchar(255) NOT NULL default ''",
            'relation'   => ['type' => 'hasOne', 'load' => 'lazy'],
        ],
        'athlete'   => [
            'inputType'  => 'select',
            'exclude'    => true,
            'search'     => false,
            'filter'     => true,
            'sorting'    => true,
            'foreignKey' => 'tl_user.name',
            'eval'       => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'        => "varchar(255) NOT NULL default ''",
            'relation'   => ['type' => 'hasOne', 'load' => 'lazy'],
        ],
        'notice'    => [
            'inputType' => 'textarea',
            'exclude'   => true,
            'search'    => true,
            'filter'    => false,
            'sorting'   => false,
            'eval'      => ['rte' => 'tinyMCE', 'tl_class' => 'clr'],
            'sql'       => 'mediumtext NULL',
        ],
        'multiSRC'  => [
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => [
                'path'       => System::getContainer()->getParameter('rsz_athletenbiografie.document_dir'),
                'multiple'   => true,
                'fieldType'  => 'checkbox',
                'orderField' => 'orderSRC',
                'files'      => true,
                'filesOnly'  => true,
                'mandatory'  => false,
            ],
            'sql'       => 'blob NULL',
        ],
        'dateAdded' => [
            'inputType' => 'text',
            'default'   => time(),
            'sorting'   => true,
            'flag'      => DataContainer::SORT_DAY_DESC,
            'eval'      => ['rgxp' => 'date', 'doNotCopy' => true, 'datepicker' => true, 'tl_class' => 'w50'],
            'sql'       => 'int(10) unsigned NOT NULL default 0',
        ],
        'orderSRC'  => [
            'label' => &$GLOBALS['TL_LANG']['MSC']['sortOrder'],
            'sql'   => 'blob NULL',
        ],
    ],
];
