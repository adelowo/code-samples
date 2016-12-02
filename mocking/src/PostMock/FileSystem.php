<?php

namespace Adelowo\Mocking\PostMock;

class FileSystem
{

    protected $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function append($data)
    {
        return $this->put($data, FILE_APPEND);
    }

    public function put($data, int $flag = 0)
    {
        return file_put_contents($this->path, $data, $flag);
    }
}
