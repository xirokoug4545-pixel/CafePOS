<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard</title>
    </head>
    <body>
        <form action="{{ route('logout') }}" method="POST">
            <button type="submit">Logout</button>
        </form>
    </body>
</html>