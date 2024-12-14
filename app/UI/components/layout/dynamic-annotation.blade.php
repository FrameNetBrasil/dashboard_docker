<x-layout.index-full>
    <section class="dynamicAnnotationPane">
        <header>
            <div class="grid grid-nogutter">
                <div class="col-8">
                    <h1>{{$title}}</h1>
                </div>
                <div class="col-4 actions">
                    {{$actions}}
                </div>
            </div>
        </header>
        <div class="flex-none">
            <div class="meta">
                {{$meta}}
            </div>
        </div>
        <section class="flex-grow-1 wt-dynamic-annotation">
            <div class="main flex flex-row p-0 h-full">
                <div class="left flex flex-column p-0 h-full">
                    <div class="video">
                        {{$video}}
                    </div>
                    <div class="controls">
                        {{$controls}}
                    </div>
                </div>
                <div class="right flex flex-column p-0 h-full flex-grow-1">
                    <div class="gridObjectSentence">
                        {{$grid}}
                    </div>
                    <div class="object">
                        {{$form}}
                    </div>
                </div>
            </div>
        </section>
    </section>
    {{$script}}
</x-layout.index-full>

