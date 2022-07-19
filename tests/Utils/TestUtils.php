<?php

namespace App\Tests\Utils;

use Google\Client;
use Google\Service\Sheets;
use Google\Exception;

/**
 * Class TestUtils
 * @package App\Tests\Utils
 */
class TestUtils
{
    /**
     * Get service sheet object
     *
     * @return Sheets
     * @throws Exception
     */
    public static function getService(): Sheets
    {
        $client = new Client();
        $client->setScopes(Sheets::SPREADSHEETS);
        $client->setAuthConfig($_ENV['GOOGLE_CREDENTIALS']);

        return new Sheets($client);
    }
}

