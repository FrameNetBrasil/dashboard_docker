@php
    use \Illuminate\Foundation\Vite;
    $vite = new Vite();
    $languages = config('webtool.actions.user.4.language.4');
    $menu = config('webtool.actions.menu.4');
    $user = session('user');
@endphp
        <!DOCTYPE html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>{{config('webtool.pageTitle')}}</title>
    <meta name="description" content="Framenet Brasil 4.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet"
          type="text/css">
</head>
<body
        hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
>
<div class="w-screen h-screen">
    <header class="wt-header w-full">
        <div class="navbar">
            <div class="flex-1">
                <img src="/images/fnbr_logo_header.png" height="36" width="36" style="margin:3px">
                <div class="text-sm breadcrumbs wt-breadcrumbs">
                    <ul>
                        <li><a href="/">{{ config('webtool.pageTitle') }}</a></li>
                        <li><a href="{{ $data->currentUrl }}"
                               class="current">{{session('currentController')}}</a></li>
                    </ul>
                </div>
            </div>
            <div class="flex-none">
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost rounded-btn">
                        <x:wt::icon icon="translate"></x:wt::icon>
                        {{$languages[session('currentLanguage')->language][0]}}
                    </label>
                    <ul tabindex="0"
                        class="menu dropdown-content z-[1] p-2 shadow bg-base-100 rounded-box w-30 mt-4">
                        @foreach($languages as $l => $language)
                            <li><a href="/language/{{$l}}">{{ $language[0] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <section class="w-full">
        {{ $slot }}
    </section>

    {{
        $vite->useHotFile(base_path('web/public/hot'))
            ->useBuildDirectory('web')
            ->withEntryPoints(['src/app.js'])
    }}

    <script>
        document.body.addEventListener("notify", function (evt) {
            window.notify(evt.detail.type, evt.detail.message)
        })
    </script>

</body>
</html>
