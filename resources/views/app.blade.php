<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel Builder</title>
    <link rel="stylesheet" href="/vendor/tlb/css/app.css">


    @stack('styles')

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>



</head>
</html>
<body>
    <div id="app">
        <header class="main-header">
            <main-nav></main-nav>
        </header>

        @include('tlb::partials.builder_sidebar_menu')

        <div class="content-wrapper">
            <router-view></router-view>
        </div>
    </div>
    <script src="/vendor/tlb/js/app.js"></script>
    @stack('scripts')
</body>