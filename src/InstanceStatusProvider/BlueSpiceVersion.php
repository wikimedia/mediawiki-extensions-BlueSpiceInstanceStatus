<?php

namespace BlueSpice\InstanceStatus\InstanceStatusProvider;

use BlueSpice\InstanceStatus\IStatusProvider;
use MediaWiki\Parser\Sanitizer;
use Message;

class BlueSpiceVersion implements IStatusProvider {

	/**
	 * @return string
	 */
	public function getLabel(): string {
		return Message::newFromKey( 'bs-instancestatus-instance-status-bs-version-label' )->text();
	}

	/**
	 * @return string
	 */
	public function getValue(): string {
		$version = '';
		$versionFile = $GLOBALS['IP'] . '/BLUESPICE-VERSION';
		if ( file_exists( $versionFile ) ) {
			$versionFileContent = file_get_contents( $versionFile );
			$version = Sanitizer::stripAllTags( $versionFileContent );
		}
		return $version;
	}

	/**
	 * @return string
	 */
	public function getIcon(): string {
		return 'bluespice';
	}

	/**
	 * @return int
	 */
	public function getPriority(): int {
		return 30;
	}
}
