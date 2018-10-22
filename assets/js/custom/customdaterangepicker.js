(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Make globaly available as well
        define(['moment', 'jquery'], function (moment, jquery) {
            return (root.loadCustomDateRangePicker = factory(moment, jquery));
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
        root.loadCustomDateRangePicker = factory(root.moment, root.jQuery);
    }
}(this, function(moment, $) {

    'use strict';

    let loadCustomDateRangePicker = function (element, table, filterId) {
        element.daterangepicker(
            {
                "timePicker": false,
                "timePicker24Hour": true,
                "timePickerIncrement": 1,
                "locale": {
                    "format": "DD/MM/YYYY HH:mm",
                    "separator": " - ",
                    "applyLabel": "Valider",
                    "cancelLabel": "Annuler",
                    "fromLabel": "De",
                    "toLabel": "A",
                    "customRangeLabel": "Personnalisé",
                    "weekLabel": "S",
                    "daysOfWeek": [ "d", "l", "ma", "me", "j", "v", "s"],
                    "monthNames": ["janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre"],
                    "firstDay": 1
                },
                "ranges": {
                    'Aujourd\'hui': [moment().startOf('day'), moment().endOf('day')],
                    'Hier': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
                    '7 derniers jours': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
                    '30 derniers jours': [moment().subtract(29, 'days').startOf('day'), moment().endOf('day')],
                    'Mois en cours': [moment().startOf('month'), moment().endOf('month')],
                    'Mois dernier': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                "opens": "center",
            },
            function (start, end) {
                let newValue = start.format('DD/MM/YYYY HH:mm') + ' - ' + end.format('DD/MM/YYYY HH:mm');
                let newHtml = newValue;
                if( start.format("HH:mm") === '00:00' && end.format("HH:mm") === '23:59' ){
                    newHtml = start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY');
                }
                element.find('span').html(newHtml);
                $.fn.setStorageItem('date' + filterId + 'htmlValue', newHtml);
                $.fn.setStorageItem('date' + filterId, newValue);
                table.columns(filterId).search( newValue ).draw();
            }
        )
    }

    $.fn.loadCustomDateRangePicker = function(table, filterId) {
        let elem = loadCustomDateRangePicker(this, table, filterId);
        if($.fn.getStorageItem("date"+filterId)){
            this.find('span').html($.fn.getStorageItem("date"+filterId+'htmlValue'));
            this.val($.fn.getStorageItem("date"+filterId));
        }
        return elem;
    };

    return loadCustomDateRangePicker;
}));