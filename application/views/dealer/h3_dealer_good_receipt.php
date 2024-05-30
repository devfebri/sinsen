<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <?= $breadcrumb ?>
</section>
  <section class="content">
<?php
  if ($set=="form") {
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='insert') {
          $form = 'save';
      }
      if ($mode=='detail') {
          $disabled = 'disabled';
          $form = 'detail';
      }
      if ($mode=='edit') {
          $form = 'update';
      } ?>
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div v-if='mode == "detail"' class="form-group">
                  <label class="col-sm-2 control-label">Good Receipt</label>
                  <div class="col-sm-3">
                      <input v-model='good_receipt.id_good_receipt' type="text" class="form-control" disabled>  
                  </div>
                  <label class="col-sm-2 control-label">Nomor Shipping List</label>
                  <div class="col-sm-3">
                      <input v-model='good_receipt.nomor_shipping_list' type="text" class="form-control" disabled>  
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Nomor Penerimaan</label>
                  <div class="col-sm-3">
                      <input v-model='good_receipt.nomor_penerimaan' type="text" class="form-control" disabled>  
                  </div>
                  <label class="col-sm-2 control-label">Tanggal Penerimaan</label>
                  <div class="col-sm-3">
                      <input v-model='good_receipt.tanggal_penerimaan' type="text" class="form-control" disabled>  
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Tipe Referensi</label>
                  <!-- <label v-if='mode == "detail"' class="col-sm-2 control-label">Tipe Referensi</label> -->
                  <div v-bind:class="{ 'has-error': _.get(errors, 'ref_type') != null }" class="col-sm-3">
                      <input type="text" class="form-control" value='Shipping List' readonly>
                      <small v-if='_.get(errors, "ref_type") != null' class="form-text text-danger">{{ errors.ref_type }}</small>
                  </div>
                  <label class="col-sm-2 control-label">Nomor Referensi</label>
                  <div v-if='ref_type != ""' v-bind:class="{ 'has-error': _.get(errors, 'id_referensi') != null }" class="col-sm-3">
                    <input v-model='good_receipt.id_reference' readonly type="text" class="form-control">
                    <small v-if='_.get(errors, "id_referensi") != null' class="form-text text-danger">{{ errors.id_referensi }}</small>
                  </div>
                  <div v-if='ref_type != "" && mode != "detail"' class="col-sm-1 no-padding">
                    <button type='button' data-toggle='modal' data-target='#referensi_good_receipt' class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">No. Purchase Order</label>
                  <div class="col-sm-3">
                      <input v-model='good_receipt.nomor_purchase_order' type="text" class="form-control" disabled>  
                  </div>
                  <label class="col-sm-2 control-label">Tanggal Purchase Order</label>
                  <div class="col-sm-3">
                      <input v-model='good_receipt.tanggal_purchase_order' type="text" class="form-control" disabled>  
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label">Jenis Purchase Order</label>
                  <div class="col-sm-3">
                      <input v-model='good_receipt.jenis_purchase_order' type="text" class="form-control" disabled>  
                  </div>
                  <label class="col-sm-2 control-label">No. Faktur</label>
                  <div class="col-sm-3">
                      <input v-model='good_receipt.no_faktur' type="text" class="form-control" disabled>  
                  </div>
                </div>
                <table class="table table-striped">
                  <tr class='bg-blue-gradient'>
                    <td width="3%">No.</td>
                    <td width="10%">Nomor Parts</td>
                    <td width="10%">Deskripsi Parts</td>
                    <td width="5%">Qty</td>
                    <td width="10%">Harga Beli</td>
                    <td width="8%">UoM</td>
                    <td width="12%">Gudang</td>
                    <td width="12%">Rak</td>
                  </tr>
                    <tr v-if="parts.length > 0" v-for="(part, index) of parts">
                      <td class="align-middle">{{ index + 1 }}.</td>
                      <td class="align-middle">{{ part.id_part }}</td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td class="align-middle">
                        <vue-numeric :read-only="true" class="form-control form-control-sm" thousand-separator="." v-model="part.qty"/>
                      </td>
                      <td class="align-middle">{{ part.harga_beli_md }}</td>
                      <td class="align-middle">{{ part.satuan }}</td>
                      <td class="align-middle">
                        {{ part.id_gudang }}
                      </td>
                      <td class="align-middle">
                        {{ part.id_rak }}
                      </td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td colspan="8" class="text-center text-muted">Belum ada part</td>
                    </tr>
              </table>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        kosong :'',
        mode : '<?= $mode ?>',
        loading: false,
        errors: {},
        indexPart: 0,
        good_receipt: <?= json_encode($good_receipt) ?>,
        parts: <?= json_encode($good_receipt_parts) ?>,
        ref_type: '<?= $good_receipt->ref_type ?>',
        referensi: <?= $referensi != null ? json_encode($referensi) : '{}' ?>,
      },
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-body">
        <table id="good_receipt_shipping_list" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Good Receipt</th>
              <th>Tanggal Good Receipt</th>
              <th>No. Penerimaan</th>
              <th>Tanggal Penerimaan</th>
              <th>No. Shipping List</th>
              <th>Tanggal Shipping List</th>
              <th>Nomor Po</th>
              <th>No. Invoice</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            good_receipt_shipping_list = $('#good_receipt_shipping_list').DataTable({
                initComplete: function() {
                  $('#good_receipt_shipping_list_length').parent().removeClass('col-sm-6').addClass('col-sm-2');
                  $('#good_receipt_shipping_list_filter').parent().removeClass('col-sm-6').addClass('col-sm-10');
                  axios.get('html/filter_good_receipt_shipping_list')
                      .then(function(res) {
                          $('#good_receipt_shipping_list_filter').prepend(res.data);
                          $('#filter_tipe_po').change(function() {
                              good_receipt_shipping_list.draw();
                          });
                      });
                },
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "<?= base_url('api/dealer/good_receipt_shipping_list') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function(data){
                      data.filter_tipe_po = $('#filter_tipe_po').val();
                      start_date = $('#filter_good_receipt_date_start').val();
                        end_date = $('#filter_good_receipt_date_end').val();
                        if ((start_date != undefined && start_date != '') && (end_date != undefined && end_date != '')) {
                            data.filter_good_receipt_date = true;
                            data.start_date = start_date;
                            data.end_date = end_date;
                        }
                    }
                },
                columns: [
                    { data: 'index', width: '3%', orderable: false },
                    { data: 'id_good_receipt' },
                    { data: 'tanggal_receipt' },
                    { data: 'id_penerimaan_barang' },
                    { data: 'tanggal_penerimaan' },
                    { data: 'id_packing_sheet' },
                    { data: 'tanggal_packing_sheet' },
                    { data: 'nomor_po' },
                    { data: 'no_faktur' },
                    { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                ],
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
  }
    ?>
  </section>
</div>