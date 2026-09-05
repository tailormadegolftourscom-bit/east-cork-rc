<?php

namespace App\Support;

use App\Models\Child;

class ChildCodeNames
{
    private const ADJECTIVES = [
        'Shiny', 'Sweet', 'Brave', 'Clever', 'Swift', 'Bright', 'Bold', 'Gentle',
        'Happy', 'Curious', 'Mighty', 'Sunny', 'Silent', 'Sparkly', 'Fearless',
        'Jolly', 'Wild', 'Calm', 'Speedy', 'Lucky',
    ];

    private const COLORS = [
        'Blue', 'Red', 'Green', 'Purple', 'Golden', 'Silver', 'Crimson', 'Amber',
        'Violet', 'Turquoise', 'Coral', 'Emerald', 'Scarlet', 'Indigo', 'Rose',
        'Teal', 'Copper', 'Ruby', 'Sapphire', 'Lavender',
    ];

    private const NOUNS = [
        'Crocodile', 'Carnation', 'Dolphin', 'Falcon', 'Otter', 'Panda', 'Tiger',
        'Fox', 'Owl', 'Rabbit', 'Dragon', 'Phoenix', 'Wolf', 'Hawk', 'Turtle',
        'Penguin', 'Koala', 'Lynx', 'Comet', 'Maple',
    ];

    public static function wordLists(): array
    {
        return [
            'adjectives' => self::ADJECTIVES,
            'colors' => self::COLORS,
            'nouns' => self::NOUNS,
        ];
    }

    public static function randomCombo(): string
    {
        return self::ADJECTIVES[array_rand(self::ADJECTIVES)]
            .' '.self::COLORS[array_rand(self::COLORS)]
            .' '.self::NOUNS[array_rand(self::NOUNS)];
    }

    public static function suggestions(int $count = 3): array
    {
        $suggestions = [];

        while (count($suggestions) < $count) {
            $combo = self::randomCombo();

            if (! in_array($combo, $suggestions, true)) {
                $suggestions[] = $combo;
            }
        }

        return $suggestions;
    }

    public static function unique(): string
    {
        for ($attempt = 0; $attempt < 20; $attempt++) {
            $combo = self::randomCombo();

            if (! Child::where('public_label', $combo)->exists()) {
                return $combo;
            }
        }

        return self::randomCombo().' '.random_int(10, 99);
    }
}
