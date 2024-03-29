<?php
namespace BlueSpice\Readers\Special;

use Html;
use PermissionsError;
use Title;

class Readers extends \BlueSpice\SpecialPage {

	/**
	 * @param string $name
	 * @param string $restrictions
	 */
	public function __construct( $name = 'Readers', $restrictions = 'viewreaders' ) {
		parent::__construct( $name, $restrictions, false );
	}

	/**
	 * @param string $parameters
	 * @throws PermissionsError
	 */
	public function execute( $parameters ) {
		$this->checkPermissions();
		$requestedTitle = null;
		$out = $this->getOutput();

		if ( !empty( $parameters ) ) {
			$requestedTitle = Title::newFromText( $parameters );

			if (
				$requestedTitle->exists() &&
				(
					$requestedTitle->getNamespace() !== NS_USER ||
					strpos( $requestedTitle->getText(), '/' ) !== false
				)
			) {
				$stringOut = $this->pageReaders( $requestedTitle );
				if ( !$stringOut ) {
					$this->pageNotExistError();
				}
			} elseif (
				$requestedTitle->getNamespace() === NS_USER &&
				strpos( $requestedTitle->getText(), '/' ) === false
			) {
				$stringOut = $this->readByUser( $requestedTitle );
				if ( !$stringOut ) {
					$stringOut = $this->pageNotExistError();
				}
			} else {
				$stringOut = $this->pageNotExistError();
			}
		} else {
			$stringOut = $this->emptyInputError();
		}

		if ( $requestedTitle === null ) {
			$out->setPageTitle( $out->getPageTitle() );
		}

		$out->addHTML( $stringOut );
	}

	/**
	 * @return string
	 */
	protected function pageNotExistError() {
		$this->getOutput()->setPageTitle( wfMessage( 'bs-readers-pagenotexists' ) );
		return $this->msg( 'bs-readers-pagenotexists' )->text();
	}

	/**
	 * @return string
	 */
	protected function emptyInputError() {
		$this->getOutput()->setPageTitle( wfMessage( 'bs-readers-emptyinput' ) );
		return $this->msg( 'bs-readers-emptyinput' )->text();
	}

	/**
	 * @param Title $requestedTitle
	 * @return string
	 */
	protected function pageReaders( Title $requestedTitle ) {
		$this->getOutput()->addModules( [ 'ext.bluespice.readers.specialreaders' ] );
		$this->getOutput()->setPageTitle(
			wfMessage( 'readers', $requestedTitle->getFullText() )->text()
		);
		$this->getOutput()->addJsConfigVars( "bsReadersTitle", $requestedTitle->getPrefixedText() );

		return Html::element( 'div', [
			'id' => 'bs-readers-grid'
		] );
	}

	/**
	 * @param Title $requestedTitle
	 * @return string
	 */
	protected function readByUser( Title $requestedTitle ) {
		$this->getOutput()->addModules( [ 'ext.bluespice.readers.specialreaderspath' ] );
		$oUser = $this->services->getUserFactory()->newFromName( $requestedTitle->getText() );
		$this->getOutput()->setPageTitle( wfMessage( 'readers-user', $oUser->getName() )->text() );

		$this->getOutput()->addJsConfigVars( "bsReadersUserID", $oUser->getId() );

		return Html::element( 'div', [
			'id' => 'bs-readerspath-grid'
		] );
	}
}
