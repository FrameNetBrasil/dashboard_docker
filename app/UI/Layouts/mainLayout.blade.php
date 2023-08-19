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
    <script src="https://unpkg.com/hyperscript.org@0.9.11"></script>
    <link href="https://cdn.jsdelivr.net/npm/quasar@2.12.4/dist/quasar.prod.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="q-app">
    <q-layout view="HHh lpR fFf">

        <q-header bordered class="wt-header" height-hint="98">
            <q-toolbar>
                <x:wt::button label="" icon="menu" @click="toogle"></x:wt::button>
                <img src="/images/fnbr_logo_header.png" height="36" width="36" style="margin:3px">
                <div class="text-sm breadcrumbs wt-breadcrumbs">
                    <ul>
                        <li><a href="/">{{ config('webtool.pageTitle') }}</a></li>
                        <li><a href="{{ $data->currentUrl }}" class="current">{{session('currentController')}}</a></li>
                    </ul>
                </div>
                <q-space></q-space>
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost rounded-btn">
                        <x:wt::icon icon="translate"></x:wt::icon>
                        Language
                    </label>
                    <ul tabindex="0" class="menu dropdown-content z-[1] p-2 shadow bg-base-100 rounded-box w-30 mt-4">
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
            </q-toolbar>
        </q-header>

        <q-drawer v-model="leftDrawerOpen" side="left" overlay elevated>
            @foreach($menu as $item)
                @if (count($item[4]) > 0)
                    <label tabindex="0" class="ml-2 mt-2 block">{{$item[0]}}</label>
                    <ul tabindex="0" class="menu z-[1] bg-base-100 w-30">
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
        </q-drawer>

        <q-page-container>
            {{ $slot }}
        </q-page-container>

    </q-layout>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(e) {
        const app = Vue.createApp({
            setup(props, {expose}) {
                const leftDrawerOpen = Vue.ref(false)
                const toogle = () => {
                    console.log('toogle');
                    leftDrawerOpen.value = !leftDrawerOpen.value
                }
                return {
                    leftDrawerOpen,
                    toogle
                }
            }
        })
        app.use(Quasar)
        app.mount('#q-app')
    });

</script>

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
<!-- Add the following at the end of your body tag -->
<script src="https://cdn.jsdelivr.net/npm/vue@3/dist/vue.global.prod.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quasar@2.12.4/dist/quasar.umd.prod.js"></script>

</body>
</html>
