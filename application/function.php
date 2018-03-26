<?php

function randNumStr($length)
{
    $chars = array(
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
    );
    $password = '';
    while (strlen($password) < $length) {
        $password .= $chars[rand(0, 9)];
    }
    return $password;
}