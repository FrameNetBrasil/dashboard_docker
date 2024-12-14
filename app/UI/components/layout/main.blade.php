<x-layout.index>
    @include('components.layout.head')
    <div id="content">
        <div class="contentContainer ui pushable">
            <main role="main" class="main">
                <header>
                    {{$head}}
                </header>
                <section id="work" class="h-full w-full">
                    {{$main}}
                </section>
            </main>
        </div>
    </div>
    <footer>
        @include("components.layout.footer")
    </footer>
</x-layout.index>
