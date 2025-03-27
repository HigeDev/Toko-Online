<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <!-- Jquery UI -->
    <link type="text/css" href="plugins/jqueryui/jquery-ui.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/views/themes/tokoonline/assets/css/main.css', 'resources/views/themes/tokoonline/assets/plugins/jqueryui/jquery-ui.css', 'resources/views/themes/tokoonline/assets/js/main.js', 'resources/views/themes/tokoonline/assets/plugins/jqueryui/jquery-ui.min.js']);

    <title>IndoToko: Official Site</title>
</head>

<body>
    @include('themes.tokoonline.shared.header');
    @yield('content');
    @include('themes.tokoonline.shared.footer');

</body>

</html>
