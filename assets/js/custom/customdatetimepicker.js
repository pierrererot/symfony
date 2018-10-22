(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Make globaly available as well
        define(['moment', 'jquery'], function (moment, jquery) {
            return (root.loadCustomDatetimePicker = factory(moment, jquery));
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
        root.loadCustomDatetimePicker = factory(root.moment, root.jQuery);
    }
}(this, function(moment, $) {

    'use strict';

    let loadCustomDatetimePicker = function (element, table, filterId) {

        let datetimepicker = element.datetimepicker(
            {
                autoclose: true,
                language:"fr",
                endDate:  moment().add(5,'m').toDate(),
                todayHighlight: true,
                value: moment().toDate(),
                debug:true,
                icons: {
                time: 'fa fa-calendar',
                    date: 'fa fa-calendar',
                    up: 'fa fa-calendar',
                    down: 'fa fa-calendar',
                    previous: 'fa fa-calendar',
                    next: 'fa fa-calendar',
                    today: 'fa fa-calendar',
                    clear: 'fa fa-calendar',
                    close: 'fa fa-calendare'
                }
            }
        );
        datetimepicker.on('changeDate',function(e){
            let newValue = moment(e.date).format('DD/MM/YYYY HH:mm');
            element.find('span').html(newValue);
            $.fn.setStorageItem('date' + filterId, newValue);
            table.columns(filterId).search( newValue ).draw();
        });
        return datetimepicker;
    };

    $.fn.loadCustomDatetimePicker = function(table, filterId) {
        return loadCustomDatetimePicker(this, table, filterId);
    };

    return loadCustomDatetimePicker;
}));