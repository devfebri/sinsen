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
    <li class="">Indent Fulfillment List</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>

  <section class="content">
    <?php 
    if($set=="form"){
      $form     = '';
      $disabled = '';
      $readonly ='';
      if ($mode=='insert') {
        $form = 'save';
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
        $('#tipe_pesan').val('<?= $row->tipe_pesan ?>').trigger('change');
    <?php } ?>
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/indent_notification_report">
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
            <form  class="form-horizontal" id="form_" action="dealer/indent_notification_report/<?= $form ?>" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Pesan</label>
                  <div class="col-sm-4">
                     <input type="text" required class="form-control" value="<?= isset($row)?$row->id_pesan:'' ?>" autocomplete="off" readonly> 
                  </div>
                </div> 
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Pesan</label>
                  <div class="col-sm-4">
                   <select name="tipe_pesan" id="tipe_pesan" class="form-control select2" <?= $disabled ?>>
                     <option value="">--choose--</option>
                     <option>Reminder Indent</option>
                     <option>Reminder STNK</option>
                     <option>Ucapan Selamat Ulang Tahun</option>
                     <option>Ucapan Selamat Tahun Baru Masehi</option>
                   </select>
                  </div>
                </div>  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Konten</label>
                  <div class="col-sm-4">
                     <textarea name="konten" id="konten" class="form-control" <?= $disabled ?>><?= isset($row)?$row->konten:'' ?></textarea>
                  </div>
                </div>  
                  <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                  <div class="col-sm-4">
                     <input type="text" name="start_date" required class="form-control datepicker" value="<?= isset($row)?$row->start_date:'' ?>" autocomplete="off" <?= $disabled ?>> 
                  </div>
                </div>   
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                  <div class="col-sm-4">
                     <input type="text" name="end_date" required class="form-control datepicker" value="<?= isset($row)?$row->end_date:'' ?>" autocomplete="off" <?= $disabled ?>>  
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
        amount_dp : '<?= isset($row)?$row->tanda_jadi:''?>',
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
  var nama_konsumen      = $("#id_spk").select2().find(":selected").data("nama_konsumen");$('#nama_konsumen').val(nama_konsumen);
  var no_ktp             = $("#id_spk").select2().find(":selected").data("no_ktp");$('#no_ktp').val(no_ktp);
  var id_sales_people    = $("#id_spk").select2().find(":selected").data("id_sales_people");$('#id_sales_people').val(id_sales_people);
  var id_karyawan_dealer = $("#id_spk").select2().find(":selected").data("id_karyawan_dealer");$('#id_karyawan_dealer').val(id_karyawan_dealer);
  var tipe_pembayaran    = $("#id_spk").select2().find(":selected").data("tipe_pembayaran");$('#tipe_pembayaran').val(tipe_pembayaran);
  var id_tipe_kendaraan  = $("#id_spk").select2().find(":selected").data("id_tipe_kendaraan");
  var tipe_ahm           = $("#id_spk").select2().find(":selected").data("tipe_ahm");
  $('#tipe').val(id_tipe_kendaraan+' | '+tipe_ahm);
  var id_warna           = $("#id_spk").select2().find(":selected").data("id_warna");
  var warna              = $("#id_spk").select2().find(":selected").data("warna");
  var harga_tunai        = $("#id_spk").select2().find(":selected").data("harga_tunai");
  var amount_dp          = $("#id_spk").select2().find(":selected").data("dp_stor");
  form_.total_harga = harga_tunai;
  form_.amount_dp = amount_dp;
  $('#warna').val(id_warna+' | '+warna);
}

</script>
    <?php
    }elseif($set=="index"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
                         
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
        <table id="datatable_server" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Kode Indent</th>
              <th>ID SPK</th>
              <th>Tgl Indent</th>
              <th>Nama Customer</th>
              <th>No Telp</th>
              <th>Tipe Motor</th>
              <th>Warna</th>
              <th>Date Notification</th>
              <th>Status</th>
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
              url:"<?php echo site_url('dealer/indent_notification_report/fetch'); ?>",  
              type:"POST",
              dataSrc: "data",
              data: function (d) {
                  return d;
              },
         },  
         "columnDefs":[  
              // { "targets":[2],"orderable":false},
              { "targets":[5],"className":'text-center'}, 
              // // { "targets":[0],"checkboxes":{'selectRow':true}}
              // { "targets":[6,7],"className":'text-right'}, 
              // // { "targets":[2,4,5], "searchable": false } 
         ],
      });
    });
</script>
    <?php
    }
    ?>
  </section>
</div>