<style>


.rainbow{
  -webkit-animation: rainbow 5s infinite;
  -ms-animation: rainbow 5s infinite;
  animation: rainbow 5s infinite;
  filter: grayscale(100%); 
}

  @-webkit-keyframes rainbow {
  0% {
    color: orange;
  }
  10% {
    color: purple;
  }
  20% {
    color: red;
  }
  30% {
    color: CadetBlue;
  }
  40% {
    color: yellow;
  }
  50% {
    color: coral;
  }
  60% {
    color: green;
  }
  70% {
    color: cyan;
  }
  80% {
    color: DeepPink;
  }
  90% {
    color: DodgerBlue;
  }
  100% {
    color: orange;
  }
}


</style>

<script src="<?= base_url('assets/jquery/jquery-3.4.1.js')?>"></script>
<script src="assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?= base_url("assets/moment/moment.min.js") ?>"></script>
<script type="text/javascript" src="<?= base_url("assets/panel/daterangepicker.min.js") ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url("assets/panel/daterangepicker.css") ?>" />
<div class="row">
  <div class="col-md-12">
    <div class="box box-danger box-solid collapsed-box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-search"></i> Search</h3>
        <div class="box-tools pull-right">
          <button type="button" class=" rainbow btn btn-box-tool " data-widget="collapse"><i class="fa fa-plus"></i>
          </button>
        </div>
        <!-- /.box-tools -->
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <form class="form-horizontal" id="form_search_leads">
          <div class="form-group">
            <label class="col-sm-2 control-label">Leads ID</label>
            <div class="form-input">
              <div class="col-sm-4">
                <select class="form-control" style="width: 100%;" id='search_leads_id' multiple></select>
              </div>
            </div>
            <script>
              $(document).ready(function() {
                $("#search_leads_id").select2({
                  minimumInputLength: 2,
                  ajax: {
                    url: "<?= site_url('api/dealer/filter_prospek/leads_id') ?>",
                    type: "POST",
                    dataType: 'json',
                    delay: 100,
                    data: function(params) {
                      return {
                        searchTerm: params.term, // search term
                      };
                    },
                    processResults: function(response) {
                      return {
                        results: response
                      };
                    },
                    cache: true
                  }
                });
              });
            </script>
            
            <!-- testing ernesto -->
            <!-- <label class="col-sm-2 control-label">Periode Event</label> -->
            <label class="col-sm-2 control-label"> Customer Action Date</label>
            <div class="form-input">
              <div class="col-sm-4">
                <input type="text" class="form-control" id='periode_event' name='periode_event'>
                <input type="hidden" class="form-control" id='start_periode_event'>
                <input type="hidden" class="form-control" id='end_periode_event'>
              </div>
            </div>
            <script>
              $(function() {
                $('#periode_event').daterangepicker({
                  // opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }, function(start, end, label) {
                  $('#start_periode_event').val(start.format('YYYY-MM-DD'));
                  $('#end_periode_event').val(end.format('YYYY-MM-DD'));
                }).on('apply.daterangepicker', function(ev, picker) {
                  $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#start_periode_event').val('');
                  $('#end_periode_event').val('');
                });
              });
            </script>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">No. HP</label>
            <div class="form-input">
              <div class="col-sm-4">
                <input class='form-control' id='no_hp' onkeypress="return number_only(event)">
              </div>
            </div>
            <label class="col-sm-2 control-label">Status FU</label>
            <div class="form-input">
              <div class="col-sm-4">
                <select class="form-control select2" style="width: 100%;" id='id_status_fu' multiple>
                </select>
              </div>
            </div>
            <script>
              $(document).ready(function() {
                $("#id_status_fu").select2({
                  // minimumInputLength: 2,
                  ajax: {
                    url: "<?= site_url('api/dealer/filter_prospek/statusFU') ?>",
                    type: "POST",
                    dataType: 'json',
                    delay: 100,
                    data: function(params) {
                      return {
                        searchTerm: params.term, // search term
                      };
                    },
                    processResults: function(response) {
                      return {
                        results: response
                      };
                    },
                    cache: true
                  }
                });
              });
            </script>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Platform Data</label>
            <div class="form-input">
              <div class="col-sm-4">
                <select class="form-control select2" style="width: 100%;" id='id_platform_data' multiple>
                </select>
              </div>
            </div>
            <script>
              $(document).ready(function() {
                $("#id_platform_data").select2({
                  // minimumInputLength: 2,
                  ajax: {
                    url: "<?= site_url('api/dealer/filter_prospek/platformData') ?>",
                    type: "POST",
                    dataType: 'json',
                    delay: 100,
                    data: function(params) {
                      return {
                        searchTerm: params.term,
                      };
                    },
                    processResults: function(response) {
                      return {
                        results: response
                      };
                    },
                    cache: true
                  }
                });
              });
            </script>
            <label class="col-sm-2 control-label">Hasil FU</label>
            <div class="form-input">
              <div class="col-sm-4">
                <select class="form-control select2" style="width: 100%;" id='kodeHasilStatusFollowUp' multiple>
                  <option value=''>- Pilih -</option>
                </select>
              </div>
            </div>
            <script>
              $(document).ready(function() {
                $("#kodeHasilStatusFollowUp").select2({
                  // minimumInputLength: 2,
                  ajax: {
                    url: "<?= site_url('api/dealer/filter_prospek/hasilStatusFollowUp') ?>",
                    type: "POST",
                    dataType: 'json',
                    delay: 100,
                    data: function(params) {
                      return {
                        searchTerm: params.term, // search term
                      };
                    },
                    processResults: function(response) {
                      return {
                        results: response
                      };
                    },
                    cache: true
                  }
                });
              });
            </script>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Source Leads</label>
            <div class="form-input">
              <div class="col-sm-4">
                <select class="form-control select2" style="width: 100%;" id='id_source_leads' multiple>
                </select>
              </div>
            </div>
            <script>
              $(document).ready(function() {
                $("#id_source_leads").select2({
                  // minimumInputLength: 2,
                  ajax: {
                    url: "<?= site_url('api/dealer/filter_prospek/sourceLeads') ?>",
                    type: "POST",
                    dataType: 'json',
                    delay: 100,
                    data: function(params) {
                      return {
                        searchTerm: params.term, // search term
                      };
                    },
                    processResults: function(response) {
                      return {
                        results: response
                      };
                    },
                    cache: true
                  }
                });
              });
            </script>
            <label class="col-sm-2 control-label">Jumlah FU</label>
            <div class="form-input">
              <div class="col-sm-4">
                <select class="form-control select2" style="width: 100%;" id='jumlah_fu'>
                <?php  for ($i=0; $i <50; $i++) {  ?>
                  <option value="<?=$i?>"><?=$i?></option>
                <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
          <label class="col-sm-2 control-label">Deskripsi Event</label>
            <div class="form-input">
              <div class="col-sm-4">
                <select class="form-control select2" style="width: 100%;" id='deskripsiEvent' multiple>
                </select>
              </div>
            </div>
            <script>
              $(document).ready(function() {
                $("#deskripsiEvent").select2({
                  // minimumInputLength: 2,
                  ajax: {
                    url: "<?= site_url('api/dealer/filter_prospek/deskripsiEvent') ?>",
                    type: "POST",
                    dataType: 'json',
                    delay: 100,
                    data: function(params) {
                      return {
                        searchTerm: params.term, // search term
                      };
                    },
                    processResults: function(response) {
                      return {
                        results: response
                      };
                    },
                    cache: true
                  }
                });
              });
            </script>

            <label class="col-sm-2 control-label">Next FU</label>
            <div class="form-input">
              <div class="col-sm-4">
                <input type="text" class="form-control" id='next_fu' name='next_fu'>
                <input type="hidden" class="form-control" id='start_next_fu'>
                <input type="hidden" class="form-control" id='end_next_fu'>
              </div>
            </div>
            <script>
              $(function() {
                $('#next_fu').daterangepicker({
                  // opens: 'left',
                  autoUpdateInput: false,
                  locale: {
                    format: 'DD/MM/YYYY'
                  }
                }, function(start, end, label) {
                  $('#start_next_fu').val(start.format('YYYY-MM-DD'));
                  $('#end_next_fu').val(end.format('YYYY-MM-DD'));
                }).on('apply.daterangepicker', function(ev, picker) {
                  $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                }).on('cancel.daterangepicker', function(ev, picker) {
                  $(this).val('');
                  $('#start_next_fu').val('');
                  $('#end_next_fu').val('');
                });
              });
            </script>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">Tipe Motor</label>
            <div class="form-input">
              <div class="col-sm-4">
                <select class="form-control" style="width: 100%;" id='id_tipe_kendaraan' multiple></select>
              </div>
            </div>
            <script>
              $(document).ready(function() {
                $("#id_tipe_kendaraan").select2({
                  // minimumInputLength: 2,
                  ajax: {
                    url: "<?= site_url('api/dealer/filter_prospek/tipe_kendaraan') ?>",
                    type: "POST",
                    dataType: 'json',
                    delay: 100,
                    data: function(params) {
                      return {
                        searchTerm: params.term, // search term
                      };
                    },
                    processResults: function(response) {
                      return {
                        results: response
                      };
                    },
                    cache: true
                  }
                });
              });
            </script>
            <label class="col-sm-2 control-label">Overdue D</label>
            <div class="form-input">
              <div class="col-sm-4">
                <select class="form-control select2" style="width: 100%;" id='ontimeSLA2_multi' multiple>
                  <option value=''>- Pilih -</option>
                  <option>On Track</option>
                  <option>Overdue</option>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label">Tampilkan Hasil FU Not Deal</label>
            <div class="form-input">
              <div class="form-input">
                <div class="col-sm-1">
                  <input type="radio" name="show_hasil_fu_not_deal" value="1" class="flat-red" style="position: absolute; opacity: 0;"> Ya
                </div>
                <div class="col-sm-3">
                  <input type="radio" name="show_hasil_fu_not_deal" value="0" class="flat-red" style="position: absolute; opacity: 0;" checked> Tidak
                </div>
              </div>
            </div>

              <?php 
              $tables = $this->db->get_where('ms_user_group',['id_user_group'=>$_SESSION['group']])->row()->code;
              if ($tables !=='sales' ){?>
                          <label class="col-sm-2 control-label">Assign ke Sales People </label>
                          <div class="form-input">
                            <div class="col-sm-4">
                              <select class="form-control select2" style="width: 100%;" id='belum_assign_sales_people' multiple>
                              </select>
                            </div>
                          </div>
              <?}
              ?>

            <script>
              $(document).ready(function() {

                $("#belum_assign_sales_people").select2({
                  ajax: {
                    url: "<?php echo site_url('api/dealer/filter_prospek/salesPeople') ?>",
                    type: "POST",
                    dataType: 'json',
                    delay: 100,
                    data: function(params) {
                      return {
                        searchTerm: params.term, // search term
                      };

                    },
                    processResults: function(response) {
                      return {
                        results: response
                      };
                    },
                    cache: true
                  }
                });


              });
            </script>
            </div>

          
        </form>
      </div>
      <div class="box-footer" align='center'>
        <button class='btn btn-primary' type="button" onclick="search()"><i class="fa fa-search"></i></button>
        <button class='btn btn-default' type="button" onclick="location.reload(true)"><i class="fa fa-refresh"></i></button>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
