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
    <li class="">Invoice Keluar</li>
    <li class="">Inovice Dealer</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>
  <section class="content">


    
    <?php 
    if($set=="detail"){
      $row = $dt_invoice->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/invoice_dealer_unit">
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
            <form class="form-horizontal" action="h1/invoice_dealer_unit/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No DO</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="No DO" value="<?php echo $row->no_do ?>" readonly class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl DO</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Tgl DO" value="<?php echo $row->tgl_do ?>" readonly id="tanggal2" class="form-control">                    
                  </div>                                    
                </div>  
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Kode Customer" value="<?php echo $row->kode_dealer_md ?>" readonly class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">NPWP Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="BPWP Customer" value="<?php echo $row->npwp ?>" readonly class="form-control">                    
                  </div>                                    
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Nama Customer" value="<?php echo $row->nama_dealer ?>" readonly class="form-control">                    
                  </div>                                                      
                </div>                
                <div class="form-group">                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Plafon Unit</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Plafon Unit" value="<?php echo mata_uang2($row->plafon) ?>" readonly class="form-control">                    
                  </div>                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Customer</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Alamat Customer" value="<?php echo $row->alamat ?>" readonly class="form-control">                    
                  </div>                                    
                </div>                
                <div class="form-group">
                  <?php 
                  if($row->tgl_faktur != "0000-00-00"){
                    $tgl1 = $row->tgl_faktur;// pendefinisian tanggal awal
                    $top = $row->top_unit;
                    $tgl2 = date("Y-m-d", strtotime("+".$top." days", strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari                    
                  }else{
                    $tgl2 = "";
                  }
                  ?>                                
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Jatuh Tempo</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?php echo $tgl2 ?>" name="no_mesin" placeholder="Tgl Jatuh Tempo" readonly class="form-control">                    
                  </div>                                    
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">Total Hutang</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" placeholder="Total Hutang" readonly class="form-control">                    
                  </div>                                     -->
                </div>                
                                    
                <br>                                    
                <table id="example2" class="table myTable1 table-bordered table-hover">
                  <tr>
                    <th>Kode Item</th>
                    <th>Tipe Kendaraan</th>
                    <th>Warna</th>
                    <th>Qty DO</th>
                    <th>Total Harga Jual</th>
                    <th>Diskon Per Unit</th>
                    <th>Total Diskon</th>
                    <th>Total</th>
                  </tr>
                  <?php 
                   $dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
                    ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                    ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                    ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$row->no_do'");
                  $to=0;
                  foreach($dt_do_reg->result() as $isi){
                    $total_harga = $isi->harga * $isi->qty_do;
                    echo "
                    <tr>
                      <td>$isi->id_item</td>
                      <td>$isi->tipe_ahm</td>
                      <td>$isi->warna</td>
                      <td>$isi->qty_do</td>
                      <td>".mata_uang2($total_harga)."</td>
                      <td>0</td>
                      <td>0</td>
                      <td>".mata_uang2($total_harga)."</td>
                    </tr>
                    ";
                    $to = $to + $total_harga;                    
                  }
                  ?>
                  <tr>
                    <td colspan="6"></td>
                    <td>DPP</td>
                    <td><?php echo mata_uang2($to); ?></td>
                  </tr>
                  <tr>
                    <td colspan="6"></td>
                    <td>PPN</td>
                    <td><?php echo mata_uang2($y = $to * 0.1); ?></td>
                  </tr>
                  <tr>
                    <td colspan="6"></td>
                    <td>Total Bayar</td>
                    <td><?php echo mata_uang2($to + $y); ?></td>
                  </tr>
                </table>
              </div><!-- /.box-body -->              
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <?php 
                  if($row->no_faktur == '-'){                  
                    $visible = "style='visibility: hidden;'";
                  }else{
                    $visible = "style=''";
                  } ?>
                  <button type="button" <?php echo $visible ?> name="save" value="Detail" data-toggle="collapse" data-target="#demo" class="btn btn-info btn-flat"><i class="fa fa-list"></i> Detail Piutang</button>                                    
                  <div id="demo" class="collapse">
                    <br>        
                    <div class="form-group">                                
                      <label for="inputEmail3" class="col-sm-2 control-label">Maks Plafon</label>
                      <div class="col-sm-4">
                        <input type="text" name="no_mesin" value="<?php echo mata_uang2($row->plafon_maks) ?>" placeholder="Maks Plafon" readonly class="form-control">                    
                      </div>                                    
                      <label for="inputEmail3" class="col-sm-2 control-label">Sisa Plafon</label>
                      <div class="col-sm-4">
                        <input type="text" name="no_mesin" value="<?php echo mata_uang2($row->plafon) ?>" placeholder="Sisa Plafon" readonly class="form-control">                    
                      </div>                                    
                    </div>
                    <table class="table table-bordered table-hover">
                      <tr align="center">
                        <th colspan="3">Daftar Piutang</th>
                      </tr>
                      <tr>
                        <th>No Invoice</th>
                        <th>Tgl Jatuh Tempo</th>
                        <th>Nilai</th>
                      </tr>
                      <?php
                      $am = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do
                      WHERE tr_do_po.no_do = '$row->no_do'");
                      foreach ($am->result() as $isi) {
                        $to = $this->db->query("SELECT SUM(qty_do * harga) AS tot FROM tr_do_po_detail WHERE no_do = '$isi->no_do'")->row();
                        $t_ppn = $to->tot + ($to->tot * 0.1);
                        echo "
                          <tr>
                            <td>$isi->no_faktur</td>                            
                            <td>$isi->tgl_faktur</td>                            
                            <td>".mata_uang2($t_ppn)."</td>                            
                          </tr>
                          ";
                      }
                      ?>
                    </table>
                  </div>
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
          <a href="h1/invoice_dealer_unit/download">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-download"></i> Download Txt Bank</button>
          </a>          
          <a href="h1/invoice_dealer_unit/history">            
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
              <th>No Faktur</th>                           
              <th>Tgl Faktur</th>              
              <th>No DO</th>              
              <th>Nama Customer</th>
              <th>Bank</th>
              <th>Tgl Cair</th>
              <th>Total</th> 
              <th>Status</th>             
              <th width='10%'>Aksi</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1;           
          foreach($dt_invoice->result() as $row) {  
            if($row->status_invoice == 'waiting approval'){
              $status = "<span class='label label-warning'>$row->status_invoice</span>";
              $tampil = "<a data-toggle=\"tooltip\" title=\"Approve Data\" onclick=\"return confirm('Are you sure to approve this data?')\" class=\"btn btn-success btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/approve?id=$row->no_do\">Approve</a>";
              $tampil2 = "<a data-toggle=\"tooltip\" title=\"Reject Data\" onclick=\"return confirm('Are you sure to reject this data?')\" class=\"btn btn-danger btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/reject?id=$row->no_do\">Reject</a>";
              $tampil3 = "";            
              $tampil4 = "";
            }elseif($row->status_invoice=='rejected' OR $row->status_invoice=='approved'){
              $status = "<span class='label label-danger'>$row->status_invoice</span>";
              $tampil2 = "";              
              $tampil4 = "<button type=\"button\" title=\"Input Tgl Cair\" class=\"btn btn-xs btn-primary btn-flat\"                   
                  onclick=\"input_tgl('$row->no_do')\">Tgl Cair</button>";                            
              $tampil = "";
              $tampil3 = "";
            }elseif($row->status_invoice=='printable'){
              $status = "<span class='label label-success'>$row->status_invoice</span>";
              $tampil2 = "";
              $tampil = "";
              $tampil4 = "";
              $tampil3 = "<a data-toggle=\"tooltip\" target=\"_blank\" title=\"Print Data\"  class=\"btn btn-warning btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/cetak?id=$row->id_invoice_dealer\">Print</a>";
            }


            $rt = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();
            
          echo "          
            <tr>              
              <td>$no</td>              
              <td>$row->no_faktur</td>                            
              <td>$row->tgl_faktur</td>                            
              <td>
                <a href='h1/invoice_dealer_unit/view?id=$row->no_do'>
                  $row->no_do
                </a>
              </td>                            
              <td>$rt->nama_dealer</td>                            
              <td>$row->bank</td>                            
              <td>$row->tgl_cair</td>                            
              <td>";
              $total_harga = 0;
              $dt_do_reg = $this->db->query("SELECT SUM(qty_do * harga) AS total FROM tr_do_po_detail WHERE tr_do_po_detail.no_do = '$row->no_do'")->row();                                                   
              echo mata_uang2($dt_do_reg->total + ($dt_do_reg->total * 0.1))."</td>             
              <td>$status</td>                                                        
              <td>";
              echo $tampil;                                 
              echo $tampil2;                                 
              echo $tampil3;                                 
              echo $tampil4;                                             
              echo "</td>                                          
              ";                                      
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
          <a href="h1/invoice_dealer_unit">            
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>            
              <th>No Faktur</th>                           
              <th>Tgl Faktur</th>              
              <th>No DO</th>              
              <th>Nama Customer</th>
              <th>Bank</th>
              <th>Tgl Cair</th>
              <th>Total</th> 
              <th>Status</th>             
              <th width='10%'>Aksi</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1;           
          foreach($dt_invoice->result() as $row) {  
            if($row->status_invoice == 'waiting approval'){
              $status = "<span class='label label-warning'>$row->status_invoice</span>";
              $tampil = "<a data-toggle=\"tooltip\" title=\"Approve Data\" onclick=\"return confirm('Are you sure to approve this data?')\" class=\"btn btn-success btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/approve?id=$row->no_do\">Approve</a>";
              $tampil2 = "<a data-toggle=\"tooltip\" title=\"Reject Data\" onclick=\"return confirm('Are you sure to reject this data?')\" class=\"btn btn-danger btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/reject?id=$row->no_do\">Reject</a>";
              $tampil3 = "";            
              $tampil4 = "";
            }elseif($row->status_invoice=='rejected' OR $row->status_invoice=='approved'){
              $status = "<span class='label label-danger'>$row->status_invoice</span>";
              $tampil2 = "";              
              $tampil4 = "<button type=\"button\" title=\"Input Tgl Cair\" class=\"btn btn-xs btn-primary btn-flat\"                   
                  onclick=\"input_tgl('$row->no_do')\">Tgl Cair</button>";                            
              $tampil = "";
              $tampil3 = "";
            }elseif($row->status_invoice=='printable'){
              $status = "<span class='label label-success'>$row->status_invoice</span>";
              $tampil2 = "";
              $tampil = "";
              $tampil4 = "";
              $tampil3 = "<a data-toggle=\"tooltip\" target=\"_blank\" title=\"Print Data\"  class=\"btn btn-warning btn-xs btn-flat\" href=\"h1/invoice_dealer_unit/cetak?id=$row->id_invoice_dealer\">Print</a>";
            }


            $rt = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();
            
          echo "          
            <tr>              
              <td>$no</td>              
              <td>$row->no_faktur</td>                            
              <td>$row->tgl_faktur</td>                            
              <td>
                <a href='h1/invoice_dealer_unit/view?id=$row->no_do'>
                  $row->no_do
                </a>
              </td>                            
              <td>$rt->nama_dealer</td>                            
              <td>$row->bank</td>                            
              <td>$row->tgl_cair</td>                            
              <td>";
              $total_harga = 0;
              $dt_do_reg = $this->db->query("SELECT SUM(qty_do * harga) AS total FROM tr_do_po_detail WHERE tr_do_po_detail.no_do = '$row->no_do'")->row();                                                   
              echo mata_uang2($dt_do_reg->total + ($dt_do_reg->total * 0.1))."</td>             
              <td>$status</td>                                                        
              <td>";
              echo $tampil;                                 
              echo $tampil2;                                 
              echo $tampil3;                                 
              echo $tampil4;                                             
              echo "</td>                                          
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
<div class="modal fade"  width="850px" id="modal_tagih">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Input Tanggal Tagih</h4>
      </div>
      <div class="modal-body">
          <form class="form-horizontal" action="h1/invoice_dealer_unit/save_tagih" method="post" enctype="multipart/form-data">                        
            <input type="hidden" class="form-control" id="no_do" name="no_do">
            <div class="box-body">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">No Invoice</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="no_invoice" placeholder="No Invoice" name="no_invoice">
                </div>
                <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="nama_dealer" placeholder="Nama Dealer" name="nama_dealer" readonly>
                </div>
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Bank</label>
                <div class="col-sm-4">
                  <select class="form-control" name="bank" id="bank">
                    <option value="">- choose -</option>
                    <?php 
                    $bank = $this->m_admin->getSortCond("ms_bank","bank","ASC");
                    foreach ($bank->result() as $isi) {
                      echo "<option value='$isi->bank'>$isi->bank</option>";
                    }
                    ?>
                  </select>
                </div>                
                <label for="inputEmail3" class="col-sm-2 control-label">Bunga Bank</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="bunga_bank" placeholder="Bunga Bank" name="bunga_bank">
                </div>
              </div>                 
              <div class="form-group">                
                <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Cair</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="tanggal2" placeholder="Tanggal Cair" name="tgl_cair">
                </div>
              </div>                               
            </div><!-- /.box-body -->
            <div class="box-footer">
              <button type="submit" name="s_process" value="simpan" class="btn btn-info">Simpan</button>                            
            </div><!-- /.box-footer -->
          </form>
      </div>      
    </div>
  </div>
</div>
<script type="text/javascript">
function input_tgl(id){    
  //alert(id);
  //Ajax Load data from ajax
  $.ajax({
      url : "<?php echo site_url('h1/invoice_dealer_unit/cari_data')?>",
      type:"POST",
      data:"id="+id,      
      success: function(msg)
      { 
          data=msg.split("|");
          $('[name="no_invoice"]').val(data[0]);          
          $('[name="nama_dealer"]').val(data[1]);                              
          $('[name="no_do"]').val(data[2]);                              
          $('#modal_tagih').modal('show'); // show bootstrap modal when complete loaded
          $('.modal-title').text('Input Tanggal Tagih'); // Set title to Bootstrap modal title

      },
      error: function (jqXHR, textStatus, errorThrown)
      {
          alert('Error get data from ajax');
      }
  });
}
</script>