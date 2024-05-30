<?php $this->load->view('email/header'); ?>

  <body class="">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">
         
              <table role="presentation" class="main">
                <tr>
                  <td height="2" style="width:33.3%;background: rgb(255,0,0);
  background: linear-gradient(90deg, rgba(255,0,0,1) 0%, rgba(255,188,188,1) 50%, rgba(255,0,0,1) 100%);line-height:2px;font-size:2px;">&nbsp;</td>
                </tr>
                <tr>
                  <td class="wrapper">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="center">
                          <p  style="border-bottom: 1px solid #D0D0D0;"><img src="<?= $logo ?>" alt="SINARSENTOSA" height="60px">             
                        </td>
                      </tr>
                    </table>
                 <?php if ($set=='selisih'):
          ?>
                    <p>Telah terjadi selisih transaksi Goods Receipt gudang <?= $gudang ?>, pada tanggal <?= $tgl_penerimaan ?>.</p>
                    <table class="table-bordered">
                      <tr>
                        <td colspan="6" style="text-align: center;background:#f7c4c4;font-weight: 550">
                          Detail no mesin yang tidak diterima Dealer namun ada di Surat Jalan
                        </td>
                      </tr>
                      <tr style="font-weight: 500">
                        <td style="width: 4%">No</td>
                        <td style="width: 15%">No Mesin</td>
                        <td style="width: 15%">No Rangka</td>
                        <td>Tipe</td>
                        <td>Warna</td>
                      </tr>
                        <?php foreach ($tidak_diterima->result() as $key=>$rs): ?>
                          <tr>
                            <td><?= $key+1 ?></td>
                            <td><?= $rs->no_mesin ?></td>
                            <td><?= $rs->no_rangka ?></td>
                            <td><?= $rs->id_tipe_kendaraan.'|'.$rs->tipe_ahm ?></td>
                            <td><?= $rs->id_warna.'|'.$rs->warna ?></td>
                          </tr>
                        <?php endforeach ?>
                        <tr>
                          <td colspan="4" style="font-weight: bold;text-align: right;">Total</td>
                          <td align="center"><b><?= $tidak_diterima->num_rows() ?></b></td>
                        </tr>
                    </table>
                    <br>
                    <table class="table-bordered">
                      <tr>
                        <td colspan="6" style="text-align: center;background:#f7c4c4;font-weight: 550">Detail No Mesin NRFS </td>
                      </tr>
                      <tr>
                        <td style="width: 4%">No</td>
                        <td style="width: 15%">No Mesin</td>
                        <td style="width: 15%">No Rangka</td>
                        <td>Tipe</td>
                        <td>Warna</td>
                      </tr>
                      <?php foreach ($detail->result() as $key=>$rs):  
                          $sb = $this->db->get_where('tr_scan_barcode',['no_mesin'=>$rs->no_mesin])->row();
                          $tipe = $this->db->get_where('ms_tipe_kendaraan',['id_tipe_kendaraan'=>$sb->tipe_motor])->row();
                          $wrn = $this->db->get_where('ms_warna',['id_warna'=>$sb->warna])->row();
                        ?>
                          <tr>
                            <td><?= $key+1 ?></td>
                            <td><?= $rs->no_mesin ?></td>
                            <td><?= $sb->no_rangka ?></td>
                            <td><?= $tipe->id_tipe_kendaraan.' | '.$tipe->tipe_ahm ?></td>
                            <td><?= $wrn->id_warna.' | '.$wrn->warna ?></td>
                          </tr>
                        <?php endforeach ?>
                        <tr>
                          <td colspan="4" style="font-weight: bold;text-align: right;">Total</td>
                          <td align="center"><b><?= $detail->num_rows() ?></b></td>
                        </tr>
                    </table>
                <?php endif ?>
                <?php if ($set=='penerimaan'): 
             $dt_pu = $dt_pu->row();
             $no_do = $dt_pu->no_do;
          ?>
          <p>Telah terjadi transaksi Goods Receipt gudang <?= $gudang ?>, pada tanggal <?= $tgl_penerimaan ?> dengan nomor Surat Jalan <?= $no_surat_jalan ?>,</p>
          <table class="table-bordered">
             <tr><td colspan="6" style="text-align: center;background:#f7c4c4;font-weight: 550">Detail nomesin diterima Dealer</td></tr>
            <tr>
              <td>No</td>
              <td>No Mesin</td>
              <td>No Rangka</td>
              <td>Tipe</td>
              <td>Warna</td>
              <td>Harga Beli Dealer</td>
            </tr>
              <?php foreach ($detail->result() as $key=> $rs): ?>
                <tr>
                  <td><?= $key+1 ?></td>
                  <td><?= $rs->no_mesin ?></td>
                  <td><?= $rs->no_rangka ?></td>
                  <td><?= $rs->id_tipe_kendaraan.' | '.$rs->tipe_ahm ?></td>
                  <td><?= $rs->id_warna.' | '.$rs->warna ?></td>
                  <?php $harga = $this->db->query("SELECT harga FROM tr_do_po_detail WHERE no_do='$no_do' AND id_item='$rs->id_item' ")->row()->harga; ?>
                  <td style="text-align:right"><?= mata_uang_rp($harga) ?></td>
                </tr>
              <?php endforeach ?>
              <tr>
                <td colspan="5" style="text-align:right"><b>Total</b></td>
                <td style="text-align:right"><b><?= $detail->num_rows() ?></b></td>
              </tr>
          </table><br>
          <table class="table-bordered">
                <tr><td colspan="3" style="text-align: center;background:#f7c4c4;font-weight: 550">Detail KSU yang diterima</td></tr>
                <tr>
                  <td>No</td>
                  <td>Nama Aksesoris</td>
                  <td>Qty Terima</td>
                </tr>
                  <?php foreach ($ksu->result() as $key=> $rs): ?>
                    <tr>
                      <td><?= $key+1 ?></td>
                      <td><?= $rs->ksu ?></td>
                      <td><?= $rs->qty_terima ?></td>
                    </tr>
                  <?php endforeach ?>
              </table><br>
          <table class="table table-bordered table-condensed table-striped table-hover">
                <tr><td colspan="6" style="text-align: center;background:#f7c4c4;font-weight: 550">Detail Invoice</td></tr>
                <tr>
                  <td>No</td>
                  <td>Item Motor</td>
                  <td>Nama</td>
                  <td>Jumlah</td>
                  <td>Harga Kosong</td>
                  <td>Total</td>
                </tr>
                <tbody>
                  <?php $get_d  = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
        INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
        INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
        WHERE tr_invoice_dealer.no_do = '$no_do'")->row();  
                    $get_nosin  = $this->db->query("SELECT * FROM tr_do_po_detail INNER JOIN ms_item ON tr_do_po_detail.id_item = ms_item.id_item
                    INNER JOIN ms_tipe_kendaraan ON ms_item.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                    INNER JOIN ms_warna ON ms_item.id_warna = ms_warna.id_warna
                    WHERE tr_do_po_detail.no_do = '$get_d->no_do' AND tr_do_po_detail.qty_do > 0");
                  $i=1;$qt=0;$t=0;$p=0;$potongan=0;   
                  $potongan=0;
                  foreach ($get_nosin->result() as $rs)
                  {
                    echo '<tr>';
                    echo '<td>'.$i.'</td>';
                    echo '<td>'.$rs->id_item.'</td>';
                    echo '<td>'.$rs->deskripsi_ahm.' / '.$rs->warna.'</td>';
                    echo '<td>'.$rs->qty_do.'</td>';
                    echo '<td>'.number_format($to = $rs->harga, 0, ',', '.').'</td>';
                    echo '<td style="text-align:right">'.number_format($to = $rs->harga * $rs->qty_do, 0, ',', '.').'</td>';
                    echo '</tr>';
                    $i++;             
                    $qt = $qt + $rs->qty_do;
                    $t = $t + $to;
                    $cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po_detail ON tr_invoice_dealer_detail.no_do = tr_do_po_detail.no_do
                    WHERE tr_do_po_detail.no_do = '$no_do' AND LEFT(tr_invoice_dealer_detail.id_item,6) = '$rs->id_item'");
                    if($cek2->num_rows() > 0){
                      $d = $cek2->row();
                      $po = $d->jum;
                    }else{
                      $po = 0;
                    }

                    $potongan = $potongan + (($rs->disc + $po) * $rs->qty_do);
                    //$p = $p + $potongan;
                  }
              ?>
                <tr>
                  <td colspan="3" style="text-align:right"><b>Total</b></td>
                  <td><?= isset($q)?$q:'' ?></td>
                  <td></td>
                  <td><?= number_format($t, 0, ',', '.') ?></td>
                </tr>
                </tbody>
              </table>
              <br>
              <table class="table table-bordered table-condensed table-striped table-hover">
                <?php if ($get_d->dealer_financing=='Ya') { ?>
                <tr>
                  <td>Potongan</td><td  style="text-align: right;"><?= number_format($pot = $potongan, 0, ',', '.') ?></td>
                </tr>
                 <tr>
                  <td>Diskon TOP</td>
                  <td style="text-align: right;"><?php 
                    $d = (($t-$pot)-($bunga_bank/360*$top))/(1+(( getPPN(1.1,false) *$bunga_bank/360)*$top));
                    $diskon_top = ($t-$pot)-$d; 
                    echo number_format($diskon_top, 0, ',', '.');
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>DPP</td>
                  <td  style="text-align: right;"><?= number_format($d, 0, ',', '.') ?></td>
                </tr>
                <tr>
                  <td>PPn</td>
                  <td  style="text-align: right;"><?= number_format($hs = $d * getPPN(0.1,false), 0, ',', '.') ?></td>
                </tr>
                <tr>
                  <td>Total Bayar</td>
                  <td  style="text-align: right;">
                    <?= number_format($hs + $d, 0, ',', '.') ?>
                  </td>
                </tr>
                <?php }else{ ?>
                  <tr>
                  <td>Potongan</td><td   style="text-align: right;"><?= number_format($pot = $potongan, 0, ',', '.') ?></td>
                </tr> 
                <tr>
                  <td>DPP</td>
                  <td  style="text-align: right;"><?= number_format($d = $t - $potongan, 0, ',', '.') ?></td>
                </tr>
                <tr>
                  <td>PPn</td>
                  <td  style="text-align: right;"><?= number_format($hs = $d * getPPN(0.1,false) , 0, ',', '.') ?></td>
                </tr>
                <tr>
                  <td>Total Bayar</td>
                  <td  style="text-align: right;">
                    <?= number_format($hs + $d, 0, ',', '.') ?>
                  </td>
                </tr>
                <?php } ?>
              </table>   
          <?php endif ?>



                  </td>
                </tr>
                <tr>
                  <td align="center"><br>
                    
                  </td>
                </tr>
              <!-- END MAIN CONTENT AREA -->
              </table>
<?php $this->load->view('email/footer'); ?>