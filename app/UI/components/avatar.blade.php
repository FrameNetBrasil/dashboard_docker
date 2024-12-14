@props([
    'letter' => '',
])
<div class="btn btn-circle btn-ghost w-7" {{$attributes}}>
    <div class="avatar placeholder ">
        <div class="bg-neutral-focus text-neutral-content rounded-full w-7">
            <span>{{ucfirst($letter)}}</span>
        </div>
    </div>
</div>