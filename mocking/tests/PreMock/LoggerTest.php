<?php

namespace Adelowo\Mocking\Tests\PreMock;

use Adelowo\Mocking\PreMock\Logger;

class LoggerTest extends \PHPUnit_Framework_TestCase
{

	protected $logger;

	public function setUp()
	{
		$this->logger = new Logger();
	}

	/**
	 *@dataProvider getDevelopers
	 */
	public function testLoggerWorksCorrectly($dev)
	{
		$this->assertTrue($this->logger->log($dev));
	}

	public function getDevelopers()
	{
		return [
			["fabpot"],["codeguy"],["philsturgeon"],["funkatron"],["adelowo"]
		];
	}
}