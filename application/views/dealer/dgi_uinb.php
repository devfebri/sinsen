<base href="<?php echo base_url(); ?>" />
<?php
if ($set == "view") {
?>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="">H1</li>
        <li class="">Laporan</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-12">
              <form class="form" id="frm" method="GET">
                <input type='hidden' name="cetak" value=1>
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-2">
                      <label>From (Start Date)</label>
                      <input type="text" class="form-control" id="start" name="start">
                    </div>
                    <div class="col-sm-2">
                      <label>To (End Date)</label>
                      <input type="text" class="form-control" id="end" name="end">
                    </div>
                    <div class="col-sm-4">
                      <label>PO ID</label>
                      <input type="text" class="form-control" id="po_id" name="po_id" readonly placeholder='Klik Untuk Memilih' onclick="showModalPO()">
                    </div>
                    <div class="col-sm-4">
                      <label>No. Shipping List</label>
                      <input type="text" class="form-control" id="no_surat_jalan" name="no_surat_jalan" readonly placeholder='Klik Untuk Memilih' onclick="showModalSJ()">
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                  <div class="col-sm-12" align="center">
                    <button type="button" onclick="getReport('export')" name="export" class="btn bg-blue btn-flat">Export</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
    </section>
  </div>
  <?php
  $data['data'] = ['modalPO', 'modalSJ'];
  $this->load->view('dealer/dgi_api', $data); ?>
  <script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>
  <script src="assets/moment/moment.min.js"></script>
  <script>
    function pilihPO(params) {
      $('#po_id').val(params.no_po);
    }

    function pilihSJ(params) {
      $('#no_surat_jalan').val(params.no_surat_jalan);
    }

    function getReport(tipe) {
      var values = {
        start: document.getElementById("start").value,
        end: document.getElementById("end").value,
        po_id: document.getElementById("po_id").value,
        no_surat_jalan: document.getElementById("no_surat_jalan").value,
        tipe: tipe
      }
      if (values.start == '' || values.end == '') {
        alert('Isi data dengan lengkap !');
        return false;
      } else {
        $("#frm").submit();
      }
    }
    $(function() {
      var end_date = '';
      $("#start").datepicker({
        autoclose: true,
        // todayHighlight: true,
        format: "dd/mm/yyyy"
      }).on("changeDate", function(e) {
        cekTanggal()
      });

      $("#end").datepicker({
        autoclose: true,
        // todayHighlight: true,
        format: "dd/mm/yyyy"
      }).on("changeDate", function(e) {
        cekTanggal()
      });
    });

    function cekTanggal() {
      start = moment($('#start').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
      end = moment($('#end').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
      plus_7 = moment(start, 'YYYY-MM-DD').add(6, 'day').format('YYYY-MM-DD');
      start = Date.parse(start);
      end = Date.parse(end);
      plus_7 = Date.parse(plus_7);
      if (end > plus_7) {
        alert('Maksimal 7 hari dari start date !')
        $('#end').val('')
      } else if (end < start) {
        alert('Tanggal awal lebih besar !')
        $('#end').val('')
      }
    }
  </script>
<?php } ?>