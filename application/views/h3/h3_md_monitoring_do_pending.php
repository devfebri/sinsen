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
        
      <?php if($this->input->get('history') != null): ?>
              <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
              </a>  
              <?php else: ?>
              <a href="h3/<?= $isi ?>?history=true">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
              </a> 
      <?php endif; ?>
        <div class="container-fluid no-padding">
          <div class="row">
            <div id='status_filter' class="col-sm-3">
                <div class="form-group">
                  <label class="control-labelalign-middle">Status</label>
                  <div class="input-group">
                    <input :value='filters.length + " Status"' type="text" class="form-control" disabled>
                    <div class="input-group-btn">
                      <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_status_do_filter_monitoring_do_pending'>
                        <i class="fa fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>   
                <?php $this->load->view('modal/h3_md_status_do_filter_monitoring_do_pending'); ?>
                <script>
                    status_filter = new Vue({
                        el: '#status_filter',
                        data: {
                            filters: []
                        },
                        watch: {
                          filters: function(){
                            monitoring_do_pending.draw();
                          }
                        }
                    })
                </script>
              </div>
          </div>
        </div>
        <table id="monitoring_do_pending" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tgl & Jam DO</th>              
              <th>No. DO</th>              
              <th>Nama Customer</th>              
              <th>Status</th>              
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
                url: "<?= base_url('api/md/h3/monitoring_do_pending') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.status_filter = status_filter.filters;
                  d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                }
            },
            columns: [
              { data: 'index', orderable: false, width: '3%' }, 
              { 
                data: 'created_at',
                render: function(data){
                  return moment(data).format('DD/MM/YYYY HH:mm');
                }
              }, 
              { data: 'id_do_sales_order' }, 
              { 
                data: 'nama_dealer', 
                render: function(data, type, row){
                  return row.nama_dealer + ' [' + row.kode_dealer_md + ']';
                }
              }, 
              { data: 'status' }, 
            ],
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>