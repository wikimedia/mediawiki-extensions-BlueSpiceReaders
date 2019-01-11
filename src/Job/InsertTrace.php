<?php

namespace BlueSpice\Readers\Job;

use Job;
use User;
use Title;
use BlueSpice\Services;
use BlueSpice\Context;
use BlueSpice\Data\RecordSet;
use BlueSpice\Readers\Data\Record;
use BlueSpice\Readers\Data\PageReaders\Store;

class InsertTrace extends Job {

	const COMMAND = 'BlueSpiceReadersInsertTrace';
	const PARAM_USER_ID = 'userid';
	const PARAM_TIMESTAMP = 'ts';

	/**
	 *
	 * @param Title $title
	 * @param array $params
	 */
	public function __construct( $title, $params ) {
		parent::__construct( static::COMMAND, $title, $params );
	}

	public function run() {
		$params = $this->getParams();
		$user = User::newFromId( $params[ static::PARAM_USER_ID ] );
		if ( !$user || $user->isAnon() ) {
			return;
		}

		if ( empty( $params[static::PARAM_TIMESTAMP] ) ) {
			$params[static::PARAM_TIMESTAMP] = wfTimestampNow();
		}
		$record = new Record( (object)[
			Record::USER_ID => (int)$user->getId(),
			Record::PAGE_ID => (int)$this->getTitle()->getArticleID(),
			Record::TIMESTAMP => $params[static::PARAM_TIMESTAMP],
			Record::USER_NAME => $user->getName(),
			Record::REV_ID => $this->getTitle()->getLatestRevID()
		] );

		$this->getStore()->getWriter()->write( new RecordSet( [ $record ] ) );
	}

	/**
	 *
	 * @return Store
	 */
	protected function getStore() {
		return new Store(
			$this->getContext(),
			$this->getServices()->getDBLoadBalancer()
		);
	}

	/**
	 *
	 * @return Context
	 */
	protected function getContext() {
		return new Context(
			\RequestContext::getMain(),
			$this->getServices()->getConfigFactory()->makeConfig( 'bsg' )
		);
	}

	/**
	 *
	 * @return Services
	 */
	protected function getServices() {
		return Services::getInstance();
	}

}
