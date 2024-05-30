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
                      <label>No. SPK</label>
                      <input type="text" class="form-control" id="no_spk" name="no_spk" readonly placeholder='Klik Untuk Memilih' onclick="showModalSPK()">
                    </div>
                    <div class="col-sm-4">
                      <label>No. SO</label>
                      <input type="text" class="form-control" id="id_sales_order" name="id_sales_order" readonly placeholder='Klik Untuk Memilih' onclick="showModalSalesOrder()">
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
  $data['data'] = ['modalSPK', 'spk_id_so_not_null', 'modalSalesOrder', 'finco_not_null'];
  $this->load->view('dealer/dgi_api', $data); ?>
  <script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>
  <script src="assets/moment/moment.min.js"></script>
  <script>
    function pilihKaryawanDealer(params) {
      $('#id_karyawan_dealer').val(params.id_karyawan_dealer);
      $('#salesman').val(params.nama_lengkap);
    }

    function pilihDelivery(params) {
      $('#delivery_document_id').val(params.delivery_document_id);
    }

    function pilihSalesOrder(params) {
      $('#id_sales_order').val(params.id_sales_order);
    }

    function pilihSPK(params) {
      console.log(params)
      $('#no_spk').val(params.no_spk);
      $('#id_customer').val(params.id_customer);
    }

    function getReport(tipe) {
      var values = {
        start: document.getElementById("start").value,
        end: document.getElementById("end").value,
        no_spk: document.getElementById("no_spk").value,
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