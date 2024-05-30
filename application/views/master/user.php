<?php if (isset($_GET['id'])) { ?>

  <body onload="cek_user_edit()">
  <?php } else { ?>
    <div>

      <body onload="start()">
      <?php } ?>
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
            <li class="">User</li>
            <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
          </ol>
        </section>
        <section class="content">
          <?php
          if ($set == "form") {
            $form = '';
            if ($mode == 'insert') {
              $form = "save";
            } elseif ($mode == 'edit') {
              $form = "update";
            }
          ?>
            <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>

            <div class="box box-default">
              <div class="box-header with-border">
                <h3 class="box-title">
                  <a href="master/user">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
                    <form method="POST" id='form_' class="form-horizontal form-groups-bordered">
                      <div class="box-body">
                        <div class="form-group">
                          <?php if (isset($row)) { ?>
                            <input type='hidden' name='id' value='<?= $row->id_user ?>' />
                          <?php } ?>
                          <label for="field-1" class="col-sm-2 control-label">MD/Dealer <span>*</span></label>
                          <div class="form-input">
                            <div class="col-sm-4">
                              <select class="form-control" name="jenis_user" id="jenis_user" onchange="cek_user()" v-model="jenis_user" required :disabled="mode=='detail'">
                                <option value="">- choose -</option>
                                <option value="Main Dealer">Main Dealer</option>
                                <option value="Dealer">Dealer</option>
                              </select>
                            </div>
                          </div>
                          <span id="form_dealer">
                            <label for="field-1" class="col-sm-2 control-label">Nama Dealer *</label>
                            <div class="col-sm-4">
                              <select class="form-control select2" name="nama_dealer" id="id_dealer" onchange="cek_user()" :disabled="mode=='detail'" required>
                                <option value="">- choose -</option>
                                <?php
                                $dt_dealer = $this->m_admin->getSortCond("ms_dealer", "nama_dealer", "ASC");
                                foreach ($dt_dealer->result() as $val) {
                                  $selected = '';
                                  if (isset($row)) {
                                    $selected = $row->id_dealer == $val->id_dealer ? 'selected' : '';
                                  }
                                  echo "
                          <option value='$val->id_dealer' $selected>$val->kode_dealer_md | $val->nama_dealer</option>;
                          ";
                                }
                                ?>
                              </select>
                            </div>
                          </span>
                        </div>
                        <div class="form-group">
                          <label for="field-1" class="col-sm-2 control-label">Jenis User *</label>
                          <div class="form-input">
                            <div class="col-sm-4">
                              <select class="form-control" name="jenis_user_bagian" id="jenis_user_bagian" v-model="jenis_user_bagian" :disabled="mode=='detail'" required>
                                <option value="">- choose -</option>
                                <option value="h1">H1</option>
                                <option value="h23">H23</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="field-1" class="col-sm-2 control-label">Karyawan *</label>
                          <div class="form-input">
                            <div class="col-sm-4">
                              <select class="form-control select2" name="id_karyawan" id="id_karyawan" required :disabled="mode=='detail'">
                                <?php
                                if (isset($row)) {
                                  if ($row->jenis_user == 'Main Dealer') {
                                    $dt_cust    = $this->m_admin->getByID("ms_karyawan", "id_karyawan", $row->id_karyawan_dealer)->row();
                                    if (isset($dt_cust)) {
                                      $text = "$dt_cust->id_karyawan | $dt_cust->npk | $dt_cust->nama_lengkap";
                                    } else {
                                      $text = "- choose -";
                                    }
                                  } else {
                                    $dt_cust    = $this->m_admin->getByID("ms_karyawan_dealer", "id_karyawan_dealer", $row->id_karyawan_dealer)->row();
                                    if (isset($dt_cust)) {
                                      $text = "$dt_cust->id_karyawan_dealer | $dt_cust->id_flp_md | $dt_cust->nama_lengkap";
                                    } else {
                                      $text = "- choose -";
                                    }
                                  } ?>
                                  <option value="<?= $row->id_karyawan_dealer ?>"><?= $text ?></option>
                                <?php } else { ?>
                                  <option value="">- choose -</option>
                                <?php } ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="field-1" class="col-sm-2 control-label">Akses DMS</label>
                          <div class="col-sm-4">
                            <input v-model='akses_dms' name='akses_dms' type="checkbox" true-value='1' false-value='0' :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group" v-if="akses_dms=='1'">
                          <label for="field-1" class="col-sm-2 control-label">Username <span>*</span></label>
                          <div class="form-input">
                            <div class="col-sm-4">
                              <input type="text" class="form-control" id="username" placeholder="Username" name="username" required value="<?= isset($row) ? $row->username : '' ?>" :disabled="mode=='detail'">
                            </div>
                          </div>
                          <label for="field-1" class="col-sm-2 control-label">Password <span v-if="mode=='insert'">*</span></label>
                          <div class="form-input">
                            <div class="col-sm-4">
                              <input type="password" class="form-control" id="password" placeholder="Password" name="password" :disabled="mode=='detail'" :required="mode=='insert'">
                            </div>
                          </div>
                        </div>
                        <div class="form-group" v-if="akses_dms=='1'">
                          <label for="field-1" class="col-sm-2 control-label">Admin Password <span v-if="mode=='insert'">*</span></label>
                          <div class="form-input">
                            <div class="col-sm-4">
                              <input type="password" class="form-control" id="admin_password" placeholder="Admin Password" name="admin_password" :required="mode=='insert'" value="<?= isset($row) ? $row->admin_password : '' ?>" :disabled="mode=='detail'">
                            </div>
                          </div>

                          <label for="field-1" class="col-sm-2 control-label">User Group <span>*</span></label>
                          <div class="form-input">
                            <div class="col-sm-4">
                              <select class="form-control" name="id_user_group" id="id_user_group" required :disabled="mode=='detail'">
                                <option value="">- choose -</option>
                                <?php
                                $dt_user_group = $this->db->query("SELECT * FROM ms_user_group WHERE jenis_user != 'Admin' AND jenis_user != 'Super Admin'");
                                foreach ($dt_user_group->result() as $val) {
                                  $selected = '';
                                  if (isset($row)) {
                                    if ($val->id_user_group == $row->id_user_group) {
                                      $selected = 'selected';
                                    }
                                  }
                                  echo "
                        <option value='$val->id_user_group' $selected>$val->user_group</option>;
                        ";
                                }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="form-group" v-if="akses_dms=='1'">
                          <label for="field-1" class="col-sm-2 control-label">Avatar</label>
                          <div class="col-sm-2">
                            <input type="file" name="avatar" :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group" v-if="akses_dms=='1'">
                          <label for="field-1" class="col-sm-2 control-label">Status User</label>
                          <div class="col-sm-4">
                            <input v-model="active" name='active' type="radio" value='1' :disabled="mode=='detail'"> Aktif &nbsp;&nbsp;&nbsp;
                            <input v-model="active" name='active' type="radio" value='0' :disabled="mode=='detail'"> Tidak Aktif
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="field-1" class="col-sm-2 control-label">Akses Service Concept</label>
                          <div class="col-sm-4">
                            <input v-model='akses_sc' name='akses_sc' type="checkbox" true-value='1' false-value='0' :disabled="mode=='detail'">
                          </div>
                        </div>
                        <div class="form-group" v-if="akses_sc=='1'">
                          <label for="field-1" class="col-sm-2 control-label">Username <span>*</span></label>
                          <div class="form-input">
                            <div class="col-sm-4">
                              <input type="text" class="form-control" id="username_sc" placeholder="Username Login Service Concept" name="username_sc" required value="<?= isset($row) ? $row->username_sc : '' ?>" :disabled="mode=='detail'">
                            </div>
                          </div>
                          <label for="field-1" class="col-sm-2 control-label">Password <span v-if="mode=='insert'">*</span></label>
                          <div class="form-input">
                            <div class="col-sm-4">
                              <input type="password" class="form-control" id="password_sc" placeholder="Password Service Concept" name="password_sc" :disabled="mode=='detail'" :required="mode=='insert'">
                            </div>
                          </div>
                        </div>
                        <div class="form-group" v-if="akses_sc=='1'">
                          <label for="field-1" class="col-sm-2 control-label">Role Service Concept *</label>
                          <div class="form-input">
                            <div class="col-sm-4">
                              <select class="form-control" name="role_sc" id="role_sc" v-model="role_sc" required :disabled="mode=='detail'">
                                <option value="">- choose -</option>
                                <?php $rsc = $this->m_user->getRoleServiceConcept(['aktif' => 1]);
                                foreach ($rsc->result() as $rs) { ?>
                                  <option value="<?= $rs->id ?>"><?= $rs->role ?></option>
                                <?php }
                                ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="form-group" v-if="akses_sc=='1'">
                          <label for="field-1" class="col-sm-2 control-label">Status User</label>
                          <div class="col-sm-4">
                            <input v-model="active_sc" name='active_sc' type="radio" value='1' :disabled="mode=='detail'"> Aktif &nbsp;&nbsp;&nbsp;
                            <input v-model="active_sc" name='active_sc' type="radio" value='0' :disabled="mode=='detail'"> Tidak Aktif
                          </div>
                        </div>
                      </div>
                      <div class="box-footer">
                        <div class="col-sm-12 text-center" v-if="mode!='detail'">
                          <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save</button>
                        </div>
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
                  jenis_user: '<?= isset($row) ? $row->jenis_user : '' ?>',
                  jenis_user_bagian: '<?= isset($row) ? $row->jenis_user_bagian : '' ?>',
                  status_user_sc: 'Non Aktif',
                  akses_sc: '<?= isset($row) ? $row->akses_sc : 0 ?>',
                  active_sc: '<?= isset($row) ? $row->active_sc : 1 ?>',
                  role_sc: '<?= isset($row) ? $row->role_sc : '' ?>',
                  akses_dms: '<?= isset($row) ? $row->akses_dms : 0 ?>',
                  active: '<?= isset($row) ? $row->active : 1 ?>',
                  status_user_dms: 'Non Aktif',
                },
                methods: {},
                watch: {
                  active_sc: function() {
                    this.status_user_sc = 'Non Aktif';
                    if (parseInt(this.active_sc) == 1) {
                      this.status_user_sc = 'Aktif';
                    }
                  },
                  active: function() {
                    this.status_user_dms = 'Non Aktif';
                    if (parseInt(this.active) == 1) {
                      this.status_user_dms = 'Aktif';
                    }
                  }
                }
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
                  if (parseInt(form_.akses_dms) === 0 && parseInt(form_.akses_sc) === 0) {
                    alert('Tentukan User dapat melakukan akses DMS, akses Service Concept atau keduanya')
                    return false;
                  }
                  if (confirm("Apakah anda yakin ?") == true) {
                    $.ajax({
                      beforeSend: function() {
                        $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                        $('#submitBtn').attr('disabled', true);
                      },
                      enctype: 'multipart/form-data',
                      url: '<?= base_url('master/' . $isi . '/' . $form) ?>',
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
                  <a href="master/user/add">
                    <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th width="5%">No</th>
                      <th>Username DMS</th>
                      <th>Username SC</th>
                      <th>Fullname</th>
                      <th>Jabatan</th>
                      <th>Group</th>
                      <th>Kode Dealer</th>
                      <th>Nama Dealer</th>
                      <!--               <th>Last Login IP</th>
              <th>Last Login Date</th>
              <th>Last Login Duration</th> -->
                      <th width="13%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($dt_user->result() as $val) {
                      if ($val->jenis_user == 'Dealer') {
                        $user = $this->db->query("SELECT ms_karyawan_dealer.*,ms_divisi.divisi,ms_jabatan.jabatan,(SELECT nama_dealer FROM ms_dealer WHERE id_dealer=ms_karyawan_dealer.id_dealer) AS dealer,
                (SELECT kode_dealer_md FROM ms_dealer WHERE id_dealer=ms_karyawan_dealer.id_dealer) AS kode_dealer FROM ms_karyawan_dealer                     
                      LEFT JOIN ms_divisi ON ms_karyawan_dealer.id_divisi = ms_divisi.id_divisi
                      LEFT JOIN ms_jabatan ON ms_karyawan_dealer.id_jabatan = ms_jabatan.id_jabatan 
                      WHERE ms_karyawan_dealer.id_karyawan_dealer = '$val->id_karyawan_dealer'");
                      } else {
                        $user = $this->db->query("SELECT ms_karyawan.*,ms_divisi.divisi,ms_jabatan.jabatan,'MAIN DEALER' AS dealer,'' AS kode_dealer FROM ms_karyawan                     
                      LEFT JOIN ms_divisi ON ms_karyawan.id_divisi = ms_divisi.id_divisi
                      LEFT JOIN ms_jabatan ON ms_karyawan.id_jabatan = ms_jabatan.id_jabatan 
                      WHERE ms_karyawan.id_karyawan = '$val->id_karyawan_dealer'");
                      }

                      if ($user->num_rows() > 0) {
                        $u            = $user->row();
                        $nama_lengkap = $u->nama_lengkap;
                        $divisi       = $u->divisi;
                        $jabatan      = $u->jabatan;
                        $dealer       = $u->dealer;
                        $kode_dealer       = $u->kode_dealer;
                      } else {
                        $nama_lengkap = "";
                        $divisi       = "";
                        $jabatan      = "";
                        $dealer       = "";
                        $kode_dealer = '';
                      }
                      if ($val->status == 'online') {
                        $status = "<span class='label label-success'>online</span>";
                      } else {
                        $status = "<span class='label label-danger'>offline</span>";
                      }
                      if ($val->username == NULL) {
                        $status = '';
                      }
                      echo "
              <tr>
                <td>$no</td>
                <td>$val->username <br> $status</td>
                <td>$val->username_sc</td>
                <td>$nama_lengkap</td>               
                <td>$jabatan</td>                                                          
                <td>$val->user_group</td>";
                      // <td>$val->last_login_ip</td>               
                      // <td>$val->last_login_date</td>               
                      // <td>$val->last_login_duration minutes</td>               
                      echo "
                  <td>$kode_dealer</td>
                  <td>$dealer</td>
                <td>"; ?>
                      <a href="master/user/delete?id=<?php echo $val->id_user ?>"><button type="button" class="btn btn-danger btn-sm btn-flat" title="Delete" onclick="return confirm('Are you sure want to delete this data?')"><i class="fa fa-trash"></i></button></a>
                      <a href="master/user/edit?id=<?php echo $val->id_user ?>"><button type='button' class="btn btn-info btn-sm btn-flat" title="Edit"><i class="fa fa-pencil"></i></button></a>
                      <a href="master/user/view?id=<?php echo $val->id_user ?>"><button type='button' class="btn btn-primary btn-sm btn-flat" title="View"><i class="fa fa-eye"></i></button></a>
                      </td>
                      </tr>
                    <?php
                      $no++;
                    }
                    ?>
                  </tbody>
                </table>
              </div><!-- /.box-body -->
            </div><!-- /.box -->

          <?php
          } elseif ($set == "view_new") {
            ?>
  
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">
                    <a href="master/user/add">
                      <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
                  <table id="table_user" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th width="5%">No</th>
                        <th>Username DMS</th>
                        <th>Username SC</th>
                        <th>Fullname</th>
                        <th>Jabatan</th>
                        <th>Group</th>
                        <th>Kode Dealer</th>
                        <th>Nama Dealer</th>
                        <th width="13%">Action</th>
                      </tr>
                    </thead>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
              <script src="assets/panel/plugins/datatables/jquery.dataTables.min.js"></script>
            <script src="assets/panel/plugins/datatables/dataTables.bootstrap.min.js"></script>

            <script>
              $(document).ready(function() {
                $('#table_user').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": "",
                    "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('master/user/fetch_data_prospek_datatables') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      return d;
                    },
                    type: "POST"
                  },
                  "columnDefs": [{
                      "targets": [3,4,5,6,7,8],
                      "orderable": false
                    },
                    {
                      "targets": [1],
                      "className": 'text-center'
                    },
                    // {
                    //   "targets": [5],
                    //   "className": 'text-right'
                    // },
                    { "targets":[3,4,5,6,7,8], "searchable": false } 
                  ],
                });
              });
            </script>
             

            <?php
            }
          ?>
        </section>
      </div>

      <div class="modal fade" id="modal_foto">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <center>
                <img src="assets/panel/images/user/<?php echo $row->avatar ?>" width='100%'>
              </center>
            </div>
          </div>
        </div>
      </div>


      <script type="text/javascript">
        // $("#id_user_group").change(function(){
        //   //var id_user_group = $("#id_user_group").val();  
        //   alert("s"); 
        // });
      </script>
      <script type="text/javascript">
        function start() {
          $("#form_dealer").hide();
        }

        function cek_user_edit() {
          var jenis_user = $("#jenis_user").val();
          var id_dealer = $("#id_dealer").val();
          if (jenis_user == 'Main Dealer') {
            $("#form_dealer").hide();
          } else {
            $("#form_dealer").show();
          }
        }

        function cek_user() {
          var jenis_user = $("#jenis_user").val();
          var id_dealer = $("#id_dealer").val();
          if (jenis_user == 'Main Dealer') {
            $("#form_dealer").hide();
          } else {
            $("#form_dealer").show();
          }
          $.ajax({
            url: "<?php echo site_url('master/user/get_slot') ?>",
            type: "POST",
            data: "jenis_user=" + jenis_user + "&id_dealer=" + id_dealer,
            cache: false,
            success: function(msg) {
              $("#id_karyawan").html(msg);
            }
          })
        }

        function cek() {
          var id_user_group = $("#id_user_group").val();
          $.ajax({
            url: "<?php echo site_url('master/user/get_user_group') ?>",
            type: "POST",
            data: "id_user_group=" + id_user_group,
            cache: false,
            success: function(msg) {
              $("#id_user_level").html(msg);
            }
          })
        }

        function bulk_delete() {
          var list_id = [];
          $(".data-check:checked").each(function() {
            list_id.push(this.value);
          });
          if (list_id.length > 0) {
            if (confirm('Are you sure delete this ' + list_id.length + ' data?')) {
              $.ajax({
                type: "POST",
                data: {
                  id: list_id
                },
                url: "<?php echo site_url('master/user/ajax_bulk_delete') ?>",
                dataType: "JSON",
                success: function(data) {
                  if (data.status) {
                    window.location.reload();
                  } else {
                    alert('Failed.');
                  }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                  alert('Error deleting data');
                }
              });
            }
          } else {
            alert('no data selected');
          }
        }
      </script>