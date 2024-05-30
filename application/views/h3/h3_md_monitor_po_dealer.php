
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
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>  
        </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form id="vueForm" class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No BAPB</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control">
                  </div>  
                  <label class="col-sm-2 control-label">Tanggal Pelunasan</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control">
                  </div>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No Surat Jalan Ekspedisi</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control">
                  </div> 
                  <label class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-4">                    
                    <input readonly type="text" class="form-control">
                  </div>                                
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr>                                      
                          <th>Nomor Surat Jalan AHM</th>              
                          <th>No Packing Sheet</th>              
                          <th>No Karton</th>              
                          <th>Nomor Part</th>
                          <th>Nama Part</th>
                          <th>Qty Kurang / Rusak</th>
                          <th>Ganti uang / barang</th>              
                          <th>Proses Pembayaran</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts"> 
                          <input type="hidden" name="id_part[]" v-model="part.id_part">
                          <input type="hidden" name="qty_order[]" v-model="part.qty_order">
                          <input type="hidden" name="harga[]" v-model="part.harga">
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>                       
                          <td class="align-middle">{{ part.kelompok_vendor }}</td>                       
                          <td class="align-middle">0</td>                       
                          <td class="align-middle">0</td>                       
                          <td class="align-middle">0</td>                       
                          <td class="align-middle">0</td>                       
                          <td class="align-middle">0</td>                       
                          <td class="align-middle">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.qty_order"></vue-numeric>
                          </td>                       
                          <td width="8%" class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="ppnPerPart(part)" />
                          </td>                       
                          <td width="8%" class="align-middle">
                            <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="subTotal(part)" />
                          </td>                       
                          <td v-if="mode != 'detail'" class="align-middle">
                            <button class="btn btn-sm" v-on:click.prevent="hapusPart(index)">Hapus</button>
                          </td>                       
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="8">Belum ada data</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>     
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button v-if="mode == 'insert'" class="btn btn-flat btn-primary" @click.prevent="<?= $form ?>">Submit</button>
                  <button v-if="mode == 'edit'" class="btn btn-flat btn-warning" @click.prevent="<?= $form ?>">Update</button>
                  <?php if($mode == 'detail'): ?>
                  <a class="btn btn-flat btn-warning" href="h3/<?= $isi ?>/edit?id_mutasi_gudang=<?= $mutasi_gudang->id_mutasi_gudang ?>">Edit</a>
                  <?php endif; ?>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
    <script>
      Vue.use(VueNumeric.default);
      var vueForm = new Vue({
          el: '#vueForm',
          data: {
            kosong: '',
            mode: '<?= $mode ?>',
            parts: [],
            <?php if($mode == 'detail' or $mode == 'edit'): ?>
            part: <?= json_encode($part) ?>,
            gudang_asal: <?= json_encode($gudang_asal) ?>,
            lokasi_asal: <?= json_encode($lokasi_asal) ?>,
            gudang_tujuan: <?= json_encode($gudang_tujuan) ?>,
            lokasi_tujuan: <?= json_encode($lokasi_tujuan) ?>,
            option_gudang_asal: <?= json_encode($option_gudang_asal) ?>,
            option_lokasi_asal: <?= json_encode($option_lokasi_asal) ?>,
            option_gudang_tujuan: <?= json_encode($option_gudang_tujuan) ?>,
            option_lokasi_tujuan: <?= json_encode($option_lokasi_tujuan) ?>,
            <?php else: ?>
            part: {},
            gudang_asal: null,
            gudang_tujuan: null,
            lokasi_asal: null,
            lokasi_tujuan: null,
            option_gudang_asal: [],
            option_lokasi_asal: [],
            option_gudang_tujuan: [],
            option_lokasi_tujuan: [],
            <?php endif; ?>
          },
          methods: {
            <?= $form ?>: function(){
              post = {};
              <?php if($mode == 'edit'): ?>
              post.id_mutasi_gudang = '<?= $mutasi_gudang->id_mutasi_gudang ?>';
              <?php endif; ?>
              post.id_gudang_asal = this.gudang_asal.id_gudang;
              post.id_lokasi_asal = this.lokasi_asal.id_lokasi_unit;
              post.id_gudang_tujuan = this.gudang_tujuan.id_gudang;
              post.id_lokasi_tujuan = this.lokasi_tujuan.id_lokasi_unit;
              post.id_part = this.part.id_part;
              post.qty = this.part.qty;
              console.log(post);

              axios.post('h3/h3_md_mutasi_gudang/<?= $form ?>', Qs.stringify(post)).then(function(res){
                console.log(res);
                window.location = 'h3/h3_md_mutasi_gudang/detail?id_mutasi_gudang=' + res.data.id_mutasi_gudang;
              }).catch(function(err){
                console.log(err);
              })
            },
            cari_gudang_asal: function (search, loading) {
              loading(true);
              axios.post('h3/h3_md_mutasi_gudang/cari_gudang', Qs.stringify({
                query: search,
              })).then(function(res){
                vueForm.option_gudang_asal = res.data;
                loading(false);
              }).catch(function(err){
                console.log(err)
              });
            },
            cari_lokasi_asal: function (search, loading) {
              loading(true);
              axios.post('h3/h3_md_mutasi_gudang/cari_lokasi', Qs.stringify({
                query: search,
                id_gudang: this.gudang_asal.id_gudang,
              })).then(function(res){
                vueForm.option_lokasi_asal = res.data;
                loading(false);
              }).catch(function(err){
                console.log(err)
              });
            },
            cari_lokasi_tujuan: function (search, loading) {
              loading(true);
              axios.post('h3/h3_md_mutasi_gudang/cari_lokasi', Qs.stringify({
                query: search,
                id_gudang: this.gudang_tujuan.id_gudang,
              })).then(function(res){
                vueForm.option_lokasi_tujuan = res.data;
                loading(false);
              }).catch(function(err){
                console.log(err)
              });
            },
            cari_gudang_tujuan: function (search, loading) {
              loading(true);
              axios.post('h3/h3_md_mutasi_gudang/cari_gudang', Qs.stringify({
                query: search
              })).then(function(res){
                vueForm.option_gudang_tujuan = res.data;
                loading(false);
              }).catch(function(err){
                console.log(err)
              });
            }
          },
          computed: {
            editable: function(){
              return false;
            }
          }
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="container">
          <form action="" class="form-horizontal">
            <div class="box-body">
              <div class="form-group">
                <label for="" class="control-label col-sm-2">Nama Customer</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control">
                </div>
                <div class="col-sm-1">
                  <button class="btn btn-primary btn-flat" type='button'><i class="fa fa-search"></i></button>
                </div>
                <label for="" class="control-label col-sm-2">Periode</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label for="" class="control-label col-sm-2">Kode Customer</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control">
                </div>
              </div>
            </div>
          </form>
        </div>
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th>Kode Dealer</th>              
              <th>Nama Dealer</th>              
              <th>Alamat Dealer</th>              
              <th>Tanggal dan Waktu upload file stok</th>              
              <th>Tanggal dan waktu upload file sales</th>              
            </tr>
          </thead>
          <tbody>    
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>