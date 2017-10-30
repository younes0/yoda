import Other from "../Helpers/Other";

export default class Datatable {

    constructor() {
        this.$table = null;
    }

    setup(extend) {
        var columns = window.app.columns;

        var defaultOpts = {
            language  : 'datatableFr',
            ajaxSource: window.location.pathname+'/datatable',
            columns   : columns,
            processing: false,
            serverSide: true,
            stateSave : true,
            pageLength: 25,
            // responsive: true,
            // dom:  '<clear>CfrtipR',
                     dom:
                "R<'table-header clearfix'<'table-caption'><'DT-lf-right'C<'DT-per-page'l><'DT-search'f>>r>"+
                "t"+
                "<'table-footer clearfix'<'DT-label'i><'DT-pagination'p>>",
            drawCallback: this.drawCallback,
            serverParams: function (data) {
                if (typeof app.dataPush !== 'undefined') {
                    // convert to object
                    var obj = Object.setPrototypeOf(app.dataPush, Object.prototype);

                    for (let key in obj) {
                        data.push(obj[key]);
                    }
                }
            }
        };

        var options = $.extend({}, defaultOpts, extend);

        this.$table = $('.dataTable').dataTable(options);

        return this.$table;
    }

    drawCallback(e) {
        $('[rel=tooltip]').tooltip();
        
        // yeb.modals.setLinks(false, '#datatable');
        // $('.dataTables_wrapper .table-caption').html(
        //     $('#datatable').data('caption')
        // );
    }

}
