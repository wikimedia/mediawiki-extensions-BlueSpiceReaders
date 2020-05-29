<?php

namespace BlueSpice\Readers\Hook\BeforePageDisplay;

class AddResources extends \BlueSpice\Hook\BeforePageDisplay {

	protected function skipProcessing() {
		if ( !$this->out->getTitle() || !$this->out->getTitle()->exists() ) {
			return true;
		}
		if ( !$this->out->getTitle()->userCan( 'read' ) ) {
			return true;
		}
		if ( $this->out->getRequest()->getVal( 'action', 'view' ) !== 'view' ) {
			return true;
		}

		$excludeNS = $this->getConfig()->get( 'ReadersNamespaceBlacklist' );
		if ( in_array( $this->out->getTitle()->getNamespace(), $excludeNS ) ) {
			return true;
		}

		return false;
	}

	protected function doProcess() {
		$this->out->addJsConfigVars(
			'bsgReadersNumOfReaders',
			$this->getConfig()->get( 'ReadersNumOfReaders' )
		);
		return true;
	}

}
