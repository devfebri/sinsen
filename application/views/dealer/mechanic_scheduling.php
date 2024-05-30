<base href="<?php echo base_url(); ?>" />
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?php echo $title; ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>
      <li class="">H2</li>
      <li class="">Service Execution</li>
      <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
    </ol>
  </section>
  <section class="content">
    <?php
    if ($set == "index") {
    ?>
      <style>
        .btn-circle {
          width: 30px;
          height: 30px;
          padding: 6px 0px;
          border-radius: 15px;
          text-align: center;
          font-size: 12px;
          line-height: 1.42857;
        }
      </style>
      <script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
      <script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
      <div class="box" id="form_">
        <div class="box-body" style="min-height: 500px;">
          <div class="row" style="padding-top: 30px;padding-left: 2%;padding-right: 2%">
            <div class="col-sm-3" v-for="(dtl,index) of dt_wo">
              <div class="box box-danger" style="background: #e8e4e4;" @click.prevent="showModalWO(this,dtl.id_work_order)">
                <div class="box-header">
                  <div class="box-tools pull-right">
                    <button v-bind:style="{background:dtl.color}" class="btn btn-sm btn-circle" disabled>&nbsp;</button>
                  </div>
                </div>
                <div class="box-body">
                  <?php /* <img class="profile-user-img img-responsive img-circle" src="<?= base_url('assets/panel/images/user/admin-lk.jpg') ?>" style="width: 150px" alt="Gambar Mekanik"> */ ?>
                  <table style="width: 100%; font-size: 13px;">
                    <tr>
                      <td align="center"><b>{{dtl.nama_lengkap}}</b></td>
                    </tr>
                    <tr>
                      <td align="center" v-bind:style="{color:dtl.color}"><b>{{dtl.status}}</b></td>
                    </tr>
                    <tr>
                    <tr>
                      <td align="center"><b>Antrian : {{dtl.id_antrian}}</b></td>
                    </tr>
                    <tr>
                      <td align="center"><b>TOJ : {{dtl.id_type}}</b></td>
                    </tr>
                      <td align="center"><b>WO : {{dtl.id_work_order}}</b></td>
                    </tr>
                    <tr>
                      <td align="center"><b>PIT : {{dtl.id_pit}} - {{dtl.jenis_pit}}</b></td>
                    </tr>
                    <tr>
                      <td align="center"><b>No. Polisi : {{dtl.no_polisi}}</b></td>
                    </tr>
                    <tr>
                      <td align="center"><b>Waktu Pekerjaan :<br> {{dtl.waktu_pekerjaan}}</b></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div><!-- /.box-body -->
        <div class="modal fade modalWO" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog" style="width: 40%">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Work Order #{{m_wo.id_work_order}}</h4>
              </div>
              <div class="modal-body">
                <table class="table table-condensed">
                  <tr>
                    <td>ID Antrian</td>
                    <td>: {{m_wo.id_antrian}}</td>
                  </tr>
                  <tr>
                    <td>Type Kendaraan</td>
                    <td>: {{m_wo.tipe_ahm}}</td>
                  </tr>
                  <tr>
                    <td>No. Polisi</td>
                    <td>: {{m_wo.no_polisi}}</td>
                  </tr>
                  <tr>
                    <td>Estimated Time Required</td>
                    <td>: {{m_wo.etr}}</td>
                  </tr>
                  <tr>
                    <td>Time Elapsed</td>
                    <td>: {{m_wo.te}}</td>
                  </tr>
                  <tr>
                    <td>Time Overdue</td>
                    <td>: {{m_wo.to}}</td>
                  </tr>
                </table>
              </div>
              <div class="modal-footer">
                <div class="col-sm-12" align="center">
                  <button v-if="m_wo.start_at==null" @click.prevent="setClock('start')" class="btn btn-info">START</button>
                  <button v-if="(m_wo.status_wo=='pause' || m_wo.status_wo=='pending' && m_wo.start_at!=null)  && (m_wo.last_stats!='end')" @click.prevent="setClock('resume')" class="btn btn-info">RESUME</button>
                  <button v-if="m_wo.status_wo=='open' && (m_wo.last_stats=='start' || m_wo.last_stats=='resume')" @click.prevent="setClock('pause')" class="btn btn-default">PAUSE</button>
                  <button v-if="m_wo.status_wo=='open' && (m_wo.last_stats=='start' || m_wo.last_stats=='resume')" @click.prevent="setClock('pending')" class="btn btn-default">PENDING</button>
                  <button v-if="((m_wo.status_wo=='open' || m_wo.status_wo=='pause') && m_wo.start_at!=null) && (m_wo.last_stats!='end')" @click.prevent="setClock('closed')" class="btn btn-primary">END</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
      <script>
        $(document).ready(function() {
          form_.loadWO()
          // alert('d')
        })
        var form_ = new Vue({
          el: '#form_',
          data: {
            kosong: '',
            m_wo: {},
            dt_wo: []
          },
          methods: {
            setClock(stats) {
              values = {
                id_work_order: this.m_wo.id_work_order,
                stats: stats
              }
              if (stats == 'closed') {
                if (confirm("Apakah anda yakin ?") == true) {
                  this.ajaxSetClock(values);
                }
              }else if(stats == 'pending') {
                if (confirm("Apakah anda yakin ?") == true) {
                  this.ajaxSetClock(values);
                }
                  
              } else {
                this.ajaxSetClock(values);
              }
            },
            ajaxSetClock: function(values) {
              $.ajax({
                beforeSend: function() {
                  // $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
                  // $(el).attr('disabled',true);
                },
                url: '<?= base_url('dealer/mechanic_scheduling/setClock') ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    form_.m_wo = {};
                    $('.modalWO').modal('hide');
                    form_.loadWO();
                  } else {
                    alert(response.pesan);
                  }
                },
                error: function() {
                  alert("failure");
                },
                statusCode: {
                  500: function() {
                    alert('fail');
                  }
                }
              });
            },
            showModalWO: function(el, id_work_order) {
              values = {
                id_work_order: id_work_order
              }
              $.ajax({
                beforeSend: function() {
                  // $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
                  // $(el).attr('disabled',true);
                },
                url: '<?= base_url('dealer/mechanic_scheduling/dt_modal_wo') ?>',
                type: "POST",
                data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    form_.m_wo = response.data;
                    // console.log(form_.m_wo);
                  } else {
                    form_.dt_absen = [];
                    alert(response.pesan);
                  }
                },
                error: function() {
                  alert("failure");
                  $(el).html('Show');
                  $(el).attr('disabled', false);
                },
                statusCode: {
                  500: function() {
                    alert('fail');
                    $(el).html('Show');
                    $(el).attr('disabled', false);
                  }
                }
              });
              $('.modalWO').modal('show');
            },
            loadWO: function() {
              $.ajax({
                beforeSend: function() {
                  // $(el).html('<i class="fa fa-spinner fa-spin"></i> Process');
                  // $(el).attr('disabled',true);
                },
                url: '<?= base_url('dealer/mechanic_scheduling/loadWO') ?>',
                type: "POST",
                // data: values,
                cache: false,
                dataType: 'JSON',
                success: function(response) {
                  if (response.status == 'sukses') {
                    form_.dt_wo = [];
                    for (dtl of response.data) {
                      form_.dt_wo.push(dtl);
                    }
                    // console.log(form_.dt_wo);
                  } else {
                    form_.dt_wo = [];
                    alert(response.pesan);
                  }
                },
                error: function() {
                  alert("failure");
                },
                statusCode: {
                  500: function() {
                    alert('fail');
                  }
                }
              });
            }
          }
        });
      </script>
    <?php
    }
    ?>
  </section>
</div>