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
    if($set=="detail"){
      $row = $dt_mon->row();
      $c_stnk = $this->m_admin->getByID("tr_terima_bj","no_mesin",$row->no_mesin);
      if($c_stnk->num_rows() > 0){
        $rr = $c_stnk->row();
        $no_stnk = $rr->no_stnk;
        $no_plat = $rr->no_plat;
        $no_bpkb = $rr->no_bpkb;
      }else{
        $no_stnk = "";
        $no_plat = "";
        $no_bpkb = "";
      }
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/monitoring_samsat">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
            <form class="form-horizontal" action="h1/penggantian_fkb/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No BASTD</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" value="<?php echo $row->no_bastd ?>" readonly placeholder="No BASTD" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Konsumen</label>
                  <div class="col-sm-4"> 
                    <input type="text" name="periode_awal" value="<?php echo $row->nama_konsumen ?>" readonly placeholder="Konsumen" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" value="<?php echo $row->nama_dealer ?>" readonly placeholder="Dealer" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Pembayaran (MD)</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" readonly placeholder="Status Pembayaran (MD)" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" value="<?php echo $row->no_mesin ?>" readonly placeholder="No Mesin" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Status Pembayaran (D)</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" readonly placeholder="Status Pembayaran (D)" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" value="<?php echo $row->no_rangka ?>" readonly placeholder="No Rangka" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No STNK</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" value="<?php echo $no_stnk ?>" readonly placeholder="No STNK" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" value="<?php echo $row->tipe_ahm ?>" readonly placeholder="Tipe" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No BPKB</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" value="<?php echo $no_bpkb ?>" readonly placeholder="No BPKB" class="form-control">
                  </div>                  
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" value="<?php echo $row->warna ?>" readonly placeholder="Warna" class="form-control">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Plat</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" value="<?php echo $no_plat ?>" readonly placeholder="No Plat" class="form-control">
                  </div>                  
                </div>
                
                <?php 
                $c_stnk = $this->m_admin->getByID("tr_terima_bj","no_mesin",$row->no_mesin);
                if($c_stnk->num_rows() > 0){
                  $rr = $c_stnk->row();                  
                  $no_serah_terima_stnk = $rr->no_kirim_stnk;
                  $no_serah_terima_bpkb = $rr->no_kirim_bpkb;
                  $no_serah_terima_plat = $rr->no_kirim_plat;
                  $tgl_bpkb = $rr->tgl_bpkb;
                  $tgl_stnk = $rr->tgl_stnk;
                  $tgl_plat = $rr->tgl_plat;
                  $tgl_terima_bpkb = $rr->tgl_terima_bpkb;
                  $tgl_terima_stnk = $rr->tgl_terima_stnk;
                  $tgl_terima_plat = $rr->tgl_terima_plat;
                }else{                  
                  $no_serah_terima_stnk = "";
                  $no_serah_terima_bpkb = "";
                  $no_serah_terima_plat = "";
                  $tgl_bpkb = "";
                  $tgl_stnk = "";
                  $tgl_plat = "";
                  $tgl_terima_bpkb = "";
                  $tgl_terima_stnk = "";
                  $tgl_terima_plat = "";
                }

                $c_bastd = $this->db->query("SELECT * FROM tr_faktur_stnk INNER JOIN tr_faktur_stnk_detail ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd
                  WHERE tr_faktur_stnk_detail.no_mesin = '$row->no_mesin'");
                if($c_bastd->num_rows() > 0){
                  $r = $c_bastd->row();
                  $no_bastd = $r->no_bastd;
                  $tgl_bastd = $r->tgl_bastd;
                }else{
                  $no_bastd = "";
                  $tgl_bastd = "";
                }

                $c_stnk = $this->db->query("SELECT * FROM tr_penyerahan_stnk INNER JOIN tr_penyerahan_stnk_detail ON tr_penyerahan_stnk.no_serah_stnk = tr_penyerahan_stnk_detail.no_serah_stnk
                  WHERE tr_penyerahan_stnk_detail.no_mesin = '$row->no_mesin'");
                if($c_stnk->num_rows() > 0){
                  $r = $c_stnk->row();
                  $no_serah_stnk = $r->no_serah_stnk;
                  $tgl_serah = $r->tgl_serah_terima;
                }else{
                  $no_serah_stnk = "";
                  $tgl_serah = "";
                }


                $c_plat = $this->db->query("SELECT * FROM tr_penyerahan_plat INNER JOIN tr_penyerahan_plat_detail ON tr_penyerahan_plat.no_serah_plat = tr_penyerahan_plat_detail.no_serah_plat
                  WHERE tr_penyerahan_plat_detail.no_mesin = '$row->no_mesin'");
                if($c_plat->num_rows() > 0){
                  $r = $c_plat->row();
                  $no_serah_plat = $r->no_serah_plat;
                  $tgl_serah2 = $r->tgl_serah_terima;
                }else{
                  $no_serah_plat = "";
                  $tgl_serah2 = "";
                }

                $c_bpkb = $this->db->query("SELECT * FROM tr_penyerahan_bpkb INNER JOIN tr_penyerahan_bpkb_detail ON tr_penyerahan_bpkb.no_serah_bpkb = tr_penyerahan_bpkb_detail.no_serah_bpkb
                  WHERE tr_penyerahan_bpkb_detail.no_mesin = '$row->no_mesin'");
                if($c_bpkb->num_rows() > 0){
                  $r = $c_bpkb->row();
                  $no_serah_bpkb = $r->no_serah_bpkb;
                  $tgl_serah3 = $r->tgl_serah_terima;
                }else{
                  $no_serah_bpkb = "";
                  $tgl_serah3 = "";
                }

                $cek_biro = $this->db->query("SELECT tr_kirim_biro.tgl_terima,tr_kirim_biro.no_tanda_terima FROM tr_pengajuan_bbn_detail
                        inner join tr_faktur_stnk on tr_pengajuan_bbn_detail.no_bastd = tr_faktur_stnk.no_bastd
                        inner join ms_dealer on tr_faktur_stnk.id_dealer = ms_dealer.id_dealer
                        INNER JOIN tr_kirim_biro ON tr_kirim_biro.id_generate = tr_pengajuan_bbn_detail.id_generate
                        WHERE tr_pengajuan_bbn_detail.no_mesin = '$row->no_mesin' AND tr_pengajuan_bbn_detail.status_bbn='generated'
                        AND tr_kirim_biro.tgl_terima IS NOT NULL");
                if($cek_biro->num_rows() > 0){
                  $e = $cek_biro->row();
                  $tgl_terima_biro = $e->tgl_terima;
                  $no_terima_biro = $e->no_tanda_terima;
                }else{
                  $tgl_terima_biro = "";
                  $no_terima_biro = "";
                }

                ?>


                <table class='table table-bordered table-hover' id="example1">
                  <tr>
                    <th>Proses</th>
                    <th>Tanggal</th>
                    <th>No Tanda Terima</th>                    
                  </tr>                  
                  <tr>
                    <td>BASTD</td>
                    <td><?php echo $tgl_bastd ?></td>
                    <td><?php echo $no_bastd ?></td>
                  </tr>
                  <tr>
                    <td>Kirim ke Biro Jasa</td>
                    <td><?php echo $tgl_terima_biro ?></td>
                    <td><?php echo $no_terima_biro ?></td>
                  </tr>
                  <tr>
                    <td>Terima STNK</td>
                    <td><?php echo $tgl_serah ?></td>
                    <td><?php echo $no_serah_stnk ?></td>
                  </tr>
                  <tr>
                    <td>Terima Plat</td>
                    <td><?php echo $tgl_serah2 ?></td>
                    <td><?php echo $no_serah_plat ?></td>
                  </tr>
                  <tr>
                    <td>Terima BPKB</td>
                    <td><?php echo $tgl_serah3 ?></td>
                    <td><?php echo $no_serah_bpkb ?></td>
                  </tr>
                  <tr>
                    <td>Serah Terima STNK</td>
                    <td><?php echo $tgl_terima_stnk ?></td>
                    <td><?php echo $no_serah_terima_stnk ?></td>
                  </tr>
                  <tr>
                    <td>Serah Terima BPKB</td>
                    <td><?php echo $tgl_terima_bpkb ?></td>
                    <td><?php echo $no_serah_terima_bpkb ?></td>
                  </tr>
                  <tr>
                    <td>Serah Terima Plat</td>
                    <td><?php echo $tgl_terima_plat ?></td>
                    <td><?php echo $no_serah_terima_plat ?></td>
                  </tr>
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
          <!--a href="h1/outstanding_bpkb/add">            
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
              <th>Tgl Mohon Samsat</th>              
              <th>No BASTD</th>              
              <th>Dealer</th>
              <th>No Mesin</th>            
              <th>No Rangka</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>No STNK</th>
              <th>No Plat</th>
              <th>No BPKB</th>
              <th>Nama Konsumen</th>
              <th>Lunas/Belum (Oleh MD)</th>
              <th>Lunas/Belum (Oleh Dealer)</th>
              <th width="5%">Action</th>              
            </tr>
          </thead>
          <tbody>
          <?php 
          $no=1;
          foreach ($dt_mon->result() as $row) {
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

            $tipe = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row->id_tipe_kendaraan)->row();
            $warna = $this->m_admin->getByID("ms_warna","id_warna",$row->id_warna)->row();
            $c_stnk = $this->m_admin->getByID("tr_terima_bj","no_mesin",$row->no_mesin);
            if($c_stnk->num_rows() > 0){
              $rr = $c_stnk->row();
              $no_stnk = $rr->no_stnk;
              $no_plat = $rr->no_plat;
              $no_bpkb = $rr->no_bpkb;
            }else{
              $no_stnk = "";
              $no_plat = "";
              $no_bpkb = "";
            }
            echo "
            <tr>
              <td>$no</td>
              <td>$row->tgl_mohon_samsat</td>
              <td>$row->no_bastd</td>
              <td>$nama_dealer</td>
              <td>$row->no_mesin</td>
              <td>$row->no_rangka</td>
              <td>$tipe->tipe_ahm</td>
              <td>$warna->warna</td>
              <td>$no_stnk</td>
              <td>$no_plat</td>
              <td>$no_bpkb</td>
              <td>$row->nama_konsumen</td>
              <td></td>
              <td></td>
              <td>
                <a href='h1/monitoring_samsat/detail?id=$row->no_mesin' class='btn btn-flat btn-warning btn-xs'>view</a>
              </td>
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
    }elseif($set=="view_fix"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/outstanding_bpkb/add">            
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
        <table id="table" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                          
              <th>Tgl Mohon Samsat</th>              
              <th>No BASTD</th>              
              <th>Dealer</th>
              <th>No Mesin</th>            
              <th>No Rangka</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>No STNK</th>
              <th>No Plat</th>
              <th>No BPKB</th>
              <th>Nama Konsumen</th>
              <th>Lunas/Belum (Oleh MD)</th>
              <th>Lunas/Belum (Oleh Dealer)</th>
              <th width="5%">Action</th>              
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
<script type="text/javascript">

var table;

$(document).ready(function() {
    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        'scrollX':true,
        'scrollY':"830px",

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('h1/monitoring_samsat/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0,6,7,8,9,10,11,12,13 ], //first column / numbering column
            "orderable": false, //set not orderable
        },        
        ],
        "columnDefs": [
          { 
            "searchable": false, 
            "targets": [ 0,6,7,8,9,10,11,12,13 ], //first column / numbering column
          }
        ],
    });
});

</script>