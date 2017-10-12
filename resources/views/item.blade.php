@if($href)
    <a href="{{ $href }}">{{ $name }}</a>
@elseif($name)
    {{ $name }}
@endif