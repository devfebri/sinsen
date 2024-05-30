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
    if($set=='log'){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/monitor_history">
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>
              <th>No Mesin</th>
              <th>Status</th>
              <th>Keterangan</th>
              <th>Waktu Ubah</th>
              <th>Oleh</th>
            </tr>
          </thead>
          <tbody>
          <?php 
          $no=1;
          foreach ($dt_nosin->result() as $row) {
            $isi = $this->m_admin->getByID("ms_user","id_user",$row->created_by)->row();
            echo "
            <tr>
              <td>$no</td>
              <td>$row->no_mesin</td>
              <td>$row->status</td>
              <td>$row->keterangan</td>
              <td>$row->waktu</td>
              <td>$isi->username</td>
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
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/penerimaan_unit/add">
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
        <table id="carian" class="display responsive nowrap table table-bordered table-striped" cellspacing="0" width="100%">
          <thead>
            <tr>
              <td width="5%">No</td>
              <td>No Mesin</td>              
              <td>No Rangka</td>              
              <td width="8%">Kode Item</td>
              <td>Status Lokasi</td>
              <td width="8%">Status Sale</td>
              <td width="8%">Lokasi</td>
              <td width="8%">FIFO</td>
              <td  width="8%">No DO</td>
              <td  width="8%">No Surat Jalan</td>
              <td  width="8%">Kode Dealer</td>              
              <td  width="8%">Nama Dealer</td>
              <td  width="8%">POS Dealer</td>                           
            </tr>
            <tr>
              <td></td>
              <th>No Mesin</th>              
              <th>No Rangka</th>              
              <th>Kode Item</th>
              <th>Status Lokasi</th>
              <th>Status Sale</th>
              <th>Lokasi</th>
              <th>FIFO</th>
              <th>No DO</th>
              <th>No Surat Jalan</th>
              <th>Kode Dealer</th>              
              <th>Nama Dealer</th>
              <th>POS Dealer</th> 
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          $sl = $this->db->query("SELECT * FROM tr_shipping_list LIMIT 0,30");
          foreach($sl->result() as $row) {     
            $s = $this->db->query("SELECT * FROM ms_item WHERE id_tipe_kendaraan = '$row->id_modell' AND id_warna = '$row->id_warna' AND bundling <> 'ya'");          
            $t = $this->db->query("SELECT * FROM tr_scan_barcode WHERE no_mesin = '$row->no_mesin'");
            $do = $this->db->query("SELECT * FROM tr_picking_list_view INNER JOIN tr_picking_list ON tr_picking_list_view.no_picking_list=tr_picking_list.no_picking_list 
                    WHERE tr_picking_list_view.no_mesin = '$row->no_mesin' AND tr_picking_list_view.konfirmasi = 'ya'");
            $sj = $this->db->query("SELECT * FROM tr_surat_jalan_detail WHERE no_mesin = '$row->no_mesin'");
            $sj2 = $this->db->query("SELECT * FROM tr_surat_jalan_detail INNER JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan 
                    INNER JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
                    WHERE tr_surat_jalan_detail.no_mesin = '$row->no_mesin' AND tr_surat_jalan_detail.terima = 'ya'");
            if($t->num_rows() > 0){
              $e = $t->row();
              $fifo = $e->fifo;
              $id_item = $e->id_item;
              $s_sale = $e->tipe;
              if($e->status == '1'){
                $status = "<span class='label label-default'>Received</span>";  
              }elseif($e->status == '2'){
                $status = "<span class='label label-warning'>Unfill</span>";  
              }elseif($e->status == '3'){
                $status = "<span class='label label-primary'>Intransit Dealer</span>";  
              }elseif($e->status == '4'){
                $status = "<span class='label label-primary'>Received by Dealer</span>";  
              }elseif($e->status == '5'){
                $status = "<span class='label label-success'>Sale to Customer</span>";  
              }  
              
              if($e->status != 5){
                $lokasi = $e->lokasi;
                $slot = $e->slot;  
              }else{
                $lokasi = "";
                $slot = "";
              }              
              
              if($do->num_rows() > 0){
                $isi_do = $do->row();
                $no_do = $isi_do->no_do;
              }else{
                $no_do = "";
              }

              if($sj->num_rows() > 0){
                $isi_sj = $sj->row();
                $no_sj = $isi_sj->no_surat_jalan;
              }else{
                $no_sj = "";
              }

              if($sj2->num_rows() > 0){
                $isi_sj2 = $sj2->row();
                $no_sj2 = $isi_sj2->kode_dealer_md;
                $no_sj3 = $isi_sj2->nama_dealer;
              }else{
                $no_sj2 = "";
                $no_sj3 = "";
              }

            }else{
              $fifo = "";
              $s_sale = "";
              $status = "<span class='label label-danger'>Intransit AHM</span>";
              $no_do = "";
              $no_sj = "";
              $no_sj2 = "";
              $no_sj3 = "";
              $lokasi = "";
              $slot = "";
              $id_item = "";
              if($s->num_rows() > 0){
                $is = $s->row();
                $id_item = $is->id_item;
              }else{
                $id_item = "";
              }
              
            }          

            $get_so = $this->db->query("SELECT * FROM tr_sales_order WHERE no_mesin = '$row->no_mesin' ");
            if ($get_so->num_rows() > 0) {
              $get_so = $get_so->row();
              if ($get_so->status_so =='so_invoice') {
                
                $status =  "<span class='label label-success'>Sale to Customer</span>";
              }
            }
            echo "
            <tr>
              <td>$no</td>
              <td>
                <a data-toggle='tooltip' title='View Log' href='h1/monitor_history/log?id=$row->no_mesin'>
                  $row->no_mesin
                </a>
              </td>
              <td>$row->no_rangka</td>
              <td>$id_item</td>
              <td>$status</td>
              <td>$s_sale</td>
              <td>$lokasi - $slot</td>
              <td>$fifo</td>
              <td>$no_do</td>
              <td>$no_sj</td>
              <td>$no_sj2</td>
              <td>$no_sj3</td>
              <td></td>              
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
        <table id="table" class="display responsive nowrap table table-bordered table-striped" cellspacing="0" width="100%">
        <!-- <table id="table" class="table table-bordered table-hover">           -->
          <thead>
            <tr>
              <td width="5%">No</td>
              <td>No Mesin</td>              
              <td>No Rangka</td>              
              <td width="8%">Kode Item</td>
              <td>Status Lokasi</td>
              <td width="8%">Status Sale</td>
              <td width="8%">Lokasi</td>
              <td width="8%">FIFO</td>
              <td  width="8%">No DO</td>
              <td  width="8%">No Surat Jalan</td>
              <td  width="8%">Kode Dealer</td>              
              <td  width="8%">Nama Dealer</td>
              <td  width="8%">POS Dealer</td>                           
            </tr>
            <!-- <tr>
              <td></td>
              <th>No Mesin</th>              
              <th>No Rangka</th>              
              <th>Kode Item</th>
              <th>Status Lokasi</th>
              <th>Status Sale</th>
              <th>Lokasi</th>
              <th>FIFO</th>
              <th>No DO</th>
              <th>No Surat Jalan</th>
              <th>Kode Dealer</th>              
              <th>Nama Dealer</th>
              <th>POS Dealer</th> 
            </tr> -->
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

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('h1/monitor_history/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        {
            "targets": [ 0,5 ], //first column / numbering column
            "orderable": false, //set not orderable
        },
        ],
    });
});

</script>