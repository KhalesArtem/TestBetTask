<?php

namespace App\Services;

use App\Exceptions\LinkNotAccessibleException;
use App\Models\Link;
use Illuminate\Support\Str;

class LinkService
{
    /**
     * @throws LinkNotAccessibleException
     */
    public function validateAccess(Link $link): void
    {
        if (!$link->isAccessible()) {
            throw new LinkNotAccessibleException($link->getAccessError());
        }
    }

    /**
     * @throws LinkNotAccessibleException
     */
    public function renewLink(Link $link): Link
    {
        $this->validateAccess($link);
        
        $link->update(['is_active' => false]);
        
        return Link::create([
            'user_id' => $link->user_id,
            'token' => Str::random(32),
            'is_active' => true,
            'expires_at' => now()->addDays(7),
        ]);
    }
    
    public function deactivateLink(Link $link): void
    {
        $link->update(['is_active' => false]);
    }
}