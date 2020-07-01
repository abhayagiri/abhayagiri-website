<nav>
    <a class="btn btn-light" role="button"
       href="{{ $calendar->getLastMonthPath() }}">
        ← {{ __('calendar.previous_month' )}}
    </a>
    @if ($showMonth ?? false)
        <h2>{{ $calendar->start->isoFormat('MMMM YYYY') }}</h2>
    @endif
    <a class="btn btn-light" role="button"
       href="{{ $calendar->getNextMonthPath() }}">
       {{ __('calendar.next_month' )}} →
    </a>
</nav>
