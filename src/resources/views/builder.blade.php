<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>GraphView</title>

        <!-- DATA and CSRF Stuff -->
        <script>
            window.vuedata = {!! $schema !!}
            window.vuedata.graphic   = {};
            window.vuedata.csrfToken = '{{ csrf_token() }}';
        </script>

        <!-- Styles -->
        <link href="{{ asset('vendor/bespired/graphview/css/app.css') }}" rel="stylesheet" type="text/css">
   </head>
   <body>
        <div id='app'>
            <main-component />
        </div>
        <!-- Scripts -->
        <script src="{{ asset('vendor/bespired/graphview/js/app.js') }}"></script>
    </body>
</html>