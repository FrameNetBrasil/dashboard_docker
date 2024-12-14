<x-layout.index>
    @include('components.layout.head')
    <div id="content">
        <div class="contentContainer ui pushable">
            <div class="menuLeft ui left vertical menu sidebar">
                @include("components.layout.menu")
            </div>
            <div class="pusher closing pusher-full">
                <main role="main" class="main">
                    {{$slot}}
                    <wt-go-top id="btnTop" label="Top" offset="64"></wt-go-top>
                </main>
            </div>
        </div>
    </div>
    <footer>
        <div class="flex justify-content-between w-full">
            <div>
                @include("components.layout.footer")
            </div>
            <div>
                {!! config('webtool.version') !!}
            </div>
        </div>

    </footer>
    <script>
        $(".menuLeft")
            .sidebar({
                context: $(".contentContainer"),
                dimPage: false,
                transition: "overlay",
                mobileTransition: "overlay",
                closable: false,
            })
            .sidebar("attach events", ".menuIcon")
            .sidebar("hide")
        ;
    </script>
</x-layout.index>
