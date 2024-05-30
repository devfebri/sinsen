<?php if (in_array('modalDealerDeliveryUnit', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalDealerDeliveryUnit">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Team Sales</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_dealer_delivery_unit" style="width: 100%">
            <thead>
              <tr>
                <th>ID Karyawan Dealer</th>
                <th>Honda ID</th>
                <th>ID FLP MD</th>
                <th>Nama Driver</th>
                <th>No. Plat</th>
                <th>No. HP</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_dealer_delivery_unit').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h1_dealer/getDriverDeliveryUnit') ?>",
                  dataSrc: "data",
                  data: function(d) {
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
                  // {
                  //   "targets": [4],
                  //   "className": 'text-right'
                  // },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalDriverDeliveryUnit() {
              $('#tbl_dealer_delivery_unit').DataTable().ajax.reload();
              $('#modalDealerDeliveryUnit').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalSPK', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalSPK">
    <div class="modal-dialog" style="width:90%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">SPK (Surat Pesanan Kendaraan)</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_spk" style="width: 100%">
            <thead>
              <tr>
                <th>No. SPK</th>
                <th>Tgl. SPK</th>
                <th>Nama Konsumen</th>
                <th>No. KTP</th>
                <th>No. HP</th>
                <th>Tipe</th>
                <th>Warna</th>
                <th>Jenis Beli</th>
                <th>Tanda Jadi</th>
                <th>Total</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_spk').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h1_dealer/getSPK') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('spk_ada_tanda_jadi', $data)) { ?>
                      d.spk_ada_tanda_jadi = true;
                    <?php } ?>
                    <?php if (in_array('id_tjs_null', $data)) { ?>
                      d.id_tjs_null = true;
                    <?php } ?>
                    <?php if (in_array('spk_ada_dp', $data)) { ?>
                      d.spk_ada_dp = true;
                    <?php } ?>
                    <?php if (in_array('id_invoice_dp_null', $data)) { ?>
                      d.id_invoice_dp_null = true;
                    <?php } ?>

                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [{
                    "targets": [10],
                    "orderable": false
                  },
                  {
                    "targets": [10],
                    "className": 'text-center'
                  },
                  {
                    "targets": [8, 9],
                    "className": 'text-right'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalSPK() {
              $('#tbl_spk').DataTable().ajax.reload();
              $('#modalSPK').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalSO', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalSO">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Sales Order</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_so" style="width: 100%">
            <thead>
              <tr>
                <th>ID SO</th>
                <th>No. SPK</th>
                <th>Nama Konsumen</th>
                <th>No. KTP</th>
                <th>No. HP</th>
                <th>Amount TJS</th>
                <th>Amount DP</th>
                <th>Total</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_so').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h1_dealer/getSO') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('spk_ada_dp', $data)) { ?>
                      d.spk_ada_dp = true;
                    <?php } ?>
                    <?php if (in_array('id_invoice_dp_null', $data)) { ?>
                      d.id_invoice_dp_null = true;
                    <?php } ?>
                    <?php if (in_array('id_inv_pelunasan_null', $data)) { ?>
                      d.id_inv_pelunasan_null = true;
                    <?php } ?>
                    <?php if (in_array('jenis_beli_cash', $data)) { ?>
                      d.jenis_beli = 'Cash';
                    <?php } ?>
                    <?php if (in_array('jenis_beli_kredit', $data)) { ?>
                      d.jenis_beli = 'Kredit';
                    <?php } ?>
                    <?php if (in_array('status_tjs_close', $data)) { ?>
                      d.status_tjs_in = "'close'";
                    <?php } ?>

                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [{
                    "targets": [8],
                    "orderable": false
                  },
                  {
                    "targets": [8],
                    "className": 'text-center'
                  },
                  {
                    "targets": [5, 6, 7],
                    "className": 'text-right'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalSO() {
              $('#tbl_so').DataTable().ajax.reload();
              $('#modalSO').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalRiwayatPenerimaanPembayaran', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalRiwayatPenerimaanPembayaran">
    <div class="modal-dialog" style="width:80%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Riwayat Penerimaan</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_riwayat_penerimaan_pembayaran" style="width: 100%">
            <thead>
              <tr>
                <th>ID Kwitansi</th>
                <th>Tgl. Pembayaran</th>
                <th>Cara Bayar</th>
                <th>Amount</th>
                <th>Nominal Kelebihan</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            var no_spk = '-';
            $(document).ready(function() {
              $('#tbl_riwayat_penerimaan_pembayaran').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h1_dealer/getRiwayatPenerimaanPembayaran') ?>",
                  beforeSend: function() {
                    // getNoSPK(no_spk);
                  },
                  // dataSrc: "data",
                  dataType: 'JSON',
                  data: function(d) {
                    spk = $('#no_spk').val();
                    if (spk === undefined) {} else {
                      no_spk = spk;
                    }
                    d.no_spk = no_spk;
                  },
                  type: "POST"
                },
                "columnDefs": [{
                    "targets": [5],
                    "orderable": false
                  },
                  {
                    "targets": [5],
                    "className": 'text-center'
                  },
                  {
                    "targets": [3, 4],
                    "className": 'text-right'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalRiwayatPenerimaanPembayaran(spk = '-') {
              no_spk = spk;
              $('#tbl_riwayat_penerimaan_pembayaran').DataTable().ajax.reload();
              $('#modalRiwayatPenerimaanPembayaran').modal('show');
            }

            function printing(params) {
              $('#filePDF').html('');

              new_params = {
                'id': params.id_kwitansi
              }
              values = url(new_params)

              if (params.jenis_invoice == 'dp') {
                file = '<?= base_url('dealer/print_receipt/cetak_dp?') ?>' + values;
              } else if (params.jenis_invoice == 'tjs') {
                file = '<?= base_url('dealer/print_receipt/cetak_tjs?') ?>' + values;
              } else if (params.jenis_invoice == 'pelunasan') {
                file = '<?= base_url('dealer/print_receipt/cetak_pelunasan?') ?>' + values;
              }
              window.open(file, '_blank');
              // $('#filePDF').append('<embed id="filePDF" src="' + file + '" frameborder="0" width="100%" height="400px">')
              // $('#modalPrinting').modal('show');
            }

            function url(params) {
              let values = new URLSearchParams(params).toString();
              return values
            }
          </script>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalPrinting">
    <div class="modal-dialog" style="width:95%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="labelFoto">Cetak Kwitansi</h4>
        </div>
        <div class="modal-body">
          <div class="">
            <div id="filePDF"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php } ?>