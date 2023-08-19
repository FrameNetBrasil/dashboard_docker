@php
    $currentTab = session('currentTab') ?? 'report';
@endphp
<x-wt::indexLayout :data="$data">
    <x-wt::browserLayout>
        <x-slot:menu>
            <div class="tabs">
                <a href="/frame/main"
                   class="tab tab-bordered {{$currentTab == 'report' ? 'tab-active' : ''}}">Report</a>
                <a href="/frame/grapher" class="tab tab-bordered {{$currentTab == 'grapher' ? 'tab-active' : ''}}">Grapher</a>
            </div>
        </x-slot:menu>
        <x-slot:search>
                <div class="flex flex-row py-2">
                    <!--
                <x-wt::textfield placeholder="Search Frame"></x-wt::textfield>
                <x-wt::textfield placeholder="Search FE"></x-wt::textfield>
                <x-wt::textfield placeholder="Search LU"></x-wt::textfield>
                -->
                    <h3>
                        Search Frame
                        <span class="htmx-indicator">
    Searching...
   </span>
                    </h3>
                    <div style="line-height:32px;"><i class="material-icons info" aria-hidden="true" role="img" style="font-size:20px;vertical-align: middle">info</i>teste msg</div>
                    <x-wt::searchfield
                            id="frame"
                            placeholder="Search Frame"
                            hx-post="/frame/search"
                            hx-trigger="keypress[enter], search"
                            hx-target="#search-results"
                            hx-indicator=".htmx-indicator"
                    ></x-wt::searchfield>
                </div>
        </x-slot:search>
        <x-slot:grid>
            <table class="table">
                <tbody id="search-results">
                </tbody>
            </table>
        </x-slot:grid>
        <!--
        <template #grid>
            <div v-if="props.dataType === 'frame'" class="row">
                <FrameGrid :dataList="props.dataList"></FrameGrid>
            </div>
            <div v-if="props.dataType === 'fe'" class="row">
                <FEGrid :dataList="props.dataList"></FEGrid>
            </div>
            <div v-if="props.dataType === 'lu'" class="row">
                <LUGrid :dataList="props.dataList"></LUGrid>
            </div>
        </template>
        -->
    </x-wt::browserLayout>
</x-wt::indexLayout>
