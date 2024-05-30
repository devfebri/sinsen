<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">H2</li>
      <li class="">Service Execution</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">

    <?php
    if ($set == "index") {
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
          <table id="datatables_" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID Work Order</th>
                <th>Tgl. Servis</th>
                <th>Jenis Customer</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>No. Mesin</th>
                <th>No. Rangka</th>
                <th>Tipe Motor</th>
                <th>Warna</th>
                <th>Status</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($wo->result() as $rs) :
                $status = '';
                $button = '';

                $e_kpb = 0; //bukan untuk kpb digital
                if($rs->id_type =='ASS1' || $rs->id_type =='ASS2' || $rs->id_type =='ASS3' || $rs->id_type =='ASS4'){
                  $e_kpb = 1;
                }

                $btn_closed = '<button onclick="closed_wo(this, \'' . $rs->id_work_order . '\', \'' . $e_kpb . '\', \'' . $rs->is_ev . '\', \'' . $rs->soc . '\', \'' . $rs->serial_number_battery . '\', \'' . $rs->kesediaan_customer_lcr_id . '\', \'' . $rs->record_reason_lcr_id . '\', \'' . $rs->hasil_pengecekan_lcr_id . '\')" class="btn btn-warning btn-xs btn-flat btn_closed">Closed</button>';
                $btn_send_wo_part = '<a href="dealer/execute_wo/send_wo_part_counter?id=' . $rs->id_work_order . '" class="btn btn-primary btn-xs btn-flat">Send WO to Part Counter</a>';

                if ($rs->status_wo == 'open') {
                  if ($rs->last_stats == 'start' || $rs->last_stats == 'resume') {
                    $status = '<label class="label label-info">Working</label>';
                    $cek_parts = $this->m_h2->cekSendPartWO($rs->id_work_order);
                    if ($cek_parts > 0) {
                      $button .= $btn_send_wo_part;
                    }
                  } elseif ($rs->last_stats == null) {
                    $status = '<label class="label label-primary">Open</label>';
                    $cek_parts = $this->m_h2->cekSendPartWO($rs->id_work_order);
                    if ($cek_parts > 0) {
                      $button .= $btn_send_wo_part;
                    }
                  } elseif ($rs->last_stats == 'end') {
                    $status = '<label class="label label-success">End</label></br>
                    <label class="label label-success">Ready To Bill</label>';
                    $button .= $btn_closed;
                  }
                } elseif ($rs->status_wo == 'pause') {
                  $status = '<label class="label label-warning">Pause</label>';
                  if ($rs->last_stats == 'end') {
                    $status = '<label class="label label-success">End</label></br>
                    <label class="label label-success">Ready To Bill</label>';
                    $button .= $btn_closed;
                  } else {
                    $cek_parts = $this->m_h2->cekSendPartWO($rs->id_work_order);
                    if ($cek_parts > 0) {
                      $button .= $btn_send_wo_part;
                    }
                  }
                }
              ?>
                <tr>
                  <td><a href="dealer/execute_wo/detail_wo?id=<?= $rs->id_work_order ?>"><?= $rs->id_work_order ?></a></td>
                  <td><?= $rs->tgl_servis ?></td>
                  <td><?= $rs->jenis_customer ?></td>
                  <td><?= $rs->no_polisi ?></td>
                  <td><?= $rs->nama_customer ?></td>
                  <td><?= $rs->no_mesin ?></td>
                  <td><?= $rs->no_rangka ?></td>
                  <td><?= $rs->tipe_ahm ?></td>
                  <td><?= $rs->warna ?></td>
                  <td><?= $status ?></td>
                  <td><?= $button ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
          <script>
            $(function() {
              $('#datatables_').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "scrollX": true,
                "order": [],
                "info": true,
                fixedHeader: true,
                "lengthMenu": [
                  [10, 25, 50, 75, 100, -1],
                  [10, 25, 50, 75, 100, "All"]
                ],
                "autoWidth": true
              })
            });

            var set_id_work_order = "";
            var set_is_ev = "";
            var set_soc = "";
            var set_sn_battery = "";
            var set_kesediaan_customer_lcr_check = "";
            var set_record_reason_lcr_check = "";
            var set_hasil_pengecekan_lcr_check = "";
            function closed_wo(el, id_work_order, ekpb, is_ev, soc, serial_number_battery, kesediaan_customer_lcr_check, record_reason_lcr_check, hasil_pengecekan_lcr_check) {
              // alert(ekpb);
              set_id_work_order = id_work_order
              set_is_ev = is_ev
              set_soc = soc
              set_sn_battery = serial_number_battery
              set_kesediaan_customer_lcr_check = kesediaan_customer_lcr_check
              set_record_reason_lcr_check = record_reason_lcr_check
              set_hasil_pengecekan_lcr_check = hasil_pengecekan_lcr_check

              const formHtml = `
              <div class="row form-group">
                      <label for="inputEmail3" class="col-sm-3 control-label">State of Charge (SOC) <i style="color: red">*</i></label>
                      <div class="col-sm-6">
                          <input type="number" class="form-control" id="soc" name="soc" min="0" max="100">
                      </div>
              </div>
              <div class="row form-group">
                      <label for="inputEmail3" class="col-sm-3 control-label">Serial Number Battery Saat Ini <i style="color: red">*</i></label>
                      <div class="col-sm-9">
                          <input type="text" class="form-control" id="serial_number_battery" name="serial_number_battery">
                      </div>
              </div>`;
              const form_kesediaan_lcr = `
              <div class="row form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">Kesediaan Customer LCR Check*</label>
                        <div class="col-sm-4">
                          <select name="kesediaan_customer_lcr_id" id="kesediaan_customer" class="form-control">
                            <option value="">-choose-</option>
                            <?php
                            $getPilihanKesediaanCustomer = $this->m_sm->getPilihanKesediaanCustomer()->result(); ?>
                            <?php foreach ($getPilihanKesediaanCustomer as $act2) { ?>
                              <option value="<?= $act2->id ?>"><?= $act2->deskripsi ?></option>
                            <?php } ?>
                          </select>
                     </div>
              </div>`;
              const form_record_reason_lcr = `
              <div class="row form-group">
              <label for="inputEmail3" class="col-sm-3 control-label">Alasan Tidak Bersedia</label>
                        <div class="col-sm-4">
                          <select name="record_reason_lcr_id" class="form-control" id="record_reason">
                            <option value="">-choose-</option>
                            <?php
                            $getAlasanTidakBersedia = $this->m_sm->getAlasanTidakBersedia()->result(); ?>
                            <?php foreach ($getAlasanTidakBersedia as $act3) { ?>
                              <option value="<?= $act3->id ?>"><?= $act3->deskripsi ?></option>
                            <?php } ?>
                          </select>
                        </div>
              </div>`;
              const form_hasil_pengecekan_lcr = `
              <div class="row form-group">
                    <label for="inputEmail3" class="col-sm-3 control-label">Hasil Pengecekan LCR*</label>
                        <div class="col-sm-4">
                          <select name="hasil_pengecekan_lcr_id" class="form-control"  id="hasil_pengecekan">
                            <option value="">-choose-</option>
                            <?php
                            $getHasilPengecekanLCR = $this->m_sm->getHasilPengecekanLCR()->result(); ?>
                            <?php foreach ($getHasilPengecekanLCR as $act4) { ?>
                              <option value="<?= $act4->id ?>"><?= $act4->deskripsi ?></option>
                            <?php } ?>
                          </select>
                        </div>
              </div>`;

              if(ekpb==1){
                $("#set_work_order_ekpb").text(set_id_work_order);
                $("#modal_close_wo_ekpb").modal('show');
              }else{
                $("#set_work_order").text(set_id_work_order);
                $("#set_is_ev").text(set_is_ev);
                $("#set_soc").text(set_soc);
                $("#set_sn_battery").text(set_sn_battery);
                $("#set_kesediaan_customer_lcr_check").text(set_kesediaan_customer_lcr_check);
                $("#set_hasil_pengecekan_lcr_check").text(set_hasil_pengecekan_lcr_check);
                $("#set_record_reason_lcr_check").text(set_record_reason_lcr_check);
                if(set_is_ev == 1 && (set_soc == null || set_soc == '' || set_soc== 0) && (set_sn_battery == null || set_sn_battery == '' || set_sn_battery== 0)) {
                  $("#form_placeholder").html(formHtml);
                } else {
                  $("#form_placeholder").empty();  // Mengosongkan konten jika set_is_ev false
                }
                if(set_kesediaan_customer_lcr_check == 1 && (set_hasil_pengecekan_lcr_check == '' || set_hasil_pengecekan_lcr_check == null)){
                  $("#form_lcr").html(form_hasil_pengecekan_lcr);
                }else if(set_kesediaan_customer_lcr_check == 3 && (set_record_reason_lcr_check == '' || set_record_reason_lcr_check == null)){
                  $("#form_lcr").html(form_record_reason_lcr);
                }
                $("#modal_close_wo").modal('show');
              }
            }
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <div id="modal_close_wo" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Close Work Order #<span id="set_work_order"></span></h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="box-body">
                    <span id="set_is_ev" hidden></span>
                    <div id="form_placeholder"></div>
                    <div id="form_lcr"></div>
                    <div class="row form-group">
                      <label class="col-sm-3 control-label">Saran Mekanik <i style="color: red">*</i></label>
                      <div class="col-sm-9">
                        <input type='text' class="form-control" id='saran_mekanik'>
                      </div>
                    </div>
                    <div class="row form-group">
                      <label class="col-sm-3 control-label">Tgl. Service Selanjutnya <i style="color: red">*</i></label>
                      <div class="col-sm-6">
                        <input type='text' class="form-control datepicker" id='tgl_service_selanjutnya'>
                      </div>
                    </div>
                  </div>
                  <div class="box-footer">
                    <div class="col-sm-12" align="center">
                      <button type="button" id="submitBtn" class="btn btn-warning btn-flat" onclick="simpanClosed()"><i class="fa fa-save"></i> Simpan Data</button>
                    </div>
                  </div>
                </div>
              </div>
              <script>
                function simpanClosed() {
                  var saran_mekanik = $("#saran_mekanik").val();
                  var tgl_service_selanjutnya = $("#tgl_service_selanjutnya").val();
                  var soc = $("#soc").val();
                  var serial_number_battery = $("#serial_number_battery").val();
                  var record_reason = $("#record_reason").val();
                  var hasil_pengecekan = $("#hasil_pengecekan").val();
                  if (saran_mekanik == '' || tgl_service_selanjutnya == '') {
                    alert("Silahkan lengkapi form");
                    return false;
                  }

                  if(set_is_ev == 1 && (set_soc == null || set_soc == '' || set_soc== 0) && (set_sn_battery == null || set_sn_battery == '' || set_sn_battery== 0)){
                    if(soc == '' || soc == null){
                      alert('State Of Charge (SOC) Wajib Diisi!');
                      return false
                    }else if(soc >= 100){
                      alert('State Of Charge (SOC) Tidak boleh lebih dari 100');
                      return false
                    }else if(soc < 0){
                      alert('State Of Charge (SOC) Tidak boleh kurang dari 0');
                      return false
                    }

                    if(serial_number_battery == '' || serial_number_battery == null || serial_number_battery == 0 || serial_number_battery.length < 7 ){
                      alert('Serial Number Terakhir Wajib Diisi dan Minimal Panjang Karakter adalah 7 karakter!');
                      return false
                    }else{
                      let isValidSerialNumber = false; // Variabel global

                        function check_serial_number() {
                            $.ajax({
                                type: "POST",
                                url: '<?= base_url('dealer/' . $isi . '/check_serial_number' ) ?>',
                                data: {serial_number_battery: serial_number_battery},
                                dataType: "json",
                                success: function(response) {
                                    if (!response.available) {
                                        alert(response.message);
                                        isValidSerialNumber = false;
                                    } else {
                                        isValidSerialNumber = true;
                                    }
                                },
                                async: false 
                            });

                            return isValidSerialNumber;
                        }

                        if(check_serial_number() === false) {
                            return false;
                        }
                    }
                  }else if(set_is_ev == 1 && (set_soc != null || set_soc != '' || set_soc!= 0) && (set_sn_battery != null || set_sn_battery != '' || set_sn_battery!= 0)){
                    serial_number_battery = set_sn_battery;
                    soc = set_soc;
                  }else if(set_is_ev == 0 || set_is_ev == null || set_is_ev == ''){
                    serial_number_battery = '';
                    soc = '';
                  }

                  if(set_kesediaan_customer_lcr_check == 1 && (set_hasil_pengecekan_lcr_check == '' || set_hasil_pengecekan_lcr_check == null)){
                    alert("Hasil Pengecekan Wajib Diisi!");
                    // return false;
                  // }else if(set_kesediaan_customer_lcr_check == 3 && (record_reason == '' || record_reason == null)){
                  //   alert("Alasan Tidak Bersedia Wajib Diisi!");
                  //   return false;
                  }else if(set_kesediaan_customer_lcr_check == '' ||set_kesediaan_customer_lcr_check == null || set_kesediaan_customer_lcr_check == 2 || set_kesediaan_customer_lcr_check == 3){
                    record_reason = '';
                    hasil_pengecekan = '';
                  }
                  
                  window.location = '<?= base_url("dealer/execute_wo/closed_wo?id=") ?>' + set_id_work_order + '&saran_mekanik=' + saran_mekanik + '&tgl_service_selanjutnya=' + tgl_service_selanjutnya + '&ekpb=0' + '&soc=' + soc + '&serial_number_battery=' + serial_number_battery+ '&record_reason=' + record_reason + '&hasil_pengecekan=' + hasil_pengecekan;
                  return false;
                }
              </script>
            </div>
          </div>
        </div>
      </div>

      <div id="modal_close_wo_ekpb" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Close Work Order #<span id="set_work_order_ekpb"></span></h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="box-body">
                    <div class="row form-group">
                      <label class="col-sm-3 control-label">Saran Mekanik <i style="color: red">*</i></label>
                      <div class="col-sm-9">
                        <input type='text' class="form-control" value='' id='saran_mekanik_e'>
                      </div>
                    </div>
                    <div class="row form-group">
                      <label class="col-sm-3 control-label">Tgl. Service Selanjutnya <i style="color: red">*</i></label>
                      <div class="col-sm-6">
                        <input type='text' class="form-control datepicker" value='' id='tgl_service_selanjutnya_e'>
                      </div>
                    </div>

                    <div class="row form-group">
                      <label class="col-sm-3 control-label">Klaim e-KPB? </label>
                      <div class="col-sm-6">
                    <input type="checkbox" id="e_kpb" name="e_kpb">
                    <label for="e_kpb"> Ya</label><br>
                      </div>
                    </div>
                  </div>
                  <div class="box-footer">
                    <div class="col-sm-12" align="center">
                      <button type="button" id="submitBtn" class="btn btn-warning btn-flat" onclick="simpanClosed_e()"><i class="fa fa-save"></i> Simpan Data</button>
                    </div>
                  </div>
                </div>
              </div>
              <script>
                function simpanClosed_e() {
                  var saran_mekanik = $("#saran_mekanik_e").val();
                  var tgl_service_selanjutnya = $("#tgl_service_selanjutnya_e").val();
                  var claim_ekpb = 0; 
                  if ($('#e_kpb').is(":checked")){
                    claim_ekpb = 1;
                  }

                  if (saran_mekanik == '' || tgl_service_selanjutnya == '') {
                    alert("Silahkan lengkapi form 2");
                    return false;
                  }
                  window.location = '<?= base_url("dealer/execute_wo/closed_wo?id=") ?>' + set_id_work_order + '&saran_mekanik=' + saran_mekanik + '&tgl_service_selanjutnya=' + tgl_service_selanjutnya+ '&ekpb='+ claim_ekpb;
                  return false;
                }
              </script>
            </div>
          </div>
        </div>
      </div>

    <?php
    }
    ?>
    <?php if ($set == 'send_wo') { ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <link href='assets/select2/css/select2.min.css' rel='stylesheet' type='text/css'>
      <script src="assets/jquery/jquery.min.js"></script>
      <script src='assets/select2/js/select2.min.js'></script>

      <script>
        Vue.use(VueNumeric.default);
        $(document).ready(function() {
          <?php if (isset($row)) { ?>
            // $('#id_antrian').val('<?= $row->id_antrian ?>').trigger('change');
            getSaForm('detail');
          <?php } ?>
          <?php if (isset($row_wo)) { ?>
            getDataWO('detail');
          <?php } ?>
        })
        Vue.filter('toCurrency', function(value) {
          // // console.log("type value ke currency filter" ,  value, typeof value, typeof value !== "number");
          // if (typeof value !== "number") {
          //     return value;
          // }
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });
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
            <div class="col-md-12">
              <form class="form-horizontal" id="form_" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Work Order</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $wo->id_work_order ?>" class="form-control" name="id_work_order" readonly id="id_work_order">
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Customer</label>
                  <div class="col-sm-4">
                    <input type="text" value="<?= $wo->id_customer ?>" class="form-control" name="id_customer" id="id_customer" readonly>
                  </div>
                </div>
                <div class="col-md-12">
                  <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Parts</button><br><br>
                </div>
                <table class="table table-bordered">
                  <thead>
                    <th>ID Part</th>
                    <th>Nama Part</th>
                    <th>Harga</th>
                    <th>Quantity</th>
                    <th>Gudang</th>
                    <th>Rak</th>
                    <!-- <th>Status Picking</th> -->
                  </thead>
                  <tbody>
                    <tr v-for="(prt, index) of parts">
                      <!-- <input type="hidden" name="id_part[]" :value="prt.id_part">
                      <input type="hidden" name="id_gudang[]" :value="prt.id_gudang">
                      <input type="hidden" name="id_rak[]" :value="prt.id_rak">
                      <input type="hidden" name="kuantitas[]" :value="prt.qty">
                      <input type="hidden" name="harga_saat_dibeli[]" v-model="prt.harga"> -->
                      <td>{{prt.id_part}}</td>
                      <td>{{prt.nama_part}}</td>
                      <td align="right">{{prt.harga_saat_dibeli | toCurrency}}</td>
                      <td>{{prt.kuantitas}}</td>
                      <td>{{prt.id_gudang}}</td>
                      <td>{{prt.id_rak}}</td>
                      <!-- <td>{{prt.picking}}</td> -->
                    </tr>
                  </tbody>
                </table>
                <div class="box-footer" v-if="mode!='detail'">
                  <div class="col-sm-12" align="center">
                    <button type="button" id="submitBtn2" name="save2" value="save2" class="btn btn-success btn-flat"><i class="fa fa-send"></i> Send WO to Part Counter</button>
                  </div>
                </div><!-- /.box-footer -->
                <!-- <div class="box-footer" v-if="mode!='detail'">
                  <div class="col-sm-12" align="center">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-primary btn-flat"><i class="fa fa-send"></i> Send WO to Part Counter</button>
                  </div>
                </div> -->
                <!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div>

      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            kosong: '',
            mode: '<?= $mode ?>',
            parts: <?= isset($parts) ? json_encode($parts) : '[]' ?>,
          },
          methods: {

          },
          watch: {}
        });

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
          if ($('#form_').valid()) // check if form is valid
          {

            let parts_new = [];
            for (pr of form_.parts) {
              harga_setelah_diskon = Number(pr.harga_saat_dibeli);
              if (pr.tipe_diskon == 'Percentage') {
                potongan_harga = (pr.diskon_value / 100) * harga_setelah_diskon;
                harga_setelah_diskon -= potongan_harga;
              }

              if (pr.tipe_diskon == 'Value') {
                harga_setelah_diskon -= Number(pr.diskon_value);
              }

              field = {
                id_part: pr.id_part,
                id_part_int: pr.id_part_int,
                harga_saat_dibeli: pr.harga_saat_dibeli,
                kuantitas: pr.kuantitas,
                id_gudang: pr.id_gudang,
                id_rak: pr.id_rak,
                id_promo: pr.id_promo,
                diskon_value: pr.diskon_value == 0 ? '' : pr.diskon_value,
                tipe_diskon: pr.tipe_diskon,
                harga_setelah_diskon: harga_setelah_diskon
              }
              parts_new.push(field)
            }
            // console.log(parts_new);
            // return false
            var values = {
              parts: parts_new,
              nama_pembeli: "<?= $wo->nama_customer ?>",
              id_customer: '<?= $wo->id_customer ?>',
              id_customer_int: '<?= $wo->id_customer_int ?>',
              id_work_order: '<?= $wo->id_work_order ?>',
              no_hp_pembeli: '<?= $wo->no_hp ?>',
              alamat_pembeli: '<?= $wo->alamat ?>',
              source: 'wo',
              pembelian_dari_dealer_lain: 0,
            };
            var form = $('#form_').serializeArray();
            for (field of form) {
              values[field.name] = field.value;
            }

            let isValidWO = false;

            function check_wo() {
              values2 = {
                id_work_order: $('#id_work_order').val(),
                parts: form_.parts
              }
              $.ajax({
                  type: "POST",
                  url: '<?= base_url('dealer/' . $isi . '/check_wo' ) ?>',
                  data: values2,
                  dataType: "json",
                  success: function(response) {
                      if (!response.available) {
                          alert(response.message);
                          isValidWO = false;
                      } else {
                        isValidWO = true;
                      }
                  },
                  async: false
              });

              return isValidWO;
            }

            if(check_wo() === false) {
                return false;
            }

            if (confirm("Apakah anda yakin ?") == true) {

              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#submitBtn').attr('disabled', true);
                },
                url: '<?= base_url('dealer/h3_dealer_sales_order/save') ?>',
                type: "POST",
                data: values,
                cache: false,
                async: false,
                dataType: 'JSON',
                success: function(response) {
                  let nomor_so = response.nomor_so;
                  saveSendWOParts(nomor_so);
                },
                error: function() {
                  alert("Something Went Wrong !");
                  $('#submitBtn').html('<i class="fa fa-send"></i> Send WO to Part Counter');
                  $('#submitBtn').attr('disabled', false);
                },
              });
            } else {
              return false;
            }
          } else {
            alert('Silahkan isi field required !')
          }
        })

        function saveSendWOParts(nomor_so) {
          values = {
            nomor_so: nomor_so,
            id_work_order: $('#id_work_order').val(),
            parts: form_.parts
          }
          $.ajax({
            beforeSend: function() {
              $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
              $('#submitBtn').attr('disabled', true);
            },
            url: '<?= base_url('dealer/execute_wo/save_send_wo') ?>',
            type: "POST",
            data: values,
            cache: false,
            async: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 'sukses') {
                window.location = response.link;
              } else {
                alert(response.pesan);
                $('#submitBtn').attr('disabled', false);
              }
              $('#submitBtn').html('<i class="fa fa-send"></i> Send WO to Part Counter');
            },
            error: function() {
              alert("Something Went Wrong !");
              $('#submitBtn').html('<i class="fa fa-send"></i> Send WO to Part Counter');
              $('#submitBtn').attr('disabled', false);
            },
          });
        }

        $('#submitBtn2').click(function() {
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
          if ($('#form_').valid()) // check if form is valid
          {

            let parts_new = [];
            for (pr of form_.parts) {
              harga_setelah_diskon = Number(pr.harga_saat_dibeli);
              if (pr.tipe_diskon == 'Percentage') {
                potongan_harga = (pr.diskon_value / 100) * harga_setelah_diskon;
                harga_setelah_diskon -= potongan_harga;
              }

              if (pr.tipe_diskon == 'Value') {
                harga_setelah_diskon -= Number(pr.diskon_value);
              }

              field = {
                id_part: pr.id_part,
                id_part_int: pr.id_part_int,
                harga_saat_dibeli: pr.harga_saat_dibeli,
                kuantitas: pr.kuantitas,
                id_gudang: pr.id_gudang,
                id_rak: pr.id_rak,
                id_promo: pr.id_promo,
                diskon_value: pr.diskon_value == 0 ? '' : pr.diskon_value,
                tipe_diskon: pr.tipe_diskon,
                harga_setelah_diskon: harga_setelah_diskon,
                id_jasa: pr.id_jasa,
              }
              parts_new.push(field)
            }
            // console.log(parts_new);
            // return false
            var values = {
              parts: parts_new,
              nama_pembeli: "<?= $wo->nama_customer ?>",
              id_customer: '<?= $wo->id_customer ?>',
              id_customer_int: '<?= $wo->id_customer_int ?>',
              id_work_order: '<?= $wo->id_work_order ?>',
              no_hp_pembeli: '<?= $wo->no_hp ?>',
              alamat_pembeli: '<?= $wo->alamat ?>',
              source: 'wo',
              pembelian_dari_dealer_lain: 0,
            };
            var form = $('#form_').serializeArray();
            for (field of form) {
              values[field.name] = field.value;
            }

            let isValidWO = false;

            function check_wo() {
              values2 = {
                id_work_order: $('#id_work_order').val(),
                parts: form_.parts
              }
              $.ajax({
                  type: "POST",
                  url: '<?= base_url('dealer/' . $isi . '/check_wo' ) ?>',
                  data: values2,
                  dataType: "json",
                  success: function(response) {
                      if (!response.available) {
                          alert(response.message);
                          isValidWO = false;
                      } else {
                        isValidWO = true;
                      }
                  },
                  async: false
              });

              return isValidWO;
            }

            if(check_wo() === false) {
                return false;
            }

            if (confirm("Apakah anda yakin ?") == true) {
              values3= {
                // values, 
                id_work_order: $('#id_work_order').val(),
                id_customer: $('#id_customer').val(),
                parts: parts_new,
                source: 'wo'
              }
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn2').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#submitBtn2').attr('disabled', true);
                },
                url: '<?= base_url('dealer/execute_wo/save_send_wo_2') ?>',
                type: "POST",
                data: values3,
                cache: false,
                async: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    alert(response.pesan);
                    $('#submitBtn2').attr('disabled', false);
                  }
                  $('#submitBtn2').html('<i class="fa fa-send"></i> Send WO to Part Counter');
                },
                error: function() {
                  alert("Something Went Wrong !");
                  $('#submitBtn2').html('<i class="fa fa-send"></i> Send WO to Part Counter');
                  $('#submitBtn2').attr('disabled', false);
                },
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