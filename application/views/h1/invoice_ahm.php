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
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
</style>
<base href="<?php echo base_url(); ?>" />
<?php 
if(isset($_GET['id'])){
?>
<body onload="cek_jenis()">
<?php }else{ ?>
<body onload="auto()">
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
    <li class="">Pembelian</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php 
    if($set=="view"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/po/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a-->          
          
          <!--a href="h1/invoice/upload">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-upload"></i> Upload</button>
          </a-->          
          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
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
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No Faktur</th>              
              <th>Tgl Faktur</th>              
              <th>Tgl Pemb. Pokok</th>              
              <th>Tgl Pemb. PPN</th>
              <th>Tgl Pemb. PPH</th>
              <th>DPP</th>
              <th>PPN</th>
              <th>PPh</th>
              <th>Total Bayar</th>
              <th>Status</th>              
              <th>Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1;$total=0; 
          foreach($dt_invoice->result() as $row) {
            $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');
            $get_amount = $this->db->query("SELECT qty, harga, no_sipb, no_sl,ppn,pph FROM tr_invoice WHERE no_faktur ='$row->no_faktur' ");
            $amount = 0;
            $kosong = 0;

            foreach ($get_amount->result() as $am) {
              $amount = $amount + $am->harga;
              
              $cek_sw = $this->db->query("SELECT no_sipb FROM tr_sipb WHERE no_sipb = '$am->no_sipb'");
              if($cek_sw->num_rows() == 0){
                $kosong++;
              }

            }
            
            $bulan = substr($row->tgl_faktur, 2,2);
            $tahun = substr($row->tgl_faktur, 4,4);
            $tgl = substr($row->tgl_faktur, 0,2);
            $tanggal = $tgl."-".$bulan."-".$tahun;
            $bulan1 = substr($row->tgl_pokok, 2,2);
            $tahun1 = substr($row->tgl_pokok, 4,4);
            $tgl1 = substr($row->tgl_pokok, 0,2);
            $tanggal1 = $tgl1."-".$bulan1."-".$tahun1;
            $bulan2 = substr($row->tgl_ppn, 2,2);
            $tahun2 = substr($row->tgl_ppn, 4,4);
            $tgl2 = substr($row->tgl_ppn, 0,2);
            $tanggal2 = $tgl2."-".$bulan2."-".$tahun2;
            $bulan3 = substr($row->tgl_pph, 2,2);
            $tahun3 = substr($row->tgl_pph, 4,4);
            $tgl3 = substr($row->tgl_pph, 0,2);
            $tanggal3 = $tgl3."-".$bulan3."-".$tahun3;
          

            if($kosong == 0){
              if($row->status == 'waiting'){
                $kosong_sl=0;
                foreach ($get_amount->result() AS $isi) {
                  //$cek_sw = $this->db->query("SELECT * FROM tr_sipb WHERE no_sipb = '$isi->no_sipb'");
                  $cek_sl = $this->db->query("SELECT no_shipping_list FROM tr_shipping_list WHERE no_shipping_list = '$isi->no_sl'");
                  if($cek_sl->num_rows() > 0){
                    $kosong_sl++;
                  }
                }
                if($kosong_sl > 0){
                  $rt = "<a href='h1/invoice_ahm/approve?id=$row->no_faktur'>
                        <button $approval class='btn btn-primary btn-flat btn-xs'><i class='fa fa-check'></i> Approve</button>
                      </a>";                        
                }else{
                  $rt = "";
                }
                $rw = "<a href='h1/invoice_ahm/reject?id=$row->no_faktur'>
                      <button $approval class='btn btn-danger btn-flat btn-xs'><i class='fa fa-close'></i> Reject</button>
                    </a>";
              }else{
                $rt = "";
                $rw = "";
              }
              $status = $row->status;
            }else{
              $rt = "";
              $rw = "";
              $status = "SIPB not Completed";
            }

            // $dt_invoice = $this->db->query("SELECT ppn,pph FROM tr_invoice WHERE tr_invoice.no_faktur = '$row->no_faktur'");     
            $ppn=0;$pph=0;
            foreach ($get_amount->result() as $isi) {
              $ppn = $ppn + $isi->ppn;            
              $pph = $pph + $isi->pph;            
            }
            
            $amount_m = mata_uang2($amount);
            $pph_m = mata_uang2($pph);
            $ppn_m = mata_uang2($ppn);
            $total = $ppn + $pph + $amount;
            $total_m = mata_uang2($total);
            echo "          
              <tr>
                <td>$no</td>
                <td>
                  <a title='detail' href='h1/invoice_ahm/detail?id=$row->no_faktur'>
                    $row->no_faktur
                  </a>
                </td>
                <td>$tanggal</td>
                <td>$tanggal1</td>              
                <td>$tanggal2</td>
                <td>$tanggal3</td>
                <td align='right'>$amount_m</td>
                <td align='right'>$ppn_m</td>
                <td align='right'>$pph_m</td>
                <td align='right'>$total_m</td>
                <td>$status</td>
                <td>";
                  echo $rt.$rw;
                echo "                
                </td>
              </tr>";                        
            $no++;
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php 
    }elseif($set=="detail"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/invoice_ahm">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>          
          
          <!--a href="h1/invoice/upload">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-upload"></i> Upload</button>
          </a-->          
          <!--button class="btn bg-maroon btn-flat margin" onclick="bulk_delete()"><i class="fa fa-trash"></i> Bulk Delete</button-->                  
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
              <th>Kode Tipe</th>              
              <th>Kode Warna</th>      
              <th>Deskripsi Tipe</th>                     
              <th>Qty</th>              
              <th>No SIPB</th>
              <th>No SL</th>
              <th>Disc Quo.</th>              
              <th>Disc Type Cash</th>
              <th>Disc Other</th>
              <th>DPP</th>
              <th>PPN</th>
              <th>PPH</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1;$t_harga=0;$t_ppn=0;$t_pph=0;$t_tot=0;$d1=0;$d2=0;$d3=0; 
          foreach($dt_invoice->result() as $row) {      
            $cek = $this->m_admin->getByID("tr_sipb","no_sipb",$row->no_sipb);
            if($cek->num_rows() > 0){
              $bg = "";
            }else{
              $bg = "style='background-color:red;'";
            }              
          echo "          
            <tr $bg>
              <td>$row->id_tipe_kendaraan</td>
              <td>$row->id_warna</td> 
              <td>$row->deskripsi_ahm</td>             
              <td>$row->qty</td>
              <td>$row->no_sipb</td>
              <td>$row->no_sl</td>
              <td>".mata_uang2($row->disc_quo)."</td>
              <td>".mata_uang2($row->disc_type)."</td>
              <td>".mata_uang2($row->disc_other)."</td>
              <td>".mata_uang2($row->harga)."</td>
              <td>".mata_uang2($row->ppn)."</td>
              <td>".mata_uang2($row->pph)."</td>
              <td>".mata_uang2($tot = $row->pph + $row->ppn + $row->harga)."</td>
            </tr>";                     
            $t_harga += $row->harga;   
            $t_ppn += $row->ppn;   
            $t_pph += $row->pph;   
            $t_tot += $tot;   
            $d1 += $row->disc_quo;   
            $d2 += $row->disc_type;   
            $d3 += $row->disc_other;   
          }
          ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="6">Grand Total</td>
              <td><?php echo mata_uang2($d1); ?></td>              
              <td><?php echo mata_uang2($d2); ?></td>              
              <td><?php echo mata_uang2($d3); ?></td>              
              <td><?php echo mata_uang2($t_harga); ?></td>              
              <td><?php echo mata_uang2($t_ppn); ?></td>              
              <td><?php echo mata_uang2($t_pph); ?></td>              
              <td><?php echo mata_uang2($t_tot); ?></td>              
            </tr>
          </tfoot>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div>
