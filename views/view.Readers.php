<?php
/**
 * Renders the Readers frame.
 *
 * Part of BlueSpice MediaWiki
 *
 * @author     Stephan Muggli <muggli@hallowelt.com>
 * @package    BlueSpice_Extensions
 * @subpackage Authors
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License v3
 * @filesource
 */

/**
 * This view renders the Readers frame.
 * @package    BlueSpice_Extensions
 * @subpackage Readers
 */
class ViewReaders extends ViewBaseElement {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * This method actually generates the output
	 * @return string HTML output
	 */
	public function execute( $params = false ) {
		if ( empty( $this->_mItems ) ) {
			return '';
		}

		$sReadersList = '';
		$iReaders = count( $this->_mItems );

		foreach ( $this->_mItems as $renderer ) {
			$renderer instanceof \BlueSpice\Renderer\UserImage;
			$sReadersList .= $renderer->render();
		}

		$sUsername = $this->_mItems[0]->getUser()->getName();
		$aOut = array();
		$aOut[] = '<div class="bs-readers">';
		$aOut[] = '  <fieldset>';
		$aOut[] = '    <legend>';
		$aOut[] = wfMessage( 'bs-readers-title', $iReaders, $sUsername )->text();
		$aOut[] = '    </legend>';
		$aOut[] = $sReadersList;
		$aOut[] = '  </fieldset>';
		$aOut[] = '</div>';

		return implode( "\n", $aOut );
	}

	public function addItem( $item, $key = false ) {
		if ( !$item ) {
			return false;
		}
		if ( $key && ( is_numeric( $key ) || is_string( $key ) ) ) {
			$this->_mItems[$key] = $item;
		}
		else {
			$this->_mItems[] = $item;
		}
		return $item;
	}
}