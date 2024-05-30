<table class="table table-bordered table-hovered" id="example2" width="100%">
    <thead>
        <tr>
            <th>No</th>            
            <th>No Penerimaan</th>
            <th>Tgl Penerimaan</th>
            <th>No Polisi</th>                    
            <th>No SJ</th>
            <th>Qty</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>                  
    </thead>
    <tbody>
        <?php 
        $no=1;
        $total=0;
        foreach ($dt_rekap->result() as $isi) {
            $jum = $dt_rekap->num_rows();
            echo "
            <tr>
                <td>$no</td>                                
                <td> "; ?>
                <a href="<?= base_url('h1/rekap_tagihan/penerimaan_unit?id='.$isi->id_penerimaan_unit) ?>" target="_blank"><?= $isi->id_penerimaan_unit ?></a>
        <?php echo"</td>
                <td>$isi->tgl_penerimaan</td>
                <td>$isi->no_polisi</td>
                <td>$isi->no_surat_jalan</td>
                <td>$isi->qty_terima</td>
                <td align='right'>".mata_uang_rp($isi->total)."</td>
                <td align='center'>
                    <input type='hidden' name='jum' value='$jum'>
                    <input type='hidden' name='id_penerimaan_unit_$no' value='$isi->id_penerimaan_unit'>
                    <input type='checkbox' name='cek_$no'>
                </td>
            </tr>
            ";
            $no++;
            $total+=$isi->total;
        }
        ?>
    <tr>
        <td colspan="6" style="font-weight: bold;text-align:right;">Total</td>
        <td style="text-align: right;font-weight: bold;"><?= mata_uang_rp($total) ?></td>
        <td></td>
    </tr>
    </tbody>
</table>  