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
 * Table tl_rsz_athletenbiografie
 */
$GLOBALS['TL_DCA']['tl_rsz_athletenbiografie'] = [

    // Config
    'config'      => [
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'sql'              => [
            'keys' => [
                'id' => 'primary',
                'athlet' => 'index'
            ]
        ],
    ],
    'list'        => [
        'sorting'           => [
            'mode'        => 2,
            'fields'      => ['title'],
            'flag'        => 1,
            'panelLayout' => 'filter;sort,search,limit'
        ],
        'label'             => [
            'fields' => ['title'],
            'format' => '%s',
        ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ]
        ],
        'operations'        => [
            'edit'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_sample_table']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_sample_table']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ],
            'show'   => [
                'label'      => &$GLOBALS['TL_LANG']['tl_sample_table']['show'],
                'href'       => 'act=show',
                'icon'       => 'show.gif',
                'attributes' => 'style="margin-right:3px"'
            ],
        ]
    ],
    // Palettes
    'palettes'    => [
        //'__selector__' => ['addSubpalette'],
        'default' => '{first_legend},title,notice;{attachment_legend},multiSRC'
    ],
    // Subpalettes
    'subpalettes' => [
        //
    ],
    // Fields
    'fields'      => [
        'id'       => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp'   => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'title'    => [
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'athlet'   => [
            'inputType'        => 'select',
            'exclude'          => true,
            'search'           => true,
            'filter'           => true,
            'sorting'          => true,
            'options_callback' => ['tl_rsz_athletenbiografie', 'getAthletes'],
            'eval'             => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'              => "varchar(255) NOT NULL default ''"
        ],
        'notice'   => [
            'inputType' => 'textarea',
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'sorting'   => true,
            'eval'      => ['rte' => 'tinyMCE', 'tl_class' => 'clr'],
            'sql'       => 'text NOT NULL'
        ],
        'multiSRC' => [
            'exclude'       => true,
            'inputType'     => 'fileTree',
            'eval'          => ['multiple' => true, 'fieldType' => 'checkbox', 'orderField' => 'orderSRC', 'files' => true, 'mandatory' => true],
            'load_callback' => [
                //['tl_module', 'setMultiSrcFlags']
            ],
            'sql'           => "blob NULL"
        ],
        'orderSRC' => [
            'label' => &$GLOBALS['TL_LANG']['MSC']['sortOrder'],
            'sql'   => "blob NULL"
        ],
    ]
];

/**
 * Class tl_rsz_athletenbiografie
 */
class tl_rsz_athletenbiografie extends Contao\Backend
{
    /**
     * @return array
     * @throws Exception
     */
    public function getAthletes()
    {
        if (!Contao\Database::getInstance()->fieldExists('funktion', 'tl_user'))
        {
            throw new \Exception('Field tl_user.funktion does not exist. Be sure you have installed RSZ Benutzerverwaltung.');
        }
        $arrUsers = [];
        $objUser = Contao\Database::getInstance()
            ->prepare('SELECT * FROM tl_user WHERE funktion=? ORDER BY name')
            ->execute('Athlet');
        while ($objUser->next())
        {
            $arrUsers[$objUser->id] = $objUser->name;
        }

        return $arrUsers;
    }
}

