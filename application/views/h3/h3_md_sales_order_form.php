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
<div id="app" class="box box-default">
  <div class="box-header with-border">
    <!-- <h3 class="box-title">
      <a href="h3/<?= $isi ?>">
        <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
      </a>
    </h3> -->
    <div class="col-md-6">
      <a href="h3/<?= $isi ?>">
        <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
      </a>
    </div>
    <?php if($mode == 'detail' AND $sales_order['status'] != 'Canceled' AND $sales_order['status'] != 'Closed'){ ?>
      <div class="col-md-6 text-right">
        <a href="h3/<?= $isi ?>/update_harga?id_sales_order=<?= $sales_order['id_sales_order'] ?>">
          <button class="btn bg-primary btn-flat margin">Update Harga</button>
        </a>
      </div>
    <?php }elseif(!empty($do) AND ($do['status'] == 'On Process' OR $do['status'] == 'Approved' OR $do['status'] == 'Picking List' OR $do['status'] == 'Closed Scan')){ ?>
      <div class="col-md-6 text-right">
        <a href="h3/<?= $isi ?>/update_harga?id_sales_order=<?= $sales_order['id_sales_order'] ?>">
          <button class="btn bg-primary btn-flat margin">Update Harga</button>
        </a>
      </div>
    <?php } ?>
  </div>
  <!-- /.box-header -->
  <div v-if="loading" class="overlay">
    <i class="fa fa-refresh fa-spin text-light-blue"></i>
  </div>
  <div class="box-body">
    <?php $this->load->view('template/normal_session_message.php'); ?>
    <div class="row">
      <div class="col-md-12">
        <form class="form-horizontal">
          <div class="box-body">
            <div v-if='mode != "insert"' class="form-group">
              <label class="col-sm-2 control-label">No SO</label>
              <div class="col-sm-4">
                <input type="text" readonly class="form-control" v-model="sales_order.id_sales_order" />
              </div>
              <label class="col-sm-2 control-label">Tanggal SO</label>
              <div class="col-sm-4">
                <input type="text" readonly class="form-control" :value="moment(sales_order.tanggal_order).format('DD/MM/YYYY')" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Nama Customer</label>
              <div v-bind:class="{ 'has-error': error_exist('id_dealer') }" class="col-sm-4">
                <div class="input-group">
                  <input v-model='sales_order.nama_dealer' type="text" class="form-control" disabled>
                  <div class="input-group-btn">
                    <button :disabled='mode == "detail" || generateByPO || sales_order_gimmick || sales_order_ekspedisi || sales_order_logistik || !sales_order_create_by_md || sales_order_rekap || kategori_kpb || kategori_bundling' class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_dealer_sales_order'>
                      <i class="fa fa-search"></i>
                    </button>
                  </div>
                </div>
                <small v-if="error_exist('id_dealer')" class="form-text text-danger">{{ get_error('id_dealer') }}</small>
              </div>
              <?php $this->load->view('modal/h3_md_dealer_sales_order'); ?>
              <script>
                function pilih_dealer_sales_order(data) {
                  app.sales_order.id_dealer = data.id_dealer;
                  app.sales_order.nama_dealer = data.nama_dealer;
                  app.sales_order.kode_dealer_md = data.kode_dealer_md;
                  app.sales_order.alamat = data.alamat;
                  app.sales_order.batas_waktu = data.batas_waktu;
                }
              </script>
              <label class="col-sm-2 control-label">Kode Customer</label>
              <div class="col-sm-4">
                <input type="text" readonly class="form-control" v-model="sales_order.kode_dealer_md" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Tipe PO</label>
              <div v-bind:class="{ 'has-error': error_exist('po_type') }" class="col-sm-2">
                <select :disabled='mode != "insert" || generateByPO || sales_order_gimmick || sales_order_ekspedisi || sales_order_logistik || sales_order_rekap' class="form-control" v-model="sales_order.po_type">
                  <option value="">-Pilih-</option>
                  <option value="FIX">Fix</option>
                  <option value="REG">Reguler</option>
                  <option value="URG">Urgent</option>
                  <option value="HLO">Hotline</option>
                </select>
                <small v-if="error_exist('po_type')" class="form-text text-danger">{{ get_error('po_type') }}</small>
              </div>
              <div class="col-sm-2 no-padding">
                <button v-if='sales_order.po_type == "FIX" && this.mode != "detail"' @click.prevent='generate_parts_auto_fix' class="btn btn-flat btn-sm btn-success" id="simulate_auto">Simulate Autofulfillment-FIX</button>
                <button v-if='sales_order.po_type == "REG" && this.mode != "detail"' @click.prevent='generate_parts_auto_reg' class="btn btn-flat btn-sm btn-primary">Simulate Autofulfillment-REG</button>
              </div>
              <label class="col-sm-2 control-label">Alamat Customer</label>
              <div class="col-sm-4">
                <input type="text" readonly class="form-control" v-model="sales_order.alamat" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Masa Berlaku</label>
              <div class="col-sm-4">
                <input v-if='sales_order.batas_waktu != "" && sales_order.batas_waktu != null' type="text" readonly class="form-control" :value='moment(sales_order.batas_waktu).format("DD/MM/YYYY")' />
                <input v-if='sales_order.batas_waktu == "" || sales_order.batas_waktu == null' type="text" readonly class="form-control" value='-' />
              </div>
              <label class="col-sm-2 control-label">Jenis Pembayaran</label>
              <div v-bind:class="{ 'has-error': error_exist('jenis_pembayaran') }" class="col-sm-4">
                <select :disabled='mode == "detail" || sales_order_gimmick' class="form-control" v-model="sales_order.jenis_pembayaran">
                  <option value="">-Pilih-</option>
                  <option value="Credit">Credit</option>
                  <option value="Tunai">Tunai</option>
                </select>
                <small v-if="error_exist('jenis_pembayaran')" class="form-text text-danger">{{ get_error('jenis_pembayaran') }}</small>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Kategori PO</label>
              <div v-bind:class="{ 'has-error': error_exist('kategori_po') }" class="col-sm-4">
                <select :disabled='kategori_po_can_disable' class="form-control" v-model="sales_order.kategori_po">
                  <option value="">-Pilih-</option>
                  <option value="SIM Part">SIM Part</option>
                  <option value="Non SIM Part">Non SIM Part</option>
                  <option v-if='mode != "insert"' value="KPB">KPB</option>
                </select>
                <small v-if="error_exist('kategori_po')" class="form-text text-danger">{{ get_error('kategori_po') }}</small>
              </div>
              <div>
                <label class="col-sm-2 control-label">Nama Salesman</label>
                <div v-bind:class="{ 'has-error': error_exist('id_salesman') }" class="col-sm-4">
                  <div class="input-group">
                    <input v-model='sales_order.nama_salesman' type="text" class="form-control" disabled>
                    <div class="input-group-btn">
                      <button :disabled='mode == "detail" || sales_order_logistik || kategori_kpb' class="btn btn-flat btn-primary" type="button" data-toggle='modal' data-target='#h3_md_salesman_sales_order'>
                        <i class="fa fa-search"></i>
                      </button>
                    </div>
                  </div>
                  <small v-if="error_exist('id_salesman')" class="form-text text-danger">{{ get_error('id_salesman') }}</small>
                </div>
              </div>
              <?php $this->load->view('modal/h3_md_salesman_sales_order'); ?>
              <script>
                function pilih_salesman_sales_order(data) {
                  app.sales_order.id_salesman = data.id_salesman;
                  app.sales_order.nama_salesman = data.nama_lengkap;
                }
              </script>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Produk</label>
              <div v-bind:class="{ 'has-error': error_exist('produk') }" class="col-sm-4">
                <select :disabled='mode != "insert" || generateByPO || sales_order_gimmick || sales_order_logistik' class="form-control" v-model="sales_order.produk">
                  <option value="">-Pilih-</option>
                  <option value="Parts">Parts</option>
                  <option value="Oil">Oil</option>
                  <option value="Acc">Acc</option>
                  <option value="Apparel">Apparel</option>
                  <option value="Tools">Tools</option>
                  <option value="Other">Other</option>
                </select>
                <small v-if="error_exist('produk')" class="form-text text-danger">{{ get_error('produk') }}</small>
              </div>
              <label class="col-sm-2 control-label">Target Customer</label>
              <div v-bind:class="{ 'has-error': error_exist('target_customer') }" class="col-sm-4">
                <vue-numeric class="form-control" v-model='sales_order.target_customer' currency='Rp' separator='.' disabled></vue-numeric>
                <small v-if="error_exist('target_customer')" class="form-text text-danger">{{ get_error('target_customer') }}</small>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">EV/Non EV</label>
              <div v-bind:class="{ 'has-error': error_exist('is_ev') }" class="col-sm-4">
                <select :disabled='mode != "insert" || generateByPO || sales_order_gimmick || sales_order_logistik' class="form-control" v-model="sales_order.is_ev">
                  <option value="">-Pilih-</option>
                  <option value="0">Non EV</option>
                  <option value="1">EV</option>
                </select>
                <small v-if="error_exist('is_ev')" class="form-text text-danger">{{ get_error('is_ev') }}</small>
              </div>
              <label class="col-sm-2 control-label">Actual SO</label>
              <div v-bind:class="{ 'has-error': error_exist('sales_order_target') }" class="col-sm-4">
                <vue-numeric class="form-control" v-model='sales_order.sales_order_target' currency='Rp' separator='.' disabled></vue-numeric>
                <small v-if="error_exist('sales_order_target')" class="form-text text-danger">{{ get_error('sales_order_target') }}</small>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Tipe Source</label>
              <div v-bind:class="{ 'has-error': error_exist('tipe_source') }" class="col-sm-4">
                <select :disabled='mode == "detail" || generateByPO || sales_order_gimmick || sales_order_ekspedisi || sales_order_logistik' class="form-control" v-model="sales_order.tipe_source">
                  <option value="">-Pilih-</option>
                  <option value="Dealer">Dealer</option>
                  <option value="Toko">Toko</option>
                </select>
                <small v-if="error_exist('tipe_source')" class="form-text text-danger">{{ get_error('tipe_source') }}</small>
              </div>
              <label class="col-sm-2 control-label">% Ach SO</label>
              <div v-bind:class="{ 'has-error': error_exist('persentase_sales_order_target') }" class="col-sm-4">
                <vue-numeric class="form-control" v-model='sales_order.persentase_sales_order_target' currency-symbol-position='suffix' separator='.' currency='%' precision='1' disabled></vue-numeric>
                <small v-if="error_exist('persentase_sales_order_target')" class="form-text text-danger">{{ get_error('persentase_sales_order_target') }}</small>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2">Plafon</label>
              <div class="col-sm-4">
                <vue-numeric class="form-control" readonly v-model='sales_order.plafon' currency='Rp' separator='.'></vue-numeric>
              </div>
              <label class="col-sm-2 control-label">Actual Sales Out</label>
              <div v-bind:class="{ 'has-error': error_exist('sales_order_out_target') }" class="col-sm-4">
                <vue-numeric class="form-control" v-model='sales_order.sales_order_out_target' currency='Rp' separator='.' disabled></vue-numeric>
                <small v-if="error_exist('sales_order_out_target')" class="form-text text-danger">{{ get_error('sales_order_out_target') }}</small>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2">Sisa Plafon</label>
              <div class="col-sm-4">
                <vue-numeric class="form-control" readonly v-model='sisa_plafon' currency='Rp' separator='.'></vue-numeric>
              </div>
              <label class="col-sm-2 control-label">% Ach Sales Out</label>
              <div v-bind:class="{ 'has-error': error_exist('persentase_sales_order_out_target') }" class="col-sm-4">
                <vue-numeric class="form-control" v-model='sales_order.persentase_sales_order_out_target' separator='.' currency-symbol-position='suffix' currency='%' precision='1' disabled></vue-numeric>
                <small v-if="error_exist('persentase_sales_order_out_target')" class="form-text text-danger">{{ get_error('persentase_sales_order_out_target') }}</small>
              </div>
            </div>
            <!-- <div class="form-group">
              <label class="col-sm-2 control-label">Autofulfillment MD ? </label>
              <div class="col-sm-4">
               <input disabled type="checkbox" id="input_autofulfillment_md" v-model='sales_order.autofulfillment_md' true-value='1' false-value='0'>
              </div>
              <label class="col-sm-2 col-sm-offset-6 control-label">% Ach Sales Out</label>
              <div v-bind:class="{ 'has-error': error_exist('persentase_sales_order_out_target') }" class="col-sm-4">
                <vue-numeric class="form-control" v-model='sales_order.persentase_sales_order_out_target' separator='.' currency-symbol-position='suffix' currency='%' precision='1' disabled></vue-numeric>
                <small v-if="error_exist('persentase_sales_order_out_target')" class="form-text text-danger">{{ get_error('persentase_sales_order_out_target') }}</small>
              </div>
            </div> -->

            <div class="form-group">
              <label class="col-sm-2 control-label">Autofulfillment MD ? </label>
              <div class="col-sm-4" v-if="mode=='detail'">
              <input type="checkbox" disabled id="input_autofulfillment_md" v-model='sales_order.autofulfillment_md' true-value='1' false-value='0'>
              </div>
              <div class="col-sm-4" v-else>
                <input type="checkbox" id="input_autofulfillment_md" v-model='sales_order.autofulfillment_md' true-value='1' false-value='0'>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Barang Hadiah ? </label>
              <div class="col-sm-4" v-if="mode=='detail'">
               <input type="checkbox" disabled id="input_barang_hadiah" v-model='sales_order.is_hadiah' true-value='1' false-value='0'>
              </div>
              <div class="col-sm-4" v-else>
               <input type="checkbox" id="input_barang_hadiah" v-model='sales_order.is_hadiah' true-value='1' false-value='0'>
              </div>
            </div>
            <div v-if='mode == "detail"' class="form-group">
              <label class="col-sm-2 control-label">No PO Dealer</label>
              <div class="col-sm-4">
                <!-- <input type="text" readonly class="form-control" v-model="sales_order.id_ref" /> -->
                <input type="text" readonly class="form-control" v-model="po_id.id_ref" />
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-12">
                <table id="table" class="table table-condensed table-responsive">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Part Number</th>
                      <th>Nama Part</th>
                      <th v-if='kategori_kpb'>Tipe Kendaraan</th>
                      <th>HET</th>
                      <th>Disc. Dealer</th>
                      <th>Disc. Campaign</th>
                      <th v-if="kategori_sim_part">Qty SIM Part Dealer</th>
                      <th class='text-right'>Qty Actual Dealer</th>
                      <th class='text-right'>Qty AVS</th>
                      <th class='text-right' width='10%'>Qty Order</th>
                      <th v-if="sales_order.created_by_md == 0" class='text-right'>Qty Terpenuhi</th>
                      <th width="15%" class="text-right">Nilai (Amount)</th>
                      <th v-if='mode != "detail" && !generateByPO && !sales_order_gimmick && sales_order_create_by_md && !sales_order_rekap && !kategori_kpb && !kategori_bundling' width="3%"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(part, index) in parts">
                      <td class="align-middle">{{ index + 1 }}.</td>
                      <td class="align-middle">{{ part.id_part }}</td>
                      <td class="align-middle">{{ part.nama_part }}</td>
                      <td v-if='kategori_kpb' class='align-middle'>{{ part.id_tipe_kendaraan }}</td>
                      <td class="align-middle">
                        <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.harga" currency="Rp " />
                      </td>
                      <td class="align-middle">
                        <vue-numeric read-only v-bind:precision="2" v-model="part.diskon_value" :currency='get_currency_symbol(part.tipe_diskon)' :currency-symbol-position='get_currency_position(part.tipe_diskon)' thousand-separator='.' class='input-compact' />
                      </td>
                      <td class="align-middle">
                        <vue-numeric read-only v-bind:precision="2" v-model="part.diskon_value_campaign" :currency='get_currency_symbol(part.tipe_diskon_campaign)' :currency-symbol-position='get_currency_position(part.tipe_diskon_campaign)' separator='.' />
                      </td>
                      <td v-if="kategori_sim_part" class="align-middle">
                        <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_sim_part" />
                      </td>
                      <td class="align-middle text-right">
                        <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_actual_dealer" />
                      </td>
                      <td class="align-middle text-right">
                        <vue-numeric :read-only="true" class="form-control" separator="." :empty-value="1" v-model="part.qty_avs" />
                      </td>
                      <td class="align-middle text-right">
                        <vue-numeric :read-only="mode == 'detail' || generateByPO || sales_order_gimmick || sales_order_ekspedisi || sales_order_logistik || !sales_order_create_by_md || sales_order_rekap || kategori_kpb || kategori_bundling" class="form-control" separator="." :empty-value="1" v-model="part.qty_order" v-on:keyup.native="qty_order_change_handler" />
                      </td>
                      <td v-if="sales_order.created_by_md == 0" class="align-middle text-right">
                        <vue-numeric :read-only="mode == 'detail' || generateByPO || !sales_order_create_by_md" class="form-control" separator="." :empty-value="1" v-model="part.qty_pemenuhan" />
                      </td>
                      <td width="8%" class="align-middle text-right">
                        <vue-numeric :read-only="true" currency="Rp " thousand-separator="." v-model="sub_total(part)" />
                      </td>
                      <td v-if='mode != "detail" && !generateByPO && !sales_order_gimmick && !sales_order_ekspedisi && !sales_order_logistik && sales_order_create_by_md && !sales_order_rekap && !kategori_kpb && !kategori_bundling' class="align-middle">
                        <button class="btn btn-flat btn-danger" @click.prevent="hapus_part(index)"><i class="fa fa-trash-o"></i></button>
                      </td>
                    </tr>
                    <tr v-if="parts.length > 0">
                      <td class="text-right" :colspan="total_label_colspan">Total</td>
                      <td class="text-right">
                        <vue-numeric :read-only="true" class="form-control" separator="." v-model="total" currency="Rp"></vue-numeric>
                      </td>
                    </tr>
                    <tr v-if="parts.length < 1">
                      <td class="text-center" colspan="15">Belum ada part</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div v-if="mode != 'detail' && !generateByPO && sales_order_create_by_md" class="form-group">
              <div class="col-sm-12 text-right">
                <button v-if='sales_order.id_dealer != "" && !sales_order_gimmick && !sales_order_ekspedisi && !sales_order_logistik && !sales_order_rekap && !kategori_kpb && !kategori_bundling' type="button" class="btn btn-flat btn-primary btn-sm" data-toggle="modal" data-target="#h3_md_parts_sales_order"><i class="fa fa-plus"></i></button>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <?php $this->load->view('modal/h3_md_parts_sales_order'); ?>
          <?php $this->load->view('modal/h3_md_view_tipe_motor_part_sales_order'); ?>
          <script>
            function pilih_parts_sales_order(part) {
              app.parts.push(part);
              app.get_diskon_parts();
              // h3_md_parts_sales_order_datatable.draw();
              drawing_so_part();
            }

            function open_view_tipe_motor_part_sales_order_modal(id_part) {
              $('#id_part_untuk_view_tipe_motor').val(id_part);
              // h3_md_view_tipe_motor_part_sales_order_datatable.draw();
              drawing_tipe_motor_pso();
              $('#h3_md_view_tipe_motor_part_sales_order').modal('show');
            }
          </script>
          <input type="hidden" id='id_part_untuk_view_tipe_motor'>
          <div class="box-footer">
            <div class="col-sm-6 no-padding">
              <button v-if="mode == 'insert'" @click.prevent="<?= $form ?>" class="btn btn-sm btn-info btn-flat"><i class="fa fa-save"></i> Save All</button>
              <button v-if="mode == 'edit'" @click.prevent="<?= $form ?>" class="btn btn-sm btn-warning btn-flat">Update</button>
              <a v-if='mode == "detail" && sales_order.status == "New SO" && !sales_order_gimmick && !sales_order_logistik' :href="'h3/h3_md_sales_order/edit?id=' + sales_order.id_sales_order" class="btn btn-sm btn-flat btn-warning">Edit</a>
            </div>
            <div class="col-sm-6 no-padding text-right">
              <a v-if='mode == "detail" && sales_order.status != "Canceled" && sales_order.status != "Closed"' :href="'h3/h3_md_create_do_sales_order/detail?id=' + sales_order.id_sales_order">
                <button :disabled='sales_order.status == "Back Order" || (qty_so.qty_order == qty_do.qty_supply)' type='button' class="btn btn-sm btn-flat btn-info">Create to DO</button>
              </a>
              <!-- <a onclick='return confirm("Apakah anda yakin untuk membatalkan Sales Order ini?")' v-if='mode == "detail" && sales_order.status == "New SO"' :href="'h3/h3_md_sales_order/cancel?id=' + sales_order.id_sales_order" class="btn btn-sm btn-flat btn-danger">Cancel</a> -->
              <button v-if='mode == "detail" && sales_order.status == "New SO"' class="btn btn-flat btn-sm btn-danger" type='button' data-toggle='modal' data-target='#cancel_so'>Cancel</button>
              <?php $this->load->view('modal/alasan_cancel_sales_order_md') ?>
            </div>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /.box -->
<script>
  app = new Vue({
    el: '#app',
    data: {
      generateByPO: <?= $this->input->get('generateByPO') != null ? 'true' : 'false' ?>,
      loading: false,
      errors: {},
      mode: '<?= $mode ?>',
      <?php if ($mode == 'detail' or $mode == 'edit' or $this->input->get('generateByPO') != null or $this->input->get('generateByRekapPurchaseOrder') != null or $this->input->get('generateGimmick') != null or $this->input->get('generateSalesOrderEkspedisi') != null or $this->input->get('generatePOLogistik') != null or $this->input->get('generateGimmickTidakLangsung') != null) : ?>
        sales_order: <?= json_encode($sales_order) ?>,
        parts: <?= json_encode($sales_order_parts) ?>,
        qty_do: <?= json_encode($qty_do) ?>,
        qty_so: <?= json_encode($qty_so) ?>,
        po_id: <?= json_encode($po_id) ?>,
      <?php else : ?>
        sales_order: {
          id_ref: '',
          id_salesman: '',
          nama_salesman: '',
          id_dealer: '',
          nama_dealer: '',
          kode_dealer_md: '',
          alamat: '',
          tipe_po: '',
          batas_waktu: '',
          kategori_po: 'Non SIM Part',
          produk: 'Parts',
          jenis_pembayaran: 'Credit',
          bulan_kpb: '',
          tipe_source: '',
          po_type: 'REG',
          created_by_md: 1,
          target_customer: '',
          sales_order_target: '',
          persentase_sales_order_target: '',
          sales_order_out_target: '',
          persentase_sales_order_out_target: '',
          plafon: 0,
          plafon_booking: 0,
          plafon_yang_dipakai: 0,
          gimmick: 0,
          autofulfillment_md: '',
          is_hadiah: 0,
          is_ev: 0,
        },
        parts: [],
      <?php endif; ?>
    },
    mounted: function() {
      if (this.sales_order.gimmick == 0) {
        this.get_target_customer();
      }
      this.get_plafon();
      this.get_statistik_penjualan_customer();

      <?php if ($this->input->get('generateSalesOrderEkspedisi') != null) : ?>
        this.get_diskon_parts();
      <?php endif; ?>

      <?php if ($this->input->get('generateByRekapPurchaseOrder') != null || $this->input->get('generateByPO') != null) : ?>
        this.get_diskon_parts();
      <?php endif; ?>

      <?php if ($this->input->get('generateGimmick') != null || $this->input->get('generateGimmickTidakLangsung') != null) : ?>
        this.get_diskon_parts();
      <?php endif; ?>

      if (this.kategori_bundling) {
        this.get_diskon_parts();
      }

      if (this.mode == 'detail' || this.mode == 'edit') {
        this.get_qty_actual_dan_simpart_dealer();
      }
    },
    methods: {
      <?= $form ?>: function() {
        post = _.pick(this.sales_order, [
          'id_dealer', 'kategori_po', 'jenis_pembayaran',
          'bulan_kpb', 'tipe_source', 'po_type', 'id_ref',
          'created_by_md', 'batas_waktu', 'id_salesman', 'produk', 'autofulfillment_md','is_hadiah',
          'target_customer', 'sales_order_target', 'persentase_sales_order_target', 'sales_order_out_target',
          'persentase_sales_order_out_target', 'id_rekap_purchase_order_dealer', 'id_ref', 'gimmick', 'id_campaign', 'id_item', 'no_do_sumber_gimmick', 'no_bapb', 'id_po_logistik', 'created_by_md',
          'gimmick_tidak_langsung', 'id_perolehan','is_ev'
        ]);

        if (this.mode == 'edit') {
          post.id_sales_order = this.sales_order.id_sales_order;
        }

        post.parts = _.map(this.parts, function(p) {
          keys = [
            'id_part', 'harga', 'qty_order', 'qty_on_hand', 'qty_pemenuhan', 'tipe_diskon', 'diskon_value', 'tipe_diskon_campaign', 'diskon_value_campaign', 'id_campaign_diskon', 'jenis_diskon_campaign', 'id_do_gimmick'
          ];
          if (app.kategori_kpb) {
            keys.push('id_tipe_kendaraan');
          }
          return _.pick(p, keys);
        });

        post.total_amount = this.total;

        this.errors = {};
        this.loading = true;
        axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res) {
            data = res.data;

            if (data.redirect_url != null) {
              window.location = data.redirect_url;
            }
          })
          .catch(function(err) {
            data = err.response.data;
            if (data.error_type == 'validation_error') {
              app.errors = data.errors;
              toastr.error(data.message);
            } else {
              toastr.error(data.message);
            }

            app.loading = false;
          });
      },
      hapus_part: function(index) {
        this.parts.splice(index, 1);
        h3_md_parts_sales_order_datatable.draw();
      },
      get_target_customer: function() {
        if (this.sales_order.id_dealer == '') return;

        this.loading = true;
        axios.get('h3/<?= $isi ?>/get_target_customer', {
            params: {
              id_dealer: this.sales_order.id_dealer,
              produk: this.sales_order.produk,
              tanggal_order: this.sales_order.tanggal_order,
            }
          })
          .then(function(res) {
            data = res.data;
            if (data.target_customer != null) {
              app.sales_order.target_customer = data.target_customer;
              if (app.mode != 'detail') {
                app.sales_order.id_salesman = data.id_salesman;
                app.sales_order.nama_salesman = data.nama_salesman;
              }
            } else {
              app.sales_order.target_customer = 0;
            }
          })
          .catch(function(err) {
            toastr.error(err);
          })
          .then(function() {
            app.loading = false;
          });
      },
      get_statistik_penjualan_customer: function() {
        if (this.sales_order.id_dealer == '' || this.sales_order.gimmick == 1) return;

        this.loading = true;
        axios.get('h3/<?= $isi ?>/get_statistik_penjualan_customer', {
            params: {
              id_dealer: this.sales_order.id_dealer,
              produk: this.sales_order.produk
            }
          })
          .then(function(res) {
            data = res.data;
            app.sales_order.sales_order_target = data.sales_order_target;
            app.sales_order.persentase_sales_order_target = data.persentase_sales_order_target;
            app.sales_order.sales_order_out_target = data.sales_order_out_target;
            app.sales_order.persentase_sales_order_out_target = data.persentase_sales_order_out_target;
          })
          .catch(function(err) {
            toastr.error(err);
          })
          .then(function() {
            app.loading = false;
          });
      },
      get_plafon: function() {
        if (this.sales_order.id_dealer == '') return;

        this.loading = true;
        axios.get('h3/<?= $isi ?>/get_plafon', {
            params: {
              id_sales_order: this.sales_order.id_sales_order,
              id_dealer: this.sales_order.id_dealer,
              gimmick: this.sales_order.gimmick,
              kategori_po: this.sales_order.kategori_po,
            }
          })
          .then(function(res) {
            data = res.data;
            app.sales_order.plafon = data.plafon;
            app.sales_order.plafon_booking = data.plafon_booking;
            app.sales_order.plafon_yang_dipakai = data.plafon_yang_dipakai;
          })
          .catch(function(err) {
            toastr.error(err);
          })
          .then(function() {
            app.loading = false;
          });
      },
      sub_total: function(part) {
        harga_setelah_diskon = part.harga;

        if (part.tipe_diskon == 'Rupiah') {
          harga_setelah_diskon = part.harga - part.diskon_value;
        } else if (part.tipe_diskon == 'Persen') {
          diskon = (part.diskon_value / 100) * part.harga;
          harga_setelah_diskon = part.harga - diskon;
        }

        if (part.tipe_diskon_campaign == 'Rupiah') {
          harga_setelah_diskon = harga_setelah_diskon - part.diskon_value_campaign;
        } else if (part.tipe_diskon_campaign == 'Persen') {
          if (part.jenis_diskon_campaign == 'Additional') {
            diskon = (part.diskon_value_campaign / 100) * harga_setelah_diskon;
            harga_setelah_diskon = harga_setelah_diskon - diskon;
          } else if (part.jenis_diskon_campaign == 'Non Additional') {
            diskon = (part.diskon_value_campaign / 100) * part.harga;
            harga_setelah_diskon = harga_setelah_diskon - diskon;
          }
        }

        if (this.sales_order.created_by_md == 1) {
          return (part.qty_order * harga_setelah_diskon);
        }

        if (harga_setelah_diskon < 0) {
          harga_setelah_diskon = 0;
        }

        return (part.qty_pemenuhan * harga_setelah_diskon);
      },
      get_parts_diskon: function() {
        if (this.parts.length < 1 || this.sales_order.po_type == '' || this.sales_order.id_dealer == '') return;

        this.reset_diskon_dealer();

        this.loading = true;
        axios.get('h3/h3_md_diskon_part_tertentu/get_parts_diskon', {
            params: {
              id_part: _.map(this.parts, function(p) {
                return p.id_part
              }),
              po_type: this.sales_order.po_type,
              id_dealer: this.sales_order.id_dealer,
              produk: this.sales_order.produk
            }
          }).then(function(res) {
            for (data of res.data) {
              index = _.findIndex(app.parts, function(p) {
                return p.id_part == data.id_part;
              });

              app.parts[index].tipe_diskon = data.tipe_diskon;
              app.parts[index].diskon_value = data.diskon_value;
            }
          }).catch(function(error) {
            toastr.error(error);
          })
          .then(function() {
            app.loading = false;
          });
      },
      get_parts_sales_campaign: function() {
        if (this.parts.length < 1) return;

        this.reset_diskon_sales_campaign();

        this.loading = true;
        post = {};
        post.order = _.map(this.parts, function(part) {
          return _.pick(part, ['id_part', 'qty_order'])
        });
        axios.post('h3/h3_md_sales_order/get_parts_sales_campaign', Qs.stringify(post)).then(function(res) {
            for (data of res.data) {
              index = _.findIndex(app.parts, function(p) {
                return p.id_part == data.id_part;
              });

              app.parts[index].tipe_diskon_campaign = data.tipe_diskon;
              app.parts[index].diskon_value_campaign = data.diskon_value;
              app.parts[index].id_campaign_diskon = data.id;
              app.parts[index].jenis_diskon_campaign = data.jenis_diskon_campaign;
            }
          }).catch(function(error) {
            toastr.error(error);
          })
          .then(function() {
            app.loading = false;
          });
      },
      reset_diskon_sales_campaign: function() {
        for (var index = 0; index < this.parts.length; index++) {
          this.parts[index].tipe_diskon_campaign = '';
          this.parts[index].diskon_value_campaign = '';
        }
      },
      reset_diskon_dealer: function() {
        for (var index = 0; index < this.parts.length; index++) {
          this.parts[index].tipe_diskon = '';
          this.parts[index].diskon_value = '';
        }
      },
      get_parts_diskon_oli_reguler: function() {
        if (this.parts.length < 1 || this.sales_order.id_dealer == '') return;

        this.reset_diskon_dealer();

        this.loading = true;
        post = _.pick(this.sales_order, ['id_dealer']);
        post.parts = _.map(this.parts, function(p) {
          data = _.pick(p, ['id_part']);
          data.kuantitas = p.qty_order;
          return data;
        });

        axios.post('h3/h3_md_diskon_oli_reguler/get_parts_diskon_oli_reguler', Qs.stringify(post)).then(function(res) {
            for (data of res.data) {
              index = _.findIndex(app.parts, function(p) {
                return p.id_part == data.id_part;
              });

              app.parts[index].tipe_diskon = data.tipe_diskon;
              app.parts[index].diskon_value = data.diskon_value;
            }
          }).catch(function(error) {
            toastr.error(error);
          })
          .then(function() {
            app.loading = false;
          });
      },
      get_parts_diskon_oli_kpb: function() {
        if (this.parts.length < 1) return;

        this.reset_diskon_dealer();

        this.loading = true;
        post = {};
        post.parts = _.map(this.parts, function(p) {
          data = _.pick(p, ['id_part', 'id_tipe_kendaraan']);
          data.kuantitas = p.qty_order;
          return data;
        });

        axios.post('h3/h3_md_ms_diskon_oli_kpb/get_parts_diskon_oli_kpb', Qs.stringify(post))
          .then(function(res) {
            for (data of res.data) {
              index = _.findIndex(app.parts, function(p) {
                return p.id_part == data.id_part;
              });

              app.parts[index].tipe_diskon = data.tipe_diskon;
              app.parts[index].diskon_value = data.diskon_value;
            }
          }).catch(function(error) {
            toastr.error(error);
          })
          .then(function() {
            app.loading = false;
          });
      },
      get_diskon_parts: function() {
        if (!this.sales_order_hotline && !this.sales_order_urgent) {
          if (this.produk_oli) {
            if (this.kategori_kpb) {
              this.get_parts_diskon_oli_kpb();
            } else {
              this.get_parts_diskon_oli_reguler();
            }
          } else {
            this.get_parts_diskon();
          }
        }

        if (!this.kategori_kpb && !this.sales_order_hotline && !this.sales_order_urgent) {
          this.get_parts_sales_campaign();
        }
      },
      get_batas_waktu_dealer: function() {
        if (this.sales_order.po_type != 'FIX' && this.sales_order_po_type != 'REG' && this.customer_empty) return;

        this.loading = true;
        axios.get('h3/h3_md_sales_order/get_batas_waktu', {
            params: {
              id_dealer: this.sales_order.id_dealer,
              po_type: this.sales_order.po_type,
            }
          }).then(function(res) {
            app.sales_order.batas_waktu = res.data.batas_waktu;
          }).catch(function(error) {
            toastr.error(error);
          })
          .then(function() {
            app.loading = false;
          });
      },
      get_qty_actual_dan_simpart_dealer: function() {
        if (this.customer_empty || this.parts.length < 1) return;

        post = {};
        post.id_dealer = this.sales_order.id_dealer;
        post.parts = _.chain(this.parts)
          .map(function(part) {
            return _.pick(part, ['id_part']);
          })
          .value();

        this.loading = true;
        axios.post('h3/h3_md_sales_order/get_qty_actual_dan_simpart_dealer', Qs.stringify(post))
          .then(function(res) {
            data = res.data;
            for (row of data) {
              index = _.findIndex(app.parts, function(part) {
                return part.id_part == row.id_part;
              });

              if (index != -1) {
                app.parts[index].qty_actual_dealer = row.qty_actual_dealer;
                app.parts[index].qty_sim_part = row.qty_sim_part;
              }
            }
          }).catch(function(error) {
            toastr.error(error);
          })
          .then(function() {
            app.loading = false;
          });
      },
      qty_order_change_handler: _.debounce(function($event) {
        app.get_diskon_parts();
      }, 500),
      reset_parts: function() {
        this.parts = [];
      },
      error_exist: function(key) {
        return _.get(this.errors, key) != null;
      },
      get_error: function(key) {
        return _.get(this.errors, key)
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
      generate_parts_auto_fix: function() {
        toastr.options = {
          preventDuplicates: true,
          preventOpenDuplicates: true
        };
        // alert(this.sales_order.id_dealer);

        // $('#input_autofulfillment_md').show();

        this.sales_order.autofulfillment_md = 1;
        if (this.sales_order.id_dealer == null || this.sales_order.id_dealer == '') {
          toastr.warning('Inputan DEALER belum terisi.');
          return;
        }

        if (this.sales_order.produk == null || this.sales_order.produk == '') {
          toastr.warning('Inputan PRODUK belum terisi.');
          return;
        }

        this.loading = true;
        params = {};
        params.jenis_po = this.sales_order.jenis_po;
        params.tanggal_order = this.sales_order.tanggal_order;
        params.produk = this.sales_order.produk;
        params.nama_dealer = this.sales_order.id_dealer;
        axios.get('h3/<?= $isi ?>/generate_parts_fix', {
            params: params
          })
          // .then(function(res) {
          //   app.parts = res.data;
          // })
          .then(function(resGenerate) {
            const generateData = resGenerate.data;
            axios.get('h3/h3_md_diskon_part_tertentu/get_parts_diskon', {
                params: {
                  id_part: _.map(generateData, function(p) {
                    return p.id_part;
                  }),
                  po_type: app.sales_order.po_type,
                  id_dealer: app.sales_order.id_dealer,
                  produk: app.sales_order.produk
                }
              })
              .then(function(resDiskon) {
                const diskonData = resDiskon.data;

                if (Array.isArray(diskonData)) {
                  diskonData.forEach(function(data) {
                    const matchingItem = generateData.find(function(item) {
                      return item.id_part === data.id_part;
                    });

                    if (matchingItem) {
                      matchingItem.tipe_diskon = data.tipe_diskon;
                      matchingItem.diskon_value = data.diskon_value;
                    }
                  });

                  app.parts = generateData;
                  // app.get_diskon_parts();
                } else {
                  toastr.error("Dealer/Toko belum diinput autofulfillment!");
                }
              })
              .catch(function(error) {
                toastr.error(error);
              })
              .then(function() {
                app.loading = false;
              });
          })
          .catch(function(err) {
            toastr.error(err);
          })
          .then(function() {
            app.loading = false;
          });
      },
      generate_parts_auto_reg: function() {
        toastr.options = {
          preventDuplicates: true,
          preventOpenDuplicates: true
        };

        this.sales_order.autofulfillment_md = 1;

        if (this.sales_order.id_dealer == null || this.sales_order.id_dealer == '') {
          toastr.warning('Inputan DEALER belum terisi.');
          return;
        }

        this.loading = true;
        params = {};
        params.jenis_po = this.sales_order.jenis_po;
        params.tanggal_order = this.sales_order.tanggal_order;
        params.produk = this.sales_order.produk;
        params.nama_dealer = this.sales_order.id_dealer;
        axios.get('h3/<?= $isi ?>/generate_parts_reg', {
            params: params
          })
          // .then(function(res) {
          //   app.parts = res.data;
          // })
          .then(function(resGenerate) {
            const generateData = resGenerate.data;
            axios.get('h3/h3_md_diskon_part_tertentu/get_parts_diskon', {
                params: {
                  id_part: _.map(generateData, function(p) {
                    return p.id_part;
                  }),
                  po_type: app.sales_order.po_type,
                  id_dealer: app.sales_order.id_dealer,
                  produk: app.sales_order.produk
                }
              })
              .then(function(resDiskon) {
                const diskonData = resDiskon.data;

                if (Array.isArray(diskonData)) {
                  diskonData.forEach(function(data) {
                    const matchingItem = generateData.find(function(item) {
                      return item.id_part === data.id_part;
                    });

                    if (matchingItem) {
                      matchingItem.tipe_diskon = data.tipe_diskon;
                      matchingItem.diskon_value = data.diskon_value;
                    }
                  });

                  app.parts = generateData;
                  // app.get_diskon_parts();
                } else {
                  toastr.error("Dealer/Toko belum diinput autofulfillment!");
                }
              })
              .catch(function(error) {
                toastr.error(error);
              })
              .then(function() {
                app.loading = false;
              });
          })
          .catch(function(err) {
            toastr.error(err);
          })
          .then(function() {
            app.loading = false;
          });
      },
      cancel_so_md: function(){
        post = {};
        post.id = this.sales_order.id_sales_order;
        post.alasan_reject = $('#alasan_reject').val();
        post.pw = $('#pw').val();
        this.loading = true;
        axios.post('h3/h3_md_sales_order/cancel', Qs.stringify(post))
        .then(function(res){
          // window.location = 'h3/h3_md_sales_order/detail?id=' + id;
          data = res.data;

          if(data.redirect_url != null && data.status == 'Sukses'){
            window.location = data.redirect_url;
            toastr.success(data.message);
          }else{
            toastr.error(data.status);
          }
        })
        .catch(function(e){
          data = e.response.data;
            if (data.status == 'gagal') {
              app.errors = data.errors;
              toastr.error(data.message);
            } else {
              toastr.error(data.message);
            }
          // toastr.error(e);
        })
        .then(function(){ app.loading = false; })
      },
    },
    watch: {
      'sales_order.id_dealer': function() {
        // h3_md_parts_sales_order_datatable.draw();
        h3_md_salesman_sales_order_datatable.draw();
        this.get_target_customer();
        this.get_statistik_penjualan_customer();
        this.get_plafon();
        this.get_batas_waktu_dealer();

        this.get_qty_actual_dan_simpart_dealer();
        this.get_diskon_parts();
      },
      'sales_order.produk': function() {
        // h3_md_parts_sales_order_datatable.draw();
        drawing_so_part();
        this.get_target_customer();
        this.get_statistik_penjualan_customer();
        this.get_diskon_parts();

        if (this.sales_order_ekspedisi || this.sales_order_rekap) return;
        this.reset_parts();
      },
      'sales_order.kategori_po': function() {
        // h3_md_parts_sales_order_datatable.draw();
        drawing_so_part();
      },
      'sales_order.po_type': function() {
        this.get_batas_waktu_dealer();
        this.get_diskon_parts();
      },
      'sales_order.autofulfillment_md': function() {
        this.get_batas_waktu_dealer();
      }
    },
    computed: {
      total: function() {
        total = 0;
        for (part of this.parts) {
          total += this.sub_total(part);
        }
        return total;
      },
      customer_empty: function() {
        return this.sales_order.id_dealer == '' || this.sales_order.id_dealer == null;
      },
      sisa_plafon: function() {
        return this.sales_order.plafon - this.sales_order.plafon_booking - this.sales_order.plafon_yang_dipakai;
      },
      sales_order_hotline: function() {
        return this.sales_order.po_type == 'HLO';
      },
      sales_order_urgent: function() {
        return this.sales_order.po_type == 'URG';
      },
      kategori_bundling: function() {
        return this.sales_order.kategori_po == "Bundling H1";
      },
      kategori_sim_part: function() {
        return this.sales_order.kategori_po == "SIM Part";
      },
      kategori_kpb: function() {
        return this.sales_order.kategori_po == "KPB";
      },
      produk_acc: function() {
        return this.sales_order.produk == "Acc";
      },
      produk_oli: function() {
        return this.sales_order.produk == "Oil";
      },
      produk_parts: function() {
        return this.sales_order.produk == "Parts";
      },
      produk_other: function() {
        return this.sales_order.produk == "Other";
      },
      produk_apparel: function() {
        return this.sales_order.produk == "Apparel";
      },
      produk_tools: function() {
        return this.sales_order.produk == "Tools";
      },
      dealer_terisi: function() {
        return this.sales_order.id_dealer != '';
      },
      sales_order_gimmick: function() {
        return this.sales_order.gimmick == 1;
      },
      sales_order_ekspedisi: function() {
        return this.sales_order.no_bapb != '' && this.sales_order.no_bapb != null;
      },
      sales_order_logistik: function() {
        return this.sales_order.id_po_logistik != '' && this.sales_order.id_po_logistik != null;
      },
      sales_order_rekap: function() {
        return this.sales_order.id_rekap_purchase_order_dealer != '' && this.sales_order.id_rekap_purchase_order_dealer != null;
      },
      sales_order_create_by_md: function() {
        return this.sales_order.created_by_md == 1;
      },
      total_label_colspan: function() {
        // colspan = 2;

        if ((this.mode == 'insert'||this.mode == 'edit') && this.sales_order.po_type == 'HLO') {
          colspan = 1;
        }

        if ((this.mode == 'insert'||this.mode == 'edit') && this.sales_order.po_type == 'FIX') {
          colspan = 2;
        }

        if ((this.mode == 'insert'||this.mode == 'edit') && this.sales_order.po_type == 'REG') {
          colspan = 2;
        }

        if ((this.mode == 'insert'||this.mode == 'edit') && this.sales_order.po_type == 'URG') {
          colspan = 0;
        }

        if (this.mode == 'detail' && this.sales_order.po_type == 'FIX') {
          colspan = 2;
        }

        if (this.mode == 'detail' && this.sales_order.po_type == 'HLO') {
          colspan = 1;
        }
        if (this.mode == 'detail' && this.sales_order.po_type == 'REG') {
          colspan = 2;
        }

        if (this.mode == 'detail' && this.sales_order.po_type == 'URG') {
          colspan = 1;
        }



        if (this.kategori_sim_part) {
          console.log('Label SIM PART');
          colspan += 9;
        } else {
          console.log('Label Non SIM PART');
          colspan += 7;
        }

        if (this.sales_order.po_type == 'HLO') {
          console.log('Label Hotline');
          colspan += 1;
        }

        if (this.generateByPO) {
          console.log('Label Generate By PO');
          colspan -= 1;
        }

        if (this.kategori_kpb) {
          console.log('Label SO KPB');
          colspan += 1;
        }

        if (this.sales_order_rekap) {
          console.log('Label SO Rekap');
          colspan -= 1;
        }

        if (this.sales_order.po_type == 'URG' && this.mode == 'insert') {
          console.log('Label SO URG Insert');
          colspan += 1;
        }

        if (this.sales_order.po_type == 'URG') {
          console.log('Label SO URG');
          colspan += 1;
        }

        // if(this.produk_parts){
        //   colspan = 8;
        // }

        // if(this.produk_oli){
        //   colspan = 8;
        // }

        // if(this.produk_acc){
        //   colspan = 8;
        // }

        // if(this.produk_other){
        //   colspan = 8;
        // }

        // if (this.sales_order.created_by_md) {
        //   console.log('Label SO dibuat MD');
        //   colspan += 1;
        // }

        return colspan;
      },
      kategori_po_can_disable: function() {
        if (this.mode == 'detail') return true;

        if (this.generateByPO || this.sales_order_gimmick || this.sales_order_ekspedisi || this.sales_order_rekap) {
          return true;
        } else if (this.sales_order_logistik) {
          return true;
        }
        return false;
      }
    }
  });
</script>