<label style="margin-right: 5px;">
    <div class="input-group date">
        <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
        </div>
        <input type="text" class="form-control pull-right" id="filter_tahun">
        <input type="hidden" id="filter_tahun_kendaraan">
    </div>
</label>
<script>
    $(document).ready(function(){
        $('#filter_tahun').datepicker({
            format: "yyyy",
            viewMode: "years", 
            minViewMode: "years"
        })
        .on('changeDate', function(e){
            $('#filter_tahun_kendaraan').val(e.target.value);
            tipe_kendaraan_datatable.draw();
        });
    });
</script>