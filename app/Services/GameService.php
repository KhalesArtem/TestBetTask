<?php

namespace App\Services;

use App\Exceptions\LinkNotAccessibleException;
use App\Models\Link;
use App\Models\History;
use Illuminate\Support\Collection;
use Random\RandomException;

class GameService
{
    private const int MIN_NUMBER = 1;
    private const int MAX_NUMBER = 1000;
    
    private const int WIN_THRESHOLD_HIGH = 900;
    private const int WIN_THRESHOLD_MEDIUM = 600;
    private const int WIN_THRESHOLD_LOW = 300;
    
    private const float WIN_RATE_HIGH = 0.7;
    private const float WIN_RATE_MEDIUM = 0.5;
    private const float WIN_RATE_LOW = 0.3;
    private const float WIN_RATE_BASE = 0.1;
    
    private RandomNumberGenerator $rng;
    
    public function __construct(RandomNumberGenerator $rng)
    {
        $this->rng = $rng;
    }

    /**
     * @throws RandomException
     * @throws LinkNotAccessibleException
     */
    public function play(Link $link): array
    {
        $this->validateLinkAccess($link);
        
        $number = $this->rng->generate(self::MIN_NUMBER, self::MAX_NUMBER);
        $result = $this->determineResult($number);
        $winAmount = $this->calculateWinAmount($number, $result);
        
        History::create([
            'link_id' => $link->id,
            'random_number' => $number,
            'result' => $result,
            'win_amount' => round($winAmount, 2),
        ]);
        
        return [
            'random_number' => $number,
            'result' => $result,
            'win_amount' => round($winAmount, 2),
        ];
    }
    
    private function determineResult(int $number): string
    {
        return ($number % 2 === 0) ? 'Win' : 'Lose';
    }
    
    private function calculateWinAmount(int $number, string $result): float
    {
        if ($result !== 'Win') {
            return 0;
        }
        
        if ($number > self::WIN_THRESHOLD_HIGH) {
            return $number * self::WIN_RATE_HIGH;
        }
        
        if ($number > self::WIN_THRESHOLD_MEDIUM) {
            return $number * self::WIN_RATE_MEDIUM;
        }
        
        if ($number > self::WIN_THRESHOLD_LOW) {
            return $number * self::WIN_RATE_LOW;
        }
        
        return $number * self::WIN_RATE_BASE;
    }
    
    public function getHistory(Link $link, int $limit = 3): Collection
    {
        return $link->history()
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get(['random_number', 'result', 'win_amount', 'created_at']);
    }

    /**
     * @throws LinkNotAccessibleException
     */
    private function validateLinkAccess(Link $link): void
    {
        if (!$link->isAccessible()) {
            throw new LinkNotAccessibleException($link->getAccessError());
        }
    }
}