<?php if (in_array('all_parts', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalAllParts">
    <div class="modal-dialog" style="width:60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Parts</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_parts" style="width: 100%">
            <thead>
              <tr>
                <th>Kode Part</th>
                <th>Nama Part</th>
                <th>Kelompok Vendor</th>
                <th>HET/1.1</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_parts').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h2/getAllParts') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('part_oli', $data)) { ?>
                      d.part_oli = 1;
                    <?php } ?>
                    <?php if (in_array('filter_tipe_motor', $data)) { ?>
                      d.id_tipe_kendaraan = $("#id_tipe_kendaraan").val();
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

            function showmodalAllParts() {
              $('#tbl_parts').DataTable().ajax.reload();
              $('#modalAllParts').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalJasa', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalJasa">
    <div class="modal-dialog" style="width:60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Jasa Servis</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_jasa" style="width: 100%">
            <thead>
              <tr>
                <th>ID Jasa</th>
                <th>Deskripsi</th>
                <th>Type</th>
                <th>Kategori</th>
                <th>Tipe Motor</th>
                <th>Harga</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
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
                  url: "<?= base_url('api/md/h2/getJasa') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('cek', $data)) { ?>
                      // d.cek = 1;
                    <?php } ?>
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
                  {
                    "targets": [5],
                    "className": 'text-right'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalJasa() {
              $('#tbl_jasa').DataTable().ajax.reload();
              $('#modalJasa').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalAHASS', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalAHASS">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">AHASS</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_ahass" style="width: 100%">
            <thead>
              <tr>
                <th>Kode AHASS</th>
                <th>Nama AHASS</th>
                <th>PIC AHASS</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_ahass').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h2/getAHASS') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('cek', $data)) { ?>
                      // d.cek = 1;
                    <?php } ?>
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [3],
                    "className": 'text-center'
                  }
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalAHASS() {
              $('#tbl_ahass').DataTable().ajax.reload();
              $('#modalAHASS').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalKaryawanMD', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalKaryawanMD">
    <div class="modal-dialog" style="width:60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Karyawan MD</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_kry_md" style="width: 100%">
            <thead>
              <tr>
                <th>ID Karyawan</th>
                <th>Nama Lengkap</th>
                <th>Jabatan</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_kry_md').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h2/getKaryawanMD') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('cek', $data)) { ?>
                      // d.cek = 1;
                    <?php } ?>
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [3],
                    "className": 'text-center'
                  }
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalKaryawanMD() {
              $('#tbl_kry_md').DataTable().ajax.reload();
              $('#modalKaryawanMD').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
<?php if (in_array('modalKabupaten', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalKabupaten">
    <div class="modal-dialog" style="width:60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Kabupaten</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_kabupaten" style="width: 100%">
            <thead>
              <tr>
                <th>ID Kabupaten</th>
                <th>Kabupaten</th>
                <th>Provinsi</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_kabupaten').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h2/getKabupaten') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    <?php if (in_array('filter_provinsi', $data)) { ?>
                      d.id_provinsi = 1500;
                    <?php } ?>
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [3],
                    "className": 'text-center'
                  }
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalKabupaten() {
              $('#tbl_kabupaten').DataTable().ajax.reload();
              $('#modalKabupaten').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalNoMesinClaimKPB', $data)) { ?>
  <div class="modal fade modalNosin" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Cari No Mesin</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_nosin" style="width: 100%">
            <thead>
              <tr>
                <th>No Mesin</th>
                <th>No Rangka</th>
                <th>Tipe Kendaraan</th>
                <th>No KPB</th>
                <th>KPB Ke-</th>
                <th>Tgl. Beli SMH</th>
                <th>KM Service</th>
                <th>Tgl Service</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_nosin').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('h2/claim_kpb/fetch_nosin') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    d.id_dealer = $('#id_dealer').val();
                    d.start_date = $('#start_date').val();
                    d.end_date = $('#end_date').val();
                    d.id_work_order = id_work_order
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

            function showModalNosin() {
              var id_dealer = $('#id_dealer').val();
              var start_date = $('#start_date').val();
              var end_date = $('#end_date').val();
              if (id_dealer == '' || start_date == '' || end_date == '') {
                alert('Silahkan lengkapi data terlebih dahulu ! (Kode AHASS dan Periode Claim)');
                return false;
              } else {
                $('#tbl_nosin').DataTable().ajax.reload();
                $('.modalNosin').modal('show');
              }
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalSymptom', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalSymptom">
    <div class="modal-dialog" style="width:60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data Symptom</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_symptom" style="width: 100%">
            <thead>
              <tr>
                <th>ID Symptom</th>
                <th>Symptom ID</th>
                <th>Symptom EN</th>
                <th>ID Kelompok Symptom</th>
                <th>Kelompok Symptom ID</th>
                <th>Kelompok Symptom EN</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_symptom').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h2/getSymptom') ?>",
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
                  }
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalSymptom() {
              $('#tbl_symptom').DataTable().ajax.reload();
              $('#modalSymptom').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modalLKH', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalLKH">
    <div class="modal-dialog" style="width:60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Data LKH</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_lkh" style="width: 100%">
            <thead>
              <tr>
                <th>No. LKH</th>
                <th>Tgl. LKH</th>
                <th>Kode AHASS</th>
                <th>Nama AHASS</th>
                <th>No. Registrasi Claim</th>
                <th>Tipe Kendaraan</th>
                <th>Tema</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_lkh').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/md/h2/getLKH') ?>",
                  dataSrc: "data",
                  data: function(d) {
                    return d;
                  },
                  type: "POST"
                },
                "columnDefs": [
                  // { "targets":[4],"orderable":false},
                  {
                    "targets": [7],
                    "className": 'text-center'
                  }
                  // { "targets":[4], "searchable": false } 
                ],
                "lengthChange": false
              });
            });

            function showModalLKH() {
              $('#tbl_lkh').DataTable().ajax.reload();
              $('#modalLKH').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>


<?php if (in_array('modalPartsOli', $data)) { ?>
  <div class="modal fade" id="modalPartsOli" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" style="width: 80%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Detail Parts Oli</h4>
        </div>
        <div class="modal-body" id="app_modalPartsOli">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_nosin" style="width: 100%">
            <thead>
              <tr>
                <th>No.</th>
                <th>ID Parts</th>
                <th>Nama Parts</th>
                <th style='text-align:right'>Harga</th>
                <th>Qty</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(dtl, index) of details.parts">
                <td>{{index+1}}</td>
                <td>{{dtl.id_part}}</td>
                <td>{{dtl.nama_part}}</td>
                <td style='text-align:right'>{{dtl.harga_oli_kpb | toCurrency}}</td>
                <td>{{dtl.qty}}</td>
              </tr>
            </tbody>
          </table>
          <script>
            var app_modalPartsOli = new Vue({
              el: '#app_modalPartsOli',
              data: {
                details: {},
              },
              methods: {},
              computed: {
                grandTotPart: function() {
                  let grand = 0;
                  if (this.details.parts != undefined) {
                    for (dt of this.details.parts) {
                      grand += parseFloat(dt.subtotal);
                    }
                  }
                  return grand;
                }
              }
            })
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>