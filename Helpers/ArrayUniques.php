<?php

namespace PVXArtisan\Helpers;

class ArrayUniques
{
    public static function make(Array $data, String $column) {
        $temp_array = [];
        foreach ($data as &$v) {
            if (!isset($temp_array[$v[$column]]))
            $temp_array[$v[$column]] =& $v;
        }
        $data = array_values($temp_array);

        return $data;
    }
}
