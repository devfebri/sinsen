<?php 
function mata_uang2($a){
    	// if(preg_match("/^[0-9,]+$/", $a)) $a = str_replace(',', '', $a);
    if ($a==''|| $a==null) {
      $a =0;
    }
    $a = preg_replace('/[^0-9\  ]/', '', $a);
    return number_format($a, 0, ',', '.');
  return $a;
}
?>

<table id="example2" class="table table-hover table-bordered myTable1" width="100%">
  <tr>
    <td></td>
  </tr>
  <tr>
    <th>No Account</th>
    <th>Jenis Transaksi</th>                    
    <th>Referensi</th>
    <th>Nominal</th>
    <th>Dibayar Kepada</th>
    <th>Sisa Piutang</th>
    <th>Keterangan</th>                    
  </tr>
  <?php 
  foreach ($dt_detail->result() as $isi) {
    $cek_claim =$this->db->get_where('tr_claim_sales_program', ['id_claim_sp'=>$isi->referensi]);
    $referensi = $cek_claim->num_rows()>0?$cek_claim->row()->id_program_md:$isi->referensi;
    $nominal = $isi->nominal;
    $dibayar = $isi->dibayar;
    if($isi->tipe_customer == 'Dealer'){
      $dealer = $this->m_admin->getByID("ms_dealer","id_dealer",$isi->dibayar)->row();
      $dibayar = $dealer->nama_dealer;      
    }

    // $nominal   = $isi->nominal==NULL?0:$isi->nominal
    echo "
    <tr>
    <td>$isi->kode_coa</td>
    <td>$isi->coa</td>
    <td>$referensi $isi->tipe_customer  </td>
    <td align='right'>".mata_uang2($nominal)."</td>
    <td>$dibayar</td>    
    <td align='right'>".mata_uang2($isi->sisa_hutang)."</td>
    <td>$isi->keterangan</td>
    </tr>
    ";
  }
  ?>
</table>