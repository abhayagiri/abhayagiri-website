@extends('layouts.app')

@section('title', $calendar->start->isoFormat('LL'))

@section('main')

<section id="calendar">

    <h2>{{ $calendar->start->isoFormat('LL') }}</h2>

    @foreach ($calendar->filterEvents($queryCalendar->getEvents()) as $event)
        <div class="calendar-event">
            <h3>{{ $event->summary }}</h3>
            @if ($event->startDateTime)
                <p>When: {{ $event->getTimeRange() }}, {{ $event->getDate()->isoFormat('LL') }}</p>
            @endif
            @if ($event->location)
                <p>Location: {{ $event->location }}</p>
            @endif
            @if ($event->description)
                <p>Description: {!! $event->description !!}</p>
            @endif
        </div>
    @endforeach

    <p>
        <a class="btn btn-light" role="button" href="{{ $calendar->getThisMonthPath() }}">
            ⇠ {{ __('common.back_to',
                ['section' => $calendar->startOfMonth->isoFormat('MMMM YYYY')]) }}
        </a>
    </p>

</section>

@endsection
