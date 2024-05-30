<style type="text/css">
  .myTable1 {
    margin-bottom: 0px;
  }

  .myt {
    margin-top: 0px;
  }

  .isi {
    height: 25px;
    padding-left: 4px;
    padding-right: 4px;
  }
</style>
<base href="<?php echo base_url(); ?>" />

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">Dealer Group Integration</li>
      <li class="">Web Service</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php
    if ($set == "form") {
      $form = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'edit') {
        $form = 'save_edit';
        $readonly = 'readonly';
      }
      if ($mode == 'detail') {
        $form = '';
        $disabled = 'disabled';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <script>
        Vue.use(VueNumeric.default);
        Vue.filter('toCurrency', function(value) {
          // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
          if (typeof value !== "number") {
            return value;
          }
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/dgi_activity_log">
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
              <form id="form_" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">ID Dealer</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="id_dealer" id="id_dealer" autocomplete="off" value="<?= isset($row) ? $row->id_dealer : '' ?>" readonly required onclick="showModalDealer()">
                      </div>
                    </div>
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama dealer</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="nama_dealer" id="nama_dealer" autocomplete="off" value="<?= isset($row) ? $row->nama_dealer : '' ?>" readonly required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">API Key</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="api_key" id="api_key" autocomplete="off" value="<?= isset($row) ? $row->api_key : '' ?>" readonly required>
                      </div>
                    </div>
                    <div class="form-ipnut">
                      <label for="inputEmail3" class="col-sm-2 control-label">Secret Key</label>
                      <div class="col-sm-3">
                        <input type="text" class="form-control" name="secret_key" id="secret_key" autocomplete="off" value="<?= isset($row) ? $row->secret_key : '' ?>" readonly required>
                      </div>
                    </div>
                    <div class="col-sm-1">
                      <button type='button' class='btn btn-primary btn-flat' id='btnGenerate' onclick='generateKey()'><i class='fa fa-refresh'></i></button>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align="center" v-if="mode=='insert' || mode=='edit'">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <?php
      $data['data'] = ['modalDealer'];
      $this->load->view('dealer/h2_api', $data); ?>
      <script>
        function pilihDealer(params) {
          $('#id_dealer').val(params.kode_dealer_md)
          $('#nama_dealer').val(params.nama_dealer)
        }
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
          },
          methods: {

          }
        })

        function generateKey() {
          values = {
            id_dealer: $('#id_dealer').val()
          }
          $.ajax({
            beforeSend: function() {
              $('#btnGenerate').attr('disabled', true);
              $('#btnGenerate').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            url: '<?= base_url('dealer/dgi_activity_log/generateKey') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              $('#btnGenerate').html('<i class="fa fa-refresh"></i>');
              $('#btnGenerate').attr('disabled', false);
              if (response.status == 'sukses') {
                $('#api_key').val(response.data.api_key);
                $('#secret_key').val(response.data.secret_key);
              } else {
                $('#btnGenerate').attr('disabled', false);
                alert(response.pesan);
              }
            },
            error: function() {
              alert("failure");
              $('#btnGenerate').attr('disabled', false);

            }
          });
        }

        $('#submitBtn').click(function() {
          $('#form_').validate({
            rules: {
              'checkbox': {
                required: true
              }
            },
            highlight: function(input) {
              $(input).parents('.form-input').addClass('has-error');
            },
            unhighlight: function(input) {
              $(input).parents('.form-input').removeClass('has-error');
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
                url: '<?= base_url('dealer/dgi_activity_log/' . $form) ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save');
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    $('#submitBtn').attr('disabled', false);
                    alert(response.pesan);
                  }
                },
                error: function() {
                  alert("failure");
                  $('#submitBtn').attr('disabled', false);

                },
                statusCode: {
                  500: function() {
                    alert('fail');
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
    <?php
    } elseif ($set == "view") {
    ?>

      <div class="box">
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
          <table id="datatable_server" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID Dealer</th>
                <th>Nama Dealer</th>
                <th>Endpoint</th>
                <th>Request Time</th>
                <th>IP Address</th>
                <th>HTTP Response</th>
                <th>Data Count</th>
                <th>Status</th>
                <th>Message</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              var dataTable = $('#datatable_server').DataTable({
                "processing": true,
                "serverSide": true,
                "language": {
                  "infoFiltered": "",
                  "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                },
                "order": [],
                "lengthMenu": [
                  [10, 25, 50, 75, 100],
                  [10, 25, 50, 75, 100]
                ],
                "ajax": {
                  url: "<?php echo site_url('dealer/dgi_activity_log/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                },
                "columnDefs": [
                  // // { "targets":[2],"orderable":false},
                  {
                    "targets": [7],
                    "className": 'text-center'
                  },
                  // // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  // // { "targets":[4],"className":'text-right'}, 
                  // // // { "targets":[2,4,5], "searchable": false } 
                ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div>