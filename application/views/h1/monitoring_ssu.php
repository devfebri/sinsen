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
          <!--a href="h1/outstanding_bpkb/add">            
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
              <th width="5%">No</th>                          
              <th>No Mesin</th>              
              <th>No Rangka</th>
              <th>Nama Konsumen/Group</th>            
              <th>Tipe</th>              
              <th>Warna</th>
              <th>Kode Item</th>
              <th>Tgl SSU</th>
              <th>Tgl Penjualan</th>
              <th>Status</th>
              <th>Dealer</th>              
            </tr>
          </thead>
          <tbody>
          <?php 
          $no = 1;
          $tr = $this->db->query("SELECT * FROM tr_ssu_konsumen INNER JOIN tr_ssu_kendaraan ON tr_ssu_konsumen.id_list_appointment = tr_ssu_kendaraan.id_list_appointment");
          foreach ($tr->result() as $r) {
             $row = $this->db->query("SELECT tr_ssu_konsumen.*,ms_dealer.nama_dealer,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna,tr_scan_barcode.no_mesin,tr_scan_barcode.no_rangka,tr_scan_barcode.id_item FROM tr_ssu_konsumen
               LEFT JOIN tr_ssu_kendaraan ON tr_ssu_konsumen.id_list_appointment = tr_ssu_kendaraan.id_list_appointment
               LEFT JOIN ms_dealer ON tr_ssu_konsumen.id_dealer = ms_dealer.id_dealer   
               LEFT JOIN tr_scan_barcode ON tr_ssu_kendaraan.no_mesin = tr_scan_barcode.no_mesin                        
               LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
               LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan              
               WHERE tr_ssu_konsumen.id_list_appointment = '$r->id_list_appointment'")->row();
            echo "
            <tr>
              <td>$no</td>
              <td>$row->no_mesin</td>
              <td>$row->no_rangka</td>
              <td>$row->nama_pemilik</td>
              <td>$row->tipe_ahm</td>
              <td>$row->warna</td>
              <td>$row->id_item</td>            
              <td>$row->tgl_surat_jalan</td>
              <td>$row->tgl_surat_jalan</td>              
              <td>$row->status_ssu</td>              
              <td>$row->nama_dealer</td>              
            </tr>
            ";
            $no++;
          }
          ?>                       
          </tbody>
          <tfoot>
            <tr>
              <td colspan="8"></td>
              <td colspan="2">Total SSU</td>
              <td>0</td>
            </tr>
            <tr>
              <td colspan="8"></td>
              <td colspan="2">Total Penjualan</td>
              <td>0</td>
            </tr>
            <tr>
              <td colspan="8"></td>
              <td colspan="2">Sisa SSU</td>
              <td>0</td>
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
