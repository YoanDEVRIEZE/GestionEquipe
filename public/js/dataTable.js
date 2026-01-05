if ($('#dataTable tbody tr').length > 0) {
    $(document).ready(function () {
        var table = $('#dataTable').DataTable({
            paging: true,
            ordering: true,
            info: false,
            pageLength: 11,
            columnDefs: [
                { orderable: false, targets: -1 }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            dom: 'ft<"d-flex justify-content-end mt-4"p>',
            initComplete: function () {
                $('#dataTable_filter').prependTo('#datatable-controls');
                $('#dataTable_filter').addClass('d-flex align-items-center mb-0 text-black').css('margin-top', '-20px');
                $('#dataTable_filter input').addClass('form-control form-control-sm me-2 text-black').css('max-width', '150px').css('border', '1px solid black');
                $('#dataTable_filter label').addClass('mb-0 text-black');
            }
        });
    });
}
