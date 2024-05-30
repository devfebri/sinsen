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
<body onload="auto()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">Customer</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="approve"){
      $row = $dt_hasil->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/hasil_survey">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
            <form class="form-horizontal" action="dealer/hasil_survey/save_approve" method="post" enctype="multipart/form-data">
              <div class="box-body">       
              <button class="btn btn-block btn-success btn-flat" disabled> DATA KONSUMEN </button> <br>       
                <div class="form-group">
                  <input type="hidden" name="id" value="<?php echo $row->no_spk ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Spk</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->no_spk ?>" readonly class="form-control" placeholder="No SPK" name="no_spk">                    
                  </div>                                    
                </div>                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->nama_konsumen ?>" readonly class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">                    
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->alamat ?>" readonly class="form-control" placeholder="Alamat Konsumen" name="nama_konsumen">                    
                  </div>                  
                </div>
                 <button class="btn btn-block btn-danger btn-flat" disabled> DATA KENDARAAN </button> <br>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" value="<?php echo "$row->id_tipe_kendaraan | $row->tipe_ahm"; ?>" placeholder="Tipe Motor" name="nama_konsumen">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">DP Gross</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Nilai DP Gross" readonly value="<?php echo mata_uang2($row->uang_muka) ?>" name="nilai_dp_gross">                    
                  </div>                                                      
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo "$row->id_warna | $row->warna"; ?>" readonly class="form-control" placeholder="Warna" name="nama_konsumen">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nilai Voucher</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->voucher_2 ?>" readonly class="form-control" placeholder="Nilai Voucher" name="voucher">                    
                  </div>                                
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Harga Motor</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->harga_tunai ?>" readonly class="form-control" placeholder="Harga Motor" name="harga_motor">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Voucher Tambahan</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->voucher_tambahan_2 ?>" readonly class="form-control" placeholder="Voucher Tamabahan" name="voucher_tambahan">                    
                  </div>                                    
                </div> 
                <div class="form-group">               
                  <label for="inputEmail3" class="col-sm-2 control-label">Tenor</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" readonly value="<?php echo $row->tenor ?>" placeholder="Tenor" name="tenor">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">DP Setor</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" required placeholder="DP Setor" value="<?php echo $row->dp_stor ?>" name="nilai_dp">                    
                  </div>                  
                </div>
                <div class="form-group">                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Approve</label>
                  <div class="col-sm-4">
                    <input type="text" required class="form-control" id="tanggal" placeholder="Tanggal Approve" name="tgl_approval">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Tanda Jadi</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->uang_muka ?>" required class="form-control" placeholder="Tanda Jadi" name="tanda_jadi">                    
                  </div>                  
                </div>                
              </div>
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save and approve?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save and Approve</button>
                  <button type="button" onClick="cancel_tr()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="reject"){
      $row = $dt_hasil->row();      
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/hasil_survey">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>
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
            <form class="form-horizontal" action="dealer/hasil_survey/save_reject" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <input type="hidden" name="id" value="<?php echo $row->no_spk ?>">
                  <label for="inputEmail3" class="col-sm-2 control-label">No Spk</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $row->no_spk ?>" readonly class="form-control" placeholder="No SPK" name="no_spk">                    
                  </div>                                    
                </div>                
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Konsumen</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->nama_konsumen ?>" readonly class="form-control" placeholder="Nama Konsumen" name="nama_konsumen">                    
                  </div>                  
                </div>
                <div class="form-group">                
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->alamat ?>" readonly class="form-control" placeholder="Alamat Konsumen" name="alamat_konsumen">                    
                  </div>                  
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Tipe Motor</label>
                  <div class="col-sm-4">                    
                    <input type="text" readonly class="form-control" value="<?php echo "$row->id_tipe_kendaraan | $row->tipe_ahm"; ?>" placeholder="Tipe Motor" name="nama_konsumen">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Warna</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo "$row->id_warna | $row->warna"; ?>" readonly class="form-control" placeholder="Warna" name="nama_konsumen">                    
                  </div>                  
                </div>
                <div class="form-group">                                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alasan Reject</label>
                  <div class="col-sm-10">
                    <input type="text" required class="form-control" placeholder="Alasan Reject" name="alasan">                    
                  </div>                  
                </div>                
              </div>
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save and reject?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save and Reject</button>
                  <button type="button" onClick="cancel_tr()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
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
          <!--a href="dealer/hasil_survey/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>              
              <th>No SPK</th>              
              <th>Nama Konsumen</th>              
              <th>Alamat</th>              
              <th>Leasing</th>
              <th>Harga Motor</th>
              <th>Tanda Jadi</th>
              <th>DP Setor</th>
              <th>Tenor</th>
              <th>Tgl Approval</th>
              <th>Status Approval</th>
              <th>Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_hasil_survey->result() as $row) {     
            $s = $this->db->query("SELECT tr_prospek.nama_konsumen,tr_spk.* FROM tr_spk INNER JOIN tr_prospek ON tr_spk.id_customer=tr_prospek.id_customer 
                WHERE tr_spk.no_spk = '$row->no_spk'");
            if($s->num_rows() > 0){
              $rt       = $s->row();
              $nama     = $rt->nama_konsumen;
              $alamat   = $rt->alamat;
              $id_f     = $rt->id_finance_company;
              $id_tipe  = $rt->id_tipe_kendaraan;
              $id_warna = $rt->id_warna;        
              $harga_jual_f = $rt->harga_tunai;
              $dp_stor_f = $rt->dp_stor;      
              $tenor_f   = $rt->tenor;      
              $uang_muka_f = $rt->uang_muka;      
            }else{
              $nama     = "";
              $alamat   = "";
              $id_f     = "";
              $id_tipe  = "";
              $id_warna = "";
              $harga_jual_f = "";
              $dp_stor_f = "";
              $tenor_f = "";
              $uang_muka_f = "";
            }

            $r = $this->m_admin->getByID("ms_finance_company","id_finance_company",$id_f);
            if($r->num_rows() > 0){
              $tr = $r->row();
              $leasing = $tr->finance_company;
            }else{
              $leasing = "";
            }

            $item = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_kelompok_md ON ms_item.id_item = ms_kelompok_md.id_item 
                      WHERE ms_item.id_tipe_kendaraan = '$id_tipe' AND ms_item.id_warna = '$id_warna'");
            if($item->num_rows() > 0){
              $rr = $item->row();
              $id_item  = $rr->id_item;
              $harga_jual    = $rr->harga_jual;
            }else{
              $id_item    = "";
              $harga_jual = "0";
            }
            
            $cek = $this->db->query("SELECT * FROM tr_hasil_survey WHERE no_spk = '$row->no_spk'");
            if($cek->num_rows() > 0){
              $rt = $cek->row();
              $tanda_jadi = $rt->tanda_jadi;
              $tenor = $rt->tenor;
              $tgl_approval = $rt->tgl_approval;
              $status = $rt->status_approval;
              $tombol = "";
            }else{
              $tanda_jadi = 0;
              $tenor = "";
              $tgl_approval = "";
              $status = "input";
              $tombol = "<a href='dealer/hasil_survey/approve?id=$row->no_spk'>
                  <button class='btn btn-flat btn-xs btn-success'><i class='fa fa-check'></i> Approve</button>
                </a>
                <a href='dealer/hasil_survey/reject?id=$row->no_spk'>
                  <button class='btn btn-flat btn-xs btn-danger'><i class='fa fa-close'></i> Reject</button>
                </a>";
            }

            if($status =='approved'){
              $status2 = "<span class='label label-success'>Approved</span>";
            }elseif($status =='rejected'){
              $status2 = "<span class='label label-danger'>Rejected</span>";
            }else{
              $status2 = "<span class='label label-primary'>Proses</span>";
            }

            // $cek_c = $this->db->query("SELECT * FROM tr_hasil_survey WHERE no_spk = '$row->no_spk'");
            // if($cek_c->num_rows() > 0){
            //   $tu = $cek_c->row();
            //   $harga_jualan = $tu->harga_motor;
            //   $uang_muka = $tu->nilai_dp;
            // }else{
            //   $harga_jualan = 0;
            //   $uang_muka = 0;
            // }



            // if ($status = 'input') {
            //   $status2='proses';
            // }
            $harga_jual_f =$harga_jual_f>0?mata_uang2($harga_jual_f):0;
            if(is_numeric($harga_jual_f)){
              $harga_jual_f = $harga_jual_f;
            }else{
              $harga_jual_f = 0;
            }
            $tanda_jadi = $tanda_jadi>0?mata_uang2($tanda_jadi):0;
            if(is_numeric($tanda_jadi)){
              $tanda_jadi = $tanda_jadi;
            }else{
              $tanda_jadi = 0;
            }
            $uang_muka_f = $uang_muka_f>0?mata_uang2($uang_muka_f):0;
            if(is_numeric($uang_muka_f)){
              $uang_muka_f = $uang_muka_f;
            }else{
              $uang_muka_f = 0;
            }
            echo "
            <tr>
              <td>$no</td>
              <td>$row->no_spk</td>
              <td>$nama</td>              
              <td>$alamat</td>
              <td>$leasing</td>
              <td>".$harga_jual_f."</td>                            
              <td>".$tanda_jadi."</td>                                          
              <td>".$uang_muka_f."</td>
              <td>$tenor_f</td>                            
              <td>$tgl_approval</td>
              <td>$status2</td>                                          
              <td>                                
                $tombol
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
    }
    ?>
  </section>
</div>



<script type="text/javascript">
function auto(){
  var tgl_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('dealer/hasil_survey/cari_id')?>",
      type:"POST",
      data:"tgl="+tgl_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#id_hasil_survey").val(data[0]);                
        $("#id_customer").val(data[1]);                
      }        
  })
}
function take_sales(){
  var id_karyawan_dealer = $("#id_karyawan_dealer").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/hasil_survey/take_sales')?>",
      type:"POST",
      data:"id_karyawan_dealer="+id_karyawan_dealer,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");          
          //$("#no_polisi").html(msg);                                                    
          $("#kode_sales").val(data[0]);                                                    
          $("#nama_sales").val(data[1]);                                                    
      } 
  })
}


</script>