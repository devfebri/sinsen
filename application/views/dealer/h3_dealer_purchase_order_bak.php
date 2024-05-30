<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <?= $breadcrumb ?>
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
      if ($mode == 'detail') {
        $disabled = 'disabled';
        $form = 'detail';
      }
      if ($mode == 'edit') {
        $form = 'update';
      } ?>
      <script>
        Vue.use(VueNumeric.default);
      </script>
      <div id="form_" class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $isi ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
          </h3>
        </div><!-- /.box-header -->
        <div v-if='loading' class="overlay">
          <i class="fa fa-refresh fa-spin text-light-blue"></i>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div v-if='purchase.po_type == "HLO" && purchase.status == "Approved" && purchase.no_inv_uang_jaminan == null' class="alert alert-warning" role="alert">
                <strong>Perhatian!</strong> Tidak bisa submit PO Hotline karena belum membayar uang jaminan.
              </div>
              <form class="form-horizontal" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kategori</label>
                    <div v-bind:class="{ 'has-error': error_exist('kategori_po') }" class="col-sm-4">
                    <select :disabled='mode != "insert"' class="form-control" v-model='purchase.kategori_po'>
                        <option value="">-Pilih-</option>
                        <option value="SIM Part">SIM Part</option>
                        <option value="Non SIM Part">Non SIM Part</option>
                      </select>
                      <small v-if="error_exist('kategori_po')" class="form-text text-danger">{{ get_error('kategori_po') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Produk</label>
                    <div v-bind:class="{ 'has-error': error_exist('produk') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='purchase.produk'>
                        <option value="">-Pilih-</option>
                        <option value="Parts">Parts</option>
                        <option value="Oil">Oil</option>
                        <option value="Acc">Acc</option>
                        <option value="Other">Other</option>
                      </select>
                      <small v-if="error_exist('produk')" class="form-text text-danger">{{ get_error('produk') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Target Pembelian</label>
                    <div class="col-sm-4">
                      <vue-numeric class="form-control" v-model='purchase.target_pembelian' currency='Rp' separator='.' readonly/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">ACH %</label>
                    <div class="col-sm-4">
                      <vue-numeric class="form-control" v-model='ach' currency='%' currency-symbol-position='suffix' readonly/>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Total amount</label>
                    <div class="col-sm-4">
                      <vue-numeric class="form-control" v-model='total_amount_po_for_ach' currency='Rp' separator='.' readonly/>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer</label>
                    <div class="col-sm-4">
                        <input v-model='dealer.kode_dealer_md' type="text" class="form-control" :disabled="mode=='detail'" disabled>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Dealer</label>
                    <div class="col-sm-4">
                      <input v-model='dealer.nama_dealer' type="text" class="form-control" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <div v-if='is_hlo || is_other'>
                      <label for="inputEmail3" class="col-sm-2 control-label">Order To</label>
                      <div class="col-sm-4">
                        <div class="input-group">
                          <input v-if='!order_to_empty' readonly type="text" class="form-control" v-model="purchase.nama_dealer_terdekat">
                          <input v-if='order_to_empty' readonly type="text" class="form-control" value='MD'>
                          <div class="input-group-btn">
                            <button v-show='order_to_empty || mode == "detail"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle="modal" data-target="#modal_dealer_terdekat"><i class="fa fa-search"></i></button>
                            <button v-show='!order_to_empty && mode != "detail"' :disabled='generateByBooking == 1' class="btn btn-flat btn-danger" @click.prevent='hapus_order_to'><i class="fa fa-trash-o"></i></button>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/dealer_terdekat') ?>
                    <script>
                      function pilih_dealer_terdekat(data){
                        form_.purchase.nama_dealer_terdekat = data.nama_dealer;
                        form_.purchase.order_to = data.id_dealer
                      }
                    </script>
                    <label for="inputEmail3" class="control-label col-sm-2">Ship To</label>
                    <div class="col-sm-4">
                      <input v-model='dealer.nama_dealer' type="text" class="form-control" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis Purchase</label>
                    <div v-bind:class="{ 'has-error': errors.po_type != null }" class="col-sm-4">
                      <select required class="form-control" :disabled="mode=='detail' || mode=='edit'" v-model="purchase.po_type">
                        <option value="">-choose-</option>
                        <option value="FIX">Fix</option>
                        <option value="REG">Regular</option>
                        <option value="URG">Urgent</option>
                        <option value="HLO">Hotline</option>
                        <option value="OTHER">Other</option>
                      </select>
                      <small v-if='errors.po_type != null' class="form-text text-danger">{{ errors.po_type }}</small>
                    </div>
                    <div v-if="is_fix && mode != 'detail'" class="col-sm-6 no-padding">
                        <button @click='simulate' class="btn btn-flat bg-blue pull-left" type="button">
                          <span >Simulate</span>
                        </button>
                    </div>
                    <div v-if='mode == "detail"'>
                      <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Order</label>
                      <div class="col-sm-4">
                        <input :value='moment(purchase.tanggal_order).format("DD/MM/YYYY")' type="text" class="form-control" disabled>
                      </div>
                    </div>
                  </div>
                  <div v-if='mode == "detail"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode</label>
                    <div class="col-sm-4">
                      <input v-model='purchase.periode' type="text" class="form-control" disabled>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Tanggal Selesai</label>
                    <div class="col-sm-4">
                      <input v-model='purchase.tanggal_selesai' type="text" class="form-control" disabled>
                    </div>
                  </div>
                  <div v-show='is_fix || is_reg' class="form-group">
                    <div v-show='is_fix || is_reg'>
                      <label for="inputEmail3" class="col-sm-2 control-label">Batas Waktu</label>
                      <div v-bind:class="{ 'has-error': error_exist('batas_waktu') }" class="col-sm-4">
                        <input :disabled='mode == "detail"' id='batas_waktu' type="text" class="form-control" readonly>
                        <small v-if="error_exist('batas_waktu')" class="form-text text-danger">{{ get_error('batas_waktu') }}</small>
                      </div>
                    </div>
                  </div>
                  <div v-if='is_urg' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">NRFS</label>
                    <div v-bind:class="{ 'has-error': error_exist('dokumen_nrfs_id') }" class="col-sm-4">
                      <div class="input-group">
                          <input readonly type="text" placeholder="Klik untuk pilih" class="form-control" v-model="purchase.dokumen_nrfs_id">
                          <div class="input-group-btn">
                            <button v-show='nrfs_empty || mode == "detail"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle="modal" data-target="#modal_nrfs_purchase_order"><i class="fa fa-search"></i></button>
                            <button v-show='!nrfs_empty && mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='hapus_nrfs'><i class="fa fa-trash-o"></i></button>
                          </div>
                        </div>
                        <small v-if="error_exist('dokumen_nrfs_id')" class="form-text text-danger">{{ get_error('dokumen_nrfs_id') }}</small>
                    </div>
                  </div>
                  <?php $this->load->view('modal/nrfs_purchase_order') ?>
                  <script>
                    function pilih_nrfs(nrfs){
                      form_.purchase.dokumen_nrfs_id = nrfs.dokumen_nrfs_id;
                      form_.get_nrfs_parts();
                    }
                  </script>
                  <div v-show='is_hlo' class="form-group">
                    <label class="col-sm-2 control-label">Booking Reference</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_booking') }" class="col-sm-4">
                      <div class="input-group">
                          <input readonly type="text" class="form-control" v-model="purchase.id_booking">
                          <div class="input-group-btn">
                            <button :disabled='mode == "edit"' v-show='request_document_empty || mode == "detail" || mode == "edit"' :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle="modal" data-target="#modal-booking-ref"><i class="fa fa-search"></i></button>
                            <button v-show='!request_document_empty && mode != "detail" && mode != "edit"' :disabled='generateByBooking == 1' class="btn btn-flat btn-danger" @click.prevent='hapus_request_document'><i class="fa fa-trash-o"></i></button>
                          </div>
                        </div>
                        <small v-if="error_exist('id_booking')" class="form-text text-danger">{{ get_error('id_booking') }}</small>
                    </div>
                  </div>
                  <?php $this->load->view('modal/booking_reference_purchase_order') ?>
                  <script>
                  function pilihRequestDocument(data) {
                      form_.purchase.id_booking = data.id_booking;
                      form_.purchase.order_to = data.order_to;
                      form_.purchase.nama_dealer_terdekat = data.nama_dealer_terdekat;
                      form_.getRequestDocumentParts();
                  }
                  </script>
                  <div v-show='is_fix' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Pesan untuk bulan</label>
                    <div v-bind:class="{ 'has-error': error_exist('pesan_untuk_bulan') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' v-model='purchase.pesan_untuk_bulan' class="form-control">
                        <option value="">-Pilih-</option>
                        <?php for ($i=1; $i <= 12 ; $i++): ?>
                        <option value="<?= $i ?>"><?= $this->lang->line("month_{$i}", true) ?></option>
                        <?php endfor; ?>
                      </select>
                      <small v-if="error_exist('pesan_untuk_bulan')" class="form-text text-danger">{{ get_error('pesan_untuk_bulan') }}</small>
                    </div>
                  </div>
                  <div class='form-group'>
                    <label for="inputEmail3" class="col-sm-2 control-label">Total Item Price</label>
                    <div class="col-sm-4">
                      <vue-numeric v-model='totalHargaNonFiltered' thousand-separator='.' currency='Rp ' disabled class='form-control'></vue-numeric>
                    </div>
                  </div>
                  <div v-if='mode == "detail" || is_fix' class="form-group">
                    <div v-if='mode == "detail"'>
                      <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                      <div class="col-sm-4">
                        <input v-model='purchase.status' type="text" class="form-control" disabled>
                      </div>
                    </div>
                  </div>
                  <div v-if='mode == "detail" && purchase.status == "Rejected"' class='form-group'>
                    <label for="inputEmail3" class="col-sm-2 control-label">Keterangan</label>
                    <div class="col-sm-4">
                      <input v-model='purchase.alasan_reject' type="text" class="form-control" disabled>
                    </div>   
                  </div>
                  <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-2" v-if="mode=='insert'">
                      <button @click.prevent='<?= $form ?>' type="button" class="btn btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
                    </div>
                    <div class="col-sm-12 col-sm-offset-2" v-if="mode=='edit'">
                      <button @click.prevent='<?= $form ?>' type="button" class="btn btn-sm btn-warning btn-flat"><i class="fa fa-save"></i> Update</button>
                    </div>
                  </div>
                  <div v-if='is_fix || is_reg' class="container no-margin">
                    <div class="row">
                      <div class="col-sm-2 mr-10">
                        <div class="form-group">
                          <label>Part Group</label>
                          <input readonly v-model='checked_part_group' type="text" class="form-control" data-toggle='modal' data-target='#part_group_purchase_order'>
                        </div>
                      </div>
                      <?php $this->load->view('modal/part_group_purchase_order') ?>
                      <div class="col-sm-2 mr-10">
                        <div class="form-group">
                          <label>Search</label>
                          <input :disabled='loading' type="text" class="form-control" v-model="search_filter">
                        </div>
                      </div>
                      <div style="padding-top: 30px;" class="col-sm-2 mr-10">
                        <div class="form-group">
                          <input :disabled='loading' type="checkbox" v-model="sim_part_filter">
                          <label> Show SIM Part</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table">
                      <tr>
                        <td width="3%">No. </td>
                        <td>
                          <span>Part Number</span>
                        </td>
                        <td>
                          <span>Part Deskripsi</span>
                        </td>
                        <td v-if='is_fix || is_reg'>
                          <span>Rank</span>
                        </td>
                        <td v-if='is_fix || is_reg' width='5%'>SIM Part</td>
                        <td v-if='is_fix || is_reg' width='5%'>W-6</td>
                        <td v-if='is_fix || is_reg' width='5%'>W-5</td>
                        <td v-if='is_fix || is_reg' width='5%'>W-4</td>
                        <td v-if='is_fix || is_reg' width='5%'>W-3</td>
                        <td v-if='is_fix || is_reg' width='5%'>W-2</td>
                        <td v-if='is_fix || is_reg' width='5%'>W-1</td>
                        <td v-if='is_fix || is_reg'>Avg. Weekly Demand</td>
                        <td v-if='is_fix || is_reg'>Qty AVS</td>
                        <td v-if='is_fix || is_reg'>Qty On Order</td>
                        <td v-if='is_fix || is_reg'>Qty In Transit</td>
                        <td v-if='is_fix || is_reg'>Stock Days</td>
                        <td v-if='is_fix || is_reg'>Sugessted Order</td>
                        <td v-if='is_fix || is_reg' width='7%'>Adjust Order</td>
                        <td v-if='!is_fix && !is_reg' width='7%'>Qty Order</td>
                        <td width='15%' class="text-right">Harga</td>
                        <td width='15%' class="text-right">Sub total</td>
                        <td width='3%' v-if="mode!='detail' && !is_urg && !is_fix"></td>
                      </tr>
                      <tr v-if="filtered_parts.length > 0" v-for="(part, index) of filtered_parts" v-bind:class="{ 'text-red': melewati_maks_stok(part) }">
                        <td class="align-middle text-right">{{ index + 1 }}.</td>
                        <td class="align-middle">
                          <span>{{ part.id_part }}</span>
                        </td>
                        <td class="align-middle">
                          <span>{{ part.nama_part }}</span>
                        </td>
                        <td v-if='is_fix || is_reg' class="align-middle">
                          <span v-if='part.rank != null'> ({{ part.rank }})</span>
                          <span v-if='part.rank == null'> (-)</span>
                        </td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.sim_part }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.w6 }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.w5 }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.w4 }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.w3 }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.w2 }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.w1 }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.avg_six_weeks }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.stock }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.order_md }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.qty_in_transit }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle">{{ part.stock_days }}</td>
                        <td v-if='is_fix || is_reg' class="align-middle" class="align-middle">
                          <vue-numeric :read-only="true" thousand-separator="." v-model="part.suggested_order" :empty-value="1" />
                        </td>
                        <td class='align-middle'>
                          <vue-numeric :read-only="mode=='detail' || is_urg" class="input-compact" thousand-separator="." v-model="part.kuantitas" :empty-value="1"  v-on:keyup.native="qty_order_change_handler" />
                        </td>
                        <td class="align-middle text-right">
                          <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="harga_setelah_diskon(part)" />
                        </td>
                        <td class="align-middle text-right">
                          <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="subTotal(part)" />
                        </td>
                        <td v-if="mode!='detail' && !is_urg && !is_fix" class="align-middle text-center">
                          <button class="btn btn-sm btn-flat btn-danger" v-on:click.prevent="hapusPart(index)"><i class="fa fa-trash-o"></i></button>
                        </td>
                      </tr>
                      <tr v-if="parts.length > 0">
                        <td class="text-right" v-bind:colspan="item_colspan-2">Total</td>
                        <td class="text-right">
                          <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="totalHargaTanpaPPN" />
                        </td>
                      </tr>
                      <!-- <tr v-if="parts.length > 0">
                        <td class="text-right" v-bind:colspan="item_colspan-2">PPN</td>
                        <td class="text-right">
                          <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="totalPPN" />
                        </td>
                      </tr>
                      <tr v-if="parts.length > 0">
                        <td class="text-right" v-bind:colspan="item_colspan-2">Total</td>
                        <td class="text-right">
                          <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="totalHarga" />
                        </td>
                      </tr> -->
                      <tr v-if="parts.length < 1">
                        <td v-bind:class='{ "bg-red": errors.parts != null }' v-bind:colspan="item_colspan" class="text-center text-muted">Belum ada part</td>
                      </tr>
                    </table>
                  </div>
                  <button v-if="( mode!='detail' && (!is_urg && !is_fix)) || (mode == 'edit' && is_hlo)" style="margin-top:15px" type="button" class="mt-3 pull-right btn btn-flat btn-info btn-sm" data-toggle="modal" data-target="#modal-part"><i class="fa fa-plus"></i></button>
                  <?php $this->load->view('modal/part_purchase_order') ?>
                  <script>
                  function pilihPart(part) {
                      form_.parts.push(part);
                      form_.get_diskon_parts();
                      form_.update_eta_parts();
                  }
                  </script>
                  <div class="box-footer">
                    <div v-if="mode=='detail'">
                    <?php if ($mode == 'detail') : ?>
                      <div class="col-sm-6 no-padding">
                        <a v-if='mode == "detail" && (purchase.status == "Draft" || purchase.status == "Rejected") && auth.can_update' :href="'dealer/h3_dealer_purchase_order/edit?id=' + purchase.po_id" class="btn btn-sm btn-warning btn-flat">Edit</a>
                      </div>
                      <div class="col-sm-6 no-padding text-right">
                          <button v-if='mode == "detail" && purchase.status == "Rejected" && auth.can_reopen' class="btn btn-flat btn-sm btn-success" @click.prevent='reopen_purchase'>Re-Open</button>
                          <button v-if='allowed_submit' class="btn btn-flat btn-sm btn-info" @click.prevent='update_status("Submitted")'>Submit</button>
                          <button v-if='mode == "detail" && purchase.status == "Draft" && auth.can_approval' class="btn btn-flat btn-sm btn-success" @click.prevent='update_status("Approved")'>Approve</button>
                          <button v-if='mode == "detail" && purchase.status == "Draft" && auth.can_cancel' class="btn btn-flat btn-sm btn-danger" @click.prevent='update_status("Canceled")'>Cancel</button>
                          <button v-if='mode == "detail" && purchase.status != "Processed by MD" && purchase.status != "Rejected" && purchase.status != "Closed" && auth.can_reject' class="btn btn-flat btn-sm btn-danger" type='button' data-toggle='modal' data-target='#reject_purchase'>Reject</button>
                          <a v-if='mode == "detail" && purchase.po_type == "HLO" && auth.can_print && !order_to_empty' :href="'dealer/h3_dealer_purchase_order/cetak?id=' + purchase.po_id" class="btn btn-sm btn-primary btn-flat">Cetak PO</a>
                          <a v-if='mode == "detail" && purchase.po_type == "HLO" && auth.can_print && order_to_empty && !purchase_hotline_penomoran_ulang' :href="'dealer/h3_dealer_purchase_order/cetakan_po_hotline_md_non_penomoran_ulang?id=' + purchase.po_id" class="btn btn-sm btn-primary btn-flat">Cetak PO</a>
                          <a v-if='mode == "detail" && purchase.po_type == "HLO" && auth.can_print && order_to_empty && purchase_hotline_penomoran_ulang' :href="'dealer/h3_dealer_purchase_order/cetakan_po_hotline_md_penomoran_ulang?id=' + purchase.po_id" class="btn btn-sm btn-primary btn-flat">Cetak PO</a>
                          <a v-if='mode == "detail" && (purchase.po_type == "REG" || purchase.po_type == "OTHER" || purchase.po_type == "FIX") && auth.can_print ' :href="'h23_api/cetak_po_reg?id=' + purchase.po_id" class="btn btn-sm btn-primary btn-flat">Cetak PO</a>
                          <?php $this->load->view('modal/alasan_reject_purchase_order') ?>
                      </div>
                    <?php endif; ?>
                    </div>
                  </div><!-- /.box-footer -->
              </form>
            </div>
          </div>
        </div>
      </div>
      <script>
        form_ = new Vue({
          el: '#form_',
          data: {
            auth: <?= json_encode(get_user('h3_dealer_purchase_order')) ?>, 
            mode: '<?= $mode ?>',
            search_filter: '',
            sim_part_filter: false,
            errors: {},
            loading: false,
            checked_part_group: [],
            <?php if ($mode == 'detail' or $mode == 'edit') : ?>
            purchase: <?= json_encode($purchase_order) ?>,
            parts: <?= json_encode($parts) ?>,
            <?php else : ?>
            purchase : {
              kategori_po: '',
              id_salesman: '',
              po_type: '<?= $this->input->get('generateByBooking') == 'true' ? 'HLO' : '' ?>',
              pesan_untuk_bulan: '',
              dokumen_nrfs_id: null,
              order_to: null,
              id_booking: null,
              nama_dealer_terdekat: '',
              produk: '',
              total_pembelian: 0,
              total_amount: 0,
              total_amount_po: 0,
              ach: 0,
            },
            parts: [],
            <?php endif; ?>
            dealer: <?= json_encode($dealer) ?>,
            part_group: <?= json_encode($part_group); ?>,
            generateByBooking: <?= $this->input->get('generateByBooking') == null ? 0 : 1; ?>
          },
          mounted: function(){
            <?php if($this->input->get('generateByBooking') == 'true'): ?>
            <?php
              $booking = $this->db
              ->select('rd.id_booking')
              ->select('rd.order_to')
              ->select('order_to.nama_dealer as nama_dealer_terdekat')
              ->from('tr_h3_dealer_request_document as rd')
              ->join('ms_dealer as order_to', 'order_to.id_dealer = rd.order_to', 'left')
              ->where('rd.id_booking', $this->input->get('booking'))
              ->get()->row();
            ?>
            this.purchase.id_booking = '<?= $booking->id_booking ?>';
            this.purchase.order_to = '<?= $booking->order_to ?>';
            this.purchase.nama_dealer_terdekat = '<?= $booking->nama_dealer_terdekat ?>';
            this.getRequestDocumentParts();
            <?php endif; ?>

            if(this.mode != 'insert'){
              this.ambil_data_target_pembelian();
            }
          },
          methods: {
            <?= $form ?>: function(){
              this.loading = true;
              this.errors = {};
              
              post = {};
              if(this.mode == 'edit'){
                post.po_id = this.purchase.po_id;
              }

              post = this.purchase;
              post.total_amount = this.totalHarga;
              post.batas_waktu = $('#batas_waktu').val();
              post.ach = this.ach;

              subTotal_fn = this.subTotal;
              post.parts = _.chain(this.parts).filter(function(p){
                return p.kuantitas > 0;
              })
              .map(function(p){
                part = _.omit(p, [
                  'id_dealer', 'id', 'nama_part', 
                  'kelompok_vendor', 'min_sales', 'safety_stock', 
                  'kelompok_part', 'min_stok', 'maks_stok', 
                  'qty_part', 'dokumen_nrfs_id', 'id_booking', 
                  'harga_md_dealer', 'sim_part', 'id_dealer'
                ]);
                part.tot_harga_part = subTotal_fn(part);
                return part;
              }).value();

              axios.post('dealer/h3_dealer_purchase_order/<?= $form ?>', Qs.stringify(post))
              .then(function(res){
                data = res.data;

                if(data.redirect_url != null) window.location = data.redirect_url;
              })
              .catch(function(err){
                data = err.response.data;
                if (data.error_type == 'validation_error') {
                  form_.errors = data.errors;
                  toastr.error(data.message);
                } else {
                  toastr.error(data.message);
                }

                form_.loading = false;
              });
            },
            update_status: function(status){
              post = {};
              post.status = status;
              post.po_id = this.purchase.po_id;

              this.loading = true;
              axios.post('dealer/h3_dealer_purchase_order/update_status', Qs.stringify(post))
              .then(function(res){
                window.location = 'dealer/h3_dealer_purchase_order/detail?id=' + res.data.po_id;
              })
              .catch(function(e){
                toastr.error(e);
              })
              .then(function(){ form_.loading = false; });
            },
            reopen_purchase: function(){
              post = {};
              post.po_id = this.purchase.po_id;

              this.loading = true;
              axios.post('dealer/h3_dealer_purchase_order/reopen_purchase', Qs.stringify(post))
              .then(function(res){
                window.location = 'dealer/h3_dealer_purchase_order/detail?id=' + res.data.po_id;
              })
              .catch(function(e){
                toastr.error(e);
              })
              .then(function(){ form_.loading = false; })
            },
            reject_purchase: function(){
              post = {};
              post.po_id = this.purchase.po_id;
              post.alasan_reject = $('#alasan_reject').val();
              post.status = 'Rejected';

              this.loading = true;
              axios.post('dealer/h3_dealer_purchase_order/reject', Qs.stringify(post))
              .then(function(res){
                window.location = 'dealer/h3_dealer_purchase_order/detail?id=' + res.data.po_id;
              })
              .catch(function(e){
                toastr.error(e);
              })
              .then(function(){ form_.loading = false; })
            },
            melewati_maks_stok: function(part){
              return false;
              return (Number(part.kuantitas) + Number(part.stock)) > part.maks_stok;
            },
            hapus_request_document: function () {
              this.purchase.id_booking = null;
              this.parts = [];
            },
            hapus_nrfs: function(){
              this.purchase.dokumen_nrfs_id = null;
              this.parts = [];
            },
            hapus_order_to: function(){
              this.purchase.order_to = null;
              this.parts = [];
            },
            simulate: function(){
              this.loading = true;
              params = {};
              params.kategori_po = this.purchase.kategori_po;
              params.produk = this.purchase.produk;
              axios.get('api/suggestedOrder', {
                params: params
              })
              .then(function(response) {
                form_.parts = response.data;
                form_.get_diskon_parts();
              })
              .catch(function(err) {
                toastr.error(err);
              })
              .then(function() {
                form_.loading = false;
              });
            },
            qty_order_change_handler: _.debounce(function(){
              form_.get_diskon_parts();
            }, 500),
            get_nrfs_parts:function(){
              if(this.purchase.dokumen_nrfs_id == '' || this.purchase.dokumen_nrfs_id == null) return;

              this.loading = true;
              axios.get('api/nrfs_part', {
                params: {
                    dokumen_nrfs_id: this.purchase.dokumen_nrfs_id
                }
              }).then(function(res) {
                form_.parts = res.data;
                form_.get_parts_diskon();
                form_.get_parts_sales_campaign();
              }).catch(function(error) {
                toastr.error(error);
              }).then(function(){ form_.loading = false; });
            },
            ambil_data_target_pembelian: function(){
              if(this.purchase.order_to != null && this.purchase.order_to != 0){
                this.purchase.target_pembelian = 0;
                this.purchase.id_salesman = null;
                this.purchase.total_amount_po = 0;
                return;
              }

              this.loading = true;
              params = {
                produk: this.purchase.produk,
                po_type: this.purchase.po_type,
                pesan_untuk_bulan: this.purchase.pesan_untuk_bulan,
              };
              if(this.mode != 'insert'){
                params.exclude_po = [this.purchase.po_id];
                params.tanggal_order = this.purchase.tanggal_order;
              }

              axios.get('dealer/h3_dealer_purchase_order/ambil_data_target_pembelian', {
                params: params
              })
              .then(function(res) {
                data = res.data;
                if(data.id_salesman != null){
                  form_.purchase.target_pembelian = data.total_amount;
                  form_.purchase.id_salesman = data.id_salesman;
                  form_.purchase.total_amount_po = data.total_amount_po;
                }else{
                  form_.purchase.target_pembelian = 0;
                  form_.purchase.id_salesman = null;
                  form_.purchase.total_amount_po = 0;
                }
              })
              .catch(function(err) {
                toastr.error(err);
              })
              .then(function() {
                form_.loading = false;
              });
            },
            harga_setelah_diskon: function(part){
              harga_setelah_diskon = part.harga_saat_dibeli;

              if(part.tipe_diskon == 'Rupiah'){
                harga_setelah_diskon = part.harga_saat_dibeli - part.diskon_value;
              }else if(part.tipe_diskon == 'Persen'){
                diskon = (part.diskon_value/100) * part.harga_saat_dibeli;
                harga_setelah_diskon = part.harga_saat_dibeli - diskon;
              }

              if(part.tipe_diskon_campaign == 'Rupiah'){
                harga_setelah_diskon = harga_setelah_diskon - part.diskon_value_campaign;
              }else if(part.tipe_diskon_campaign == 'Persen'){
                diskon = (part.diskon_value_campaign/100) * harga_setelah_diskon;
                harga_setelah_diskon = harga_setelah_diskon - diskon;
              }

              return harga_setelah_diskon;
            },
            subTotal: function(part) {
              harga_setelah_diskon = this.harga_setelah_diskon(part);

              return part.kuantitas * harga_setelah_diskon;
            },
            get_parts_diskon: function(){
              if(this.parts.length < 1 || this.purchase.po_type == '') return;

              this.reset_diskon_dealer();

              this.loading = true;
              axios.get('dealer/<?= $isi ?>/get_parts_diskon', {
                params: {
                    id_part: _.map(this.parts, function(p){
                      return p.id_part
                    }),
                    po_type: this.purchase.po_type,
                    id_dealer: <?= $this->m_admin->cari_dealer() ?>,
                    produk: this.purchase.produk
                }
              }).then(function(res) {
                for(data of res.data){
                  index = _.findIndex(form_.parts, function(p) {
                    return p.id_part == data.id_part;
                  });

                  form_.parts[index].tipe_diskon = data.tipe_diskon;
                  form_.parts[index].diskon_value = data.diskon_value;
                }
              }).catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ form_.loading = false; });
            },
            get_parts_sales_campaign: function(){
              if(this.parts.length < 1) return;

              this.reset_diskon_sales_campaign();

              this.loading = true;
              post = {};
              post.order = _.map(this.parts, function(part){
                return {
                  id_part: part.id_part,
                  qty_order: part.kuantitas
                };
              });
              axios.post('dealer/<?= $isi ?>/get_parts_sales_campaign', Qs.stringify(post)).then(function(res) {
                for(data of res.data){
                  index = _.findIndex(form_.parts, function(p) {
                    return p.id_part == data.id_part;
                  });

                  form_.parts[index].tipe_diskon_campaign = data.tipe_diskon;
                  form_.parts[index].diskon_value_campaign = data.diskon_value;
                  form_.parts[index].id_campaign_diskon = data.id;
                  form_.parts[index].jenis_diskon_campaign = data.jenis_diskon_campaign;
                }
              }).catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ form_.loading = false; });
            },
            reset_diskon_sales_campaign: function(){
              for (var index = 0; index < this.parts.length; index++) {
                this.parts[index].tipe_diskon_campaign = '';
                this.parts[index].diskon_value_campaign = '';
              }
            },
            reset_diskon_dealer: function(){
              for (var index = 0; index < this.parts.length; index++) {
                this.parts[index].tipe_diskon = '';
                this.parts[index].diskon_value = '';
              }
            },
            get_parts_diskon_oli_reguler: function(){
              if(this.parts.length < 1 || this.purchase.id_dealer == '') return;

              this.reset_diskon_dealer();

              this.loading = true;
              post = {};
              post.id_dealer = <?= $this->m_admin->cari_dealer() ?>;
              post.parts = _.map(this.parts, function(p){
                return {
                  id_part: p.id_part,
                  kuantitas: p.kuantitas
                };
              });

              axios.post('dealer/<?= $isi ?>/get_parts_diskon_oli_reguler', Qs.stringify(post)).then(function(res) {
                for(data of res.data){
                  index = _.findIndex(form_.parts, function(p) {
                    return p.id_part == data.id_part;
                  });

                  form_.parts[index].tipe_diskon = data.tipe_diskon;
                  form_.parts[index].diskon_value = data.diskon_value;
                }
              }).catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ form_.loading = false; });
            },
            get_parts_diskon_oli_kpb: function(){
              if(this.parts.length < 1) return;

              this.reset_diskon_dealer();

              this.loading = true;
              post = {};
              post.parts = _.map(this.parts, function(p){
                return {
                  id_part: p.id_part,
                  id_tipe_kendaraan: p.id_tipe_kendaraan,
                  kuantitas: p.qty_order
                };
              });

              axios.post('dealer/<?= $isi ?>/get_parts_diskon_oli_kpb', Qs.stringify(post))
              .then(function(res) {
                for(data of res.data){
                  index = _.findIndex(form_.parts, function(p) {
                    return p.id_part == data.id_part;
                  });

                  form_.parts[index].tipe_diskon = data.tipe_diskon;
                  form_.parts[index].diskon_value = data.diskon_value;
                }
              }).catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ form_.loading = false; });
            },
            get_diskon_parts: function(){
              if(!this.is_hlo && !this.is_urg){
                if(this.produk_oli){
                  if(this.kategori_kpb){
                    this.get_parts_diskon_oli_kpb();
                  }else{
                    this.get_parts_diskon_oli_reguler();
                  }
                }else{
                  this.get_parts_diskon();
                }
              }
              
              if(!this.kategori_kpb && !this.is_hlo && !this.is_urg){
                this.get_parts_sales_campaign();
              }
            },
            getRequestDocumentParts: function(){
              this.loading = true;
              axios.get('dealer/h3_dealer_request_document/getRequestDocumentParts', {
                params: {
                    id_booking: this.purchase.id_booking,
                    po_type: this.purchase.po_type,
                }
              }).then(function(res) {
                form_.parts = _.map(res.data, function(p){
                  return _.omit(p, ['eta_revisi']);
                });
                form_.get_parts_diskon();
                form_.get_parts_sales_campaign();
              }).catch(function(error) {
                toastr.error(error);
              })
              .then(function(){ form_.loading = false; });
            },
            update_eta_parts: function() {
              if(!this.is_hlo || this.parts.length < 1) return;

              this.reset_eta();

              post = {};
              post.claim = this.purchase.penomoran_ulang;
              if (this.purchase.tipe_penomoran_ulang == 'claim_c1_c2') {
                post.tipe_claim = 'renumbering_claim';
              } else if (this.purchase.tipe_penomoran_ulang == 'non_claim') {
                post.tipe_claim = 'renumbering_non_claim';
              }
              post.parts = _.chain(this.parts)
              .map(function(part){
                return part.id_part;
              })
              .value();

              axios.post('dealer/h3_dealer_request_document/update_eta_parts', Qs.stringify(post))
              .then(function(res) {
                if(res.data.length > 0){
                  for (row of res.data) {
                    index_part = _.findIndex(form_.parts, function(part) { return part.id_part == row.id_part; });
                    if(index_part != -1){
                      form_.parts[index_part].eta_terlama = row.eta_terlama;                
                      form_.parts[index_part].eta_tercepat = row.eta_tercepat;
                    }
                  }
                }
              })
              .catch(function(e) {
                toastr.error(e);
              });
            },
            reset_eta: function(){
              for (index = 0; index < this.parts.length; index++) {
                this.parts[index].eta_terlama = null;                
                this.parts[index].eta_tercepat = null;                
              }
            },
            hapusPart: function(index) {
              this.parts.splice(index, 1);
              this.update_eta_parts();
            },
            error_exist: function(key){
              return _.get(this.errors, key) != null;
            },
            get_error: function(key){
              return _.get(this.errors, key)
            }
          },
          computed: {
            allowed_submit: function(){
              if(this.is_hlo && this.purchase.no_inv_uang_jaminan == null){
                return false;
              }

              return this.mode == "detail" && this.purchase.status == "Approved" && this.auth.can_submit;
            },
            is_urg: function(){
              return this.purchase.po_type == 'URG';
            },
            is_fix: function(){
              return this.purchase.po_type == 'FIX';
            },
            is_hlo: function(){
              return this.purchase.po_type == 'HLO';
            },
            is_other: function(){
              return this.purchase.po_type == 'OTHER';
            },
            is_reg: function(){
              return this.purchase.po_type == 'REG';
            },
            produk_oli: function () {
              return this.purchase.produk == "Oil";
            },
            kategori_kpb: function () {
              return this.purchase.kategori_po == "KPB";
            },
            item_colspan: function(){
              if(this.is_fix || this.is_reg){
                return 21;
              }
              return 7;
            },
            totalHargaTanpaPPN: function() {
              total = 0;
              for (part of this.filtered_parts) {
                total += this.subTotal(part);
              }
              return total;
            },
            totalPPN: function (){
              return 0;
              return (10/100) * this.totalHargaTanpaPPN;
            },
            totalHarga: function() {
              return this.totalHargaTanpaPPN + this.totalPPN;
            },
            totalHargaNonFiltered: function(){
              total = 0;
              for (part of this.parts) {
                total += this.subTotal(part);
              }
              return total;
              return total + ((10/100) * total);
            },
            total_amount_po_for_ach: function(){
              return parseFloat(this.totalHarga) + parseFloat(this.purchase.total_amount_po);
            },
            ach: function(){
              if(this.purchase.target_pembelian != 0){
                ach = this.total_amount_po_for_ach / this.purchase.target_pembelian;
                ach = ach > 1 ? 1 : ach;
                ach = ach * 100;
                return ach;
              }else{
                return 0;
              }
            },
            request_document_empty: function(){
              return this.purchase.id_booking == null;
            },
            purchase_hotline_penomoran_ulang: function(){
              return this.purchase.penomoran_ulang != null && this.purchase.penomoran_ulang != 0;
            },
            nrfs_empty: function(){
              return this.purchase.dokumen_nrfs_id == null;
            },
            order_to_empty: function(){
              return this.purchase.order_to == null || this.purchase.order_to == 0;
            },
            filtered_parts: function(){
              checked_part_group = this.checked_part_group
              filtered =  _.filter(this.parts, function(part){
                if(checked_part_group.length > 0){
                  return _.includes(checked_part_group, part.kelompok_part);
                }else{
                  return true;
                }
              });

              search_filter = this.search_filter;
              filtered = _.filter(filtered, function(part){
                return part.id_part.toLowerCase().includes(search_filter.toLowerCase())
              });

              if(this.sim_part_filter){
                filtered = _.filter(filtered, function(part){
                  return part.sim_part > 0;
                });
              }

              return filtered;
            }
          },
          watch: {
            'purchase.po_type': function(val) {
              this.parts = [];
              this.purchase.dokumen_nrfs_id = null;
              this.purchase.id_booking = null;
              this.purchase.order_to = null;
              this.purchase.nama_dealer_terdekat = '';
              this.purchase.pesan_untuk_bulan = '';
              if(this.is_fix || this.is_reg){
                $('#batas_waktu').datepicker({ format: 'yyyy-mm-dd' });
              }else{
                $('#batas_waktu').datepicker('remove');
              }
              datatable_part.draw();
            },
            'purchase.produk': function(data){
              if(this.is_fix && this.parts.length > 0){
                this.simulate();
              }
              this.ambil_data_target_pembelian();
              datatable_part.draw();
            },
            'purchase.kategori_po': function(data){
              if(this.is_fix && this.parts.length > 0){
                this.simulate();
              }
              datatable_part.draw();
            },
            'purchase.pesan_untuk_bulan': function(data){
              this.ambil_data_target_pembelian();
            },
            totalHargaTanpaPPN: function(data){
              this.ambil_data_target_pembelian();
            },
            parts: {
              deep: true,
              handler: function(){
                datatable_part.draw();
              }
            }
          }
        });

        $(document).ready(function(){
          if(form_.mode == 'detail' || form_.mode == 'edit'){
            $('#batas_waktu').datepicker({ format: 'yyyy-mm-dd' });
            $('#batas_waktu').val(form_.purchase.batas_waktu);
          }
        });
      </script>
    <?php
    } elseif ($set == "index") {
    ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <?php if(can_access('h3_dealer_purchase_order', 'can_insert')): ?>
            <a href="dealer/<?= $isi ?>/add">
              <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
            </a>
            <?php endif; ?>
          </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <link rel="stylesheet" href="assets/css-progress-wizard-master/css/progress-wizard.min.css">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-2">
                <div class="form-group">
                  <label for="" class="control-label">Tipe PO</label>
                  <select id="filter_tipe_po" class='form-control'>
                    <option value="">All</option>
                    <option value="reg">Reguler</option>
                    <option value="fix">Fix</option>
                    <option value="urg">Urgent</option>
                    <option value="hlo">Hotline</option>
                  </select>
                  <script>
                    $(document).ready(function(){
                      $('#filter_tipe_po').on('change', function(e){
                        purchase_order.draw();
                      })
                    })
                  </script>
                </div>
              </div>
              <div class="col-sm-2">
                <div class="form-group">
                  <label for="" class="control-label">Filter Status</label>
                  <select id="filter_status_po" class='form-control'>
                    <option value="">All</option>
                    <option value="Submitted">Submitted</option>
                    <option value="Draft">Draft</option>
                    <option value="Approved">Approved</option>
                    <option value="Processed by MD">Processed by MD</option>
                    <option value="Closed">Closed</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Canceled">Canceled</option>
                  </select>
                  <script>
                    $(document).ready(function(){
                      $('#filter_status_po').on('change', function(e){
                        purchase_order.draw();
                      })
                    })
                  </script>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="form-group">
                  <label for="" class="control-label">Periode</label>
                  <input id='filter_periode_purchase_order' type="text" class='form-control' readonly>
                  <input type="hidden" id="filter_periode_purchase_order_start">
                  <input type="hidden" id="filter_periode_purchase_order_end">
                </div>
                <script>
                  $('#filter_periode_purchase_order').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                      format: 'DD/MM/YYYY'
                    }
                  }, function(start, end, label) {
                    $('#filter_periode_purchase_order_start').val(start.format('YYYY-MM-DD'));
                    $('#filter_periode_purchase_order_end').val(end.format('YYYY-MM-DD'));
                    purchase_order.draw();
                  }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                  }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    $('#filter_periode_purchase_order_start').val('');
                    $('#filter_periode_purchase_order_end').val('');
                    purchase_order.draw();
                  });
                </script>
              </div>
              <div class="col-sm-2">
                  <div class="form-group">
                      <label class="control-label">&nbsp</label>
                      <br>
                      <button type="button" class="btn btn-primary btn-sm" id="btn-cari"><span class="fa fa-search"></span></button>
                  </div>
              </div>
            </div>
          </div>
          <table id="purchase_order" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>No.</th>
                <th>PO ID</th>
                <th>Periode</th>
                <th>PO Type</th>
                <!--<th>Dealer</th>-->
                <th>Nama Customer</th>
                <th>ID Booking</th>
                <th>Qty Order</th>
                <th>Qty Item</th>
                <th>Fulfillment Qty</th>
                <th>Fulfillment Rate</th>
                <th>Tanggal Order</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
           <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
          <script>
            $(document).ready(function() {
                      purchase_order = $('#purchase_order').DataTable({
                      processing: true,
                      serverSide: true,
                      ordering: false,
                      order: [],
                      language: {
                        infoFiltered: "",
                        processing: "Processing",
                       },
                      lengthMenu: [10, 25, 50, 75, 100 ],
                      scrollX: true,
                      ajax: {
                          url: "<?= base_url('api/dealer/purchase_order_new') ?>",
                          dataSrc: "data",
                          type: "POST",
                          data: function(data) {
                              data.filter_status = $('#filter_status_po').val();
                              data.filter_tipe_po = $('#filter_tipe_po').val();
    
                              start_date = $('#filter_periode_purchase_order_start').val();
                              end_date = $('#filter_periode_purchase_order_end').val();
                              if ((start_date != undefined && start_date != '') && (end_date != undefined && end_date != '')) {
                                  data.filter_purchase_date = true;
                                  data.start_date = start_date;
                                  data.end_date = end_date;
                              }
                          }
                      },
                      createdRow: function(row, data, index) {
                          $('td', row).addClass('align-middle');
                      },
                      columns: [
                        { data: 'index', orderable: false, width: '3%' }, 
                        { data: 'aksi' }, 
                        { data: 'periode' }, 
                        { data: 'po_type' }, 
                        //{ data: 'dealer' }, 
                        { 
                          data: 'nama_customer',
                          render: function(data){
                            if(data != null){
                              return data;
                            }
                            return '-';
                          }
                        }, 
                        {data:'id_booking'},
                        { data: 'unit_qty', align:'center' }, 
                        { data: 'item_qty' , align:'center'}, 
                        { data: 'fulfillment_qty' , align:'center' }, 
                        { data: 'fulfillment_rate', align:'center' }, 
                        { data: 'tanggal_order' }, 
                        { data: 'tanggal_selesai' }, 
                        { data: 'status', width: '30%' },
                      ],
                      
                  });
            });
                
        $('#btn-cari').click(function(){
            purchase_order.draw();
        });
            
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div>