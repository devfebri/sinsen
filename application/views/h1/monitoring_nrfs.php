
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

    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>

  </ol>

  </section>

  <section class="content">

    

    <?php 

    if($set=="view"){

    ?>



    <div class="box box-default">

      <div class="box-header with-border">              

        <div class="row">

          <div class="col-md-12">

           

              <div class="box-body">  

                <form class="form-horizontal" action="h1/monitor_nrfs" id="frm" method="get">                                                            

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Dari tanggal</label>

                  <div class="col-sm-4">

                    <input type="text" id="tanggal1" name="tanggal1" class="form-control" placeholder="Periode Awal" autocomplete="off">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Sampai tanggal</label>

                  <div class="col-sm-4">

                    <input type="text" id="tanggal2" name="tanggal2" class="form-control" placeholder="Periode Akhir" autocomplete="off">

                  </div>                  

                </div>

                

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label"></label>

                  <div class="col-sm-2">

                    <button type="submit" name="set" value="lihat" class="btn bg-blue btn-flat"><i class="fa fa-eye"></i> Lihat</button>

                  </div>  

                  </form>

                  <div class="col-sm-2">

                   

                  </div>                 

                </div>                

              </div><!-- /.box-body -->              

              <div class="box-footer">                                                              

                

              </div>

            

          </div>



        </div>

      </div>

    </div><!-- /.box -->




    

    <?php }elseif ($set=='lihat') { 

      ?>


    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">

      <div class="box box-default">

      <div class="box-header with-border">              

        <div class="row">

          <div class="col-md-12">

           

              <div class="box-body">  

                <form class="form-horizontal" action="h1/monitor_nrfs" id="frm" method="get">                                                            

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label">Dari tanggal</label>

                  <div class="col-sm-4">

                    <input type="text" id="tanggal1" name="tanggal1" class="form-control" placeholder="Periode Awal" value="<?php echo $_GET['tanggal1'] ?>" autocomplete="off">

                  </div>

                  <label for="inputEmail3" class="col-sm-2 control-label">Sampai tanggal</label>

                  <div class="col-sm-4">

                    <input type="text" id="tanggal2" name="tanggal2" class="form-control" placeholder="Periode Akhir" value="<?php echo $_GET['tanggal2'] ?>" autocomplete="off">

                  </div>                  

                </div>

                

                <div class="form-group">

                  <label for="inputEmail3" class="col-sm-2 control-label"></label>

                  <div class="col-sm-2">

                    <button type="submit" name="set" value="lihat" class="btn bg-blue btn-flat"><i class="fa fa-eye"></i> Lihat</button>

                  </div>  

                  </form>

                  <?php if ($_GET['tanggal1'] != '') {
                    ?>
                    <div class="col-sm-2">

                      <a href="<?php echo base_url() ?>h1/monitor_nrfs/generate?tanggal1=<?php echo $_GET['tanggal1'] ?>&tanggal2=<?php echo $_GET['tanggal2'] ?>" class="btn bg-green btn-flat"><i class="fa fa-download"></i> Download</a>

                    </div>     
                    <?php
                  } ?>            

                </div>                

              </div><!-- /.box-body -->              

              <div class="box-footer">                                                              

                

              </div>

            

          </div>



        </div>

      </div>

    </div><!-- /.box -->


     <div class="box box-default">

      <div class="box-header with-border">              

        <div class="row">

          <div class="col-md-12">

      

              <div class="box-body">                      
                <div class="col-12">
                  <div class="table-responsive">
                  <table class="table" id="datatable">
                      <thead>
                          <tr>
                              <th>No</th>
                              <th>Date AT</th>
                              <th>Nama Pemeriksa</th>
                              <th>ID Part</th>
                              <th>Gejala</th>
                              <th>Penyebab</th>
                              <th>No Mesin</th>
                              <th>No Rangka</th>
                              <th>Tanggal Penerimaan</th>
                              <th>Perbaikan Gudang</th>
                              <th>ID Ekspedisi</th>
                              <th>No Polisi</th>
                              <th>Nama Kapal</th>
                              <th>Butuh PO</th>
                              <th>No PO Urgent</th>
                              <th>Estimasi Tgl Selesai</th>
                              <th>Actual Tgl Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>  
                    </div>   
              </div>

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
                         "pageLength" : 5,
                         "serverSide": true,
                         "ordering": true, // Set true agar bisa di sorting
                          "processing": true,
                          "language": {
                            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                            searchPlaceholder: "Masukkan Date AT / No Mesin..."
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
                                  url :  base_url+'h1/monitor_nrfs/getData?tanggal1=<?php echo $_GET['tanggal1'] ?>&tanggal2=<?php echo $_GET['tanggal2'] ?>',
                                  type : 'POST'
                                },
                      }); // End of DataTable


                    }); 

                  </script>

                             

              </div><!-- /.box-body -->              

              
            

          </div>



        </div>

      </div>

    </div><!-- /.box -->

    



  <?php } elseif ($set == 'download') { ?>


  <?php } ?>

  </section>

</div>





