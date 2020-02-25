<?php

namespace PVXArtisan\Helpers;

class CsvFile
{

    public function writeToCsvFile($filePath, $csv)
    {
        file_put_contents($filePath, $csv);
    }

    public function readToArray($filePath)
    {
        $productsArray = array_map('str_getcsv', file('Weight and dims doncaster.csv'));
        array_shift($productsArray);
    }

}
