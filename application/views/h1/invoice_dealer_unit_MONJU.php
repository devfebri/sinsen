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
<body>
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
    <li class="">Invoice Keluar</li>
    <li class="">Inovice Dealer</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>
  <section class="content">
  <?php 
    if($set=="detail"){
      $row = $dt_invoice->row();

      if($row->pos == 'Ya'){          
        $cek_de = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer_induk);
        $kode_dealer_md = ($cek_de->num_rows() > 0) ? $cek_de->row()->kode_dealer_md : "" ;
        $nama_dealer_1 = ($cek_de->num_rows() > 0) ? $cek_de->row()->nama_dealer : "" ;
        $nama_dealer = $nama_dealer_1." - ".$row->nama_dealer;
      }else{
        $nama_dealer = $row->nama_dealer;
      }
    ?>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
    <?php if (isset($row)) { ?>
        
    <?php } ?>
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/invoice_dealer_unit">
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
            <!-- <form id="form_" class="form-horizontal" action="h1/invoice_dealer_unit/approve" method="post" enctype="multipart/form-data">               -->
            <form id="form_" class="form-horizontal" action="h1/invoice_dealer_unit/approve" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_do" placeholder="No DO" value="<?php echo $row->no_do ?>" readonly class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl DO</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Tgl DO" value="<?php echo $row->tgl_do ?>" readonly id="tanggal2" class="form-control">                    
                  </div>                                    
                </div>  
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Kode Customer" value="<?php echo $row->kode_dealer_md ?>" readonly class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">NPWP Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="BPWP Customer" value="<?php echo $row->npwp ?>" readonly class="form-control">                    
                  </div>                                    
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Nama Customer" value="<?php echo $nama_dealer ?>" readonly class="form-control">                    
                  </div>                                                      
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Sisa Plafon</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Sisa Plafon" value="<?php echo mata_uang2($row->plafon) ?>" readonly class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Plafon Maks</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Plafon Maks" value="<?php echo mata_uang2($row->plafon_maks) ?>" readonly class="form-control">                    
                  </div>                                                                      
                </div>  
                <div class="form-group">
                  <?php 
                  if($row->tgl_faktur != "0000-00-00"){
                    $tgl1 = $row->tgl_faktur;// pendefinisian tanggal awal
                    $top = $row->top_unit;
                    $tgl2 = date("Y-m-d", strtotime("+".$top." days", strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari                    
                  }else{
                    $tgl2 = "";
                  }
                  ?>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Jatuh Tempo</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $tgl2 ?>" name="no_mesin" placeholder="Tgl Jatuh Tempo" readonly class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Alamat Customer" value="<?php echo $row->alamat ?>" readonly class="form-control">                    
                  </div>                                    
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Total Hutang</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Total Hutang" readonly class="form-control">                    
                  </div>                                     -->
                </div> 
                <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Bank Dealer Financing</label>
                <div class="col-sm-4">
                  <?php
                    $disabled = '';
                    if ($status_invoice=='printable' OR $status_invoice=='approve') {
                      $disabled = 'disabled';
                    }
                  ?>
                  <select class="form-control" name="bank" id="bank_pilih" onchange="getBunga()" <?= $disabled ?>>
                    <option value="">- choose -</option>
                    <?php 
                    $bank = $this->m_admin->getSortCond("ms_bank","bank","ASC");
                    foreach ($bank->result() as $isi) {
                      $select = $isi->bank==$row->bank?'selected':'';
                      echo "<option value='$isi->bank' bunga_bank=$isi->bunga_bank $select>$isi->bank</option>";
                    }
                    ?>
                  </select>
                </div>                
                <label for="inputEmail3" class="col-sm-2 control-label">Bunga Bank Dealer Financing</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="bunga_bank" placeholder="Bunga Bank" name="bunga_bank" value="<?= $row->bunga_bank ?>" readonly>
                </div>
              </div>       
                <table class="table myTable1 table-bordered table-hover">
                  <thead>
                    <th width="7%">Kode Item</th>
                    <th width="12%">Tipe Kendaraan</th>
                    <th width="10%">Warna</th>
                    <th width="7%">Qty DO</th>
                    <th width="7%">Qty RFS</th>
                    <th>Total Harga Satuan</th>
                    <th>Diskon Per Unit</th>
                    <th width="12%">Diskon Tambahan / Nominal Quotation</th>
                    <th>Total Diskon</th>
                    <th>Total</th>
                  </thead>
                  <tbody>
                    <tr v-for="(dtl, index) of details">
                      <td>{{dtl.id_item}}</td>
                      <td>{{dtl.deskripsi_ahm}}</td>
                      <td>{{dtl.warna}}</td>
                      <td>{{dtl.qty_do}}</td>
                      <td>{{dtl.qty_rfs}}</td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="dtl.harga" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="diskonUnit(dtl)" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                      <td>
                        <input type="hidden" v-model="dtl.disc_tambahan" :name="'disc_tambahan_'+dtl.id_item">
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="dtl.disc_tambahan" 
                        v-bind:minus="false" :empty-value="0" separator="." :readonly="status=='printable'||status=='approved'"/>
                      </td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="totDiskon(dtl)" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="dtl.harga*dtl.qty_do" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="3"><b>Total</b></td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="totQtyDO" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="totDiskons" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="totBayarKotor" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="8"></td>
                      <td>Potongan</td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="totDiskons" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                    </tr>
                    <tr v-if="dealer_financing=='Ya'">
                      <td colspan="8"></td>
                      <td>Diskon TOP</td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="diskonTop" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="8"></td>
                       <td>DPP</td>
                       <td>
                         <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="dpp" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                       </td>
                    </tr>
                    <tr>
                      <td colspan="8"></td>
                      <td>PPN</td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="ppn" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="8"></td>
                      <td>Total Bayar</td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="totBayar" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                    </tr>
                  </tfoot>
                </table>            
                <br>                                                
              </div><!-- /.box-body -->              
              <?php if ($row->status_invoice=='waiting approval'):
                  $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');
                ?>
                      <p align="center">
                        <!-- <a <?= $approval ?> data-toggle="tooltip" title="Approve Data" onclick="return confirm('Are you sure to approve this data?')" class="btn btn-success btn-flat" href="h1/invoice_dealer_unit/approve?id=<?= $row->no_do ?>">Approve</a> -->
                        <button <?= $approval ?> onclick="return confirm('Are you sure to approve this data?')" type="submit" name="approve" class="btn btn-success btn-flat">Approve</button>
                        <button <?= $approval ?> onclick="return confirm('Are you sure to approve this data without overdue checking?')" type="submit" name="toleran" class="btn btn-info btn-flat">Approve without Overdue Checking</button>
                      <a <?= $approval ?> data-toggle="tooltip" title="Reject Data" onclick="return confirm('Are you sure to reject this data?')" class="btn btn-danger btn-flat" href="h1/invoice_dealer_unit/reject?id=<?= $row->no_do ?>">Reject</a>
                      </p>
                    <?php endif ?>
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <?php 
                  // if($row->no_faktur == '-'){                  
                  //   $visible = "style='visibility: hidden;'";
                  // }else{
                  //   $visible = "style=''";
                  // } 
                  $visible='';
                  ?>
                  <button type="button" <?php echo $visible ?> name="save" value="Detail" data-toggle="collapse" data-target="#demo" class="btn btn-info btn-flat"><i class="fa fa-list"></i> Detail Piutang</button>                                    
                  <div id="demo" class="collapse">
                    <br>        
                    <div class="form-group">                                
                      <label for="inputEmail3" class="col-sm-2 control-label">Maks Plafon</label>
                      <div class="col-sm-4">
                        <input type="text" name="no_mesin" value="<?php echo mata_uang2($row->plafon_maks) ?>" placeholder="Maks Plafon" readonly class="form-control">                    
                      </div>                                    
                      <label for="inputEmail3" class="col-sm-2 control-label">Sisa Plafon</label>
                      <div class="col-sm-4">
                        <input type="text" name="no_mesin" value="<?php echo mata_uang2($row->plafon) ?>" placeholder="Sisa Plafon" readonly class="form-control">                    
                      </div>                                    
                    </div>
                    <table class="table table-bordered table-hover">
                      <tr align="center">
                        <th colspan="3">Daftar Piutang</th>
                      </tr>
                      <tr>
                        <th>No Invoice</th>
                        <th>Tgl Jatuh Tempo</th>
                        <th>Nilai</th>
                      </tr>
                      <?php echo '';
                      // $am = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do
                      // WHERE tr_do_po.no_do = '$row->no_do'");
                      // foreach ($am->result() as $isi) {
                      //   $to = $this->db->query("SELECT SUM(qty_do * harga) AS tot FROM tr_do_po_detail WHERE no_do = '$isi->no_do'")->row();
                      //   //$t_ppn = $to->tot + ($to->tot * 0.1);
                      //   echo "
                      //     <tr>
                      //       <td>$isi->no_faktur</td>                            
                      //       <td>$isi->tgl_faktur</td>                            
                      //       <td>".mata_uang2($total_bayar)."</td>                            
                      //     </tr>
                      //     ";
                      // }
                      ?>
                      <tbody>            
                        <?php           
                        $id_dealer = $row->id_dealer;
                        $dt_invoice = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
                            WHERE (tr_invoice_dealer.status_invoice = 'printable' OR tr_invoice_dealer.status_invoice = 'approved') AND  
                            tr_invoice_dealer.status_bayar <> 'lunas' 
                            AND tr_do_po.id_dealer=$id_dealer
                            ORDER BY tr_invoice_dealer.id_invoice_dealer DESC");
                        foreach($dt_invoice->result() as $row) {                                                     
                          $rt = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();
                          $total_harga = 0;
                              $total_harga = 0;
                              $dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
                                  ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                                  ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                                  ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$row->no_do'");
                                $to=0;$po=0;$do=0;
                                foreach($dt_do_reg->result() as $isi){
                                  $total_harga = $isi->harga * $isi->qty_do;

                                  $get_d  = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
                                    INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
                                    INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
                                    WHERE tr_invoice_dealer.no_do = '$isi->no_do'");
                                  if($get_d->num_rows() > 0){
                                    $g = $get_d->row();
                                    $bunga_bank = $g->bunga_bank/100;
                                    $top_unit = $g->top_unit;
                                    $dealer_financing = $g->dealer_financing;
                                  }else{
                                    $bunga_bank = "";
                                    $top_unit = "";
                                    $dealer_financing = "";
                                  }

                                  $pot = $isi->disc * $isi->qty_do;                    
                                  $to = $to + $total_harga;                    
                                  $po = $po + $pot;                    
                                  $do = $do + $isi->qty_do;                    
                                }                  
                                $d = (($to-$po)-($bunga_bank/360*$top_unit))/(1+((1.1*$bunga_bank/360)*$top_unit));
                                $diskon_top = ($to-$po)-$d;
                                if($dealer_financing=='Ya') {
                                  $y = $d * 0.1;
                                  $total_bayar = $d + $y;
                                }else{
                                  $y = $d * 0.1;
                                  $total_bayar = $d + $y;
                                }  
                        $cek = $this->m_admin->cekPembayaran($row->no_faktur,$total_bayar);
                          if ($cek>0) {
                             echo "          
                            <tr>               
                              <td>$row->no_faktur</td>                            
                              <td>$row->tgl_faktur</td>                            
                              <td>$rt->nama_dealer</td>
                              <td align='right'>".mata_uang2($cek)."</td>             
                            </tr>";
                          }                 

                        }
                        $dt_rekap = $this->db->query("SELECT tr_monout_piutang_bbn.*,tr_pengajuan_bbn.id_dealer
                        FROM tr_monout_piutang_bbn 
                        INNER JOIN tr_pengajuan_bbn ON tr_monout_piutang_bbn.no_bastd=tr_pengajuan_bbn.no_bastd
                    JOIN tr_faktur_stnk ON tr_pengajuan_bbn.no_bastd=tr_faktur_stnk.no_bastd
                    WHERE (tr_pengajuan_bbn.status_pengajuan='checked' OR tr_pengajuan_bbn.status_pengajuan='approved') AND tr_faktur_stnk.status_faktur='approved'
                    AND tr_pengajuan_bbn.id_dealer=$id_dealer
                    ");
                         foreach($dt_rekap->result() as $row) {                                         
                          $dealer = $this->db->get_where('ms_dealer', ['id_dealer'=>$row->id_dealer])->row();
                          $cek = $this->m_admin->cekPembayaran($row->no_bastd,$row->total);
                            if ($cek>0){
                            echo "          
                            <tr>                                                 
                              <td>$row->no_bastd</td>                            
                              <td>$row->tgl_rekap</td>
                              <td>$dealer->nama_dealer</td>
                              <td align='right'>".mata_uang2($cek)."</td>    
                            </tr>                                      
                              ";   
                            } 
                          }
                          

                        ?>
                        </tbody>
                    </table>
                  </div>
                </div>
              </div><!-- /.box-footer -->  
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<script>
function getBunga(){ 
    var element = $('#bank_pilih').find('option:selected'); 
    var bunga_bank = element.attr("bunga_bank"); 
    $('#bunga_bank').val(bunga_bank); 
    form_.bunga_bank = bunga_bank/100;
    // console.log(form_.bunga_bank)
}; 
   var form_ = new Vue({
      el: '#form_',
      data: {
        detail:[],
        top_unit:'<?= $top_unit ?>',
        bunga_bank:'<?= $bunga_bank_awal ?>',
        status:'<?= $status_invoice ?>',
        dealer_financing:'<?= $dealer_financing ?>',
        details : <?= isset($details)?json_encode($details):'[]' ?>,
      },
      methods: {
        totDiskon:function(dtl) {
          disc_tambahan = isNaN(dtl.disc_tambahan)?0:parseInt(dtl.disc_tambahan);
          diskon_unit   = isNaN(dtl.diskon_unit)?0:parseInt(dtl.diskon_unit);
          disc_scp    = isNaN(dtl.disc_scp)?0:parseInt(dtl.disc_scp);
          qty_do        = isNaN(dtl.qty_do)?0:parseInt(dtl.qty_do);
          total         = parseInt((diskon_unit+disc_scp) * qty_do)+parseInt(disc_tambahan);
          // console.log('disc_tambahan'+disc_tambahan);
          // console.log('diskon_unit'+diskon_unit);
          // console.log('disc_scp'+disc_scp);
          // console.log('qty_do'+qty_do);
          return total;
        },
        diskonUnit:function(dtl) {
          diskon_unit   = isNaN(dtl.diskon_unit)?0:parseInt(dtl.diskon_unit);
          disc_scp    = isNaN(dtl.disc_scp)?0:parseInt(dtl.disc_scp);
          total         = parseInt(diskon_unit+disc_scp);
          return total;
        },
        totDetail:function(dtl) {
          return total;
        }
      },
      watch:{
        detail:function () {
          // alert('dd');
        }
      },
      computed: {
        totDiskons:function() {
          total = 0;
          for(dtl of this.details)
          {
            total += this.totDiskon(dtl);
          }
          return total;
        },
        totQtyDO:function() {
          total = 0;
          for(dtl of this.details)
          {
            total += parseInt(dtl.qty_do);
          }
          return total;
        },
        dpp:function() {
          dpp = 0;
          total_bayar = this.totBayarKotor;
          total_diskon = this.totDiskons;
          bunga_bank = parseFloat(this.bunga_bank).toFixed(2);
          total = (((total_bayar-total_diskon)-(this.bunga_bank/360*parseInt(this.top_unit)))/(1+((1.1*this.bunga_bank/360)*parseInt(this.top_unit)))).toFixed(2);
          // return Math.round(total / 10) * 10;
           return Math.round(total).toFixed(0);
          // console.log('dpp:'+total);
        },
        ppn: function () {
           let ppn =  (this.dpp * 0.1).toFixed(2);
           // return Math.round(ppn / 10) * 10;
           return Math.round(ppn).toFixed(0);
        },
        totBayar : function () {
          let total_bayar = parseInt(this.dpp) + parseInt(this.ppn);
          return total_bayar;
        },
        totBayarKotor : function () {
          total_bayar = 0;
          total_diskon = 0;
          for(dtl of this.details)
          {
            total_bayar += parseInt(dtl.qty_do)*parseInt(dtl.harga);
          }
          return parseInt(total_bayar);
        },
        diskonTop:function () {
          total_bayar = 0;
          total_diskon = 0;
          let diskon_top = 0;
          for(dtl of this.details)
          {
            total_diskon += this.totDiskon(dtl);
            total_bayar += parseInt(dtl.qty_do)*parseInt(dtl.harga);
            // console.log(total_bayar+'>'+total_diskon);
          }
            console.log(total_bayar+'>'+total_diskon);
            diskon_top = parseInt((total_bayar - total_diskon)-this.dpp);
            console.log(diskon_top);
          return diskon_top;

        }
      },
  });
  function getItem() {
    values = {id_tipe_kendaraan:$('#id_tipe_kendaraan').val(),
              start_date:$('#start_date').val(),
              id_kelompok_harga:$('#id_kelompok_harga').val(),
             }
    $.ajax({
      url:'<?= base_url('master/kelompok_md/getItem') ?>',
      type:"POST",
      data: values,
      cache:false,
      dataType:'JSON',
      success:function(response){
        console.log(response);
        form_.detail=[];
        for (dtl of response) {
            form_.detail.push(dtl);
        }
      }
    }); 
  }

$('#submitBtn').click(function(){
  $('#form_').validate({
      rules: {
          'checkbox': {
              required: true
          }
      },
      highlight: function (input) {
          $(input).parents('.form-group').addClass('has-error');
      },
      unhighlight: function (input) {
          $(input).parents('.form-group').removeClass('has-error');
      }
  })
  var values = {details:form_.details};
  var form   = $('#form_').serializeArray();
  for (field of form) {
    values[field.name] = field.value;
  }
  if (form_.details.length==0) {
    alert('Detail belum ditentukan !');
    return false;
  }
  if ($('#form_').valid()) // check if form is valid
  {
    $.ajax({
      beforeSend: function() {
        $('#submitBtn').attr('disabled',true);
      },
      url:'<?= base_url('master/kelompok_md/') ?>',
      type:"POST",
      data: values,
      cache:false,
      dataType:'JSON',
      success:function(response){
        if (response.status=='sukses') {
          window.location = response.link;
        }else{
          alert(response.pesan);
          $('#submitBtn').attr('disabled',false);
        }
      },
      error:function(){
        alert("failure");
        $('#submitBtn').attr('disabled',false);

      },
      statusCode: {
        500: function() { 
          alert('fail');
          $('#submitBtn').attr('disabled',false);

        }
      }
    });
  }else{
    alert('Silahkan isi field required !')
  }
})
</script> 
<?php 
    }elseif($set=="disc_tambahan"){
      $row = $dt_invoice->row();
    ?>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
  $(document).ready(function(){
    <?php if (isset($row)) { ?>
        
    <?php } ?>
  })
</script>
    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/invoice_dealer_unit">
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
            <form id="form_" class="form-horizontal" action="h1/invoice_dealer_unit/save_set_diskon" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_do" placeholder="No DO" value="<?php echo $row->no_do ?>" readonly class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl DO</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Tgl DO" value="<?php echo $row->tgl_do ?>" readonly id="tanggal2" class="form-control">                    
                  </div>                                    
                </div>  
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Kode Customer" value="<?php echo $row->kode_dealer_md ?>" readonly class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">NPWP Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="BPWP Customer" value="<?php echo $row->npwp ?>" readonly class="form-control">                    
                  </div>                                    
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Nama Customer" value="<?php echo $row->nama_dealer ?>" readonly class="form-control">                    
                  </div>                                                      
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Sisa Plafon</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Sisa Plafon" value="<?php echo mata_uang2($row->plafon) ?>" readonly class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Plafon Maks</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Plafon Maks" value="<?php echo mata_uang2($row->plafon_maks) ?>" readonly class="form-control">                    
                  </div>                                                                      
                </div>                
                <div class="form-group">
                  <?php 
                  if($row->tgl_faktur != "0000-00-00"){
                    $tgl1 = $row->tgl_faktur;// pendefinisian tanggal awal
                    $top = $row->top_unit;
                    $tgl2 = date("Y-m-d", strtotime("+".$top." days", strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari                    
                  }else{
                    $tgl2 = "";
                  }
                  ?>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Jatuh Tempo</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $tgl2 ?>" name="no_mesin" placeholder="Tgl Jatuh Tempo" readonly class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Alamat Customer" value="<?php echo $row->alamat ?>" readonly class="form-control">                    
                  </div>                                    
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Total Hutang</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Total Hutang" readonly class="form-control">                    
                  </div>                                     -->
                </div>                
                                    
                <br>                                    
                <table class="table myTable1 table-bordered table-hover">
                  <thead>
                    <th>Kode Item</th>
                    <th>Tipe Kendaraan</th>
                    <th>Warna</th>
                    <th>Qty DO</th>
                    <th>Total Harga Satuan</th>
                    <th>Diskon Per Unit</th>
                    <th>Diskon SCP</th>
                    <th width="12%">Diskon Tambahan</th>
                    <th>Total Diskon</th>
                    <th>Total</th>
                  </thead>
                  <tbody>
                    <tr v-for="(dtl, index) of details">
                      <td>{{dtl.id_item}}</td>
                      <td>{{dtl.deskripsi_ahm}}</td>
                      <td>{{dtl.warna}}</td>
                      <td>{{dtl.qty_do}}</td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="dtl.harga" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="dtl.diskon_unit" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="dtl.disc_scp" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                      <td>
                        <input type="hidden" v-model="dtl.id_item" name="id_item[]">
                        <vue-numeric name="disc_tambahan[]" style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="dtl.disc_tambahan" 
                        v-bind:minus="false" :empty-value="0" separator="."/>
                      </td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="totDiskon(dtl)" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                      <td>
                        <vue-numeric style="float: left;width: 100%;text-align: right;"
                        class="form-control text-rata-kanan isi" v-model="dtl.harga*dtl.qty_do" 
                        v-bind:minus="false" :empty-value="0" separator="." readonly/>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div><!-- /.box-body -->              
                      <p align="center">
                        <button type="submit" onclick="return confirm('Apakah anda yakin ?')" class="btn btn-primary btn-flat">Simpan Diskon</button>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<script>
   var form_ = new Vue({
      el: '#form_',
      data: {
        detail:[],
        details : <?= isset($details)?json_encode($details):'[]' ?>,
      },
      methods: {
        clearDetail: function () {
        this.detail=[];
        $('#id_tipe_kendaraan').val('').trigger('change'); 
        },
        addDetails : function(){
          // if (this.details[index].parts.length > 0) {
          //   for (prt of this.details[index].parts) {
          //     if (part.id_part === prt.id_part) {
          //         alert("Part Sudah Dipilih !");
          //         return false;
          //     }
          //   }
          // }
          // if (this.details[index].part.id_part=='' || this.details[index].part.qty_part=='') 
          // {
          //   alert('Isi data dengan lengkap !');
          //   return false;
          // }
          // console.log(this.detail);
          // this.details.push(this.detail);
          // console.log(this.details);
          for(ck of this.details){
            for(ck2 of this.detail){
              if (ck.id_item==ck2.id_item) {
                alert('Kode Item = '+ck.id_item+' sudah ada dalam daftar !');
                return false;
              }
            }
          }
          for (dtl of this.detail) {
            if (dtl.harga_baru>0) {
              this.details.push(dtl);
            }
          }
          this.clearDetail();
        },
  
        delDetails: function(index){
            this.details.splice(index, 1);
        },
        showModalPart: function(index) {
          $('.modalPart').modal('show');
          this.index_detail_part = index;
          console.log(this.index_detail_part);
        },
        totDiskon:function(dtl) {
          disc_tambahan = isNaN(dtl.disc_tambahan)?0:parseInt(dtl.disc_tambahan);
          diskon_unit = isNaN(dtl.diskon_unit)?0:parseInt(dtl.diskon_unit);
          disc_scp = isNaN(dtl.disc_scp)?0:parseInt(dtl.disc_scp);
          qty_do = isNaN(dtl.qty_do)?0:parseInt(dtl.qty_do);
          total = parseInt((diskon_unit+disc_tambahan+disc_scp) * qty_do);
          return total;
        },
        totDetail:function(dtl) {
          
          return total;
        }
      },
      watch:{
        detail:function () {
          // alert('dd');
        }
      },
      computed: {
        totDiskons:function(detail) {
          total = 0;
          // total = parseInt((detail.diskon_unit+detail.disc_tambahan+detail.disc_scp) * detail.qty_do);
          // po_fix     = detail.po_fix==''?0:detail.po_fix;
          // qty_indent = detail.qty_indent==''?0:detail.qty_indent;
          // total      = detail.harga * (parseInt(po_fix)+parseInt(qty_indent));
          // ppn = total *(10/100);
          // this.detail.total_harga = total+ppn;
          return total;
        },
      },
  });
  function getItem() {
    values = {id_tipe_kendaraan:$('#id_tipe_kendaraan').val(),
              start_date:$('#start_date').val(),
              id_kelompok_harga:$('#id_kelompok_harga').val(),
             }
    $.ajax({
      url:'<?= base_url('master/kelompok_md/getItem') ?>',
      type:"POST",
      data: values,
      cache:false,
      dataType:'JSON',
      success:function(response){
        console.log(response);
        form_.detail=[];
        for (dtl of response) {
            form_.detail.push(dtl);
        }
      }
    }); 
  }

$('#submitBtn').click(function(){
  $('#form_').validate({
      rules: {
          'checkbox': {
              required: true
          }
      },
      highlight: function (input) {
          $(input).parents('.form-group').addClass('has-error');
      },
      unhighlight: function (input) {
          $(input).parents('.form-group').removeClass('has-error');
      }
  })
  var values = {details:form_.details};
  var form   = $('#form_').serializeArray();
  for (field of form) {
    values[field.name] = field.value;
  }
  if (form_.details.length==0) {
    alert('Detail belum ditentukan !');
    return false;
  }
  if ($('#form_').valid()) // check if form is valid
  {
    $.ajax({
      beforeSend: function() {
        $('#submitBtn').attr('disabled',true);
      },
      url:'<?= base_url('master/kelompok_md/') ?>',
      type:"POST",
      data: values,
      cache:false,
      dataType:'JSON',
      success:function(response){
        if (response.status=='sukses') {
          window.location = response.link;
        }else{
          alert(response.pesan);
          $('#submitBtn').attr('disabled',false);
        }
      },
      error:function(){
        alert("failure");
        $('#submitBtn').attr('disabled',false);

      },
      statusCode: {
        500: function() { 
          alert('fail');
          $('#submitBtn').attr('disabled',false);

        }
      }
    });
  }else{
    alert('Silahkan isi field required !')
  }
})
</script>
    <?php 
    }elseif($set=="download"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/invoice_dealer_unit">
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
            <form class="form-horizontal" action="h1/invoice_dealer_unit/download_file" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Cair</label>
                  <div class="col-sm-4">
                    <input type="text" name="tgl_cair" placeholder="Tanggal Cair" class="form-control" id='tanggal' autocomplete="off">                    
                  </div>     
                  <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-download"></i>Download</button>
                  </div>                               
                </div>  
                  
                </div>
              </div><!-- /.box-footer -->  
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
          <a href="h1/invoice_dealer_unit/download">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-download"></i> Download Txt Bank</button>
          </a>          
          <a href="h1/invoice_dealer_unit/history">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> History</button>
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
              <th>No Faktur</th>                           
              <th>Tgl Faktur</th>              
              <th>No DO</th>              
              <th>Nama Customer</th>
            <!--   <th>Bank</th>
              <th>Tgl Cair</th> -->
              <th>Total</th> 
              <th>Status</th>             
              <th width='10%'>Aksi</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1;           
          foreach($dt_invoice->result() as $row) {  
             $rt = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();
             $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');
             $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            if($row->status_bayar == 'lunas'){
              $status_bayar = "<span class='label label-success'>Lunas</span>";
            }else{
              $status_bayar = "";
            }
            if($row->status_invoice == 'waiting approval'){
              $status = "<span class='label label-warning'>$row->status_invoice</span>";
              $tampil='';
              $tampil = "<a $approval data-toggle=\"tooltip\" title=\"Approve Data\" href=\"h1/invoice_dealer_unit/view?id=$row->no_do\" class=\"btn btn-success btn-xs btn-flat\">Approve</a>";
              //$tampil = "<a $approval data-toggle=\"tooltip\" title=\"Approve Data\" onclick=\"return confirm('Are you sure to approve this data?')\" class=\"btn btn-success btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/approve?id=$row->no_do\">Approve</a>";

              $tampil2 = "<a $approval data-toggle=\"tooltip\" title=\"Reject Data\" onclick=\"return confirm('Are you sure to reject this data?')\" class=\"btn btn-danger btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/reject?id=$row->no_do\">Reject</a>";
              $tampil2 = '';
              $tampil3 = "";            
              $tampil4 = "";
            }elseif($row->status_invoice=='rejected' OR $row->status_invoice=='reject finance'){
              $status = "<span class='label label-danger'>$row->status_invoice</span>";
              $tampil2 = "";  
              if($rt->dealer_financing=='Ya' AND $row->tgl_cair == '0000-00-00') {
                $tampil4 = "<button type=\"button\" title=\"Input Tgl Cair\" class=\"btn btn-xs btn-primary btn-flat\"                   
                  onclick=\"input_tgl('$row->no_do')\">Tgl Cair</button>";                            
              }            else{
                $tampil4='';
              }
              $tampil = "";
              $tampil3 = "";              
            }elseif($row->status_invoice=='approved'){
              $status = "<span class='label label-danger'>$row->status_invoice</span>";
              $tampil2 = "";  
              if($rt->dealer_financing=='Ya' AND $row->tgl_cair == '0000-00-00') {
                $tampil4 = "<button type=\"button\" title=\"Input Tgl Cair\" class=\"btn btn-xs btn-primary btn-flat\"                   
                  onclick=\"input_tgl('$row->no_do')\">Tgl Cair</button>";                            
              }            else{
                $tampil4='';
              }
              $tampil = "";
              if($row->tgl_cair != "0000-00-00" AND $rt->dealer_financing=='Ya'){
                $tampil3 = "<a $print data-toggle=\"tooltip\" target=\"_blank\" title=\"Print Data\"  class=\"btn btn-warning btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/cetak?id=$row->id_invoice_dealer\">Print</a>";         
                // $tampil3 = "<button $print data-toggle=\"tooltip\" target=\"_blank\" title=\"Print Data\"  class=\"btn btn-warning btn-xs btn-flat\" onclick=\"setDiskon('$row->id_invoice_dealer','$row->set_disc_tambahan_by')\">Print</button>";            
              }elseif($rt->dealer_financing != 'Ya'){
                $tampil3 = "<a $print data-toggle=\"tooltip\" target=\"_blank\" title=\"Print Data\"  class=\"btn btn-warning btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/cetak?id=$row->id_invoice_dealer\">Print</a>";
                // $tampil3 = "<button $print data-toggle=\"tooltip\" target=\"_blank\" title=\"Print Data\"  class=\"btn btn-warning btn-xs btn-flat\" onclick=\"setDiskon('$row->id_invoice_dealer')\">Print</button>"; 
              }else{
              $tampil3 = "";
              }
            }elseif($row->status_invoice=='printable'){
              $status = "<span class='label label-success'>$row->status_invoice</span>";
              $tampil2 = "";
              $tampil = "";
              $tampil4 = "";
              $tampil3 = "<a $print data-toggle=\"tooltip\" target=\"_blank\" title=\"Print Data\"  class=\"btn btn-warning btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/cetak?id=$row->id_invoice_dealer\">Print</a>";
            }


           
            $cek_do = $this->db->query("SELECT SUM(qty_do) AS jum FROM tr_do_po_detail WHERE no_do = '$row->no_do'")->row();
            if($cek_do->jum > 0){            
              echo "          
              <tr>              
                <td>$no</td>              
                <td>$row->no_faktur $status_bayar</td>                            
                <td>$row->tgl_faktur</td>                            
                <td>
                  <a href='h1/invoice_dealer_unit/view?id=$row->no_do'>
                    $row->no_do
                  </a>
                </td>                            
                <td>$rt->nama_dealer</td>             ";               
                // <td>$row->bank</td>                            
                // <td>$row->tgl_cair</td>                            
               echo "<td>";
                $total_harga = 0;
                $dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
                    ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                    ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                    ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$row->no_do'");
                  $to=0;$po=0;$do=0;
                  foreach($dt_do_reg->result() as $isi){
                    $total_harga = $isi->harga * $isi->qty_do;

                    $get_d  = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
                      INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
                      INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
                      WHERE tr_invoice_dealer.no_do = '$isi->no_do'");
                    if($get_d->num_rows() > 0){
                      $g = $get_d->row();
                      $bunga_bank = $g->bunga_bank/100;
                      $top_unit = $g->top_unit;
                      $dealer_financing = $g->dealer_financing;
                    }else{
                      $bunga_bank = "";
                      $top_unit = "";
                      $dealer_financing = "";
                    }


                    $cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po_detail ON tr_invoice_dealer_detail.no_do = tr_do_po_detail.no_do
                          WHERE tr_do_po_detail.no_do = '$isi->no_do' AND LEFT(tr_invoice_dealer_detail.id_item,6) = '$isi->id_item'");
                    if($cek2->num_rows() > 0){
                      $d = $cek2->row();
                      $potongan = $d->jum;
                    }else{
                      $potongan = 0;
                    }

                    $pot = ($potongan + $isi->disc + $isi->disc_scp) * $isi->qty_do + $isi->disc_tambahan;                    
                    $to = $to + $total_harga;                    
                    $po = $po + $pot;                    
                    $do = $do + $isi->qty_do;                    
                  }                  
                  $d = (($to-$po)-($bunga_bank/360*$top_unit))/(1+((1.1*$bunga_bank/360)*$top_unit));
                  $diskon_top = ($to-$po)-$d;
                  if($dealer_financing=='Ya') {
                    $y = $d * 0.1;
                    $total_bayar = $d + $y;
                  }else{
                    $y = $d * 0.1;
                    $total_bayar = $d + $y;
                  }         
                                              
                  echo mata_uang2($total_bayar)."</td>
                <td>$status</td>                                                        
                <td>";
                echo $tampil;                                 
                echo $tampil2;                                 
                echo $tampil3;                                 
                  echo $tampil4;                                             
                echo "</td>                                          
                ";                                      
              $no++;
              }
            } 
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<script>
  function setDiskon(id_invoice_dealer,sudah_set) {
    if (sudah_set!='') {
      var alert = 'Apakah anda akan mengatur diskon kembali untuk invoice ini ?';
    }else{
      var alert = "Apakah ada diskon tambahan untuk invoice ini ?";
    }
    if(confirm(alert) == true) {
      window.location = "<?= base_url('h1/invoice_dealer_unit/set_disc_tambahan?id=') ?>"+id_invoice_dealer;
    }else{
      window.location = "<?= base_url('h1/invoice_dealer_unit/cetak?id=') ?>"+id_invoice_dealer;
    }
  }
</script>
     <?php
    }elseif($set=="history"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/invoice_dealer_unit">            
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>               
              <th width="5%">No</th>            
              <th>No Faktur</th>                           
              <th>Tgl Faktur</th>              
              <th>No DO</th>              
              <th>Nama Customer</th>
             <!--  <th>Bank</th>
              <th>Tgl Cair</th> -->
              <th>Total</th> 
              <th>Tgl Cair</th>
              <th>Status</th>         
              <th width='10%'>Aksi</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1;           
          foreach($dt_invoice->result() as $row) {  
            
            $print = $this->m_admin->set_tombol($id_menu,$group,'print');
            if($row->status_bayar == 'lunas'){
              $status_bayar = "<span class='label label-success'>Lunas</span>";
            }else{
              $status_bayar = "";
            }
            if($row->status_invoice == 'waiting approval'){
              $status = "<span class='label label-warning'>$row->status_invoice</span>";
              //$tampil = "<a $approval data-toggle=\"tooltip\" title=\"Approve Data\" onclick=\"return confirm('Are you sure to approve this data?')\" class=\"btn btn-success btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/approve?id=$row->no_do\">Approve</a>";
              $tampil = "<a $approval data-toggle=\"tooltip\" title=\"Approve Data\" href=\"h1/invoice_dealer_unit/view?id=$row->no_do\" class=\"btn btn-success btn-xs btn-flat\">Approve</a>";
              $tampil2 = "<a $approval data-toggle=\"tooltip\" title=\"Reject Data\" onclick=\"return confirm('Are you sure to reject this data?')\" class=\"btn btn-danger btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/reject?id=$row->no_do\">Reject</a>";
              $tampil3 = "";            
              $tampil4 = "";
            }elseif($row->status_invoice=='rejected' OR $row->status_invoice=='approved'){
              $status = "<span class='label label-danger'>$row->status_invoice</span>";
              $tampil2 = "";              
              $tampil4 = "<button type=\"button\" title=\"Input Tgl Cair\" class=\"btn btn-xs btn-primary btn-flat\"                   
                  onclick=\"input_tgl('$row->no_do')\">Tgl Cair</button>";                            
              $tampil = "";
              $tampil3 = "";
            }elseif($row->status_invoice=='printable'){
              $status = "<span class='label label-success'>$row->status_invoice</span>";
              $tampil2 = "";
              $tampil = "";
              $tampil4 = "";
              $tampil3 = "
              <button type=\"button\" title=\"Input Tgl Cair\" class=\"btn btn-xs btn-primary btn-flat\"                   
                  onclick=\"input_tgl('$row->no_do')\">Tgl Cair</button>

              <a $print data-toggle=\"tooltip\" target=\"_blank\" title=\"Print Data\"  class=\"btn btn-warning btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/cetak?id=$row->id_invoice_dealer\">Print</a>";
            }


            $rt = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();          
            echo "          
              <tr>              
                <td>$no</td>              
                <td>$row->no_faktur $status_bayar</td>                            
                <td>$row->tgl_faktur</td>                            
                <td>
                  <a href='h1/invoice_dealer_unit/view?id=$row->no_do'>
                    $row->no_do
                  </a>
                </td>                            
                <td>$rt->nama_dealer</td>    ";                                        
                echo "<td>";                                  
                  $nosin = $this->m_admin->get_detail_inv_dealer($row->no_do);
                  $total_bayar2 = $nosin['total_bayar'];
                  echo mata_uang2($total_bayar2)."</td>             
                  <td>$row->tgl_cair</td>                                                        
                <td>$status</td>                                                        
                <td>";
                echo $tampil;                                 
                echo $tampil2;                                 
                echo $tampil3;                                 
                echo $tampil4;                                             
                echo "</td>                                          
                ";                                      
            $no++;
            }          
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->    

    <?php
    }elseif($set=="history_ulang"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/invoice_dealer_unit">            
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>                                      
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">        
        <table id="table" class="table table-bordered table-hover">
          <thead>
            <tr>               
              <th width="5%">No</th>            
              <th>No Faktur</th>                           
              <th>Tgl Faktur</th>              
              <th>No DO</th>              
              <th>Nama Customer</th>             
              <th>Total</th> 
              <th>Tgl Cair</th>
              <th>Status</th>         
              <th width='10%'>Aksi</th>
            </tr>
          </thead>
          <tbody>                                 
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    }
    ?>

  </section>
</div>
<div class="modal fade"  width="850px" id="modal_tagih">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Input Tanggal Tagih</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" action="h1/invoice_dealer_unit/save_tagih" method="post" enctype="multipart/form-data">                        
            <input type="hidden" class="form-control" id="no_do" name="no_do">
            <?php if ($this->uri->segment(3)=='history'): ?>
              <input type="hidden" name="history" value="ya">
            <?php endif ?>
            <div class="box-body">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">No Invoice</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="no_invoice" placeholder="No Invoice" name="no_invoice" readonly>
                </div>
                <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="nama_dealer" placeholder="Nama Dealer" name="nama_dealer" readonly>
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Bank</label>
                <div class="col-sm-4">
                  <select class="form-control" name="bank" id="bank">
                    <option value="">- choose -</option>
                    <?php 
                    $bank = $this->m_admin->getSortCond("ms_bank","bank","ASC");
                    foreach ($bank->result() as $isi) {
                      echo "<option value='$isi->bank' bunga_bank=$isi->bunga_bank>$isi->bank</option>";
                    }
                    ?>
                  </select>
                </div>                
                <label for="inputEmail3" class="col-sm-2 control-label">Bunga Bank</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="bunga_bank" placeholder="Bunga Bank" name="bunga_bank" readonly>
                </div>
              </div>                 
              <div class="form-group">                
                <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Cair</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="tanggal2" placeholder="Tanggal Cair" name="tgl_cair" required>
                </div>
              </div>                               
            </div><!-- /.box-body -->
            <div class="box-footer">
              <button type="submit" name="s_process" value="simpan" class="btn btn-info">Simpan</button>                            
            </div><!-- /.box-footer -->
          </form>
      </div>      
    </div>
  </div>
</div>
<script type="text/javascript">
function input_tgl(id){    
  //alert(id);
  //Ajax Load data from ajax
  $.ajax({
      url : "<?php echo site_url('h1/invoice_dealer_unit/cari_data')?>",
      type:"POST",
      data:"id="+id,      
      success: function(msg)
      { 
          data=msg.split("|");
          $('[name="no_invoice"]').val(data[0]);          
          $('[name="nama_dealer"]').val(data[1]);                              
          $('[name="no_do"]').val(data[2]);                              
          $('[name="tgl_cair"]').val(data[3]);
          $('#modal_tagih').modal('show'); // show bootstrap modal when complete loaded
          $('.modal-title').text('Input Tanggal Tagih'); // Set title to Bootstrap modal title
          $('[name="bank"]').val('');
            $('[name="bunga_bank"]').val('');
          if (data[4]!='') {
            $('[name="bank"]').val(data[4]);
            $('[name="bunga_bank"]').val(data[5]);
          } 

      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          alert('Error get data from ajax');
      }
  });
}

$(function() { 
    $("#bank").change(function(){ 
        var element = $(this).find('option:selected'); 
        var bunga_bank = element.attr("bunga_bank"); 

        $('#bunga_bank').val(bunga_bank); 
    }); 
});


</script>

<script type="text/javascript">

var table;

$(document).ready(function() {
    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.        
        'scrollX':true,                  

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('h1/invoice_dealer_unit/ajax_list')?>",
            "type": "POST"
        },
    });
});


</script>
