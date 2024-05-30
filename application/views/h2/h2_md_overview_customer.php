<script src="<?= base_url("assets/vue/qs.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/axios.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/accounting.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/vue/vue-numeric.min.js") ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/panel/lodash.min.js") ?>"></script>
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
            <h3 class="login-box-msg" style="font-size: 16px;"><b>View Comparison Chart</b></h3>
          </div>
             
          <form class="form-horizontal" action="h2/h2_md_overview_customer/index" method="get">
            <div class="box-body">       
              <!-- <div class="form-group"> -->
                <table>
                  <tr>
                    <td style="width: 460px;"><label for="inputEmail3">Range Tanggal <b>BEFORE</b> From<i style="color:red;"><b>*</b></i></label></td>
                    <td style="width: 400px;"><input type="text" class="form-control datepicker"  name="tgl1" value="" id="tanggal1" readonly></td>
                    <td><label for="inputEmail3" >To<i style="color:red;"><b>*</b></i></label></td>
                    <td style="width: 400px;"><input type="text" class="form-control datepicker"  name="tgl2" value="" id="tanggal2" readonly></td>
                    <td style="width: 450px;"><label for="inputEmail3">Range Tanggal <b>AFTER</b> From<i style="color:red;"><b>*</b></i></label></td>
                    <td style="width: 400px;"><input type="text" class="form-control datepicker"  name="tgl3" value="" id="tanggal3" readonly></td>
                    <td><label for="inputEmail3">To<i style="color:red;"><b>*</b></i></label></td>
                    <td style="width: 400px;"><input type="text" class="form-control datepicker"  name="tgl4" value="" id="tanggal4" readonly></td>
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
                <table>
                  <tr>
                    <td style="width: 190px;"><label for="inputEmail3" >M/C Type (Opsional)</label></td>
                    <td style="width: 400px;">
                      <div class="input-group" id="filter_mc_type">
                        <!-- <input name='mc_type[]' id='mc_type' type="hidden" disabled> -->
                        <!-- <input type="text" id='ekspedisi' class="form-control" readonly> -->
                        <input type="hidden" :value='filters' name="id_tipe_kendaraan" id='id_tipe_kendaraan'>
                        <input :value='filters.length + " M/C Type"'  type="text" class="form-control" placeholder="Cari Tipe Kendaraan" disabled>
                        <div class="input-group-btn">
                          <button class="btn btn-flat btn-primary" type='button' data-toggle='modal' data-target='#h2_list_fu'><i class="fa fa-search"></i></button>
                        </div>
                      </div>
                      <?php $this->load->view('modal/h2_list_fu'); ?>
                    </td>
                    <script>
                    filter_mc_type = new Vue({
                          el: '#filter_mc_type',
                          data: {
                            filters: []
                          },
                            watch: {
                              filters: function(){
                                // list_fu_table.draw();
                              }
                            }
                        });

                        $("#h2_list_fu").on('change',"input[type='checkbox']",function(e){
                          target = $(e.target);
                          id_tipe_kendaraan = target.attr('data-id_tipe_kendaraan');
                          
                          if(target.is(':checked')){
                            filter_mc_type.filters.push(id_tipe_kendaraan);
                          }else{
                            index_picker = _.indexOf(filter_mc_type.filters, id_tipe_kendaraan);
                            filter_mc_type.filters.splice(index_picker, 1);
                          }
                            // h2_list_fu_table.draw();
                          });
                    </script>
                    <td style="width: 190px;"><label for="inputEmail3" >Pekerjaan (Opsional)</label></td>
                    <td ><select class="form-control select2"  aria-label="Default select example" name="profesi" id="profesi">
                        <option selected disabled>Pilih Profesi</option>
                        <?php foreach($pekerjaan as $kerja) : ?>
                            <option value="<?php echo $kerja->id_pekerjaan?>"><?php echo $kerja->pekerjaan?></option>
                        <?php endforeach; ?>
                    </select></td>
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
      <?php if($grafikActivePassive) {?>
        <button type="button" class="btn btn-xl btn-block btn-success" disabled>Periode <?= $this->input->get('tgl1')?> S.D. <?= $this->input->get('tgl2') ?> <b>(BEFORE)</b></button>
        <div class="row">
          <div class="col-sm-6">
            <div class="box-header with-border " style=" width:1800px;">        
              <div class="row">
                <div class="col-md-4" id="divActivePassive">
                  <div class="box box-warning">
                    <div class="box-header">
                      <i class="fa fa-graphic"></i>
                      <h3 class="login-box-msg" style="font-size: 16px;"><b>Active-Passive Customer</b></h3>
                    </div>
                    <div class="box-body chat">
                      <canvas id="activePassiveCustomer" style="margin-top: -15px;height:330px !important;"></canvas>
                      <?php 
                      $tahun_produksi=null;
                      $active=null;
                      $passive=null;
                          foreach($grafikActivePassive->result() as $row)
                          {
                              // $id_type_email= "";
                              $line = number_format(($row->active/($row->active+$row->passive))*100,0);
                              $jur0=$line;
                              $line2 .= "'$jur0'". ", ";
                              $jur=$row->tahun_produksi;
                              $tahun_produksi .= "'$jur'". ", ";
                              $jur2=$row->active;
                              $active .= "'$jur2'". ", ";
                              $jur3=$row->passive;
                              $passive .= "'$jur3'". ", ";
                          }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="box-header with-border " style=" width:1800px;">        
              <div class="row">
                <div class="col-md-4" id="divFrequencyVisit">
                  <div class="box box-warning">
                    <div class="box-header">
                      <i class="fa fa-graphic"></i>
                      <h3 class="login-box-msg" style="font-size: 16px;"><b>Frequency of Visit</b></h3>
                    </div>
                    <div class="box-body chat">
                      <canvas id="frequencyOfVisit" style="margin-top: -15px;height:330px !important;"></canvas>
                      <?php 
                        $tahun_produksi2=null;
                        $active2="";
                        $line3="";
                          foreach($frequencyOfVisit->result() as $row2)
                          {
                              // $id_type_email= "";
                              // $jur0=$line;
                              $jur0=$row2->total;
                              $line3 .= "'$jur0'". ", ";
                              $jur=$row2->tahun_produksi;
                              $tahun_produksi2 .= "'$jur'". ", ";
                              $jur2=$row2->active;
                              $active2 .= "'$jur2'". ", ";
                              
                            }      
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="box-header with-border " style=" width:1800px;">        
              <div class="row">
                <div class="col-md-4" id="divSalesAbility">
                  <div class="box box-warning">
                    <div class="box-header">
                      <i class="fa fa-graphic"></i>
                      <h3 class="login-box-msg" style="font-size: 16px;"><b>Sales Ability</b></h3>
                    </div>
                    <div class="box-body chat">
                      <canvas id="salesAbility" style="margin-top: -15px;height:330px !important;"></canvas>
                      <?php 
                        $tahun_produksi3=null;
                        $active3="";
                        $line4="";
                          foreach($salesAbility->result() as $row2)
                          {
                              // $id_type_email= "";
                              // $jur0=$line;
                              $jur0=$row2->total;
                              $line4 .= "'$jur0'". ", ";
                              $jur=$row2->tahun_produksi;
                              $tahun_produksi3 .= "'$jur'". ", ";
                              $jur2=$row2->active;
                              $active3 .= "'$jur2'". ", ";
                              
                            }  
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- 22/09/23 HIDE PENDING ITEM BELUM DIGUNAKAN -->
          <!-- <div class="col-sm-6">
            <div class="box-header with-border " style=" width:1800px;">        
              <div class="row">
                <div class="col-md-4" id="divPendingItem">
                  <div class="box box-warning">
                    <div class="box-header">
                      <i class="fa fa-graphic"></i>
                      <h3 class="login-box-msg" style="font-size: 16px;"><b>Pending Item</b></h3>
                    </div>
                    <div class="box-body chat">
                      <canvas id="pendingItem" style="margin-top: -15px;height:330px !important;"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
        </div>
      <?php } else{ ?>
        Mohon Diisi Tanggal
      <?php } ?>
    <?php }?>
    
    <?php if ($chart2) {?>
      <?php if($grafikActivePassiveAfter) {?>
        <button type="button" class="btn btn-xl btn-block btn-success" disabled>Periode <?= $this->input->get('tgl3')?> S.D. <?= $this->input->get('tgl4') ?> <b>(AFTER)</b></button>
        <div class="row">
          <div class="col-sm-6">
            <div class="box-header with-border " style=" width:1800px;">        
              <div class="row">
                <div class="col-md-4" id="divActivePassiveAfter">
                  <div class="box box-warning">
                    <div class="box-header">
                      <i class="fa fa-graphic"></i>
                      <h3 class="login-box-msg" style="font-size: 16px;"><b>Active-Passive Customer</b></h3>
                    </div>
                    <div class="box-body chat">
                      <canvas id="activePassiveCustomerAfter" style="margin-top: -15px;height:330px !important;"></canvas>
                      <?php 
                      $tahun_produksiAfter=null;
                      $activeAfter=null;
                      $passiveAfter=null;
                          foreach($grafikActivePassiveAfter->result() as $row)
                          {
                              // $id_type_email= "";
                              $line = number_format(($row->active/($row->active+$row->passive))*100,0);
                              $jur0=$line;
                              $line2After .= "'$jur0'". ", ";
                              $jur=$row->tahun_produksi;
                              $tahun_produksiAfter .= "'$jur'". ", ";
                              $jur2=$row->active;
                              $activeAfter .= "'$jur2'". ", ";
                              $jur3=$row->passive;
                              $passiveAfter .= "'$jur3'". ", ";
                          }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="box-header with-border " style=" width:1800px;">        
              <div class="row">
                <div class="col-md-4" id="divFrequencyVisitAfter">
                  <div class="box box-warning">
                    <div class="box-header">
                      <i class="fa fa-graphic"></i>
                      <h3 class="login-box-msg" style="font-size: 16px;"><b>Frequency of Visit</b></h3>
                    </div>
                    <div class="box-body chat">
                      <canvas id="frequencyOfVisitAfter" style="margin-top: -15px;height:330px !important;"></canvas>
                      <?php 
                        $tahun_produksi2After=null;
                        $active2After="";
                        $line3After="";
                          foreach($frequencyOfVisitAfter->result() as $row2)
                          {
                              // $id_type_email= "";
                              // $jur0=$line;
                              $jur0=$row2->total;
                              $line3After .= "'$jur0'". ", ";
                              $jur=$row2->tahun_produksi;
                              $tahun_produksi2After .= "'$jur'". ", ";
                              $jur2=$row2->active;
                              $active2After .= "'$jur2'". ", ";
                              
                            }      
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <div class="box-header with-border " style=" width:1800px;">        
              <div class="row">
                <div class="col-md-4" id="divSalesAbilityAfter">
                  <div class="box box-warning">
                    <div class="box-header">
                      <i class="fa fa-graphic"></i>
                      <h3 class="login-box-msg" style="font-size: 16px;"><b>Sales Ability</b></h3>
                    </div>
                    <div class="box-body chat">
                      <canvas id="salesAbilityAfter" style="margin-top: -15px;height:330px !important;"></canvas>
                      <?php 
                        $tahun_produksi3After=null;
                        $active3After="";
                        $line4After="";
                          foreach($salesAbilityAfter->result() as $row2)
                          {
                              // $id_type_email= "";
                              // $jur0=$line;
                              $jur0=$row2->total;
                              $line4After .= "'$jur0'". ", ";
                              $jur=$row2->tahun_produksi;
                              $tahun_produksi3After .= "'$jur'". ", ";
                              $jur2=$row2->active;
                              $active3After .= "'$jur2'". ", ";
                              
                            }  
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- 22/09/23 HIDE PENDING ITEM BELUM DIGUNAKAN -->
          <!-- <div class="col-sm-6">
            <div class="box-header with-border " style=" width:1800px;">        
              <div class="row">
                <div class="col-md-4" id="divPendingItemAfter">
                  <div class="box box-warning">
                    <div class="box-header">
                      <i class="fa fa-graphic"></i>
                      <h3 class="login-box-msg" style="font-size: 16px;"><b>Pending Item</b></h3>
                    </div>
                    <div class="box-body chat">
                      <canvas id="pendingItemAfter" style="margin-top: -15px;height:330px !important;"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
        </div>
      <?php } else{ ?>
        Mohon Diisi Tanggal <b>BEFORE</b>
      <?php } ?>
    <?php }?>
    </body>
  </html>
  </section>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.1.0/chartjs-plugin-datalabels.min.js" integrity="sha512-Tfw6etYMUhL4RTki37niav99C6OHwMDB2iBT5S5piyHO+ltK2YX8Hjy9TXxhE1Gm/TmAV0uaykSpnHKFIAif/A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
  const ctx = document.getElementById('activePassiveCustomer');
  topLabels = {
      id:'topLabels',
      afterDatasetsDraw(chart,args,pluginOptions){
        const { ctx, scales: {x,y} } = chart;
        chart.data.datasets[0].data.forEach((datapoint,index)=> {
          const datasetArray = [];

          chart.data.datasets.forEach((dataset)=> {
            datasetArray.push(dataset.data[index])
          })

          // alert(datasetArray);

          function totalSum(total,values){
            return total+values;
          };

          let sum=datasetArray.reduce(totalSum,0);
          ctx.font = 'bold 12px sans-serif';
          ctx.fillStyle = 'rgba(0, 0, 0, 1)';
          ctx.textAlign = 'center';
          ctx.fillText(sum,x.getPixelForValue(index),chart.getDatasetMeta(1).data[index].y-10);
        })
        
      }
    };
  var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
      labels: [<?php echo $tahun_produksi; ?>],
      datasets: [{
        // axis:'y',
        label:'Active',
        borderWidth: 1,
        borderColor: 'rgba(0, 0, 0, 1)',
        backgroundColor: 'rgba(216, 0, 3, 0.54)',
        data: [<?php echo $active; ?>],
        fill: false,
        yAxisID:'total',
        datalabels:{
          font: {
          weight: 'bold',
          size: 10,
        }
        }
      },
      {
        // axis:'y',
        label:'Passive',
        borderWidth: 1,
        borderColor: 'rgba(0, 0, 0, 1)',
        backgroundColor: 'rgba(39, 213, 245, 0.31)',
        data: [<?php echo $passive; ?>],
        fill: false,
        yAxisID:'total',
        datalabels:{
          font: {
          weight: 'bold',
          size: 10,
        }
        }
      },
      {
        label: '% Active',
        data: [<?php echo $line2; ?>],
        borderColor: 'rgba(255, 252, 0, 1)',
        backgroundColor: 'rgba(253, 119, 1, 1)',
        type: 'line',
        lineTension: 0,
        order: 0,
        yAxisID:'percentage',
        datalabels: {
        display: false,
        // pointStyle: 'circle',
        // pointRadius: 50,
        // pointHoverRadius: 15
    },
      }]
    },
    
    // Configuration options go here
    options: {
        // indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        stacked:true,
        scales: {
            x:{
              stacked:true,
              title: {
                display: true,
                text: 'Tahun Motor'
              }
            },
            // y:{
            //   stacked:true,
            //   // beginAtZero: true
            // },
            total:{
              stacked:true,
              beginAtZero:true,
              type:'linear',
              position:'left',
              title: {
                display: true,
                text: 'Jumlah Customer'
              }
            },
            percentage:{
              beginAtZero:true,
              type:'linear',
              position:'right',
              title: {
                display: true,
                text: 'Persentase Active'
              },
              ticks:{
                callback:function(value,index,values){
                  return `${value} %`
                }
              }
            }
        },
    },
    plugins: [ChartDataLabels]
  });
</script>

<script type="text/javascript">
  const cta = document.getElementById('activePassiveCustomerAfter');
  var chart = new Chart(cta, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
      labels: [<?php echo $tahun_produksiAfter; ?>],
      datasets: [{
        // axis:'y',
        label:'Active',
        borderWidth: 1,
        borderColor: 'rgba(0, 0, 0, 1)',
        backgroundColor: 'rgba(216, 0, 3, 0.54)',
        data: [<?php echo $activeAfter; ?>],
        fill: false,
        yAxisID:'total',
        datalabels:{
          font: {
          weight: 'bold',
          size: 10,
        }
        }
      },
      {
        // axis:'y',
        label:'Passive',
        borderWidth: 1,
        borderColor: 'rgba(0, 0, 0, 1)',
        backgroundColor: 'rgba(39, 213, 245, 0.31)',
        data: [<?php echo $passiveAfter; ?>],
        fill: false,
        yAxisID:'total',
        datalabels:{
          font: {
          weight: 'bold',
          size: 10,
        }
        }
      },
      {
        label: '% Active',
        data: [<?php echo $line2After; ?>],
        borderColor: 'rgba(255, 252, 0, 1)',
        backgroundColor: 'rgba(253, 119, 1, 1)',
        type: 'line',
        lineTension: 0,
        order: 0,
        yAxisID:'percentage',
        datalabels: {
        display: false,
        // pointStyle: 'circle',
        // pointRadius: 50,
        // pointHoverRadius: 15
    },
      }]
    },
    
    // Configuration options go here
    options: {
        // indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        stacked:true,
        scales: {
            x:{
              stacked:true,
              title: {
                display: true,
                text: 'Tahun Motor'
              }
            },
            // y:{
            //   stacked:true,
            //   // beginAtZero: true
            // },
            total:{
              stacked:true,
              beginAtZero:true,
              type:'linear',
              position:'left',
              title: {
                display: true,
                text: 'Jumlah Customer'
              }
            },
            percentage:{
              beginAtZero:true,
              type:'linear',
              position:'right',
              title: {
                display: true,
                text: 'Persentase Active'
              },
              ticks:{
                callback:function(value,index,values){
                  return `${value} %`
                }
              }
            }
        },
    },
    plugins: [ChartDataLabels]
  });
</script>

<script type="text/javascript">
  const cty = document.getElementById('frequencyOfVisit');
  var chart = new Chart(cty, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
      labels: [<?php echo $tahun_produksi2; ?>],
      datasets: [{
        // axis:'y',
        label:'Active',
        borderWidth: 1,
        borderColor: 'rgba(0, 0, 0, 1)',
        backgroundColor: 'rgba(216, 0, 3, 0.54)',
        data: [<?php echo $active2; ?>],
        fill: false,
        yAxisID:'total',
        datalabels:{
          font: {
          weight: 'bold',
          size: 10,
        }
        }
      },
      {
        label: 'Frequency of Visit',
        data: [<?php echo $line3; ?>],
        borderColor: 'rgba(255, 252, 0, 1)',
        backgroundColor: 'rgba(253, 119, 1, 1)',
        type: 'line',
        lineTension: 0,
        order: 0,
        yAxisID:'visit',
        datalabels: {
        display: false,
        // pointStyle: 'circle',
        // pointRadius: 50,
        // pointHoverRadius: 15
    },
      }]
    },
    
    // Configuration options go here
    options: {
        // indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x:{
              title: {
                display: true,
                text: 'Tahun Motor'
              }
            },
            total:{
              beginAtZero:true,
              type:'linear',
              position:'left',
              title: {
                display: true,
                text: 'Jumlah Customer'
              }
            },
            visit:{
              beginAtZero:true,
              type:'linear',
              position:'right',
              title: {
                display: true,
                text: 'Frequecy of Visit'
              },
            }
        },
    },
    plugins: [ChartDataLabels]
  });
</script>

<script type="text/javascript">
  const ctb = document.getElementById('frequencyOfVisitAfter');
  var chart = new Chart(ctb, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
      labels: [<?php echo $tahun_produksi2After; ?>],
      datasets: [{
        // axis:'y',
        label:'Active',
        borderWidth: 1,
        borderColor: 'rgba(0, 0, 0, 1)',
        backgroundColor: 'rgba(216, 0, 3, 0.54)',
        data: [<?php echo $active2After; ?>],
        fill: false,
        yAxisID:'total',
        datalabels:{
          font: {
          weight: 'bold',
          size: 10,
        }
        }
      },
      {
        label: 'Frequency of Visit',
        data: [<?php echo $line3After; ?>],
        borderColor: 'rgba(255, 252, 0, 1)',
        backgroundColor: 'rgba(253, 119, 1, 1)',
        type: 'line',
        lineTension: 0,
        order: 0,
        yAxisID:'visit',
        datalabels: {
        display: false,
        // pointStyle: 'circle',
        // pointRadius: 50,
        // pointHoverRadius: 15
    },
      }]
    },
    
    // Configuration options go here
    options: {
        // indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x:{
              title: {
                display: true,
                text: 'Tahun Motor'
              }
            },
            total:{
              beginAtZero:true,
              type:'linear',
              position:'left',
              title: {
                display: true,
                text: 'Jumlah Customer'
              }
            },
            visit:{
              beginAtZero:true,
              type:'linear',
              position:'right',
              title: {
                display: true,
                text: 'Frequecy of Visit'
              },
            }
        },
    },
    plugins: [ChartDataLabels]
  });
</script>

<script type="text/javascript">
  const ctz = document.getElementById('salesAbility');
  var chart = new Chart(ctz, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
      labels: [<?php echo $tahun_produksi3; ?>],
      datasets: [{
        // axis:'y',
        label:'Active',
        borderWidth: 1,
        borderColor: 'rgba(0, 0, 0, 1)',
        backgroundColor: 'rgba(216, 0, 3, 0.54)',
        data: [<?php echo $active3; ?>],
        fill: false,
        yAxisID:'total',
        datalabels:{
          font: {
          weight: 'bold',
          size: 10,
        }
        }
      },
      {
        label: 'Sales Ability',
        data: [<?php echo $line4; ?>],
        borderColor: 'rgba(255, 252, 0, 1)',
        backgroundColor: 'rgba(253, 119, 1, 1)',
        type: 'line',
        lineTension: 0,
        order: 0,
        yAxisID:'ability',
        datalabels: {
        display: false,
        // pointStyle: 'circle',
        // pointRadius: 50,
        // pointHoverRadius: 15
    },
      }]
    },
    
    // Configuration options go here
    options: {
        // indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x:{
              title: {
                display: true,
                text: 'Tahun Motor'
              }
            },
            total:{
              beginAtZero:true,
              type:'linear',
              position:'left',
              title: {
                display: true,
                text: 'Jumlah Customer'
              }
            },
            ability:{
              beginAtZero:true,
              type:'linear',
              position:'right',
              title: {
                display: true,
                text: 'Sales Ability'
              },
            }
        },
    },
    plugins: [ChartDataLabels]
  });
</script>

<script type="text/javascript">
  const ctc = document.getElementById('salesAbilityAfter');
  var chart = new Chart(ctc, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
      labels: [<?php echo $tahun_produksi3After; ?>],
      datasets: [{
        // axis:'y',
        label:'Active',
        borderWidth: 1,
        borderColor: 'rgba(0, 0, 0, 1)',
        backgroundColor: 'rgba(216, 0, 3, 0.54)',
        data: [<?php echo $active3After; ?>],
        fill: false,
        yAxisID:'total',
        datalabels:{
          font: {
          weight: 'bold',
          size: 10,
        }
        }
      },
      {
        label: 'Sales Ability',
        data: [<?php echo $line4After; ?>],
        borderColor: 'rgba(255, 252, 0, 1)',
        backgroundColor: 'rgba(253, 119, 1, 1)',
        type: 'line',
        lineTension: 0,
        order: 0,
        yAxisID:'ability',
        datalabels: {
        display: false,
        // pointStyle: 'circle',
        // pointRadius: 50,
        // pointHoverRadius: 15
    },
      }]
    },
    
    // Configuration options go here
    options: {
        // indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x:{
              title: {
                display: true,
                text: 'Tahun Motor'
              }
            },
            total:{
              beginAtZero:true,
              type:'linear',
              position:'left',
              title: {
                display: true,
                text: 'Jumlah Customer'
              }
            },
            ability:{
              beginAtZero:true,
              type:'linear',
              position:'right',
              title: {
                display: true,
                text: 'Sales Ability'
              },
            }
        },
    },
    plugins: [ChartDataLabels]
  });
</script>

<script>
  $('#reset').click(function () {
    window.location = "<?php echo base_url('h2/h2_md_overview_customer/')?>"; 
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

    $("#tanggal3").datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    }).on('changeDate', function (selected) {
      var minDate = new Date(selected.date);
      var maxDate = new Date(selected.date);
      minDate.setDate(minDate.getDate());
      maxDate.setDate(maxDate.getDate()+30);
      $('#tanggal4').datepicker('setStartDate', minDate);
      $('#tanggal4').datepicker('setEndDate', maxDate);
    });
 
    $("#tanggal4").datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
    }).on('changeDate', function (selected) {
      var minDate = new Date(selected.date);
      // var maxDate = new Date(selected.date+30);
      minDate.setDate(minDate.getDate());
      // maxDate.setDate(maxDate.getDate());
      $('#tanggal3').datepicker('setEndDate', minDate);
    });
    
    
  });
</script>

<script type="text/javascript">
  const ctw = document.getElementById('pendingItem');
  var chart = new Chart(ctw, {
    // The type of chart we want to create
    type: 'pie',
    // The data for our dataset
    data: {
      labels: ['Item 1','Item 2','Item 3'],
      datasets: [{
          label:'Pending Item',
          borderWidth: 1,
          data: ['10','30','70'],
          datalabels:{
          font: {
          weight: 'bold',
          size: 10,
        }
        }
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
        
    },
    plugins: [ChartDataLabels]
  });
</script>

<script type="text/javascript">
  const ctd = document.getElementById('pendingItemAfter');
  var chart = new Chart(ctd, {
    // The type of chart we want to create
    type: 'pie',
    // The data for our dataset
    data: {
      labels: ['Item 1','Item 2','Item 3'],
      datasets: [{
          label:'Pending Item',
          borderWidth: 1,
          data: ['10','30','70'],
          datalabels:{
          font: {
          weight: 'bold',
          size: 10,
        }
        }
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
        
    },
    plugins: [ChartDataLabels]
  });
</script>

<?php }?>