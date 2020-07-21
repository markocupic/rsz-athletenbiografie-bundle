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
                'id'      => 'primary',
                'athlete' => 'index'
            ]
        ],
        'onload_callback'  => [
            ["tl_rsz_athletenbiografie", "downloadRszAthletenbiografie"],

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
            'all'                          => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ],
            'rsz_athletenbiografie_export' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['rsz_athletenbiografie_export'],
                'href'       => 'action=downloadRszAthletenbiografie',
                'class'      => 'header_rsz_athletenbiografie_export',
                'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="i"'
            ],
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
        'default' => '{first_legend},title,athlete,dateAdded,notice;{attachment_legend},multiSRC'
    ],
    // Subpalettes
    'subpalettes' => [
        //
    ],
    // Fields
    'fields'      => [
        'id'        => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp'    => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'title'     => [
            'inputType' => 'text',
            'exclude'   => true,
            'search'    => true,
            'filter'    => false,
            'sorting'   => false,
            'flag'      => 1,
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'athlete'   => [
            'inputType'        => 'select',
            'exclude'          => true,
            'search'           => false,
            'filter'           => true,
            'sorting'          => true,
            'options_callback' => ['tl_rsz_athletenbiografie', 'getAthletes'],
            'eval'             => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'              => "varchar(255) NOT NULL default ''"
        ],
        'notice'    => [
            'inputType' => 'textarea',
            'exclude'   => true,
            'search'    => true,
            'filter'    => false,
            'sorting'   => false,
            'eval'      => ['rte' => 'tinyMCE', 'tl_class' => 'clr'],
            'sql'       => 'text NOT NULL'
        ],
        'multiSRC'  => [
            'exclude'       => true,
            'inputType'     => 'fileTree',
            'eval'          => ['multiple' => true, 'fieldType' => 'checkbox', 'orderField' => 'orderSRC', 'files' => true, 'mandatory' => false],
            'load_callback' => [
                //['tl_module', 'setMultiSrcFlags']
            ],
            'sql'           => "blob NULL"
        ],
        'dateAdded' => [
            'label'     => &$GLOBALS['TL_LANG']['MSC']['dateAdded'],
            'inputType' => 'text',
            'default'   => time(),
            'sorting'   => true,
            'flag'      => 6,
            'eval'      => ['rgxp' => 'date', 'doNotCopy' => true, 'datepicker' => true, 'tl_class' => 'w50'],
            'sql'       => "int(10) unsigned NOT NULL default 0"
        ],
        'orderSRC'  => [
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
            ->execute('SELECT * FROM tl_user ORDER BY name');
        while ($objUser->next())
        {
            $arrFunktion = \Contao\StringUtil::deserialize($objUser->funktion, true);
            if (in_array('Athlet', $arrFunktion))
            {
                $arrUsers[$objUser->id] = $objUser->name;
            }
        }

        return $arrUsers;
    }

    /**
     * Onload callback
     */
    public function downloadRszAthletenbiografie()
    {
        if (\Contao\Input::get('do') === 'rsz_athletenbiografie' && \Contao\Input::get('action') === 'downloadRszAthletenbiografie')
        {
            $blnAllowDownload = false;
            /** @var \Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface $objSessionBag */
            $objSessionBag = System::getContainer()->get('session')->getBag('contao_backend');
            if (\Contao\Input::get('do') === 'rsz_athletenbiografie')
            {
                $objSessionBag->all();
                if ($objSessionBag->has('filter'))
                {
                    $arrFilter = $objSessionBag->get('filter');
                    if (isset($arrFilter['tl_rsz_athletenbiografie']['athlete']))
                    {
                        $blnAllowDownload = true;
                    }
                }
            }

            if (!$blnAllowDownload)
            {
                \Contao\Message::addError($GLOBALS['TL_LANG']['ERR']['downloasRszAthleteBiographyNotPossible']);
                return;
            }

            $athleteId = $arrFilter['tl_rsz_athletenbiografie']['athlete'];

            $objUser = \Contao\UserModel::findByPk($athleteId);

            $objRszAthletenbiografieModel = \Markocupic\RszAthletenbiografieBundle\Model\RszAthletenbiografieModel::findByAthlete($athleteId);
            if ($objRszAthletenbiografieModel !== null && $objUser !== null)
            {
                $objExport = \Contao\System::getContainer()->get('Markocupic\RszAthletenbiografieBundle\Docx\Athletenbiografie');

                $objExport->print($objRszAthletenbiografieModel, $objUser);
                return;
            }

            \Contao\Message::addError('Error: Could not download biography.');
        }
    }
}

