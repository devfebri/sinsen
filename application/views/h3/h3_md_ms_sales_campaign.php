<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/jquery.min.js") ?>"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script src="<?= base_url("assets/vue/custom/vb-rangedatepicker.js") ?>"></script>
<script>
  Vue.use(VueNumeric.default);
</script>
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

      <div id="form_" class="box box-default">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="box-title">
                <a href="h3/<?= $isi ?>">
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
                </a>
              </h3>
            </div>
            <div class="col-sm-6 text-right">
              <h3 v-if='sales_campaign.jenis_reward_poin == 1 && sales_campaign.reward_poin == "Tidak Langsung" && mode == "detail"' class="box-title">
                <a :href="'h3/<?= $isi ?>/insentif_poin?id=' + sales_campaign.id">
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-gift"></i> Perolehan Poin</button>
                </a>
              </h3>
              <h3 v-if='sales_campaign.jenis_reward_gimmick == 1 && sales_campaign.reward_gimmick == "Tidak Langsung" && mode == "detail"' class="box-title">
                <a :href="'h3/<?= $isi ?>/perolehan_gimmick?id=' + sales_campaign.id">
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-gift"></i> Perolehan Gimmick</button>
                </a>
              </h3>
              <h3 v-if='sales_campaign.jenis_reward_cashback == 1 && sales_campaign.reward_cashback == "Tidak Langsung" && mode == "detail"' class="box-title">
                <a :href="'h3/<?= $isi ?>/insentif_cashback?id=' + sales_campaign.id">
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-gift"></i> Perolehan Cashback</button>
                </a>
              </h3>
            </div>
          </div>
        </div><!-- /.box-header -->
        <div v-if='loading' class="overlay">
          <i class="text-light-blue fa fa-refresh fa-spin"></i>
        </div>
        <div class="box-body">
          <?php $this->load->view('template/session_message.php'); ?>
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Kode Campaign</label>
                    <div v-bind:class="{ 'has-error': error_exist('kode_campaign') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='sales_campaign.kode_campaign'>
                      <small v-if="error_exist('kode_campaign')" class="form-text text-danger">{{ get_error('kode_campaign') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Periode Campaign</label>
                    <div v-bind:class="{ 'has-error': error_exist('start_date') }" class="col-sm-4">
                      <range-date-picker :disabled='mode == "detail"' :config='get_config_periode_campaign()' class='form-control' @apply-date='applyDatePeriodeCampaign' @cancel-date='cancelDatePeriodeCampaign' readonly></range-date-picker>
                      <small v-if="error_exist('start_date')" class="form-text text-danger">{{ get_error('start_date') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Campaign</label>
                    <div v-bind:class="{ 'has-error': error_exist('nama') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='sales_campaign.nama'>
                      <small v-if="error_exist('nama')" class="form-text text-danger">{{ get_error('nama') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kontribusi</label>
                    <div v-bind:class="{ 'has-error': error_exist('kontribusi') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='sales_campaign.kontribusi'>
                        <option value="">-Pilih-</option>
                        <option value="AHM">AHM</option>
                        <option value="MD">MD</option>
                        <option value="AHM & MD">AHM & MD</option>
                      </select>
                      <small v-if="error_exist('kontribusi')" class="form-text text-danger">{{ get_error('kontribusi') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Jenis Reward</label>
                    <div class="col-sm-4">
                      <div class="container-fluid">
                        <div class="row">
                          <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='sales_campaign.jenis_reward_poin'> Poin
                        </div>
                        <div class="row">
                          <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='sales_campaign.jenis_reward_diskon'> Diskon
                        </div>
                        <div class="row">
                          <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='sales_campaign.jenis_reward_cashback'> Cashback
                        </div>
                        <div class="row">
                          <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='sales_campaign.jenis_reward_gimmick'> Gimmick
                        </div>
                      </div>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Produk Campaign</label>
                    <div v-bind:class="{ 'has-error': error_exist('nama_produk_campaign') }" class="col-sm-4">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='sales_campaign.nama_produk_campaign'>
                      <small v-if="error_exist('nama_produk_campaign')" class="form-text text-danger">{{ get_error('nama_produk_campaign') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Mekanisme Program</label>
                    <div v-bind:class="{ 'has-error': error_exist('mekanisme_program') }" class="col-sm-4">
                      <textarea :disabled='mode == "detail"' rows="5" class="form-control" v-model='sales_campaign.mekanisme_program'></textarea>
                      <small v-if="error_exist('mekanisme_program')" class="form-text text-danger">{{ get_error('mekanisme_program') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">Kategori</label>
                    <div v-bind:class="{ 'has-error': error_exist('kategori') }" class="col-sm-4">
                      <select :disabled='mode == "detail"' class="form-control" v-model='sales_campaign.kategori'>
                        <option value="">-Pilih-</option>
                        <option value="Parts">Parts</option>
                        <option value="Oil">Oil</option>
                        <option value="Acc">Acc</option>
                      </select>
                      <small v-if="error_exist('kategori')" class="form-text text-danger">{{ get_error('kategori') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label no-padding">Active</label>
                    <div class="col-sm-4 ">
                      <input :disabled='mode == "detail"' type="checkbox" true-value='1' false-value='0' v-model='sales_campaign.active'>
                    </div>
                  </div>
                  <div class="container-fluid bg-blue-gradient">
                    <div class="row">
                      <div class="col-sm-12 text-center" style='padding: 8px 0px;'>
                        <span class='text-bold'>Detail Dealer</span>
                      </div>
                    </div>
                  </div>
                  <table class="table table-compact">
                    <tr>
                      <td width='3%'>No.</td>
                      <td>Kode Dealer</td>
                      <td>Nama Dealer</td>
                      <td v-if='mode == "detail"'>Diskualifikasi</td>
                      <td v-if='mode == "detail"'>Tanggal Diskualifikasi</td>
                      <td v-if='mode == "detail"'>Yang Men-diskualifikasi</td>
                      <td v-if='mode == "detail"'>Keterangan Diskualifikasi</td>
                      <td></td>
                    </tr>
                    <tr v-if='sales_campaign_dealers.length > 0' v-for='(dealer, index) of sales_campaign_dealers'>
                      <td width='3%'>{{ index + 1 }}.</td>
                      <td>{{ dealer.kode_dealer_md }}</td>
                      <td>{{ dealer.nama_dealer }}</td>
                      <td v-if='mode == "detail"'>
                        <span v-if='dealer.diskualifikasi == 1'>Ya</span>
                        <span v-if='dealer.diskualifikasi == 0'>Tidak</span>
                      </td>
                      <td v-if='mode == "detail"'>
                        <span v-if='dealer.tanggal_diskualifikasi == null'>-</span>
                        <span v-if='dealer.tanggal_diskualifikasi != null'>{{ moment(dealer.tanggal_diskualifikasi).format('DD/MM/YYYY HH:mm:ss') }}</span>
                      </td>
                      <td v-if='mode == "detail"'>
                        <span v-if='dealer.actor_diskualifikasi == null'>-</span>
                        <span v-if='dealer.actor_diskualifikasi != null'>{{ dealer.nama_lengkap }}</span>
                      </td>
                      <td v-if='mode == "detail"'>
                        <span v-if='dealer.keterangan_diskualifikasi == null'>-</span>
                        <span v-if='dealer.keterangan_diskualifikasi != null'>{{ dealer.keterangan_diskualifikasi }}</span>
                      </td>
                      <td width='3%'>
                        <button v-if='mode == "detail" && dealer.diskualifikasi == 0' class="btn btn-flat btn-info btn-xs" @click.prevent='open_diskualifikasi_dealer(index)'>Diskualifikasi</button>
                        <button v-if='mode != "detail"' class="btn btn-flat btn-danger" @click.prevent='hapus_dealer(index)'><i class="fa fa-trash-o"></i></button>
                      </td>
                    </tr>
                    <tr v-if='sales_campaign_dealers.length < 1'>
                      <td colspan='4' class='text-center'>Tidak ada data.</td>
                    </tr>
                  </table>
                  <div class="container-fluid" style='margin-bottom: 10px;'>
                    <div class="row">
                      <div class="col-sm-12 text-right">
                        <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_dealer_sales_campaign'><i class="fa fa-plus"></i></button>
                      </div>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_dealer_sales_campaign'); ?>
                  <script>
                    function pilih_dealer_sales_campaign(data) {
                      index = _.findIndex(form_.sales_campaign_dealers, function(row) {
                        return row.id_dealer == data.id_dealer;
                      });
                      if (index == -1) {
                        form_.sales_campaign_dealers.push(data);
                        h3_md_dealer_sales_campaign_datatable.draw(false);
                      }
                    }
                  </script>
                  <!-- Modal -->
                  <div id="h3_md_diskualifikasi_dealer" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content box">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
                          <h4 class="modal-title" id="myModalLabel">Diskualifikasi Dealer</h4>
                        </div>
                        <div v-if="loading_diskualifikasi" class="overlay">
                          <i class="fa fa-refresh fa-spin text-light-blue"></i>
                        </div>
                        <div class="modal-body box-body">
                          <div class="container-fluid">
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="form-group">
                                  <label for="" class="control-label">Keterangan Diskualifikasi</label>
                                  <textarea class="form-control" rows="10" v-model='keterangan_diskualifikasi'></textarea>
                                </div>
                                <div class="form-group">
                                  <button class="btn btn-flat btn-sm btn-success" @click.prevent='diskualifikasi_dealer'>Simpan</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div v-show='sales_campaign.jenis_reward_poin == 1'>
                    <?php $this->load->view('h3/h3_md_sales_campaign_detail_poin'); ?>
                    <?php $this->load->view('h3/h3_md_sales_campaign_detail_hadiah'); ?>
                  </div>
                  <div v-show='sales_campaign.jenis_reward_diskon == 1'>
                    <?php $this->load->view('h3/h3_md_sales_campaign_detail_diskon'); ?>
                    <?php $this->load->view('modal/h3_md_diskon_bertingkat_sales_campaign_detail_diskon'); ?>
                    <div v-show='sales_campaign.produk_program_diskon == "Global"'>
                      <?php $this->load->view('h3/h3_md_sales_campaign_detail_diskon_global'); ?>
                    </div>
                  </div>
                  <div v-show='sales_campaign.jenis_reward_cashback == 1'>
                    <?php $this->load->view('h3/h3_md_sales_campaign_detail_cashback'); ?>
                    <?php $this->load->view('modal/h3_md_sales_campaign_detail_cashback_item'); ?>
                    <div v-show='sales_campaign.produk_program_cashback == "Global"'>
                      <?php $this->load->view('h3/h3_md_sales_campaign_detail_cashback_global'); ?>
                    </div>
                  </div>
                  <div v-show='sales_campaign.jenis_reward_gimmick == 1'>
                    <?php $this->load->view('h3/h3_md_sales_campaign_detail_gimmick'); ?>
                    <?php $this->load->view('modal/h3_md_sales_campaign_detail_gimmick_item'); ?>
                    <?php $this->load->view('modal/h3_md_parts_detail_gimmick_hadiah'); ?>
                    <div v-show='sales_campaign.produk_program_gimmick == "Global"'>
                      <?php $this->load->view('h3/h3_md_sales_campaign_detail_gimmick_global'); ?>
                    </div>
                    <script>
                      function pilih_part_detail_gimmick_hadiah(data) {
                        if (form_.sales_campaign.produk_program_gimmick == 'Global') {
                          form_.sales_campaign_detail_gimmick_global[form_.index_detail_gimmick_global].id_part = data.id_part;
                          form_.sales_campaign_detail_gimmick_global[form_.index_detail_gimmick_global].nama_hadiah = data.nama_part;
                        } else {
                          form_.detail_gimmick_item[form_.index_detail_gimmick_item].id_part = data.id_part
                          form_.detail_gimmick_item[form_.index_detail_gimmick_item].nama_hadiah = data.nama_part;
                        }
                      }
                    </script>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-6">
                      <button v-if='mode == "insert"' class="btn btn-flat btn-sm btn-primary" @click.prevent='<?= $form ?>'>Simpan</button>
                      <button v-if='mode == "edit"' class="btn btn-flat btn-sm btn-warning" @click.prevent='<?= $form ?>'>Update</button>
                      <a v-if='mode == "detail" && sales_campaign.status == "Open"' :href="'h3/h3_md_ms_sales_campaign/edit?id=' + sales_campaign.id" class="btn btn-flat btn-sm btn-warning">Edit</a>
                    </div>
                    <div class="col-sm-6 text-right">
                      <a v-if='sales_campaign.jenis_reward_poin == 1 && mode == "detail"' :href="'h3/h3_md_ms_sales_campaign/report_hadiah?id=' + sales_campaign.id" class="btn btn-flat btn-sm btn-info">Report</a>
                      <a v-if='sales_campaign.jenis_reward_poin == 1 && mode == "detail" && sales_campaign.sudah_generate_hadiah == 0' :href="'h3/h3_md_ms_sales_campaign/generate_hadiah?id=' + sales_campaign.id" class="btn btn-flat btn-sm btn-info">Generate Hadiah</a>
                      <a v-if='mode == "detail" && sales_campaign.status != "Closed"' :href="'h3/h3_md_ms_sales_campaign/close?id=' + sales_campaign.id" class="btn btn-flat btn-sm btn-danger">Close</a>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            mode: '<?= $mode ?>',
            index_part: 0,
            index_dealer: 0,
            keterangan_diskualifikasi: '',
            loading_diskualifikasi: false,
            loading: false,
            errors: {},
            <?php if ($mode == 'detail' or $mode == 'edit') : ?>
              sales_campaign: <?= json_encode($sales_campaign) ?>,
              sales_campaign_dealers: <?= json_encode($sales_campaign_dealers) ?>,
              sales_campaign_detail_poin: <?= json_encode($sales_campaign_detail_poin) ?>,
              sales_campaign_detail_hadiah: <?= json_encode($sales_campaign_detail_hadiah) ?>,
              sales_campaign_detail_diskon: <?= json_encode($sales_campaign_detail_diskon) ?>,
              sales_campaign_detail_diskon_global: <?= json_encode($sales_campaign_detail_diskon_global) ?>,
              sales_campaign_detail_cashback: <?= json_encode($sales_campaign_detail_cashback) ?>,
              sales_campaign_detail_cashback_global: <?= json_encode($sales_campaign_detail_cashback_global) ?>,
              sales_campaign_detail_gimmick: <?= json_encode($sales_campaign_detail_gimmick) ?>,
              sales_campaign_detail_gimmick_global: <?= json_encode($sales_campaign_detail_gimmick_global) ?>,
            <?php else : ?>
              sales_campaign: {
                kode_campaign: '',
                nama: '',
                start_date: '',
                end_date: '',
                kontribusi: '',
                jenis_reward_poin: 0,
                jenis_item_poin: 'Per Item Number',
                reward_poin: '',
                produk_program_poin: '',
                start_date_poin: '',
                end_date_poin: '',
                satuan_rekapan_poin: '',
                jenis_reward_diskon: 0,
                jenis_item_diskon: 'Per Item Number',
                jenis_diskon_campaign: '',
                produk_program_diskon: '',
                start_date_diskon: '',
                end_date_diskon: '',
                jenis_reward_cashback: 0,
                jenis_item_cashback: 'Per Item Number',
                reward_cashback: '',
                produk_program_cashback: '',
                start_date_cashback: '',
                end_date_cashback: '',
                satuan_rekapan_cashback: '',
                jenis_item_gimmick: 'Per Item Number',
                jenis_reward_gimmick: 0,
                reward_gimmick: '',
                produk_program_gimmick: '',
                start_date_gimmick: '',
                end_date_gimmick: '',
                satuan_rekapan_gimmick: 'Pcs',
                kelipatan_gimmick: 0,
                nama_produk_campaign: '',
                mekanisme_program: '',
                kategori: '',
                active: 1,
              },
              sales_campaign_dealers: [],
              sales_campaign_detail_poin: [],
              sales_campaign_detail_hadiah: [],
              sales_campaign_detail_diskon: [],
              sales_campaign_detail_diskon_global: [],
              sales_campaign_detail_cashback: [],
              sales_campaign_detail_cashback_global: [],
              sales_campaign_detail_gimmick: [],
              sales_campaign_detail_gimmick_global: [],
            <?php endif; ?>
            index_detail_cashback: 0,
            detail_cashback_item: [],
            index_detail_diskon: 0,
            diskon_bertingkat: [],
            index_detail_gimmick: 0,
            detail_gimmick_item: [],
            index_detail_gimmick_item: 0,
            index_detail_gimmick_global: 0,
          },
          methods: {
            <?= $form ?>: function() {
              post = _.pick(this.sales_campaign, [
                'kode_campaign', 'nama', 'start_date', 'end_date', 'kontribusi', 'jenis_reward_poin',
                'jenis_item_poin', 'reward_poin', 'produk_program_poin', 'start_date_poin', 'end_date_poin', 'satuan_rekapan_poin', 'jenis_reward_diskon',
                'jenis_item_diskon', 'jenis_diskon_campaign', 'produk_program_diskon', 'start_date_diskon', 'end_date_diskon', 'jenis_reward_cashback',
                'jenis_item_cashback', 'reward_cashback', 'produk_program_cashback', 'start_date_cashback', 'end_date_cashback', 'satuan_rekapan_cashback',
                'jenis_item_gimmick', 'jenis_reward_gimmick', 'reward_gimmick', 'produk_program_gimmick', 'start_date_gimmick', 'end_date_gimmick', 'satuan_rekapan_gimmick',
                'kelipatan_gimmick', 'nama_produk_campaign', 'mekanisme_program', 'kategori', 'active',
              ]);

              if (this.mode == 'edit') {
                post.id = this.sales_campaign.id;
              }

              post.sales_campaign_dealers = this.sales_campaign_dealers;
              post.sales_campaign_detail_poin = this.sales_campaign_detail_poin;
              post.sales_campaign_detail_diskon = this.sales_campaign_detail_diskon;
              post.sales_campaign_detail_diskon_global = this.sales_campaign_detail_diskon_global;
              post.sales_campaign_detail_hadiah = this.sales_campaign_detail_hadiah;
              post.sales_campaign_detail_cashback = this.sales_campaign_detail_cashback;
              post.sales_campaign_detail_cashback_global = this.sales_campaign_detail_cashback_global;
              post.sales_campaign_detail_gimmick = this.sales_campaign_detail_gimmick;
              post.sales_campaign_detail_gimmick_global = this.sales_campaign_detail_gimmick_global;

              this.loading = true;
              this.errors = {};
              axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
                .then(function(res) {
                  window.location = 'h3/h3_md_ms_sales_campaign/detail?id=' + res.data.id;
                })
                .catch(function(err) {
                  data = err.response.data;
                  if (data.error_type == 'validation_error') {
                    form_.errors = data.errors;
                    toastr.error(data.message);
                  } else {
                    toastr.error(err);
                  }
                })
                .then(function() {
                  form_.loading = false;
                });
            },
            open_diskualifikasi_dealer: function(index) {
              this.index_dealer = index;
              $('#h3_md_diskualifikasi_dealer').modal('show');
            },
            diskualifikasi_dealer: function() {
              post = {};
              post.id_campaign = this.sales_campaign.id;
              post.id_dealer = this.sales_campaign_dealers[this.index_dealer].id_dealer;
              post.keterangan_diskualifikasi = this.keterangan_diskualifikasi;

              this.loading_diskualifikasi = true;
              axios.post('h3/<?= $isi ?>/diskualifikasi_dealer', Qs.stringify(post))
                .then(function(res) {
                  if (res.data != null) {
                    form_.sales_campaign_dealers.splice(form_.index_dealer, 1, res.data);
                  }

                  $('#h3_md_diskualifikasi_dealer').modal('hide');
                })
                .catch(function(err) {
                  toastr.error(err);
                })
                .then(function() {
                  form_.loading_diskualifikasi = false;
                });
            },
            hapus_dealer: function(index) {
              this.sales_campaign_dealers.splice(index, 1);
            },
            open_detail_cashback_item: function(index) {
              this.index_detail_cashback = index;
              this.detail_cashback_item = this.sales_campaign_detail_cashback[index].detail_cashback_item;
              $('#h3_md_sales_campaign_detail_cashback_item').modal('show');
            },
            open_diskon_bertingkat: function(index) {
              this.index_detail_diskon = index;
              this.diskon_bertingkat = this.sales_campaign_detail_diskon[index].diskon_bertingkat;
              $('#h3_md_diskon_bertingkat_sales_campaign_detail_diskon').modal('show');
            },
            add_diskon_bertingkat: function() {
              this.diskon_bertingkat.push({
                qty: 0,
                diskon_value: 0,
                satuan: '',
              });
            },
            hapus_diskon_bertingkat: function(index) {
              this.diskon_bertingkat.splice(index, 1);
            },
            add_sales_campaign_detail_gimmick_item: function() {
              this.detail_gimmick_item.push({
                qty: '',
                satuan: '',
                hadiah_part: 0,
                id_part: '',
                nama_hadiah: '',
                qty_hadiah: 0,
                satuan_hadiah: '',
              });
            },
            hapus_detail_gimmick_item: function(index) {
              this.detail_gimmick_item.splice(index, 1);
              h3_md_parts_sales_campaign_detail_gimmick_datatable.draw();
            },
            open_detail_gimmick_item: function(index) {
              this.index_detail_gimmick = index;
              this.detail_gimmick_item = this.sales_campaign_detail_gimmick[index].detail_gimmick_item;
              $('#h3_md_sales_campaign_detail_gimmick_item').modal('show');
            },
            open_part_hadiah_modal: function(index) {
              if (this.detail_gimmick_item[index].hadiah_part == 0) return;

              this.index_detail_gimmick_item = index;
              $('#h3_md_parts_detail_gimmick_hadiah').modal('show');
            },
            open_part_hadiah_modal_for_global: function(index) {
              if (this.sales_campaign_detail_gimmick_global[index].hadiah_part == 0) return;

              this.index_detail_gimmick_global = index;
              $('#h3_md_parts_detail_gimmick_hadiah').modal('show');
            },
            add_sales_campaign_detail_gimmick_global: function() {
              this.sales_campaign_detail_gimmick_global.push({
                nama_paket: '',
                qty: 0,
                hadiah_part: 0,
                id_part: '',
                nama_hadiah: '',
                qty_hadiah: 0,
                satuan: '',
                satuan_hadiah: '',
              });
            },
            hapus_sales_campaign_detail_gimmick_global: function(index) {
              this.sales_campaign_detail_gimmick_global.splice(index, 1);
            },
            add_sales_campaign_detail_hadiah: function() {
              this.sales_campaign_detail_hadiah.push({
                nama_paket: '',
                jumlah_poin: 1,
                voucher_rupiah: 0,
                nama_hadiah: '',
              });
            },
            add_sales_campaign_detail_diskon_global: function() {
              this.sales_campaign_detail_diskon_global.push({
                nama_paket: '',
                qty: 0,
                tipe_diskon: '',
                diskon_value: 0,
                satuan: '',
              });
            },
            hapus_sales_campaign_detail_diskon_global: function(index) {
              this.sales_campaign_detail_diskon_global.splice(index, 1);
            },
            add_sales_campaign_detail_cashback_global: function() {
              this.sales_campaign_detail_cashback_global.push({
                qty: 1,
                cashback: 0,
                satuan: '',
              });
            },
            add_sales_campaign_detail_cashback_item: function() {
              this.detail_cashback_item.push({
                qty: 1,
                cashback: 0,
                satuan: '',
              });
            },
            hapus_detail_poin: function(index) {
              this.sales_campaign_detail_poin.splice(index, 1);
              h3_md_parts_sales_campaign_detail_poin_datatable.draw();
              h3_md_kelompok_part_sales_campaign_detail_poin_datatable.draw();
            },
            hapus_detail_diskon: function(index) {
              this.sales_campaign_detail_diskon.splice(index, 1);
              h3_md_parts_sales_campaign_detail_diskon_datatable.draw();
              h3_md_kelompok_part_sales_campaign_detail_diskon_datatable.draw();
            },
            hapus_detail_hadiah: function(index) {
              this.sales_campaign_detail_hadiah.splice(index, 1);
            },
            hapus_detail_cashback: function(index) {
              this.sales_campaign_detail_cashback.splice(index, 1);
              h3_md_parts_sales_campaign_detail_cashback_datatable.draw();
              h3_md_kelompok_part_sales_campaign_detail_cashback_datatable.draw();
            },
            hapus_detail_cashback_global: function(index) {
              this.sales_campaign_detail_cashback_global.splice(index, 1);
            },
            hapus_detail_cashback_item: function(index) {
              this.detail_cashback_item.splice(index, 1);
            },
            hapus_detail_gimmick: function(index) {
              this.sales_campaign_detail_gimmick.splice(index, 1);
            },
            error_exist: function(key) {
              return _.get(this.errors, key) != null;
            },
            get_error: function(key) {
              return _.get(this.errors, key)
            },
            get_config_periode_campaign: function() {
              condition = (this.mode == 'detail' || this.mode == 'edit') && this.sales_campaign.start_date != null && this.sales_campaign.end_date != null;

              config = {
                opens: 'left',
                autoUpdateInput: condition,
                locale: {
                  format: 'DD/MM/YYYY'
                }
              };

              if (condition) {
                config.startDate = new Date(this.sales_campaign.start_date);
                config.endDate = new Date(this.sales_campaign.end_date);
              }
              return config;
            },
            applyDatePeriodeCampaign: function(picker) {
              this.sales_campaign.start_date = picker.startDate.format('YYYY-MM-DD');
              this.sales_campaign.end_date = picker.endDate.format('YYYY-MM-DD');
            },
            cancelDatePeriodeCampaign: function(picker) {
              this.sales_campaign.start_date = '';
              this.sales_campaign.end_date = '';
            },
            get_config_periode_campaign_poin: function() {
              condition = (this.mode == 'detail' || this.mode == 'edit') && this.sales_campaign.start_date_poin != null && this.sales_campaign.end_date_poin != null;

              config = {
                opens: 'left',
                autoUpdateInput: condition,
                locale: {
                  format: 'DD/MM/YYYY'
                }
              };

              if (condition) {
                config.startDate = new Date(this.sales_campaign.start_date_poin);
                config.endDate = new Date(this.sales_campaign.end_date_poin);
              }
              return config;
            },
            applyDatePeriodeCampaignPoin: function(picker) {
              this.sales_campaign.start_date_poin = picker.startDate.format('YYYY-MM-DD');
              this.sales_campaign.end_date_poin = picker.endDate.format('YYYY-MM-DD');
            },
            cancelDatePeriodeCampaignPoin: function(picker) {
              this.sales_campaign.start_date_poin = '';
              this.sales_campaign.end_date_poin = '';
            },
            get_config_periode_campaign_diskon: function() {
              condition = (this.mode == 'detail' || this.mode == 'edit') && this.sales_campaign.start_date_diskon != null && this.sales_campaign.end_date_diskon != null;

              config = {
                opens: 'left',
                autoUpdateInput: condition,
                locale: {
                  format: 'DD/MM/YYYY'
                }
              };

              if (condition) {
                config.startDate = new Date(this.sales_campaign.start_date_diskon);
                config.endDate = new Date(this.sales_campaign.end_date_diskon);
              }
              return config;
            },
            applyDatePeriodeCampaignDiskon: function(picker) {
              this.sales_campaign.start_date_diskon = picker.startDate.format('YYYY-MM-DD');
              this.sales_campaign.end_date_diskon = picker.endDate.format('YYYY-MM-DD');
            },
            cancelDatePeriodeCampaignDiskon: function(picker) {
              this.sales_campaign.start_date_diskon = '';
              this.sales_campaign.end_date_diskon = '';
            },
            get_config_periode_campaign_cashback: function() {
              condition = (this.mode == 'detail' || this.mode == 'edit') && this.sales_campaign.start_date_cashback != null && this.sales_campaign.end_date_cashback != null;

              config = {
                opens: 'left',
                autoUpdateInput: condition,
                locale: {
                  format: 'DD/MM/YYYY'
                }
              };

              if (condition) {
                config.startDate = new Date(this.sales_campaign.start_date_cashback);
                config.endDate = new Date(this.sales_campaign.end_date_cashback);
              }
              return config;
            },
            applyDatePeriodeCampaignCashback: function(picker) {
              this.sales_campaign.start_date_cashback = picker.startDate.format('YYYY-MM-DD');
              this.sales_campaign.end_date_cashback = picker.endDate.format('YYYY-MM-DD');
            },
            cancelDatePeriodeCampaignCashback: function(picker) {
              this.sales_campaign.start_date_cashback = '';
              this.sales_campaign.end_date_cashback = '';
            },
            get_config_periode_campaign_gimmick: function() {
              condition = (this.mode == 'detail' || this.mode == 'edit') && this.sales_campaign.start_date_gimmick != null && this.sales_campaign.end_date_gimmick != null;

              config = {
                opens: 'left',
                autoUpdateInput: condition,
                locale: {
                  format: 'DD/MM/YYYY'
                }
              };

              if (condition) {
                config.startDate = new Date(this.sales_campaign.start_date_gimmick);
                config.endDate = new Date(this.sales_campaign.end_date_gimmick);
              }
              return config;
            },
            applyDatePeriodeCampaignGimmick: function(picker) {
              this.sales_campaign.start_date_gimmick = picker.startDate.format('YYYY-MM-DD');
              this.sales_campaign.end_date_gimmick = picker.endDate.format('YYYY-MM-DD');
            },
            cancelDatePeriodeCampaignGimmick: function(picker) {
              this.sales_campaign.start_date_gimmick = '';
              this.sales_campaign.end_date_gimmick = '';
            },
          },
          computed: {
            program_untuk_per_item_number: function() {
              return this.sales_campaign.program_untuk == 'Per Item Number';
            },
            program_untuk_per_kelompok_part: function() {
              return this.sales_campaign.program_untuk == 'Per Kelompok Part';
            },
            produk_program_cashback_global: function() {
              return this.sales_campaign.produk_program_cashback == 'Global';
            },
            produk_program_cashback_item: function() {
              return this.sales_campaign.produk_program_cashback == 'Per Item';
            },
          },
          watch: {
            'sales_campaign.kategori': function(n, o) {
              h3_md_parts_sales_campaign_detail_poin_datatable.draw();
              h3_md_kelompok_part_filter_part_sales_campaign_detail_poin_datatable.draw();
              h3_md_kelompok_part_sales_campaign_detail_poin_datatable.draw();
              h3_md_parts_sales_campaign_detail_diskon_datatable.draw();
              h3_md_kelompok_part_filter_part_sales_campaign_detail_diskon_datatable.draw();
              h3_md_kelompok_part_sales_campaign_detail_diskon_datatable.draw();
              h3_md_parts_sales_campaign_detail_cashback_datatable.draw();
              h3_md_kelompok_part_filter_part_sales_campaign_detail_cashback_datatable.draw();
              h3_md_kelompok_part_sales_campaign_detail_cashback_datatable.draw();
              h3_md_parts_sales_campaign_detail_gimmick_datatable.draw();
              h3_md_kelompok_part_filter_part_sales_campaign_detail_gimmick_datatable.draw();
              h3_md_kelompok_part_sales_campaign_detail_gimmick_datatable.draw();
            }
          },
        });
      </script>
    <?php
    } elseif ($set == "index") {
    ?>
      <div class="box">
        <div class="box-header with-border">
          <div class="container-fluid no-padding">
            <div class="col-md-6">
              <a href="h3/<?= $isi ?>/add">
                <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
              </a>
              <?php if ($this->input->get('history') != null) : ?>
                <a href="h3/<?= $isi ?>">
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> Non-History</button>
                </a>
              <?php else : ?>
                <a href="h3/<?= $isi ?>?history=true">
                  <button class="btn bg-maroon btn-flat margin"><i class="fa fa-history"></i> History</button>
                </a>
              <?php endif; ?>
            </div>
            <div class="col-md-6 text-right">
              <a href="h3/h3_md_update_diskon">
                <button class="btn bg-blue btn-flat margin">Update diskon</button>
              </a>
            </div>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="master_sales_campaign" class="table table-bordered table-hover table-condensed">
            <thead>
              <tr>
                <th>No.</th>
                <th>Kode Campaign</th>
                <th>Nama Campaign</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Akhir</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              master_sales_campaign = $('#master_sales_campaign').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/master_sales_campaign') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d) {
                    d.history = <?= $this->input->get('history') != null ? '1' : '0' ?>;
                  }
                },
                createdRow: function(row, data, index) {
                  $('td', row).addClass('align-middle');
                },
                columns: [{
                    data: 'index',
                    orderable: false,
                    width: '3%'
                  },
                  {
                    data: 'kode_campaign'
                  },
                  {
                    data: 'nama'
                  },
                  {
                    data: 'start_date'
                  },
                  {
                    data: 'end_date'
                  },
                  {
                    data: 'status'
                  },
                  {
                    data: 'action',
                    width: '3%',
                    orderable: false,
                    className: 'text-center'
                  },
                ],
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    <?php } elseif ($set == "perolehan_gimmick") { ?>
      <?php $this->load->view('h3/h3_md_sales_campaign_perolehan_gimmick'); ?>
    <?php } elseif ($set == "insentif_poin") { ?>
      <?php $this->load->view('h3/h3_md_sales_campaign_insentif_poin'); ?>
    <?php } elseif ($set == "insentif_cashback") { ?>
      <?php $this->load->view('h3/h3_md_sales_campaign_insentif_cashback'); ?>
    <?php } ?>
  </section>
</div>