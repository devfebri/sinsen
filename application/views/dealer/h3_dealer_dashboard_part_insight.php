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
    <li class="">H3</li>
    <li class="">Laporan</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
  </section>
  <section class="content" style="height:100%">
    <div class="box box-default" >
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12" >
            <form class="form-horizontal" id="frm" method="post" action= "" enctype="multipart/form-data">
              <div class="box-body">                                      
                <iframe title="Part_Data_Insight" width="1140" height="541.25" src="https://app.powerbi.com/reportEmbed?reportId=e72b9a60-bea9-4e07-a380-3170c66250e9&autoAuth=true&ctid=3d2f10c8-5e4c-4203-8a06-be5d54ffb75a" frameborder="0" allowFullScreen="true"></iframe>
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

<script>
  $(function () {
    $("#tanggal1").datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    }).on('changeDate', function (selected) {
      var minDate = new Date(selected.date);
      minDate.setDate(minDate.getDate());
      $('#tanggal2').datepicker('setStartDate', minDate);
    });
 
    $("#tanggal2").datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    }).on('changeDate', function (selected) {
      var minDate = new Date(selected.date);
      minDate.setDate(minDate.getDate());
      $('#tanggal1').datepicker('setEndDate', minDate);
    });
  });
</script>
<?php }?>