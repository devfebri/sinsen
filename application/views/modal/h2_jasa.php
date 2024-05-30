<?php if (in_array('modal_detail_work_list', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_detail_work_list">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Detail Work List</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_detail_work_list">
            <thead>
              <tr>
                <th width="5%">Kode</th>
                <th>Nama Detail Work List</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_detail_work_list').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2_jasa/get_detail_work_list') ?>",
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
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
              });
            });

            function showModalDetailWorkList() {
              $('#tbl_detail_work_list').DataTable().ajax.reload();
              $('#modal_detail_work_list').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modal_spareparts', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_spareparts">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">List Sparepart</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_sparepart">
            <thead>
              <tr>
                <th width="5%">Kode</th>
                <th>Nama Part</th>
                <th width="5%">Action</th>
              </tr>
            </thead>
          </table>
          <script>
            $(document).ready(function() {
              $('#tbl_sparepart').DataTable({
                processing: true,
                serverSide: true,
                "language": {
                  "infoFiltered": ""
                },
                order: [],
                ajax: {
                  url: "<?= base_url('api/h2_jasa/get_spareparts') ?>",
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
                    "className": 'text-center'
                  },
                  // { "targets":[4], "searchable": false } 
                ],
              });
            });

            function showModalSpareparts() {
              $('#tbl_sparepart').DataTable().ajax.reload();
              $('#modal_spareparts').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (in_array('modal_jasa', $data)) { ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_jasa">
    <div class="modal-dialog" style="width:50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">List Jasa</h4>
        </div>
        <div class="modal-body">
          <table class="table table-striped table-bordered table-hover table-condensed" id="tbl_jasa">
            <thead>
              <tr>
                <th width="5%">ID Jasa</th>
                <th>Nama Jasa</th>
                <th>Deskripsi</th>
                <th>Tot. Detail Work List</th>
                <th>Tot. Spareparts</th>
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
                  url: "<?= base_url('api/h2_jasa/get_jasa') ?>",
                  dataSrc: "data",
                  data: function(d) {
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
              });
            });

            function showModalJasa() {
              $('#tbl_jasa').DataTable().ajax.reload();
              $('#modal_jasa').modal('show');
            }
          </script>
        </div>
      </div>
    </div>
  </div>
<?php } ?>