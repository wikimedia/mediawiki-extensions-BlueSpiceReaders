<?php

namespace BlueSpice\Readers\Data\PageReaders;

use BlueSpice\Readers\Data\Record;

class Writer extends \BlueSpice\Data\DatabaseWriter {

	/**
	 *
	 * @param \BlueSpice\Data\IReader $reader
	 * @param \LoadBalancer $loadBalancer
	 * @param \IContextSource|null $context
	 */
	public function __construct( \BlueSpice\Data\IReader $reader, $loadBalancer, \IContextSource $context = null ) {
		parent::__construct( $reader, $loadBalancer, $context, $context->getConfig() );
	}

	protected function getTableName() {
		return 'bs_readers';
	}

	public function getSchema() {
		return new \BlueSpice\Readers\Data\Schema();
	}

	protected function getIdentifierFields() {
		return [ Record::PAGE_ID, Record::USER_ID ];
	}
}
