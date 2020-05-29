<?php

namespace BlueSpice\Readers\Hook\SkinTemplateNavigation;

class AddReaders extends \BlueSpice\Hook\SkinTemplateNavigation {

	protected function skipProcessing() {
		if ( !$this->sktemplate->getTitle() || !$this->sktemplate->getTitle()->exists() ) {
			return true;
		}
		if ( !\MediaWiki\MediaWikiServices::getInstance()
			->getPermissionManager()
			->userCan(
				'viewreaders',
				$this->sktemplate->getUser(),
				$this->sktemplate->getTitle()
			)
		) {
			return true;
		}
		$excludeNS = $this->getConfig()->get( 'ReadersNamespaceBlacklist' );
		if ( in_array( $this->sktemplate->getTitle()->getNamespace(), $excludeNS ) ) {
			return true;
		}
		return false;
	}

	protected function doProcess() {
		$special = \SpecialPage::getTitleFor(
			'Readers',
			$this->sktemplate->getTitle()->getPrefixedText()
		);

		// Add menu entry
		$this->links['actions']['readers'] = [
			'class' => false,
			'text' => $this->sktemplate->msg( 'bs-readers-contentactions-label' ),
			'href' => $special->getLocalURL(),
			'id' => 'ca-readers',
			'bs-group' => 'hidden'
		];

		return true;
	}

}
