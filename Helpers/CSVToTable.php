<?php

namespace PVXArtisan\Helpers;

class CsvToTable{

    public static function make(String $csv)
    {
        $array = explode("\n", $csv);

        $html = "
            <table class=\"table\">
                <thead>
                    
            ";

        $headers = explode(",", array_shift($array));

        foreach ($headers as $header) {
            $html .= "<th>$header</th>";
        }

        $html .= "</thead>";

        foreach ($array as $body) {
            $html .= "<tr>";

            $row = explode(",", $body);
            foreach ($row as $value) {
                $html .= "<td>$value</td>";
            }

            $html .= "</tr>";
        }

        $html .= "</table>";

        return $html;
    }

}