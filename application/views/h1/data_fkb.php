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
          <!--a href="h1/retur_unit/add">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <th>Kode Tipe</th>
              <th>Kode Warna</th>
              <th>No Faktur</th>
              <th>Tahun Produksi</th>
              <?php /* <th>Harga Di STNK</th> */ ?>
              <th>Nama Kapal</th>
              <th>No SIPB</th>
              <th>NO SL</th>
              <th>Tgl SL</th>
              <th>Model</th>
              <th>Isi Silinder</th>
              <th>Bahan Bakar</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          $dt_fkb = $this->db->query("SELECT * FROM tr_input_fkb INNER JOIN tr_input_fkb_detail ON tr_input_fkb.id_input_fkb=tr_input_fkb_detail.id_input_fkb
                    WHERE tr_input_fkb.status = 'close'");
          foreach($dt_fkb->result() as $row) {                                
            $amb = $this->m_admin->getByID("tr_fkb","no_surat",$row->no_surat)->row();                     
            $amc = $this->m_admin->getByID("tr_shipping_list","no_shipping_list",$amb->no_shipping_list)->row();
            $bulan = substr($amc->tgl_sl, 2,2);
            $tahun = substr($amc->tgl_sl, 4,4);
            $tgl = substr($amc->tgl_sl, 0,2);
            $tanggal = $tgl."-".$bulan."-".$tahun;
          echo "          
            <tr>
              <td>$no</td>              
              <td>$row->no_mesin</td>                                         
              <td>$amb->no_rangka</td>                                         
              <td>$amb->kode_tipe</td>                                         
              <td>$amb->kode_warna</td>                                         
              <td>$amb->nomor_faktur</td>                                         
              <td>$amb->tahun_produksi</td>";
              //<td>".mata_uang2($amb->harga_beli)."</td>                                         
              echo "<td>$amb->nama_kapal</td>                                         
              <td>$amb->no_sipb</td>                                         
              <td>$amb->no_shipping_list</td>                                         
              <td>$tanggal</td>                                         
              <td>$amb->modell</td>                                         
              <td>$amb->isi_silinder</td>                                         
              <td>$amb->bahan_bakar</td>";                                      
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
