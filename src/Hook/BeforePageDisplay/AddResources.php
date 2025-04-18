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

		$excludeNS = $this->getConfig()->get( 'ReadersNamespaceBlacklist' );
		if ( in_array( $this->out->getTitle()->getNamespace(), $excludeNS ) ) {
			return true;
		}

		return false;
	}

	protected function doProcess() {
		$this->out->addModules( [ 'ext.bluespice.readers.insertTrace' ] );

		$isAllowed = false;
		$user = $this->skin->getUser();
		$permissionManager = $this->getServices()->getPermissionManager();
		if ( $permissionManager->userHasRight( $user, 'viewreaders' ) ) {
			$isAllowed = true;
		}

		$this->out->addJsConfigVars( 'bsViewReadersRight', $isAllowed );

		return true;
	}
}
