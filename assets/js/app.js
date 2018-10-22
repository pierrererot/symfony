// Gentelella - Minimum SetUp
const $ = require('jquery');
const moment = require('gentelella/vendors/moment/moment');

// Others
require('gentelella/vendors/bootstrap/dist/js/npm');
require('gentelella/vendors/fastclick/lib/fastclick');
require('gentelella/vendors/nprogress/nprogress');
require('./jquery.buttonLoader.min');

// Input
require('gentelella/vendors/bootstrap-daterangepicker/daterangepicker');
require('./malot-bootstrap-datetimepicker.min');
require('./mickaelr-jquery-stepProgressBar');
require('gentelella/vendors/select2/dist/js/select2');
require('gentelella/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard');

// Datatable
require('gentelella/vendors/datatables.net/js/jquery.dataTables.min');
require('gentelella/vendors/datatables.net-bs/js/dataTables.bootstrap.min');
require('gentelella/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min');
require('gentelella/vendors/datatables.net-responsive/js/dataTables.responsive');
require('gentelella/vendors/datatables.net-responsive-bs/js/responsive.bootstrap');
require('gentelella/vendors/datatables.net-scroller/js/dataTables.scroller.min');

// Gentelella
require('gentelella');

// Custom JS
require('./custom/customdaterangepicker');
require('./custom/customdatetimepicker');
require('./custom/customExportCsvButtonForDatatable');
require('./custom/customdatatable');

$.fn.setStorageItem = function (fieldId, fieldValue) {
    sessionStorage.setItem(sessionStorage.getItem("pageIdentifier")+fieldId, fieldValue);
};
$.fn.getStorageItem = function (fieldId) {
    return sessionStorage.getItem(sessionStorage.getItem("pageIdentifier")+fieldId);
};

$(document).ready(function() {

    sessionStorage.setItem("pageIdentifier", window.location.pathname);

    //Initialize Select2 Elements
    $('.select2_group').select2({allowClear: true, placeholder: ''});


    // Custom Page JS
    if(typeof loadWhenPageIsReady !== 'undefined'){
        loadWhenPageIsReady($,moment );
    }
});