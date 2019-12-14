@php

// Fancy Pants Diff'ing

$textFieldNames = [
    'body',
    'body_en',
    'body_th',
    'description',
    'description_en',
    'description_th',
];

@endphp

@if (!in_array($history->fieldName(), $textFieldNames))

          <div class="row">
            <div class="col-md-6">{{ mb_ucfirst(trans('backpack::crud.from')) }}:</div>
            <div class="col-md-6">{{ mb_ucfirst(trans('backpack::crud.to')) }}:</div>
          </div>
          <div class="row">
            <div class="col-md-6"><div class="alert alert-danger" style="overflow: hidden;">{{ $history->oldValue() }}</div></div>
            <div class="col-md-6"><div class="alert alert-success" style="overflow: hidden;">{{ $history->newValue() }}</div></div>
          </div>

@else
    <div class="row">
        @php
            $a = preg_split('/\R/', (string) $history->oldValue());
            $b = preg_split('/\R/', (string) $history->newValue());
            $diff = new \Diff($a, $b, []);
            $diffRenderer = new \Diff_Renderer_Html_Inline;
            echo $diff->Render($diffRenderer);
        @endphp
    </div>
@endif
