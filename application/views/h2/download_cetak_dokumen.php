<base href="<?php echo base_url(); ?>" />
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script>
  Vue.use(VueNumeric.default);
  Vue.filter('toCurrency', function(value) {
    return accounting.formatMoney(value, "", 0, ".", ",");
    return value;
  });
</script>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">H2</li>
      <li class="">Claim</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php
    if ($set == "view") {
    ?>
      <?= $this->session->userdata('group') ?>
      <div class="row" id="form_">
        <div class="col-sm-5">
          <div class="box box-danger" style='min-height:226px'>
            <div class="box-body">
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="nama_dokumen">Nama Dokumen</label>
                  <select class='form-control' v-model='dokumen' id='dokumen'>
                    <option value=''>-choose-</option>
                    <option value='wos'>Download WOS Excel</option>
                    <option value='lbpc_ahm'>LBPC (AHM)</option>
                    <option value='rekap_lbpc_internal'>Rekap LBPC (internal)</option>
                    <option value='ganti_claim_internal'>Daftar Penggantian Claim (internal)</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Start Date</label>
                  <input type="text" required class="form-control datepicker2" placeholder="Start Date" id='start_date'>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>End Date</label>
                  <input type="text" required class="form-control datepicker2" placeholder="End Date" id='end_date'>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>Kelompok Pengajuan</label>
                  <select class='form-control' id='kelompok_pengajuan'>
                    <option value=''>-choose-</option>
                    <option value='E'>E</option>
                    <option value='F'>F</option>
                    <option value='L'>L</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="box-footer text-center">
              <button type='button' class="btn btn-danger btn-flat" onclick='search()'>Search</button>
              <button class="btn btn-danger btn-flat" onclick="reset()">Reset</button>
              <button class="btn btn-danger btn-flat" onclick="printing('selected','print')" v-if="show_print==1">Print</button>
              <button class="btn btn-danger btn-flat" onclick="printing('all','print')" v-if="show_print==1">Print All</button>
              <button class="btn btn-danger btn-flat" onclick="printing('selected','download')" v-if="show_download==1">Download</button>
              <button class="btn btn-danger btn-flat" onclick="printing('all','download')" v-if="show_download==1" style='margin-top:2px'>Download All</button>
            </div>
          </div>
        </div>
        <div class="col-sm-7">
          <div class="box box-danger" style='min-height:300px'>
            <div class="box-body">
              <table class="table table-bordered">
                <thead>
                  <th width='10%'>No.</th>
                  <th width='30%'>No. Registrasi Claim</th>
                  <th width='20%'>Tgl. LBPC</th>
                  <th width='20%'>No. LBPC</th>
                </thead>
                <tbody>
                  <tr v-for="(dt, index) of detailPrint">
                    <td>{{index+1}}</td>
                    <td>{{dt.id_rekap_claim}}</td>
                    <td>{{dt.tgl_lbpc}}</td>
                    <td>{{dt.no_lbpc}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="box">
            <div class="box-body">
              <table class="table table-striped table-bordered table-hover table-condensed" id="serverside_tables" style="width: 100%">
                <thead>
                  <th>
                    <input name="select_all" value="1" type="checkbox">
                  </th>
                  <th>No.</th>
                  <th>No. Registrasi Claim</th>
                  <th>Tgl. Claim</th>
                  <th>No. LBPC</th>
                  <th>Amount Material</th>
                  <th>Amount Jasa</th>
                  <th>Amount Pokok</th>
                  <th>PPN</th>
                  <th>Amount Pokok + PPN</th>
                  <th>PPh</th>
                  <th>Total Yang Dibayar</th>
                  <th>Status</th>
                </thead>
              </table>
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
              <h4 class="modal-title" id="labelFoto">Preview</h4>
            </div>
            <div class="modal-body">
              <div class="">
                <div id="filePDF"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <script>
        var form_ = new Vue({
          el: '#form_',
          data: {
            dokumen: '',
            detailPrint: [],
            rows_selected: [],
            show_print: 0,
            show_download: 0,
          },
          methods: {

          },
          computed: {

          },
          watch: {
            dokumen: function() {
              this.show_download = 0;
              this.show_print = 0;
              if (this.dokumen == 'wos') {
                this.show_download = 1;
              } else {
                if (this.dokumen == 'rekap_lbpc_internal') {
                  this.show_download = 1;
                }
                if (this.dokumen == 'ganti_claim_internal') {
                  this.show_download = 1;
                }
                this.show_print = 1;
              }
            }
          }
        })

        function search() {
          let val = validasi();
          if (val.status == 0) {
            toastr_warning(val.msg)
            return false
          }
          $('#serverside_tables').DataTable().ajax.reload();
        }

        function validasi() {
          let dokumen = $('#dokumen').val();
          let start_date = $('#start_date').val();
          let end_date = $('#end_date').val();
          let kelompok_pengajuan = $('#kelompok_pengajuan').val();
          let response = {
            status: 1,
            msg: null
          }
          if (dokumen === '' || dokumen === undefined || start_date === '' || start_date === undefined || end_date === '' || end_date === undefined || kelompok_pengajuan === '' || kelompok_pengajuan === undefined) {
            response = {
              status: 0,
              msg: 'Silahkan Lengkapi Data !'
            }
          }
          return response
        }

        function reset() {
          $('#start_date').val('');
          $('#end_date').val('');
          $('#kelompok_pengajuan').val('');
          form_.dokumen = '';
          $('#serverside_tables').DataTable().ajax.reload();
        }
        //
        // Updates "Select all" control in a data table
        //
        function updateDataTableSelectAllCtrl(table) {
          var $table = table.table().node();
          var $chkbox_all = $('tbody input[type="checkbox"]', $table);
          var $chkbox_checked = $('tbody input[type="checkbox"]:checked', $table);
          var chkbox_select_all = $('thead input[name="select_all"]', $table).get(0);

          // If none of the checkboxes are checked
          if ($chkbox_checked.length === 0) {
            chkbox_select_all.checked = false;
            if ('indeterminate' in chkbox_select_all) {
              chkbox_select_all.indeterminate = false;
            }

            // If all of the checkboxes are checked
          } else if ($chkbox_checked.length === $chkbox_all.length) {
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
              chkbox_select_all.indeterminate = false;
            }

            // If some of the checkboxes are checked
          } else {
            chkbox_select_all.checked = true;
            if ('indeterminate' in chkbox_select_all) {
              chkbox_select_all.indeterminate = true;
            }
          }
        }


        $(document).ready(function() {
          // Array holding selected row IDs
          var rows_selected = [];
          var table = $('#serverside_tables').DataTable({
            'processing': true,
            'serverSide': true,
            "language": {
              "infoFiltered": "",
              "processing": "<p style='font-size:20pt;background:#d9d9d9b8;color:black;width:100%'><i class='fa fa-refresh fa-spin'></i></p>",
            },
            // 'ajax': "<?= base_url($folder . '/' . $isi . '/fetch') ?>",
            ajax: {
              url: "<?= base_url($folder . '/' . $isi . '/fetch') ?>",
              type: "POST",
              dataSrc: "data",
              data: function(d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
                d.kelompok_pengajuan = $('#kelompok_pengajuan').val();
                return d;
              },
            },
            'columnDefs': [{
              'targets': 0,
              'searchable': false,
              'orderable': false,
              'width': '1%',
              'className': 'dt-body-center',
              'render': function(data, type, full, meta) {
                return '<input type="checkbox">';
              }
            }],
            'order': [
              [1, 'asc']
            ],
            'rowCallback': function(row, data, dataIndex) {
              // Get row ID
              var rowId = data[0];

              // If row ID is in the list of selected row IDs
              if ($.inArray(rowId, rows_selected) !== -1) {
                $(row).find('input[type="checkbox"]').prop('checked', true);
                $(row).addClass('selected');
              }
            }
          });

          // Handle click on checkbox
          $('#serverside_tables tbody').on('click', 'input[type="checkbox"]', function(e) {
            var $row = $(this).closest('tr');

            // Get row data
            var data = table.row($row).data();

            // Get row ID
            var rowId = data[0];

            // Determine whether row ID is in the list of selected row IDs 
            var index = $.inArray(rowId, rows_selected);

            // If checkbox is checked and row ID is not in list of selected row IDs
            if (this.checked && index === -1) {
              rows_selected.push(rowId);
              form_.rows_selected.push(rowId);
            } else if (!this.checked && index !== -1) {
              rows_selected.splice(index, 1);
              form_.rows_selected.splice(index, 1);
            }
            if (this.checked) {
              $row.addClass('selected');
            } else {
              $row.removeClass('selected');
            }
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
            setDataPrint()
            // Prevent click event from propagating to parent
            e.stopPropagation();
          });

          function setDataPrint() {
            let values = {
              no_claim: rows_selected
            }
            $.ajax({
              beforeSend: function() {},
              url: '<?= base_url('h2/' . $isi . '/setDataPrint') ?>',
              type: "POST",
              data: values,
              cache: false,
              dataType: 'JSON',
              success: function(response) {
                if (response.status == 'sukses') {
                  form_.detailPrint = response.data;
                } else {
                  alert(response.pesan);
                  form_.detailPrint = [];
                }
              },
              error: function() {
                alert("Something Went Wrong !");
              },
            });
          }

          // Handle click on table cells with checkboxes
          $('#serverside_tables').on('click', 'tbody td, thead th:first-child', function(e) {
            $(this).parent().find('input[type="checkbox"]').trigger('click');
          });

          // Handle click on "Select all" control
          $('thead input[name="select_all"]').trigger('click', function(e) {
            console.log('dd');
            if (this.checked) {
              $('#serverside_tables tbody input[type="checkbox"]:not(:checked)').trigger('click');
            } else {
              $('#serverside_tables tbody input[type="checkbox"]:checked').trigger('click');
            }

            // Prevent click event from propagating to parent
            e.stopPropagation();
          });

          // Handle table draw event
          table.on('draw', function() {
            // Update state of "Select all" control
            updateDataTableSelectAllCtrl(table);
          });
        });

        function printing(set, tipe) {
          let val = validasi();
          if (val.status == 0) {
            toastr_warning(val.msg)
            return false
          }
          $('#filePDF').html('');
          params = {
            'dokumen': form_.dokumen,
            'data': set === 'selected' ? form_.rows_selected : null,
            'start_date': $('#start_date').val(),
            'end_date': $('#end_date').val(),
            'kelompok_pengajuan': $('#kelompok_pengajuan').val(),
            'set': set,
            'tipe': tipe
          }
          if (set === 'selected') {
            if (form_.rows_selected.length == 0) {
              toastr_warning('Silahkan Pilih Data Yang Akan Dicetak !')
              return false;
            }
          }
          let values = new URLSearchParams(params).toString();
          if (tipe === 'download') {
            window.location = '<?= base_url('h2/download_cetak_dokumen/printing?') ?>' + values;
          } else {
            showPDF(values)
          }
        }

        function showPDF(values) {
          file = '<?= base_url('h2/download_cetak_dokumen/printing?') ?>' + values;
          $('#filePDF').append('<embed id="filePDF" src="' + file + '" frameborder="0" width="100%" height="400px">')
          $('#modalPrinting').modal('show');
        }
      </script>
    <?php
    } ?>
  </section>
</div>