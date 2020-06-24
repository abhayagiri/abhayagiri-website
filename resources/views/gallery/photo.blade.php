<img
    srcset="{{ $photo->small_url }} {{ $photo->small_width }}w,
            {{ $photo->medium_url }} {{ $photo->medium_width }}w"
    sizes="{{ $sizes }}"
    src="{{ $photo->small_url }}"
    width="{{ $photo->small_width }}" height="{{ $photo->small_height }}"
    alt="{{ tp($photo, 'caption') ?? 'Photo #' . $photo->id }}">
