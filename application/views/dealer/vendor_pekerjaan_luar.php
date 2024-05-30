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
      <li class="">Master</li>
      <li class="">Master H2</li>
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
          // // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
          // if (typeof value !== "number") {
          //     return value;
          // }
          return "Rp. " + accounting.formatMoney(value, "", 0, ".", ",");
        });
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/vendor_pekerjaan_luar">
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
                  <div class="form-group" v-if="mode=='edit' || mode=='detail'">
                    <label for="inputEmail3" class="col-sm-2 control-label">ID Vendor</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="id_vendor" id="id_vendor" autocomplete="off" value="<?= isset($row) ? $row->id_vendor : '' ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Vendor</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="nama_vendor" id="nama_vendor" autocomplete="off" value="<?= isset($row) ? $row->nama_vendor : '' ?>" :readonly="mode=='detail'" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. HP</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" name="no_hp" id="no_hp" autocomplete="off" value="<?= isset($row) ? $row->no_hp : '' ?>" :readonly="mode=='detail'" required>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="alamat" id="alamat" autocomplete="off" value="<?= isset($row) ? $row->alamat : '' ?>" :readonly="mode=='detail'" required>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label">Aktif</label>
                    <div class="col-sm-2">
                      <div id="label-switch" class="make-switch" data-on-label="<i class='entypo-check'></i>" data-off-label="<i class='entypo-cancel'></i>">
                        <input <?= $disabled ?> type="checkbox" class="form-control flat-red" name="aktif" value="1" <?= isset($row) ? $row->aktif == 1 ? 'checked' : '' : 'checked' ?>>
                        Aktif
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Detail Pekerjaan</button><br><br>
                    </div>
                    <div class="col-sm-12">
                      <table class="table table-bordered table-condensed">
                        <thead>
                          <th>No.</th>
                          <th>Kode Pekerjaan</th>
                          <th>Deskripsi Pekerjaan</th>
                          <th v-if="mode=='insert'||mode=='edit'">Aksi</th>
                        </thead>
                        <tbody>
                          <tr v-for="(dt, index) of details">
                            <td>{{index+1}}</td>
                            <td>{{dt.id_jasa}}</td>
                            <td>{{dt.deskripsi}}</td>
                            <td align="center" v-if="mode=='insert'||mode=='edit'">
                              <button @click.prevent="delDetails(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                            </td>
                          </tr>
                        </tbody>
                        <tfoot v-if="mode=='insert'||mode=='edit'">
                          <tr>
                            <td></td>
                            <td>
                              <input class="form-control isi" v-model="detail.id_jasa" readonly onclick="showModalJasa()" />
                            </td>
                            <td>
                              <input class="form-control isi" v-model="detail.deskripsi" readonly onclick="showModalJasa()" />
                            </td>
                            <td align="center">
                              <button @click.prevent="addDetails" type="button" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i></button>
                            </td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class=" box-footer">
                  <div class="col-sm-12" align="center" v-if="mode=='insert' || mode=='edit'">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php
      $data['data'] = ['modalJasa', 'modalAHASS'];
      $this->load->view('h2/api', $data); ?>
      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            detail: {},
            details: <?= isset($row) ? json_encode($details) : '[]' ?>,
          },
          methods: {
            clearDetail: function() {
              this.detail = {}
            },
            addDetails: function() {

              if (this.detail.id_jasa === '' || this.detail.id_jasa === undefined) {
                alert('Tentukan pekerjaan terlebih dahulu !');
                return false;
              }
              this.details.push(this.detail);
              this.clearDetail();
            },
            delDetails: function(index) {
              this.details.splice(index, 1);
            },
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
              $(input).parents('.form-group').addClass('has-error');
            },
            unhighlight: function(input) {
              $(input).parents('.form-group').removeClass('has-error');
            }
          })
          var values = {
            details: form_.details,
            dealers: form_.dealers,
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
                  $('#submitBtn').attr('disabled', true);
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                },
                url: '<?= base_url('dealer/vendor_pekerjaan_luar/' . $form) ?>',
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

                }
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')
          }
        })

        function pilihJasa(js) {
          for (dt of form_.details) {
            if (dt.id_jasa === js.id_jasa) {
              alert('Pekerjaan sudah dipilih !');
              return false;
            }
          }
          form_.detail = {
            id_jasa: js.id_jasa,
            deskripsi: js.deskripsi,
            harga: js.harga
          }
        }

        function pilihAHASS(ah) {
          form_.dealer = {
            kode_dealer_md: ah.kode_dealer_md,
            id_dealer: ah.id_dealer,
            nama_dealer: ah.nama_dealer
          }
          console.log(form_.dealer);
        }
      </script>
    <?php
    } elseif ($set == "view") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if (can_access($isi, 'can_insert')) : ?>
              <a href="dealer/vendor_pekerjaan_luar/add">
                <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
              </a>
            <?php endif; ?>

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
                <th>ID Vendor</th>
                <th>Nama Vendor</th>
                <th>No. HP</th>
                <th>Alamat</th>
                <th>Aktif</th>
                <th>Aksi</th>
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
                  url: "<?php echo site_url('dealer/vendor_pekerjaan_luar/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                },
                "columnDefs": [
                  // { "targets":[2],"orderable":false},
                  {
                    "targets": [5],
                    "className": 'text-center'
                  },
                  // // { "targets":[0],"checkboxes":{'selectRow':true}}
                  // { "targets":[4],"className":'text-right'}, 
                  // // { "targets":[2,4,5], "searchable": false } 
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