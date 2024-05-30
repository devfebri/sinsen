 
 <table id="example" class="table table-bordered table-hover">
  <thead>
    <tr>                      
      <th width="10%">Kode Item</th>              
      <th width="20%">Tipe</th>              
      <th width="10%">Warna</th>
      <th width="10%">Qty DO</th>
      <th width="5%">Qty Pengambilan</th>                                    
    </tr>
  </thead>
  <tbody>    
    <?php 
    $i=1;
    foreach ($dt_pl->result() as $row) {
      $jum = $dt_pl->num_rows();
      $cek_do_sppm = $this->db->query("SELECT *,sum(qty_ambil) as qty FROM tr_sppm
            LEFT JOIN tr_sppm_detail on tr_sppm.no_surat_sppm=tr_sppm_detail.no_surat_sppm
            WHERE no_do='$row->no_do' AND id_item='$row->id_item'");

      $cek_do_sj = $this->db->query("SELECT *,count(tr_surat_jalan_detail.no_mesin) as qty FROM tr_surat_jalan_detail
            INNER JOIN tr_surat_jalan on tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
            WHERE tr_surat_jalan.no_picking_list = '$row->no_picking_list' AND tr_surat_jalan_detail.id_item = '$row->id_item'
            AND tr_surat_jalan_detail.ceklist = 'ya'");
      $cek_sj = $this->db->query("SELECT * FROM tr_surat_jalan_detail
            INNER JOIN tr_surat_jalan on tr_surat_jalan.no_surat_jalan = tr_surat_jalan_detail.no_surat_jalan
            WHERE tr_surat_jalan.no_picking_list = '$row->no_picking_list' AND tr_surat_jalan_detail.id_item = '$row->id_item'");

      // $cek_do_sppm->num_rows()>0?$cek_do_sppm=$cek_do_sppm->row()->qty:$cek_do_sppm=0;      
      // $cek_do_sj->num_rows()>0?$cek_do_sj=$cek_do_sj->row()->qty:$cek_do_sj=0;

      $cek_do_sj = ($cek_do_sj->num_rows() > 0) ? $cek_do_sj->row()->qty : 0 ;
      $cek_do_sppm = ($cek_do_sppm->num_rows() > 0) ? $cek_do_sppm->row()->qty : 0 ;
      
      $id_dealer = $this->m_admin->cari_dealer();
      $cek = $this->m_admin->getByID("ms_dealer","id_dealer",$id_dealer);
      if($cek->num_rows() > 0){
        $r = $cek->row();
        if($r->bisa_pilih_unit_do == 'Ya'){
          $mode = "";
        }else{
          //$mode = "readonly";
          $mode = "";
        }
      }

      if($cek_sj->num_rows() > 0){
        $qty_do = $row->qty_do - $cek_do_sj;
      }else{        
        $qty_do = $row->qty_do - $cek_do_sppm;
      }
      $qty_sudah = $cek_do_sppm - $cek_do_sj;
      $dis = "";
      if($qty_do <= 0){
        $dis = 'readonly';
      }
      // echo "
      // <tr>
      //   <td>$row->id_item</td>
      //   <td>$row->tipe_ahm ($row->id_tipe_kendaraan)</td>
      //   <td>$row->warna ($row->id_warna)</td>
      //   <td>$qty_do</td>
      //   <td>
      //     <input type='hidden' name='id_item[]' value='$row->id_item'>
      //     <input type='hidden' name='qty_do[]' value='$qty_do'>
      //     <input type='text' required class='form-control isi' $dis name='qty_ambil[]' $mode value='$qty_do'>
      //   </td>
      // </tr>
      // ";
      echo "
      <tr>
        <td>$row->id_item</td>
        <td>$row->tipe_ahm ($row->id_tipe_kendaraan)</td>
        <td>$row->warna ($row->id_warna)</td>
        <td>$qty_do</td>
        <td>
          <input type='hidden' name='id_item_$i' value='$row->id_item'>
          <input type='hidden' name='qty_do_$i' value='$qty_do'>
          <input type='hidden' name='jum' value='$jum'>
          <input type='text' required class='form-control isi' $dis name='qty_ambil_$i' $mode value='$qty_do'>
        </td>
      </tr>
      ";
      $i++;
    }
    ?>
                 
  </tbody>
</table> 