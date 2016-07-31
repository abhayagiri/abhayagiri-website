/*------------------------------------------------------------------------------
 Global Variables
 ------------------------------------------------------------------------------*/
var hash = window.location.href.split('/');
var _page = (hash[3])?hash[3]:"dashboard";
var page_id = "0";
var entry_id = null;
var parent_id = null;
/*------------------------------------------------------------------------------
 SELECT
 ------------------------------------------------------------------------------*/
function _select(table, columns, where, f) {
    //init args
    var table = table;
    var columns = columns || "*";
    var where = where || {
        1:1
    };
    var f = f || "";
    //ajax call
    $.ajax({
        url: '/php/ajax.php',
        type: 'POST',
        data: {
            action:"select",
            table: table,
            columns: columns,
            where: where
        },
        success: f
    });
}
/*------------------------------------------------------------------------------
 INSERT
 ------------------------------------------------------------------------------*/
function _insert(table, columns, f) {
    //init args
    f = f || "";
    //ajax call
    $.ajax({
        url: '/php/ajax.php',
        type: 'POST',
        data: {
            action:"insert",
            table: table,
            columns: columns
        }
    }).done(
        //f
        function(data) {
            if (typeof f == "function")
                f(data);
        });
}
/*------------------------------------------------------------------------------
 INSERT
 ------------------------------------------------------------------------------*/
function _update(table, columns, where, f) {
    console.log(columns);
    //init args
    f = f || "";
    //ajax call

    $.ajax({
        url: '/php/ajax.php',
        type: 'POST',
        data: {
            action:"update",
            table: table,
            columns: columns,
            where:where
        }
    }).done(
        //f
        function(data) {
            if (typeof f == "function")
                f(data);
        });
}
/*------------------------------------------------------------------------------
 DELETE
 ------------------------------------------------------------------------------*/
function _delete(table, columns, f) {
    //init args
    f = f || "";
    //ajax call

    $.ajax({
        url: '/php/ajax.php',
        type: 'POST',
        data: {
            action:"delete",
            table: table,
            columns: columns
        }
    }).done(
        //f
        function(data) {
            if (typeof f == "function")
                f(data);
        });
}

/*------------------------------------------------------------------------------
 LOAD
 ------------------------------------------------------------------------------*/
function _load(id, data, f, src) {
    src=src||id;
    f = f || "";
    $('#' + id).load('/php/'+src+'.php', data, function() {
        if (typeof f == "function") {
            f();
        }
    });
}
