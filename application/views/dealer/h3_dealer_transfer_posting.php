<!-- TODO: Transfer posting -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
        <?php echo $title; ?>    
    </h1>
    <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">H3</li>
        <li class="">Warehouse</li>
        <li class="active">
          <?php echo ucwords(str_replace("_", " ", $isi)); ?>
        </li>
    </ol>
  </section>
  <section class="content">
    <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
              <a href="dealer/<?= $isi ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
              </a>
          </h3>
          <div class="box-tools pull-right">
              <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal" id="form_" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                      <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Tanggal</label>
                          <div class="col-sm-4">
                            <input name="dates" type="text" class="form-control">
                          </div>
                      </div>
                    </div>
                    <script>
                      $('#tanggal').daterangepicker();
                      $('input[name="dates"]').daterangepicker();
                    </script>
                    <div class="box-footer"></div>
                    <!-- /.box-footer -->
                </form>
              </div>
          </div>
        </div>
  </section>
</div>