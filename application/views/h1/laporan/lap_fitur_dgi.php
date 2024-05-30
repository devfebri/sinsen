<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        <?php echo $title; ?>    

      </h1>

      <ol class="breadcrumb">

        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    

        <li class="">H1</li>

        <li class="">Laporan</li>

        <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>

      </ol>

    </section>

  

    <section class="content">

        <div class="box box-default">

          <div class="box-header with-border">        

            <div class="row">

              <div class="col-md-12">

                <form class="form-horizontal" action="h1/lap_fitur_dgi/generate_file" id="frm" method="post" enctype="multipart/form-data">

                  <div class="box-body">                                                                              

                    <div class="form-group">              

                      <div class="col-sm-12"><h4><?php echo 'Periode 01 s/d '. date('d F Y') ?> </h4></div>                                                  

                      <div class="col-sm-2">
                        <button type="submit" name="process" value="excel_all" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Per Total</button>                                                      
                      </div>
                      <div class="col-sm-2">
                        <button type="submit" name="process" value="excel_tgl" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Per Tanggal</button>                                                      
                      </div>  
                      <div class="col-sm-2">
                        <button type="submit" name="process" value="excel_tipe" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Per Endpoint</button>                                                      
                      </div>          
			
<?php /*
                      <div class="col-sm-2">

                        <button type="submit" name="process" value="csv" class="btn bg-blue btn-block btn-flat"><i class="fa fa-download"></i> CSV</button>                                                      

                      </div>                              
*/?>
                  </div><!-- /.box-body -->                           

                </form>

                <!-- <div id="imgContainer"></div> -->

              </div>

            </div>

          </div>

        </div><!-- /.box -->

    </section>

</div>