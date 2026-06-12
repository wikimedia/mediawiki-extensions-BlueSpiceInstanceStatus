<?php

namespace BlueSpice\InstanceStatus\Tests\Rest;

use BlueSpice\InstanceStatus\Rest\StatusCheckHandler;
use BlueSpice\InstanceStatus\StatusReport;
use MediaWiki\Config\ConfigFactory;
use MediaWiki\Config\HashConfig;
use MediaWiki\Rest\HttpException;

/**
 * @covers \BlueSpice\InstanceStatus\Rest\StatusCheckHandler
 */
class StatusCheckHandlerTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @dataProvider provideAllowedIPsData
	 */
	public function testHttpExceptionInExecuteDueToInvalidIP( $configData ) {
		$statusReportMock = $this->createMock( StatusReport::class );
		$statusReportMock->expects( $this->never() )
			->method( 'getReportForAPI' );

		$configFactoryMock = $this->createMock( ConfigFactory::class );
		$configFactoryMock->expects( $this->once() )
			->method( 'makeConfig' )
			->with( 'bsg' )
			->willReturn( new HashConfig( $configData ) );

		$handler = $this->getMockBuilder( StatusCheckHandler::class )
			->setConstructorArgs( [ $statusReportMock, $configFactoryMock ] )
			->onlyMethods( [ 'getClientIP' ] )
			->getMock();
		$handler->method( 'getClientIP' )->willReturn( '8.8.8.8' );

		$this->expectException( HttpException::class );
		$this->expectExceptionCode( 401 );
		$handler->execute();
	}

	public static function provideAllowedIPsData() {
		return [
			// Bare IPs are not valid ranges (CIDR or explicit range required),
			// so all of these must be rejected regardless of the client IP
			'single-value-in-AllowedIP' => [
				[
					'InstanceStatusCheckAllowedIP' => '127.0.0.1',
					'InstanceStatusCheckAllowedIPs' => []
				]
			],
			'multiple-values-in-AllowedIP' => [
				[
					'InstanceStatusCheckAllowedIP' => '127.0.0.1,192.168.0.1',
					'InstanceStatusCheckAllowedIPs' => []
				]
			],
			'singlevalue-values-in-AllowedIP-and-in-AllowedIPs' => [
				[
					'InstanceStatusCheckAllowedIP' => '127.0.0.1',
					'InstanceStatusCheckAllowedIPs' => [ '192.168.0.1' ]
				]
			],
			'novalue-in-AllowedIP-and-singlevalue-in-AllowedIPs' => [
				[
					'InstanceStatusCheckAllowedIP' => null,
					'InstanceStatusCheckAllowedIPs' => [ '192.168.0.1' ]
				]
			],
			'novalue-in-AllowedIP-and-novalues-in-AllowedIPs' => [
				[
					'InstanceStatusCheckAllowedIP' => null,
					'InstanceStatusCheckAllowedIPs' => []
				]
			],
			'valid-ranges-but-client-not-in-any' => [
				[
					'InstanceStatusCheckAllowedIP' => '127.0.0.1/32',
					'InstanceStatusCheckAllowedIPs' => [ '192.168.0.0/24' ]
				]
			]
		];
	}
}
