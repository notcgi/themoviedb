<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
        <style>
            .card-columns {
                column-count: 6;
            }
        </style>
    </head>
    <body>
    <header>
    </header>
    <div id="app">
        <film-filter></film-filter>
    </div>
    <footer></footer>
    <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
