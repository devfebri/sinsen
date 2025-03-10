<?php if (in_array('modalProspek', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalProspek">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Prospek</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_prospek" style="width: 100%">
            <thead>
              <tr>
                <th>ID Prospek</th>
                <th>Tanggal</th>
                <th>Nama Lengkap</th>
                <th>Nomor Kontak Prospect</th>
                <th>Sumber prospect</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_prospek').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/dgi/getProspek') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('jabatan_kry', $data)) { ?>
                      d.id_jabatan_in = '<?= arr_in_sql($data['jabatan_kry']) ?>';
                    <?php } ?>
                    d.group_by_prospek = true;
                    d.start_date = $('#start').val();
                    d.end_date = $('#end').val();
                    d.id_karyawan_dealer = $('#id_karyawan_dealer').val();
                    return d;
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
                  // {
                  //   "targets": [4],
                  //   "className": 'text-right'
                  // },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalProspek() {
              $('#tbl_prospek').DataTable().ajax.reload();
              $('#modalProspek').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalKaryawanDealer', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalKaryawanDealer">
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
                <th>ID Karyawan Dealer</th>
                <th>ID FLP MD</th>
                <th>Honda ID</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            var id_jabatan = '';
            $(document).ready(function() {
              $('#tbl_karyawan_dealer').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/dgi/getKaryawanDealer') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    d.active = 1;
                    d.id_jabatan = id_jabatan;
                    <?php if (in_array('post_id_dealer', $data)) { ?>
                      d.id_dealer = $('#id_dealer').val();
                    <?php } ?>
                    <?php if (in_array('filterKaryawanNotInTeamStructure', $data)) { ?>
                      if (set_from != 'header') {
                        d.filter_not_in_team_structure = true;
                      }
                    <?php } ?>
                    <?php if (in_array('filterSalesCoordinatorNotInTeamStructure', $data)) { ?>
                      if (set_from == 'header') {
                        d.filter_sales_coordinator_not_in_team_structure = true;
                      }
                    <?php } ?>
                    return d;
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
                  // {
                  //   "targets": [4],
                  //   "className": 'text-right'
                  // },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalKaryawanDealer(jbt, from) {
              id_jabatan = jbt;
              set_from = from;
              $('#tbl_karyawan_dealer').DataTable().ajax.reload();
              $('#modalKaryawanDealer').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalSPK', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalSPK">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data SPK</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_spk" style="width: 100%">
            <thead>
              <tr>
                <th>ID SPK</th>
                <th>ID Customer</th>
                <th>Nama Customer</th>
                <th>No. KTP</th>
                <th>Alamat</th>
                <th>Tipe</th>
                <th>Warna</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            set_modal_spk = 0

            function showModalSPK() {
              if (set_modal_spk == 0) {
                $(document).ready(function() {
                  $('#tbl_spk').DataTable({
                    processing: true,
                    serverSide: true,
                    "language": {
                      "infoFiltered": ""
                    },
                    order: [],
                    ajax: {
                      url: "<?= base_url('api/dgi/getSPK') ?>",
                      dataSrc: "data",
                      data: function(d) {
                        <?php if (in_array('spk_so_generate_list_unit', $data)) { ?>
                          d.spk_so_generate_list_unit = true;
                        <?php } ?>
                        <?php if (in_array('spk_id_so_not_null', $data)) { ?>
                          d.spk_id_so_not_null = true;
                        <?php } ?>
                        <?php if (in_array('finco_not_null', $data)) { ?>
                          d.finco_not_null = true;
                        <?php } ?>
                        d.start_date = $('#start').val();
                        d.end_date = $('#end').val();
                        <?php if (in_array('modalSPKFilterPeriodeBast', $data)) { ?>
                          d.periode = 'bast';
                        <?php } ?>
                        return d;
                      },
                      type: "POST"
                    },
                    "columnDefs": [{
                        "targets": [7],
                        "orderable": false
                      },
                      {
                        "targets": [7],
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
              } else {
                $('#tbl_spk').DataTable().ajax.reload();
              }
              set_modal_spk++;
              $('#modalSPK').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalDelivery', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalDelivery">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Delivery Document ID</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_delivery" style="width: 100%">
            <thead>
              <tr>
                <th>Delivery Document ID</th>
                <th>Tgl. Pengiriman</th>
                <th>Driver</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            var set_modal_delivery = 0

            function showModalDelivery() {
              if (set_modal_delivery == 0) {
                $(document).ready(function() {
                  $('#tbl_delivery').DataTable({
                    processing: true,
                    serverSide: true,
                    "language": {
                      "infoFiltered": ""
                    },
                    order: [],
                    ajax: {
                      url: "<?= base_url('api/dgi/getDelivery') ?>",
                      dataSrc: "data",
                      data: function(d) {
                        d.delivery_document_id_not_null = 1;
                        d.start_date = $('#start').val();
                        d.end_date = $('#end').val();
                        <?php if (in_array('modalDeliveryFilterPeriodeBast', $data)) { ?>
                          d.periode = 'bast';
                        <?php } ?>
                        return d;
                      },
                      type: "POST"
                    },
                    "columnDefs": [{
                        "targets": [3],
                        "orderable": false
                      },
                      {
                        "targets": [3],
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
              } else {
                $('#tbl_delivery').DataTable().ajax.reload();
              }
              set_modal_delivery++;
              $('#modalDelivery').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalSalesOrder', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalSalesOrder">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Sales Order</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_so" style="width: 100%">
            <thead>
              <tr>
                <th>No. SO</th>
                <th>No. SPK</th>
                <th>Nama Customer</th>
                <th>No. Mesin</th>
                <th>No. Rangka</th>
                <th>Tipe</th>
                <th>Warna</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            set_modal_so = 0;

            function showModalSalesOrder() {
              if (set_modal_so == 0) {
                $(document).ready(function() {
                  $('#tbl_so').DataTable({
                    processing: true,
                    serverSide: true,
                    "language": {
                      "infoFiltered": ""
                    },
                    order: [],
                    ajax: {
                      url: "<?= base_url('api/dgi/getSalesOrder') ?>",
                      dataSrc: "data",
                      data: function(d) {
                        <?php if (in_array('spk_so_generate_list_unit', $data)) { ?>
                          d.delivery_document_id_not_null = 1
                          d.join_generate_list = true;
                        <?php } ?>
                        <?php if (in_array('finco_not_null', $data)) { ?>
                          d.finco_not_null = true;
                        <?php } ?>

                        d.start_date = $('#start').val();
                        d.end_date = $('#end').val();
                        <?php if (in_array('modalSalesOrderFilterPeriodeBast', $data)) { ?>
                          d.periode = 'bast';
                        <?php } ?>

                        return d;
                      },
                      type: "POST"
                    },
                    "columnDefs": [{
                        "targets": [7],
                        "orderable": false
                      },
                      {
                        "targets": [7],
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
              } else {
                $('#tbl_so').DataTable().ajax.reload();
              }
              set_modal_so++;
              $('#modalSalesOrder').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalPO', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalPO">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Purchase Order</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_po" style="width: 100%">
            <thead>
              <tr>
                <th>PO ID</th>
                <th>Periode</th>
                <th>PO Type</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_po').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/dgi/getPO') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    // d.in_do = 1
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [{
                    "targets": [3],
                    "orderable": false
                  },
                  {
                    "targets": [3],
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

            function showModalPO() {
              $('#tbl_po').DataTable().ajax.reload();
              $('#modalPO').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalSJ', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalSJ">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Surat Jalan</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_surat_jalan" style="width: 100%">
            <thead>
              <tr>
                <th>No. Surat Jalan</th>
                <th>Tgl. Surat Jalan</th>
                <th>No. DO</th>
                <th>PO ID</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_surat_jalan').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/dgi/getSJ') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    // d.in_do = 1
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [{
                    "targets": [4],
                    "orderable": false
                  },
                  {
                    "targets": [4],
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

            function showModalSJ() {
              $('#tbl_surat_jalan').DataTable().ajax.reload();
              $('#modalSJ').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalPODealerPart', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalPODealerPart">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Purchase Order</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_po_dealer_part" style="width: 100%">
            <thead>
              <tr>
                <th>PO ID</th>
                <th>Tipe PO</th>
                <th>Tanggal</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_po_dealer_part').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/dgi/getPODealerPart') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('po_type_hlo', $data)) { ?>
                      d.po_type = 'hlo';
                    <?php } ?>
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [{
                    "targets": [3],
                    "orderable": false
                  },
                  {
                    "targets": [3],
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

            function showModalPODealerPart() {
              $('#tbl_po_dealer_part').DataTable().ajax.reload();
              $('#modalPODealerPart').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalPOPart', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalPOPart">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data HLO Document</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_po_part" style="width: 100%">
            <thead>
              <tr>
                <th>ID HLO Document</th>
                <th>Tanggal</th>
                <th>ID Customer</th>
                <th>Nama Customer</th>
                <th>Uang Muka</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_po_part').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/dgi/getPOPart') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('po_type_hlo', $data)) { ?>
                      d.po_type = 'hlo';
                    <?php } ?>
                    return d;
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
                    "targets": [4],
                    "className": 'text-right'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalPOPart() {
              $('#tbl_po_part').DataTable().ajax.reload();
              $('#modalPOPart').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
<?php if (in_array('modalTeamSales', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalTeamSales">
    <div class="modal-dialog" style="width:70%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Team Sales</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_team_sales" style="width: 100%">
            <thead>
              <tr>
                <th>ID Team</th>
                <th>Nama Team</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_team_sales').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/dgi/getTeamSales') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    d.active = 1;
                    <?php if (in_array('teamNotInTeamStructure', $data)) { ?>
                      d.team_not_in_team_structure = true;
                    <?php } ?>
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [{
                    "targets": [2],
                    "orderable": false
                  },
                  {
                    "targets": [2],
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

            function showModalTeamSales() {
              $('#tbl_team_sales').DataTable().ajax.reload();
              $('#modalTeamSales').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>