@extends('layouts.app')

@section('title', 'Ссылка создана')

@section('content')
    <h1>Ваша уникальная ссылка создана!</h1>
    
    <p>Ваша уникальная ссылка:</p>
    
    <div class="link-display">
        <a href="{{ url('/a/' . $link->token) }}" target="_blank">
            {{ url('/a/' . $link->token) }}
        </a>
    </div>
    
    <p>Ссылка действительна до: {{ $link->expires_at->format('d.m.Y H:i') }}</p>
    
    <a href="{{ route('register') }}">
        <button type="button">Создать новую ссылку</button>
    </a>
@endsection