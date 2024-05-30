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
        <li class="">H2</li>
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
                      <!--<button type="button" onclick="getReport('preview')" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>-->
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
    header("Content-Disposition: attachment; filename=$file_name");
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
          padding-right: 5px;
        }

        body {
          font-family: "Arial";
          font-size: 10pt;
        }
      }
    </style>
  </head>

  <body>
    <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>
    <div style="text-align: center;font-size: 11pt"><b>Periode : <?= date_dmy($params->start_date) . ' s/d ' . date_dmy($params->end_date) ?></b></div>
    <br>
    <br>
     <div style="text-align: right;font-size: 11pt;font-weight:normal;">Dicetak pada : <?php echo tgl_indo(date('Y-m-d'))?> <?php echo date('H:i:s')?></div>
    <hr>
 
    <table class="table table-bordered" border=1>
        <tr style="background-color:yellow">
            <th rowspan="5">No</th>
            <th rowspan="5" width="100px">KARESIDENAN / WILAYAH</th>
            <th rowspan="5">NO. H2</th>
            <th rowspan="5" width="150px">NAME OF AHASS</th>
        </tr>
       
        <tr >
            <th colspan="13" style="background-color:yellow" >TYPE OF JOB</th>
            <th rowspan="2" width="50px" style="background-color:pink">CUST. VISIT</th>
            <th colspan="9" rowspan="2" style="background-color:#ADD8E6">&nbsp;</th>
        </tr>
        <tr>
            <th colspan="13" style="background-color:yellow">&nbsp;</th>
        </tr>
        <tr >
            <th colspan="5" style="background-color:yellow">ASS</th>
            <th rowspan="2" style="background-color:yellow" width="50px">JOB RETURN</th>
            <th rowspan="2" style="background-color:yellow">CLAIM</th>
            <th colspan="3" style="background-color:yellow">QS</th>
            <th rowspan="2" style="background-color:yellow">LR</th>
            <th rowspan="2" style="background-color:yellow">HR</th>
            <th rowspan="2" style="background-color:yellow">TOTAL</th>
            <th rowspan="2" style="background-color:pink">TOTAL</th>
            <th rowspan="2" style="background-color:#87CEFA">ASS</th>
            <th rowspan="2" style="background-color:#87CEFA">QS</th>
            <th rowspan="2" style="background-color:#87CEFA">LR</th>
            <th rowspan="2" style="background-color:#87CEFA">HR</th>
            <th rowspan="2" style="background-color:yellow">TOTAL</th>
            <th rowspan="2" width="80px" style="background-color:#87CEFA">NON DIRECT</th>
            <th rowspan="2" style="background-color:#87CEFA">OLI</th>
            <th rowspan="2" style="background-color:#87CEFA">LAIN2</th>
            <th rowspan="2" style="background-color:yellow">TOTAL</th>
        </tr>
        <tr>
            <th style="text-align:right;background-color:yellow">1</th>
            <th style="text-align:right;background-color:yellow">2</th>
            <th style="text-align:right;background-color:yellow">3</th>
            <th style="text-align:right;background-color:yellow">4</th>
            <th style="background-color:yellow" >TOTAL</th>
            <th style="background-color:yellow">PL</th>
            <th style="background-color:yellow">PR</th>
            <th style="background-color:yellow">GO</th>
        </tr>
         <tr>
            <th colspan="27" style="background-color:red">&nbsp;</th>
        </tr>
        <?php 
            $index=1;
        ?>
        <?php foreach($detail->result() as $rows){ 
          $typeJob= $this->db->query("select  
					IFNULL(SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='ASS1' then 1 ELSE 0 end ),0)as total_ass1, 
					IFNULL(SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='ASS2' then 1 ELSE 0 end ),0)as total_ass2,
					IFNULL(SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='ASS3' then 1 ELSE 0 end ),0)as total_ass3,
					IFNULL(SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='ASS4' then 1 ELSE 0 end ),0)as total_ass4,
					IFNULL(SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='CS' then 1 ELSE 0 end ),0)as total_cs,
					IFNULL(SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='LS' then 1 ELSE 0 end ),0)as total_ls,
					IFNULL(SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='OR+' then 1 ELSE 0 end ),0)as total_or,
					IFNULL(SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='LR' then 1 ELSE 0 end ),0)as total_lr,
					IFNULL(SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='HR' then 1 ELSE 0 end ),0)as total_hr,
					IFNULL(SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type='JR' then 1 ELSE 0 end ),0)as total_jr,
					IFNULL(SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type in('C2','C1','PUD') then 1 ELSE 0 end ),0)as total_claim,
					IFNULL(SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan and e.id_type in('OTHER') then 1 ELSE 0 end ),0)as total_other,
					IFNULL(SUM(CASE WHEN c.id_tipe_kendaraan=c.id_tipe_kendaraan THEN 1 else 0 end),0) as total_job,
					IFNULL(SUM(CASE WHEN a.status='Closed' then 1 else 0 end),0) as total_entri
				from tr_h2_wo_dealer a left join tr_h2_sa_form b on a.id_sa_form=b.id_sa_form
				left join ms_customer_h23 c on b.id_customer=c.id_customer join tr_h2_wo_dealer_pekerjaan d 
				on a.id_work_order=d.id_work_order left join ms_h2_jasa e on e.id_jasa=d.id_jasa left join ms_ptm f on c.id_tipe_kendaraan=f.tipe_marketing where a.id_dealer='$rows->id_dealer' 
				and a.status='Closed' and left(a.created_at,10) between '$params->start_date' and '$params->end_date'")->row();
		   $totalAmountJob = $this->db->query("				
				SELECT 
					IFNULL(SUM(CASE WHEN A.id_type in ('ASS1','ASS2','ASS3','ASS4') THEN B.harga ELSE 0 END),0) AS ass, 
					IFNULL(SUM(CASE WHEN A.id_type in ('CS','LS','OR+') THEN B.harga ELSE 0 END),0) AS qs,
					IFNULL(SUM(CASE WHEN A.id_type in ('LR') THEN B.harga ELSE 0 END),0) AS LR,
					IFNULL(SUM(CASE WHEN A.id_type in ('HR') THEN B.harga ELSE 0 END),0) AS HR,	
					IFNULL(SUM(CASE WHEN A.id_type in('C2','C1','PUD') THEN B.harga ELSE 0 END),0) AS CLAIM,	
					IFNULL(SUM(CASE WHEN A.id_type in ('ASS1','ASS2','ASS3','ASS4','CS','LS','OR+','LR','HR') THEN B.harga ELSE 0 END),0) AS TOTAL	
				FROM tr_h2_wo_dealer_pekerjaan B 
				JOIN ms_h2_jasa A ON A.id_jasa=B.id_jasa JOIN tr_h2_wo_dealer C 
				ON B.id_work_order=C.id_work_order WHERE C.status='Closed'
				AND LEFT(C.created_at,10) BETWEEN '$params->start_date' AND '$params->end_date' and C.id_dealer ='$rows->id_dealer'")->row();
		    $totalPart = $this->db->query("select 
					IFNULL(SUM(CASE WHEN A.kelompok_part in('AH','AHM','BB','BBIST','CABLE','CB','CCKIT','CDKGP',
					'CH','CHAIN','COMP','COOL','CRKIT','DISK','EC','ELECT','EP','EPMTI','ET','GS','GSA','GSB','GST','HM','HNMTI',
					'IMPOR','INS','ISTC','LGS','LSIST','MF','MTI','MUF','N','NF','OAHM1','OAHM2','OC','OFCC','OINS','OKGD',
					'OMTI','ORPL','OSEAL','OTHER','PLAST','PSKIT','PSTN','RBR','RIMWH','RPIST','RSKIT','SD','SDN','SDN2','SDT','SE',
					'SPGUI','SPOKE','STR','TB','TBHGP','TDI','VALVE','VV','BM1','BR','BRNG','BRNG2','BRNG3','BS','CD','CDKIT','DIHVL',
					'EPHVL','HAH','HPLAS','HRW','HSD','OISTC','OTHR','PAINT','PT','RPHVL','RW','RW2','RW3','RWHVL','SA','SAOIL','SHOCK',
					'TAS','TB1','TBVL','ACG','ACB') then B.harga_beli ELSE 0 END),0) AS spart,
					IFNULL(SUM(CASE WHEN A.kelompok_part in('SPLUR','SP','SPLUG','SP2') then B.harga_beli ELSE 0 END),0) AS busi,
					IFNULL(SUM(CASE WHEN A.kelompok_part in('TIRE','TR','TIRE1') then B.harga_beli ELSE 0 END),0) AS tire,
					IFNULL(SUM(CASE WHEN A.kelompok_part in('BLDRV') then B.harga_beli ELSE 0 END),0) AS belt,
					IFNULL(SUM(CASE WHEN A.kelompok_part in('BATT','BT') then B.harga_beli ELSE 0 END),0) AS battery,
					IFNULL(SUM(CASE WHEN A.kelompok_part in('BRAKE','PS') then B.harga_beli ELSE 0 END),0) AS brake,
					IFNULL(SUM(CASE WHEN A.kelompok_part in('ACCEC','FKT','TL','FED OIL','ACC','HELM','PA') then B.harga_beli ELSE 0 END),0) AS other,
					IFNULL(SUM(CASE WHEN A.kelompok_part in('GMO','FLUID','OIL') then B.harga_beli ELSE 0 END),0) AS oil
				 from tr_h23_nsc_parts B JOIN ms_part A ON A.id_part=B.id_part join tr_h23_nsc C ON C.no_nsc=B.no_nsc where  
				 C.tgl_nsc BETWEEN '$params->start_date' AND '$params->end_date' and C.id_dealer ='$rows->id_dealer'")->row();
		   
        ?>
        <tr>
            <td style="text-align:center"><?=$index?></td>
            <td><?=$rows->kabupaten?></td>
            <td style="text-align:center"><?=$rows->kode_dealer_ahm?></td>
            <td><?=$rows->nama_dealer?></td>
            <td style="text-align:right"><?=$typeJob->total_ass1?></td>
            <td style="text-align:right"><?=$typeJob->total_ass2?></td>
            <td style="text-align:right"><?=$typeJob->total_ass3?></td>
            <td style="text-align:right"><?=$typeJob->total_ass4?></td>
            <td style="text-align:right"><?=$typeJob->total_ass1 + $typeJob->total_ass2 + $typeJob->total_ass3 + $typeJob->total_ass4?></td>
            <td style="text-align:right"><?=$typeJob->total_jr?></td>
            <td style="text-align:right"><?=$typeJob->total_claim?></td>
            <td style="text-align:right"><?=$typeJob->total_cs?></td>
            <td style="text-align:right"><?=$typeJob->total_ls?></td>
            <td style="text-align:right"><?=$typeJob->total_or?></td>
            <td style="text-align:right"><?=$typeJob->total_lr?></td>
            <td style="text-align:right"><?=$typeJob->total_hr?></td>
            <td style="text-align:right"><?=number_format($typeJob->total_job,0,',','.')?></td>
            <td style="text-align:right"><?=number_format($typeJob->total_entri,0,',','.')?></td>
            <td style="text-align:right"><?=number_format($totalAmountJob->ass,0,',','.')?></td>
            <td style="text-align:right"><?=number_format($totalAmountJob->qs,0,',','.')?></td>
            <td style="text-align:right"><?=number_format($totalAmountJob->LR,0,',','.')?></td>
            <td style="text-align:right"><?=number_format($totalAmountJob->HR,0,',','.')?></td>
            <td style="text-align:right"><?=number_format($totalAmountJob->TOTAL,0,',','.')?></td>
            <td style="text-align:right"><?=number_format($totalPart->spart + $totalPart->busi + $totalPart->tire + $totalPart->belt + $totalPart->battery + $totalPart->brake + $totalPart->other ,0,',','.')?></td>
            <td style="text-align:right"><?=number_format($totalPart->oil ,0,',','.')?></td>
            <td style="text-align:right"><?=number_format($totalAmountJob->CLAIM,0,',','.')?></td>
             <td style="text-align:right"><?=number_format($totalAmountJob->CLAIM + $totalAmountJob->TOTAL + $totalPart->spart + $totalPart->busi + $totalPart->tire + $totalPart->belt + $totalPart->battery + $totalPart->brake + $totalPart->other + $totalPart->oil ,0,',','.')?></td>
        </tr>
        <?php $index++; } ?>
    </table>
  </body>
   
  </html>
<?php } ?>