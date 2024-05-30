<link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<div class="form-group">
    <label>Date:</label>

    <div class="input-group date">
        <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
        </div>
        <input type="text" class="form-control pull-right" id="filter_picking_date">
        <input type="hidden" id="filter_picking_date_start">
        <input type="hidden" id="filter_picking_date_end">
    </div>
    <!-- /.input group -->
</div>
<script>
    $(document).ready(function(){
        $('#filter_picking_date').daterangepicker().on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        $('#filter_picking_date_start').val(picker.startDate.format('YYYY-MM-DD'));
        $('#filter_picking_date_end').val(picker.endDate.format('YYYY-MM-DD'));
        picking_slip.draw();

      }).on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#filter_picking_date_start').val('');
        $('#filter_picking_date_end').val('');
        picking_slip.draw();
      });
    });
</script>