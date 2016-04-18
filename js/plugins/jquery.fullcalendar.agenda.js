/*!
 * FullCalendar v2.2.2 Google Calendar Plugin
 * Docs & License: http://arshaw.com/fullcalendar/
 * (c) 2013 Adam Shaw
 */
 
(function(factory) {
	if (typeof define === 'function' && define.amd) {
		define([ 'jquery' ], factory);
	}
	else {
		factory(jQuery);
	}
})(function($) {


var API_BASE = 'https://www.googleapis.com/calendar/v3/calendars';
var fc = $.fullCalendar;
var applyAll = fc.applyAll;


fc.sourceNormalizers.push(function(sourceOptions) {

	var googleCalendarId = sourceOptions.googleCalendarId;
	var url = sourceOptions.url;
	var match;
			//console.log(sourceOptions);
	// if the Google Calendar ID hasn't been explicitly defined
	if (!googleCalendarId && url) {

		// detect if the ID was specified as a single string
		if ((match = /^[^\/]+@([^\/]+\.)?calendar\.google\.com$/.test(url))) {
			googleCalendarId = url;
		}
		// try to scrape it out of a V1 or V3 API feed URL
		else if (
			(match = /^https:\/\/www.googleapis.com\/calendar\/v3\/calendars\/([^\/]*)/.exec(url)) ||
			(match = /^https?:\/\/www.google.com\/calendar\/feeds\/([^\/]*)/.exec(url))
		) {
			googleCalendarId = decodeURIComponent(match[1]);
		}

		if (googleCalendarId) {
			sourceOptions.googleCalendarId = googleCalendarId;
		}
	}


	if (googleCalendarId) { // is this a Google Calendar?

		// make each Google Calendar source uneditable by default
		if (sourceOptions.editable == null) {
			sourceOptions.editable = false;
		}

		// We want removeEventSource to work, but it won't know about the googleCalendarId primitive.
		// Shoehorn it into the url, which will function as the unique primitive. Won't cause side effects.
		sourceOptions.url = googleCalendarId;
	}
});


fc.sourceFetchers.push(function(sourceOptions, start, end, timezone) {
	if (sourceOptions.googleCalendarId) {
		return transformOptions(sourceOptions, start, end, timezone, this); // `this` is the calendar
	}
});


function transformOptions(sourceOptions, start, end, timezone, calendar) {

	var url = API_BASE + '/' + encodeURIComponent(sourceOptions.googleCalendarId) + '/events?callback=?'; // jsonp
	var apiKey = sourceOptions.googleCalendarApiKey || calendar.options.googleCalendarApiKey;
	var success = sourceOptions.success;
	var data;

	function reportError(message, apiErrorObjs) {
		var errorObjs = apiErrorObjs || [ { message: message } ]; // to be passed into error handlers
		var consoleObj = window.console;
		var consoleWarnFunc = consoleObj ? (consoleObj.warn || consoleObj.log) : null;

		// call error handlers
		(sourceOptions.googleCalendarError || $.noop).apply(calendar, errorObjs);
		(calendar.options.googleCalendarError || $.noop).apply(calendar, errorObjs);

		// print error to debug console
		if (consoleWarnFunc) {
			consoleWarnFunc.apply(consoleObj, [ message ].concat(apiErrorObjs || []));
		}
	}

	if (!apiKey) {
		reportError("Specify a googleCalendarApiKey. See http://fullcalendar.io/docs/google_calendar/");
		return {}; // an empty source to use instead. won't fetch anything.
	}

	// The API expects an ISO8601 datetime with a time and timezone part.
	// Since the calendar's timezone offset isn't always known, request the date in UTC and pad it by a day on each
	// side, guaranteeing we will receive all events in the desired range, albeit a superset.
	// .utc() will set a zone and give it a 00:00:00 time.
	if (!start.hasZone()) {
		start = start.clone().utc().add(-1, 'day');
	}
	if (!end.hasZone()) {
		end = end.clone().utc().add(1, 'day');
	}

	data = $.extend({}, sourceOptions.data || {}, {
		key: apiKey,
		timeMin: start.format(),
		timeMax: end.format(),
		singleEvents: true,
		maxResults: 9999
	});

	return $.extend({}, sourceOptions, {

		googleCalendarId: null, // prevents source-normalizing from happening again
		url: url,
		data: data,
		timezoneParam: 'timeZone',
		startParam: false, // `false` omits this parameter. we already included it above
		endParam: false, // same
		success: function(data) {

			var events = [];
			var successArgs;
			var successRes;

			if (data.error) {
				reportError('Google Calendar API: ' + data.error.message, data.error.errors);
			}

			else if (data.items) {

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

				// call the success handler(s) and allow it to return a new events array
				successArgs = [ events ].concat(Array.prototype.slice.call(arguments, 1)); // forward other jq args
				successRes = applyAll(success, this, successArgs);
				if ($.isArray(successRes)) {
					return successRes;
				}
			}

				//Sort the events
				sortedEvents = events.sort(function (a, b) {
				  if (a.start > b.start) {
				    return 1;
				  }
				  if (a.start < b.start) {
				    return -1;
				  }
				  // a must be equal to b
				  return 0;
				});

			    eventList = "";
                eventDetails = "";
                
                //Build the HTML for the event details
                for (i = 0; i < sortedEvents.length; i++) {
          			
          			//Local Variable
          			var e = sortedEvents[i];
                    var id = e.id;
                    var start = moment(e.start).format("LL");

                    //Event Details
                    eventDetails += "<div id='" + id + "'>";
                    eventDetails += "<dl class='dl-horizontal'>";
                    eventDetails += '<a class="title" href="/calendar/' + id + '" onclick="navEvent(\'' + id + "');return false\">" + e.title + "</a><br><br>";
                    eventDetails += "<dt>When<dt><dd>" + start + "</dd>";
                
                    if(!!events[i].location){
                    	eventDetails += "<dt>Location</dt><dd>" + e.location + "";
                    	eventDetails += '<a target="_blank" href="http://maps.google.com/maps?q=' + e.location.replace(/\n/g, "+") + '"> (Map)</a></dd>';
                    }
    				
    				if(!!events[i].description){
                    	eventDetails += "<dt>Description</dt><dd>" + e.description.replace(/\n/g, "<br />") + "</dd>";
                	}

                    eventDetails += "</dl>";
                    eventDetails += "<div class='backtotop' onclick='backtotop()'><span class='pull-right'><i class='icon-caret-up'></i> " + _lang.backtotop + "</span></div>";
                    eventDetails += '<hr class="border">'
                    eventDetails += "</div>";

                    //Event List
                    eventList += '<div class="event-title">'; 
                    eventList += "<div >" + start + "</div>";
                    eventList += '<div class="title" onclick="scrollToElm(\'' + id + "');return false\">" + e.title + "</div>";
                    eventList += "</div><br/>";
                }

                $("#event-list").html("<legend>Events</legend>" + eventList);
                $("#event-details").html(eventDetails);

			return events;
		}
	});
}


});
//var event_details;(function(l){var n=l.fullCalendar;var o=n.formatDate;var k=n.parseISO8601;var j=n.addDays;var h=n.applyAll;n.sourceNormalizers.push(function(a){if(a.dataType=="gcal"||a.dataType===undefined&&(a.url||"").match(/^(http|https):\/\/www.google.com\/calendar\/feeds\//)){a.dataType="gcal";if(a.editable===undefined){a.editable=false}}});n.sourceFetchers.push(function(b,a,c){if(b.dataType=="gcal"){return m(b,a,c)}});function m(d,f,e){var b=d.success;var c=l.extend({},d.data||{},{"start-min":o(f,"u"),"start-max":o(e,"u"),singleevents:true,"max-results":9999});var a=d.currentTimezone;if(a){c.ctz=a=a.replace(" ","_")}return l.extend({},d,{url:d.url.replace(/\/basic$/,"/full")+"?alt=json-in-script&callback=?",dataType:"jsonp",data:c,startParam:false,endParam:false,success:function(g){var s=[];if(g.feed.entry){l.each(g.feed.entry,function(D,q){var C=q["gd$when"][0]["startTime"];var G=k(C,true);var r=o(G,"MMMM dS, dddd");var I=o(G,"yyyyMMdd");var F=o(G,"hh:mm TT");var E=k(q["gd$when"][0]["endTime"],true);var p=C.indexOf("T")==-1;var H;l.each(q.link,function(w,v){if(v.type=="application/atom+xml"){H=v.href}});if(p){j(E,-1)}s.push({id:q["gCal$uid"]["value"],title:q.title["$t"],url:H,start:G,end:E,when:r,time:F,allDay:p,location:q["gd$where"][0]["valueString"],description:q.content["$t"],stamp:I})})}var u=[s].concat(Array.prototype.slice.call(arguments,1));var t=h(b,this,u);if(l.isArray(t)){return t}if(s.length==0){l("#notify").html("No events have been listed for this month yet")}else{l("#notify").html("")}abhaya_events=new Array();abhaya_list=new Array();for(i=0;i<s.length;i++){id=s[i].url.replace("https://www.google.com/calendar/feeds/abhayagiri.org_2tr1cpnhbe4i5cria1l6ae8si8%40group.calendar.google.com/public/full/","");abhaya_list[i]=s[i].stamp+"~";abhaya_list[i]+="<div >"+s[i].when+"</div>";abhaya_list[i]+='<div class="title" onclick="scrollToElm(\''+id+"');return false\">"+s[i].title+"</div>";abhaya_events[i]=s[i].stamp+"~";abhaya_events[i]+=id+"~";abhaya_events[i]+="<dl class='dl-horizontal'>";abhaya_events[i]+='<a class="title" href="/calendar/'+id+'" onclick="navEvent(\''+id+"');return false\">"+s[i].title+"</a><br><br>";abhaya_events[i]+="<dt>When<dt><dd>"+s[i].when+"</dd>";abhaya_events[i]+="<dt>Location</dt><dd>"+s[i].location+"";abhaya_events[i]+='<a target="_blank" href="http://maps.google.com/maps?q='+s[i].location.replace(/\n/g,"+")+'"> (Map)</a></dd>';abhaya_events[i]+="<dt>Description</dt><dd>"+s[i].description.replace(/\n/g,"<br />")+"</dd>";abhaya_events[i]+="</dl>";abhaya_events[i]+="<div class='backtotop' onclick='backtotop()'><span class='pull-right'><i class='icon-caret-up'></i> "+_lang.backtotop+"</span></div>";abhaya_events[i]+='<hr class="border">'}abhaya_events.sort();abhaya_list.sort();event_details="";events="";for(i=0;i<abhaya_events.length;i++){event_data=abhaya_events[i].split("~");list_data=abhaya_list[i].split("~");event_details+="<div id='"+event_data[1]+"'>"+event_data[2]+"</div>";events+='<div class="event-title">'+list_data[1]+"</div><br>"}l("#event-list").html("<legend>Events</legend>"+events);l("#event-details").html(event_details);return s}})}n.gcalFeed=function(a,b){return l.extend({},b,{url:a,dataType:"gcal"})}})(jQuery);