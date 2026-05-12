<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class RecipeHubExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('time_ago', [$this, 'timeAgo']),
            new TwigFilter('cooking_time_format', [$this, 'cookingTimeFormat']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('difficulty_stars', [$this, 'difficultyStars']),
        ];
    }

    public function timeAgo(\DateTimeInterface $date): string
    {
        $now  = new \DateTime();
        $diff = $now->diff($date);

        if ($diff->days === 0)    return "aujourd'hui";
        if ($diff->days === 1)    return 'il y a 1 jour';
        if ($diff->days < 30)     return 'il y a ' . $diff->days . ' jours';
        if ($diff->m === 1)       return 'il y a 1 mois';
        if ($diff->m < 12)        return 'il y a ' . $diff->m . ' mois';
        if ($diff->y === 1)       return 'il y a 1 an';
        return 'il y a ' . $diff->y . ' ans';
    }

    public function cookingTimeFormat(int $minutes): string
    {
        if ($minutes < 60) return $minutes . 'min';
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return $m === 0 ? $h . 'h' : $h . 'h' . $m;
    }

    public function difficultyStars(string $difficulte): string
    {
        return match($difficulte) {
            'facile'    => '⭐',
            'moyen'     => '⭐⭐',
            'difficile' => '⭐⭐⭐',
            default     => ''
        };
    }
}
