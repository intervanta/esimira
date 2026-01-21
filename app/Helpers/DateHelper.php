<?php

namespace App\Helpers;

class DateHelper
{
    public static function formatDays($days)
    {
        $months = floor($days / 30);
        $remainingDays = $days % 30;
        
        if ($months >= 1) {
            $result = $months . ' Month' . ($months > 1 ? 's' : '');
            if ($remainingDays > 0) {
                $result .= ' ' . $remainingDays . ' Day' . ($remainingDays > 1 ? 's' : '');
            }
            return $result;
        }
        
        return $days . ' Day' . ($days > 1 ? 's' : '');
    }
}