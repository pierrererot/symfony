
(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Make globaly available as well
        define(['jquery'], function ( jquery) {
            return (root.loadExportCsvButton = factory(jquery));
        });
    } else if (typeof module === 'object' && module.exports) {
        // Node / Browserify
        //isomorphic issue
        let jQuery = (typeof window !== 'undefined') ? window.jQuery : undefined;
        if (!jQuery) {
            jQuery = require('jquery');
            if (!jQuery.fn) jQuery.fn = {};
        }
        module.exports = factory(jQuery);
    } else {
        // Browser globals
        root.loadExportCsvButton = factory(root.jQuery);
    }
}(this, function($) {

    'use strict';

    function showFile(data, filename) {
        // It is necessary to create a new blob object with mime-type explicitly set
        // otherwise only Chrome works like it should
        let newBlob = new Blob([data], {type: "application/vnd.excel"});

        // IE doesn't allow using a blob object directly as link href
        // instead it is necessary to use msSaveOrOpenBlob
        if (window.navigator && window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveOrOpenBlob(newBlob, filename);
            return;
        }

        // For other browsers:
        // Create a link pointing to the ObjectURL containing the blob.
        const url = window.URL.createObjectURL(newBlob);
        let link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('target', '_blank');
        link.setAttribute('download', filename);
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        setTimeout(
            function(){
                // For Firefox it is necessary to delay revoking the ObjectURL
                window.URL.revokeObjectURL(url);
            },
            100
        );
    }

    let loadExportCsvButton = function(element, sourceTable, targetAjaxUrl, getFilename){

        $(element).html(
            '<button type="button" class="btn btn-success pull-right loading-img" id="Excel">' +
            '    <i class="fa fa-file-excel-o"></i>' +
            '    <span id="spanexcel">Excel</span>' +
            '</button>'
        ).click(function (e) {
            console.log("CLick");
            var btn = $("#spanexcel");
            console.log("BTN");
            console.log(btn);
            console.log($(btn));
            $(btn).buttonLoader('start');
            console.log("start");
            e.preventDefault();
            var a = function(){};
            setTimeout(a, 5000);
            $(btn).buttonLoader('stop')
            console.log("stop");
            // $.post(
            //     targetAjaxUrl,
            //     sourceTable.ajax.params()
            // ).done(
            //     function (msg) {
            //         showFile(msg, getFilename())
            //     }
            // ).always(
            //     $(btn).buttonLoader('stop')
            // )
        });
    };

    $.fn.loadExportCsvButton = function(sourceTable, targetAjaxUrl, getFilename) {
        loadExportCsvButton(this, sourceTable, targetAjaxUrl, getFilename);
        return this;
    };
    return loadExportCsvButton;
}));
