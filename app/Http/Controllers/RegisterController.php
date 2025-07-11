<?php

namespace App\Http\Controllers;

use App\Services\RegistrationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisterController extends Controller
{
    private RegistrationService $registrationService;
    
    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }
    
    public function index(): View
    {
        return view('register');
    }

    public function store(Request $request): View
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
        ]);

        $link = $this->registrationService->register(
            $validated['username'],
            $validated['phone_number']
        );

        return view('link_created', ['link' => $link]);
    }
}
