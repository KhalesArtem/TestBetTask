<?php

namespace App\Http\Controllers;

use App\Exceptions\LinkNotAccessibleException;
use App\Models\Link;
use App\Services\LinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class LinkController extends Controller
{
    private LinkService $linkService;
    
    public function __construct(LinkService $linkService)
    {
        $this->linkService = $linkService;
    }

    /**
     * @throws LinkNotAccessibleException
     */
    public function show(Link $link): View
    {
        $this->linkService->validateAccess($link);

        return view('page_a', ['link' => $link]);
    }

    /**
     * @throws LinkNotAccessibleException
     */
    public function renew(Link $link): JsonResponse
    {
        $newLink = $this->linkService->renewLink($link);
        
        return response()->json([
            'new_url' => url('/a/' . $newLink->token)
        ]);
    }

    public function deactivate(Link $link): JsonResponse
    {
        $this->linkService->deactivateLink($link);

        return response()->json([
            'message' => 'Ссылка успешно деактивирована'
        ]);
    }
}
