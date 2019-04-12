<?php

namespace BlueSpice\Readers\Data\PageReaders;

class Store implements \BlueSpice\Data\IStore {
	/**
	 *
	 * @var \IContextSource
	 */
	protected $context = null;

	/**
	 *
	 * @param \IContextSource $context
	 * @param \Wikimedia\Rdbms\LoadBalancer $loadBalancer
	 */
	public function __construct( $context, $loadBalancer ) {
		$this->context = $context;
		$this->loadBalancer = $loadBalancer;
	}

	public function getReader() {
		return new Reader( $this->loadBalancer, $this->context );
	}

	public function getWriter() {
		throw new Exception( 'This store does not support writing!' );
	}

}
