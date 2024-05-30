<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="https://unpkg.com/vue-select@latest"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
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
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H3</li>
    <li class="">Warehouse Management</li>
    <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
  </ol>
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
          $form = 'proses';
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
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div v-if='mode != "insert"' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Opname</label>
                  <div class="col-sm-4">
                      <input v-model='stock_opname.id_stock_opname' type="text" class="form-control" readonly>  
                  </div>
                </div>
                <div v-if='mode != "insert"' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">NIK PIC</label>
                  <div class="col-sm-4">
                      <input v-model='pic.nik' type="text" class="form-control" readonly data-toggle='modal' :data-target="is_pic_warehouse && mode != 'detail' ? '#pic_stock_opname' : ''" placeholder='Pilih PIC.'>  
                  </div>
                </div>
                <div v-if='mode != "insert"' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama PIC</label>
                  <div class="col-sm-4">
                      <input v-model='pic.nama_lengkap' type="text" class="form-control" readonly data-toggle='modal' :data-target="is_pic_warehouse && mode != 'detail' ? '#pic_stock_opname' : ''" placeholder='Pilih PIC.'>  
                  </div>
                </div>
                <?php $this->load->view('modal/pic_stock_opname') ?>
                <script>
                  function pilih_pic_stock_opname(data) {
                    form_.pic = data;
                  }
                </script>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Gudang</label>
                  <div class="col-sm-4">
                      <input v-model='gudang.id_gudang' type="text" class="form-control" readonly data-toggle='modal' data-target='#gudang_stock_opname'>
                  </div>
                </div>
                <script>
                  function pilih_gudang_stock_opname(gudang) {
                    form_.gudang = gudang;
                  }
                </script>
                <div v-if='!_.isEqual(gudang, {})' class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Opname</label>
                  <div class="col-sm-4">
                      <select name="tipe" class="form-control" v-model="tipe_opname" <?= $mode == 'detail' ? 'disabled' : '' ?>>
                        <option value="">--choose--</option>
                        <option value="Stock Opname">Stock Opname</option>
                        <option value="Cycle Count">Cycle Count</option>
                      </select>
                  </div>
                </div>
                <?php $this->load->view('modal/gudang_stock_opname') ?>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Dibuat</label>
                  <div class="col-sm-4">
                      <input v-model='stock_opname.created_at' type="text" class="form-control" disabled> 
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div class="col-sm-4">
                      <input v-model='stock_opname.status' type="text" class="form-control" disabled> 
                  </div>
                </div>
                <div class="container">
                  <div class="row">
                    <div class="col-sm-2 mr-10">
                        <div class="form-group">
                            <label>ID Part</label>
                            <input type="text" class="form-control" v-model='filter_id_part'>
                        </div>
                    </div>
                    <div class="col-sm-1 mr-10">
                        <div class="form-group">
                            <label>Unit</label>
                            <select class="form-control" v-model='filter_unit'>
                                <option value="">-Choose-</option>
                                <option v-for="e of unit" :value="e">{{ e }}</option>
                            </select>
                        </div>
                    </div>
                  </div>
                </div>
                <table class="table">
                  <tr>
                    <td width='3%'>No.</td>
                    <td>Nomor Parts</td>
                    <td>Deskripsi Parts</td>
                    <td>Gudang</td>
                    <td>Rak</td>
                    <td>Unit</td>
                    <td width='10%'>Qty Actual</td>
                    <td v-if="mode!='detail' && tipe_opname == 'Cycle Count'"></td>
                  </tr>
                    <tr v-if="parts.length > 0" v-for="(part, index) of filtered_parts">
                      <td class="align-middle">{{ index + 1 }}.</td>
                      <td class="align-middle">
                        {{ part.id_part }}
                      </td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td class='align-middle'>
                        <span>{{ part.id_gudang }}</span>
                      </td>
                      <td class='align-middle'>
                        <span>{{ part.id_rak }}</span>
                      </td>
                      <td class='align-middle'>
                        <span>{{ part.unit }}</span>
                      </td>
                      <td>
                        <vue-numeric v-on:keypress.native="input_stock_aktual(index, $event)" v-model='part.stock_aktual' class='form-control' separator='.'></vue-numeric>
                      </td>
                      <td v-if="mode!='detail' && tipe_opname == 'Cycle Count'" class="align-middle" width='3%'>
                        <button class="btn btn-sm btn-flat btn-danger" v-on:click.prevent="hapusPart(index)"><i class="fa fa-trash-o"></i></button>
                      </td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td colspan="8" class="text-center text-muted">Belum ada part</td>
                    </tr>
              </table>
              <button v-if="mode!='detail' && tipe_opname == 'Cycle Count'" type="button" class="margin pull-right btn btn-flat btn-primary btn-sm" data-toggle="modal" data-target="#part_stock_opname"><i class="fa fa-plus"></i></button>
              <?php $this->load->view('modal/part_stock_opname') ?>
              <script>
                function pilih_part_stock_opname(part){
                    form_.parts.push(part);
                }
              </script>
              <div class="box-footer">
                <div class="col-sm-6 no-padding">
                  <button v-if="mode=='detail'" @click.prevent='<?= $form ?>' type="button" class="btn btn-sm btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
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
        is_pic_warehouse: <?= $this->m_admin->is_pic_warehouse() ? 'true' : 'false' ?>,
        filter_id_part: '',
        filter_unit: '',
        loading: false,
        <?php if ($mode == 'detail' or $mode == 'edit'): ?>
        tipe_opname: '<?= $stock_opname->tipe ?>',
        stock_opname: <?= json_encode($stock_opname) ?>,
        parts: <?= json_encode($parts) ?>,
        gudang: <?= json_encode($gudang) ?>,
        pic: <?= $pic != null ? json_encode($pic) : '{}' ?>,
        member: {},
        <?php else: ?>
        parts: [],
        gudang: {},
        tipe_opname: '',
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          post = {};
          post.id_stock_opname = this.stock_opname.id_stock_opname;
          post.parts = _.map(this.parts, function(p){
            return _.pick(p, ['id_part', 'id_gudang', 'id_rak', 'stock', 'stock_aktual'])
          });

          axios.post('dealer/h3_dealer_input_stock_count_result/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            console.log(res);
            window.location = 'dealer/h3_dealer_input_stock_count_result/detail?id=' + res.data.id_stock_opname;
          })
          .catch(function(err){
            toastr.error(err);
          });
        },
        input_stock_aktual: _.debounce(function(index, e){
          part = this.filtered_parts[index];
          post = _.pick(part, ['id_stock_opname', 'id_part', 'id_rak', 'id_gudang', 'stock_aktual']);

          this.loading = true;
          axios.post('dealer/h3_dealer_input_stock_count_result/input_stock_aktual', Qs.stringify(post))
          .then(function(res){
            console.log(res);
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){
            form_.loading = false;
          })
        }, 500),
        hapusPart: function(index){
          this.parts.splice(index, 1);
        }
      },
      computed: {
        unit: function(){
          unique_unit = _.uniqBy(this.parts, function(e){
            return e.unit;
          });

          unique_unit = _.map(unique_unit, function(e){
            return e.unit;
          });

          return unique_unit;
        },
        filtered_parts: function(){
          filter_unit = this.filter_unit;
          filtered =  _.filter(this.parts, function(part){
            if(filter_unit != ''){
              return part.unit == filter_unit;
            }else{
              return true;
            }
          });

          filter_id_part = this.filter_id_part;
          filtered = _.filter(filtered, function(part){
            return part.id_part.toLowerCase().includes(filter_id_part.toLowerCase())
          });
          return filtered;
        }
      },
      watch: {
        gudang: {
          deep: true,
          handler: function(){
            this.parts = [];
            part_stock_opname_datatable.draw();
            if(this.tipe_opname == 'Stock Opname'){
              this.get_all_stock();
            }
          }
        },
        tipe_opname: function(){
          this.parts = [];
          if(this.tipe_opname == 'Stock Opname'){
            this.get_all_stock();
          }
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
          <a href="dealer/<?= $isi ?>/add">
            <!-- <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button> -->
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table id="input_stock_count_result_stock_opname" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID Stock Opname</th>
              <th>Tipe</th>
              <th>Tanggal</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <script>
      $(document).ready(function() {
        input_stock_count_result_stock_opname = $('#input_stock_count_result_stock_opname').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            order: [],
            ajax: {
              url: "<?= base_url('api/dealer/input_stock_count_result_stock_opname') ?>",
              dataSrc: "data",
              type: "POST",
              data: function(data){
              }
            },
            createdRow: function (row, data, index) {
              $('td', row).addClass('align-middle');
            },
            columns: [
                { data: 'id_stock_opname' },
                { data: 'tipe' },
                { data: 'tanggal' },
                { data: 'action' },
            ],
        });
      });
    </script>
    <?php
  }
    ?>
  </section>
</div>