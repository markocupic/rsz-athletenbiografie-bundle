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
    'config'   => [
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
            ["tl_rsz_athletenbiografie", "createAthleteDirs"],
        ],
    ],
    'list'     => [
        'sorting'           => [
            'mode'            => 2,
            'fields'          => ['athlete, dateAdded'],
            'flag'            => 5,
            'disableGrouping' => true,
            'panelLayout'     => 'filter;sort,search,limit'
        ],
        'label'             => [
            'fields'         => ['dateAdded', 'title'],
            'format'         => '%s: [Athlet: ###athlete###] <strong>%s</strong> [Autor: ###author###]',
            'label_callback' => ['tl_rsz_athletenbiografie', 'labelCallback']
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
                'class'      => 'header_icon header_rsz_athletenbiografie_export',
                'title'      => &$GLOBALS['TL_LANG']['MSC']['rsz_athletenbiografie_export'],
                'icon'       => 'bundles/markocupicrszathletenbiografie/word_icon.svg',
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
    'palettes' => [
        'default' => '{first_legend},author,title,athlete,dateAdded,notice;{attachment_legend},multiSRC'
    ],
    // Fields
    'fields'   => [
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
        'author'    => [
            'inputType'        => 'select',
            'exclude'          => true,
            'default'          => \Contao\BackendUser::getInstance()->id,
            'search'           => false,
            'filter'           => true,
            'sorting'          => true,
            'options_callback' => ['tl_rsz_athletenbiografie', 'getUsers'],
            'foreignKey'       => 'tl_user.name',
            'eval'             => ['mandatory' => true, 'readonly' => true, 'doNotShow' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'              => "varchar(255) NOT NULL default ''",
            'relation'         => ['type' => 'hasOne', 'load' => 'lazy']
        ],
        'athlete'   => [
            'inputType'        => 'select',
            'exclude'          => true,
            'search'           => false,
            'filter'           => true,
            'sorting'          => true,
            'options_callback' => ['tl_rsz_athletenbiografie', 'getAthletes'],
            'foreignKey'       => 'tl_user.name',
            'eval'             => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'              => "varchar(255) NOT NULL default ''",
            'relation'         => ['type' => 'hasOne', 'load' => 'lazy']
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
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => [
                'path'       => \Contao\System::getContainer()->getParameter('rsz-athletenbiografie-file-directory'),
                'multiple'   => true,
                'fieldType'  => 'checkbox',
                'orderField' => 'orderSRC',
                'files'      => true,
                'filesOnly'  => true,
                'mandatory'  => false
            ],
            'sql'       => "blob NULL"
        ],
        'dateAdded' => [
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
     * @return array
     * @throws Exception
     */
    public function getUsers()
    {
        $arrUsers = [];
        $objUser = Contao\Database::getInstance()
            ->execute('SELECT * FROM tl_user ORDER BY name');
        while ($objUser->next())
        {
            $arrUsers[$objUser->id] = $objUser->name;
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
                \Contao\Message::addError($GLOBALS['TL_LANG']['ERR']['downloadRszAthleteBiographyNotPossible']);
                $this->redirect($this->getReferer());
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
            $this->redirect($this->getReferer());
        }
    }

    /**
     * onload_callback
     */
    public function createAthleteDirs()
    {
        \Contao\System::getContainer()->get('Markocupic\RszAthletenbiografieBundle\RszUser\RszUser')->createFolders();
    }

    /**
     * @param $row
     * @param $label
     * @return string
     */
    public function labelCallback($row, $label)
    {
        $objUser = \Contao\UserModel::findByPk($row['athlete']);
        if ($objUser !== null)
        {
            $label = str_replace('###athlete###', $objUser->name, $label);
        }

        $objUser = \Contao\UserModel::findByPk($row['author']);
        if ($objUser !== null)
        {
            $label = str_replace('###author###', $objUser->name, $label);
        }

        return $label;
    }
}

