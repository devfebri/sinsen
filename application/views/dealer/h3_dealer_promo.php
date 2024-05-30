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

<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>

<script>
  Vue.use(VueNumeric.default);
</script>
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
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama</label>
                  <div class="col-sm-4">
                      <input v-model='master.nama' type="text" class="form-control" :readonly='mode == "detail"'> 
                  </div>
                  <div v-if='mode != "insert"'>
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Promo</label>
                    <div class="col-sm-4">
                        <input v-model='master.id_promo' type="text" class="form-control" readonly> 
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode Promo</label>
                  <div class="col-sm-4">
                      <input id='periode_promo' type="text" class="form-control" readonly> 
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Promo</label>
                  <div class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='master.tipe_promo'>
                        <option value="">-Choose-</option>
                        <option value="Standar">Standar</option>
                        <option value="Bundling">Bundling</option>
                        <option value="Bertingkat">Bertingkat</option>
                        <option value="Paket">Paket</option>
                      </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Mekanisme Promo</label>
                  <div class="col-sm-4">
                      <textarea rows="5" class="form-control" v-model='master.mekanisme_promo' :readonly='mode == "detail"'></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label no-padding">Hadiah Per Item</label>
                  <div class="col-sm-4">
                      <input v-model='master.hadiah_per_item' type="checkbox" true-value='1' false-value='0'>
                  </div>
                  <div v-if='master.tipe_promo == "Paket"'>
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Minimal Pembelian</label>
                    <div class="col-sm-4">
                        <vue-numeric :disabled='mode == "detail"' class="form-control" currency='Rp' separator='.' v-model='master.minimal_pembelian'></vue-numeric>
                    </div>
                  </div>
                  <div v-if='master.tipe_promo == "Bundling"'>
                    <label v-bind:class="{ 'col-sm-offset-6': master.tipe_promo == 'Bundling' }" for="inputEmail3" class="col-sm-2 control-label no-padding">Diskon Bundling</label>
                    <div class="col-sm-2">
                      <select :disabled='mode == "detail"' class="form-control" v-model='master.tipe_diskon_master'>
                          <option value="">-Choose-</option>
                          <option value="Percentage">Percentage</option>
                          <option value="Value">Value</option>
                          <option value="Foc">Foc</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                      <input :disabled='mode == "detail"' type="text" class="form-control" v-model='master.diskon_value_master'>
                    </div>
                  </div>
                </div>
                <div v-if='master.hadiah_per_item == 0' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Hadiah</label>
                  <div class="col-sm-4">
                      <button class="btn btn-flat btn-primary btn-sm" @click.prevent='detail_hadiah_master'>Show Detail</button>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label no-padding">Promo untuk Kelompok Part</label>
                  <div class="col-sm-4">
                      <input v-model='master.promo_untuk_kelompok_part' type="checkbox" true-value='1' false-value='0'>
                  </div>
                </div>
                <table v-show='master.tipe_promo != ""' class="table table-condensed">
                  <tr>
                    <td class="align-middle" width='3%'>No.</td>
                    <td v-if='master.promo_untuk_kelompok_part == 1' class="align-middle">Kelompok Part</td>
                    <td v-if='master.promo_untuk_kelompok_part == 0' class="align-middle">Part Number</td>
                    <td v-if='master.promo_untuk_kelompok_part == 0' class="align-middle">Part Deskripsi</td>
                    <td v-if='master.tipe_promo != "Paket" && master.tipe_promo != "Standar"' class="align-middle">Qty</td>
                    <td v-if='master.tipe_promo != "Bundling"' class="align-middle">Tipe Disc</td>
                    <td v-if='master.tipe_promo != "Bundling"' class="align-middle">Disc Value</td>
                    <td v-if='master.hadiah_per_item == 1' class="align-middle">Hadiah</td>
                    <td v-if='mode != "detail"' class="align-middle" width='3%'></td>
                  </tr>
                  <tr v-if='parts_promo.length > 0' v-for='(e, index) in parts_promo'>
                    <td class="align-middle">{{ index + 1 }}.</td>
                    <td v-if='master.promo_untuk_kelompok_part == 1' class="align-middle">
                      <input :disabled='mode == "detail"' readonly type="text" class="form-control" v-model='e.kelompok_part'>
                    </td>
                    <td v-if='master.promo_untuk_kelompok_part == 0' class="align-middle">
                      <input :disabled='mode == "detail"' readonly type="text" class="form-control" v-model='e.id_part'>
                    </td>
                    <td v-if='master.promo_untuk_kelompok_part == 0' class="align-middle">
                      <input :disabled='mode == "detail"' readonly type="text" class="form-control" v-model='e.nama_part'>
                    </td>
                    <td v-if='master.tipe_promo != "Paket" && master.tipe_promo != "Standar"' class="align-middle">
                      <vue-numeric :disabled='mode == "detail"' class="form-control" separator='.' v-model='e.qty'></vue-numeric>
                    </td>
                    <td v-if='master.tipe_promo != "Bundling"' class="align-middle">
                      <select :disabled='mode == "detail"' class="form-control" v-model='e.tipe_disc'>
                        <option value="">-Choose-</option>
                        <option value="Percentage">Percentage</option>
                        <option value="Value">Value</option>
                        <option value="Foc">Foc</option>
                      </select>
                    </td>
                    <td v-if='master.tipe_promo != "Bundling"' class="align-middle">
                      <vue-numeric :disabled='mode == "detail"' class="form-control" separator='.' v-model='e.disc_value'></vue-numeric>
                    </td>
                    <td v-if='master.hadiah_per_item == 1' class="align-middle">
                      <button class="btn btn-flat btn-sm btn-primary" @click.prevent='detail_hadiah(index)'><i class="fa fa-gift"></i></button>
                    </td>
                    <td v-if='mode != "detail"' class="align-middle">
                      <button @click.prevent='remove_part_promo(index)' class="btn btn-sm btn-flat btn-danger"><i class="fa fa-trash-o"></i></button>
                    </td>
                  <tr v-if='parts_promo.length < 1'>
                    <td class="align-middle text-center" colspan='7'>Tidak ada data</td>
                  </tr>
                  <tr v-if='mode != "detail"'>
                    <td class="align-middle"></td>
                    <td v-if='master.promo_untuk_kelompok_part == 1' class="align-middle">
                      <input :disabled='mode == "detail"' type="text" class="form-control" v-model='part_promo.kelompok_part' data-toggle='modal' data-target='#kelompok_parts_promo' readonly>
                    </td>
                    <td v-if='master.promo_untuk_kelompok_part == 0' class="align-middle">
                      <input :disabled='mode == "detail"' type="text" class="form-control" v-model='part_promo.id_part' data-toggle='modal' data-target='#parts_promo' readonly>
                    </td>
                    <td v-if='master.promo_untuk_kelompok_part == 0' class="align-middle">
                      <input :disabled='mode == "detail"' type="text" class="form-control" v-model='part_promo.nama_part' data-toggle='modal' data-target='#parts_promo' readonly>
                    </td>
                    <td v-if='master.tipe_promo != "Paket" && master.tipe_promo != "Standar"' class="align-middle">
                      <input :disabled='mode == "detail"' type="text" class="form-control" v-model='part_promo.qty'>
                    </td>
                    <td v-if='master.tipe_promo != "Bundling"' class="align-middle">
                      <select class="form-control" v-model='part_promo.tipe_disc'>
                        <option value="">-Choose-</option>
                        <option value="Percentage">Percentage</option>
                        <option value="Value">Value</option>
                        <option value="Foc">Foc</option>
                      </select>
                    </td>
                    <td v-if='master.tipe_promo != "Bundling"' class="align-middle">
                      <input type="text" class="form-control" v-model='part_promo.disc_value'>
                    </td>
                    <td v-if='master.hadiah_per_item == 1' class="align-middle">
                      <button class="btn btn-sm btn-flat btn-primary" type='button' @click.prevent='detail_hadiah_single'><i class="fa fa-gift"></i></button>
                    </td>
                    <td class="align-middle">
                      <button @click.prevent='add_part_promo' class="btn btn-sm btn-flat btn-primary"><i class="fa fa-plus"></i></button>
                    </td>
                  </tr>
                </table>
                <?php $this->load->view('modal/parts_promo') ?>
                <script>
                  function pilih_parts_promo(data){
                    form_.part_promo.id_part = data.id_part;
                    form_.part_promo.nama_part = data.nama_part;
                  }
                </script>

                <?php $this->load->view('modal/kelompok_parts_promo') ?>
                <script>
                  function pilih_kelompok_parts_promo(data){
                    form_.part_promo.kelompok_part = data.kelompok_part;
                  }
                </script>
                <?php $this->load->view('modal/detail_hadiah_promo_master') ?>
                <?php $this->load->view('modal/add_hadiah_promo_master') ?>
                <?php $this->load->view('modal/part_ahass_untuk_promo_master') ?>
                <script>
                  function pilih_part_ahass_untuk_promo_master(data){
                    form_.gift_master_single.nama_hadiah = data.nama_part;
                    form_.gift_master_single.id_part = data.id_part;
                  }
                </script>
                <?php $this->load->view('modal/detail_hadiah_promo') ?>
                <?php $this->load->view('modal/add_hadiah_promo') ?>
                <?php $this->load->view('modal/part_ahass_untuk_promo') ?>
                <script>
                  function pilih_part_ahass_untuk_promo(data){
                    form_.gift.nama_hadiah = data.nama_part;
                    form_.gift.id_part = data.id_part;
                  }
                </script>
                <?php $this->load->view('modal/detail_hadiah_promo_single') ?>
                <?php $this->load->view('modal/add_hadiah_promo_single') ?>
                <?php $this->load->view('modal/part_ahass_untuk_promo_single') ?>
                <script>
                  function pilih_part_ahass_untuk_promo_single(data){
                    form_.gift_single.nama_hadiah = data.nama_part;
                    form_.gift_single.id_part = data.id_part;
                  }
                </script>
              <div class="box-footer">
                <div class="col-sm-6">
                  <a v-if='mode == "detail"' :href="'dealer/h3_dealer_promo/edit?id_promo=' + master.id_promo" class="btn btn-flat btn-warning btn-sm">Edit</a>
                  <button :disabled='parts_promo.length == 0' v-if="mode=='insert'" @click.prevent='<?= $form ?>' class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button :disabled='parts_promo.length == 0' v-if="mode=='edit'" @click.prevent='<?= $form ?>' type="submit" class="btn btn-warning btn-flat"><i class="fa fa-save"></i> Update</button>
                </div>
              </div><!-- /.box-footer -->
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
        index_part: 0,
        loading: false,
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        master: <?= $master != null ? json_encode($master): '{}' ?>,
        parts_promo: <?= $parts_promo != null ? json_encode($parts_promo) : '[]' ?>,
        <?php else: ?>
        master: {
          nama: '',
          start_date: '<?= date('Y-m-d') ?>',
          end_date: '<?= date('Y-m-d') ?>',
          tipe_promo: '',
          mekanisme_promo: '',
          minimal_pembelian: '',
          promo_untuk_kelompok_part: 0,
          hadiah_per_item: 0,
          tipe_diskon_master: '',
          diskon_value_master: '',
          gifts: []
        },
        parts_promo: [],
        <?php endif; ?>
        gift_master_single: {
          part_ahass: 0,
          id_part: '',
          nama_hadiah: '',
          qty_hadiah: '',
        },
        gift_single: {
          part_ahass: 0,
          id_part: '',
          nama_hadiah: '',
          qty_hadiah: '',
        },
        gifts_single: [],
        gift: {
          part_ahass: 0,
          id_part: '',
          nama_hadiah: '',
          qty_hadiah: '',
        },
        part_promo: {
          kelompok_part: '',
          id_part: '',
          nama_part: '',
          minimal_pembelian: 0,
          qty: 0,
          tipe_disc: '',
          disc_value: '',
          gifts: [],
        },
      },
      methods: {
        <?= $form ?>: function(){
          post = _.pick(this.master, ['id_promo', 'nama','start_date','end_date','tipe_promo','minimal_pembelian','kelompok_part', 'mekanisme_promo', 'hadiah_per_item','promo_untuk_kelompok_part',]);
          post.gifts = _.map(this.master.gifts, function(g){
            return _.pick(g, ['part_ahass', 'id_part', 'nama_hadiah', 'qty_hadiah']);
          });
          post.tipe_diskon_master = this.master.tipe_diskon_master;
          post.diskon_value_master = this.master.diskon_value_master;
          post.parts_promo = _.chain(this.parts_promo).map(function(e){
            return _.pick(e, ['id_part', 'qty', 'tipe_disc', 'disc_value', 'gifts', 'kelompok_part']);
          }).value();


          this.loading = true;
          axios.post('dealer/h3_dealer_promo/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            window.location = 'dealer/h3_dealer_promo/detail?id_promo=' + res.data.id_promo;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){
            form_.loading = false;
          })

          console.log(post);
        },
        detail_hadiah_master: function(){
          $('#detail_hadiah_promo_master').modal('show');
        },
        detail_hadiah: function(index){
          this.index_part = index;
          $('#detail_hadiah_promo').modal('show');
        },
        detail_hadiah_single: function(index){
          $('#detail_hadiah_promo_single').modal('show');
        },
        tambahkan_hadiah_master: function(){
          this.master.gifts.push(this.gift_master_single);
          this.gift_master_single = {
            part_ahass: 0,
            id_part: '',
            nama_hadiah: '',
            qty_hadiah: '',
          };
          $('#add_hadiah_promo_master').modal('hide');
        },
        tambahkan_hadiah: function(){
          this.parts_promo[this.index_part].gifts.push(this.gift);
          this.gift = {
            part_ahass: 0,
            id_part: '',
            nama_hadiah: '',
            qty_hadiah: '',
          };
          $('#add_hadiah_promo').modal('hide');
        },
        tambahkan_hadiah_single: function(){
          this.gifts_single.push(this.gift_single);
          this.gift_single = {
            part_ahass: 0,
            id_part: '',
            nama_hadiah: '',
            qty_hadiah: '',
          };
          $('#add_hadiah_promo_single').modal('hide');
        },
        tambahkan_hadiah: function(){
          this.parts_promo[this.index_part].gifts.push(this.gift);
          this.gift = {
            part_ahass: 0,
            id_part: '',
            nama_hadiah: '',
            qty_hadiah: '',
          };
          $('#add_hadiah_promo').modal('hide');
        },
        hapus_gift_master: function(index){
          this.master.gifts.splice(index, 1);
        },
        hapus_gift: function(index){
          this.parts_promo[this.index_part].gifts.splice(index, 1);
        },
        hapus_gift_single: function(index){
          this.gifts_single.splice(index, 1);
        },
        add_part_promo: function(){
          part_promo = {
            kelompok_part: this.part_promo.kelompok_part,
            id_part: this.part_promo.id_part,
            nama_part: this.part_promo.nama_part,
            minimal_pembelian: this.part_promo.minimal_pembelian,
            qty: this.part_promo.qty,
            tipe_disc: this.part_promo.tipe_disc,
            disc_value: this.part_promo.disc_value,
            gifts: this.gifts_single
          };

          this.parts_promo.push(part_promo);

          this.part_promo = {
            kelompok_part: '',
            id_part: '',
            nama_part: '',
            minimal_pembelian: 0,
            qty: 0,
            tipe_disc: '',
            disc_value: '',
            gifts: []
          };

          this.gifts_single = [];
        },
        remove_part_promo: function(index){
          this.parts_promo.splice(index, 1);
        }
      },
      watch: {
        master: {
          deep: true,
          handler: function(newVal, oldVal){
            console.log(newVal, oldVal);
            if(newVal.tipe_promo != oldVal.tipe_promo){
              this.parts_promo = [];
            }
          }
        }
      },
      mounted: function(){
        config = {
          opens: 'left',
          autoUpdateInput: this.mode == 'detail' || this.mode == 'edit',
          locale: {
            format: 'DD/MM/YYYY'
          }
        };

        if(this.mode == 'detail' || this.mode == 'edit'){
          config.startDate = new Date(this.master.start_date);
          config.endDate = new Date(this.master.end_date);
        }

        periode_promo = $('#periode_promo').daterangepicker(config).on('apply.daterangepicker', function(ev, picker) {
          form_.master.start_date = picker.startDate.format('YYYY-MM-DD');
          form_.master.end_date = picker.endDate.format('YYYY-MM-DD');
          $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        }).on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
        });
      }
  });

</script>

    <?php

  } elseif ($set=="index") {

      ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="promo" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>ID Promo</th>
              <th>Tipe Promo</th>
              <th>Nama Promo</th>
              <th>Tanggal Mulai Promo</th>
              <th>Tanggal Selesai Promo</th>
              <th>Diskon</th>
              <th>Gimmick</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
        $(document).ready(function() {
              promo = $('#promo').DataTable({
                  initComplete: function() {
                  },
                  processing: true,
                  serverSide: true,
                  order: [],
                  ajax: {
                      url: "<?= base_url('api/dealer/promo') ?>",
                      dataSrc: "data",
                      type: "POST",
                      data: function(data) {
                      }
                  },
                  createdRow: function(row, data, index) {
                      $('td', row).addClass('align-middle');
                  },
                  columns: [
                      { data: 'index', orderable: false, width: '3%' },
                      { data: 'id_promo' },
                      { data: 'tipe_promo' },
                      { data: 'nama' },
                      { data: 'start_date' },
                      { data: 'end_date' },
                      { 
                        data: 'diskon', 
                        render: function ( data, type, row ) {
                          if(data == 1){
                            return '<i class="fa fa-check"></i>';
                          }
                          return '<i class="fa fa-close"></i>';
                        },
                        width: '3%',
                        orderable: false,
                      },
                      {
                        data: 'gimmick', 
                        render: function ( data, type, row ) {
                          if(data == 1){
                            return '<i class="fa fa-check"></i>';
                          }
                          return '<i class="fa fa-close"></i>';
                        },
                        width: '3%',
                        orderable: false,
                      },
                      { data: 'action', width: '3%', orderable: false, className: 'text-center' },
                  ],
              });
            });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<?php } ?>
  </section>
</div>