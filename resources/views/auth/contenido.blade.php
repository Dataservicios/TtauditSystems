<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Ttaudit Systems</title>
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <script>
      window.Laravel = {!! json_encode([
			'csrfToken' => csrf_token(),
		]) !!};
  </script>

</head>
<body class="app flex-row align-items-center   pace-done pace-done">

  <div class="container">
    @yield('login')
  </div>

  <!-- Bootstrap and necessary plugins -->
  <script src="js/app.js"></script>

</body>
</html>