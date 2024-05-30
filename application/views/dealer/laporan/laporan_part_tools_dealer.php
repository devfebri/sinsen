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
                       <!--<label for="inputEmail3" class="col-sm-2 control-label">Dealer</label>-->
                    <div class="col-sm-5">
                     <input type="hidden" value="<?=$dealer?>"  id="dealer" name="dealer"/>
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
            $("#showReport").attr("src", '<?php echo site_url("dealer/laporan_part_tools_dealer?") ?>cetak=' + value.cetak + '&params=' + values);
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
       <table>
      <tr>
        <td colspan="10"><?= kop_surat_dealer($this->m_admin->cari_dealer()); ?></td>
      </tr>
    </table>
    <div style="text-align: center;font-size: 11pt"><b>LAPORAN PART TOOLS</b></div>
    <br>
    <br>
     <div style="text-align: right;font-size: 11pt;font-weight:normal;">Dicetak pada : <?php echo tgl_indo(date('Y-m-d'))?> <?php echo date('H:i:s')?></div>
    <hr>
 

    <table class="table table-bordered" border=1>
        <tr>
            <th>No</th>
            <th>Kode Dealer</th>
            <th>Nama Dealer</th>
            <th>ID Part</th>
            <th>ID Deskripsi Part</th>
            <th>Tanggal Penerimaan</th>
            <th>No Faktur</th>
            <th>Qty Stock</th>
            <th>Qty Transaksi</th>
            <th>Referensi</th>
        </tr>
        <?php
        $index=1;
        foreach($detail->result() as $each){
        $dealer = $this->db->get_where('ms_dealer',['id_dealer'=>$params->id_dealer])->row();
        $receipt = $this->db->query("SELECT a.qty_fulfillment,left(a.created_at,10) as tanggal,b.id_reference from tr_h3_dealer_order_fulfillment a join tr_h3_dealer_good_receipt b on a.id_referensi = b.id_good_receipt where b.id_dealer='$params->id_dealer' and a.id_part='$each->id_part'");
        $transaksi = $this->db->query("SELECT a.no_nsc,b.qty from tr_h23_nsc a join tr_h23_nsc_parts b on a.no_nsc=b.no_nsc where a.id_dealer='$params->id_dealer' and b.id_part='$each->id_part'");
        ?>
        <tr>
            <td><?=$index?></td>
            <td style="text-align:center">&nbsp;<?=$dealer->kode_dealer_ahm?></td>
            <td><?=$dealer->nama_dealer?></td>
            <td>&nbsp;<?=$each->id_part?></td>
            <td><?=$each->nama_part?></td>
            <td style="text-align:center">
                <?php 
                if($receipt->num_rows()>0){
                    foreach($receipt->result() as $r){
                        echo formatTanggal($r->tanggal) ."<br>";
                    }
                    echo "";
                }
                ?>
            </td>
             <td width="120px">
                <?php 
                if($receipt->num_rows()>0){
                    foreach($receipt->result() as $r){
                        echo $r->id_reference ."<br>";
                    }
                    echo "";
                }
                ?>
            </td>
            <td style="text-align:center"><?=$each->stock?></td>
             <td style="text-align:center">
                <?php 
                if($transaksi->num_rows()>0){
                    foreach($transaksi->result() as $t){
                        echo $t->qty ."<br>";
                    }
                    echo "";
                }
                ?>
            </td>
             <td>
                <?php 
                if($transaksi->num_rows()>0){
                    foreach($transaksi->result() as $t){
                        echo $t->no_nsc ."<br>";
                    }
                    echo "";
                }
                ?>
            </td>
        </tr>
        <?php $index++; } ?>
    </table>
  </body>
   
  </html>
<?php } ?>