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
    if($set=="upload"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/sipb">
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
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/sipb/import_db" method="post" enctype="multipart/form-data">
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Choose File</label>
                  <div class="col-sm-10">
                    <input type="file" accept=".SIPB, .KSIPB" required class="form-control" autofocus name="userfile">                    
                  </div>                  
                </div>                                                                                                      
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to import this data?')" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Start Upload</button>                                  
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
          <!--a href="h1/po/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a-->          
          
          <a href="h1/sipb/upload">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-upload"></i> Upload</button>
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>
              <th>No.SIPB</th>              
              <th>Tgl.SIPB</th>              
              <th>No.Spes</th>              
              <th>Tgl.Spes</th>
              <th>Tipe</th>              
              <th>Warna</th>
              <th>Jumlah</th>
              <!-- <th>Harga</th> -->
              <!-- <th>Disc</th> -->
              <th>Q.Flag</th>
              <th>No.PO</th>
              <th>Kode Dealer</th>
              <!-- <th>Amount</th>
              <th>PPN</th>
              <th>PPH</th> -->
              <th>Dealer MD</th>
              <th>Provinsi</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_sipb->result() as $row) {
          $bulan = substr($row->tgl_sipb, 2,2);
          $tahun = substr($row->tgl_sipb, 4,4);
          $tgl = substr($row->tgl_sipb, 0,2);
          $tanggal = $tgl."-".$bulan."-".$tahun;

          $bulan1 = substr($row->tgl_spes, 2,2);
          $tahun1 = substr($row->tgl_spes, 4,4);
          $tgl1 = substr($row->tgl_spes, 0,2);
          $tanggal2 = $tgl1."-".$bulan1."-".$tahun1;

          if(!is_null($row->warna)){
            $warna = "<td>$row->warna</td>";
          }else{
            $warna = "<td bgcolor='red'>$row->id_warna</td>";
          }

          if(!is_null($row->tipe_ahm)){
            $tipe = "<td>$row->tipe_ahm</td>";
          }else{
            $tipe = "<td bgcolor='red'>$row->id_tipe_kendaraan</td>";
          }

          echo "          
            <tr>
              <td>$no</td>
              <td>$row->no_sipb</td>
              <td>$tanggal</td>
              <td>$row->no_spes</td>              
              <td>$tanggal2</td>
              $tipe
              $warna
              <td>$row->jumlah</td>              
              <td>$row->q_flag</td>
              <td>$row->no_po_md</td>
              <td>$row->dealer_qq</td>
              <td>$row->nama_dealer</td>
              <td>$row->provinsi</td>
            </tr>";                        
          $no++;
          }
          ?>
              <!-- <td>$row->disc</td> -->
              <!-- <td>$row->amount</td>
              <td>$row->ppn</td>
              <td>$row->pph</td> -->
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>


