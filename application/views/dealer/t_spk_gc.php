<?php if ($jenis == "prospek") { ?>
  <table class="table table-bordered table-hover myTable1">
    <thead>
      <tr>
        <th>Tipe Kendaraan</th>
        <th>Warna</th>
        <th>Qty</th>
        <th>Tahun</th>
        <th>Total Harga Per Unit</th>
        <th>Total Harga Per Type-Warna</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $total = 0;
      $no = 1;
      foreach ($detail->result() as $rs) {
        $biaya_bbn   = $rs->biaya_bbn;
        $harga_jual  = $rs->harga_jual;
        $harga       = $rs->harga;
        $ppn         = $rs->ppn;
        $harga_on    = $rs->harga_on;
        $harga_tunai = $rs->harga_tunai;
        $harga_asli  = $rs->harga_asli;

      ?>
        <tr>
          <td><?= $rs->id_tipe_kendaraan . " | " . $rs->tipe_ahm ?></td>
          <td><?= $rs->id_warna . " | " . $rs->warna ?></td>
          <td><?= $rs->qty ?></td>
          <td><?= $rs->tahun ?></td>
          <td align="right"><?= mata_uang_rp($harga_asli) ?></td>
          <td align="right"><?= mata_uang_rp($grand = $rs->qty * $harga_asli) ?></td>
          <td>
            <input type="hidden" name="jumlah_detail" value="<?php echo $detail->num_rows() ?>">
            <input type="hidden" name="id_tipe_kendaraan2_<?php echo $no ?>" value="<?php echo $rs->id_tipe_kendaraan ?>">
            <input type="hidden" name="id_warna2_<?php echo $no ?>" value="<?php echo $rs->id_warna ?>">
            <input type="hidden" name="qty2_<?php echo $no ?>" value="<?php echo $rs->qty ?>">
            <input type="hidden" name="tahun2_<?php echo $no ?>" value="<?php echo $rs->tahun ?>">
            <input type="hidden" name="total_unit_<?php echo $no ?>" value="<?php echo $harga_asli ?>">
            <input type="hidden" name="total_harga_<?php echo $no ?>" value="<?php echo $grand ?>">

            <button type="button" class="btn btn-danger btn-xs btn-flat" title="Add" onclick="delDetail(<?= $rs->id_prospek_gc_kendaraan ?>)"><i class="fa fa-trash"></i></button>
            <button type="button" class="btn btn-warning btn-flat btn-xs" data-toggle="modal" data-target=".modal_edit" id="<?php echo $rs->id_prospek_gc_kendaraan ?>" onclick="edit_popup('<?php echo $rs->id_prospek_gc_kendaraan ?>')"><i class="fa fa-edit"></i></button>
          </td>
        </tr>
      <?php
        $no++;
        $total += $grand;
      }
      ?>
    </tbody>
    <tfoot>
      <tr>
        <td>

          <select class="form-control select3 is-ev-check" name="id_tipe_kendaraan" id="id_tipe_kendaraan_gc" onchange="getWarna_gc()">
            <?php if ($dt_tipe->num_rows() > 0) : ?>
              <option value="">- choose -</option>
              <?php foreach ($dt_tipe->result() as $rs) : ?>
                <option value="<?= $rs->id_tipe_kendaraan ?>"><?= $rs->id_tipe_kendaraan ?> | <?= $rs->tipe_ahm ?></option>
              <?php endforeach ?>
            <?php endif ?>
          </select>
        </td>
        <td>
          <select class="form-control select2" name="id_warna" id="id_warna_gc"></select>
        </td>
        <td>
          <input type="text" autocomplete="off" class="form-control" id="qty_gc" placeholder="QTY">
        </td>
        <td>
          <input type="text" autocomplete="off" class="form-control" id="tahun_gc" placeholder="Tahun">
        </td>
        <td></td>
        <td></td>
        <td>
          <button type="button" class="btn btn-primary btn-xs btn-flat" title="Add" onclick="addDetail()"><i class="fa fa-plus"></i></button>

        </td>
      </tr>
      <tr>
        <td colspan="5"></td>
        <td align='right'><b><?php echo mata_uang_rp($total) ?></b></td>
        <td></td>
      </tr>
    </tfoot>
  </table>
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/sweetalert2/sweetalert2.min.css">
  <script type="text/javascript" src="<?php echo base_url() ?>assets/sweetalert2/sweetalert2.min.js"></script>

  <script>
    $(document).ready(function() {
      $('select.is-ev-check').change(function() {
        var selectedValue = $(this).val();
        var valuesArray = ['ME0', 'MH0'];

        var found = valuesArray.some(function(value) {
        return $('select.is-ev-check').val() === value;
       });

       if (found) {
        Swal.fire('Note ', 'Kategori Kendaraan EV | Maksimal 1 Unit EV dalam 1 SPK', 'info');
     }

    });
    });
  </script>


<?php } else { ?>
  <table class="table table-bordered table-hover myTable1">
    <thead>
      <tr>
        <th>Tipe Kendaraan</th>
        <th>Warna</th>
        <th>Qty</th>
        <th>Tahun</th>
        <th>Total Harga Per Unit</th>
        <th>Total Harga Per Type-Warna</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $g = 0;
      foreach ($detail->result() as $isi) {
        $cari_bbn = $this->m_admin->getByID("tr_spk_gc_detail", "no_spk_gc", $isi->no_spk_gc);
        $bbn = ($cari_bbn->num_rows() > 0) ? $cari_bbn->row()->biaya_bbn : "";
        $harga = ($cari_bbn->num_rows() > 0) ? $cari_bbn->row()->harga : "";
        $detail2 = $this->db->query("SELECT tr_spk_gc_kendaraan.*,ms_tipe_kendaraan.tipe_ahm,ms_warna.warna FROM tr_spk_gc_kendaraan
          LEFT JOIN ms_tipe_kendaraan on tr_spk_gc_kendaraan.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
          LEFT JOIN ms_warna ON tr_spk_gc_kendaraan.id_warna = ms_warna.id_warna
          WHERE no_spk_gc='$isi->no_spk_gc' AND ms_tipe_kendaraan.id_tipe_kendaraan = '$isi->id_tipe_kendaraan'")->row();

        $total_bbn = $bbn * $isi->qty;
        echo "
      <tr>
        <td>$isi->tipe_ahm</td>
        <td>$isi->warna</td>
        <td>$isi->qty</td>
        <td>$detail2->tahun_produksi</td>
        <td>" . mata_uang_rp($harga_unit = $detail2->total_unit) . "</td>
        <td>" . mata_uang_rp($ga = $harga_unit * $isi->qty) . "</td>
      </tr>";
        $g += $ga;
      }
      ?>
      <tr>
        <td colspan="5"></td>
        <td><?php echo mata_uang_rp($g) ?></td>
      </tr>
    </tbody>
  <?php } ?>