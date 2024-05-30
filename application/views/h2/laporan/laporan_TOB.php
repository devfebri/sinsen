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
        .text{
            mso-number-format:"\@";/*force text*/
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
        <tr>
            <th>ORDER_NO</th>
            <th>NO_MESIN</th>
            <th>NO_RANGKA</th>
            <th>EFF_DATE</th>
            <th>NAMA_LENGKAP_PEMBAWA</th>
            <th>NAMA_LENGKAP_PEMILIK</th>
            <th>ALAMAT_LENGKAP</th>
            <th>KOTA</th>
            <th>JENIS_KELAMIN</th>
            <th>TANGGAL_LAHIR</th>
            <th>NO_KTP</th>
            <th>MOBILE_NO_PEMBAWA</th>
            <th>MOBILE_NO_PEMILIK</th>
            <th>JENIS_SERVICE</th>
            <th>EMAIL_ADDRESS</th>
            <th>NO_AHASS</th>
            <th>AHASS</th>
            <th>STATUS</th>
            <th>AREA</th>
        </tr>
          <?php $data = $this->db->query("SELECT wo.id_work_order,cus.no_mesin,
               cus.no_rangka,left(wo.closed_at,10) as eff,sa.id_customer,cus.nama_customer,cus.id_kelurahan as id_kelurahan,cus.alamat,cus.jenis_kelamin,cus.tgl_lahir,cus.no_identitas,cus.no_hp,jasa.id_type,cus.email,
               dealer.kode_dealer_ahm,dealer.nama_dealer,dealer.id_kelurahan as kelurahan_dealer
               from tr_h2_wo_dealer wo
               join tr_h2_wo_dealer_pekerjaan wop on wo.id_work_order = wop.id_work_order 
               join ms_h2_jasa jasa on jasa.id_jasa =wop.id_jasa 
               join tr_h2_sa_form sa on wo.id_sa_form = sa.id_sa_form 
               join ms_dealer dealer on dealer.id_dealer = wo.id_dealer
               join ms_customer_h23 cus on sa.id_customer =cus.id_customer where wo.id_dealer ='$params->id_dealer'
               and wop.pekerjaan_batal != '1'
               and jasa.id_type in('CS','LS') and left(wo.closed_at,10) >= '$params->start_date' and left(wo.closed_at,10) <= '$params->end_date'")->result();
       foreach($data as $rows){
               $id_kabupaten = substr($rows->id_kelurahan,0,4);
               $id_kabupaten2 = substr($rows->kelurahan_dealer,0,4);
               $kota = $this->db->query("SELECT kabupaten from ms_kabupaten where id_kabupaten='$id_kabupaten'");
               $area = $this->db->query("SELECT kabupaten from ms_kabupaten where id_kabupaten='$id_kabupaten2'");
               $status = $this->db->query("select h1,h2,h3 from ms_dealer where id_dealer ='$params->id_dealer'")->row();
               $ssu = $this->db->query("select prospek.jenis_kelamin,prospek.no_ktp,prospek.tgl_lahir from tr_spk spk 
                                        join tr_prospek prospek on spk.id_customer=prospek.id_customer where spk.no_mesin_spk='$rows->no_mesin'");
       ?>
            <tr>
                <td><?=$rows->id_work_order?></td>        
                <td><?=$rows->no_mesin?></td>        
                <td><?=str_replace('MH1','',$rows->no_rangka)?></td>        
                <td><?=formatTanggal($rows->eff)?></td>        
                <td><?=$rows->nama_customer?></td>        
                <td><?=$rows->nama_customer?></td>        
                <td><?=$rows->alamat?></td>        
                <td><?php
                    if($kota->num_rows()>0){
                        echo $kota->row()->kabupaten;
                    }else{
                        echo "";
                    }
                ?></td>        
                <td><?php
                if($ssu->num_rows()>0){
                    echo $ssu->row()->jenis_kelamin;
                }else{
                        echo $rows->jenis_kelamin;
                    }
                ?></td>        
                <td><?php
                if($ssu->num_rows()>0){
                    echo $ssu->row()->tgl_lahir;
                }else{
                        echo $rows->tgl_lahir;
                    }
                ?></td>        
                <td class="text">&nbsp;<?php
                if($ssu->num_rows()>0){
                    echo $ssu->row()->no_ktp;
                }else{
                        echo $rows->no_identitas;
                    }
                ?></td>        
                <td class="text">&nbsp;<?=$rows->no_hp?></td>        
                <td class="text">&nbsp;<?=$rows->no_hp?></td>        
                <td><?=$rows->id_type?></td>        
                <td><?=$rows->email?></td>        
                <td class="text">&nbsp;<?=$rows->kode_dealer_ahm?></td>        
                <td><?=$rows->nama_dealer?></td>   
                <td>
                 <?php
                    $temp ="";
                    if($status->h1==0 and $status->h2 == 1 and $status->h3 == 1){
                        $temp = "H23";
                    }elseif($status->h1==0 and $status->h2 == 1 and $status->h3 == 0){
                         $temp = "H2";
                    }elseif($status->h1==1 and $status->h2 == 1 and $status->h3 == 1){
                        $temp = "H123";
                    }
                    
                    echo $temp;
                 ?>    
                </td>   
                <td><?php
                    if($area->num_rows()>0){
                        echo $area->row()->kabupaten;
                    }else{
                        echo "";
                    }
                ?></td>
            </tr>    
       <?php } ?>
    </table>
    
  </body>
   
  </html>
<?php } ?>