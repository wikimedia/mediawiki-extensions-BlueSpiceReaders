<?php

namespace BlueSpice\Readers\Data\RevisionReaders;

use BlueSpice\Readers\Data\Record;
use MediaWiki\Language\Language;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;

class SecondaryDataProvider extends \MWStake\MediaWiki\Component\DataStore\SecondaryDataProvider {

	/**
	 * @var Language
	 */
	private $language;

	/**
	 * @var RevisionLookup
	 */
	private $revisionLookup;

	/**
	 * SecondaryDataProvider constructor.
	 * @param Language $language
	 * @param RevisionLookup $revisionLookup
	 */
	public function __construct( $language, $revisionLookup ) {
		$this->language = $language;
		$this->revisionLookup = $revisionLookup;
	}

	/**
	 *
	 * @param Record &$dataSet
	 */
	protected function doExtend( &$dataSet ) {
		$datetime = $this->language->timeanddate(
			$this->language->userAdjust(
				$dataSet->get( Record::TIMESTAMP )
			)
		);

		$dataSet->set( Record::DATETIME, $datetime );

		$revision = $this->revisionLookup->getRevisionById(
			$dataSet->get( Record::REV_ID )
		);

		if ( $revision instanceof RevisionRecord ) {
			$revDatetime = $this->language->timeanddate(
				$this->language->userAdjust(
					$revision->getTimestamp()
				)
			);
			$dataSet->set( Record::REV_DATETIME, $revDatetime );
		}
	}
}
