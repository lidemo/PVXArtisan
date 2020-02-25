<?php

namespace PVXArtisan\Helpers;

class CsvFile
{

    public static function writeToFile($filePath, $csv)
    {
        file_put_contents($filePath, $csv);
    }

    public static function readToArray($filePath)
    {
        $array = array_map('str_getcsv', file($filePath));
        array_shift($array);

        return $array;
    }

}
