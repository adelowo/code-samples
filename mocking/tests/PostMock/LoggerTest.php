<?php

namespace Adelowo\Mocking\Tests\PostMock;

use Mockery;
use Adelowo\Mocking\PostMock\Logger;
use Adelowo\Mocking\PostMock\FileSystem;

class LoggerTest extends \PHPUnit_Framework_TestCase
{

	protected $logger;

	public function setUp()
	{
		$fileSystem = Mockery::mock(FileSystem::class,["storage/logs/app.log"]);

		$fileSystem->shouldReceive("append")
			->andReturn(true);

		$this->logger = new Logger($fileSystem);
	}

    public function tearDown()
    {
        Mockery::close();
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
