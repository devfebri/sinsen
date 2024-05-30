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
<body onload="auto()">
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
    <li class="">Kontrol Unit</li>
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
          <!--a href="h1/kekurangan_ksu/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>              
              <th>ID KSU</th>              
              <th>Nama KSU</th>              
              <th width="10%">Qty</th>              
            </tr>            
          </thead>
          <tbody>            
          <?php 
          $no=1;
          $t_rfs=0;$t_nrfs=0;$t_pinj=0;$t_book=0;$tot=0; 
          $id_dealer = $this->m_admin->cari_dealer();
          $dt_ksu = $this->db->query("SELECT DISTINCT(tr_penerimaan_ksu_dealer.id_ksu) AS id,ms_ksu.ksu,SUM(tr_penerimaan_ksu_dealer.qty_terima) AS jum FROM tr_penerimaan_ksu_dealer
            INNER JOIN ms_ksu ON tr_penerimaan_ksu_dealer.id_ksu = ms_ksu.id_ksu
            INNER JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer = tr_penerimaan_ksu_dealer.id_penerimaan_unit_dealer
            WHERE tr_penerimaan_unit_dealer.id_dealer = '$id_dealer'
            GROUP BY tr_penerimaan_ksu_dealer.id_ksu");

          $dt_monitoring_outstanding_ksu =$this->db->query("SELECT DISTINCT(tr_surat_jalan_ksu.no_surat_jalan) AS no_sj,tr_surat_jalan_ksu_pl.no_pl_ksu,tr_surat_jalan_ksu_pl.no_pl_ksu as no_pl_ksu_alias, 
                      tr_surat_jalan_ksu_pl.tgl_pl_ksu,tr_surat_jalan_ksu.no_do,tr_do_po.tgl_do,ms_dealer.nama_dealer,(select status_mon from tr_mon_ksu where no_pl_ksu=no_pl_ksu_alias group by no_pl_ksu) as status_mon
                      FROM tr_surat_jalan_ksu_pl 
                      INNER JOIN tr_surat_jalan_ksu ON tr_surat_jalan_ksu.no_surat_jalan = tr_surat_jalan_ksu_pl.no_surat_jalan                      
                      INNER JOIN tr_do_po ON tr_surat_jalan_ksu.no_do = tr_do_po.no_do
                      INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
                      WHERE tr_surat_jalan_ksu.qty < tr_surat_jalan_ksu.qty_do  
                      AND tr_do_po.id_dealer = '$id_dealer'
                      ");    

          foreach ($dt_monitoring_outstanding_ksu->result() as $key => $mon) {
            if ($mon->status_mon=='diterima') {
              //$no_pl_ksu=array($key=>$mon->no_pl_ksu);
            	$no_pl_ksu[$key]=$mon->no_pl_ksu;
            }
          }
          
          if (isset($no_pl_ksu)) {
            $no_pl_ksu = implode(',', $no_pl_ksu);
          }

          foreach($dt_ksu->result() as $row) {
            if (isset($no_pl_ksu)) {
              $sql = "SELECT sum(qty_konfirmasi) as jml FROM tr_mon_ksu_detail WHERE no_pl_ksu IN($no_pl_ksu) AND id_ksu = '$row->id'";
              $cek_mon = $this->db->query($sql);        

              if ($cek_mon->num_rows() > 0) {
                $stok_mon = $cek_mon->row()->jml;
              }else{
                $stok_mon = 0;
              }
            }else{
              $stok_mon=0;
            }
            // $cek_ksu = $this->db->query("SELECT SUM(qty) AS jum,id_ksu FROM tr_surat_jalan_ksu WHERE id_ksu = '$row->id'");
            // if($cek_ksu->num_rows() > 0){
            //   $rt = $cek_ksu->row();
            //   $stok_amb = $rt->jum;
            // }else{
            //   $stok_amb = 0;
            // }             
            $cek_ksu_terjual = $this->db->query("SELECT count(id_ksu) as stok FROM tr_sales_order_ksu WHERE id_dealer='$id_dealer' AND id_ksu='$row->id' ")->row()->stok;

            $stok_ak = ($row->jum + $stok_mon)-$cek_ksu_terjual  ;
            echo "
            <tr>
              <td>$no</td>              
              <td>$row->id</td>
              <td>$row->ksu</td>
              <td>$stok_ak</td>                            
            </tr>
            ";
          $no++;        
          }
          ?>
          </tbody>          
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  <?php } else if($set=="stock_oem"){
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
        <table id="example1" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>              
              <th>ID KSU (Battery)</th>              
              <th>Nama KSU</th>              
              <th width="10%">Qty</th>              
              <th width="10%">Action</th>              
            </tr>            
          </thead>
          <tbody>    
          <?php 
          $no=1; 
          foreach($battery as $row) {?>       
            <tr>
              <td><?=$no?></td>
              <td><?=$row->part_jd?></td>
              <td><?=$row->part_desc?></td>
              <td><?=$row->qty?></td>
              <td>
                <a href="/stock_ksu/stock_oem_detail?id=<?=$row->part_id?>" class="btn btn-md">View</a>
              </td>
            </tr>
          <?php
          $no++;
          }
          ?>       
          </tbody>          
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  <?php } ?>
</section>
</div>