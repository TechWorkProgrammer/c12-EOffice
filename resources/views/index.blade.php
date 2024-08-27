<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T3 API Server</title>
    <link href="{{ asset('style.css') }}" rel="stylesheet">
</head>
<body class="antialiased">
<div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
    <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class=" items-center text-center pt-8 sm:justify-end">
            <div class="px-4 text-lg text-gray-500 border-gray-400">
                Build Using Laravel Framework
            </div>
        </div>
        <div class="flex items-center text-center pt-8 sm:justify-end">
            <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                Version : {{ app()->version() }}
            </div>
            <div class="ml-4 text-lg text-gray-500 tracking-wider">
                PHP : {{ phpversion() }}
            </div>
        </div>
    </div>
</div>
</body>
</html>
