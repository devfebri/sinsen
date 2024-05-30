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

<body>
  <?php $form = 'saveAssignSalespeople'; ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">Prospek</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/prospek_crm">
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
              <form class="form-horizontal" id="form_">
                <div class="box-body">
                  <button class="btn btn-block btn-primary btn-flat" disabled> DATA PROSPEK </button> <br>
                  <?php if (isset($row)) { ?>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">ID Prospek</label>
                      <div class="col-md-4">
                        <input type="text" class="form-control" name="id" value="<?= isset($row) ? $row->id_prospek : '' ?>" readonly>
                      </div>
                    </div>
                  <?php } ?>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Prospek</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" name="tgl_prospek" value="<?= isset($row) ? $row->tgl_prospek : date('Y-m-d') ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Sales *</label>
                    <div class="col-sm-4">
                      <div class="form-input">
                        <select class="form-control select2" name="id_karyawan_dealer" required id="id_karyawan_dealer" onchange="take_sales()" :disabled="mode=='detail'">
                          <option value="">- choose -</option>
                          <?php
                          foreach ($dt_karyawan->result() as $val) {
                            $selected = isset($row) ? $row->id_karyawan_dealer == $val->id_karyawan_dealer ? 'selected' : '' : '';
                            echo "
                        <option value='$val->id_karyawan_dealer' $selected>$val->nama_lengkap ($val->nama_dealer)</option>;
                        ";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-1">
                      <button type="button" class="btn btn-primary btn-flat btn-sm" onclick="showModalHistoryAssignedDealer()"><i class="fa fa-list"></i></button>
                    </div>
                    <label for="inputEmail3" class="col-sm-1 control-label">FLP ID *</label>
                    <div class="col-sm-4">
                      <div class="form-input">
                        <input type="hidden" class="form-control" id="nama_sales" name="nama_sales">
                        <input type="text" readonly class="form-control" required id="kode_sales" placeholder="FLP ID" name="id_flp_md" value="<?= $row->id_flp_md ?>">
                      </div>
                    </div>
                  </div>
                  <div class="form-group" v-if="ganti_sales==1">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alasan Ganti Sales *</label>
                    <div class="col-sm-4">
                      <select class="form-control" name="alasan_reassign_id" id="alasan_reassign_id" required>
                        <option value="">-Pilih-</option>
                        <?php 
                        $alasan = $this->scm->getAlasanReassign();
                        foreach ($alasan->result() as $als) { ?>
                          <option value="<?=$als->id?>"><?=$als->name?></option>
                        <?php }
                        ?>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Catatan</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" value="" name="catatan">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Sales Pembelian Sebelumnya</label>
                    <div class="col-sm-4">
                      <input type="text" disabled class="form-control" value="<?= isset($row) ? $row->nama_sales_sebelumnya : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">FLP ID Pembelian Sebelumnya</label>
                    <div class="col-sm-4">
                      <input type="text" disabled class="form-control" value="<?= isset($row) ? $row->id_flp_md_sebelumnya : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Leads ID</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" disabled value="<?= isset($row) ? $row->leads_id : '' ?>" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Customer Type</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" disabled value="<?= isset($row) ? $row->customerTypeDesc : '' ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Rangka Pembelian Sebelumnya</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" disabled value="<?= isset($row) ? $row->noFramePembelianSebelumnya : '' ?>" readonly>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Finance Company Pembelian Sebelumnya</label>
                    <div class="col-md-4">
                      <input type="text" class="form-control" disabled value="<?= isset($row) ? $row->finance_company : '' ?>" readonly>
                    </div>
                  </div>
                  <button class="btn btn-block btn-info btn-flat" disabled> INTERAKSI </button> <br>
                  <button type="button" class="btn btn-block btn-primary btn-flat btn-sm" onclick="showModalInteraksi(this,'<?= $row->id_prospek ?>')" style="width:10%;margin-bottom:10px">View All</button>
                  <div class="row">
                    <div class="col-sm-12 col-md-12">
                      <div class="table-responsive">
                        <table class='table table-condensed table-bordered'>
                          <thead>
                            <th>Kode Unit Motor</th>
                            <th>Warna Motor</th>
                            <th>Source Data</th>
                            <th>Platform Data</th>
                            <th>Keterangan</th>
                            <th>Customer Action Date</th>
                          </thead>
                          <tbody>
                            <?php foreach ($interaksi as $itr) { ?>
                              <tr>
                                <td><?= $itr->kodeTypeUnit ?></td>
                                <td><?= $itr->kodeWarnaUnit ?></td>
                                <td><?= $itr->descSourceLeads ?></td>
                                <td><?= $itr->descPlatformData ?></td>
                                <td><?= $itr->keterangan ?></td>
                                <td><?= $itr->customerActionDate ?></td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <?php $this->load->view('dealer/prospek_modal_interaksi'); ?>
                  </div>

                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" align='center'>
                    <button id="submitBtn"  type="button" onclick="saveBtn()"class="btn btn-info btn-flat">Assign Salespeople</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <script>
        function saveBtn(){
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
          values.append('details', JSON.stringify(form_.details));
          if ($('#form_').valid()) // check if form is valid
          {
            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#submitBtn').attr('disabled', true);
                },
                enctype: 'multipart/form-data',
                url: '<?= base_url('/dealer/prospek_crm/' . $form) ?>',
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
                  $('#submitBtn').html('Assign Salespeople');
                },
                error: function() {
                  alert("Something Went Wrong !");
                  $('#submitBtn').html('Assign Salespeople');
                  $('#submitBtn').attr('disabled', false);

                }
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')
          }
        }

        function take_sales() {
          var id_karyawan_dealer = $("#id_karyawan_dealer").val();
          $.ajax({
            url: "<?php echo site_url('dealer/prospek_crm/take_sales') ?>",
            type: "POST",
            data: "id_karyawan_dealer=" + id_karyawan_dealer,
            cache: false,
            success: function(msg) {
              data = msg.split("|");
              //$("#no_polisi").html(msg);                                                    
              $("#kode_sales").val(data[0]);
              $("#nama_sales").val(data[1]);
            }
          })
          if (form_.id_karyawan_dealer!='') {
            if (id_karyawan_dealer!=form_.id_karyawan_dealer) {
              form_.ganti_sales=1;
            }else{
              $("#alasan_reassign_id").val('');
              form_.ganti_sales=0;
            }
          }else{
            form_.ganti_sales=0;
          }
        }

        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            id_karyawan_dealer: '<?= $row->id_karyawan_dealer ?>',
            ganti_sales:0,
          },
          methods: {
            clearFolUp: function() {
              $('#tgl_fol_up').val('');
              this.detail = {
                tgl_fol_up: '',
                waktu_fol_up: '',
                metode_fol_up: '',
                keterangan: ''
              }
            },
          },
        });

        function showModalHistoryAssignedDealer() {
          $("#modalHistoryAssignedDealer").modal('show')
        }
      </script>
    </section>
  </div>
  
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalHistoryAssignedDealer">
  <div class="modal-dialog" style="width:70%">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">History Assigned Sales</h4>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_riwayat_servis" style="width: 100%">
          <thead>
            <tr>
              <th>Sales Lama</th>
              <th>Sales Baru</th>
              <th>Alasan Reassign</th>
              <th>Catatan</th>
              <th>Waktu Assigned</>
            </tr>
          </thead>
          <?php foreach ($history_sales as $hst) {?>
            <tr>
              <td><?=$hst->nama_sales_lama?></td>
              <td><?=$hst->nama_sales_baru?></td>
              <td><?=$hst->alasan?></td>
              <td><?=$hst->catatan?></td>
              <td><?=$hst->created_at?></td>
            </tr>
          <?php } ?>
        </table>
      </div>
    </div>
  </div>
</div>