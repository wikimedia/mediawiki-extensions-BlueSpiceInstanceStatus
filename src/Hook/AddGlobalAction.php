<?php

namespace BlueSpice\InstanceStatus\Hook;

use MediaWiki\SpecialPage\SpecialPageFactory;
use MWStake\MediaWiki\Component\CommonUserInterface\Component\RestrictedTextLink;
use MWStake\MediaWiki\Component\CommonUserInterface\Hook\MWStakeCommonUIRegisterSkinSlotComponents;
use RawMessage;

class AddGlobalAction implements MWStakeCommonUIRegisterSkinSlotComponents {

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/**
	 * @param SpecialPageFactory $specialPageFactory
	 */
	public function __construct( SpecialPageFactory $specialPageFactory ) {
		$this->specialPageFactory = $specialPageFactory;
	}

	/**
	 * @inheritDoc
	 */
	public function onMWStakeCommonUIRegisterSkinSlotComponents( $registry ): void {
		$special = $this->specialPageFactory->getPage( 'InstanceStatus' );
		$registry->register(
			'GlobalActionsAdministration',
			[
				'bs-instance-status' => [
					'factory' => static function () use ( $special ) {
						return new RestrictedTextLink( [
							'id' => 'ga-bs-instance-status',
							'href' => $special->getPageTitle()->getLocalURL(),
							'text' => new RawMessage( $special->getDescription() ),
							'title' => new RawMessage( $special->getDescription() ),
							'aria-label' => new RawMessage( $special->getDescription() ),
							'permissions' => [ 'wikiadmin' ]
						] );
					}
				]
			]
		);
	}
}
