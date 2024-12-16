@php
    $language = session('currentLanguage')->language;
@endphp
<x-layout.main>
    <x-slot:head>
        <x-breadcrumb :sections="[['','Reinventa']]"></x-breadcrumb>
    </x-slot:head>
    <x-slot:main>
        <div class="w-full">
            <div class="dashboard-title">{{ __("dashboard.subcorpus") }}: Frame2</div>
            @include('Dashboard.subcorporaFrame2')

            <div class="dashboard-title">{{ __("dashboard.subcorpus") }}: Audition</div>
            @include('Dashboard.subcorporaAudition')

            <div class="dashboard-title">{{ __("dashboard.subcorpus") }}: Framed Multi30k</div>
            @include('Dashboard.subcorporaMulti30k')

            <div class="dashboard-title">{{__('dashboard.annotatorProfile')}}</div>
            @include('Dashboard.annotatorProfile')

        </div>
    </x-slot:main>
</x-layout.main>
