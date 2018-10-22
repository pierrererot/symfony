(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Make globaly available as well
        define(['moment', 'jquery'], function (moment, jquery) {
            return (root.loadDataTable = factory(moment, jquery));
        });
    } else if (typeof module === 'object' && module.exports) {
        // Node / Browserify
        //isomorphic issue
        let jQuery = (typeof window !== 'undefined') ? window.jQuery : undefined;
        if (!jQuery) {
            jQuery = require('jquery');
            if (!jQuery.fn) jQuery.fn = {};
        }
        module.exports = factory(require('moment'), jQuery);
    } else {
        // Browser globals
        root.loadDataTable = factory(root.moment, root.jQuery);
    }
}(this, function(moment, $) {

    'use strict';

    function showOrHideChildRow(tr, row, sp,renderView) {

        if(sp.length > 0)
        {
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                sp.addClass('fa-search-plus');
                sp.removeClass('fa-search-minus')
            }
            else {
                // Open this row
                row.child(renderView(row)).show();
                tr.addClass('shown');
                sp.addClass('fa-search-minus');
                sp.removeClass('fa-search-plus');
            }
        }
    }

    let loadDataTable = function (element, targetDataUrl, columns, targetExportData, getFilename, detailColumnId, detailColumnName, initialFilters, renderView,  order, drawCallback ) {

        let searchCols = initialFilters.map( function (id) {

            if( typeof id != 'null' ) {
                return {
                    "search": function () {
                        let elem = $("#" + id);
                        if($.fn.getStorageItem(id)){
                            elem.val($.fn.getStorageItem(id));
                        }
                        return elem.val()
                    }
                }
            }else{
                return null
            }
        });
        let table = element.DataTable({
            "searchCols": searchCols,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: targetDataUrl,
                type: 'POST',
            },
            "scrollX": true,
            "dom": '<"col-md-10"l><"#excel.col-md-2">rt<"col-md-6"i><"col-md-6"p>',
            "columns": columns,
            "order": order,
            "drawCallback": drawCallback,
            "initComplete": function () {

                this.api().columns().every(function () {

                    var sending;
                    function sendWithTimeout(initValue, component, arrayCompare) {
                        sending = setTimeout(function() {
                            let valueAfterTimeout = component.val();
                            let valueToSend = valueAfterTimeout;

                            if(arrayCompare === true) {
                                initValue = initValue.join()
                                valueAfterTimeout = valueAfterTimeout.join()
                                if (!valueAfterTimeout) {
                                    valueToSend = ''
                                }
                            }
                            if(initValue === valueAfterTimeout){
                                table.columns(column.index()).search(valueToSend, true, false).draw();
                            }
                        }, 1000);
                    }

                    function stopSending() {
                        clearTimeout(sending)
                    }

                    var column = this;
                    // Initialize Select Filters
                    if (typeof $('#select' + column.index()) !== "undefined") {

                        var select = $('#select' + column.index());
                        select.on('change.select2', function () {
                            stopSending();
                            let values = select.val()
                            sendWithTimeout(values, select, true);
                            $.fn.setStorageItem('select' + column.index(), values);
                        });
                    }

                    // Initialize Text Filter
                    if (typeof $('#inputsearch' + column.index()) !== "undefined") {


                        let inputtext = $('#inputsearch' + column.index())
                        inputtext.keyup( function (zEvent) {
                            stopSending()
                            if (zEvent.ctrlKey  ||  zEvent.altKey || zEvent.metaKey || zEvent.shiftKey) {
                                return null;
                            }
                            let initValue = inputtext.val();
                            sendWithTimeout(initValue, inputtext, false)
                            $.fn.setStorageItem('inputsearch' + column.index(), initValue);
                        });
                    }
                });

                // Excel button init in Dom option
                $('#excel').loadExportCsvButton(table, targetExportData, getFilename );

                // Hide all childs row
                $('th.details-control').on('click', function () {
                    let row, tr, sp;
                    table.rows().eq(0).each(function (idx) {
                        row = table.row(idx);
                        tr = $(row.node());
                        sp = $(tr).find('.fa-search-minus')
                        showOrHideChildRow(tr, row, sp,renderView);
                    });
                });

                // Add event listener for opening and closing details
                table.on('click', 'td.details-control', function () {
                    let tr = $(this).closest('tr');
                    let row = table.row(tr);
                    let sp = $(this).children('span.fa');
                    showOrHideChildRow(tr, row, sp, renderView)
                });

                $('#loadingContentWrapper').hide();
                $('#ContentWrapper').show();

            }
        });
        return table;
    };

    $.fn.loadDataTable = function(targetDataUrl, columns, targetExportData, getFilename, detailRowColumnId, detailRowColumnName, filters, renderView,  orderBy=[[ 0, 'DESC']], drawCallback=null) {
        return loadDataTable(this, targetDataUrl, columns, targetExportData, getFilename, detailRowColumnId, detailRowColumnName, filters, renderView, orderBy, drawCallback);
    };

    return loadDataTable;
}));