<?php

namespace Adelowo\Mocking\PostMock;

use Mockery;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @dataProvider getTopPhpers
     */
    public function testFileAppendingIsWorking($phper)
    {
        $file = Mockery::mock(FileSystem::class,["storage/logs/app.log"])->makePartial();

        $file->shouldReceive("put")
            ->with($phper, FILE_APPEND)
            ->once()
            ->andReturn(true);

        $this->assertTrue($file->append($phper));
    }

    public function getTopPhpers()
    {
        return [
            ["fabpot"],
            ["philsturgeon"]
        ];
    }
}
