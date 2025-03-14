<?php if (in_array('riwayatServisCustomerH23', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalRiwayatServisCustomerH23">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Riwayat Servis</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_riwayat_servis" style="width: 100%">
            <thead>
              <tr>
                <th>Tanggal Servis</th>
                <th>Jam Servis</th>
                <th>Kode AHASS</th>
                <th>AHASS</th>
                <th>ID Job</th>
                <th>Aksi</>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php
  $data['data'] = ['detailWO'];
  $this->load->view('dealer/h2_api', $data); ?>
  <script>
    cek_rs = 0;

    function cekRiwayatServis() {
      cek_rs++;
      if (cek_rs == 1) {
        $('#tbl_riwayat_servis').DataTable({
          processing: true,
          serverSide: true,
          "language": {
            "infoFiltered": ""
          },
          order: [],
          ajax: {
            url: "<?= base_url('api/h2/riwayatServisCustomerH23') ?>",
            dataSrc: "data",
            data: function(d) {
              d.id_customer = $('#id_customer').val();
              return d;
            },
            type: "POST"
          },
          "columnDefs": [
            // { "targets":[4],"orderable":false},
            {
              "targets": [5],
              "className": 'text-center'
            },
            // { "targets":[4], "searchable": false } 
          ]
        });
      }
      let id_customer = $('#id_customer').val();
      if (id_customer == undefined || id_customer == '') {
        alert('Silahkan pilih customer terlebih dahulu !');
        return false
      }
      $('#tbl_riwayat_servis').DataTable().ajax.reload();
      $('#modalRiwayatServisCustomerH23').modal('show');
    }
  </script>
<?php } ?>

<?php if (in_array('detailWO', $data)) { ?>
  <script src="assets/moment/moment.min.js"></script>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalDetailWO">
    <div class="modal-dialog" style="width:95%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><b>Detail Work Order</b></h4>
        </div>
        <div class="modal-body form-horizontal">
          <div class="box-body" id="showDetailWO">
            <iframe id="iframe_detail_wo" src="" width="100%" style="height: 800px; border: 0;"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    function detailWO(wo) {
      $('#modalDetailWO').modal('show');
      url = '<?= base_url('iframe/dealer/h2/work_order_dealer/detail?id=') ?>' + wo.id_work_order;
      $('#iframe_detail_wo').attr('src', url);
    }
    $('#modalDetailWO')
      .on('hide', function() {
        console.log('hide');
      })
      .on('hidden', function() {
        console.log('hidden');
      })
      .on('show', function() {
        console.log('show');
      })
      .on('shown', function() {
        console.log('shown')
      })
      .on('hide.bs.modal', function() {
        console.log('dddd')
      });
  </script>
<?php } ?>

<?php if (in_array('selectTipeKendaraan', $data)) { ?>
  selectTipeKendaraan: function(search, loading) {
  $.ajax({
  beforeSend: function() {
  loading(true);
  },
  url: '<?= base_url('api/h2/selectTipeKendaraan?q=') ?>' + search,
  type: "POST",
  // data: values,
  cache: false,
  dataType: 'JSON',
  success: function(response) {
  loading(false);
  form_.opt_tipe_kendaraan = response.items;
  },
  error: function() {
  loading(false);
  alert("Something Went Wrong !");
  },
  statusCode: {
  500: function() {
  loading(false);
  alert('Fail Error 500 !');
  }
  }
  });
  },
<?php } ?>


<?php if (in_array('selectWarnaItem', $data)) { ?>
  selectWarnaItem: function(search, loading) {
  let id_tipe_kendaraan = $('#id_tipe_kendaraan').val();
  $.ajax({
  beforeSend: function() {
  loading(true);
  },
  url: '<?= base_url('api/h2/selectWarnaItem?q=') ?>' + search +'&tk='+id_tipe_kendaraan,
  type: "POST",
  // data: values,
  cache: false,
  dataType: 'JSON',
  success: function(response) {
  loading(false);
  form_.opt_warna = response.items;
  },
  error: function() {
  loading(false);
  alert("Something Went Wrong !");
  },
  statusCode: {
  500: function() {
  loading(false);
  alert('Fail Error 500 !');
  }
  }
  });
  },
<?php } ?>

<?php if (in_array('partWithAllStock', $data)) { ?>
  <script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>

  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalPartWithAllStock" style="overflow-y:auto;">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Parts</h4>
        </div>
        <div class="modal-body" id="cari_part">
          <h4>Pencarian Data Part</h4>
          <div class="row">
            <div class="col-sm-3">
              <input id="tipe_ahm_cari_part" type="text" class="form-control" readonly data-toggle='modal' data-target='#tipe_kendaraan' placeholder="Tipe Kendaraan">
            </div>
            <div class="col-sm-3">
              <input type="text" autocomplete="off" class="form-control" id="sr_id_part" placeholder="ID Part">
            </div>
            <div class="col-sm-3">
              <input type="text" autocomplete="off" class="form-control" id="sr_nama_part" placeholder="Deskripsi Part">
            </div>
            <div class="col-sm-1" v-if="searchs=='lain'">
              <input type="text" autocomplete="off" class="form-control" id="sr_qty_part" placeholder="Qty Part">
            </div>
            <div class="col-sm-2">
              <button type="button" onclick="searchSalesParts()" class="btn btn-primary btn-flat"><i class="fa fa-search"></i> Cari</button>
              <button type="button" onclick="clearSearchSalesParts()" class="btn btn-warning btn-flat"><i class="fa fa-refresh"></i> Reset</button>
            </div>
          </div>
          </br>
          </hr>
          <div class="row">
            <div class="col-md-12">
              <!-- Custom Tabs -->
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab_reguler" @click.prevent="cekSearch('reg')" data-toggle="tab">Reguler</a></li>
                  <li><a href="#tab_dealer_lain" data-toggle="tab" @click.prevent="cekSearch('lain')">Dealer Lain</a></li>
                  <li><a href="#tab_hlo" @click.prevent="cekSearch('md')" data-toggle="tab">HLO</a></li>
                </ul>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_reguler">
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_reguler_part_sales" style="width: 100%">
                        <thead>
                          <tr>
                            <th>ID Part</th>
                            <th>Deskripsi Part</th>
                            <th>HET</th>
                            <th>Gudang</th>
                            <th>Rak</th>
                            <th>Stok</th>
                            <th>Status Part</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane" id="tab_dealer_lain">
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_dealer_lain_part_sales" style="width: 100%">
                        <thead>
                          <tr>
                            <th>ID Part</th>
                            <th>Deskripsi Part</th>
                            <th>HET</th>
                            <th>Nama Dealer</th>
                            <th>Status Ketersediaan</th>
                            <th>Status Part</th>
                            <th width="15%">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_hlo">
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_hlo_part_sales" style="width: 100%">
                        <thead>
                          <tr>
                            <th>ID Part</th>
                            <th>Deskripsi Part</th>
                            <th>HET</th>
                            <th>Status Part</th>
                            <th width="15%">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- /.tab-content -->
              </div>
              <!-- nav-tabs-custom -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <?php $this->load->view('modal/tipe_kendaraan'); ?>

  <script>
    function clearSearchSalesParts() {
      $('#sr_id_part').val('');
      $('#sr_nama_part').val('');
      $('#sr_qty_part').val('');
      if (form_.dtl.id_type != 'ASS1') {
        cari_part.id_tipe_kendaraan = '';
        $('#tipe_ahm_cari_part').val('');
      }
    }

    function pilih_tipe_kendaraan(data) {
      console.log(data);
      $('#tipe_ahm_cari_part').val(data.tipe_ahm)
      cari_part.id_tipe_kendaraan = data.id_tipe_kendaraan
    }
    var cp_ = new Vue({
      el: '#cari_part',
      data: {
        searchs: '',
        id_tipe_kendaraan: '',
      },
      methods: {
        cekSearch: function(c) {
          if (c == 'reg') {
            $('#tbl_reguler_part_sales').DataTable().ajax.reload();
          } else if (c == 'lain') {
            alert('Tentukan Qty Part terlebih dahulu !');
            $('#tbl_dealer_lain_part_sales').DataTable().ajax.reload();
          } else if (c == 'md') {
            $('#tbl_hlo_part_sales').DataTable().ajax.reload();
          }
          this.searchs = c;
        }
      }
    })

    function searchSalesParts() {
      id_part = $('#sr_id_part').val();
      nama_part = $('#sr_nama_part').val();
      if (id_part === '' && nama_part === '') {
        alert('Tentukan ID Part atau nama part terlebih dahulu ')
        return false
      }
      if (id_part.length > 0 && id_part.length < 5) {
        alert('Masukkan minimal 5 digit ID Part')
        return false
      }
      if (nama_part.length > 0 && nama_part.length < 3) {
        alert('Masukkan minimal 3 digit Nama Part')
        return false
      }
      if (cp_.searchs == 'lain') {
        let qty_part = $('#sr_qty_part').val();
        if (qty_part == '') {
          alert('Tentukan Qty Part terlebih dahulu !');
          return false;
        }
        $('#tbl_dealer_lain_part_sales').DataTable().ajax.reload();
      } else if (cp_.searchs == 'reg' || cp_.searchs == undefined || cp_.searchs == '') {
        $('#tbl_reguler_part_sales').DataTable().ajax.reload();
      } else if (cp_.searchs == 'md') {
        $('#tbl_hlo_part_sales').DataTable().ajax.reload();
      }
    }
  </script>
<?php } ?>

<?php if (in_array('partWithDealerStock', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalPartWithDealerStock">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Parts</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_part_dealer_stock" style="width: 100%">
              <thead>
                <tr>
                  <th>ID Part</th>
                  <th>Nama Part</th>
                  <th>Kelompok Vendor</th>
                  <th>Harga</th>
                  <th>Gudang</th>
                  <th>Rak</th>
                  <th>Stok</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      $('#tbl_part_dealer_stock').DataTable({
        processing: true,
        serverSide: true,
        "language": {
          "infoFiltered": ""
        },
        order: [],
        ajax: {
          url: "<?= base_url('api/h2/partWithDealerStock') ?>",
          dataSrc: "data",
          data: function(d) {
            // d.id_part = $('#id_part').val();
            return d;
          },
          type: "POST"
        },
        "columnDefs": [{
            "targets": [0, 1, 2, 3, 4, 5, 6, 7],
            "orderable": false
          },
          {
            "targets": [6],
            "className": 'text-center'
          },
          // { "targets":[4], "searchable": false } 
        ]
      });
    });
  </script>
<?php } ?>

<?php if (in_array('kelurahan', $data)) { ?>

  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalKelurahan">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Kelurahan</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_kelurahan" style="width: 100%">
              <thead>
                <tr>
                  <th>ID Kelurahan</th>
                  <th>Kode Pos</th>
                  <th>Kelurahan</th>
                  <th>Kecamatan</th>
                  <th>Kota/Kabupaten</th>
                  <th>Provinsi</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
          <script>
            cek_kelurahan = 0;

            function showModalKelurahan(kel = 'customer') {
              cek_kelurahan++;
              if (cek_kelurahan == 1) {
                $('#tbl_kelurahan').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": ""
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/h2/kelurahan') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      // d.kode_item     = $('#kode_item').val();
                      return d;
                    },
                    type: "POST"
                  },
                  "columnDefs": [{
                      "targets": [6],
                      "orderable": false
                    },
                    {
                      "targets": [6],
                      "className": 'text-center'
                    },
                    // { "targets":[4], "searchable": false } 
                  ]
                });
              }
              kelurahan_untuk = kel;
              $('#modalKelurahan').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('item', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalItem">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Item</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_item" style="width: 100%">
              <thead>
                <tr>
                  <th>5 Digit Nomor Mesin</th>
                  <th>ID Item</th>
                  <!--<th>ID Tipe Kendaraan</th>-->
                  <th>Tipe Kendaraan</th>
                  <!--<th>ID Warna</th>-->
                  <th>Warna</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
          <script>
            $(document).ready(function() {
              $('#tbl_item').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/item') ?>",
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
                    "targets": [4],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ]
              });
            });

            function showModalItem() {
              $('#modalItem').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('tipe_kendaraan', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalTipeKendaraan">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Tipe Kendaraan</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">

            <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_tipe_kendaraan" style="width: 100%">
              <thead>
                <tr>
                  <th>ID Tipe Kendaraan</th>
                  <th>Deskripsi Tipe Kendaraan</th>
                  <th>5 Digit No. Mesin</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
          <script>
            cek_tk = 0;

            function showModalTipeKendaraan() {
              cek_tk++;
              if (cek_tk == 1) {
                $('#tbl_tipe_kendaraan').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": ""
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/h2/tipe_kendaraan') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      <?php if (in_array('not_in_tipe_vs_5nosin', $data)) { ?>
                        d.not_in_tipe_vs_5nosin = true;
                      <?php } ?>
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
              }
              $('#modalTipeKendaraan').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('allCustomer', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalAllCustomer">
    <div class="modal-dialog" style="width:90%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Customer</h4>
        </div>
        <div class="modal-body">
          <h5>Pencarian Data</h5>
          <div class="row">
            <div class="col-sm-3">
              <input type="text" autocomplete="off" class="form-control" id="search_nama_customer" placeholder="Nama Customer">
            </div>
            <div class="col-sm-2">
              <input type="text" autocomplete="off" class="form-control" id="search_no_hp" placeholder="No. HP / No. Telp">
            </div>
            <div class="col-sm-2">
              <input type="text" autocomplete="off" class="form-control" id="search_no_mesin" placeholder="No. Mesin">
            </div>
            <div class="col-sm-2">
              <input type="text" autocomplete="off" class="form-control" id="search_no_polisi" placeholder="No. Polisi">
            </div>
            <div class="col-sm-2">
              <button type="button" onclick="searchAllCustomerAgain()" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
            </div>
          </div>
          </br>
          </hr>
          <div class="row">
            <div class="col-md-12">
              <!-- Custom Tabs -->
              <div class="nav-tabs-custom">
                <?php if (in_array('customer_h23', $data)) { ?>

                <?php } else { ?>
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" onclick="setSearch('h23')">Customer H23</a></li>
                    <li><a href="#tab_2" data-toggle="tab" onclick="setSearch('h1')">Customer H1</a></li>
                  </ul>
                <?php } ?>
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_customer_h23" style="width: 100%">
                        <thead>
                          <tr>
                            <th>ID Customer H23</th>
                            <th>Nama Customer</th>
                            <th>No. HP / No. Telp</th>
                            <th>Tipe Kendaraan</th>
                            <th>Warna</th>
                            <th>No. Mesin</th>
                            <th>No. Rangka</th>
                            <th>No. Polisi</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_2">
                    <div class="table-responsive">
                      <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_customer_h1" style="width: 100%">
                        <thead>
                          <tr>
                            <th>ID Sales Order</th>
                            <th>Nama Customer</th>
                            <th>No. HP / No. Telp</th>
                            <th>Tipe Kendaraan</th>
                            <th>Warna</th>
                            <th>No. Mesin</th>
                            <th>No. Rangka</th>
                            <th>No. Polisi</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- /.tab-content -->
              </div>
              <!-- nav-tabs-custom -->
            </div>
          </div>
          <script>
            $(document).ready(function() {

            });

            var set_search_cust = 'h23';
            var cek_ch23 = 0;
            var cek_ch1 = 0;

            function showModalAllCustomer() {
              cek_ch23++;
              cek_ch1++;
              if (cek_ch23 == 1) {
                $('#tbl_customer_h23').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": ""
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/h2/customerH23') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      d.nama_customer = $('#search_nama_customer').val();
                      d.no_hp = $('#search_no_hp').val();
                      d.no_mesin = $('#search_no_mesin').val();
                      d.no_polisi = $('#search_no_polisi').val();
                      return d;
                    },
                    type: "POST"
                  },
                  "columnDefs": [
                    // { "targets":[4],"orderable":false},
                    {
                      "targets": [8],
                      "className": 'text-center'
                    },
                    // { "targets":[4], "searchable": false } 
                  ],
                  "searching": false,
                  // "lengthChange": false
                });
              }
              if (cek_ch1 == 1) {
                $('#tbl_customer_h1').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": ""
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/h2/customerH1') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      d.nama_customer = $('#search_nama_customer').val();
                      d.no_hp = $('#search_no_hp').val();
                      d.no_mesin = $('#search_no_mesin').val();
                      d.no_polisi = $('#search_no_polisi').val();
                      return d;
                    },
                    type: "POST"
                  },
                  "columnDefs": [
                    // { "targets":[4],"orderable":false},
                    {
                      "targets": [8],
                      "className": 'text-center'
                    },
                    // { "targets":[4], "searchable": false } 
                  ],
                  "searching": false,
                  // "lengthChange": false
                });
              }
              $('#modalAllCustomer').modal('show');
            }

            function setSearch(params) {
              set_search_cust = params;
              console.log(set_search_cust);
            }

            function searchAllCustomerAgain() {
              let no_mesin = $('#search_no_mesin').val();
              let nama_customer = $('#search_nama_customer').val();
              let no_hp = $('#search_no_hp').val();
              let no_polisi = $('#search_no_polisi').val();
              if (no_mesin.length > 0 && no_mesin.length < 5) {
                alert('Masukkan minimal 5 digit No. Mesin');
                return false
              }
              if (nama_customer.length > 0 && nama_customer.length < 5) {
                alert('Masukkan minimal 5 digit Nama Customer');
                return false
              }
              if (no_hp.length > 0 && no_hp.length < 5) {
                alert('Masukkan minimal 5 digit No. HP');
                return false
              }
              if (no_polisi.length > 0 && no_polisi.length < 5) {
                alert('Masukkan minimal 5 digit No. Polisi');
                return false
              }
              if (no_mesin == '' && nama_customer == '' && no_hp == '' && no_polisi == '') {
                alert('Tentukan pencarian data terlebih dahulu');
                return false
              }
              if (set_search_cust == 'h23') {
                $('#tbl_customer_h23').DataTable().ajax.reload();
              } else if (set_search_cust == 'h1') {
                $('#tbl_customer_h1').DataTable().ajax.reload();
              }
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>


<?php if (in_array('customerBooking', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalCustomerBooking">
    <div class="modal-dialog" style="width:90%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Booking Service</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-2">
              <input type="text" autocomplete="off" class="form-control" id="search_id_booking" placeholder="ID Booking">
            </div>
            <div class="col-sm-3">
              <input type="text" autocomplete="off" class="form-control" id="search_nama_customer_booking" placeholder="Nama Customer">
            </div>
            <div class="col-sm-2">
              <input type="text" autocomplete="off" class="form-control" id="search_no_hp_booking" placeholder="No. HP / No. Telp">
            </div>
            <div class="col-sm-2">
              <input type="text" autocomplete="off" class="form-control" id="search_no_mesin_booking" placeholder="No. Mesin">
            </div>
            <div class="col-sm-2">
              <input type="text" autocomplete="off" class="form-control" id="search_no_polisi_booking" placeholder="No. Polisi">
            </div>
            <div class="col-sm-1">
              <button type="button" onclick="searchCustomerBookingAgain()" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
            </div>
          </div>
          </hr>
          </br>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_booking_servis" style="width: 100%">
              <thead>
                <tr>
                  <th>ID Booking</th>
                  <th>ID Customer H23</th>
                  <th>Nama Customer</th>
                  <th>No. HP / No. Telp</th>
                  <th>Tipe Kendaraan</th>
                  <th>Warna</th>
                  <th>No. Mesin</th>
                  <th>No. Rangka</th>
                  <th>No. Polisi</th>
                  <th>Tgl Servis</th>
                  <th>Jam Servis</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
          <script>
            $(document).ready(function() {
              $('#tbl_booking_servis').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                ordering: false,
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/customerBooking') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    d.id_booking = $('#search_id_booking').val();
                    d.nama_customer = $('#search_nama_customer_booking').val();
                    d.no_hp = $('#search_no_hp_booking').val();
                    d.no_mesin = $('#search_no_mesin_booking').val();
                    d.no_polisi = $('#search_no_polisi_booking').val();
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [8],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "searching": false,
                "lengthChange": false
              });
            });

            function showModalCustomerBooking() {
              $('#modalCustomerBooking').modal('show');
            }

            function searchCustomerBookingAgain() {
              $('#tbl_booking_servis').DataTable().ajax.reload();
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('pembawa', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalPembawa">
    <div class="modal-dialog" style="width:75%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Pembawa</h4>
        </div>
        <div class="modal-body">
          <h5>Pencarian Data</h5>
          <div class="row">
            <div class="col-sm-5">
              <input type="text" autocomplete="off" class="form-control" id="sr_nama_pembawa" placeholder="Nama Pembawa">
            </div>
            <div class="col-sm-4">
              <input type="text" autocomplete="off" class="form-control" id="sr_no_hp_pembawa" placeholder="No. HP / No. Telp">
            </div>
            <div class="col-sm-3">
              <button type="button" onclick="searchPembawaAgain()" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
            </div>
          </div>
          <h5>Pembawa baru</h5>
          <div class="row">
            <div class="col-sm-3">
              <button type="button" onclick="pembawaBaru()" class="btn btn-info btn-flat"><i class="fa fa-plus"></i> Pembawa Baru</button>
            </div>
          </div>
          </hr>
          </br>
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_pembawa" style="width: 100%">
              <thead>
                <tr>
                  <th>ID Pembawa</th>
                  <th>Nama Pembawa</th>
                  <th>Jenis Kelamin</th>
                  <th>Hubungan Dengan Pemilik</th>
                  <th>No. HP / No. Telp</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>
          </div>
          <script>
            var cek_pbw = 0;

            function showModalPembawa() {
              cek_pbw++;
              if (cek_pbw == 1) {
                $('#tbl_pembawa').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": ""
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/h2/pembawa') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      d.id_customer = $('#id_customer').val();
                      d.sr_nama_pembawa = $('#sr_nama_pembawa').val();
                      d.sr_no_hp_pembawa = $('#sr_no_hp_pembawa').val();
                      return d;
                    },
                    type: "POST"
                  },
                  "columnDefs": [
                    // { "targets":[4],"orderable":false},
                    {
                      "targets": [5],
                      "className": 'text-center'
                    },
                    // { "targets":[4], "searchable": false } 
                  ],
                  "searching": false,
                  "lengthChange": false
                });
              } else {
                $('#tbl_pembawa').DataTable().ajax.reload();
              }
              $('#modalPembawa').modal('show');
            }

            function searchPembawaAgain() {
              $('#tbl_pembawa').DataTable().ajax.reload();
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('SOReadyNSC', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalSOReadyNSC">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Sales Order</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_so_ready_nsc" style="width: 100%">
              <thead>
                <tr>
                  <th>Nomor Sales Order</th>
                  <th>Tgl. Sales Order</th>
                  <th>Nama Customer</th>
                  <th width="5%">Action</th>
                </tr>
              </thead>
            </table>
          </div>
          <script>
            $(document).ready(function() {
              $('#tbl_so_ready_nsc').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/so_ready_nsc') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    // d.id_customer = $('#id_customer').val();
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [3],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalSOReadyNSC() {
              $('#tbl_so_ready_nsc').DataTable().ajax.reload();
              $('#modalSOReadyNSC').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('WOProses', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalWOProses">
    <div class="modal-dialog" style="width:65%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Work Order</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_wo_proses" style="width: 100%">
              <thead>
                <tr>
                  <th>ID Work Order</th>
                  <th>Tgl. Servis</th>
                  <th>Jam Servis</th>
                  <th>ID Customer</th>
                  <th>Nama Customer</th>
                  <th>Mekanik</th>
                  <th width="5%">Action</th>
                </tr>
              </thead>
            </table>
          </div>
          <script>
            $(document).ready(function() {
              $('#tbl_wo_proses').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/wo_proses') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('wo_nsc', $data)) { ?>
                      d.need_parts = true;
                      d.status_wo = 'closed';
                      d.not_exists_nsc = true;
                    <?php } ?>
                    <?php if (in_array('wo_selesai', $data)) { ?>
                      d.status_wo = 'closed';
                      d.njb_not_null = true;
                    <?php } ?>
                    <?php if (in_array('filter_id_dealer', $data)) { ?>
                      d.id_dealer = $('#id_dealer').val();
                    <?php } ?>
                    <?php if (in_array('wo_c2', $data)) { ?>
                      d.wo_c2 = true;
                    <?php } ?>
                    <?php if (in_array('wo_njb', $data)) { ?>
                      d.status_wo = 'closed';
                      d.njb_null = true;
                    <?php } ?>
                    <?php if (in_array('wo_pekerjaan_luar', $data)) { ?>
                      d.pekerjaan_luar = true;
                    <?php } ?>
                    <?php if (in_array('id_claim_not_in_lkh', $data)) { ?>
                      d.id_claim_not_in_lkh = true;
                    <?php } ?>
                    // d.id_customer = $('#id_customer').val();
                    return d;
                  },
                  type: "POST"
                },
                // "columnDefs": [
                //   // { "targets":[4],"orderable":false},
                //   {
                //     "targets": [6],
                //     "className": 'text-center'
                //   },
                //   // { "targets":[4], "searchable": false } 
                // ],
                "lengthChange": false
              });
            });

            function showModalWOProses() {
              $('#tbl_wo_proses').DataTable().ajax.reload();
              $('#modalWOProses').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
  <?php
  $data['data'] = ['detailWO'];
  $this->load->view('dealer/h2_api', $data); ?>
<?php } ?>

<?php if (in_array('antrian', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalAntrian">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Antrian</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_antrian" style="width: 100%">
              <thead>
                <tr>
                  <th>ID Antrian</th>
                  <th>Tgl. Servis</th>
                  <th>Jam Servis</th>
                  <th>ID Customer</th>
                  <th>Nama Customer</th>
                  <th>No. Polisi</th>
                  <th>No. Mesin</th>
                  <th>jenis Customer</th>
                  <th width="5%">Action</th>
                </tr>
              </thead>
            </table>
          </div>
          <script>
            var cek_atr = 0;

            function showModalAntrian() {
              cek_atr++;
              if (cek_atr == 1) {
                $('#tbl_antrian').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": ""
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/h2/getAntrian') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      <?php if (in_array('new_antrian', $data)) { ?>
                        d.id_sa_form_null = true;
                      <?php } ?>
                      return d;
                    },
                    type: "POST"
                  },
                  "columnDefs": [{
                      "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8],
                      "orderable": false
                    },
                    {
                      "targets": [8],
                      "className": 'text-center'
                    },
                    // { "targets":[4], "searchable": false } 
                  ],
                  "lengthChange": false
                });
              } else {
                $('#tbl_antrian').DataTable().ajax.reload();
              }
              $('#modalAntrian').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('sa_form', $data)) { ?>

  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalSaForm">
    <div class="modal-dialog" style="width:80%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data SA Form</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_sa_form" style="width: 100%">
              <thead>
                <tr>
                  <th>ID SA Form</th>
                  <th>Tgl. Servis</th>
                  <th>Jam Servis</th>
                  <th>ID Customer</th>
                  <th>Nama Customer</th>
                  <th>jenis Customer</th>
                  <th width="5%">Action</th>
                </tr>
              </thead>
            </table>
          </div>
          <script>
            var cek_sa_form = 0;

            function showModalSaForm() {
              cek_sa_form++;
              if (cek_sa_form == 1) {
                $('#tbl_sa_form').DataTable({
                  processing: true,
                  serverSide: true,
                  "language": {
                    "infoFiltered": ""
                  },
                  order: [],
                  ajax: {
                    url: "<?= base_url('api/h2/getSaForm') ?>",
                    dataSrc: "data",
                    data: function(d) {
                      <?php if (in_array('open_sa_form', $data)) { ?>
                        d.status_form = 'open';
                      <?php } ?>
                      return d;
                    },
                    type: "POST"
                  },
                  "columnDefs": [{
                      "targets": [6],
                      "orderable": false
                    },
                    {
                      "targets": [6],
                      "className": 'text-center'
                    },
                    // { "targets":[4], "searchable": false } 
                  ],
                  "lengthChange": false
                });
              } else {
                $('#tbl_sa_form').DataTable().ajax.reload();
              }
              $('#modalSaForm').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modal_demand', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalRecordDemand">
    <div class="modal-dialog" style="width:30%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Record Parts Demand</h4>
        </div>
        <form id="formRecordPart">
          <div class="modal-body">
            <div class="form-group">
              <label>ID Part</label>
              <input type="text" name="rec_id_part" class="form-control" id="rec_id_part" readonly>
            </div>
            <div class="form-group">
              <label>Nama Part</label>
              <input type="text" class="form-control" id="rec_nama_part" readonly>
            </div>
            <div class="form-group">
              <label>Qty</label>
              <input type="text" class="form-control" id="rec_qty" required>
            </div>
            <div class="form-group">
              <label>Alasan</label>
              <textarea required id="alasan" name="" cols="30" rows="4" class="form-control"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <div class="col-sm-12" align="center">
              <button type="button" onclick="simpanDemand()" v-if="upd_record==''" class="btn btn-flat btn-info">Simpan</button>
              <button type="button" onclick="updateDemand()" v-if="upd_record==1" class="btn btn-flat btn-warning">Update</button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
  <script>
    var fr_ = new Vue({
      el: '#formRecordPart',
      data: {
        upd_record: '',
      },
      methods: {}
    })
  </script>
<?php } ?>


<?php if (in_array('modalRequestDocument', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalRequestDocument">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Request Document</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_request_document" style="width: 100%">
            <thead>
              <tr>
                <th>ID Booking</th>
                <th>Tgl Request Document</th>
                <th>ID Customer</th>
                <th>Nama Customer</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_request_document').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/modalRequestDocument') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('not_exists_on_po', $data)) { ?>
                      d.not_exists_on_po = 1;
                    <?php } ?>
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [4],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalRequestDocument() {
              $('#tbl_request_document').DataTable().ajax.reload();
              $('#modalRequestDocument').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalJasa', $data)) { ?>

  <div class="modal fade modalJasa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Jasa / Pekerjaan</h4>
        </div>
        <div class="modal-body">
          <h5>Pencarian Data</h5>
          <div class="row">
            <div class="col-sm-4">
              <select class="form-control" v-model="dtl.kategori" onchange="setTypePekerjaan()" id="search_kategori_pekerjaan">
                <option value="">-Kategori Pekerjaan-</option>
                <option value="penggantian">Penggantian</option>
                <!--<option value="perbaikan">Perbaikan</option>-->
                <option value="perawatan">Perawatan</option>
              </select>
            </div>
            <div class="col-sm-4">
              <select class="form-control" id="search_type_pekerjaan" onchange="cekKPB()">
                <option value="">-Tipe Pekerjaan-</option>
              </select>
              <script>
                function setTypePekerjaan() {
                  $('#search_type_pekerjaan').html('');
                  values = {
                    kategori: $('#search_kategori_pekerjaan').val()
                  }
                  $.ajax({
                    beforeSend: function() {},
                    url: '<?= base_url('api/h2/getTipePekerjaan') ?>',
                    type: "POST",
                    data: values,
                    cache: false,
                    dataType: 'JSON',
                    success: function(response) {
                      $('#search_type_pekerjaan').append($('<option>', {
                        value: '',
                        text: '-Tipe Pekerjaan-'
                      }));
                      if (response.status == 'sukses') {
                        for (dtl of response.data) {
                          $('#search_type_pekerjaan').append($('<option>', {
                            value: dtl.id_type,
                            text: dtl.desk_type
                          }));
                        }
                      } else {
                        alert(response.pesan);
                      }
                    },
                    error: function() {
                      alert("Something Went Wrong !");
                    }
                  });
                }
              </script>
            </div>
            <!-- <div class="col-sm-4">-->
            <!--  <select class="form-control" v-model="dtl.kategori" onchange="setTypePekerjaan()" id="search_kategori_pekerjaan">-->
            <!--    <option value="">-Kategori Pekerjaan-</option>-->
            <!--    <option value="penggantian">Penggantian</option>-->
            <!--    <option value="perbaikan">Perbaikan</option>-->
            <!--    <option value="perawatan">Perawatan</option>-->
            <!--  </select>-->
            <!--</div>-->
            <div class="col-sm-2">
              <button type="button" id="btnSearchJasa" onclick="searchJasaAgain()" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
            </div>
          </div>
          </br>
          </hr>
          <div class="table-responsive">

            <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_jasa" style="width: 100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Deskripsi</th>
                  <th>Kategori</th>
                  <th>Tipe</th>
                  <th>Estimasi Biaya</th>
                  <th>Estimasi Waktu</th>
                  <!-- <th>Promo</th>
                <th>Nilai Promo</th> -->
                  <!-- <th>Biaya Setelah Promo</th> -->
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <script>
            $(document).ready(function() {
              $('#tbl_jasa').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/fetch_jasa') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    // console.log(form_);
                    if (form_.customer != undefined) {
                      d.tipe_motor = form_.customer.kode_ptm;
                      d.id_tipe_kendaraan = form_.customer.id_tipe_kendaraan;
                    }
                    d.kategori = $('#search_kategori_pekerjaan').val();
                    d.job_type = $('#search_type_pekerjaan').val();
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [6],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ]
              });
            });

            function searchJasaAgain() {
              let id_type = $('#search_type_pekerjaan').val();
              // console.log(id_type)
              if (id_type == 'ASS1' || id_type == 'ASS2' || id_type == 'ASS3' || id_type == 'ASS4') {
                cekKPB()
              }
              $('#tbl_jasa').DataTable().ajax.reload();
            }

            function cekKPB() {
              let id_type = $('#search_type_pekerjaan').val();
              // console.log(id_type)
              if (id_type == 'ASS1' || id_type == 'ASS2' || id_type == 'ASS3' || id_type == 'ASS4') {
                let values = {
                  kpb_ke: id_type,
                  id_tipe_kendaraan: form_.customer.id_tipe_kendaraan,
                  no_mesin: form_.customer.no_mesin,
                  tgl_pembelian: form_.customer.tgl_pembelian,
                  km_terakhir: $('#km_terakhir').val(),
                }
                $.ajax({
                  beforeSend: function() {
                    $('#btnSearchJasa').html('<i class="fa fa-spinner fa-spin"></i>');
                    $('#btnSearchJasa').attr('disabled', true);
                  },
                  url: '<?= base_url('dealer/sa_form/cekKPB') ?>',
                  type: "POST",
                  data: values,
                  cache: false,
                  dataType: 'JSON',
                  success: function(resp) {
                    $('#btnSearchJasa').html('<i class="fa fa-search"></i> Pilih Pekerjaan');
                    if (resp.status == 'kosong' || resp.status == 'tgl_lewat' || resp.status == 'km_lewat') {
                      alert(resp.msg);
                      $("#search_type_pekerjaan").val('').change();
                      form_.dtl.id_type = '';
                    } else if (resp.status == 'error') {
                      alert(resp.pesan);
                      return false;
                    } else {
                      $('#btnSearchJasa').attr('disabled', false);
                    }
                  },
                  error: function() {
                    alert("failure");
                    // $('#btnRiwayatServis').attr('disabled',false);

                  },
                });
              }
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
<?php if (in_array('modalPromoServis', $data)) { ?>

  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalPromoServis">
    <div class="modal-dialog" style="width: 60%">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Promo Servis</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_promo_servis" style="width: 100%">
            <thead>
              <tr>
                <th>ID Promo</th>
                <th>Nama Promo</th>
                <th>Jasa/Pekerjaan</th>
                <th>Tipe Diskon</th>
                <th>Diskon</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_promo_servis').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/modalPromoServis') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    d.id_jasa = form_.dtl.jasa;
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [5],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ]
              });
            });

            function showModalPromoServis() {
              $('#tbl_promo_servis').DataTable().ajax.reload();
              $('#modalPromoServis').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalVendorPekerjaanLuar', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalVendorPekerjaanLuar">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Vendor Pekerjaan Luar</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_vendor_pekerjaan_luar" style="width: 100%">
            <thead>
              <tr>
                <th>ID Vendor</th>
                <th>Nama Vendor</th>
                <th>No. HP</th>
                <th>Alamat</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_vendor_pekerjaan_luar').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/getVendorPekerjaanLuar') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('filter_vendor_by_jasa_wo', $data)) { ?>
                      d.id_work_order = $('#id_work_order').val();
                    <?php } ?>
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [4],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalVendorPekerjaanLuar() {
              $('#tbl_vendor_pekerjaan_luar').DataTable().ajax.reload();
              $('#modalVendorPekerjaanLuar').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalRekDealer', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalRekDealer">
    <div class="modal-dialog" style="width:60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Rekening Dealer</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_rek_dealer" style="width: 100%">
            <thead>
              <tr>
                <th>No. Rekening</th>
                <th>Atas Nama</th>
                <th>Jenis Rekening</th>
                <th>Bank</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_rek_dealer').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/getRekDealer') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [4],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalRekDealer() {
              $('#tbl_rek_dealer').DataTable().ajax.reload();
              $('#modalRekDealer').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>


<?php if (in_array('modalJasaWO', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalJasaWO">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Pekerjaan Work Order</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_jasa_wo" style="width: 100%">
            <thead>
              <tr>
                <th>ID Pekerjaan</th>
                <th>Deskripsi</th>
                <th>Tipe</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_jasa_wo').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/getJasaWO') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    d.id_work_order = $('#id_work_order').val();
                    <?php if (in_array('jasa_pekerjaan_luar', $data)) { ?>
                      d.pekerjaan_luar = 1;
                    <?php } ?>
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [5],
                    "className": 'text-center'
                  },
                  {
                    "targets": [4],
                    "className": 'text-right'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModaljasaWO() {
              $('#tbl_jasa_wo').DataTable().ajax.reload();
              $('#modalJasaWO').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
<?php if (in_array('modalAllParts', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalAllParts">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Parts</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_all_parts" style="width: 100%">
            <thead>
              <tr>
                <th>ID Parts</th>
                <th>Deskripsi Part</th>
                <th>HET</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_all_parts').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/getAllParts') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [3],
                    "className": 'text-center'
                  },
                  {
                    "targets": [2],
                    "className": 'text-right'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalAllParts() {
              $('#tbl_all_parts').DataTable().ajax.reload();
              $('#modalAllParts').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
<?php if (in_array('modalCOADealer', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalCOADealer">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">COA</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_coa_dealer" style="width: 100%">
            <thead>
              <tr>
                <th>Kode COA</th>
                <th>COA</th>
                <th>Tipe Transaksi</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_coa_dealer').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/getCOADealer') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [3],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            // function showModalCOADealer() {
            //   $('#tbl_coa_dealer').DataTable().ajax.reload();
            //   $('#modalCOADealer').modal('show');
            // }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalPromoPart', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalPromoPart">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Promo Part</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_promo_part" style="width: 100%">
            <thead>
              <tr>
                <th>ID Promo</th>
                <th>Nama Promo</th>
                <th>Tipe Promo</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_promo_part').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ordering: false,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/getPromoPart') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    d.id_part = form_.dtl_part.id_part;
                    d.kelompok_part = form_.dtl_part.kelompok_part;
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [3],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalPromoPart() {
              $('#tbl_promo_part').DataTable().ajax.reload();
              $('#modalPromoPart').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalDealer', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalDealer">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Dealer</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_dealer" style="width: 100%">
            <thead>
              <tr>
                <th>ID Dealer</th>
                <th>Nama Dealer</th>
                <th>Group Dealer</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_dealer').DataTable({
                processing: true,
                serverSide: true,
                // searching: false,
                // ordering: false,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h2/getDealer') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [3],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalDealer() {
              $('#tbl_dealer').DataTable().ajax.reload();
              $('#modalDealer').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('karyawan_dealer', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modaKaryawanDealer">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Karyawan Dealer</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_karyawan_dealer" style="width: 100%">
            <thead>
              <tr>
                <th>ID Karyawan</th>
                <th>ID FLP MD</th>
                <th>Honda ID</th>
                <th>Nama Lengkap</th>
                <!-- <th>Username Login</th> -->
                <th>Jabatan</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_karyawan_dealer').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2/getKaryawanDealer') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('karyawan_can_login', $data)) { ?>
                      d.karyawan_can_login = true;
                    <?php } ?>
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [5],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModaKaryawanDealer() {
              $('#tbl_karyawan_dealer').DataTable().ajax.reload();
              $('#modaKaryawanDealer').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>