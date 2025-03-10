<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vuejs-paginate.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script src="<?= base_url("assets/panel/onscan.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script>
  Vue.use(VueNumeric.default);
  Vue.component('paginate', VuejsPaginate);
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
  if ($set=="form") {
      $form     = '';
      $disabled = '';
      if ($mode=='insert') {
          $form = 'save';
      }
      if ($mode=='detail') {
          $disabled = 'disabled';
          $form = 'detail';
      }
      if ($mode=='edit') {
          $form = 'update';
      } ?>
    <div id="form_" class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>">
            <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
      </div><!-- /.box-header -->
      <div v-if='loading' class="overlay">
        <i class="text-light-blue fa fa-refresh fa-spin"></i>
      </div>
      <div class="box-body">
        <div class="row">
            <div class="col-md-12">
              <form  class="form-horizontal" action="dealer/<?= $isi ?>/<?= $form ?>" method="post" enctype="multipart/form-data">
                <div class="box-body">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Ekspedisi</label>
                    <div v-bind:class="{ 'has-error': error_exist('id_vendor') }" class="col-sm-3">
                      <input v-model='penerimaan_barang.vendor_name' type="text" class="form-control" readonly>
                      <small v-if="error_exist('id_vendor')" class="form-text text-danger">{{ get_error('id_vendor') }}</small>
                    </div>
                    <div class="col-sm-1 no-padding">
                      <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_ekspedisi_penerimaan_barang'><i class="fa fa-search"></i></button>
                    </div>
                    <div v-if='mode != "detail"'>
                    <?php $this->load->view('modal/h3_md_ekspedisi_penerimaan_barang') ?>
                    </div>
                    <script>
                      function pilih_ekspedisi_penerimaan_barang(data){
                        form_.penerimaan_barang.vendor_name = data.nama_ekspedisi;
                        form_.penerimaan_barang.id_vendor = data.id;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">Produk</label>
                    <div v-bind:class="{ 'has-error': error_exist('produk') }" class="col-sm-3">
                      <input v-model='penerimaan_barang.produk' type="text" class="form-control" readonly>
                      <small v-if="error_exist('produk')" class="form-text text-danger">{{ get_error('produk') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Surat Jalan Ekspedisi</label>
                    <div v-bind:class="{ 'has-error': error_exist('no_surat_jalan_ekspedisi') }" class="col-sm-3">
                      <input :readonly='mode != "insert" || !no_penerimaan_barang_empty' v-model='penerimaan_barang.no_surat_jalan_ekspedisi' type="text" class="form-control">
                      <small v-if="error_exist('no_surat_jalan_ekspedisi')" class="form-text text-danger">{{ get_error('no_surat_jalan_ekspedisi') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-offset-1 col-sm-2 control-label">Tanggal Surat Jalan Ekspedisi</label>
                    <div v-bind:class="{ 'has-error': error_exist('tgl_surat_jalan_ekspedisi') }" class="col-sm-3">
                      <input readonly type="text" class="form-control" id='tgl_surat_jalan_ekspedisi'>
                      <small v-if="error_exist('tgl_surat_jalan_ekspedisi')" class="form-text text-danger">{{ get_error('tgl_surat_jalan_ekspedisi') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-offset-6 col-sm-2 control-label">Jumlah Koli</label>
                    <div v-bind:class="{ 'has-error': error_exist('jumlah_koli') }" class="col-sm-3">
                      <div class="input-group">
                        <input disabled :value='jumlah_koli + " Koli"' type="text" class="form-control">
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-target='#h3_md_jumlah_koli_penerimaan_barang' data-toggle='modal'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <small v-if="error_exist('jumlah_koli')" class="form-text text-danger">{{ get_error('jumlah_koli') }}</small>
                    </div>
                  </div>
                  <?php $this->load->view('modal/h3_md_jumlah_koli_penerimaan_barang'); ?>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No Plat</label>
                    <div v-bind:class="{ 'has-error': error_exist('no_plat') }" class="col-sm-3">
                      <input v-model='penerimaan_barang.no_plat' type="text" class="form-control" readonly>
                      <small v-if="error_exist('no_plat')" class="form-text text-danger">{{ get_error('no_plat') }}</small>
                    </div>
                    <div class="col-sm-1 no-padding">
                      <button v-if='mode != "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_ekspedisi_item_penerimaan_barang'><i class="fa fa-search"></i></button>
                    </div>
                    <?php $this->load->view('modal/h3_md_ekspedisi_item_penerimaan_barang'); ?>
                    <script>
                      function pilih_ekspedisi_item_penerimaan_barang(data){
                        form_.penerimaan_barang.no_plat = data.no_polisi;
                        form_.penerimaan_barang.produk = data.produk_angkatan;
                        form_.penerimaan_barang.type_mobil = data.type_mobil;
                        form_.penerimaan_barang.nama_driver = data.nama_supir;
                      }
                    </script>
                    <label for="inputEmail3" class="col-sm-2 control-label">Berat / Truk</label>
                    <div v-bind:class="{ 'has-error': error_exist('berat_truk') }" class="col-sm-3">
                      <vue-numeric :disabled='mode == "detail"' class="form-control" precision='2' v-model='penerimaan_barang.berat_truk'></vue-numeric>
                      <small v-if="error_exist('berat_truk')" class="form-text text-danger">{{ get_error('berat_truk') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nama Driver</label>
                    <div v-bind:class="{ 'has-error': error_exist('nama_driver') }" class="col-sm-3">
                      <input :readonly='mode == "detail"' type="text" class="form-control" v-model='penerimaan_barang.nama_driver'>
                      <small v-if="error_exist('nama_driver')" class="form-text text-danger">{{ get_error('nama_driver') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-offset-1 col-sm-2 control-label">Harga Satuan</label>
                    <div v-bind:class="{ 'has-error': error_exist('harga_ongkos_angkut_part') }" class="col-sm-3">
                      <vue-numeric class="form-control" disabled v-model='penerimaan_barang.harga_ongkos_angkut_part' separator='.' currency='Rp '></vue-numeric>
                      <small v-if="error_exist('harga_ongkos_angkut_part')" class="form-text text-danger">{{ get_error('harga_ongkos_angkut_part') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Type Mobil</label>
                    <div v-bind:class="{ 'has-error': error_exist('type_mobil') }" class="col-sm-3">
                      <input readonly type="text" class="form-control" v-model='penerimaan_barang.type_mobil'>
                      <small v-if="error_exist('type_mobil')" class="form-text text-danger">{{ get_error('type_mobil') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-offset-1 col-sm-2 control-label">Total Harga</label>
                    <div v-bind:class="{ 'has-error': error_exist('total_harga') }" class="col-sm-3">
                      <vue-numeric class="form-control" disabled v-model='total_harga' separator='.' currency='Rp '></vue-numeric>
                      <small v-if="error_exist('total_harga')" class="form-text text-danger">{{ get_error('total_harga') }}</small>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">No. Surat Jalan AHM</label>
                    <div v-bind:class="{ 'has-error': error_exist('surat_jalan_ahm') }" class="col-sm-3">
                      <div class="row">
                        <div class="col-sm-6">
                          <input :value='total_surat_jalan_ahm_diterima + " Surat Jalan AHM"' type="text" class="form-control" readonly>
                        </div>
                        <div class="col-sm-6">
                          <vue-numeric class="form-control" readonly v-model='jumlah_koli_surat_jalan_ahm' currency='Koli' currency-symbol-position='suffix'></vue-numeric>
                        </div>
                      </div>
                      <small v-if="error_exist('surat_jalan_ahm')" class="form-text text-danger">{{ get_error('surat_jalan_ahm') }}</small>
                    </div>
                    <label for="inputEmail3" class="col-sm-3 control-label">Packing Sheet</label>
                    <div class="col-sm-3">
                      <input :value='total_packing_sheet_diterima + " Packing Sheet"' type="text" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Nomor Karton</label>
                    <div class="col-sm-3">
                      <div class="input-group">
                        <!-- <input :value='list_nomor_karton2.length + " Nomor Karton "' type="text" class="form-control" readonly> -->
                        <input :value='list_nomor_karton.length + " Nomor Karton "' type="text" class="form-control" readonly>
                        <div class="input-group-btn">
                          <button :disabled='mode == "detail"' class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h3_md_nomor_karton_penerimaan_barang'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                    </div>
                    <?php $this->load->view('modal/h3_md_nomor_karton_penerimaan_barang') ?>
                    <script>
                      $(document).ready(function(){
                        $("#h3_md_nomor_karton_penerimaan_barang").on('change',"input[type='checkbox']",function(e){
                          target = $(e.target);
                          nomor_karton = target.attr('data-nomor-karton');
                          nomor_karton_int = target.attr('data-nomor-karton-int');
                          jenis_penerimaan_barang = target.attr('data-jenis-penerimaan');

                          data = {
                            nomor_karton_int: nomor_karton_int,
                            jenis_penerimaan_barang: jenis_penerimaan_barang,
                            nomor_karton: nomor_karton,
                          };

                          
                          if(jenis_penerimaan_barang =='non_ev'){
                            if(target.is(':checked')){
                              form_.list_nomor_karton.push(data);
                            }else{
                              index_nomor_karton = _.findIndex(form_.list_nomor_karton, function(data){
                                return data.nomor_karton_int == nomor_karton_int;
                              });
                              nomor_karton = form_.list_nomor_karton[index_nomor_karton];
                              form_.list_nomor_karton.splice(index_nomor_karton, 1);
                            }
                          }else if(jenis_penerimaan_barang =='ev'){
                            if(form_.list_nomor_karton.length > 0){
                              alert("Terdapat data penerimaan non ev yang telah dichecklist, harap disimpan terlebih dahulu!.");
                              $(this).prop('checked', false);
                              return false;
                            }
                            if(target.is(':checked')){
                              form_.list_nomor_karton_ev.push(data);
                            }else{
                              index_nomor_karton = _.findIndex(form_.list_nomor_karton_ev, function(data){
                                return data.nomor_karton == nomor_karton;
                              });
                              nomor_karton = form_.list_nomor_karton_ev[index_nomor_karton];
                              form_.list_nomor_karton_ev.splice(index_nomor_karton, 1);
                            }
                          }

                          // if(target.is(':checked')){
                          //   form_.list_nomor_karton.push(data);
                          // }else{
                          //   index_nomor_karton = _.findIndex(form_.list_nomor_karton, function(data){
                          //     return data.nomor_karton_int == nomor_karton_int;
                          //   });
                          //   nomor_karton = form_.list_nomor_karton[index_nomor_karton];
                          //   form_.list_nomor_karton.splice(index_nomor_karton, 1);
                          // }

                          // data = {
                          //   nomor_karton: nomor_karton,
                          // };

                          // if(target.is(':checked')){
                          //   form_.list_nomor_karton2.push(data);
                          // }else{
                          //   index_nomor_karton2 = _.findIndex(form_.list_nomor_karton2, function(data){
                          //     return data.nomor_karton == nomor_karton;
                          //   });
                          //   nomor_karton2 = form_.list_nomor_karton2[index_nomor_karton2];
                          //   form_.list_nomor_karton2.splice(index_nomor_karton2, 1);
                          // }


                          // h3_md_nomor_karton_penerimaan_barang_datatable.draw(false);
                          // drawing_no_karton(false);
                        });
                      });
                    </script>
                  </div>
                  <div v-if='mode == "detail"' class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-3">
                      <input readonly v-model='penerimaan_barang.status' type="text" class="form-control">
                    </div>
                  </div>
                  <div class="container-fluid margin">
                      <div class="row">
                        <div class="col-sm-6">
                          <a v-if='mode == "detail" && penerimaan_barang.status != "Closed"' :href="'h3/h3_md_laporan_penerimaan_barang/edit?no_penerimaan_barang=' + penerimaan_barang.no_penerimaan_barang" class='btn btn-flat btn-sm btn-warning'>Edit</a>
                          <button v-if='mode != "detail"' class="btn btn-flat btn-sm btn-primary" type='button' @click.prevent='close'>Close</button>
                          <button v-if='mode != "detail"' class="btn btn-flat btn-sm btn-warning" @click.prevent='keep'>Keep</button>
                          <button v-if='start_scan == 0 && mode != "detail"' @click.prevent='start_scan = 1' class="btn btn-flat btn-sm btn-success">Start Scan</button>
                          <button v-if='start_scan == 1' @click.prevent='start_scan = 0' class="btn btn-flat btn-sm btn-danger">Stop Scan</button>
                        </div>
                        <div class="col-sm-6 text-right"> 
                          <button class="btn btn-sm btn-primary btn-flat" type='button' @click.prevent='edit_non_ev' >Edit Data Non EV</button>
                          <button class="btn btn-sm btn-success btn-flat" type='button' @click.prevent='edit_ev' >Edit Data EV</button>
                          <button class="btn btn-sm btn-info btn-flat" type='button' data-toggle='modal' data-target='#h3_md_show_penerimaan_barang'>Show</button>
                          <button class="btn btn-sm btn-warning btn-flat" type='button' data-toggle='modal' data-target='#h3_md_check_penerimaan_barang'>Check</button>
                        </div>
                      </div>
                  </div>
                  <div class="container-fluid" v-if='mode != "detail"'>
                      <div class="row">
                        <div class="col-sm-12">
                          <table class="table table-condensed table-striped">
                              <tr>
                                <td width='3%'></td>
                                <td width='3%'>No.</td>
                                <td>Tanggal PS</td>
                                <td>No. Packing Sheet</td>
                                <td>No. Karton</td>
                                <td>Part Number</td>
                                <td>Nama Part</td>
                                <td>Serial Number</td>
                                <td>Qty PS</td>
                                <td width='7%'>
                                  <span>Qty Scan</span>
                                  <input v-if='mode != "detail"' type="checkbox" true-value='1' false-value='0' v-model='check_all_qty_scan'>
                                </td>
                                <td>Qty (+/-)</td>
                                <td width='8%'>Lokasi Rak</td>
                                <td width='5%'>Reason</td>
                                <td width='5%'></td>
                                <td v-if='mode != "detail"' width='5%'></td>
                              </tr>
                              <tr v-if='parts.length > 0' v-for='(part, index) of parts'>
                                <td class='align-middle' width='3%'>
                                  <span v-if='!part_complete(part)'><i class="fa fa-circle text-yellow"></i></span>
                                  <span v-if='part_complete(part)'><i class="fa fa-circle text-green"></i></span>
                                </td>
                                <td class='align-middle' width='3%'>{{ index + 1 + ((page * 10) - 10) }}.</td>
                                <td class='align-middle'>{{ part.packing_sheet_date }}</td>
                                <td class='align-middle'>{{ part.packing_sheet_number }}</td>
                                <td class='align-middle'>{{ part.nomor_karton }}</td>
                                <td class='align-middle'>{{ part.id_part }}</td>
                                <td class='align-middle'>{{ part.nama_part }}</td>
                                <td class='align-middle' v-if='part.kategori_penerimaan_barang =="non_ev"' > </td>
                                <td class='align-middle' v-if='part.kategori_penerimaan_barang =="ev"' >{{part.serial_number}}</td>
                                <td class='align-middle'>{{ part.packing_sheet_quantity }}</td>
                                <td class='align-middle'>
                                  <vue-numeric :read-only='(mode == "detail" || part.tersimpan == 1) && part.edit == 0' class="form-control" v-model='part.qty_diterima' separator='.'></vue-numeric>
                                </td>
                                <td class='align-middle'>{{ get_selisih_symbol(part) }} {{ hitung_qty_plus_minus(part) }}</td>
                                <td class='align-middle'>
                                  <input type="text" class="form-control" v-model='part.kode_lokasi_rak' readonly @click.prevent='open_lokasi_rak_modal(part)'>
                                  <input v-if='show_lokasi_temporary(part)' style='margin-top: 10px;' type="text" class="form-control" v-model='part.kode_lokasi_rak_temporary' readonly @click.prevent='open_lokasi_rak_temporary_modal(part)'>
                                </td>
                                <td class="align-middle">
                                  <button class="btn btn-flat btn-info" @click.prevent='open_reason(part)'><i class="fa fa-eye"></i></button>
                                </td>
                                <td class="align-middle">
                                  <button v-if='mode != "detail" && part.tersimpan == 1 && part.edit == 0 && penerimaan_barang.status != "Closed"' class="btn btn-flat btn-warning" @click.prevent='set_edit(part)'><i class="fa fa-pencil"></i></button>
                                  <button v-if='mode != "detail" && part.tersimpan == 1 && part.edit == 1 && penerimaan_barang.status != "Closed"' class="btn btn-flat btn-info" @click.prevent='save_edit(part)'><i class="fa fa-save"></i></button>
                                </td>
                                <td v-if='mode != "detail"' class="align-middle">
                                  <!-- <button class="btn btn-flat btn-danger" @click.prevent='hapus(part)'><i class="fa fa-trash"></i></button> -->
                                </td>
                              </tr>
                              <tr v-if='parts.length < 1'>
                                <td colspan='13' class='text-center'>Tidak ada data</td>
                              </tr>
                          </table>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-6">
                          <!-- <div class="row">
                            <div class="col-sm-12">
                              <span v-if='!no_penerimaan_barang_empty' class='text-bold'>Terdapat <vue-numeric read-only separator='.' v-model='total_parts_tersimpan'></vue-numeric> baris telah diterima dari <vue-numeric read-only separator='.' v-model='total_parts'></vue-numeric> baris</span>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12">
                              <span v-if='!no_penerimaan_barang_empty' class='text-bold'>Terdapat <vue-numeric read-only separator='.' v-model='total_item_tersimpan'></vue-numeric> item telah diterima dari <vue-numeric read-only separator='.' v-model='total_item'></vue-numeric> item</span>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-12">
                              <span v-if='!no_penerimaan_barang_empty' class='text-bold'>Terdapat <vue-numeric read-only separator='.' v-model='total_pcs_tersimpan'></vue-numeric> pcs telah diterima dari <vue-numeric read-only separator='.' v-model='total_pcs'></vue-numeric> pcs</span>
                            </div>
                          </div> -->
                        </div>
                        <!-- <div class="col-sm-6 text-right">
                          <paginate
                            v-model='page'
                            :page-count="jumlah_page"
                            :page-range="3"
                            :click-handler="set_page"
                            :prev-text="'Prev'"
                            :next-text="'Next'"
                            :container-class="'pagination'">
                          </paginate>
                        </div> -->
                        
                        <div class="col-sm-6 text-right">
                          <paginate v-if="mode === 'edit_non_ev'"
                            v-model='page'
                            :page-count="jumlah_page"
                            :page-range="3"
                            :click-handler="set_page"
                            :prev-text="'Prev'"
                            :next-text="'Next'"
                            :container-class="'pagination'">
                          </paginate>
                          
                          <paginate v-if="mode === 'edit_ev'"
                            v-model='page2'
                            :page-count="jumlah_page2"
                            :page-range="3"
                            :click-handler="set_page2"
                            :prev-text="'Prev'"
                            :next-text="'Next'"
                            :container-class="'pagination'">
                          </paginate>
                        </div>
                      </div>
                  </div>
                  <div class="box-footer">
                    <div class="container-fluid no-padding">
                    <div v-if='parts.length > 0 && lokasi_tidak_mencukupi.length > 0' class="alert alert-warning" role="alert">
                      <strong>Perhatian!</strong> Terdapat Lokasi Rak yang tidak dapat mencukupi kebutahan penyimpanan part, antara lain:
                      <ul>
                        <li v-for='lokasi of lokasi_tidak_mencukupi'>Pada lokasi {{ lokasi.kode_lokasi_rak }} diperlukan untuk menyimpan {{ lokasi.kapasitas_diperlukan }} item dengan kapasitas yang tersedia hanya {{ lokasi.kapasitas_tersedia }}.</li>
                      </ul>
                    </div>
                    </div>
                    <div class="container-fluid no-padding">
                      <div class="col-sm-6 no-padding">
                        
                      </div>
                      <div class="col-sm-6 text-right no-padding">
                        <button v-if='mode != "detail"' :disabled='lokasi_tidak_mencukupi.length > 0' class="btn btn-flat btn-sm btn-primary" @click.prevent='simpan_parts'>Simpan</button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php $this->load->view('modal/h3_md_show_penerimaan_barang'); ?>
                <?php $this->load->view('modal/h3_md_check_penerimaan_barang'); ?>
                <?php $this->load->view('modal/h3_md_pop_up_barang_kurang_tanpa_reason_penerimaan_barang'); ?>
                <?php $this->load->view('modal/h3_md_view_reasons_penerimaan_barang'); ?>
                <?php $this->load->view('modal/h3_md_pop_up_kekurangan_part') ?>
                <?php $this->load->view('modal/h3_md_reason_ahm_penerimaan_barang'); ?>
                <?php $this->load->view('modal/h3_md_reason_ekspedisi_penerimaan_barang'); ?>
                <?php $this->load->view('modal/h3_md_pop_up_alasan_barang_kurang') ?>
                <?php $this->load->view('modal/h3_md_pop_up_kekurangan_oleh_ekspedisi') ?>
                <?php $this->load->view('modal/h3_md_lokasi_rak_penerimaan_barang') ?>
                <?php $this->load->view('modal/h3_md_view_stock_lokasi_penerimaan_barang') ?>
                <script>
                  function pilih_lokasi_rak_penerimaan_barang(data) {
                    confirmed = true;
                    if (parseInt(form_.parts[form_.index_part].packing_sheet_quantity) > parseInt(data.kapasitas_tersedia)) {
                      confirmed = confirm('Lokasi ' + data.kode_lokasi_rak + ' sudah penuh.');
                    }

                    if(confirmed){
                      form_.parts[form_.index_part].id_lokasi_rak = data.id;
                      form_.parts[form_.index_part].kode_lokasi_rak = data.kode_lokasi_rak;
                      form_.parts[form_.index_part].kapasitas_tersedia = data.kapasitas_tersedia;
                    }
                  }
                </script>
                <div v-if='mode != "detail"'> 
                <?php $this->load->view('modal/h3_md_lokasi_rak_temporary_penerimaan_barang') ?>
                </div>
                <script>
                  function pilih_lokasi_rak_temporary_penerimaan_barang(data) {
                    form_.parts[form_.index_part].id_lokasi_rak_temporary = data.id;
                    form_.parts[form_.index_part].kode_lokasi_rak_temporary = data.kode_lokasi_rak;
                  }
                </script>
            </form>
          </div>
        </div>
      </div>
    </div>
<script>
  var form_ = new Vue({
      el: '#form_',
      data: {
        mode : '<?= $mode ?>',
        start_scan: 0,
        index_part: 0,
        list_nomor_karton: [],
        list_nomor_karton_ev: [],
        reasons: [],
        reasons_ahm: [],
        reasons_ekspedisi: [],
        jumlah_koli_surat_jalan_ahm: 0,
        loading: false,
        errors: [],
        check_all_qty_scan: 0,
        check_all_qty_scan2: 0,
        <?php if($mode == 'detail' or $mode == 'edit'): ?>
        penerimaan_barang: <?= json_encode($penerimaan_barang) ?>,
        list_jumlah_koli: <?= json_encode($list_jumlah_koli) ?>,
        <?php else: ?>
        penerimaan_barang: {
          id: null,
          no_penerimaan_barang: '',
          no_surat_jalan_ekspedisi: '',
          tgl_surat_jalan_ekspedisi: '',
          no_plat: '',
          nama_driver: '',
          id_vendor: '',
          vendor_name: '',  
          jenis_ongkos_angkut_part: '',
          per_satuan_ongkos_angkut_part: '',
          harga_ongkos_angkut_part: '',
          produk: '',
          type_mobil: '',
          berat_truk: 0,
          surat_jalan_ahm: '',
          jumlah_koli: '',
          alasan_barang_kurang: '',
          status: 'Open',
          ahm_belum_kirim: 0,
          add_new: 1,
        },
        list_jumlah_koli: [],
        <?php endif; ?>
        parts: [],
        parts_for_checking: [],

        perPage: 10,
        page: 1,
        total_data: 0,

        perPage2: 10,
        page2: 1,
        total_data2: 0,

        total_surat_jalan_ahm_diterima: 0,
        total_packing_sheet_diterima: 0,
        total_parts: 0,
        total_parts_tersimpan: 0,
        total_item: 0,
        total_item_tersimpan: 0,
        total_pcs: 0,
        total_pcs_tersimpan: 0,
      },
      mounted: function(){
        if(this.mode != "insert"){
          // this.get_total_surat_jalan_ahm_diterima();
          // this.get_total_packing_sheet_diterima();
          // this.get_parts();
          // this.get_jumlah_koli();
        }

        // if(this.mode =="edit"){
        //   this.get_parts();
        // }
        $(document).ready(function(){
          $('#tgl_surat_jalan_ekspedisi').datepicker({
            autoclose: true,
            format: 'dd/mm/yyyy'
          })
          .on('changeDate', function(e){
            form_.penerimaan_barang.tgl_surat_jalan_ekspedisi = e.format('yyyy-mm-dd');
          });

          // Scanner listener
          onScan.attachTo(document, {
              suffixKeyCodes: [13], // enter-key expected at the end of a scan
              onScan: function(sCode, iQty) { // Alternative to document.addEventListener('scan')
                if(form_.start_scan == 1){
                  form_.get_info_nomor_karton_dari_scanner(sCode);
                }else{
                  toastr.options = {
                    preventDuplicates: true,
                    preventOpenDuplicates: true
                  };
                  toastr.warning('Anda menggunakan scanner, tapi belum mengaktifkan mode "Start Scan"');
                }
              },
              onScanError: function(error){
                console.warn(error);
              }
          });
        });
        if(this.mode != 'insert'){
          date = new Date(this.penerimaan_barang.tgl_surat_jalan_ekspedisi);
          $(document).ready(function(){
            $("#tgl_surat_jalan_ekspedisi").datepicker("setDate", date);
            $('#tgl_surat_jalan_ekspedisi').datepicker('update');
          });
        }
      },
      methods:{
        edit_non_ev: function(){
          this.mode = 'edit_non_ev';
          this.get_parts();
        },
        edit_ev: function(){
          this.mode = 'edit_ev';
          this.get_parts2();
        },
        get_total_surat_jalan_ahm_diterima: function(){
          axios.get('<?= base_url(sprintf('h3/%s/get_count_surat_jalan_ahm_penerimaan_barang', $isi)) ?>', {
            params: {
              no_penerimaan_barang_int: this.penerimaan_barang.id
            }
          })
          .then(function(response){
            data = response.data;

            form_.total_surat_jalan_ahm_diterima = data.count;
          })
          .catch(function(error){
            data = error.response.data;

            if(data.message != null){
              toastr.error(data.message);
            }else{
              toastr.error(error);
            }
          });
        },
        get_total_packing_sheet_diterima: function(){
          axios.get('<?= base_url(sprintf('h3/%s/get_count_packing_sheet_penerimaan_barang', $isi)) ?>', {
            params: {
              no_penerimaan_barang_int: this.penerimaan_barang.id
            }
          })
          .then(function(response){
            data = response.data;

            form_.total_packing_sheet_diterima = data.count;
          })
          .catch(function(error){
            data = error.response.data;

            if(data.message != null){
              toastr.error(data.message);
            }else{
              toastr.error(error);
            }
          });
        },
        tambah_jumlah_koli: function(){
          this.list_jumlah_koli.push({
            koli: 0,
            keterangan: '',
          });
        },
        hapus_jumlah_koli: function(index){
          this.list_jumlah_koli.splice(index, 1);
        },
        keep: function(){
          post = _.pick(this.penerimaan_barang, [
            'no_penerimaan_barang', 'no_surat_jalan_ekspedisi', 'tgl_surat_jalan_ekspedisi', 'no_plat','nama_driver','id_vendor', 'produk', 'jenis_ongkos_angkut_part',
            'per_satuan_ongkos_angkut_part','harga_ongkos_angkut_part', 'tgl_surat_jalan_ekspedisi',
            'surat_jalan_ahm', 'alasan_barang_kurang','status', 'type_mobil', 'berat_truk', 'ahm_belum_kirim'
          ]);
          post.jumlah_koli = this.jumlah_koli;
          post.total_harga = this.total_harga;
          post.list_jumlah_koli = this.list_jumlah_koli;
          
          post.parts = _.chain(this.parts)
          .filter(function(part){
            return part.tersimpan == 0;
          })
          .map(function(part){
            return _.pick(part, [
              'id_part_int', 'id_part', 'qty_diterima', 'nomor_karton', 
              'packing_sheet_quantity', 'no_po', 'no_po_int', 'id_lokasi_rak', 
              'packing_sheet_number', 'packing_sheet_number_int', 'reasons', 'tersimpan', 'surat_jalan_ahm', 'surat_jalan_ahm_int','serial_number'
            ]);
          })
          .value();

          this.errors = {};
          this.loading = true;
          axios.post('h3/<?= $isi ?>/keep', Qs.stringify(post))
          .then(function(res){
            data = res.data;
            window.location = data.redirect_url;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
              toastr.error(data.message);
            }else{
              toastr.error(err);
            }

            form_.loading = false;
          });
        },
        close: function(){
          this.loading = true;
          axios.post('api/md/h3/check_kekurangan_part/total_kekurangan_part', Qs.stringify({
            no_surat_jalan_ekspedisi: this.penerimaan_barang.no_surat_jalan_ekspedisi
          }))
          .then(function(res){
            if(res.data > 0 && form_.penerimaan_barang.ahm_belum_kirim == 0){
              // check_kekurangan_part.draw();
              drawing_check_kekurangan_part();
              $('#h3_md_pop_up_kekurangan_part').modal('show');
            }else{
              form_.penerimaan_barang.status = 'Closed';
              form_.keep();
            }
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; })
          ;
        },
        send_data: function(){
          this.errors = {};
          this.loading = true;

          post = _.pick(this.penerimaan_barang, [
            'no_surat_jalan_ekspedisi', 'tgl_surat_jalan_ekspedisi', 'no_plat','nama_driver','id_vendor', 'produk', 'jenis_ongkos_angkut_part',
            'per_satuan_ongkos_angkut_part','harga_ongkos_angkut_part', 'tgl_surat_jalan_ekspedisi',
            'surat_jalan_ahm', 'alasan_barang_kurang','status', 'type_mobil', 'berat_truk', 'ahm_belum_kirim'
          ]);
          post.jumlah_koli = this.jumlah_koli;
          post.total_harga = this.total_harga;
          post.list_jumlah_koli = this.list_jumlah_koli;
          
          if(this.mode == 'edit'){
            post.no_penerimaan_barang = this.penerimaan_barang.no_penerimaan_barang;
          }

          post.parts = _.chain(this.parts)
          .filter(function(part){
            return part.tersimpan == 0;
          })
          .map(function(part){
            return _.pick(part, [
              'id_part', 'id_part_int', 'qty_diterima', 
              'nomor_karton', 'packing_sheet_quantity', 
              'no_po', 'no_po_int', 'id_lokasi_rak', 'packing_sheet_number', 'packing_sheet_number_int',
              'reasons', 'tersimpan', 'surat_jalan_ahm', 'surat_jalan_ahm_int','serial_number'
            ]);
          })
          .value();

          axios.post('h3/<?= $isi ?>/<?= $form ?>', Qs.stringify(post))
          .then(function(res){
            window.location = 'h3/<?= $isi ?>/detail?no_penerimaan_barang=' + res.data.no_penerimaan_barang;
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
              toastr.error(data.message);
            }else{
              toastr.error(err);
            }
          })
          .then(function(){ form_.loading = false; });
        },
        part_complete: function(part){
          return part.id_lokasi_rak != null && part.id_lokasi_rak != '';
        },
        open_lokasi_rak_modal: function(part){
          if(this.mode == 'detail' || part.tersimpan == 1) return;
          
          this.index_part = _.findIndex(this.parts, function(data){
            return part.id_part == data.id_part && part.nomor_karton == data.nomor_karton && part.packing_sheet_number == data.packing_sheet_number && part.no_po == data.no_po;
          });
          // h3_md_lokasi_rak_penerimaan_barang_datatable.draw();
          drawing_lokasi_rak_penerimaan_barang();
          $('#h3_md_lokasi_rak_penerimaan_barang').modal('show');
        },
        open_lokasi_rak_temporary_modal: function(part){
          if(this.mode == 'detail' || part.tersimpan == 1) return;
          
          this.index_part = _.findIndex(this.parts, function(data){
            return part.id_part == data.id_part && part.nomor_karton == data.nomor_karton && part.packing_sheet_number == data.packing_sheet_number && part.no_po == data.no_po;
          });
          h3_md_lokasi_rak_temporary_penerimaan_barang_datatable.draw();
          $('#h3_md_lokasi_rak_temporary_penerimaan_barang').modal('show');
        },
        hitung_qty_plus_minus: function(part){
          return Math.abs(part.packing_sheet_quantity - part.qty_diterima);
        },
        get_selisih_symbol: function(data){
          if(parseInt(data.packing_sheet_quantity) > parseInt(data.qty_diterima)){
            return '-';
          }
          if(parseInt(data.packing_sheet_quantity) < parseInt(data.qty_diterima)){
            return '+';
          }
        },
        update_alasan_barang_kurang: function(string){
          if(string == 'Kehilangan oleh Ekspedisi'){
            this.penerimaan_barang.status = 'Closed';
          }

          if(string == 'Masih di Ekspedisi'){
            this.penerimaan_barang.status = 'Open';
          }

          this.penerimaan_barang.alasan_barang_kurang = string;
          $('#h3_md_pop_up_kekurangan_part').modal('hide');
          $('#h3_md_pop_up_alasan_barang_kurang').modal('hide');
          $('#h3_md_pop_up_kekurangan_oleh_ekspedisi').modal('hide');

          this.send_data();
        },
        harga_ekspedisi: function(){
          if(this.penerimaan_barang.id_vendor == '' || this.penerimaan_barang.type_mobil == '') return;

          this.loading = true;
          axios.get('h3/<?= $isi ?>/harga_ekspedisi', {
            params: {
              id_vendor: this.penerimaan_barang.id_vendor,
              type_mobil: this.penerimaan_barang.type_mobil
            }
          })
          .then(function(res){
            form_.penerimaan_barang.harga_ongkos_angkut_part = res.data.harga_ongkos_angkut_part;
            form_.penerimaan_barang.per_satuan_ongkos_angkut_part = res.data.per_satuan_ongkos_angkut_part;
            form_.penerimaan_barang.jenis_ongkos_angkut_part = res.data.jenis_ongkos_angkut_part;
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; });
        },
        get_parts: _.debounce(function(reset_page = false){
          post = {
            no_penerimaan_barang: this.penerimaan_barang.no_penerimaan_barang,
            no_surat_jalan_ekspedisi: this.penerimaan_barang.no_surat_jalan_ekspedisi,
            limit: this.perPage,
            start: (this.page * this.perPage) - this.perPage,
            status: this.penerimaan_barang.status,
            add_new: this.penerimaan_barang.add_new,
          };
          post.list_nomor_karton = _.map(this.list_nomor_karton, function(data){
            return data.nomor_karton_int;
          });
          
          // post.list_nomor_karton2 = _.map(this.list_nomor_karton2, function(data){
          //   return data.nomor_karton;
          // });

          if(reset_page == true){
            post.start = 0;
          }
          
          this.loading = true;
          axios.post('h3/<?= $isi ?>/get_parts', Qs.stringify(post))
          .then(function(res){
            form_.parts = res.data.parts;
            form_.total_data = res.data.total;

            if(reset_page == true){
              form_.page = 1;
            }
          })
          .catch(function(err){
            data = err.response.data;

            if(data.error_type == 'validation_error'){
              toastr.error(data.message);
              form_.errors = data.errors
            }else{
              toastr.error(data.message);
            }
          })
          .then(function(){ 
            form_.loading = false;
            // form_.get_info_penyelesaian();
            // form_.get_total_surat_jalan_ahm_diterima();
            // form_.get_total_packing_sheet_diterima();
          });
        }, 500),
        get_parts2: _.debounce(function(reset_page2 = false){
          post = {
            no_penerimaan_barang: this.penerimaan_barang.no_penerimaan_barang,
            no_surat_jalan_ekspedisi: this.penerimaan_barang.no_surat_jalan_ekspedisi,
            limit2: this.perPage2,
            start2: (this.page2 * this.perPage2) - this.perPage2,
            status: this.penerimaan_barang.status,
            add_new: this.penerimaan_barang.add_new,
          };

          post.list_nomor_karton_ev = _.map(this.list_nomor_karton_ev, function(data){
            return data.nomor_karton;
          });

          if(reset_page2 == true){
            post.start2 = 0;
          }
          
          this.loading = true;
          axios.post('h3/<?= $isi ?>/get_parts2', Qs.stringify(post))
          .then(function(res){
            form_.parts = res.data.parts;
            form_.total_data2 = res.data.total;

            if(reset_page2 == true){
              form_.page2 = 1;
            }
          console.log('jumlah_page2:', form_.total_data2);
          })
          .catch(function(err){
            data = err.response.data;

            if(data.error_type == 'validation_error'){
              toastr.error(data.message);
              form_.errors = data.errors
            }else{
              toastr.error(data.message);
            }
          })
          .then(function(){ 
            form_.loading = false;
            // form_.get_info_penyelesaian();
            // form_.get_total_surat_jalan_ahm_diterima();
            // form_.get_total_packing_sheet_diterima();
          });
        }, 500),
        reset_packing_sheet: function(){
          this.packing_sheet_number = '';
          this.reset_nomor_karton();
        },
        reset_nomor_karton: function(){
          this.nomor_karton = '';
          this.reset_part_number();
        },
        reset_part_number: function(){
          this.part_number = '';
        },
        simpan_parts: function(){
          if(this.part_tanpa_lokasi_rak.length > 0){
            toastr.error('Terdapat part penerimaan yang belum dipilih lokasi rak.');
            return;
          }

          if(this.part_harus_mengisi_lokasi_temporary.length > 0){
            toastr.error('Terdapat part penerimaan yang harus mengisi lokasi temporary.');
            return;
          }

          if(this.terdapat_part_dengan_reasons_tidak_lengkap){
            $('#h3_md_pop_up_barang_kurang_tanpa_reason_penerimaan_barang').modal('show');
            return;
          }
          
          post = _.pick(this.penerimaan_barang, [
            'no_penerimaan_barang', 'no_surat_jalan_ekspedisi', 'tgl_surat_jalan_ekspedisi', 'no_plat','nama_driver','id_vendor', 'produk', 'jenis_ongkos_angkut_part',
            'per_satuan_ongkos_angkut_part','harga_ongkos_angkut_part', 'tgl_surat_jalan_ekspedisi',
            'surat_jalan_ahm', 'alasan_barang_kurang','status', 'type_mobil', 'berat_truk', 'ahm_belum_kirim'
          ]);
          post.jumlah_koli = this.jumlah_koli;
          post.total_harga = this.total_harga;
          post.list_jumlah_koli = this.list_jumlah_koli;

          post.parts = _.chain(this.parts)
          .filter(function(data){
            return data.tersimpan == 0 && data.id_lokasi_rak != null && data.id_lokasi_rak != '';
          })
          .value();

          this.loading = true;
          this.errors = {};
          axios.post('h3/<?= $isi ?>/simpan_parts', Qs.stringify(post))
          .then(function(res){
            penerimaan_barang = res.data.penerimaan_barang;
            form_.penerimaan_barang.id = penerimaan_barang.id;
            form_.penerimaan_barang.no_penerimaan_barang = penerimaan_barang.no_penerimaan_barang;

            parts = res.data.parts;
            for (part of parts) {
              index_part = _.findIndex(form_.parts, function(data){
                return data.id_part == part.id_part &&
                data.nomor_karton == part.nomor_karton &&
                data.packing_sheet_number == part.packing_sheet_number &&
                data.no_po == part.no_po;
              });
              form_.parts.splice(index_part, 1, part);
            }

            toastr.success('Data berhasil disimpan.');

            if(form_.list_nomor_karton.length > 0){
              form_.list_nomor_karton = [];
              form_.list_nomor_karton_ev = [];
              // form_.get_parts(true);
            // if(form_.list_nomor_karton2.length > 0){
            //   form_.list_nomor_karton2 = [];
              // form_.get_parts(true);
            }else{
              // form_.get_parts();
            }
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
              toastr.error(data.message);
            }else{
              toastr.error(err);
            }
          })
          .then(function(){
            form_.loading = false;
            h3_md_nomor_karton_penerimaan_barang_datatable.draw(false);
            // drawing_no_karton(false);
            // h3_md_lokasi_rak_penerimaan_barang_datatable.draw(false);
            // h3_md_lokasi_rak_temporary_penerimaan_barang_datatable.draw(false);

            barang_belum_dicek.draw(false);
            barang_sudah_dicek.draw(false);
            // check_kekurangan_part.draw(false);
            drawing_check_kekurangan_part(false);
            form_.check_all_qty_scan = 0;
          });
        },
        get_reason_ahm_qty: function(data){
          reasons = data.reasons;
          return _.chain(reasons)
          .filter(function(d){
            return d.tipe_claim == 'Kualitas' || d.tipe_claim == 'Non Kualitas';
          })
          .filter(function(d){
            return d.checked == 1;
          })
          .sumBy(function(d){
            return d.qty;
          })
          .value();
        },
        get_reason_ekspedisi_qty: function(data){
          reasons = data.reasons;
          return _.chain(reasons)
          .filter(function(d){
            return d.tipe_claim == 'Claim Ekspedisi';
          })
          .filter(function(d){
            return d.checked == 1;
          })
          .sumBy(function(d){
            return d.qty;
          })
          .value();
        },
        open_reason: function(part){
          this.index_part = _.findIndex(this.parts, function(data){
            return part.id_part == data.id_part && part.nomor_karton == data.nomor_karton && part.packing_sheet_number == data.packing_sheet_number && part.no_po == data.no_po;
          });
          this.reasons = this.parts[this.index_part].reasons;
          $('#h3_md_view_reasons_penerimaan_barang').modal('show');
        },
        set_edit: function(part){
          index = _.findIndex(this.parts, function(data){
            return part.id_part == data.id_part && part.nomor_karton == data.nomor_karton && part.packing_sheet_number == data.packing_sheet_number && part.no_po == data.no_po;
          });

          this.parts[index].edit = 1;
        },
        save_edit: function(part){
          index = _.findIndex(this.parts, function(data){
            return part.id_part == data.id_part && part.nomor_karton == data.nomor_karton && part.packing_sheet_number == data.packing_sheet_number && part.no_po == data.no_po;
          });

          selisih = Math.abs(this.parts[index].packing_sheet_quantity - this.parts[index].qty_diterima);
          qty_reason = form_.get_sum_of_qty_reasons(this.parts[index].reasons);

          if(selisih != qty_reason){
            toastr.warning('Kuantitas Selisih Penerimaan tidak sama dengan Kuantitas Reason.');
            return;
          }

          this.parts[index].edit = 0;

          part.no_surat_jalan_ekspedisi = this.penerimaan_barang.no_surat_jalan_ekspedisi;

          this.loading = true;
          axios.post('h3/<?= $isi ?>/save_edit', Qs.stringify(part))
          .then(function(res){
            console.log(res);
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; })
        },
        hapus: function(part){
          index = _.findIndex(this.parts, function(data){
            return part.id_part == data.id_part && part.nomor_karton == data.nomor_karton && part.packing_sheet_number == data.packing_sheet_number && part.no_po == data.no_po;
          });

          this.parts[index].edit = 0;

          this.loading = true;
          axios.post('h3/<?= $isi ?>/hapus_parts', Qs.stringify(part))
          .then(function(res){
            console.log(res);
          })
          .catch(function(err){
            toastr.error(err);
          })
          .then(function(){ form_.loading = false; })
        },
        open_reason_ekspedisi: function(part){
          this.index_part = _.findIndex(this.parts, function(data){
            return part.id_part == data.id_part && part.nomor_karton == data.nomor_karton && part.packing_sheet_number == data.packing_sheet_number && part.no_po == data.no_po;
          });
          this.reasons_ekspedisi = _.chain(this.parts[this.index_part].reasons)
          .filter(function(d){
            return d.tipe_claim == 'Claim Ekspedisi';
          })
          .value();
          
          $('#h3_md_pop_up_kekurangan_part').modal('hide');
          $('#h3_md_reason_ekspedisi_penerimaan_barang').modal('show');
        },
        ahm_belum_kirim: function(){
          this.penerimaan_barang.ahm_belum_kirim = 1;
          $('#h3_md_pop_up_kekurangan_part').modal('hide');
          this.close();
        },
        create_berita_acara: function(){
          if(check_input.checked.length < 1){
            return;
          }
          
          this.loading = true;
          this.errors = {};
          axios.get('h3/h3_md_laporan_penerimaan_barang/create_berita_acara', {
            params: {
              id_penerimaan_barang: check_input.checked,
              no_surat_jalan_ekspedisi: this.penerimaan_barang.no_surat_jalan_ekspedisi,
              id_vendor: this.penerimaan_barang.id_vendor,
              no_plat: this.penerimaan_barang.no_plat,
              nama_driver: this.penerimaan_barang.nama_driver,
            }
          })
          .then(function(res){
            toastr.success('BAP Berhasil dibuat.')
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              if(data.response_type == 'warning'){
                toastr.warning(data.message);
              }else{
                toastr.error(data.message);
              }
              form_.errors = data.errors;
            }else{
              toastr.error(err);
            }
          })
          .then(function(){
            form_.loading = false;
            // check_kekurangan_part.draw();
            drawing_check_kekurangan_part();
            check_input.checked = [];
          });
        },
        proses_claim: function(){
          if(check_input.checked.length < 1){
            return;
          }

          post = {};
          post.id_penerimaan_barang_header = this.penerimaan_barang.id;
          post.id_penerimaan_barang = check_input.checked;

          this.errors = {};
          this.loading = true;
          axios.post('h3/<?= $isi ?>/proses_claim', Qs.stringify(post))
          .then(function(res){
            toastr.success('Claim AHM Berhasil disubmit.');
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'validation_error'){
              form_.errors = data.errors;
              toastr.error(data.message);
            }else{
              toastr.error(err);
            }
          })
          .then(function(){ 
            form_.loading = false;
            // check_kekurangan_part.draw();
            drawing_check_kekurangan_part();
            check_input.checked = [];
          });
        },
        get_sum_of_qty_reasons: function(reasons){
          return _.chain(reasons)
          .filter(function(data){
            return data.checked == 1;
          })
          .sumBy(function(data){
            return Number(data.qty);
          })
          .value()
        },
        show_lokasi_temporary: function(part){
          if(
            (part.tersimpan == 1 && (part.id_lokasi_rak_temporary == null || part.id_lokasi_rak_temporary == '')) 
            && part.edit == 0
          ){
            return false;
          }
          return (parseInt(part.packing_sheet_quantity) > parseInt(part.kapasitas_tersedia)) && part.id_lokasi_rak != null && part.id_lokasi_rak != '';
        },
        get_jumlah_koli: function(){
          this.loading = true;
          axios.get('h3/h3_md_laporan_penerimaan_barang/get_jumlah_koli', {
            params: {
              surat_jalan_ahm: this.surat_jalan_ahm
            }
          })
          .then(function(res){
            form_.jumlah_koli_surat_jalan_ahm = res.data;
          })
          .catch(function(err){
            toast.error(err);
          })
          .then(function(){ form_.loading = false; })
        },
        // get_info_penyelesaian: function(){
        //   params = {
        //     no_surat_jalan_ekspedisi: this.penerimaan_barang.no_surat_jalan_ekspedisi,
        //   }

        //   axios.get('h3/h3_md_laporan_penerimaan_barang/get_info_penyelesaian', {
        //     params: params
        //   })
        //   .then(function(res){
        //     data = res.data;
        //     form_.total_parts = data.total_parts;
        //     form_.total_parts_tersimpan = data.total_parts_tersimpan;
        //     form_.total_item = data.total_item;
        //     form_.total_item_tersimpan = data.total_item_tersimpan;
        //     form_.total_pcs = data.total_pcs;
        //     form_.total_pcs_tersimpan = data.total_pcs_tersimpan;
        //   })
        //   .catch(function(err){
        //     toastr.error(err);
        //   });
        // },
        set_page: function(page){
          this.page = page;
          this.get_parts();
          this.check_all_qty_scan = 0;
        },
        set_page2: function(page2){
          this.page2 = page2;
          this.get_parts2();
          this.check_all_qty_scan2 = 0;
          
        },
        reset_info_supir_ekspedisi: function(){
          this.penerimaan_barang.produk = '';
          this.penerimaan_barang.no_plat = '';
          this.penerimaan_barang.nama_driver = '';
          this.penerimaan_barang.harga_ongkos_angkut_part = '';
          this.penerimaan_barang.type_mobil = '';
        },
        error_exist: function(key){
          return _.get(this.errors, key) != null;
        },
        get_error: function(key){
          return _.get(this.errors, key)
        },
        get_info_nomor_karton_dari_scanner: function(nomor_karton_dari_scanner){
          axios.get('h3/<?= $isi ?>/get_info_nomor_karton_dari_scanner', {
            params: {
              nomor_karton_dari_scanner: nomor_karton_dari_scanner,
            }
          })
          .then(function(res){
            data = res.data;

            form_.list_nomor_karton = [];
            form_.list_nomor_karton.push(data);
            // form_.list_nomor_karton2 = [];
            // form_.list_nomor_karton2.push(data);
            $('#filter_surat_jalan_ahm').val(data.surat_jalan_ahm);
            $('#filter_packing_sheet_number').val(data.packing_sheet_number);
            $('#filter_nomor_karton').val(data.nomor_karton);
            h3_md_nomor_karton_penerimaan_barang_datatable.draw();
          })
          .catch(function(err){
            data = err.response.data;
            if(data.error_type == 'nomor_karton_not_found'){
              toastr.error(data.message);
            }else{
              toastr.error(data.message);
            }
          });
        }
      },
      watch: {
        check_all_qty_scan: function(value){
          parts = _.chain(this.parts)
          .filter(function(part){
            return part.tersimpan == 0;
          })
          .value();

          for (let index = 0; index < this.parts.length; index++) {
            const part = this.parts[index];
            if(part.tersimpan == 0){
              if(value == 1){
                this.parts[index].qty_diterima = this.parts[index].packing_sheet_quantity;
              }else{
                this.parts[index].qty_diterima = 0;
              }
            }
          }
        },
        check_all_qty_scan2: function(value){
          parts = _.chain(this.parts)
          .filter(function(part){
            return part.tersimpan == 0;
          })
          .value();

          for (let index = 0; index < this.parts.length; index++) {
            const part = this.parts[index];
            if(part.tersimpan == 0){
              if(value == 1){
                this.parts[index].qty_diterima = this.parts[index].packing_sheet_quantity;
              }else{
                this.parts[index].qty_diterima = 0;
              }
            }
          }
        },
        'penerimaan_barang.id_vendor': function(){
          this.reset_info_supir_ekspedisi();
          // h3_md_ekspedisi_item_penerimaan_barang_datatable.draw(false);
          drawing_ekspedisi_item(false);
        },
        'penerimaan_barang.no_plat': function(){
          this.harga_ekspedisi();
        },
        list_nomor_karton: function(){
          this.get_parts(true);
        },
        list_nomor_karton_ev: function(){
          this.get_parts2(true);
        },
        // list_nomor_karton2: function(){
        //   this.get_parts(true);
        // },
        index_part: function(){
          h3_md_lokasi_rak_penerimaan_barang_datatable.draw(false);
          h3_md_lokasi_rak_temporary_penerimaan_barang_datatable.draw(false);
        }
      }, 
      computed: {
        jumlah_koli: function(){
          return _.chain(this.list_jumlah_koli)
          .sumBy(function(data){
            return parseInt(data.koli);
          })
          .value();
        },
        part_berselisih: function(){
          get_reason_ahm_qty_fn = this.get_reason_ahm_qty;
          get_reason_ekspedisi_qty_fn = this.get_reason_ekspedisi_qty;

          return _.chain(this.parts)
          .filter(function(p){
            selisih = Math.abs(p.packing_sheet_quantity - p.qty_diterima);
            qty_reasons = form_.get_sum_of_qty_reasons(p.reasons);

            qty_reason_ahm = get_reason_ahm_qty_fn(p);
            qty_reason_ekspedisi = get_reason_ekspedisi_qty_fn(p);

            if(( (qty_reason_ahm > 0 && p.proses_claim_ahm == 0) || (qty_reason_ekspedisi > 0 && p.proses_claim_ekspedisi == 0) )){
              return true;
            }

            if((qty_reason_ahm > 0 && p.proses_claim_ahm == 0)){
              return true;
            }else if((qty_reason_ekspedisi > 0 && p.proses_claim_ekspedisi == 0)){
              return true;
            }

            return selisih > 0 && qty_reasons != selisih;
          })
          .value();
        },
        part_claim_belum_proses: function(){
          result = false;
          for (part of this.parts) {
            console.log(parseInt(form_.get_reason_ahm_qty(part)));
            console.log(parseInt(form_.get_reason_ekspedisi_qty(part)));
            if(parseInt(form_.get_reason_ahm_qty(part)) > 0 && part.proses_claim_ahm == 0){
              result = true;
            }

            if(parseInt(form_.get_reason_ekspedisi_qty(part)) > 0 && part.proses_claim_ekspedisi == 0){
              result = true;
            }
          }
          return result;
        },
        terdapat_part_dengan_reasons_tidak_lengkap: function(){
          parts = _.chain(this.parts)
          .filter(function(part){
            return part.id_lokasi_rak != null;
          })
          .value();

          for (data of parts) {
            selisih = form_.hitung_qty_plus_minus(data);
            qty_reason = form_.get_sum_of_qty_reasons(data.reasons);

            if(
              (selisih != 0) &&
              (qty_reason != selisih)
            ){
              return true;
            }
          }
          return false;
        },
        parts_dengan_reasons_tidak_lengkap: function(){
          return _.chain(this.parts)
          .filter(function(data){
            return data.tersimpan == 0 && data.id_lokasi_rak != null && data.id_lokasi_rak != '';
          })
          .filter(function(data){
            qty_selisih_tidak_sama_dengan_nol = form_.hitung_qty_plus_minus(data) != 0;
            qty_reasons_tidak_sama_dengan_qty_selisih = form_.get_sum_of_qty_reasons(data.reasons) != form_.hitung_qty_plus_minus(data);
            return qty_selisih_tidak_sama_dengan_nol && qty_reasons_tidak_sama_dengan_qty_selisih;
          })
          .value();
        },
        part_tanpa_lokasi_rak: function(){
          return _.filter(this.parts, function(p){
            return p.id_lokasi_rak == null || p.id_lokasi_rak == '';
          });
        },
        part_harus_mengisi_lokasi_temporary: function(){
          show_lokasi_temporary_fn = this.show_lokasi_temporary;
          return _.chain(this.parts)
          .filter(function(part){
            return show_lokasi_temporary_fn(part) && (part.id_lokasi_rak_temporary == null || part.id_lokasi_rak_temporary == '');
          })
          .value();
        },
        parts_tersimpan: function(){
          return _.chain(this.parts)
          .filter(function(data){
            return data.tersimpan == 1;
          })
          .value();
        },
        parts_belum_tersimpan: function(){
          return _.chain(this.parts)
          .filter(function(data){
            return data.tersimpan == 0;
          })
          .value();
        },
        total_harga: function(){
          return this.penerimaan_barang.harga_ongkos_angkut_part * (this.penerimaan_barang.berat_truk/this.penerimaan_barang.per_satuan_ongkos_angkut_part);
        },
        list_no_karton: function(){
          return _.chain(this.parts)
          .uniqBy('nomor_karton')
          .map(function(part){
            return _.pick(part, ['nomor_karton', 'packing_sheet_number'])
          }).value();
        },
        jumlah_page: function(){
          if(this.total_data == 0){
            return 0;
          }
          return Math.ceil(this.total_data / this.perPage);
        },
        jumlah_page2: function(){
          if(this.total_data2 == 0){
            return 0;
          }
          return Math.ceil(this.total_data2 / this.perPage2);
        },
        no_penerimaan_barang_empty: function(){
          return this.penerimaan_barang.no_penerimaan_barang == null || this.penerimaan_barang.no_penerimaan_barang == '';
        },
        lokasi_tidak_mencukupi: function(){
          return _.chain(this.parts)
          .filter(function(part){
            return part.tersimpan == 0 && (part.id_lokasi_rak != null && part.id_lokasi_rak != '');
          })
          .groupBy(function(part){
            return part.id_lokasi_rak;
          })
          .map(function(grouped, id_lokasi_rak){
            mapped = {};

            mapped.id_lokasi_rak = id_lokasi_rak;
            mapped.kode_lokasi_rak = grouped[0].kode_lokasi_rak;
            mapped.kapasitas_tersedia = parseInt(grouped[0].kapasitas_tersedia);
            mapped.kapasitas_diperlukan = _.sumBy(grouped, function(row_grouped){
              return parseInt(row_grouped.packing_sheet_quantity);
            });
            return mapped;
          })
          .filter(function(row){
            return row.kapasitas_tersedia < row.kapasitas_diperlukan;
          })
          .value();
        }
      }
  });
</script>
<?php $this->load->view('modal/h3_md_view_stock_lokasi_temporary_penerimaan_barang') ?>
    <?php
  } elseif ($set=="index") {
      ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h3/<?= $isi ?>/add">
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a>
          <div class="btn-group">
            <button type="button" class="btn btn-success">Download</button>
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a target='_blank' id='download_excel_by_packing_sheet' href="h3/<?= $isi ?>/download_excel_by_packing_sheet">Packing Sheet</a></li>
                <li><a target='_blank' id='download_excel_by_packing_sheet_with_amount' href="h3/<?= $isi ?>/download_excel_by_packing_sheet_with_amount">Packing Sheet & Amount</a></li>
            </ul>
          </div>
        </h3>
      </div><!-- /.box-header -->
      <script>
        function download_excel_by_packing_sheet(){
          query_string = new URLSearchParams({
            periode_awal : $('#periode_tanggal_penerimaan_filter_start').val(),
            periode_akhir : $('#periode_tanggal_penerimaan_filter_end').val(),
          }).toString();

          $('#download_excel_by_packing_sheet').attr('href', 'h3/<?= $isi ?>/download_excel_by_packing_sheet?' + query_string);
          $('#download_excel_by_packing_sheet_with_amount').attr('href', 'h3/<?= $isi ?>/download_excel_by_packing_sheet_with_amount?' + query_string);
        }
      </script>
      <div class="box-body">
        <div class="container-fluid no-padding">
          <div class="row">
            <div class="col-sm-3">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label">Periode Tanggal Penerimaan</label>
                    <input id='periode_tanggal_penerimaan_filter' type="text" class="form-control" readonly>
                    <input id='periode_tanggal_penerimaan_filter_start' type="hidden" disabled>
                    <input id='periode_tanggal_penerimaan_filter_end' type="hidden" disabled>
                  </div>                
                  <script>
                    $('#periode_tanggal_penerimaan_filter').daterangepicker({
                      opens: 'left',
                      autoUpdateInput: false,
                      locale: {
                        format: 'DD/MM/YYYY'
                      }
                    }).on('apply.daterangepicker', function(ev, picker) {
                      $('#periode_tanggal_penerimaan_filter_start').val(picker.startDate.format('YYYY-MM-DD'));
                      $('#periode_tanggal_penerimaan_filter_end').val(picker.endDate.format('YYYY-MM-DD'));
                      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                      penerimaan_barang.draw();
                      download_excel_by_packing_sheet();
                    }).on('cancel.daterangepicker', function(ev, picker) {
                      $(this).val('');
                      $('#periode_tanggal_penerimaan_filter_start').val('');
                      $('#periode_tanggal_penerimaan_filter_end').val('');
                      penerimaan_barang.draw();
                      download_excel_by_packing_sheet();
                    });
                  </script>
                </div>
              </div>
            </div>
          </div>
        </div>
        <table id="penerimaan_barang" class="table table-bordered table-hover table-condensed">
          <thead>
            <tr>
              <th>No.</th>
              <th>Tanggal Penerimaan</th>
              <th>No. Penerimaan</th>
              <th>Tgl Surat Jalan Ekspedisi</th>
              <th>No. Surat Jalan Ekspedisi</th>
              <th>Ekspedisi</th>
              <th>No. Polisi Ekspedisi</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <script>
          $(document).ready(function() {
            penerimaan_barang = $('#penerimaan_barang').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h3/penerimaan_barang') ?>",
                  dataSrc: "data",
                  type: "POST",
                  data: function(d){
                    d.periode_tanggal_penerimaan_filter_start = $('#periode_tanggal_penerimaan_filter_start').val();
                    d.periode_tanggal_penerimaan_filter_end = $('#periode_tanggal_penerimaan_filter_end').val();
                  }
                },
                columns: [
                    { data: 'index', orderable: false, width: '3%' },
                    { data: 'tanggal_penerimaan' },
                    { data: 'no_penerimaan_barang' },
                    { data: 'tgl_surat_jalan_ekspedisi' },
                    { data: 'no_surat_jalan_ekspedisi' },
                    { data: 'ekspedisi', name: 'e.nama_ekspedisi' },
                    { data: 'no_plat' },
                    { data: 'status' },
                    { data: 'action', width: '15%', orderable: false, className: 'text-center' },
                ],
            });
          });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php } ?>
  </section>
</div>