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
<body onload="auto()">
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
    <li class="">Kontrol Unit</li>
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
          <!--a href="h1/kekurangan_ksu/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
        <table id="example2" class="table table-bordered table-hover table-striped">
          <thead>
            <tr>              
              <th width="5%">No</th>
              <th>Action</th>
              <th>Kode Item</th>                            
              <th>Tipe</th>              
              <th>Warna</th>              
              <th>Qty Stok</th>        
              <th>Qty Soft Book</th>      
              <th>Qty Hard Book</th>      
              <th>Qty Unfill</th>
              <th>Qty Intransit</th>              
            </tr>           
          </thead>
          <tbody>            
          <?php 
          $no=1;
          $t_qty=0;$t_unfill=0;$t_in=0;$t_book=0;$t_hard_book=0;
          foreach($dt_list->result() as $row) {   
            $id_dealer = $this->m_admin->cari_dealer();            
            $cek_qty = $this->db->query("SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
                LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
                LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
                LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
                LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
                LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer                
                WHERE tr_scan_barcode.id_item = '$row->id_item' 
                AND tr_penerimaan_unit_dealer.id_dealer = '$id_dealer' 
                AND tr_scan_barcode.status = '4' AND tr_penerimaan_unit_dealer.status = 'close' AND tr_penerimaan_unit_dealer_detail.retur = '0'
                AND (tr_penerimaan_unit_dealer_detail.status_on_spk IS NULL OR tr_penerimaan_unit_dealer_detail.status_on_spk = '(NULL)')
                -- AND tr_penerimaan_unit_dealer_detail.no_mesin NOT IN (SELECT no_mesin_spk FROM tr_spk WHERE no_mesin_spk IS NOT NULL)
                ")->row();    
            $cek_unfill = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
                            INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                            WHERE tr_do_po_detail.id_item = '$row->id_item' AND tr_do_po.id_dealer = '$id_dealer' AND tr_do_po_detail.qty_do > 0 AND tr_do_po.status = 'approved'")->row();                        
            $cek_unfill_kurang = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.id_item) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                             
                            WHERE tr_surat_jalan_detail.id_item = '$row->id_item' AND tr_surat_jalan.id_dealer = '$id_dealer' AND tr_surat_jalan_detail.ceklist = 'ya' AND tr_surat_jalan_detail.retur = 0")->row();                        
            $cek_unfill_real = $cek_unfill->jum - $cek_unfill_kurang->jum;

            // $cek_unfill3 = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
            //                 INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
            //                 WHERE tr_picking_list.no_picking_list NOT IN (SELECT tr_surat_jalan.no_picking_list FROM tr_surat_jalan WHERE no_picking_list IS NOT NULL) AND
            //                 tr_do_po_detail.id_item = '$row->id_item' AND tr_do_po.id_dealer = '$id_dealer' AND tr_do_po_detail.qty_do > 0 AND tr_do_po.status = 'approved'")->row();                        
            // $cek_unfill2 = $this->db->query("SELECT SUM(tr_do_po_detail.qty_do) AS jum FROM tr_do_po INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
            //             WHERE tr_do_po.no_do NOT IN (SELECT no_do FROM tr_picking_list WHERE no_do IS NOT NULL) 
            //             AND tr_do_po_detail.id_item = '$row->id_item' AND tr_do_po.id_dealer = '$id_dealer'")->row();
            $cek_in = $this->db->query("SELECT COUNT(tr_surat_jalan_detail.no_mesin) AS jum FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
                        WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)
                        AND tr_surat_jalan_detail.id_item = '$row->id_item' AND tr_surat_jalan.id_dealer = '$id_dealer'")->row();
            $soft = $this->db->query("SELECT count(tpud.no_mesin) AS c FROM tr_penerimaan_unit_dealer_detail AS tpud
                JOIN tr_penerimaan_unit_dealer AS tpu ON tpud.id_penerimaan_unit_dealer=tpu.id_penerimaan_unit_dealer
                JOIN tr_scan_barcode ON tpud.no_mesin=tr_scan_barcode.no_mesin
                WHERE tpud.status_on_spk='booking' AND id_dealer=$id_dealer AND id_item='$row->id_item'
                 AND tr_scan_barcode.status = '4' AND tpu.status = 'close'
                 ")->row()->c;
              $t_book+=$soft;
               $hard = $this->db->query("SELECT count(tpud.no_mesin) AS c FROM tr_penerimaan_unit_dealer_detail AS tpud
                JOIN tr_penerimaan_unit_dealer AS tpu ON tpud.id_penerimaan_unit_dealer=tpu.id_penerimaan_unit_dealer
                JOIN tr_scan_barcode ON tpud.no_mesin=tr_scan_barcode.no_mesin
                WHERE tpud.status_on_spk='hard_book' AND id_dealer=$id_dealer AND id_item='$row->id_item'
                 AND tr_scan_barcode.status = '4' AND tpu.status = 'close'
                  ")->row()->c;
               $t_hard_book+=$hard;

            if($cek_qty->jum > 0 or $cek_unfill_real > 0 OR $cek_in->jum > 0 OR $soft>0 OR $hard>0){
              echo "
              <tr>
                <td>$no</td>
                <td>
                  <a href='dealer/real_stock/detail?id=$row->id_item'>
                    <button type='button' title='Detail' class='btn bg-maroon btn-flat btn-sm'><i class='fa fa-eye'></i> Detail</button>
                  </a>
                </td>
                <td>$row->id_item</td>              
                <td>$row->tipe_ahm</td>
                <td>$row->warna</td>
                <td>$cek_qty->jum</td>
                <td>$soft</td>
                <td>$hard</td>
                <td>$cek_unfill_real</td>
                <td>$cek_in->jum</td>              
              </tr>
              ";
              $t_qty = $t_qty + $cek_qty->jum;
              $t_unfill = $t_unfill + $cek_unfill_real;
              $t_in = $t_in + $cek_in->jum;
              $no++;          
            }
          }
          ?>
          </tbody>    
          <tfoot>
            <tr>
              <td colspan="5">Total</td>
              <td><?php echo $t_qty ?></td>
              <td><?php echo $t_book ?></td>
              <td><?php echo $t_hard_book ?></td>
              <td><?php echo $t_unfill ?></td>
              <td><?php echo $t_in ?></td>
            </tr>
          </tfoot>
        </table>          
                              
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }elseif($set=="view_fix"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/kekurangan_ksu/add">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
        <table id="table" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <td width="5%">No</td>
              <td>Action</td>
              <td>Kode Item</td>              
              <td>Kode Dealer</td>              
              <td>POS Dealer</td>                            
              <td>Tipe</td>              
              <td>Warna</td>              
              <td>Qty Stok</td>              
              <td>Qty Unfill</td>
              <td>Qty Intransit</td>              
            </tr>                       
          </thead>
          <tbody>            
          </tbody>    
        </table>  
        
                              
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }elseif($set=="detail"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="dealer/real_stock">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-chevron-left"></i> Back</button>
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
        
        $row = $this->db->query("SELECT * FROM ms_item INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
          WHERE ms_item.id_item = '$id_item'")->row();
        $ms = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer)->row();      
       
        ?>
        <div class="col-md-12">
          <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
            <div class="box-body">              
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Kode Item</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->id_item ?>" id="id_niguri" readonly placeholder="Kode Item" name="kode_item">
                </div>              
                <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $ms->nama_dealer ?>" id="id_niguri" readonly placeholder="QTY RFS" name="qty_rfs">                  
                </div>                 
              </div>
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Tipe</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->tipe_ahm ?>" id="id_niguri" readonly placeholder="Tipe" name="kode_item">
                </div>              
                <!-- <label for="inputEmail3" class="col-sm-2 control-label">QTY</label>
                <div class="col-sm-4">
                  <input type="text" required class="form-control" value="<?php echo $row->jum ?>" id="id_niguri" readonly placeholder="QTY NRFS" name="qty_nrfs">                  
                </div>                  -->
              </div>
                                            
              
              <div class="form-group">                
                <button class="btn btn-block btn-success btn-flat" type="button">Detail</button>
                <table id="stok_detail" class="table table-bordered table-hover">
                  <thead>
                    <tr>                                    
                      <th>No Mesin</th>
                      <th>No Rangka</th>     
                      <th>FIFO</th>                               
                      <th>Status</th> 
                      <th>Status Stok</th>                                   
                    </tr>            
                  </thead>
                  <tbody>            
                  <?php                   
                  foreach($dt_pu->result() as $row) {                       
                     if ($row->status != 5) {
                          if($row->status == 4){
                            $status = "<span class='label label-primary'>Ready</span>";
                            if ($row->status_on_spk=='booking') {
                              $status = "<span class='label label-primary'>Soft Booking</span>";
                            }
                             if ($row->status_on_spk=='hard_book') {
                              $status = "<span class='label label-primary'>Hard Booking</span>";
                            }
                          }elseif($row->status == 5){
                            $status = "<span class='label label-success'>Booking</span>";
                          }elseif($row->status == 6){
                            $status = "<span class='label label-danger'>Retur to Dealer</span>";
                          }elseif($row->status == 7){
                            $status = "<span class='label label-danger'>Retur to MD</span>";
                          }elseif($row->status == 3){
                            $status = "<span class='label label-warning'>Intransit</span>";
                          }else{
                            $status = "<span class='label label-danger'>Unknown</span>";
                          }
                          
                        echo "
                        <tr>
                          <td>$row->no_mesin</td>
                          <td>$row->no_rangka</td>  
                          <td>$row->fifo_terima_dealer</td>                    
                          <td>";echo strtoupper($row->jenis_pu);
                          echo "</td>                                    
                          <td>$status</td>                                    
                        </tr>
                        ";                      
                     }
                  }
                  
                  $cek_unfill = $this->db->query("SELECT * FROM tr_do_po INNER JOIN tr_picking_list ON tr_do_po.no_do = tr_picking_list.no_do 
                            INNER JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                            INNER JOIN tr_picking_list_view ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list AND tr_do_po_detail.id_item = tr_picking_list_view.id_item
                            WHERE tr_do_po_detail.id_item = '$id_item' AND tr_do_po.id_dealer = '$id_dealer' AND tr_do_po_detail.qty_do > 0 AND tr_do_po.status = 'approved'");
                  foreach($cek_unfill->result() as $isi) {     
                      $row = $this->m_admin->getByID("tr_scan_barcode","no_mesin",$isi->no_mesin)->row();                  
                      $row2 = $this->db->query("SELECT no_mesin FROM tr_surat_jalan_detail WHERE no_mesin = '$isi->no_mesin' AND ceklist = 'ya' AND retur = 0");
                      if(isset($row->status) AND $row->status != 4 AND $row2->num_rows() == 0) {                          
                        $status = "<span class='label label-danger'>Unfill</span>";                          
                          
                        echo "
                        <tr>
                          <td>$row->no_mesin</td>
                          <td>$row->no_rangka</td>         
                          <td></td>             
                          <td>";echo strtoupper($row->tipe);
                          echo "</td>                                    
                          <td>$status</td>                                    
                        </tr>
                        ";
                     }
                  }

                  $cek_in = $this->db->query("SELECT *  FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan                       
                        INNER JOIN tr_scan_barcode ON tr_surat_jalan_detail.no_mesin = tr_scan_barcode.no_mesin
                        WHERE tr_surat_jalan.no_surat_jalan NOT IN (SELECT no_surat_jalan FROM tr_penerimaan_unit_dealer WHERE no_surat_jalan IS NOT NULL)
                        AND tr_surat_jalan_detail.id_item = '$id_item' AND tr_surat_jalan.id_dealer = '$id_dealer'");
                  foreach($cek_in->result() as $row) {                       
                     if ($row->status != 4) {
                          
                        $status = "<span class='label label-warning'>Intransit</span>";
                          
                          
                        echo "
                        <tr>
                          <td>$row->no_mesin</td>
                          <td>$row->no_rangka</td>   
                          <td></td>                                
                          <td>";echo strtoupper($row->tipe);
                          echo "</td>                                    
                          <td>$status</td>                                    
                        </tr>
                        ";
                     }
                  }
                  ?>
                  </tbody>                  
                </table>
              </div>
            </div>
          </form>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div>

<script type="text/javascript">

// var table;

// $(document).ready(function() {
//     //datatables
//     table = $('#table').DataTable({

//         "processing": true, //Feature control the processing indicator.
//         "serverSide": true, //Feature control DataTables' server-side processing mode.
//         "order": [], //Initial no order.

//         // Load data for the table's content from an Ajax source
//         "ajax": {
//             "url": "<?php echo site_url('h1/real_time_stok_d_d/ajax_list')?>",
//             "type": "POST"
//         },

//         //Set column definition initialisation properties.
//         "columnDefs": [
//         {
//             "targets": [ 0 ], //first column / numbering column
//             "orderable": false, //set not orderable
//         },
//         ],
//     });
// });

</script>