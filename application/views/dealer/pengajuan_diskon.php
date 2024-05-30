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

  <section class="content">
    <?php
    if ($set == "form") {
      $form     = '';
      $disabled = '';
      $readonly = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'edit') {
        // $readonly ='readonly';
        $form = 'save_edit';
      }
      if ($mode == 'detail') {
        $disabled = 'disabled';
      }
      if ($mode == 'approve') {
        $disabled = 'disabled';
        $form = 'save_approve';
      }
      if ($mode == 'reject') {
        $disabled = 'disabled';
        $form = 'save_reject';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>

      <script>
        Vue.use(VueNumeric.default);
        $(document).ready(function() {
          <?php if (isset($row)) { ?>
            getProspek()
          <?php } ?>
        })
      </script>
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/pengajuan_diskon">
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
              <form class="form-horizontal" id="form_" action="dealer/pengajuan_diskon/<?= $form ?>" method="post" enctype="multipart/form-data">
                <?php if (isset($row)) : ?>
                  <input type="hidden" id="id_pengajuan" name="id_pengajuan" value="<?= $row->id_pengajuan ?>">
                <?php endif ?>
                <div class="box-body">
                  <div class="form-group">
                    <!-- <label for="inputEmail3" class="col-sm-2 control-label">Kode Event</label> -->
                    <div class="col-sm-4">
                      <input type="hidden" required class="form-control" placeholder="Otomatis" name="id_pengajuan" id="id_pengajuan" readonly value="<?= isset($row) ? $row->id_pengajuan : '' ?>">
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
                          <option value="<?= $rs->id_prospek ?>" <?= $selected ?> data-id_prospek="<?= $rs->id_prospek ?>" data-nama_konsumen="<?= $rs->nama_konsumen ?>" data-no_hp="<?= $rs->no_hp ?>" data-alamat="<?= $rs->alamat ?>" data-harga_off_road="<?= $rs->harga_off_road ?>" data-harga_on_road="<?= $rs->harga_on_road ?>" data-biaya_bbn="<?= $rs->biaya_bbn ?>" data-id_tipe_kendaraan="<?= $rs->id_tipe_kendaraan ?>" data-id_warna="<?= $rs->id_warna ?>" data-warna="<?= $rs->warna ?>" data-tipe_ahm="<?= $rs->tipe_ahm ?>"><?= $rs->nama_konsumen . ' | ' . $rs->id_prospek ?></option>
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
                  <input type="hidden" name="tipe_pembayaran" v-model="tipe_pembayaran">
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
                    <label for="inputEmail3" class="col-sm-2 control-label">Harga Unit On The Road</label>
                    <div class="col-sm-4">
                      <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="harga_on_road" name="harga_on_road" readonly v-bind:minus="false" :empty-value="0" separator="." />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Harga Unit Off The Road</label>
                    <div class="col-sm-4">
                      <vue-numeric style="float: right;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="harga_off_road" name="harga_off_road" readonly v-bind:minus="false" :empty-value="0" separator="." />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Biaya BBN</label>
                    <div class="col-sm-4">
                      <vue-numeric style="float: right;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="biaya_bbn" name="biaya_bbn" readonly v-bind:minus="false" :empty-value="0" separator="." />
                    </div>
                  </div>
                  <div class="form-group" v-if="tipe_pembayaran=='kredit'">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tenor</label>
                    <div class="col-sm-4">
                      <!-- <input type="text" required class="form-control" name="tenor" id="tenor" value="<?= isset($row) ? $row->tenor : '' ?>" v-model="tenor" autocomplete="off" readonly> -->
                      <vue-numeric style="float: right;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="tenor" name="tenor" readonly v-bind:minus="false" :empty-value="0" separator="." />
                    </div>
                  </div>
                  <div class="form-group" v-if="tipe_pembayaran=='kredit'">
                    <label for="inputEmail3" class="col-sm-2 control-label">Angsuran</label>
                    <div class="col-sm-4">
                      <!-- <input type="text" required class="form-control" name="angsuran" id="angsuran" value="<?= isset($row) ? $row->angsuran : '' ?>" v-model="angsuran" autocomplete="off" readonly> -->
                      <vue-numeric style="float: right;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="angsuran" name="angsuran" readonly v-bind:minus="false" :empty-value="0" separator="." />
                    </div>
                  </div>
                  <div class="form-group" v-if="tipe_pembayaran=='kredit'">
                    <label for="inputEmail3" class="col-sm-2 control-label">Down Payment (DP)</label>
                    <div class="col-sm-4">
                      <vue-numeric style="float: right;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="dp" name="dp" readonly v-bind:minus="false" :empty-value="0" separator="." />
                      <!-- <input type="text" class="form-control" name="dp" id="dp" value="<?= isset($row) ? $row->dp : '' ?>" v-model="dp" autocomplete="off" readonly> -->
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nominal Diskon</label>
                    <div class="col-sm-4">
                      <!--  <input type="text" class="form-control" name="nominal_diskon" id="nominal_diskon" value="<?= isset($row) ? $row->nominal_diskon : '' ?>" <?= $disabled ?> v-model="nominal_diskon" autocomplete="off"> -->
                      <vue-numeric style="float: right;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="nominal_diskon" name="nominal_diskon" v-bind:minus="false" :empty-value="0" separator="." />
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="keterangan" id="keterangan" value="<?= isset($row) ? $row->keterangan : '' ?>" <?= $disabled ?> autocomplete="off">
                    </div>
                  </div>
                  <div class="form-group" v-if="mode=='reject_gk jadi'">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alasan Reject</label>
                    <div class="col-sm-6">
                      <input type="text" class="form-control" name="alasan_reject" id="alasan_reject" value="<?= isset($row) ? $row->alasan_reject : '' ?>" autocomplete="off">
                    </div>
                  </div>
                  <div class="box-footer" v-if="mode!='detail'">
                    <div class="col-sm-12" v-if="mode=='insert'||mode=='edit'" align="center">
                      <button type="submit" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    </div>
                    <div class="col-sm-12" v-if="mode=='approve'" align="center">
                      <button type="submit" name="save" value="save" class="btn btn-primary btn-flat"><i class="fa fa-check"></i> Approve</button>
                    </div>
                    <div class="col-sm-12" v-if="mode=='reject'" align="center">
                      <button type="submit" name="save" value="save" class="btn btn-danger btn-flat"><i class="fa fa-check"></i> Reject</button>
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
            tenor: '<?= isset($row) ? $row->tenor : "" ?>',
            angsuran: '<?= isset($row) ? $row->angsuran : "" ?>',
            nominal_diskon: '<?= isset($row) ? $row->nominal_diskon : "" ?>',
            nominal_diskon: '<?= isset($row) ? $row->nominal_diskon : "" ?>',
            harga_off_road: '',
            harga_on_road: '',
            biaya_bbn: '',
            dp: '<?= isset($row) ? $row->dp : "" ?>',
            nominal_diskon: '<?= isset($row) ? $row->nominal_diskon : "" ?>',
            tipe_pembayaran: '<?= isset($row) ? $row->tipe_pembayaran : "" ?>',
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
              form_.biaya_bbn = response.biaya_bbn;
              form_.harga_on_road = response.harga_on;
              form_.harga_off_road = response.harga_jual;
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

        function getProspek() {
          var nama_konsumen = $("#id_prospek").select2().find(":selected").data("nama_konsumen");
          $('#nama_konsumen').val(nama_konsumen);
          var no_hp = $("#id_prospek").select2().find(":selected").data("no_hp");
          $('#no_hp').val(no_hp);
          var alamat = $("#id_prospek").select2().find(":selected").data("alamat");
          $('#alamat').val(alamat);
          var id_tipe_kendaraan = $("#id_prospek").select2().find(":selected").data("id_tipe_kendaraan");
          var id_warna = $("#id_prospek").select2().find(":selected").data("id_warna");
          var warna = $("#id_prospek").select2().find(":selected").data("warna");
          var tipe_ahm = $("#id_prospek").select2().find(":selected").data("tipe_ahm");
          var id_prospek = $("#id_prospek").select2().find(":selected").data("id_prospek");
          getHarga(id_tipe_kendaraan, id_warna)
          form_.unit = [{
            id_tipe_kendaraan: id_tipe_kendaraan,
            id_warna: id_warna,
            warna: warna,
            tipe_ahm: tipe_ahm
          }];
          var values = {
            id_prospek: id_prospek
          }

          $.ajax({
            beforeSend: function() {},
            url: '<?= base_url('dealer/pengajuan_diskon/getProspek') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              // console.log(response);
              form_.tenor = response.tenor;
              form_.angsuran = response.angsuran;
              form_.dp = response.dp;
              form_.tipe_pembayaran = response.tipe_pembayaran;

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
          // console.log(form_.unit.length)
        }
      </script>
    <?php
    } elseif ($set == "index") {
    ?>

      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/pengajuan_diskon/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
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
                <th>Tipe Pembayaran</th>
                <th>Jatah Approval</th>
                <th>Status</th>
                <th width="10%">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($event->result() as $rs) :
                $status = '';
                $button = '';
                // $btn_edit ='<a data-toggle=\'tooltip\' title="Edit Data" class=\'btn btn-warning btn-xs btn-flat\' href=\'dealer/pengajuan_diskon/edit?id='.$rs->id_pengajuan.'\'><i class=\'fa fa-edit\'></i></a>';
                $btn_view = '<a data-toggle=\'tooltip\' title="View Detail" class=\'btn btn-primary btn-xs btn-flat\' href=\'dealer/pengajuan_diskon/detail?id=' . $rs->id_pengajuan . '\'><i class=\'fa fa-eye\'></i></a>';
                $btn_approve = '<a data-toggle=\'tooltip\' title="Approval" class=\'btn btn-primary btn-xs btn-flat\' href=\'dealer/pengajuan_diskon/approve?id=' . $rs->id_pengajuan . '\'><i class=\'fa fa-check\'></i></a>';
                $btn_reject = '<a data-toggle=\'tooltip\' title="Reject" class=\'btn btn-danger btn-xs btn-flat\' href=\'dealer/pengajuan_diskon/reject?id=' . $rs->id_pengajuan . '\'><i class=\'fa fa-close\'></i></a>';
                $button = $btn_view;
                if ($rs->status == 'Waiting Approval Disc') {
                  $status = '<label class="label label-warning">Waiting Approval Disc</label>';
                  $button = $btn_view . ' ' . $btn_approve . ' ' . $btn_reject;
                }
                if ($rs->status == 'Approved Disc') {
                  $status = '<label class="label label-success">Approved Disc</label>';
                }
                if ($rs->status == 'rejected') {
                  $status = '<label class="label label-danger">Rejected</label>';
                }
              ?>
                <tr>
                  <td><a href="<?= base_url('dealer/pengajuan_diskon/detail?id=' . $rs->id_pengajuan) ?>"><?= $rs->id_prospek ?></a></td>
                  <td><?= $rs->nama_konsumen ?></td>
                  <td><?= $rs->no_hp ?></td>
                  <td><?= $rs->alamat ?></td>
                  <td><?= $rs->tipe_pembayaran ?></td>
                  <td><?= $rs->jatah ?></td>
                  <td><?= $status ?></td>
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

            window.location = '<?= base_url("dealer/pengajuan_diskon/reject_save?id=") ?>' + id_event + '&alasan_reject=' + alasan_reject;

            return false;

          }

          return false

        }
      </script>
    <?php
    }
    ?>
  </section>
</div>