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
              <?php if($mode == 'edit'): ?>
              <input type="hidden" name="id_so_other" value="<?= $so_other->id_so_other ?>">
              <?php endif; ?>
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tipe Penjualan</label>
                  <div class="col-sm-4">                    
                    <select :disabled="mode == 'detail'" name="tipe_penjualan" class="form-control">
                      <option <?= (($mode != 'insert') AND ($so_other->tipe_penjualan == 'fix_reg')) ? 'selected' : '' ?> value="fix_reg">Fix/Reg</option>
                      <option <?= (($mode != 'insert') AND ($so_other->tipe_penjualan == 'hlo')) ? 'selected' : '' ?> value="hlo">Hotline</option>
                      <option <?= (($mode != 'insert') AND ($so_other->tipe_penjualan == 'urg')) ? 'selected' : '' ?> value="urg">Urgent</option>
                    </select>
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">         
                    <input type="hidden" name="id_dealer" v-model="customer.id_dealer">           
                    <input type="text" class="form-control" readonly v-model="customer.nama_dealer">
                  </div>                                
                  <div class="col-sm-2">
                    <button v-if="mode != 'detail'" data-toggle="modal" data-target="#modal-dealer" class="btn btn-info btn-flat" type="button">Cari Customer</button>
                  </div>
                  <?php $this->load->view('modal/h3_dealer_modal'); ?>
                  <script>
                    function choose_dealer(customer) {
                      vueForm.customer = customer;
                    }
                  </script>
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" readonly v-model="customer.kode_dealer_md">
                  </div> 
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">                    
                    <input type="text" class="form-control" readonly v-model="customer.alamat">
                  </div> 
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Jenis Pembayaran</label>
                  <div class="col-sm-4">                    
                    <select :disabled="mode == 'detail'" name="jenis_pembayaran" class="form-control">
                      <option <?= (($mode != 'insert') AND ($so_other->jenis_pembayaran == 'Credit')) ? 'selected' : '' ?> value="Credit">Credit</option>
                      <option <?= (($mode != 'insert') AND ($so_other->jenis_pembayaran == 'Tunai')) ? 'selected' : '' ?> value="Tunai">Tunai</option>
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
                          <th width="15%">HET</th>              
                          <th width="10%">Qty On Hand</th>
                          <th width="10%">Qty Order</th>
                          <th width="15%">Total</th>
                          <th v-if="mode != 'detail' && mode != 'terima_claim'" width="10%">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts"> 
                          <input type="hidden" name="id_part[]" v-model="part.id_part">
                          <input type="hidden" name="qty_on_hand[]" v-model="part.qty_on_hand">
                          <input type="hidden" name="qty_order[]" v-model="part.qty_order">
                          <input type="hidden" name="harga[]" v-model="part.harga">
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>            
                          <td class="align-middle">
                            <vue-numeric currency="Rp " :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.harga"></vue-numeric>
                          </td>            
                          <td class="align-middle">
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_on_hand"></vue-numeric>
                          </td>  
                          <td class="align-middle">
                            <vue-numeric :read-only="mode == 'detail' || mode == 'terima_claim'" class="form-control" separator="." :empty-value="1" v-model="part.qty_order"></vue-numeric>
                          </td>     
                          <td class="align-middle">
                            <vue-numeric currency="Rp " :read-only="true" class="form-control" separator="." :empty-value="1" v-model="subTotal(part)"></vue-numeric>
                          </td>  
                          <td v-if="mode != 'detail' && mode != 'terima_claim'" class="align-middle">
                            <button class="btn btn-sm" v-on:click.prevent="hapusPart(index)">Hapus</button>
                          </td>                              
                        </tr>
                        <tr v-if="parts.length < 1">
                          <td class="text-center" colspan="7">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>                                                                                                                                
                <div v-if="mode != 'detail' && mode != 'terima_claim'" class="form-group">
                  <div class="col-sm-12">
                    <button type="button" class="pull-right btn btn-flat btn-info btn-sm" data-toggle="modal" data-target="#modal-stock-md">Tambah</button>
                  </div>
                  <?php $this->load->view('modal/h3_md_stock_modal'); ?>
                  <script>
                    function choose_stock(part) {
                      part.qty_order = 1;
                      vueForm.parts.push(part);
                    }
                  </script>
                </div> 
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-12 no-padding">
                  <button v-if="mode == 'insert'" class="btn btn-flat btn-primary">Submit</button>
                  <button v-if="mode == 'edit'" class="btn btn-flat btn-warning">Update</button>
                  <?php if($mode == 'detail'): ?>
                  <a class="btn btn-flat btn-warning" href="h3/<?= $isi ?>/edit?id=<?= $so_other->id_so_other ?>">Edit</a>
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
            <?php if($mode == 'detail' or $mode == 'edit'): ?>
            parts: <?= json_encode($so_other_parts) ?>,
            respon: '',
            tipe_ganti: '',
            customer: <?= json_encode($dealer) ?>,
            <?php else: ?>
            parts: [],
            customer: {},
            <?php endif; ?>
          },
          methods: {
            ppnPerPart: function(part) {
              return (10 / 100) * part.harga ;
            },
            subTotal: function(part) {
              return (part.qty_order * part.harga ) + this.ppnPerPart(part);
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
              <th>ID SO</th>              
              <th>Tanggal</th>              
              <th>Customer</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>    
            <?php foreach($so_other as $each): ?>
            <?php $dealer = $this->dealer->find($each->id_dealer, 'id_dealer'); ?>            
            <tr>
              <td><?= $each->id_so_other ?></td>              
              <td><?= date('d-m-Y', strtotime($each->tanggal)) ?></td>              
              <td><?= $dealer->nama_dealer ?></td>              
              <td><?= $each->jenis_pembayaran ?></td>              
              <td>
                <a href='h3/<?= $isi ?>/detail?id=<?= $each->id_so_other ?>' class='btn btn-flat btn-info btn-xs'>View</a>
              </td>              
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
