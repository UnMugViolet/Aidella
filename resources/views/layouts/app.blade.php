<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Aidella')</title>
        
        @stack('css')

        <!-- MetaTags General -->
        <meta name="title" content="@yield('meta_title', 'Aidella - Elevage canin')">
        <meta name="description" content="@yield('meta_description', 'Aidella - Elevage canin')">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph -->
        <meta property="og:title" content="@yield('meta_title', 'Aidella - Elevage canin')">
        <meta property="og:description" content="@yield('meta_description', '')">
        <meta property="og:country-name" content="France">
        <meta property="og:image" content="/images/logo-aidella.webp">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">

        <!-- Facebook Meta Tags -->
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="@yield('meta_title', 'Aidella - Elevage canin')">
        <meta property="og:description" content="@yield('meta_description', 'Aidella - Elevage canin')">
        <meta property="og:image" content="/images/logo-aidella.webp">

        <!-- Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta property="twitter:domain" content="logma-production.com">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="@yield('meta_title', 'Aidella - Elevage canin')">
        <meta name="twitter:description" content="@yield('meta_description', 'Aidella - Elevage canin')">
        <meta name="twitter:image" content="/images/logo-aidella.webp">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app"></div>
        <script>
            window.currentComponent = "@yield('component', 'Homepage')";
            window.pageData = @yield('data', '{}');
        </script>
    </body>
    <script>
        var _mtm = window._mtm = window._mtm || [];
        _mtm.push({'mtm.startTime': (new Date().getTime()), 'event': 'mtm.Start'});
        (function() {
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src='https://matomo.rocketegg.systems/js/container_82A85JUj.js'; s.parentNode.insertBefore(g,s);
        })();
    </script>
</html>
