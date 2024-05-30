<base href="<?php echo base_url(); ?>" />
    <?php 
    if($set=="view"){
    ?>

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>

  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H2</li>
    <li class="">3 Axis Analysis</li>
    <li class="active"><?php echo ucwords(str_replace("_"," ",$isi)); ?></li>
  </ol>
</section>


<section class="content">
    <!-- <div class="col-sm-12"> -->
      <div class="box-header with-border">
        <div class="box box-warning">
          <div class="modal-header" style="height: 40px;">
            <h3 class="login-box-msg" style="font-size: 16px;"><b>Monitoring Dashboard Follow Up</b></h3>
          </div>
             
          <form class="form-horizontal" action="h2/h2_md_monitoring_dashboard_follow_up/index" method="get">
            <div class="box-body">       
              <!-- <div class="form-group"> -->
                <table>
                  <tr>
                    <td  style="width: 190px;"><label for="inputEmail3">Range Tanggal From<i style="color:red;"><b>*</b></i></label></td>
                    <td style="width: 400px;"><input type="text" class="form-control datepicker"  name="tgl1" value="" id="tanggal1" readonly></td>
                    <td><label for="inputEmail3" >To<i style="color:red;"><b>*</b></i></label></td>
                    <td style="width: 400px;"><input type="text" class="form-control datepicker"  name="tgl2" value="" id="tanggal2" readonly></td>
                   
                  </tr>
                </table>
                <table>
                <tr>
                    <td style="width: 190px;"><label for="inputEmail3">Dealer<i style="color:red;"><b>*</b></i></label></td>
                    <td >
                      <select class="form-control select2"  aria-label="Default select example" name="dealer" id="dealer">
                          <option selected disabled>Pilih Dealer</option>
                          <?php foreach($dealer as $row) : ?>
                              <option value="<?php echo $row->id_dealer?>"><?php echo $row->nama_dealer?></option>
                          <?php endforeach; ?>
                      </select>
                    </td>
                  </tr>
                </table>   
              <div class="modal-footer">
                <div class="col-sm-12" align="center">
                  <button type="submit" name="load" value="chart" class="btn btn-info btn-flat"><i class="fa fa-eye"></i> Load</button>
                  <button type="button"  id="reset" class="btn btn-warning btn-flat"><i class="fa fa-refresh"></i> Reset</button>
                </div>
              </div>
            </form>
        </div>
      </div>
    <!-- </div> -->  
  <?php if ($chart) {?>
    <?php if($customer_db) {?>
      <?php
      $persen_cust_db          =  number_format(($customer_db->row()->id_follow_up/$customer_db->row()->id_follow_up)*100,0);
      $persen_belum_fu         =  number_format(($belum_fu->row()->id_follow_up/$customer_db->row()->id_follow_up)*100,0);
      $persen_cust_reminder    =  number_format(($customer_reminder->row()->status_komunikasi/$customer_db->row()->id_follow_up)*100,0);
      $persen_contacted        =  number_format(($contacted->row()->contacted/$customer_reminder->row()->status_komunikasi)*100,0);
      $persen_booking_service  =  number_format(($booking_service->row()->booking_service/$contacted->row()->contacted)*100,0);
      $persen_actual_service   =  number_format(($actual_service->row()->actual_service/$booking_service->row()->booking_service)*100,0);

      $persen_actual_customer = number_format(($actual_service->row()->actual_service/$customer_db->row()->id_follow_up)*100,0);
      $persen_actual_fu       = number_format(($actual_service->row()->actual_service/$customer_reminder->row()->status_komunikasi)*100,0);
      $persen_actual_contacted= number_format(($actual_service->row()->actual_service/$contacted->row()->contacted)*100,0);
      $persen_actual_booking  = number_format(($actual_service->row()->actual_service/$booking_service->row()->booking_service)*100,0);
  ?>
      <button type="button" class="btn btn-xl btn-block btn-success" disabled>Periode <?= $this->input->get('tgl1')?> S.D. <?= $this->input->get('tgl2') ?></button>
      <br>
        <div class="row"  align="center">
            <div class="col-sm-2">
              <div class="small-box bg-maroon">
                <div class="inner">
                  <p>Customer Database</p>
                  <div style="font-weight:bold; font-size:20px; margin-top:-10px;"><?php echo $customer_db->row()->id_follow_up ?></div>
                  <p><?php echo $persen_cust_db .'%';?></p>
                </div>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="small-box bg-olive">
                <div class="inner">
                  <p>Customer Reminder</p>
                  <div style="font-weight:bold; font-size:20px; margin-top:-10px;">
                  <?php echo $customer_reminder->row()->status_komunikasi;?></div>
                  <p><?php echo  $persen_cust_reminder.'%'?></p>
                </div>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="small-box bg-navy">
                <div class="inner">
                  <p>Contacted</p>
                  <div style="font-weight:bold; font-size:20px; margin-top:-10px;"><?php echo $contacted->row()->contacted ?></div>
                  <p><?php echo  $persen_contacted.'%'?></p>
                </div>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="small-box bg-blue">
                <div class="inner">
                  <p>Booking Service</p>
                  <div style="font-weight:bold; font-size:20px; margin-top:-10px;"><?php echo $booking_service->row()->booking_service ?></div>
                  <p><?php echo  $persen_booking_service.'%'?></p>
                </div>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="small-box bg-green">
                <div class="inner">
                  <p>Actual Service</p>
                  <div style="font-weight:bold; font-size:20px; margin-top:-10px;"><?php echo $actual_service->row()->actual_service ?></div>
                  <p><?php echo  $persen_actual_service.'%'?></p>
                </div>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="small-box bg-red">
                <div class="inner">
                  <p>Belum di Follow Up</p>
                  <div style="font-weight:bold; font-size:20px; margin-top:-10px;"><?php echo $belum_fu->row()->id_follow_up ?></div>
                  <p><?php echo  $persen_belum_fu.'%'?></p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12" id="divCustFuneling">
            <div class="box box-warning" style="height: 450px">
              <div class="box-header">
                <i class="fa fa-graphic"></i>
                <h3 class="login-box-msg" style="font-size: 16px;"><b>Cust Funeling</b></h3>
              </div>
              <div class="box-body chat" >
                <div class="row" style="margin-top: -15px;text-align: center;" >
                <div class="col-md-2">
                    <div class="small-box bg-blue" >
                      <div class="inner">
                        <div style="font-weight:bold; font-size:16px;"><?php echo $persen_actual_customer .'%'?></div>
                        <p style="font-size:13px;">Actual of Total Customer</p>
                      </div>
                    </div>
                    <div class="small-box bg-blue">
                      <div class="inner">
                        <div style="font-weight:bold; font-size:16px;"><?php echo $persen_actual_fu .'%'?></div>
                        <p style="font-size:13px;">Actual of Follow Up</p>
                      </div>
                    </div>
                    <div class="small-box bg-blue">
                      <div class="inner">
                        <div style="font-weight:bold; font-size:16px;"><?php echo $persen_actual_contacted .'%'?></div>
                        <p style="font-size:13px;">Actual of Contacted</p>
                      </div>
                    </div>
                    <div class="small-box bg-blue">
                      <div class="inner">
                        <div style="font-weight:bold; font-size:16px;"><?php echo $persen_actual_booking .'%'?></div>
                        <p style="font-size:13px;">Actual of Booking Service</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="panel panel-success">
                      <div class="panel-heading"><b>Terkirim & direspon/Tersambung</b></div>
                        <div class="panel-body">
                          <table class="table">
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-envelope"></i></td>
                              <td style="width: 150px;"><?php echo $custFuneling->row()->sms ?></td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-whatsapp"></i></td>
                              <td style="width: 150px;"><?php echo $custFuneling->row()->wa ?></td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-phone"></i></td>
                              <td style="width: 150px;"><?php echo $custFuneling->row()->telepon ?></td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-at"></i></td>
                              <td style="width: 150px;"><?php echo $custFuneling->row()->email ?></td>
                            </tr>
                          </table>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="panel panel-success">
                      <div class="panel-heading"><b>Terkirim & Tidak direspon/ Tidak Sambung</b></div>
                        <div class="panel-body">
                          <table class="table">
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-envelope"></i></td>
                              <td style="width: 150px;"><?php echo $custFuneling->row()->sms_rejected ?></td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-whatsapp"></i></td>
                              <td style="width: 150px;"><?php echo $custFuneling->row()->wa_rejected ?></td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-phone"></i></td>
                              <td style="width: 150px;"><?php echo $custFuneling->row()->telepon_rejected ?></td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-at"></i></td>
                              <td style="width: 150px;"><?php echo $custFuneling->row()->email_rejected ?></td>
                            </tr>
                          </table>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="panel panel-success">
                      <div class="panel-heading"><b>Tidak Terkirim/Nomor Salah</b></div>
                        <div class="panel-body">
                          <table class="table">
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-envelope"></i></td>
                              <td style="width: 150px;"><?php echo $custFuneling->row()->sms_failed ?></td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-whatsapp"></i></td>
                              <td style="width: 150px;"><?php echo $custFuneling->row()->wa_failed ?></td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-phone"></i></td>
                              <td style="width: 150px;"><?php echo $custFuneling->row()->telepon_failed ?></td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-at"></i></td>
                              <td style="width: 150px;"><?php echo $custFuneling->row()->email_failed ?></td>
                            </tr>
                          </table>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="panel panel-success">
                      <div class="panel-heading"><b>Booking Service</b></div>
                        <div class="panel-body">
                          <table class="table">
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-envelope"></i></td>
                              <td style="width: 150px;"><?php echo $custFunelingBooking->row()->sms ?> </td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-whatsapp"></i></td>
                              <td style="width: 150px;"><?php echo $custFunelingBooking->row()->wa ?> </td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-phone"></i></td>
                              <td style="width: 150px;"><?php echo $custFunelingBooking->row()->telepon ?> </td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-at"></i></td>
                              <td style="width: 150px;"><?php echo $custFunelingBooking->row()->email ?> </td>
                            </tr>
                          </table>
                        </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="panel panel-success">
                      <div class="panel-heading"><b>Actual Service</b></div>
                        <div class="panel-body">
                          <table class="table">
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-envelope"></i></td>
                              <td style="width: 150px;"><?php echo $custFunelingActual->row()->sms ?></td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-whatsapp"></i></td>
                              <td style="width: 150px;"><?php echo $custFunelingActual->row()->wa ?></td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-phone"></i></td>
                              <td style="width: 150px;"><?php echo $custFunelingActual->row()->telepon ?></td>
                            </tr>
                            <tr>
                              <td style="width: 10px;"><i class="fa fa-at"></i></td>
                              <td style="width: 150px;"><?php echo $custFunelingActual->row()->email ?></td>
                            </tr>
                          </table>
                        </div>
                    </div>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4" id="divGrafikPerformance">
            <div class="box box-warning">
              <div class="box-header">
                <i class="fa fa-graphic"></i>
                <h3 class="login-box-msg" style="font-size: 16px;"><b>Grafik Performance</b></h3>
              </div>
              <div class="box-body chat">
                <canvas id="grafikPerformance" style="margin-top: -15px;height: 300px; width:400px;"></canvas>
                <?php
                  $id_follow_up = $customer_db->row()->id_follow_up;
                  $id_media_kontak_fol_up2=null;
                  $hitung_media2=null;
                  $customer_reminder_1=null;
                  $customer_reminder_2=null;
                  $customer_reminder_3=null;
                  $customer_reminder_4=null;
                  $customer_reminder_5=null;
                  $customer_reminder_6=null;
                  $customer_reminder_7=null;
                  $customer_reminder_8=null;
                  $customer_reminder_9=null;
                  $customer_reminder_10=null;
                  $customer_reminder_1 = $channelGroupById1->row()->customer_reminder;
                  $customer_reminder_2 = $channelGroupById2->row()->customer_reminder;
                  $customer_reminder_3 = $channelGroupById3->row()->customer_reminder;
                  $customer_reminder_4 = $channelGroupById4->row()->customer_reminder;
                  $customer_reminder_5 = $channelGroupById5->row()->customer_reminder;
                  $customer_reminder_6 = $channelGroupById6->row()->customer_reminder;
                  $customer_reminder_7 = $channelGroupById7->row()->customer_reminder;
                  $customer_reminder_8 = $channelGroupById8->row()->customer_reminder;
                  $customer_reminder_9 = $channelGroupById9->row()->customer_reminder;
                  $customer_reminder_10 = $channelGroupById10->row()->customer_reminder;

                  $contacted_1 = $channelGroupById1->row()->contacted;
                  $contacted_2 = $channelGroupById2->row()->contacted;
                  $contacted_3 = $channelGroupById3->row()->contacted;
                  $contacted_4 = $channelGroupById4->row()->contacted;
                  $contacted_5 = $channelGroupById5->row()->contacted;
                  $contacted_6 = $channelGroupById6->row()->contacted;
                  $contacted_7 = $channelGroupById7->row()->contacted;
                  $contacted_8 = $channelGroupById8->row()->contacted;
                  $contacted_9 = $channelGroupById9->row()->contacted;
                  $contacted_10 = $channelGroupById10->row()->contacted;

                  $booking_service_1  = $channelGroupById1->row()->booking_service;
                  $booking_service_2  = $channelGroupById2->row()->booking_service;
                  $booking_service_3  = $channelGroupById3->row()->booking_service;
                  $booking_service_4  = $channelGroupById4->row()->booking_service;
                  $booking_service_5  = $channelGroupById5->row()->booking_service;
                  $booking_service_6  = $channelGroupById6->row()->booking_service;
                  $booking_service_7  = $channelGroupById7->row()->booking_service;
                  $booking_service_8  = $channelGroupById8->row()->booking_service;
                  $booking_service_9  = $channelGroupById9->row()->booking_service;
                  $booking_service_10 = $channelGroupById10->row()->booking_service;

                  $actual_service_1  = $channelGroupById1->row()->actual_service;
                  $actual_service_2  = $channelGroupById2->row()->actual_service;
                  $actual_service_3  = $channelGroupById3->row()->actual_service;
                  $actual_service_4  = $channelGroupById4->row()->actual_service;
                  $actual_service_5  = $channelGroupById5->row()->actual_service;
                  $actual_service_6  = $channelGroupById6->row()->actual_service;
                  $actual_service_7  = $channelGroupById7->row()->actual_service;
                  $actual_service_8  = $channelGroupById8->row()->actual_service;
                  $actual_service_9  = $channelGroupById9->row()->actual_service;
                  $actual_service_10 = $channelGroupById10->row()->actual_service;
                ?>
              </div>
            </div>
          </div>
          <div class="col-md-4" id="divChannelEffectiveness">
            <div class="box box-warning">
              <div class="box-header">
                <i class="fa fa-graphic"></i>
                <h3 class="login-box-msg" style="font-size: 16px;"><b>Channel Effectiveness</b></h3>
                <!-- <div class="box-tools pull-right">
                  <button class="btn btn-primary btn-sm" type="button" onclick="channelEffectiveness()"><i class="fa fa-refresh"></i></button>
                </div> -->
              </div>
              <div class="box-body chat">
                <canvas id="channelEffectiveness" style="margin-top: -15px;height: 300px"></canvas>
                <?php
                  $id_media_kontak_fol_up= "";
                  $hitung_media=null;
                  foreach ($channel_effectiveness as $item)
                  {
                      $jur=$item->id_media_kontak_fol_up;
                      $id_media_kontak_fol_up .= "'$jur'". ", ";
                      $jum=$item->hitung_media;
                      $hitung_media .= "$jum". ", ";
                  }
                ?>
              </div>
            </div>
          </div>
          <div class="col-md-4" id="divGrafikLeaderboard">
            <div class="box box-warning">
              <div class="box-header">
                <i class="fa fa-graphic"></i>
                <h3 class="login-box-msg" style="font-size: 16px;"><b>Grafik Leaderboard ALL AHASS</b></h3>
                <!-- <div class="box-tools pull-right">
                  <button class="btn btn-primary btn-sm" type="button" onclick="grafikLeaderboard()"><i class="fa fa-refresh"></i></button>
                </div> -->
              </div>
              <div class="box-body chat">
                <canvas id="grafikLeaderboard" style="margin-top: -15px;height: 300px; width:400px;"></canvas>
                <?php
                  $terkontak_actual_service= null;
                  $terkontak_tidak_service=null;
                  $dealer="";
                  foreach ($grafikLeaderboard->result() as $item)
                  {
                      $d=$item->kode_dealer_md;
                      $dealer .= "'$d'". ", ";
                      $jur=$item->terkontak_actual_service;
                      $terkontak_actual_service .= "'$jur'". ", ";
                      $jum=$item->terkontak_tidak_service;
                      $terkontak_tidak_service .= "$jum". ", ";
                  }
                ?>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12" id="divActualofToJ">
            <div class="box box-warning" style="height: 690px">
              <div class="box-header">
                <i class="fa fa-graphic"></i>
                <h3 class="login-box-msg" style="font-size: 16px;"><b>Actual of Type Job</b></h3>
              </div>
              <div class="box-body chat" >
                <div class="row" style="margin-top: -15px;text-align: center;">
                  <div class="col-md-6">
                    <canvas id="grafikToJWa" style="margin-top: -15px;height: 300px; width:400px;"></canvas>
                    <?php
                      $id_type= "";
                      $hitung_toj_wa=null;
                      foreach ($grafikToJWa->result() as $item)
                      {
                          $jur=$item->id_type;
                          $id_type .= "'$jur'". ", ";
                          $jum=$item->hitung_toj_wa;
                          $hitung_toj_wa .= "$jum". ", ";
                      }
                    ?>
                  </div>
                  
                  <div class="col-md-6">
                    <canvas id="grafikToJSMS" style="margin-top: -15px;height: 300px; width:400px;"></canvas>
                    <?php
                      $id_type_sms= "";
                      $hitung_toj_sms=null;
                      foreach ($grafikToJSMS->result() as $item)
                      {
                          $jur=$item->id_type;
                          $id_type_sms .= "'$jur'". ", ";
                          $jum=$item->hitung_toj_sms;
                          $hitung_toj_sms .= "$jum". ", ";
                      }
                    ?>
                  </div>
                  <br>
                  <br>
            
                  <div class="col-md-6">
                    <canvas id="grafikToJTelepon" style="margin-top: -15px;height: 300px; width:400px;"></canvas>
                    <?php
                      $id_type_telepon= "";
                      $hitung_toj_telepon=null;
                      foreach ($grafikToJTelepon->result() as $item)
                      {
                          $jur=$item->id_type;
                          $id_type_telepon .= "'$jur'". ", ";
                          $jum=$item->hitung_toj_telepon;
                          $hitung_toj_telepon .= "$jum". ", ";
                      }
                    ?>
                  </div>

                  <div class="col-md-6">
                    <canvas id="grafikToJEmail" style="margin-top: -15px;height: 300px; width:400px;"></canvas>
                    <?php
                      $id_type_email= "";
                      $hitung_toj_email=null;
                      foreach ($grafikToJEmail->result() as $item)
                      {
                          $jur=$item->id_type;
                          $id_type_email .= "'$jur'". ", ";
                          $jum=$item->hitung_toj_email;
                          $hitung_toj_email .= "$jum". ", ";
                      }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    <?php } else{ ?>
      Mohon Diisi Tanggal
    <?php } ?>
  <?php }?>
    
    
    </body>
  </html>
  </section>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.1.0/chartjs-plugin-datalabels.min.js" integrity="sha512-Tfw6etYMUhL4RTki37niav99C6OHwMDB2iBT5S5piyHO+ltK2YX8Hjy9TXxhE1Gm/TmAV0uaykSpnHKFIAif/A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
  const ctx = document.getElementById('channelEffectiveness');
  var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'doughnut',
    // The data for our dataset
    data: {
      labels: [<?php echo $id_media_kontak_fol_up; ?>],
      datasets: [{
          label:'Channel Effectiveness',
          borderWidth: 1,
          data: [<?php echo $hitung_media; ?>]
      }],
      hoverOffset: 4
    },
    // Configuration options go here
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        },
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            labels: {
              usePointStyle: true
            }
          }
        }
          // Change options for ALL labels of THIS CHART
          
        
    },
    plugins: [ChartDataLabels]
  });
</script>

<script type="text/javascript">
  const cty = document.getElementById('grafikPerformance');
  
  var chart = new Chart(cty, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
      labels: ["Customer DB","Customer Reminder","Contacted","Booking Service","Actual Service"],
      datasets: [{
          axis:'y',
          label:'Customer DB',
          borderWidth: 1,
          backgroundColor: '#87f0ac',
          data: [<?php echo $id_follow_up; ?>,'','','',''],
          fill: false,
      },
      {
          axis:'y',
          label:'Telepon',
          borderWidth: 1,
          backgroundColor: '#b0cf13',
          data: ['',<?php echo $customer_reminder_1; ?>,<?php echo $contacted_1; ?>,<?php echo $booking_service_1; ?>,<?php echo $actual_service_1; ?>],
          fill: false,
      },
      {
          axis:'y',
          label:'Telepon/WA Call',
          borderWidth: 1,
          backgroundColor: '#e39f3a',
          data: ['',<?php echo $customer_reminder_2; ?>,<?php echo $contacted_2; ?>,<?php echo $booking_service_2; ?>,<?php echo $actual_service_2; ?>],
          fill: false,
      },
      {
          axis:'y',
          label:'WA',
          borderWidth: 1,
          backgroundColor: '#d917d6',
          data: ['',<?php echo $customer_reminder_3; ?>,<?php echo $contacted_3; ?>,<?php echo $booking_service_3; ?>,<?php echo $actual_service_3; ?>],
          fill: false,
      },
      {
          axis:'y',
          label:'SMS',
          borderWidth: 1,
          backgroundColor: '#d91754',
          data: ['',<?php echo $customer_reminder_4; ?>,<?php echo $contacted_4; ?>,<?php echo $booking_service_4; ?>,<?php echo $actual_service_4; ?>],
          fill: false,
      },
      {
          axis:'y',
          label:'Visit',
          borderWidth: 1,
          backgroundColor: '#8417d9',
          data: ['',<?php echo $customer_reminder_5; ?>,<?php echo $contacted_5; ?>,<?php echo $booking_service_5; ?>,<?php echo $actual_service_5; ?>],
          fill: false,
      },
      {
          axis:'y',
          label:'Facebook',
          borderWidth: 1,
          backgroundColor: '#38d72e',
          data: ['',<?php echo $customer_reminder_6; ?>,<?php echo $contacted_6; ?>,<?php echo $booking_service_6; ?>,<?php echo $actual_service_6; ?>],
          fill: false,
      },
      {
          axis:'y',
          label:'Instagram',
          borderWidth: 1,
          backgroundColor: '#9f3232',
          data: ['',<?php echo $customer_reminder_7; ?>,<?php echo $contacted_7; ?>,<?php echo $booking_service_7; ?>,<?php echo $actual_service_7; ?>],
          fill: false,
      },
      {
          axis:'y',
          label:'Telegram',
          borderWidth: 1,
          backgroundColor: '#25be5b',
          data: ['',<?php echo $customer_reminder_8; ?>,<?php echo $contacted_8; ?>,<?php echo $booking_service_8; ?>,<?php echo $actual_service_8; ?>],
          fill: false,
      },
      {
          axis:'y',
          label:'Twitter',
          borderWidth: 1,
          backgroundColor: '#a8d5e5',
          data: ['',<?php echo $customer_reminder_9; ?>,<?php echo $contacted_9; ?>,<?php echo $booking_service_9; ?>,<?php echo $actual_service_9; ?>],
          fill: false,
      },
      {
          axis:'y',
          label:'Email',
          borderWidth: 1,
          backgroundColor: '#3ba1c5',
          data: ['',<?php echo $customer_reminder_10; ?>,<?php echo $contacted_10; ?>,<?php echo $booking_service_10; ?>,<?php echo $actual_service_10; ?>],
          fill: false,
      }]
    },
    // Configuration options go here
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        stacked:true,
        scales: {
            x:{
              stacked:true
            },
            y:{
              stacked:true,
              beginAtZero: true
            }
        },
        plugins: {
          legend: {
            labels: {
              usePointStyle: true
            }
          }
        }
    },
    plugins: [ChartDataLabels]
  });
</script>

<script type="text/javascript">
  const ctz = document.getElementById('grafikLeaderboard');
  var chart = new Chart(ctz, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
      labels: [<?php echo $dealer; ?>],
      datasets: [{
          axis:'y',
          label:'Terkontak & Actual Service',
          borderWidth: 1,
          backgroundColor: '#87f0ac',
          data: [<?php echo $terkontak_actual_service; ?>],
          fill: false,
      },
      {
          axis:'y',
          label:'Terkontak, Tidak Service',
          borderWidth: 1,
          backgroundColor: '#b0cf13',
          data: [<?php echo $terkontak_tidak_service; ?>],
          fill: false,
      }]
    },
    // Configuration options go here
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        stacked:true,
        scales: {
            x:{
              stacked:true
            },
            y:{
              stacked:true,
              beginAtZero: true
            }
        },
        plugins: {
          legend: {
            labels: {
              usePointStyle: true
            }
          }
        }
    },
    plugins: [ChartDataLabels]
  });
</script>

<script type="text/javascript">
  const cta = document.getElementById('grafikToJWa');
  var chart = new Chart(cta, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
      labels: [<?php echo $id_type; ?>],
      datasets: [{
          label:'ToJ',
          borderWidth: 1,
          backgroundColor: '#87f0ac',
          data: [<?php echo $hitung_toj_wa; ?>],
          fill: false,
      }]
    },
    // Configuration options go here
    options: {
        responsive: true,
        maintainAspectRatio: false,
        stacked:true,
        scales: {
            x:{
              stacked:true,
              title: {
                display: true,
                text: 'Type of Job'
              }
            },
            y:{
              stacked:true,
              beginAtZero: true,
              title: {
                display: true,
                text: 'Jumlah UE'
              }
            }
        },
        plugins: {
          legend: {
            labels: {
              usePointStyle: true
            }
          },
          title: {
                display: true,
                text: 'WA',
                padding: {
                    top: 30,
                    bottom: 10
                }
            },
        }
    },
    plugins: [ChartDataLabels]
  });
</script>

<script type="text/javascript">
  const ctb = document.getElementById('grafikToJSMS');
  var chart = new Chart(ctb, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
      labels: [<?php echo $id_type_sms; ?>],
      datasets: [{
          label:'ToJ',
          borderWidth: 1,
          backgroundColor: '#87f0ac',
          data: [<?php echo $hitung_toj_sms; ?>],
          fill: false,
      }]
    },
    // Configuration options go here
    options: {
        responsive: true,
        maintainAspectRatio: false,
        stacked:true,
        scales: {
            x:{
              stacked:true,
              title: {
                display: true,
                text: 'Type of Job',

              }
            },
            y:{
              stacked:true,
              beginAtZero: true,
              title: {
                display: true,
                text: 'Jumlah UE'
              }
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'SMS',
                padding: {
                    top: 30,
                    bottom: 10
                }
            },
            legend: {
            labels: {
              usePointStyle: true
            }
          }
        }
    },
    plugins: [ChartDataLabels]
  });
</script>

<script type="text/javascript">
  const ctc = document.getElementById('grafikToJTelepon');
  var chart = new Chart(ctc, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
      labels: [<?php echo $id_type_telepon; ?>],
      datasets: [{
          label:'ToJ',
          borderWidth: 1,
          backgroundColor: '#87f0ac',
          data: [<?php echo $hitung_toj_telepon; ?>],
          fill: false,
      }]
    },
    // Configuration options go here
    options: {
        responsive: true,
        maintainAspectRatio: false,
        stacked:true,
        scales: {
            x:{
              stacked:true,
              title: {
                display: true,
                text: 'Type of Job'
              }
            },
            y:{
              stacked:true,
              beginAtZero: true,
              title: {
                display: true,
                text: 'Jumlah UE'
              }
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Telepon',
                padding: {
                    top: 30,
                    bottom: 10
                }
            },
            legend: {
            labels: {
              usePointStyle: true
            }
          }
        }
    },
    plugins: [ChartDataLabels]
  });
</script>

<script type="text/javascript">
  const ctd = document.getElementById('grafikToJEmail');
  var chart = new Chart(ctd, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
      labels: [<?php echo $id_type_email; ?>],
      datasets: [{
          label:'ToJ',
          borderWidth: 1,
          backgroundColor: '#87f0ac',
          data: [<?php echo $hitung_toj_email; ?>],
          fill: false,
      }]
    },
    // Configuration options go here
    options: {
        responsive: true,
        maintainAspectRatio: false,
        stacked:true,
        scales: {
            x:{
              stacked:true,
              title: {
                display: true,
                text: 'Type of Job'
              }
            },
            y:{
              stacked:true,
              beginAtZero: true,
              title: {
                display: true,
                text: 'Jumlah UE'
              }
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Email',
                padding: {
                    top: 30,
                    bottom: 10
                }
            },
            legend: {
              labels: {
                usePointStyle: true
              }
            }
        }
    },
    plugins: [ChartDataLabels]
  });
</script>

<script>
  $('#reset').click(function () {
    window.location = "<?php echo base_url('h2/h2_md_monitoring_dashboard_follow_up/')?>"; 
  });
  $(function () {
    $("#tanggal1").datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    }).on('changeDate', function (selected) {
      var minDate = new Date(selected.date);
      var maxDate = new Date(selected.date);
      minDate.setDate(minDate.getDate());
      maxDate.setDate(maxDate.getDate()+30);
      $('#tanggal2').datepicker('setStartDate', minDate);
      $('#tanggal2').datepicker('setEndDate', maxDate);
    });
 
    $("#tanggal2").datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
    }).on('changeDate', function (selected) {
      var minDate = new Date(selected.date);
      // var maxDate = new Date(selected.date+30);
      minDate.setDate(minDate.getDate());
      // maxDate.setDate(maxDate.getDate());
      $('#tanggal1').datepicker('setEndDate', minDate);
    });

    
    
  });
</script>
<?php }?>