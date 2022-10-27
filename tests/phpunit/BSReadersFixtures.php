<?php

use MediaWiki\MediaWikiServices;

class BSReadersFixtures {

	/**
	 *
	 * @param \Wikimedia\Rdbms\IDatabase $db
	 */
	public function __construct( $db ) {
		$oFixtures = FormatJson::decode(
			file_get_contents( __DIR__ . '/data/bs_readers.fixtures.json' )
		);

		$userFactory = MediaWikiServices::getInstance()->getUserFactory();
		foreach ( $oFixtures->rows as $row ) {
			$title = Title::newFromText( $row[0] );
			$user = $userFactory->newFromName( $row[1] );
			$db->insert( 'bs_readers', [
				'readers_user_id'   => $user->getId(),
				'readers_user_name' => $user->getName(),
				'readers_page_id'   => $title->getArticleID(),
				// This is not used by the extension
				'readers_rev_id'    => 0,
				'readers_ts'        => $row[2],
			] );
		}
	}
}
