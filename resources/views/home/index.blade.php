@extends('layouts.app')

@section('main')

<section id="home">

    <section class="first">

        <section class="news">

            <h2><i class="la la-bullhorn"></i> {{ __('home.news') }}</h2>

            @foreach ($newsItems as $news)

                <article>

                    <h3>
                        <a href="{{ $news->path }}">{{ $news->title }}</a>
                    </h3>

                    <p class="body">
                        {!! \App\Util::abridge( $news->bodyHtml, 400) !!}
                    </p>

                    <div class="posted">
                        {{ __('common.posted') }}: @date($news->posted_at)
                    </div>

                </article>

            @endforeach

            <a class="more btn btn-light" role="button" href="{{ lp(route('news.index', null, false)) }}">
                {{ __('home.more_news') }}
            </a>

        </section>

        <section class="calendar">

            <h2><i class="la la-calendar"></i> {{ __('home.calendar') }}</h2>

            @foreach ($calendar->groupByDay($calendar->getEvents()) as [$date, $events])
                @include('calendar.day-events')
            @endforeach

            <a class="more btn btn-light" role="button" href="{{ lp(route('calendar.index', null, false)) }}">
                {{ __('home.view_full_calendar') }}
            </a>

        </section>

    </section>

    <section class="second">

        <section class="reflections">

            <h2><i class="la la-leaf"></i> {{ __('home.reflections') }}</h2>

            @if ($reflection)
                <article>
                    <h3>
                        <a href="{{ $reflection->path }}">{{ $reflection->title }}</a>
                    </h3>
                    <p class="author">{{ $reflection->author->title }}</p>
                    <p class="date">@date($reflection->posted_at)</p>
                    <p class="body">
                        {!! \App\Util::abridge($reflection->bodyHtml, 600) !!}
                    </p>
                </article>
            @endif

            <a class="more btn btn-light" role="button" href="{{ lp(route('reflections.index', null, false)) }}">
                {{ __('home.more_reflections') }}
            </a>

        </section>

        <section class="talks">

            <h2><i class="la la-volume-up"></i> {{ __('home.talks') }}</h2>

            @if ($talk)
                <article>
                    <h3>
                        <a href="{{ $talk->path }}">{{ tp($talk, 'title') }}</a>
                    </h3>
                    <p class="author">{{ $talk->author->title }}</p>
                    <p class="date">@date($talk->posted_at)</p>
                    <p class="body">
                        {!! \App\Util::abridge($talk->bodyHtml, 300) !!}
                    </p>
                </article>
            @endif

            <a class="more btn btn-light" role="button" href="{{ lp(route('talks.index', null, false)) }}">
                {{ __('home.more_talks') }}
            </a>

        </section>

    </section>

</section>

@endsection
