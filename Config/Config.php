<?php

namespace PVXArtisan\Config;

class Config
{

    //public static $configFile = "config.json";

    public static function get() {
        return json_decode( json_encode( [
            'datetime' => [
                'offset'    => 1,
                'offsetOperation' => '-'
            ]

        ]));
    }

}
