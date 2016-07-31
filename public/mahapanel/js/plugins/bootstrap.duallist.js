(function($) {
    var container;
    var row;
    var ulLeft;
    var ulRight;
    var leftTitle;
    var rightTitle;
    var rightArrow;
    var leftArrow;
    var leftButton;
    var rightButton;
    var right;
    var left;
    var input = $('#access');
    var value;
    var methods = {
        init: function(options) {
            value = [];
            build(this);
            this.append(container);
        },
        config: function(list) {
            list = list.split(',');
            $.each(list, function(i, val) {
                $('#duallist-id-' + val).trigger('click');
            });
        }

    };
    function build(elm) {
        container = ($('<div></div>').addClass("container-fluid"));
        row = ($('<div></div>').addClass("row-fluid"));
        ulLeft = ($('<ul></ul>').addClass("duallist-ul duallist-ul-left"));
        ulRight = ($('<ul></ul>').addClass("duallist-ul duallist-ul-right"));
        leftTitle = ($('<div></div>').addClass("duallist-title").append("Sections"));
        rightTitle = ($('<div></div>').addClass("duallist-title").append("Has Access To"));

        right = ($('<div></div>').addClass("duallist-select duallist-right span4"));
        left = ($('<div></div>').addClass("duallist-select duallist-left span4"));

        leftArrow = $('<i></i>').addClass('icon-arrow-left');
        rightArrow = $('<i></i>').addClass('icon-arrow-right');
        rightButton = ($('<button></button>').addClass('btn duallist-button'));
        leftButton = ($('<button></button>').addClass('btn duallist-button'));
        leftButton.append(leftArrow);
        leftButton.append(leftArrow);
        rightButton.append(rightArrow);
        rightButton.append(rightArrow);
        right.append(rightTitle);
        //right.append(leftButton);
        right.append(ulRight);
        left.append(leftTitle);
        //left.append(rightButton);
        left.append(ulLeft);
        row.append(left);
        row.append(right);
        container.append(row);
        fill();
    }
    function fill() {
        $.ajax({
            url: '/php/ajax.php',
            type: 'POST',
            data: {
                action: 'select',
                table: 'pages',
                columns: 'url_title,icon',
                where: {
                    mahapanel: 'yes'
                },
                order: 'ORDER by DATE DESC'
            },
            success: function(data) {
                var data = jQuery.parseJSON(data);

                $.each(data, function(index, val) {
                    icon = ($('<i></i>').addClass('' + val.icon));
                    li = ($('<li></li>').addClass('duallist-li btn btn-small btn-warning '));
                    li.attr('id', 'duallist-id-' + val.url_title);
                    li.click(function(event) {
                        event.stopPropagation();
                        shift(this);
                    });
                    li.append(icon);
                    li.append(' ' + val.url_title);
                    ulLeft.append(li);
                });
                right.height(left.height());
                list = $('#access').val().split(',');
                console.log(list);
                $.each(list, function(i, val) {
                    $('#duallist-id-' + val).trigger('click');
                });
            }
        });
    }
    function shift(elm) {
        li = $(elm);
        title = li.attr('id').replace('duallist-id-', '');
        if (li.parent().hasClass('duallist-ul-left')) {
            li.removeClass('btn-warning');
            li.addClass('btn-success');
            ulRight.append(li);
            value.push(title);
            $('#access').val(value.join(','));
        } else {
            li.removeClass('btn-success');
            li.addClass('btn-warning');
            ulLeft.append(li);
            value.remove(title);
            $('#access').val(value.join(','));
        }
    }

    $.fn.duallist = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.duallist');
        }
    };

})(jQuery);
