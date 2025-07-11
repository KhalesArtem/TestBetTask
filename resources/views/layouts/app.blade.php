<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel App')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        button.secondary {
            background-color: #008CBA;
        }
        button.secondary:hover {
            background-color: #0073aa;
        }
        button.danger {
            background-color: #f44336;
        }
        button.danger:hover {
            background-color: #da190b;
        }
        .button-group {
            margin: 20px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .button-group button {
            margin-right: 0;
        }
        .link-display {
            background-color: #f0f0f0;
            padding: 15px;
            border-radius: 4px;
            word-break: break-all;
            margin: 20px 0;
        }
        .result-box {
            margin: 20px 0;
            padding: 20px;
            border: 2px solid #ddd;
            border-radius: 4px;
            min-height: 100px;
        }
        .error {
            color: #f44336;
            margin-top: 5px;
            font-size: 14px;
        }
        .history-item {
            margin: 10px 0;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        
        /* Адаптивность для мобильных устройств */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
            .button-group {
                flex-direction: column;
            }
            .button-group button {
                width: 100%;
                margin-bottom: 10px;
            }
            button {
                width: 100%;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    @yield('scripts')
</body>
</html>