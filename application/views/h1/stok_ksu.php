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
          $dt_ksu = $this->db->query("SELECT DISTINCT(tr_penerimaan_ksu.id_ksu) AS id_ksu,ms_ksu.ksu,SUM(tr_penerimaan_ksu.qty) AS qty FROM tr_penerimaan_ksu
            INNER JOIN ms_ksu ON tr_penerimaan_ksu.id_ksu = ms_ksu.id_ksu
            GROUP BY tr_penerimaan_ksu.id_ksu");
          //$dt_ksu = $this->db->query("SELECT * FROM tr_stok_ksu ORDER BY id_ksu ASC");
          foreach($dt_ksu->result() as $row) {  
            $cek_ksu = $this->db->query("SELECT SUM(qty) AS jum,id_ksu FROM tr_surat_jalan_ksu WHERE id_ksu = '$row->id_ksu'");
            if($cek_ksu->num_rows() > 0){
              $rt = $cek_ksu->row();
              $stok_amb = $rt->jum;
            }else{
              $stok_amb = 0;
            }

            $cek_ksu2 = $this->db->query("SELECT SUM(qty_terima) AS jum FROM tr_retur_dealer_detail_ksu WHERE id_ksu = '$row->id_ksu'");
            if($cek_ksu2->num_rows() > 0){
              $rt = $cek_ksu2->row();
              $stok_retur = $rt->jum;
            }else{
              $stok_retur = 0;
            }   


            $cek_ksu3 = $this->db->query("SELECT COUNT(id) AS jum FROM tr_rfs_pinjaman_detail_ksu 
              JOIN tr_rfs_pinjaman_detail ON tr_rfs_pinjaman_detail.no_mesin=tr_rfs_pinjaman_detail_ksu.no_mesin
              WHERE id_ksu = '$row->id_ksu'
              AND checked=1
              AND (terima='' OR terima IS NULL)
              ");
            if($cek_ksu3->num_rows() > 0){
              $rt = $cek_ksu3->row();
              $stok_pinjaman = $rt->jum;
            }else{
              $stok_pinjaman = 0;
            }             


            $stok_ak = ($row->qty - $stok_amb + $stok_retur)-$stok_pinjaman;
            echo "
            <tr>
              <td>$no</td>              
              <td>$row->id_ksu</td>
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
  <?php } ?>
</section>
</div>