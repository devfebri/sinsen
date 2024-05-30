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
    <li class="">Monitoring</li>
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
          <!--a href="h1/monitoring_appoinment/add">            
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>
              <!--th width="1%"><input type="checkbox" id="check-all"></th-->              
              <th width="5%">No</th>                            
              <th>ID Customer</th>              
              <th>Nama Konsumen</th>            
              <th>Alamat Konsumen</th>              
              <th>Tipe Motor</th> 
              <th>Warna</th>
              <th>Rencana Pembayaran</th>
              <th>Tgl Follow up 1</th>
              <th>Tgl Follow up 2</th>
              <th>Tgl Follow up 3</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
           foreach($dt_guest_book->result() as $row) {                        
            $cek = $this->db->query("SELECT * FROM tr_guest_book_detail WHERE id_guest_book = '$row->id_guest_book'");
            $jum = $cek->num_rows();
            $tgl_fu_1="";$tgl_fu_2="";$tgl_fu_3="";
            for ($i=1; $i <= $jum; $i++) { 
              $cek_2 = $this->db->query("SELECT * FROM tr_guest_book_detail WHERE id_guest_book = '$row->id_guest_book' ORDER BY id_guest_book_detail LIMIT 0,$i")->row();  
              if($i==1){
                $tgl_fu_1 = $cek_2->tgl_fu;
              }elseif($i==2){
                $tgl_fu_2 = $cek_2->tgl_fu;
              }elseif($i==3){
                $tgl_fu_3 = $cek_2->tgl_fu;
              }
            }
            echo "                  
            <tr>
              <td>$no</td>                             
              <td>$row->id_customer</td>
              <td>                
                  $row->nama_konsumen                
              </td>
              <td>$row->alamat</td>
              <td>$row->tipe_ahm</td>
              <td>$row->warna</td>
              <td>$row->rencana_bayar</td>
              <td>$tgl_fu_1</td>
              <td>$tgl_fu_2</td>
              <td>$tgl_fu_3</td>              
            </td>
          </tr>";          
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
