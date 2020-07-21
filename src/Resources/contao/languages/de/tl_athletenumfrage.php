<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ AthletenumfrageBundle
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-athletenumfrage-bundle
 *
 */


$int_last_year = date('Y');
$int_next_year = $int_last_year + 1;

/**
 * Operations
 */
$GLOBALS['TL_LANG']['tl_athletenumfrage']['show'] = ['Details der Athletenumfrage', 'Details der Athletenumfrage ID %s anzeigen'];

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_athletenumfrage']['ID'] = ['ID', ''];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['username'] = ['Name', ''];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['ziele_indoor'] = ['Indoor-Ziele ' . $int_next_year, 'Welches sind ganz konkret deine Indoor-Ziele im nächsten Jahr? Erkläre in mehreren deutschen Sätzen!'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['ziele_outdoor'] = ['Outdoor-Ziele ' . $int_next_year, 'Welches sind ganz konkret deine Outdoor-Ziele im nächsten Jahr? Erkläre in mehreren deutschen Sätzen!'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['ziele_wettkampf'] = ['Wettkampf-Ziele ' . $int_next_year, 'Welches sind ganz konkret deine Wettkampf-Ziele im nächsten Jahr? Erkläre in mehreren deutschen Sätzen!'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['ziele_allgemein'] = ['Allgemeine Ziele ' . $int_next_year, 'Hast du weitere Ziele im nächsten Jahr (mental, schulisch,...)? Erkläre in mehreren deutschen Sätzen!'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['fortschritte'] = ['Erzielte Fortschritte ' . $int_last_year, 'Wo hast du im vergangenen Jahr besonders Fortschritte gemacht? Beschreibe!'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['anz_trainings'] = ['Anzahl Trainings pro Woche', 'Wie oft trainierst du durchschnittlich in der Woche?'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['trainingsspass'] = ['Freude am Trainieren', 'Wie viel Freude bereitet dir das leistungsorientierte Klettern?'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['pos_training'] = ['Was gefällt dir am Training?', 'Beschreibe!'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['neg_training'] = ['Was gefällt dir nicht am Training?', 'Beschreibe!'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['trainingseffizienz'] = ['Trainingseffizienz', 'Wie effizient nutzt du die Zeit? Liegst du viel herum, oder bist du in den Trainings sehr aktiv?'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['selbstvertrauen_training'] = ['Selbstvertrauen in den Trainings', 'Wie gross ist dein Selbsvertrauen in den Trainings?'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['selbstvertrauen_wettkaempfe'] = ['Selbstvertrauen an den Wettkämpfen', 'Wie gross ist dein Selbstvertrauen an den Wettkämpfen?'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['trainingsplan'] = ['Training nach Trainingsplan', 'Trainierst du nach einem bestimmten System? Hältst du dich an die Trainingspläne?'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['rumpfkrafttraining'] = ['Rumpfkrafttraining', 'Wie oft betreibst du in der Woche ein Rumpfkrafttraining?'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['ausdauertraining'] = ['Allg. Ausdauertraining', 'Wie oft betreibst du in der Woche ein allg. Ausdauertraining, z.B. Joggen?'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['trainingstagebuch'] = ['Trainingstagebuch', 'Führst du ein Trainingstagebuch?'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['kletterleistung_indoor_rp'] = ['Beste Kletterleistung in der Halle ' . $int_last_year . ' (Rotpunkt)', ''];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['kletterleistung_outdoor_rp'] = ['Beste Kletterleistung outdoor ' . $int_last_year . ' (Rotpunkt)', ''];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['boulderleistung_indoor'] = ['Beste Boulderleistung in der Halle ' . $int_last_year, ''];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['boulderleistung_outdoor'] = ['Beste Boulderleistung outdoor ' . $int_last_year, ''];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['umgang_mit_druck'] = ['Umgang mit Druck', 'Wie stark fühlst du dich von deinem Umfeld (Eltern, Trainer, Schule, Lehre, Job) unter Druck gestellt?'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['gruende_mitgliedschaft'] = ['Gründe für Mitgliedschaft im RSZ ' . $int_next_year, 'Nenne Gründe, weshalb du auch im nächsten Jahr einen Platz im Regionalkader verdient hast.'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['funktion_next_year'] = ['Funktion ' . $int_next_year, 'In welcher Funktion möchtest du nächstes Jahr im RSZ mitmachen?'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['speed_nutzung'] = ['Speed-Route (Nutzung)', 'Hast du die Speed-Route im Pilatus Indoor zum Trainieren benutzt? Wenn nein, warum nicht?'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['speed_verbesserung'] = ['Speed-Route (Verbesserungsvorschläge)', 'Was muss angepasst werden, dass du in Zukunft an der Speed-Route trainieren wirst?'];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['allgemeines'] = ['Was ich sonst noch sagen wollte', ''];
$GLOBALS['TL_LANG']['tl_athletenumfrage']['trainerkommentar'] = ['Trainerkommentare', 'Dieses Feld ist für die Trainerkommentare gedacht.'];




