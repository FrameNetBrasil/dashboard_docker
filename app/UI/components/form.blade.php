<form id="{{$id}}" name="{{$id}}" {{$attributes}} class="ui form">
    <div class="ui card h-full w-full mb-2">
        @if($title != '')
            <div class="flex-grow-0 content h-4rem bg-gray-100">
                <div class="flex flex align-items-center justify-content-between">
                    <div><h3 class="ui header line-height-4">{{$title}}</h3></div>
                </div>
            </div>
        @endif
        <div class="flex-grow-1 content bg-white">
            {{$fields}}
        </div>
        <div class="flex-grow-0 content h-4rem bg-gray-100">
            {{$buttons}}
        </div>
    </div>
</form>
