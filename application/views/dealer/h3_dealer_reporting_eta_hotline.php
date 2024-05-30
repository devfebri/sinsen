<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 25px;
  padding-left: 4px;
  padding-right: 4px;  
}
.vertical-text{
  writing-mode: lr-tb;
  text-orientation: mixed;
}
.rotate {
  -webkit-transform: rotate(-90deg);
  -moz-transform: rotate(-90deg);
}
#mySpan{
  writing-mode: vertical-lr; 
  transform: rotate(180deg);
}
</style>

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
    <div class="box box-default">
      <div class="box-header with-border">        
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" id="frm" method="post" action= "dealer/h3_dealer_reporting_eta_hotline/downloadReport" enctype="multipart/form-data">
              <div class="box-body">                                                              
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control datepicker" name="tgl1" value="<?= date('Y-m-d') ?>" id="tanggal1">
                  </div>  
                  <label for="inputEmail3" class="col-sm-1 control-label">End Date</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control datepicker" name="tgl2" value="<?= date('Y-m-d') ?>" id="tanggal2">
                  </div>                                     
                </div>   
                <!-- <div class="form-group">
                <div class="col-sm-2">
                    <button type="submit" name="process" value="excel" class="btn bg-maroon btn-block btn-flat"><i class="fa fa-download"></i> Download .xls</button>                                                      
                  </div>    -->
  		            <!-- <div class="col-sm-2">
                    <button type="submit" name="process" value="csv" class="btn bg-blue btn-block btn-flat"><i class="fa fa-download"></i> Download .csv</button>                                                      
                  </div> -->
                <!-- </div>              -->
              </div><!-- /.box-body -->              
              <div class="box-footer">
              <div class="col-sm-12" align="center">
                <button type="submit" id="submitBtn" name="process" value="excel"  class="btn btn-info btn-flat"><i class="fa fa-download"></i> Download .xls</button>
                <!-- <button type="submit" id="btnLoad"  name="process" value="load"  class="btn btn-primary btn-flat"><i class="fa fa-eye"></i> Load</button> -->
              </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
    <?php if($dashboard){?>
      <?php if($report) {?> 
        <button type="button" class="btn btn-xl btn-block btn-success" disabled>Dashboard Performance HO % ETA Accuracy </button>
      <br> 
        
      <?php 
      $total_ontime = 0;
      $total_delay = 0;
      $avg_ontime = 0;
      $avg_delay = 0;
      foreach($report->result() as $row){
        // $history_hotline = $this->db->query("SELECT po_id, (CASE WHEN eta_revisi is null and (eta != '' or eta != '0000-00-00') then DATE_FORMAT(eta,'%d-%m-%Y') WHEN eta_revisi is not null then DATE_FORMAT(eta_revisi,'%d-%m-%Y') else '' end) as eta_terlama from tr_h3_md_history_estimasi_waktu_hotline where po_id = '$row->po_id' and id_part='$row->id_part' 
		    //   and created_at = (SELECT max(created_at) from tr_h3_md_history_estimasi_waktu_hotline 
				// 	where po_id = '$row->po_id' and id_part='$row->id_part') LIMIT 1")->row();

        $history_hotline = $this->db->query("SELECT po_id, (CASE WHEN eta_revisi is null and (eta_terlama != '' or eta_terlama != '0000-00-00') then eta_terlama WHEN eta_revisi is not null then eta_revisi else eta_terlama end) as eta_terlama from tr_h3_dealer_purchase_order_parts where po_id = '$row->po_id' and id_part='$row->id_part' ")->row();

          $good_receipt = $this->db->query("SELECT sum(case when $history_hotline->eta_terlama<=left(a.created_at,10) then 1 else 0 end) as hitung_ontime, sum(case when $history_hotline->eta_terlama>=left(a.created_at,10) then 1 else 0 end) as hitung_delay FROM tr_h3_dealer_good_receipt a JOIN tr_h3_dealer_good_receipt_parts b WHERE a.nomor_po = '$row->po_id' and b.id_part_int='$row->id_part_int'")->row();
          $total_ontime += $good_receipt->hitung_ontime;
          $total_delay += $good_receipt->hitung_delay;
      }
      $total_row = $report->num_rows();
      if($total_row >0){
        $avg_ontime = ($total_ontime/$total_row)*100;
        $avg_delay = ($total_delay/$total_row)*100;
      }else {
        $avg_ontime=0;
        $avg_delay =0;
      }
      
      ?>
        <div class="row"  align="center">
            <div class="col-sm-6">
              <div class="small-box bg-green">
                <div class="inner">
                  <!-- <h5>% OnTime</h5> -->
                  <div style="font-weight:bold; font-size:20px; margin-top:10px;">% On Time</div>
                  <p><?php echo $avg_ontime .' %';?></p>
                </div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="small-box bg-yellow">
                <div class="inner">
                  <!-- <h5>% Delay</h5> -->
                  <div style="font-weight:bold; font-size:20px; margin-top:10px;">% Delay</div>
                  <p><?php echo  $avg_delay .' %'?></p>
                </div>
              </div>
            </div>
          </div>
        </div>

      <?php }?>
    <?php }?>
    </body>
  </html>
  </section>
</div>
<?php }?>