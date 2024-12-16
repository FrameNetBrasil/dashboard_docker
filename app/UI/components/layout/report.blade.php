<x-layout.page>
    <header>
        {{$head}}
    </header>
    <section id="work" class="h-full w-full flex flex-row align-content-start flex-wrap">
        <div class="col-12 sm:col-12 md:col-4 lg:col-3 xl:col-3 h-full" >
            <div class="flex flex-column align-content-start h-full">
                <div class="h-3rem">
                    {{$search}}
                </div>
                <div class="flex-grow-1">
                    {{$grid}}
                </div>
            </div>

        </div>
        <div class="col-12 sm:col-12 md:col-8 lg:col-9 xl:col-9 pl-3 h-full">
            <div class="flex flex-column align-content-start h-full">
            {{$pane}}
            </div>
        </div>
    </section>
</x-layout.page>