@foreach ($current as $resident)
    @include('subpages.resident')
@endforeach

<legend>Traveling</legend>

@foreach ($traveling as $resident)
    @include('subpages.resident')
@endforeach
