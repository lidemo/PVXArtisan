<?php

namespace PVXArtisan\Helpers\Formatters;

class Arrayf
{

    public static function pre($array) {
        echo '<pre>';
        print_r($array);
    }

    public static function dump($array) {
        echo '<pre>';
        var_dump($array);
    }

}
