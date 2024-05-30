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
                  url: "<?= base_url('api/h2_finance/getCOADealer') ?>",
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

<?php if (in_array('modalVendorPO', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalVendorPO">
    <div class="modal-dialog" style="width:60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Vendor PO</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_vendor_po" style="width: 100%">
            <thead>
              <tr>
                <th>ID Vendor</th>
                <th>Nama Vendor</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_vendor_po').DataTable({
                processing: true,
                serverSide: true,
                // searching: false,
                // ordering: false,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2_finance/getVendorPO') ?>",
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

            function showModalVendorPO() {
              $('#tbl_vendor_po').DataTable().ajax.reload();
              $('#modalVendorPO').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalBarangLuar', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalBarangLuar">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data barang</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_barang_luar" style="width: 100%">
            <thead>
              <tr>
                <th>ID Barang</th>
                <th>Nama barang</th>
                <th>Harga Satuan</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_barang_luar').DataTable({
                processing: true,
                serverSide: true,
                // searching: false,
                // ordering: false,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2_finance/getBarangLuar') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [2],
                    "className": 'text-right'
                  },
                  {
                    "targets": [3],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalBarangLuar() {
              $('#tbl_barang_luar').DataTable().ajax.reload();
              $('#modalBarangLuar').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalPOFinance', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalPOFinance">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data PO Finance</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_po_finance" style="width: 100%">
            <thead>
              <tr>
                <th>No. PO</th>
                <th>Tgl. PO</th>
                <th>Nama Vendor</th>
                <th>Total</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_po_finance').DataTable({
                processing: true,
                serverSide: true,
                // searching: false,
                // ordering: false,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2_finance/getPOFinance') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('po_sel_vendor', $data)) { ?>
                      d.id_vendor = $('#id_vendor').val();
                    <?php } ?>
                    <?php if (in_array('po_sel_approved', $data)) { ?>
                      d.status = 'approved';
                    <?php } ?>
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [3],
                    "className": 'text-right'
                  },
                  {
                    "targets": [4],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalPOFinance() {
              $('#tbl_po_finance').DataTable().ajax.reload();
              $('#modalPOFinance').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalRefPenerimaan', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalRefPenerimaan">
    <div class="modal-dialog" style="width:40%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Referensi</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_ref_penerimaan" style="width: 100%">
            <thead>
              <tr>
                <th>No. Referensi</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            var jenis_penerimaan = '';

            function showModalRefPenerimaan(jenis) {
              jenis_penerimaan = jenis;
              values = {
                jenis_penerimaan: jenis
              };
              $.ajax({
                url: "<?= base_url('api/h2_finance/getRefPenerimaan') ?>",
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    $('#tbl_ref_penerimaan').DataTable({
                      destroy: true,
                      "data": response.data,
                      'order': [1, 'desc'],
                      "columns": [{
                          "data": "id_referensi"
                        },
                        {
                          "data": "tanggal"
                        },
                        {
                          "data": "jumlah"
                        },
                        {
                          "data": "aksi"
                        },
                      ],
                      "columnDefs": [{
                          "targets": [0],
                          "orderable": false
                        },
                        {
                          "targets": [3],
                          "className": 'text-center'
                        },
                        {
                          "targets": [2],
                          "className": 'text-right'
                        },
                      ]
                    })
                  }
                },
                error: function() {
                  alert('Something went wrong !');
                }
              })
              $('#modalRefPenerimaan').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalRefPenerimaanBank', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalRefPenerimaanBank">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Referensi</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_ref_penerimaan_bank" style="width: 100%">
            <thead>
              <tr>
                <th>Referensi</th>
                <th>No. Transaksi</th>
                <th>Tgl. Transaksi</th>
                <th>Jumlah</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            var jenis_penerimaan = '';

            function showModalRefPenerimaanBank(jenis) {
              jenis_penerimaan = jenis;
              values = {};
              $.ajax({
                url: "<?= base_url('api/h2_finance/getRefPenerimaanBank') ?>",
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    $('#tbl_ref_penerimaan_bank').DataTable({
                      destroy: true,
                      "data": response.data,
                      'order': [2, 'desc'],
                      "columns": [{
                          "data": "referensi"
                        },
                        {
                          "data": "no_transaksi"
                        },
                        {
                          "data": "tgl_transaksi"
                        },
                        {
                          "data": "saldo"
                        },
                        {
                          "data": "aksi"
                        },
                      ],
                      "columnDefs": [{
                          "targets": [0],
                          "orderable": false
                        },
                        {
                          "targets": [4],
                          "className": 'text-center'
                        },
                        {
                          "targets": [3],
                          "className": 'text-right'
                        },
                      ]
                    })
                  }
                },
                error: function() {
                  alert('Something went wrong !');
                }
              })
              $('#modalRefPenerimaanBank').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalRefPengeluaran', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalRefPengeluaran">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Referensi</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_ref_pengeluaran" style="width: 100%">
            <thead>
              <tr>
                <th>No. Transaksi</th>
                <th>Tgl. Transaksi</th>
                <th>Saldo</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            var jenis_pengeluaran = '';

            function showModalRefPengeluaran(jenis, tipe_customer = null, id_vendor = null) {
              console.log(id_vendor)
              jenis_penerimaan = jenis;
              values = {
                tipe_customer: tipe_customer,
                id_vendor: id_vendor
              };
              $.ajax({
                url: "<?= base_url('api/h2_finance/getRefPengeluaran') ?>",
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    $('#tbl_ref_pengeluaran').DataTable({
                      destroy: true,
                      "data": response.data,
                      'order': [1, 'desc'],
                      "columns": [{
                          "data": "no_transaksi"
                        },
                        {
                          "data": "tgl_transaksi"
                        },
                        {
                          "data": "saldo"
                        },
                        {
                          "data": "aksi"
                        },
                      ],
                      "columnDefs": [{
                          "targets": [0],
                          "orderable": false
                        },
                        {
                          "targets": [3],
                          "className": 'text-center'
                        },
                        {
                          "targets": [2],
                          "className": 'text-right'
                        },
                      ]
                    })
                  }
                },
                error: function() {
                  alert('Something went wrong !');
                }
              })
              $('#modalRefPengeluaran').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalPengeluaranFinance', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalPengeluaranFinance">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Pengeluaran</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_pengeluaran_finance" style="width: 100%">
            <thead>
              <tr>
                <th>No. Voucher</th>
                <th>Tgl. Entry</th>
                <th>Tipe Customer</th>
                <th>Dibayar Kepada</th>
                <th>Total</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_pengeluaran_finance').DataTable({
                processing: true,
                serverSide: true,
                // searching: false,
                // ordering: false,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2_finance/getPengeluaranFinance') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('jenis_pengeluaran', $data)) { ?>
                      d.jenis_pengeluaran = $('#jenis_pengeluaran').val();
                    <?php } ?>
                    <?php if (in_array('no_bukti_null', $data)) { ?>
                      d.no_bukti_null = true;
                    <?php } ?>
                    d.status = 'approved';
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [{
                    "targets": [5],
                    "orderable": false
                  },
                  {
                    "targets": [4],
                    "className": 'text-right'
                  },
                  {
                    "targets": [5],
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalPengeluaranFinance() {
              $('#tbl_pengeluaran_finance').DataTable().ajax.reload();
              $('#modalPengeluaranFinance').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>


<?php if (in_array('modalDetailNJB', $data)) { ?>
  <script src="assets/moment/moment.min.js"></script>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalDetailNJB">
    <div class="modal-dialog" style="width:95%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><b>Detail NJB</b></h4>
        </div>
        <div class="modal-body form-horizontal">
          <div class="box-body" id="showModalDetailNJB">
            <iframe id="iframe_detail_njb" src="" width="100%" style="height: 600px; border: 0;"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    function modalDetailNJB(no_njb) {
      $('#modalDetailNJB').modal('show');
      url = '<?= base_url('iframe/dealer/h2/njb/detail?id=') ?>' + no_njb;
      $('#iframe_detail_njb').attr('src', url);
    }
  </script>
<?php } ?>

<?php if (in_array('modalDetailNSC', $data)) { ?>
  <script src="assets/moment/moment.min.js"></script>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalDetailNSC">
    <div class="modal-dialog" style="width:95%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel"><b>Detail NSC</b></h4>
        </div>
        <div class="modal-body form-horizontal">
          <div class="box-body" id="showModalDetailNSC">
            <iframe id="iframe_detail_nsc" src="" width="100%" style="height: 900px; border: 0;"></iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    function modalDetailNSC(no_nsc) {
      $('#modalDetailNSC').modal('show');
      url = '<?= base_url('iframe/dealer/h2/nsc/detail?id=') ?>' + no_nsc;
      $('#iframe_detail_nsc').attr('src', url);
    }
  </script>
<?php } ?>