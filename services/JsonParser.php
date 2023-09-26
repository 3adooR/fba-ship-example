<?php

namespace App\Services;

class JsonParser
{
    /**
     * Parse JSON file and return data as array
     *
     * @param string $filename
     * @return array
     */
    public static function parse(string $filename): array
    {
        $jsonData = file_get_contents($filename);

        return json_decode($jsonData, true);
    }
}