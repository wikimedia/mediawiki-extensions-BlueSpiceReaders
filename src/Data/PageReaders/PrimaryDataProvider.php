<?php

namespace BlueSpice\Readers\Data\PageReaders;

use BlueSpice\Readers\Data\Record;

class PrimaryDataProvider implements \BlueSpice\Data\IPrimaryDataProvider {

	/**
	 *
	 * @var \BlueSpice\Data\Record[]
	 */
	protected $data = [];

	/**
	 *
	 * @var \Wikimedia\Rdbms\IDatabase
	 */
	protected $db = null;

	/**
	 *
	 * @var \IContextSource
	 */
	protected $context = null;

	/**
	 *
	 * @param \Wikimedia\Rdbms\IDatabase $db
	 * @param \IContextSource $context
	 */
	public function __construct( $db, $context ) {
		$this->db = $db;
		$this->context = $context;
	}

	/**
	 *
	 * @param \BlueSpice\Data\ReaderParams $params
	 * @return array
	 */
	public function makeData( $params ) {
		$this->data = [];

		$rows = $this->db->select(
			'bs_readers',
			'*',
			'',
			__METHOD__
		);

		foreach ( $rows as $row ) {
			$record = new Record( $row );
			$this->appendRowToData( $record );
		}

		return $this->data;
	}

	protected function appendRowToData( Record $record ) {
		$this->data[] = $record;
	}
}
