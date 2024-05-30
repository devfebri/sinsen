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
            <!-- <button class="btn bg-maroon btn-flat margin"><i class="fa fa-refresh"></i> History</button> -->
            <a href="#aa" class="btn btn-flat btn-success"><i class="fa fa-send"></i> Send AHM</a>
            <a href="h1/indent_fips/download_excel" target="_blank" class="btn btn-flat btn-info"><i class="fa fa-export"></i> Download Excel</a>
            <a href="h1/indent_fips/download_history" target="_blank" class="btn btn-flat btn-info"><i class="fa fa-export"></i> History Indent</a>
            <a href="h1/indent_fips/download_sla_finco" target="_blank" class="btn btn-flat btn-warning"><i class="fa fa-export"></i> SLA Finco</a>
            <a href="h1/indent_fips/download_all_indent" target="_blank" class="btn btn-flat btn-warning"><i class="fa fa-export"></i> ALL Indent</a>
            <a href="h1/indent_fips/check_status" class="btn btn-flat btn-primary"><i class="fa fa-book"></i> Check Status SPK/ Indent</a>
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
          <form action="" method="get">
            <div class="col-sm-4">
              <input type="text" name="kd_dealer" placeholder="kode dealer" class="form-control">
            </div>
            <div class="col-sm-4">
              <input type="text" name="nm_dealer" placeholder="nama dealer" class="form-control">
            </div>
            <div class="col-sm-4">
              <input type="text" name="tipe" placeholder="tipe motor" class="form-control">
            </div>
            <div class="col-sm-2">
              <button type="submit" class="btn btn-flat btn-info">CARI</button>
            </div>
          </form>
        </div>
        <div class="table-responsive">
        
        <table class="table table-bordered" id="datatable">
          <thead>
            <tr>
              <th>No.</th>
              <th>Tanggal SPK</th>
              <th>No SPK</th>                  
              <th>Nama Dealer</th>        
              <th>Nama Konsumen</th>  
              <th>No HP</th>
              <th>Deskripsi Tipe</th>
              <th>Tipe</th>
              <th>Warna</th>
              <th>Tanda Jadi / Uang Muka</th>
              <th>Nama Finco</th>
              <th>Tgl PO</th>
              <th>No PO</th>
              <th>Status Indent</th>
              <th>Aging (Days)</th>
              <th>Option</th>
              
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>


        <script type="text/javascript">
          $(document).ready(function(e){
            $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
            {
                return {
                    "iStart": oSettings._iDisplayStart,
                    "iEnd": oSettings.fnDisplayEnd(),
                    "iLength": oSettings._iDisplayLength,
                    "iTotal": oSettings.fnRecordsTotal(),
                    "iFilteredTotal": oSettings.fnRecordsDisplay(),
                    "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                    "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                };
            };

           <?php if (isset($_GET['nm_dealer'])): ?>

              var base_url = "<?php echo base_url() ?>h1/indent_fips/get_data?<?php echo param_get() ?>";

            <?php else: ?>

              var base_url = "<?php echo base_url() ?>h1/indent_fips/get_data";

           <?php endif ?>
            
            $('#datatable').DataTable({
               "pageLength" : 10,
               "serverSide": true,
               "ordering": true, // Set true agar bisa di sorting
                "processing": true,
                "language": {
                  processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                  searchPlaceholder: "Pencarian..."
                },

               "order": [[2, "DESC" ]],
               "rowCallback": function (row, data, iDisplayIndex) {
                    var info = this.fnPagingInfo();
                    var page = info.iPage;
                    var length = info.iLength;
                    var index = page * length + (iDisplayIndex + 1);
                    $('td:eq(0)', row).html(index);
                },
               "ajax":{
                        url :  base_url,
                        type : 'POST'
                      },
            }); // End of DataTable


          }); 

        </script>


        </div>
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
              <td>No HP</td>
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
    }elseif($set=="check_status_spk"){
      ?>
  <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://lipis.github.io/bootstrap-sweetalert/dist/sweetalert.js"></script>
  <link rel="stylesheet" href="https://lipis.github.io/bootstrap-sweetalert/dist/sweetalert.css" /> -->
  
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h1/indent_fips">
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
          <div id="row2" class="row">
            <form action="h1/indent_fips/check_status" method="get">
              <div class="col-sm-1">
                <label>No. SPK</label>
              </div>
              <div class="col-sm-4">
                <!-- <select name="dealer" id="dealer" class="form-control select2"> -->
                <input type="text" id="no_spk" name="no_spk" placeholder="Cari No SPK" class="form-control">
                  </select>	
              </div>
              <div class="col-sm-2">
                <button type="submit" name="set" value="filter" class="btn btn-flat btn-info">Get Info</button>
              </div>
            </form>
          </div>
          <br>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <?php
        if ($status_search) {
      ?>
       <?php if ($data_search) { ?>
        <div class="box">
          <div class="box-header with-border">
            <h4 class="box-title">
                <p class="h4" style="margin:15px; text-align:justify;"><b>No. SPK : <?= $this->input->get('no_spk') ?> </b></p>               
            </h4>
          </div>
          <div class="box-body">
            <?php  if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {?>                  
              <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
                  <strong><?php echo $_SESSION['pesan'] ?></strong>
                  <button class="close" data-dismiss="alert">
                      <span aria-hidden="true">&times;</span>
                      <span class="sr-only">Close</span>  
                  </button>
              </div>
            <?php } $_SESSION['pesan'] = ''; ?>
            
           
              <?php foreach ($data_search_distinct as $ps) { ?> 
                <!-- <form action="<?php echo base_url(). 'indent_fips/create_indent'; ?>" method="post">  -->
                <div class="row">
                  <div class="col-sm-6">
                    <table>
                          <tr>
                            <input type="hidden" id="id_dealer2" value="<?php echo $ps->id_dealer ?>" name="id_dealer">
                            <td>Nama Konsumen</td>
                            <td>:</td>
                            <td><?php echo $ps->nama_konsumen ?></td>
                          </tr>
                          
                          <tr>
                            <td>Pembelian</td>
                            <td>:</td>
                            <td><?php echo $ps->jenis_beli ?></td>
                          </tr>
                          <tr>
                            <td>Status SPK</td>
                            <td>:</td>
                            <td>
                              <?php if($ps->status_spk == 'approved'){ ?>
                                <span class="label label-pill label-success"><?php echo ucwords($ps->status_spk) ?></span></td>
                              <?php }else if($ps->status_spk == 'close'){ ?>
                                <span class="label label-pill label-warning"><?php echo ucwords('Close / SSU') ?></span></td>
                              <?php }else{ ?>
                                <span class="label label-pill label-warning"><?php echo ucwords($ps->status_spk) ?></span></td>
                              <?php } ?>
                          </tr>
                          <tr>
                            <td>Tanda Jadi</td>
                            <td>:</td>
                            <td><?php echo number_format($ps->tanda_jadi, 0, ",", "."); ?></td>
                          </tr>
                          <tr>
                            <td>Nomor Mesin Sementara</td>
                            <td>:</td>
                            <td><?php echo $ps->no_mesin_spk ?></td>
                          </tr>
                         
                    </table>
                  </div>
                  <!-- <div class="col-sm-6">
                    <table>
                          <tr>
                            <td>No. Indent</td>
                            <td>:</td>
                            <td><?php echo $pd->id_indent ?></td>
                          </tr>
                          <tr>
                            <td>Status Indent</td>
                            <td>:</td>
                            <td><span class="badge badge-success"><?php echo ucwords($pd->status_indent) ?><span></td>
                          </tr>
                    </table> -->
                    
                  <!-- </div> -->
                </div>
              <?php } ?>
              <hr>
              <table class="table">
                      <tr>
                        <td>No Indent</td>
                        <td>Tipe Motor</td>
                        <td>Deskripsi Motor</td>
                        <td>Kode Warna</td>
                          <?php foreach ($data_search_distinct as $ps) { ?>
                          <?php if ($ps->jenis_beli=='Kredit'){ ?>
                              <td>Nama Finco</td>
                              <td>Tgl PO</td>
                              <td>No PO</td>
                          <?php }?>
                          <?php } ?>
                        <td>Keterangan</td> 
                      </tr>
                      <?php foreach ($data_search as $pd) { ?> 
                      <tr>
                        <td><?php echo $pd->id_indent ?></td>
                        <td><?php echo $pd->id_tipe_kendaraan ?></td>
                        <td><?php echo $pd->tipe_ahm ?></td>
                        <td><?php echo $pd->id_warna ?></td>
                        <?php if ($pd->jenis_beli=='Kredit' && $pd->id_reasons ==''){ ?>
                              <td><?php echo $pd->finance_company ?></td>
                              <td><?php echo $pd->tgl_pembuatan_po ?></td>
                              <td><?php echo $pd->po_dari_finco ?></td>
                        <?php }elseif($pd->jenis_beli=='Kredit' && $pd->id_reasons !=''){ ?> 
                              <td></td><td></td><td></td>
                        <?php }?>
                        <td>
                          <?php if($pd->status_indent=='Belum Indent'){ 
                          if ($pd->jenis_beli=='Kredit'&& $pd->po_dari_finco ==''&& $pd->tanda_jadi==0){ ?>
                            <span class="label label-pill label-warning">No PO Leasing belum diinput dan Tanda Jadi Rp.0<span>
                          <?php }else if($pd->tanda_jadi==0){ ?>
                            <span class="label label-pill label-warning">Tanda Jadi Rp. 0<span>
                          <?php }elseif($pd->jenis_beli=='Kredit'&& $pd->po_dari_finco ==''){?>
                            <span class="label label-pill label-warning">No PO Leasing belum diinput<span>
                          <?php }?>
                          <?php }elseif($pd->jenis_beli=='Kredit'&& $pd->po_dari_finco =='' && $pd->id_reasons ==''){?>
                            <span class="label label-pill label-warning">No PO Leasing belum diinput<span>
                          <?php }elseif ($pd->status_indent=='Dealer Batal SPK' || $ps->status_spk =='canceled'){ ?>
                          <span class="label label-pill label-danger"><?php echo ucwords('Dealer Batal Indent/ SPK') ?><span>
                          <?php }elseif ($pd->id_reasons !='' && $ps->status_spk !='canceled'){ ?>
                          <span class="label label-pill label-danger"><?php echo ucwords('Perubahan Data SPK') ?><span>
                          <?php }else{ ?>
                          <span class="label label-pill label-primary"><?php echo ucwords($pd->status_indent) ?><span>
                          <?php }?>
                        </td>
                        
                        </tr>
                      <?php } ?>
                      
                </table>
                <br>
                      <div class="container">
                        <div class="row justify-content-center">
                          <div class="col">
                            <!-- Button Create Indent -->
                            <?php if($pd->no_mesin_spk != null && $pd->id_indent == null && $pd->tanda_jadi!=0){
                              if($pd->jenis_beli=='Kredit'&& $pd->po_dari_finco =='') {?>
                                <span></span>
                                <!-- <button type="submit" name="set" value="create" class="btn3 btn btn-flat btn-warning btn-sm">Create Indent</button> -->
                              <?php }else{?>
                                <button type="submit" name="set" value="create" class="btn3 btn btn-flat btn-warning btn-sm" data-no="<?= $this->input->get('no_spk') ?>" data-no2="<?= $pd->id_dealer ?>">Create Indent</button>
                              <?php }?>
                            <?php }else if($pd->status_indent=='Dealer Batal SPK'){ ?>
                                <button type="submit" name="set" value="create" class="btn3 btn btn-flat btn-warning btn-sm" data-no="<?= $this->input->get('no_spk') ?>" data-no2="<?= $pd->id_dealer ?>">Create Indent</button>
                              <?php }?>
  
                            <!-- Button Re-Fulfilled -->
                            <?php if($pd->status_indent=='Sudah Dipenuhi' && ($pd->status_spk!='close' && $pd->status_spk!='canceled' && $pd->status_spk!='rejected')){ ?>
                            <a type="submit" data-no3="<?php echo $pd->id_indent ?>" name="set" value="reopen" class="btn2 btn-flat btn-info btn-sm">Re-Fulfilled Indent</a>
                            <?php }?>
                          </div>
                        </div>
                      </div>
            <?php } else { ?>
                <h4>No SPK tidak ditemukan / Sudah di-SSU kan</h4>
            <?php } ?>
            <!-- </form>	 -->
          </div><!-- /.box-body -->
        </div><!-- /.box -->
     <?php } ?>
        <?php
        }
        ?>
  </section>
</div>


<script type="text/javascript">
// $(document).ready(function() {
//     $.ajax({
//         beforeSend: function() {
//           $('#showSPK').html('<tr><td colspan=8 style="font-size:12pt;text-align:center">Processing...</td></tr>');
//         },
//         url: "<?php echo site_url('dashboard/history_indent')?>",
//         type:"POST",
//         data:"",            
//         cache:false,
//         success:function(response){                
//            $('#showSPK').html(response);
//            datatables();
//         } 
//     })
// });

$(document).on('click', '.btn2', function(){
      const id_indent = $(this).data('no3');
      
      if(confirm('Apakah Anda yakin akan Refullfill data indent ?'))
          {
            $.ajax({
              type: 'GET',
              url: "<?php echo base_url('h1/Indent_fips/reFullFill')?>",
              data: {id_indent: id_indent },
              dataType: 'json',
              // data : data,
              success: function (data) {
                alert(data);
                // console.log(data);
                window.location = "<?php echo base_url('h1/indent_fips/check_status')?>";
                // swal("Done!", "It was succesfully created!", "success");
              },
              error: function (errs) { 
                console.log(errs) }
            });
          }
     
    });

  
    $(document).on('click', '.btn3', function(){
      const no_spk = $(this).data('no');
      const id_dealer = $(this).data('no2');
        if(confirm('Apakah Anda yakin ingin Create data Indent ?'))
          {
              $.ajax({
                type: 'GET',
                url: "<?php echo base_url('h1/Indent_fips/create_indent')?>",
                data: {no_spk: no_spk, id_dealer:id_dealer },
                dataType: 'json',
                // data : data,
                success: function (data) {
                  alert(data);
                  // console.log(data);
                  window.location = "<?php echo base_url('h1/indent_fips/check_status')?>";
                  // swal("Done!", "It was succesfully created!", "success");
                },
                error: function (errs) { 
                  console.log(errs) }
              });
          }
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