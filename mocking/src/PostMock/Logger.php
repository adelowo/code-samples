<?php

namespace Adelowo\Mocking\PostMock;

class Logger
{

    protected $fileSystem;

    public function __construct(FileSystem $flysystem)
    {
        $this->fileSystem = $flysystem;
    }

    public function log(string $username)
    {
        $status = false;

        $userNamePlusSeperator = $username . ';';

        if ($this->fileSystem->append($userNamePlusSeperator)) {
            $status = true;
        }

        return $status;
    }
}
