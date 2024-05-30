<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/plugins/datepicker/bootstrap-datepicker.js") ?>"></script>
<script src="<?= base_url("assets/vue/custom/vb-datepicker.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<script>
  Vue.use(VueNumeric.default);
</script>
<base href="<?php echo base_url(); ?>" /> 
<body>
<div class="content-wrapper">
<section class="content-header">
  <h1><?= $title; ?></h1>
  <?= $breadcrumb ?>
</section>
  <section class="content">
    <?php if($mode=="index"): ?>
    <div class="box">
      <div class="box-header">
        <a href="h3/<?= $isi ?>/download_excel" id='btn_download_excel'>
          <button class="btn btn-flat btn-sm btn-success">Download Excel</button>
        </a>
      </div>
      <script>
      function set_download_excel_url(){
        query_string = new URLSearchParams({
          id_ekspedisi : $('#id_ekspedisi').val(),
        }).toString();

        $('#btn_download_excel').attr('href', 'h3/<?= $isi ?>/download_excel?' + query_string);
      }
      </script>
      <div class="box-body">
        <div class="container-fluid no-padding">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label for="" class="control-label">Ekspedisi</label>
                <div class="input-group">
                  <input type="text" id='ekspedisi' class="form-control" readonly>
                  <input type="hidden" id='id_ekspedisi'>
                  <div class="input-group-btn">
                    <button id='choose_ekspedisi_filter' type='button' data-toggle='modal' data-target='#h3_md_ekspedisi_filter_report_penerimaan_ekspedisi' class="btn btn-flat btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                    <button style='display: none;' id='reset_ekspedisi_filter' onclick='return reset_ekspedisi_filter()' type='button' class="btn btn-flat btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <?php $this->load->view('modal/h3_md_ekspedisi_filter_report_penerimaan_ekspedisi'); ?>
            <script>
              function pilih_ekspedisi_filter_report_penerimaan_ekspedisi(data){
                $('#id_ekspedisi').val(data.id);
                $('#ekspedisi').val(data.nama_ekspedisi);

                $('#reset_ekspedisi_filter').show();
                $('#choose_ekspedisi_filter').hide();
                report_penerimaan_ekspedisi.draw();
                set_download_excel_url();
              }

              function reset_ekspedisi_filter(){
                $('#id_ekspedisi').val('');
                $('#ekspedisi').val('');

                $('#reset_ekspedisi_filter').hide();
                $('#choose_ekspedisi_filter').show();
                report_penerimaan_ekspedisi.draw();
                set_download_excel_url();
              }
            </script>
          </div>
        </div>
        <table id="report_penerimaan_ekspedisi" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>No.</th>              
              <th>Tgl Penerimaan Gudang</th>              
              <th>No Penerimaan</th>              
              <th>Ekspedisi</th>              
              <th>Tipe Mobil</th>              
              <th>Tgl Surat Jalan Ekspedisi</th>              
              <th>No. Surat Jalan Ekspedisi</th>              
              <th>No Plat</th>              
              <th>No Invoice AHM</th>              
              <th>No Shipping List</th>              
              <th>Tgl Shipping List</th>              
              <th>No Packing Sheet</th>              
              <th>Kode Part</th>              
              <th>Nama Part</th>              
              <th>Kuantitas</th>              
              <th>Koli</th>              
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <script>
        $(document).ready(function(){
          report_penerimaan_ekspedisi = $('#report_penerimaan_ekspedisi').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            order: [],
            ajax: {
                url: "<?= base_url('api/md/h3/report_penerimaan_ekspedisi') ?>",
                dataSrc: "data",
                type: "POST",
                data: function(d){
                  d.id_ekspedisi = $('#id_ekspedisi').val();
                }
            },
            columns: [
                { data: 'index', orderable: false, width: '3%' }, 
                { 
                  data: 'tanggal_penerimaan',
                  render: function(data){
                    return moment(data).format('DD/MM/YYYY');
                  }
                }, 
                { data: 'no_penerimaan_barang' }, 
                { data: 'nama_ekspedisi' }, 
                { data: 'type_mobil', width: '80px' }, 
                { 
                  data: 'tgl_surat_jalan_ekspedisi',
                  render: function(data){
                    return moment(data).format('DD/MM/YYYY');
                  }
                }, 
                { data: 'no_surat_jalan_ekspedisi' }, 
                { data: 'no_plat', width: '80px' }, 
                { data: 'invoice_number' }, 
                { data: 'surat_jalan_ahm' }, 
                { 
                  data: 'packing_sheet_date',
                  render: function(data){
                    return moment(data).format('DD/MM/YYYY');
                  }
                }, 
                { data: 'packing_sheet_number' }, 
                { data: 'id_part' }, 
                { data: 'nama_part', width: '200px' }, 
                { 
                  data: 'qty_diterima',
                  render: function(data){
                    return accounting.format(data, 0, '.',',');
                  }
                }, { 
                  data: 'jumlah_koli',
                  render: function(data){
                    return accounting.format(data, 2, '.',',');
                  }
                }, 
            ],
          });
        });
        </script>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php endif; ?>
  </section>
</div>