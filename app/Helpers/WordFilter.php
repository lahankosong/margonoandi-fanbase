<?php

namespace App\Helpers;

class WordFilter
{
    // Daftar kata kasar (Indonesia + beberapa Inggris). Dicocokkan utuh (word boundary).
    protected static array $words = [
        'anjing', 'anjg', 'anjir', 'asu', 'bangsat', 'bangsad', 'bajingan', 'bangke',
        'bacot', 'babi', 'bego', 'bgst', 'goblok', 'tolol', 'kontol', 'memek', 'ngentot',
        'ngewe', 'jancok', 'jancuk', 'jancik', 'pepek', 'pukimak', 'pukima', 'sialan',
        'kampret', 'keparat', 'tai', 'taik', 'taek', 'brengsek', 'kunyuk', 'jembut',
        'peler', 'lonte', 'pelacur', 'bispak', 'perek', 'kontl', 'mmk',
        'fuck', 'fucking', 'shit', 'bitch', 'asshole', 'dick', 'pussy', 'bastard',
    ];

    public static function clean(?string $text): string
    {
        $text = (string) $text;
        foreach (self::$words as $w) {
            $text = preg_replace_callback(
                '/\b' . preg_quote($w, '/') . '\b/iu',
                function ($m) {
                    $s = $m[0];
                    return mb_substr($s, 0, 1) . str_repeat('*', max(1, mb_strlen($s) - 1));
                },
                $text
            );
        }
        return $text;
    }

    public static function contains(?string $text): bool
    {
        $text = (string) $text;
        foreach (self::$words as $w) {
            if (preg_match('/\b' . preg_quote($w, '/') . '\b/iu', $text)) return true;
        }
        return false;
    }
}
