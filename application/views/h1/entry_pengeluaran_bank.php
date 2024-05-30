<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 30px;  
  padding-left: 5px;
  padding-right: 5px;  
  margin-right: 0px; 
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}

.disabled-select {
  background-color: #d5d5d5;
  opacity: 0.5;
  border-radius: 3px;
  cursor: not-allowed;
  position: absolute;
  top: 0;
  bottom: 0;
  right: 0;
  left: 0;
}

select[readonly].select2-hidden-accessible + .select2-container {
  pointer-events: none;
  touch-action: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
  background: #eee;
  box-shadow: none;
}

select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow,
select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
  display: none;
}

</style>
<base href="<?php echo base_url(); ?>" />
<!-- <body onload="kirim_data_pl()"> -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Finance</li>
    <li class="">Bank,Kas,BG Beredar</li>
    <li class="">Bank/Cash</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>
  <section class="content">


    
    <?php 
    if($set=="form"){
    ?>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/entry_pengeluaran_bank">
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
            <form id="form_" class="form-horizontal" action="h1/entry_pengeluaran_bank/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">                       
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Voucher</label>
                  <div class="col-sm-4">
                     <select class="form-control select2" id="no_voucher" name="no_voucher" >
                      <option value="">- choose -</option>
                      <?php 
                      $r = $this->db->query("SELECT * FROM tr_voucher_bank WHERE status <> 'batal' AND id_voucher_bank NOT IN (SELECT no_voucher FROM tr_pengeluaran_bank)");
                      foreach ($r->result() as $isi) {
                        $cek_no_bg = $this->db->get_where('tr_voucher_bank_bg',['id_voucher_bank'=>$isi->id_voucher_bank]); ?>
                        <option value='<?= $isi->id_voucher_bank ?>'><?= $isi->id_voucher_bank ?></option>";
                     <?php }
                      ?>
                    </select>
                  </div>                                                      
                  <div class="col-sm-4">
                    <button @click.prevent="form_.generate" type="button" class="btn btn-flat btn-primary">Generate</button>    
                  </div>                                    
                </div>  
                 <button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button><br><br>
                 <table class="table table-bordered">
                   <thead>
                     <th style="width: 20%">No BG / Transfer</th>
                     <th>Jenis</th>
                     <th style="width: 20%">Nilai Transfer/BG-Cek</th>
                     <th style="width: 20%">Tanggal Cair</th>
                   </thead>
                   <tbody>
                    <tr v-for="(dtl, index) of details">
                      <td>{{dtl.no_bg_alias}}</td>
                      <td>{{dtl.jenis}}</td>
                      <td>
                        <input type="hidden" name="no_bg[]" v-model="dtl.no_bg">
                        <input type="hidden" name="total[]" v-model="dtl.nominal">
                        <input type="hidden" name="jenis[]" v-model="dtl.jenis">
                        <vue-numeric style="float: left;text-align: right;"
                          class="form-control text-rata-kanan isi" v-model="dtl.nominal" 
                          v-bind:minus="false" readonly :empty-value="0"  separator="."/>
                      </td>
                      <td>
                        <date-picker name="tgl_cair[]" v-model="dtl.tgl_cair" autocomplete="off" class="form-control isi tgl_cair"></date-picker>
                      </td>
                    </tr>
                   </tbody>
                 </table><br>
                 <span id="tampil_detail"></span>
              </div><!-- /.box-body -->              
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="button" onclick="form_.submitForm()" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>

<script>
var form_ = new Vue({
    el: '#form_',
    data: {
      mode : '<?= $mode ?>',
      detail : { no_bg : '',
                 nominal : '',
                 tgl_bg : '',
                 jenis : '',
                 tgl_cair : '',
                 no_bg_alias : '',
      },
      details : [],
    },
    methods: {
      generate: function() {
        var no_voucher = $('#no_voucher').val();
        if (no_voucher=='') {
          alert('Silahkan pilih No Voucher !');
          return false
        }
        values = {no_voucher:no_voucher}
        $.ajax({
          url:"<?php echo site_url('h1/entry_pengeluaran_bank/generate');?>",
          type:"POST",
          data:values,
          cache:false,
          dataType:'JSON',
          success:function(response){
            // console.log(response);
              if (response.length>0) {
                kirim_data();
                form_.details         = [];
                for (i = 0; i < response.length; i++){
                  form_.clearDetail();
                  console.log(response[i]);
                  form_.detail.no_bg_alias = response[i].jenis=='Transfer'?'-':response[i].no_bg;
                  form_.detail.no_bg       = response[i].no_bg;
                  form_.detail.nominal     = response[i].nominal_bg;
                  form_.detail.tgl_bg      = response[i].tgl_bg;
                  form_.detail.jenis       = response[i].jenis;
                  form_.detail.tgl_cair    = '';
                  form_.details.push(form_.detail);
                }
              }else{
                alert('Data tidak ditemukan !');
              }
          }
        });
      },
      clearDetail: function(){
        this.detail={
          no_bg :'',
          nominal : '',
          jenis : '',
          tgl_bg : '',
          tgl_cair : '',
          no_bg_alias:'',
        }
      },
      
      addDetails : function(detail){

        // console.log(detail)
        // if (form_.details.length > 0) {
        //   for (detail of form_.details) {
        //       if (detail.referensi === this.detail.referensi) {
        //           alert("Referensi Sudah Dipilih !");
        //           this.clearDetail();
        //           return;
        //       }
        //   }
        // }

        if (this.detail.no_kpb === '' || this.detail.kpb_ke === ''|| this.detail.km_service === ''|| this.detail.tgl_service === '') {
          alert('Silahkan isi data dengan lengkap !');
          return false;
        }
        // if (parseInt(this.detail.qty_retur) == 0 || this.detail.qty_retur=='') {
        //   alert('Qty Retur Tidak boleh lebih kecil dari 1');
        //   return;
        // }
        var new_detail = {
          no_mesin : this.detail.no_mesin,
          no_rangka : this.detail.no_rangka,
          id_tipe_kendaraan : this.detail.id_tipe_kendaraan,
          tipe_ahm : this.detail.tipe_ahm,
          no_kpb : this.detail.no_kpb,
          kpb_ke : this.detail.kpb_ke,
          tgl_beli_smh : this.detail.tgl_beli_smh,
          km_service : this.detail.km_service,
          tgl_service : this.detail.tgl_service,
          kpb_detail : this.kpb_detail
        }

        this.details.push(new_detail);
        this.clearDetail();
        this.kpb_detail =[];
      },

      delDetails: function(index){
          this.details.splice(index, 1);
      },
      submitForm: function(){
        // console.log(this.detail.length);
        count_detail = this.details.length;
        if (count_detail==0) {
          alert('isi data dengan lengkap !');
          return false;
        }
        var check_tgl_cair = 0;
          $( ".tgl_cair" ).each(function( index ) {
            if ($(this).val()!='') {
              check_tgl_cair++;
            }
          });
          if (check_tgl_cair==0) {
            alert('Anda belum menentukan tanggal cair !');
            return false;
          }
          if (check_tgl_cair>0) {
            var response = confirm("Are you sure to save all data ?");
            if (response==true) {
              $('#submitBtn').attr('disabled',true);
              $("#form_").submit();
            }
          }
      }
    },
    watch:{
      detail:function () {
        // alert('dd');
      }
    },
    computed: {
      totPembayaran: function(){
        total=0;
        for(dtl of this.details)
        {
          total+=dtl.nominal;
        }
        if (isNaN(total)) return 0;
        // return total.toFixed(1);
        return total;
      }
    },
});
Vue.component('date-picker',{
    template: '<input type="text" v-datepicker class="form-control isi_combo" :value="value" @input="update($event.target.value)">',
    directives: {
        datepicker: {
            inserted (el, binding, vNode) {
                $(el).datepicker({
                    autoclose: true,
                    format: 'yyyy-mm-dd',
                    todayHighlight:false,
                }).on('changeDate',function(e){
                    vNode.context.$emit('input', e.format(0))
                })
            }
        }
    },
    props: ['value'],
    methods: {
        update (v){
            this.$emit('input', v)
        }
    }
})
</script>
 <?php 
    }elseif($set=="insert_old"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/entry_pengeluaran_bank">
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
            <form class="form-horizontal" action="h1/entry_pengeluaran_bank/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">                       
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">No Voucher</label>
                  <div class="col-sm-4">
                    <!-- <input type="text" readonly value="" class="form-control" id="no_voucher" name="no_voucher"> -->
                     <select class="form-control select2" id="no_voucher" name="no_voucher" onchange="getNoBG()" >
                      <option value="">- choose -</option>
                      <?php 
                      $r = $this->db->query("SELECT * FROM tr_voucher_bank WHERE id_voucher_bank NOT IN(SELECT no_voucher FROM tr_pengeluaran_bank)");
                      foreach ($r->result() as $isi) {
                        $cek_no_bg = $this->db->get_where('tr_voucher_bank_bg',['id_voucher_bank'=>$isi->id_voucher_bank]);
                        $no_bg = $cek_no_bg->num_rows()>0?$cek_no_bg->row()->no_bg:''; ?>
                        <option value='<?= $isi->id_voucher_bank ?>' data-no_bg="<?= $no_bg ?>"><?= $isi->id_voucher_bank ?></option>";
                     <?php }
                      ?>
                    </select>
                  </div>                                                      
                  <div class="col-sm-4">
                    <button onclick="kirim_data()" type="button" class="btn btn-flat btn-primary">Generate</button>    
                  </div>                                    
                </div>       

                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No BG</label>
                  <div class="col-sm-4">                  
                    <!-- <select class="form-control select2" name="no_bg" id="no_bg">
                      <option value="">- choose -</option>
                      <?php 
                      $r = $this->db->query("SELECT DISTINCT(no_bg) FROM tr_voucher_bank_bg");
                      foreach ($r->result() as $isi) {
                        echo "<option value='$isi->no_bg'>$isi->no_bg</option>";
                      }
                      ?>
                    </select> -->
                    <!-- <select class="form-control select2" name="no_bg" id="no_bg" onchange="getVoucher()"> -->
                    <select class="form-control select2" name="no_bg" id="no_bg" readonly='readonly'>
                      <option value="">-</option>
                      <?php 
                      $r = $this->db->query("SELECT DISTINCT(no_bg),id_voucher_bank FROM tr_voucher_bank_bg");
                      foreach ($r->result() as $isi) {
                        echo "<option value='$isi->no_bg' data-no_voucher='$isi->id_voucher_bank
                        '>$isi->no_bg</option>";
                      }
                      ?>
                    </select>
                  </div>                                                      
                </div>
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Cair</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_cair" id="tanggal3" class="form-control" placeholder="Tanggal Cair" required>
                  </div>
                </div>           
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nilai BG/Cek</label>
                  <div class="col-sm-4">
                    <input type="text" name="total_pembayaran_real" id="total_pembayaran_real" class="form-control" placeholder="Nilai BG/Cek" readonly>
                    <input type="hidden" name="total_pembayaran" id="total_pembayaran">
                  </div>                                                                        
                </div>                                                                  
                <br>                                    
                <span id="tampil_detail"></span>
              </div><!-- /.box-body -->              
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/entry_pengeluaran_bank/add">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          <a href="h1/entry_pengeluaran_bank/history">            
            <button class="btn btn-warning btn-flat margin"><i class="fa fa-refresh"></i> History</button>
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>            
              <th>No Bukti</th>                           
              <th>Tgl Entry</th> 
              <th>Tgl Cair</th>             
              <th>Amount</th>                            
              <th>Customer</th>                            
              <th>Status</th>              
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_pengeluaran->result() as $row) {                                         
            if($row->status=='input'){
            	$tom = "<a class='btn btn-flat btn-xs btn-primary' href='h1/entry_pengeluaran_bank/approve?id=$row->id_pengeluaran_bank'>Approve</a>";
              $status = "<span class='label label-warning'>$row->status</span>";
            }else{
            	$tom = "";
              $status = "<span class='label label-success'>$row->status</span>";
            }
            $dt = $this->db->query("SELECT * FROM tr_voucher_bank WHERE id_voucher_bank = '$row->no_voucher'")->row();    
            $customer = $dt->dibayar;
            if ($dt->tipe_customer=='Dealer') {
              $customer = $this->db->get_where('ms_dealer',['id_dealer'=>$dt->dibayar])->row()->nama_dealer;
            }                              
            if ($dt->tipe_customer=='Vendor') {
              $customer='';
              $is_customer = $this->db->get_where('ms_vendor',['id_vendor'=>$dt->dibayar]);
              if($is_customer->num_rows()>0){
                $customer = $is_customer->row()->vendor_name;
              }
            }                 
            echo "          
            <tr>               
              <td>$no</td>                           
              <td>$row->id_pengeluaran_bank</td>                           
              <td>$row->tgl_entry</td>                            
              <td>$row->tgl_cair</td>                            
              <td>".mata_uang2($row->total)."</td>                                          
              <td>$row->no_voucher ($customer)</td>                                          
              <td>$status</td>                                                        
              <td>$tom</td>                                                      
              ";                                      
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }elseif($set=="history"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/entry_pengeluaran_bank">            
            <button class="btn bg-red btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>            
              <th>No Bukti</th>                           
              <th>Tgl Entry</th> 
              <th>Tgl Cair</th>             
              <th>Amount</th>                            
              <th>Customer</th>                            
              <th>Status</th>              
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_pengeluaran->result() as $row) {                                         
            if($row->status=='input'){
              $tom = "<a class='btn btn-flat btn-xs btn-primary' href='h1/entry_pengeluaran_bank/approve?id=$row->id_pengeluaran_bank'>Approve</a>";
              $status = "<span class='label label-warning'>$row->status</span>";
            }else{
              $tom = "";
              $status = "<span class='label label-success'>$row->status</span>";
            }
            $dt = $this->db->query("SELECT * FROM tr_voucher_bank WHERE id_voucher_bank = '$row->no_voucher'")->row();    
            $customer = $dt->dibayar;
            if ($dt->tipe_customer=='Dealer') {
              $customer = $this->db->get_where('ms_dealer',['id_dealer'=>$dt->dibayar]);
              $customer = ($customer->num_rows() > 0) ? $customer->row()->nama_dealer : "" ;              
            }                              
            if ($dt->tipe_customer=='Vendor') {
              $customer = $this->db->get_where('ms_vendor',['id_vendor'=>$dt->dibayar]);
              $customer = ($customer->num_rows() > 0) ? $customer->row()->vendor_name : "" ;
            }                 
            echo "          
            <tr>               
              <td>$no</td>                           
              <td>$row->id_pengeluaran_bank</td>                           
              <td>$row->tgl_entry</td>                            
              <td>$row->tgl_cair</td>                            
              <td>".mata_uang2($row->total)."</td>                                          
              <td>$row->no_voucher ($customer)</td>                                          
              <td>$status</td>                                                        
              <td>$tom</td>                                                      
              ";                                      
          $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>
<script type="text/javascript">
function sum(){      
  var no_voucher = $("#no_voucher").val();            
  $.ajax({
    url : "<?php echo site_url('h1/entry_pengeluaran_bank/cari_total')?>",
    type:"POST",
    data:"no_voucher="+no_voucher,
    cache:false,
    success:function(msg){            
      data=msg.split("|");
      $("#total_pembayaran").val(data[0]);
      $("#total_pembayaran_real").val(convertToRupiah(data[0]));
    }
  })        
}
function kirim_data(){    
  $("#tampil_detail").show();
  var no_voucher = document.getElementById("no_voucher").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_voucher="+no_voucher;                           
     xhr.open("POST", "h1/entry_pengeluaran_bank/t_detail", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_detail").innerHTML = xhr.responseText;
                sum();
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}

// function getVoucher()
// {
//   var no_voucher = $("#no_bg").select2().find(":selected").data("no_voucher");
//   // $('#no_voucher').val(no_voucher);
//    // $('#no_voucher').select2().val(no_voucher).trigger('change');  
//   // console.log(no_voucher);
// }
function readonly_select(objs, action) {
  if (action === true)
    objs.prepend('<div class="disabled-select"></div>');
  else
    $(".disabled-select", objs).remove();
}

function getNoBG()
{
  var no_bg = $("#no_voucher").select2().find(":selected").data("no_bg");
  if (no_bg=='') {
    $("#no_bg").attr("readonly", "readonly");
    $('#no_bg').select2().val('').trigger('change');  
  }else{
    $('#no_bg').select2().val(no_bg).trigger('change');  
  }
  sum();
}
</script>