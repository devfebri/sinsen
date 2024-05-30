<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

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
      <li class=""><?= strtoupper($folder) ?></li>
      <li class="">Upload CSL</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php if ($set == "upload") {
      $disabled = '';
      if ($mode == 'detail') {
        $disabled = 'disabled';
      }
    ?>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="<?= $folder . '/' . $page ?>">
              <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
            <a href="<?= base_url('downloads/csl-template/template-csl.xlsx') ?>" class="btn btn-success btn-flat margin" download><i class="fa fa-download"></i> Download Template</a>
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
              <form class="form-horizontal" id='form_'>
                <div class="box-body">
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Tahun *</label>
                      <div class="col-sm-4">
                        <select class='form-control' name='tahun' v-model='tahun' required <?= $disabled ?>>
                          <option value=''>-choose-</option>
                          <?php for ($i = date('Y'); $i >= 2019; $i--) {  ?>
                            <option value='<?= $i ?>'><?= $i ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Bulan *</label>
                      <div class="col-sm-4">
                        <select class='form-control' name='bulan' v-model='bulan' required <?= $disabled ?>>
                          <option value=''>-choose-</option>
                          <?php for ($i = 1; $i <= 12; $i++) {  ?>
                            <option value='<?= $i ?>'><?= $i ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tipe *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" name="tipe" id="tipe" autocomplete="off" required value="<?= isset($row) ? $row->tipe : 'ALL' ?>" <?= $disabled ?>>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="field-1" class="col-sm-2 control-label">Kategori *</label>
                      <div class="col-sm-2">
                        <select class='form-control' name='kategori' v-model='kategori' required <?= $disabled ?>>
                          <option value=''>-choose-</option>
                          <option value='h1'>H1</option>
                          <option value='h2'>H2</option>
                          <option value='h3'>H3</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <?php if ($mode == 'upload') { ?>
                    <div class="form-group">
                      <div class="form-input">
                        <label for="inputEmail3" class="col-sm-2 control-label">Choose File</label>
                        <div class="col-sm-10">
                          <input type="file" accept=".xlsx, .xls" required class="form-control" autofocus name="file_csl">
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                  <button v-if="mode=='detail'" style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Detail</button>
                  <table class="table table-bordered table-hover table-condensed table-stripped" v-if="mode=='detail'" style='margin-top:20px'>
                    <thead>
                      <th>No.</th>
                      <th>Kode Dealer</th>
                      <th>Nama Dealer</th>
                      <th v-for="(dt, index) of detail_target">{{dt.code}} (%)</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of detail_actual">
                        <td>{{index+1}}</td>
                        <td>{{dt.kode_dealer_md}}</td>
                        <td>{{dt.nama_dealer}}</td>
                        <td v-for="(act, index) of dt.actual">{{act.actual}}</td>
                      </tr>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <?php if ($mode == 'upload') { ?>
                    <div class="col-sm-12" align='center'>
                      <input name='submit' value='submit' type='hidden'>
                      <button type="button" id="submitBtn" name="upload" value="upload" class="btn btn-info btn-flat"><i class="fa fa-upload"></i> Start Upload</button>
                    </div>
                  <?php } ?>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <style>
        span.error {
          outline: none;
          border: 1px solid #800000;
          box-shadow: 0 0 3px 1px #800000;
        }
      </style>
      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            tahun: '<?= isset($row) ? $row->tahun : '' ?>',
            bulan: '<?= isset($row) ? $row->bulan : '' ?>',
            kategori: '<?= isset($row) ? $row->kategori : '' ?>',
            detail_actual: <?= isset($row) ? json_encode($detail_actual) : '[]' ?>,
            detail_target: <?= isset($row) ? json_encode($detail_target) : '[]' ?>,
          },
        })
        $('#submitBtn').click(function() {
          $('#form_').validate({
            highlight: function(element, errorClass, validClass) {
              var elem = $(element);
              if (elem.hasClass("select2-hidden-accessible")) {
                $("#select2-" + elem.attr("id") + "-container").parent().addClass(errorClass);
              } else {
                $(element).parents('.form-input').addClass('has-error');
              }
            },
            unhighlight: function(element, errorClass, validClass) {
              var elem = $(element);
              if (elem.hasClass("select2-hidden-accessible")) {
                $("#select2-" + elem.attr("id") + "-container").parent().removeClass(errorClass);
              } else {
                $(element).parents('.form-input').removeClass('has-error');
              }
            },
            errorPlacement: function(error, element) {
              var elem = $(element);
              if (elem.hasClass("select2-hidden-accessible")) {
                element = $("#select2-" + elem.attr("id") + "-container").parent();
                error.insertAfter(element);
              } else {
                error.insertAfter(element);
              }
            }
          })
          var values = new FormData($('#form_')[0]);

          if ($('#form_').valid()) // check if form is valid
          {
            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#submitBtn').attr('disabled', true);
                },
                enctype: 'multipart/form-data',
                url: '<?= base_url('hc3/upload_csl/upload') ?>',
                type: "POST",
                data: values,
                processData: false,
                contentType: false,
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
                  alert("Something Went Wrong !");
                  $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                  $('#submitBtn').attr('disabled', false);

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
            <a href="hc3/upload_csl/upload">
              <button class="btn btn-info btn-flat margin"><i class="fa fa-upload"></i> Upload</button>
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
              <div class="box box-default box-solid collapsed-box">
                <div class="box-header with-border">
                  <h3 class="box-title">Search</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                    </button>
                  </div>
                  <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-2">
                      <label>Kategori</label>
                      <select class='form-control' id='kategori'>
                        <option value=''>-choose-</option>
                        <option value='h1'>H1</option>
                        <option value='h2'>H2</option>
                        <option value='h23'>H23</option>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <label>Tahun</label>
                      <select class='form-control' id='tahun'>
                        <option value=''>-choose-</option>
                        <?php for ($i = date('Y'); $i >= 2019; $i--) {  ?>
                          <option value='<?= $i ?>'><?= $i ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="col-sm-2">
                      <label>Bulan</label>
                      <select class='form-control' id='bulan'>
                        <option value=''>-choose-</option>
                        <?php for ($i = 1; $i <= 12; $i++) {  ?>
                          <option value='<?= $i ?>'><?= $i ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="box-footer" align='center'>
                  <button class='btn btn-primary' type="button" onclick="search()"><i class="fa fa-search"></i></button>
                  <button class='btn btn-default' type="button" onclick="refresh()"><i class="fa fa-refresh"></i></button>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <?php
            $data = ['data' => ['modalAHASS']];
            $this->load->view('h2/api', $data);
            ?>
          </div>
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_datatables" style="width: 100%">
            <thead>
              <tr>
                <th>ID Upload</th>
                <th>Tgl Upload</th>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Tipe</th>
                <th>Kategori</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_datatables').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": "",
                  "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                },
                order: [],
                ajax: {
                  url: "<?= base_url($folder . '/' . $isi . '/fetch') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    d.kategori = $('#kategori').val();
                    d.tahun = $('#tahun').val();
                    d.bulan = $('#bulan').val();
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [{
                    "targets": [6],
                    "orderable": false
                  },
                  {
                    "targets": [6],
                    "className": 'text-center'
                  },
                  // {
                  //   "targets": [5],
                  //   "className": 'text-right'
                  // },
                  // { "targets":[4], "searchable": false } 
                ],
              });
            });

            function search() {
              $('#tbl_datatables').DataTable().ajax.reload();
            }

            function refresh() {
              $('#kategori').val('');
              $('#tahun').val('');
              $('#bulan').val('');
              $('#tbl_datatables').DataTable().ajax.reload();
            }
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->

    <?php
    }
    ?>
  </section>
</div>