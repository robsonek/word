$('input').bind('paste', function (e) {
    var $start = $(this);
    var source

    //check for access to clipboard from window or event
    if (window.clipboardData !== undefined) {
        source = window.clipboardData
    } else {
        source = e.originalEvent.clipboardData;
    }
    var data = source.getData("Text");
    if (data.length > 0) {
        if (data.indexOf("\t") > -1) {
            var columns = data.split("\n");
            $.each(columns, function () {
                var values = this.split("\t");
                $.each(values, function () {
                    $start.val(this);
                    if ($start.closest('td').next('td').find('input,textarea')[0] != undefined || $start.closest('td').next('td').find('textarea')[0] != undefined) {
                        $start = $start.closest('td').next('td').find('input,textarea');
                    }
                    else
                    {
                        return false;
                    }
                });
                $start = $start.closest('td').parent().next('tr').children('td:first').find('input,textarea');
            });
            e.preventDefault();
        }
    }
});































(function ($) {

    $.fn.enableCellNavigation = function () {

        var arrow = {
            left: 37,
            up: 38,
            right: 39,
            down: 40,
            enter: 13
        };

        // select all on focus
        this.find('input,select').keydown(function (e) {
            // shortcut for key other than arrow keys
            if ($.inArray(e.which, [arrow.left, arrow.up, arrow.right, arrow.down, arrow.enter]) < 0) {
                return;
            }

            var input = e.target;
            var td = $(e.target).closest('td');
            var moveTo = null;

            switch (e.which) {

                case arrow.left:
                {
                    if (typeof input.selectionStart == 'undefined') {
                        moveTo = td.prev('td:has(input,select)');
                    } else if (input.selectionStart == 0) {
                        moveTo = td.prev('td:has(input,select)');
                    }
                    break;
                }
                case arrow.right:
                {
                    if (typeof input.selectionStart == 'undefined') {
                        moveTo = td.next('td:has(input,select)');
                    } else if (input.selectionEnd == input.value.length) {
                        moveTo = td.next('td:has(input,select)');
                    }
                    break;
                }
                case arrow.enter:
                {

                    var tr = td.closest('tr');
                    var pos = td[0].cellIndex;

                    var moveToRow = null;
                    if (e.which == arrow.down) {
                        moveToRow = tr.next('tr');
                    } else if (e.which == arrow.up) {
                        moveToRow = tr.prev('tr');
                    }

                    if (moveToRow.length) {
                        moveTo = $(moveToRow[0].cells[pos]);
                    }

                    break;
                }

                case arrow.up:
                case arrow.down:
                {

                    var tr = td.closest('tr');
                    var pos = td[0].cellIndex;

                    var moveToRow = null;
                    if (e.which == arrow.down) {
                        moveToRow = tr.next('tr');
                    } else if (e.which == arrow.up) {
                        moveToRow = tr.prev('tr');
                    }

                    if (moveToRow.length) {
                        moveTo = $(moveToRow[0].cells[pos]);
                    }

                    break;
                }

            }

            if (moveTo && moveTo.length) {

                e.preventDefault();

                moveTo.find('input,select').each(function (i, input) {
                    input.focus();
                    input.select();
                });

            }

        });

    };

})(jQuery);
$(function () {
    $('table').enableCellNavigation();
});