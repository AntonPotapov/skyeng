<?php


$value_1 = '9223372036854775807';
$value_2 = '19223372036854775807';


/**
 * Суммирует 2 больших числа
 * @param string $v1
 * @param string $v2
 * @return string
 */
function sum_big_values(string $v1, string $v2) : string
{
    if (strlen($v1) < strlen($v2)) {
        list($v2, $v1) = array($v1, $v2);
    }

    $result_sum = '';

    $v1 = strrev($v1);
    $v2 = strrev($v2);

    $enlarge = 0;
    for ($pos = 0; $pos < strlen($v1); $pos++) {
        $sum_num = $v1[$pos] + (isset($v2[$pos]) ? $v2[$pos] : 0) + $enlarge;

        $result_sum .= ($sum_num % 10);

        $enlarge = intdiv($sum_num, 10);
    }

    if ($enlarge) {
        $result_sum .= $enlarge;
    }

    return strrev($result_sum);
}

echo sum_big_values($value_1, $value_2);