<?php

namespace Adelowo\Mocking\PreMock;

class Logger
{

    const LOG_FILE = 'storage/logs/app.log';

    public function log(string $username)
    {
        $status = false;

        $userNamePlusSeparator = $username . ';';

        if (file_put_contents(self::LOG_FILE, $userNamePlusSeparator, FILE_APPEND)) {
            $status = true;
        }

        return $status;
    }
}
