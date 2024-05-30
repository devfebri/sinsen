<?php 
$mode = '';
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
    <li class="">Pembelian</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <?php 
    if($set=="insert"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/po_d">
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

            $id_user = $this->session->userdata("id_user");
            $sql = $this->db->query("SELECT * FROM ms_user INNER JOIN ms_karyawan_dealer ON ms_user.id_karyawan_dealer = ms_karyawan_dealer.id_karyawan_dealer 
              INNER JOIN ms_dealer ON ms_karyawan_dealer.id_dealer = ms_dealer.id_dealer 
              WHERE ms_user.id_user = '$id_user'")->row();
                
        ?>
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="dealer/po_d/save" method="post" enctype="multipart/form-data">
              <div class="box-body">              
                <div class="form-group">
                  <!-- <label for="inputEmail3" class="col-sm-2 control-label">No.PO</label>
                  <div class="col-sm-4"> -->
                    <input type="hidden" required class="form-control"  id="id_po" readonly placeholder="No.PO" name="id_po">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                    <input type="hidden" id="mode" value="new">
                  <!-- </div> -->
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">
                    <input id="jenis_po" name="jenis_po" onchange="cek_jenis()" readonly value="<?php echo $jenis ?>" class="form-control">                    
                  </div>
                <!-- </div> 
                <div class="form-group"> -->
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $sql->nama_dealer ?>" placeholder="Dealer" name="delaer">
                    <input type="hidden" name="id_dealer" value="<?php echo $sql->id_dealer ?>">
                  </div>
                </div>                                                                                     
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bulan" id="bulan" onchange="cek_bulan()">
                      <option value="<?php echo date("m") ?>"><?php echo bln() ?></option>
                      <option value="1">Januari</option>
                      <option value="2">Februari</option>
                      <option value="3">Maret</option>
                      <option value="4">April</option>
                      <option value="5">Mei</option>
                      <option value="6">Juni</option>
                      <option value="7">Juli</option>
                      <option value="8">Agustus</option>
                      <option value="9">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="tahun" id="tahun" onchange="cek_bulan()">
                      <option><?php echo date("Y") ?></option>
                      <?php 
                      $y = date("Y");
                      for ($i=$y - 10; $i <= $y + 10; $i++) { 
                        echo "<option>$i</option>";
                      }
                      ?>                          
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                </div>                  

                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_po"></span>                                                                                  
                  
                  
                </div>                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="button" onClick="cancel_tr()" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel All</button>                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    
    

    <?php 
    }elseif($set=="edit"){
      $row = $dt_po->row();

      $id_dealer = $row->id_dealer;
      $sql = $this->db->query("SELECT * FROM ms_dealer WHERE ms_dealer.id_dealer = '$id_dealer'")->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/po_d">
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
            <form class="form-horizontal" action="dealer/po_d/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_po ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.PO</label>
                  <div class="col-sm-4">
                    <input type="text" required value="<?php echo $row->id_po ?>" readonly class="form-control"  id="id_po" placeholder="No.PO" name="id_po">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                    <input type="hidden" id="mode" value="edit">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">
                    <input id="jenis_po" name="jenis_po" onchange="cek_jenis()" readonly value="<?php echo $row->jenis_po ?>" class="form-control">                    
                  </div>
                </div>                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $sql->nama_dealer ?>" placeholder="Dealer" name="delaer">
                    <input type="hidden" name="id_dealer" value="<?php echo $sql->id_dealer ?>">
                  </div>
                </div>                                                                                                                                                         
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bulan" readonly id="bulan">
                      <option value="<?php echo $row->bulan ?>"><?php echo $row->bulan ?></option>                                            
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-4">
                    <select class="form-control" readonly name="tahun" id="tahun">
                      <option value="<?php echo $row->tahun ?>"><?php echo $row->tahun ?></option>                      
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->ket ?>" class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                </div>                    

                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_po"></span>                                                                                  
                  
                  
                </div>                                                 
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                  <button type="submit" onclick="return confirm('Are you sure to update all data?')" name="process" value="edit" class="btn btn-info btn-flat"><i class="fa fa-edit"></i> Update</button>                
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Reset</button>                                
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php 
    }elseif($set=="detail"){
      $row = $dt_po->row();

      $id_dealer = $row->id_dealer;
      $sql = $this->db->query("SELECT * FROM ms_dealer WHERE ms_dealer.id_dealer = '$id_dealer'")->row(); 
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/po_d">
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
            <form class="form-horizontal" action="dealer/po_d/update" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $row->id_po ?>" />
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">No.PO</label>
                  <div class="col-sm-4">
                    <input type="text" required value="<?php echo $row->id_po ?>" readonly class="form-control"  id="id_po" placeholder="No.PO" name="id_po">
                    <input type="hidden" id="tgl" value="<?php echo date("d-M-y") ?>">
                    <input type="hidden" id="mode" value="detail">
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Jenis PO</label>
                  <div class="col-sm-4">
                    <input id="jenis_po" name="jenis_po" onchange="cek_jenis()" readonly value="<?php echo $row->jenis_po ?>" class="form-control">                    
                  </div>
                </div>                  
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" readonly class="form-control" value="<?php echo $sql->nama_dealer ?>" placeholder="Dealer" name="delaer">
                    <input type="hidden" name="id_dealer" value="<?php echo $sql->id_dealer ?>">
                  </div>
                </div>                                                                                                                                                         
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Bulan</label>
                  <div class="col-sm-4">
                    <select class="form-control" name="bulan" readonly id="bulan">
                      <option value="<?php echo $row->bulan ?>"><?php echo $row->bulan ?></option>                                            
                    </select>
                  </div>
                  <label for="inputEmail3" class="col-sm-2 control-label">Tahun</label>
                  <div class="col-sm-4">
                    <select class="form-control" readonly name="tahun" id="tahun">
                      <option value="<?php echo $row->tahun ?>"><?php echo $row->tahun ?></option>                      
                    </select>
                  </div>
                </div>                                            
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                  <div class="col-sm-10">
                    <input type="text" value="<?php echo $row->ket ?>" readonly class="form-control" id="inputEmail3" placeholder="Keterangan" name="ket">
                  </div>
                </div>    
                

                <hr>                
                <div class="form-group">
                                    
                  
                  <span id="tampil_po"></span>                                                                                  
                  
                  
                </div>                                                 
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
          <a href="dealer/po_d/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>          
          
          <!--a href="dealer/po_d/download(20171000004)">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Download</button>
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
              <th>No.PO</th>              
              <th>Jenis PO</th>
              <th>Bulan</th>              
              <th>Tahun</th>
              <th width="13%">Action</th>
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_po->result() as $row) {   
            
            $edit = $this->m_admin->set_tombol($id_menu,$group,'edit');
            $delete = $this->m_admin->set_tombol($id_menu,$group,'delete');            
            $approval = $this->m_admin->set_tombol($id_menu,$group,'approval');            

            if($row->status=='stay'){
              $status = "<span class='label label-warning'>$row->status</span>";
              $tombol = "<button title='Approval' $approval type=\"button\" class=\"btn btn-warning btn-xs btn-flat\" data-toggle=\"modal\" data-target=\".modal_detail\" onclick=\"detail_popup('$row->id_po')\"><i class=\"fa fa-eye\"></i></button>
                        <a data-toggle=\"tooltip\" $edit title=\"Edit Data\" class=\"btn btn-primary btn-xs btn-flat\" href=\"dealer/po_d/edit?id=$row->id_po\"><i class=\"fa fa-edit\"></i></a>
                        <a data-toggle=\"tooltip\" $delete title=\"Delete Data\" onclick=\"return confirm('Are you sure to delete this data?')\" class=\"btn btn-danger btn-xs btn-flat\" href=\"dealer/po_d/delete?id=$row->id_po\"><i class=\"fa fa-trash-o\"></i></a>
                        

                        ";
            }else{
              $status = "<span class='label label-success'>$row->status</span>";
              $tombol = "";
            } 

            $cek_ap = $this->db->query("SELECT * FROM tr_do_po WHERE no_po = '$row->id_po' AND status = 'approved'")->num_rows();
            $cek_re = $this->db->query("SELECT * FROM tr_do_po WHERE no_po = '$row->id_po' AND status = 'rejected'")->num_rows();
            $cek = $this->db->query("SELECT * FROM tr_picking_list INNER JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
                    WHERE tr_do_po.no_po = '$row->id_po' AND (tr_picking_list.status = 'proses' OR tr_picking_list.status = 'close' OR tr_picking_list.status = 'input')");
            if($cek->num_rows() > 0){
              $ket = "<span class='pull-right-container'>
                        <small class='label pull-right bg-green'>PL Process</small>
                      </span>";
            }elseif($cek_ap > 0){
              $ket = "<span class='pull-right-container'>
                        <small class='label pull-right bg-green'>Approved By MD</small>
                      </span>";
            }elseif($cek_re > 0){
              $ket = "<span class='pull-right-container'>
                        <small class='label pull-right bg-green'>Rejected By MD</small>
                      </span>";
            }else{
              $ket = "";                        
            }
          echo "          
            <tr>
              <td>$no</td> ";
              /*echo "<td>
                <a href=dealer/po_d/detail?id=$row->id_po>
                  $row->id_po
                </a>" */;
              echo "<td>
                <a href=dealer/po_d/detail?id=$row->id_po>
                  $row->id_po $ket
                </a></td>
              <td>$row->jenis_po</td>
              <td>$row->bulan</td>
              <td>$row->tahun</td>              
              <td align='center'>";
              echo $tombol;
              ?>
                <!--a data-toggle='tooltip' title="Download PO" class="btn btn-warning btn-sm btn-flat" href="dealer/po_d/download?id=<?php echo $row->id_po ?>"><i class="fa fa-download"></i></a-->
                
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
    }
    ?>
  </section>
</div>


<div class="modal fade" id="Itemmodal">      
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Search Item
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>ID Item</th>
              <th>Tipe Kendaraan</th>                                    
              <th>Warna</th>                                               
              <th width="1%"></th>
            </tr>
          </thead>
          <tbody>
          <?php
          $no = 1; 
          foreach ($dt_item->result() as $ve2) {
            echo "
            <tr>
              <td>$no</td>
              <td>$ve2->id_item</td>
              <td>$ve2->tipe_ahm</td>
              <td>$ve2->warna</td>";
              ?>
              <td class="center">
                <button title="Choose" data-dismiss="modal" onclick="chooseitem('<?php echo $ve2->id_item; ?>')" class="btn btn-flat btn-success btn-sm"><i class="fa fa-check"></i></button>                 
              </td>           
            </tr>
            <?php
            $no++;
          }
          ?>
          </tbody>
        </table>
      </div>      
    </div>
  </div>
</div>

<?php if ($set=='view') { ?>
  <!-- Modal Detail -->
  <div class="modal fade modal_detail">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Detail Purchase Order (PO)</h4>
        </div>
        <div class="modal-body" id="show_detail">
        </div>
        <div class="modal-footer">
           <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  <!-- End Of Modal Detail -->
 <?php } ?>

<script type="text/javascript">
function cek_jenis(){
  var jenis_po = document.getElementById("jenis_po").value;
  if(jenis_po == 'PO Reguler'){
    kirim_data_po_reg();
  }else if(jenis_po == 'PO Additional'){
    kirim_data_po_add();
  }
}
function cek_bulan(){
  var bulan = document.getElementById("bulan").value;
  var tahun = document.getElementById("tahun").value;
  //$("#jenis_po").val(bulan);
  $.ajax({
      url : "<?php echo site_url('dealer/po_d/cari_jenis')?>",
      type:"POST",
      data:"bulan="+bulan+"&tahun="+tahun,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        $("#jenis_po").val(data[0]);
        cek_jenis();                        
      }        
  })
}
function auto(){
  var po_js=document.getElementById("tgl").value; 
  $.ajax({
      url : "<?php echo site_url('dealer/po_d/cari_id')?>",
      type:"POST",
      data:"po="+po_js,   
      cache:false,   
      success: function(msg){ 
        data=msg.split("|");
        if(data[1] != 'nihil'){
          $("#id_po").val(data[1]);                                            
        }else{
          $("#id_po").val(data[0]);                            
        }        
        cek_jenis();                   
      }        
  })
}
function cancel_tr(){
  var id_po_js=document.getElementById("id_po").value; 
  if (confirm("Are you sure to cancel this transaction...?") == true) {
      $.ajax({
        url : "<?php echo site_url('dealer/po_d/cancel_po')?>",
        type:"POST",
        data:"id_po="+id_po_js,   
        cache:false,   
        success: function(msg){ 
          window.location.reload();
        }        
    })
  }else{
    return false;
  }  
}
function chooseitem(id_item){
  document.getElementById("id_item").value = id_item; 
  cek_item();
  $("#Itemmodal").modal("hide");
}
function cek_item(){
  var id_item_js  = $("#id_item").val();                       
  var bulan       = $("#bulan").val();                       
  var tahun       = $("#tahun").val();                       
  $.ajax({
      url: "<?php echo site_url('dealer/po_d/cek_item')?>",
      type:"POST",
      data:"id_item="+id_item_js+"&bulan="+bulan+"&tahun="+tahun,            
      cache:false,
      success:function(msg){                
          data=msg.split("|");
          if(data[0]=="ok"){          
            $("#id_item").val(data[1]);                
            $("#tipe").val(data[2]);                
            $("#warna").val(data[3]);
            $("#on_hand").val(data[4]);                        
            $("#qty_niguri_fix").val(data[5]);                        
            $("#qty_po_fix").val(data[6]);
            $("#qty_po_t1").val(data[7]);
            $("#qty_po_t2").val(data[8]);
          }else{
            alert(data[0]);
          }
      } 
  })
}
function hide_po(){
    $("#tampil_po").hide();
}
function kirim_data_po_reg(){    
  $("#tampil_po").show();
  var id_po = document.getElementById("id_po").value;   
  var mode = document.getElementById("mode").value;   
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_po="+id_po+"&mode="+mode;                           
     xhr.open("POST", "dealer/po_d/t_po_reg", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_po").innerHTML = xhr.responseText;
                getSelect2();
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function kirim_data_po_add(){    
  $("#tampil_po").show();
  var id_po = document.getElementById("id_po").value;
  var mode = document.getElementById("mode").value;      
  var xhr;
  if (window.XMLHttpRequest) { // Mozilla, Safari, ...
    xhr = new XMLHttpRequest();
  }else if (window.ActiveXObject) { // IE 8 and older
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
  } 
   //var data = "birthday1="+birthday1_js;          
    var data = "id_po="+id_po+"&mode="+mode;                           
     xhr.open("POST", "dealer/po_d/t_po_add", true); 
     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
     xhr.send(data);
     xhr.onreadystatechange = display_data;
     function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {       
                document.getElementById("tampil_po").innerHTML = xhr.responseText;
            }else{
                alert('There was a problem with the request.');
            }
        }
    } 
}
function tes(){
  alert("hello");
}
function simpan_po(){
  var jenis_po = document.getElementById("jenis_po").value;
  if(jenis_po == 'PO Reguler'){
    var id_po               = document.getElementById("id_po").value;   
    var id_item             = document.getElementById("id_item").value;   
    var qty_po_fix          = document.getElementById("qty_po_fix").value;   
    var qty_po_t1           = document.getElementById("qty_po_t1").value;   
    var qty_po_t2           = document.getElementById("qty_po_t2").value;
    var bulan               = $("#bulan").val();                       
    var tahun               = $("#tahun").val();                                 
    //alert(id_po);
    if (id_po == "" || id_item == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }else{
        $.ajax({
            url : "<?php echo site_url('dealer/po_d/save_po_reg')?>",
            type:"POST",
            data:"id_po="+id_po+"&id_item="+id_item+"&qty_po_fix="+qty_po_fix+"&qty_po_t1="+qty_po_t1+"&qty_po_t2="+qty_po_t2+"&bulan="+bulan+"&tahun="+tahun,
            cache:false,
            success:function(msg){            
                data=msg.split("|");
                if(data[0]=="nihil"){
                    kirim_data_po_reg();
                    kosong();                
                }else if(data[0]=="niguri"){
                    alert("Qty PO Fix item ini melebihi QTY Niguri Fix");
                    $("#qty_po_fix").val("");
                }else if(data[0]=="po_fix"){
                    alert("Qty PO Fix item ini melebihi batas maksimum yang telah ditentukan");
                    $("#qty_po_fix").val("");
                }else if(data[0]=="po_t1"){
                    alert("Qty PO T1 item ini melebihi batas maksimum yang telah ditentukan");
                    $("#qty_po_t1").val("");
                }else{
                    alert(data[0]);
                    kosong();                      
                }                
            }
        })    
    }
  }else if(jenis_po == 'PO Additional'){
    var id_po               = document.getElementById("id_po").value;   
    var id_item             = document.getElementById("id_item").value;   
    var qty_order           = document.getElementById("qty_order").value;       
    //alert(id_po);
    if (id_po == "" || id_item == "") {    
        alert("Isikan data dengan lengkap...!");
        return false;
    }
      else if (qty_order==0) {    
        alert("Qty Order Tidak Boleh sama dengan 0");
        return false;
    }

    else{
      $.ajax({
          url : "<?php echo site_url('dealer/po_d/save_po_add')?>",
          type:"POST",
          data:"id_po="+id_po+"&id_item="+id_item+"&qty_order="+qty_order,
          cache:false,
          success:function(msg){            
              data=msg.split("|");
              if(data[0]=="nihil"){
                  kirim_data_po_add();
                  kosong();                
              }else{
                  alert('Item ini sudah ditambahkan');
                  kosong();                      
              }                
          }
      })    
    }
  }
}

function kosong(args){
  $("#id_item").val("");
  $("#warna").val("");   
  $("#tipe").val("");   
  $("#qty_po_t1").val("");   
  $("#qty_po_t2").val("");   
  $("#qty_order").val("");   
  $("#qty_po_fix").val("");   
}
function hapus_po(a,b){ 
    var id_po_detail  = a;   
    var id_item   = b;       
    $.ajax({
        url : "<?php echo site_url('dealer/po_d/delete_po')?>",
        type:"POST",
        data:"id_po_detail="+id_po_detail,
        cache:false,
        success:function(msg){            
            data=msg.split("|");
            if(data[0]=="nihil"){
              cek_jenis();
            }
        }
    })
}

function detail_popup(id_po)
  {
        $.ajax({
              beforeSend: function() { $('#loading-status').show(); },
             url:"<?php echo site_url('dealer/po_d/detail_popup');?>",
             type:"POST",
             data:"id_po="+id_po,
             cache:false,
             success:function(html){
                $('#loading-status').hide();
                $("#show_detail").html(html);
             }
        });
  }

  function getInput()
   {
    var value={id_tipe_kendaraan:$('#id_tipe_kendaraan').val(),
               }
      $.ajax({
           beforeSend: function() { $('#loading-status').show(); },
           url:"<?php echo site_url('dealer/po_d/getInputPoReg')?>",
           type:"POST",
           data:value,
           cache:false,
           success:function(html){
              $('#loading-status').hide();
              if (html=='kosong') {
                alert('Data Tidak Ditemukan');
              }
              else
              {
                $('#showInput').html(html);
                getSelect2();
              }
           },
           statusCode: {
        500: function() {
          $('#loading-status').hide();
          alert("Something Wen't Wrong");
        }
      }
      });
   }
</script>