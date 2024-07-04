<?php

if (!function_exists('remainingTime')) {
    function remainingTime($limitTime)
    {
        $now = now();
        $limit = \Carbon\Carbon::parse($limitTime);
        $diff = $now->diff($limit);

        if ($diff->invert) {
            return '時間切れ';
        }

        if ($diff->days > 0) {
            return $diff->days . '日' . $diff->h . '時間';
        }

        return $diff->h . '時間' . $diff->i . '分';
    }
}