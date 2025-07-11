@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <h1>Регистрация</h1>
    
    <form method="POST" action="{{ route('register.store') }}">
        @csrf
        
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" 
                   id="username" 
                   name="username" 
                   value="{{ old('username') }}" 
                   required>
            @error('username')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="phone_number">Phonenumber</label>
            <input type="tel" 
                   id="phone_number" 
                   name="phone_number" 
                   value="{{ old('phone_number') }}" 
                   required>
            @error('phone_number')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit">Register</button>
    </form>
@endsection