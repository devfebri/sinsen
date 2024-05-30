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
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-body">
        <?php if($dealer_h1 != null): ?>
        <div class="container-fluid no-padding">
          <?php $this->load->view('template/session_message.php'); ?>
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label for="" class="control-label">Customer</label>
                <input id='nama_customer_filter' type="text" class="form-control" value='<?= $dealer_h1['kode_dealer_md'] ?> - <?= $dealer_h1['nama_dealer'] ?>' disabled>
              </div>
            </div>
          </div>
        </div>
        <div class="container-fluid no-padding">
          <div class="row">
            <div class="col-sm-12">
              <button onclick='return generate_po()' class='btn btn-flat btn-sm btn-success'>Generate PO</button>
              <script>
                function generate_po(){
                  window.location = 'h3/h3_md_rekap_po_logistik/generate_po_logistik';
                }
              </script>
            </div>
          </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning" role="alert">
          <strong>Perhatian!</strong> Dealer H1 Logistik tidak tersedia.
        </div>
        <?php endif; ?>
        <div class="container-fluid no-padding" style='margin-top: 20px;'>
          <table id="rekap_po_logistik" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No.</th>              
                <th>No. Checker</th>              
                <th>Tanggal Checker</th>              
                <th>No. Shipping List</th>              
                <th>No. Mesin</th>              
                <th>No. Rangka</th>              
                <th>Kode Tipe Unit</th>              
                <th>Keterangan</th>              
                <th>Status</th>              
                <th width='3%'></th>   
              </tr>
            </thead>
            <tbody>    
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              rekap_po_logistik = $('#rekap_po_logistik').DataTable({
                  processing: true,
                  serverSide: true,
                  order: [],
                  scrollX: true,
                  ajax: {
                    url: "<?= base_url('api/md/h3/rekap_po_logistik') ?>",
                    dataSrc: "data",
                    type: "POST",
                  },
                  createdRow: function (row, data, index) {
                    $('td', row).addClass('align-middle');
                  },
                  columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'id_checker' }, 
                    { 
                      data: 'tgl_checker',
                      render: function(data){
                        return moment(data).format('DD/MM/YYYY');
                      }
                    }, 
                    { data: 'no_shipping_list' }, 
                    { data: 'no_mesin' }, 
                    { data: 'no_rangka' }, 
                    { data: 'tipe_motor' }, 
                    { data: 'keterangan' }, 
                    { data: 'status_checker' }, 
                    { data: 'action', orderable: false, className: 'text-center' }, 
                  ],
              });

              $("#rekap_po_logistik").on('change',"input[type='checkbox']",function(e){
                target = $(e.target);
                id_checker = target.attr('data-id');

                if(target.is(':checked')){
                  axios.post('<?= base_url('api/md/h3/rekap_po_logistik/add_session_rekap_po_logistik') ?>', Qs.stringify({
                    id_checker: id_checker
                  }))
                  .catch(function(err){
                    toast.error(err);
                  })
                  .then(function(){
                    rekap_po_logistik.draw(false);
                  });
                }else{
                  axios.post('<?= base_url('api/md/h3/rekap_po_logistik/remove_session_rekap_po_logistik') ?>', Qs.stringify({
                    id_checker: id_checker
                  }))
                  .catch(function(err){
                    toast.error(err);
                  })
                  .then(function(){
                    rekap_po_logistik.draw(false);
                  });
                }
              });
            });
          </script>
          <?php $this->load->view('modal/h3_md_view_modal_po_logistik'); ?>
          <script>
          function view_modal_po_logistik(dokumen_nrfs_id) {
            url = 'iframe/md/h3/h3_md_po_logistik?dokumen_nrfs_id=' + dokumen_nrfs_id;
            $('#view_iframe_po_logistik').attr('src', url);
            $('#h3_md_view_modal_po_logistik').modal('show');
          }
          </script>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
