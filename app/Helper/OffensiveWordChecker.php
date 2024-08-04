<?php

namespace App\Helper;

class OffensiveWordChecker
{
    public static function containsOffensiveWords($content)
    {
        $offensiveWords = config('offensive_words.offensive_words');
        
        foreach ($offensiveWords as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/i';
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        return false;
    }
}
