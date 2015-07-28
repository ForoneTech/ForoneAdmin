<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="description" content="material, material design, angular material, app, web app, responsive, responsive layout, admin, admin panel, admin dashboard, flat, flat ui, ui kit, AngularJS, ui route, charts, widgets, components" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <link rel="stylesheet" href="/libs/assets/animate.css/animate.css" type="text/css" />
    <link rel="stylesheet" href="/libs/assets/font-awesome/css/font-awesome.css" type="text/css" />
    <link rel="stylesheet" href="/libs/jquery/bootstrap/dist/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="/libs/jquery/waves/dist/waves.css" type="text/css" />

    <link rel="stylesheet" href="/styles/material-design-icons.css" type="text/css" />
    <link rel="stylesheet" href="/styles/font.css" type="text/css" />
    <link rel="stylesheet" href="/styles/app.css" type="text/css" />
    <link rel="stylesheet" href="/styles/jquery.fancybox.css" type="text/css" />

    {{--<!-- Chosen -->--}}
    <link href="/select/css/chosen/chosen.min.css" rel="stylesheet"/>
    <!-- Endless -->
    <link href="/select/css/endless.min.css" rel="stylesheet">

    <link rel="stylesheet" href="/components/humane/themes/original.css" type="text/css" />
    <link rel="stylesheet" href="/components/remodal/dist/remodal.css">
    <link rel="stylesheet" href="/components/remodal/dist/remodal-default-theme.css">

    <style>
        input {
            font-size: 16px;
        }

        .float-label .md-input ~ label {
            font-size: 1.2em;
        }

        .searchDiv {
            padding-left:0px;
        }

        @media (min-width: 480px) {
            .searchDiv{
                float: right;
            }
        }
    </style>

    <script>
        var init = [];
        window.onload = function () {
            init.forEach(function (f) {
                f();
            });
        };
    </script>

    @yield('css')

</head>
<body ng-app="app">
<div class="app" ui-view ng-controller="AppCtrl">

    @yield('app')

</div>

<script src="/libs/jquery/jquery/dist/jquery.js"></script>
<script src="/scripts/jquery.fancybox.pack.js"></script>
<script src="/libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
<script src="/libs/jquery/waves/dist/waves.js"></script>

<script src="/scripts/ui-load.js"></script>
<script src="/scripts/ui-jp.config.js"></script>
<script src="/scripts/ui-jp.js"></script>
<script src="/scripts/ui-nav.js"></script>
<script src="/scripts/ui-toggle.js"></script>
<script src="/scripts/ui-waves.js"></script>


{{--<!-- Chosen -->--}}
<script src='/select/js/chosen.jquery.min.js'></script>
{{--<!-- Endless -->--}}
<script src="/select/js/endless_form.js"></script>

<script src="/components/humane/humane.min.js"></script>
<script src="/components/remodal/dist/remodal.min.js"></script>

@yield('js')

<script>
    $(function(){

        $(document).on('blur', 'input, textarea', function (e) {
            $(this).val() ? $(this).addClass('has-value') : $(this).removeClass('has-value');
        });

    });
</script>

@if (count($errors) > 0)
    <script>
    @foreach ($errors->all() as $error)
    humane.log('{{ $error }}');
    @endforeach
    </script>
@endif

</body>
</html>
