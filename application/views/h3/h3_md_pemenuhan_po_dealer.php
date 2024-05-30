<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/sweet_alert.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
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
      <h1>
        <?php echo $title; ?>
      </h1>
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
          $form = 'detail';
          $disabled = 'disabled';
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
                  <div v-if='qty_avs_tidak_memenuhi.length > 0 && mode != "detail"' class="alert alert-warning" role="alert">
                    <strong>Perhatian!</strong> Terdapat pemenuhan yang melebihi kuantitas AVS.
                  </div>
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Nomor PO</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.po_id' type="text" readonly class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">Tanggal PO</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.tanggal_order' type="text" readonly class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Kode Customer</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.kode_dealer_md' type="text" readonly class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">Customer</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.nama_dealer' type="text" readonly class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-2 control-label">Kategori PO</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.kategori_po' type="text" readonly class="form-control">
                      </div>
                      <label class="col-sm-2 control-label">Produk</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.produk' type="text" readonly class="form-control">
                      </div>
                    </div>
                    <div class="container-fluid bg-blue-gradient" style='margin-bottom: 15px;'>
                      <div class="row" style='padding: 6px 0;'>
                        <div class="col-sm-12 text-center">
                          <span class="bold">Data Request Document</span>
                        </div>
                      </div>
                    </div>
                    <div class="row" style='max-height: 400px; overflow-x:scroll;'>
                      <div class="form-group">
                        <label for="" class="control-label col-sm-2">No. Customer</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.id_customer'>
                        </div>
                        <label for="" class="control-label col-sm-2">Nama Customer</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.nama_customer'>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="" class="control-label col-sm-2 no-padding">Masukkan Pemesan</label>
                        <div class="col-sm-4">
                          <input disabled type="checkbox" v-model='purchase.masukkan_pemesan' true-value='1' false-value='0'>
                        </div>
                      </div>
                      <div v-if='purchase.masukkan_pemesan == 1' class="form-group">
                        <label for="" class="control-label col-sm-2">Nama Pemesan</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.nama_pemesan'>
                        </div>
                        <label for="" class="control-label col-sm-2">No. HP Pemesan</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.no_hp'>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="" class="control-label col-sm-2">Nomor Identitas</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.no_identitas'>
                        </div>
                        <label for="" class="control-label col-sm-2">No. Telepon</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.no_hp_customer'>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="" class="control-label col-sm-2">Alamat</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.alamat'>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="" class="control-label col-sm-2">Kelurahan</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.kelurahan'>
                        </div>
                        <label for="" class="control-label col-sm-2">Kecamatan</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.kecamatan'>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="" class="control-label col-sm-2">Kabupaten/Kota</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.kabupaten'>
                        </div>
                        <label for="" class="control-label col-sm-2">Provinsi</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.provinsi'>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="" class="control-label col-sm-2">No Polisi</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.no_polisi'>
                        </div>
                        <label for="" class="control-label col-sm-2">Tipe Kendaraan</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.tipe_kendaraan'>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="" class="control-label col-sm-2">Deskripsi Unit</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.deskripsi_unit'>
                        </div>
                        <label for="" class="control-label col-sm-2">Deskripsi Warna</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.deksripsi_warna'>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="" class="control-label col-sm-2">No Mesin</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.no_mesin'>
                        </div>
                        <label for="" class="control-label col-sm-2">No Rangka</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.no_rangka'>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="" class="control-label col-sm-2">Tahun Perakitan</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.tahun_produksi'>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="" class="control-label col-sm-2">ID SA Form</label>
                        <div class="col-sm-4">
                          <input readonly type="text" class="form-control" v-model='purchase.id_sa_form'>
                        </div>
                        <label for="" class="control-label col-sm-2">ID Work Order</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.id_work_order'>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="" class="control-label col-sm-2">No. Buku Claim C2</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.no_buku_claim_c2'>
                        </div>
                        <label for="" class="control-label col-sm-2">No. Claim C2</label>
                        <div class="col-sm-4">
                          <input type="text" class="form-control" readonly v-model='purchase.no_claim_c2'>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label no-padding">Flag Renumbering</label>
                        <div class="col-sm-4">
                          <input disabled type="radio" value="1" v-model="purchase.penomoran_ulang">
                          <label>Yes</label>
                          <br>
                          <input disabled type="radio" value="0" v-model="purchase.penomoran_ulang">
                          <label>No</label>
                          <br>
                        </div>
                      </div>
                      <div v-if="purchase.penomoran_ulang == '1'">
                        <div class="form-group">
                          <label class='control-label col-sm-2 no-padding'>Claim C1/C2</label>
                          <div class="col-sm-4">
                            <input disabled type="radio" value="claim_c1_c2" v-model="purchase.tipe_penomoran_ulang">
                          </div>
                          <label class='control-label col-sm-2 no-padding'>Non-Claim</label>
                          <div class="col-sm-4">
                            <input disabled type="radio" value="non_claim" v-model="purchase.tipe_penomoran_ulang">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-4 col-sm-offset-2">
                            <div class="col-sm-6 no-padding">
                              <span>Form Warranty Claim C1/C2</span>
                              <input disabled type="text" class="input-compact" v-model="purchase.form_warranty_claim_c2_c2">
                            </div>
                          </div>
                          <div class="col-sm-4 col-sm-offset-2">
                            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_bpkb_faktur_ahm_non_claim"> Copy BPKB/Faktur AHM
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-4 col-sm-offset-2">
                            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_faktur_ahm_claim_c1_c2"> Copy Faktur AHM
                          </div>
                          <div class="col-sm-4 col-sm-offset-2">
                            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_stnk_non_claim"> Copy STNK
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-4 col-sm-offset-2">
                            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.gesekan_nomor_framebody_claim_c1_c2"> Gesekan Nomor Framebody (Rangka)
                          </div>
                          <div class="col-sm-4 col-sm-offset-2">
                            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_ktp_non_claim"> Copy KTP
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-4 col-sm-offset-2">
                            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.gesekan_nomor_crankcase_claim_c1_c2"> Gesekan Nomor Crankcase (Mesin)
                          </div>
                          <div class="col-sm-4 col-sm-offset-2">
                            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.gesekan_nomor_framebody_non_claim"> Gesekan Nomor Framebody (Rangka)
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-4 col-sm-offset-2">
                            <span class="text-bold visible-lg-block">Khusus Untuk Claim C2</span>
                            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_ktp_claim_c1_c2"> Copy KTP
                          </div>
                          <div class="col-sm-4 col-sm-offset-2">
                            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.gesekan_nomor_crankcase_non_claim"> Gesekan Nomor Crankcase (Mesin)
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-4 col-sm-offset-2">
                            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.copy_stnk_claim_c1_c2"> Copy STNK
                          </div>
                          <div class="col-sm-4 col-sm-offset-2">
                            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.potongan_no_rangka_mesin_non_claim"> Potongan No Rangka/Mesin (Jangan Dipotong)*
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-4 col-sm-offset-8">
                            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.surat_permohonan_penomoran_ulang_dari_kepolisian_non_claim"> Surat Permohonan Penomoran Ulang Dari Kepolisian (Asli)
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-4 col-sm-offset-8">
                            <span class="text-bold visible-lg-block">Khusus untuk kasus nomor pada rangka/mesin tidak terbaca</span>
                            <input disabled type="checkbox" true-value="1" false-value="0" v-model="purchase.surat_laporan_forensik_kepolisian_non_claim"> Surat laporan forensik kepolisian (Asli)
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label no-padding">Vehicle of The Road Flag</label>
                        <div class="col-sm-4">
                          <input disabled type="radio" value="1" v-model="purchase.vor">
                          <label>Yes</label>
                          <br>
                          <input disabled type="radio" value="0" v-model="purchase.vor">
                          <label>No</label>
                          <br>
                        </div>
                        <label for="" class="control-label col-sm-2 no-padding">Keterangan Tambahan</label>
                        <div class="col-sm-4">
                          <input disabled type="checkbox" v-model='purchase.ada_keterangan_tambahan' true-value='1' false-value='0'>
                          <textarea v-show='purchase.ada_keterangan_tambahan == 1' disabled rows="1" class="form-control auto-resize" v-model='purchase.keterangan_tambahan'></textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label no-padding">Job Return</label>
                        <div class="col-sm-4">
                          <input disabled type="radio" value="1" v-model="purchase.job_return_flag">
                          <label>Yes</label>
                          <br>
                          <input disabled type="radio" value="0" v-model="purchase.job_return_flag">
                          <label>No</label>
                          <br>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12">
                        <table id="table" class="table table-condensed table-responsive table-bordered">
                          <thead>
                            <tr class='bg-blue-gradient'>
                              <th width='3%'>No.</th>
                              <th width='7%'>Part Number</th>
                              <th width='10%'>Nama Part</th>
                              <th width='10%' class='text-right'>HET</th>
                              <th width="5%">Qty PO Dealer</th>
                              <th width="5%">Qty PO AHM</th>
                              <th width="5%">Qty Penerimaan</th>
                              <th width="5%">Qty SO</th>
                              <th width="5%">Qty DO</th>
                              <th width="5%">Qty Supply</th>
                              <th width="5%">Qty BO</th>
                              <th width="5%">Qty On Hand</th>
                              <th width="5%">Qty AVS</th>
                              <th width="5%">Qty Book</th>
                              <th width="5%">Qty HLO</th>
                              <th width="5%">Qty URG</th>
                              <th width="10%" class='text-right'>Total Harga</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(part, index) in parts">
                              <td class="align-middle" width='3%'>{{ index + 1 }}.</td>
                              <td class="align-middle">{{ part.id_part }}</td>
                              <td class="align-middle">{{ part.nama_part }}</td>
                              <td class="align-middle text-right">
                                <vue-numeric :read-only="true" class="form-control" separator="." currency='Rp' v-model="part.harga"></vue-numeric>
                              </td>
                              <td class="align-middle">
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_order"></vue-numeric>
                              </td>
                              <td class="align-middle" @click.prevent='open_qty_po(index)'>
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_po"></vue-numeric>
                              </td>
                              <td class="align-middle" @click.prevent='open_qty_penerimaan(index)'>
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_penerimaan"></vue-numeric>
                              </td>
                              <td class="align-middle">
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_so"></vue-numeric>
                              </td>
                              <td class="align-middle">
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_do"></vue-numeric>
                              </td>
                              <td class="align-middle bg-blue-gradient" @click.prevent='open_qty_supply(index)'>
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_supply"></vue-numeric>
                              </td>
                              <td class="align-middle">
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_belum_terpenuhi"></vue-numeric>
                              </td>
                              <td class="align-middle">
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_on_hand"></vue-numeric>
                              </td>
                              <td class="align-middle">
                                <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_avs"></vue-numeric>
                              </td>
                              <td class="align-middle bg-blue-gradient">
                                <vue-numeric :read-only='mode == "detail"' class="form-control" separator="." :empty-value="0" v-model="part.qty_pemenuhan"></vue-numeric>
                              </td>
                              <td class="align-middle bg-blue-gradient">
                                <vue-numeric :read-only='mode == "detail"' class="form-control" separator="." :empty-value="0" v-model="part.qty_hotline"></vue-numeric>
                              </td>
                              <td class="align-middle bg-blue-gradient">
                                <vue-numeric :read-only='mode == "detail"' class="form-control" separator="." :empty-value="0" v-model="part.qty_urgent"></vue-numeric>
                              </td>
                              <td width="8%" class="align-middle text-right">
                                <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="sub_total(part)" />
                              </td>
                            </tr>
                            <tr v-if="parts.length < 1">
                              <td class="text-center" colspan="15">Belum ada part</td>
                            </tr>
                          </tbody>
                        </table>
                        <?php $this->load->view('modal/h3_md_qty_po_pemenuhan_po_dealer'); ?>
                        <?php $this->load->view('modal/h3_md_qty_penerimaan_pemenuhan_po_dealer'); ?>
                        <?php $this->load->view('modal/h3_md_qty_supply_pemenuhan_po_dealer'); ?>
                      </div>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                    <div class="col-sm-6 no-padding">
                      <a v-if='mode == "detail"' :href="'h3/h3_md_pemenuhan_po_dealer/edit?id=' + purchase.po_id" class="btn btn-flat btn-sm btn-warning">Edit</a>
                      <button v-if='mode == "edit"' :disabled='qty_pemecahan_tidak_sinkron.length > 0 || qty_avs_tidak_memenuhi.length > 0' class="btn btn-flat btn-warning btn-sm" @click.prevent='<?= $form ?>'>Update</button>
                    </div>
                    <div class="col-sm-6 no-padding text-right">
                      <a v-if='mode == "detail"' :disabled='terdapat_qty_pemenuhan.length < 1' :href="'h3/h3_md_sales_order/add?generateByPO=true&po_id=' + purchase.po_id" class="btn btn-flat btn-info btn-sm">Menuju Sales Order</a>
                    </div>
                  </div><!-- /.box-footer -->
                </form>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
        <script>
          app = new Vue({
            el: '#app',
            data: {
              loading: false,
              mode: '<?= $mode ?>',
              index_part: 0,
              purchase: <?= json_encode($purchase) ?>,
              parts: <?= json_encode($parts) ?>,
            },
            methods: {
              <?= $form ?>: function() {
                this.loading = true;
                post = _.pick(this.purchase, ['po_id']);
                post.parts = _.chain(this.parts)
                  .map(function(data) {
                    return _.pick(data, ['id_part','id_part_int', 'qty_pemenuhan', 'qty_hotline', 'qty_urgent']);
                  })
                  .value();

                axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
                  .then(function(res) {
                    window.location = 'h3/h3_md_pemenuhan_po_dealer/detail?id=' + res.data.po_id;
                  })
                  .catch(function(err) {
                    toastr.error(err);
                  })
                  .then(function() {
                    app.loading = false;
                  });
              },
              open_qty_po: function(index) {
                this.index_part = index;
                h3_md_qty_po_pemenuhan_po_dealer_datatable.draw();
                $('#h3_md_qty_po_pemenuhan_po_dealer').modal('show');
              },
              open_qty_supply: function(index) {
                this.index_part = index;
                h3_md_qty_supply_pemenuhan_po_dealer_datatable.draw();
                $('#h3_md_qty_supply_pemenuhan_po_dealer').modal('show');
              },
              open_qty_penerimaan: function(index) {
                this.index_part = index;
                h3_md_qty_penerimaan_pemenuhan_po_dealer_datatable.draw();
                $('#h3_md_qty_penerimaan_pemenuhan_po_dealer').modal('show');
              },
              get_max_qty_pemenuhan: function(part) {
                if (this.mode == 'detail') {
                  return part.qty_pemenuhan;
                }
                if (part.qty_avs <= part.qty_order) {
                  return part.qty_avs;
                }
                return part.qty_order;
              },
              ppn: function(part) {
                return (10 / 100) * part.harga;
              },
              sub_total: function(part) {
                return parseInt(part.qty_order) * parseFloat(part.harga);
              },
            },
            computed: {
              qty_pemecahan_tidak_sinkron: function() {
                return _.chain(this.parts)
                  .filter(function(part) {
                    // return part.qty_belum_terpenuhi != (part.qty_pemenuhan + part.qty_supply + part.qty_so +part.qty_do + part.qty_hotline + part.qty_urgent)
                    return 0;
                  })
                  .value();
              },
              terdapat_qty_pemenuhan: function() {
                return _.chain(this.parts)
                  .filter(function(part) {
                    return parseInt(part.qty_pemenuhan) > 0;
                  })
                  .value();
              },
              qty_avs_tidak_memenuhi: function() {
                return _.chain(this.parts)
                  .filter(function(part) {
                    return parseInt(part.qty_pemenuhan) > parseInt(part.qty_avs);
                  })
                  .value();
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
          </div><!-- /.box-header -->
          <div class="box-body">
            <div class="container-fluid no-padding">
              <div class="row">
                <div class="col-sm-3">
                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label class="control-label">Tanggal PO</label>
                        <input id='tanggal_po_filter' type="text" class="form-control" readonly>
                        <input id='tanggal_po_filter_start' type="hidden" disabled>
                        <input id='tanggal_po_filter_end' type="hidden" disabled>
                      </div>                
                      <script>
                        $('#tanggal_po_filter').daterangepicker({
                          opens: 'left',
                          autoUpdateInput: false,
                          locale: {
                            format: 'DD/MM/YYYY'
                          }
                        }).on('apply.daterangepicker', function(ev, picker) {
                          $('#tanggal_po_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                          $('#tanggal_po_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                          $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                          pemenuhan_po_dealer.draw();
                        }).on('cancel.daterangepicker', function(ev, picker) {
                          $(this).val('');
                          $('#tanggal_po_filter_start').val('');
                          $('#tanggal_po_filter_end').val('');
                          pemenuhan_po_dealer.draw();
                        });
                      </script>
                    </div>
                  </div>
                </div>
                <div class="col-sm-3">
                  <div class="row">
                    <div id='dealer_filter' class="col-sm-12">
                      <div class="form-group">
                        <label for="" class="control-label">Dealer</label>
                        <div class="input-group">
                          <input :value='filters.length + " dealer"' type="text" class="form-control" readonly>
                          <div class="input-group-btn">
                            <button type='button' class="btn btn-flat btn-primary" data-toggle='modal' data-target='#h3_md_dealer_filter_pemenuhan_po_dealer'><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_dealer_filter_pemenuhan_po_dealer'); ?>
                    <script>
                        dealer_filter = new Vue({
                            el: '#dealer_filter',
                            data: {
                                filters: []
                            },
                            watch: {
                              filters: function(){
                                pemenuhan_po_dealer.draw();
                              }
                            }
                        });

                        $("#h3_md_dealer_filter_pemenuhan_po_dealer").on('change',"input[type='checkbox']",function(e){
                          target = $(e.target);
                          id_dealer = target.attr('data-id-dealer');

                          if(target.is(':checked')){
                            dealer_filter.filters.push(id_dealer);
                          }else{
                            index_dealer = _.indexOf(dealer_filter.filters, id_dealer);
                            dealer_filter.filters.splice(index_dealer, 1);
                          }
                          h3_md_dealer_filter_pemenuhan_po_dealer_datatable.draw();
                        });
                    </script>
                  </div>
                </div>
              </div>
            </div>
            <table id="pemenuhan_po_dealer" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <!--th width="1%"><input type="checkbox" id="check-all"></th-->
                  <th>No.</th>
                  <th>PO Number</th>
                  <th>Tanggal PO</th>
                  <th>Tanggal PO MD</th>
                  <th>Tanggal PO AHM</th>
                  <th>Nama Konsumen</th>
                  <th>No. HP Konsumen</th>
                  <th>Dealer</th>
                  <th>Amount</th>
                  <th>Amount Supply</th>
                  <th>SR (%)</th>
                  <th>Status</th>
                  <th width="10%">Action</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
            <script>
              $(document).ready(function() {
                pemenuhan_po_dealer = $('#pemenuhan_po_dealer').DataTable({
                  processing: true,
                  serverSide: true,
                  scrollX: true,
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/md/h3/pemenuhan_po_dealer') ?>",
                    dataSrc: "data",
                    type: "POST",
                    data: function(d) {
                      d.tanggal_po_filter_start = $('#tanggal_po_filter_start').val();
                      d.tanggal_po_filter_end = $('#tanggal_po_filter_end').val();
                      d.dealer_filter = dealer_filter.filters;
                      d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                    }
                  },
                  columns: [{
                      data: 'index',
                      orderable: false,
                      width: '3%'
                    },
                    {
                      data: 'po_id'
                    },
                    {
                      data: 'tanggal_order',
                      render: function(data) {
                        return moment(data).format("DD/MM/YYYY");
                      }
                    },
                    {
                      data: 'tanggal_po_md',
                      render: function(data) {
                        if(data != null){
                          return moment(data).format("DD/MM/YYYY");
                        }
                        return '-';
                      }
                    },
                    {
                      data: 'tanggal_po_ahm',
                      render: function(data) {
                        if(data != null){
                          return moment(data).format("DD/MM/YYYY");
                        }
                        return '-';
                      }
                    },
                    { data: 'nama_customer', width: '15%' },
                    { data: 'no_hp' },
                    { data: 'nama_dealer', width: '15%' },
                    {
                      data: 'total_amount',
                      width: '10%',
                      className: 'text-right',
                      render: function(data){
                        return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                      }
                    },
                    {
                      data: 'amount_supply_md',
                      width: '10%',
                      className: 'text-right',
                      render: function(data){
                        return accounting.formatMoney(data, 'Rp ', 0, '.', ',');
                      }
                    },
                    {
                      data: 'service_rate',
                      width: '5%',
                      className: 'text-right',
                      render: function(data){
                        return accounting.format(data, 2, ',', '.') + '%';
                      }
                    },
                    { 
                      data: 'status_md', 
                      width: '10%',
                      render: function(data){
                        if(data != null) return data;
                        return '-';
                      }
                    },
                    {
                      data: 'action',
                      orderable: false,
                      width: '3%',
                    },
                  ],
                });
              });
            </script>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      <?php endif; ?>
    </section>
  </div>