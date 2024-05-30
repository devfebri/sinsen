<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
</style>
<base href="<?php echo base_url(); ?>" /> 
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H3</li>
    <li class="">Purchase</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
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
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/session_message.php'); ?>
        <div class="row">
          <div class="col-md-12">
            <form id="vueForm" class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
              <?php if($mode == 'terima_claim'): ?>
              <input type="hidden" name="id_claim_ahm" value="<?= $claim_ahm->id_claim_ahm ?>">
              <?php endif; ?>
              <div class="box-body">
                <div v-if="mode == 'terima_claim'" class="form-group">                  
                  <label class="col-sm-2 control-label">Respon</label>
                  <div class="col-sm-4">                    
                    <select name="respon" class="form-control" v-model="respon">
                      <option value="Diterima">Diterima</option>
                      <option value="Ditolak">Ditolak</option>
                    </select>
                  </div>                                
                </div>
                <div v-if="mode == 'terima_claim' && respon == 'Diterima'" class="form-group">                  
                  <label class="col-sm-2 control-label">Tipe Ganti</label>
                  <div class="col-sm-4">                    
                    <select name="tipe_ganti" class="form-control" v-model="tipe_ganti">
                      <option value="Ganti Spare Part">Ganti Spare Part</option>
                      <option value="Ganti Uang">Ganti Uang</option>
                    </select>
                  </div>                                
                </div> 
                <?php $this->load->view('modal/h3_part_modal'); ?>
                <script>
                  function choose_part(part) {
                    part.qty_order = 1;
                    part.qty_claim = 1;
                    part.harga = part.harga_md_dealer;
                    vueForm.parts.push(part);
                  }
                </script>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr>                                      
                          <th>Part Number</th>              
                          <th>Nama Part</th>              
                          <th width="10%">Qty Claim</th>
                          <th v-if="mode != 'detail' && mode != 'terima_claim'" width="10%">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts"> 
                          <input type="hidden" name="id_part[]" v-model="part.id_part">
                          <input type="hidden" name="qty_claim[]" v-model="part.qty_claim">
                          <input type="hidden" name="harga[]" v-model="part.harga">
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>            
                          <td class="align-middle">
                            <vue-numeric :read-only="mode == 'detail' || mode == 'terima_claim'" class="form-control" separator="." :empty-value="1" v-model="part.qty_claim"></vue-numeric>
                          </td>      
                          <td v-if="mode != 'detail' && mode != 'terima_claim'" class="align-middle">
                            <button class="btn btn-sm" v-on:click.prevent="hapusPart(index)">Hapus</button>
                          </td>                              
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="4">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>                                                                                                                                
                <div v-if="mode != 'detail' && mode != 'terima_claim'" class="form-group">
                  <div class="col-sm-12">
                    <button type="button" class="pull-right btn btn-flat btn-info btn-sm" data-toggle="modal" data-target="#modal-part">Tambah</button>
                  </div>
                </div> 
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button v-if="mode == 'insert'" class="btn btn-flat btn-primary">Submit</button>
                  <button v-if="mode == 'terima_claim'" class="btn btn-flat btn-primary">Claim</button>
                  <?php if($mode == 'detail'): ?>
                  <a class="btn btn-flat btn-primary" href="h3/<?= $isi ?>/terima_claim?id=<?= $claim_ahm->id_claim_ahm ?>">Terima Claim</a>
                  <?php endif; ?>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
    <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
    <script>
      Vue.use(VueNumeric.default);
      var vueForm = new Vue({
          el: '#vueForm',
          data: {
            kosong: '',
            mode: '<?= $mode ?>',
            <?php if($mode == 'detail' or $mode == 'terima_claim'): ?>
            parts: <?= json_encode($claim_ahm_parts) ?>,
            respon: '',
            tipe_ganti: '',
            <?php else: ?>
            parts: [],
            <?php endif; ?>
          },
          methods: {
            ppnPerPart: function(part) {
              return (10 / 100) * part.harga_saat_dibeli ;
            },
            subTotal: function(part) {
              return (part.kuantitas * part.harga_saat_dibeli ) + this.ppnPerPart(part);
            },
            hapusPart: function(index) {
              this.parts.splice(index, 1);
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
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th>No Claim Customer</th>              
              <th>Tgl Claim Customer</th>              
              <th>Kode Customer</th>              
              <th>Nama Customer</th>              
              <th>Kode Part</th>              
              <th>Nama Part</th>              
              <th>Qty Claim AHASS</th>              
              <th>Qty Kirim ke AHASS</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>    
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><a href="" class="btn btn-flat btn-xs btn-primary">View</a></td>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
