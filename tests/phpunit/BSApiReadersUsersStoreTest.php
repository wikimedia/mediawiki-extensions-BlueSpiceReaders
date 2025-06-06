<?php

/**
 * @group medium
 * @group API
 * @group Database
 * @group BlueSpice
 * @group BlueSpiceExtensions
 * @group BlueSpiceReaders
 * @covers BSApiReadersUsersStore
 */
class BSApiReadersUsersStoreTest extends \BlueSpice\Tests\BSApiExtJSStoreTestBase {
	/** @var int */
	protected $iFixtureTotal = 3;
	// ID = 2; ID = 1 has default "UTPage"; See BSPageFixturesProvider / pages.json

	/** @var string */
	protected $sQuery = "Test";

	protected function getStoreSchema() {
		return [
			'user_image' => [
				'type' => 'string'
			],
			'user_name' => [
				'type' => 'string'
			],
			'user_page' => [
				'type' => 'string'
			],
			'user_page_link' => [
				'type' => 'string'
			],
			'user_readers' => [
				'type' => 'string'
			],
			'user_readers_link' => [
				'type' => 'string'
			],
			'user_ts' => [
				'type' => 'string'
			],
			'user_date' => [
				'type' => 'string'
			],
		];
	}

	protected function setUp(): void {
		parent::setUp();

		new BSReadersFixtures( $this->db );
	}

	protected function createStoreFixtureData() {
		$oPageFixtures = new \BlueSpice\Tests\BSPageFixturesProvider();
		$aFixtures = $oPageFixtures->getFixtureData();
		foreach ( $aFixtures as $aFixture ) {
			$this->insertPage( $aFixture[0], $aFixture[1] );
		}
	}

	protected function getModuleName() {
		return 'bs-readers-users-store';
	}

	public function providePagingData() {
		return parent::providePagingData();
	}

	public function provideSingleFilterData() {
		return [
			'Filter by user name equals' => [ 'string', 'eq', 'user_name', 'UTSysop', 1 ],
			'Filter by user name starts with' => [ 'string', 'sw', 'user_name', 'UTS', 1 ]
		];
	}

	public function provideMultipleFilterData() {
		return [
			'Filter by username starts with and timestamp equals' => [
				[
					[
						'type' => 'date',
						'comparison' => 'eq',
						'field' => 'user_ts',
						'value' => '20170101000000'
					],
					[
						'type' => 'string',
						'comparison' => 'sw',
						'field' => 'user_name',
						'value' => 'UTS'
					]
				],
				1
			]
		];
	}

}
