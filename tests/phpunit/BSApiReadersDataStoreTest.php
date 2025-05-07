<?php

use BlueSpice\Tests\BSApiExtJSStoreTestBase;

/**
 * @group medium
 * @group API
 * @group Database
 * @group BlueSpice
 * @group BlueSpiceExtensions
 * @group BlueSpiceReaders
 * @covers BSApiReadersDataStore
 */
class BSApiReadersDataStoreTest extends BSApiExtJSStoreTestBase {

	/** @var int */
	protected $iFixtureTotal = 1;

	/** @var string */
	protected $sQuery = '1';

	protected function getStoreSchema() {
		return [
			'pv_page' => [
				'type' => 'string'
			],
			'pv_page_link' => [
				'type' => 'string'
			],
			'pv_page_title' => [
				'type' => 'string'
			],
			'pv_ts' => [
				'type' => 'date'
			],
			'pv_date' => [
				'type' => 'string'
			],
			'pv_readers_link' => [
				'type' => 'string'
			]
		];
	}

	protected function createStoreFixtureData() {
		$dbw = $this->getDb();
		$this->setUp();

		$pageID = $this->insertPage( 'Test' )['id'];
		$dbw->insert(
			'bs_readers',
			[
				'readers_id' => 1,
				'readers_user_id' => 1,
				'readers_user_name' => 'WikiSysop',
				'readers_page_id' => $pageID,
				'readers_rev_id' => 1,
				'readers_ts' => '20250101010101'
			],
			__METHOD__
		);
	}

	protected function getModuleName() {
		return 'bs-readers-data-store';
	}

	public function provideSingleFilterData() {
		return [
			'Filter by pv_page_title equals' => [ 'string', 'eq', 'pv_page_title', 'Test', 1 ],
			'Filter by pv_page_title contains' => [ 'string', 'ct', 'pv_page_title', 'est', 1 ]
		];
	}

	public function provideMultipleFilterData() {
		return [
			'Filter by pv_page_title contains and pv_ts equals' => [
				[
					[
						'type' => 'date',
						'comparison' => 'gt',
						'field' => 'pv_ts',
						'value' => '20250101010100'
					],
					[
						'type' => 'string',
						'comparison' => 'ct',
						'field' => 'pv_page_title',
						'value' => 'est'
					]
				],
				1
			]
		];
	}

}
