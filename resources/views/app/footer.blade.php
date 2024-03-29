<nav>
    <section class="main">
        <div class="btn-group" role="group">
            <a href="{{ lp('contact') }}" class="btn btn-light">
                <i class="la la-envelope"></i>
                @lang('page.footer.contact')
            </a>
            <a href="{{ lp('visiting/directions') }}" class="btn btn-light">
                <i class="la la-map-marker"></i>
                @lang('page.footer.directions')
            </a>
            <a href="tel:+1-707-485-1630" class="btn btn-light"
                data-toggle="popover"
                data-placement="top"
                data-content="+1 (707) 485-1630">
                <i class="la la-phone"></i>
                @lang('page.footer.call')
            </a>
        </div>
    </section>
    <section class="subscriptions">
        <div class="btn-group float-md-right">
            <a href="https://www.youtube.com/channel/UCFAuQ5fmYYVv5_Dim0EQpVA?sub_confirmation=1" class="btn btn-light">
                <i class="la la-youtube"></i>
                @lang('page.footer.subscribe-youtube')
            </a>
            <a href="https://itunes.apple.com/us/podcast/abhayagiri-dhamma-talks/id600138148?mt=2" class="btn btn-light">
                <i class="la la-apple"></i>
                @lang('page.footer.subscribe-itunes')
            </a>
            <a href="{{ lp('contact/subscribe-to-our-email-lists') }}" class="btn btn-light">
                <i class="la la-file-text"></i>
                @lang('page.footer.newsletter')
            </a>
            <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">
                <i class="la la-rss"></i>
                @lang('page.footer.rss')
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="http://feed.abhayagiri.org/abhayagiri-news">
                    <i class='la la-bullhorn'></i>
                    @lang('page.footer.news')
                </a>
                <a class="dropdown-item" href="http://feed.abhayagiri.org/abhayagiri-talks">
                    <i class='la la-volume-up'></i>
                    @lang('page.footer.audio')
                </a>
            </div>
        </div>
    </section>
</nav>
<section class="copyright">
    <p>&copy; @lang('common.abhayagiri_monastery') {{ date('Y') }}</p>
</section>
