<?php
if ($set == "view") {
?>
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
        <li class="">Laporan</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="box box-default">
        <div class="box-header with-border">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" id="frm" method="post" enctype="multipart/form-data">
                <div class="box-body" style="padding-bottom:0px">
                  <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Start Date</label>
                    <div class="col-sm-3">
                      <input class="form-control datepicker" id="start_date" readonly />
                    </div>
                    <label for="inputEmail3" class="col-sm-2 control-label">End Date</label>
                    <div class="col-sm-3">
                      <input class="form-control datepicker" id="end_date" readonly />
                    </div>
                    
                  </div>
                  <div class="form-group">
                       <label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>
                    <div class="col-sm-5">
                     <select name="dealer" id="dealer" class="form-control select2">
                         <option value="">Choose</option>
                         <option value="all">All Dealers</option>
                         <?php foreach($dealer as $rows):?>
                            <option value="<?=$rows->id_dealer?>"><?=$rows->kode_dealer_ahm?> | <?=$rows->nama_dealer?></option>
                         <?php endforeach;?>
                     </select>
                    </div>
                  </div>
                  <div class="form-group" style="border-top:1px solid #f4f4f4">
                    <div class="col-sm-12" align="center" style="padding-top:10px">
                      <button type="button" onclick="getReport('preview')" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>
                      <button type="button" onclick="getReport('download')" name="process" value="edit" class="btn bg-blue btn-flat"><i class="fa fa-download"></i> Download .xls</button>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                  <div style="min-height: 600px">
                    <iframe style="overflow: auto; border: 0px solid #fff; width: 100%; height: 602px;margin-bottom: -5px;" id="showReport"></iframe>
                  </div>
                </div>
              </form>
            </div>

          </div>
        </div>
      </div><!-- /.box -->

      <script>
        function getReport(tipe) {
          var value = {
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            id_dealer:$('#dealer').val(),
            kpb: $('#kpb').val(),
            tipe: tipe,
            cetak: 'cetak',
          }

          if (value.end_date == '' || value.start_date == '' || value.id_dealer == '') {
            alert('Isi data dengan lengkap ..!');
            return false;
          } else {
            let values = JSON.stringify(value);
            $('.loader').show();
            $('#btnShow').disabled;
            $("#showReport").attr("src", '<?php echo site_url("h2/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
            document.getElementById("showReport").onload = function(e) {
              $('.loader').hide();
            };
          }
        }
        
      </script>

    </section>
  </div>
<?php } elseif ($set == 'cetak') {
  if ($params->tipe == 'download') {
    header("Content-type: application/octet-stream");
    $file_name = remove_space($title, '_') . '.xls';
    header("Content-Disposition: attachment; filename=$file_name.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
  }
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Cetak</title>
    <style>
      @media print {
        @page {
          sheet-size: 330mm 210mm;
          margin-left: 0.8cm;
          margin-right: 0.8cm;
          margin-bottom: 1cm;
          margin-top: 1cm;
        }

        .text-center {
          text-align: center;
        }

        .bold {
          font-weight: bold;
        }

        .table {
          width: 100%;
          max-width: 100%;
          border-collapse: collapse;
          /*border-collapse: separate;*/
        }

        .table-bordered tr td {
          border: 0.01em solid black;
          padding-left: 5px;
          padding-right: 3px;
        }

        body {
          font-family: "Arial";
          font-size: 10pt;
        }
      }
    </style>
  </head>

  <body>
    <table>
      <tr>
        <td colspan="19"><?= kop_surat_dealer($params->id_dealer); ?></td>
      </tr>
    </table>
    <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>
    <div style="text-align: center;font-size: 11pt"><b>Periode : <?= date_dmy($params->start_date) . ' s/d ' . date_dmy($params->end_date) ?></b></div>
    <br>
    <br>
     <div style="text-align: right;font-size: 11pt;font-weight:normal;">Dicetak pada : <?php echo tgl_indo(date('Y-m-d'))?> <?php echo date('H:i:s')?></div>
   
    <hr>
    <table>
        <tr>
            <th colspan="19" style="text-align: left;font-size: 11pt"><b>I. Jumlah Job dan unit kendaraan yang dikerjakan</b></th>
        </tr>
    </table>
    <table class="table table-bordered" border=1>
      <tr>
        <th rowspan="2" colspan="5" style="text-align: center;font-size: 11pt"><b>TIPE MOTOR</b></th>
        <th colspan="4" style="text-align: center;font-size: 11pt"><b>ASS</b></th>
        <th rowspan="2" style="text-align: center;font-size: 11pt"><b>CLAIM</b></th>
        <th colspan="4" style="text-align: center;font-size: 11pt"><b>QUICK SERVICE</b></th>
        <th rowspan="2" style="text-align: center;font-size: 11pt" width="5%"><b>HR</b></th>
        <th rowspan="2" style="text-align: center;font-size: 11pt" width="5%"><b>JR</b></th>
        <th rowspan="2" style="text-align: center;font-size: 11pt" width="5%"><b>OTHER</b></th>
        <th rowspan="2" style="text-align: center;font-size: 11pt" width="5%"><b>TOTAL JOB</b></th>
        <th rowspan="2" style="text-align: center;font-size: 11pt" width="5%"><b>UNIT ENTRY</b></th>
      </tr>
      <tr>
          <th style="text-align: center;font-size: 11pt"><b>1</b></th>
          <th style="text-align: center;font-size: 11pt"><b>2</b></th>
          <th style="text-align: center;font-size: 11pt"><b>3</b></th>
          <th style="text-align: center;font-size: 11pt"><b>4</b></th>
          <th style="text-align: center;font-size: 11pt"><b>CS</b></th>
          <th style="text-align: center;font-size: 11pt"><b>LS</b></th>
          <th style="text-align: center;font-size: 11pt"><b>OR+</b></th>
          <th style="text-align: center;font-size: 11pt"><b>LR</b></th>
      </tr>
      <?php
        $kpb1=array();
        $kpb2=array();
        $kpb3=array();
        $kpb4=array();
        $claim=array();
        $cs=array();
        $ls=array();
        $or=array();
        $lr=array();
        $hr=array();
        $jr=array();
        $other=array();
        $job=array();
        $ue=array();
      ?>
      <?php foreach($details as $rows):?>
      <?php 
      $date = 0;
      
      $id = $params->id_dealer;
      
      $nomesin= $this->db->query("select count(c.id_work_order) as total_wo from ms_customer_h23 a 
      join tr_h2_sa_form b on a.id_customer=b.id_customer join tr_h2_wo_dealer c on b.id_sa_form=c.id_sa_form where 
      c.id_dealer='$id' and c.status='Closed' and a.id_tipe_kendaraan='$rows->tipe_kendaraan' and left(c.created_at,10) between '{$params->start_date}' and '{$params->end_date}'")->row(); 
	 ?>
		
	 
      <?php
        $kpb1[] = intval($rows->total_ass1);
        $kpb2[] = intval($rows->total_ass2);
        $kpb3[] = intval($rows->total_ass3);
        $kpb4[] = intval($rows->total_ass4);
        $claim[] = intval($rows->total_claim);
        $cs[] = intval($rows->total_cs);
        $ls[] = intval($rows->total_ls);
        $or[] = intval($rows->total_or);
        $lr[] = intval($rows->total_lr);
        $hr[] = intval($rows->total_hr);
        $jr[] = intval($rows->total_jr);
        $other[] = intval($rows->total_other);
        $job[] = intval($rows->total_job);
        $ue[] = intval($nomesin->total_wo);
      ?>
     <tr>
         <td colspan="5" style="text-align: left;font-size: 11pt"><?=$rows->tipe_kendaraan?> - <?=$rows->deskripsi?></td>
         <td style="text-align: center;font-size: 11pt"><?=$rows->total_ass1?></td>
         <td style="text-align: center;font-size: 11pt"><?=$rows->total_ass2?></td>
         <td style="text-align: center;font-size: 11pt"><?=$rows->total_ass3?></td>
         <td style="text-align: center;font-size: 11pt"><?=$rows->total_ass4?></td>
         <td style="text-align: center;font-size: 11pt"><?=$rows->total_claim?></td>
         <td style="text-align: center;font-size: 11pt"><?=$rows->total_cs?></td>
         <td style="text-align: center;font-size: 11pt"><?=$rows->total_ls?></td>
         <td style="text-align: center;font-size: 11pt"><?=$rows->total_or?></td>
         <td style="text-align: center;font-size: 11pt"><?=$rows->total_lr?></td>
         <td style="text-align: center;font-size: 11pt"><?=$rows->total_hr?></td>
         <td style="text-align: center;font-size: 11pt"><?=$rows->total_jr?></td>
         <td style="text-align: center;font-size: 11pt"><?=$rows->total_other?></td>
         <td style="text-align: center;font-size: 11pt"><?=$rows->total_job?></td>
         
         <td style="text-align: center;font-size: 11pt">
           <?=$nomesin->total_wo?>
         </td>
     </tr>
     <?php endforeach;?>
     <tr>
         <td colspan="5" style="text-align: center;font-size: 11pt"><b>TOTAL</b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($kpb1)?></b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($kpb2)?></b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($kpb3)?></b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($kpb4)?></b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($claim)?></b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($cs)?></b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($ls)?></b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($or)?></b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($lr)?></b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($hr)?></b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($jr)?></b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($other)?></b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($job)?></b></td>
         <td style="text-align: center;font-size: 11pt"><b><?=array_sum($ue)?></b></td>
     </tr>
    </table>
    <br>
    <br>
     <table>
        <tr>
            <th colspan="12" style="text-align: left;font-size: 11pt" width="60%"><b>II. Penjualan Jasa</b></th>
            <th colspan="7" style="text-align: left;font-size: 11pt" ><b>IV. Penjualan Sparepart Direct/Langsung</b></th>
        </tr>
        <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;a.</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3">Jasa / Ongkos Kerja</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:0px;"><?=number_format($jasa->ongkos_kerja,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >a. Sparepart</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"  width="5px">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:55px;"><?=number_format($part->spart,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
        </tr>
        <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;b.</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3">ASS</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-si2e: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:0px;"><?=number_format($jasa->ass,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >b. Oli</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" width="5px">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:55px;"><?=number_format($part->oil,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
        </tr>
          <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;c.</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3">Claim</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:0px;"><?=number_format($jasa->claim,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >c. Busi</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"  width="5px">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:55px;"><?=number_format($part->busi,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
        </tr>
        <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;d.</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3">Other / Lain-lain</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:0px;"><?=number_format($jasa->other,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >d. Tire</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"  width="5px">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:55px;"><?=number_format($part->tire,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
        </tr>
         <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3"><b>Total</b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:0px;"><?=number_format(($jasa->ongkos_kerja + $jasa->ass + $jasa->claim + $jasa->other),0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >e. Vbelt</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"  width="5px">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:55px;"><?=number_format($part->belt,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
        </tr>
         <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >f. Battery</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"  width="5px">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:55px;"><?=number_format($part->battery,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
          <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >g. Brake</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"  width="5px">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:55px;"><?=number_format($part->brake,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
        </tr>
         <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >h. Other / Lain-lain</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"  width="5px">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:55px;"><?=number_format($part->other,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
        </tr>
          <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal">&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;padding-left:50px;font-weight:normal" colspan="3" ><b>Total</b></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"  width="5px">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="2"> <b style="margin-left:55px;"><?=number_format(($part->spart + $part->oil + $part->busi + $part->tire + $part->belt + $part->battery + $part->brake + $part->other),0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <br>
    <table>
        <tr>
            <th colspan="12" style="text-align: left;font-size: 11pt" width="60%"><b>III. Penjualan Sparepart Service</b></th>
            <th colspan="7" style="text-align: left;font-size: 11pt" ><b>&nbsp;</b></th>
        </tr>
      
        <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;a.</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3">Sparepart</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="3"> <b style="margin-left:55px;"><?=number_format($part_wo->spart,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >&nbsp;</th>
            <th style="text-align: left;font-si2e: 11pt;font-weight:normal" colspan="4"> <b style="margin-left:55px;">&nbsp;</b></th>
        </tr>
        <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;b.</th>
          <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3">Oli Reguler</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="3"> <b style="margin-left:55px;"><?=number_format($part_wo->oil,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"> <b style="margin-left:55px;">&nbsp;</b></th>
        </tr>
        <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;c.</th>
          <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3">Oli KPB</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="3"> <b style="margin-left:55px;"><?=number_format($part_wo->oil_kpb,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"> <b style="margin-left:55px;">&nbsp;</b></th>
        </tr>
          <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;d.</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3">Busi</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="3"> <b style="margin-left:55px;"><?=number_format($part_wo->busi,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"> <b style="margin-left:55px;">&nbsp;</b></th>
        </tr>
        <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;e.</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3">Tire</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="3"> <b style="margin-left:55px;"><?=number_format($part_wo->tire,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"> <b style="margin-left:55px;">&nbsp;</b></th>
        </tr>
         <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;f.</th>
           <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3">Vbelt</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="3"> <b style="margin-left:55px;"><?=number_format($part_wo->belt,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"> <b style="margin-left:55px;">&nbsp;</b></th>
        </tr>
         <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;g.</th>
           <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3">Battery</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="3"> <b style="margin-left:55px;"><?=number_format($part_wo->battery,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"> <b style="margin-left:55px;">&nbsp;</b></th>
        </tr>
          <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;h.</th>
          <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3">Brake</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="3"> <b style="margin-left:55px;"><?=number_format($part_wo->brake,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal">&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" ><b>Pendapatan AHASS</b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="3"> <b style="margin-left:55px;"><?=number_format(($part_wo->spart + $part_wo->oil + $part_wo->busi + $part_wo->tire + $part_wo->belt + $part_wo->battery + $part_wo->brake + $part_wo->other + ($part->spart + $part->oil + $part->busi + $part->tire + $part->belt + $part->battery + $part->brake + $part->other) + ($jasa->ongkos_kerja + $jasa->ass + $jasa->claim + $jasa->other)),0,',','.')?></b></th>
          
        </tr>
         <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;i.</th>
           <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3">Other / Lain-lain</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="3"> <b style="margin-left:55px;"><?=number_format($part_wo->other,0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"> <b style="margin-left:55px;">&nbsp;</b></th>
        </tr>
          <tr>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="3"><b>Total</b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal">:</th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal" colspan="3"> <b style="margin-left:55px;"><?=number_format(($part_wo->spart + $part_wo->oil + $part_wo->busi + $part_wo->tire + $part_wo->belt + $part_wo->battery + $part_wo->brake + $part_wo->other),0,',','.')?></b></th>
            <th style="text-align: right;font-size: 11pt;font-weight:normal"></th>
            <th colspan="2"></th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal"></th>
            <th style="text-align: left;font-size: 11pt;padding-left:35px;font-weight:normal" colspan="3" >&nbsp;</th>
            <th style="text-align: left;font-size: 11pt;font-weight:normal" colspan="4"></th>
        </tr>
    </table>
    
  </body>
   
  </html>
<?php } ?>