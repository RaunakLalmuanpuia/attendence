<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content=" Kai- Attendance Management System - Your solution for efficient attendance tracking.Developed by Thangzaliana, Software Developer, MSeGS.">
        <meta name="keywords" content="Mizoram, Kai, Attendance Management, System, Attendance Tracking, Government of Mizoram">
        <meta name="author" content="Thangzaliana,MSeGS">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="index, follow">
        <meta name="googlebot" content="index, follow">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Kai-Attendance Management System</title>

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="https://kai.msegs.in/assets/images/logo.png"
              type="image/x-icon">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


        <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
        <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body style="background: #f7f7f7" class="font-sans antialiased">
        @inertia
    </body>
</html>
