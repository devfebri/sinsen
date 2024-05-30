<table class='table table-bordered table-hover' id="example4">
  <thead>
    <tr>
      <th>Kode Tipe Kendaraan Honda</th>
      <th>Nama Tipe Kendaraan Honda</th>
      <th>Qty Penjualan Honda</th>
      <th>Tipe Kendaraan Yamaha</th>
      <th>Qty Penjualan Yamaha</th>
      <th>Tipe Kendaraan Suzuki</th>                    
      <th>Qty Penjualan Suzuki</th>                    
      <th>Tipe Kendaraan Kawasaki</th>                    
      <th>Qty Penjualan Kawasaki</th>                    
    </tr>                  
  </thead>
  <tbody>
    <?php 
    $dt_tipe = $this->db->query("SELECT * FROM ms_tipe_kendaraan WHERE id_segment = '$id_segment' AND id_kategori = '$id_kategori'");
    foreach ($dt_tipe->result() as $isi) {
      echo "
        <tr>
          <td>
            <input type='text' readonly placeholder='ID Tipe Kendaraan' value='$isi->id_tipe_kendaraan' id='tipe_ahm' name='id_tipe_kendaraan[]' class='form-control isi'>
          </td>
          <td>
            <input type='text' readonly placeholder='Nama Tipe' id='tipe_ahm' value='$isi->tipe_ahm' name='tipe_ahm[]' class='form-control isi'>
          </td>
          <td>
            <input type='text' placeholder='Qty Honda' name='qty_honda[]' class='form-control isi' value='0'>
          </td>
          <td>
            <input type='text' placeholder='Tipe Yamaha' name='tipe_yamaha[]' class='form-control isi'>
          </td>
          <td>
            <input type='text' placeholder='Qty Yamaha' name='qty_yamaha[]' class='form-control isi' value='0'>
          </td>
          <td>
            <input type='text' placeholder='Tipe Suzuki' name='tipe_suzuki[]' class='form-control isi'>
          </td>
          <td>
            <input type='text' placeholder='Qty Suzuki' name='qty_suzuki[]' class='form-control isi' value='0'>
          </td>
          <td>
            <input type='text' placeholder='Tipe Kawasaki' name='tipe_kawasaki[]' class='form-control isi'>
          </td>
          <td>
            <input type='text' placeholder='Qty Kawasaki' name='qty_kawasaki[]' class='form-control isi' value='0'>
          </td>
        </tr>


      ";
    }
    ?>    
  </tbody>
</table>