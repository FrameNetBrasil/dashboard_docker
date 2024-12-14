@props([
    'icon' => '',
    'name' => ''
])
<i {{ $attributes->merge(['class' => 'material-icons ' . $name]) }} aria-hidden="true" role="img" style="font-size:16px">{{$icon}}</i>