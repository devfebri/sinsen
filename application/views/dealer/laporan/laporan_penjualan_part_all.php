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
        <li class="">H3</li>
        <li class="">Laporan Penjualan Part All</li>
        <li class="active"><?php echo ucwords(str_replace("_", " ", $isi)); ?></li>
      </ol>
    </section>
    <section class="content">
         <body onload="ld();"></body>
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
                      <button type="button" onclick="getReport('preview')" name="process" value="edit" class="btn bg-maroon btn-flat"><i class="fa fa-print"></i> Preview</button>
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
          padding-left: 6px;
          padding-right: 6px;
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
        <td colspan="15"><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
      </tr>
    </table>
    <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>
    <div style="text-align: center;font-size: 11pt">Periode : <?= date_dmy($params->start_date) . ' - ' . date_dmy($params->end_date) ?></div>
    <div style="text-align: right;font-size: 11pt">Dicetak  : <?=$_SESSION['nama']?> - <?=tgl_indo(date('Y-m-d H:i:s'))?> <?=date('H:i:s')?></div>
    <hr>
    <table class="table table-bordered" border=1>
      <tr>
        <th style="text-align:center;">No.</th>
        <th>No. NSC</th>
        <th>Tgl NSC</th>
        <th>ID. Jaminan</th>
        <th>Nama Konsumen</th>
        <th>ID Picking Slip</th>
        <th>Nomor Part</th>
        <th>Deskripsi Part</th>
        <th style="text-align:center;">HET</th>
        <th style="text-align:center;">Diskon %</th>
        <th style="text-align:center;">Qty</th>
        <th style="text-align:center;">Jumlah</th>
        <th style="text-align:center;">Kelompok Barang</th>
        <th style="text-align:center;">Referensi</th>
        <th style="text-align:center;">ID Referensi</th>
        <th style="text-align:center;">No WO</th>
      </tr>
      <?php $no = 1;
        $sum_het=array();
        $sum_diskon = array();
        $sum_jumlah = array();
        $sum_qty = array();
      foreach ($details as $rows) {
       $sum_het[]=intval($rows->harga_beli);
       $sum_diskon[]=intval($rows->diskon_persen);
       $sum_jumlah[]=intval(($rows->harga_beli - $rows->diskon_rupiah) *  $rows->qty);
       $sum_qty[]=intval($rows->qty);
      ?>
        <tr>
          <td style="text-align:center;" width="30px"><?= $no ?></td>
          <td><?= $rows->no_nsc ?></td>
          <td><?= formatTanggal($rows->tgl_nsc) ?></td>
          <td><?=$rows->no_inv_jaminan?></td>
          <td>
            <?php
              $dataKons ="";
              if($rows->referensi=='work_order'){
                  $dataKon = $this->db->query("SELECT nomor_so from tr_h3_dealer_sales_order where id_work_order='$rows->id_referensi' order by nomor_so DESC")->row()->nomor_so;
                 $dataKons=$dataKon;
              }else{
                $dataKons =$rows->id_referensi;
              }
              echo $this->db->get_where('tr_h3_dealer_sales_order',array('nomor_so'=>$dataKons))->row()->nama_pembeli;
              ?>
          </td>
          <td>
              <?php
                echo $this->db->get_where('tr_h3_dealer_picking_slip',array('nomor_so'=>$dataKons))->row()->nomor_ps;
              ?>
          </td>
          <td><?= $rows->id_part ?></td>
          <td><?= $rows->nama_part ?></td>
          <td style="text-align:right;"><?= mata_uang_rp($rows->harga_beli) ?></td>
          <td style="text-align:right;"><?= (int)$rows->diskon_persen ?></td>
          <td style="text-align:center;"><?= $rows->qty ?></td>
          <td style="text-align:right;"><?= mata_uang_rp(($rows->harga_beli - $rows->diskon_rupiah) *  $rows->qty)?></td>
          <td style="text-align:center;"><?= $rows->kelompok_part ?></td>
          <td style="text-align:center;"><?= $rows->referensi ?></td>
          <td style="text-align:left;">
              <?php
              $data ="";
              if($rows->referensi=='work_order'){
                  $data = $this->db->query("SELECT nomor_so from tr_h3_dealer_sales_order where id_work_order='$rows->id_referensi' order by nomor_so DESC")->row()->nomor_so;
                  echo $data;
              }else{
                echo $rows->id_referensi;
              }?>
              
          </td>
          <td>
              <?php
              echo $rows->referensi == 'work_order' ? $rows->id_referensi : "";
              ?>
          </td>
        
        </tr>
      <?php $no++;
      } ?>
        <tfoot>
            <tr>
                <th colspan="8" style="text-align:right;">Total</th>
                <th><?=number_format(array_sum($sum_het),0,',','.')?></th>
                <th><?=number_format(array_sum($sum_diskon),0,',','.')?></th>
                <th><?=number_format(array_sum($sum_qty),0,',','.')?></th>
                <th><?=number_format(array_sum($sum_jumlah),0,',','.')?></th>
                <th colspan="4" style="background-color:black"></th>
            </tr>
        </tfoot>
    </table>
    <br>
    <?php if ($params->tipe == 'preview') {?>
      <table>
                <tr>
                    <td colspan ="2" style='font-size: 13px;'>Dibuat Oleh,</td>
                    <td style='font-size: 13px;padding: left 30px;'>Diperiksa Oleh,</td>
                    <td style='font-size: 13px;padding: left 30px;'>Diketahui Oleh,</td>
                </tr>

                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                <tr>
                    <td><br></td>
                </tr>
                
                <tr>
                    <td colspan ="2">__________________________</td>
                    <td style='padding: left 30px;'>__________________________</td>
                    <td style='padding: left 30px;'>__________________________</td>
                </tr>
                <tr>
                    <td style='font-size: 13px;' colspan ="2">Kasir</td>
                    <td style='font-size: 13px;padding: left 30px;'>Finance</td>
                    <td style='font-size: 13px;padding: left 30px;'>Kacab</td>
                </tr>
      </table>
    <?php }?>
  </body>

  </html>
<?php } ?>