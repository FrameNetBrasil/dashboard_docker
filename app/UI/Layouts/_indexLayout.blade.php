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
    <sl-drawer label="Drawer" placement="start" class="drawer-placement-start">
        This drawer slides in from the start.
        <sl-button slot="footer" variant="primary">Close</sl-button>
    </sl-drawer>

    <section class="w-full h-full">
        {{ $slot }}
        <sl-button>Open Drawer</sl-button>
    </section>

    <!--
    <aside>
        <div class="drawer">
            <input id="my-drawer" type="checkbox" class="drawer-toggle"/>
            <div class="drawer-content">
                <header class="wt-header w-full">
                    <div class="navbar">
                        <div class="flex-1">
                            <label for="my-drawer" class="btn btn-ghost drawer-button">
                                <x:wt::icon icon="menu"></x:wt::icon>
                            </label>
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
                                    Language
                                </label>
                                <ul tabindex="0"
                                    class="menu dropdown-content z-[1] p-2 shadow bg-base-100 rounded-box w-30 mt-4">
                                    @foreach($languages as $language)
                                        <li><a>{{ $language[0] }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            @if (is_null($user))
                                <x:wt::button
                                        label="Login"
                                        icon="login"
                                        class="btn btn-ghost"
                                        hx-get="/login"
                                ></x:wt::button>
                            @else
                                <div class="dropdown dropdown-end">
                                    <x-wt::avatar tabindex="0" :letter="substr($user['email'],0,1)"></x-wt::avatar>
                                    <ul tabindex="0"
                                        class="menu dropdown-content z-[1] p-2 shadow bg-base-100 rounded-box w-52 mt-4">
                                        <li><a>{{$user['email']}}</a></li>
                                        <li><a href="/main/profile">Profile</a></li>
                                        <li><a href="/logout">Logout</a></li>
                                    </ul>
                                </div>

                            @endif
                        </div>
                    </div>
                </header>
                <section class="w-full h-full">
                    {{ $slot }}
                </section>
            </div>
            <div class="drawer-side">
                <label for="my-drawer" class="drawer-overlay"></label>
                <ul class="menu p-4 w-80 h-full bg-base-200 text-base-content">
                    @foreach($menu as $item)
                        @if (count($item[4]) > 0)
                            <ul tabindex="0" class="menu z-[1] bg-base-100 w-30 mb-2">
                                <label tabindex="0" class="ml-2 mt-2 block">{{$item[0]}}</label>
                                @foreach($item[4] as $subitem)
                                    <li>
                                        <a href="{{$subitem[1]}}">
                                            <x:wt::icon :name="$subitem[2]"></x:wt::icon>
                                            {{ $subitem[0] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                        @endif
                    @endforeach

                </ul>
            </div>
        </div>
    </aside>
</div>
-->
{{
    $vite->useHotFile(base_path('web/public/hot'))
        ->useBuildDirectory('web')
        ->withEntryPoints(['src/app.js'])
}}

<script>
    document.body.addEventListener("notify", function (evt) {
        window.notyf.open({
            type: evt.detail.type,
            message: evt.detail.message
        });
    })
</script>
    <script>
        const drawer = document.querySelector('.drawer-placement-start');
        const openButton = drawer.nextElementSibling;
        const closeButton = drawer.querySelector('sl-button[variant="primary"]');

        openButton.addEventListener('click', () => drawer.show());
        closeButton.addEventListener('click', () => drawer.hide());
    </script>

</body>
</html>
