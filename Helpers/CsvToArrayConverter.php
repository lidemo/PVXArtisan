<?php

namespace PVXArtisan\Helpers;

class CsvToArrayConverter{

    public static function csvToTwoDimensional(String $csv) : Array{
        $lines = explode("\n", $csv); //explode by a new line, so you can get every line first

        $head = str_getcsv(array_shift($lines));
    
        $array = array();
        foreach ($lines as $line) {
            $row = array_pad(str_getcsv($line), count($head), '');
            $array[] = array_combine($head, $row);
        }

        return $array;
    }

    public static function csvToArray(String $csv) : Array{
        return explode(',', $csv);
    }

}