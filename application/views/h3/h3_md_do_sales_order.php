<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" />

<body>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><?= $title; ?></h1>
      <?= $breadcrumb ?>
    </section>
    <section class="content">
      <?php if ($set == 'form') : ?>
        <?php
        $form     = '';
        $disabled = '';
        $readonly = '';
        if ($mode == 'insert') {
          $form = 'save';
        }
        if ($mode == 'detail') {
          $disabled = 'disabled';
          $form = 'save_do';
        }
        if ($mode == 'edit') {
          $form = 'update';
        }
        ?>
        <div id='app' class="box box-default">
          <div class="box-header with-border">
            <h3 class="box-title">
              <a href="h3/<?= $isi ?>">
                <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
              </a>
            </h3>
          </div><!-- /.box-header -->
          <div v-if="loading" class="overlay">
            <i class="fa fa-refresh fa-spin text-light-blue"></i>
          </div>
          <div class="box-body">
            <?php $this->load->view('template/session_message.php'); ?>
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal">
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Tanggal DO</label>
                      <div class="col-sm-4">
                        <input v-if='do_sales_order.tanggal_do != "" && do_sales_order.tanggal_do != null' type="text" readonly class="form-control" :value='moment(do_sales_order.tanggal_do).format("DD/MM/YYYY")' />
                      </div>
                      <label class="col-sm-2 control-label">Nama Customer</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" v-model='do_sales_order.nama_dealer'>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Nomor DO</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" v-model='do_sales_order.id_do_sales_order'>
                      </div>
                      <label class="col-sm-2 control-label">Kode Customer</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" v-model='do_sales_order.kode_dealer_md'>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Tanggal SO</label>
                      <div class="col-sm-4">
                        <input v-if='do_sales_order.tanggal_so != "" && do_sales_order.tanggal_so != null' type="text" readonly class="form-control" :value='moment(do_sales_order.tanggal_so).format("DD/MM/YYYY")' />
                      </div>
                      <label class="col-sm-2 control-label">Alamat Customer</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" v-model='do_sales_order.alamat'>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Nomor SO</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" v-model='do_sales_order.id_sales_order'>
                      </div>
                      <label class="col-sm-2 control-label">Plafon</label>
                      <div class="col-sm-4">
                        <vue-numeric readonly class="form-control" currency='Rp' separator='.' v-model='do_sales_order.plafon'></vue-numeric>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">TOP</label>
                      <div class="col-sm-4">
                        <input v-if='do_sales_order.top != "" && do_sales_order.top != null' type="text" readonly class="form-control" :value='moment(do_sales_order.top).format("DD/MM/YYYY")' />
                      </div>
                      <label class="col-sm-2 control-label">Plafon Booking</label>
                      <div class="col-sm-4">
                        <vue-numeric readonly class="form-control" currency='Rp' separator='.' v-model='do_sales_order.plafon_booking'></vue-numeric>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Name Salesman</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" v-model='do_sales_order.nama_salesman'>
                      </div>

                      <label class="col-sm-2 control-label">Sisa Plafon</label>
                      <div class="col-sm-4">
                        <vue-numeric readonly class="form-control" currency='Rp' separator='.' v-model='sisa_plafon'></vue-numeric>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Tipe PO</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" v-model='do_sales_order.po_type'>
                      </div>
                      <label class="col-sm-2 control-label">Kategori PO</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" v-model='do_sales_order.kategori_po'>
                      </div>
                    </div>
                    <div v-if='do_sales_order.gimmick == 1' class="form-group">
                      <label class="col-sm-2 control-label">Gimmick</label>
                      <div class="col-sm-4">
                        <input v-if='do_sales_order.gimmick == 1' type="text" readonly class="form-control" value='Yes'>
                        <input v-if='do_sales_order.gimmick == 0' type="text" readonly class="form-control" value='No'>
                      </div>
                      <label class="col-sm-2 control-label">Nama Campaign</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" :value='do_sales_order.nama_campaign + " (" + do_sales_order.kode_campaign + ")"'>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Status</label>
                      <div class="col-sm-4">
                        <input type="text" readonly class="form-control" v-model='do_sales_order.status'>
                      </div>
                      <div v-if='do_sales_order.status == "Rejected"'>
                        <label class="col-sm-2 control-label">Alasan Reject</label>
                        <div class="col-sm-4">
                          <input type="text" readonly class="form-control" v-model='do_sales_order.alasan_reject'>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12">
                        <table id="table" class="table table-condensed table-responsive">
                          <thead>
                            <tr class='bg-blue-gradient'>
                              <th width='3%'>No.</th>
                              <th>Part Number</th>
                              <th>Nama Part</th>
                              <th v-if='kategori_kpb'>Tipe Kendaraan</th>
                              <th>HET</th>
                              <th>Qty</th>
                              <th width='10%' class='text-right'>Diskon Satuan Dealer</th>
                              <th width='10%' class='text-right'>Diskon Campaign</th>
                              <th width='10%' class='text-right'>Harga Setelah Diskon</th>
                              <th width='10%' class='text-right'>Amount</th>
                              <th width='10%' class='text-right'>Harga Beli</th>
                              <th width='10%' class='text-right'>Selisih</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(part, index) in parts">
                              <td class="align-top">{{ index + 1 }}.</td>
                              <td class="align-top">{{ part.id_part }}</td>
                              <td class="align-top">{{ part.nama_part }}</td>
                              <td v-if='kategori_kpb' class="align-top">{{ part.id_tipe_kendaraan }}</td>
                              <td class="align-top">
                                <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.harga_jual" />
                              </td>
                              <td class="align-top">
                                <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.qty_supply" />
                              </td>
                              <td class="align-top text-right">
                                <vue-numeric currency='Rp' read-only class="form-control" separator="." :empty-value="1" :value='convert_diskon_ke_rupiah(part.tipe_diskon_satuan_dealer, part.diskon_satuan_dealer, part.harga_jual)' />
                              </td>
                              <td class="align-top text-right">
                                <vue-numeric currency='Rp' read-only class="form-control" separator="." :empty-value="1" :value='convert_diskon_ke_rupiah(part.tipe_diskon_campaign, part.diskon_campaign, part.harga_jual)' />
                              </td>
                              <td class="align-top text-right">
                                <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="harga_setelah_diskon(part)" />
                              </td>
                              <td class="align-top text-right">
                                <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="amount(part)" />
                              </td>
                              <td class="align-top text-right">
                                <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="part.harga_beli" />
                              </td>
                              <td class="align-top text-right">
                                <vue-numeric :read-only="mode == 'detail'" class="form-control" separator="." :empty-value="1" v-model="harga_setelah_diskon(part) - part.harga_beli" />
                              </td>
                            </tr>
                            <tr v-if="parts.length > 0">
                              <td class="text-right" :colspan="sub_total_colspan">Sub Total</td>
                              <td class="text-right" colspan='3'>
                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="sub_total" currency='Rp' />
                              </td>
                            </tr>
                            <tr v-if="parts.length > 0 && (do_sales_order.produk == 'Tools' || do_sales_order.kategori_po == 'KPB')">
                              <td :colspan='ppn_tools_colspan'></td>
                              <td class='text-right align-middle'>
                                <input v-if='do_sales_order.status == "On Process"' type="checkbox" true-value='1' false-value='0' v-model='do_sales_order.check_ppn_tools'>
                              </td>
                              <td class="text-right align-middle" colspan="1" v-if="do_sales_order.produk == 'Tools'">PPN Tools</td>
                              <td class="text-right align-middle" colspan="1" v-if="do_sales_order.kategori_po == 'KPB'">PPN</td>
                              <td v-if="do_sales_order.check_ppn_tools == 1" class="text-right align-middle" colspan='3'>
                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="ppn_tools" currency='Rp' />
                              </td>
                              <td v-else class="text-right align-middle" colspan='3'>
                                0
                              </td>
                            </tr>
                            <tr v-if="parts.length > 0">
                              <td v-if="do_sales_order.kategori_po == 'KPB'" colspan='8'></td>
                              <td v-else colspan='7'></td>
                              <td class="text-right align-middle" colspan="1">Insentif Langsung</td>
                              <td class="text-right align-middle" colspan='3'>
                                <vue-numeric read-only class="form-control" separator="." v-model="insentif_langsung" currency='Rp' />
                              </td>
                            </tr>
                            <tr v-if="parts.length > 0">
                              <td colspan='2' class='align-middle'>Total Insentif</td>
                              <td class='align-middle'>
                                <vue-numeric :read-only="true" v-model='do_sales_order.insentif_dealer' class="form-control" separator="." currency='Rp' />
                              </td>
                              <td :colspan='insentif_colspan'></td>
                              <td class='text-right align-middle'>
                                <input v-if='do_sales_order.status == "On Process"' type="checkbox" true-value='1' false-value='0' v-model='do_sales_order.check_diskon_insentif'>
                              </td>
                              <td class="text-right align-middle" colspan="1">Diskon Insentif</td>
                              <td class="text-right align-middle" colspan='3'>
                                <vue-numeric :read-only="do_sales_order.check_diskon_insentif == 0 || do_sales_order.status != 'On Process'" class="form-control" separator="." v-model="do_sales_order.diskon_insentif" currency='Rp' />
                              </td>
                            </tr>
                            <tr v-if="parts.length > 0">
                              <td :colspan='cashback_langsung_colspan'></td>
                              <td class="text-right align-middle" colspan="1">Cashback Langsung</td>
                              <td class="text-right align-middle" colspan='3'>
                                <vue-numeric read-only class="form-control" separator="." v-model="do_sales_order.diskon_cashback_otomatis" currency='Rp' />
                              </td>
                            </tr>
                            <tr v-if="parts.length > 0">
                              <td :colspan='diskon_cashback_colspan'></td>
                              <td class='text-right align-middle'>
                                <input v-if='do_sales_order.status == "On Process"' type="checkbox" true-value='1' false-value='0' v-model='do_sales_order.check_diskon_cashback'>
                              </td>
                              <td class="text-right align-middle" colspan="1">Diskon Cashback</td>
                              <td class="text-right align-middle" colspan='3'>
                                <vue-numeric :read-only="do_sales_order.check_diskon_cashback == 0 || do_sales_order.status != 'On Process'" class="form-control" separator="." v-model="do_sales_order.diskon_cashback" currency='Rp' />
                              </td>
                            </tr>
                            <!-- <tr v-if="parts.length > 0">
                          <td :colspan='total_colspan'></td>
                          <td class="text-right align-middle" colspan="1">Total</td>
                          <td class="text-right align-middle" colspan='3'>
                            <vue-numeric :read-only="true" class="form-control" separator="." v-model="total" currency='Rp'/>
                          </td>
                        </tr> -->
                            <tr v-if="parts.length > 0 && do_sales_order.check_ppn_tools == 1">
                              <td :colspan='total_colspan'></td>
                              <td class="text-right align-middle" colspan="1">Total</td>
                              <td class="text-right align-middle" colspan='3'>
                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="total_tambah_ppn" currency='Rp' />
                              </td>
                            </tr>
                            <tr v-if="parts.length > 0 && do_sales_order.check_ppn_tools != 1">
                              <td :colspan='total_colspan'></td>
                              <td class="text-right align-middle" colspan="1">Total</td>
                              <td class="text-right align-middle" colspan='3'>
                                <vue-numeric :read-only="true" class="form-control" separator="." v-model="total" currency='Rp' />
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="alert alert-warning" v-if="(do_sales_order.produk == 'Tools' || do_sales_order.kategori_po == 'KPB') && do_sales_order.check_ppn_tools != 1">
                      <strong>PPN Belum Dichecklist!</strong>
                    </div>
                    <div class="row">
                      <div class="col-sm-12 text-center no-padding">
                        <button v-if='do_sales_order.status == "Draft" || do_sales_order.status == "On Process" || do_sales_order.status == "Revisi"' :disabled='total_minus || claim_diskon_insentif_melebihi_insentif_tersedia' @click.prevent='approve' type="submit" class="btn btn-success btn-sm btn-flat">Approve</button>
                        <!-- <button v-if='do_sales_order.status == "Draft" || do_sales_order.status == "On Process" || do_sales_order.status == "Revisi"' @click.prevent='cancel' type="submit" class="btn btn-danger btn-sm btn-flat">Cancel</button> -->
                        <button v-if='do_sales_order.status == "Draft" || do_sales_order.status == "On Process" || do_sales_order.status == "Revisi"' data-toggle='modal' data-target='#cancel_modal' type="button" class="btn btn-danger btn-sm btn-flat">Cancel</button>
                        <button v-if='do_sales_order.status == "Draft" || do_sales_order.status == "On Process" || do_sales_order.status == "Revisi"' :disabled='total_minus || claim_diskon_insentif_melebihi_insentif_tersedia' data-toggle='modal' data-target='#reject_modal' type="button" class="btn btn-danger btn-sm btn-flat">Reject</button>
                        <div id="reject_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                  <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title text-left" id="myModalLabel">Alasan Reject</h4>
                              </div>
                              <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="pw_reject" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-sm-12">
                                    <textarea class="form-control" id="alasan_reject" placeholder="Alasan Reject"></textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-sm-12">
                                    <button @click.prevent='reject' class="btn btn-flat btn-sm btn-primary">Submit</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div id="cancel_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                  <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title text-left" id="myModalLabel">Alasan Cancel</h4>
                              </div>
                              <div class="modal-body">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="pw_cancel" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-sm-12">
                                    <textarea class="form-control" id="alasan_cancel" placeholder="Alasan Cancel"></textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-sm-12">
                                    <button @click.prevent='cancel' class="btn btn-flat btn-sm btn-primary">Submit</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <table style='margin-top: 20px;' class="table table-compact">
                      <tr class='bg-blue-gradient'>
                        <td>No.</td>
                        <td>No </td>
                        <td>Tanggal Faktur</td>
                        <td>Tanggal Jatuh Tempo</td>
                        <td class='text-right'>Nominal</td>
                        <td class='text-center'>Status Pembayaran</td>
                      </tr>
                      <tr v-if='monitoring_piutang.length > 0' v-for='(piutang, index) of monitoring_piutang'>
                        <td>{{ index + 1 }}.</td>
                        <td>{{ piutang.referensi }}</td>
                        <td>{{ piutang.tanggal_transaksi }}</td>
                        <td>{{ piutang.tanggal_jatuh_tempo }}</td>
                        <td class='text-right'>
                          <vue-numeric read-only v-model='piutang.sisa_piutang' currency='Rp' separator='.'></vue-numeric>
                        </td>
                        <td @click.prevent='open_status_pembayaran(piutang.referensi)'>
                          <ul v-if='piutang.list_bg.length > 0' class='no-margin'>
                            <li v-for='bg of piutang.list_bg'>{{ bg.nomor_bg }}</li>
                          </ul>
                        </td>
                      </tr>
                      <tr v-if='monitoring_piutang.length > 0'>
                        <td colspan='4' class='text-right'>Total</td>
                        <td class='text-right'>
                          <vue-numeric read-only v-model='total_sisa_piutang' currency='Rp' separator='.'></vue-numeric>
                        </td>
                        <td></td>
                      </tr>
                      <tr v-if='monitoring_piutang.length < 1'>
                        <td class='text-center' colspan='6'>Tidak ada data</td>
                      </tr>
                    </table>
                    <?php $this->load->view('modal/h3_md_open_status_pembayaran_piutang_pada_do'); ?>
                    <table style='margin-top: 20px;' class="table table-compact">
                      <tr class='bg-blue-gradient'>
                        <td width='3%'>No.</td>
                        <td>No. Campaign</td>
                        <td>Nama</td>
                        <td>Cashback</td>
                      </tr>
                      <tr v-if='do_cashback.length > 0' v-for='(row, index) of do_cashback'>
                        <td>{{ index + 1 }}.</td>
                        <td>{{ row.kode_campaign }}</td>
                        <td>{{ row.nama }}</td>
                        <td>
                          <vue-numeric read-only v-model='row.cashback' separator='.'></vue-numeric>
                        </td>
                      </tr>
                      <tr v-if='do_cashback.length < 1'>
                        <td class='text-center' colspan='5'>Tidak ada data</td>
                      </tr>
                    </table>
                    <table style='margin-top: 20px;' class="table table-compact">
                      <tr class='bg-blue-gradient'>
                        <td width='3%'>No.</td>
                        <td>No. Campaign</td>
                        <td>Nama</td>
                        <td>Poin</td>
                        <td>Insentif</td>
                      </tr>
                      <tr v-if='do_poin.length > 0' v-for='(row, index) of do_poin'>
                        <td>{{ index + 1 }}.</td>
                        <td>{{ row.kode_campaign }}</td>
                        <td>{{ row.nama }}</td>
                        <td>
                          <vue-numeric read-only v-model='row.poin' separator='.'></vue-numeric>
                        </td>
                        <td>
                          <vue-numeric read-only v-model='row.nilai_insentif' separator='.' currency='Rp'></vue-numeric>
                        </td>
                      </tr>
                      <tr v-if='do_poin.length < 1'>
                        <td class='text-center' colspan='5'>Tidak ada data</td>
                      </tr>
                    </table>
                    <table style='margin-top: 20px;' class="table table-compact">
                      <tr class='bg-blue-gradient'>
                        <td width='3%'>No.</td>
                        <td>No. Campaign</td>
                        <td>Nama</td>
                        <td>Hadiah Part</td>
                        <td>Kuantitas Hadiah</td>
                        <td>No. SO</td>
                        <td>Status SO</td>
                      </tr>
                      <tr v-if='do_gimmick.length > 0' v-for='(row, index) of do_gimmick'>
                        <td>{{ index + 1 }}.</td>
                        <td>{{ row.kode_campaign }}</td>
                        <td>{{ row.nama }}</td>
                        <td>{{ row.id_part }}</td>
                        <td>
                          <vue-numeric read-only v-model='row.qty_hadiah' separator='.'></vue-numeric>
                        </td>
                        <td>
                          <span v-if='row.id_sales_order != null'>{{ row.id_sales_order }}</span>
                          <span v-if='row.id_sales_order == null'>-</span>
                        </td>
                        <td>
                          <span v-if='row.id_sales_order != null'>{{ row.status_so }}</span>
                          <span v-if='row.id_sales_order == null'>-</span>
                        </td>
                      </tr>
                      <tr v-if='do_gimmick.length < 1'>
                        <td class='text-center' colspan='7'>Tidak ada data</td>
                      </tr>
                    </table>
                  </div><!-- /.box-body -->
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
        <script>
          var app = new Vue({
            el: '#app',
            data: {
              loading: false,
              mode: '<?= $mode ?>',
              <?php if ($mode == 'detail' or $mode == 'edit') : ?>
                do_sales_order: <?= json_encode($do_sales_order) ?>,
                parts: <?= json_encode($do_sales_order_parts) ?>,
                monitoring_piutang: <?= json_encode($monitoring_piutang) ?>,
                do_cashback: <?= json_encode($do_cashback) ?>,
                do_gimmick: <?= json_encode($do_gimmick) ?>,
                do_poin: <?= json_encode($do_poin) ?>,
                ppn: <?= json_encode($ppn) ?>,
              <?php else : ?>
                do_sales_order: {},
                parts: [],
                monitoring_piutang: [],
              <?php endif; ?>
            },
            methods: {
              approve: function(status) {
                this.loading = true;
                post = {};
                post = _.pick(this.do_sales_order, [
                  'id_do_sales_order', 'check_diskon_insentif', 'diskon_insentif',
                  'check_diskon_cashback', 'diskon_cashback', 'id_dealer', 'top',
                  'id_salesman', 'id_dealer', 'check_ppn_tools'
                ]);
                if (post.check_ppn_tools == 1) {
                  post.total = Math.round(this.total_tambah_ppn);
                  post.total_ppn = Math.round(this.ppn_tools);
                } else {
                  post.total = Math.round(this.total);
                  post.total_ppn = this.total_ppn;
                }
                // post.total = this.total;
                // post.total_ppn = this.total_ppn;
                post.sub_total = this.sub_total;

                harga_setelah_diskon_fn = this.harga_setelah_diskon;
                post.parts = _.map(this.parts, function(part) {
                  keys = ['id_part_int', 'id_part', 'qty_supply'];

                  if (app.kategori_kpb) {
                    keys.push('id_tipe_kendaraan');
                  }
                  data = _.pick(part, keys);
                  data.harga_setelah_diskon = harga_setelah_diskon_fn(part);
                  return data;
                });

                axios.post("h3/h3_md_do_sales_order/approve", Qs.stringify(post))
                  .then(function(res) {
                    data = res.data;

                    if (data.redirect_url != null) {
                      window.location = data.redirect_url;
                    }
                  })
                  .catch(function(err) {
                    data = err.response.data;
                    if (data.error_type == 'part_for_picking_list_not_available') {
                      toastr.error(data.message);
                    } else if (data.error_type == 'insentif_tidak_cukup') {
                      toastr.warning(data.message);
                    } else {
                      toastr.error(err);
                    }
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
              reject: function(status) {
                this.loading = true;
                post = _.pick(this.do_sales_order, ['id_do_sales_order', 'id_sales_order']);
                post.alasan_reject = $('#alasan_reject').val();
                post.pw_reject = $('#pw_reject').val();
                // post.total = this.total;

                if (post.check_ppn_tools == 1) {
                  post.total = Math.round(this.total_tambah_ppn);
                } else {
                  post.total = Math.round(this.total);
                }

                axios.post("h3/h3_md_do_sales_order/reject", Qs.stringify(post))
                  .then(function(res) {
                    window.location = 'h3/h3_md_do_sales_order/detail?id=' + res.data.id_do_sales_order;
                  })
                  .catch(function(err) {
                    // toastr.error(err);
                    data = err.response.data;
                    if (data.status == 'gagal') {
                      app.errors = data.errors;
                      toastr.error(data.message);
                    } else {
                      toastr.error(data.message);
                    }
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
              cancel: function(status) {
                confirmed = confirm('Apakah anda yakin?');
                if (!confirmed) return;

                this.loading = true;
                axios.get('h3/h3_md_do_sales_order/cancel', {
                    params: {
                      id_do_sales_order: this.do_sales_order.id_do_sales_order,
                      alasan_cancel : $('#alasan_cancel').val(),
                      pw_cancel : $('#pw_cancel').val()
                    }
                  })
                  .then(function(res) {
                    data = res.data;
                    if (data.redirect_url != null) {
                      window.location = data.redirect_url;
                    }
                  })
                  .catch(function(err) {
                    // toastr.error(err);
                    data = err.response.data;
                    if (data.status == 'gagal') {
                      app.errors = data.errors;
                      toastr.error(data.message);
                    } 

                    app.loading = false;
                  });
              },
              get_salesman: function() {
                this.loading = true;
                axios.get('h3/<?= $isi ?>/get_salesman', {
                    params: {
                      id_dealer: this.do_sales_order.id_dealer
                    }
                  })
                  .then(function(res) {
                    app.do_sales_order.id_salesman = res.data.id_salesman;
                    app.do_sales_order.nama_salesman = res.data.nama_salesman;
                  })
                  .catch(function(err) {
                    toastr.error(err);
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
              hitung_dpp: function(part) {
                if (part.include_ppn == 1) {
                  dpp = part.harga / 1.1;
                  return dpp;
                }
                return part.harga;
              },
              harga_setelah_diskon: function(part) {
                harga_setelah_diskon = part.harga_jual;
                harga_setelah_diskon = harga_setelah_diskon - this.calculate_discount(part.diskon_satuan_dealer, part.tipe_diskon_satuan_dealer, harga_setelah_diskon);

                if (part.jenis_diskon_campaign == 'Additional') {
                  harga_setelah_diskon = harga_setelah_diskon - this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, harga_setelah_diskon);
                } else if (part.jenis_diskon_campaign == 'Non Additional') {
                  harga_setelah_diskon = harga_setelah_diskon - this.calculate_discount(part.diskon_campaign, part.tipe_diskon_campaign, part.harga_jual);
                }

                return harga_setelah_diskon;
              },
              calculate_discount: function(discount, tipe_diskon, price) {
                if (tipe_diskon == 'Persen') {
                  if (discount == 0) return 0;

                  discount = (discount / 100) * price;
                  return discount;
                } else if (tipe_diskon == 'Rupiah') {
                  return discount;
                }
                return 0;
              },
              amount: function(part) {
                return this.harga_setelah_diskon(part) * part.qty_supply
              },
              convert_diskon_ke_rupiah: function(tipe_diskon, diskon_value, harga) {
                if (tipe_diskon == 'Rupiah') return diskon_value;

                diskon = (diskon_value / 100) * harga;
                return diskon;
              },
              get_currency_position: function(tipe_diskon) {
                if (tipe_diskon == 'Rupiah') {
                  return 'prefix';
                } else if (tipe_diskon == 'Persen') {
                  return 'suffix';
                }
                return;
              },
              get_currency_symbol: function(tipe_diskon) {
                if (tipe_diskon == 'Rupiah') {
                  return 'Rp';
                } else if (tipe_diskon == 'Persen') {
                  return '%';
                }
                return;
              },
              open_status_pembayaran: function(referensi) {
                $('#referensi_open_status_pembayaran').val(referensi);
                h3_md_open_status_pembayaran_piutang_pada_do_datatable.draw();
                $('#h3_md_open_status_pembayaran_piutang_pada_do').modal('show');
              },
            },
            computed: {
              kategori_kpb: function() {
                return this.do_sales_order.kategori_po == 'KPB';
              },
              sub_total_colspan: function() {
                colspan = 8;
                if (this.kategori_kpb) {
                  colspan += 1;
                }
                return colspan;
              },
              insentif_colspan: function() {
                colspan = 3;
                if (this.kategori_kpb) {
                  colspan += 1;
                }
                return colspan;
              },
              cashback_langsung_colspan: function() {
                colspan = 7;
                if (this.kategori_kpb) {
                  colspan += 1;
                }
                return colspan;
              },
              diskon_cashback_colspan: function() {
                colspan = 6;
                if (this.kategori_kpb) {
                  colspan += 1;
                }
                return colspan;
              },
              ppn_tools_colspan: function() {
                colspan = 6;
                if (this.kategori_kpb) {
                  colspan += 1;
                }
                return colspan;
              },
              total_colspan: function() {
                colspan = 7;
                if (this.kategori_kpb) {
                  colspan += 1;
                }
                return colspan;
              },
              total_diskon_parts: function() {
                harga_setelah_diskon_fn = this.harga_setelah_diskon;
                hitung_dpp_fn = this.hitung_dpp;
                return _.chain(this.parts)
                  .sumBy(function(part) {
                    return (hitung_dpp_fn(part) - harga_setelah_diskon_fn(part)) * part.qty_supply;
                  })
                  .value();
              },
              sub_total: function() {
                total = 0;
                for (index = 0; index < this.parts.length; index++) {
                  part = this.parts[index];
                  total += this.amount(part);
                }
                return total;
              },
              total_diskon_insentif_cashback: function() {
                return (parseFloat(this.do_sales_order.diskon_insentif) + parseFloat(this.insentif_langsung)) + (parseFloat(this.do_sales_order.diskon_cashback) + parseFloat(this.do_sales_order.diskon_cashback_otomatis));
              },
              ppn_tools: function() {
                return this.sub_total * this.ppn;
              },
              total: function() {
                return (this.sub_total - this.total_diskon_insentif_cashback);
              },
              total_tambah_ppn: function() {
                return (this.sub_total - this.total_diskon_insentif_cashback + parseFloat(this.ppn_tools));
              },
              sisa_plafon: function() {
                return this.do_sales_order.plafon - this.do_sales_order.plafon_booking - this.do_sales_order.plafon_yang_dipakai;
              },
              total_sisa_piutang: function() {
                return _.chain(this.monitoring_piutang)
                  .sumBy(function(item) {
                    return item.sisa_piutang;
                  })
                  .value();
              },
              total_minus: function() {
                // return this.total < 0;
                return this.total < 0 || this.total_tambah_ppn <0;
              },
              claim_diskon_insentif_melebihi_insentif_tersedia: function() {
                return parseFloat(this.do_sales_order.diskon_insentif) > parseFloat(this.do_sales_order.insentif_dealer);
              },
              insentif_langsung: function() {
                return _.chain(this.do_poin)
                  .sumBy(function(row) {
                    return row.nilai_insentif;
                  })
                  .value();
              }
            },
            watch: {
              'do_sales_order.check_diskon_insentif': function(data) {
                if (data == 0) {
                  this.do_sales_order.diskon_insentif = 0;
                }
              }
            }
          });
        </script>
      <?php endif; ?>
      <?php if ($mode == "index") : ?>
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              <?php if ($this->input->get('history') != null) : ?>
                <a href="h3/<?= $isi ?>">
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
                </a>
              <?php else : ?>
                <a href="h3/<?= $isi ?>?history=true">
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
                </a>
              <?php endif; ?>
            </h3>
          </div>
          <div class="box-body">
            <?php $this->load->view('template/normal_session_message'); ?>
            <div class="container-fluid">
              <form class='form-horizontal'>
                <div class="row">
                  <div class="col-sm-6">
                    <div id='customer_filter' class="form-group">
                      <label class="control-label col-sm-4 align-middle">Customer</label>
                      <div class="col-sm-8">
                        <div class="input-group">
                          <input :value='filters.length + " Customer"' type="text" class="form-control" disabled>
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_dealer_filter_do_sales_order_index'>
                              <i class="fa fa-search"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_dealer_filter_do_sales_order_index'); ?>
                    <script>
                      customer_filter = new Vue({
                        el: '#customer_filter',
                        data: {
                          filters: []
                        },
                        watch: {
                          filters: function() {
                            do_sales.draw();
                          }
                        }
                      });

                      $("#h3_md_dealer_filter_do_sales_order_index").on('change', "input[type='checkbox']", function(e) {
                        target = $(e.target);
                        id_dealer = target.attr('data-id-dealer');

                        if (target.is(':checked')) {
                          customer_filter.filters.push(id_dealer);
                        } else {
                          index_kabupaten = _.indexOf(customer_filter.filters, id_dealer);
                          customer_filter.filters.splice(index_kabupaten, 1);
                        }
                        h3_md_dealer_filter_do_sales_order_index_datatable.draw();
                      });
                    </script>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="control-label col-sm-4 no-padding-x">Periode Sales</label>
                      <div class="col-sm-8">
                        <input id='periode_sales_filter' type="text" class="form-control" readonly>
                        <input id='periode_sales_filter_start' type="hidden" disabled>
                        <input id='periode_sales_filter_end' type="hidden" disabled>
                      </div>
                    </div>
                    <script>
                      $('#periode_sales_filter').daterangepicker({
                        opens: 'left',
                        autoUpdateInput: false,
                        locale: {
                          format: 'DD/MM/YYYY'
                        }
                      }).on('apply.daterangepicker', function(ev, picker) {
                        $('#periode_sales_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                        $('#periode_sales_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                        do_sales.draw();
                      }).on('cancel.daterangepicker', function(ev, picker) {
                        $(this).val('');
                        $('#periode_sales_filter_start').val('');
                        $('#periode_sales_filter_end').val('');
                        do_sales.draw();
                      });
                    </script>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="control-label col-sm-4 align-middle">No SO</label>
                      <div class="col-sm-8">
                        <input id='no_so_filter' type="text" class="form-control">
                      </div>
                    </div>
                    <script>
                      $(document).ready(function() {
                        // $('#no_so_filter').on("keyup", _.debounce(function(){
                        //   do_sales.draw();
                        // }, 500));
                        $('#no_so_filter').on("keyup", _.debounce(function() {
                          if (this.value.length >= 4) {
                            do_sales.draw();
                          }
                          if (this.value == '') {
                            do_sales.draw();
                          }
                        }, 500));
                      });
                    </script>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="control-label col-sm-4 align-middle">No DO</label>
                      <div class="col-sm-8">
                        <input id='no_do_filter' type="text" class="form-control">
                      </div>
                    </div>
                    <script>
                      $(document).ready(function() {
                        // $('#no_do_filter').on("keyup", _.debounce(function(){
                        //   do_sales.draw();
                        // }, 500));
                        $('#no_do_filter').on("keyup", _.debounce(function() {
                          if (this.value.length >= 4) {
                            do_sales.draw();
                          }
                          if (this.value == '') {
                            do_sales.draw();
                          }
                        }, 500));
                      });
                    </script>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="control-label col-sm-4 align-middle">Salesman</label>
                      <div class="col-sm-8">
                        <div class="input-group">
                          <input id='nama_salesman_filter' type="text" class="form-control" disabled>
                          <input id='id_salesman_filter' type="hidden" disabled>
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_salesman_filter_do_sales_order_index'>
                              <i class="fa fa-search"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_salesman_filter_do_sales_order_index'); ?>
                    <script>
                      function pilih_salesman_filter_do_sales_order_index(data, type) {
                        if (type == 'add_filter') {
                          $('#nama_salesman_filter').val(data.nama_lengkap);
                          $('#id_salesman_filter').val(data.id_karyawan);
                        } else if (type == 'reset_filter') {
                          $('#nama_salesman_filter').val('');
                          $('#id_salesman_filter').val('');
                        }
                        do_sales.draw();
                        h3_md_salesman_filter_do_sales_order_index_datatable.draw();
                      }
                    </script>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="control-label col-sm-4 align-middle">Tipe Penjualan</label>
                      <div class="col-sm-8">
                        <select id="tipe_penjualan_filter" class="form-control">
                          <option value="">-Pilih-</option>
                          <option value="FIX">Fixed</option>
                          <option value="REG">Reguler</option>
                          <option value="HLO">Hotline</option>
                          <option value="URG">Urgent</option>
                        </select>
                      </div>
                    </div>
                    <script>
                      $(document).ready(function() {
                        $('#tipe_penjualan_filter').on("change", function() {
                          do_sales.draw();
                        });
                      });
                    </script>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="control-label col-sm-4 align-middle">Kategori Sales</label>
                      <div class="col-sm-8">
                        <select id="kategori_sales_filter" class="form-control">
                          <option value="">-Pilih-</option>
                          <option value="SIM Part">SIM Part</option>
                          <option value="Non SIM Part">Non SIM Part</option>
                          <option value="KPB">KPB</option>
                        </select>
                      </div>
                    </div>
                    <script>
                      $(document).ready(function() {
                        $('#kategori_sales_filter').on("change", function() {
                          do_sales.draw();
                        });
                      });
                    </script>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="control-label col-sm-4 align-middle">Tipe Produk</label>
                      <div class="col-sm-8">
                        <select id="tipe_produk_filter" class="form-control">
                          <option value="">-Pilih-</option>
                          <option value="Parts">Parts</option>
                          <option value="Oil">Oil</option>
                          <option value="Acc">Accesories</option>
                          <option value="Apparel">Apparel</option>
                          <option value="Tools">Tools</option>
                          <option value="Other">Other</option>
                        </select>
                      </div>
                    </div>
                    <script>
                      $(document).ready(function() {
                        $('#tipe_produk_filter').on("change", function() {
                          sales_order.draw();
                        });
                      });
                    </script>
                  </div>
                </div>
                <div class="row">
                  <div id='jenis_dealer_filter' class="col-sm-6">
                    <div class="form-group">
                      <label class="control-label col-sm-4 align-middle">Jenis Dealer</label>
                      <div class="col-sm-8">
                        <div class="input-group">
                          <input :value='filters.length + " filter"' type="text" class="form-control" disabled>
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_jenis_dealer_filter_sales_order_index'>
                              <i class="fa fa-search"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_jenis_dealer_filter_sales_order_index'); ?>
                    <script>
                      jenis_dealer_filter = new Vue({
                        el: '#jenis_dealer_filter',
                        data: {
                          filters: []
                        },
                        watch: {
                          filters: function() {
                            do_sales.draw();
                          }
                        }
                      })
                    </script>
                  </div>
                  <div id='kabupaten_filter' class="col-sm-6">
                    <div class="form-group">
                      <label class="control-label col-sm-4 align-middle">Kabupaten</label>
                      <div class="col-sm-8">
                        <div class="input-group">
                          <input :value='filters.length + " kabupaten"' type="text" class="form-control" disabled>
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kabupaten_filter_sales_order_index'>
                              <i class="fa fa-search"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_kabupaten_filter_sales_order_index'); ?>
                    <script>
                      kabupaten_filter = new Vue({
                        el: '#kabupaten_filter',
                        data: {
                          filters: []
                        },
                        watch: {
                          filters: function() {
                            do_sales.draw();
                          }
                        }
                      });

                      $("#h3_md_kabupaten_filter_sales_order_index").on('change', "input[type='checkbox']", function(e) {
                        target = $(e.target);
                        id_kabupaten = target.attr('data-id-kabupaten');


                        if (target.is(':checked')) {
                          kabupaten_filter.filters.push(id_kabupaten);
                        } else {
                          index_kabupaten = _.indexOf(kabupaten_filter.filters, id_kabupaten);
                          kabupaten_filter.filters.splice(index_kabupaten, 1);
                        }
                        h3_md_kabupaten_filter_sales_order_index_datatable.draw();
                      });
                    </script>
                  </div>
                </div>
                <div class="row">
                  <div id='kelompok_part_filter' class="col-sm-6">
                    <div class="form-group">
                      <label class="control-label col-sm-4 align-middle">Kelompok Part</label>
                      <div class="col-sm-8">
                        <div class="input-group">
                          <input :value='filters.length + " kelompok part"' type="text" class="form-control" disabled>
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_kelompok_part_filter_sales_order_index'>
                              <i class="fa fa-search"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_kelompok_part_filter_sales_order_index'); ?>
                    <script>
                      kelompok_part_filter = new Vue({
                        el: '#kelompok_part_filter',
                        data: {
                          filters: []
                        },
                        watch: {
                          filters: function() {
                            do_sales.draw();
                          }
                        }
                      });

                      $("#h3_md_kelompok_part_filter_sales_order_index").on('change', "input[type='checkbox']", function(e) {
                        target = $(e.target);
                        id_kelompok_part = target.attr('data-id-kelompok-part');

                        if (target.is(':checked')) {
                          kelompok_part_filter.filters.push(id_kelompok_part);
                        } else {
                          index_kabupaten = _.indexOf(kelompok_part_filter.filters, id_kelompok_part);
                          kelompok_part_filter.filters.splice(index_kabupaten, 1);
                        }
                        h3_md_kelompok_part_filter_sales_order_index_datatable.draw();
                      });
                    </script>
                  </div>
                  <div id='status_filter' class="col-sm-6">
                    <div class="form-group">
                      <label class="control-label col-sm-4 align-middle">Status</label>
                      <div class="col-sm-8">
                        <div class="input-group">
                          <input :value='filters.length + " Status"' type="text" class="form-control" disabled>
                          <div class="input-group-btn">
                            <button class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_status_filter_do_sales_order_index'>
                              <i class="fa fa-search"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_status_filter_do_sales_order_index'); ?>
                    <script>
                      status_filter = new Vue({
                        el: '#status_filter',
                        data: {
                          filters: []
                        },
                        watch: {
                          filters: function() {
                            do_sales.draw();
                          }
                        }
                      })
                    </script>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <table class="table table-condensed">
                      <tr>
                        <th colspan='2'>Produk</th>
                        <th>Nilai SO</th>
                        <th>Nilai DO</th>
                        <th>S/R (%)</th>
                      </tr>
                      <tr>
                        <td>Parts</td>
                        <td width='3%' class='text-center'>:</td>
                        <!-- <td id='amount_parts_sales_order'>Rp 0</td>
                    <td id='amount_parts_do_sales_order'>Rp 0</td>
                    <td id='service_rate_parts'>0%</td> -->
                        <td id='amount_parts_sales_order2'>Rp 0</td>
                        <td id='amount_parts_do_sales_order2'>Rp 0</td>
                        <td id='service_rate_parts2'>0%</td>
                      </tr>
                      <tr>
                        <td>Qty Parts</td>
                        <td width='3%' class='text-center'>:</td>
                        <!-- <td id='qty_parts_sales_order'>0</td>
                    <td id='qty_parts_do_sales_order' colspan='2'>0</td> -->
                        <td id='qty_parts_sales_order2'>0</td>
                        <td id='qty_parts_do_sales_order2' colspan='2'>0</td>
                      </tr>
                      <tr>
                        <td>Oil</td>
                        <td width='3%' class='text-center'>:</td>
                        <!-- <td id='amount_oil_sales_order'>Rp 0</td>
                    <td id='amount_oil_do_sales_order'>Rp 0</td>
                    <td id='service_rate_oil'>0%</td> -->
                        <td id='amount_oil_sales_order2'>Rp 0</td>
                        <td id='amount_oil_do_sales_order2'>Rp 0</td>
                        <td id='service_rate_oil2'>0%</td>
                      </tr>
                      <tr>
                        <td>Qty Oil</td>
                        <td width='3%' class='text-center'>:</td>
                        <!-- <td id='qty_oil_sales_order'>0</td>
                    <td id='qty_oil_do_sales_order' colspan='2'>0</td> -->
                        <td id='qty_oil_sales_order2'>0</td>
                        <td id='qty_oil_do_sales_order2' colspan='2'>0</td>
                      </tr>
                      <tr>
                        <td>Accesories</td>
                        <td width='3%' class='text-center'>:</td>
                        <!-- <td id='amount_acc_sales_order'>Rp 0</td>
                    <td id='amount_acc_do_sales_order'>Rp 0</td>
                    <td id='service_rate_acc'>0%</td> -->
                        <td id='amount_acc_sales_order2'>Rp 0</td>
                        <td id='amount_acc_do_sales_order2'>Rp 0</td>
                        <td id='service_rate_acc2'>0%</td>
                      </tr>
                      <tr>
                        <td>Qty Accesories</td>
                        <td width='3%' class='text-center'>:</td>
                        <!-- <td id='qty_acc_sales_order'>0</td>
                    <td id='qty_acc_do_sales_order' colspan='2'>0</td> -->
                        <td id='qty_acc_sales_order2'>0</td>
                        <td id='qty_acc_do_sales_order2' colspan='2'>0</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </form>
            </div>
            <table id="create_do_sales_order_index" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Tanggal DO</th>
                  <th>Nomor DO</th>
                  <th>Kode Customer</th>
                  <th>Nama Customer</th>
                  <th>Kota/Kabupaten</th>
                  <th>Nilai DO Awal</th>
                  <th>Nilai DO-Rev</th>
                  <th>Status</th>
                  <th width="10%">Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
        <script>
          $(document).ready(function() {
            do_sales = $('#create_do_sales_order_index').DataTable({
              processing: true,
              serverSide: true,
              searching: false,
              scrollX: true,
              order: [],
              ajax: {
                url: "<?= base_url('api/md/h3/do_sales_order') ?>",
                dataSrc: function(json) {
                  amount_parts_sales_order = _.chain(json.data)
                    .filter(function(data) {
                      return data.produk == 'Parts';
                    })
                    .sumBy(function(data) {
                      return parseFloat(data.total_so);
                    })
                    .value();

                  $('#amount_parts_sales_order').text(
                    accounting.formatMoney(amount_parts_sales_order, "Rp ", 0, ".", ",")
                  );

                  amount_parts_do_sales_order = _.chain(json.data)
                    .filter(function(data) {
                      return data.produk == 'Parts';
                    })
                    .sumBy(function(data) {
                      return parseFloat(data.total_do);
                    })
                    .value();

                  $('#amount_parts_do_sales_order').text(
                    accounting.formatMoney(amount_parts_do_sales_order, "Rp ", 0, ".", ",")
                  );

                  service_rate_parts = (amount_parts_do_sales_order / amount_parts_sales_order) * 100;
                  if (Number.isNaN(service_rate_parts)) {
                    service_rate_parts = 0;
                  }

                  $('#service_rate_parts').text(Math.round(service_rate_parts) + '%');

                  qty_parts_sales_order = _.chain(json.data)
                    .filter(function(data) {
                      return data.produk == 'Parts';
                    })
                    .sumBy(function(data) {
                      return parseFloat(data.qty_parts_sales_order);
                    })
                    .value();

                  $('#qty_parts_sales_order').text(qty_parts_sales_order);

                  qty_parts_do_sales_order = _.chain(json.data)
                    .filter(function(data) {
                      return data.produk == 'Parts';
                    })
                    .sumBy(function(data) {
                      return parseFloat(data.qty_parts_do_sales_order);
                    })
                    .value();

                  $('#qty_parts_do_sales_order').text(qty_parts_do_sales_order);

                  amount_oil_sales_order = _.chain(json.data)
                    .filter(function(data) {
                      return data.produk == 'Oil';
                    })
                    .sumBy(function(data) {
                      return parseFloat(data.total_so);
                    })
                    .value();

                  $('#amount_oil_sales_order').text(
                    accounting.formatMoney(amount_oil_sales_order, "Rp ", 0, ".", ",")
                  );

                  amount_oil_do_sales_order = _.chain(json.data)
                    .filter(function(data) {
                      return data.produk == 'Oil';
                    })
                    .sumBy(function(data) {
                      return parseFloat(data.total_do);
                    })
                    .value();

                  $('#amount_oil_do_sales_order').text(
                    accounting.formatMoney(amount_oil_do_sales_order, "Rp ", 0, ".", ",")
                  );

                  service_rate_oil = (amount_oil_do_sales_order / amount_oil_sales_order) * 100;
                  if (Number.isNaN(service_rate_oil)) {
                    service_rate_oil = 0;
                  }

                  $('#service_rate_oil').text(Math.round(service_rate_oil) + '%');

                  qty_oil_sales_order = _.chain(json.data)
                    .filter(function(data) {
                      return data.produk == 'Oil';
                    })
                    .sumBy(function(data) {
                      return parseFloat(data.qty_parts_sales_order);
                    })
                    .value();

                  $('#qty_oil_sales_order').text(qty_oil_sales_order);

                  qty_oil_do_sales_order = _.chain(json.data)
                    .filter(function(data) {
                      return data.produk == 'Oil';
                    })
                    .sumBy(function(data) {
                      return parseFloat(data.qty_parts_do_sales_order);
                    })
                    .value();

                  $('#qty_oil_do_sales_order').text(qty_oil_do_sales_order);

                  amount_acc_sales_order = _.chain(json.data)
                    .filter(function(data) {
                      return data.produk == 'Acc';
                    })
                    .sumBy(function(data) {
                      return parseFloat(data.total_so);
                    })
                    .value();

                  $('#amount_acc_sales_order').text(
                    accounting.formatMoney(amount_oil_sales_order, "Rp ", 0, ".", ",")
                  );

                  amount_acc_do_sales_order = _.chain(json.data)
                    .filter(function(data) {
                      return data.produk == 'Acc';
                    })
                    .sumBy(function(data) {
                      return parseFloat(data.total_do);
                    })
                    .value();

                  $('#amount_acc_do_sales_order').text(
                    accounting.formatMoney(amount_acc_do_sales_order, "Rp ", 0, ".", ",")
                  );

                  service_rate_acc = (amount_acc_do_sales_order / amount_acc_sales_order) * 100;
                  if (Number.isNaN(service_rate_acc)) {
                    service_rate_acc = 0;
                  }

                  $('#service_rate_acc').text(Math.round(service_rate_acc) + '%');

                  qty_acc_sales_order = _.chain(json.data)
                    .filter(function(data) {
                      return data.produk == 'Acc';
                    })
                    .sumBy(function(data) {
                      return parseFloat(data.qty_parts_sales_order);
                    })
                    .value();

                  $('#qty_acc_sales_order').text(qty_acc_sales_order);

                  qty_acc_do_sales_order = _.chain(json.data)
                    .filter(function(data) {
                      return data.produk == 'Acc';
                    })
                    .sumBy(function(data) {
                      return parseFloat(data.qty_parts_do_sales_order);
                    })
                    .value();

                  $('#qty_acc_do_sales_order').text(qty_acc_do_sales_order);

                  return json.data;
                },
                type: "POST",
                data: function(d) {
                  d.customer_filter = customer_filter.filters;
                  d.id_salesman_filter = $('#id_salesman_filter').val();
                  d.no_so_filter = $('#no_so_filter').val();
                  d.no_do_filter = $('#no_do_filter').val();
                  d.periode_sales_filter_start = $('#periode_sales_filter_start').val();
                  d.periode_sales_filter_end = $('#periode_sales_filter_end').val();
                  d.tipe_penjualan_filter = $('#tipe_penjualan_filter').val();
                  d.kategori_sales_filter = $('#kategori_sales_filter').val();
                  d.tipe_produk_filter = $('#tipe_produk_filter').val();
                  d.jenis_dealer_filter = jenis_dealer_filter.filters;
                  d.kabupaten_filter = kabupaten_filter.filters;
                  d.kelompok_part_filter = kelompok_part_filter.filters;
                  d.status_filter = status_filter.filters;
                  d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                }
              },
              columns: [{
                  data: 'nomor',
                  orderable: false,
                  width: '5px'
                },
                {
                  data: 'tanggal_do',
                  width: '50px'
                },
                {
                  data: 'id_do_sales_order',
                  width: '170px'
                },
                {
                  data: 'kode_dealer_md',
                  width: '20px'
                },
                {
                  data: 'nama_dealer',
                  width: '250px'
                },
                {
                  data: 'kabupaten'
                },
                {
                  data: 'sub_total_do_awal_formatted',
                  width: '90px',
                  className: 'text-right'
                },
                {
                  data: 'sub_total_do_rev_formatted',
                  width: '90px',
                  className: 'text-right'
                },
                {
                  data: 'status',
                  width: '100px'
                },
                {
                  data: 'action',
                  orderable: false,
                  width: '3%',
                  className: 'text-center'
                }
              ],
            });
          });
        </script>
      <?php endif; ?>
    </section>
  </div>