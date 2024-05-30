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
        <li class="">Finance</li>
        <li class="">Laporan</li>
        <li class="active">Laporan Pendapatan Harian AHASS</li>
      </ol>
    </section>
    <body onload="ld();"></body>
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
                  <div class="form-group" style="border-top:1px solid #f4f4f4">
                    <div class="col-sm-12" align="center" style="padding-top:10px">
                      <button type="button" id="btnShow" onclick="getReport('preview')" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>
                      <button type="button" onclick="getReport('download')" name="process" value="edit" class="btn bg-blue btn-flat"><i class="fa fa-download"></i> Download .xls</button>
                    </div>
                  </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                <div id="loader" class="loader" style="display:flex;align-items:center;align-content:center;justify-content:center;">
                    <img src="assets/keong.gif">
                </div>
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
      function ld(){
          $('.loader').hide(); 
      }
        function getReport(tipe) {
              $('.loader').show();
               $("#showReport").hide();
          var value = {
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            kpb: $('#kpb').val(),
            tipe: tipe,
            cetak: 'cetak',
          }
            if(value.tipe=='preview'){
                if (value.end_date == '' || value.start_date == '' || value.kpb == '') {
            confirm('Periode belum dipilih, anda yakin ingin menampilkan seluruh data ? \nProses ini mungkin memerlukan waktu, mohon menunggu.');
            if(confirm){
                let values = JSON.stringify(value);
                $('.loader').show();
              
              
                $("#showReport").attr("src", '<?php echo site_url("dealer/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
                document.getElementById("showReport").onload = function(e) {
                  $('.loader').hide();
                  $("#showReport").show();
                };
            }else{
                return false;
            }
          } else {
              
                let values = JSON.stringify(value);
                $('.loader').show();
               
                $('#btnShow').disabled;
                $("#showReport").attr("src", '<?php echo site_url("dealer/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
                document.getElementById("showReport").onload = function(e) {
                  $('.loader').hide();
                  $("#showReport").show();
                };
          
           
          } 
            }else{
               let values = JSON.stringify(value);
               
                 $('.loader').hide();
                $('#btnShow').disabled;
                $("#showReport").attr("src", '<?php echo site_url("dealer/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
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
      ini_set('display_errors', 0);
    echo "<script>
          $('.loader').hide(); 
    </script>";
    header("Content-type: application/vnd-ms-excel");
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
        <td colspan="17"><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
      </tr>
    </table>
    <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>
   <div style="text-align: center;font-size: 11pt">Periode : <?= date_dmy($params->start_date) . ' - ' . date_dmy($params->end_date) ?></div>
    <div style="text-align: right;font-size: 11pt">Dicetak  : <?=$_SESSION['nama']?> - <?=tgl_indo(date('Y-m-d H:i:s'))?> <?=date('H:i:s')?></div>
    <hr>
  
    <table class="table table-bordered" border=1>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">No WO</th>
            <th rowspan="2">Status</th>
            <th rowspan="2" width="100px">No.Pol</th>
            <th rowspan="2" width="180px">Nama Mekanik</th>
            <th rowspan="2" width="70px">Cash / KU</th>
            <th colspan="3">Penerimaan</th>
            <th colspan="6">KPB</th>
            <th rowspan="2">No. Claim</th>
            <th rowspan="2" width="50px">Claim C1/C2</th>
        </tr>
        <tr>
            <th>Service</th>
            <th>Oli</th>
            <th>Spart</th>
            <th width="30px">1</th>
            <th width="30px">2</th>
            <th width="30px">3</th>
            <th width="30px">4</th>
            <th width="30px">Jasa KPB</th>
            <th width="30px">Oli KPB</th>
        </tr>
        <?php
        ini_set('display_errors', 0);
        $no=1;
        $sum_service=array();
        $sum_oli_biasa = array();
        $sum_part = array();
        $total_oli_dan_part = array();
        $sum_kpb1=array();
        $sum_kpb2=array();
        $sum_kpb3=array();
        $sum_kpb4=array();
        $sum_jasa_kpb = array();
        $sum_oli_kpb=array();
        $sum_claim=array();
        
        foreach($details as $rows){
        $total_service = $this->db->query("SELECT count(1) as ts, COALESCE(SUM(wop.harga)) as total_service from tr_h2_wo_dealer_pekerjaan wop 
        join ms_h2_jasa jasa on wop.id_jasa=jasa.id_jasa
        where wop.id_work_order='$rows->id_work_order' and jasa.id_type not in('ASS1','ASS2','ASS3','ASS4','C1','C2') and wop.pekerjaan_batal =0")->row();
        
       
        
        $total_oli_biasa = $this->db->query("SELECT count(1) as ob, 
        COALESCE(SUM(nsc_part.harga_beli * nsc_part.qty)) as total_oli 
        from tr_h23_nsc_parts nsc_part 
        join tr_h23_nsc nsc on nsc.no_nsc=nsc_part.no_nsc join tr_h2_wo_dealer wo on nsc.id_referensi = wo.id_work_order 
        join tr_h2_sa_form sa on sa.id_sa_form=wo.id_sa_form join ms_part mp on nsc_part.id_part=mp.id_part inner join tr_h2_wo_dealer_pekerjaan wop on wop.id_work_order=wo.id_work_order join ms_h2_jasa jasa 
        on wop.id_jasa=jasa.id_jasa where wo.id_work_order='$rows->id_work_order'
        and jasa.id_type not in('ASS1') and mp.kelompok_part in('GMO','OIL','FED OIL') group by jasa.id_jasa");
        
        $total_part = $this->db->query("select nsc.no_nsc,nspart.id_part,nsc.id_referensi,wo.id_work_order,wo.id_karyawan_dealer,nspart.harga_beli,
                COALESCE(SUM(nspart.harga_beli * nspart.qty - (CASE WHEN nspart.tipe_diskon='Percentage' 
                THEN nspart.harga_beli*(nspart.diskon_value/100)* nspart.qty ELSE nspart.diskon_value END ))) as total_part
               from tr_h23_nsc nsc 
               join tr_h23_nsc_parts nspart on nspart.no_nsc=nsc.no_nsc
               join tr_h2_wo_dealer wo on wo.id_work_order = nsc.id_referensi 
               join ms_part part on part.id_part_int = nspart.id_part_int 
               where nsc.referensi ='work_order'
               and part.kelompok_part not in('GMO','OIL','FED OIL')
               and wo.id_work_order='$rows->id_work_order'
              ");
        
        $jasa_kpb = $this->db->query("SELECT count(1) AS kp, COALESCE(SUM(wop.harga)) as jasa_kpb from tr_h2_wo_dealer_pekerjaan wop 
        join ms_h2_jasa jasa on wop.id_jasa=jasa.id_jasa
        join tr_h2_wo_dealer wo on wop.id_work_order=wo.id_work_order
        join tr_h2_sa_form sa on sa.id_sa_form=wo.id_sa_form
        where wo.id_work_order='$rows->id_work_order' and jasa.id_type in('ASS1','ASS2','ASS3','ASS4') and wop.pekerjaan_batal =0")->row();
        
        $total_oli_kpb = $this->db->query("SELECT count(1) as ok, 
         COALESCE(SUM(nsc_part.harga_beli * nsc_part.qty) - (CASE WHEN nsc_part.tipe_diskon='Percentage' 
         THEN nsc_part.harga_beli*(nsc_part.diskon_value/100)* nsc_part.qty ELSE nsc_part.diskon_value END )) as total_oli_kpb from tr_h23_nsc_parts nsc_part 
        
        join tr_h23_nsc nsc on nsc.no_nsc=nsc_part.no_nsc join tr_h2_wo_dealer wo on nsc.id_referensi = wo.id_work_order 
        join tr_h2_sa_form sa on sa.id_sa_form=wo.id_sa_form join ms_part mp on nsc_part.id_part=mp.id_part join tr_h2_wo_dealer_pekerjaan wop on wop.id_work_order=wo.id_work_order join ms_h2_jasa jasa 
        on wop.id_jasa=jasa.id_jasa  where wo.id_work_order='$rows->id_work_order'
        and jasa.id_type in('ASS1') and mp.kelompok_part in('GMO','OIL')")->row();
        
         $total_claim = $this->db->query("SELECT count(1) as tc, COALESCE(SUM(wop.subtotal)) as total_claim from tr_h2_wo_dealer_pekerjaan wop 
        join ms_h2_jasa jasa on wop.id_jasa=jasa.id_jasa
        where wop.id_work_order='$rows->id_work_order' and jasa.id_type in('C1','C2') and wop.pekerjaan_batal =0")->row();
        $totalOli =0;
        $totalPart = 0;
        if($total_oli_biasa->num_rows() > 0){
            $totalOli = $total_oli_biasa->row()->total_oli;
        }else{
             $totalOli=0;
        }
        if($total_part->num_rows() > 0){
            $totalPart = $total_part->row()->total_part;
        }else{
            $totalPart=0;
        }
        $sum_service[]=intval($total_service->total_service);
        $sum_oli_biasa[]=intval($totalOli);
        $sum_part[]=intval($totalPart);
        $total_oli_dan_part[]=intval($total_service->total_service +  $totalPart + $totalOli);
        $sum_kpb1[]=intval($rows->kpb1);
        $sum_kpb2[]=intval($rows->kpb2);
        $sum_kpb3[]=intval($rows->kpb3);
        $sum_kpb4[]=intval($rows->kpb4);
        $sum_jasa_kpb[]=intval($jasa_kpb->jasa_kpb);
        $sum_oli_kpb[]=intval($total_oli_kpb->total_oli_kpb);
        $sum_claim[]=intval($total_claim->total_claim);
        ?>
        <tr>
        <td style="text-align:center"><?=$no++?></td>
        <td><?=$rows->id_work_order?></td>
        <td style="text-align:center"><?=ucwords($rows->status)?></td>
        <td><?=$rows->no_polisi?></td>
        <td><?=$rows->nama_lengkap?></td>
        <td style="text-align:center"><?=ucwords($rows->tipe_pembayaran)?></td>
        <td style="text-align:right"><?=number_format($total_service->total_service,0,',','.')?></td>
        <td style="text-align:right"><?=number_format($totalOli,0,',','.')?></td>
        <td style="text-align:right"><?=number_format($totalPart,0,',','.')?></td>
    
        <td style="text-align:center"><?=$rows->kpb1?></td>
        <td style="text-align:center"><?=$rows->kpb2?></td>
        <td style="text-align:center"><?=$rows->kpb3?></td>
        <td style="text-align:center"><?=$rows->kpb4?></td>
        <td style="text-align:right"><?=number_format($jasa_kpb->jasa_kpb,0,',','.')?></td>
        <td style="text-align:right"><?=number_format($total_oli_kpb->total_oli_kpb,0,',','.')?></td>
        <td><?=$rows->no_claim_c2?></td>
        <td style="text-align:right"><?=number_format($total_claim->total_claim,0,',','.')?></td>
        </tr>
       <?php } ?>
       <tr>
           <th colspan="5" rowspan="2">&nbsp;</th>
           <th>Total (a)</th>
           <th><?=number_format(array_sum($sum_service),0,',','.')?></th>
           <th><?=number_format(array_sum($sum_oli_biasa),0,',','.')?></th>
           <th><?=number_format(array_sum($sum_part),0,',','.')?></th>
        
           <th><?=number_format(array_sum($sum_kpb1),0,',','.')?></th>
           <th><?=number_format(array_sum($sum_kpb2),0,',','.')?></th>
           <th><?=number_format(array_sum($sum_kpb3),0,',','.')?></th>
           <th><?=number_format(array_sum($sum_kpb4),0,',','.')?></th>
           <th><?=number_format(array_sum($sum_jasa_kpb),0,',','.')?></th>
           <th><?=number_format(array_sum($sum_oli_kpb),0,',','.')?></th>
           <th></th>
           <th style="text-align:right"><?=number_format(array_sum($sum_claim),0,',','.')?></th>
       </tr>
       <tr>
           <th>Total (b)</th>
            <th><?=number_format($diskon_service->diskon_jasa,0,',','.')?></th>
            <th><?=number_format($diskon_oil->row()->diskon,0,',','.')?></th>
            <th><?=number_format($diskon_part->row()->diskon,0,',','.')?></th>
            <th colspan="8" style="background-color:black;"></th>
       </tr>
       <tr>
           <?php 
           $a= array_sum($sum_service) - $diskon_service->diskon_jasa;
           $b= array_sum($sum_oli_biasa) - $diskon_oil->row()->diskon;
           $c= array_sum($sum_part) - $diskon_part->row()->diskon;
           $d= array_sum($sum_jasa_kpb);
           $e= array_sum($sum_oli_kpb);
           $f= array_sum($sum_claim);
           ?>
           <th colspan="6">TOTAL PENDAPATAN AHASS</th>
           <th><?=number_format($a,0,',','.')?></th>
           <th><?=number_format($b,0,',','.')?></th>
           <th><?=number_format($c,0,',','.')?></th>
           <th colspan="4"></th>
            <th><?=number_format(array_sum($sum_jasa_kpb),0,',','.')?></th>
           <th><?=number_format(array_sum($sum_oli_kpb),0,',','.')?></th>
           <th></th>
           <th><?=number_format(array_sum($sum_claim),0,',','.')?></th>
       </tr>
        <tr>
           <th colspan="6">GRAND TOTAL</th>
           <th colspan="11" align="left"><?=number_format($a+$b+$c+$d+$e+$f,0,',','.')?></th>
       </tr>
    </table>
  </body>
   
  </html>
<?php } ?>