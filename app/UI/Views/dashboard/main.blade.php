@php
$language = session('currentLanguage')->language;
@endphp
<x-wt::indexLayout :data="$data">
    <div class="w-full p-2">
        <div class="dashboard-title">{{ __("dashboard.subcorpus") }}: Frame2</div>
        @include('dashboard.subcorporaFrame2')

        <div class="dashboard-title">{{ config("webtool.dashboard.{$language}.subcorpus") }}: Audition</div>
        @include('dashboard.subcorporaAudition')

        <div class="dashboard-title">{{ config("webtool.dashboard.{$language}.subcorpus") }}: Framed Multi30k</div>
        @include('dashboard.subcorporaMulti30k')

        <div class="dashboard-title">{{ config("webtool.dashboard.{$language}.annotatorProfile") }}</div>
        @include('dashboard.annotatorProfile')

    </div>

    <script type="application/javascript">

    </script>
</x-wt::indexLayout>