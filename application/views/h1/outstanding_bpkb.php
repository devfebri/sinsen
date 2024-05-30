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
<body onload="kirim_data_pl()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Faktur STNK</li>
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
          <a href="h1/outstanding_bpkb/history">            
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
              <th>No BASTD</th>              
              <th>No Mesin</th>            
              <th>No Rangka</th>
              <th>Dealer</th>
              <th>Nama Konsumen</th>
              <th>Tgl Terima Biro Jasa</th>
              <th>Tgl Mohon Samsat</th>
              <th>No STNK</th>
              <th>No Polisi</th>
              <th>No BPKB</th>
              <th>Harga Notice Pajak</th>              
            </tr>
          </thead>
          <tbody> 
          <?php 
          $no=1;
          foreach ($dt_out->result() as $row) {
            $cek_dealer = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer=ms_dealer.id_dealer 
              WHERE no_bastd = '$row->no_bastd'");
            if($cek_dealer->num_rows() > 0){
              $r = $cek_dealer->row();
              $nama_dealer = $r->nama_dealer;
            }else{
              $nama_dealer = "";
            }
            $cek_tgl = $this->db->query("SELECT * FROM tr_konfirmasi_map_detail INNER JOIN tr_konfirmasi_map 
              ON tr_konfirmasi_map.id_generate = tr_konfirmasi_map_detail.id_generate WHERE tr_konfirmasi_map_detail.no_mesin='$row->no_mesin'");
            if($cek_tgl->num_rows() > 0){
              $t = $cek_tgl->row();
              $tgl = $t->tgl_terima;
            }else{
              $tgl = "";
            }
            $samsat = $this->m_admin->getByID("tr_pengajuan_bbn_detail","no_mesin",$row->no_mesin)->row();
            if(isset($samsat->no_bastd)){
              $bastd = $samsat->no_bastd;
              $no_mesin = $row->no_mesin;
              $this->db->query("UPDATE tr_terima_bj SET no_bastd = '$bastd' WHERE no_mesin = '$no_mesin'");
            }
            echo "
            <tr>
              <td>$no</td>
              <td>$row->no_bastd</td>
              <td>$row->no_mesin</td>
              <td>$row->no_rangka</td>
              <td>$nama_dealer</td>
              <td>$row->nama_konsumen</td>
              <td>$tgl</td>
              <td>$samsat->tgl_mohon_samsat</td>
              <td>$row->no_stnk</td>
              <td>$row->no_plat</td>
              <td>$row->no_bpkb</td>
              <td>".mata_uang2($row->notice_pajak)."</td>
            </tr>            
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
    elseif($set=="history"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/outstanding_bpkb/">            
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                          
              <th>No BASTD</th>              
              <th>No Mesin</th>            
              <th>No Rangka</th>
              <th>Dealer</th>
              <th>Nama Konsumen</th>
              <th>Tgl Terima Biro Jasa</th>
              <th>Tgl Mohon Samsat</th>
              <th>No STNK</th>
              <th>No Polisi</th>
              <th>No BPKB</th>
              <th>Harga Notice Pajak</th>              
            </tr>
          </thead>
          <tbody> 
          <?php 
          $no=1;
          foreach ($dt_out->result() as $row) {
            $cek_dealer = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN ms_dealer ON tr_faktur_stnk.id_dealer=ms_dealer.id_dealer 
              WHERE no_bastd = '$row->no_bastd'");
            if($cek_dealer->num_rows() > 0){
              $r = $cek_dealer->row();
              $nama_dealer = $r->nama_dealer;
            }else{
              $nama_dealer = "";
            }
            $cek_tgl = $this->db->query("SELECT * FROM tr_konfirmasi_map_detail INNER JOIN tr_konfirmasi_map 
              ON tr_konfirmasi_map.id_generate = tr_konfirmasi_map_detail.id_generate WHERE tr_konfirmasi_map_detail.no_mesin='$row->no_mesin'");
            if($cek_tgl->num_rows() > 0){
              $t = $cek_tgl->row();
              $tgl = $t->tgl_terima;
            }else{
              $tgl = "";
            }
            $samsat = $this->m_admin->getByID("tr_pengajuan_bbn_detail","no_mesin",$row->no_mesin)->row();
            echo "
            <tr>
              <td>$no</td>
              <td>$row->no_bastd</td>
              <td>$row->no_mesin</td>
              <td>$row->no_rangka</td>
              <td>$nama_dealer</td>
              <td>$row->nama_konsumen</td>
              <td>$tgl</td>
              <td>$samsat->tgl_mohon_samsat</td>
              <td>$row->no_stnk</td>
              <td>$row->no_plat</td>
              <td>$row->no_bpkb</td>
              <td>".mata_uang2($row->notice_pajak)."</td>
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
