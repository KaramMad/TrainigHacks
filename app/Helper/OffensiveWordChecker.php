<?php

namespace App\Helper;

class OffensiveWordChecker
{
    public static function containsOffensiveWords($content)
    {
        $offensiveWords = config('offensive_words.offensive_words');
        foreach ($offensiveWords as $word) {
            if (stripos($content, $word) !== false) {
                return true;
            }
        }
        return false;
    }
}
