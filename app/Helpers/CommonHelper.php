<?php

if (!function_exists('getInitials')) {
    function getInitials($name)
    {
        $words = explode(" ", $name);

        if (count($words) > 3) {
            $words = array_slice($words, 0, 3); // Take only the first 3 words
        }

        $initials = "";

        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return $initials;
    }
}


if (!function_exists('durationCalculation')) {
    function durationCalculation($start, $end)
    {
        $start = \Carbon\Carbon::parse($start);
        $end = \Carbon\Carbon::parse($end);
        $duration = $start->diff($end);
        $formatted_duration = '';

        if ($duration->days > 0) {
            $formatted_duration .= $duration->days . ' day';
            if ($duration->days > 1) {
                $formatted_duration .= 's';
            }
        } elseif ($duration->h > 0) {
            $formatted_duration .= $duration->h . ' hour';
            if ($duration->h > 1) {
                $formatted_duration .= 's';
            }
        } elseif ($duration->i > 0) {
            $formatted_duration .= $duration->i . ' minute';
            if ($duration->i > 1) {
                $formatted_duration .= 's';
            }
        }

        return $formatted_duration;
    }
}
