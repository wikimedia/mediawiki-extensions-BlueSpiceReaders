<?php
namespace BlueSpice\Readers\Api\Store;

use BlueSpice\Readers\Data\PageReaders\Store;
use BlueSpice\Context;

class PageReaders extends \BlueSpice\Api\Store {

	protected function makeDataStore() {
		return new Store(
			new Context( $this->getContext(), $this->getConfig() ),
			\MediaWiki\MediaWikiServices::getInstance()->getDBLoadBalancer()
		);
	}
}
