<?php

namespace BlueSpice\InstanceStatus\InstanceStatusProvider;

use Message;

class LastUpdate extends Created {

	/**
	 * @return string
	 */
	public function getLabel(): string {
		return Message::newFromKey( 'bs-instancestatus-instance-status-last-update-label' )->text();
	}

	/**
	 * @return mixed|string
	 */
	protected function getConfigVar() {
		$buildInfoFilePath = $GLOBALS['IP'] . '/BUILDINFO';
		if ( !file_exists( $buildInfoFilePath ) ) {
			return '';
		}
		$buildFile = fopen( $buildInfoFilePath, "r" );
		$lastUpdate = fread( $buildFile, filesize( $GLOBALS['IP'] . "/BUILDINFO" ) );
		fclose( $buildFile );
		return ( !$lastUpdate ) ? '' : $lastUpdate;
	}

	/**
	 * @return string
	 */
	public function getIcon(): string {
		return 'reload';
	}

	/**
	 * @return int
	 */
	public function getPriority(): int {
		return 50;
	}
}
