<div class="media">
    <span class="pull-left">
        <img class="media-object" src="{{ $resident->image_url }}">
    </span>
    <div class="media-body">
        <h3 class="media-heading">{{ tp($resident, 'title', $lng) }}</h3>
        {!! tp($resident, 'description_html', $lng) !!}
    </div>
</div>
