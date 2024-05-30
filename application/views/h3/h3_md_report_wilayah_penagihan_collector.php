<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
</section>
  <section class="content">
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-body">
        <div class="container-fluid no-padding">
          <div class="row">
            <div id='filter_customer' class="col-sm-3">
              <div class="form-group">
                <label class="control-labelalign-middle">Wilayah Penagihan</label>
                <div class="input-group">
                  <input :value='filters.length + " Wilayah Penagihan"' type="text" class="form-control" disabled>
                  <div class="input-group-btn">
                    <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_wilayah_penagihan_collector_filter'>
                      <i class="fa fa-search"></i>
                    </button>
                  </div>
                </div>
              </div>   
            </div>
            <?php $this->load->view('modal/h3_md_wilayah_penagihan_collector_filter'); ?>
            <script>
              filter_wilayah_penagihan = new Vue({
                el: '#filter_wilayah_penagihan',
                data: {
                  filters: []
                },
                watch: {
                  filters: function(){
                    monitoring_do_pending.draw();
                  }
                }
              });

              $("#h3_md_wilayah_penagihan_collector_filter").on('change',"input[type='checkbox']",function(e){
                target = $(e.target);
                id = target.attr('data-id');

                if(target.is(':checked')){
                  filter_wilayah_penagihan.filters.push(id);
                }else{
                  index_id = _.indexOf(filter_wilayah_penagihan.filters, id);
                  filter_wilayah_penagihan.filters.splice(index_id, 1);
                }
                h3_md_wilayah_penagihan_collector_filter_datatable.draw();
              });
            </script>
          </div>
        </div>
        <table id="monitoring_do_pending" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Kode Customer</th>              
              <th>Nama Customer</th>              
              <th>Alamat</th>              
              <th>Kode Wilayah</th>              
              <th>Nama Wilayah</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          monitoring_do_pending = $('#monitoring_do_pending').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/report_wilayah_penagihan_collector') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.id_wilayah_penagihan_filter = filter_wilayah_penagihan.filters;
                }
            },
            columns: [
              { data: 'index', orderable: false, width: '3%' }, 
              { data: 'kode_dealer_md', name: 'd.kode_dealer_md' }, 
              { data: 'nama_dealer', name: 'd.nama_dealer' }, 
              { data: 'alamat', name: 'd.alamat' }, 
              { data: 'kode_wilayah', name: 'wp.kode_wilayah' }, 
              { data: 'nama_wilayah', name: 'wp.nama' }, 
            ],
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>