/*------------------------------------------------------------------------------
 Global
 ------------------------------------------------------------------------------*/
var oTable;
var sTable;
/*------------------------------------------------------------------------------
 Plugin
 ------------------------------------------------------------------------------*/
function plugin(page) {

    switch (page) {
        /*News,Books,Audio,Reflections*/
        case "news":
        case "books":
        case "reflections":
        case "audio":
        case "construction":
            !!_subpage ? hideLoading() : initDataTables(page);
            break;
        case "calendar":
            !!_subpage ? getEvent(_subpage) : initFullCalendar();
            break;
        case "gallery":
            initGallery();
            break;
        case "subpage":
                hideLoading();
            break;
        case "home":
               getLatestEvents();
            break;
        case "event":
                break;
        default:
            hideLoading();
            break;
    }
}
/*------------------------------------------------------------------------------
 Datatables
 ------------------------------------------------------------------------------*/
function initSearch() {
    sTable = $('#search-results').dataTable({
        "sDom": "<'row-fluid'>t<'row-fluid'<'span12 center'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sProcessing": "<i class='icon-spinner icon-spin'></i>"
        },
        "bServerSide": true,
        "aaSorting": [],
        "bLengthChange": false,
        "sAjaxSource": "/php/search.php",
        "fnInitComplete": function() {
            $('#search input').change(function() {
                val = $(this).val();
                (val === "") ? $('#search-results_wrapper').fadeOut('fast') : $('#search-results_wrapper').fadeIn('fast');
                sTable.fnFilter(val);
            });
            $('#search input').keyup(function() {
                val = $(this).val();
                (val === "") ? $('#search-results_wrapper').fadeOut('fast') : $('#search-results_wrapper').fadeIn('fast');
                sTable.fnFilter(val);
            });
            $('#search #search-button').click(function() {
                val = $('#search input').val();
                $('#search-results_wrapper').fadeIn('fast');
                sTable.fnFilter(val);
            });
            $('#search .dataTables_paginate').click(
                    function() {
                        scrollToElm('search');
                        return false;
                    });
            hideLoading();
        }
    });
}
function initDataTables(page) {
    showLoading();
    oTable = $('#datatable').dataTable({
        "sDom": "<'row-fluid'>t<'row-fluid'<'span12 center'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sProcessing": "<i class='icon-spinner icon-spin'></i>",
            "oPaginate": {
                "sNext": _lang['next'],
                "sPrevious": _lang['prev']
            }
        },
        "bServerSide": true,
        "aaSorting": [],
        "bLengthChange": false,
        "sAjaxSource": _lang['base'] + "/php/datatables.php",
        "fnServerData": function(sSource, aoData, fnCallback) {
            aoData.push({
                "name": "page",
                "value": page
            });
            $.getJSON(sSource, aoData, function(json) {
                fnCallback(json)
            });

        },
        "fnInitComplete": function() {

            $('#filter-search input').attr("placeholder", _lang["filter"]);
            $('#filter-search input').change(function() {
                oTable.fnFilter($(this).val());
            });
            $('#filter-search input').keyup(function() {
                oTable.fnFilter($(this).val());
            });
            $('#filter-category .option').click(function() {
                elm = $(this);
                elm.parent().siblings('.active').removeClass('active');
                elm.parent().addClass('active');
                val = elm.html();
                oTable.fnFilter(val, 0);
		  $('#filter-category .dropdown-toggle').html(val + " <i class='icon icon-caret-down'></i>");
            });
            $('#filter-language .option').click(function() {
                elm = $(this);
                elm.parent().siblings('.active').removeClass('active');
                elm.parent().addClass('active');
                val = elm.html();
                oTable.fnFilter(val, 1);
 		  $('#filter-language .dropdown-toggle').html(val + " <i class='icon icon-caret-down'></i>");
            });
            $('#datatable_wrapper .dataTables_paginate').click(
                    function() {
                        scrollToElm('breadcrumb-container');
                        return false;
                    });
            //initBackToTop();
            hideLoading();
        }
    });
}
/*------------------------------------------------------------------------------
 Back-top-top
 ------------------------------------------------------------------------------
 function initBackToTop() {
 if ($('#content').width() <= 768) {
 $('.backtotop').click(function() {
 $('html,body').animate({scrollTop: 0}, 'slow');
 return false;
 });
 } else {
 $('.backtotop').hide();
 }
 }
  /*------------------------------------------------------------------------------
 Google Calendar
 ------------------------------------------------------------------------------*/
 var googleApiKey = "AIzaSyAOGdSYLTJ9qlplQJXx3BqPXt6yAcYIvXA";
 var googleCalendarId = "abhayagiri.org_2tr1cpnhbe4i5cria1l6ae8si8%40group.calendar.google.com";
 var googleCalendarUrl = "https://www.googleapis.com/calendar/v3/calendars/";

function getLatestEvents(){

    var start = moment();
    var end = moment().add(1, 'M');
    var url = googleCalendarUrl + googleCalendarId + '/events';

   $.get(url,
    {
        key: googleApiKey,
        timeMin: start.format(),
        timeMax: end.format(),
        orderBy: "startTime",
        singleEvents: true,
        maxResults: 4
    },
    function( data ) {

      var eventList = "";
      var events = [];

      $.each(data.items, function(i, entry) {
            events.push({
                id: entry.id,
                title: entry.summary,
                start: entry.start.dateTime || entry.start.date, // try timed. will fall back to all-day
                end: entry.end.dateTime || entry.end.date, // same
                url: entry.htmlLink,
                location: entry.location,
                description: entry.description
            });
        });

      for (i = 0; i < events.length; i++) {

            //Local Variable
            var e = events[i];
            var id = e.id;
            var start = moment(e.start).format("LL");

            //Event List
            eventList += '<div class="event-title">';
            eventList += "<div >" + start + "</div>";
            eventList += '<a class="title" onclick="navEvent(\'' + id + "');return false\">" + e.title + "</a>";
            eventList += "</div><br/>";
      }

      $('#latest-event-list').html(eventList);
        hideLoading();
    });
}

function getEvent(eventId){
   var url = googleCalendarUrl + googleCalendarId + '/events/' + eventId;
   $.get(url,
    {
        key: googleApiKey
    },
    function( event ) {

      var eventDetails = "";
          eventDetails += '<div class="title">' + event.summary + "</div><br><br>";
          eventDetails += "<dt>When<dt><dd>" + moment(event.start.date ? event.start.date : event.start.dateTime).format("LL") + "</dd>";

          if(!!event.location){
                eventDetails += "<dt>Location</dt><dd>" + event.location + "";
                eventDetails += '<a target="_blank" href="http://maps.google.com/maps?q=' + event.location.replace(/\n/g, "+") + '"> (Map)</a></dd>';
          }

          if(!!event.description){
                eventDetails += "<dt>Description</dt><dd>" + event.description.replace(/\n/g, "<br />") + "</dd>";
          }

      $('#breadcrumb').html(event.summary);
      $('#event-details').html(eventDetails);
      hideLoading();

    });
}
 /*------------------------------------------------------------------------------
 Full Calendar
 ------------------------------------------------------------------------------*/
function initFullCalendar() {
    $("#calendar").fullCalendar({
        header: {
            left: "",
            center: "",
            right: "prev title next"
        },
        buttonText: {
            //prev: '<i class="icon icon-arrow-left"></i>',
            //next: '<i class="icon icon-arrow-right"></i>'
        }, eventRender: function(event, element) {
            var icon;
            if (event.title.indexOf('Moon') > 0) {
                if (event.title.indexOf('New Moon') > -1) {
                    icon = 'icon-circle';
                } else if (event.title.indexOf('Half Moon') > -1) {
                    icon = 'icon-adjust';
                } else if (event.title.indexOf('Full Moon') > -1) {
                    icon = 'icon-circle-blank';
                }
                element.find(".fc-event-title").before($("<span class=\"fc-event-icons\"></span>").html("<i class='icon " + icon + "'></i> "));
            }
        },
        googleCalendarApiKey: 'AIzaSyAOGdSYLTJ9qlplQJXx3BqPXt6yAcYIvXA',
        eventSources: [
            //"https://www.google.com/calendar/feeds/abhayagiri.org_2tr1cpnhbe4i5cria1l6ae8si8%40group.calendar.google.com/public/full"
            "https://www.googleapis.com/calendar/v3/calendars/abhayagiri.org_2tr1cpnhbe4i5cria1l6ae8si8%40group.calendar.google.com/events"
        ],
        eventClick: function(event) {
            if(event.url){
                scrollToElm(event.id);
                return false;
            }
        },
        loading: function(success) {
            if (!success) {
                hideLoading();
            }
        }
    });
}
/*------------------------------------------------------------------------------
 Gallery
 ------------------------------------------------------------------------------*/
function initGallery() {
    $(function() {
        var $container = $('#gallery');
        $container.imagesLoaded(function() {
            $container.masonry({
                itemSelector: '.brick',
                isFitWidth: true
            });
            $container.css('visibility', 'visible');
            hideLoading();
        });
    });
}
/*------------------------------------------------------------------------------
 Masonry
 ------------------------------------------------------------------------------*/
function initMasonry() {
    $(function() {
        var $container = $('.masonic');
        $.when($container.masonry({
            itemSelector: '.brick',
            isFitWidth: true
        })).then(function() {
            if (!isMobile()) {
                $('#btn-container').fadeTo("fast", 1);
            }
        });
    });
}

/*------------------------------------------------------------------------------
 Images Loaded
 ------------------------------------------------------------------------------*/
$.fn.imagesLoaded = function(callback) {
    var elems = this.find('img'),
            elems_src = [],
            self = this,
            len = elems.length;
    if (!elems.length) {
        callback.call(this);
        return this;
    }

    elems.one('load error', function() {
        if (--len === 0) {
            // Rinse and repeat.
            len = elems.length;
            elems.one('load error', function() {
                if (--len === 0) {
                    callback.call(self);
                }
            }).each(function() {
                this.src = elems_src.shift();
            });
        }
    }).each(function() {
        elems_src.push(this.src);
        this.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
    });
    return this;
};
/*------------------------------------------------------------------------------
 Audio Player
 ------------------------------------------------------------------------------*/

function initAudioPlayer() {
    $('#fap').fullwidthAudioPlayer({
        'socials': false,
        'opened': false,
        'wrapperColor': '#222',
        'mainColor': '#f1f1f1',
        'fillColor': '#f1f1f1',
        'metaColor': '#f1f1f1',
        'strokeColor': '#08c'
    });
}
/*------------------------------------------------------------------------------
 SoundManager
 ------------------------------------------------------------------------------*/
function initSoundManager() {
    soundManager.setup({
        url: '/swf/',
        flashVersion: 9,
        useHTML5Audio: true,
        debugMode: false,
        onready: function() {
            console.log("SoundManager Ready!");
        },
        ontimeout: function() {
            console.log("SoundManager Timed Out :/");
        }
    });
    soundManager.beginDelayedInit();
}

function initAffix() {
    if ($(window).width() > 768) {
        $('.well').css('width', $('.well').width());
        $('.well').affix({
            offset: {top: 571}
        });
    }
}