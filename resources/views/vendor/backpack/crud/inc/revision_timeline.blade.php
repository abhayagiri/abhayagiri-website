@php

// HACK
//
// This is quite ugly but it will do for now.

$textFieldNames = [
    'body',
    'body_en',
    'body_th',
    'description',
    'description_en',
    'description_th',
];

$diffOptions = [];

$diffRenderer = new \Diff_Renderer_Html_Inline;

@endphp

<style>
.Differences {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        empty-cells: show;
}

.Differences thead th {
        text-align: left;
        border-bottom: 1px solid #000;
        background: #aaa;
        color: #000;
        padding: 4px;
}
.Differences tbody th {
        text-align: right;
        background: #ccc;
        width: 4em;
        padding: 1px 2px;
        border-right: 1px solid #000;
        vertical-align: top;
        font-size: 13px;
}

.Differences td {
        padding: 1px 2px;
        font-family: Consolas, monospace;
        font-size: 13px;
}

.DifferencesSideBySide .ChangeInsert td.Left {
        background: #dfd;
}

.DifferencesSideBySide .ChangeInsert td.Right {
        background: #cfc;
}

.DifferencesSideBySide .ChangeDelete td.Left {
        background: #f88;
}

.DifferencesSideBySide .ChangeDelete td.Right {
        background: #faa;
}

.DifferencesSideBySide .ChangeReplace .Left {
        background: #fe9;
}

.DifferencesSideBySide .ChangeReplace .Right {
        background: #fd8;
}

.Differences ins, .Differences del {
        text-decoration: none;
}

.DifferencesSideBySide .ChangeReplace ins, .DifferencesSideBySide .ChangeReplace del {
        background: #fc0;
}

.Differences .Skipped {
        background: #f7f7f7;
}

.DifferencesInline .ChangeReplace .Left,
.DifferencesInline .ChangeDelete .Left {
        background: #fdd;
}

.DifferencesInline .ChangeReplace .Right,
.DifferencesInline .ChangeInsert .Right {
        background: #dfd;
}

.DifferencesInline .ChangeReplace ins {
        background: #9e9;
}

.DifferencesInline .ChangeReplace del {
        background: #e99;
}
</style>

<ul class="timeline">
@foreach($revisions as $revisionDate => $dateRevisions)
  @php
    $localRevisionDate = (new \Carbon\Carbon($revisionDate))->tz('America/Los_Angeles');
  @endphp
  <li class="time-label" data-date="{{ date('Y-m-d', strtotime($revisionDate)) }}">
      <span class="bg-red">
        {{ $localRevisionDate->format(config('backpack.base.default_date_format')) }}
      </span>
  </li>

  @foreach($dateRevisions as $history)
  @php
    $localCreatedAt = (new \Carbon\Carbon($history->created_at))->tz('America/Los_Angeles');
  @endphp
  <li class="timeline-item-wrap">
    <i class="fa fa-calendar bg-default"></i>
    <div class="timeline-item">
      <span class="time"><i class="fa fa-clock-o"></i> {{ $localCreatedAt->format('h:ia') }}</span>
      @if($history->key == 'created_at' && !$history->old_value)
        <h3 class="timeline-header">{{ $history->userResponsible()?$history->userResponsible()->name:trans('backpack::crud.guest_user') }} {{ trans('backpack::crud.created_this') }} {{ $crud->entity_name }}</h3>
      @else
        <h3 class="timeline-header">{{ $history->userResponsible()?$history->userResponsible()->name:trans('backpack::crud.guest_user') }} {{ trans('backpack::crud.changed_the') }} {{ $history->fieldName() }}</h3>
        <div class="timeline-body p-b-0">
          @if (!in_array($history->fieldName(), $textFieldNames))
            <div class="row">
              <div class="col-md-6">{{ ucfirst(trans('backpack::crud.from')) }}:</div>
              <div class="col-md-6">{{ ucfirst(trans('backpack::crud.to')) }}:</div>
            </div>
            <div class="row">
              <div class="col-md-6"><div class="well well-sm" style="overflow: hidden;">{{ $history->oldValue() }}</div></div>
              <div class="col-md-6"><div class="well well-sm" style="overflow: hidden;">{{ $history->newValue() }}</div></div>
            </div>
          @else
            @php
              $a = explode("\n", (string) $history->oldValue());
              $b = explode("\n", (string) $history->newValue());
              $diff = new \Diff($a, $b, $diffOptions);
              echo $diff->Render($diffRenderer);
            @endphp
          @endif
        </div>
        <div class="timeline-footer p-t-0">
          {!! Form::open(array('url' => \Request::url().'/'.$history->id.'/restore', 'method' => 'post')) !!}
          <button type="submit" class="btn btn-primary btn-sm restore-btn" data-entry-id="{{ $entry->id }}" data-revision-id="{{ $history->id }}" onclick="onRestoreClick(event)">
            <i class="fa fa-undo"></i> {{ trans('backpack::crud.undo') }}</button>
          {!! Form::close() !!}
        </div>
      @endif
    </div>
  </li>
  @endforeach
@endforeach
</ul>

@section('after_scripts')
  <script type="text/javascript">
    $.ajaxPrefilter(function(options, originalOptions, xhr) {
        var token = $('meta[name="csrf_token"]').attr('content');

        if (token) {
              return xhr.setRequestHeader('X-XSRF-TOKEN', token);
        }
    });
    function onRestoreClick(e) {
      e.preventDefault();
      var entryId = $(e.target).attr('data-entry-id');
      var revisionId = $(e.target).attr('data-revision-id');
      $.ajax('{{ \Request::url().'/' }}' +  revisionId + '/restore', {
        method: 'POST',
        data: {
          revision_id: revisionId
        },
        success: function(revisionTimeline) {
          // Replace the revision list with the updated revision list
          $('.timeline').replaceWith(revisionTimeline);

          // Animate the new revision in (by sliding)
          $('.timeline-item-wrap').first().addClass('fadein');
          new PNotify({
              text: '{{ trans('backpack::crud.revision_restored') }}',
              type: 'success'
          });
        }
      });
  }
  </script>
@endsection

@section('after_styles')
  {{-- Animations for new revisions after ajax calls --}}
  <style>
     .timeline-item-wrap.fadein {
      -webkit-animation: restore-fade-in 3s;
              animation: restore-fade-in 3s;
    }
    @-webkit-keyframes restore-fade-in {
      from {opacity: 0}
      to {opacity: 1}
    }
      @keyframes restore-fade-in {
        from {opacity: 0}
        to {opacity: 1}
    }
  </style>
@endsection
