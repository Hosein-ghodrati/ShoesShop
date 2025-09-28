<?php
require 'vendor/autoload.php'; // if you installed morilog/jalali via composer

use Morilog\Jalali\Jalalian;

function toJalali($gregorianDateTime) {
    // $gregorianDateTime is like '2025-09-14 13:34:11'
    $dt = Jalalian::fromDateTime($gregorianDateTime);
    return $dt->format('Y/m/d H:i:s'); // change format as you like
}
