<?php

/*
 * This file is part of Contao Bundle Creator Bundle.
 *
 * (c) Marko Cupic 2020 <m.cupic@gmx.ch>
 * @license MIT
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/markocupic/rsz-athletenbiografie-bundle
 */

use Contao\Backend;
use Contao\BackendUser;
use Contao\Controller;
use Contao\Database;
use Contao\FilesModel;
use Contao\Input;
use Contao\Message;
use Contao\StringUtil;
use Contao\System;
use Contao\UserModel;
use Contao\Validator;
use Markocupic\RszAthletenbiografieBundle\Model\RszAthletenbiografieModel;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBagInterface;

/**
 * Table tl_rsz_athletenbiografie
 */
$GLOBALS['TL_DCA']['tl_rsz_athletenbiografie'] = array(
	// Config
	'config'   => array(
		'dataContainer'    => 'Table',
		'enableVersioning' => true,
		'sql'              => array(
			'keys' => array(
				'id'      => 'primary',
				'athlete' => 'index'
			)
		),
		'onload_callback'  => array(
			array("tl_rsz_athletenbiografie", "loadAssets"),
			array("tl_rsz_athletenbiografie", "downloadRszAthletenbiografie"),
			array("tl_rsz_athletenbiografie", "downloadAttachment"),
			array("tl_rsz_athletenbiografie", "createAthleteDirs"),
		),
	),
	'list'     => array(
		'sorting'           => array(
			'mode'            => 2,
			'fields'          => array('athlete, dateAdded'),
			'flag'            => 5,
			'disableGrouping' => true,
			'panelLayout'     => 'filter;sort,search,limit'
		),
		'label'             => array(
			'fields'         => array('dateAdded', 'title'),
			'format'         => '%s: [Athlet: ###athlete###] <strong>%s</strong> [Autor: ###author###] ###attachments###',
			'label_callback' => array('tl_rsz_athletenbiografie', 'labelCallback')
		),
		'global_operations' => array(
			'all'                          => array(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			),
			'rsz_athletenbiografie_export' => array(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['rsz_athletenbiografie_export'],
				'href'       => 'action=downloadRszAthletenbiografie',
				'class'      => 'header_icon header_rsz_athletenbiografie_export',
				'title'      => &$GLOBALS['TL_LANG']['MSC']['rsz_athletenbiografie_export'],
				'icon'       => 'bundles/markocupicrszathletenbiografie/word_icon.svg',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="i"'
			),
		),
		'operations'        => array(
			'edit'   => array(
				'label' => &$GLOBALS['TL_LANG']['tl_sample_table']['edit'],
				'href'  => 'act=edit',
				'icon'  => 'edit.gif'
			),
			'delete' => array(
				'label'      => &$GLOBALS['TL_LANG']['tl_sample_table']['delete'],
				'href'       => 'act=delete',
				'icon'       => 'delete.gif',
				'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show'   => array(
				'label'      => &$GLOBALS['TL_LANG']['tl_sample_table']['show'],
				'href'       => 'act=show',
				'icon'       => 'show.gif',
				'attributes' => 'style="margin-right:3px"'
			),
		)
	),
	// Palettes
	'palettes' => array(
		'default' => '{first_legend},author,title,athlete,dateAdded,notice;{attachment_legend},multiSRC'
	),
	// Fields
	'fields'   => array(
		'id'        => array(
			'sql' => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp'    => array(
			'sql' => "int(10) unsigned NOT NULL default '0'"
		),
		'title'     => array(
			'inputType' => 'text',
			'exclude'   => true,
			'search'    => true,
			'filter'    => false,
			'sorting'   => false,
			'flag'      => 1,
			'eval'      => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
			'sql'       => "varchar(255) NOT NULL default ''"
		),
		'author'    => array(
			'inputType'        => 'select',
			'exclude'          => true,
			'default'          => BackendUser::getInstance()->id,
			'search'           => false,
			'filter'           => true,
			'sorting'          => true,
			'options_callback' => array('tl_rsz_athletenbiografie', 'getUsers'),
			'foreignKey'       => 'tl_user.name',
			'eval'             => array('mandatory' => true, 'readonly' => true, 'doNotShow' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
			'sql'              => "varchar(255) NOT NULL default ''",
			'relation'         => array('type' => 'hasOne', 'load' => 'lazy')
		),
		'athlete'   => array(
			'inputType'        => 'select',
			'exclude'          => true,
			'search'           => false,
			'filter'           => true,
			'sorting'          => true,
			'options_callback' => array('tl_rsz_athletenbiografie', 'getAthletes'),
			'foreignKey'       => 'tl_user.name',
			'eval'             => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
			'sql'              => "varchar(255) NOT NULL default ''",
			'relation'         => array('type' => 'hasOne', 'load' => 'lazy')
		),
		'notice'    => array(
			'inputType' => 'textarea',
			'exclude'   => true,
			'search'    => true,
			'filter'    => false,
			'sorting'   => false,
			'eval'      => array('rte' => 'tinyMCE', 'tl_class' => 'clr'),
			'sql'       => 'text NOT NULL'
		),
		'multiSRC'  => array(
			'exclude'   => true,
			'inputType' => 'fileTree',
			'eval'      => array(
				'path'       => System::getContainer()->getParameter('rsz-athletenbiografie-file-directory'),
				'multiple'   => true,
				'fieldType'  => 'checkbox',
				'orderField' => 'orderSRC',
				'files'      => true,
				'filesOnly'  => true,
				'mandatory'  => false
			),
			'sql'       => "blob NULL"
		),
		'dateAdded' => array(
			'inputType' => 'text',
			'default'   => time(),
			'sorting'   => true,
			'flag'      => 6,
			'eval'      => array('rgxp' => 'date', 'doNotCopy' => true, 'datepicker' => true, 'tl_class' => 'w50'),
			'sql'       => "int(10) unsigned NOT NULL default 0"
		),
		'orderSRC'  => array(
			'label' => &$GLOBALS['TL_LANG']['MSC']['sortOrder'],
			'sql'   => "blob NULL"
		),
	)
);

/**
 * Class tl_rsz_athletenbiografie
 */
class tl_rsz_athletenbiografie extends Backend
{
	/**
	 * @return array
	 * @throws Exception
	 */
	public function getAthletes()
	{
		if (!Database::getInstance()->fieldExists('funktion', 'tl_user'))
		{
			throw new Exception('Field tl_user.funktion does not exist. Be sure you have installed RSZ Benutzerverwaltung.');
		}
		$arrUsers = array();
		$objUser = Database::getInstance()
			->execute('SELECT * FROM tl_user ORDER BY name');

		while ($objUser->next())
		{
			$arrFunktion = StringUtil::deserialize($objUser->funktion, true);

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
		$arrUsers = array();
		$objUser = Database::getInstance()
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
	public function loadAssets()
	{
		$GLOBALS['TL_CSS'][] = 'web/bundles/markocupicrszathletenbiografie/backend.css||static';
	}

	/**
	 * Onload callback
	 */
	public function downloadRszAthletenbiografie()
	{
		if (Input::get('do') === 'rsz_athletenbiografie' && Input::get('action') === 'downloadRszAthletenbiografie')
		{
			$blnAllowDownload = false;
			/** @var AttributeBagInterface $objSessionBag */
			$objSessionBag = System::getContainer()->get('session')->getBag('contao_backend');

			if (Input::get('do') === 'rsz_athletenbiografie')
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
				Message::addError($GLOBALS['TL_LANG']['ERR']['downloadRszAthleteBiographyNotPossible']);
				$this->redirect($this->getReferer());
			}

			$athleteId = $arrFilter['tl_rsz_athletenbiografie']['athlete'];

			$objUser = UserModel::findByPk($athleteId);

			$objRszAthletenbiografieModel = RszAthletenbiografieModel::findByAthlete($athleteId);

			if ($objRszAthletenbiografieModel !== null && $objUser !== null)
			{
				$objExport = System::getContainer()->get('Markocupic\RszAthletenbiografieBundle\Docx\Athletenbiografie');

				$objExport->print($objRszAthletenbiografieModel, $objUser);

				return;
			}

			Message::addError('Error: Could not download biography.');
			$this->redirect($this->getReferer());
		}
	}

	/**
	 * Onload callback
	 */
	public function downloadAttachment()
	{
		if (Input::get('do') === 'rsz_athletenbiografie' && Input::get('action') === 'downloadAttachment' && Input::get('uuid') != '')
		{
			if ('' != ($uuid = Input::get('uuid')))
			{
				if (Validator::isStringUuid($uuid))
				{
					$binUuid = StringUtil::uuidToBin($uuid);

					if (null !== ($objFile = FilesModel::findByUuid($binUuid)))
					{
						$rootDir = System::getContainer()->getParameter('kernel.project_dir');

						if (is_file($rootDir . '/' . $objFile->path))
						{
							Controller::sendFileToBrowser($objFile->path, true);
						}
					}
				}
			}
		}
	}

	/**
	 * onload_callback
	 */
	public function createAthleteDirs()
	{
		System::getContainer()->get('Markocupic\RszAthletenbiografieBundle\RszUser\RszUser')->createFolders();
	}

	/**
	 * @param $row
	 * @param $label
	 * @return string
	 */
	public function labelCallback($row, $label)
	{
		$projectDir = System::getContainer()->getParameter('kernel.project_dir');
		$objUser = UserModel::findByPk($row['athlete']);

		if ($objUser !== null)
		{
			$label = str_replace('###athlete###', $objUser->name, $label);
		}

		$objUser = UserModel::findByPk($row['author']);

		if ($objUser !== null)
		{
			$label = str_replace('###author###', $objUser->name, $label);
		}

		// Append attachments
		$arrAttachments = array();
		$src = unserialize($row['multiSRC']);

		if (!empty($src) && is_array($src))
		{
			$i = 0;
			$objFiles = FilesModel::findMultipleByUuids($src);

			if ($objFiles !== null)
			{
				while ($objFiles->next())
				{
					$i++;

					if (is_file($projectDir . '/' . $objFiles->path))
					{
						$arrAttachments[] = sprintf(
							'<a href="contao?do=%s&amp;action=downloadAttachment&amp;uuid=%s" class="backend-listing-link" title="%s">LINK_%s</a>',
							Input::get('do'),
							StringUtil::binToUuid($objFiles->uuid),
							StringUtil::specialchars($objFiles->name),
							$i
						);
					}
				}
			}
		}
		$label = str_replace('###attachments###', implode(', ', $arrAttachments), $label);

		return $label;
	}
}
