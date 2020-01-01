@if (\Request::session()->has('message'))
    <div class="container">
        <div class="row">
            <div class="mx-auto alert alert-primary alert-dismissible fade show" role="alert">
                {{ \Request::session()->get('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
@endif
