<?php

namespace App;

class Helper
{
    /**
     * Get rows from the data for the table to be shown correctly
     *
     * @param array $data
     * @return array
     */
    public function getRows(array $data): array
    {
        $rowsArr = array();

        for ($i=0; $i < count($data['id']); $i++) {
            $arr = array();

            foreach ($data as $value) {
                array_push($arr, $value[$i]);
            }

            array_push($rowsArr, $arr);
        }

        return $rowsArr;
    }
}
