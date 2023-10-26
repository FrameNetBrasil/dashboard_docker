@props(['label','icon','type'])
<button  {{$attributes}}>
    <x-wt::icon icon="{{$icon}}"></x-wt::icon>
    {{$label}}
</button>