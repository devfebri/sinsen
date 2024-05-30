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
</style>
<base href="<?php echo base_url(); ?>" />
<?php if (isset($row->id_penerimaan_bank)) {}else{ ?>
  <body onload="sembunyi()">
<?php } ?>
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
    <li class="">Bank,KS,BG Beredar</li>
    <li class="">Bank/Cash</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>
  <section class="content">


    
    <?php 
    if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/entry_penerimaan_bank">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="h1/entry_penerimaan_bank/save" method="post" enctype="multipart/form-data" id="form_entri">              
              <div class="box-body">       
                <div class="form-group">                  
                  <input type="hidden" name="id_penerimaan_bank" id="id_penerimaan_bank">
                  <label for="inputEmail3" class="col-sm-2 control-label">Account</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="account">
                      <option value="">- choose -</option>
                      <?php 
                      $r = $this->m_admin->getAll("ms_rek_md");
                      foreach ($r->result() as $isi) {
                        echo "<option value='$isi->no_rekening'>$isi->no_rekening ($isi->bank)</option>";
                      }
                      ?>
                    </select>
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Entry</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_entry" placeholder="Tgl Entry" value="<?php echo date('Y-m-d') ?>" readonly class="form-control">                    
                  </div>                                                      
                </div>  
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Customer</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tipe_customer" id="tipe_customer" onchange="cek_tipe()">
                      <option value="">- choose -</option>
                      <option>Dealer</option>
                      <option>Vendor</option>
                      <option>Lain-lain</option>
                    </select>
                  </div>                                                      
                  <span id="lain_lain">
                    <label for="inputEmail3" class="col-sm-2 control-label">Diterima Dari</label>
                    <div class="col-sm-4">
                      <input type="text" id="dibayar_l" name="dibayar_l" placeholder="Diterima Dari" class="form-control">                    
                    </div>                                                      
                  </span>
                  <span id="dealer">
                    <label for="inputEmail3" class="col-sm-2 control-label">Diterima Dari</label>
                    <div class="col-sm-4">
                      <!-- <select class="form-control select2" name="dibayar_d" id="dibayar_d" onchange="cek_slot_d()"> -->
                      <select class="form-control select2" name="dibayar_d" id="dibayar_d" onchange="cek_slot_d()">
                        <option value="">- choose -</option>
                        <?php 
                        $r = $this->m_admin->getAll("ms_dealer");
                        foreach ($r->result() as $isi) {
                          echo "<option value='$isi->id_dealer'>$isi->nama_dealer ($isi->kode_dealer_md)</option>";
                        }
                        ?>
                      </select>
                    </div>                                                      
                  </span>
                  <span id="vendor">
                    <label for="inputEmail3" class="col-sm-2 control-label">Diterima Dari</label>
                    <div class="col-sm-4">
                      <!-- <select class="form-contro select2" name="dibayar_v" id="dibayar_v" onchange="cek_slot_v()"> -->
                      <select class="form-contro select2" name="dibayar_v" id="dibayar_v" >
                        <option value="">- choose -</option>
                        <?php 
                        $r = $this->m_admin->getAll("ms_vendor");
                        foreach ($r->result() as $isi) {
                          echo "<option value='$isi->id_vendor'>$isi->vendor_name</option>";
                        }
                        ?>
                      </select>
                    </div>                                                      
                  </span>
                </div>
                <!-- <div class="form-group">                                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">PPh</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pph">
                      <option value="">- choose -</option>
                      <?php 
                      $r = $this->m_admin->getAll("ms_pph");
                      foreach ($r->result() as $isi) {
                        echo "<option value='$isi->presentase'>$isi->presentase</option>";
                      }
                      ?>
                    </select>
                  </div>                                                      
                </div>  -->                               
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembayaran</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_bayar" id="jenis_bayar">
                      <option value="">- choose -</option>
                      <option>Unit</option>                      
                      <option>Ekspedisi Unit</option>                      
                    </select>
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Pembayaran</label>
                  <div class="col-sm-4">
                    <input type="text" name="total_pembayaran_real" id="total_pembayaran_real" placeholder="Total Pembayaran" class="form-control" readonly>
                    <input type="hidden" name="total_pembayaran" id="total_pembayaran">
                  </div>                                                      
                </div>
                <div class="form-group">                                
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Rekening Tujuan</label>
                  <div class="col-sm-4">
                    <input type="text" name="rekening_tujuan" id="rekening_tujuan" placeholder="Rekening Tujuan" class="form-control">
                  </div>                                                       -->
                  <label for="inputEmail3" class="col-sm-2 control-label">Via Bayar</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="via_bayar" id="via_bayar" onchange="cek_via()">
                      <option value="">- choose -</option>                      
                      <option>BG</option>
                      <option>Transfer</option>                      
                    </select>
                  </div>                                                                        
                </div>           
                <div class="form-group">                                
                  <span id="tampil_bg">
                    <hr>                    
                    <div class="form-group">
                      <label for="field-1" class="col-sm-2 control-label">No BG</label>                              
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="no_bg" name="no_bg">
                        <!-- <select class="form-control select2" id="no_bg" name="no_bg">
                          <option value="">- choose -</option>
                          <?php 
                          $dt_cek = $this->m_admin->getAll("ms_cek_giro");
                          foreach($dt_cek->result() as $val) {
                            echo "
                            <option value='$val->kode_giro'>$val->kode_giro</option>;
                            ";
                          }
                          ?>                      
                        </select> -->
                      </div>                    
                      <label for="field-1" class="col-sm-2 control-label">Tgl.Jatuh Tempo BG/Cek</label>            
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="tanggal4" autocomplete="off" placeholder="Tgl.Jatuh Tempo BG/Cek" name="tgl_bg">
                      </div>
                    </div>
                    <div class="form-group">                   
                      <label for="field-1" class="col-sm-2 control-label">Nominal BG/Cek</label>                              
                      <div class="col-sm-4">
                        <input type="text" id="nominal_bg" onkeypress="return number_only(event);" onkeyup="cek_format()" autocomplete="off" class="form-control" placeholder="Nominal BG/Cek" name="nominal_bg">
                      </div>                                          
                      <div class="col-sm-2">
                      </div>
                      <div class="col-sm-4">
                       <button type="button" onClick="simpan_bg()" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>
                       <button type="button" onClick="kirim_data_bg()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                       <button type="button" onClick="hide_bg()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                      </div> 
                    </div>
                    <div class="form-group">
                      <div class="col-sm-2">
                      </div>  
                      <div class="col-sm-10">
                        <div id="tampil_bg_isi"></div>
                      </div>
                    </div>
                  </span>

                  <span id="tampil_transfer">
                    <hr>                    
                    <div class="form-group">                                        
                      <label for="field-1" class="col-sm-2 control-label">Tgl.Transfer</label>            
                      <div class="col-sm-4">
                        <input type="text" autocomplete="off" class="form-control" id="tanggal5" placeholder="Tgl.Transfer" name="tgl_transfer">
                      </div>                    
                      <label for="field-1" class="col-sm-2 control-label">Nominal Transfer</label>                              
                      <div class="col-sm-4">
                        <input type="text" autocomplete="off" onkeypress="return number_only(event)" id="nominal_transfer" class="form-control" placeholder="Nominal Transfer" name="nominal_transfer">
                      </div>                                          
                    <div class="form-group">                                        
                    </div>  
                      <div class="col-sm-8">
                      </div>
                      <div class="col-sm-4">
                       <button type="button" onClick="simpan_transfer()" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>
                       <button type="button" onClick="kirim_data_transfer()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                       <button type="button" onClick="hide_transfer()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                      </div> 
                    </div>
                    <div class="form-group">
                      <div class="col-sm-2">
                      </div>  
                      <div class="col-sm-10">
                        <div id="tampil_transfer_isi"></div>
                      </div>
                    </div>
                  </span>                  
                </div>                                                      
              <div id="tampil_detail"></span>
              </div><!-- /.box-body -->
               <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="button" onclick="return submitForm()" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer -->              
                            
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<script>
    function submitForm()
  {
    var count_detail     = parseInt($('#count_detail').val());
    var total_pembayaran = parseInt($('#total_pembayaran').val());
    var total_bayar      = parseInt($('#total_bayar').val());
    if (count_detail>0) {
      if (total_pembayaran!=total_bayar) {
        alert('Nominal pembayaran tidak sama !');
        return false;
      }
      alert=confirm('Are you sure to save all data ?');
    }else{
      alert('isi data dengan lengkap !');
      return false;
    }
    if (alert==true) {
      $("#form_entri").submit();
      return true;
    }else{
      return false;
    }
  }
</script>
    <?php  
    }elseif($set=="edit"){
    ?>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/underscore.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/money.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
Vue.use(VueNumeric.default);
$(document).ready(function(){
  cek_tipe();
})
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/entry_penerimaan_bank">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="h1/entry_penerimaan_bank/save_edit" method="post" enctype="multipart/form-data" id="form_entri">              
              <div class="box-body">       
                <div class="form-group">                  
                  <input type="hidden" name="id_penerimaan_bank" value="<?= $row->id_penerimaan_bank ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">Account</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="account">
                      <option value="">- choose -</option>
                      <?php 
                      $r = $this->m_admin->getAll("ms_rek_md");
                      foreach ($r->result() as $isi) {
                        $select = $isi->no_rekening==$row->account?'selected':'';
                        echo "<option value='$isi->no_rekening' $select>$isi->no_rekening ($isi->bank)</option>";
                      }
                      ?>
                    </select>
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Entry</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_entry" placeholder="Tgl Entry" value="<?php echo date('Y-m-d') ?>" readonly class="form-control">                    
                  </div>                                                      
                </div>  
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Customer</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tipe_customer" id="tipe_customer" onchange="cek_tipe()" v-model="tipe_customer">
                      <option value="">- choose -</option>
                      <option>Dealer</option>
                      <option>Vendor</option>
                      <option>Lain-lain</option>
                    </select>
                  </div>                                                      
                  <span v-if="tipe_customer=='Lain-lain'">
                    <label for="inputEmail3" class="col-sm-2 control-label">Diterima Dari</label>
                    <div class="col-sm-4">
                      <input type="text" id="dibayar_l" name="dibayar_l" placeholder="Diterima Dari" class="form-control" value="<?= $row->dibayar ?>">                    
                    </div>                                                      
                  </span>
                  <span v-if="tipe_customer=='Dealer'">
                    <label for="inputEmail3" class="col-sm-2 control-label">Diterima Dari</label>
                    <div class="col-sm-4">
                      <!-- <select class="form-control select2" name="dibayar_d" id="dibayar_d" onchange="cek_slot_d()"> -->
                      <select class="form-control select2" name="dibayar_d" id="dibayar_d" onchange="cek_slot_d()">
                        <option value="">- choose -</option>
                        <?php 
                        $r = $this->m_admin->getAll("ms_dealer");
                        foreach ($r->result() as $isi) {
                          $select = $isi->id_dealer==$row->dibayar?'selected':'';
                          echo "<option value='$isi->id_dealer' $select>$isi->nama_dealer ($isi->kode_dealer_md)</option>";
                        }
                        ?>
                      </select>
                    </div>                                                      
                  </span>
                  <span v-if="tipe_customer=='Vendor'">
                    <label for="inputEmail3" class="col-sm-2 control-label">Diterima Dari</label>
                    <div class="col-sm-4">
                      <!-- <select class="form-contro select2" name="dibayar_v" id="dibayar_v" onchange="cek_slot_v()"> -->
                      <select class="form-control select2" name="dibayar_v" id="dibayar_v" onchange="cek_slot_v()">
                        <option value="">- choose -</option>
                        <?php 
                        $r = $this->m_admin->getAll("ms_vendor");
                        foreach ($r->result() as $isi) {
                          $select = $isi->id_vendor==$row->dibayar?'selected':'';
                          echo "<option value='$isi->id_vendor' $select>$isi->vendor_name</option>";
                        }
                        ?>
                      </select>
                    </div>                                                      
                  </span>
                </div>
                <!-- <div class="form-group">                                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">PPh</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="pph">
                      <option value="">- choose -</option>
                      <?php 
                      $r = $this->m_admin->getAll("ms_pph");
                      foreach ($r->result() as $isi) {
                        echo "<option value='$isi->presentase'>$isi->presentase</option>";
                      }
                      ?>
                    </select>
                  </div>                                                      
                </div>  -->                               
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembayaran</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="jenis_bayar" id="jenis_bayar">
                      <option <?= $row->jenis_bayar==''?'selected':'' ?>  value="">- choose -</option>
                      <option <?= $row->jenis_bayar=='Unit'?'selected':'' ?> >Unit</option>                      
                      <option <?= $row->jenis_bayar=='Ekspedisi Unit'?'selected':'' ?> >Ekspedisi Unit</option>                      
                    </select>
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Pembayaran</label>
                  <div class="col-sm-4">
                    <vue-numeric style="float: left;width: 80%;text-align: right;"
                          class="form-control text-rata-kanan isi_combo" v-model="totPembayaran" 
                          v-bind:minus="false" name="totPembayaran" readonly :empty-value="0"  separator="."/>
                  </div>                                                      
                </div>
                <div class="form-group">                                
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Rekening Tujuan</label>
                  <div class="col-sm-4">
                    <input type="text" name="rekening_tujuan" id="rekening_tujuan" placeholder="Rekening Tujuan" class="form-control">
                  </div>                                                       -->
                  <label for="inputEmail3" class="col-sm-2 control-label">Via Bayar</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="via_bayar" id="via_bayar" v-model="via_bayar">
                      <option value="">- choose -</option>                      
                      <option>BG</option>
                      <option>Transfer</option>                      
                    </select>
                  </div>                                                                        
                </div>           
                <div class="form-group">                                
                  <span v-if="via_bayar=='BG'">
                    <hr>                    
                    <div class="form-group">
                      <label for="field-1" class="col-sm-2 control-label">No BG</label>                              
                      <div class="col-sm-4">
                        <input type="text" class="form-control" v-model="bg.no_bg">
                        <!-- <select class="form-control select2" v-model="bg.no_bg">
                          <option value="">- choose -</option>
                          <?php 
                          $dt_cek = $this->m_admin->getAll("ms_cek_giro");
                          foreach($dt_cek->result() as $val) {
                            echo "
                            <option value='$val->kode_giro'>$val->kode_giro</option>;
                            ";
                          }
                          ?>                      
                        </select> -->
                      </div>                    
                      <label for="field-1" class="col-sm-2 control-label">Tgl.Jatuh Tempo BG/Cek</label>            
                      <div class="col-sm-4">
                        <date-picker v-model="bg.tgl_bg" placeholder="Tgl. BG"></date-picker>
                      </div>
                    </div>
                    <div class="form-group">                   
                      <label for="field-1" class="col-sm-2 control-label">Nominal BG/Cek</label>                              
                      <div class="col-sm-4">
                        <vue-numeric style="float: left;width: 80%;text-align: right;"
                          class="form-control text-rata-kanan isi_combo" v-model="bg.nominal_bg" placeholder="Nominal BG/Cek"
                          v-bind:minus="false" :empty-value="0" separator="."/>
                      </div>                                          
                      <div class="col-sm-2">
                      </div>
                      <div class="col-sm-4">
                       <button @click.prevent="form_entri.addBG(bg)" type="button" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button> 
                       <button type="button" @click.prevent="form_entri.showIsiBG(1)" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                       <button type="button" @click.prevent="form_entri.showIsiBG(0)" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                      </div> 
                    </div>
                    <div class="form-group">
                      <div class="col-sm-2">
                      </div>  
                      <div class="col-sm-10">
                        <div v-if="show_bg==1">
                          <table class="table table-bordered">
                            <thead>                                               
                              <th width="5%">No </th>            
                              <th>No BG </th>
                              <th>Tgl.Jatuh Tempo </th>     
                              <th>Nominal </th>   
                              <th width="7%">Aksi </th>   
                            </thead>
                            <tbody> 
                             <tr v-for="(bg, index) of bg_">
                                <td>{{index+1}}</td>
                                <td><input type="text" class="form-control isi_combo" v-model="bg.no_bg" name="no_bg[]" readonly></td>
                                <td>
                                  <date-picker v-model="bg.tgl_bg" name="tgl_bg[]"></date-picker>
                                </td>
                                <td>
                                  <vue-numeric style="float: left;text-align: right;"
                          class="form-control text-rata-kanan isi_combo" v-model="bg.nominal_bg"
                          v-bind:minus="false" name="nominal_bg[]" :empty-value="0" separator="."/>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <button @click.prevent="form_entri.delBG(index)" type="button" class="btn btn-danger btn-sm fa fa-trash-o"></button>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="3" align="right" style="vertical-align:middle;"><b>Total</b></td>
                                <td><vue-numeric style="text-align: right;font-weight: bold;"
                          class="form-control text-rata-kanan isi_combo" readonly v-model="totBG"
                          v-bind:minus="false" :empty-value="0" separator="."/></td>
                              </tr>
                          </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </span>
                  <div v-if="via_bayar=='Transfer'">
                    <hr>                    
                    <div class="form-group">                                        
                      <label for="field-1" class="col-sm-2 control-label">Tgl.Transfer</label>            
                      <div class="col-sm-4">
                         <date-picker v-model="transfer.tgl_transfer" placeholder="Tgl.Transfer"></date-picker>
                      </div>                    
                      <label for="field-1" class="col-sm-2 control-label">Nominal Transfer</label>
                      <div class="col-sm-4">
                        <vue-numeric style="float: left;width: 80%;text-align: right;"
                          class="form-control text-rata-kanan isi_combo" v-model="transfer.nominal_transfer" 
                          v-bind:minus="false" :empty-value="0" placeholder="Nominal Transfer" separator="."/>
                      </div>                                          
                    <div class="form-group">                                        
                    </div>  
                      <div class="col-sm-8">
                      </div>
                      <div class="col-sm-4">
                       <button @click.prevent="form_entri.addTransfer(transfer)" type="button" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button> 
                       <button type="button" @click.prevent="form_entri.showIsiTransfer(1)" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                       <button type="button" @click.prevent="form_entri.showIsiTransfer(0)" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                      </div> 
                    </div>
                    <div class="form-group" v-if="show_transfer==1">
                        <div class="col-md-offset-4 col-md-8">
                          <table class="table table-bordered responsive-utilities jambo_table bulk_action">
                          <thead>
                              <tr class="headings">                                                
                                  <th width="5%">No </th>                        
                                  <th>Tgl.Transfer</th>      
                                  <th>Nominal </th>                
                                  <th width="7%">Aksi </th>                                          
                              </tr>
                          </thead>
                          <tbody> 
                             <tr v-for="(trf, index) of transfers">
                                <td>{{index+1}}</td>
                                <td>
                                  <date-picker v-model="trf.tgl_transfer" name="tgl_transfer[]"></date-picker>
                                </td>
                                <td>
                                  <vue-numeric style="float: left;text-align: right;"
                          class="form-control text-rata-kanan isi_combo" v-model="trf.nominal_transfer"
                          v-bind:minus="false" name="nominal_transfer[]" :empty-value="0" separator="."/>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <button @click.prevent="form_entri.delTransfer(index)" type="button" class="btn btn-danger btn-sm fa fa-trash-o"></button>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="2" align="right" style="vertical-align:middle;"><b>Total</b></td>
                                <td><vue-numeric style="text-align: right;font-weight: bold;"
                          class="form-control text-rata-kanan isi_combo" readonly v-model="totTransfers"
                          v-bind:minus="false" :empty-value="0" separator="."/></td>
                              </tr>
                          </tbody>
                        </table>
                        </div>
                    </div>
                  </div>
                </div>                                                      
              <div>
                <table id="example2" class="table table-hover table-bordered myTable1" width="100%">
                  <tr>
                    <th width="15%">No Account</th>
                    <th width="20%">Jenis Transaksi</th>                    
                    <th width="20%">Referensi</th>
                    <th width="10%">Nominal</th>
                    <th width="10%">Sisa Hutang</th>
                    <th width="15%">Keterangan</th>
                    <th width="7%">Aksi</th>
                  </tr>
                  <tr v-for="(detail, index) of details">
                    <td><input type="text" class="form-control isi_combo" name="kode_coa[]" v-model="detail.kode_coa" readonly></td>               
                    <td><input type="text" class="form-control isi_combo" name="coa[]" v-model="detail.coa" readonly></td>               
                    <td><input type="text" class="form-control isi_combo" name="referensi[]" v-model="detail.referensi" readonly></td>               
                     <td><vue-numeric style="float: left;width: 80%;text-align: right;"
                          class="form-control text-rata-kanan isi_combo" v-model="detail.nominal"
                          v-bind:minus="false" name="nominal[]" :empty-value="0" separator="."/>
                    </td>
                     <td>
                      <vue-numeric style="float: left;width: 80%;text-align: right;"
                          class="form-control text-rata-kanan isi_combo" v-model="detail.sisa_hutang"
                          v-bind:minus="false" name="sisa_hutang[]" :empty-value="0" separator="." readonly/>
                    </td>
                    <td><input type="text" class="form-control isi_combo" name="keterangan[]" v-model="detail.keterangan"></td>             
                    <td align="center" style="vertical-align: middle;">
                        <button @click.prevent="form_entri.delDetail(index)" type="button" class="btn btn-danger btn-sm fa fa-trash-o"></button>
                    </td>                                
                  </tr>
                    <tr>
                      <td>
                        <input id="kode_coa" readonly type="text" onclick="showModalCOA()" v-model="detail.kode_coa" class="form-control isi" placeholder="Kode COA">
                      </td>
                      <td>
                        <input id="coa" readonly type="text" onclick="showModalCOA()" class="form-control isi" placeholder="COA" v-model="detail.coa">
                      </td>                          
                      <td>
                        <input id="referensi" readonly type="text" onclick="showModalRef()" class="form-control isi" placeholder="Referensi" v-model="detail.referensi">
                      </td>
                      <td>
                        <vue-numeric id="nominal" style="float: left;width: 80%;text-align: right;"
                          class="form-control text-rata-kanan isi_combo" v-model="detail.nominal"
                          v-bind:minus="false" :empty-value="0" separator="."/>
                      </td>
                      <td>
                        <vue-numeric id="sisa_hutang" style="float: left;width: 80%;text-align: right;"
                          class="form-control text-rata-kanan isi_combo" v-model="detail.sisa_hutang"
                          v-bind:minus="false" :empty-value="0" separator="." readonly />
                      </td>
                      <td>
                        <input type="text" autocomplete="off" class="form-control isi_combo" id="keterangan" placeholder="Keterangan" v-model="detail.keterangan">
                      </td>
                      <td align="center">
                        <button @click.prevent="form_entri.addDetail(detail)" type="button" class="btn btn-primary btn-sm fa fa-plus"></button>                         
                      </td>                        
                    </tr>

                </table>
              </div><!-- /.box-body -->
               <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="button" onclick="form_entri.submit()" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer -->              
                            
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>

<script>

var form_entri = new Vue({
      el: '#form_entri',
      data: {
        show_transfer:1,
        show_bg:1,
        tipe_customer:'<?= $row->tipe_customer ?>',
        detail:{
          kode_coa:'',
          coa:'',
          referensi:'',
          nominal:'',
          sisa_hutang:'',
          keterangan:''
        },
        transfer:{
          tgl_transfer:'',
          nominal_transfer:''
        },
        bg:{
          no_bg:'',
          tgl_bg:'',
          nominal_bg:''
        },
        via_bayar:'<?= $row->via_bayar ?>',
        details: <?= isset($details)?json_encode($details):'[]' ?>,
        bg_: <?= $bg_->num_rows()>0?json_encode($bg_->result()):'[]' ?>,
        transfers: <?= $transfers->num_rows()>0?json_encode($transfers->result()):'[]' ?>
      },
      methods: {
        showIsiTransfer: function(cond){
            this.show_transfer=cond;
        },
        showIsiBG: function(cond){
            this.show_bg=cond;
        },
        // getItem : function() {
        //   $('#tabel_item').DataTable().ajax.reload();
        //   $('#modalItem').modal('show');
        // },
        clearDetail: function(){
          this.detail={
            kode_coa:'',
          coa:'',
          referensi:'',
          nominal:'',
          sisa_hutang:'',
          keterangan:''
          }
        },
        clearTransfer: function(){
          this.transfer={
            tgl_transfer:'',
            nominal_transfer:'',
          }
        },
        clearBG: function(){
          this.bg={
            no_bg:'',
            tgl_bg:'',
            nominal_transfer:'',
          }
        },
        addDetail : function(detail){
          // console.log(detail)
          if (form_entri.details.length > 0) {
            for (detail of form_entri.details) {
                if (detail.referensi === this.detail.referensi) {
                    alert("Referensi Sudah Dipilih !");
                    this.clearDetail();
                    return;
                }
            }
          }

          if (this.detail.kode_coa === '' || this.detail.nominal === ''|| this.detail.sisa_hutang === ''|| this.detail.keterangan === '') {
            alert('Silahkan isi data dengan lengkap !');
            return false;
          }

          // if (parseInt(this.detail.qty_retur) == 0 || this.detail.qty_retur=='') {
          //   alert('Qty Retur Tidak boleh lebih kecil dari 1');
          //   return;
          // }
          this.details.push(this.detail);
          this.clearDetail();
            console.log(this.details);
        },
        addTransfer : function(transfer){
          // if (form_entri.details.length > 0) {
          //   for (detail of form_entri.details) {
          //       if (detail.kode_item === this.detail.kode_item) {
          //           alert("Barang/Item ini sudah dipilih !");
          //           this.detail = {
          //               kode_item: "",
          //               nama_item: "",
          //               qty_po: null,
          //               qty_retur:null,
          //           };
          //           return;
          //       }
          //   }
          // }

          // if (this.detail.no_po === '' || this.detail.tgl_po === ''|| this.detail.no_kwitansi === ''|| this.detail.tgl_kwitansi === ''|| this.detail.no_bast === ''|| this.detail.tgl_bast === ''|| this.detail.due_datetime === ''|| this.detail.harga === '') {
          //   alert('Silahkan isi data dengan lengkap !');
          //   return;
          // }

          // if (parseInt(this.detail.qty_retur) == 0 || this.detail.qty_retur=='') {
          //   alert('Qty Retur Tidak boleh lebih kecil dari 1');
          //   return;
          // }
          this.transfers.push(this.transfer);
          this.clearTransfer();
        },
        addBG : function(transfer){
          this.bg_.push(this.bg);
          this.clearBG();
        },

        delDetail: function(index){
            this.details.splice(index, 1);
        },
        delTransfer: function(index){
            this.transfers.splice(index, 1);
        },
        delBG: function(index){
            this.bg_.splice(index, 1);
        },
        submit: function(){
          if (this.via_bayar=='BG') {
            pembayaran=this.totBG;
          }
          if (this.via_bayar=='Transfer') {
            pembayaran=this.totTransfers;
          }

          count_detail = this.details.length;
          if (count_detail>0) {
            if (pembayaran!=this.totPembayaran) {
              alert('Nominal pembayaran tidak sama !');
              return false;
            }
            alrt=confirm('Are you sure to save all data ?');
          }else{
            alert('isi data dengan lengkap !');
            return false;
          }
          if (alrt==true) {
            $('#submitBtn').attr('disabled',true);
            $("#form_entri").submit();
            // console.log('e');
            return true;
          }else{
            return false;
          }
        }
        // cekQty: function(index){
        //     if (parseInt(this.details[index].qty_retur)>parseInt(this.details[index].qty_po)) {
        //       this.details[index].qty_retur=1;
        //       alert('Qty retur tidak boleh melebihi Qty PO !');
        //       // return;

        //     }
        //     if (parseInt(this.details[index].qty_retur)==0) {
        //       this.details[index].qty_retur=1;
        //       alert('Qty Retur Tidak boleh lebih kecil dari 1');
        //       // return;

        //     }
        //   // if (this.details.qty_retur) {}
        //     // alert(this.details);
        //     // objIndex = this.details.findIndex((obj => obj.id == index));
        //     // console.log("Before update: ", this.details[index].qty_retur);
        //     // console.log(this.details.index.kode_item);
        // }
      },

      computed: {
        totTransfers: function(){
          total=0;
          for(trf of this.transfers)
          {
            total+=trf.nominal_transfer;
          }
          if (isNaN(total)) return 0;
          // return total.toFixed(1);
          return total;
        },
        totBG: function(){
          total=0;
          for(bg of this.bg_)
          {
            total+=bg.nominal_bg;
          }
          if (isNaN(total)) return 0;
          // return total.toFixed(1);
          return total;
        },
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
    }elseif($set=="detail"){
      $row = $dt_penerimaan->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/entry_penerimaan_bank">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
            <form class="form-horizontal" action="h1/entry_penerimaan_bank/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <input type="hidden" name="id_penerimaan_bank" id="id_penerimaan_bank">
                  <label for="inputEmail3" class="col-sm-2 control-label">Account</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" name="" value="<?php echo $row->account ?>">
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Entry</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_entry" placeholder="Tgl Entry" value="<?php echo $row->tgl_entry ?>" readonly class="form-control">                    
                  </div>                                                      
                </div>  
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Customer</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" name="" value="<?php echo $row->tipe_customer ?>">
                  </div>                                                                        
                  <label for="inputEmail3" class="col-sm-2 control-label">Diterima Dari</label>
                  <div class="col-sm-4">
                    <?php 
                    if($row->tipe_customer == 'Dealer'){
                      $dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$row->dibayar)->row();
                      $dari = $dealer->nama_dealer." (".$dealer->kode_dealer_md.")";
                    }elseif($row->tipe_customer == 'Vendor'){
                      $vendor = $this->m_admin->getByID("ms_vendor","id_vendor",$row->dibayar)->row();
                      $dari = $vendor->vendor_name;
                    }elseif($row->tipe_customer == 'Lain-lain'){
                      $dari = $row->dibayar;
                    }
                    ?>
                    <input readonly type="text" class="form-control" name="" value="<?php echo $dari ?>">
                  </div>                                                                        
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembayaran</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" name="" value="<?php echo $row->jenis_bayar ?>">
                  </div>                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Total Pembayaran</label>
                  <div class="col-sm-4">
                    <?php 
                    $id_penerimaan_bank = $_GET['id'];
                    $get = $this->db->query("SELECT sum(nominal) as jum FROM tr_penerimaan_bank_detail WHERE id_penerimaan_bank = '$id_penerimaan_bank'")->row();                    
                    ?>
                    <input type="text" name="total_pembayaran_real" placeholder="Total Pembayaran" value="Rp. <?php echo mata_uang2($get->jum) ?>" class="form-control" readonly>
                    <input type="hidden" name="total_pembayaran" id="total_pembayaran" value="<?php echo $get->jum ?>">
                  </div>                                                      
                </div>
                <div class="form-group">                                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Via Bayar</label>
                  <div class="col-sm-4">
                    <input readonly type="text" class="form-control" name="" value="<?php echo $row->via_bayar ?>">
                  </div>                                                                        
                </div>           
                <div class="form-group">                                                  
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  <div class="col-sm-10">
                  <?php if($row->via_bayar == 'Transfer'){ ?>
                    <table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
                      <thead>
                          <tr class="headings">                                                
                              <th width="5%">No </th>                        
                              <th>Tgl.Transfer</th>                                                                                                                                
                              <th>Nominal </th>                                                                                
                          </tr>
                      </thead>
                      <tbody> 
                      <?php 
                        $no=1; 
                        $dt_transfer = $this->m_admin->getByID("tr_penerimaan_bank_transfer","id_penerimaan_bank",$row->id_penerimaan_bank);
                        foreach($dt_transfer->result() as $row) {                             
                        echo "          
                          <tr>
                            <td>$no</td>          
                            <td>$row->tgl_transfer</td>
                            <td>".mata_uang2($row->nominal_transfer)."</td>                                        
                          </tr>";                        
                          $no++;
                          }
                      ?>   
                      </tbody>
                    </table>
                  <?php }else{ ?>

                    <table class="table table-bordered responsive-utilities jambo_table bulk_action" id="jasa">
                        <thead>
                            <tr class="headings">                                                
                                <th width="5%">No </th>            
                                <th>No BG </th>
                                <th>Tgl.Jatuh Tempo </th>                                                                                                                                
                                <th>Nominal </th>                                                                                                                
                            </tr>
                        </thead>
                        <tbody> 
                        <?php 
                          $no=1; 
                          $dt_bg = $this->m_admin->getByID("tr_penerimaan_bank_bg","id_penerimaan_bank",$row->id_penerimaan_bank);
                          foreach($dt_bg->result() as $row) {                                 
                          echo "          
                            <tr>
                              <td>$no</td>
                              <td>$row->no_bg</td>
                              <td>$row->tgl_bg</td>
                              <td>$row->nominal_bg</td>                                              
                            </tr>";                            
                            $no++;
                            }
                        ?>   
                        </tbody>
                    </table>

                    <?php } ?>
                    </div>
                  </div>
                </div>                                                      
                <table id="example2" class="table table-hover table-bordered myTable1" width="100%">
                  <tr>
                    <th width="15%">No Account</th>
                    <th width="20%">Jenis Transaksi</th>                    
                    <th width="20%">Referensi</th>
                    <th width="10%">Nominal</th>
                    <th width="10%">Sisa Hutang</th>
                    <th width="15%">Keterangan</th>
                  </tr>

                <?php   
                $dt_detail = $this->m_admin->getByID("tr_penerimaan_bank_detail","id_penerimaan_bank",$row->id_penerimaan_bank);
                foreach($dt_detail->result() as $row) {           
                  echo "   
                  <tr>                    
                    <td width='15%'>$row->kode_coa</td>
                    <td width='20%'>$row->coa</td>      
                    <td width='20%'>$row->referensi</td>      
                    <td align='right' width='10%'>".mata_uang2($row->nominal)."</td>      
                    <td align='right' width='10%'>".mata_uang2($row->sisa_hutang)."</td>      
                    <td width='15%'>$row->keterangan</td>            
                  </tr>";
                  }
                ?>
                </table>

              </div><!-- /.box-body -->                                          
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
          <a href="h1/entry_penerimaan_bank/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <th>Total Terima</th>              
              <th>Customer</th>              
              <th>Status</th>
              <th width="5%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <!-- <a href='h1/entry_penerimaan_bank/view?id='>$row->id_penerimaan_bank</a> -->
          <?php 
          $no=1; 
          foreach($dt_penerimaan->result() as $row) {                                         
            if($row->status=='input'){
            	$tom = "<a onclick=\"return confirm('Are you sure to approve this data?')\" class=\"btn btn-flat btn-xs btn-primary\" href=\"h1/entry_penerimaan_bank/approve?id=$row->id_penerimaan_bank\">Approve</a>
                <a class=\"btn btn-flat btn-xs btn-danger\" href=\"h1/entry_penerimaan_bank/edit?id=$row->id_penerimaan_bank\">Edit</a>";
              $status = "<span class='label label-warning'>$row->status</span>";
            }else{
            	$tom = "<a href='h1/entry_penerimaan_bank/cetak_kwitansi?id=$row->id_penerimaan_bank' class='btn btn-flat btn-xs btn-success'>Cetak Kwitansi</a>";
              $status = "<span class='label label-success'>$row->status</span>";
            }      
            $dt = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail WHERE id_penerimaan_bank = '$row->id_penerimaan_bank'")->row();                  
            if($row->tipe_customer == 'Dealer'){
              $isi_dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$row->dibayar);
              $isi = ($isi_dealer->num_rows() > 0) ? $isi_dealer->row()->nama_dealer : "" ;
            }elseif($row->tipe_customer == 'Vendor'){
              $isi_vendor = $this->m_admin->getByID("ms_vendor","id_vendor",$row->dibayar);
              $isi = ($isi_vendor->num_rows() > 0) ? $isi_vendor->row()->vendor_name : "" ;
            }else{
              $isi = "";
            }

            

          echo "          
            <tr>               
              <td>$no</td>             
              <td>
                <a href='h1/entry_penerimaan_bank/detail?id=$row->id_penerimaan_bank'>
                  $row->id_penerimaan_bank
                </a>
              </td>                            
              <td>$row->tgl_entry</td>                                          
              <td>".mata_uang2($dt->jum)."</td>                                          
              <td>$isi</td>                                                        
              <td>$status</td>                                          
              <td>$tom</td>                                          
              ";                                      
          $no++;

          // $get = $this->m_admin->getByID("tr_penerimaan_bank_detail","id_penerimaan_bank",$row->id_penerimaan_bank);
          // foreach ($get->result() as $isi) {              
            
          //   $cek1 = $this->db->query("SELECT SUM(nominal) AS jum FROM tr_penerimaan_bank_detail INNER JOIN tr_penerimaan_bank 
          //     ON tr_penerimaan_bank.id_penerimaan_bank = tr_penerimaan_bank_detail.id_penerimaan_bank 
          //     WHERE tr_penerimaan_bank_detail.referensi = '$isi->referensi' AND tr_penerimaan_bank.status = 'approved'")->row()->jum;
          //   $cek2 = $this->db->query("SELECT total_bayar FROM tr_invoice_dealer WHERE no_faktur = '$isi->referensi'");                        
          
          //   if($cek2->num_rows() > 0){
          //     $is = $cek2->row();
          //       if($cek1 == $is->total_bayar){
          //        $da['status_bayar'] = 'lunas';
          //     //   //$this->simpan_rekap($id,$cek1);                   
          //       }else{
          //        $da['status_bayar'] = "";
          //       }
          //       $this->m_admin->update("tr_invoice_dealer",$da,"no_faktur",$isi->referensi);
          //   }
          // }
          
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
<div class="modal fade modal_coa">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">COA</h4>
      </div>
      <div class="modal-body" id="show_detail">
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade modal_ref">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <span aria-hidden="true">&times;</span></button>
         <h4 class="modal-title">Referensi</h4>
      </div>
      <div class="modal-body" id="show_detail_ref">
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

function showModalCOA(){
  $.ajax({
       url:"<?php echo site_url('master/coa/coa_popup');?>",
       type:"POST",
       // data:"id_checker="+id_checker,
       cache:false,
       success:function(html){
          $("#show_detail").html(html);
          $('.modal_coa').modal('show');
          datatables();
       }
  });
}
function showModalRef(){
  var tipe_customer = $('#tipe_customer').val();
  var id_dealer     = $('#dibayar_d').val();
  var id_vendor     = $('#dibayar_v').val();
  var dibayar_l     = $('#dibayar_l').val();

  if (tipe_customer=='') {
    alert('Silahkan Pilih Tipe Customer');
    return false;
  }
  if (tipe_customer=='Dealer') {
    if (id_dealer=='') {
      alert('Silahkan Pilih Dealer');
      return false;
    }
  }
  if (tipe_customer=='Vendor') {
    if (id_vendor=='') {
      alert('Silahkan Pilih Vendor');
      return false;
    }
  }
  if (tipe_customer=='Lain-lain') {
    if (dibayar_l=='') {
      alert('Silahkan Isi Kolom Diterima Dari');
      return false;
    }
  }
  value = {tipe_customer : tipe_customer,
           id_dealer : id_dealer,
           id_vendor : id_vendor,
           dibayar_l : dibayar_l,
          }
  $.ajax({
       url:"<?php echo site_url('h1/entry_penerimaan_bank/modal_ref');?>",
       type:"POST",
       data:value,
       cache:false,
       success:function(html){
          $("#show_detail_ref").html(html);
          $('.modal_ref').modal('show');
          datatables2();
       }
  });
}
function sembunyi(){
  $("#lain_lain").hide();
  $("#vendor").hide();
  $("#dealer").hide();  
  auto();
  cek_via();
}
function cek_via(){
  var via_bayar = $("#via_bayar").val();
  if(via_bayar=='BG'){
    $("#tampil_bg").show();
    $("#tampil_transfer").hide();
  }else if(via_bayar=="Transfer"){
    $("#tampil_bg").hide();
    $("#tampil_transfer").show();
  }else{    
    $("#tampil_bg").hide();
    $("#tampil_transfer").hide();
  }
}
function cek_tipe(){
  var tipe = $("#tipe_customer").val();
  if(tipe=='Dealer'){
    $("#dealer").show();
    $("#lain_lain").hide();
    $("#vendor").hide();
  }else if(tipe=="Vendor"){
    $("#vendor").show();
    $("#dealer").hide();
    $("#lain_lain").hide();
  }else if(tipe=="Lain-lain"){
    $("#lain_lain").show();
    $("#vendor").hide();
    $("#dealer").hide();
  }else{
    $("#lain_lain").hide();
    $("#vendor").hide();
    $("#dealer").hide();
  }
}  
function auto(){    
  var id = 1;        
  $.ajax({
    url : "<?php echo site_url('h1/entry_penerimaan_bank/cari_id')?>",
    type:"POST",
    data:"id="+id,
    cache:false,
    success:function(msg){            
      data=msg.split("|");
      respon=data[0];
      respon = respon.replace(/\n|\r/g, "");
      $("#id_penerimaan_bank").val(respon);
      // $("#id_penerimaan_bank").val(msg);
      sum();        
      kirim_detail();
    }
  })        
}
function sum(){      
  var id_penerimaan_bank = $("#id_penerimaan_bank").val();            
  $.ajax({
    url : "<?php echo site_url('h1/entry_penerimaan_bank/cari_total')?>",
    type:"POST",
    data:"id_penerimaan_bank="+id_penerimaan_bank,
    cache:false,
    success:function(msg){            
      data=msg.split("|");
      $("#total_pembayaran").val(data[0]);
      $("#total_pembayaran_real").val(convertToRupiah(data[0]));
    }
  })        
}
function cek_coa(){      
  var kode_coa = $("#kode_coa").val();            
  $.ajax({
    url : "<?php echo site_url('h1/entry_penerimaan_bank/cari_coa')?>",
    type:"POST",
    data:"kode_coa="+kode_coa,
    cache:false,
    success:function(msg){            
      data=msg.split("|");
      $("#coa").val(data[0]);      
    }
  })        
}
function cek_hutang(){      
  var nominal           = $("#nominal").val();            
  var total_pembayaran  = $("#sisa_hutang_real").val();              
  var hasil             = total_pembayaran - nominal;
  if(hasil < 0){
    alert("Sisa hutang tidak boleh minus");
    $("#sisa_hutang").focus();
  }else if(nominal == 0){
    alert("Nominal tidak boleh kosong");
    $("#nominal").focus();
  }else{
    $("#sisa_hutang").val(hasil);                
  }
}
function cek_ref(){      
  var referensi = $("#referensi").val();            
  $.ajax({
    url : "<?php echo site_url('h1/entry_penerimaan_bank/cari_ref')?>",
    type:"POST",
    data:"referensi="+referensi,
    cache:false,
    success:function(msg){            
      data=msg.split("|");
      
      $("#nominal").val(convertNoRupiah(data[0]));      
      //$("#sisa_hutang").val(data[0]);      
      $("#sisa_hutang_real").val(data[0]);      
      $("#sisa_hutang").val(convertNoRupiah(data[0]));
      //cek_hutang();
    }
  })        
}
function cek_nominal(){
  var nominal = $("#nominal").val();
  var sisa_hutang = $("#sisa_hutang").val();
  var sisa_hutang_real = $("#sisa_hutang_real").val();
  if(parseInt(nominal) > parseInt(sisa_hutang_real)){
    alert('Nominal tidak boleh melebihi sisa hutang');
    $('#nominal').val() = $('#sisa_hutang_real').val();
    $('#sisa_hutang').val() = $('#sisa_hutang_real').val();
  }else{
    var total = parseInt(sisa_hutang_real) - parseInt(nominal);
    $('#sisa_hutang').val(total);
  }
}
function hide_bg(){
    $("#tampil_bg_isi").hide();
}
function kirim_data_bg(){    
  $("#tampil_bg_isi").show();
  var id_penerimaan_bank = document.getElementById("id_penerimaan_bank").value;   
  //var id_penerimaan_bank = 1;
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_penerimaan_bank="+id_penerimaan_bank;                           
     xhr.open("POST", "h1/entry_penerimaan_bank/t_bg", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_bg_isi").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function simpan_bg(){
    var id_penerimaan_bank = $("#id_penerimaan_bank").val();            
    var no_bg           = $("#no_bg").val();            
    var tgl_bg          = $("#tanggal4").val();            
    var nominal_bg      = $("#nominal_bg").val();            
    //alert(id_dealer);
    if (no_bg == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('h1/entry_penerimaan_bank/save_bg')?>",
            type:"POST",
            data:"id_penerimaan_bank="+id_penerimaan_bank+"&no_bg="+no_bg+"&tgl_bg="+tgl_bg+"&nominal_bg="+nominal_bg,
            cache:false,
            success:function(msg){  
                data=msg.split("|");
                respon=data[0];
                respon = respon.replace(/\n|\r/g, "");
                //if(respon=="nihil"){
                if(respon!="failed"){
                    kirim_data_bg();
                    sum();
                    kosong();                
                }else{
                    alert('No BG ini sudah ditambahkan');
                    kosong();                      
                }                
            }
        })    
    }
}
function kosong(args){
  $("#no_bg").val("");
  $("#tanggal4").val("");   
  $("#nominal_bg").val("");   
}
function hapus_bg(a){ 
    var id  = a;       
    $.ajax({
        url : "<?php echo site_url('h1/entry_penerimaan_bank/delete_bg')?>",
        type:"POST",
        data:"id="+id,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            // if(data[0]=="nihil"){
              kirim_data_bg();
              sum();
            // }
        }
    })
}



function hide_transfer(){
    $("#tampil_transfer_isi").hide();
}
function kirim_data_transfer(){    
  $("#tampil_transfer_isi").show();
  var id_penerimaan_bank = document.getElementById("id_penerimaan_bank").value;   
  //var id_penerimaan_bank = 1;
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_penerimaan_bank="+id_penerimaan_bank;                           
     xhr.open("POST", "h1/entry_penerimaan_bank/t_transfer", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                getSelect2();
                document.getElementById("tampil_transfer_isi").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}

function simpan_transfer(){
    var id_penerimaan_bank      = $("#id_penerimaan_bank").val();                
    var tgl_transfer          = $("#tanggal5").val();            
    var nominal_transfer      = $("#nominal_transfer").val();            
    //alert(id_dealer);
    if (tgl_transfer == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('h1/entry_penerimaan_bank/save_transfer')?>",
            type:"POST",
            data:"id_penerimaan_bank="+id_penerimaan_bank+"&tgl_transfer="+tgl_transfer+"&nominal_transfer="+nominal_transfer,
            cache:false,
            success:function(msg){            
                // data=msg.split("|");
                // if(data[0]=="nihil"){
                    kirim_data_transfer();
                    sum();
                    kosong2();                
                // }                
            }
        })    
    }
}
function kosong2(args){  
  $("#tanggal5").val("");   
  $("#nominal_transfer").val("");   
}
function hapus_transfer(a){ 
    var id  = a;       
    $.ajax({
        url : "<?php echo site_url('h1/entry_penerimaan_bank/delete_transfer')?>",
        type:"POST",
        data:"id="+id,
        cache:false,
        success:function(msg){            
            // data=msg.split("|");
            // if(data[0]=="nihil"){
              kirim_data_transfer();
              sum();
            // }
        }
    })
}
function kirim_detail(){    
  $("#tampil_detail").show();
  var id = 1;
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id="+id;                           
     xhr.open("POST", "h1/entry_penerimaan_bank/t_detail", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_detail").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function simpan_detail(){
                    // kirim_detail();

    var id_penerimaan_bank  = $("#id_penerimaan_bank").val();            
    var kode_coa            = $("#kode_coa").val();            
    var coa                 = $("#coa").val();            
    var referensi           = $("#referensi").val();            
    var sisa_hutang         = $("#sisa_hutang").val();            
    var nominal             = $("#nominal").val();            
    var keterangan          = $("#keterangan").val();            
    //alert(id_dealer);
    if (kode_coa == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('h1/entry_penerimaan_bank/save_detail')?>",
            type:"POST",
            data:"id_penerimaan_bank="+id_penerimaan_bank+"&kode_coa="+kode_coa+"&coa="+coa+"&referensi="+referensi+"&nominal="+nominal+"&sisa_hutang="+sisa_hutang+"&keterangan="+keterangan,
            cache:false,
            success:function(msg){  
              data=msg.split("|");
              respon=data[0];
              respon = respon.replace(/\n|\r/g, "");

               //if(respon=="nihil"){
              if(respon!="failed"){
                  // alert('dwed');
                    kirim_detail();
                    kosong3(); 
                    sum();               
                    cek_slot_d();
              }else{
                alert("Data duplikat, silahkan ulangi!");
                kosong3();
                sum();               
                cek_slot_d();
              }                
            }
        })    
    }
}
function kosong3(args){
  $("#kode_coa").val("");
  $("#coa").val("");   
  $("#referensi").val("");   
  $("#nominal").val("");   
  $("#sisa_hutang").val("");   
  $("#keterangan").val("");     
}
function hapus_detail(a){ 
    var id  = a;       
    $.ajax({
        url : "<?php echo site_url('h1/entry_penerimaan_bank/delete_detail')?>",
        type:"POST",
        data:"id="+id,
        cache:false,
        success:function(msg){            
            // data=msg.split("|");
            // if(data[0]=="nihil"){
              kirim_detail();
              sum();
            // }
        }
    })
}
function getSelect2() {
  $(".select3").select2({
    allowClear:false
  });
}
function cek_slot_d(){
  var id_dealer  = $("#dibayar_d").val(); 
  $.ajax({
    url : "<?php echo site_url('h1/entry_penerimaan_bank/get_slot')?>",
    type:"POST",
    data:"id_dealer="+id_dealer,
    cache:false,   
    success:function(msg){            
      $("#referensi").html(msg);            
    }
  })  
}
function cek_format(){
  var tanpa_rupiah = document.getElementById('nominal_bg');
  tanpa_rupiah.addEventListener('keyup', function(e)
  {
    tanpa_rupiah.value = formatRupiah(this.value);
  });

  tanpa_rupiah.addEventListener('keydown', function(event)
  {
    limitCharacter(event);
  });
}

function cek_format2(){
  var tanpa_rupiah = document.getElementById('nominal');
  tanpa_rupiah.addEventListener('keyup', function(e)
  {
    tanpa_rupiah.value = formatRupiah(this.value);
  });

  tanpa_rupiah.addEventListener('keydown', function(event)
  {
    limitCharacter(event);
  });
}
function datatables()
  {
    $('#datatables').DataTable({
          paging: true,
          lengthChange: true,
          searching: true,
          ordering: true,
          info: true,
          order: [[ 1, "desc" ]],                
          autoWidth: true         
        });
  }
  function datatables2()
  {
    $('#datatables2').DataTable({
          paging: true,
          lengthChange: true,
          searching: true,
          ordering: true,
          info: true,
          order: [[ 1, "desc" ]],                
          autoWidth: true         
        });
  }
</script>