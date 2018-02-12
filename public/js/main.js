/*------------------------------------------------------------------------------
 Global Variables
 ------------------------------------------------------------------------------*/
//Window
var _height = window.innerHeight;
var _width = window.innerWidth;

//Gallery
var _gallery;
var gallery_index;
var gallery_title;

//Event
var _event;

//Audioplayer
var _audio;
var audio_elm;
var audio_info;
var audio_height = 0;
//Toggle
var isSearch = false;
var isNav = false;
var isAudio = false;
/*------------------------------------------------------------------------------
 Nav
 ------------------------------------------------------------------------------*/
function nav($page) {
    history.pushState({page: $page}, null, _lang["base"] + "/" + $page);
    page($page);
    //toggleNav();
}
function navSub($page, $subpage, $title) {
    history.pushState({action: "subpage", page: $page, subpage: $subpage, title: $title}, null, _lang["base"] + "/" + $page + "/" + $subpage);
    subpage($page, $subpage, $title);
}
function navEntry($page, $entry) {
    history.pushState({action: "entry", page: $page, entry: $entry}, null, _lang["base"] + "/" + $page + "/" + $entry);
    entry($page, $entry);
}
function navAlbum($album) {
    history.pushState({action: "gallery", page: 'gallery', album: $album}, null, _lang["base"] + "/gallery/" + $album);
    album($album);
}
function navEvent($id) {
    history.pushState({action: "event", page: 'calendar', event: $id}, null, _lang["base"] + "/calendar/" + $id);
    event($id);
}
function navResident($id) {
    history.pushState({action: "resident", page: 'residents', resident: $id}, null, _lang["base"] + "/community/residents/" + $id);
    resident($id);
}
/*------------------------------------------------------------------------------
 Page
 ------------------------------------------------------------------------------*/
function page($page, f) {
    f = f || "";
    $('#page').hide();
    $('#fold').hide();
    showLoading();
    $('#page').load(_lang["base"] + "/php/ajax.php?" + $.param({_page: $page, _subpage: null}), null, function() {
        $('#page').fadeIn();
        $('#fold').fadeIn();
        $('#btn-' + _page).removeClass('active');
        $('#btn-' + $page).addClass('active');

        _page = $page;
        _subpage = null;

        trackPage();
        plugin($page);
        if (typeof f === "function") {
            f();
        }
    });
}
/*------------------------------------------------------------------------------
 Subpage
 ------------------------------------------------------------------------------*/
function subpage($page, $subpage, $title) {
    if ($page != _page) {
        page($page, function() {
            subpage($page, $subpage, $title);
        });
    } else {
        // $('#' + _subpage).removeClass('active');
        $('#page').load(_lang["base"] + "/php/ajax.php?" + $.param({_page: $page, _subpage: $subpage}), null, function() {
            // $('#breadcrumb').html("" + $title + "");
            // $('#' + $subpage).addClass('active');
            // if (!isDesktop()) {
            //     scrollToElm('subpage');
            // }
            trackPage();
            plugin("subpage");
            _subpage = $subpage;
        });
    }
}
/*------------------------------------------------------------------------------
 Entry
 ------------------------------------------------------------------------------*/
function entry($page, $entry) {
    $('#content').hide();
    $('#fold').hide();
    showLoading();
    $('#page').load(_lang["base"] + "/php/ajax.php?" + $.param({_page: $page, _subpage: null, _entry: $entry}), null, function() {
        $('#page').fadeIn();
        $('#fold').fadeIn();
        $('#btn-' + _page).removeClass('active');
        $('#btn-' + $page).addClass('active');
        trackPage();
        //plugin($page);
        _page = $page;
        _subpage = null;
        _entry = $entry;
        hideLoading();
    });
}
/*------------------------------------------------------------------------------
 Album
 ------------------------------------------------------------------------------*/
function album($album) {
    $('#content').hide();
    $('#fold').hide();
    showLoading();
    $('#page').load(_lang["base"] + "/php/ajax.php?" + $.param({_page: 'gallery', _subpage: null, _type: 'Album', _album: $album}), null, function() {
        $('#page').fadeIn();
        $('#fold').fadeIn();
        $('#btn-' + _page).removeClass('active');
        $('#btn-gallery').addClass('active');
        trackPage();
        initGallery();
        _page = 'gallery';
        _subpage = null;
    });
}
/*------------------------------------------------------------------------------
 Album
 ------------------------------------------------------------------------------*/
function event($id) {

    $('#content').hide();
    $('#fold').hide();
    showLoading();
    $('#page').load(_lang["base"] + "/php/ajax.php?" + $.param({_page: 'calendar', _subpage: $id, _event: $id}), null, function() {
     $('#page').fadeIn();
        $('#fold').fadeIn();
        $('#btn-' + _page).removeClass('active');
        $('#btn-calendar').addClass('active');
        _page = 'calendar';
        _subpage = $id;
        trackPage();
        plugin(_page);
    });
}
/*------------------------------------------------------------------------------
 Resident
 ------------------------------------------------------------------------------*/
function resident($id) {
    $('#content').hide();
    $('#fold').hide();
    showLoading();
    $('#page').load(_lang["base"] + "/php/ajax.php?" + $.param({_page: 'community', _subpage: 'residents', _subsubpage: $id}), null, function() {
        $('#page').fadeIn();
        $('#fold').fadeIn();
        $('#btn-' + _page).removeClass('active');
        _page = 'resident';
        _subpage = 'residents';
        trackPage();
        hideLoading();
    });
}
/*------------------------------------------------------------------------------
 Preload
 ------------------------------------------------------------------------------*/
function preload(arrayOfImages) {
    $(arrayOfImages).each(function() {
        $('<img/>')[0].src = this;
    });
}
/*------------------------------------------------------------------------------
 ToggleNav
 ------------------------------------------------------------------------------*/
function toggleNav() {
    closeSearch();
    (!isNav) ? openNav() : closeNav();
}
function openNav() {
    $('#nav').fadeIn('fast', function() {
        $('#nav .masonic').masonry('reload');
    });
    if ($(window).width() <= 480) {
        scrollToElm('btn-mobile-container');
    }
    $('#btn-mobile-menu i').removeClass().addClass('icon-remove');
    isNav = true;
}
function closeNav() {
    $('#btn-mobile-menu i').removeClass().addClass('icon-th');
    $('#nav').fadeOut('fast');
    isNav = false;
}
function toggleSearch() {
    closeNav();
    (!isSearch) ? openSearch() : closeSearch();
}
function openSearch() {
    $('#search').fadeIn('fast');
    if ($('#search').is(":visible")) {
        $('.gsc-input').trigger('focus');
        $('.gsc-input').attr('placeholder', "");
        $('#btn-mobile-search i').removeClass().addClass('icon-remove');
    }
    if ($(window).width() <= 480) {
        scrollToElm('btn-mobile-container');
    }
    isSearch = true;
}
function closeSearch() {
    $('#search').fadeOut('fast');
    $('#search-results_wrapper').fadeOut('fast');
    $('#btn-mobile-search i').removeClass().addClass('icon-search');
    isSearch = false;
}
/*------------------------------------------------------------------------------
 Typeography
 ------------------------------------------------------------------------------*/
function toTitle(url) {
    var small = ['of', 'and', 'the'];
    url = url.split('-');
    for (x = 0; x < url.length; x++) {
        if (x != 0 && inArray(url[x], small)) {
            url[x] = lcfirst(url[x]);
        } else {
            url[x] = ucfirst(url[x]);
        }
    }
    return url.join(' ');
}
function toURL(title) {
    return title.replace(' ', '_').toLowerCase()
}
function ucfirst(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}
function lcfirst(string)
{
    return string.charAt(0).toLowerCase() + string.slice(1);
}
/*------------------------------------------------------------------------------
 Ajax Form
 ------------------------------------------------------------------------------*/
function submitForm($page) {
    //get form data
    var data = {};
    var name;
    var value;
    var mistake = false;
    var c, t;
    $.each($('#form').serializeArray(), function() {
        name = this.name;
        value = this.value;
        if ((name.indexOf("[]") < 0) && ($('#form #' + name).attr('required'))) {
            if (name === "email" && (value.indexOf('@') < 0 || value.indexOf('.') < 0)) {
                c = 'control-label alert alert-error alert-email';
                t = 'Invalid email address.';
                mistake = 'email';
            } else if (value === "") {
                c = 'control-label alert alert-error alert-field';
                t = 'Please fill out this field before submitting.';
                mistake = name;
            } else {
                if ($('#form #' + name + '-error')) {
                    $('#form #' + name + '-error').hide();
                }
            }
        } else if (name === 'g-recaptcha-response' && value === '') {
            c = 'control-label alert alert-error alert-field';
            t = 'Please fill this out before submitting.';
            mistake = name;
        }
        var index = name.indexOf('[]');
        if (index > -1) {
            name = name.substring(0, index);
            if (!(name in data)) {
                data[name] = [];
            }
            data[name].push(value);
        }
        else {
            data[name] = value;
        }
    });
    if (mistake !== false) {
        jQuery('<div/>', {id: mistake + '-error', class: 'controls'}).appendTo($('#form #' + mistake).parents('div.control-group'));
        jQuery('<div/>', {class: c, text: t}).appendTo($('#form #' + mistake + '-error'));
        scrollToElm(mistake);
        return false;
    }
    $(window).scrollTop(0, 1);
    $('#alert .alert').hide();
    $('#alert .alert-warning').show();

    var url;
    if ($page == 'request') {
        url = '/books/cart/request';
    } else {
        url = '/contact'
    }
    console.log([url, data]);
    $.post(url, data, function(data) {
        $('#alert .alert').hide();
        if (data === '1') {
            clearForm();
            if ($page === "request") {
                $('#selection').html('');
            }
            $('#alert .alert-success').show();
        } else {
            $('#alert .alert-error').show();
        }
        $(window).scrollTop(0, 1);
    });
}
function clearForm() {
    $('form').each(function() {
        this.reset();
    });
}
/*------------------------------------------------------------------------------
 Slideshow
 ------------------------------------------------------------------------------*/
function showSlideshow() {
    $("#gallery").hide();
    $('#slideshow').show();
    $("body").addClass("noscroll");
}

function hideSlideshow() {
    $("#gallery").show();
    $("#gallery").masonry('reload');
    $('#slideshow').hide();
    $("body").removeClass("noscroll");
}
function centerSlide(index) {
    height = _gallery[index][1];
    width = _gallery[index][2];
    c_height = $(window).height();
    c_width = $(window).width();
    if (width > c_width) {
        height = height * (c_width / width);
    }
    if (height < c_height) {
        margin = height / 2 * -1;
        $('#slide').css('margin-top', margin);
        $('#slide').css('padding-top', c_height / 2);
    } else {
        $('#slide').css('margin-top', 0);
        $('#slide').css('padding-top', 0);
    }
}
function loadSlide(index) {
    window.scrollTo(0, 1);
    if (index === -1) {
        index = _gallery.length - 1;
    } else if (index === (_gallery.length)) {
        index = 0;
    }
    url = _gallery[index][0];
    caption = _gallery[index][3];
    count = " (" + (index + 1) + "/" + _gallery.length + ")";
    $('#slide-caption h4').html(caption);
    $('#slide').html('<img src="' + url + '"/>');
    centerSlide(index);
    gallery_index = index;
}
/*------------------------------------------------------------------------------
 Audio Player
 ------------------------------------------------------------------------------*/
function play(elm, title, author, date, img, file) {
    if (_audio) {
        _audio.destruct();
        $(audio_elm).html("<i class='icon-play'></i> " + _lang["play"]);
        $(audio_elm).attr('onclick', "play(this, '" + audio_info.join("','") + "')");
    }
    $('#audioplayer #info-container .author').html(title);
    $('#audioplayer #info-container .title').html(author);
    $('#audioplayer #info-container .date').html(date);
    $('#audioplayer #speaker').attr('src', img);
    _audio = soundManager.createSound({
        id: 'dhammaTalk',
        url: '/media/audio/' + file
    });
    _audio.options.whileplaying = function() {
        duration(this.position);
    };
    _audio.options.whileloading = function() {
        buffer(this.bytesLoaded, this.bytesTotal);
    };
    _audio.play();
    audio_elm = elm;
    audio_info = [title, author, date, img, file];
    $(elm).html("<i class='icon-pause'></i> " + _lang["pause"]);
    $(elm).attr('onclick', 'pause(this)');
    $('#audioplayer .play').html("<i class='icon-pause'></i>");
    $('#audioplayer .play').attr('onclick', 'pause()');
    openPlayer();
    if (isMobile()) {
        scrollToElm('audioplayer');
    }
    trackPlay(file);
}

function pause() {
    _audio.pause();
    $(audio_elm).html("<i class='icon-play'></i> " + _lang["play"]);
    $(audio_elm).attr('onclick', 'resume(this, _audio)');
    $('#audioplayer .play').html("<i class='icon-play'></i>");
    $('#audioplayer .play').attr('onclick', 'resume()');
}

function resume() {
    _audio.resume();
    $(audio_elm).html("<i class='icon-pause'></i> " + _lang["pause"]);
    $(audio_elm).attr('onclick', 'pause(this)');
    $('#audioplayer .play').html("<i class='icon-pause'></i>");
    $('#audioplayer .play').attr('onclick', 'pause()');
}
function milliToHour(ms) {
    x = ms / 1000;
    seconds = x % 60;
    x /= 60;
    minutes = x % 60;
    x /= 60;
    hours = x % 24;
    seconds = (parseInt(seconds));
    minutes = parseInt(minutes);
    hours = parseInt(hours);
    seconds = (seconds < 10) ? "0" + seconds : seconds;
    minutes = (minutes < 10) ? "0" + minutes : minutes;
    hours = (hours < 10) ? "0" + hours : hours;
    return (hours + ":" + minutes + ":" + seconds);
}
function skip(pos) {
    dur = (_audio.readyState === 3) ? _audio.duration : _audio.durationEstimate;
    pos = pos * dur;
    _audio.setPosition(pos);
    duration(pos);
}
function duration(pos) {
    dur = (_audio.readyState === 3) ? _audio.duration : _audio.durationEstimate;
    prog = (pos / dur * 100) + "%";
    $('#duration').width(prog);
    $('#elapsed').html(milliToHour(pos) + " / " + milliToHour(dur));
}
function buffer(loaded, total) {
    buff = (loaded / total * 100) + "%";
    $('#buffer').width(buff);
}
function volume(pos) {
    $('#volume .bar').width(pos + "%");
    _audio.setVolume(pos);
}
function closePlayer() {
    $("#audioplayer .tab-audioplayer").removeClass('icon-chevron-down').addClass('icon-volume-up');
    $('#audioplayer .audioplayer-inner').slideUp('fast');
    $('#audioplayer').css('z-index', '999');
    isAudio = false;
}
function openPlayer() {
    $('#audioplayer').css('z-index', '99999');
    $("#audioplayer .tab-audioplayer").removeClass('icon-volume-up').addClass('icon-chevron-down');
    $('#audioplayer .audioplayer-inner').slideDown('fast');
    isAudio = true;
}
$('#time').click(function(e) {
    time = $('#time');
    pos = ((e.pageX - time.offset().left) / time.width());
    skip(pos);
});
$('#volume').click(function(e) {
    vol = $('#volume');
    pos = ((e.pageX - vol.offset().left) / vol.width()) * 100;
    volume(pos);
});
$(".tab-audioplayer").click(function() {
    if (isAudio) {
        closePlayer();
    } else {
        openPlayer();
    }
});
/*------------------------------------------------------------------------------
 BOOK REQUEST
 ------------------------------------------------------------------------------*/
function addBook(book){
    console.log(book);
    $.ajax({
        url: '/books/cart/' + book,
        type: 'POST',
        success: function(result) {
            navEntry('books', 'request');
        }
    });
}

function updateBook(book, quantity) {
    $.ajax({
        url: '/books/cart/' + book + '/' + quantity,
        type: 'PATCH',
        success: function(result) {
            navEntry('books', 'request');
        }
    });
}

function removeBook(book) {
    $.ajax({
        url: '/books/cart/' + book,
        type: 'DELETE',
        success: function(result) {
            navEntry('books', 'request');
        }
    });
}
/*------------------------------------------------------------------------------
 MISC
 ------------------------------------------------------------------------------*/
function inArray(needle, haystack) {
    var length = haystack.length;
    for (var i = 0; i < length; i++) {
        if (haystack[i] === needle)
            return true;
    }
    return false;
}
function scrollToElm(id) {
    offset = $('#' + id).offset().top
    $('html,body').animate({scrollTop: offset}, 'slow');
}
function isDesktop() {
    return $(window).width() > 768;
}
function isMobile() {
    return $(window).width() < 550;
}
function backtotop() {
    $('html,body').animate({scrollTop: 0}, 'slow');
}

/*------------------------------------------------------------------------------
 Initialization
 ------------------------------------------------------------------------------*/
/*Nav*/
$('html').click(function() {
    closeNav();
    //closeSearch();
    $('#rss,#call').popover('hide');
});
$('#btn-menu,#btn-mobile-menu').click(function(event) {
    event.stopPropagation();
    toggleNav();
});
/*Search*/
$('#btn-search,#btn-mobile-search').click(function(event) {
    event.stopPropagation();
    toggleSearch();
});
$('#search').click(function(event) {
    event.stopPropagation();
});
//$('#btn-container').animate({opacity: 1}, 500);
$('#rss,#call').click(function(event) {
    event.stopPropagation();
});
$(document).ready(function() {

    /*------------------------------------------------------------------------------
     Event Listeners
     ------------------------------------------------------------------------------*/
    /*Center Gallery*/
    $(window).resize(function() {
        if ($("#slideshow").is(":visible")) {
            centerSlide(gallery_index);
        }
        //$('#responsive-tester').html($(window).height() + " x " + $(window).width());
    });
    /*Back-to-top*/
    $('.backtotop').click(function() {
        backtotop();
        return false;
    });
    window.scrollTo(0, 1);
    //$('#responsive-tester').html($(window).height() + " x " + $(window).width());
    /*------------------------------------------------------------------------------
     popState
     ------------------------------------------------------------------------------*/
    window.onpopstate = function(event) {

        state = event.state;

        if (state) {
            switch (state.action) {
                case "entry":
                    entry(state.page, state.entry);
                    break;
                case "subpage":
                    subpage(state.page, state.subpage, state.title);
                    break;
                case "album":
                    album(state.album);
                    break;
                case "event":
                    event(state.event);
                    break;
                case "resident":
                    resident(state.resident);
                    break;
                default:
                    page(state.page);
                    break;
            }
        }
    };
}
);

// Google Analytics

function trackPage() {
    var page = window.location.pathname;
    page = page.replace(/\/$/, '');
    ga('set', 'page', page);
    ga('send', 'pageview');
}

function trackPlay(file) {
    ga('send', 'event', 'audio', 'play', file);
}

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-34323281-1', 'auto');

trackPage();

// CSRF Protection

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

function routePath(path) {
    var parts = path.split('/').filter(function(part) { return !!part });
    if (parts[0] === 'new') {
        window.location.href = path;
        return;
    }
    if (parts[0] === 'th') {
        parts.shift();
    }
    if (parts[0] === 'gallery' || parts[0] === 'talks' || parts[0] === 'contact') {
        window.location.href = path;
        return;
    }
    if (parts.length === 0) {
        nav('home');
    } else if (parts[0] === 'community' && parts[1] === 'residents' && parts[2]) {
        navResident(parts[2]);
    } else if (parts[0] === 'calendar') {
        if (parts[1]) {
            navEvent(parts[1]);
        } else {
            nav(parts[0]);
        }
    } else if (['books', 'news', 'reflections'].includes(parts[0])) {
        if (parts[1]) {
            navEntry(parts[0], parts[1]);
        } else {
            nav(parts[0]);
        }
    } else {
        if (parts[1]) {
            navSub(parts[0], parts[1]);
        } else {
            nav(parts[0]);
        }
    }
}

function switchLanguage()
{
    var path = location.pathname;
    var matches, newPath, newLangBase;
    if (matches = path.match(/^\/th(\/.*)?$/)) {
        newPath = matches[1] ? matches[1] : '/';
        // newLangBase = '';
    } else {
        newPath = '/th' + path;
        // newLangBase = '/th';
    }
    window.location.href = newPath;
    // _lang['base'] = newLangBase;
    // routePath(newPath);
    return false;
}

$('body').on('click', 'a', function (event) {
    var url = event.currentTarget.getAttribute('href');
    if (!url ||
        $(event.currentTarget).hasClass('nonav') ||
        url.match(/^\/media(\/|$)/) ||
        url.match(/^\/20(\/|$)/) ||
        url.match(/^\/(th\/)?audio(\/|$)/) ||
        url.match(/^mailto:/)) {
        // Allow the default handler.
        return;
    } else if (url[0] === '/') {
        event.preventDefault();
        if (event.ctrlKey || event.metaKey) {
            window.open(url, '_blank');
        } else {
            routePath(url);
        }
    } else {
        event.preventDefault();
        window.open(url, '_blank');
    }
});
