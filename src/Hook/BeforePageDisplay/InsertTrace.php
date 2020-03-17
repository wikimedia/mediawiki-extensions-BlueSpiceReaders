<?php

namespace BlueSpice\Readers\Hook\BeforePageDisplay;

use BlueSpice\Readers\Job\InsertTrace as Job;
use JobQueueGroup;

class InsertTrace extends \BlueSpice\Hook\BeforePageDisplay {

	protected function skipProcessing() {
		if ( $this->getServices()->getReadOnlyMode()->isReadOnly() ) {
			return true;
		}
		if ( !$this->out->getTitle() || !$this->out->getTitle()->exists() ) {
			return true;
		}
		if ( !\MediaWiki\MediaWikiServices::getInstance()
			->getPermissionManager()
			->userCan(
				'read',
				$this->out->getUser(),
				$this->out->getTitle()
			)
		) {
			return true;
		}
		if ( $this->out->getUser()->isAnon() ) {
			return true;
		}
		// Not sure if this is needed additionaly to isAnon...
		if ( \User::isIP( $this->out->getUser()->getName() ) ) {
			return true;
		}
		$excludeNS = [ NS_MEDIA, NS_SPECIAL, NS_CATEGORY, NS_FILE, NS_MEDIAWIKI ];
		if ( in_array( $this->out->getTitle()->getNamespace(), $excludeNS ) ) {
			return true;
		}
		return false;
	}

	protected function doProcess() {
		// TODO: Use Javascript and new Taks api and task base class instead of
		// BeforePageDisplayHook to insert this job.
		// There should not be any insert on the simple reading of any page
		$job = new Job( $this->out->getTitle(), [
			Job::PARAM_USER_ID => $this->out->getUser()->getId(),
			Job::PARAM_TIMESTAMP => wfTimestampNow(),
		] );
		JobQueueGroup::singleton()->push( $job );
		return true;
	}

}
