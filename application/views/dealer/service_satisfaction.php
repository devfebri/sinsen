<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">Finance H23</li>
      <li class="">Billing Process</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>

  <section class="content">
    <?php
    if ($set == "index") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $this->uri->segment(2); ?>/history">
              <button class="btn btn-primary btn-flat margin"><i class="fa fa-list"></i> History</button>
            </a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <?php
          if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
          ?>
            <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
              <strong><?php echo $_SESSION['pesan'] ?></strong>
              <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
              </button>
            </div>
          <?php
          }
          $_SESSION['pesan'] = '';

          ?>
          <table id="example4" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Sumber</th>
                <th>ID Referensi</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>Tipe Motor</th>
                <th>Level</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($result as $rs) :
                $button = '';
                $record = '<button type="button" onClick = \'return showModalRecord(' . json_encode($rs) . ')\' class = "btn btn-success btn-flat btn-xs">Record</button>';

                // $filter = ['id_work_order' => $rs->id_work_order];
                // $cek_satisfaction = $this->m_h2->getWOSatisfaction($filter);
                // $level = '';
                // if ($cek_satisfaction->num_rows() > 0) {
                //   $sat = $cek_satisfaction->row();
                //   $level = $sat->level;
                // } else {
                if (can_access($isi, 'can_update')) $button = $record;
                // }
              ?>
                <tr>
                  <td><?= $rs['sumber'] ?></td>
                  <td><?= $rs['id_referensi'] ?></td>
                  <td><?= $rs['no_polisi'] ?></td>
                  <td><?= $rs['nama_customer'] ?></td>
                  <td><?= $rs['tipe_motor'] ?></td>
                  <td></td>
                  <td align="center"><?= $button ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <style>
        .pd-1 {
          margin-top: 2px
        }
      </style>
      <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modalRecord">
        <div class="modal-dialog" style="width:45%">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" id="myModalLabel">Record Satisfaction Level</h4>
            </div>
            <form id="formRecordPart">
              <div class="modal-body">
                <div class="row">
                  <div class="col-sm-6">
                    <label>ID Referensi</label>
                    <input type="text" name="id_referensi" class="form-control" id="id_referensi" readonly>
                  </div>
                  <div class="col-sm-6">
                    <label>Sumber</label>
                    <input type="text" class="form-control" id="sumber" readonly>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <label>No. Polisi</label>
                    <input type="text" class="form-control" id="no_polisi" readonly>
                  </div>
                  <div class="col-sm-6">
                    <label>Nama Customer</label>
                    <input type="text" class="form-control" id="nama_customer" readonly>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <label>Tipe Motor</label>
                    <input type="text" class="form-control" id="tipe_motor" readonly>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <div class="row">
                  <div class="col-sm-12" align="center">
                    <button class="btn btn-lg btn-info pd-1" onclick="submitLevel(this,1)">Very Poor</button>
                    <button class="btn btn-lg btn-info pd-1" onclick="submitLevel(this,2)">Poor</button>
                    <button class="btn btn-lg btn-info pd-1" onclick="submitLevel(this,3)">Don't Know</button>
                    <button class="btn btn-lg btn-info pd-1" onclick="submitLevel(this,4)">Good</button>
                    <button class="btn btn-lg btn-info pd-1" onclick="submitLevel(this,5)">Very Good</button>
                  </div>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
      <script>
        function showModalRecord(dtl) {
          $('#modalRecord').modal('show');
          $('#id_referensi').val(dtl.id_referensi);
          $('#sumber').val(dtl.sumber);
          $('#no_polisi').val(dtl.no_polisi);
          $('#nama_customer').val(dtl.nama_customer);
          $('#tipe_motor').val(dtl.tipe_motor);
        }

        function submitLevel(el, level) {
          let values = {
            id_referensi: $('#id_referensi').val(),
            sumber: $('#sumber').val(),
            level: level
          }
          let val = '';
          if (level == 1) {
            val = 'Very Poor';
          } else if (level == 2) {
            val = 'Poor';
          } else if (level == 3) {
            val = "Don't Know";
          } else if (level == 4) {
            val = 'Good';
          } else if (level == 5) {
            val = 'Very Good';
          }
          $.ajax({
            beforeSend: function() {
              $(el).html('<i class="fa fa-spinner fa-spin"></i>');
              $('.pd-1').attr('disabled', true);
            },
            url: '<?= base_url('dealer/service_satisfaction/save') ?>',
            type: "POST",
            data: values,
            cache: false,
            dataType: 'JSON',
            success: function(response) {
              if (response.status == 'sukses') {
                window.location = response.link;
              } else {
                alert(response.pesan);
                $('.pd-1').attr('disabled', false);
              }
              $(el).html(val);
            },
            error: function() {
              alert("Something Went Wrong !");
              $(el).html(val);
              $('.pd-1').attr('disabled', false);
            }
          });
        }
      </script>
    <?php } elseif ($set == "history") { ?>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            <a href="dealer/<?= $this->uri->segment(2); ?>">
              <button class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
            </a>
          </h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
          </div>
        </div><!-- /.box-header -->
        <div class="box-body">
          <table id="tabel_history" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Sumber</th>
                <th>ID Referensi</th>
                <th>No. Polisi</th>
                <th>Nama Customer</th>
                <th>Tipe Motor</th>
                <th>Level</th>
                <!-- <th>Aksi</th> -->
              </tr>
            </thead>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
      <script>
        $(document).ready(function() {
          $('#tabel_history').DataTable({
            processing: true,
            serverSide: true,
            "language": {
              "infoFiltered": ""
            },
            order: [],
            ajax: {
              url: "<?= base_url('dealer/service_satisfaction/fetchHistory') ?>",
              dataSrc: "data",
              data: function(d) {
                return d;
              },
              type: "POST"
            },
            // "columnDefs": [
            //   // { "targets":[4],"orderable":false},
            //   {
            //     "targets": [5],
            //     "className": 'text-center'
            //   },
            //   // { "targets":[4], "searchable": false } 
            // ]
          });
        });
      </script>
    <?php } ?>
  </section>
</div>