<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
  </section>
  <section class="content">
    <?php if($set == 'form'): ?>
    <div id="apps" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>  
        </h3>
        <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {?>
          <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
          </div>
      <?php } $_SESSION['pesan'] = ''; ?>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" action="<?php echo base_url('h3/h3_md_pho/injects');?>" method="post" enctype="multipart/form-data">
            <div class="box-body">       
              <!-- <div class="form-group"> -->
              <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">File .PHO</label>
                  <div class="col-sm-4">
                    <input type="file" name="file" class="form-control" id="file" accept=".pho,.PHO">
                  </div>                                     
                </div>    
              <div class="modal-footer">
                <div align="left">
                  <button type="submit" name="import" value="upload" class="btn btn-info btn-flat"><i class="fa fa-upload"></i> upload</button>
                  <button type="submit" name="import_v2" value="upload_v2" class="btn btn-success btn-flat"><i class="fa fa-upload"></i> upload V2</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php endif; ?>
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/import">
            <button class="btn bg-blue btn-flat margin">Import</button>
          </a>
        </h3>
        <?php if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {?>
          <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
              <span aria-hidden="true">&times;</span>
              <span class="sr-only">Close</span>
            </button>
          </div>
      <?php } $_SESSION['pesan'] = ''; ?>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php $this->load->view('template/normal_session_message.php'); ?> 
        <table id="pho" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Kode Part</th>              
              <th>Nama Part</th>              
              <th>HOO Flag</th>              
              <th>HOO Max</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
       
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>
<script type="text/javascript">
    var table;
    $(document).ready(function() {
        table = $('#pho').DataTable({ 
            "processing": true, 
            "serverSide": true, 
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('h3/h3_md_pho/getDataTable')?>",
                "type": "POST"
            },
            "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false, 
            },
            ],
        });
    });
</script>
