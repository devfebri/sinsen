<base href="<?php echo base_url(); ?>" />

<div class="content-wrapper">
  <!-- Content Header (Page header) -->

  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>

    <ol class="breadcrumb">
      <li>
        <a href="panel/home"><i class="fa fa-home"></i> Dashboard</a>
      </li>

      <li class="">Master Data</li>

      <li class="">CRM H23</li>

      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php

    if ($set == "setting") {

    ?>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title"><br /></h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse">
              <i class="fa fa-minus"></i>
            </button>
            <button class="btn btn-box-tool" data-widget="remove">
              <i class="fa fa-remove"></i>
            </button>
          </div>
        </div>
        <!-- /.box-header -->

        <div class="box-body">
          <?php
          if (
            isset($_SESSION['pesan']) && $_SESSION['pesan'] <>
            ''
          ) { ?>
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
              <form id="form_" class="form-horizontal">
                <div class="box-body">
                  <h4 style="padding-left: 15px"><b>Waktu SMS Reminder Service</b></h4>
                  <br>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Reminder Service Via SMS</label>
                    <div class="col-sm-4">
                      <input type="text" name="reminder_service_via_sms" class="form-control" value="<?= $bg->reminder_service_via_sms ?>" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Contact Customer Service Via SMS</label>
                    <div class="col-sm-4">
                      <input type="text" name="contact_customer_service_via_sms" class="form-control" value="<?= $bg->contact_customer_service_via_sms ?>" required>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="col-sm-12" align="center">
                    <button type="button" class="btn btn-primary btn-flat" id="submitBtn">
                      <i class="fa fa-save"></i> Save All
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- /.box -->
      <script>
        $('#submitBtn').click(function() {
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
          var values = {};
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {

            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').attr('disabled', true);
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                },
                url: '<?= base_url('master/' . $isi . '/save') ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    $('#submitBtn').attr('disabled', false);
                    alert(response.pesan);
                  }
                },
                error: function() {
                  alert("failure");
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                  $('#submitBtn').attr('disabled', false);

                },
                statusCode: {
                  500: function() {
                    alert('fail');
                    $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                    $('#submitBtn').attr('disabled', false);

                  }
                }
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')
          }
        })
      </script>
    <?php } ?>
  </section>
</div>