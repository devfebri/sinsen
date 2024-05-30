
<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script src="<?= base_url("assets/vue/custom/vb-rangedatepicker.js") ?>"></script> 
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
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Kelompok Part</label>
                  <div v-bind:class="{ 'has-error': errors.id_kelompok_part != null }" class="col-sm-4">
                    <input :readonly='mode == "detail" || kelompok_part.created_manually == 0' v-model='kelompok_part.id_kelompok_part' type="text" class="form-control">
                    <small v-if='errors.id_kelompok_part != null' class="form-text text-danger">{{ errors.id_kelompok_part }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Part</label>
                  <div v-bind:class="{ 'has-error': errors.kelompok_part != null }" class="col-sm-4">
                    <input :readonly='mode == "detail"' v-model='kelompok_part.kelompok_part' type="text" class="form-control">
                    <small v-if='errors.kelompok_part != null' class="form-text text-danger">{{ errors.kelompok_part }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Expired Keep Stok</label>
                  <div v-bind:class="{ 'has-error': errors.start_date != null }" class="col-sm-4">
                    <range-date-picker class='form-control' @apply-date='applyDateExpiredKeepStock' @cancel-date='cancelDateExpiredKeepStock' readonly></range-date-picker>
                    <small v-if='errors.start_date != null' class="form-text text-danger">{{ errors.start_date }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Proses Bagi Barang</label>
                  <div v-bind:class="{ 'has-error': errors.proses_barang_bagi != null }" class="col-sm-4">
                    <select :disabled='mode == "detail"' class="form-control" v-model='kelompok_part.proses_barang_bagi'>
                      <option value="">-Choose-</option>
                      <option value='1'>Ya</option>
                      <option value='0'>Tidak</option>
                    </select>
                    <small v-if='errors.proses_barang_bagi != null' class="form-text text-danger">{{ errors.proses_barang_bagi }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keep Stock Toko (%)</label>
                  <div v-bind:class="{ 'has-error': errors.keep_stock_toko != null }" class="col-sm-4">
                    <vue-numeric :disabled='mode == "detail"' class="form-control" :max='100' v-model='kelompok_part.keep_stock_toko' currency-symbol-position='suffix' currency='%'></vue-numeric>
                    <small v-if='errors.keep_stock_toko != null' class="form-text text-danger">{{ errors.keep_stock_toko }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Keep Stock Dealer Non Fix (%)</label>
                  <div v-bind:class="{ 'has-error': errors.keep_stock_dealer != null }" class="col-sm-4">
                    <vue-numeric :disabled='mode == "detail"' class="form-control" :max='100' v-model='kelompok_part.keep_stock_dealer' currency-symbol-position='suffix' currency='%'></vue-numeric>
                    <small v-if='errors.keep_stock_dealer != null' class="form-text text-danger">{{ errors.keep_stock_dealer }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keep Stock Dealer Fix (%)</label>
                  <div v-bind:class="{ 'has-error': errors.keep_stock_dealer_fix != null }" class="col-sm-4">
                    <vue-numeric :disabled='mode == "detail"' class="form-control" :max='100' v-model='kelompok_part.keep_stock_dealer_fix' currency-symbol-position='suffix' currency='%'></vue-numeric>
                    <small v-if='errors.keep_stock_dealer_fix != null' class="form-text text-danger">{{ errors.keep_stock_dealer_fix }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Include PPN</label>
                  <div v-bind:class="{ 'has-error': errors.include_ppn != null }" class="col-sm-4">
                    <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='kelompok_part.include_ppn'>
                    <small v-if='errors.include_ppn != null' class="form-text text-danger">{{ errors.include_ppn }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Termasuk Plastik Part?</label>
                  <div v-bind:class="{ 'has-error': errors.plastik_part != null }" class="col-sm-4">
                    <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='kelompok_part.plastik_part'>
                    <small v-if='errors.plastik_part != null' class="form-text text-danger">{{ errors.plastik_part }}</small>
                  </div>
                </div>
                <div v-if='mode != "insert"' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Active</label>
                  <div v-bind:class="{ 'has-error': errors.active != null }" class="col-sm-4">
                    <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='kelompok_part.active'>
                    <small v-if='errors.active != null' class="form-text text-danger">{{ errors.active }}</small>
                  </div>
                </div>
                <div v-if='mode != "insert"' class="container-fluid">
                  <div class="row">
                    <div class="col-sm-2">
                      <div class="form-group">
                        <label for="" class="control-label">Part Number</label>
                        <input type="text" class="form-control" v-model='filter_part_number'>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-if='mode != "insert"' class="container-fluid">
                  <div class="row">
                    <div class="col-sm-12 no-padding">
                      <table class="table table-condensed">
                        <tr>
                          <td width='3%'>No.</td>
                          <td>Kode Part</td>
                          <td>Part Deskripsi</td>
                          <td>HET</td>
                          <td>Status</td>
                          <td>Stok AVS</td>
                          <td>M-1</td>
                          <td>M-2</td>
                          <td>M-3</td>
                          <td>M-4</td>
                          <td>M-5</td>
                          <td>M-6</td>
                          <td width='10%'>Qty Keep Stok</td>
                          <td v-if='mode != "detail"' width='3%'></td>
                        </tr>
                        <tr v-if='items.length > 0' v-for='(item, index) of filtered_items'>
                          <td width='3%'>{{ index + 1 }}.</td>
                          <td>{{ item.id_part }}</td>
                          <td>{{ item.nama_part }}</td>
                          <td>{{ item.het }}</td>
                          <td>{{ item.status }}</td>
                          <td>
                            <vue-numeric read-only v-model='item.stock_avs' separator='.'></vue-numeric>
                          </td>
                          <td>
                            <vue-numeric read-only v-model='item.m_1' separator='.'></vue-numeric>
                          </td>
                          <td>
                            <vue-numeric read-only v-model='item.m_2' separator='.'></vue-numeric>
                          </td>
                          <td>
                            <vue-numeric read-only v-model='item.m_3' separator='.'></vue-numeric>
                          </td>
                          <td>
                            <vue-numeric read-only v-model='item.m_4' separator='.'></vue-numeric>
                          </td>
                          <td>
                            <vue-numeric read-only v-model='item.m_5' separator='.'></vue-numeric>
                          </td>
                          <td>
                            <vue-numeric read-only v-model='item.m_6' separator='.'></vue-numeric>
                          </td>
                          <td>
                            <vue-numeric :read-only='mode == "detail"' class='form-control' v-model='item.qty_keep_stock' separator='.'></vue-numeric>
                          </td>
                          <td v-if='mode != "detail"' width='3%'>
                            <button class="btn btn-flat btn-danger" @click.prevent='hapus_item(index)'><i class="fa fa-trash-o"></i></button>
                          </td>
                        </tr>
                        <tr v-if='filtered_items.length < 1'>
                          <td class='text-center' colspan='13'>Tidak ada data</td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-12 no-padding text-right">
                      <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_part_kelompok_part'><i class="fa fa-plus"></i></button>
                    </div>
                    <?php $this->load->view('modal/h3_md_part_kelompok_part'); ?>
                    <script>
                      function pilih_part_kelompok_part(data){
                        form_.items.push(data);
                      }
                    </script>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
      <div class="box-footer">
        <div class="col-sm-6">
          <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" type='button' @click.prevent='<?= $form ?>'>Simpan</button>
          <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" type='button' @click.prevent='<?= $form ?>'>Update</button>
          <a v-if='mode == "detail"' :href="'h3/kelompok_part/edit?id=' + kelompok_part.id" class="btn btn-sm btn-flat btn-warning">Edit</a>
        </div>
      </div>
    </div>
<script>
  form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        loading: false,
        errors: {},
        filter_part_number: '',
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        kelompok_part: <?= json_encode($kelompok_part) ?>,
        items: <?= json_encode($items) ?>,
        <?php else: ?>
        kelompok_part: {
          id_kelompok_part: '',
          kelompok_part: '',
          include_ppn: 0,
          plastik_part: 0, 
          keep_stock_toko: '',
          keep_stock_dealer: '',
          keep_stock_dealer_fix: '',
          keep_stock_hotline: '',
          proses_barang_bagi: 1,
          start_date: '',
          end_date: '',
          active: 1
        },
        items: [],
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          this.errors = {};
          this.loading = true;

          post = _.pick(this.kelompok_part, [
            'id', 'id_kelompok_part', 'kelompok_part', 'start_date', 'end_date', 
            'proses_barang_bagi', 'keep_stock_toko', 'keep_stock_dealer', 'keep_stock_dealer_fix', 'keep_stock_hotline',
            'active', 'include_ppn', 'plastik_part'
          ]);

          post.items = _.map(this.items, function(item){
            return _.pick(item, ['qty_keep_stock', 'id_part', 'id_part_int']);
          });

          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            data = res.data;

            if(data.redirect_url){
              window.location = data.redirect_url;
            }
          })
          .catch(function(err){
            form_.loading = false;

            data = err.response.data;
            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
              toastr.error(data.message);
            }else{
              toastr.error(err);
            }
          });
        },
        hapus_item: function(index){
          this.items.splice(index, 1);
        },
        applyDateExpiredKeepStock: function(picker){
          this.start_date = picker.startDate.format('YYYY-MM-DD');
          this.end_date = picker.endDate.format('YYYY-MM-DD');
          detail_stock.draw();
        },
        cancelDateExpiredKeepStock: function(picker){
          this.start_date = '';
          this.end_date = '';
        },
      },
      computed: {
        filtered_items: function(){
          filter_part_number = this.filter_part_number;
          return _.chain(this.items)
          .filter(function(data){
            if(filter_part_number != ''){
              return data.id_part.toUpperCase().includes(filter_part_number.toUpperCase());
            }
            return true;
          })
          .value();
        }
      }
  });
</script>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/kelompok_part/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="master_kelompok_part" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Kode Kelompok Part</th>
              <th>Nama Kelompok Part</th>
              <th>Satuan</th>
              <th>Active</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            master_kelompok_part = $('#master_kelompok_part').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_kelompok_part') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(data){
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'id_kelompok_part' },
                    { data: 'kelompok_part' },
                    { data: 'satuan' },
                    { data: 'active', orderable: false, width: '3%', className: 'text-center' },
                    { data: 'action', orderable: false, width: '3%', className: 'text-center' },
                ],
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php } ?>
  </section>
</div>