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
    <li class="">Faktur STNK</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    <?php 
    if($set=="cetak_terima"){
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/kirim_biro_jasa">
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
            <form class="form-horizontal" action="h1/kirim_biro_jasa/save" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <br>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Nama Biro Jasa</label>
                  <div class="col-sm-4">
                    <select class="form-control select2">
                      <option>- choose -</option>
                    </select>
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Alamat Biro Jasa</label>
                  <div class="col-sm-4">
                    <input type="text" name="periode_awal" placeholder="Alamat Biro Jasa" class="form-control">
                  </div>                                
                </div>
                
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


    <?php
    }elseif($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/kirim_biro_jasa/add">            
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
        
        <table class="table" id="datatable">
          <thead>
              <tr>
                  <th>No</th>
                  <th>Tgl Mohon Samsat</th>
                  <th>ID Generate</th>
                  <th>Nama Biro Jasa</th>
	              <th>Jumlah Unit</th>
	              <th>Total Biaya</th> 
	              <th>Tgl Cetak SP</th> 
	              <th width="15%">Action</th>
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

                      var base_url = "<?php echo base_url();?>/"; // You can use full url here but I prefer like this
                      $('#datatable').DataTable({
                         "pageLength" : 10,
                         "serverSide": true,
                         "ordering": true, // Set true agar bisa di sorting
                          "processing": true,
                          "language": {
                            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                            searchPlaceholder: "Tgl Mohon/ID Generate/Nama Biro..."
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
                                  url :  base_url+'h1/kirim_biro_jasa/getData',
                                  type : 'POST'
                                },
                      }); // End of DataTable


                    }); 

                  </script>  

      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    }elseif($set=="detail"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/kirim_biro_jasa">
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
        <table id="example2" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th>Nama Dealer</th>
              <th>Nama Konsumen</th>
              <th>Tipe Motor</th>
              <th>No Mesin</th>              
              <th>Biaya Adm PKB</th>            
              <th>Biaya Adm STNK</th>            
              <th>Biaya Adm Plat</th> 
              <th>Biaya BBN</th>
              <th>Total</th>                       
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          $biaya = $this->db->query("SELECT * FROM ms_setting_h1")->row();
          $tot_all=0;$tot_all2=0;
          foreach($detail->result() as $row) {  
            $total = $biaya->biaya_bpkb+ $biaya->biaya_stnk+$biaya->biaya_plat+$row->biaya_bbn_md_bj;   
            $tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan'")->row()->deskripsi_ahm;                              
            if(isset($row->nama_dealer)){
              $nama_dealer = $row->nama_dealer;
            }else{
              $nama_dealer = "";
            }
            echo "          
            <tr>
              <td>$nama_dealer</td>                        
              <td>$row->nama_konsumen</td>                        
              <td>$tipe</td>                           
              <td>$row->no_mesin</td>                        
              <td>".number_format($biaya->biaya_bpkb, 0, ',', '.')."</td>
              <td>".number_format($biaya->biaya_stnk, 0, ',', '.')."</td>
              <td>".number_format($biaya->biaya_plat, 0, ',', '.')."</td>
              <td>".number_format($row->biaya_bbn_md_bj, 0, ',', '.')."</td>
              <td>".number_format($total, 0, ',', '.')."</td>
            </tr>
              ";                                      
          $tot_all+=$total;
          }
          

          foreach($detail2->result() as $row) {  
            $total2 = $biaya->biaya_bpkb+ $biaya->biaya_stnk+$biaya->biaya_plat+$row->biaya_bbn_md_bj;   
            $tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan = '$row->id_tipe_kendaraan'")->row()->deskripsi_ahm;                                          
            echo "          
            <tr>
              <td></td>                        
              <td>$row->nama_konsumen</td>                        
              <td>$tipe</td>                           
              <td>$row->no_mesin</td>                        
              <td>".number_format($biaya->biaya_bpkb, 0, ',', '.')."</td>
              <td>".number_format($biaya->biaya_stnk, 0, ',', '.')."</td>
              <td>".number_format($biaya->biaya_plat, 0, ',', '.')."</td>
              <td>".number_format($row->biaya_bbn_md_bj, 0, ',', '.')."</td>
              <td>".number_format($total2, 0, ',', '.')."</td>
            </tr>
              ";                                      
          $tot_all2+=$total2;
          }

          $tot_akhir = $tot_all + $tot_all2;
          ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="8">Total</td>
              <td><?=number_format($tot_akhir, 0, ',', '.')?></td>
            </tr>
          </tfoot>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>
