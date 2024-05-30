<base href="<?php echo base_url(); ?>" />
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
  Vue.filter('toCurrency', function(value) {
    // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
    // if (typeof value !== "number") {
    //   return value;
    // }
    return accounting.formatMoney(value, "", 0, ".", ",");
    return value;
  });
</script>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">HC3</li>
      <li class="">Upload CSL</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php
    if ($set == "view") {
    ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="<?= $folder . '/' . $isi ?>/add" class="btn bg-blue btn-flat">
              <i class="fa fa-plus"></i> Add New
            </a>
          </h3>
        </div>
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
                      <label>Aktif</label>
                      <select class='form-control' id='active'>
                        <option value=''>-choose-</option>
                        <option value='1'>Ya</option>
                        <option value='0'>Tidak</option>
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
          </div>
          <table class="table table-striped table-bordered table-hover table-condensed" id="tr_datatables" style="width: 100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Kategori</th>
                <th>Kode Atribut</th>
                <th>Nama Atribut</th>
                <th width='5%'>Aktif</th>
                <th width='5%'>Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tr_datatables').DataTable({
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
                    d.active = $('#active').val();
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [{
                    "targets": [4, 5],
                    "orderable": false
                  },
                  {
                    "targets": [4, 5],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
              });
            });

            function search() {
              $('#tr_datatables').DataTable().ajax.reload();
            }

            function refresh() {
              $('#kategori').val('');
              $('#active').val('');
              $('#tr_datatables').DataTable().ajax.reload();
            }
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    } elseif ($set == "form") {
      $form = '';
      $disabled = '';
      if ($mode == 'insert') {
        $form = 'save';
      } elseif ($mode == 'edit') {
        $form = 'save_edit';
      }
    ?>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="<?= $folder . '/' . $isi ?>" class="btn bg-maroon btn-flat">
              <i class="fa fa-eye"></i> View Data
            </a>
          </h3>
        </div>
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
                      <input type="hidden" class='form-control' readonly value="<?= isset($row) ? $row->id : '' ?>" name='id'>
                      <label for="field-1" class="col-sm-2 control-label">Kategori *</label>
                      <div class="col-sm-2">
                        <select class='form-control' name='kategori' v-model='kategori' :disabled="disabled" required>
                          <option value=''>-choose-</option>
                          <option value='h1'>H1</option>
                          <option value='h2'>H2</option>
                          <option value='h3'>H3</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Nama Atribut *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" placeholder="Nama Atribut" name="nama_atribut" id="nama_atribut" autocomplete="off" required value="<?= isset($row) ? $row->nama_atribut : '' ?>" :disabled="disabled">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Kode Atribut *</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" placeholder="Kode Atribut" name="code" id="code" autocomplete="off" required value="<?= isset($row) ? $row->code : '' ?>" :disabled="disabled">
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label">Aktif</label>
                    <div class="col-sm-2">
                      <select class='form-control' name='active' v-model='active'>
                        <option value=1>Ya</option>
                        <option value=0>Tidak</option>
                      </select>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align='center'>
                    <button v-if="mode=='insert' || mode=='edit'" type="button" id="submitBtn" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div>
      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            active: <?= isset($row) ? $row->active : '1' ?>,
            kategori: '<?= isset($row) ? $row->kategori : '' ?>',
          },
          methods: {

          },
          computed: {
            disabled: function() {
              let disabled = false;
              if (this.mode === 'approved') {
                disabled = true;
              } else if (this.mode === 'detail') {
                disabled = true;
              }
              return disabled
            }
          }
        })
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
          var values = {
            details: form_.details
          };
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if ($('#form_').valid()) // check if form is valid
          {
            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#submitBtn').attr('disabled', true);
                },
                url: '<?= base_url('hc3/' . $isi . '/' . $form) ?>',
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

        function setApproval(params, el) {
          var values = {
            set: params
          };
          var form = $('#form_').serializeArray();
          for (field of form) {
            values[field.name] = field.value;
          }
          if (confirm("Apakah anda yakin ?") == true) {
            $.ajax({
              beforeSend: function() {
                $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
                $(el).attr('disabled', true);
              },
              url: '<?= base_url('h2/' . $isi . '/' . $form) ?>',
              type: "POST",
              data: values,
              cache: false,
              dataType: 'JSON',
              success: function(response) {
                if (response.status == 'sukses') {
                  window.location = response.link;
                } else {
                  alert(response.pesan);
                  $(el).attr('disabled', false);
                }
                $(el).html('<i class="fa fa-save"></i> Save All');
              },
              error: function() {
                alert("Something Went Wrong !");
                $(el).html('<i class="fa fa-save"></i> Save All');
                $(el).attr('disabled', false);

              }
            });
          }
        }

        function pilihAHASS(ahass) {
          $('#kode_dealer_md').val(ahass.kode_dealer_md);
          $('#nama_ahass').val(ahass.nama_dealer);
          $('#id_dealer').val(ahass.id_dealer);
        }
      </script>
    <?php } ?>
  </section>
</div>