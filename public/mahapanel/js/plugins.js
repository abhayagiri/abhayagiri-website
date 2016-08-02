var editor;
/*------------------------------------------------------------------------------
 Init
 ------------------------------------------------------------------------------*/
function initUpload(id, val, dir, update) {
    update = update || false;
    val = val.replace(/C:\\fakepath\\/i, '');
    val = val.replace("jpg","JPG");
    $('#' + id).val(val);
      $('#title').val(val);
    id = id + '_file';
    $('#uploading').show();
    ajaxFileUpload(id, dir, function() {
        $('#uploading').hide();

        if (update) {

        }
    });
    /*
     update = update || false;
     switch (id) {
     case "avatar":
     ajaxFileUpload('avatar', 'mahapanel/img/mahaguild/', function() {
     if (update)
     $('#maha-avatar').attr('src', '/img/mahaguild/' + val);
     });
     break;
     case "banner":
     ajaxFileUpload('banner', 'mahapanel/img/mahaguild/', function() {
     if (update)
     setBanner(val);
     });
     break;
     case "mp3":
     ajaxFileUpload('mp3', 'www/media/audio/');
     break;
     default:
     break;
     }*/
}

function initEdit(id) {
    /*entry_id = id;
     _select(_page, '*', {
     id: id
     },
     //set values
     function(data) {
     var data = jQuery.parseJSON(data);
     $.each(data, function() {
     $.each(this, function(key, val) {
     switch (key) {
     case "url_title":
     initForm(val);
     $('#url_title').val(val);
     break;
     case "avatar":
     case "banner":
     $('#' + key + "_curr").html("<img class='propic' src='/img/mahaguild/" + val + "'/>");
     break;
     case "cover":
     $('#' + key + "_curr").html("<img class='propic' src='https://www.abhayagiri.org/media/images/books/" + val + "'/>");
     break;
     case "photo":
     $('#' + key + "_curr").html("<img class='propic' src='https://www.abhayagiri.org/media/images/residents/" + val + "'/>");
     break;
     case "epub":
     case "mobi":
     case "pdf":
     case "mp3":
     $('#' + key + "_curr").html(val);
     break;
     default:
     $('#' + key).val(val); //fix later add type
     break;
     }
     });
     });*/
    //init plugins
    initForm();
    initPlugins();
    /*});*/
}

function initPlugins() {
    try {
        if (_page == "mahaguild")
            initDualList();
    } catch (err) {
        //console.log(err);
    }
    try {
        initDatePicker();
    } catch (err) {
        //console.log(err);
    }
    try {
        initWYSIWYG();
    } catch (err) {
        //console.log(err);
    }


}

function initDataTables(table, columns, filter) {
//Variabless
    var aoColumns = [];
    var fnColumns = [];
    //Generate Column Names
    for (i = 0; i < columns.length; i++) {
        name = columns[i].title;
        display = columns[i].display;
        title = columns[i].display_title;
        if (display == 'yes') {
            aoColumns.push({
                "sTitle": title
            });
            fnColumns.push(name);
        }
    }
    aoColumns.push({
        "sTitle": "Edit"
    });
    if (table == "pages" || table == "columns" || table == "dropdowns" || table == "options") {
        page = table;
    } else {
        page = "datatables";
    }
//AJAX call
    oTable = $('.datatable').dataTable({
        "sDom": "<'row'<'span12' f>>t<'row'<'span6'><'span6'p>>",
        "aoColumns": aoColumns,
        "oLanguage": {"sSearch": "<i class='icon-search'></i>"},
        "aaSorting": [],
        "bProcessing": false,
        "bServerSide": true,
        "bLengthChange": false,
        "bAutoWidth": false,
        "iDisplayLength": 30,
        "sAjaxSource": "/mahapanel/php/" + page + ".php",
        "fnServerData": function(sSource, aoData, fnCallback) {
            aoData.push({
                "name": "table",
                "value": table
            }, {
                "name": "columns",
                "value": fnColumns
            },
            {
                "name": "filter",
                "value": filter
            });
            $.getJSON(sSource, aoData, function(json) {
                fnCallback(json)
            });
        }
    });
}

function initDatePicker() {
    $(".form_datetime").datetimepicker({
        format: "yyyy-mm-dd HH:ii:ss",
        pickerPosition: 'bottom-left',
        todayHighlight: true,
        minView: "month",
        autoclose: true,
        todayBtn: 'linked',
        showMeridian: true
    });
}

function initWYSIWYG() {
//editor = $('.wysihtml5').wysihtml5();
    editor = new TINY.editor.edit('editor', {
        id: 'body',
        cssclass: 'tinyeditor',
        controlclass: 'tinyeditor-control',
        rowclass: 'tinyeditor-header',
        dividerclass: 'tinyeditor-divider',
        controls: ['size', 'bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript',
            'orderedlist', 'unorderedlist', 'outdent', 'indent', 'leftalign',
            'centeralign', 'rightalign', 'blockjustify', 'unformat', 'undo', 'redo', 'image', 'hr', 'link', 'unlink', 'print',
        ],
        footer: true,
        fonts: ['Verdana', 'Arial', 'Georgia', 'Trebuchet MS'],
        xhtml: true,
        cssfile: '/css/tinyeditor.css',
        bodyid: 'editor',
        footerclass: 'tinyeditor-footer',
        toggle: {
            text: 'source',
            activetext: 'wysiwyg',
            cssclass: 'toggle'
        },
        resize: {
            cssclass: 'resize'
        }
    });
}

function initDualList() {
    $('.duallist').duallist();
}