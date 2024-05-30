<?php
function hariIndo ($hariInggris) {
  switch ($hariInggris) {
    case 'Sunday':
      return 'Minggu';
    case 'Monday':
      return 'Senin';
    case 'Tuesday':
      return 'Selasa';
    case 'Wednesday':
      return 'Rabu';
    case 'Thursday':
      return 'Kamis';
    case 'Friday':
      return 'Jumat';
    case 'Saturday':
      return 'Sabtu';
    default:
      return 'hari tidak valid';
  }
}

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
        <li class="">CRM H23</li>
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
            kpb: $('#kpb').val(),
            tipe: tipe,
            cetak: 'cetak',
          }

          if (value.end_date == '' || value.start_date == '' || value.kpb == '') {
            confirm('Periode belum dipilih, anda yakin ingin menampilkan seluruh data ? \nProses ini mungkin memerlukan waktu, mohon menunggu.');
            if(confirm){
                let values = JSON.stringify(value);
                $('.loader').show();
                $('#btnShow').disabled;
                $("#showReport").attr("src", '<?php echo site_url("dealer/" . $isi . "?") ?>cetak=' + value.cetak + '&params=' + values);
                document.getElementById("showReport").onload = function(e) {
                  $('.loader').hide();
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
   
    <div style="text-align: center;font-size: 13pt"><b><?= $title ?></b></div>
    <div style="text-align: center;font-size: 11pt"><b>Periode : <?= $params->start_date !="" && $params->end_date !=""  ? date_dmy($params->start_date) . ' s/d ' . date_dmy($params->end_date) : "-" ?></b></div>
    <hr>
  
    <table class="table table-bordered" border=1>
        <tr>
            <th>No</th>
            <th>No WO</th>
            <th>No Mesin</th>
            <th>No Rangka</th>
            <th>Tahun Produksi</th>
            <th>Hari Service </th>
	    <th>Tanggal Service</th>
	    <th>Jam Service</th>
            <th>Tanggal Pembelian</th>
	    <th>Dealer Pembelian Motor</th>
            <th>No Polisi</th>
            <th>Tipe PKB</th>
            <th>Nama Konsumen</th>
            <th>Alamat</th>
	    <th>Kelurahan</th>
	    <th>Kecamatan</th> 
	    <th>Kabupaten</th> 
	    <th>Provinsi</th> 
	    <th>Pekerjaan</th>
	    <th>Pendidikan</th>
            <th>Tipe Motor</th>
            <th>Deskripsi Motor</th>
            <th>No Handphone</th>
            <th>Amount Jasa</th>
            <th>Amount Parts</th>
            <th>Amount Oil</th>
            <th>Total</th>
	    
            
        </tr>
        <?php 
        $no=1;
        foreach($details as $rows){
            $part = $this->db->query("SELECT a.harga,a.qty from tr_h2_wo_dealer_parts a join ms_part b on a.id_part_int = b.id_part_int where b.kelompok_part not in('OIL','GMO','FED OIL') and a.id_work_order='$rows->id_work_order' and a.id_jasa='$rows->id_jasa'  and a.pekerjaan_batal !='1'");
            $oil = $this->db->query("SELECT a.harga,a.qty from tr_h2_wo_dealer_parts a join ms_part b on a.id_part_int = b.id_part_int where b.kelompok_part in('OIL','GMO','FED OIL') and  a.id_work_order='$rows->id_work_order' and a.id_jasa='$rows->id_jasa' and a.pekerjaan_batal !='1'");
            
        ?>
        <tr>
           <td style="text-align:center"><?=$no++?></td> 
           <td style="text-align:center"><?=$rows->id_work_order?></td> 
           <td><?=$rows->no_mesin?></td> 
           <td><?=$rows->no_rangka?></td> 
           <td style="text-align:center"><?=$rows->tahun_produksi?></td> 
	   <td><?= hariIndo(date('l',strtotime($rows->tanggal_service)))?></td>
           <td><?=date_dmy($rows->tanggal_service)?></td> 
           <td><?= $rows->jam_servis?> </td>
           <td><?=date_dmy($rows->tgl_pembelian)?></td> 
           <td><?= $rows->detail_pembelian?></td>
           <td style="text-align:center"><?=strtoupper($rows->no_polisi)?></td> 
           <td style="text-align:center"><?=$rows->id_type?></td> 
           <td><?=$rows->nama_customer?></td> 
           <td><?=$rows->alamat?></td> 
	   <td><?=$rows->nama_kelurahan?></td> 
	   <td><?=$rows->nama_kecamatan?></td>
 	   <td><?=$rows->nama_kabupaten?></td> 
	   <td><?=$rows->nama_provinsi?></td> 
	   <td><?=$rows->pekerjaan?></td>  
	   <td><?=$rows->pendidikan?></td> 
           <td><?=$rows->deskripsi_ahm?></td> 
           <td><?=$rows->tipe_ahm?></td> 
           <td>&nbsp;<?=$rows->no_hp?></td> 
           <td><?=number_format($rows->harga,0,',','.')?></td> 
           <td>
               <?php 
               $part_amount =0;
                if($part->num_rows()>0){
                    $part_amount = $part->row()->harga * $part->row()->qty;
                }else{
                     $part_amount=0;
                }
                if($part->num_rows()>0){
                    echo number_format($part_amount,0,',','.');
                }else{
                    echo 0;
                }
               ?>
           </td> 
           <td>
               <?php 
               $oil_amount =0;
                if($oil->num_rows()>0){
                   $oil_amount = $oil->row()->harga * $oil->row()->qty;
                }else{
                    $oil_amount = 0;
                }
                if($oil->num_rows()>0){
                    echo number_format($oil_amount,0,',','.');
                }else{
                    echo 0;
                }
               ?>
           </td> 
           <td><?=number_format($rows->harga+ $part_amount + $oil_amount,0,',','.')?></td>
	    
        </tr>
        <?php } ?>
    </table>
  </body>
   
  </html>
<?php } ?>