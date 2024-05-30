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
    <li class="">Indent</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
     <?php 
    if($set=="detail"){
      $row = $dt_indent->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <?php if(isset($_GET['h'])){ ?>
            <a href="h1/indent/history">
          <?php }else{ ?>            
            <a href="h1/indent">
          <?php } ?>
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
            <form class="form-horizontal" action="h1/indent/update" method="post" enctype="multipart/form-data">
              <div class="box-body">    

               
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID SPK</label>
                  <div class="col-sm-4">
                    <input type="hidden" value="<?php echo $row->id_spk ?>" name="id">                                        
                    <input type="text" required class="form-control" disabled value="<?php echo $row->id_spk ?>" placeholder="ID SPK" name="id_spk">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" disabled value="<?php echo $row->nama_konsumen ?>" placeholder="Nama Konsumen" name="nama_konsumen">                                        
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Konsumen</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" disabled value="<?php echo $row->alamat ?>" placeholder="Alamat Konsumen" name="alamat">                                        
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">No KTP</label>
                  <div class="col-sm-4">
                    <input type="text" required  disabled onkeypress="return number_only(event)" value="<?php echo $row->no_ktp ?>" class="form-control" placeholder="No KTP" name="no_ktp">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Telp</label>
                  <div class="col-sm-4">
                    <input type="text" required disabled onkeypress="return number_only(event)" value="<?php echo $row->no_telp ?>" class="form-control" placeholder="No telp" name="no_telp">                    
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                  <div class="col-sm-4">
                    <input type="email" disabled class="form-control" placeholder="Email" value="<?php echo $row->email ?>" name="email">                    
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Kendaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_tipe_kendaraan" disabled>
                      <option value="<?php echo $row->id_tipe_kendaraan ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_tipe_kendaraan","id_tipe_kendaraan",$row->id_tipe_kendaraan)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->id_tipe_kendaraan." - ".$dt_cust->tipe_ahm;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_tipe = $this->m_admin->kondisi("ms_tipe_kendaraan","id_tipe_kendaraan != '$row->id_tipe_kendaraan'");                                                                      
                      foreach($dt_tipe->result() as $val) {
                        echo "
                        <option value='$val->id_tipe_kendaraan'>$val->id_tipe_kendaraan - $val->tipe_ahm</option>;
                        ";
                      }
                      ?>
                      <option value="">- choose -</option>                      
                    </select>
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna Kendaraan</label>
                  <div class="col-sm-4">
                    <select class="form-control select2" name="id_warna" disabled>
                      <option value="<?php echo $row->id_warna ?>">
                        <?php 
                        $dt_cust    = $this->m_admin->getByID("ms_warna","id_warna",$row->id_warna)->row();                                 
                        if(isset($dt_cust)){
                          echo $dt_cust->id_warna." - ".$dt_cust->warna;
                        }else{
                          echo "- choose -";
                        }
                        ?>
                      </option>
                      <?php 
                      $dt_warna = $this->m_admin->kondisi("ms_warna","id_warna != '$row->id_warna'");                                                                                            
                      foreach($dt_warna->result() as $val) {
                        echo "
                        <option value='$val->id_warna'>$val->id_warna - $val->warna</option>;
                        ";
                      }
                      ?>
                      <option value="">- choose -</option>                      
                    </select>
                  </div>                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nilai DP</label>
                  <div class="col-sm-4">
                    <input type="text" disabled onkeypress="return number_only(event)" value="<?php echo mata_uang($row->nilai_dp) ?>" required class="form-control" placeholder="Nilai DP" name="nilai_dp">                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Qty</label>
                  <div class="col-sm-4">
                    <input type="text" disabled onkeypress="return number_only(event)" value="<?php echo $row->qty ?>" class="form-control" placeholder="Qty" name="qty">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal ETA</label>
                  <div class="col-sm-4">
                    <input type="text" disabled id="tanggal" class="form-control" value="<?php echo $row->tgl ?>" placeholder="Tanggal ETA" name="tgl">                    
                  </div>                  
                </div>
                <div class="form-group">                                                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text"  disabled required class="form-control" value="<?php echo $row->ket ?>" placeholder="Keterangan" name="ket">                    
                  </div>                  
                </div>                                                  
                                
                
              </div><!-- /.box-body --> 
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <!-- <button type="submit" onclick="return confirm('Are you sure to approve this data?')"  name="save" value="approve" class="btn btn-info btn-flat"><i class="fa fa-check"></i> Approve </button> -->
                  <!-- <button type="submit" onclick="return confirm('Are you sure to reject this data?')"  name="save" value="reject" class="btn btn-danger btn-flat"><i class="fa fa-close"></i> Reject </button>                   -->
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
          <a href="h1/indent/history">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-refresh"></i> History</button>
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
              <th>Tanggal Indent</th>
              <th>No SPK</th>                  
              <th>Nama Dealer</th>        
              <th>Nama Konsumen</th>
              <th>No KTP</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>Tanda Jadi Konsumen</th>
              <th>Status Indent</th>
              <th width="5%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 

          foreach($dt_indent->result() as $row) {     
            $btnKonf = '';
            $status=$row->status;            
            if($row->status=='requested'){
              $status = "<span class='label label-primary'>Open</span>";
            }elseif($row->status=='canceled'){
              $status = "<span class='label label-danger'>Canceled</span>";
            }else{
              $status = "<span class='label label-success'>$status</span>";
            }
	    
	    if($row->send_ahm == '1'){
	      $status = "<span class='label label-warning'>Send AHM</span>";	
            }	
		
            $cek_stok = $this->db->query("SELECT count(1) as avs_stok FROM tr_scan_barcode WHERE tipe_motor = '$row->id_tipe_kendaraan' AND warna = '$row->id_warna' AND tipe = 'RFS' AND status = '1'")->row();          
            if($cek_stok->avs_stok > 0){
              $isi = 'white';
            }else{
              $isi = 'pink';
              $btnKonf = '<a data-toggle=\'tooltip\' title="Konfirmasi" onclick="return confirm(\'Yakin akan mengirim data ini ke AHM?\')" class="btn btn-warning btn-flat" href="h1/indent/konfirmasi?id_tipe='.$row->id_tipe_kendaraan.'&id_warna='.$row->id_warna.'&id_indent='.$row->id_indent.'"><i class="fa fa-check-circle"></i> Send to AHM</a>';
            }
            $tgl = explode(' ', $row->tgl);
            $tgl = $tgl[0];
            echo "
            <tr style='background:$isi'>
              <td>$no</td>
              <td>$tgl</td>                            
              <td>
              <a href='h1/indent/detail?id=$row->id_spk'>
                $row->id_spk
              </a>
              </td>
              
              <td>$row->nama_dealer</td>                            
              <td>$row->nama_konsumen</td>                            
              <td>$row->no_ktp</td>              
              <td>$row->id_tipe_kendaraan - $row->tipe_ahm</td>              
              <td>$row->id_warna - $row->warna</td>                            
              <td>".mata_uang2($row->tanda_jadi)."</td>                            
              <td>$status</td>                            
              <td>"; 
              if($row->status == 'requested'){
              ?>
                <a data-toggle='tooltip' <?php echo $this->m_admin->set_tombol($id_menu,$group,"approval"); ?> title="Fullfilled Data" onclick="return confirm('Are you sure to fullfilled this data?')" class="btn btn-success btn-flat" href="h1/indent/fullfilled?id=<?php echo $row->id_spk ?>"><i class="fa fa-check"></i> Fullfilled</a>   
                <?php 
                echo $retVal = ($row->send_ahm == '0') ? $btnKonf : '';
                 ?>


              <?php } ?>
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

    <?php
    }elseif($set=="history"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/spk">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
          </a>                    
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
        <table id="table_ajax" class="table table-bordered">
          <thead>
            <tr>              
              <td width="5%">No</td>
              <td>Tanggal Indent</td>
              <td>No SPK</td>                  
              <td>Nama Dealer</td>        
              <td>Nama Konsumen</td>
              <td>No KTP</td>
              <td>Tipe</td>
              <td>Warna</td>
              <td>Tanda Jadi</td>
              <td>Status</td>                                        
            </tr>            
          </thead>
          <tbody id="showSPK">                              
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
$(document).ready(function() {
    $.ajax({
        beforeSend: function() {
          $('#showSPK').html('<tr><td colspan=8 style="font-size:12pt;text-align:center">Processing...</td></tr>');
        },
        url: "<?php echo site_url('dashboard/history_indent')?>",
        type:"POST",
        data:"",            
        cache:false,
        success:function(response){                
           $('#showSPK').html(response);
           datatables();
        } 
    })
});
</script>
<script type="text/javascript">
  function datatables() {
    $('#table_ajax').DataTable({      
          "paging": true,
          "lengthChange": true,
          "searching": true,
          "ordering": true,
          "info": true,
          "scrollX":true,        
          "lengthMenu": [[10, 25, 50,75,100], [10, 25, 50,75,100]],
          "autoWidth": true
        });
  }
</script>