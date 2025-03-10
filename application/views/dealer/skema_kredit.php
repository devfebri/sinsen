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
      <li class="">Skema Kredit</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <style>
    .t2 {
      width: 30%;
      margin-right: 3%;
      float: right;
    }
  </style>
  <section class="content">
    <?php
    if ($set == "form") {
      $form     = '';
      $disabled = '';
      $readonly = '';
      $ang = 'angsuran';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'edit') {
        // $readonly ='readonly';
        $form = 'save_edit';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $ang = '';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

      <script>
        var show = 0;
        Vue.use(VueNumeric.default);
        $(document).ready(function() {
          <?php if (isset($row)) { ?>
            $('#id_prospek').val('<?= $row->id_prospek ?>').trigger('change');
          <?php } ?>
        })
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/skema_kredit">
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
              <form class="form-horizontal" id="form_" action="dealer/skema_kredit/<?= $form ?>" method="post" enctype="multipart/form-data">
                <?php if (isset($row)) : ?>
                  <input type="hidden" id="id_skema" name="id_skema" value="<?= $row->id_skema ?>">
                <?php endif ?>
                <div class="box-body">
                  <div class="form-group">
                    <!-- <label for="inputEmail3" class="col-sm-2 control-label">Kode Event</label> -->
                    <div class="col-sm-4">
                      <input type="hidden" required class="form-control" placeholder="Otomatis" name="id_skema" id="id_skema" readonly value="<?= isset($row) ? $row->id_skema : '' ?>">
                    </div>

                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Prospek</label>
                    <div class="col-sm-4">
                      <select name="id_prospek" id="id_prospek" onchange="getProspek()" class="form-control select2" <?= $disabled ?> required>
                        <option value="">--choose-</option>
                        <?php foreach ($prospek->result() as $rs) :
                          $selected = isset($row) ? $rs->id_prospek == $row->id_prospek ? 'selected' : '' : '';
                        ?>
                          <option value="<?= $rs->id_prospek ?>" <?= $selected ?> data-nama_konsumen="<?= $rs->nama_konsumen ?>" data-no_hp="<?= $rs->no_hp ?>" data-alamat="<?= $rs->alamat ?>" data-id_tipe_kendaraan="<?= $rs->id_tipe_kendaraan ?>" data-id_warna="<?= $rs->id_warna ?>" data-warna="<?= $rs->warna ?>" data-tipe_ahm="<?= $rs->tipe_ahm ?>" data-alamat="<?= $rs->alamat ?>"><?= $rs->id_customer ?> - <?= $rs->nama_konsumen ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">No. HP</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="no_hp" id="no_hp" value="<?= isset($row) ? $row->no_hp : '' ?>" autocomplete="off" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="nama_konsumen" id="nama_konsumen" value="<?= isset($row) ? $row->nama_konsumen : '' ?>" autocomplete="off" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                    <div class="col-sm-6">
                      <input type="text" required class="form-control datepicker" name="alamat" id="alamat" value="<?= isset($row) ? $row->alamat : '' ?>" autocomplete="off" disabled>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <button style="font-size: 11pt;font-weight: 540;width: 100%" class="btn btn-success btn-flat btn-sm" disabled>Detail Kendaraan</button><br><br>
                    </div>
                    <div class="col-md-12">
                      <table class="table table-bordered">
                        <thead>
                          <th>Tipe</th>
                          <th>Warna</th>
                        </thead>
                        <tbody>
                          <tr v-for="(unt, index) of unit">
                            <td>{{unt.id_tipe_kendaraan}} | {{unt.tipe_ahm}}</td>
                            <td>{{unt.id_warna}} | {{unt.warna}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Finance Company</label>
                    <div class="col-sm-4">
                      <select name="id_finco" id="id_finco" class="form-control select2" <?= $disabled ?> required>
                        <option value="">--choose-</option>
                        <?php foreach ($finco->result() as $rs) :
                          $selected = isset($row) ? $rs->id_finance_company == $row->id_finco ? 'selected' : '' : '';
                        ?>
                          <option value="<?= $rs->id_finance_company ?>" <?= $selected ?>><?= $rs->finance_company ?></option>
                        <?php endforeach ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Harga Unit On The Road</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="harga_on_road" id="harga_on_road" value="<?= isset($row) ? $row->harga_on_road : '' ?>" autocomplete="off" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Harga Unit Off The Road</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="harga_off_road" id="harga_off_road" value="<?= isset($row) ? $row->harga_off_road : '' ?>" autocomplete="off" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" name="biaya_bbn" id="biaya_bbn" value="<?= isset($row) ? $row->biaya_bbn : '' ?>" autocomplete="off" readonly>
                    </div>
                    <table class="t2 table table-bordered" v-if="tenor_angsuran.length>0">
                      <thead>
                        <th>Tenor (Bulan)</th>
                        <th>Angsuran (Rp)</th>
                      </thead>
                      <tbody>
                        <tr v-for="(ta, index) of tenor_angsuran">
                          <td>{{ta.tenor}}</td>
                          <td>{{ta.angsuran}}</td>
                        </tr>
                      </tbody>
                      <tbody>

                      </tbody>
                    </table>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Down Payment (DP)</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" id="dp" name="dp" onchange="getTenorAngsuran()" style="width: 100%" <?= $disabled ?>></select>
                    </div>
                    <div class="col-sm-4" v-if="dp=='lainnya'">
                      <vue-numeric style="float: left;width: 100%;" class="form-control" name='dp_lainnya' id='dp_lainnya' v-model="dp_lainnya" v-bind:minus="false" :empty-value="0" separator="." onkeypress="return number_only(event)" autocomplete="off" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tenor (Bulan)</label>
                    <div class="col-sm-4">
                      <select class="form-control select2" id="tenor" name="tenor" onchange="getAngsuran()" style="width: 100%" <?= $disabled ?>></select>
                    </div>
                    <div class="col-sm-4" v-if="dp=='lainnya'||tenor=='lainnya'">
                      <input type="text" required class="form-control" name="tenor_lainnya" id="tenor_lainnya" value="<?= isset($row) ? $row->tenor : '' ?>" autocomplete="off" placeholder="Tenor (Bulan)" :readonly="mode=='detail'" onkeypress="return number_only(event)">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Angsuran</label>
                    <div class="col-sm-4">
                      <vue-numeric style="float: left;width: 100%;" class="form-control" name='angsuran' id='angsuran' v-model="angsuran" v-bind:minus="false" :empty-value="0" separator="." onkeypress="return number_only(event)" :readonly="dp>0||tenor>0||mode=='detail'" />
                    </div>
                  </div>
                  <div class="box-footer" v-if="mode!='detail'">
                    <div class="col-sm-12" v-if="mode=='insert'||mode=='edit'" align="center">
                      <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
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
          data: {
            mode: '<?= $mode ?>',
            unit: <?= isset($unit) ? json_encode($unit) : '[]' ?>,
            id_warna: '',
            id_tipe_kendaraan: '',
            tenor: '',
            dp: '',
            dp_lainnya: <?= isset($row) ? $row->dp : 0 ?>,
            angsuran: <?= isset($row) ? $row->angsuran : 0 ?>,
            tenor_angsuran: <?= isset($tenor_angsuran) ? json_encode($tenor_angsuran) : '[]' ?>,
          },
          methods: {
            clearDealers: function() {
              this.dealer = {
                id_dealer: '',
                nama_dealer: ''
              }
            },
            addDealers: function() {
              if (this.dealers.length > 0) {
                for (dl of this.dealers) {
                  if (dl.id_dealer === this.dealer.id_dealer) {
                    alert("Dealer Sudah Dipilih !");
                    this.clearDealers();
                    return;
                  }
                }
              }
              if (this.dealer.id_dealer == '') {
                alert('Pilih Dealer !');
                return false;
              }
              this.dealers.push(this.dealer);
              this.clearDealers();
            },

            delDealers: function(index) {
              this.dealers.splice(index, 1);
            },
            getDealer: function() {
              var el = $('#dealer').find('option:selected');
              var id_dealer = el.attr("id_dealer");
              form_.dealer.id_dealer = id_dealer;
            },
          },
        });

        function getAngsuran() {
          var tenor = $('#tenor').val();
          form_.tenor = '';
          if (tenor == 'lainnya' || form_.dp == 'lainnya') {
            form_.tenor = 'lainnya';
          } else {
            var angsuran = $("#tenor").select2().find(":selected").data("angsuran");
            $('#angsuran').val(angsuran);
          }
        }

        function getProspek() {
          $('#tenor').html('');
          $('#dp').html('');
          $('#angsuran').val('');
          var nama_konsumen = $("#id_prospek").select2().find(":selected").data("nama_konsumen");
          $('#nama_konsumen').val(nama_konsumen);
          var no_hp = $("#id_prospek").select2().find(":selected").data("no_hp");
          $('#no_hp').val(no_hp);
          // var harga_on_road             = $("#id_prospek").select2().find(":selected").data("harga_on_road");$('#harga_on_road').val(harga_on_road);
          // var harga_off_road             = $("#id_prospek").select2().find(":selected").data("harga_off_road");$('#harga_off_road').val(harga_off_road);
          // var biaya_bbn             = $("#id_prospek").select2().find(":selected").data("biaya_bbn");$('#biaya_bbn').val(biaya_bbn);
          var alamat = $("#id_prospek").select2().find(":selected").data("alamat");
          $('#alamat').val(alamat);
          var id_tipe_kendaraan = $("#id_prospek").select2().find(":selected").data("id_tipe_kendaraan");
          var id_warna = $("#id_prospek").select2().find(":selected").data("id_warna");
          var warna = $("#id_prospek").select2().find(":selected").data("warna");
          var tipe_ahm = $("#id_prospek").select2().find(":selected").data("tipe_ahm");
          form_.id_tipe_kendaraan = id_tipe_kendaraan;
          form_.id_warna = id_warna;
          getHarga(id_tipe_kendaraan, id_warna);
          // console.log(form_.unit.length)
          form_.unit = [{
            id_tipe_kendaraan: id_tipe_kendaraan,
            id_warna: id_warna,
            warna: warna,
            tipe_ahm: tipe_ahm
          }]
          // console.log(form_.unit.length)
          values = {
            id_tipe_kendaraan: id_tipe_kendaraan
          }
          form_.tenor_angsuran = [];

          $.ajax({
            beforeSend: function() {},
            url: '<?= base_url('dealer/skema_kredit/getSimulasiKredit') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              // console.log(response);
              $('#dp').html('');
              // if (response.length>0) {
              $('#dp').append($('<option>').text('--choose--').attr('value', ''));
              // }
              <?php if (isset($row)) { ?>
                var cek = <?= $row->dp ?>;
                var sama = 0;
              <?php } ?>
              $.each(response, function(i, value) {
                $('#dp').append($('<option>').text(response[i].uang_muka + ', Cukup Bayar = ' + response[i].cukup_bayar).attr({
                  'value': response[i].cukup_bayar,
                  'data-id_simulasi': response[i].id_simulasi
                }));
                <?php if (isset($row)) { ?>
                  if (cek == response[i].cukup_bayar) {
                    sama++;
                  }
                <?php } ?>
              });
              $('#dp').append($('<option>').text('Lainnya').attr('value', 'lainnya'));
              <?php if (isset($row)) { ?>
                if (sama > 0) {
                  $('#dp').val('<?= $row->dp ?>').trigger('change');
                } else {
                  $('#dp').val('lainnya').trigger('change');
                }
              <?php } ?>
            },
            error: function() {
              alert("failure");
            },
            statusCode: {
              500: function() {
                alert('fail');
              }
            }
          });
        }

        function getHarga(id_tipe_kendaraan, id_warna) {
          var values = {
            id_tipe_kendaraan: id_tipe_kendaraan,
            id_warna: id_warna
          }
          $.ajax({
            beforeSend: function() {},
            url: '<?= base_url('dealer/skema_kredit/getHarga') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              $('#harga_on_road').val(response.harga_on);
              $('#harga_off_road').val(response.harga_jual);
              $('#biaya_bbn').val(response.biaya_bbn);
            },
            error: function() {
              alert("failure");
            },
            statusCode: {
              500: function() {
                alert('fail');
              }
            }
          });
        }

        function getTenorAngsuran() {

          var id_simulasi = $("#dp").select2().find(":selected").data("id_simulasi");
          var cukup_bayar = $("#dp").val();
          form_.dp = '';
          if (cukup_bayar == 'lainnya') {
            form_.dp = 'lainnya';
          }
          if (show > 0) {
            $('#angsuran').val('');
          } else {
            <?php if (isset($row)) { ?>
              $('#angsuran').val(<?= $row->angsuran ?>);
            <?php } ?>
          }
          values = {
            cukup_bayar: cukup_bayar,
            id_simulasi: id_simulasi,
            id_warna: form_.id_warna,
            id_tipe_kendaraan: form_.id_tipe_kendaraan,
          }
          $.ajax({
            beforeSend: function() {},
            url: '<?= base_url('dealer/skema_kredit/getTenorAngsuran') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              show = 1;
              form_.tenor_angsuran = [];
              // console.log(form_.tenor_angsuran);
              $('#tenor').html('');
              <?php if (isset($row)) { ?>
                var cek = <?= $row->tenor ?>;
                var sama = 0;
              <?php } ?>
              if (response.length > 0) {
                $('#tenor').append($('<option>').text('--choose--').attr('value', ''));
              }
              $.each(response, function(i, value) {
                $('#tenor').append($('<option>').text(response[i].tenor).attr({
                  'value': response[i].tenor,
                  'data-angsuran': response[i].angsuran
                }));
                <?php if (isset($row)) { ?>
                  if (cek == response[i].tenor) {
                    sama++;
                  }
                <?php } ?>
                var ta = {
                  tenor: response[i].tenor,
                  angsuran: response[i].angsuran
                }
                form_.tenor_angsuran.push(ta);
              });
              $('#tenor').append($('<option>').text('Lainnya').attr('value', 'lainnya'));
              <?php if (isset($row)) { ?>
                if (sama > 0) {
                  $('#tenor').val('<?= $row->tenor ?>').trigger('change');
                } else {
                  $('#tenor').val('lainnya').trigger('change');
                }
              <?php } ?>
            },
            error: function() {
              alert("failure");
            },
            statusCode: {
              500: function() {
                alert('fail');
              }
            }
          });
        }
      </script>
    <?php
    } elseif ($set == "index") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/skema_kredit/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
            </a>
            <a href="dealer/skema_kredit/history">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-check"></i> Cek History</button>
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
                <th>Prospek</th>
                <th>Nama</th>
                <th>No. HP</th>
                <th>Alamat</th>
                <th>Tenor</th>
                <th>Angsuran</th>
                <th>DP</th>
                <th>FinCoy</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($event->result() as $rs) :
                $status = '';
                $button = '';
                $btn_edit = '<a data-toggle=\'tooltip\' title="Edit Data" class=\'btn btn-warning btn-xs btn-flat\' href=\'dealer/skema_kredit/edit?id=' . $rs->id_skema . '\'><i class=\'fa fa-edit\'></i></a>';
                $btn_view = '<a data-toggle=\'tooltip\' title="View Detail" class=\'btn btn-primary btn-xs btn-flat\' href=\'dealer/skema_kredit/detail?id=' . $rs->id_skema . '\'><i class=\'fa fa-eye\'></i></a>';
                $button = $btn_view . ' ' . $btn_edit;
                // $get_dp = $this->db->query("SELECT * FROM ms_simulasi_kredit_detail WHERE id_detail=$rs->dp");
                // $uang_muka = $get_dp->num_rows()>0?$get_dp->row()->uang_muka:0;
                // if ($rs->status=='waiting_approval') {
                //   $status = '<label class="label label-warning">Waiting Approval</label>';
                //   // $button = $btn_edit.' '.$btn_approve.' '.$btn_reject;
                // }
                // if ($rs->status=='approved') {
                //   $status = '<label class="label label-success">Approved</label>';
                // }
                //  if ($rs->status=='rejected') {
                //   $status = '<label class="label label-danger">Rejected</label>';
                // }
              ?>
                <tr>
                  <td><a href="<?= base_url('dealer/skema_kredit/detail?id=' . $rs->id_skema) ?>"><?= $rs->id_prospek ?></a></td>
                  <td><?= $rs->nama_konsumen ?></td>
                  <td><?= $rs->no_hp ?></td>
                  <td><?= $rs->alamat ?></td>
                  <td><?= $rs->tenor ?></td>
                  <td align="right"><?= mata_uang_rp($rs->angsuran) ?></td>
                  <td align="right"><?= mata_uang_rp($rs->dp) ?></td>
                  <td><?= $rs->finco ?></td>
                  <td align="center">
                    <?= $button ?>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>

          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <script>
        function closePrompt(kode_event, id_event) {

          var alasan_reject = prompt("Alasan melakukan reject untuk Kode Event : " + kode_event);

          if (alasan_reject != null || alasan_reject == "") {

            window.location = '<?= base_url("dealer/skema_kredit/reject_save?id=") ?>' + id_event + '&alasan_reject=' + alasan_reject;

            return false;

          }

          return false

        }
      </script>
    <?php
    } elseif ($set == "history") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/skema_kredit">

              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Kembali</button>

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
                <th>Prospek</th>
                <th>Nama</th>
                <th>No. HP</th>
                <th>Alamat</th>
                <th>Tenor (Bulan)</th>
                <th>Angsuran</th>
                <th>DP</th>
                <th>FinCoy</th>
                <!-- <th width="10%">Action</th> -->
              </tr>
            </thead>
            <tbody>
              <?php foreach ($event->result() as $rs) :
                $status = '';
                $button = '';
                $btn_edit = '<a data-toggle=\'tooltip\' title="Edit Data" class=\'btn btn-warning btn-xs btn-flat\' href=\'dealer/skema_kredit/edit?id=' . $rs->id_skema . '\'><i class=\'fa fa-edit\'></i></a>';
                $btn_view = '<a data-toggle=\'tooltip\' title="View Detail" class=\'btn btn-primary btn-xs btn-flat\' href=\'dealer/skema_kredit/detail?id=' . $rs->id_skema . '\'><i class=\'fa fa-eye\'></i></a>';
                $button = $btn_view . ' ' . $btn_edit;
                // $get_dp = $this->db->query("SELECT * FROM ms_simulasi_kredit_detail WHERE id_detail=$rs->dp");
                // $uang_muka = $get_dp->num_rows()>0?$get_dp->row()->uang_muka:0;
                // if ($rs->status=='waiting_approval') {
                //   $status = '<label class="label label-warning">Waiting Approval</label>';
                //   // $button = $btn_edit.' '.$btn_approve.' '.$btn_reject;
                // }
                // if ($rs->status=='approved') {
                //   $status = '<label class="label label-success">Approved</label>';
                // }
                //  if ($rs->status=='rejected') {
                //   $status = '<label class="label label-danger">Rejected</label>';
                // }
              ?>
                <tr>
                  <td><a href="<?= base_url('dealer/skema_kredit/detail?id=' . $rs->id_skema) ?>"><?= $rs->id_prospek ?></a></td>
                  <td><?= $rs->nama_konsumen ?></td>
                  <td><?= $rs->no_hp ?></td>
                  <td><?= $rs->alamat ?></td>
                  <td><?= $rs->tenor ?></td>
                  <td align="right"><?= mata_uang_rp($rs->angsuran) ?></td>
                  <td align="right"><?= mata_uang_rp($rs->dp) ?></td>
                  <td><?= $rs->finco ?></td>

                </tr>
              <?php endforeach ?>
            </tbody>

          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div>