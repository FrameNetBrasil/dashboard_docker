@php
    $currentLanguage = session('currentLanguage');
    $languages = config('webtool.user')[3]['language'][3];
@endphp
<header id="head" class="flex justify-content-between">
    <div class="flex align-items-center ">
        <div class="headApp">
            <a href="/">
                <span>{!! config('webtool.headerTitle') !!}</span>
            </a>
        </div>
    </div>
    <div class="flex align-items-center justify-content-end pr-1 h-full">
        <div id="menuLanguage" class="ui dropdown pointing top left pr-3">
            {!! $currentLanguage->description !!}<i class="dropdown icon"></i>
            <div class="menu">
                @foreach($languages as $language)
                    <div class="item" hx-get="{{$language[1]}}" hx-trigger="click">{{$language[0]}}</div>
                @endforeach
            </div>
        </div>
    </div>
</header>
<script>
    $(function() {
        $("#menuLanguage").dropdown();
    });
</script>
