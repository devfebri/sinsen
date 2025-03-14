<table id="example2" class="table myTable1 table-bordered table-hover">
  <thead>
    <tr>
      <th>No Mesin</th>
      <th>No Rangka</th>
      <th>Nama Konsumen</th>
      <th>Alamat</th>
      <th>Biaya BBN</th>
      <!-- <th>Biaya BBN D-MD</th> -->
      <?php /* ?> <th>Harga Unit</th> <?php */ ?>
      <th>Fotocopy KTP (5)</th>
      <th>Cek Fisik Kendaraan (2)</th>
      <th>Hasil Cek Fisik STNK (1)</th>
      <th>Formulir Data BPKB (1)</th>
      <th>Surat Kuasa (2)</th>
      <th>CKD STNK & BPKB (2)</th>
      <th>Form Permohonan STNK (1)</th>
    </tr>
  </thead>

  <tbody>
    <?php
    $no = 1;
    foreach ($dt_stnk->result() as $isi) {
      $rw = $this->m_admin->getByID("tr_faktur_stnk_detail", "no_mesin", $isi->no_mesin);
      if ($rw->num_rows() > 0) {
        $row = $rw->row();
        if ($row->ktp == 'ya') $ktp = "checked";
        else $ktp = "";
        if ($row->fisik == 'ya') $fisik = "checked";
        else $fisik = "";
        if ($row->stnk == 'ya') $stnk = "checked";
        else $stnk = "";
        if ($row->bpkb == 'ya') $bpkb = "checked";
        else $bpkb = "";
        if ($row->kuasa == 'ya') $kuasa = "checked";
        else $kuasa = "";
        if ($row->ckd == 'ya') $ckd = "checked";
        else $ckd = "";
        if ($row->permohonan == 'ya') $pem = "checked";
        else $pem = "";
      } else {
        $ktp = "";
        $fisik = "";
        $stnk = "";
        $bpkb = "";
        $kuasa = "";
        $ckd = "";
        $pem = "";
      }
      $er = $this->db->query("SELECT * FROM tr_spk WHERE tr_spk.no_spk = '$isi->no_spk'");
      if ($er->num_rows() > 0) {
        $ts = $er->row();
        $nama_konsumen = $ts->nama_bpkb;
        $alamat = $ts->alamat;
      } else {
        $nama_konsumen = "";
        $alamat = "";
      }
      $re = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin)->row();
      $ra = $this->m_admin->getByID("tr_sales_order", "no_spk", $isi->no_spk)->row();
      $tr = $this->m_admin->getByID("ms_bbn_dealer", "id_tipe_kendaraan", $re->tipe_motor);
      if ($tr->num_rows() > 0) {
        $t = $tr->row();
        $biaya_bbn_md = $t->biaya_bbn;
      } else {
        $biaya_bbn_md = 0;
      }

      $cek_approve = $this->db->query("SELECT tr_faktur_stnk.status_faktur FROM tr_faktur_stnk JOIN tr_faktur_stnk_detail ON tr_faktur_stnk.no_bastd = tr_faktur_stnk_detail.no_bastd
       WHERE tr_faktur_stnk_detail.no_mesin = '$isi->no_mesin'");
      $status = "";
      if ($cek_approve->num_rows() > 0) {
        $status = $cek_approve->row()->status_faktur;
      }
      if ($ktp == '' or $fisik == '' or $stnk == '' or $bpkb == '' or $kuasa == '' or $ckd == '' or $pem == '') {
        echo "
        <tr>             
          <td>$isi->no_mesin</td> 
          <td>$re->no_rangka</td> 
          <td>$nama_konsumen</td> 
          <td>$alamat</td> 
          <td>" . number_format($biaya_bbn_md, 0, ',', '.') . "</td>";
        echo "<td align='center'>
            <input type='hidden' value='$no' name='no'>
            <input type='hidden' value='$isi->id_sales_order' name='id_sales_order_$no'>
            <input type='hidden' value='$isi->no_spk' name='no_spk_$no'>
            <input type='hidden' value='$biaya_bbn_md' name='biaya_bbn_$no'>
            <input type='hidden' value='$biaya_bbn_md' name='biaya_bbn_md_$no'>
            <input type='hidden' value='$ra->harga_unit' name='harga_unit_$no'>
            <input type='hidden' value='$isi->no_mesin' name='no_mesin_$no'>
            <input type='hidden' value='$re->no_rangka' name='no_rangka_$no'>
            <input type='hidden' value='$alamat' name='alamat_$no'>
            <input type='hidden' value='$nama_konsumen' name='nama_konsumen_$no'>
            <input type='checkbox' name='check_ktp_$no' $ktp>
          </td>      
          <td align='center'>
            <input type='checkbox' name='check_fisik_$no' $fisik>
          </td>      
          <td align='center'>
            <input type='checkbox' name='check_stnk_$no' $stnk>
          </td>      
          <td align='center'>
            <input type='checkbox' name='check_bpkb_$no' $bpkb>
          </td>      
          <td align='center'>
            <input type='checkbox' name='check_kuasa_$no' $kuasa>
          </td>      
          <td align='center'>
            <input type='checkbox' name='check_ckd_$no' $ckd>
          </td>      
          <td align='center'>
            <input type='checkbox' name='check_permohonan_$no' $pem>
          </td>      
        </tr>";
        $no++;
      }
    }
    ?>
    <?php
    $no_gc = 1;
    foreach ($dt_stnk_gc->result() as $isi) {
      $rw = $this->m_admin->getByID("tr_faktur_stnk_detail", "no_mesin", $isi->no_mesin);
      if ($rw->num_rows() > 0) {
        $row = $rw->row();
        if ($row->ktp == 'ya') $ktp = "checked";
        else $ktp = "";
        if ($row->fisik == 'ya') $fisik = "checked";
        else $fisik = "";
        if ($row->stnk == 'ya') $stnk = "checked";
        else $stnk = "";
        if ($row->bpkb == 'ya') $bpkb = "checked";
        else $bpkb = "";
        if ($row->kuasa == 'ya') $kuasa = "checked";
        else $kuasa = "";
        if ($row->ckd == 'ya') $ckd = "checked";
        else $ckd = "";
        if ($row->permohonan == 'ya') $pem = "checked";
        else $pem = "";
      } else {
        $ktp = "";
        $fisik = "";
        $stnk = "";
        $bpkb = "";
        $kuasa = "";
        $ckd = "";
        $pem = "";
      }
      $er = $this->db->query("SELECT * FROM tr_spk_gc INNER JOIN tr_spk_gc_detail ON tr_spk_gc.no_spk_gc = tr_spk_gc_detail.no_spk_gc 
        WHERE tr_spk_gc.no_spk_gc = '$isi->no_spk_gc'");
      if ($er->num_rows() > 0) {
        $ts = $er->row();
        $alamat = $ts->alamat;
        $harga_unit = $ts->harga;
      } else {
        $alamat = "";
        $harga_unit = "";
      }
      $ar = $this->db->query("SELECT * FROM tr_sales_order_gc_nosin WHERE no_mesin = '$isi->no_mesin'");
      if ($ar->num_rows() > 0) {
        $ts = $ar->row();
        $nama_konsumen = $ts->nama_stnk;
      } else {
        $nama_konsumen = "";
      }
      $re = $this->m_admin->getByID("tr_scan_barcode", "no_mesin", $isi->no_mesin);
      $no_rangka = ($re->num_rows() > 0) ? $re->row()->no_rangka : "";
      $tipe_motor = ($re->num_rows() > 0) ? $re->row()->tipe_motor : "";
      $ra = $this->m_admin->getByID("tr_sales_order_gc", "no_spk_gc", $isi->no_spk_gc)->row();
      $tr = $this->m_admin->getByID("ms_bbn_dealer", "id_tipe_kendaraan", $tipe_motor);
      if ($tr->num_rows() > 0) {
        $t = $tr->row();
        $cek_swasta = $this->db->query("SELECT tr_prospek_gc.jenis FROM tr_prospek_gc JOIN tr_spk_gc ON tr_prospek_gc.id_prospek_gc = tr_spk_gc.id_prospek_gc
          WHERE tr_spk_gc.no_spk_gc = '$isi->no_spk_gc'");
        if ($cek_swasta->num_rows() > 0) {
          $jenis = $cek_swasta->row()->jenis;
          if ($jenis == 'Swasta/BUMN/Koperasi' || $jenis == 'Joint Promo') {
            $biaya_bbn_gc = $t->biaya_bbn;
          } else {
            $biaya_bbn_gc = $t->biaya_instansi;
          }
        } else {
          $biaya_bbn_gc = 0;
        }
      } else {
        $biaya_bbn_gc = 0;
      }
      if ($ktp == '' or $fisik == '' or $stnk == '' or $bpkb == '' or $kuasa == '' or $ckd == '' or $pem == '') {
        echo "
        <tr>             
          <td>$isi->no_mesin</td>         
          <td>$no_rangka</td> 
          <td>$nama_konsumen</td> 
          <td>$alamat</td> 
          <td>" . number_format($biaya_bbn_gc, 0, ',', '.') . "</td>";
        echo "<td align='center'>
            <input type='hidden' value='$no_gc' name='no_gc'>
            <input type='hidden' value='$isi->id_sales_order_gc' name='id_sales_order_gc_$no_gc'>
            <input type='hidden' value='$isi->no_spk_gc' name='no_spk_gc_$no_gc'>
            <input type='hidden' value='$biaya_bbn_gc' name='biaya_bbn_gc_$no_gc'>
            <input type='hidden' value='$biaya_bbn_gc' name='biaya_bbn_md_gc_$no_gc'>
            <input type='hidden' value='$harga_unit' name='harga_unit_gc_$no_gc'>
            <input type='hidden' value='$isi->no_mesin' name='no_mesin_gc_$no_gc'>
            <input type='hidden' value='$no_rangka' name='no_rangka_gc_$no_gc'>
            <input type='hidden' value='$alamat' name='alamat_gc_$no_gc'>
            <input type='hidden' value='$nama_konsumen' name='nama_konsumen_gc_$no_gc'>
            <input type='checkbox' name='check_ktp_gc_$no_gc' $ktp>
          </td>      
          <td align='center'>
            <input type='checkbox' name='check_fisik_gc_$no_gc' $fisik>
          </td>      
          <td align='center'>
            <input type='checkbox' name='check_stnk_gc_$no_gc' $stnk>
          </td>      
          <td align='center'>
            <input type='checkbox' name='check_bpkb_gc_$no_gc' $bpkb>
          </td>      
          <td align='center'>
            <input type='checkbox' name='check_kuasa_gc_$no_gc' $kuasa>
          </td>      
          <td align='center'>
            <input type='checkbox' name='check_ckd_gc_$no_gc' $ckd>
          </td>      
          <td align='center'>
            <input type='checkbox' name='check_permohonan_gc_$no_gc' $pem>
          </td>
        </tr>";
        $no_gc++;
      }
    }
    ?>
  </tbody>
</table>