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
    <li class="">Biro Jasa</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php 
    if($set=="konfirmasi"){
      $ro = $dt_biro->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/konfirmasi_map">
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
            <form class="form-horizontal" action="h1/konfirmasi_map/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <!-- <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Scan</label>                  
                  <div class="col-sm-4">
                    <input type="text" name="scan_barcode" placeholder="Scan Barcode" class="form-control">
                  </div>                              
                  <div class="col-sm-2">
                    <button type="button" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> Browse</button>
                  </div>  
                </div> -->
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Tanda Terima</label>                  
                  <div class="col-sm-4">
                    <input type="hidden" name="id_generate2" value="<?php echo $ro->id_generate ?>">
                    <input type="text" name="no_tanda_terima" readonly value="<?php echo $ro->no_tanda_terima ?>" placeholder="No Tanda Terima" class="form-control">
                  </div>                              
                  <label for="inputEmail3" class="col-sm-2 control-label">Tgl Tanda Terima</label>                  
                  <div class="col-sm-4">
                    <input type="text" name="tgl_terima" readonly value="<?php echo $ro->tgl_terima ?>" placeholder="Tgl Tanda Terima" class="form-control">
                  </div>                              
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-md-offset-6 col-sm-2 control-label">Tgl Mohon Samsat</label>            
                  <div class="col-sm-4">
                    <input type="text" name="tgl_mohon_samsat" readonly value="<?php echo $ro->tgl_mohon_samsat ?>" placeholder="Tgl Mohon Samsat" class="form-control">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>                  
                  <div class="col-sm-10">
                    <?php 
                    $c = $this->m_admin->getAll("tr_konfirmasi_map","id_generate",$ro->id_generate)->row();
                    if(isset($c->keterangan)){
                      $val = $c->keterangan;
                    }else{
                      $val = "";
                    }
                    ?>
                    <input type="text" value="" name="keterangan" placeholder="Keterangan" class="form-control">
                  </div>                                                
                </div>
                <table class="table table-bordered table-hover" id="exampleX">
                  <thead>
                    <tr>              
                      <th width="5%">No</th>  
                      <th>Nama Dealer</th>                       
                      <th>No Mesin</th>
                      <th>No Rangka</th>
                      <th>Warna</th>
                      <th>Tipe</th>              
                      <th>Nama Konsumen</th>
                      <th>Alamat Konsumen</th>
                      <th align="center" width="2%">
                        <input type="checkbox" id="check-all">
                      </th>              
                    </tr>
                  </thead>
                  <tbody>            
                  <?php 
                  $no=1; 
                  foreach($dt_map->result() as $row) { 
                    $jum = $dt_map->num_rows();                                        
                    $cek = $this->m_admin->getByID("tr_konfirmasi_map_detail","no_mesin",$row->no_mesin)->row();
                    if(isset($cek->konfirmasi)){                                            
                      if($cek->konfirmasi == 'ya'){                                              
                        $rf = "checked";
                      }else{
                        $rf = "";  
                      }                    
                    }else{
                      $rf = "";
                    }
                    $getdealer_1 = $this->db->query("SELECT * FROM tr_sales_order WHERE no_mesin ='$row->no_mesin'");
                    $getdealer_2 = $this->db->query("SELECT * FROM tr_sales_order_gc INNER JOIn tr_sales_order_gc_nosin ON 
                        tr_sales_order_gc.id_sales_order_gc = tr_sales_order_gc_nosin.id_sales_order_gc 
                        WHERE tr_sales_order_gc_nosin.no_mesin ='$row->no_mesin'");
		    $getdealer_3 = $this->db->query("SELECT * FROM tr_bantuan_bbn_luar WHERE no_mesin ='$row->no_mesin'");

                    if($getdealer_1->num_rows() > 0){
                      $getdealer = $getdealer_1->row()->id_dealer;
                    }else if($getdealer_2->num_rows() > 0){
                      $getdealer = $getdealer_2->row()->id_dealer;
                    }else{
                      $getdealer = $getdealer_3->row()->id_dealer;
                    }                    

                    $dealer = $this->db->query("SELECT * FROM ms_dealer WHERE id_dealer='$getdealer'")->row()->nama_dealer;
                    if(isset($dealer)){
                      $isi_dealer = $dealer;
                    }else{
                      $isi_dealer = $this->m_admin->getByID("tr_bantuan_bbn","no_mesin",$row->no_mesin)->row()->nama_konsumen;
                    } 


                  echo "          
                    <tr>
                      <td>$no</td>
                      <td>$isi_dealer</td>
                      <td>$row->no_mesin</td>                           
                      <td>$row->no_rangka</td>                           
                      <td>$row->id_warna | $row->warna</td>                           
                      <td>$row->id_tipe_kendaraan | $row->tipe_ahm</td>                           
                      <td>$row->nama_konsumen</td>                           
                      <td>$row->alamat</td>                           
                      <td>
                        <input type='hidden' name='jum' value='$jum'>
                        <input type='hidden' name='id_generate_$no' value='$ro->id_generate'>
                        <input type='hidden' name='no_mesin_$no' value='$row->no_mesin'>
                        <input type='checkbox' class='data-check' name='konfirmasi_$no' value='$ro->id_generate' $rf>
                      </td>";                                      
                        // <input type='checkbox' name='konfirmasi_$no' $rf>
                  $no++;
                  }
                  $sql = $this->db->query("SELECT tr_bantuan_bbn.*, ms_tipe_kendaraan.tipe_ahm AS tipe_motor,ms_warna.warna AS warna_motor,ms_warna.warna_samsat FROM tr_bantuan_bbn
        LEFT JOIN tr_scan_barcode ON tr_bantuan_bbn.no_mesin = tr_scan_barcode.no_mesin
        LEFT JOIN ms_item ON tr_scan_barcode.id_item = ms_item.id_item
        LEFT JOIN ms_tipe_kendaraan ON tr_bantuan_bbn.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
        LEFT JOIN ms_warna ON tr_bantuan_bbn.id_warna = ms_warna.id_warna            
        WHERE tr_bantuan_bbn.id_generate='$id_generate' ");

            foreach ($sql->result() as $row) {  
            $jum2 = $sql->num_rows();        
            $cek = $this->m_admin->getByID("tr_konfirmasi_map_detail","no_mesin",$row->no_mesin)->row();
                    if(isset($cek->konfirmasi)){                                            
                      if($cek->konfirmasi == 'ya'){                                              
                        $rf = "checked";
                      }else{
                        $rf = "";  
                      }                    
                    }else{
                      $rf = "";
                    }   
               echo "          
                    <tr>
                      <td>$no</td>
                      <td>$row->nama_konsumen</td>                           
                      <td>$row->no_mesin</td>                           
                      <td>$row->no_rangka</td>                           
                      <td>$row->id_warna | $row->warna_motor</td>                           
                      <td>$row->id_tipe_kendaraan | $row->tipe_motor</td>                           
                      <td>$row->nama_konsumen</td>                           
                      <td>$row->alamat</td>                           
                      <td>                                
                        <input type='hidden' name='jum2' value='$jum2'>
                        <input type='hidden' name='id_generate_$no' value='$ro->id_generate'>
                        <input type='hidden' name='no_mesin_$no' value='$row->no_mesin'>
                        <input type='checkbox' name='konfirmasi_$no' $rf>
                      </td>";                                      
                  $no++;
                  }             
        
                  ?>
                  </tbody>
                </table>                
              </div><!-- /.box-body -->
              <div class="box-footer">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">                  
                  <button type="submit" onclick="return confirm('Are you sure to save all data?')" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>                                  
                </div>
              </div><!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
  <script>
    $('#exampleX').dataTable({
    paging: false
});
  </script>

    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/konfirmasi_map/add">            
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                          
              <th>Tgl Mohon Samsat</th>
              <th>ID Generate</th>
              <th>No Tanda Terima</th>
              <th>Tgl Tanda Terima</th>   
              <th>Jumlah</th>           
              <th width="15%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_biro->result() as $row){       
            if($row->no_tanda_terima != ''){
              $item = $this->db->query("SELECT COUNT(no_mesin) as jum FROM tr_pengajuan_bbn_detail WHERE id_generate = '$row->id_generate'")->row();          
              $item2 = $this->db->query("SELECT count(no_mesin) as jum FROM tr_bantuan_bbn WHERE id_generate='$row->id_generate'")->row();
              $cek1 = $this->db->query("SELECT id_generate FROM tr_konfirmasi_map WHERE id_generate = '$row->id_generate'");
              if($cek1->num_rows() > 0){            
                $cek2 = $this->db->query("SELECT id_generate FROM tr_konfirmasi_map_detail WHERE id_generate = '$row->id_generate' AND konfirmasi <> 'ya'");
                if($cek2->num_rows() > 0){            
                  $tom = "<a href='h1/konfirmasi_map/konfirmasi?id=$row->id_generate&a=$row->no_tanda_terima' class='btn btn-primary btn-flat btn-xs'><i class='fa fa-check'> Konfirmasi Penerimaan</i></a>";
                }else{
                  $tom = "";
                }
              }else{
                $tom = "<a href='h1/konfirmasi_map/konfirmasi?id=$row->id_generate&a=$row->no_tanda_terima' class='btn btn-primary btn-flat btn-xs'><i class='fa fa-check'> Konfirmasi Penerimaan</i></a>";
              } 

              $hasil = $item->jum + $item2->jum;

              echo "          
              <tr>
                <td>$no</td>
                <td>$row->tgl_mohon_samsat</td>                           
                <td>$row->id_generate</td>                           
                <td>$row->no_tanda_terima</td>                           
                <td>$row->tgl_terima</td>                           
                <td>".$hasil." Item</td>                           
                <td>";
                echo $tom;                                                
                
                echo "
                </td>";                                      
              $no++;
            }
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }elseif($set=="view_new"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/konfirmasi_map/add">            
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
        <table id="datatable" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th width="5%">No</th>                          
              <th>Tgl Mohon Samsat</th>
              <th>ID Generate</th>
              <th>No Tanda Terima</th>
              <th>Tgl Tanda Terima</th>   
              <th>Jumlah</th>           
              <th width="15%">Action</th>              
            </tr>
          </thead>
          <tbody>            
          </tbody>
        </table>

      <!-- <table id="table_konfirmasi_map" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="5%">No</th>                          
              <th>Tgl Mohon Samsat</th>
              <th>ID Generate</th>
              <th>No Tanda Terima</th>
              <th>Tgl Tanda Terima</th>   
              <th>Jumlah</th>           
              <th width="15%">Action</th>        
            </tr>
          </thead>
          <tbody>   
          </tbody>
        </table> -->

      </div><!-- /.box-body -->
    </div><!-- /.box -->

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

                var base_url = "<?php echo base_url();?>/"; 
                $('#datatable').DataTable({
                   "pageLength" : 10,
                   "serverSide": true,
                   "ordering": false, // Set true agar bisa di sorting
                    "processing": true,
                    "language": {
                      processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                      searchPlaceholder: "Min 5 character"
                    },

                   "order": [[1, "desc" ]],
                   "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    },
                   "ajax":{
                            url :  base_url+'h1/konfirmasi_map/getAllDatas',
                            type : 'POST'
                          },
                }); // End of DataTable

                // Add an event listener to the search input to enforce a minimum length
                $('#dataTables_filter input').on('keyup', function () {
                      var searchValue = this.value.trim();

                      if (searchValue.length >= 5 || searchValue === '') {
                          dataTable.search(searchValue).draw();
                      } else {
                          dataTable.search('').draw();
                      }
                  });

                  // Disable the search button when input length is less than 5
                  $('#dataTables_filter input').on('input', function () {
                      var searchButton = dataTable.table().container().closest('.dataTables_wrapper').find('.dataTables_filter button');
                      if (this.value.length >= 5) {
                          searchButton.prop('disabled', false);
                      } else {
                          searchButton.prop('disabled', true);
                      }
                  });


              }); 

       

            </script>



<!-- <script>
  $( document ).ready(function() {
   tabless = $('#table_konfirmasi_map').DataTable({
	      "scrollX": true,
        "processing": true, 
        "bDestroy": true,
        "serverSide": true, 
        "bInfo" : true,
        "order": [],
        "ajax": {
          "url": "<?php //  echo site_url('h1/konfirmasi_map/fetch_data_konfirmasi_map')?>",
            "type": "POST"
        },  
        "columnDefs": [
        {
            "targets": [ 0,5 ],
            "orderable": false, 
        },
        ],
        });
});
</script> -->

    <?php
    } elseif($set=="testing"){
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
  
        <table class="table table-bordered table-hover" id="example6">
            <thead>
              <tr>
                <th width="5%">No</th>                          
                <th>Tgl Mohon Samsat</th>
                <th>ID Generate</th>
                <th>No Tanda Terima</th>
                <th>Tgl Tanda Terima</th>   
                <th>Jumlah</th>           
                <th width="15%">Action</th>        
              </tr>
            </thead>
            <tbody>   
              <?php 
            foreach($konfirmasi_map as $row){  ?>   
                <tr>
                  <td></td>
                  <td><?=$row->tgl_mohon_samsat?></td>                           
                  <td><?=$row->id_generate?></td>                           
                  <td><?=$row->no_tanda_terima?></td>                           
                  <td><?=$row->tgl_terima?></td>                           
                  <td><?=$row->jumlah?></td>  
                  <td>
                    <?php 
                    $tom = "<a href='h1/konfirmasi_map/konfirmasi?id=$row->id_generate&a=$row->no_tanda_terima' class='btn btn-primary btn-flat btn-xs'><i class='fa fa-check'> Konfirmasi Penerimaan</i></a>";
                            // $cek2 = $this->db->query("SELECT id_generate FROM tr_konfirmasi_map_detail WHERE id_generate = '$row->id_generate' AND konfirmasi <> 'ya'");
                            // if($cek2->num_rows() > 0){            
                            // }else{
                            //   $tom = "";
                            // }
                            echo $tom;
                      ?>
                  </td>                           
                </tr>
            <?}?>     
            </tbody>
          </table> 
  
        </div><!-- /.box-body -->
      </div><!-- /.box -->


      <script>
         $('#example6').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      fixedHeader: true,
      "lengthMenu": [
        [10, 25, 50, 75, 100, -1],
        [10, 25, 50, 75, 100, "All"]
      ],
      "autoWidth": true
    });
      </script>
  
  
  <script>
  //   $( document ).ready(function() {
  //    tabless = $('#table_konfirmasi_map').DataTable({
  //         "scrollX": true,
  //         "processing": true, 
  //         "bDestroy": true,
  //         "serverSide": true, 
  //         "bInfo" : true,
  //         "order": [],
  //         "ajax": {
  //           "url": "<?php //echo site_url('h1/konfirmasi_map/fetch_data_konfirmasi_map')?>",
  //             "type": "POST"
  //         },  
  //         "columnDefs": [
  //         {
  //             "targets": [ 0,5 ],
  //             "orderable": false, 
  //         },
  //         ],
  //         });
  // });
  </script>
  
      <?php
      }
    ?>
  </section>
</div>



