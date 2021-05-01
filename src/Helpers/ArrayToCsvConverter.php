<?php

namespace PVXArtisan\Helpers;

class ArrayToCsvConverter
{

    public function TwoDimToCsv($array)
    {
        $lines = '';

        foreach ($array as $outerArray){
/*             echo '<pre>';
            print_r($order); */
            $line = '';
            foreach ($outerArray as $value) {

                $line .= $value .",";

            }

            $line = substr($line, 0, strlen($line) -1);
            $line .= "\r\n";
            $lines .= $line;
            
        }

        return $lines;
    }

}
