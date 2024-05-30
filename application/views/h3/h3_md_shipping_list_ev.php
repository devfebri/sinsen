<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
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
    <?php if($set == 'form'): 
      $form     = '';
      $disabled = '';
      $readonly = '';

      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'terima_claim') {
        $form = 'simpan_claim';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      }
    ?>
    <?php endif; ?>


    <?php if($mode=="index"): ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <?php if($this->session->userdata('id_user')==1){ ?> 
          <a href="/mdms/ahmsdeve/v1/accessories_from_md/log_ev">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-history"></i> LOG EV  </button>
            <a href="h3/h3_md_shipping_list_ev/ev_send_status_9" class="btn bg-blue btn-flat margin" title="send api"><i class="fa fa-send-o"></i> ACC 9 </a>
          </a>
          <?php } ?> 
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="shipping_list_ev" class="table table-bordered table-hover">
          <thead>
            <tr>
                <th>No</th>
                <th>No SL</th>              
                <th>Tgl SL</th>    
                <th>Kode MD</th>    
                <th>Box No</th>
                <th>Packing No</th>
                <th>Carton No</th>
                <th>Acc Type</th>
                <th>Part No</th>
                <th>Serial No</th>
                <th>Tgl Data Dikirim</th>
                <!-- <th>From </th> -->
                <th>Tgl Penerimaan MD</th>
                <th>No Penerimaan MD</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

        
        <script>
          $(document).ready(function() {
            pelunasan_bapb = $('#shipping_list_ev').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/shipping_list_ev') ?>",
                  dataSrc: "data",
                  type: "POST",
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'no_shipping_list' },
                    { data: 'tgl_shipping_list' },
                    { data: 'kode_dealer_md' },
                    { data: 'box_id'},
                    { data: 'packing_id' },
                    { data: 'carton_id' },
                    { data: 'acc_tipe' },
                    { data: 'part_id' },
                    { data: 'serial_number' },
                    { data: 'tgl_dicreate_ahm' },
                    // { data: 'data_from' },
                    { data: 'created_at_penerimaan_md' },
                    { data: 'no_penerimaan_barang_md' },
                ],
            });

          });
        </script>
     
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>