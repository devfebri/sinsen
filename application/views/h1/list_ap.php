<style type="text/css">
.myTable1{
  margin-bottom: 0px;
}
.myt{
  margin-top: 0px;
}
.isi{
  height: 30px;  
  padding-left: 5px;
  padding-right: 5px;  
  margin-right: 0px; 
}
.isi_combo{   
  height: 30px;
  border:1px solid #ccc;
  padding-left:1.5px;
}
</style>
<base href="<?php echo base_url(); ?>" />
<body onload="kirim_data_pl()">
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?php echo $title; ?>    
  </h1>
  <ol class="breadcrumb">
    <li><a href="panel/home"><i class="fa fa-home"></i> Dashboard</a></li>    
    <li class="">H1</li>
    <li class="">Finance</li>    
    <li class="">List AP/AR</li>    
    <li class="active"><?php echo ucwords(str_replace("_"," ",$page)); ?></li>
  </ol>
  </section>
  <section class="content">


    <?php
    if($set=="view"){
    ?>

    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <!--a href="h1/voucher_pengeluaran_bank/add">            
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"insert"); ?> class="btn bg-blue btn-flat margin"><i class="fa fa-plus"></i> Add New</button>
          </a-->          
                    
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                        
                
        ?>
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>                            
              <th>Tgl Invoice</th>
              <th>No Invoice</th>                           
              <th width="15%">Tgl Jatuh Tempo</th>              
              <th width="25%">Vendor</th>                            
              <th width="20%">Ket</th>                                          
              <!-- <th>DPP</th>
              <th>PPN</th>
              <th>PPh</th> -->
              <th>Total Bayar</th>                            
            </tr>
          </thead>
          <tbody>            
          <?php 
          $no=1; 
          foreach($dt_rekap->result() as $row) {        
            $r = $this->m_admin->getByID("tr_rekap_tagihan_detail","id_rekap_tagihan",$row->id_rekap_tagihan);
            $jum=0;
            foreach ($r->result() as $isi) {            
              // $qty = $this->db->query("SELECT COUNT(no_mesin) AS jum FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail 
              //   ON tr_scan_barcode.no_shipping_list=tr_penerimaan_unit_detail.no_shipping_list
              //   INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit_detail.id_penerimaan_unit = tr_penerimaan_unit.id_penerimaan_unit
              //   WHERE tr_penerimaan_unit_detail.id_penerimaan_unit = '$isi->id_penerimaan_unit'")->row();
              // $jum = $jum + $qty->jum;

              $qty = $this->db->query("SELECT SUM(total) as jum FROM tr_rekap_tagihan_detail INNER JOIN tr_penerimaan_unit ON tr_rekap_tagihan_detail.id_penerimaan_unit=tr_penerimaan_unit.id_penerimaan_unit
                    LEFT JOIN tr_invoice_penerimaan ON tr_invoice_penerimaan.no_penerimaan = tr_penerimaan_unit.id_penerimaan_unit
                    WHERE tr_rekap_tagihan_detail.id_rekap_tagihan = '$row->id_rekap_tagihan'")->row();            
              // $jum = $jum + $qty->jum;  
              $jum = $qty->jum;  
            }    
            // $invoice_dibayar = $this->db->query("SELECT IFNULL(SUM(nominal),0)as dibayar FROM tr_voucher_bank_detail
            //     JOIN tr_voucher_bank ON tr_voucher_bank.id_voucher_bank=tr_voucher_bank_detail.id_voucher_bank
            //     WHERE referensi='$row->id_rekap_tagihan' AND status='input' ")->row()->dibayar;
            // $total = $jum - $invoice_dibayar;
            $total = $this->m_admin->cekVoucherBank($row->id_rekap_tagihan,$jum);
            if ($total>0) {
              echo "          
            <tr>                             
              <td>$row->tgl_rekap</td>                            
              <td>$row->id_rekap_tagihan</td>                            
              <td></td>                                          
              <td>$row->vendor_name</td>                                          
              <td>Hutang Ekspedisi</td>                                                        
              <td align='right'>".mata_uang_rp($total)."</td>                                                        
              "; 
            }                                               
          } 

          $dt_rekap2 = $this->db->query("SELECT *,(SELECT IFNULL(SUM(harga),0) FROM tr_tagihan_lain_detail WHERE id_tagihan_lain=tr_tagihan_lain.id_tagihan_lain )as total FROM tr_tagihan_lain ORDER BY id_tagihan_lain DESC");
          foreach($dt_rekap2->result() as $row2) {   
            // $invoice_dibayar = $this->db->query("SELECT IFNULL(SUM(nominal),0)as dibayar FROM tr_voucher_bank_detail
            //     JOIN tr_voucher_bank ON tr_voucher_bank.id_voucher_bank=tr_voucher_bank_detail.id_voucher_bank
            //     WHERE referensi='$row2->id_tagihan_lain' AND status='input' ")->row()->dibayar;
            $total = $this->m_admin->cekVoucherBank($row2->id_tagihan_lain,$row2->total);
            if ($total>0) {
            echo "          
              <tr>                             
                <td>$row2->tgl_tagih</td>                            
                <td>$row2->id_tagihan_lain</td>                            
                <td>-</td>                                          
                <td>$row2->kode_customer</td>                                          
                <td>Hutang Lain-lain</td>                                                        
                <td align='right'>".mata_uang2($total)."</td>                                                        
                ";
            }                                                
          }

          $dt_rekap3 = $this->db->query("SELECT * FROM tr_rekap_asuransi INNER JOIN ms_vendor ON tr_rekap_asuransi.id_vendor = ms_vendor.id_vendor
              ORDER BY tr_rekap_asuransi.id_rekap_asuransi DESC");  
          foreach($dt_rekap3->result() as $row3) {         
          // $invoice_dibayar = $this->db->query("SELECT IFNULL(SUM(nominal),0)as dibayar FROM tr_voucher_bank_detail
          //       JOIN tr_voucher_bank ON tr_voucher_bank.id_voucher_bank=tr_voucher_bank_detail.id_voucher_bank
          //       WHERE referensi='$row3->id_rekap_asuransi' AND status='input' ")->row()->dibayar;
          // $total = $row3->total_bayar - $invoice_dibayar;       
          $total = $this->m_admin->cekVoucherBank($row3->id_rekap_asuransi,$row3->total_bayar);                         
          echo "          
            <tr>                             
              <td>$row3->tgl_rekap</td>                            
              <td>$row3->id_rekap_asuransi</td>                            
              <td>-</td>                                          
              <td>$row3->vendor_name</td>                                          
              <td>Hutang Asuransi</td>                                          
              <td align='right'>".mata_uang2($total)."</td>                                                        
              ";                                                
          }

          // $dt_rekap4 = $this->db->query("SELECT * FROM tr_monitor_tempo");  
          
          $dt_rekap4 = $this->db->query("SELECT no_rekap, tgl_jatuh_tempo FROM tr_monitor_tempo a join tr_invoice b on a.no_faktur =b.no_faktur where b.status_pelunasan =0 group by b.no_faktur ");  
            
          foreach($dt_rekap4->result() as $row4) {     
            $bulan = substr($row4->tgl_jatuh_tempo, 2,2);
            $tahun = substr($row4->tgl_jatuh_tempo, 4,4);
            $tgl = substr($row4->tgl_jatuh_tempo, 0,2);
            $tanggal = $tgl."-".$bulan."-".$tahun;

            $dt_invoice = $this->db->query("SELECT no_faktur FROM tr_invoice WHERE tr_invoice.tgl_pokok = '$row4->tgl_jatuh_tempo' GROUP BY no_faktur");
            $sisa=0;
            foreach ($dt_invoice->result() as $inv) {
              $get_inv = $dt_invoice = $this->db->query("SELECT * FROM tr_invoice WHERE tr_invoice.no_faktur = '$inv->no_faktur'");
              $total=0;
              foreach ($get_inv->result() as $g_i) {
                $total += ($g_i->ppn+$g_i->pph+$g_i->harga);
              }
              $sisa += $this->m_admin->cekVoucherBank($inv->no_faktur,$total);
            }
            if ($sisa>0) {
              echo "          
              <tr>                             
                <td>-</td>                            
                <td>
                  <a href='h1/list_ap/detail?id=$row4->tgl_jatuh_tempo&t=inv_ahm'>
                    $row4->no_rekap
                  </a>
                </td>                            
                <td>$tanggal</td>                                          
                <td>AHM</td>                                          
                <td>Hutang Unit</td>                                                        
                <td align='right'>".mata_uang2($sisa)."</td>                                          
                ";                                                    
            }                                               
          }          
          $dt_rekap5 = $this->db->query("SELECT *,LEFT(tr_claim_sales_program.created_at,10)as tgl FROM tr_claim_sales_program
              LEFT JOIN tr_sales_program on tr_claim_sales_program.id_program_md = tr_sales_program.id_program_md
              LEFT JOIN ms_dealer on tr_claim_sales_program.id_dealer=ms_dealer.id_dealer
           ORDER BY id_claim_sp DESC limit 0");
          foreach($dt_rekap5->result() as $row5) {    
            $cek = $this->db->query("SELECT sum(perlu_revisi) as sum FROM tr_claim_sales_program_detail WHERE id_claim_sp='$row5->id_claim_sp'");
            $jum = $this->db->query("SELECT IFNULL(SUM(nilai_potongan),0) AS jum FROM tr_claim_sales_program_detail 
              INNER JOIN tr_claim_dealer ON tr_claim_sales_program_detail.id_claim_dealer=tr_claim_dealer.id_claim
              WHERE id_claim_sp = '$row5->id_claim_sp' 
              AND (tr_claim_dealer.status='approved' OR tr_claim_dealer.status='ulang' OR tr_claim_dealer.status='ajukan')")->row();
            if ($cek->num_rows()>0) {  
             // $invoice_dibayar = $this->db->query("SELECT IFNULL(SUM(nominal),0)as dibayar FROM tr_voucher_bank_detail
             //    JOIN tr_voucher_bank ON tr_voucher_bank.id_voucher_bank=tr_voucher_bank_detail.id_voucher_bank
             //    WHERE referensi='$row5->id_claim_sp' AND status='input' ")->row()->dibayar;
             //    $total = $jum->jum - $invoice_dibayar;        
             $total = $this->m_admin->cekVoucherBank($row5->id_claim_sp,$jum->jum);        
              $cek = $cek->row();
              if ($cek->sum == 0) {
                if ($total>0) {

                    echo "          
                    <tr>                             
                      <td>$row5->tgl</td>                            
                      <td><a href=\"h1/list_ap/detail?id=$row5->id_claim_sp&t=claim\">$row5->id_program_md</a></td>                            
                      <td></td>                                          
                      <td>$row5->nama_dealer</td>                                          
                      <td>Hutang Claim</td>                                          
                       <td align='right'>".mata_uang2($total)."</td>   
                      "; 
                }                                               
              }
              }
            }
          $dt_rekap6 = $this->db->query("SELECT tr_adm_bbn.*,ms_vendor.vendor_name FROM tr_adm_bbn LEFT JOIN ms_vendor ON tr_adm_bbn.nama_biro_jasa=ms_vendor.id_vendor where tr_adm_bbn.status_pelunasan = 0 ORDER BY id_adm_bbn DESC");  
          foreach($dt_rekap6->result() as $row6) {  
          // $invoice_dibayar = $this->db->query("SELECT IFNULL(SUM(nominal),0)as dibayar FROM tr_voucher_bank_detail
          //       JOIN tr_voucher_bank ON tr_voucher_bank.id_voucher_bank=tr_voucher_bank_detail.id_voucher_bank
          //       WHERE referensi='$row6->id_adm_bbn' AND status='input' ")->row()->dibayar;
          //     $total = $row6->total - $invoice_dibayar; 
          $total = $this->m_admin->cekVoucherBank($row6->id_adm_bbn,$row6->total);                                      
          if ($total>0) {
                  echo "          
            <tr>                             
              <td>$row6->tgl_mohon_samsat</td>                            
              <td>$row6->id_adm_bbn</td>                            
              <td>-</td>                                          
              <td>$row6->vendor_name</td>                                          
              <td>Hutang BBN+Fee</td>                                          
              <td align='right'>".mata_uang2($total)."</td>                                                        
              ";                                          
          }                                              
          }
          $dt_rekap7 = $this->db->query("SELECT tr_adm_bpkb.*,ms_vendor.vendor_name FROM tr_adm_bpkb LEFT JOIN ms_vendor ON tr_adm_bpkb.nama_biro_jasa=ms_vendor.id_vendor where tr_adm_bpkb.status_pelunasan = 0 ORDER BY id_adm_bpkb DESC");  
          foreach($dt_rekap7->result() as $row7) {  
          // $invoice_dibayar = $this->db->query("SELECT IFNULL(SUM(nominal),0)as dibayar FROM tr_voucher_bank_detail
          //       JOIN tr_voucher_bank ON tr_voucher_bank.id_voucher_bank=tr_voucher_bank_detail.id_voucher_bank
          //       WHERE referensi='$row7->id_adm_bpkb' AND status='input' ")->row()->dibayar;
          //     $total = $row7->total - $invoice_dibayar;    
          // $biaya_cek = $this->m_admin->getByID("ms_setting_h1","id_setting_h1",1);
          // $biaya = ($biaya_cek->num_rows() > 0) ? $biaya_cek->row()->biaya_bpkb : 0 ;
          $total = $this->m_admin->cekVoucherBank($row7->id_adm_bpkb,$row7->total);                                   
          if ($total>0) {
                  echo "          
            <tr>                             
              <td>$row7->tgl_mohon_samsat</td>                            
              <td>$row7->id_adm_bpkb</td>                            
              <td>-</td>                                          
              <td>$row7->vendor_name</td>                                          
              <td>Hutang BPKB</td>                                          
              <td align='right'>".mata_uang2($total)."</td>                                                        
              ";                                          
          }                                              
          }
          $dt_rekap8 = $this->db->query("SELECT tr_adm_stnk.*,ms_vendor.vendor_name FROM tr_adm_stnk LEFT JOIN ms_vendor ON tr_adm_stnk.nama_biro_jasa=ms_vendor.id_vendor where tr_adm_stnk.status_pelunasan = 0 ORDER BY id_adm_stnk DESC");  
          foreach($dt_rekap8->result() as $row8) {  
          // $invoice_dibayar = $this->db->query("SELECT IFNULL(SUM(nominal),0)as dibayar FROM tr_voucher_bank_detail
          //       JOIN tr_voucher_bank ON tr_voucher_bank.id_voucher_bank=tr_voucher_bank_detail.id_voucher_bank
          //       WHERE referensi='$row8->id_adm_stnk' AND status='input' ")->row()->dibayar;
          //     $total = $row8->total - $invoice_dibayar;    
          $total = $this->m_admin->cekVoucherBank($row8->id_adm_stnk,$row8->total);                                   
          if ($total>0) {
                  echo "          
            <tr>                             
              <td>$row8->tgl_mohon_samsat</td>                            
              <td>$row8->id_adm_stnk</td>                            
              <td>-</td>                                          
              <td>$row8->vendor_name</td>                                          
              <td>Hutang STNK</td>                                          
              <td align='right'>".mata_uang2($total)."</td>                                                        
              ";                                          
          }                                              
          }

          $dt_rekap10 = $this->db->query("SELECT tr_adm_jual.*,ms_vendor.vendor_name FROM tr_adm_jual LEFT JOIN ms_vendor ON tr_adm_jual.nama_biro_jasa=ms_vendor.id_vendor where tr_adm_jual.status_pelunasan = 0 ORDER BY id_adm_jual DESC");  
          foreach($dt_rekap10->result() as $row10) {            
            $total = $this->m_admin->cekVoucherBank($row10->id_adm_jual,$row10->total);                                   
            if ($total>0) {
                    echo "          
              <tr>                             
                <td>$row10->tgl_mohon_samsat</td>                            
                <td>$row10->id_adm_jual</td>                            
                <td>-</td>                                          
                <td>$row8->vendor_name</td>                                          
                <td>Hutang ADM</td>                                          
                <td align='right'>".mata_uang2($total)."</td>                                                        
                ";                                          
            }                                              
          }

          $dt_rekap9 = $this->db->query("SELECT tr_retur_unit.*,ms_dealer.nama_dealer FROM tr_retur_unit LEFT JOIN tr_retur_dealer ON tr_retur_unit.no_retur_dealer = tr_retur_dealer.no_retur_dealer 
            LEFT JOIN ms_dealer ON tr_retur_dealer.id_dealer = ms_dealer.id_dealer
            WHERE tr_retur_dealer.status_retur_d = 'approved' ORDER BY no_retur_unit DESC");  
          $total_semua = 0;
          foreach($dt_rekap9->result() as $row9) {  
            $ambil_nosin = $this->db->query("SELECT * FROM tr_retur_dealer_detail WHERE no_retur_dealer = '$row9->no_retur_dealer'");
            $np="";
            $harga=0;$ppn=0;
            foreach ($ambil_nosin->result() as $isi) {              
              $cari_no_do = $this->db->query("SELECT 'list_ap' as menu, tr_picking_list_view.id_item, tr_picking_list.no_do FROM tr_picking_list_view JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
                WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin' and tr_picking_list.tgl_pl  < '$row9->tgl_retur' ORDER BY tr_picking_list.no_do desc LIMIT 0,1");
              $no_do = ($cari_no_do->num_rows() > 0) ? $cari_no_do->row()->no_do : "" ;
              $id_item = ($cari_no_do->num_rows() > 0) ? $cari_no_do->row()->id_item : "" ;
              
              $dt_do_reg = $this->db->query("SELECT * FROM tr_do_po_detail WHERE tr_do_po_detail.no_do = '$no_do' AND qty_do>0 AND tr_do_po_detail.id_item = '$id_item'");
              $harga_asli = ($dt_do_reg->num_rows() > 0) ? $dt_do_reg->row()->harga : 0 ;
              $harga += $harga_asli;
              $ppn += $harga_asli * getPPN(0.1, $row9->tgl_retur);

              $total_semua = ($harga + $ppn);
              //$np = $np.",".$no_do."-".$id_item."-".$harga_asli;
            }
            
            

            $total = $this->m_admin->cekVoucherBank($row9->no_retur_unit,$total_semua);                                   
            if ($total>0) {
                  echo "          
            <tr>                             
              <td>$row9->tgl_retur</td>                            
              <td>
                <a href='h1/list_ap/detail?id=$row9->no_retur_dealer&t=retur'>
                  $row9->no_retur_dealer
                </a>
              </td>                            
              <td></td>                                          
              <td>$row9->nama_dealer</td>                                          
              <td>Retur Unit</td>                                          
              <td align='right'>".mata_uang2($total_semua)."</td>                                                        
              ";                                          
            }                                              
          }
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->

    <?php
    } elseif($set=="detail_claim"){
      $row=$row->row();
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/list_ap">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                      
        ?>
        <div class="row">
          <div class="col-md-12">            
            
              <div class="box-body">       
                <br>
                <div class="form-horizontal">
                    <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Progam AHM</label>
                  <div class="col-sm-4">
                    <input type="text" name="id_program_md" id="id_program_md" class="form-control" readonly value="<?=$row->id_program_ahm?>">
                  </div>                  
                  <label for="inputEmail3" class="col-sm-2 control-label">ID Program MD</label>
                  <div class="col-sm-4">
                    <input type="text" name="id_program_md" id="id_program_md" class="form-control" readonly value="<?=$row->id_program_md?>">
                  </div>
                </div>
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">Kode Dealer</label>
                  <div class="col-sm-4">
                    <input type="text" name="id_program_md" id="id_program_md" class="form-control" readonly value="<?=$row->kode_dealer_md?> | <?=$row->nama_dealer?>" >
                                  
                </div>  <br><br><br>
                <button class="btn btn-block btn-warning btn-flat btn-sm" disabled> DETAIL UNIT </button><br>
 <table id="example4" class="table table-bordered table-hovered table-condensed" width="100%">
  <thead>
    <th width='5%'>No Mesin</th>
    <th width='30%'>Tipe</th>                                        
    <th>Warna</th>
    <th>No BASTK</th>
    <th>Tgl BASTK</th>
    <th>Nama Konsumen</th>
    <th>No PO Leasing</th>
    <th>Tgl PO Leasing</th>
    <th width='10%'>No Faktur</th>                                        
    <th width='7%'>Tgl Faktur</th>                                        
    <th width='10%'>Nilai Potongan</th>                                        
    <!-- <th width='8%'>Cek Syarat</th>                                         -->
    <!-- <th width='5%'>Status</th>                                        
    <th width='8%'>Perlu Revisi</th>                                                         -->
  </thead> 
  <tbody>
    <?php $detail = $this->db->query("SELECT *,tr_sales_order.no_mesin,tr_claim_dealer.status FROM tr_claim_sales_program_detail
              inner join tr_claim_dealer on tr_claim_sales_program_detail.id_claim_dealer=tr_claim_dealer.id_claim
              inner join tr_sales_order on tr_claim_dealer.id_sales_order=tr_sales_order.id_sales_order
              inner join tr_spk on tr_sales_order.no_spk=tr_spk.no_spk
              inner join ms_tipe_kendaraan on tr_spk.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
    WHERE id_claim_sp='$row->id_claim_sp'");?>
      <?php foreach ($detail->result() as $key => $rs){ ?>
        <?php $getTipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan='$rs->id_tipe_kendaraan'");
            $getTipe = $getTipe->num_rows()>0?$getTipe->row()->deskripsi_ahm.'|'.$getTipe->row()->id_tipe_kendaraan:'';
            $getWarna = $this->db->query("SELECT * FROM ms_warna WHERE id_warna='$rs->id_warna'");
            $getWarna = $getWarna->num_rows()>0?$getWarna->row()->warna:''; ?>
          <tr>
              <td><?=$rs->no_mesin?></td>
              <td><?=$getTipe?></td>
              <td><?= $getWarna ?></td>
              <td><?= $rs->no_bastk ?></td>
              <td><?= $rs->tgl_bastk ?></td>
              <td><?= $rs->nama_konsumen ?></td>
              <td><?= $rs->no_po_leasing ?></td>
              <td><?= $rs->tgl_po_leasing ?></td>
              <td><?=$rs->no_invoice?></td> 
              <td><?=$rs->tgl_cetak_invoice?></td> 
              <td><?=number_format($rs->nilai_potongan, 0, ',', '.') ?></td>
              <!-- <td><button class="btn btn-link" type="button" onclick="showSyarat('<?=$rs->id_claim?>')" >Proses</button></td> -->
              <!-- 
              <?php if ($rs->status=='approved') {
                  $status="<span class='label label-success'>Approved</span>";
              }elseif ($rs->status=='rejected') {
                  $status="<span class='label label-danger'>Rejected</span>";
              }elseif ($rs->status=='ulang') {
                  $status="<span class='label label-danger'>Rejected</span>";
              }else{
                $status='';
              } ?>
              <td align="center"><?=$status?></td>
              <td align="center">
                  <input type="hidden" name="id_claim[]" value="<?=$rs->id_claim?>">
                  <input type="hidden" name="id_<?=$key?>" value="<?=$rs->id?>">
                  <input type="checkbox" name="chk_revisi_<?=$key?>" id="chk_revisi_<?=$key?>" onchange="setRevisi(<?=$key?>)" <?php if($rs->perlu_revisi==1){ ?>checked <?php }else{ echo"disabled"; } ?>>
              </td> -->
          </tr>
      <?php } ?>
  
  </tbody>       
</table>   


<div class="modal fade" id="modalSyarat">      
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Cek Syarat
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
      </div>
      <div class="modal-body">
        <div id="showModalSyarat"></div>
      </div>      
    </div>
  </div>
</div>             
                <br>

                
              </div><!-- /.box-body -->
            </form>
                </div>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<?php }elseif($set=="detail_inv_ahm"){
      $row = $dt->row();
      $bulan = substr($row->tgl_jatuh_tempo, 2,2);
      $tahun = substr($row->tgl_jatuh_tempo, 4,4);
      $tgl = substr($row->tgl_jatuh_tempo, 0,2);
      $tanggal = $tgl."-".$bulan."-".$tahun;
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/list_ap">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                      
        ?>
        <div class="row">
          <div class="col-md-12">            
            <form class="form-horizontal" method="post" enctype="multipart/form-data">              
              <div class="box-body">       
                <div class="form-group">                  
                  <label for="inputEmail3" class="col-sm-2 control-label">No Rekap</label>
                  <div class="col-sm-3">
                    <input type="text" name="no_mesin" value="<?php echo $row->no_rekap ?>" placeholder="No Rekap" readonly class="form-control">                    
                  </div>                  
                  <label for="inputEmail3" class="col-sm-3 control-label">Tgl Jatuh Tempo</label>
                  <div class="col-sm-4">
                    <input type="text" name="no_mesin" value="<?php echo $tanggal ?>" placeholder="Tgl Jatuh Tempo" readonly class="form-control">                    
                  </div>                  
                </div>  
                
                
                <table class="table table-bordered table-hovered" id="example3" width="100%">
                  <thead>
                    <tr>
                      <th>No Faktur</th>
                      <th>Tgl Faktur</th>
                      <th>Tgl Jatuh Tempo</th>
                      <th>Total Amount</th>
                      <th>Total Diskon</th>
                      <th>Total PPN</th>
                      <th>Total PPh</th>
                      <th>Total Bayar</th>   
                      <th>Sisa Hutang</th>                 
                    </tr>                  
                  </thead>
                  <tbody>
                  <?php 
                  $tot_amount=0;$tot_disc=0;$tot_ppn=0;$tot_pph=0;$sub_tot=0;$tot_sisa=0;
                  foreach ($dt_mon->result() as $isi) {
                    // $total = $isi->jum_pph + $isi->jum_ppn + $isi->jum_amount-$isi->jum_disc;
                    $total = $isi->jum_pph + $isi->jum_ppn + $isi->jum_amount;
                    $sisa = $this->m_admin->cekOnlyVoucherBank($isi->no_faktur,$total);
                    $tot_amount += $isi->jum_amount;
                    $tot_disc   += $isi->jum_disc;
                    $tot_ppn    += $isi->jum_ppn;
                    $tot_pph    += $isi->jum_pph;
                    $sub_tot    += $total;
                    $tot_sisa   += $sisa;
                    echo 
                    "<tr>
                      <td>$isi->no_faktur</td>
                      <td>$isi->tgl_faktur</td>
                      <td align='right'>$isi->tgl_pokok</td>
                      <td align='right'>".mata_uang2($isi->jum_amount)."</td>
                      <td align='right'>".mata_uang2($isi->jum_disc)."</td>
                      <td align='right'>".mata_uang2($isi->jum_ppn)."</td>
                      <td align='right'>".mata_uang2($isi->jum_pph)."</td>
                      <td align='right'>".mata_uang2($total)."</td>
                      <td align='right'>".mata_uang2($sisa)."</td>
                    </tr>";
                  }
                  ?>
                  </tbody>                
                  <tfoot>
                    <tr style="font-weight: bold;">
                    <td colspan="3"><b>Total</b></td>
                    <td align="right"><?= mata_uang2($tot_amount) ?></td>
                    <td align="right"><?= mata_uang2($tot_disc) ?></td>
                    <td align="right"><?= mata_uang2($tot_ppn) ?></td>
                    <td align="right"><?= mata_uang2($tot_pph) ?></td>
                    <td align="right"><?= mata_uang2($sub_tot) ?></td>
                    <td align="right"><?= mata_uang2($tot_sisa) ?></td>
                  </tr>
                  </tfoot>
                </table>  
              </div><!-- /.box-body -->            
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->

    <?php }elseif($set=="retur"){      
    ?>

    <div class="box box-default">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/list_ap">
            <button <?php echo $this->m_admin->set_tombol($id_menu,$group,"select"); ?> class="btn bg-maroon btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
          </a>
        </h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
        </div>
      </div><!-- /.box-header -->
      <div class="box-body">
        <?php                       
        if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {                    
        ?>                  
        <div class="alert alert-<?php echo $_SESSION['tipe'] ?> alert-dismissable">
            <strong><?php echo $_SESSION['pesan'] ?></strong>
            <button class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>  
            </button>
        </div>
        <?php
        }
            $_SESSION['pesan'] = '';                      
        ?>
        <div class="row">
          <div class="col-md-12">            
            <form class="form-horizontal" method="post" enctype="multipart/form-data">              
              <div class="box-body">                       
                
                <table class="table table-bordered table-hovered" id="example3" width="100%">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Tgl Retur</th>
                      <th>Dealer Asal</th>
                      <th>No DO Asal</th>
                      <th>No Faktur Asal</th>
                      <th>Tgl Distribusi Asal</th>
                      <th>Harga Unit di Faktur Asal</th>
                      <th>No Surat Jalan Asal</th>
                      <th>Kode Item</th>
                      <th>No Mesin</th>   
                      <th>No Rangka</th>                 
                      <th>Dealer Tujuan</th>
                      <th>No DO Tujuan</th>
                      <th>Tgl DO</th>
                      <th>No Faktur</th>
                      <th>Tgl Faktur</th>
                      <th>No Surat Jalan</th>
                      <th>Tgl Surat</th>
                    </tr>                  
                  </thead>  
                  <tbody>
                  <?php 
                  $no=1;$total=0;$total_semua=0;$ppn=0;$harga=0;
                  foreach ($dt_retur->result() as $isi) {
                      
                    $cek_item = $this->db->query("select id_item from tr_picking_list_view where no_mesin = '$isi->no_mesin' order by no_picking_list_view ASC LIMIT 0,1");
                    $item = ($cek_item->num_rows() > 0) ? $cek_item->row()->id_item : $isi->id_item ;
                    
                    $do_lama2 = $this->db->query("SELECT tr_surat_jalan.no_surat_jalan, tr_surat_jalan.tgl_surat, tr_invoice_dealer.no_faktur,tr_invoice_dealer.tgl_faktur, tr_do_po.tgl_do, tr_picking_list_view.id_item, tr_picking_list.no_do 
                          FROM tr_picking_list_view 
                          LEFT JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
                          LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
                          LEFT JOIN tr_invoice_dealer ON tr_picking_list.no_do = tr_invoice_dealer.no_do
                          LEFT JOIN tr_surat_jalan ON tr_picking_list.no_picking_list = tr_surat_jalan.no_picking_list                          
                          WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin' AND tr_surat_jalan.status = 'close' ORDER BY tr_surat_jalan.id_surat_jalan ASC LIMIT 0,1");
                    $do_lama = $this->db->query("SELECT 
                          tr_surat_jalan.tgl_surat, tr_do_po_detail.id_item, tr_surat_jalan.no_surat_jalan, ms_dealer.nama_dealer, tr_invoice_dealer.no_faktur,tr_invoice_dealer.tgl_faktur, tr_do_po.tgl_do , tr_do_po.no_do 
                          FROM tr_surat_jalan_detail
                          LEFT JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
                          LEFT JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list
                          INNER JOIN tr_picking_list_view ON tr_picking_list.no_picking_list = tr_picking_list_view.no_picking_list
                          LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
                          LEFT JOIN tr_do_po_detail ON tr_do_po.no_do = tr_do_po_detail.no_do
                          LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
                          LEFT JOIN tr_invoice_dealer ON tr_do_po.no_do = tr_invoice_dealer.no_do                                                    
                          WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin' AND tr_surat_jalan.status = 'close' 
                          and tr_surat_jalan_detail.retur = 1 and tr_surat_jalan.tgl_surat < '$isi->tgl_retur'
                          and ms_dealer.id_dealer = '$isi->id_dealer' ORDER BY tr_surat_jalan.id_surat_jalan desc LIMIT 0,1");
                    $no_do = ($do_lama->num_rows() > 0) ? $do_lama->row()->no_do : "";
                    $tgl_do = ($do_lama->num_rows() > 0) ? $do_lama->row()->tgl_do : "";
                    $no_faktur = ($do_lama->num_rows() > 0) ? $do_lama->row()->no_faktur : "";
                    $tgl_faktur = ($do_lama->num_rows() > 0) ? $do_lama->row()->tgl_faktur : "";
                    $no_surat_jalan = ($do_lama->num_rows() > 0) ? $do_lama->row()->no_surat_jalan : "";
                    $tgl_surat = ($do_lama->num_rows() > 0) ? $do_lama->row()->tgl_surat : "";
                    $id_item = ($do_lama->num_rows() > 0) ? $do_lama->row()->id_item : "" ;
                
                    $dt_do_reg = $this->db->query("SELECT * FROM tr_do_po_detail WHERE tr_do_po_detail.no_do = '$no_do' AND qty_do>0 AND tr_do_po_detail.id_item = '$item'");
                    $harga = ($dt_do_reg->num_rows() > 0) ? $dt_do_reg->row()->harga : 0 ;                                        
                                        
                    $ppn = $harga * getPPN(0.1, $tgl_faktur);
                    $total_semua = ($harga + $ppn);
			
		    $where_tgl = "and tr_surat_jalan.tgl_surat >='$isi->tgl_retur'";
	    	    if($isi->tgl_retur ==  $tgl_surat){
		    	$where_tgl = "and tr_surat_jalan.tgl_surat >='$isi->tgl_retur' and tr_surat_jalan.no_surat_jalan != '$no_surat_jalan'";
		    }

                    $do_baru = $this->db->query("SELECT 
                          tr_surat_jalan.tgl_surat, tr_surat_jalan.no_surat_jalan, ms_dealer.nama_dealer, tr_invoice_dealer.no_faktur,tr_invoice_dealer.tgl_faktur, tr_do_po.tgl_do , tr_picking_list.no_do 
                          FROM tr_surat_jalan_detail
                          LEFT JOIN tr_surat_jalan ON tr_surat_jalan_detail.no_surat_jalan = tr_surat_jalan.no_surat_jalan
                          LEFT JOIN tr_picking_list ON tr_surat_jalan.no_picking_list = tr_picking_list.no_picking_list
                          LEFT JOIN tr_do_po ON tr_picking_list.no_do = tr_do_po.no_do
                          LEFT JOIN ms_dealer ON tr_surat_jalan.id_dealer = ms_dealer.id_dealer
                          LEFT JOIN tr_invoice_dealer ON tr_do_po.no_do = tr_invoice_dealer.no_do                                                    
                          WHERE tr_surat_jalan_detail.no_mesin = '$isi->no_mesin' AND tr_surat_jalan.status = 'close' 
			  $where_tgl
			  ORDER BY tr_surat_jalan.id_surat_jalan ASC limit 1");
		
                    $nama_dealer = ($do_baru->num_rows() > 0) ? $do_baru->row()->nama_dealer : "";                    
                    $no_do2 = ($do_baru->num_rows() > 0) ? $do_baru->row()->no_do: "";
                    $tgl_do2 = ($do_baru->num_rows() > 0) ? $do_baru->row()->tgl_do : "";
                    $no_faktur2 = ($do_baru->num_rows() > 0) ? $do_baru->row()->no_faktur : "";
                    $tgl_faktur2 = ($do_baru->num_rows() > 0) ? $do_baru->row()->tgl_faktur : "";
                    $no_surat_jalan2 = ($do_baru->num_rows() > 0) ? $do_baru->row()->no_surat_jalan : "";
                    $tgl_surat2 = ($do_baru->num_rows() > 0) ? $do_baru->row()->tgl_surat : "";
                    
                    echo "
                    <tr>
                      <td>$no</td>
                      <td>$isi->tgl_retur</td>
                      <td>$isi->nama_dealer</td>
                      <td>$no_do</td>
                      <td>$no_faktur</td>
                      <td>$tgl_surat</td>
                      <td>".mata_uang2($total_semua)."</td>
                      <td>$no_surat_jalan</td>
                      <td>$item</td>
                      <td>$isi->no_mesin</td>
                      <td>$isi->no_rangka</td>
                      <td>$nama_dealer</td>                      
                      <td>$no_do2</td>
                      <td>$tgl_do2</td>
                      <td>$no_faktur2</td>
                      <td>$tgl_faktur2</td>
                      <td>$no_surat_jalan2</td>
                      <td>$tgl_surat2</td>                                    
                    </tr>
                    ";
                    $no++;
                    $total = $total + $total_semua;
                  }
                  ?>
                  </tbody>              
                  <tfoot>
                    <tr>
                      <td colspan="5"></td>
                      <td><?php echo mata_uang2($total) ?></td>
                    </tr>
                  </tfoot>
                </table>  
              </div><!-- /.box-body -->            
            </form>
          </div>
        </div>
      </div>
    </div><!-- /.box -->
<?php } ?>
  </section>
</div>
