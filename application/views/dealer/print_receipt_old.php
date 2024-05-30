<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Penjualan Unit</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>

  <section class="content">

<?php 
    if($set=="form_tjs"){
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='insert') {
        $form = 'save_tjs';
      }
      if ($mode=='edit') {
        // $readonly ='readonly';
        $form = 'save_edit';
      }
      if ($mode=='detail') {
        $disabled = 'disabled';
      }
    ?>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
    <?php if (isset($row)) { ?>
        $('#id_spk').val('<?= $row->id_spk ?>').trigger('change');
    <?php } ?>
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/print_receipt">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <div class="row">
          <div class="col-md-12">
            <form  class="form-horizontal" id="form_" action="dealer/print_receipt/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">ID SPK</label>
                  <div class="col-sm-4">
                    <select name="id_spk" id="id_spk" onchange="getSPK()" class="form-control select2" <?= $disabled ?> required>
                      <option value="">--choose-</option>
                      <?php foreach ($spk->result() as $rs): 
                        $selected = isset($row)?$rs->no_spk==$row->id_spk?'selected':'':'';
                      ?>
                        <option value="<?= $rs->no_spk ?>" <?= $selected ?> 
                              data-no_spk             = "<?= $rs->no_spk ?>"
                              data-nama_konsumen      = "<?= $rs->nama_konsumen ?>"
                              data-id_sales_people    = "<?= $rs->id_sales_people ?>"
                              data-id_karyawan_dealer = "<?= $rs->id_karyawan_dealer ?>"
                              data-no_ktp             = "<?= $rs->no_ktp ?>"
                              data-no_hp              = "<?= $rs->no_hp ?>"
                              data-tipe_pembayaran    = "<?= $rs->tipe_pembayaran ?>"
                              data-id_tipe_kendaraan  = "<?= $rs->id_tipe_kendaraan ?>"
                              data-tipe_ahm           = "<?= $rs->tipe_ahm ?>"
                              data-id_warna           = "<?= $rs->id_warna ?>"
                              data-warna              = "<?= $rs->warna ?>"
                              data-harga_on_road        = "<?= $rs->harga_on_road-$rs->diskon ?>"
                              data-tanda_jadi         = "<?= $rs->tanda_jadi ?>"
                              data-diskon         = "<?= $rs->diskon ?>"
                              data-id_invoice         = "<?= $rs->id_invoice ?>"
                        ><?= $rs->no_spk.' | '.$rs->nama_konsumen ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                   <label for="inputEmail3" class="col-sm-2 control-label">ID Invoice TJS</label></label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" name="id_invoice" id="id_invoice" autocomplete="off" readonly>
                  </div> 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">                   
                    <input type="text" required class="form-control" disabled id="tipe" name="tipe">
                  </div> 
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" disabled id="warna" name="warna">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Cara Bayar</label>
                  <div class="col-sm-4">                   
                    <select name="cara_bayar" id="cara_bayar" v-model="cara_bayar" class="form-control" <?= $disabled ?>>
                      <option value="">--choose--</option>
                      <option value="cash">Cash</option>
                      <option value="transfer">Transfer</option>
                      <option value="kartu_kredit">Kartu Kredit</option>
                    </select>
                  </div> 
                  <label for="inputEmail3" class="col-sm-2 control-label">Amount</label>
                  <div class="col-sm-4">
                    <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" name="amount" v-model="tanda_jadi"  readonly
                          v-bind:minus="false" :empty-value="0" separator="."/>                                       
                  </div>
                  </div>
                  <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Note</label>
                  <div class="col-sm-4">                   
                    <input type="text" required class="form-control" name="note" v-model="note" <?= $disabled ?>>
                  </div> 
                  <label for="inputEmail3" class="col-sm-2 control-label">Creation Date</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" disabled v-model="created_at">
                  </div>
                </div>
              </div><!-- /.box-body -->
             <div>
                        
              <div class="box-footer" v-if="mode!='detail'">
                <div class="col-sm-12" v-if="mode=='insert'||mode=='edit'" align="center">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        total_harga : '',
        sisa_pelunasan :'',
        tanda_jadi:'',
        diskon : '<?= isset($row)?$row->diskon:''?>',
        cara_bayar : '<?= isset($row)?$row->cara_bayar:''?>',
        note : '<?= isset($row)?$row->note:''?>',
        created_at : '<?= isset($row)?$row->created_at:''?>',
      },
    methods: {
      clearDealers: function () {
      this.dealer = {
              id_dealer:'',
              nama_dealer:''
        }
      },
      addDealers : function(){
        if (this.dealers.length > 0) {
          for (dl of this.dealers) {
            if (dl.id_dealer === this.dealer.id_dealer) {
                alert("Dealer Sudah Dipilih !");
                this.clearDealers();
                return;
            }
          }
        }
        if (this.dealer.id_dealer=='') 
        {
          alert('Pilih Dealer !');
          return false;
        }
        this.dealers.push(this.dealer);
        this.clearDealers();
      },

      delDealers: function(index){
          this.dealers.splice(index, 1);
      },
      getDealer: function(){
        var el   = $('#dealer').find('option:selected'); 
        var id_dealer    = el.attr("id_dealer"); 
        form_.dealer.id_dealer = id_dealer;
      },
    },
  });
function getSPK() {
  var nama_konsumen     = $("#id_spk").select2().find(":selected").data("nama_konsumen");$('#nama_konsumen').val(nama_konsumen);
  var no_ktp             = $("#id_spk").select2().find(":selected").data("no_ktp");$('#no_ktp').val(no_ktp);
  var id_sales_people             = $("#id_spk").select2().find(":selected").data("id_sales_people");$('#id_sales_people').val(id_sales_people);
  var id_karyawan_dealer             = $("#id_spk").select2().find(":selected").data("id_karyawan_dealer");$('#id_karyawan_dealer').val(id_karyawan_dealer);
  var tipe_pembayaran             = $("#id_spk").select2().find(":selected").data("tipe_pembayaran");$('#tipe_pembayaran').val(tipe_pembayaran);
  var id_invoice             = $("#id_spk").select2().find(":selected").data("id_invoice");$('#id_invoice').val(id_invoice);

  var id_tipe_kendaraan = $("#id_spk").select2().find(":selected").data("id_tipe_kendaraan");
  var tipe_ahm          = $("#id_spk").select2().find(":selected").data("tipe_ahm");
  $('#tipe').val(id_tipe_kendaraan+' | '+tipe_ahm);
  var id_warna          = $("#id_spk").select2().find(":selected").data("id_warna");
  var warna             = $("#id_spk").select2().find(":selected").data("warna");
  $('#warna').val(id_warna+' | '+warna);

  var harga_on_road             = $("#id_spk").select2().find(":selected").data("harga_on_road");
  var tanda_jadi             = $("#id_spk").select2().find(":selected").data("tanda_jadi");
  
  var diskon             = $("#id_spk").select2().find(":selected").data("diskon");

  form_.tanda_jadi= tanda_jadi;
}

</script>
<?php }  ?>

<?php if($set=="index"){ ?>

    <div class="box">
      <div class="box-body">
        <div class="nav-tabs-custom" style="margin-bottom: 10px">
        <ul class="nav nav-tabs">
          <li class="active"><a href="<?= base_url('dealer/print_receipt') ?>">Tanda Jadi</a></li>
          <li class=""><a href="<?= base_url('dealer/print_receipt/dp') ?>">Down Payment (DP)</a></li>
          <li class=""><a href="<?= base_url('dealer/print_receipt/pelunasan') ?>">Pelunasan</a></li>
        </ul>
      </div>
      <a href="dealer/print_receipt/add_tjs">
        <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
      </a>
      <hr style="margin-top: 0px">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <table id="datatable_server" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID Invoice TJS</th>
              <th>ID SPK</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>Amount</th>
              <th>Cara Bayar</th>
              <th>Note</th>
              <th>Creation Date</th>
              <th width="10%">Action</th>
            </tr>
          </thead>          
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<script>
   $(document).ready(function(){  
      var dataTable = $('#datatable_server').DataTable({  
         "processing":true, 
         "serverSide":true, 
         "language": {                
              "infoFiltered": "",
              "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
          }, 
         "order":[],
         "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],  
         "ajax":{  
              url:"<?php echo site_url('dealer/print_receipt/fetch_tjs'); ?>",  
              type:"POST",
              dataSrc: "data",
              data: function (d) {
                  // d.start_date = $('#start_date').val();
                  // d.end_date = $('#end_date').val();
                  d.<?php echo $this->security->get_csrf_token_name(); ?>='<?php echo $this->security->get_csrf_hash(); ?>';
                  return d;
              },
         },  
         "columnDefs":[  
              // { "targets":[2],"orderable":false},
              { "targets":[8],"className":'text-center'}, 
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              { "targets":[6,7],"className":'text-right'}, 
              // // { "targets":[2,4,5], "searchable": false } 
         ],
      });
    });
</script>
<?php } ?>



<?php if($set=="index_dp"){ ?>

    <div class="box">
      <div class="box-body">
        <div class="nav-tabs-custom" style="margin-bottom: 10px">
        <ul class="nav nav-tabs">
          <li class=""><a href="<?= base_url('dealer/print_receipt') ?>">Tanda Jadi</a></li>
          <li class="active"><a href="<?= base_url('dealer/print_receipt/dp') ?>">Down Payment (DP)</a></li>
          <li class=""><a href="<?= base_url('dealer/print_receipt/pelunasan') ?>">Pelunasan</a></li>
        </ul>
      </div>
      <a href="dealer/print_receipt/add_dp">
        <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
      </a>
      <hr style="margin-top: 0px">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <table id="datatable_server" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID Invoice DP</th>
              <th>ID SPK</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>Amount DP</th>
              <th>Cara Bayar</th>
              <th>Note</th>
              <th>Creation Date</th>
              <th>Status</th>
              <th width="10%">Action</th>
            </tr>
          </thead>          
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<script>
   $(document).ready(function(){  
      var dataTable = $('#datatable_server').DataTable({  
         "processing":true, 
         "serverSide":true, 
         "language": {                
              "infoFiltered": "",
              "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
          }, 
         "order":[],
         "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],  
         "ajax":{  
              url:"<?php echo site_url('dealer/print_receipt/fetch_dp'); ?>",  
              type:"POST",
              dataSrc: "data",
              data: function (d) {
                  // d.start_date = $('#start_date').val();
                  // d.end_date = $('#end_date').val();
                  d.<?php echo $this->security->get_csrf_token_name(); ?>='<?php echo $this->security->get_csrf_hash(); ?>';
                  return d;
              },
         },  
         "columnDefs":[  
              // { "targets":[2],"orderable":false},
              { "targets":[8,9],"className":'text-center'}, 
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              { "targets":[4],"className":'text-right'}, 
              // // { "targets":[2,4,5], "searchable": false } 
         ],
      });
    });
</script>
<?php } ?>

<?php 
    if($set=="form_dp"){
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='insert') {
        $form = 'save_dp';
      }
      if ($mode=='edit') {
        // $readonly ='readonly';
        $form = 'save_edit';
      }
      if ($mode=='detail') {
        $disabled = 'disabled';
      }
    ?>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
    <?php if (isset($row)) { ?>
        $('#id_spk').val('<?= $row->id_spk ?>').trigger('change');
    <?php } ?>
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/print_receipt/dp">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <div class="row">
          <div class="col-md-12">
            <form  class="form-horizontal" id="form_" action="dealer/print_receipt/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">ID SPK</label>
                  <div class="col-sm-4">
                    <select name="id_spk" id="id_spk" onchange="getSPK()" class="form-control select2" <?= $disabled ?> required>
                      <option value="">--choose-</option>
                      <?php foreach ($spk->result() as $rs): 
                        $selected = isset($row)?$rs->no_spk==$row->id_spk?'selected':'':'';
                      ?>
                        <option value="<?= $rs->no_spk ?>" <?= $selected ?> 
                              data-no_spk             = "<?= $rs->no_spk ?>"
                              data-nama_konsumen      = "<?= $rs->nama_konsumen ?>"
                              data-id_sales_people    = "<?= $rs->id_sales_people ?>"
                              data-id_karyawan_dealer = "<?= $rs->id_karyawan_dealer ?>"
                              data-no_ktp             = "<?= $rs->no_ktp ?>"
                              data-no_hp              = "<?= $rs->no_hp ?>"
                              data-tipe_pembayaran    = "<?= $rs->tipe_pembayaran ?>"
                              data-id_tipe_kendaraan  = "<?= $rs->id_tipe_kendaraan ?>"
                              data-tipe_ahm           = "<?= $rs->tipe_ahm ?>"
                              data-id_warna           = "<?= $rs->id_warna ?>"
                              data-warna              = "<?= $rs->warna ?>"
                              data-harga_on_road      = "<?= $rs->harga_on_road-$rs->diskon ?>"
                              data-dp_stor            = "<?= $rs->dp_stor ?>"
                              data-diskon             = "<?= $rs->diskon ?>"
                              data-id_invoice_dp      = "<?= $rs->id_invoice_dp ?>"
                        ><?= $rs->no_spk.' | '.$rs->nama_konsumen ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                   <label for="inputEmail3" class="col-sm-2 control-label">ID Invoice dp</label></label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" name="id_invoice_dp" id="id_invoice_dp" autocomplete="off" readonly>
                  </div> 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">                   
                    <input type="text" required class="form-control" disabled id="tipe" name="tipe">
                  </div> 
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" disabled id="warna" name="warna">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pelanggan</label>
                  <div class="col-sm-4">                   
                    <input type="text" required class="form-control" disabled id="nama_konsumen" name="nama_konsumen">
                  </div> 
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Harga</label>
                  <div class="col-sm-4">
                    <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" name="total_harga" v-model="total_harga"  readonly
                          v-bind:minus="false" :empty-value="0" separator="."/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Cara Bayar</label>
                  <div class="col-sm-4">                   
                    <select name="cara_bayar" id="cara_bayar" v-model="cara_bayar" class="form-control" <?= $disabled ?>>
                      <option value="">--choose--</option>
                      <option value="cash">Cash</option>
                      <option value="transfer">Transfer</option>
                      <option value="kartu_kredit">Kartu Kredit</option>
                    </select>
                  </div> 
                  <label for="inputEmail3" class="col-sm-2 control-label">Amount DP</label>
                  <div class="col-sm-4">
                    <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" name="amount_dp" v-model="amount_dp"  readonly
                          v-bind:minus="false" :empty-value="0" separator="."/>                                       
                  </div>
                  </div>
                  <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Note</label>
                  <div class="col-sm-4">                   
                    <input type="text" required class="form-control" name="note" v-model="note" <?= $disabled ?>>
                  </div> 
                  <label for="inputEmail3" class="col-sm-2 control-label">Creation Date</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" disabled v-model="created_at">
                  </div>
                </div>
              </div><!-- /.box-body -->
             <div>
                        
              <div class="box-footer" v-if="mode!='detail'">
                <div class="col-sm-12" v-if="mode=='insert'||mode=='edit'" align="center">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        total_harga : '',
        sisa_pelunasan :'',
        tanda_jadi:'',
        amount_dp : '<?= isset($row)?$row->amount_dp:''?>',
        cara_bayar : '<?= isset($row)?$row->cara_bayar:''?>',
        note : '<?= isset($row)?$row->note:''?>',
        created_at : '<?= isset($row)?$row->created_at:''?>',
      },
    methods: {
      clearDealers: function () {
      this.dealer = {
              id_dealer:'',
              nama_dealer:''
        }
      },
      addDealers : function(){
        if (this.dealers.length > 0) {
          for (dl of this.dealers) {
            if (dl.id_dealer === this.dealer.id_dealer) {
                alert("Dealer Sudah Dipilih !");
                this.clearDealers();
                return;
            }
          }
        }
        if (this.dealer.id_dealer=='') 
        {
          alert('Pilih Dealer !');
          return false;
        }
        this.dealers.push(this.dealer);
        this.clearDealers();
      },

      delDealers: function(index){
          this.dealers.splice(index, 1);
      },
      getDealer: function(){
        var el   = $('#dealer').find('option:selected'); 
        var id_dealer    = el.attr("id_dealer"); 
        form_.dealer.id_dealer = id_dealer;
      },
    },
  });
function getSPK() {
  var nama_konsumen     = $("#id_spk").select2().find(":selected").data("nama_konsumen");$('#nama_konsumen').val(nama_konsumen);
  var no_ktp             = $("#id_spk").select2().find(":selected").data("no_ktp");$('#no_ktp').val(no_ktp);
  var id_sales_people             = $("#id_spk").select2().find(":selected").data("id_sales_people");$('#id_sales_people').val(id_sales_people);
  var id_karyawan_dealer             = $("#id_spk").select2().find(":selected").data("id_karyawan_dealer");$('#id_karyawan_dealer').val(id_karyawan_dealer);
  var tipe_pembayaran             = $("#id_spk").select2().find(":selected").data("tipe_pembayaran");$('#tipe_pembayaran').val(tipe_pembayaran);
  var id_invoice_dp             = $("#id_spk").select2().find(":selected").data("id_invoice_dp");$('#id_invoice_dp').val(id_invoice_dp);

  var id_tipe_kendaraan = $("#id_spk").select2().find(":selected").data("id_tipe_kendaraan");
  var tipe_ahm          = $("#id_spk").select2().find(":selected").data("tipe_ahm");
  $('#tipe').val(id_tipe_kendaraan+' | '+tipe_ahm);
  var id_warna          = $("#id_spk").select2().find(":selected").data("id_warna");
  var warna             = $("#id_spk").select2().find(":selected").data("warna");
  $('#warna').val(id_warna+' | '+warna);

  var harga_on_road             = $("#id_spk").select2().find(":selected").data("harga_on_road");
  var diskon             = $("#id_spk").select2().find(":selected").data("diskon");
  var dp_stor             = $("#id_spk").select2().find(":selected").data("dp_stor");
  form_.amount_dp= dp_stor;
  form_.total_harga=parseInt(harga_on_road);
}

</script>
<?php }  ?>

<?php if($set=="index_pelunasan"){ ?>

    <div class="box">
      <div class="box-body">
        <div class="nav-tabs-custom" style="margin-bottom: 10px">
        <ul class="nav nav-tabs">
          <li class=""><a href="<?= base_url('dealer/print_receipt') ?>">Tanda Jadi</a></li>
          <li class=""><a href="<?= base_url('dealer/print_receipt/dp') ?>">Down Payment (DP)</a></li>
          <li class="active"><a href="<?= base_url('dealer/print_receipt/pelunasan') ?>">Pelunasan</a></li>
        </ul>
      </div>
      <a href="dealer/print_receipt/add_pelunasan">
        <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
      </a>
      <hr style="margin-top: 0px">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <table id="datatable_server" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID Invoice Penjualan</th>
              <th>ID SPK</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>Amount</th>
              <th>Sisa Pelunasan</th>
              <th>Note</th>
              <th>Creation Date</th>
              <th>Status</th>
              <th width="10%">Action</th>
            </tr>
          </thead>          
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<script>
   $(document).ready(function(){  
      var dataTable = $('#datatable_server').DataTable({  
         "processing":true, 
         "serverSide":true, 
         "language": {                
              "infoFiltered": "",
              "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
          }, 
         "order":[],
         "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],  
         "ajax":{
              url:"<?php echo site_url('dealer/print_receipt/fetch_pelunasan'); ?>",  
              type:"POST",
              dataSrc: "data",
              data: function (d) {
                  // d.start_date = $('#start_date').val();
                  // d.end_date = $('#end_date').val();
                  d.<?php echo $this->security->get_csrf_token_name(); ?>='<?php echo $this->security->get_csrf_hash(); ?>';
                  return d;
              },
         },  
         "columnDefs":[  
              // { "targets":[2],"orderable":false},
              { "targets":[8,9],"className":'text-center'}, 
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              { "targets":[4],"className":'text-right'}, 
              // // { "targets":[2,4,5], "searchable": false } 
         ],
      });
    });
</script>
<?php } ?>

<?php 
    if($set=="form_pelunasan"){
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='insert') {
        $form = 'save_pelunasan';
      }
      if ($mode=='edit') {
        // $readonly ='readonly';
        $form = 'save_edit';
      }
      if ($mode=='detail') {
        $disabled = 'disabled';
      }
    ?>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
    // $('.datepicker').datepicker({
    //     format:"yyyy-mm-dd",autoclose: true,            
    // }); 
    <?php if (isset($row)) { ?>
        $('#id_spk').val('<?= $row->id_spk ?>').trigger('change');
    <?php } ?>
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/print_receipt/pelunasan">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <div class="row">
          <div class="col-md-12">
            <form  class="form-horizontal" id="form_" action="dealer/print_receipt/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                   <label for="inputEmail3" class="col-sm-2 control-label">ID SPK</label>
                  <div class="col-sm-4">
                    <select name="id_spk" id="id_spk" onchange="getSPK()" class="form-control select2" <?= $disabled ?> required>
                      <option value="">--choose-</option>
                      <?php foreach ($spk->result() as $rs): 
                        $selected = isset($row)?$rs->no_spk==$row->id_spk?'selected':'':'';
                      ?>
                        <option value="<?= $rs->no_spk ?>" <?= $selected ?> 
                              data-no_spk             = "<?= $rs->no_spk ?>"
                              data-nama_konsumen      = "<?= $rs->nama_konsumen ?>"
                              data-id_sales_people    = "<?= $rs->id_sales_people ?>"
                              data-id_karyawan_dealer = "<?= $rs->id_karyawan_dealer ?>"
                              data-no_ktp             = "<?= $rs->no_ktp ?>"
                              data-no_hp              = "<?= $rs->no_hp ?>"
                              data-tipe_pembayaran    = "<?= $rs->tipe_pembayaran ?>"
                              data-id_tipe_kendaraan  = "<?= $rs->id_tipe_kendaraan ?>"
                              data-tipe_ahm           = "<?= $rs->tipe_ahm ?>"
                              data-id_warna           = "<?= $rs->id_warna ?>"
                              data-warna              = "<?= $rs->warna ?>"
                              data-harga_on_road      = "<?= $rs->harga_on_road-$rs->diskon ?>"
                              data-tanda_jadi            = "<?= $rs->tanda_jadi ?>"
                              data-diskon             = "<?= $rs->diskon ?>"
                              data-voucher_1          = "<?= $rs->voucher_1 ?>"
                              data-voucher_tambahan_1 = "<?= $rs->voucher_tambahan_1 ?>"
                              data-id_inv_pelunasan   = "<?= $rs->id_inv_pelunasan ?>"
                        ><?= $rs->no_spk.' | '.$rs->nama_konsumen ?></option>
                      <?php endforeach ?>
                    </select>
                  </div>
                   <label for="inputEmail3" class="col-sm-2 control-label">ID Invoice Pelunasan</label></label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" name="id_inv_pelunasan" id="id_inv_pelunasan" autocomplete="off" readonly>
                  </div> 
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">                   
                    <input type="text" required class="form-control" disabled id="tipe" name="tipe">
                  </div> 
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" disabled id="warna" name="warna">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Pelanggan</label>
                  <div class="col-sm-4">                   
                    <input type="text" required class="form-control" disabled id="nama_konsumen" name="nama_konsumen">
                  </div> 
                  <label for="inputEmail3" class="col-sm-2 control-label">Diskon</label>
                  <div class="col-sm-4">
                    <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" name="diskon" v-model="diskon"  readonly
                          v-bind:minus="false" :empty-value="0" separator="."/>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Cara Bayar</label>
                  <div class="col-sm-4">                   
                    <select name="cara_bayar" id="cara_bayar" v-model="cara_bayar" class="form-control" <?= $disabled ?>>
                      <option value="">--choose--</option>
                      <option value="cash">Cash</option>
                      <option value="transfer">Transfer</option>
                      <option value="kartu_kredit">Kartu Kredit</option>
                      <option value="cek_giro">Cek / Giro</option>
                    </select>
                  </div> 
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Harga</label>
                  <div class="col-sm-4">
                    <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" name="total_harga" v-model="total_harga"  readonly
                          v-bind:minus="false" :empty-value="0" separator="."/>                                       
                  </div>
                  </div>
                  <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Note</label>
                  <div class="col-sm-4">                   
                    <input type="text" required class="form-control" name="note" v-model="note" <?= $disabled ?>>
                  </div> 
                  <label for="inputEmail3" class="col-sm-2 control-label">Amount</label>
                  <div class="col-sm-4">
                    <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" name="tanda_jadi" v-model="tanda_jadi"  readonly
                          v-bind:minus="false" :empty-value="0" separator="."/>                                       
                  </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Voucher Program</label>
                    <div class="col-sm-4">
                      <vue-numeric style="float: left;width: 100%;text-align: right;"
                            class="form-control text-rata-kanan isi" name="voucher_1" v-model="voucher_1"  readonly
                            v-bind:minus="false" :empty-value="0" separator="."/>                                       
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Voucher Tambahan</label>
                    <div class="col-sm-4">
                      <vue-numeric style="float: left;width: 100%;text-align: right;"
                            class="form-control text-rata-kanan isi" name="voucher_tambahan_1" v-model="voucher_tambahan_1"  readonly
                            v-bind:minus="false" :empty-value="0" separator="."/>                                       
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Sisa Pelunasan</label>
                    <div class="col-sm-4">
                      <vue-numeric style="float: left;width: 100%;text-align: right;"
                            class="form-control text-rata-kanan isi" name="sisa_pelunasan" v-model="sisa_pelunasan"  readonly
                            v-bind:minus="false" :empty-value="0" separator="."/>                                       
                    </div>
                  </div>
                   <div class="form-group">
                    <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Creation Date</label>
                    <div class="col-sm-4">
                    <input type="text" required class="form-control" name="created_at" v-model="created_at" readonly=>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Pembayaran {{cara_bayar}}</button><br><br>
                    </div>
                    <div class="col-md-12" v-if="cara_bayar=='cek_giro'">
                      <table class="table table-bordered">
                        <thead>
                          <th>Bank Konsumen</th>
                          <th>No Rekening Tujuan</th>
                          <th>No Cek / Giro</th>
                          <th>Tgl Cek / Giro</th>
                          <th>Nilai</th>
                          <th v-if="mode!='detail'">Aksi</th>
                        </thead>
                        <tbody>
                          <tr v-for="(cgr, index) of cek_giros">
                            <td>
                              <input type="text" class="form-control" v-model="cgr.bank_konsumen" :disabled="mode=='detail'">
                              <input type="hidden" name="bank_konsumen[]" v-model="cgr.bank_konsumen">
                            </td>
                            <td>
                              <input type="hidden" name="id_norek_dealer_detail[]" v-model="cgr.id_norek_dealer_detail" >
                              {{cgr.bank}} | {{cgr.nama_rek}} | {{cgr.no_rek}}
                            </td>
                            <td>
                              <input type="hidden" name="no_cek_giro[]" v-model="cgr.no_cek_giro">
                              <input type="text" class="form-control" v-model="cgr.no_cek_giro" :disabled="mode=='detail'">
                            </td>
                            <td>
                              <input type="hidden" name="tgl_cek_giro[]" v-model="cgr.tgl_cek_giro">

                              {{cgr.tgl_cek_giro}}
                            </td>
                            <td>
                              <input type="hidden" name="nilai[]" v-model="cgr.nilai">
                               <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" name="amount" v-model="cgr.nilai" v-bind:minus="false" :empty-value="0" separator="." :disabled="mode=='detail'"/>
                            </td>
                            <td v-if="mode!='detail'">
                               <button type="button" @click.prevent="delCekGiro(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                            </td>
                          </tr>
                        </tbody>
                        <tfoot v-if="mode!='detail'">
                        <tr>
                          <td>
                            <input type="text" v-model="cek_giro.bank_konsumen" class="form-control isi" oninput="setDetail()">
                          </td>
                          <td>
                            <select id="rek_dealer" class="form-control" onclick="setDetail()">
                              <?php if ($rek_dealer->num_rows()>0): ?>
                                <option value="">--choose--</option>
                                <?php foreach ($rek_dealer->result() as $rs): ?>
                                  <option value="<?= $rs->id_norek_dealer_detail ?>"
                                    data-bank     = "<?= $rs->bank ?>"
                                    data-no_rek   = "<?= $rs->no_rek ?>"
                                    data-nama_rek = "<?= $rs->nama_rek ?>"
                                    data-id_norek_dealer_detail = "<?= $rs->id_norek_dealer_detail ?>"

                                  >
                                  <?= $rs->bank ?> | <?= $rs->nama_rek ?> | <?= $rs->no_rek ?> </option>
                                <?php endforeach ?>
                              <?php endif ?>
                            </select>
                          </td>
                          <td>
                            <input type="text" v-model="cek_giro.no_cek_giro" class="form-control isi">
                          </td>
                          <td>
                            <input type="text" id="tgl_cek_giro" onchange="setDetail()" class="form-control">
                          </td>
                          <td>
                            <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" name="amount" v-model="cek_giro.nilai" v-bind:minus="false" :empty-value="0" separator="."/>
                          </td>
                          <td align="center">
                            <button type="button" @click.prevent="addCekGiro()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i></button> 
                          </td>
                        </tr>
                      </tfoot>
                      </table>
                    </div>
                    <div class="col-md-12" v-if="cara_bayar=='transfer' || cara_bayar=='kartu_kredit'">
                      <table class="table table-bordered">
                        <thead>
                          <th>Bank Penerima</th>
                          <th>No. Rekening</th>
                          <th>Tgl Transfer</th>
                          <th>Nilai</th>
                          <th v-if="mode!='detail'">Aksi</th>
                        </thead>
                        <tbody>
                          <tr v-for="(trf, index) of transfers">
                            <td>
                              {{trf.bank}} | {{trf.nama_rek}} | {{trf.no_rek}}
                              <input type="hidden" name="id_norek_dealer_detail[]" v-model="trf.id_norek_dealer_detail">
                            </td>
                            <td>
                              {{trf.no_rek}}
                            </td>
                            <td>
                              {{trf.tgl_transfer}}
                              <input type="hidden" name="tgl_transfer[]" v-model="trf.tgl_transfer">
                            </td>
                            <td>
                              <input type="hidden" name="nilai[]" v-model="trf.nilai">
                              <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi"  v-model="trf.nilai" v-bind:minus="false" :empty-value="0" separator="." :disabled="mode=='detail'"/>
                            </td>
                            <td v-if="mode!='detail'">
                               <button type="button" @click.prevent="delTransfer(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                            </td>
                          </tr>
                        </tbody>
                        <tfoot v-if="mode!='detail'">
                        <tr>
                          <td>
                            <select id="rek_dealer" class="form-control" onclick="setDetail()">
                              <?php if ($rek_dealer->num_rows()>0): ?>
                                <option value="">--choose--</option>
                                <?php foreach ($rek_dealer->result() as $rs): ?>
                                  <option value="<?= $rs->id_norek_dealer_detail ?>"
                                    data-bank     = "<?= $rs->bank ?>"
                                    data-no_rek   = "<?= $rs->no_rek ?>"
                                    data-nama_rek = "<?= $rs->nama_rek ?>"
                                    data-id_norek_dealer_detail = "<?= $rs->id_norek_dealer_detail ?>"
                                  >
                                  <?= $rs->bank ?> | <?= $rs->nama_rek ?> | <?= $rs->no_rek ?> </option>
                                <?php endforeach ?>
                              <?php endif ?>
                            </select>
                          </td>
                          <td>
                            <input type="text" v-model="transfer.no_rek" disabled class="form-control">
                          </td>
                          <td>
                            <input type="text" id="tgl_transfer" onchange="setDetail()" class="form-control">
                          </td>
                          <td>
                            <vue-numeric style="float: left;width: 100%;text-align: right;"
                          class="form-control text-rata-kanan isi" name="amount" v-model="transfer.nilai" v-bind:minus="false" :empty-value="0" separator="."/>
                          </td>
                          <td align="center">
                            <button type="button" @click.prevent="addTransfer()" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i></button> 
                          </td>
                        </tr>
                      </tfoot>
                      </table>
                    </div>
                  </div>
              </div><!-- /.box-body -->
             <div>
                        
              <div class="box-footer" v-if="mode!='detail'">
                <div class="col-sm-12" v-if="mode=='insert'||mode=='edit'" align="center">
                  <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        total_harga : '',
        sisa_pelunasan :'',
        voucher_tambahan_1 :'',
        voucher_1 :'',
        tanda_jadi:'',
        cek_giros : <?= isset($cek_giros)?json_encode($cek_giros):'[]' ?>,
        cek_giro:{
          bank_konsumen:'',
          id_norek_dealer_detail:'',
          bank:'',
          no_rek:'',
          nama_rek:'',
          no_cek_giro:'',
          tgl_cek_giro:'',
          nilai:''
        },
        transfers : <?= isset($transfers)?json_encode($transfers):'[]' ?>,
        transfer:{
          id_norek_dealer_detail:'',
          bank:'',
          no_rek:'',
          nama_rek:'',
          tgl_transfer:'',
          nilai:''
        },
        note : '<?= isset($row)?$row->note:''?>',
        created_at : '<?= isset($row)?$row->created_at:''?>',
        diskon : '<?= isset($row)?$row->diskon:''?>',
        tanda_jadi : '<?= isset($row)?$row->tanda_jadi:''?>',
        cara_bayar : '<?= isset($row)?$row->cara_bayar:''?>',
      },
    methods: {
      clearCekGiro: function () {
        $('#tgl_cek_giro').val('')
        $('#rek_dealer').val('')
         this.cek_giro={
          bank_konsumen:'',
          bank:'',
          id_norek_dealer_detail:'',
          no_rek:'',
          nama_rek:'',
          no_cek_giro:'',
          tgl_cek_giro:'',
          nilai:''
        }
      },
      addCekGiro : function(){
        // if (this.dealers.length > 0) {
        //   for (dl of this.dealers) {
        //     if (dl.id_dealer === this.dealer.id_dealer) {
        //         alert("Dealer Sudah Dipilih !");
        //         this.clearDealers();
        //         return;
        //     }
        //   }
        // }
        if (this.cek_giro.bank_konsumen=='' || this.cek_giro.bank=='' || this.cek_giro.no_cek_giro==''|| this.cek_giro.tgl_cek_giro==''|| this.cek_giro.nilai=='') 
        {
          alert('Silahkan Lengkapi Data');
          return false;
        }
        this.cek_giros.push(this.cek_giro);
        this.clearCekGiro();
      },
      delCekGiro: function(index){
          this.cek_giros.splice(index, 1);
      },
       clearTransfer: function () {
        $('#tgl_transfer').val('')
        $('#rek_dealer').val('')
         this.transfer={
          bank:'',
          no_rek:'',
          id_norek_dealer_detail:'',
          nama_rek:'',
          tgl_transfer:'',
          nilai:''
        }
      },
      addTransfer : function(){
        // if (this.dealers.length > 0) {
        //   for (dl of this.dealers) {
        //     if (dl.id_dealer === this.dealer.id_dealer) {
        //         alert("Dealer Sudah Dipilih !");
        //         this.clearDealers();
        //         return;
        //     }
        //   }
        // }
        // console.log(this.transfer)
        if (this.transfer.bank=='' || this.transfer.tgl_transfer==''|| this.transfer.nilai=='') 
        {
          alert('Silahkan Lengkapi Data');
          return false;
        }
        this.transfers.push(this.transfer);
        this.clearTransfer();
      },
      delTransfer: function(index){
          this.transfers.splice(index, 1);
      },
    },
  });
function getSPK() {
  var nama_konsumen      = $("#id_spk").select2().find(":selected").data("nama_konsumen");$('#nama_konsumen').val(nama_konsumen);
  var no_ktp             = $("#id_spk").select2().find(":selected").data("no_ktp");$('#no_ktp').val(no_ktp);
  var id_sales_people    = $("#id_spk").select2().find(":selected").data("id_sales_people");$('#id_sales_people').val(id_sales_people);
  var id_karyawan_dealer = $("#id_spk").select2().find(":selected").data("id_karyawan_dealer");$('#id_karyawan_dealer').val(id_karyawan_dealer);
  var tipe_pembayaran    = $("#id_spk").select2().find(":selected").data("tipe_pembayaran");$('#tipe_pembayaran').val(tipe_pembayaran);
  var id_inv_pelunasan   = $("#id_spk").select2().find(":selected").data("id_inv_pelunasan");$('#id_inv_pelunasan').val(id_inv_pelunasan);

  var id_tipe_kendaraan = $("#id_spk").select2().find(":selected").data("id_tipe_kendaraan");
  var tipe_ahm          = $("#id_spk").select2().find(":selected").data("tipe_ahm");
  $('#tipe').val(id_tipe_kendaraan+' | '+tipe_ahm);
  var id_warna          = $("#id_spk").select2().find(":selected").data("id_warna");
  var warna             = $("#id_spk").select2().find(":selected").data("warna");
  $('#warna').val(id_warna+' | '+warna);
  
  var harga_on_road     = $("#id_spk").select2().find(":selected").data("harga_on_road");
  var diskon            = $("#id_spk").select2().find(":selected").data("diskon");
  var voucher_1            = $("#id_spk").select2().find(":selected").data("voucher_1");
  var voucher_tambahan_1            = $("#id_spk").select2().find(":selected").data("voucher_tambahan_1");
  var tanda_jadi           = $("#id_spk").select2().find(":selected").data("tanda_jadi");
  form_.tanda_jadi       = tanda_jadi;
  form_.voucher_1       = voucher_1;
  form_.voucher_tambahan_1       = voucher_tambahan_1;
  form_.total_harga     =parseInt(harga_on_road) - form_.voucher_tambahan_1 - form_.voucher_1;;
  form_.sisa_pelunasan     =form_.total_harga - tanda_jadi;
}
function setDetail() {
  $('#tgl_cek_giro').datepicker({ format:"yyyy-mm-dd",autoclose: true}); 
  $('#tgl_transfer').datepicker({ format:"yyyy-mm-dd",autoclose: true}); 
  // $("#rek_dealer").select2();
  if (form_.cara_bayar=='cek_giro')setCekGiro();
  if (form_.cara_bayar=='transfer')setTransfer();
  if (form_.cara_bayar=='kartu_kredit')setTransfer();
}
function setCekGiro() {
  form_.cek_giro.tgl_cek_giro = $('#tgl_cek_giro').val();
  form_.cek_giro.bank         = $('#rek_dealer option:selected').data('bank');
  form_.cek_giro.nama_rek     = $('#rek_dealer option:selected').data('nama_rek');
  form_.cek_giro.id_norek_dealer_detail       = $('#rek_dealer option:selected').data('id_norek_dealer_detail');
  form_.cek_giro.no_rek       = $('#rek_dealer option:selected').data('no_rek');
  // console.log(form_.cek_giro)
}
function setTransfer() {
  form_.transfer.tgl_transfer = $('#tgl_transfer').val();
  form_.transfer.bank         = $('#rek_dealer option:selected').data('bank');
  form_.transfer.id_norek_dealer_detail     = $('#rek_dealer option:selected').data('id_norek_dealer_detail');
  form_.transfer.nama_rek     = $('#rek_dealer option:selected').data('nama_rek');
  form_.transfer.no_rek       = $('#rek_dealer option:selected').data('no_rek');
  // console.log(form_.transfer)
}
</script>
<?php }  ?>

  </section>
</div>