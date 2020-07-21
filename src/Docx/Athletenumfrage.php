<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ AthletenumfrageBundle
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-athletenumfrage-bundle
 *
 */

declare(strict_types=1);

namespace Markocupic\RszAthletenumfrageBundle\Docx;

use Contao\Date;
use Contao\UserModel;
use Markocupic\PhpOffice\PhpWord\MsWordTemplateProcessor;
use Markocupic\RszAthletenumfrageBundle\Model\AthletenumfrageModel;

/**
 * Class Athletenumfrage
 * @package Markocupic\RszAthletenumfrageBundle\Docx\Athletenumfrage
 */
class Athletenumfrage
{
    /**
     * @var string
     */
    private $templateSrc = 'vendor/markocupic/rsz-athletenumfrage-bundle/src/Resources/contao/templates/docx/athletenumfrage.docx';

    /**
     * @var string
     */
    private $targetFilename = 'system/tmp/athletenumfrage_%s_%s.docx';

    /**
     * Import constructor.
     * @param SessionMessage $sessionMessage
     */
    public function __construct()
    {
    }

    /**
     * @param AthletenumfrageModel $objAthletenumfrage
     * @param array $arrDca
     * @param array $arrLang
     */
    public function print(AthletenumfrageModel $objAthletenumfrage, array $arrDca, array $arrLang): void
    {
        /** @var UserModel $objUser */
        $objUser = $objAthletenumfrage->getRelated('pid');

        $targetFilename = sprintf($this->targetFilename, str_replace(' ', '', strtolower($objUser->username)), Date::parse('Y-m-d', time()));

        // Create template processor object
        $objPhpWord = new MsWordTemplateProcessor($this->templateSrc, $targetFilename);

        $objPhpWord->replace('athlete_name', $objUser->name);
        $objPhpWord->replace('athlete_dateOfBirth', Date::parse('d.m.Y', $objUser->dateOfBirth));
        $objPhpWord->replace('date', Date::parse('d.m.Y', $objAthletenumfrage->tstamp));
        $objPhpWord->replace('trainerkommentar', $objUser->trainerkommentar);

        foreach ($arrDca['fields'] as $fieldname => $value)
        {
            if (
                $fieldname === 'id' ||
                $fieldname === 'pid' ||
                $fieldname === 'tableOverview' ||
                $fieldname === 'tstamp' ||
                $fieldname === 'username' ||
                $fieldname === 'trainerkommentar'
            )
            {
                continue;
            }

            // Clone row
            $objPhpWord->createClone('label');

            $label = (string) $arrLang[$fieldname][0];
            $response = (string) $objAthletenumfrage->{$fieldname};

            // Push data to clone
            $objPhpWord->addToClone('label', 'label', $label, ['multiline' => false]);
            $objPhpWord->addToClone('label', 'response', $response, ['multiline' => true]);
        }

        // Generate Docx file from template;
        $objPhpWord->generateUncached(true)
            ->sendToBrowser(true)
            ->generate();
    }
}
