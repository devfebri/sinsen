<?php 
function mata_uang2($a){
    	if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    return number_format($a, 0, ',', '.');
}
?>
<table class="table table-bordered table-hovered" id="example2" width="100%">
    <thead>
        <tr>
            <th>Kode Part</th>
            <th>Nama Part</th>
            <th>No Surat Jalan Ekspedisi</th>
            <th>Tgl Surat Jalan Ekspedisi</th>
            <th>Tgl Terima</th>
            <th>Tgl Checker</th>
            <th>No Polisi</th>                                
            <th>No Mesin</th>
            <th>Harga Part</th>            
            <th>Ongkos Kerja</th>            
            <th>Total</th>       
            <th>Action</th> 
        </tr>                  
    </thead>
    <tbody>
        <?php 
        $no=1;$h=0;$o=0;$g=0;
        foreach ($dt_rekap->result() as $isi) {
            $cek = $this->db->query("SELECT *, tr_scan_barcode.tgl_penerimaan AS tgl FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list
                INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit.id_penerimaan_unit = tr_penerimaan_unit_detail.id_penerimaan_unit
                WHERE tr_scan_barcode.no_mesin = '$isi->no_mesin'")->row();
            $ek = $this->m_admin->getByID("tr_checker_detail","id_checker",$isi->id_checker);
            $biaya=0;
            foreach ($ek->result() as $k) {
                $biaya = $biaya + $k->ongkos_kerja;
            }
            $tot = $biaya + $isi->harga_jasa;
            $jum = $dt_rekap->num_rows();
            $qty_part = $this->db->get_where("tr_checker_detail",['id_checker'=>$isi->id_checker,'id_part'=>$isi->id_part]);
            if ($qty_part->num_rows()>0) {
                $qty_part = $qty_part->row()->qty_order;
            }else{
                $qty_part=0;
            }
            $harga_part = $isi->harga_dealer_user*$qty_part;
            $hasil = $isi->ongkos_kerja + $harga_part;
            echo "
            <tr>                
                <td>$isi->id_part</td>
                <td>$isi->nama_part</td>
                <td>$cek->no_surat_jalan</td>
                <td>$cek->tgl_surat_jalan</td>
                <td>$cek->tgl</td>
                <td>$isi->tgl_checker</td>
                <td>$isi->no_polisi</td>                
                <td>$isi->no_mesin</td>
                <td align='right'>".mata_uang2($harga_part)."</td>                            
                <td align='right'>".mata_uang2($isi->ongkos_kerja)."</td>
                <td align='right'>".mata_uang2($hasil)."</td>
                <td align='center'>
                    <input type='hidden' name='jum' value='$jum'>
                    <input type='hidden' name='tot_$no' value='$hasil'>
                    <input type='hidden' name='id_checker_$no' value='$isi->id_checker'>
                    <input type='checkbox' name='cek_$no'>                    
                </td>                
            </tr>
            ";
            $no++;
            $h = $h + $harga_part;            
            $o = $o + $isi->ongkos_kerja;
            $g = $g + $hasil;
        }
        ?>
        <tfoot>
            <tr>
                <td colspan="8"></td>
                <td align='right'><?php echo mata_uang2($h); ?></td>                                
                <td align='right'><?php echo mata_uang2($o); ?></td>
                <td align='right'><?php echo mata_uang2($g); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </tbody>
</table>  




<!-- 
        $no=1;
        foreach ($dt_rekap->result() as $isi) {
            $cek = $this->db->query("SELECT * FROM tr_scan_barcode INNER JOIN tr_penerimaan_unit_detail ON tr_scan_barcode.no_shipping_list = tr_penerimaan_unit_detail.no_shipping_list
                INNER JOIN tr_penerimaan_unit ON tr_penerimaan_unit.id_penerimaan_unit = tr_penerimaan_unit_detail.id_penerimaan_unit
                WHERE tr_scan_barcode.no_mesin = '$isi->no_mesin'")->row();
            $ek = $this->m_admin->getByID("tr_checker_detail","id_checker",$isi->id_checker);
            $biaya=0;
            foreach ($ek->result() as $k) {
                $biaya = $biaya + $k->ongkos_kerja;
            }
            $tot = $biaya + $isi->harga_jasa;
            $jum = $dt_rekap->num_rows();
            echo "
            <tr>                
                <td>$cek->no_surat_jalan</td>
                <td>$cek->tgl_surat_jalan</td>
                <td>$cek->tgl_penerimaan</td>
                <td>$isi->tgl_checker</td>
                <td>$isi->no_polisi</td>
                <td>$cek->nama_driver</td>
                <td>$isi->no_mesin</td>
                <td>$isi->keterangan</td>
                <td>$isi->harga_jasa</td>
                <td>$biaya</td>
                <td>$tot</td>
                <td align='center'>
                    <input type='hidden' name='jum' value='$jum'>
                    <input type='hidden' name='tot_$no' value='$tot'>
                    <input type='hidden' name='id_checker_$no' value='$isi->id_checker'>
                    <input type='checkbox' name='cek_$no'>                    
                </td>                
            </tr>
            ";
            $no++;
        }
        ?> -->