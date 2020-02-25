<?php

namespace PVXArtisan\Helpers;

class CsvFile
{

    public static function writeToFile($filePath, $csv)
    {

        $exploded = explode('/', $filePath);
        array_pop($exploded);
        $directory = implode('/', $exploded);

        if (!file_exists($directory)) mkdir($directory, 0777, true);
        file_put_contents($filePath, $csv);
    }

    public static function readToArray($filePath)
    {
        $array = array_map('str_getcsv', file($filePath));
        array_shift($array);

        return $array;
    }

}
