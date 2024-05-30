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
    <li class="">Finance</li>    
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>
  <section class="content">


    <?php
    if($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/voucher_pengeluaran_bank/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a-->          
                    
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
              <th>No Invoice</th>                           
              <th>Tgl Jatuh Tempo</th>              
              <th>Nama Vendor</th>                            
              <th>Jumlah Uang</th>                                          
            </tr>
          </thead>
          <tbody>            
          <?php 
          $dt_rekap = $this->db->query("SELECT * FROM tr_rekap_tagihan INNER JOIN ms_vendor ON tr_rekap_tagihan.id_vendor = ms_vendor.id_vendor
            ORDER BY tr_rekap_tagihan.id_rekap_tagihan DESC");  
          foreach($dt_rekap->result() as $row) {                                         
          echo "          
            <tr>                             
              <td>$row->id_rekap_tagihan</td>                            
              <td></td>                                          
              <td>$row->vendor_name</td>                                                                                                    
              <td align='right'></td>                                                        
              ";                                                
          } 
          $dt_rekap2 = $this->db->query("SELECT * FROM tr_tagihan_lain ORDER BY id_tagihan_lain DESC");
          foreach($dt_rekap2->result() as $row2) {                                         
            $jum = $this->db->query("SELECT SUM(harga) AS jum FROM tr_tagihan_lain_detail WHERE id_tagihan_lain = '$row2->id_tagihan_lain'")->row();
          echo "          
            <tr>                             
              <td>$row2->id_tagihan_lain</td>                                          
              <td>-</td>                                          
              <td>$row2->kode_customer</td>                                                      
              <td align='right'>".mata_uang2($jum->jum)."</td>                                                        
              ";                                                
          }
          $dt_rekap3 = $this->db->query("SELECT * FROM tr_rekap_asuransi INNER JOIN ms_vendor ON tr_rekap_asuransi.id_vendor = ms_vendor.id_vendor
              ORDER BY tr_rekap_asuransi.id_rekap_asuransi DESC");  
          foreach($dt_rekap3->result() as $row3) {                                         
          echo "          
            <tr>                             
              <td>$row3->id_rekap_asuransi</td>                            
              <td></td>                                          
              <td>$row3->vendor_name</td>                                                        
              <td align='right'>".mata_uang2($row3->total)."</td>                                          
              ";                                                
          }
          $dt_rekap4 = $this->db->query("SELECT * FROM tr_monitor_tempo");  
          foreach($dt_rekap4->result() as $row4) {     
            $bulan = substr($row4->tgl_jatuh_tempo, 2,2);
            $tahun = substr($row4->tgl_jatuh_tempo, 4,4);
            $tgl = substr($row4->tgl_jatuh_tempo, 0,2);
            $tanggal = $tgl."-".$bulan."-".$tahun;                                                                         
          echo "          
            <tr>                             
              <td>$row4->no_rekap</td>                            
              <td>$tanggal</td>                                                        
              <td>AHM</td>                                                      
              <td align='right'>".mata_uang2($row4->total_pembayaran)."</td>                                          
              ";                                                
          }          
          $dt_rekap5 = $this->db->query("SELECT * FROM tr_claim_sales_program
              LEFT JOIN tr_sales_program on tr_claim_sales_program.id_program_md = tr_sales_program.id_program_md
              LEFT JOIN ms_dealer on tr_claim_sales_program.id_dealer=ms_dealer.id_dealer
           ORDER BY id_claim_sp DESC");
          foreach($dt_rekap5->result() as $row5) {                                         
            $jum = $this->db->query("SELECT IFNULL(SUM(nilai_potongan),0) AS jum FROM tr_claim_sales_program_detail 
              INNER JOIN tr_claim_dealer ON tr_claim_sales_program_detail.id_claim_dealer=tr_claim_dealer.id_claim
              WHERE id_claim_sp = '$row5->id_claim_sp' 
              AND (tr_claim_dealer.status='approved' OR tr_claim_dealer.status='ulang' OR tr_claim_dealer.status='ajukan')")->row();
            
            $cek = $this->db->query("SELECT sum(perlu_revisi) as sum FROM tr_claim_sales_program_detail WHERE id_claim_sp='$row5->id_claim_sp'");
            if ($cek->num_rows()>0) {                                     
              $cek = $cek->row();
              if ($cek->sum == 0) {
                echo "          
                  <tr>                             
                    <td>$row5->id_program_md</td>                                          
                    <td></td>                                          
                    <td>$row5->nama_dealer</td>                                                        
                    <td align='right'>".mata_uang2($jum->jum)."</td>                                                        
                    ";                  
              }
            }                              
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
