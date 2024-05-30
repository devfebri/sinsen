<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">Finance H23</li>
      <li class="">Finance</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "index") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if (can_access($isi, 'can_insert')) : ?>
              <a href="dealer/<?= $isi ?>/add">
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
                <th>ID Team Structure Management</th>
                <th>Nama Team</th>
                <th>Sales Coordinator</th>
                <th>Jml. Sales People</th>
                <th>Active</th>
                <th>Aksi</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              var dataTable = $('#datatable_server').DataTable({
                "processing": true,
                "serverSide": true,
                // "scrollX": true,
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
                  url: "<?php echo site_url('dealer/' . $isi . '/fetch'); ?>",
                  type: "POST",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                },
                "columnDefs": [{
                    "targets": [3, 4, 5],
                    "orderable": false
                  },
                  {
                    "targets": [4, 5],
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
    <?php } elseif ($set == 'form') {
      $form = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'edit') {
        $form = 'save_edit';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <link href='assets/select2/css/select2.min.css' rel='stylesheet' type='text/css'>
      <script src="assets/jquery/jquery.min.js"></script>
      <script src='assets/select2/js/select2.min.js'></script>

      <script>
        Vue.use(VueNumeric.default);
        Vue.filter('toCurrency', function(value) {
          return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          // return value;
        });

        Vue.filter('cekType', function(value, arg1) {
          if (arg1 == 'persen') {
            return value + ' %';
          } else {
            return 'Rp. ' + accounting.formatMoney(value, "", 0, ".", ",");
          }
        });

        $(document).ready(function() {})
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $this->uri->segment(2); ?>">
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
            <div class="col-sm-12">
              <form class="form-horizontal" id="form_" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <div class="form-input">
                      <div class="form-input">
                        <label class="col-sm-2 control-label">Nama Team</label>
                        <div class="col-sm-3">
                          <input type="hidden" readonly v-model="data.id_team_structure" name="id_team_structure" id="id_team_structure" class="form-control" required>
                          <input type="hidden" readonly v-model="data.id_team" name="id_team" id="id_team" class="form-control" required>
                          <input type="text" readonly v-model="data.nama_team" name="nama_team" id="nama_team" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-sm-1">
                        <button v-if="mode=='insert' || mode=='edit'" type="button" onclick="showModalTeamSales()" class="btn btn-flat btn-primary" id="btnSearchTeam"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <div class="form-input">
                        <label class="col-sm-2 control-label">ID Sales Coordinator</label>
                        <div class="col-sm-3">
                          <input type="text" readonly v-model="data.id_sales_coordinator" name="id_sales_coordinator" id="id_sales_coordinator" class="form-control" required>
                        </div>
                      </div>
                      <div class="col-sm-1">
                        <button v-if="mode=='insert' || mode=='edit'" type="button" onclick="showModalKaryawanDealer('JBT-035','header')" class="btn btn-flat btn-primary" id="btnSearchSalesCoordinator"><i class="fa fa-search"></i></button>
                      </div>
                    </div>
                    <div class="form-input">
                      <label class="col-sm-2 control-label">Nama Sales Coordinator</label>
                      <div class="col-sm-4">
                        <input type="text" readonly v-model="data.nama_lengkap" class="form-control" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="field-1" class="col-sm-2 control-label">Active</label>
                    <div class="col-sm-4">
                      <input v-model='active' type="checkbox" name='active' true-value='1' false-value='0' :disabled="mode=='detail'">
                    </div>
                  </div>
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-info btn-flat btn-sm" disabled>Sales People</button><br><br>
                  <table class="table table-bordered table-hover table-condensed table-stripped">
                    <thead>
                      <th width='30%'>ID Karyawan Dealer</th>
                      <th>Honda ID</th>
                      <th>Nama Lengkap</th>
                      <th>Jabatan</th>
                      <th v-if="mode=='insert' || mode=='edit'">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dt, index) of details">
                        <td>{{dt.id_karyawan_dealer}}</td>
                        <td>{{dt.honda_id}}</td>
                        <td>{{dt.nama_lengkap}}</td>
                        <td>{{dt.jabatan}}</td>
                        <td align="center" v-if="mode=='insert' || mode=='edit'">
                          <button @click.prevent="delDetails(index)" type="button" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr v-if="mode=='insert' || mode=='edit'">
                        <td>
                          <input class="form-control input-inline isi" v-model="dtl.id_karyawan_dealer" onclick="showModalKaryawanDealer(null,'detail')" readonly placeholder='Klik Untuk Memilih' />
                        </td>
                        <td>{{dtl.honda_id}}</td>
                        <td>{{dtl.nama_lengkap}}</td>
                        <td>{{dtl.jabatan}}</td>
                        <td align="center">
                          <!-- <button @click.prevent="addDetails" type="button" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-plus"></i></button> -->
                        </td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div class="box-footer">
                  <div class="form-group">
                    <div class="col-sm-12" align="center" v-if="mode=='insert' || mode=='edit'">
                      <button type="button" id="submitBtn" @click.prevent="savePenerimaan" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php
        $data['data'] = ['modalKaryawanDealer', 'filterKaryawanNotInTeamStructure', 'modalTeamSales', 'teamNotInTeamStructure', 'filterSalesCoordinatorNotInTeamStructure'];
        $this->load->view('dealer/dgi_api', $data); ?>

        <script>
          function pilihKaryawanDealer(params) {

            if (set_from == 'header') {
              form_.data.id_sales_coordinator = params.id_karyawan_dealer;
              form_.data.nama_lengkap = params.nama_lengkap;
            } else if (set_from == 'detail') {
              for (dt of form_.details) {
                if (dt.id_karyawan_dealer == params.id_karyawan_dealer) {
                  alert('Data sudah dipilih !')
                  return false;
                }
              }
              form_.dtl.id_karyawan_dealer = params.id_karyawan_dealer;
              form_.dtl.honda_id = params.id_flp_md;
              form_.dtl.nama_lengkap = params.nama_lengkap
              form_.dtl.jabatan = params.jabatan
              form_.details.push(form_.dtl);
              form_.clearDetail();
            }
          }

          function pilihTeam(params) {
            form_.data.id_team = params.id_team
            form_.data.nama_team = params.nama_team
          }
          var set_from = '';
          var form_ = new Vue({
            el: '#form_',
            data: {
              mode: '<?= $mode ?>',
              active: '<?= isset($row) ? $row->active : '' ?>',
              data: <?= isset($row) ? json_encode($row) : "{id_sales_coordinator:'',nama_lengkap:'',id_team:'',nama_team:'',jabatan:''}" ?>,
              details: <?= isset($row) ? json_encode($details) : '[]' ?>,
              dtl: {
                id_karyawan_dealer: '',
                nama_lengkap: '',
                honda_id: '',
                jabatan: ''
              },
            },
            methods: {
              savePenerimaan: function() {
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
                if ($('#form_').valid()) // check if form is valid
                {
                  let values = {
                    details: form_.details
                  };
                  var form = $('#form_').serializeArray();
                  for (field of form) {
                    values[field.name] = field.value;
                  }
                  if (confirm("Apakah anda yakin ?") == true) {
                    if (values.details.length == 0) {
                      alert('Detail penerimaan belum ditentukan !');
                      return false;
                    }
                    $.ajax({
                      beforeSend: function() {
                        $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                        $('#submitBtn').attr('disabled', true);
                      },
                      url: '<?= base_url('dealer/' . $isi . '/' . $form) ?>',
                      type: "POST",
                      data: values,
                      cache: false,
                      dataType: 'JSON',
                      success: function(response) {
                        $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                        if (response.status == 'sukses') {
                          window.location = response.link;
                        } else {
                          alert(response.pesan);
                          $('#submitBtn').attr('disabled', false);
                        }
                      },
                      error: function() {
                        alert("Something Went Wrong !");
                        $('#submitBtn').html('<i class="fa fa-save"></i> Save All');
                        $('#submitBtn').attr('disabled', false);
                      },
                    });
                  } else {
                    return false;
                  }
                } else {
                  alert('Silahkan isi field required !')
                }
              },
              showDetailTransaksi: function(dtl) {
                console.log(dtl)
              },
              clearDetail: function() {
                this.dtl = {
                  id_karyawan_dealer: '',
                  honda_id: '',
                  nama_lengkap: '',
                  id_team: '',
                  nama_team: ''
                }
              },
              addDetails: function() {
                if (this.dtl.id_karyawan_dealer === '') {
                  alert('Karyawan belum dipilih !');
                  return false
                }
                this.details.push(this.dtl);
                this.clearDetail();
              },
              delDetails: function(index) {
                this.details.splice(index, 1);
              },
            },
          });
        </script>
      <?php } ?>
  </section>
</div>