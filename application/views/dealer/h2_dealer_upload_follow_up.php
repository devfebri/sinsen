
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
.vertical-text{
  writing-mode: lr-tb;
  text-orientation: mixed;
}
.rotate {
  -webkit-transform: rotate(-90deg);
  -moz-transform: rotate(-90deg);
}
#mySpan{
  writing-mode: vertical-lr; 
  transform: rotate(180deg);
}
</style>

<base href="<?php echo base_url(); ?>" />
    <?php 
    if($set=="view"){
    ?>

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>

  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H2</li>
    <li class="">3 Axis Analysis</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content">
    <div class="box box-default">
      <div class="box-header with-border">
      <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {?>
          <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
          </div>
      <?php } $_SESSION['pesan'] = ''; ?>        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" id="frm" method="post" action= "dealer/h2_dealer_upload_follow_up/import_excel" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Pilih File</label>
                    <div class="col-sm-4">
                        <input type="file" class="form-control" name="fileExcel">
                    </div>                                      
                </div>             
                <!-- <div class="form-group">
                <div class="col-sm-2">
                    <button type="submit" name="process" value="excel" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Download .xls</button>                                                      
                  </div>    -->
  		            <!-- <div class="col-sm-2">
                    <button type="submit" name="process" value="csv" class="btn bg-blue btn-block btn-flat"><i class="fa fa-download"></i> Download .csv</button>                                                      
                  </div> -->
                <!-- </div>              -->
              </div><!-- /.box-body -->              
              <div class="box-footer">
              <div class="col-sm-12" align="center">
                <button type="submit" id="submitBtn" name="process" value="excel"  class="btn btn-info btn-flat"><i class="fa fa-download"></i> Import File .xlsx</button>
              </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
      </table>
    </body>
  </html>
  </section>
</div>
<?php }?>