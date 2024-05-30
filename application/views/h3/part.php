<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
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
              <form  class="form-horizontal" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                <h4>
                  <b>Masukkan data <?= $title ?></b>
                </h4>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Part</label>
                  <div v-bind:class="{ 'has-error': errors.id_part != null }" class="col-sm-4">
                    <input :readonly='mode != "insert"' v-model='part.id_part' type="text" class="form-control">
                    <small v-if='errors.id_part != null' class="form-text text-danger">{{ errors.id_part }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Part</label>
                  <div v-bind:class="{ 'has-error': errors.nama_part != null }" class="col-sm-4">
                    <input :readonly='mode == "detail"' v-model='part.nama_part' type="text" class="form-control">
                    <small v-if='errors.nama_part != null' class="form-text text-danger">{{ errors.nama_part }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Link Superseed</label>
                  <div class="col-sm-4">
                    <input :readonly='mode == "detail"' v-model='part.superseed' type="text" class="form-control">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Minimal Order</label>
                  <div v-bind:class="{ 'has-error': errors.minimal_order != null }" class="col-sm-4">
                    <input :readonly='mode == "detail"' v-model='part.minimal_order' type="text" class="form-control">
                    <small v-if='errors.minimal_order != null' class="form-text text-danger">{{ errors.minimal_order }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Vendor</label>
                  <div v-bind:class="{ 'has-error': errors.kelompok_vendor != null }" class="col-sm-4">
                    <div class="input-group">
                      <input type="text" class="form-control" readonly v-model='part.kelompok_vendor'>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kelompok_vendor_part'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if='errors.kelompok_vendor != null' class="form-text text-danger">{{ errors.kelompok_vendor }}</small>
                  </div>
                  <?php $this->load->view('modal/h3_md_kelompok_vendor_part'); ?>
                  <script>
                    function pilih_kelompok_vendor(data){
                      form_.part.kelompok_vendor = data.id_kelompok_vendor;
                    }
                  </script>
                  <label for="inputEmail3" class="col-sm-2 control-label">Satuan</label>
                  <div v-bind:class="{ 'has-error': errors.id_satuan != null }" class="col-sm-4">
                    <div class="input-group">
                      <input type="text" class="form-control" readonly v-model='part.satuan'>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_satuan_part'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if='errors.id_satuan != null' class="form-text text-danger">{{ errors.id_satuan }}</small>
                  </div>
                  <?php $this->load->view('modal/h3_md_satuan_part'); ?>
                  <script>
                    function pilih_satuan(data){
                      form_.part.id_satuan = data.id_satuan;
                      form_.part.satuan = data.satuan;
                    }
                  </script>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Min Stok</label>
                  <div v-bind:class="{ 'has-error': errors.min_stok != null }" class="col-sm-4">
                    <vue-numeric :readonly='mode == "detail"' v-model='part.min_stok' class='form-control' separator='.'></vue-numeric>
                    <small v-if='errors.min_stok != null' class="form-text text-danger">{{ errors.min_stok }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Maks Stok</label>
                  <div v-bind:class="{ 'has-error': errors.maks_stok != null }" class="col-sm-4">
                    <vue-numeric :readonly='mode == "detail"' v-model='part.maks_stok' class='form-control' separator='.'></vue-numeric>
                    <small v-if='errors.maks_stok != null' class="form-text text-danger">{{ errors.maks_stok }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Safety Stok</label>
                  <div v-bind:class="{ 'has-error': errors.safety_stok != null }" class="col-sm-4">
                    <vue-numeric :readonly='mode == "detail"' v-model='part.safety_stok' class='form-control' separator='.'></vue-numeric>
                    <small v-if='errors.safety_stok != null' class="form-text text-danger">{{ errors.safety_stok }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Min Sales</label>
                  <div v-bind:class="{ 'has-error': errors.min_sales != null }" class="col-sm-4">
                    <vue-numeric :readonly='mode == "detail"' v-model='part.min_sales' class='form-control' separator='.'></vue-numeric>
                    <small v-if='errors.min_sales != null' class="form-text text-danger">{{ errors.min_sales }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Part</label>
                  <div v-bind:class="{ 'has-error': errors.kelompok_part != null }" class="col-sm-4">
                    <div class="input-group">
                      <input type="text" class="form-control" readonly v-model='part.kelompok_part'>
                      <div class="input-group-btn">
                        <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_kelompok_part_part'><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <small v-if='errors.kelompok_part != null' class="form-text text-danger">{{ errors.kelompok_part }}</small>
                  </div>
                  <?php $this->load->view('modal/h3_md_kelompok_part_part'); ?>
                  <script>
                    function pilih_kelompok_part(data){
                      form_.part.kelompok_part = data.id_kelompok_part;
                    }
                  </script>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Part</label>
                  <div class="col-sm-4">
                    <div class="row">
                      <div class="col-sm-12">
                        <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='part.sim_part'> SIM Part
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='part.fix'> Fix
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">HPP</label>
                  <div v-bind:class="{ 'has-error': errors.harga_md_dealer != null }" class="col-sm-4">
                    <vue-numeric :disabled='mode == "detail" || part.kelompok_vendor == "AHM"' v-model='part.harga_md_dealer' class='form-control' separator='.' currency='Rp '></vue-numeric>
                    <small v-if='errors.harga_md_dealer != null' class="form-text text-danger">{{ errors.harga_md_dealer }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">HET</label>
                  <div v-bind:class="{ 'has-error': errors.harga_dealer_user != null }" class="col-sm-4">
                    <vue-numeric :disabled='mode == "detail" || part.kelompok_vendor == "AHM"' v-model='part.harga_dealer_user' class='form-control' separator='.' currency='Rp '></vue-numeric>
                    <small v-if='errors.harga_dealer_user != null' class="form-text text-danger">{{ errors.harga_dealer_user }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">PNT</label>
                  <div v-bind:class="{ 'has-error': errors.pnt != null }" class="col-sm-4">
                    <select :disabled='mode == "detail"' v-model='part.pnt' class="form-control">
                      <option value="">-Choose-</option>
                      <option value="A">A</option>
                      <option value="B">B</option>
                      <option value="C">C</option>
                    </select>
                    <small v-if='errors.pnt != null' class="form-text text-danger">{{ errors.pnt }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Fast/Slow</label>
                  <div v-bind:class="{ 'has-error': errors.fast_slow != null }" class="col-sm-4">
                    <select :disabled='mode == "detail"' v-model='part.fast_slow' class="form-control">
                      <option value="">-Choose-</option>
                      <option value="F">Fast</option>
                      <option value="S">Slow</option>
                    </select>
                    <small v-if='errors.fast_slow != null' class="form-text text-danger">{{ errors.fast_slow }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Import/Lokal</label>
                  <div v-bind:class="{ 'has-error': errors.import_lokal != null }" class="col-sm-4">
                    <select :disabled='mode == "detail"' v-model='part.import_lokal' class="form-control">
                      <option value="">-Choose-</option>
                      <option value="Y">Import</option>
                      <option value="N">Lokal</option>
                    </select>
                    <small v-if='errors.import_lokal != null' class="form-text text-danger">{{ errors.import_lokal }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Rank</label>
                  <div v-bind:class="{ 'has-error': errors.rank != null }" class="col-sm-4">
                    <select :disabled='mode == "detail"' v-model='part.rank' class="form-control">
                      <option value="">-Choose-</option>
                      <option value='A'>A</option>
                      <option value='B'>B</option>                      
                      <option value='C'>C</option>
                      <option value='D'>D</option>
                      <option value='E'>E</option>
                      <option value='F'>F</option>
                    </select>
                    <small v-if='errors.rank != null' class="form-text text-danger">{{ errors.rank }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Current/Non-Current</label>
                  <div v-bind:class="{ 'has-error': errors.current != null }" class="col-sm-4">
                    <select :disabled='mode == "detail"' v-model='part.current' class="form-control">
                      <option value="">-Choose-</option>
                      <option value="C">Current</option>
                      <option value="N">Non Current</option>                      
                      <option value="O">Others</option>
                    </select>
                    <small v-if='errors.current != null' class="form-text text-danger">{{ errors.current }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Important/Safety/Additional</label>
                  <div v-bind:class="{ 'has-error': errors.important != null }" class="col-sm-4">
                    <select :disabled='mode == "detail"' v-model='part.important' class="form-control">
                      <option value="">-Choose-</option>
                      <option value="I">Important</option>
                      <option value="S">Safety</option>                      
                      <option value="A">Additional</option>                      
                      <option value="O">Others</option>                      
                    </select>
                    <small v-if='errors.important != null' class="form-text text-danger">{{ errors.important }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Long/Short/Others</label>
                  <div v-bind:class="{ 'has-error': errors.long != null }" class="col-sm-4">
                    <select :disabled='mode == "detail"' v-model='part.long' class="form-control">
                      <option value="">-Choose-</option>
                      <option value="L">Long</option>
                      <option value="S">Short</option>                      
                      <option value="O">Others</option>
                    </select>
                    <small v-if='errors.long != null' class="form-text text-danger">{{ errors.long }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Engine/Frame/Electrical</label>
                  <div v-bind:class="{ 'has-error': errors.engine != null }" class="col-sm-4">
                    <select :disabled='mode == "detail"' v-model='part.engine' class="form-control">
                      <option value="">-Choose-</option>
                      <option value="E">Engine</option>
                      <option value="F">Frame</option>                      
                      <option value="L">Electrical</option>                      
                      <option value="O">Others</option>                      
                    </select>
                    <small v-if='errors.engine != null' class="form-text text-danger">{{ errors.engine }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Recommend Part</label>
                  <div v-bind:class="{ 'has-error': errors.recommend_part != null }" class="col-sm-4">
                    <select :disabled='mode == "detail"' v-model='part.recommend_part' class="form-control">
                      <option value="">-Choose-</option>
                      <option value='Ya'>Ya</option>
                      <option value='Tidak'>Tidak</option>                                            
                    </select>
                    <small v-if='errors.recommend_part != null' class="form-text text-danger">{{ errors.recommend_part }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                  <div v-bind:class="{ 'has-error': errors.status != null }" class="col-sm-4">
                    <select :disabled='mode == "detail"' v-model='part.status' class="form-control">
                      <option value="">-Choose-</option>
                      <option value='A'>Active</option>
                      <option value='D'>Discountinued</option>                                            
                    </select>
                    <small v-if='errors.status != null' class="form-text text-danger">{{ errors.status }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label no-padding">Part/Oli</label>
                  <div v-bind:class="{ 'has-error': errors.part_oli != null }" class="col-sm-4">
                    <input :disabled='mode == "detail"' type="checkbox" true-value='Oli' false-value='' v-model='part.part_oli'> 
                    <small v-if='errors.part_oli != null' class="form-text text-danger">{{ errors.part_oli }}</small>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Qty Per Dus</label>
                  <div v-bind:class="{ 'has-error': errors.qty_dus != null }" class="col-sm-4">
                    <vue-numeric :disabled='mode == "detail"' v-model='part.qty_dus' class="form-control" separator='.'></vue-numeric>
                    <small v-if='errors.qty_dus != null' class="form-text text-danger">{{ errors.qty_dus }}</small>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label no-padding-top">Gambar (Maks 100KB)</label>
                  <div v-if='mode != "detail"' class="col-sm-4">
                    <input type="file" @change='on_gambar_change()' ref='gambar'>
                  </div>
                  <div v-if='mode == "detail"' class="col-sm-4">
                    <button v-if='part.gambar != null' class="btn btn-sm btn-flat btn-primary" type='button' data-toggle='modal' data-target='#gambar_part'>Lihat Foto</button>
                    <span v-if='part.gambar == null'>Gambar tidak ada.</span>
                  </div>
                  <!-- Modal -->
                  <div id="gambar_part" class="modal fade modalcustomer" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title text-left" id="myModalLabel">Gambar Part</h4>
                              </div>
                              <div class="modal-body">
                                <img class='img-responsive' :src="'assets/panel/images/' + part.gambar">
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label no-padding-top">Active</label>
                  <div class="col-sm-4">
                    <input :disabled='mode == "detail"' v-model='part.active' type="checkbox" true-value='1' false-value='0'>
                  </div>
                </div>
                <div class="container-fluid">
                  <div class="col-sm-8 col-sm-offset-2">
                    <?php $this->load->view('modal/h3_md_detail_ptm_ms_part'); ?>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="col-sm-6 no-padding">
                    <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" type='button' @click.prevent='<?= $form ?>'>Perbarui</button>
                    <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" type='button' @click.prevent='<?= $form ?>'>Simpan</button>
                    <a v-if='mode == "detail"' :href="'h3/part/edit?id_part=' + part.id_part" class="btn btn-flat btn-sm btn-warning">Edit</a>
                  </div>
                </div>
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
        errors: {},
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        part: <?= json_encode($part) ?>,
        ptm: <?= json_encode($ptm) ?>,
        <?php else: ?>
        part: {
          id_part: '',
          nama_part: '',
          kelompok_vendor: '',
          id_satuan: '',
          satuan: '',
          min_stok: '',
          maks_stok: '',
          safety_stok: '',
          min_sales: '',
          kelompok_part: '',
          harga_md_dealer: '',
          harga_dealer_user: '',
          sim_part: 0,
          fix: 0,
          reguler: 0,
          pnt: '',
          fast_slow: '',
          import_lokal: '',
          rank: '',
          current: '',
          important: '',
          long: '',
          engine: '',
          recommend_part: '',
          part_oli: '',
          qty_dus: '',
          superseed: '',
          status: '',
          active: 1,
          gambar: null,
        },
        <?php endif; ?>
      },
      methods:{
        <?= $form ?>: function(){
          keys = [
            'id_part', 'nama_part', 'kelompok_vendor', 'id_satuan', 'min_stok', 'maks_stok', 'minimal_order',
            'safety_stok', 'min_sales', 'kelompok_part', 'harga_md_dealer', 'harga_dealer_user',
            'sim_part', 'fix', 'reguler', 'pnt', 'fast_slow', 'import_lokal', 'rank', 'current',
            'important', 'long', 'engine', 'recommend_part', 'part_oli', 'qty_dus', 'superseed',
            'status', 'active'
          ];

          post = new FormData();
          for ( key of keys ) {
            if(this.part[key] != null){
              post.set(key, this.part[key]);
            }
          }
          post.append('gambar', this.part.gambar);

          this.loading = true;
          axios.post('h3/part/<?= $form ?>', post, {
            headers: {
              'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
            }
          })
          .then(function(res){
            data = res.data;

            if(data.redirect_url != null) window.location = data.redirect_url;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
              toastr.error(data.message);
            }else{
              toastr.error(data.message);
            }

            form_.loading = false;
          });
        },
        on_gambar_change: function(){
          this.part.gambar = this.$refs.gambar.files[0];
        },
        hapusPart: function(index){
          this.parts.splice(index, 1);
        }
      },
      watch: {
        gudang: {
          deep: true,
          handler: function(){
            parts_outbound_form_datatable.draw();
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
          <a href="h3/part/add">
            <button class="btn bg-blue btn-flat"><i class="fa fa-plus"></i> Add New</button>
          </a>
          <a href="h3/part/upload_pmp">
            <button class="btn btn-info btn-flat">Import PMP</button>
          </a>
          <a href="h3/part/upload_minimal_order">
            <button class="btn btn-info btn-flat">Upload Minimal Order</button>
          </a>
          <a href="h3/part/upload_simpart">
            <button class="btn btn-info btn-flat">Upload SIM Part</button>
          </a>
          <a href="h3/part/upload_fix_part">
            <button class="btn btn-info btn-flat">Upload Fix Part</button>
          </a>
          <a href="h3/part/upload_deskripsi_bahasa_part">
            <button class="btn btn-warning btn-flat">Upload Deskripsi Bahasa Indonesia</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message.php'); ?>
        <form class='form-horizontal'>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kode Part</label>
                  <div class="col-sm-8">
                    <input id='id_part_filter' type="text" class="form-control">
                  </div>
                </div>                
                <script>
                  $(document).ready(function(){
                    $('#id_part_filter').on("keyup", _.debounce(function(){
                      master_part.draw();
                    }, 500));
                  });
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Nama Part</label>
                  <div class="col-sm-8">
                    <input id='nama_part_filter' type="text" class="form-control">
                  </div>
                </div>                
                <script>
                  $(document).ready(function(){
                    $('#nama_part_filter').on("keyup", _.debounce(function(){
                      master_part.draw();
                    }, 500));
                  });
                </script>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Nama Part Bahasa</label>
                  <div class="col-sm-8">
                    <input id='nama_part_bahasa_filter' type="text" class="form-control">
                  </div>
                </div>                
                <script>
                  $(document).ready(function(){
                    $('#nama_part_bahasa_filter').on("keyup", _.debounce(function(){
                      master_part.draw();
                    }, 500));
                  });
                </script>
              </div>
            </div>
            <div class="row">
              <div id='kelompok_part_filter' class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Kelompok Part</label>
                  <div class="col-sm-8">
                    <div class="input-group">
                      <input id='id_kelompok_part_filter' type="text" class="form-control" disabled>
                      <div class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kelompok_part_filter_master_part_index'>
                          <i class="fa fa-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>       
                <?php $this->load->view('modal/h3_md_kelompok_part_filter_master_part_index'); ?>         
                <script>
                  kelompok_part_filter = new Vue({
                      el: '#kelompok_part_filter',
                      data: {
                          filters: []
                      },
                      watch: {
                        filters: function(){
                          master_part.draw();
                        }
                      }
                  });

                  $("#h3_md_kelompok_part_filter_master_part_index").on('change',"input[type='checkbox']",function(e){
                    target = $(e.target);
                    id_kelompok_part = target.attr('data-id-kelompok-part');

                    if(target.is(':checked')){
                      kelompok_part_filter.filters.push(id_kelompok_part);
                    }else{
                      index_kelompok_part = _.indexOf(kelompok_part_filter.filters, id_kelompok_part);
                      kelompok_part_filter.filters.splice(index_kelompok_part, 1);
                    }
                    h3_md_kelompok_part_filter_master_part_index_datatable.draw();
                  });
                </script>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="control-label col-sm-4 align-middle">Jenis Part</label>
                  <div class="col-sm-8">
                    <select class="form-control" id='status_filter'>
                      <option value="">All</option>
                      <option value="A">Active</option>
                      <option value="D">Discontinued</option>
                    </select>
                  </div>
                </div>                
                <script>
                $(document).ready(function(){
                    $('#status_filter').on("change", function(){
                      master_part.draw();
                    });
                  });
                </script>
              </div>
            </div>
        </form>
        <table id="master_part" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>ID Part</th>
              <th>Nama Part</th>
              <th>Nama Part Bahasa</th>
              <th>Kelompok Vendor</th>
              <th>Satuan</th>
              <th>Min Stok</th>
              <th>Max Stok</th>
              <th>Safety Stok</th>
              <th>Kelompok Part</th>
              <th>Status</th>
              <th>Active</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            master_part = $('#master_part').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                searching: false,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_part') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.id_part_filter = $('#id_part_filter').val();
                    d.nama_part_filter = $('#nama_part_filter').val();
                    d.nama_part_bahasa_filter = $('#nama_part_bahasa_filter').val();
                    d.id_kelompok_part_filter = kelompok_part_filter.filters;
                    d.status_filter = $('#status_filter').val();
                  }
                },
                createdRow: function (row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [
                    { data: null },
                    { data: 'id_part' },
                    { data: 'nama_part' },
                    { data: 'nama_part_bahasa' },
                    { data: 'kelompok_vendor' },
                    { data: 'kode_satuan' },
                    { data: 'min_stok' },
                    { data: 'maks_stok' },
                    { data: 'safety_stok' },
                    { data: 'kelompok_part' },
                    { data: 'status' },
                    { 
                      data: 'active',
                      render: function(data){
                        if(data == 1){
                          return "<i class='glyphicon glyphicon-ok'></i>";
                        }
                        return "<i class='glyphicon glyphicon-remove'></i>";
                      }
                    },
                    { data: 'action' },
                ],
            });

            master_part.on('draw.dt', function() {
              var info = master_part.page.info();
              master_part.column(0, {
                  search: 'applied',
                  order: 'applied',
                  page: 'applied'
              }).nodes().each(function(cell, i) {
                  cell.innerHTML = i + 1 + info.start + ".";
              });
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php }
    elseif($set == 'upload_pmp'){ ?>
    <div id="app" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/part">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div>
      <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <div v-if='validation_error.length > 0' class="alert alert-warning alert-dismissible">
          <button type="button" class="close" @click.prevent='validation_error = []' aria-hidden="true">×</button>
          <h4>
            <i class="icon fa fa-warning"></i> 
            Alert!
          </h4>
          <ol class="">
            <li v-for='(each, index) of validation_error.slice(0, 10)'>
              {{ each.message }}
              <ul>
                <li v-for='(error, index) of each.errors'>{{ error }}</li>
              </ul>
            </li>
          </ol>
        </div>
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">File PMP</label>
                    <div class="col-sm-4">
                      <input type="file" @change='on_file_change()' ref='file' class="form-control" accept=".pmp,.PMP">
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="col-sm-6 no-padding">
                    <button :disabled='file == null' class="btn btn-flat btn-primary" @click.prevent='upload'>Upload</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script>
      var app = new Vue({
          el: '#app',
          data: {
            loading: false,
            validation_error: [],
            file: null
          },
          methods: {
            upload: function(){
              post = new FormData();
              post.append('file', this.file);

              this.validation_error = [];
              this.loading = true;
              axios.post('h3/<?= $isi ?>/inject', post, {
                headers: {
                  'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
                }
              })
              .then(function(res){
                data = res.data;
                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){
                data = err.response.data;
                if(data.error_type == 'validation_error'){
                  app.validation_error = data.payload;
                }else if(data.error_type == 'format_error'){
                  toastr.error(data.message);
                }else{
                  toastr.error(data.message);
                }
                app.reset_file();
              })
              .then(function(){ app.loading = false; });
            },
            on_file_change: function(){
              this.file = this.$refs.file.files[0];
            },
            reset_file: function(){
              const input = this.$refs.file;
              input.type = 'text';
              input.type = 'file';
            }
          }
        });
    </script>
   <?php } 
   elseif($set == 'upload_minimal_order'){ ?>
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/part">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div>
      <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Minimal Order Excel Template</label>
                    <div class="col-sm-4">
                      <input type="file" @change='on_file_change()' ref='file' class="form-control">
                    </div>
                    <div class="col-sm-3 no-padding">
                      <a href="h3/part/download_minimal_order_template" class="btn btn-flat btn-info">Download Template</a>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="col-sm-6 no-padding">
                    <button class="btn btn-flat btn-primary" @click.prevent='upload'>Upload</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script>
      form_ = new Vue({
        el: '#form_',
        data: {
          loading: false,
          errors: {},
          file: null
        },
        methods: {
          upload: function(){
            post = new FormData();
            post.append('file', this.file);

            this.errors = {};
            this.loading = true;
            axios.post('h3/<?= $isi ?>/store_upload_minimal_order', post, {
              headers: {
                'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
              }
            })
            .then(function(res){
              window.location = 'h3/part';
            })
            .catch(function(err){
              data = err.response.data;
              if(data.error_type == 'validation_error'){
                form_.errors = data.errors;
                toastr.error(data.message);
              }else{
                toastr.error(err);
              }
            })
            .then(function(){ form_.loading = false; });
          },
          on_file_change: function(){
            this.file = this.$refs.file.files[0];
          },
          error_exist: function(key){
            return _.get(this.errors, key) != null;
          },
          get_error: function(key){
            return _.get(this.errors, key)
          }
        }
      })
    </script>
   <?php }elseif($set == 'upload_simpart'){ ?>
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/part">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div>
      <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">SIM Part Excel Template</label>
                    <div class="col-sm-4">
                      <input type="file" @change='on_file_change()' ref='file' class="form-control">
                    </div>
                    <div class="col-sm-3 no-padding">
                      <a href="h3/part/download_simpart_template" class="btn btn-flat btn-info">Download Template</a>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="col-sm-6 no-padding">
                    <button class="btn btn-flat btn-primary" @click.prevent='upload'>Upload</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script>
      form_ = new Vue({
        el: '#form_',
        data: {
          loading: false,
          errors: {},
          file: null
        },
        methods: {
          upload: function(){
            post = new FormData();
            post.append('file', this.file);

            this.errors = {};
            this.loading = true;
            axios.post('h3/<?= $isi ?>/store_upload_simpart', post, {
              headers: {
                'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
              }
            })
            .then(function(res){
              data = res.data;

              if(data.redirect_url != null){
                window.location = data.redirect_url;
              }
            })
            .catch(function(err){
              data = err.response.data;
              if(data.error_type == 'validation_error'){
                form_.errors = data.errors;
                toastr.error(data.message);
              }else{
                toastr.error(data.message);
              }
            })
            .then(function(){ form_.loading = false; });
          },
          on_file_change: function(){
            this.file = this.$refs.file.files[0];
          },
          error_exist: function(key){
            return _.get(this.errors, key) != null;
          },
          get_error: function(key){
            return _.get(this.errors, key)
          }
        }
      })
    </script>
   <?php } elseif($set == 'upload_fix_part'){ ?>
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/part">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div>
      <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <div v-if='error_type == "part_purchase_order_yang_harus_fix_validation_error"' class="alert alert-warning alert-dismissible">
          <button type="button" class="close" @click.prevent='errors_payload = []' aria-hidden="true">×</button>
          <h4>
            <i class="icon fa fa-warning"></i> 
            Perhatian!
          </h4>
          <p>{{ error_message }}</p>
          <div class="row">
            <div class="col-sm-12">
              <button class="btn btn-flat btn-sm btn-success" @click.prevent='force_upload'>Ya, lakukan update data</button>
              <button class="btn btn-flat btn-sm btn-danger" @click.prevent='batalkan'>Batalkan</button>
            </div>
          </div>
        </div>
        <div v-if='error_type == "part_niguri_yang_harus_fix_validation_error"' class="alert alert-warning alert-dismissible">
          <button type="button" class="close" @click.prevent='errors_payload = []' aria-hidden="true">×</button>
          <h4>
            <i class="icon fa fa-warning"></i> 
            Perhatian!
          </h4>
          <p>{{ error_message }}</p>
          <div class="row">
            <div class="col-sm-12">
              <button class="btn btn-flat btn-sm btn-success" @click.prevent='force_upload'>Ya, lakukan update data</button>
              <button class="btn btn-flat btn-sm btn-danger" @click.prevent='batalkan'>Batalkan</button>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Fix Part Excel Template</label>
                    <div class="col-sm-4">
                      <input type="file" @change='on_file_change()' ref='file' class="form-control">
                    </div>
                    <div class="col-sm-3 no-padding">
                      <a href="h3/part/download_fix_part_template" class="btn btn-flat btn-info">Download Template</a>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="col-sm-6 no-padding">
                    <button class="btn btn-flat btn-primary" @click.prevent='upload'>Upload</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script>
      form_ = new Vue({
        el: '#form_',
        data: {
          loading: false,
          file: null,
          errors: {},
          error_type: '',
          error_message: '',
          errors_payload: [],
          force: 0,
        },
        methods: {
          force_upload: function(){
            this.force = 1;
            this.upload();
          },
          batalkan: function(){
            this.reset_error();
          },
          reset_error: function(){
            this.errors = {};
            this.error_type = '';
            this.error_message = '';
            this.errors_payload = [];
          },
          upload: function(){
            post = new FormData();
            post.append('file', this.file);
            post.append('force', this.force);

            this.reset_error();
            this.loading = true;
            axios.post('h3/<?= $isi ?>/store_upload_fix_part', post, {
              headers: {
                'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
              }
            })
            .then(function(res){
              window.location = 'h3/part';
            })
            .catch(function(err){
              data = err.response.data;
              if(data.error_type == 'validation_error'){
                form_.errors = data.errors;
                toastr.error(data.message);
              }else if(data.error_type == 'part_purchase_order_yang_harus_fix_validation_error'){
                form_.error_type = data.error_type;
                form_.error_message = data.message;
              }else if(data.error_type == 'part_niguri_yang_harus_fix_validation_error'){
                form_.error_type = data.error_type;
                form_.error_message = data.message;
              }else{
                toastr.error(data.message);
              }
            })
            .then(function(){ form_.loading = false; });
          },
          on_file_change: function(){
            this.file = this.$refs.file.files[0];
          },
          error_exist: function(key){
            return _.get(this.errors, key) != null;
          },
          get_error: function(key){
            return _.get(this.errors, key)
          }
        }
      })
    </script>
   <?php } elseif($set == 'upload_deskripsi_bahasa_part'){ ?>
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/part">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div>
      <div v-if="loading" class="overlay">
        <i class="fa fa-refresh fa-spin text-light-blue"></i>
      </div>
      <div class="box-body">
        <div v-if='error_type == "part_purchase_order_yang_harus_fix_validation_error"' class="alert alert-warning alert-dismissible">
          <button type="button" class="close" @click.prevent='errors_payload = []' aria-hidden="true">×</button>
          <h4>
            <i class="icon fa fa-warning"></i> 
            Perhatian!
          </h4>
          <p>{{ error_message }}</p>
          <div class="row">
            <div class="col-sm-12">
              <button class="btn btn-flat btn-sm btn-success" @click.prevent='force_upload'>Ya, lakukan update data</button>
              <button class="btn btn-flat btn-sm btn-danger" @click.prevent='batalkan'>Batalkan</button>
            </div>
          </div>
        </div>
        <div v-if='error_type == "part_niguri_yang_harus_fix_validation_error"' class="alert alert-warning alert-dismissible">
          <button type="button" class="close" @click.prevent='errors_payload = []' aria-hidden="true">×</button>
          <h4>
            <i class="icon fa fa-warning"></i> 
            Perhatian!
          </h4>
          <p>{{ error_message }}</p>
          <div class="row">
            <div class="col-sm-12">
              <button class="btn btn-flat btn-sm btn-success" @click.prevent='force_upload'>Ya, lakukan update data</button>
              <button class="btn btn-flat btn-sm btn-danger" @click.prevent='batalkan'>Batalkan</button>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi Bahasa Indonesia Excel Template</label>
                    <div class="col-sm-4">
                      <input type="file" @change='on_file_change()' ref='file' class="form-control">
                    </div>
                    <div class="col-sm-3 no-padding">
                      <a href="h3/part/download_bahasa_part_template" class="btn btn-flat btn-info">Download Template</a>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="col-sm-6 no-padding">
                    <button class="btn btn-flat btn-primary" @click.prevent='upload'>Upload</button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script>
      form_ = new Vue({
        el: '#form_',
        data: {
          loading: false,
          file: null,
          errors: {},
          error_type: '',
          error_message: '',
          errors_payload: [],
          force: 0,
        },
        methods: {
          force_upload: function(){
            this.force = 1;
            this.upload();
          },
          batalkan: function(){
            this.reset_error();
          },
          reset_error: function(){
            this.errors = {};
            this.error_type = '';
            this.error_message = '';
            this.errors_payload = [];
          },
          upload: function(){
            post = new FormData();
            post.append('file', this.file);
            post.append('force', this.force);

            this.reset_error();
            this.loading = true;
            axios.post('h3/<?= $isi ?>/store_upload_bahasa_part', post, {
              headers: {
                'Content-Type': 'multipart/form-data; boundary=' + post._boundary,
              }
            })
            .then(function(res){
              window.location = 'h3/part';
            })
            .catch(function(err){
              data = err.response.data;
              if(data.error_type == 'validation_error'){
                form_.errors = data.errors;
                toastr.error(data.message);
              }else{
                toastr.error(data.message);
              }
            })
            .then(function(){ form_.loading = false; });
          },
          on_file_change: function(){
            this.file = this.$refs.file.files[0];
          },
          error_exist: function(key){
            return _.get(this.errors, key) != null;
          },
          get_error: function(key){
            return _.get(this.errors, key)
          }
        }
      })
    </script>
   <?php } ?>
  </section>
</div>

<?php if(isset($harus_update_harga) AND $harus_update_harga): ?>
  <?php $this->load->view('modal/h3_md_popup_update_harga'); ?>
  <script>
    $(document).ready(function(){
      $('#h3_md_popup_update_harga').modal('show');
    });
  </script>
<?php endif; ?>