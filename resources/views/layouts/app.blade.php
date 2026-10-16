<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'SiagaPangan')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body>

<div class="app-shell">

    @include('partials.navbar')

    <div class="container-fluid">
        <div class="row">

            @include('partials.sidebar')

            <main class="col main-content">

                @yield('content')

            </main>

        </div>
    </div>

</div>

</body>
</html>