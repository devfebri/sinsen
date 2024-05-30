<?php if ($set != 'download_excel') : ?>
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
        <li class="">H2</li>
        <li class="">Claim</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">
    <?php endif ?>
    <?php
    if ($set == "form") {
      $form = '';
      $readonly = '';
      $disabled = '';
      if ($mode == 'insert') {
        $form = 'save';
      }
      if ($mode == 'approve') {
        $form = 'save_approve';
        $readonly = 'readonly';
        $disabled = 'disabled';
      }
      if ($mode == 'reject') {
        $form = 'save_reject';
        $readonly = 'readonly';
        $disabled = 'disabled';
      }
      if ($mode == 'perbaikan') {
        $form = 'save_perbaikan';
        $readonly = 'readonly';
        $disabled = 'disabled';
      }
      if ($mode == 'detail') {
        $readonly = 'readonly';
        $disabled = 'disabled';
      }
    ?>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <script>
        Vue.use(VueNumeric.default);
        $(document).ready(function() {})
        Vue.filter('toCurrency', function(value) {
          return accounting.formatMoney(value, "", 0, ".", ",");
          return value;
        });
      </script>

      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="h2/rekap_claim_waranty">
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
              <form id="form_" class="form-horizontal" action="h2/rekap_claim_waranty/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. LKH</label>
                    <div class="col-sm-4">
                      <input type="text" readonly class="form-control" placeholder="Klik Untuk Memilih" id="no_lkh" name="no_lkh" onclick="showModalLKH()" value="<?= isset($row) ? $row->no_lkh : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl LKH</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control datepicker" placeholder="Tgl LKH" id="tgl_lkh" readonly value="<?= isset($row) ? $row->tgl_lkh : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <?php if (isset($row)) : ?>
                      <input type="hidden" value="<?= $row->id_rekap_claim ?>" name="id_rekap_claim">
                    <?php endif ?>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl. Pengajuan</label>
                    <div class="col-sm-4">
                      <input type="text" readonly autocomplete="false" required class="form-control datepicker2" placeholder="Tgl Pengajuan" name="tgl_pengajuan" <?= $disabled ?> value="<?= isset($row) ? $row->tgl_pengajuan : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Registrasi Claim</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="no_registrasi" placeholder="No Registrasi" name="no_registrasi" <?= $disabled ?> value="<?= isset($row) ? $row->no_registrasi : '' ?>" readonly>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode AHASS</label>
                    <div class="col-sm-4">
                      <input type="text" readonly class="form-control" id="kode_dealer_md" value="<?= isset($row) ? $row->kode_dealer_md : '' ?>">
                      <input type="hidden" name="id_dealer" id="id_dealer" value="<?= isset($row) ? $row->id_dealer : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama AHASS</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="nama_dealer" readonly value="<?= isset($row) ? $row->nama_dealer : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kategori Claim</label>
                    <div class="col-sm-4">
                      <select class="form-control" required class="form-control" name="ktg_claim" id='ktg_claim' v-model='ktg_claim' :disabled="mode=='detail' || mode=='approve'">
                        <option value=''>- choose -</option>
                        <option value='C1'>C1</option>
                        <option value='C2'>C2</option>
                      </select>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Sub Kategori Claim</label>
                    <div class="col-sm-4">
                      <select class="form-control" required class="form-control" name="sub_ktg_claim" v-model='sub_ktg_claim' :disabled="mode=='detail' || mode=='approve'">
                        <option value=''>- choose -</option>
                        <option value='srbu'>SR/BU</option>
                        <option value='none'>None</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-input">
                      <label for="inputEmail3" class="col-sm-2 control-label">Kelompok Pengajuan</label>
                      <div class="col-sm-4">
                        <select class="form-control" required class="form-control" name="kelompok_pengajuan" v-model='kelompok_pengajuan' :disabled="mode=='detail' || mode=='approve'">
                          <option value=''>- choose -</option>
                          <option value='E'>Engine</option>
                          <option value='L'>Electric</option>
                          <option value='F'>Frame</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Rangka</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" placeholder="No Rangka" id="no_rangka" readonly value="<?= isset($row) ? $row->no_rangka : '' ?>">
                    </div>

                    <label for="inputEmail3" class="col-sm-2 control-label">No Mesin</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="no_mesin" id="no_mesin" readonly value="<?= isset($row) ? $row->no_mesin : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Tgl Pembelian</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="tgl_pembelian" readonly value="<?= isset($row) ? $row->tgl_pembelian : '' ?>">
                    </div>

                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Kerusakan</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="tgl_kerusakan" name="tgl_kerusakan" placeholder="Tanggal Kerusakan" <?= $disabled ?> value="<?= isset($row) ? $row->tgl_kerusakan : '' ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">KM Kerusakan</label>
                    <div class="col-sm-4">
                      <input type="number" class="form-control" placeholder="KM Kerusakan" name="km_kerusakan" id="km_kerusakan" <?= $disabled ?> value="<?= isset($row) ? $row->km_kerusakan : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Alamat</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" placeholder="Alamat" id='alamat' name="alamat" <?= $disabled ?> value="<?= isset($row) ? $row->alamat : '' ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Kelurahan</label>
                    <div class="col-sm-4">
                      <input type="text" required class="form-control" id="id_kelurahan" name="id_kelurahan" readonly value="<?= isset($row) ? $row->id_kelurahan : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Kelurahan</label>
                    <div class="col-sm-4">
                      <input type="text" name="kelurahan" class="form-control" id="kelurahan" readonly value="<?= isset($row) ? $row->kelurahan : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Kota/Kabupaten</label>
                    <div class="col-sm-4">
                      <input type="text" disabled class="form-control" id="kabupaten" value="<?= isset($row) ? $row->kabupaten : '' ?>">
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode POS</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="kode_pos" name="kode_pos" value="<?= isset($row) ? $row->kode_pos : '' ?>" <?= $disabled ?>>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Telepon</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id='no_telepon' name="no_telepon" placeholder="No Telepon" readonly value="<?= isset($row) ? $row->no_telepon : '' ?>">
                    </div>

                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Area</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="kode_area" name="kode_area" <?= $disabled ?> value="<?= isset($row) ? $row->kode_area : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Tanggal Perbaikan</label>
                    <div class="col-sm-4">
                      <input type="text" id='tgl_perbaikan' name="tgl_perbaikan" class="form-control" placeholder="Tanggal Perbaikan" <?= $disabled ?> value="<?= isset($row) ? $row->tgl_perbaikan : '' ?>">
                    </div>
                    <label class="col-sm-2 control-label">Tanggal Selesai Perbaikan</label>
                    <div class="col-sm-4">
                      <input type="text" id='tgl_selesai_perbaikan' name="tgl_selesai_perbaikan" class="form-control" placeholder="Tanggal Selesai Perbaikan" <?= $disabled ?> value="<?= isset($row) ? $row->tgl_selesai_perbaikan : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">KM Perbaikan</label>
                    <div class="col-sm-4">
                      <input type="text" id='km_perbaikan' name="km_perbaikan" class="form-control" placeholder="KM Perbaikan" <?= $disabled ?> value="<?= isset($row) ? $row->km_perbaikan : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Uraian Gejala Kerusakan</label>
                    <div class="col-sm-10">
                      <input type="text" id='uraian_gejala_kerusakan' name="uraian_gejala_kerusakan" class="form-control" placeholder="Uraian Gejala Kerusakan" <?= $disabled ?> value="<?= isset($row) ? $row->uraian_gejala_kerusakan : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Part Utama</label>
                    <div class="col-sm-4">
                      <input type="text" readonly id="part_utama" class="form-control" placeholder="Part Utama" value="<?= isset($row) ? $row->part_utama : '' ?>">
                    </div>
                    <label class="col-sm-2 control-label">Deskripsi Part Utama</label>
                    <div class="col-sm-4">
                      <input type="text" readonly id="nama_part" class="form-control" placeholder="Part Utama" value="<?= isset($row) ? $row->nama_part : '' ?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Symptom Code</label>
                    <div class="col-sm-4">
                      <input name='id_symptom' id='id_symptom' class='form-control' value="<?= isset($row) ? $row->uraian_gejala_kerusakan : '' ?>" readonly />
                    </div>
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-4">
                      <input name='symptom_id' id='symptom_id' class='form-control' value="<?= isset($row) ? $row->uraian_gejala_kerusakan : '' ?>" readonly />
                    </div>
                  </div>
                  <!-- <div class="col-sm-1">
                    <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                  </div> -->
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Rank</label>
                    <div class="col-sm-4">
                      <input type="text" id='rank' name="rank" class="form-control" placeholder="Rank" <?= $disabled ?> value="<?= isset($row) ? $row->rank : '' ?>" readonly>
                    </div>
                  </div><br>
                  <button class="btn btn-primary btn-flat col-sm-12 col-xs-12 col-lg-12  col-md-12 " disabled>Detail</button>
                  <br><br><br>
                  <table class="table table-hover table-bordered">
                    <thead>
                      <th>ID Part</th>
                      <th>Nama Part</th>
                      <th>Jumlah</th>
                      <th>Tipe Penggantian</th>
                      <th>HET</th>
                      <th>Ongkos Kerja</th>
                      <th>Status Part</th>
                      <th v-if="mode=='insert'">Aksi</th>
                    </thead>
                    <tbody>
                      <tr v-for="(dtl, index) of details">
                        <td>{{dtl.id_part}}</td>
                        <td>{{dtl.nama_part}}</td>
                        <td>{{dtl.qty}}</td>
                        <td>
                          <select class="form-control isi2" v-model='dtl.tipe_penggantian' :disabled="mode=='detail' || mode=='approve'">
                            <option value=''>-choose-</option>
                            <option value='U'>U</option>
                            <option value='P'>P</option>
                            <option value='B'>B</option>
                          </select>
                        </td>
                        <td align="right">
                            <!--<input type="text" v-model='dtl.harga' class="form-control"/>-->
                            {{dtl.harga | toCurrency}}
                            </td>
                        <td align="right">
                            <!--<input type="text" v-model='dtl.ongkos' class="form-control"/>-->
                            {{dtl.ongkos | toCurrency}}
                            </td>
                        <td>
                          <select class="form-control isi2" v-model='dtl.status_part' :disabled="mode=='detail' || mode=='approve'">
                            <option value=''>-choose-</option>
                            <option value=1>M</option>
                            <option value=0>R</option>
                          </select>
                        </td>
                        <td align="center" v-if="mode=='insert'" style="text-align: center;vertical-align: middle;">
                          <button type="button" @click.prevent="delDetails(index)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                        </td>
                      </tr>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan=2><b>Total</b></td>
                        <td><b>{{total.qty}}</b></td>
                        <td></td>
                        <td align='right'><b>{{total.harga | toCurrency}}</b></td>
                        <td align='right'><b>{{total.ongkos | toCurrency}}</b></td>
                        <td colspan=2 v-if="mode=='insert'"></td>
                        <td v-if="mode=='detail' || mode=='approve'"></td>
                      </tr>
                      <tr v-if="mode=='insert'">
                        <td>
                          <input type="text" v-model="detail.id_part" class="form-control isi2" readonly @click.prevent="showModalPart">
                        </td>
                        <td>
                          <input type="text" v-model="detail.nama_part" class="form-control isi2" readonly @click.prevent="showModalPart">
                        </td>
                        <td><input type="text" class="form-control isi2" v-model="detail.qty"></td>
                        <td>
                          <select class="form-control isi2" v-model='detail.tipe_penggantian'>
                            <option value=''>-choose-</option>
                            <option value='U'>U</option>
                            <option value='P'>P</option>
                            <option value='B'>B</option>
                          </select>
                        </td>
                        <td>
                          <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control isi2" v-model="detail.harga" v-bind:minus="false" :empty-value="0" separator="." readonly />
                        </td>
                        <td>
                          <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control isi2" v-model="detail.ongkos" v-bind:minus="false" :empty-value="0" separator="." />
                        </td>
                        <td>
                          <select class="form-control isi2" v-model='detail.status_part'>
                            <option value=''>-choose-</option>
                            <option value='1'>M</option>
                            <option value='0'>R</option>
                          </select>
                        </td>
                        <td>
                          <button type="button" class="btn btn-primary btn-flat btn-xs" data-toggle="tooltip" data-placement="top" title="Add" @click.prevent="addDetails"><i class="fa fa-plus"></i></button>
                        </td>
                      </tr>
                    </tfoot>
                  </table>

                  <?php if (($mode == 'reject' or $mode == 'detail')) : ?>
                    <br><br>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Alasan Reject</label>
                      <div class="col-sm-9">
                        <input type="text" name="alasan_reject" class="form-control" placeholder="Alasan Reject" :readonly="mode!='reject'" required value="<?= isset($row) ? $row->alasan_reject : '' ?>">
                      </div>
                    </div>
                  <?php endif ?>
                  <?php if (($mode == 'perbaikan' or $mode == 'detail')) : ?>
                    <br><br>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Alasan Perbaikan</label>
                      <div class="col-sm-9">
                        <input type="text" name="alasan_perbaikan" class="form-control" placeholder="Alasan Perbaikan" :readonly="mode!='perbaikan'" required value="<?= isset($row) ? $row->alasan_reject : '' ?>">
                      </div>
                    </div>
                  <?php endif ?>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div class="col-sm-12" style="text-align: center;" v-if="mode=='insert'">
                    <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                  </div>
                  <div class="col-sm-12" style="text-align: center;" v-if="mode=='approve'">
                    <button type="submit" onclick="return confirm('Apakah anda yakin ?')" name="save" value="save" class="btn btn-success btn-flat">Approve</button>
                  </div>
                  <div class="col-sm-12" style="text-align: center;" v-if="mode=='reject'">
                    <button type="submit" onclick="return confirm('Apakah anda yakin ?')" name="save" value="save" class="btn btn-danger btn-flat">Reject</button>
                  </div>
                  <div class="col-sm-12" style="text-align: center;" v-if="mode=='perbaikan'">
                    <button type="submit" onclick="return confirm('Apakah anda yakin ?')" name="save" value="save" class="btn btn-primary btn-flat">Save</button>
                  </div>
                </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal fade modalPart" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">

            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Part</h4>
            </div>
            <div class="modal-body">
              <input type="hidden" id="no_mesin_part">
              <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_part" style="width: 100%">
                <thead>
                  <tr>
                    <th>ID Part</th>
                    <th>Nama Part</th>
                    <th>Kel. Vendor</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
              <script>
                $(document).ready(function() {
                  $('#tbl_part').DataTable({
                    processing: true,
                    serverSide: true,
                    "language": {
                      "infoFiltered": ""
                    },
                    order: [],
                    ajax: {
                      url: "<?= base_url('master/kpb/fetch_part') ?>",
                      dataSrc: "data",
                      data: function(d) {
                        // d.kode_item     = $('#kode_item').val();
                        return d;
                      },
                      type: "POST"
                    },
                    "columnDefs": [
                      // { "targets":[4],"orderable":false},
                      {
                        "targets": [2],
                        "className": 'text-center'
                      },
                      // { "targets":[4], "searchable": false } 
                    ]
                  });
                });
              </script>
            </div>
          </div>
        </div>
      </div>
      <?php
      $data = ['data' => ['modalLKH']];
      $this->load->view('h2/api', $data);
      ?>
      <?php
      $data['data'] = ['kelurahan'];
      $this->load->view('dealer/h2_api', $data); ?>
      <script>
        function pilihLKH(lkh) {
          $('#no_lkh').val(lkh.id_lkh);
          $('#tgl_lkh').val(lkh.tgl_lkh);
          $('#no_rangka').val(lkh.no_rangka);
          $('#no_mesin').val(lkh.no_mesin);
          $('#kode_dealer_md').val(lkh.kode_dealer_md);
          $('#nama_dealer').val(lkh.nama_dealer);
          $('#id_dealer').val(lkh.id_dealer);
          $('#alamat').val(lkh.alamat);
          $('#id_kelurahan').val(lkh.id_kelurahan);
          $('#kelurahan').val(lkh.kelurahan);
          $('#kabupaten').val(lkh.kabupaten);
          $('#tgl_pembelian').val(date_dmy(lkh.tgl_pembelian));
          $('#tgl_kerusakan').val(date_dmy(lkh.tgl_kejadian));
          $('#tgl_perbaikan').val(date_dmy(lkh.tgl_kejadian));
          $('#tgl_selesai_perbaikan').val(date_dmy(lkh.tgl_kejadian));
          $('#km_kerusakan').val(lkh.km);
          $('#part_utama').val(lkh.part_utama);
          $('#nama_part').val(lkh.nama_part);
          $('#no_registrasi').val(lkh.no_claim_c2);
          $('#kode_pos').val(lkh.kode_pos);
          $('#no_telepon').val(lkh.no_hp);
          form_.ktg_claim = lkh.kategori_claim;
          $('#km_perbaikan').val(lkh.km);
          $('#id_symptom').val(lkh.symptom_code);
          $('#symptom_id').val(lkh.symptom_id);
          $('#rank').val(lkh.grade);
          $('#uraian_gejala_kerusakan').val(lkh.keluhan_konsumen);
          pilihPartLKH(lkh)
        }

        function pilihPartLKH(lkh) {
          values = {
            id_sa_form: lkh.id_sa_form,
            id_dealer: $('#id_dealer').val()
          }
          $.ajax({
            beforeSend: function() {
              $('#searchWO').html('<i class="fa fa-spinner fa-spin"></i>');
              $('#searchWO').attr('disabled', true);
            },
            url: '<?= base_url('dealer/work_order_dealer/getDataWO') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              $('#searchWO').html('<i class="fa fa-search"></i>');
              $('#searchWO').attr('disabled', false);
              if (response.status == 'sukses') {
                for (pkj of response.details) {
                  if (pkj.id_type === 'C2') {

                    for (prt of pkj.parts) {
                      let ongkos = prt.part_utama == 1 ? parseInt(pkj.harga) : 0;
                      let new_part = {
                        id_part: prt.id_part,
                        nama_part: prt.nama_part,
                        qty: prt.qty,
                        tipe_penggantian: '',
                        harga: prt.harga,
                        ongkos: ongkos,
                        status_part: prt.part_utama == 1 ? prt.part_utama : 0,
                      }
                      form_.details.push(new_part);
                    }
                  }
                  if (pkj.id_type === 'C1') {

                    for (prt of pkj.parts) {
                      let ongkos = prt.part_utama == 1 ? parseInt(pkj.harga) : 0;
                      let new_part = {
                        id_part: prt.id_part,
                        nama_part: prt.nama_part,
                        qty: prt.qty,
                        tipe_penggantian: '',
                        harga: prt.harga,
                        ongkos: ongkos,
                        status_part: prt.part_utama == 1 ? prt.part_utama : 0,
                      }
                      form_.details.push(new_part);
                    }
                  }
                }
              } else {
                alert(response.pesan);
                form_.details = [];
              }
            },
            error: function() {
              alert("Something Went Wrong !");
              $('#searchWO').html('<i class="fa fa-search"></i>');
              $('#searchWO').attr('disabled', false);
            },
          });
        }

        function pilihPart(part) {
          form_.detail = {
            id_part: part.id_part,
            nama_part: part.nama_part,
            harga: part.harga_dealer_user / 1.1,
            status_part: part.status,
          }
        }
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            detail: {
              id_part: '',
              nama_part: '',
              jumlah: '',
              tipe_penggantian: '',
              harga: '',
              ongkos: '',
              status_part: ''
            },
            ktg_claim: '<?= isset($row) ? $row->ktg_claim : '' ?>',
            sub_ktg_claim: '<?= isset($row) ? $row->sub_ktg_claim : '' ?>',
            kelompok_pengajuan: '<?= isset($row) ? $row->kelompok_pengajuan : '' ?>',
            details: <?= isset($details) ? json_encode($details) : '[]' ?>,
          },
          methods: {
            showModalPart: function() {
              // $('#tbl_part').DataTable().ajax.reload();
              $('.modalPart').modal('show');
            },
            clearDetail: function() {
              this.detail = {
                id_part: '',
                nama_part: '',
                qty: '',
                tipe_penggantian: '',
                harga: '',
                ongkos: '',
                status_part: ''
              }
            },
            addDetails: function() {
              this.details.push(this.detail);
              this.clearDetail();
            },
            delDetails: function(index) {
              this.details.splice(index, 1);
            },
          },
          computed: {
            total: function() {
              let total = {
                qty: 0,
                harga: 0,
                ongkos: 0
              }
              for (dt of this.details) {
                console.log(dt);
                total.qty += parseInt(dt.qty);
                total.harga += parseFloat(dt.harga) * parseInt(dt.qty);
                total.ongkos += parseInt(dt.ongkos);
              }
              
              return total;
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
            if (values.details.length == 0) {
              alert('Detail masih kosong !')
              return false;
            }
            if (confirm("Apakah anda yakin ?") == true) {
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').html('<i class="fa fa-spinner fa-spin"></i> Process');
                  $('#submitBtn').attr('disabled', true);
                },
                url: '<?= base_url('h2/rekap_claim_waranty/' . $form) ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    window.location = response.link;
                  } else {
                    alert(response.pesan);
                  }
                  $('#submitBtn').html('<i class="fa save"></i> Save All');
                  $('#submitBtn').attr('disabled', false);
                },
                error: function() {
                  alert("failure");
                  $('#submitBtn').html('<i class="fa save"></i> Save All');
                  $('#submitBtn').attr('disabled', false);

                },
                statusCode: {
                  500: function() {
                    alert('fail');
                    $('#submitBtn').html('<i class="fa save"></i> Save All');
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
            <a href="h2/rekap_claim_waranty/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
            </a>
            <a href="h2/rekap_claim_waranty/download_excel" onclick="return confirm('Apakah anda yakin ?')" class="btn bg-maroon btn-flat margin">
              <i class="fa fa-download"></i> Download Excel
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
          <table id="example4" class="table table-hover">
            <thead>
              <tr>
                <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                <!-- <th width="5%">No</th>               -->
                <th>No Registrasi Claim</th>
                <th>No LKH</th>
                <th>Tanggal Pengajuan</th>
                <th>Kode AHASS</th>
                <th>Nama AHASS</th>
                <th>No Mesin</th>
                <th>No Rangka</th>
                <!--<th>Tanggal Kerusakan</th>-->
                <th>Status Claim</th>
                <th width="12%" align="center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // $no=1; 
              foreach ($dt_result->result() as $rs) {
                $status = '';
                $button = '';
                $btn_edit = "<a class='btn btn-warning btn-xs' href='" . base_url('h2/rekap_claim_waranty/edit?id=') . "$rs->id_rekap_claim'>Edit</a>";
                $btn_approve = "<a class='btn btn-success btn-xs' href='" . base_url('h2/rekap_claim_waranty/approve?id=') . "$rs->id_rekap_claim'>Approve</a>";
                $btn_reject = "<a class='btn btn-danger btn-xs' href='" . base_url('h2/rekap_claim_waranty/reject?id=') . "$rs->id_rekap_claim'>Reject</a>";
                $btn_perbaikan = "<a class='btn btn-info btn-xs' href='" . base_url('h2/rekap_claim_waranty/perbaikan?id=') . "$rs->id_rekap_claim'>Perbaikan</a>";

                if ($rs->status == 'input') {
                  $status = '<label class="label label-info">Input</label>';
                  $button = $btn_approve . ' ' . $btn_perbaikan . ' ' . $btn_reject;
                }
                if ($rs->status == 'approve') {
                  $status = '<label class="label label-success">Approved</label>';
                }
                if ($rs->status == 'reject') {
                  $status = '<label class="label label-danger">Rejected</label>';
                }
                echo "
            <tr>
              <td><a href='" . base_url('h2/rekap_claim_waranty/detail?id=') . "$rs->id_rekap_claim'>$rs->no_registrasi</a></td>
              <td>$rs->no_lkh</td>
              <td>".formatTanggal($rs->tgl_pengajuan)."</td>
              <td>$rs->kode_dealer_md</td>
              <td>$rs->nama_dealer</td>
              <td>$rs->no_mesin</td>
              <td>$rs->no_rangka</td>
             
              <td>$status</td>
              <td>$button</td>   
            </tr> ";
              }
              ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    } elseif ($set == 'download_excel') {
      header("Content-type: application/octet-stream");
      header("Content-Disposition: attachment; filename=Rekap Claim Waranty.xls");
      header("Pragma: no-cache");
      header("Expires: 0");
    ?>
      <div align="center" style="font-weight: bold">Rekap Claim Waranty</div>
      <table border="1" width="100%">
        <tr>
          <td>No.</td>
          <td>No Registrasi Claim</td>
          <td>Tanggal Pengajuan</td>
          <td>Kode AHASS</td>
          <td>Nama AHASS</td>
          <td>No Mesin</td>
          <td>No Rangka</td>
          <td>Tanggal Pembelian</td>
          <td>Tanggal Kerusakan</td>
        </tr>
        <?php $no = 1;
        foreach ($rekap->result() as $rs) : ?>
          <tr>
            <td><?= $no ?></td>
            <td><?= $rs->no_registrasi ?></td>
            <td><?= $rs->tgl_pengajuan ?></td>
            <td><?= $rs->kode_dealer_md ?></td>
            <td><?= $rs->nama_dealer ?></td>
            <td><?= $rs->no_mesin ?></td>
            <td><?= $rs->no_rangka ?></td>
            <td><?= $rs->tgl_pembelian ?></td>
            <td><?= $rs->tgl_kerusakan ?></td>
          </tr>
          <tr>
            <td></td>
            <td colspan="8"><b>Detail</b></td>
          </tr>
          <?php $detail = $this->db->query("SELECT tr_rekap_claim_waranty_detail.*,nama_part FROM tr_rekap_claim_waranty_detail 
      JOIN ms_part ON tr_rekap_claim_waranty_detail.id_part=ms_part.id_part
      WHERE id_rekap_claim='$rs->id_rekap_claim'"); ?>
          <tr>
            <td></td>
            <td>Kode Part</td>
            <td>Nama Part</td>
            <td>Jumlah</td>
            <td>Tipe Penggantian</td>
            <td>HET</td>
            <td>Ongkos Kerja</td>
          </tr>
          <?php foreach ($detail->result() as $dtl) : ?>
            <tr>
              <td></td>
              <td><?= $dtl->id_part ?></td>
              <td><?= $dtl->nama_part ?></td>
              <td><?= $dtl->qty ?></td>
              <td><?= $dtl->tipe_penggantian ?></td>
              <td><?= $dtl->harga ?></td>
              <td><?= $dtl->ongkos ?></td>
            </tr>
          <?php endforeach ?>
        <?php $no++;
        endforeach ?>
      </table>
    <?php } ?>
    <?php if ($set != 'download_excel') : ?>
    </section>
  </div>
<?php endif ?>