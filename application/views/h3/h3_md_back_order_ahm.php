<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><?= $title; ?></h1>
    <?= $breadcrumb ?>
  </section>
  <section class="content">
    <?php if($set == 'form'): ?>
    <?php 
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
    <?php 
      if($mode == 'detail'):
        $this->load->view('h3/h3_md_back_order_ahm_detail', [
          'form' => $form
        ]);
      elseif($mode == 'po_expired'):
        $this->load->view('h3/h3_md_back_order_ahm_po_expired', [
          'form' => $form
        ]);
      elseif($mode == 'upload'):
        $this->load->view('h3/h3_md_back_order_ahm_upload', [
          'form' => $form
        ]);
      endif; 
    ?>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/upload">
            <button class="btn btn-sm btn-success btn-flat">Upload</button>
          </a>

          <?php if($this->input->get('history') != null): ?>
              <a href="h3/<?= $isi ?>">
                <button class="btn btn-sm bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
              </a>  
              <?php else: ?>
              <a href="h3/<?= $isi ?>?history=true">
                <button class="btn btn-sm bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
              </a> 
      <?php endif; ?>
        </h3>
      </div>
      <div class="box-body">
        <div class="container-fluid no-padding" style='margin-top: 20px;'>
          <table id="back_order_ahm" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No.</th>              
                <th>Tanggal PO</th>              
                <th>Jenis PO</th>              
                <th>No PO</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>No. BO AHM</th>
                <th>Sudah Proses BO</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>    
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              back_order_ahm = $('#back_order_ahm').DataTable({
                  processing: true,
                  serverSide: true,
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/md/h3/back_order_ahm') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function(d){
                        d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                    }
                  },
                  createdRow: function (row, data, index) {
                    $('td', row).addClass('align-middle');
                  },
                  columns: [
                      { data: 'index', orderable: false, width: '3%' },
                      { data: 'tanggal_po' },
                      { data: 'jenis_po' },
                      { data: 'id_purchase_order' },
                      { 
                        data: 'total_amount',
                        render: function(data){
                          return accounting.formatMoney(data, "Rp ", 0, ".", ",");
                        },
                        className: 'text-right'
                      },
                      { data: 'status' },
                      { 
                        data: 'id_purchase_order',
                        render: function(data){
                          if(data != null){
                            return data;
                          }             
                          return '-';             
                        }
                      },
                      { 
                        data: 'sudah_back_order',
                        render: function(data){
                          if(data == 1){
                            return 'Ya';
                          }else if(data == 0){
                            return 'Tidak';
                          }  
                          return '-';             
                        }
                      },
                      { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                  ],
              });
            });
          </script>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
