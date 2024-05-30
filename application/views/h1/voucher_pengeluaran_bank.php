<style type="text/css">
  .myTable1 {
    margin-bottom: 0px;
  }

  .myt {
    margin-top: 0px;
  }

  .isi {
    height: 30px;
    padding-left: 5px;
    padding-right: 5px;
    margin-right: 0px;
  }

  .isi_combo {
    height: 30px;
    border: 1px solid #ccc;
    padding-left: 1.5px;
  }
</style>
<base href="<?php echo base_url(); ?>" />
<?php if ($set != 'form') : ?>

  <body onload="sembunyi()">
  <?php endif ?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">H1</li>
        <li class="">Finance</li>
        <li class="">Bank,KS,BG Beredar</li>
        <li class="">Bank/Cash</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $page)); ?></li>
      </ol>
    </section>
    <section class="content">
      <?php
      if ($set == "insert") {
      ?>

        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/voucher_pengeluaran_bank">
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
                <form id="form_entri" class="form-horizontal" action="h1/voucher_pengeluaran_bank/save" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                        <!-- testing -->
                      <input type="hidden" name="id_voucher_bank" id="id_voucher_bank">
                      <label for="inputEmail3" class="col-sm-2 control-label">Account</label>
                      <div class="col-sm-4">
                        <select class="form-control select2" name="account" id="account" onchange="getNoBG()" required>
                          <option value="">- choose -</option>
                          <?php
                          $r = $this->m_admin->getAll("ms_rek_md");
                          foreach ($r->result() as $isi) {
                            echo "<option value='$isi->no_rekening'>$isi->no_rekening ($isi->bank)</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl Entry</label>
                      <div class="col-sm-4">
                        <input type="text" name="tgl_entry" placeholder="Tgl Entry" value="<?php echo date('Y-m-d') ?>" readonly class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tipe Customer</label>
                      <div class="col-sm-4">
                        <select class="form-control" name="tipe_customer" id="tipe_customer" onchange="cek_tipe()" required>
                          <option value="">- choose -</option>
                          <option>Dealer</option>
                          <option>Vendor</option>
                          <option>Lain-lain</option>
                        </select>
                      </div>
                      <span id="lain_lain">
                        <label for="inputEmail3" class="col-sm-2 control-label">Dibayar kepada</label>
                        <div class="col-sm-4">
                          <input type="text" name="dibayar_l" placeholder="Dibayar kepada" class="form-control">
                        </div>
                      </span>
                      <span id="dealer">
                        <label for="inputEmail3" class="col-sm-2 control-label">Dibayar kepada</label>
                        <div class="col-sm-4">
                          <select class="form-control select2" name="dibayar_d" id="id_dealer" onchange="getRekTujuan()">
                            <option value="">- choose -</option>
                            <?php
                            $r = $this->m_admin->getAll("ms_dealer");
                            foreach ($r->result() as $isi) {
                              echo "<option value='$isi->id_dealer'>$isi->nama_dealer ($isi->kode_dealer_md)</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </span>
                      <span id="vendor">
                        <label for="inputEmail3" class="col-sm-2 control-label">Dibayar kepada</label>
                        <div class="col-sm-4">
                          <select class="form-control select2" name="dibayar_v" id="id_vendor" onchange="getRekTujuan()">
                            <option value="">- choose -</option>
                            <?php
                            $r = $this->m_admin->getAll("ms_vendor");
                            foreach ($r->result() as $isi) {
                              echo "<option value='$isi->id_vendor'>$isi->vendor_name</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </span>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">PPh</label>
                      <div class="col-sm-4">
                        <select class="form-control" name="pph">
                          <option value="">- choose -</option>
                          <?php
                          $r = $this->m_admin->getAll("ms_pph");
                          foreach ($r->result() as $isi) {
                            echo "<option value='$isi->presentase'>$isi->presentase</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembayaran</label>
                      <div class="col-sm-4">
                        <select class="form-control" name="jenis_bayar" id="jenis_bayar" onchange="getRef()" required>
                          <option value="">- choose -</option>
                          <option>Unit</option>
                          <option>Ekspedisi Unit</option>
                        </select>
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Total Pembayaran</label>
                      <div class="col-sm-4">
                        <input type="text" name="total_pembayaran_real" readonly id="total_pembayaran_real" placeholder="Total Pembayaran" class="form-control">
                        <input type="hidden" name="total_pembayaran" id="total_pembayaran">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Rekening Tujuan</label>
                      <div class="col-sm-4">
                        <div id="rek_tujuan_l" style="display: none;">
                          <input type="text" name="rekening_tujuan_l" id="rekening_tujuan_l" placeholder="Rekening Tujuan" class="form-control">
                        </div>
                        <div id="rek_tujuan_d" style="display: none;">
                          <select style="width: 100%;" class="form-control select2" name="rekening_tujuan_d" id="rekening_tujuan_d"></select>
                        </div>
                        <div id="rek_tujuan_v" style="display: none;">
                          <select style="width: 100%;" class="form-control select2" name="rekening_tujuan_v" id="rekening_tujuan_v"></select>
                        </div>
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Via Bayar</label>
                      <div class="col-sm-4">
                        <select class="form-control" name="via_bayar" id="via_bayar" onchange="cek_via()" required>
                          <option value="">- choose -</option>
                          <option>BG</option>
                          <option>Transfer</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>
                      <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="deskripsi"></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <span id="tampil_bg">
                        <hr>
                        <div class="form-group">
                          <label for="field-1" class="col-sm-2 control-label">No BG</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" id="no_bg" name="no_bg">
                              <!-- <option value="">- choose -</option>
                          <?php
                          $dt_cek = $this->m_admin->getAll("ms_cek_giro");
                          foreach ($dt_cek->result() as $val) {
                            echo "
                            <option value='$val->kode_giro'>$val->kode_giro</option>;
                            ";
                          }
                          ?> -->
                            </select>
                          </div>
                          <label for="field-1" class="col-sm-2 control-label">Tgl.Jatuh Tempo BG/Cek</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="tanggal4" placeholder="Tgl.Jatuh Tempo BG/Cek" name="tgl_bg">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="field-1" class="col-sm-2 control-label">Nominal BG/Cek</label>
                          <div class="col-sm-4">
                            <input type="text" id="nominal_bg" class="form-control" placeholder="Nominal BG/Cek" name="nominal_bg">
                          </div>
                          <div class="col-sm-2">
                          </div>
                          <div class="col-sm-4">
                            <button type="button" onClick="simpan_bg()" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>
                            <button type="button" onClick="kirim_data_bg()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                            <button type="button" onClick="hide_bg()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-2">
                          </div>
                          <div class="col-sm-10">
                            <div id="tampil_bg_isi"></div>
                          </div>
                        </div>
                      </span>

                      <span id="tampil_transfer">
                        <hr>
                        <div class="form-group">
                          <label for="field-1" class="col-sm-2 control-label">Tgl.Transfer</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="tanggal5" placeholder="Tgl.Transfer" name="tgl_transfer">
                          </div>
                          <label for="field-1" class="col-sm-2 control-label">Nominal Transfer</label>
                          <div class="col-sm-4">
                            <input type="text" id="nominal_transfer" class="form-control" placeholder="Nominal Transfer" name="nominal_transfer">
                          </div>
                          <div class="form-group">
                          </div>
                          <div class="col-sm-8">
                          </div>
                          <div class="col-sm-4">
                            <button type="button" onClick="simpan_transfer()" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>
                            <button type="button" onClick="kirim_data_transfer()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                            <button type="button" onClick="hide_transfer()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-2">
                          </div>
                          <div class="col-sm-10">
                            <div id="tampil_transfer_isi"></div>
                          </div>
                        </div>
                      </span>
                      <hr>

                    </div>
                    
                    <div id="tampil_detail"></span></div>
                    <div id="tampil_rekap"></div>


                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-2">
                    </div>
                    <div class="col-sm-10">
                      <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                      <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                    </div>
                  </div><!-- /.box-footer -->

                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
        <script>
          $('#submitBtn').click(function() {
            $('#form_entri').validate({
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

            if ($('#form_entri').valid()) // check if form is valid
            {
              var totNominal = $('#totNominal').text();
              if (totNominal == 0) {
                alert('Detail belum dipilih !');
                return false;
              }
              $('#submitBtn').attr('disabled', true);
              $('#form_entri').submit();
            } else {
              alert('Silahkan isi field required !')
            }
          })
        </script>
      <?php
      } elseif ($set == "form") {
        $form = '';
        $disabled = '';
        $readonly = '';
        if ($mode == 'edit') {
          $form = 'save_edit';
        }
        if ($mode == 'detail') {
          $disabled = 'disabled';
        }
      ?>
        <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
        <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
        <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
        <div class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/voucher_pengeluaran_bank">
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
                <form id="form_entri" class="form-horizontal" action="h1/voucher_pengeluaran_bank/<?= $form ?>" method="post" enctype="multipart/form-data">
                  <div class="box-body">
                    <div class="form-group">
                      <!-- testing -->
                      <input type="hidden" name="id_voucher_bank" id="id_voucher_bank" value="<?= $row->id_voucher_bank ?>">
                      <label for="inputEmail3" class="col-sm-2 control-label">Account</label>
                      <div class="col-sm-4">
                        <select class="form-control select2" name="account" id="account" onchange="getNoBG()">
                          <option value="">- choose -</option>
                          <?php
                          $r = $this->m_admin->getAll("ms_rek_md");
                          foreach ($r->result() as $isi) {
                            $select = $row->account == $isi->no_rekening ? 'selected' : '';
                            echo "<option value='$isi->no_rekening' $select>$isi->no_rekening ($isi->bank)</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tgl Entry</label>
                      <div class="col-sm-4">
                        <input type="text" name="tgl_entry" placeholder="Tgl Entry" value="<?php echo date('Y-m-d') ?>" readonly class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Tipe Customer</label>
                      <div class="col-sm-4">
                        <select class="form-control" name="tipe_customer" v-model="tipe_customer">
                          <option value="">- choose -</option>
                          <option value="Dealer">Dealer</option>
                          <option value="Vendor">Vendor</option>
                          <option value="Lain-lain">Lain-lain</option>
                        </select>
                      </div>
                      <span v-if="tipe_customer=='Lain-lain'">
                        <label for="inputEmail3" class="col-sm-2 control-label">Dibayar kepada</label>
                        <div class="col-sm-4">
                          <input type="text" name="dibayar_l" value ='<?= $row->dibayar ?>' placeholder="Dibayar kepada" class="form-control">
                        </div>
                      </span>
                      <span v-if="tipe_customer=='Dealer'">
                        <label for="inputEmail3" class="col-sm-2 control-label">Dibayar kepada</label>
                        <div class="col-sm-4">
                          <select class="form-control select2" name="dibayar_d" id="id_dealer" onchange="getRekTujuan()">
                            <option value="">- choose -</option>
                            <?php
                            $r = $this->m_admin->getAll("ms_dealer");
                            foreach ($r->result() as $isi) {
                              $select = $row->dibayar == $isi->id_dealer ? 'selected' : '';
                              echo "<option value='$isi->id_dealer' $select>$isi->nama_dealer ($isi->kode_dealer_md)</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </span>
                      <span v-if="tipe_customer=='Vendor'">
                        <label for="inputEmail3" class="col-sm-2 control-label">Dibayar kepada</label>
                        <div class="col-sm-4">
                          <select class="form-control select2" name="dibayar_v" id="id_vendor" onchange="getRekTujuan()">
                            <option value="">- choose -</option>
                            <?php
                            $r = $this->m_admin->getAll("ms_vendor");
                            foreach ($r->result() as $isi) {
                              $select = $row->dibayar == $isi->id_vendor ? 'selected' : '';

                              echo "<option value='$isi->id_vendor' $select>$isi->vendor_name</option>";
                            }
                            ?>
                          </select>
                        </div>
                      </span>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">PPh</label>
                      <div class="col-sm-4">
                        <select class="form-control" name="pph">
                          <option value="">- choose -</option>
                          <?php
                          $r = $this->m_admin->getAll("ms_pph");
                          foreach ($r->result() as $isi) {
                            $select = $isi->presentase == $row->pph ? 'selected' : '';
                            echo "<option value='$isi->presentase' $select>$isi->presentase</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Jenis Pembayaran</label>
                      <div class="col-sm-4">
                        <select class="form-control" name="jenis_bayar" v-model="jenis_bayar" onchange="getRef()">
                          <option value="">- choose -</option>
                          <option value="Unit">Unit</option>
                          <option value="Ekspedisi Unit">Ekspedisi Unit</option>
                        </select>
                      </div>
                      <!--   <label for="inputEmail3" class="col-sm-2 control-label">Total Pembayaran</label>
                  <div class="col-sm-4">
                    <input type="text" name="total_pembayaran_real" readonly id="total_pembayaran_real" placeholder="Total Pembayaran" class="form-control">
                    <input type="hidden" name="total_pembayaran" id="total_pembayaran">
                  </div>     -->
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Rekening Tujuan</label>
                      <div v-if="tipe_customer=='Lain-lain'">
                        <div class="col-sm-4">
                          <input type="text" name="rekening_tujuan_l" value ='<?= $row->rekening_tujuan ?>' placeholder="Rekening Tujuan" class="form-control">
                        </div>
                      </div>
                      <div v-if="tipe_customer=='Dealer'">
                        <div class="col-sm-4">
                          <select style="width: 100%;" class="form-control select2" name="rekening_tujuan_d"></select>
                        </div>
                      </div>
                      <div v-if="tipe_customer=='Vendor'">
                        <div class="col-sm-4">
                          <select style="width: 100%;" class="form-control select2" name="rekening_tujuan_v"></select>
                        </div>
                      </div>
                      <label for="inputEmail3" class="col-sm-2 control-label">Via Bayar</label>
                      <div class="col-sm-4">
                        <select class="form-control" name="via_bayar" v-model="via_bayar" onchange="getNoBG()">
                          <option value="">- choose -</option>
                          <option value="BG">BG</option>
                          <option value="Transfer">Transfer</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputEmail3" class="col-sm-2 control-label">Deskripsi</label>
                      <div class="col-sm-10">
                        <textarea class="form-control" rows="3" name="deskripsi" id="deskripsi"><?= $row->deskripsi ?></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <span v-if="via_bayar=='BG'">
                        <hr>
                        <div class="form-group">
                          <label for="field-1" class="col-sm-2 control-label">No BG</label>
                          <div class="col-sm-4">
                            <select class="form-control select2" id="no_bg" name="no_bg">
                              <!-- <option value="">- choose -</option>
                          <?php
                          $dt_cek = $this->m_admin->getAll("ms_cek_giro");
                          foreach ($dt_cek->result() as $val) {
                            echo "
                            <option value='$val->kode_giro'>$val->kode_giro</option>;
                            ";
                          }
                          ?> -->
                            </select>
                          </div>
                          <label for="field-1" class="col-sm-2 control-label">Tgl.Jatuh Tempo BG/Cek</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="tanggal4" placeholder="Tgl.Jatuh Tempo BG/Cek" name="tgl_bg">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="field-1" class="col-sm-2 control-label">Nominal BG/Cek</label>
                          <div class="col-sm-4">
                            <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="bg.nominal_bg" v-bind:minus="false" :empty-value="0" separator="." />
                          </div>
                          <div class="col-sm-2">
                          </div>
                          <div class="col-sm-4">
                            <button type="button" @click.prevent="addBg" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>
                            <button type="button" onClick="kirim_data_bg()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                            <button type="button" onClick="hide_bg()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-3">
                          </div>
                          <div class="col-sm-9">
                            <table class="table table-bordered responsive-utilities jambo_table bulk_action">
                              <tr>
                                <td>No. BG</td>
                                <td>Tgl.BG</td>
                                <td>Nominal</td>
                                <td>Aksi</td>
                              </tr>
                              <tr v-for="(bg, index) of bg_">
                                <td>{{bg.no_bg}}</td>
                                <td>
                                  <input type="text" class="form-control datepicker" v-model="bg.tgl_bg" autocomplete="off" <?= $disabled ?>>
                                </td>
                                <td>
                                  <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="bg.nominal_bg" v-bind:minus="false" :empty-value="0" separator="." <?= $disabled ?> />
                                </td>
                                <td align="center" v-if="mode=='edit'">
                                  <button type="button" @click.prevent="delBg(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="2" align="right"><b>Total</b></td>
                                <td>
                                  <vue-numeric style="float: left;width: 100%;text-align: right;font-weight: bold;" class="form-control text-rata-kanan isi" v-model="totBg" v-bind:minus="false" :empty-value="0" separator="." disabled />
                                </td>
                                <td></td>
                              </tr>
                            </table>
                          </div>
                        </div>
                      </span>

                      <span v-if="via_bayar=='Transfer'">
                        <hr>
                        <div class="form-group">
                          <label for="field-1" class="col-sm-2 control-label">Tgl.Transfer</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="tanggal5" name="tgl_transfer">
                          </div>
                          <label for="field-1" class="col-sm-2 control-label">Nominal Transfer</label>
                          <div class="col-sm-3">
                            <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan" v-model="transfer.nominal_transfer" v-bind:minus="false" :empty-value="0" separator="." />
                          </div>
                          <div class="form-group">
                          </div>
                          <div class="col-sm-8">
                          </div>
                          <div class="col-sm-4">
                            <button type="button" @click.prevent="addTransfer" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Add</button>
                            <button type="button" onClick="kirim_data_transfer()" class="btn btn-warning btn-flat"><i class="fa fa-eye"></i> Show</button>
                            <button type="button" onClick="hide_transfer()" class="btn btn-warning btn-flat"><i class="fa fa-eye-slash"></i> Hide</button>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-3">
                          </div>
                          <div class="col-sm-9">
                            <table class="table table-bordered responsive-utilities jambo_table bulk_action">
                              <tr>
                                <td>No.</td>
                                <td>Tgl.Transfer</td>
                                <td>Nominal</td>
                                <td>Aksi</td>
                              </tr>
                              <tr v-for="(trf, index) of transfers">
                                <td>{{index+1}}</td>
                                <td>
                                  <input type="text" class="form-control datepicker" v-model="trf.tgl_transfer" autocomplete="off" <?= $disabled ?>>
                                </td>
                                <td>
                                  <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="trf.nominal_transfer" v-bind:minus="false" :empty-value="0" separator="." <?= $disabled ?> />
                                </td>
                                <td align="center" v-if="mode=='edit'">
                                  <button type="button" @click.prevent="delTransfers(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="2" align="right"><b>Total</b></td>
                                <td>
                                  <vue-numeric style="float: left;width: 100%;text-align: right;font-weight: bold;" class="form-control text-rata-kanan isi" v-model="totTransfer" v-bind:minus="false" :empty-value="0" separator="." disabled />
                                </td>
                                <td></td>
                              </tr>
                            </table>
                          </div>
                        </div>
                        <hr>
                      </span>
                    </div>
                    <div class="form-group">
                      <table id="s" class="table table-hover table-bordered" width="100%">
                        <thead>
                          <th width="15%">No Account</th>
                          <th width="15%">Jenis Transaksi</th>
                          <!-- <th width="15%">Tipe Transaksi</th> -->
                          <th width="15%">Referensi</th>
                          <th width="15%">Sisa Hutang</th>
                          <th width="15%">Nominal</th>
                          <th width="15%">Keterangan</th>
                          <th width="5%" align="center">Aksi</th>
                        </thead>
                        <tbody>
                          <tr v-for="(dtl, index) of details">
                            <td>{{dtl.kode_coa}}</td>
                            <td>{{dtl.coa}}</td>
                            <!-- <td>{{dtl.tipe_transaksi}}</td> -->
                            <td>{{dtl.referensi}}</td>
                            <td>
                              <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="dtl.sisa_hutang" v-bind:minus="false" :empty-value="0" separator="." readonly />
                            </td>
                            <td>
                              <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="dtl.nominal" v-bind:minus="false" :empty-value="0" separator="." />
                            </td>
                            
                            <td>
                              <input type="text" class="form-control isi_combo" v-model="dtl.keterangan" placeholder="Keterangan">
                            </td>
                            <td align="center" v-if="mode=='edit'">
                              <button type="button" @click.prevent="delDetails(index)" class="btn btn-sm btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                            </td>
                          </tr>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td>
                              <input id="kode_coa" readonly type="text" onclick="showModalCOA()" name="kode_coa" class="form-control isi" placeholder="Kode COA">
                            </td>
                            <td>
                              <input type="text" readonly id="coa" class="form-control isi_combo" onclick="showModalCOA()" placeholder="COA">
                            </td>
                            <!-- <td></td> -->
                            <td>
                              <select class="form-control isi_combo select2" id="referensi" onchange="cek_referensi()">
                              </select>
                            </td>
                            <td>
                              <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="detail.sisa_hutang" v-bind:minus="false" :empty-value="0" separator="." readonly />
                            </td>
                            <td>
                              <vue-numeric style="float: left;width: 100%;text-align: right;" class="form-control text-rata-kanan isi" v-model="detail.nominal" v-bind:minus="false" :empty-value="0" separator="." />
                            </td>
                            <td>
                              <input type="text" class="form-control isi_combo" v-model="detail.keterangan" placeholder="Keterangan">
                            </td>
                            <td align="center" v-if="mode=='edit'">
                              <button type="button" @click.prevent="addDetails" class="btn btn-sm btn-primary btn-flat"><i class="fa fa-plus"></i></button>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="4"><b>Total</b></td>
                            <td>
                              <vue-numeric style="float: left;width: 100%;text-align: right;font-weight: bold;" class="form-control text-rata-kanan isi" v-model="totNominalDetails" v-bind:minus="false" :empty-value="0" separator="." readonly />
                            </td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                    <div id="tampil_rekap"></div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-3">
                    </div>
                    <div class="col-sm-9">
                      <button type="button" id="submitBtn" name="save" value="save" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                      <button type="reset" class="btn btn-default btn-flat"><i class="fa fa-refresh"></i> Cancel</button>
                    </div>
                  </div><!-- /.box-footer -->

                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
        <script>
          Vue.use(VueNumeric.default);
          $(document).ready(function() {
            getRekTujuanEdit();
            getRef();
            getNoBG();
          })
          var form_entri = new Vue({
            el: '#form_entri',
            data: {
              mode: '<?= $mode ?>',
              tipe_customer: '<?= $row->tipe_customer ?>',
              jenis_bayar: '<?= $row->jenis_bayar ?>',
              rekening_tujuan: '<?= $row->rekening_tujuan ?>',
              via_bayar: '<?= $row->via_bayar ?>',
              transfer: {
                tgl_transfer: '',
                nominal_transfer: ''
              },
              bg: {
                no_bg: '',
                tgl_bg: '',
                nominal_bg: ''
              },
              detail: {
                kode_coa: '',
                tipe_transaksi: '',
                coa: '',
                referensi: '',
                sisa_hutang: '',
                nominal: '',
                keterangan: ''
              },
              transfers: <?= isset($transfers) ? json_encode($transfers) : '[]' ?>,
              bg_: <?= isset($bg_) ? json_encode($bg_) : '[]' ?>,
              details: <?= isset($details) ? json_encode($details) : '[]' ?>,
            },

            methods: {
              addTransfer: function() {
                $('.datepicker').datepicker({
                  format: "yyyy-mm-dd",
                  autoclose: true,
                });

                var tgl_transfer = $('#tanggal5').val();
                if (tgl_transfer == '') {
                  alert('Tanggal Transfer Masih Kosong !');
                  return false;
                }

                if (this.transfer.nominal_transfer == 0) {
                  alert('Tentukan nominal transfer !');
                  return false;
                }

                form_entri.transfer.tgl_transfer = tgl_transfer;
                this.transfers.push(this.transfer);
                this.clearTransfer();
              },
              clearTransfer: function() {
                this.transfer = {
                  tgl_transfer: '',
                  nominal_transfer: ''
                }
                $('#tanggal5').val('');
              },
              delTransfers: function(index) {
                this.transfers.splice(index, 1);
              },
              addDetails: function() {
                  if ( this.mode !=='edit'){
                    if (this.detail.referensi == '' || this.detail.kode_coa == '') {
                      alert('Silahkan lengkapi data !');
                      return false
                    }
                  }

                  if (this.detail.kode_coa == '') {
                    alert('Silahkan lengkapi data COA !');
                    return false
                  }

                this.details.push(this.detail);
                this.clearDetails();
              },
              clearDetails: function() {
                this.detail = {
                  tgl_detail: '',
                  nominal_detail: ''
                }
                $('#kode_coa').val('');
                $('#coa').val('');
                $('#tipe_transaksi').val('');
              },
              delDetails: function(index) {
                this.details.splice(index, 1);
              },
              addBg: function() {
                $('.datepicker').datepicker({
                  format: "yyyy-mm-dd",
                  autoclose: true,
                });
                var tgl_bg = $('#tanggal4').val();
                var no_bg = $('#no_bg').val();
                if (tgl_bg == '') {
                  alert('Tanggal BG Masih Kosong !');
                  return false;
                }
                if (this.bg.nominal_bg == 0) {
                  alert('Tentukan nominal BG !');
                  return false;
                }

                form_entri.bg.tgl_bg = tgl_bg;
                form_entri.bg.no_bg = no_bg;
                this.bg_.push(this.bg);
                this.clearBg();
              },
              clearBg: function() {
                this.bg = {
                  no_bg: '',
                  tgl_bg: '',
                  nominal_bg: ''
                };
                $('#tanggal4').val('');
                $('#no_bg').val('');
              },
              delBg: function(index) {
                this.bg_.splice(index, 1);
              },
              // cekKonsumen : function(){
              //   var values ={'nama_konsumen':$('#nama_konsumen').val(),
              //                'no_polisi':$('#no_polisi').val(),
              //                'no_hp':$('#no_hp').val(),
              //               }
              //   $.ajax({
              //     beforeSend: function() {
              //       $('.btnSearch').attr('disabled',true);
              //       form_entri.details = [];
              //       form_entri.kosong='';
              //     },
              //     url:'<?= base_url('dealer/manage_booking/cekKonsumen') ?>',
              //     type:"POST",
              //     data: values,
              //     cache:false,
              //     dataType:'JSON',
              //     success:function(response){
              //       if (response.row>0) {
              //         form_entri.details.push(response.data[0]);
              //         form_entri.kosong='';
              //       }else{
              //         alert('Data tidak ditemukan !. Silahkan cek kembali data yang anda input atau lengkapi form untuk menambah data');
              //         form_entri.kosong=1;
              //       }
              //       $('.btnSearch').attr('disabled',false);
              //     },
              //     error:function(){
              //       alert("Something Went Wrong !");
              //       $('.btnSearch').attr('disabled',false);

              //     },
              //     statusCode: {
              //       500: function() { 
              //         alert('Fail Error 500 !');
              //         $('.btnSearch').attr('disabled',false);

              //       }
              //     }
              //   });
              // },
              // clearDetails: function () {
              //   this.details =[]
              // },
            },
            computed: {
              totTransfer: function() {
                total = 0;
                for (trf of this.transfers) {
                  total += trf.nominal_transfer;
                }
                if (isNaN(total)) return 0;
                // return total.toFixed(1);
                return total;
              },
              totBg: function() {
                total = 0;
                for (bg of this.bg_) {
                  total += bg.nominal_bg;
                }
                if (isNaN(total)) return 0;
                // return total.toFixed(1);
                return total;
              },
              totNominalDetails: function() {

                var total = 0;
                for (dtl of this.details) {
                  if(dtl.kode_coa == '6.01.6013.01' || dtl.kode_coa == '2.01.21063.00' || dtl.kode_coa == '2.01.21064.00' || dtl.kode_coa == '5.01.5107.01' || dtl.kode_coa == '5.01.5107.03' || dtl.kode_coa == '5.01.5107.05' || dtl.kode_coa == '6.01.6013.01' || dtl.kode_coa == '6.02.6010.02' || dtl.kode_coa == '6.03.6010.03' || dtl.kode_coa == '6.03.6011.03'){      
                    total -= dtl.nominal;
                  }else{
                    total += dtl.nominal;
                  }
                  
                  console.log(total); 
                  // ada yg char dan numeric

                }
                if (isNaN(total)) return 0;
                // return total.toFixed(1);
                return total;

                /*
                total = 0;
                
                for (dtl of this.details) {
                  var coa_pph = ['1.01.11202.00',
                    '1.01.11204.00',
                    '1.01.11205.00',
                    '2.01.21062.00',
                    '2.01.21063.00',
                    '2.01.21064.00',
                    '2.01.21065.00',
                    '5.01.5405.01',
                    '5.01.5406.01',
                    '5.01.5407.01',
                    '5.02.5405.02',
                    '5.02.5406.02',
                    '5.02.5407.02',
                    '5.03.5405.03',
                    '5.03.5406.03',
                    '5.03.5407.03',
                    '7.01.7002.01',
                    '7.02.7002.02',
                    '7.03.7002.03'
                  ];

                  var coa_ppn = ['1.01.11201.00',
                    '2.03.21012.02',
                    '2.03.21013.02',
                    '2.00.21061.00',
                    '5.01.5408.01',
                    '5.02.5408.02',
                    '5.03.5408.03'
                  ];

                  var coa_potongan = [
                    '5.01.5107.01',
                    '5.03.5107.03',
                    '5.03.5107.05',
                    '6.01.6013.01',
                    '6.02.6010.02',
                    '6.03.6010.03',
                    '6.03.6011.03'
                  ];

                  if(coa_pph.indexOf(dtl.kode_coa) !== -1){ // pph
                    total -= Math.abs(dtl.nominal);
                    // console.log(dtl.kode_coa);
                  }if(coa_ppn.indexOf(dtl.kode_coa) !== -1){ // ppn
                    total += Math.abs(dtl.nominal);
                    // console.log(dtl.kode_coa);
                  }if(coa_potongan.indexOf(dtl.kode_coa) !== -1){ // potongan atau diskon
                    total -= Math.abs(dtl.nominal);
                    // console.log(dtl.kode_coa);
                  }else{
                    total += Math.abs(dtl.nominal);
                    // console.log(dtl.kode_coa);
                  }

                  if(dtl.kode_coa =='2.01.21064.00' || dtl.kode_coa =='1.01.11201.00' ){ // bugs di coa pph karna gak terpotong / terjumlah 2x
                    total -= Math.abs(dtl.nominal);
                  }
                  console.log(total);
                }
                
          
                if (isNaN(total)) return 0;
                // return total.toFixed(1);
                return total;
                */
              }
            },
          });

          function cek_hutang_edit() {
            var nominal = form_entri.detail.nominal;
            if (jenis_bayar == 'Transfer') {
              var total_pembayaran = form_entri.totTransfer;
            } else {
              var total_pembayaran = form_entri.totBg;
            }
            var hasil = nominal - total_pembayaran;
            hasil = toNumber(hasil);
            // $("#sisa_hutang").val(hasil);     
            form_entri.detail.sisa_hutang = hasil;
          }

          function cek_referensi() {
            var referensi = $("#referensi").val();
            var tipe = $('#referensi option:selected').attr('category');
            var values = {
              referensi: referensi,
              tipe: tipe
            }
            $.ajax({
              url: "<?php echo site_url('h1/voucher_pengeluaran_bank/cari_ref') ?>",
              type: "POST",
              data: values,
              cache: false,
              success: function(msg) {
                data = msg.split("|");
                // $("#nominal").val(data[0]); 
                form_entri.detail.referensi = referensi;
                form_entri.detail.nominal = data[0];
                cek_hutang_edit();
              }
            })
          }


          function cek_referensi_testing() {
            var referensi = $("#referensi").val();
            var tipe = $('#referensi option:selected').attr('category');

            var values = {
              referensi: referensi,
              tipe: tipe
            }
            $.ajax({
              url: "<?php echo site_url('h1/voucher_pengeluaran_bank/cari_ref') ?>",
              type: "POST",
              data: values,
              cache: false,
              success: function(msg) {
                data = msg.split("|");
                // $("#nominal").val(data[0]); 
                form_entri.detail.referensi = referensi;
                form_entri.detail.nominal = data[0];
                // cek_hutang_edit();
              }
            })
          }




          $('#submitBtn').click(function() {
            $('#form_entri').validate({
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
              details: form_entri.details,
              transfers: form_entri.transfers,
              total_pembayaran: form_entri.totNominalDetails,
              bg_: form_entri.bg_,
              deskripsi: form_entri.deskripsi
              //deskripsi: tinymce.get('deskripsi').save()
            };
            var form = $('#form_entri').serializeArray();
            for (field of form) {
              values[field.name] = field.value;
            }

            if ($('#form_entri').valid()) // check if form is valid
            {
              if (form_entri.via_bayar == 'Transfer') {
                if (form_entri.totNominalDetails != form_entri.totTransfer) {
                  alert('Total Pembayaran tidak sama dengan pengeluaran !');
                  return false
                }
              }
              if (form_entri.via_bayar == 'BG') {
                if (form_entri.totNominalDetails != form_entri.totBg) {
                  alert('Total Pembayaran tidak sama dengan pengeluaran !');
                  return false
                }
              }
              $.ajax({
                beforeSend: function() {
                  $('#submitBtn').attr('disabled', true);
                },
                url: '<?= base_url('h1/voucher_pengeluaran_bank/' . $form) ?>',
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
                  $('#submitBtn').attr('disabled', false);
                },
                error: function() {
                  alert("failure");
                  $('#submitBtn').attr('disabled', false);

                },
                statusCode: {
                  500: function() {
                    alert('fail');
                    $('#submitBtn').attr('disabled', false);

                  }
                }
              });
            } else {
              alert('Silahkan isi field required !')
            }
          })

          function getRekTujuanEdit() {
            var tipe_customer = $("#tipe_customer").val();
            var id_dealer = $("#id_dealer").val();
            var id_vendor = $("#id_vendor").val();
            value = {
              tipe_customer: tipe_customer,
              id_dealer: id_dealer,
              id_vendor: id_vendor,
              rekening_tujuan: form_entri.rekening_tujuan
            }
            $.ajax({
              url: "<?php echo site_url('h1/voucher_pengeluaran_bank/getRekTujuanEdit'); ?>",
              type: "POST",
              data: value,
              cache: false,
              success: function(html) {
                $("#jenis_bayar").val('');
                $('#referensi').val('');
                if (tipe_customer == 'Dealer') {
                  $('#rekening_tujuan_d').html(html);
                }
                if (tipe_customer == 'Vendor') {
                  $('#rekening_tujuan_v').html(html);
                }
                if (tipe_customer == 'Lain-lain') {}
              }
            });
          }
        </script>

      <?php
      } elseif ($set == "view") {
      ?>

        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/voucher_pengeluaran_bank/add">
                <button <?php echo $this->m_admin->set_tombol($id_menu, $group, "insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
              </a>
              <a href="h1/voucher_pengeluaran_bank/history">
                <button class="btn btn-warning btn-flat margin"><i class="fa fa-history"></i> History</button>
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
            <table id="table" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>ID Voucher Bank</th>
                  <th>Account</th>
                  <th>Tgl Entry</th>
                  <th>Nama Customer</th>
                  <th>Jenis Bayar</th>
                  <th>PPh</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th width="5%">Action</th>
                </tr>
              </thead>
              <tbody>            
              </tbody>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->

    <?php
      } elseif ($set == "history") {
      ?>

        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h1/voucher_pengeluaran_bank">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-chevron-left"></i> Back</button>
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
            <table id="example4" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th width="5%">No</th>
                  <th>ID Voucher Bank</th>
                  <th>Account</th>
                  <th>Tgl Entry</th>
                  <th>Nama Cusomter</th>
                  <th>Jenis Bayar</th>
                  <th>PPh</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th width="5%">Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- <a href='h1/voucher_pengeluaran_bank/view?id='>$row->id_voucher_bank</a> -->
                <?php
                $no = 1;
                foreach ($dt_voucher->result() as $row) {
                  if ($row->status == 'input') {
                    $status = "<span class='label label-warning'>$row->status</span>";
                  } else {
                    $status = "<span class='label label-danger'>$row->status</span>";
                  }
                  $customer = $row->dibayar;
                  if ($row->tipe_customer == 'Dealer') {
                    $customer = $this->db->get_where('ms_dealer', ['id_dealer' => $row->dibayar]);
                    $customer = $customer->num_rows() > 0 ? $customer->row()->nama_dealer : '';
                  }
                  if ($row->tipe_customer == 'Vendor') {
                    $customer = $this->db->get_where('ms_vendor', ['id_vendor' => $row->dibayar]);
                    $customer = $customer->num_rows() > 0 ? $customer->row()->vendor_name : '';
                  }
                  $dt = $this->db->query("SELECT * FROM tr_voucher_bank_detail WHERE id_voucher_bank = '$row->id_voucher_bank'");
                  $totNominal=0;

                  foreach ($dt->result() as $key) {
                    if($key->kode_coa == '2.01.21062.00' OR $key->kode_coa == '2.01.21063.00' OR $key->kode_coa == '2.01.21064.00' OR $key->kode_coa == '5.01.5107.01' OR $key->kode_coa == '5.01.5107.03' OR $key->kode_coa == '5.01.5107.05' OR $key->kode_coa == '6.01.6013.01' OR $key->kode_coa == '6.02.6010.02' OR $key->kode_coa == '6.03.6010.03' OR $key->kode_coa == '6.03.6011.03'){      
                      $totNominal-= $key->nominal;      
                    }else{  
                      $totNominal+= $key->nominal;
                    }                    
                  }
                  echo "          
            <tr>               
              <td>$no</td>             
              <td>$row->id_voucher_bank</td>                            
              <td>$row->account</td>                            
              <td>$row->tgl_entry</td>                                          
              <td>$customer</td>                                          
              <td>$row->jenis_bayar</td>                                          
              <td>$row->pph</td>                                          
              <td>" . mata_uang2($totNominal) . "</td>                                          
              <td>$status</td>                                          
              <td>
              <a href='h1/voucher_pengeluaran_bank/edit?id=$row->id_voucher_bank' class='btn btn-flat btn-xs btn-warning'>Edit</a>
              <a href='h1/voucher_pengeluaran_bank/cetak?id=$row->id_voucher_bank' class='btn btn-flat btn-xs btn-success'>Cetak</a>";
                  // <a class='btn btn-flat btn-xs btn-primary'>Cetak Voucher</a>
                  echo "<a href='h1/voucher_pengeluaran_bank/batal?id=$row->id_voucher_bank' class='btn btn-flat btn-xs btn-danger'>Batal</a>
              </td>                                          
              ";
                  $no++;
                }
                ?>
              </tbody>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->

      <?php
      }
      ?>
    </section>
  </div>
  <div class="modal fade modal_coa">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">COA</h4>
        </div>
        <div class="modal-body" id="show_detail">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    var table;
      $(document).ready(function() {
        table = $('#table').DataTable({
            "scrollX": false,
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [], //Initial no order.

            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": "<?php echo site_url('h1/voucher_pengeluaran_bank/fetch_data')?>",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
            {
                "targets": [ 0,9 ], //first column / numbering column
                "orderable": false, //set not orderable
            },
            ],
        });
    });
    
    function showModalCOA() {
      $.ajax({
        url: "<?php echo site_url('master/coa/coa_popup'); ?>",
        type: "POST",
        // data:"id_checker="+id_checker,
        cache: false,
        success: function(html) {
          $("#show_detail").html(html);
          $('.modal_coa').modal('show');
          datatables();
        }
      });
    }

    function datatables() {
      $('#datatables').DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        order: [
          [1, "desc"]
        ],
        // fixedHeader:true,         
        // columnDefs: [
        //   { 
        //       "targets": [ 0 ], //first column
        //       "orderable": false, //set not orderable
        //   },
        //   { 
        //       "targets": [ -1 ], //first column
        //       "orderable": false, //set not orderable
        //   },
        // ],            
        autoWidth: true
      });
    }

    function sembunyi() {
      $("#lain_lain").hide();
      $("#vendor").hide();
      $("#dealer").hide();
      $("#tampil_bg").hide();
      $("#tampil_transfer").hide();
      auto();
    }

    function getNoBG() {
      value = {
        account: $('#account').val()
      }
      $.ajax({
        url: "<?php echo site_url('h1/voucher_pengeluaran_bank/cekNoBG'); ?>",
        type: "POST",
        data: value,
        cache: false,
        success: function(html) {
          $('#no_bg').html(html);
        }
      });
    }

    function cek_via() {
      var via_bayar = $("#via_bayar").val();
      if (via_bayar == 'BG') {
        $("#tampil_bg").show();
        $("#tampil_transfer").hide();
        getNoBG();
      } else if (via_bayar == "Transfer") {
        $("#tampil_bg").hide();
        $("#tampil_transfer").show();
      } else {
        $("#tampil_bg").hide();
        $("#tampil_transfer").hide();
      }
    }

    function getRekTujuan() {
      var tipe_customer = $("#tipe_customer").val();
      var id_dealer = $("#id_dealer").val();
      var id_vendor = $("#id_vendor").val();
      value = {
        tipe_customer: tipe_customer,
        id_dealer: id_dealer,
        id_vendor: id_vendor,
      }
      $.ajax({
        url: "<?php echo site_url('h1/voucher_pengeluaran_bank/getRekTujuan'); ?>",
        type: "POST",
        data: value,
        cache: false,
        success: function(html) {
          $("#jenis_bayar").val('');
          $('#referensi').val('');
          if (tipe_customer == 'Dealer') {
            $('#rekening_tujuan_d').html(html);
          }
          if (tipe_customer == 'Vendor') {
            $('#rekening_tujuan_v').html(html);
          }
          if (tipe_customer == 'Lain-lain') {}
        }
      });
    }

    function getRekap() {
      var tipe_customer = $("#tipe_customer").val();
      var id_dealer = $("#id_dealer").val();
      var id_vendor = $("#id_vendor").val();
      var jenis_bayar = $("#jenis_bayar").val();
      value = {
        tipe_customer: tipe_customer,
        id_dealer: id_dealer,
        id_vendor: id_vendor,
        jenis_bayar: jenis_bayar,
      }
      $.ajax({
        url: "<?php echo site_url('h1/voucher_pengeluaran_bank/getRekap'); ?>",
        type: "POST",
        data: value,
        cache: false,
        success: function(html) {
          $('#tampil_rekap').html(html);
        }
      });
    }

    function getRef() {
      var tipe_customer = $("#tipe_customer").val();
      var id_dealer = $("#id_dealer").val();
      var id_vendor = $("#id_vendor").val();
      var jenis_bayar = $("#jenis_bayar").val();
      value = {
        tipe_customer: tipe_customer,
        id_dealer: id_dealer,
        id_vendor: id_vendor,
        jenis_bayar: jenis_bayar,
      }
      $.ajax({
        url: "<?php echo site_url('h1/voucher_pengeluaran_bank/getRef'); ?>",
        type: "POST",
        data: value,
        cache: false,
        success: function(html) {
          $('#referensi').html(html);
          if (jenis_bayar == 'Ekspedisi Unit') {
            getRekap();
          } else {
            $('#tampil_rekap').html('');
          }
        }
      });
    }

    function cek_tipe() {
      var tipe = $("#tipe_customer").val();
      $("#jenis_bayar").val('');
      $('#referensi').val('');
      if (tipe == 'Dealer') {
        $("#dealer").show();
        $("#lain_lain").hide();
        $("#vendor").hide();
        $('#rek_tujuan_d').show();
        $('#rek_tujuan_v').hide();
      } else if (tipe == "Vendor") {
        $("#vendor").show();
        $("#dealer").hide();
        $("#lain_lain").hide();
        $('#rek_tujuan_d').hide();
        $('#rek_tujuan_v').show();
        $('#rek_tujuan_l').hide();
      } else if (tipe == "Lain-lain") {
        $("#lain_lain").show();
        $("#vendor").hide();
        $("#dealer").hide();
        $('#rek_tujuan_d').hide();
        $('#rek_tujuan_v').hide();
        $('#rek_tujuan_l').show();
      } else {
        $("#lain_lain").hide();
        $("#vendor").hide();
        $("#dealer").hide();
        $('#rek_tujuan_d').hide();
        $('#rek_tujuan_v').hide();
        $('#rek_tujuan_l').hide();
      }
    }

    function auto() {
      var id = 1;
      $.ajax({
        url: "<?php echo site_url('h1/voucher_pengeluaran_bank/cari_id') ?>",
        type: "POST",
        data: "id=" + id,
        cache: false,
        success: function(msg) {
          data = msg.split("|");
          $("#id_voucher_bank").val(data[0]);
          sum();
          kirim_detail();
        }
      })
    }

    function sum() {
      var id_voucher_bank = $("#id_voucher_bank").val();
      $.ajax({
        url: "<?php echo site_url('h1/voucher_pengeluaran_bank/cari_total') ?>",
        type: "POST",
        data: "id_voucher_bank=" + id_voucher_bank,
        cache: false,
        success: function(msg) {
          data = msg.split("|");
          $("#total_pembayaran").val(data[0]);
          $("#total_pembayaran_real").val(convertToRupiah(data[0]));
        }
      })
    }

    function cek_coa() {
      var kode_coa = $("#kode_coa").val();
      $.ajax({
        url: "<?php echo site_url('h1/voucher_pengeluaran_bank/cari_coa') ?>",
        type: "POST",
        data: "kode_coa=" + kode_coa,
        cache: false,
        success: function(msg) {
          data = msg.split("|");
          $("#coa").val(data[0]);
        }
      })
    }

    function cek_hutang() {
      var nominal = $("#nominal").unmask();
      var total_pembayaran = $("#total_pembayaran").val();
      var hasil = nominal - total_pembayaran;
      hasil = toNumber(hasil);
      $("#sisa_hutang").val(hasil);
    }

    function cek_ref() {
      var referensi = $("#referensi").val();
      var tipe = $('#referensi option:selected').attr('category');
      var values = {
        referensi: referensi,
        tipe: tipe
      }
      $.ajax({
        url: "<?php echo site_url('h1/voucher_pengeluaran_bank/cari_ref') ?>",
        type: "POST",
        data: values,
        cache: false,
        success: function(msg) {
          data = msg.split("|");
          $("#nominal").val(data[0]);
          cek_hutang();
        }
      })
    }

    function hide_bg() {
      $("#tampil_bg_isi").hide();
    }

    function kirim_data_bg() {
      $("#tampil_bg_isi").show();
      var id_voucher_bank = document.getElementById("id_voucher_bank").value;
      var xhr;
      if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        xhr = new XMLHttpRequest();
      } else if (window.ActiveXObject) { // IE 8 and older
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
      }
      //var data = "birthday1="+birthday1_js;          
      var data = "id_voucher_bank=" + id_voucher_bank;
      xhr.open("POST", "h1/voucher_pengeluaran_bank/t_bg", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send(data);
      xhr.onreadystatechange = display_data;

      function display_data() {
        if (xhr.readyState == 4) {
          if (xhr.status == 200) {
            document.getElementById("tampil_bg_isi").innerHTML = xhr.responseText;
          } else {
            alert('There was a problem with the request.');
          }
        }
      }
    }

    function simpan_bg() {
      var id_voucher_bank = $("#id_voucher_bank").val();
      var no_bg = $("#no_bg").val();
      var tgl_bg = $("#tanggal4").val();
      var nominal_bg = $("#nominal_bg").val();
      //alert(id_dealer);
      if (no_bg == "" || id_voucher_bank == "") {
        alert("Isikan data dengan lengkap...!");
        return false;
      } else {
        $.ajax({
          url: "<?php echo site_url('h1/voucher_pengeluaran_bank/save_bg') ?>",
          type: "POST",
          data: "id_voucher_bank=" + id_voucher_bank + "&no_bg=" + no_bg + "&tgl_bg=" + tgl_bg + "&nominal_bg=" + nominal_bg,
          cache: false,
          success: function(msg) {
            data = msg.split("|");
            if (data[0] == "nihil") {
              kirim_data_bg();
              sum();
              kosong();
            } else {
              alert('No BG ini sudah ditambahkan');
              kosong();
            }
          }
        })
      }
    }

    function kosong(args) {
      $("#no_bg").val("");
      $("#tanggal4").val("");
      $("#nominal_bg").val("");
    }

    function hapus_bg(a) {
      var id_voucher_bank_bg = a;
      $.ajax({
        url: "<?php echo site_url('h1/voucher_pengeluaran_bank/delete_bg') ?>",
        type: "POST",
        data: "id_voucher_bank_bg=" + id_voucher_bank_bg,
        cache: false,
        success: function(msg) {
          data = msg.split("|");
          if (data[0] == "nihil") {
            kirim_data_bg();
            sum();
          }
        }
      })
    }


    function hide_transfer() {
      $("#tampil_transfer_isi").hide();
    }

    function kirim_data_transfer() {
      $("#tampil_transfer_isi").show();
      var id_voucher_bank = document.getElementById("id_voucher_bank").value;
      var xhr;
      if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        xhr = new XMLHttpRequest();
      } else if (window.ActiveXObject) { // IE 8 and older
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
      }
      //var data = "birthday1="+birthday1_js;          
      var data = "id_voucher_bank=" + id_voucher_bank;
      xhr.open("POST", "h1/voucher_pengeluaran_bank/t_transfer", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send(data);
      xhr.onreadystatechange = display_data;

      function display_data() {
        if (xhr.readyState == 4) {
          if (xhr.status == 200) {
            document.getElementById("tampil_transfer_isi").innerHTML = xhr.responseText;
          } else {
            alert('There was a problem with the request.');
          }
        }
      }
    }

    function simpan_transfer() {
      var id_voucher_bank = $("#id_voucher_bank").val();
      var tgl_transfer = $("#tanggal5").val();
      var nominal_transfer = $("#nominal_transfer").val();
      //alert(id_dealer);
      if (tgl_transfer == "" || id_voucher_bank == "") {
        alert("Isikan data dengan lengkap...!");
        return false;
      } else {
        $.ajax({
          url: "<?php echo site_url('h1/voucher_pengeluaran_bank/save_transfer') ?>",
          type: "POST",
          data: "id_voucher_bank=" + id_voucher_bank + "&tgl_transfer=" + tgl_transfer + "&nominal_transfer=" + nominal_transfer,
          cache: false,
          success: function(msg) {
            data = msg.split("|");
            if (data[0] == "nihil") {
              kirim_data_transfer();
              sum();
              kosong2();
            }
          }
        })
      }
    }

    function kosong2(args) {
      $("#tanggal5").val("");
      $("#nominal_transfer").val("");
    }

    function hapus_transfer(a) {
      var id_voucher_bank_transfer = a;
      $.ajax({
        url: "<?php echo site_url('h1/voucher_pengeluaran_bank/delete_transfer') ?>",
        type: "POST",
        data: "id_voucher_bank_transfer=" + id_voucher_bank_transfer,
        cache: false,
        success: function(msg) {
          data = msg.split("|");
          if (data[0] == "nihil") {
            kirim_data_transfer();
            sum();
          } else {
            alert(0);
          }
        }
      })
    }

    
    
    function kirim_detail() {

      $("#tampil_detail").show();
      var id_voucher_bank = document.getElementById("id_voucher_bank").value;
      // alert(id_voucher_bank);
      var xhr;
      if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        xhr = new XMLHttpRequest();
      } else if (window.ActiveXObject) { // IE 8 and older
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
      }
      //var data = "birthday1="+birthday1_js;          
      var data = "id_voucher_bank=" + id_voucher_bank;
      xhr.open("POST", "h1/voucher_pengeluaran_bank/t_detail", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.send(data);
      xhr.onreadystatechange = display_data;

      function display_data() {
        if (xhr.readyState == 4) {
          if (xhr.status == 200) {
            getRef();
            document.getElementById("tampil_detail").innerHTML = xhr.responseText;
            priceformat();
          } else {
            alert('There was a problem with the request.');
          }
        }
      }

    }


    function readonly_select(objs, action) {
      if (action === true)
        objs.prepend('<div class="disabled-select"></div>');
      else
        $(".disabled-select", objs).remove();
    }

    function simpan_detail() {
      $('#id_dealer').attr("readonly", "readonly");
      $('#id_vendor').attr("readonly", "readonly");
      $('#dibayar_l').attr('readonly', true);
      $('#tipe_customer').attr('readonly', true);
      $("#tipe_customer").css("pointer-events", "none");

      var id_voucher_bank = $("#id_voucher_bank").val();
      var kode_coa = $("#kode_coa").val();
      var coa = $("#coa").val();
      var referensi = $("#referensi").val();
      var sisa_hutang = $("#sisa_hutang").val();
      var nominal = $("#nominal").unmask();
      var keterangan = $("#keterangan").val();
      //alert(id_dealer);
      if (kode_coa == "" || id_voucher_bank == "") {
        alert("Isikan data dengan lengkap...!");
        return false;
      } else {
        $.ajax({
          url: "<?php echo site_url('h1/voucher_pengeluaran_bank/save_detail') ?>",
          type: "POST",
          data: "id_voucher_bank=" + id_voucher_bank + "&kode_coa=" + kode_coa + "&coa=" + coa + "&referensi=" + referensi + "&nominal=" + nominal + "&sisa_hutang=" + sisa_hutang + "&keterangan=" + keterangan,
          cache: false,
          success: function(msg) {
            data = msg.split("|");
            if (data[0] == "nihil") {
              kirim_detail();
              kosong3();
            }
          }
        })
      }
    }

    function kosong3(args) {
      $("#kode_coa").val("");
      $("#coa").val("");
      $("#referensi").val("");
      $("#nominal").val("");
      $("#sisa_hutang").val("");
      $("#keterangan").val("");
    }

    function hapus_detail(a) {
      var id_voucher_bank_detail = a;
      $.ajax({
        url: "<?php echo site_url('h1/voucher_pengeluaran_bank/delete_detail') ?>",
        type: "POST",
        data: "id_voucher_bank_detail=" + id_voucher_bank_detail,
        cache: false,
        success: function(msg) {
          data = msg.split("|");
          if (data[0] == "nihil") {
            kirim_detail();
          }
        }
      })
    }


    function rupiah(e) {
      sisa_hutang = $('#sisa_hutang').val();
      val = $(e).val(); //for clearing with Jquery 
      console.log(sisa_hutang);
      val_numb = parseInt(toNumber(val));
      console.log(val_numb);
      if (parseInt(sisa_hutang) < val_numb) {
        alert('Nominal lebih besar dari Sisa Hutang !');
        $(e).val(formatRupiah(sisa_hutang));
        return false;
      }
      // if (isNaN(val)){
      //   new_val=0;
      // }else{
      //   new_val=val;
      // }
      $(e).val(val);
    }
  </script>