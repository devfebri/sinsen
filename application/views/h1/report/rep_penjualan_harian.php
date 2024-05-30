

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
    <li class="">Laporan</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    
    

    <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="h1/rep_penjualan_harian/download" id="frm" method="post" enctype="multipart/form-data">
              <div class="box-body">                                                                              
                <div class="form-group">                                    
                  <label for="inputEmail3" class="col-sm-2 control-label">Periode DO</label>
                  <div class="col-sm-2">
                    <input type="text" autocomplete="off" required placeholder="Tanggal" name="tgl1" id="tanggal1" class="form-control">
                  </div>                                                    
                  <div class="col-sm-2">
                    <button type="submit" name="process" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Download</button>                                                      
                  </div>                             
              </div><!-- /.box-body -->                           
            </form>
            <!-- <div id="imgContainer"></div> -->
          </div>
        </div>
      </div>
    </div><!-- /.box -->
</section>
</div>
    
    