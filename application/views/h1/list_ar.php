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
          <a href="h1/list_ar/history">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> History</button>
          </a>
          <a href="h1/list_ar/all">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-list"></i> All</button>
          </a>
          <a href="h1/list_ar/download">            
            <button class="btn btn-success btn-flat margin"><i class="fa fa-download"></i> Download</button>
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th>No Transaksi</th>                           
              <th>Tgl Transaksi</th>              
              <th>Vendor</th>                                          
              <th>Total</th>
            </tr>
          </thead>
          <tbody>            
          <?php           
          $g_total=0;            
          foreach($dt_invoice->result() as $row) {                                                         
            $total_bayar = $this->m_admin->get_detail_inv_dealer($row->no_do, $row->bunga_bank);    
            $cek = $this->m_admin->cekPembayaran($row->no_faktur,$total_bayar['total_bayar']);
            if ($cek>0) {
               echo "          
              <tr>               
                <td>$row->no_faktur</td>                            
                <td>$row->tgl_faktur</td>                            
                <td>$row->nama_dealer</td>
                <td align='right'>".mata_uang2($cek)."</td>             
              </tr>";
              $g_total += $cek;                                         
            }       
          }

          
           foreach($dt_rekap->result() as $row) {                                         
            //$cek = $this->m_admin->cekPembayaran($row->no_bastd,$row->total);
            //  if ($cek>0){
              echo "          
              <tr>                                                 
                <td>$row->no_bastd</td>                            
                <td>$row->tgl_rekap</td>
                <td>$row->nama_dealer</td>
                <td align='right'>".mata_uang2($cek)."</td>    
              </tr>                                      
                ";   
              $g_total += $cek;                                          
            //  }
            }
            
          ?>
          </tbody>
          <tfoot>
          	<tr>
          		<td colspan="3">Grand Total</td>
          		<td align="right"><?php echo mata_uang($g_total) ?></td>
          	</tr>
          </tfoot>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    }elseif($set=="all"){      
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/list_ar">            
            <button class="btn bg-blue btn-flat margin"><i class="fa fa-chevron-left"></i> Back</button>
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th>No Transaksi</th>                           
              <th>Tgl Transaksi</th>              
              <th>Vendor</th>                                          
              <th>Total</th>
            </tr>
          </thead>
          <tbody>            
          <?php           
          $dt_rekap = $this->db->query("SELECT * FROM tr_rekap_ekspedisi INNER JOIN ms_vendor ON tr_rekap_ekspedisi.id_vendor = ms_vendor.id_vendor
                ORDER BY tr_rekap_ekspedisi.id_rekap_ekspedisi DESC");    
          foreach($dt_rekap->result() as $row) {                                         
            $tr = $this->db->query("SELECT SUM(total) as jum FROM tr_rekap_ekspedisi_detail WHERE id_rekap_ekspedisi = '$row->id_rekap_ekspedisi'")->row();
            $cek = $this->m_admin->cekPembayaran($row->id_rekap_ekspedisi,$tr->jum);
            if ($cek>0) {
              $cek_di_voucher = $this->db->query("SELECT count(no_rekap) as count FROM tr_voucher_bank_rekap 
                JOIN tr_pengeluaran_bank ON tr_voucher_bank_rekap.id_voucher_bank=tr_pengeluaran_bank.no_voucher
                WHERE status='approved' AND no_rekap='$row->id_rekap_ekspedisi'")->row()->count;
              if ($cek_di_voucher==0) {
                echo "          
              <tr>               
                <td>$row->id_rekap_ekspedisi</td>                            
                <td>$row->tgl_rekap</td>                            
                <td>$row->vendor_name</td>                                          
                <td align='right'>".mata_uang2($cek)."</td> 
              </tr>";
              }
            }
          }
          $dt_invoice = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
              WHERE tr_invoice_dealer.status_invoice = 'printable' AND  
              tr_invoice_dealer.status_bayar <> 'lunas' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC");
          foreach($dt_invoice->result() as $row) {                                                     
            $rt = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();
            // $total_harga = 0;
            //     $total_harga = 0;
            //     $dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
            //         ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
            //         ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
            //         ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$row->no_do'");
            //       $to=0;$po=0;$do=0;
            //       foreach($dt_do_reg->result() as $isi){
            //         $total_harga = $isi->harga * $isi->qty_do;
            //         $get_d  = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
            //           INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
            //           INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
            //           WHERE tr_invoice_dealer.no_do = '$isi->no_do'");
            //         if($get_d->num_rows() > 0){
            //           $g = $get_d->row();
            //           $bunga_bank = $g->bunga_bank/100;
            //           $top_unit = $g->top_unit;
            //           $dealer_financing = $g->dealer_financing;
            //         }else{
            //           $bunga_bank = "";
            //           $top_unit = "";
            //           $dealer_financing = "";
            //         }

            //         $cek2  = $this->db->query("SELECT SUM(tr_invoice_dealer_detail.potongan) as jum FROM tr_invoice_dealer_detail INNER JOIN tr_do_po_detail ON tr_invoice_dealer_detail.no_do = tr_do_po_detail.no_do
            //               WHERE tr_do_po_detail.no_do = '$isi->no_do' AND LEFT(tr_invoice_dealer_detail.id_item,6) = '$isi->id_item'");
            //         if($cek2->num_rows() > 0){
            //           $d = $cek2->row();
            //           $potongan = $d->jum;
            //         }else{
            //           $potongan = 0;
            //         }
                    
            //         $pot = ($potongan + $isi->disc + $isi->disc_scp) * $isi->qty_do + $isi->disc_tambahan;              
            //         $to = $to + $total_harga;                    
            //         $po = $po + $pot;                    
            //         $do = $do + $isi->qty_do;                    
            //       }                  
            //       $d = (($to-$po)-($bunga_bank/360*$top_unit))/(1+((1.1*$bunga_bank/360)*$top_unit));
            //       $diskon_top = ($to-$po)-$d;
            //       if($dealer_financing=='Ya') {
            //         $y = $d * 0.1;
            //         $total_bayar = $d + $y;
            //       }else{
            //         $y = $d * 0.1;
            //         $total_bayar = $d + $y;
            //       }
                $total_bayar = $this->m_admin->get_detail_inv_dealer($row->no_do,$row->bunga_bank);
                $cek = $this->m_admin->cekPembayaran($row->no_faktur,$total_bayar['total_bayar']);
            if ($cek>0) {
               echo "          
              <tr>               
                <td>$row->no_faktur</td>                            
                <td>$row->tgl_faktur</td>                            
                <td>$rt->nama_dealer</td>
                <td align='right'>".mata_uang2($cek)."</td>             
              </tr>";
            }                                                
          }
          $dt_rekap = $this->db->query("SELECT tr_monout_piutang_bbn.*,tr_pengajuan_bbn.id_dealer
          FROM tr_monout_piutang_bbn 
          INNER JOIN tr_pengajuan_bbn ON tr_monout_piutang_bbn.no_bastd=tr_pengajuan_bbn.no_bastd
      JOIN tr_faktur_stnk ON tr_pengajuan_bbn.no_bastd=tr_faktur_stnk.no_bastd
      WHERE (tr_pengajuan_bbn.status_pengajuan='checked' OR tr_pengajuan_bbn.status_pengajuan='approved') AND tr_faktur_stnk.status_faktur='approved'
      ");
           foreach($dt_rekap->result() as $row) {                                         
            $dealer = $this->db->get_where('ms_dealer', ['id_dealer'=>$row->id_dealer])->row();
            $cek = $this->m_admin->cekPembayaran($row->no_bastd,$row->total);
              if ($cek>0){
              echo "          
              <tr>                                                 
                <td>$row->no_bastd</td>                            
                <td>$row->tgl_rekap</td>
                <td>$dealer->nama_dealer</td>
                <td align='right'>".mata_uang2($cek)."</td>    
              </tr>                                      
                ";   
              } 
            }
            $tr_monout_bantuan_bbn = $this->db->query("SELECT *,LEFT(tr_monout_bantuan_bbn.created_at,10) as created_at FROM tr_monout_bantuan_bbn LEFT JOIN ms_tipe_kendaraan ON tr_monout_bantuan_bbn.tipe = ms_tipe_kendaraan.id_tipe_kendaraan
            LEFT JOIN ms_warna ON ms_warna.id_warna = tr_monout_bantuan_bbn.warna WHERE tr_monout_bantuan_bbn.status_mon <> 'Lunas'");
          foreach($tr_monout_bantuan_bbn->result() as $row) {                                         
            $cek = $this->m_admin->cekPembayaran($row->no_faktur,$row->total);
            if ($cek>0) {
              echo "          
            <tr>                  
              <td>$row->no_faktur</td>                            
              <td>$row->created_at</td>                            
              <td>$row->nama_konsumen</td>                        
              <td align='right'>".mata_uang2($cek)."</td>                                        
              ";   
            }                                   
          }

            $dt_checker = $this->db->query("SELECT * FROM tr_checker INNER JOIN tr_checker_detail ON tr_checker.id_checker = tr_checker_detail.id_checker          
              LEFT JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part
              WHERE tr_checker.status_checker = 'close'");          
            foreach($dt_checker->result() as $row) {                                                     
              $harga_jasa = ($row->harga_jasa != "") ? $row->harga_jasa : "0" ;
              $biaya_pasang = $harga_jasa + $row->ongkos_kerja;
              $total = $biaya_pasang + $row->harga_md_dealer;
              $cek = $this->m_admin->cekPembayaran($row->id_checker,$total);
              if ($cek>0){
                echo "          
                <tr>                                                 
                  <td>$row->id_checker</td>                            
                  <td>$row->tgl_checker</td>
                  <td>$row->ekspedisi</td>
                  <td align='right'>".mata_uang2($cek)."</td>    
                </tr>                                      
                ";   
              } 
            }                                                         
          
            $dt_rekap9 = $this->db->query("SELECT tr_retur_unit.*,ms_dealer.nama_dealer FROM tr_retur_unit LEFT JOIN tr_retur_dealer ON tr_retur_unit.no_retur_dealer = tr_retur_dealer.no_retur_dealer 
              LEFT JOIN ms_dealer ON tr_retur_dealer.id_dealer = ms_dealer.id_dealer
              WHERE tr_retur_dealer.status_retur_d = 'approved' ORDER BY no_retur_unit DESC");  
            $total_semua = 0;$harga=0;$ppn=0;
            foreach($dt_rekap9->result() as $row9) {  
              $ambil_nosin = $this->db->query("SELECT * FROM tr_retur_dealer_detail WHERE no_retur_dealer = '$row9->no_retur_dealer'");
              foreach ($ambil_nosin->result() as $isi) {              
                $cari_no_do = $this->db->query("SELECT tr_picking_list_view.id_item, tr_picking_list.no_do FROM tr_picking_list_view JOIN tr_picking_list ON tr_picking_list_view.no_picking_list = tr_picking_list.no_picking_list
                  WHERE tr_picking_list_view.no_mesin = '$isi->no_mesin'");
                $no_do = ($cari_no_do->num_rows() > 0) ? $cari_no_do->row()->no_do : "" ;
                $id_item = ($cari_no_do->num_rows() > 0) ? $cari_no_do->row()->id_item : "" ;
                
                $dt_do_reg = $this->db->query("SELECT * FROM tr_do_po_detail WHERE tr_do_po_detail.no_do = '$no_do' AND qty_do>0 AND tr_do_po_detail.id_item = '$id_item'");              
                $cek_harga = ($dt_do_reg->num_rows() > 0) ? $dt_do_reg->row()->harga : 0 ;

                $harga += $cek_harga;
                $ppn += $cek_harga * getPPN(0.1,$row9->tgl_retur);

                $total_semua =+ ($harga - $ppn);
              }

              $total = $this->m_admin->cekPembayaran($row9->no_retur_unit,$total_semua);                                   
              if ($total>0) {
                    echo "          
              <tr>                             
                <td>$row9->no_retur_dealer</td>                            
                <td>$row9->tgl_retur</td>                            
                <td>$row9->nama_dealer</td>                                                                                                        
                <td align='right'>".mata_uang2($total_semua)."</td>                                                        
              </tr>";                                          
              }                                              
            } 
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
<?php }elseif($set=="history"){
    ?>
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">
          <a href="h1/list_ar">            
            <button class="btn bg-red btn-flat margin"><i class="fa fa-eye"></i> View Data</button>
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
        <table id="example4" class="table table-bordered table-hover">
          <thead>
            <tr>              
              <th>No Transaksi</th>                           
              <th>Tgl Transaksi</th>              
              <th>Vendor</th>                                          
              <th>Total</th>
            </tr>
          </thead>
          <tbody>            
          <?php           
          $dt_rekap = $this->db->query("SELECT * FROM tr_rekap_ekspedisi INNER JOIN ms_vendor ON tr_rekap_ekspedisi.id_vendor = ms_vendor.id_vendor
                ORDER BY tr_rekap_ekspedisi.id_rekap_ekspedisi DESC");    
          foreach($dt_rekap->result() as $row) {                                         
            $tr = $this->db->query("SELECT SUM(total) as jum FROM tr_rekap_ekspedisi_detail WHERE id_rekap_ekspedisi = '$row->id_rekap_ekspedisi'")->row();
            $cek = $this->m_admin->cekPembayaran($row->id_rekap_ekspedisi,$tr->jum);
            // if ($cek==0) {
              $cek_di_voucher = $this->db->query("SELECT count(no_rekap) as count FROM tr_voucher_bank_rekap 
                JOIN tr_pengeluaran_bank ON tr_voucher_bank_rekap.id_voucher_bank=tr_pengeluaran_bank.no_voucher
                WHERE status='approved' AND no_rekap='$row->id_rekap_ekspedisi'")->row()->count;
              if ($cek_di_voucher>0) {
                echo "          
              <tr>               
                <td>$row->id_rekap_ekspedisi</td>                            
                <td>$row->tgl_rekap</td>                            
                <td>$row->vendor_name</td>                                          
                <td align='right'>".mata_uang2($cek)."</td> 
              </tr>";
              }
            // }
          }
          $dt_invoice = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
              WHERE (tr_invoice_dealer.status_invoice = 'printable' OR tr_invoice_dealer.status_invoice = 'approved') AND  
              tr_invoice_dealer.status_bayar = 'lunas' ORDER BY tr_invoice_dealer.id_invoice_dealer DESC");
          foreach($dt_invoice->result() as $row) {                                                     
            $rt = $this->m_admin->getByID("ms_dealer","id_dealer",$row->id_dealer)->row();
            $total_harga = 0;
                $total_harga = 0;
                $dt_do_reg = $this->db->query("SELECT tr_do_po_detail.*,ms_tipe_kendaraan.tipe_ahm,ms_tipe_kendaraan.id_tipe_kendaraan,ms_warna.id_warna, ms_warna.warna FROM tr_do_po_detail INNER JOIN ms_item 
                    ON tr_do_po_detail.id_item=ms_item.id_item INNER JOIN ms_tipe_kendaraan           
                    ON ms_item.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan INNER JOIN ms_warna
                    ON ms_item.id_warna=ms_warna.id_warna WHERE tr_do_po_detail.no_do = '$row->no_do'");
                  $to=0;$po=0;$do=0; $bunga_bank = "";
                      $top_unit = "";$dealer_financing='';
                  foreach($dt_do_reg->result() as $isi){
                    $total_harga = $isi->harga * $isi->qty_do;
                    
                    $pot = $isi->disc * $isi->qty_do;                    
                    $to = $to + $total_harga;                    
                    $po = $po + $pot;                    
                    $do = $do + $isi->qty_do;                    
                  }         
                  $get_d  = $this->db->query("SELECT * FROM tr_invoice_dealer INNER JOIN tr_do_po ON tr_invoice_dealer.no_do = tr_do_po.no_do 
                      INNER JOIN ms_dealer ON tr_do_po.id_dealer = ms_dealer.id_dealer
                      INNER JOIN ms_gudang ON tr_do_po.id_gudang = ms_gudang.id_gudang
                      WHERE tr_invoice_dealer.no_do = '$row->no_do'");
                      
                  if($get_d->num_rows() > 0){
                    $g = $get_d->row();
                    $bunga_bank = $g->bunga_bank/100;
                    $top_unit = $g->top_unit;
                    $dealer_financing = $g->dealer_financing;
                    $tgl_transaksi = $g->tgl_faktur;
                  }else{
                    $bunga_bank = "";
                    $top_unit = "";
                    $dealer_financing = "";
                    $tgl_transaksi = false;
                  }

                  $d = (($to-$po)-($bunga_bank/360*$top_unit))/(1+(( getPPN(1.1, $tgl_transaksi) *$bunga_bank/360)*$top_unit));
                  $diskon_top = ($to-$po)-$d;
                  if($dealer_financing=='Ya') {
                    $y = $d * getPPN(0.1,$tgl_transaksi);
                    $total_bayar = $d + $y;
                  }else{
                    $y = $d * getPPN(0.1,$tgl_transaksi);
                    $total_bayar = $d + $y;
                  }  
          $cek = $this->m_admin->cekPembayaran($row->no_faktur,$total_bayar);
            if ($cek==0) {
               echo "          
              <tr>               
                <td>$row->no_faktur</td>                            
                <td>$row->tgl_faktur</td>                            
                <td>$rt->nama_dealer</td>
                <td align='right'>".mata_uang2($total_bayar)."</td>             
              </tr>";
            }                                                
          }
          $dt_rekap = $this->db->query("SELECT tr_monout_piutang_bbn.*,tr_pengajuan_bbn.id_dealer
          FROM tr_monout_piutang_bbn 
          INNER JOIN tr_pengajuan_bbn ON tr_monout_piutang_bbn.no_bastd=tr_pengajuan_bbn.no_bastd
      JOIN tr_faktur_stnk ON tr_pengajuan_bbn.no_bastd=tr_faktur_stnk.no_bastd
      WHERE (tr_pengajuan_bbn.status_pengajuan='checked' OR tr_pengajuan_bbn.status_pengajuan='approved') AND tr_faktur_stnk.status_faktur='approved'
      ");
           foreach($dt_rekap->result() as $row) {                                         
            $dealer = $this->db->get_where('ms_dealer', ['id_dealer'=>$row->id_dealer])->row();
            $cek = $this->m_admin->cekPembayaran($row->no_bastd,$row->total);
              if ($cek==0){
              echo "          
              <tr>                                                 
                <td>$row->no_bastd</td>                            
                <td>$row->tgl_rekap</td>
                <td>$dealer->nama_dealer</td>
                <td align='right'>".mata_uang2($row->total)."</td>    
              </tr>                                      
                ";   
              } 
            }
            $tr_monout_bantuan_bbn = $this->db->query("SELECT *,LEFT(tr_monout_bantuan_bbn.created_at,10) as created_at FROM tr_monout_bantuan_bbn LEFT JOIN ms_tipe_kendaraan ON tr_monout_bantuan_bbn.tipe = ms_tipe_kendaraan.id_tipe_kendaraan
            LEFT JOIN ms_warna ON ms_warna.id_warna = tr_monout_bantuan_bbn.warna WHERE tr_monout_bantuan_bbn.status_mon <> 'Lunas'");
          foreach($tr_monout_bantuan_bbn->result() as $row) {                                         
            $cek = $this->m_admin->cekPembayaran($row->no_faktur,$row->total);
            if ($cek==0) {
              echo "          
            <tr>                  
              <td>$row->no_faktur</td>                            
              <td>$row->created_at</td>                            
              <td>$row->nama_konsumen</td>                        
              <td align='right'>".mata_uang2($row->total)."</td>                                        
              ";   
            }                                   
          }

          $dt_checker = $this->db->query("SELECT * FROM tr_checker INNER JOIN tr_checker_detail ON tr_checker.id_checker = tr_checker_detail.id_checker
              INNER JOIN tr_shipping_list ON tr_checker_detail.no_mesin = tr_shipping_list.no_mesin
              LEFT JOIN ms_part ON tr_checker_detail.id_part = ms_part.id_part
              WHERE tr_checker.status_checker = 'close'");          
            foreach($dt_checker->result() as $row) {                                                     
              $harga_jasa = ($row->harga_jasa != "") ? $row->harga_jasa : "0" ;
              $biaya_pasang = $harga_jasa + $row->ongkos_kerja;
              $total = $biaya_pasang + $row->harga_md_dealer;
              $cek = $this->m_admin->cekPembayaran($row->id_checker,$total);
              if ($cek==0){
                echo "          
                <tr>                                                 
                  <td>$row->id_checker</td>                            
                  <td>$row->tgl_checker</td>
                  <td>$row->ekspedisi</td>
                  <td align='right'>".mata_uang2($cek)."</td>    
                </tr>                                      
                ";   
              } 
            }

                                                                  
          ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
    <?php
    }
    ?>
  </section>
</div> ?>
