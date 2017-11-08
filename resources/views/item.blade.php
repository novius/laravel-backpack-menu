@if($href)
    <a href="{{ $href }}" title="" class="{{ Request::url() === $href ? 'active' : '' }}">
        {{ $name }}
    </a>
@elseif($name)
    <div>
        {{ $name }}
    </div>
@endif