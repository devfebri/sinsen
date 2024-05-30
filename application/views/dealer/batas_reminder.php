<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">Master Data</li>
      <li class="">Batas Reminder</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "form") {
      $form     = '';
      $disabled = '';
      $readonly = '';
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

      <script>
        Vue.use(VueNumeric.default);
        $(document).ready(function() {})
      </script>
      <div class="box box-default">
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
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id="form_" action="dealer/batas_reminder/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">Pengulangan Service Reminder</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="ulang_service_reminder" value="<?= isset($row) ? $row->ulang_service_reminder : 0 ?>" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">Pengulangan Follow Up After Service</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="ulang_follow_up_after_service" value="<?= isset($row) ? $row->ulang_follow_up_after_service : 0 ?>" autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">H+ Follow Up After Service</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="h_follow_up_after_service" value="<?= isset($row) ? $row->h_follow_up_after_service : 0 ?>" autocomplete="off">
                    </div>
                  </div>
                  <div class="box-footer">
                    <div class="col-sm-12" align="center">
                      <button type="button" id="submitBtn" onclick="funcSubmit()" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    </div>
                  </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->

      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {},
          methods: {},
        });

        function funcSubmit() {
          $('#form_').validate({
            rules: {
              'checkbox': {
                required: true
              }
            },
            highlight: function(input) {
              $(input).parents('.form-group').addClass('has-error');
            },
            unhighlight: function(input) {
              $(input).parents('.form-group').removeClass('has-error');
            }
          })

          var form = $('#form_').serializeArray();
          var values = {};
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {
            $.ajax({
              beforeSend: function() {
                $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                $('#submitBtn').attr('disabled', true);
              },
              url: '<?= base_url('dealer/batas_reminder/save') ?>',
              type: "POST",
              data: values,
              cache: false,
              dataType: 'JSON',
              success: function(response) {
                if (response.status == 'sukses') {
                  window.location = response.link;
                } else {
                  alert(response.pesan);
                  $('#submitBtn').attr('disabled', false);
                }
                $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
              },
              error: function() {
                alert("failure");
                $('#submitBtn').attr('disabled', false);
                $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
              }
            });
          } else {
            alert('Silahkan isi field required !')
          }
        }
      </script>
    <?php
    }
    ?>
  </section>
</div>