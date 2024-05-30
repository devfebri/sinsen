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
    <li class="">Bank,Kas,BG Beredar</li>    
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>
  <section class="content">


    <?php
    if($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/voucher_pengeluaran_bank/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
              <th>No Voucher</th>                                         
              <th>Tgl Entry</th>                            
              <th>No.BG-Nominal BG</th>                                          
              <th>Customer</th>                            
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_mon->result() as $row) { 

            $customer = $row->dibayar;
            
            if ($row->tipe_customer=='Dealer') {
              $customer = $this->db->get_where('ms_dealer',['id_dealer'=>$row->dibayar]);
              $customer = $customer->num_rows()>0?$customer->row()->nama_dealer:'';

            }                              
            if ($row->tipe_customer=='Vendor') {
              $customer = $this->db->get_where('ms_vendor',['id_vendor'=>$row->dibayar]);
              $customer = $customer->num_rows()>0?$customer->row()->vendor_name:'';
            }              
          echo "          
            <tr>               
              <td>$no</td>                           
              <td>$row->id_voucher_bank</td>                            
              <td>$row->tgl_entry</td>                                          
              <td>";
              $dt_bg = $this->db->query("SELECT * FROM tr_voucher_bank_bg WHERE id_voucher_bank = '$row->id_voucher_bank'");
              foreach($dt_bg->result() as $isi) {
                echo "$isi->no_bg | $isi->nominal_bg <br>";
              }
              echo "
              </td>                                          
              <td>$customer</td>                                                        
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
    else if($set=="serverside"){
      ?>
  
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
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
          <table id="tbl_set_monitoring_bg" class="table table-bordered table-hover">
            <thead>
              <tr>              
                <th width="5%">No</th>                          
                <th>No Voucher</th>                                         
                <th>Tgl Entry</th>                            
                <th>No.BG-Nominal BG</th>                                          
                <th>Customer</th>                            
              </tr>
            </thead>
            <tbody>            
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

      <script>
        $( document ).ready(function() {
        $('#tbl_set_monitoring_bg').DataTable({
              "searchable": false,
              "language": {
              "lengthMenu ": "Display _MENU_ records per page",
              "zeroRecords": "Nothing found - sorry",
              "infoEmpty": "No records available",
              "infoFiltered": ""
          },
              "processing": true, 
              "serverSide": true, 
              "order": [],
              "ajax": {
                "url": "<?php echo site_url('/h1/monitoring_bg/fetch') ?>",
                "type": "POST",
                data: function(d) {
                return d;
              },
              },
              "columnDefs": [{
                "targets": [0], 
                "orderable": false, 
              }, ],
            });
            });
      </script>
  
      <?php
      }
    ?>
  </section>
</div>


