<?php
/**
 * Readers for BlueSpice
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * This file is part of BlueSpice MediaWiki
 * For further information visit http://www.bluespice.com
 *
 * @author     Stephan Muggli <muggli@hallowelt.com>
 * @package    BlueSpiceReaders
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v3
 * @filesource
 */

/**
 * Readers extension
 * @package BlueSpiceReaders
 */
class Readers extends BsExtensionMW {
	/**
	 * Initialization of ExtensionTemplate extension
	 */
	public function  initExt() {
		$this->setHook( 'BeforePageDisplay' );
		$this->setHook( 'SkinTemplateNavigation' );
	}

	/**
	 * Hook-Handler for Hook 'LoadExtensionSchemaUpdates'
	 * @param object §updater Updater
	 * @return boolean Always true
	 */
	public static function getSchemaUpdates( $updater ) {
		$updater->addExtensionTable(
			'bs_readers',
			__DIR__ . '/db/readers.sql'
		);
		$updater->addExtensionField(
			'bs_readers',
			'readers_ts',
			__DIR__ . '/db/mysql/readers.patch.readers_ts.sql'
		);

		return true;
	}

	/**
	 * Hook-Handler for MediaWiki 'BeforePageDisplay' hook. Sets context if needed.
	 * @param OutputPage $oOutputPage
	 * @param Skin $oSkin
	 * @return bool
	 */
	public function onBeforePageDisplay( &$oOutputPage, &$oSkin ) {
		if ( $this->checkContext() === false ) return true;
		$oOutputPage->addModuleStyles( 'ext.bluespice.readers.styles' );
		$this->insertTrace();

		$config = \MediaWiki\MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'bsg' );
		$oOutputPage->addJsConfigVars( 'bsgReadersNumOfReaders',  $config->get( 'ReadersNumOfReaders' ) );

		return true;
	}

	/**
	 * Hook-Handler for Hook 'ParserFirstCallInit'
	 * @param object $oParser Parser
	 * @return boolean Always true
	 */
	public function insertTrace() {
		$oUser = $this->getUser();
		$oTitle = $this->getTitle();
		$oRevision = Revision::newFromTitle( $oTitle );

		if ( !( $oRevision instanceof Revision ) ) return true;

		$oDbw = wfGetDB( DB_MASTER );

		$oDbw->delete(
			'bs_readers',
			array(
				'readers_user_id' => $oUser->getId(),
				'readers_page_id' => $oTitle->getArticleID()
			)
		);

		$aNewRow = array();
		$aNewRow['readers_user_id'] = $oUser->getId();
		$aNewRow['readers_user_name'] = $oUser->getName();
		$aNewRow['readers_page_id'] = $oTitle->getArticleID();
		$aNewRow['readers_rev_id'] = $oRevision->getId();
		$aNewRow['readers_ts'] = wfTimestampNow();

		$oDbw->insert( 'bs_readers', $aNewRow );

		return true;
	}

	/**
	 * Adds the "Readers" menu entry in view mode
	 * @param SkinTemplate $sktemplate
	 * @param array $links
	 * @return boolean Always true to keep hook running
	 */
	public function onSkinTemplateNavigation( &$sktemplate, &$links ) {
		if ( $this->checkContext() === false ) {
			return true;
		}
		//Check if menu entry has to be displayed
		$oCurrentUser = $this->getUser();
		if ( $oCurrentUser->isLoggedIn() === false ) {
			return true;
		}

		$oCurrentTitle = $this->getTitle();
		if ( $oCurrentTitle->exists() === false ) {
			return true;
		}

		if ( !$oCurrentTitle->userCan( 'viewreaders' ) ) {
			return true;
		}

		$oSpecialPageWithParam = SpecialPage::getTitleFor(
			'Readers', $oCurrentTitle->getPrefixedText()
		);

		//Add menu entry
		$links['actions']['readers'] = array(
			'class' => false,
			'text' => wfMessage( 'bs-readers-contentactions-label' )->text(),
			'href' => $oSpecialPageWithParam->getLocalURL(),
			'id' => 'ca-readers',
			'bs-group' => 'hidden'
		);

		return true;
	}

	/**
	 * Checks wether to set Context or not.
	 * @return bool
	 */
	public function checkContext() {
		$oTitle = $this->getTitle();
		$oUser = $this->getUser();

		if ( wfReadOnly() ) return false;

		if ( is_null( $oTitle ) ) return false;

		if ( !$oTitle->exists() ) return false;

		if ( $oUser->isAnon() || User::isIP( $oUser->getName() ) ) return false;

		// Do only display when user is allowed to read
		if ( !$oTitle->userCan( 'read' ) ) return false;

		// Do only display in view mode
		if ( $this->getRequest()->getVal( 'action', 'view' ) !== 'view' ) return false;

		// Do not display on SpecialPages, CategoryPages or ImagePages
		if ( in_array( $oTitle->getNamespace(), array( NS_SPECIAL, NS_CATEGORY, NS_FILE, NS_MEDIAWIKI ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if the Readers segment should be added to the flyout
	 *
	 * @param IContextSource $context
	 * @return bool
	 */
	public static function flyoutCheckPermissions( \IContextSource $context ) {
		if( $context->getTitle()->userCan( 'viewreaders' ) == false ) {
			return false;
		}
		return true;
	}

}
