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
        <?php 
          $dealer = $this->dealer->find($purchase_order->id_dealer, 'id_dealer');
        ?>
        <div class="row">
          <div class="col-md-12">
            <form id="vueForm" class="form-horizontal" action="h3/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
              <?php if($mode == 'edit'): ?>
              <input type="hidden" name="id_purchase_order" value="<?= $purchase_order->id_purchase_order ?>">
              <?php endif; ?>
              <div class="box-body">       
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nomor PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="tanggal_po" readonly class="form-control" value="<?= $purchase_order->po_id ?>">                    
                  </div>                                
                </div>       
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal PO</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="tanggal_po" readonly class="form-control" value="<?= date('Y-m-d', strtotime($purchase_order->tanggal_order)) ?>">                    
                  </div>                                
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">                    
                    <input type="text" name="tanggal_po" readonly class="form-control" value="<?= $dealer->nama_dealer ?>">                    
                  </div>                                
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr>                                      
                          <th>Part Number</th>              
                          <th>Nama Part</th>              
                          <th>Kelompok</th>              
                          <th>Qty Order</th>
                          <th>HPP</th>
                          <th>Total Harga</th>
                          <th v-if="mode != 'detail'" width="10%">Action</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <tr v-for="(part, index) in parts"> 
                          <input type="hidden" name="id_part[]" v-model="part.id_part">
                          <input type="hidden" name="qty_order[]" v-model="part.qty_order">
                          <input type="hidden" name="harga[]" v-model="part.harga_saat_dibeli">
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>                       
                          <td class="align-middle">{{ part.kelompok_vendor }}</td>                       
                          <td class="align-middle">
                            <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.kuantitas"></vue-numeric>
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
                          <td class="text-center" colspan="15">Belum ada part</td>
                        </tr>
                      </tbody>                    
                    </table>
                  </div>
                </div>     
                <div v-if="mode != 'detail'" class="form-group">
                  <div class="col-sm-12">
                    <button type="button" class="pull-right btn btn-flat btn-info btn-sm" data-toggle="modal" data-target="#modal-part">Tambah</button>
                  </div>
                </div>                                                                                                                              
              </div><!-- /.box-body -->
              <?php $this->load->view('modal/h3_part_modal'); ?>
              <script>
                function choose_part(part) {
                  part.qty_order = 1;
                  part.harga = part.harga_md_dealer;
                  vueForm.parts.push(part);
                }
              </script>
              <div class="box-footer">
                <div class="col-sm-12">
                  <a class="btn btn-warning btn-flat" href="h3/<?= $isi ?>/create_po_hotline?id=<?= $purchase_order->po_id ?>">Buat PO Hotline</a>
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
            <?php if($mode == 'detail' OR $mode == 'edit'): ?>
            parts: <?= json_encode($purchase_order_parts) ?>,
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
        });
    </script>
    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-body">
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th>Purchase Hotline</th>              
              <th>Tanggal PO</th>              
              <th>Referensi</th>              
              <th>Status</th>
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>    
            <?php foreach($purchase_hotline as $each): ?>
            <?php // $dealer = $this->dealer->find($each->id_dealer, 'id_dealer'); ?>            
            <tr>
              <td><?= $each->id_purchase_hotline ?></td>              
              <td><?= date('d-m-Y', strtotime($each->tanggal_po)) ?></td>              
              <td><?= $each->id_ref ?></td>              
              <td><?= $each->status ?></td>              
              <td>
                <a href='h3/<?= $isi ?>/detail?id=<?= $each->id_purchase_hotline ?>' class='btn btn-flat btn-info btn-xs'>View</a>
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
