<?php

/**
 * @copyright  Marko Cupic 2020 <m.cupic@gmx.ch>
 * @author     Marko Cupic
 * @package    RSZ Athletenbiografie
 * @license    MIT
 * @see        https://github.com/markocupic/rsz-athletenbiografie-bundle
 *
 */

declare(strict_types=1);

namespace Markocupic\RszAthletenbiografieBundle\Docx;

use Contao\Date;
use Contao\Environment;
use Contao\FilesModel;
use Contao\Model\Collection;
use Contao\UserModel;
use Contao\Validator;
use Markocupic\PhpOffice\PhpWord\MsWordTemplateProcessor;
use Markocupic\RszAthletenbiografieBundle\Model\RszAthletenbiografieModel;
use Markocupic\RszAthletenumfrageBundle\Model\AthletenumfrageModel;
use PhpOffice\PhpWord\Element\Link;

/**
 * Class Athletenbiografie
 * @package Markocupic\RszAthletenbiografieBundle\Docx
 */
class Athletenbiografie
{
   /** @var string */
    private const TEMPLATE_SRC = 'vendor/markocupic/rsz-athletenbiografie-bundle/src/Resources/contao/templates/docx/athletenbiografie.docx';

   /** @var string */
    private const TARGET_FILENAME = 'system/tmp/athletenbiografie_%s_%s.docx';

    /** @var string */
    private $projectDir;

    /**
     * Athletenbiografie constructor.
     * @param string $projectDir
     */
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    /**
     * @param Collection $objAthletenbiografie
     * @param UserModel $objUser
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
    public function print(Collection $objAthletenbiografie, UserModel $objUser): void
    {
        $targetFilename = sprintf(static::TARGET_FILENAME, str_replace(' ', '', strtolower($objUser->username)), Date::parse('Y-m-d', time()));

        // Create template processor object
        $objPhpWord = new MsWordTemplateProcessor(static::TEMPLATE_SRC, $targetFilename);
        /**
         *
         * Athlet: ${athlete_name}, ${athlete_dateOfBirth}
         * ${dateAdded}    ${notice}
         * ${files}
         * ${date}
         */

        $objPhpWord->replace('athlete_name', $objUser->name);
        $objPhpWord->replace('athlete_dateOfBirth', Date::parse('d.m.Y', $objUser->dateOfBirth));

        while ($objAthletenbiografie->next())
        {
            $arrData = $objAthletenbiografie->row();

            // Clone row
            $objPhpWord->createClone('dateAdded');

            $dateAdded = (string) Date::parse('d.m.Y', $objAthletenbiografie->dateAdded);
            $notice = (string) $objAthletenbiografie->notice;

            // Push data to clone
            $objPhpWord->addToClone('dateAdded', 'dateAdded', $dateAdded, ['multiline' => false]);
            $objPhpWord->addToClone('dateAdded', 'notice', $notice, ['multiline' => true]);
            $arrLinks = [];
            $strDownloads = '';
            $arrFiles = deserialize($objAthletenbiografie->multiSRC);


            if(!empty($arrFiles) && is_array($arrFiles))
            {
                foreach($arrFiles as $uuid)
                {
                    if(Validator::isUuid($uuid))
                    {
                        $objFile = FilesModel::findByUuid($uuid);
                        if(is_file($this->projectDir . '/' . $objFile->path))
                        {
                            $arrLinks[] = [
                                'name' => $objFile->name,
                                'extension' => $objFile->extension,
                                'path' => $objFile->path,
                                'download' => sprintf('%s/contao/popup?src=%s==&download=1', Environment::get('url'),base64_encode($objFile->path)),
                            ];
                            $strDownloads .= '<a href="https://google.com">click here</a>' . "\r\n";
                            //$strDownloads .= $objFile->name . ': ' . "\r\n" . sprintf('%s/contao/popup?src=%s==&download=1', Environment::get('url'),base64_encode($objFile->path)) . "\r\n";
                        }
                    }
                }
            }

            $objHtml = \PhpOffice\PhpWord\Shared\Html::addHtml('<a href="https://google.ch">Google</a>');
            $objPhpWord->
            $pw = new \PhpOffice\PhpWord\PhpWord();
            $section = $pw->addSection();
            $textrun = $section->addTextRun();
            $textrun->addTextBreak(2);
            $section->addLink('https://google.ch','Google');
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($pw, 'Word2007');
            $fullXml = $objWriter->getWriterPart('Document')->write();
            //$templateProcessor->setValue($var, $this->getBodyBlock($fullXml));
            //die($objLink->());
//die($this->getBodyBlock($fullXml));
            //$objPhpWord->addToClone('dateAdded', 'files', $this->getBodyBlock($fullXml), ['multiline' => true, 'no_entity_decode' => true]);


        }
        // Generate Docx file from template;
        $objPhpWord->generateUncached(true)
            ->sendToBrowser(true)
            ->generate();
    }

    protected function getBodyBlock($string) {
        if (preg_match('%(?i)(?<=<w:body>)[\s|\S]*?(?=</w:body>)%', $string, $regs)) {
            return $regs[0];
        } else {
            return '';
        }
    }
}
