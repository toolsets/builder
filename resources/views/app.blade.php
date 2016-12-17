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

        <aside class="main-sidebar">
            <ul class="icon-menu">
                <li>
                    <router-link to="/database">
                        <i class="fa fa-database" aria-hidden="true"></i>
                        <div>
                            <small>Database</small>
                        </div>
                    </router-link>
                </li>
                <li>
                    <router-link to="/models">
                        <i class="fa fa-connectdevelop" aria-hidden="true"></i>
                        <div>
                            <small>Models</small>
                        </div>
                    </router-link>
                </li>
                <li>
                    <router-link to="/forms">
                        <i class="fa fa-wpforms" aria-hidden="true"></i>
                        <div>
                            <small>Forms</small>
                        </div>
                    </router-link>
                </li>
                <li>
                    <router-link to="/modules">
                        <i class="fa fa-puzzle-piece" aria-hidden="true"></i>
                        <div>
                            <small>Modules</small>
                        </div>
                    </router-link>
                </li>
                <li>
                    <router-link to="/permissions">
                        <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                        <div>
                            <small>Permissions</small>
                        </div>
                    </router-link>
                </li>
                <li>
                    <router-link to="/localization">
                        <i class="fa fa-globe" aria-hidden="true"></i>
                        <div>
                            <small>Localization</small>
                        </div>
                    </router-link>
                </li>
            </ul>
        </aside>

        <div class="content-wrapper">
            <router-view></router-view>
        </div>
    </div>
    <script src="/vendor/tlb/js/app.js"></script>
    @stack('scripts')
</body>