<?php

if (!function_exists('showDate')) {
    function showDate($time) {
        return $time->format('d-m-Y h:i A');
    }
}
