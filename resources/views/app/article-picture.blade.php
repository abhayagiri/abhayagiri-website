@php
    $routeName = $routeName ?? Str::plural($article->getTable()) . '.image';
    $preset = $preset ?? 'article';
@endphp
<picture class="{{ $class ?? '' }}">
    <source type="image/webp" srcset="{{
        route($routeName, [$article, $preset, 'webp', 'dpr' => 4])
    }} 4x, {{
        route($routeName, [$article, $preset, 'webp', 'dpr' => 3])
    }} 3x, {{
        route($routeName, [$article, $preset, 'webp', 'dpr' => 2])
    }} 2x, {{
        route($routeName, [$article, $preset, 'webp'])
    }} 1x">
    <img src="{{ route($routeName, [$article, $preset, 'jpg']) }}"
         alt="{{ $article->title }}">
</picture>
