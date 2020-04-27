@extends('layouts.app')

@section('title', $calendar->start->isoFormat('MMMM YYYY'))

@section('main')

<section id="calendar">

    @include('calendar.month-nav', [ 'showMonth' => true ])

    <div class="listing">

        <div class="days-of-week">
            @foreach ([0, 1, 2, 3, 4, 5, 6] as $dayOfWeek)
                <div class="day-of-week">
                    {{ $calendar->startOfCalendar->addDays($dayOfWeek)->isoFormat('ddd') }}
                </div>
            @endforeach
        </div>

        <div class="dates">

            @foreach ($queryCalendar->groupByDay($queryCalendar->getEvents()) as [$date, $events])
                @if ($date->dayOfWeek == 0)
                    <div class="week">
                @endif
                @include('calendar.day-events')
                @if ($date->dayOfWeek == 6)
                    </div>
                @endif
            @endforeach

        </div>

    </div>

    @include('calendar.month-nav')

</section>

@endsection
