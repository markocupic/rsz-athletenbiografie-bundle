<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ AthletenumfrageBundle
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-athletenumfrage-bundle
 *
 */

$GLOBALS['TL_DCA']['tl_athletenumfrage'] = [
    // Config
    'config'   => [
        'ptable'           => 'tl_user',
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'onload_callback'  => [
            ["tl_athletenumfrage", "createProfiles"],
            ["tl_athletenumfrage", "filterList"],
            ["tl_athletenumfrage", "setPalette"],
            ["tl_athletenumfrage", "checkPermission"],
            ["tl_athletenumfrage", "route"],
        ],
        'sql'              => [
            'keys' => [
                'id'  => 'primary',
                'pid' => 'index'
            ]
        ]
    ],
    // List
    'list'     => [
        'sorting'           => [
            'fields' => ['tstamp DESC, username']
        ],
        'label'             => [
            'fields'         => ['username'],
            'format'         => '<span>#name#</span> #datum#  #trainerkommentar#',
            'label_callback' => ['tl_athletenumfrage', 'labelCallback']
        ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            ]
        ],
        'operations'        => [
            'edit'    => [
                'label' => &$GLOBALS['TL_LANG']['tl_athletenumfrage']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ],
            'drucken' => [
                'href'            => 'action=drucken',
                'label'           => 'drucken',
                'title'           => 'drucken',
                'icon'            => 'bundles/markocupicrszathletenumfrage/printer.png',
                'button_callback' => ['tl_athletenumfrage', 'printerIcon']
            ],
            'show'    => [
                'label' => &$GLOBALS['TL_LANG']['tl_athletenumfrage']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            ]
        ]
    ],
    // Palettes
    'palettes' => [
        'default' => '',
        'athlete' => 'username;
            {Deine Leistungen:hide},kletterleistung_indoor_rp, kletterleistung_outdoor_rp, boulderleistung_indoor, boulderleistung_outdoor, fortschritte;
            {Dein Training:hide},anz_trainings,trainingsspass,pos_training,neg_training,trainingseffizienz,selbstvertrauen_training,selbstvertrauen_wettkaempfe,training_system, trainingsplan, rumpfkrafttraining, ausdauertraining, trainingstagebuch;
            {Ziele im nächsten Trainingsjahr},ziele_indoor,ziele_outdoor,ziele_wettkampf,ziele_allgemein;
            {Allgemeines:hide},umgang_mit_druck,speed_nutzung,speed_verbesserung,gruende_mitgliedschaft, funktion_next_year, austritt, allgemeines;
            {Trainerkommentare:hide},trainerkommentar',
        'trainer' => 'tableOverview,trainerkommentar'
    ],
    // Fields
    'fields'   => [
        'id'                          => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'pid'                         => [
            'foreignKey' => 'tl_user.username',
            'sql'        => "int(10) unsigned NOT NULL default 0",
            'relation'   => ['type' => 'belongsTo', 'load' => 'lazy']
        ],
        'tableOverview'               => [
            'search'               => true,
            'sorting'              => true,
            'flag'                 => 1,
            'eval'                 => [],
            'input_field_callback' => ['tl_athletenumfrage', 'tableOverviewInputFieldCallback'],
            'sql'                  => "text NOT NULL"
        ],
        'tstamp'                      => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'readonly' => true, 'style' => 'height:7em; overflow:auto; width:80%', 'allowHtml' => false, 'tl_class' => 'w50'],
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ],
        'username'                    => [
            'inputType' => 'text',
            'eval'      => ['submitOnChange' => true, 'readonly' => true, 'style' => 'width:30%', 'allowHtml' => false],
            'sql'       => "text NOT NULL"
        ],
        'ziele_indoor'                => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'style' => 'height:7em; overflow:auto; width:80%', 'allowHtml' => false, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'ziele_outdoor'               => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'style' => 'height:7em; overflow:auto; width:80%', 'allowHtml' => false, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'ziele_wettkampf'             => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'style' => 'height:7em; overflow:auto; width:80%', 'allowHtml' => false, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'ziele_allgemein'             => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'style' => 'height:7em; overflow:auto; width:80%', 'allowHtml' => false, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'fortschritte'                => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'style' => 'height:7em; overflow:auto; width:80%', 'allowHtml' => false, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'anz_trainings'               => [
            'inputType' => 'select',
            'options'   => ['1', '2', '3', '4', '5'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'trainingsspass'              => [
            'inputType' => 'select',
            'options'   => ['1', '2', '3', '4'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'pos_training'                => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'style' => 'height:7em; overflow:auto; width:80%', 'allowHtml' => false, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'neg_training'                => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'style' => 'height:7em; overflow:auto; width:80%', 'allowHtml' => false, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'trainingseffizienz'          => [
            'inputType' => 'select',
            'options'   => ['1', '2', '3', '4'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'selbstvertrauen_training'    => [
            'inputType' => 'select',
            'options'   => ['1', '2', '3', '4'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'selbstvertrauen_wettkaempfe' => [
            'inputType' => 'select',
            'options'   => ['1', '2', '3', '4'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'trainingsplan'               => [
            'inputType' => 'select',
            'options'   => ['ja', 'nein'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'rumpfkrafttraining'          => [
            'inputType' => 'select',
            'options'   => ['1x/Woche', '2x/Woche', '3x/Woche', '4x/Woche', '5x/Woche'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'ausdauertraining'            => [
            'inputType' => 'select',
            'options'   => ['1x/Woche', '2x/Woche', '3x/Woche', '4x/Woche', '5x/Woche'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'trainingstagebuch'           => [
            'inputType' => 'select',
            'options'   => ['ja', 'nein'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'kletterleistung_indoor_rp'   => [
            'inputType' => 'select',
            'options'   => ['6b', '6b+', '6c', '6c+', '7a', '7a+', '7b', '7b+', '7c', '7c+', '8a', '8a+', '8b', '8b+', '8c', '8c+', '9a'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'kletterleistung_outdoor_rp'  => [
            'inputType' => 'select',
            'options'   => ['6b', '6b+', '6c', '6c+', '7a', '7a+', '7b', '7b+', '7c', '7c+', '8a', '8a+', '8b', '8b+', '8c', '8c+', '9a'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'boulderleistung_indoor'      => [
            'inputType' => 'select',
            'options'   => ['B1', 'B2', 'B3', 'B4', 'B5', 'B6'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'boulderleistung_outdoor'     => [
            'inputType' => 'select',
            'options'   => ['fb6b', 'fb6b+', 'fb6c', 'fb6c+', 'fb7a', 'fb7a+', 'fb7b', 'fb7b+', 'fb7c', 'fb7c+', 'fb8a', 'fb8a+', 'fb8b', 'fb8b+', 'fb8c'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'speed_nutzung'               => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'style' => 'height:7em; overflow:auto; width:80%', 'allowHtml' => false],
            'sql'       => "text NOT NULL"
        ],
        'speed_verbesserung'          => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'style' => 'height:7em; overflow:auto; width:80%', 'allowHtml' => false],
            'sql'       => "text NOT NULL"
        ],
        'umgang_mit_druck'            => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'style' => 'height:7em; overflow:auto; width:80%', 'allowHtml' => false],
            'sql'       => "text NOT NULL"
        ],
        'gruende_mitgliedschaft'      => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'style' => 'height:7em; overflow:auto; width:80%', 'allowHtml' => false, 'tl_class' => 'w50'],
            'sql'       => "text NOT NULL"
        ],
        'funktion_next_year'          => [
            'inputType' => 'select',
            'options'   => ['Athlet', 'Athlet und Trainer', 'Trainer', 'Austritt'],
            'eval'      => ['submitOnChange' => true, 'includeBlankOption' => true, 'tl_class' => 'clr'],
            'sql'       => "text NOT NULL"
        ],
        'allgemeines'                 => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'style' => 'height:7em; overflow:auto; width:80%;', 'allowHtml' => false, 'tl_class' => 'clr'],
            'sql'       => "text NOT NULL"
        ],
        'trainerkommentar'            => [
            'inputType' => 'textarea',
            'eval'      => ['submitOnChange' => true, 'style' => 'height:15em; overflow:auto; width:95%;', 'allowHtml' => false],
            'sql'       => "text NOT NULL"
        ]
    ]
];

/**
 * Class tl_athletenumfrage
 */
class tl_athletenumfrage extends Backend
{
    /**
     * @var mixed|null
     */
    public $pid;

    /**
     * tl_athletenumfrage constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->import('BackendUser', 'User');

        if (Contao\Input::get('id'))
        {
            $objAthletenumfrage = Contao\Database::getInstance()->prepare("SELECT pid FROM tl_athletenumfrage WHERE id=?")
                ->execute(Contao\Input::get('id'));

            $objAthlet = Contao\Database::getInstance()->prepare("SELECT id, username FROM tl_user WHERE id=?")
                ->execute($objAthletenumfrage->pid);

            $this->pid = $objAthlet->id;
        }
    }

    /**
     * Router
     */
    public function route()
    {
        if (Contao\Input::get('action') === 'drucken')
        {
            $objAthletenumfrage = Markocupic\RszAthletenumfrageBundle\Model\AthletenumfrageModel::findByPk(Contao\Input::get('id'));
            if ($objAthletenumfrage !== null)
            {
                Contao\Controller::loadDataContainer('tl_athletenumfrage');
                Contao\Controller::loadLanguageFile('tl_athletenumfrage');

                $arrDca = $GLOBALS['TL_DCA']['tl_athletenumfrage'];
                $arrLang = $GLOBALS['TL_LANG']['tl_athletenumfrage'];

                /** @var Markocupic\RszAthletenumfrageBundle\Docx\Athletenumfrage $objPrint */
                $objPrint = Contao\System::getContainer()->get('Markocupic\RszAthletenumfrageBundle\Docx\Athletenumfrage');
                $objPrint->print($objAthletenumfrage, $arrDca, $arrLang);
            }
        }
    }

    /**
     * Filter list for athletes
     * On load callback
     */
    public function filterList()
    {
        //nur Admins haben Zugriff auf fremde Profile
        $objUser = Contao\Database::getInstance()->prepare("SELECT id FROM tl_athletenumfrage WHERE pid=?")
            ->execute($this->User->id);

        $this->User->funktion = is_array($this->User->funktion) ? $this->User->funktion : [];

        if (in_array("Trainer", $this->User->funktion) || $this->User->isAdmin)
        {
            return;
        }

        $GLOBALS['TL_DCA']['tl_athletenumfrage']['list']['sorting']['filter'] = [['pid=?', $this->User->id]];
    }

    /**
     * Creates from every athlete a blanco profile, if there is no
     */
    public function createProfiles()
    {
        $objAthlete = Contao\Database::getInstance()->prepare("SELECT id, username FROM tl_user WHERE funktion LIKE ? ORDER BY dateOfBirth")
            ->execute("%Athlet%");
        while ($objAthlete->next())
        {
            $objAthletenumfrage = Contao\Database::getInstance()->prepare("SELECT id FROM tl_athletenumfrage WHERE pid=?")
                ->execute($objAthlete->id);

            if (!$objAthletenumfrage->numRows)
            {
                $set = [
                    'pid'      => $objAthlete->id,
                    'username' => $objAthlete->username
                ];
                Contao\Database::getInstance()->prepare("INSERT INTO  tl_athletenumfrage %s")
                    ->set($set)
                    ->execute();
            }
        }
    }

    /**
     * Verhindert, dass Athleten sich gegenseitig die Daten ansehen können
     */
    public function checkPermission()
    {
        if (Contao\Input::get("action") === "drucken" || Contao\Input::get("act") === "edit" || Contao\Input::get("act") === "show" || Contao\Input::get("act") === "delete")
        {
            if ($this->User->isAdmin)
            {
                return;
            }
            if ($this->pid == $this->User->id)
            {
                return;
            }
            if (in_array("Trainer", $this->User->funktion))
            {
                return;
            }
            $this->redirect("contao?do=tl_athletenumfrage");
        }
    }

    /**
     * Set palette
     */
    public function setPalette()
    {
        // Trainer
        if (in_array("Trainer", $this->User->funktion) || $this->User->isAdmin)
        {
            $GLOBALS['TL_DCA']['tl_athletenumfrage']['palettes']['default'] = $GLOBALS['TL_DCA']['tl_athletenumfrage']['palettes']['trainer'];
        }

        // Athlete
        if ($this->pid == $this->User->id)
        {
            $GLOBALS['TL_DCA']['tl_athletenumfrage']['palettes']['default'] = $GLOBALS['TL_DCA']['tl_athletenumfrage']['palettes']['athlete'];
            $GLOBALS['TL_DCA']['tl_athletenumfrage']['fields']['trainerkommentar']['eval']['style'] = str_replace($GLOBALS['TL_DCA']['tl_athletenumfrage']['fields']['trainerkommentar']['eval']['style'], $GLOBALS['TL_DCA']['tl_athletenumfrage']['fields']['trainerkommentar']['eval']['style'] . '" readonly="readonly', $GLOBALS['TL_DCA']['tl_athletenumfrage']['fields']['trainerkommentar']['eval']['style']);
        }
    }

    /**
     * Button callback
     * @param $row
     * @param $href
     * @param $label
     * @param $title
     * @param $icon
     * @param $attributes
     * @return string
     */
    public function printerIcon($row, $href, $label, $title, $icon, $attributes)
    {
        return '<a href="' . $this->addToUrl($href . '&amp;id=' . $row['id']) . '" title="drucken"' . $attributes . '>' . $this->generateImage($icon, $label) . '</a> ';
    }

    /**
     * @param $row
     * @param $label
     * @return mixed
     */
    public function labelCallback($row, $label)
    {
        $objAthletenumfrage = Contao\Database::getInstance()->prepare("SELECT * FROM tl_athletenumfrage WHERE id=?")
            ->limit(1)
            ->execute($row["id"]);

        $name = $objAthletenumfrage->username;

        if (null !== ($objUser = \Contao\UserModel::findByPk($objAthletenumfrage->pid)))
        {
            $name = $objUser->name;
        }

        $label = str_replace("#name#", $name, $label);

        if (trim($objAthletenumfrage->trainerkommentar) != "")
        {
            $label = str_replace("#trainerkommentar#", "[Kommentar vorh.]", $label);
        }
        else
        {
            $label = str_replace("#trainerkommentar#", "", $label);
        }
        if ($row["tstamp"] == 0)
        {
            return str_replace("#datum#", "", $label);
        }
        else
        {
            return str_replace("#datum#", $this->parseDate("[D, d.m.Y, H:i]", $row["tstamp"]), $label);
        }
    }

    /**
     * Input field callback
     * @return string
     */
    public function tableOverviewInputFieldCallback()
    {
        $mySql = Contao\Database::getInstance()->prepare("SELECT * FROM tl_athletenumfrage WHERE id=?")
            ->limit(1)
            ->execute(Contao\Input::get('id'));
        $row = $mySql->fetchAssoc();
        $i = 0;
        $output = '
			<div class="widget">
			<br>
			<table style="width:100%" cellpadding="0" cellspacing="0" summary="Table lists all details of an entry">
		';
        foreach ($GLOBALS['TL_DCA']['tl_athletenumfrage']['fields'] as $key => $content)
        {
            if ($key == "tableOverview")
            {
                continue;
            }
            if ($key === "id" || $key === "pid" || $key === "tstamp")
            {
                continue;
            }
            $output .= '
				<tr>
					<td style="width:50%; font-weight:bold; border:0; padding:8px 8px; ' . ($i % 2 ? 'background-color:#fff;' : 'background-color:#f6f6f6;') . '">' . $GLOBALS['TL_LANG']['tl_athletenumfrage'][$key][0] . ':</td>
					<td style="width:50%; border:0; padding:8px 8px; ' . ($i % 2 ? 'background-color:#fff;' : 'background-color:#f6f6f6;') . '">' . nl2br($row[$key]) . '</td>
				</tr>';
            $i++;
        }
        $output .= '</table><br><br></div>';
        return $output;
    }

}

