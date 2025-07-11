<?php

namespace App\Http\Controllers;

use App\Exceptions\LinkNotAccessibleException;
use App\Models\Link;
use App\Services\GameService;
use Illuminate\Http\JsonResponse;
use Random\RandomException;

class GameController extends Controller
{
    private GameService $gameService;
    
    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    /**
     * @throws RandomException
     * @throws LinkNotAccessibleException
     */
    public function play(Link $link): JsonResponse
    {
        $result = $this->gameService->play($link);

        return response()->json($result);
    }

    public function history(Link $link): JsonResponse
    {
        $history = $this->gameService->getHistory($link);
        
        return response()->json($history);
    }
}
