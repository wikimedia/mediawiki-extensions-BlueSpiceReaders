<?php

namespace BlueSpice\Readers\Hook\BeforePageDisplay;

class AddResources extends \BlueSpice\Hook\BeforePageDisplay {

	protected function skipProcessing() {
		if ( !$this->out->getTitle() || !$this->out->getTitle()->exists() ) {
			return true;
		}
		if ( !\MediaWiki\MediaWikiServices::getInstance()
			->getPermissionManager()
			->userCan(
				'read',
				$this->out->getUser(),
				$this->out->getTitle()
			)
		) {
			return true;
		}
		if ( $this->out->getRequest()->getVal( 'action', 'view' ) !== 'view' ) {
			return true;
		}
		// TODO: config
		$excludeNS = [ NS_MEDIA, NS_SPECIAL, NS_CATEGORY, NS_FILE, NS_MEDIAWIKI ];
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
