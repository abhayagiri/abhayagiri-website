<footer id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-2">
                <div class="btn-group" role="group">
                    <a href="{{ lp('contact') }}" class="btn btn-light">
                        <i class="fa fa-envelope"></i>
                        @lang('page.footer.contact')
                    </a>
                    <a href="{{ lp('visiting/directions') }}" class="btn btn-light">
                        <i class="fa fa-map-marker"></i>
                        @lang('page.footer.directions')
                    </a>
                    <a href="tel:+1-707-485-1630" class="btn btn-light"
                        data-toggle="popover"
                        data-placement="top"
                        data-content="+1 (707) 485-1630">
                        <i class="fa fa-phone"></i>
                        @lang('page.footer.call')
                    </a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="btn-group float-md-right">
                    <a href="https://www.youtube.com/channel/UCFAuQ5fmYYVv5_Dim0EQpVA?sub_confirmation=1" class="btn btn-light">
                        <i class="fa fa-youtube"></i>
                        @lang('page.footer.subscribe')
                    </a>
                    <a href="https://itunes.apple.com/us/podcast/abhayagiri-dhamma-talks/id600138148?mt=2" class="btn btn-light">
                        <i class="fa fa-apple"></i>
                        @lang('page.footer.subscribe')
                    </a>
                    <a href="{{ lp('contact/subscribe-to-our-email-lists') }}" class="btn btn-light">
                        <i class="fa fa-file-text"></i>
                        @lang('page.footer.newsletter')
                    </a>
                    <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-rss"></i>
                        @lang('page.footer.rss')
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="http://feed.abhayagiri.org/abhayagiri-news">
                            <i class='fa fa-bullhorn'></i>
                            @lang('page.footer.news')
                        </a>
                        <a class="dropdown-item" href="http://feed.abhayagiri.org/abhayagiri-calendar">
                            <i class='fa fa-calendar'></i>
                            @lang('page.footer.calendar')
                        </a>
                        <a class="dropdown-item" href="http://feed.abhayagiri.org/abhayagiri-talks">
                            <i class='fa fa-volume-up'></i>
                            @lang('page.footer.audio')
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="float-right mt-2">
            <small class="text-muted">
                &copy; @lang('common.abhayagiri_monastery') {{ date('Y') }}
            </small>
        </div>
    </div>
</footer>
