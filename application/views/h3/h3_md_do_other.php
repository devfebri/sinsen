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
        $form = 'create';
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
              <?php if($mode == 'detail'): ?>
              <input id="id_do_other" type="hidden" name="id_do_other" value="<?= $do_other->id_do_other ?>">
              <?php endif; ?>
              <div class="box-body">
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal DO</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" value="<?= date('d-m-Y', strtotime($do_other->tanggal)) ?>">
                  </div>     
                  <label class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="customer.nama_dealer">
                  </div>                            
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" value="<?= $do_other->id_do_other ?>">
                  </div>     
                  <label class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" v-model="customer.kode_dealer_md">
                  </div>                            
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Tanggal SO</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" value="<?= date('d-m-Y', strtotime($so_other->tanggal)) ?>">
                  </div>     
                  <label class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">
                    <textarea readonly v-model="customer.alamat" class="form-control" rows="3"></textarea>
                  </div>                            
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">No SO</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" value="<?= $so_other->id_so_other ?>">
                  </div>     
                  <label class="col-sm-2 control-label">Plafon</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control">
                  </div>                            
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">TOP</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control">
                  </div>     
                  <label class="col-sm-2 control-label">Sisa Plafon</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control">
                  </div>                            
                </div>
                <div class="form-group">                  
                  <label class="col-sm-2 control-label">Nama Salesman</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control">
                  </div>     
                  <label class="col-sm-2 control-label">Plafon Booking</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control">
                  </div>                            
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <table id="table" class="table table-bordered table-hover table-responsive">
                      <thead>
                        <tr>                                      
                          <th>Part Number</th>              
                          <th>Nama Part</th>              
                          <th>HET</th>              
                          <th>Diskon Satuan Dealer (%)</th>              
                          <th>Diskon Campaign (% atau Rp)</th>              
                          <th>Qty on Hand MD</th>              
                          <th>Qty AVS</th>              
                          <th>Qty SO</th>              
                          <th>Qty Suggest</th>              
                          <th>Qty Supply</th>              
                          <th>Amount</th>              
                          <th v-if="mode != 'detail' && mode != 'terima_claim'" width="10%">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>            
                        <!-- TODO: Diskon DO Other -->
                        <tr v-for="(part, index) in parts"> 
                          <input type="hidden" name="id_part[]" v-model="part.id_part">
                          <input type="hidden" name="qty_on_hand[]" v-model="part.qty_on_hand">
                          <input type="hidden" name="qty_order[]" v-model="part.qty_order">
                          <input type="hidden" name="qty_suply[]" v-model="part.qty_suply">
                          <input type="hidden" name="harga[]" v-model="part.harga">
                          <td class="align-middle">{{ part.id_part }}</td>                       
                          <td class="align-middle">{{ part.nama_part }}</td>            
                          <td class="align-middle">
                            <vue-numeric currency="Rp " :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.harga"></vue-numeric>
                          </td>            
                          <td></td>
                          <td></td>
                          <td class="align-middle">
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_on_hand"></vue-numeric>
                          </td>  
                          <td class="align-middle">
                            <vue-numeric :read-only="mode == 'detail' || mode == 'terima_claim'" class="form-control" separator="." :empty-value="1" v-model="part.qty_order"></vue-numeric>
                          </td>     
                          <td></td>
                          <td></td>
                          <td>
                            <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_suply"></vue-numeric>
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
                  <a v-if="mode == 'detail'" class="btn btn-flat btn-success" href="h3/h3_md_do_other/approve?id_do_other=<?= $do_other->id_do_other ?>">Approve</a>
                  <button v-if="mode == 'detail'" class="btn btn-flat btn-danger" type="button" data-toggle="modal" data-target="#reject-modal">Reject</button>
                  <div class="modal fade" id="reject-modal" tabindex="-1" role="dialog" aria-labelledby="reject-modal" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="exampleModalLongTitle">Reject DO Other</h5>
                            </div>
                            <div class="modal-body">
                                  <div class="form-group">
                                    <div class="col-sm-12">
                                      <label for="">Alasan reject:</label>
                                      <textarea id="alasan_reject" cols="30" rows="10" class="form-control"></textarea>
                                    </div>
                                  </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button id="save_alasan_reject" type="button" class="btn btn-primary">Save changes</button>
                            </div>
                            <script>
                              $(document).ready(function(){
                                $('#save_alasan_reject').click(function(){
                                  axios.post('h3/h3_md_do_other/reject', Qs.stringify({
                                    alasan_reject: $('#alasan_reject').val(),
                                    id_do_other: $('#id_do_other').val()
                                  }))
                                    .then(function(response) {
                                      if(response.status == 200){;
                                        $('#reject-modal').modal('hide')
                                        location.reload();
                                      }
                                    })
                                    .catch(function(error) {
                                      console.log(error);
                                    });
                                });
                              });
                            </script>
                        </div>
                    </div>
                  </div>
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
              <th>Tanggal SO</th>              
              <th>Tanggal DO</th>              
              <th>No DO</th>              
              <th>Nama Customer</th>              
              <th>Alamat Customer</th>              
              <th>Nilai Amount</th>              
              <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>    
            <?php foreach($do_other as $each): ?>
            <?php $so_other = $this->so_other->find($each->id_so_other, 'id_so_other'); ?>            
            <?php $dealer = $this->dealer->find($so_other->id_dealer, 'id_dealer'); ?>            
            <tr>
              <td><?= date('d-m-Y', strtotime($so_other->tanggal)) ?></td>              
              <td><?= date('d-m-Y', strtotime($each->tanggal)) ?></td>              
              <td><?= $each->id_do_other ?></td>              
              <td><?= $dealer->nama_dealer ?></td>              
              <td><?= $dealer->alamat ?></td>   
              <!-- TODO: Total amount do other. -->
              <td><?= 0 ?></td>              
              <td>
                <a href='h3/<?= $isi ?>/detail?id=<?= $each->id_do_other ?>' class='btn btn-flat btn-info btn-xs'>View</a>
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
