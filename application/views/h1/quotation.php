<?php 
function bln(){
  $bulan=$bl=$month=date("m");
  switch($bulan)
  {
    case"1":$bulan="Januari"; break;
    case"2":$bulan="Februari"; break;
    case"3":$bulan="Maret"; break;
    case"4":$bulan="April"; break;
    case"5":$bulan="Mei"; break;
    case"6":$bulan="Juni"; break;
    case"7":$bulan="Juli"; break;
    case"8":$bulan="Agustus"; break;
    case"9":$bulan="September"; break;
    case"10":$bulan="Oktober"; break;
    case"11":$bulan="November"; break;
    case"12":$bulan="Desember"; break;
  }
  $bln = $bulan;
  return $bln;
}
?>
<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 40px;  
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
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Repair</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/quotation">
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
            <form class="form-horizontal" action="h1/quotation/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <input type="hidden" name="no_quotation" id="no_quotation">
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" name="bulan" id="bulan">
                      <option value="1">Januari</option>
                      <option value="2">Februari</option>
                      <option value="3">Maret</option>
                      <option value="4">April</option>
                      <option value="5">Mei</option>
                      <option value="6">Juni</option>
                      <option value="7">Juli</option>
                      <option value="8">Agustus</option>
                      <option value="9">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-2">
                    <select class="form-control select2" name="tahun" id="tahun">
                      <?php 
                      $y = date("Y");
                      for ($i=$y - 10; $i <= $y + 10; $i++) { 
                        echo "<option>$i</option>";
                      }
                      ?>       
                    </select>
                  </div>                  
                  <div class="col-sm-2">
                    <button type="button" onclick="simpan_bulan()" class="btn btn-flat btn-primary btn-sm"><i class='fa fa-plus'></i> Add</button>                    
                  </div>
                </div>
                <div class="form-group">                                                      
                  <div class="col-sm-2"></div>                  
                  <div class="col-sm-6">
                    <span id="tampil_bulan"></span>
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan untuk Dipotong</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" name="id_tipe_kendaraan" id="id_tipe_kendaraan">
                      <option value="">- choose -</option>
                      <?php 
                      $dealer = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE active='1' ORDER BY tipe_ahm ASC");
                      foreach ($dealer->result() as $isi) {
                        echo "<option value='$isi->id_tipe_kendaraan'>$isi->id_tipe_kendaraan | $isi->tipe_ahm</option>";
                      }
                      ?>
                    </select>
                  </div>                                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Nilai Pemotongan</label>
                  <div class="col-sm-2">
                    <input type="text" name="nilai" id="tanpa-rupiah" onpaste="return false" onkeypress="return number_only(event)"  class="form-control">
                  </div>
                  <div class="col-sm-2">
                    <button type="button" onclick="simpan_tipe()" class="btn btn-flat btn-primary btn-sm"><i class='fa fa-plus'></i> Add</button>                    
                  </div>                                    
                </div>
                <div class="form-group">                                                      
                  <div class="col-sm-2"></div>                  
                  <div class="col-sm-6">
                    <span id="tampil_tipe"></span>
                  </div>
                </div>

<!-- 
                <div class="form-group">                                                      
                  <label for="inputEmail3" class="col-sm-2 control-label">Sisa Total Hutang MD</label>
                  <div class="col-sm-3">
                    <input type="text" name="total_hutang_real" id="total_hutang_real" placeholder="Sisa Total Hutang MD" readonly class="form-control">
                    <input type="hidden" id="total_hutang" name="total_hutang">
                  </div>                                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Sisa Total Piutang MD</label>
                  <div class="col-sm-3">
                    <input type="text" id="total_piutang_real" name="total_piutang_real" placeholder="Sisa Total Piutang MD" readonly class="form-control">
                    <input type="hidden" id="total_piutang" name="total_piutang">
                  </div>                  
                </div>   -->
                

                <button type="reset" class="btn btn-warning btn-flat btn-block" disabled>Detail Dealer</button>                                             
                <br>

                <table id="example4" class="table table-bordered table-hovered" width="100%">
                  <thead>
                    <tr>
                      <th width="1%">No</th>
                      <th width='10%'>Kode Dealer</th>
                      <th width='30%'>Nama Dealer</th>
                      <th width='10%'>Hutang MD Ke Dealer</th>
                      <th width='10%'>Hutang Dealer Ke MD</th>
                    </tr>                  
                  </thead>
                  <tbody>
                    <?php 
                    $no=1;
                    $dealer = $this->db->query("SELECT * FROM ms_dealer WHERE active = 1 AND h1=1");
                    foreach ($dealer->result() as $row) {
                      $tot_claim=0;
                      // $cek  = $this->db->query("SELECT SUM(tr_claim_sales_program_detail.nilai_potongan) as jum FROM tr_claim_sales_program INNER JOIN tr_claim_sales_program_detail ON tr_claim_sales_program.id_claim_sp = tr_claim_sales_program_detail.id_claim_sp
                      //     INNER JOIN tr_sales_program_tipe ON tr_claim_sales_program.id_program_md = tr_sales_program_tipe.id_program_md
                      //     INNER JOIN tr_claim_dealer ON tr_claim_sales_program_detail.id_claim_dealer=tr_claim_dealer.id_claim
                      //     WHERE tr_claim_sales_program_detail.perlu_revisi = 0 AND tr_claim_sales_program.id_dealer = '$row->id_dealer'
                      //     AND tr_sales_program_tipe.jenis_bayar_dibelakang = 'Quotation'
                      //     AND (tr_claim_dealer.status='approved' OR tr_claim_dealer.status='ulang' OR tr_claim_dealer.status='ajukan')
                      //     ");
                      // if($cek->num_rows() > 0){
                      //   $t = $cek->row();
                      //   $total_hutang = $t->jum;
                      // }else{
                      //   $total_hutang = 0;
                      // }
                       

                      // $cek3  = $this->db->query("SELECT SUM(tr_do_po_detail.disc * tr_do_po_detail.qty_do) AS jum FROM tr_do_po_detail INNER JOIN tr_do_po ON tr_do_po_detail.no_do = tr_do_po.no_do
                      //     WHERE tr_do_po.id_dealer = '$row->id_dealer'");
                      // if($cek3->num_rows() > 0){
                      //   $t = $cek3->row();
                      //   $total_piutang2 = $t->jum;
                      // }else{
                      //   $total_piutang2 = 0;
                      // }

                      // $cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan * tr_invoice_dealer_detail.qty_do) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po ON tr_invoice_dealer_detail.no_do = tr_do_po.no_do
                      //     WHERE tr_do_po.id_dealer = '$row->id_dealer'");
                      // if($cek2->num_rows() > 0){
                      //   $t = $cek2->row();
                      //   $total_piutang = $t->jum + $total_piutang2;
                      // }else{
                      //   $total_piutang = 0 + $total_piutang2;
                      // }

                      // if($total_hutang >= $total_piutang){
                      //   $hasil = $total_hutang - $total_piutang;
                      //   $hasil2 = 0;
                      // }else{
                      //   $hasil = 0;
                      //   $hasil2 = $total_piutang - $total_hutang;
                      // }
                  $do_po = $this->db->query("SELECT tr_do_po_detail.*,LEFT(tgl_do,7) as th_bln_do,
                      
                      LEFT(id_item,3)as id_tipe_kendaraan, tr_do_po.tgl_do,ms_dealer.nama_dealer FROM tr_do_po_detail 
                      JOIN tr_do_po ON tr_do_po_detail.no_do=tr_do_po.no_do 
                      JOIN ms_dealer ON ms_dealer.id_dealer=tr_do_po.id_dealer
                      AND disc>0
                      AND tr_do_po.status='approved'
                      AND tr_do_po.id_dealer=$row->id_dealer
                      ");
                    $jum_dopo=0;
                    foreach ($do_po->result() as $do) {
                      $jum_dopo += $do->qty_do * $do->disc;
                    }

                    $inv= $this->db->query("SELECT tr_invoice_dealer_detail.*,LEFT(tgl_faktur,7) as th_bln_do,(tr_invoice_dealer_detail.potongan/qty_do) as disc,
                      
                      LEFT(id_item,3)as id_tipe_kendaraan, tr_invoice_dealer.tgl_faktur,ms_dealer.nama_dealer FROM tr_invoice_dealer_detail 
                      JOIN tr_invoice_dealer ON tr_invoice_dealer_detail.no_do=tr_invoice_dealer.no_do 
                      JOIN tr_do_po ON tr_invoice_dealer.no_do=tr_do_po.no_do
                      JOIN ms_dealer ON ms_dealer.id_dealer=tr_do_po.id_dealer
    
                      WHERE potongan>0
                      AND (tr_invoice_dealer.status_invoice='approved' OR tr_invoice_dealer.status_invoice='printable')
                      AND tr_do_po.id_dealer=$row->id_dealer
                      ");
                    $jum_inv=0;
                    foreach ($inv->result() as $v) {
                      $jum_inv+=$v->qty_do*$v->disc;
                    }
                    $hutang_d_md =$jum_dopo+$jum_inv;

                       $dt_rekap5 = $this->db->query("SELECT *,LEFT(tr_claim_sales_program.created_at,10)as tgl FROM tr_claim_sales_program
                        LEFT JOIN tr_sales_program on tr_claim_sales_program.id_program_md = tr_sales_program.id_program_md
                        LEFT JOIN ms_dealer on tr_claim_sales_program.id_dealer=ms_dealer.id_dealer
                        WHERE ms_dealer.id_dealer=$row->id_dealer
                     ORDER BY id_claim_sp DESC");
                    foreach($dt_rekap5->result() as $row5) {    
                      // $tot_claim =0;
                      $cek = $this->db->query("SELECT sum(perlu_revisi) as sum FROM tr_claim_sales_program_detail WHERE id_claim_sp='$row5->id_claim_sp'");

                      $jum = $this->db->query("SELECT IFNULL(SUM(nilai_potongan),0) AS jum FROM tr_claim_sales_program_detail 
                        INNER JOIN tr_claim_dealer ON tr_claim_sales_program_detail.id_claim_dealer=tr_claim_dealer.id_claim
                        WHERE id_claim_sp = '$row5->id_claim_sp' 
                        AND (tr_claim_dealer.status='approved' OR tr_claim_dealer.status='ulang' OR tr_claim_dealer.status='ajukan')")->row();
                      if ($cek->num_rows()>0) {
                        $total = $this->m_admin->cekVoucherBank($row5->id_claim_sp,$jum->jum);        
                        $cek = $cek->row();
                        if ($cek->sum == 0) {
                          if ($total>0) {
                            $tot_claim+=$total;
                          }                                               
                        }
                      }
                    }
                    if ($hutang_d_md>$tot_claim) {
                      $hutang_d_md = $hutang_d_md-$tot_claim;
                      $tot_claim = 0;
                    }
                    if ($hutang_d_md<$tot_claim) {
                      $tot_claim = $tot_claim - $hutang_d_md;
                      $hutang_d_md =0;
                    }
                    if ($hutang_d_md==$tot_claim) {
                      $hutang_d_md=0;
                      $tot_claim=0;
                    }

                          echo "
                      <tr>
                        <td>$no</td>
                        <td>$row->kode_dealer_md</td>
                        <td>$row->nama_dealer</td> 
                        <td>".mata_uang2($tot_claim)."</td> 
                        <td>".mata_uang2($hutang_d_md)."</td> 
                        ";
                        // <td>".mata_uang2($hasil)."</td>
                        // <td>".mata_uang2($hasil2)."</td>
                      echo"</tr>
                      ";
                      $no++;
                    }
                    ?>
                  </tbody>
                </table>  

                <br>

                
                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>                
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Reset</button>                                
                </div>
              </div><!-- /.box-footer -->
              
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="history"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/quotation">
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
            <form class="form-horizontal" action="h1/quotation/history?id=<?= $row->no_quotation ?>" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
              <!--   <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label"></label>
                  
                </div> -->
               <!--  <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2">
                      <option>- choose -</option>
                    </select>
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2">
                      <option>- choose -</option>
                    </select>
                  </div>                  
                </div>    -->               
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-3">
                    <select class="form-control select2" name="id_dealer">
                      <?php if ($dealer->num_rows()>0): ?>
                        <option value="">- choose -</option>
                        <?php foreach ($dealer->result() as $dl): 
                            echo $select = isset($id_dealer)?$dl->id_dealer==$id_dealer?'selected':'':'';
                        ?>
                          <option value="<?= $dl->id_dealer ?>" <?= $select ?> ><?= $dl->nama_dealer ?></option>
                        <?php endforeach ?>
                      <?php endif ?>
                    </select>
                  </div>       
                  <div class="col-sm-2">                    
                    <button type="submit" class="btn btn-flat btn-primary btn-sm">Filter</button>
                  </div>                             
                </div>
                
                <table class="table table-bordered table-hovered myTable1" width="100%">
                  <thead>
                    <th width='10%'>Bulan</th>
                    <th width='10%'>Nama Dealer</th>
                    <th width='10%'>Qty</th>
                    <th width='10%'>Tipe Kendaraan</th>
                    <th width='10%'>Tgl Pemotongan</th> 
                    <th width='10%'>Nilai Pemotongan</th> 
                    <th width='10%'>Total Pemotongan</th> 
                  </thead>            
                  <tbody>
                    <?php foreach ($detail->result() as $dt):
                        $th_bln_quo = $dt->tahun.'-'.sprintf("%02d", $dt->bulan);
                        if ($th_bln_quo==$dt->th_bln_do) {
                          $tot_potongan = $dt->qty_do * $dt->disc;
                    ?>
                      <tr>
                        <td><?= getbln($dt->bulan) ?></td>
                        <td><?= $dt->nama_dealer ?></td>
                        <td><?= $dt->qty_do ?></td>
                        <td><?= $dt->id_tipe_kendaraan ?></td>
                        <td><?= $dt->tgl_do ?></td>
                        <td align="right"><?= mata_uang_rp($dt->disc) ?></td>
                        <td align="right"><?= mata_uang_rp($tot_potongan) ?></td>
                      </tr>
                    <?php } endforeach ?>

                    <?php foreach ($detail_inv->result() as $dt):
                        $th_bln_quo = $dt->tahun.'-'.sprintf("%02d", $dt->bulan);
                        if ($th_bln_quo==$dt->th_bln_do) {
                          $tot_potongan = $dt->qty_do * $dt->disc;
                    ?>
                      <tr>
                        <td><?= getbln($dt->bulan) ?></td>
                        <td><?= $dt->nama_dealer ?></td>
                        <td><?= $dt->qty_do ?></td>
                        <td><?= $dt->id_tipe_kendaraan ?></td>
                        <td><?= $dt->tgl_faktur ?></td>
                        <td align="right"><?= mata_uang_rp($dt->disc) ?></td>
                        <td align="right"><?= mata_uang_rp($tot_potongan) ?></td>
                      </tr>
                    <?php } endforeach ?>
                  </tbody>      
                </table>  

                <br>

                
                
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
          <a href="h1/quotation/add">            
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>ID Quotation</th>            
              <th>Bulan</th>             
              <th>Tipe Kendaraan</th> 
              <th>Total Nilai Pemotongan</th>              
              <th width="5%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1;           
          foreach($dt_quo->result() as $row) {                                         
          echo "          
            <tr>
              <td>$no</td>                           
              <td>$row->no_quotation</td>                           
              <td>
                
                  $row->bulan
                
              </td>              
              <td>$row->tipe_kendaraan</td>
              <td align='right'>".mata_uang2($row->nilai)."</td>                            
              <td>
                <a href='h1/quotation/history?id=$row->no_quotation' type='button' class='btn btn-flat btn-warning btn-xs'><i class='fa fa-time'></i> History Quotation</a>
              </td>";                                      
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
function auto(){
  var no = 1;
  $.ajax({
      url : "<?php echo site_url('h1/quotation/cari_id')?>",
      type:"POST",
      data:"no="+no,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#no_quotation").val(data[0]);        
        $("#total_hutang").val(data[1]);        
        $("#total_hutang_real").val(convertNoRupiah(data[1]));        
        $("#total_piutang").val(data[2]);        
        $("#total_piutang_real").val(convertNoRupiah(data[2]));        
        kirim_bulan();
        kirim_tipe();
      }        
  })
}
function hide_data(){
    $("#tampil_bulan").hide();
}
function kirim_bulan(){    
  $("#tampil_bulan").show();
  var no_quotation = document.getElementById("no_quotation").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_quotation="+no_quotation;                           
     xhr.open("POST", "h1/quotation/t_quot_bulan", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_bulan").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function simpan_bulan(){  
  var no_quotation      = document.getElementById("no_quotation").value;           
  var bulan             = $("#bulan").val();                         
  var tahun             = $("#tahun").val();                         
  //alert(id_po);
  if (bulan == "" || no_quotation == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/quotation/save_bulan')?>",
          type:"POST",
          data:"no_quotation="+no_quotation+"&bulan="+bulan+"&tahun="+tahun,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                  kirim_bulan();
                  kosong();
              }else{
                  alert(data[0]);
                  $("#bulan").val("");
                  kosong();
              }                
          }
      })        
  }
}

function kosong(args){  
  $("#id_tipe_kendaraan").val("");     
  $("#tanpa-rupiah").val("");     
}
function hapus_bulan(a){ 
    var id_quot_bulan  = a;       
    $.ajax({
        url : "<?php echo site_url('h1/quotation/delete_bulan')?>",
        type:"POST",
        data:"id_quot_bulan="+id_quot_bulan,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_bulan();
            }
        }
    })
}

function kirim_tipe(){    
  $("#tampil_tipe").show();
  var no_quotation = document.getElementById("no_quotation").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "no_quotation="+no_quotation;                           
     xhr.open("POST", "h1/quotation/t_quot_tipe", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_tipe").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function simpan_tipe(){  
  var no_quotation      = document.getElementById("no_quotation").value;           
  var id_tipe_kendaraan = $("#id_tipe_kendaraan").val();                         
  var nilai             = $("#tanpa-rupiah").val();                         
  //alert(id_po);
  if (id_tipe_kendaraan == "" || nilai == "" || no_quotation == "") {    
      alert("Isikan data dengan lengkap...!");
      return false;
  }else{
      $.ajax({
          url : "<?php echo site_url('h1/quotation/save_tipe')?>",
          type:"POST",
          data:"no_quotation="+no_quotation+"&id_tipe_kendaraan="+id_tipe_kendaraan+"&nilai="+nilai,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                  kirim_tipe();
                  kosong();
              }else{
                  alert(data[0]);                  
                  kosong();
              }                
          }
      })        
  }
}

function hapus_tipe(a){ 
    var id_quot_tipe  = a;       
    $.ajax({
        url : "<?php echo site_url('h1/quotation/delete_tipe')?>",
        type:"POST",
        data:"id_quot_tipe="+id_quot_tipe,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              kirim_tipe();
            }
        }
    })
}
</script>