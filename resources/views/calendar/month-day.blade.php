<div class="day{{
    ($calendar->inRange($date) ? '' : ' out-of-month') .
    ($calendar->isToday($date) ? ' today' : '') .
    ($events->count() ? '' : ' no-events')
}}">

    <time class="date" datetime="{{ $date->toISOString() }}"
          title="{{ $date->formatUserDate() }}"
          data-toggle="tooltip">
        <span class="date-number">{{ $date->day }}</span>
        <span class="date-text">{{ $date->isoFormat('LL') }}
    </time>

    @foreach ($events as $event)
        <a class="event" href="{{ $event->getPath() }}">
            <time datetime="{{ $event->getDate()->toISOString() }}">
                <span class="time-range">{{ $event->getTimeRange() }}</span>
                <span class="summary">{{ $event->summary }}</span>
            </time>
        </a>
    @endforeach

</div>
