<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel Builder</title>

    <script type="text/javascript" src="/vendor/tlb/js/builder.js" />

    @stack('styles')

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>

</head>
</html>
<body>
    <h2>Builder</h2>
    <div id="app"></div>

    @stack('scripts')
</body>