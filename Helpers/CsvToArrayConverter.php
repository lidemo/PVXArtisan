<?php

namespace PVXArtisan\Helpers;

class CsvToArrayConverter{

    public static function csvToTwoDimensional(String $csv, String $actions = null) : Array{
        $lines = explode("\n", $csv); //explode by a new line, so you can get every line first

        $head = str_getcsv(array_shift($lines));
    
        $array = array();
        foreach ($lines as $line) {
            $row = array_pad(str_getcsv($line), count($head), '');
            $array[] = array_combine($head, $row);
        }

        if ($actions != null) {
            $array = self::executeArrayActions($array, $actions);
        }

        return $array;
    }

    public static function csvToArray(String $csv) : Array{
        return explode(',', $csv);
    }

    public static function executeArrayActions(Array &$array, String $actions) {
        $actions = explode('|', $actions);
        foreach ($actions as $action) {
            //$array = \call_user_func($action, [&$array]);
            if ($action == 'pop') array_pop($array);
            if ($action == 'shift') array_shift($array);
        }
        return $array;
    }

}