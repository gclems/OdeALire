<!DOCTYPE html>
<html lang="en">
    <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}" />

      <title>Ode à lire - @yield('title')</title>

      <!-- Fonts -->
      <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
      <link href="{{ URL::asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
        <!-- Styles -->
  		<link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
  		<link href="{{ URL::asset('css/bootstrap-theme.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ URL::asset('css/pnotify.custom.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ URL::asset('css/app.min.css') }}" rel="stylesheet" type="text/css">
      @yield('styles')
    </head>
    <body class="@yield('bodyClass')">
      @yield('appContent')
      <div class="clearfix"></div>
      <script src="{{ URL::asset('js/jquery-3.1.1.min.js') }}"></script>
      <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
      <script src="{{ URL::asset('js/pnotify.custom.min.js') }}"></script>
      <script src="{{ URL::asset('js/app.js') }}"></script>
      @yield('scripts')
    </body>
</html>
