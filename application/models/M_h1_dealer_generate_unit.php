<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_dealer_generate_unit extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getUnitSalesDelivery($filter)
  {
    $id_dealer = dealer()->id_dealer;
    $where_id = "WHERE so.id_dealer = '$id_dealer'";

    $where_gc = "WHERE so_gc.id_dealer = '$id_dealer'";
    if (isset($filter['tgl_pengiriman'])) {
      $where_id .= " AND so.tgl_pengiriman='{$filter['tgl_pengiriman']}'";
      $where_gc .= " AND so_gc.tgl_pengiriman='{$filter['tgl_pengiriman']}'";
    }
    if (isset($filter['id_master_plat'])) {
      $where_id .= " AND so.id_master_plat='{$filter['id_master_plat']}'";
      $where_gc .= " AND so_gc_n.id_master_plat='{$filter['id_master_plat']}'";
    }
    if (isset($filter['id_generate'])) {
      $where_id .= " AND gludd.id_generate='{$filter['id_generate']}'";
      $where_gc .= " AND gludd.id_generate='{$filter['id_generate']}'";
    }
    if (isset($filter['ready_delivery'])) {
      // perlu cek utk and id_generate
      $where_id .= " AND (so.status_delivery='back_to_dealer' OR so.status_delivery='ready') and gludd.id_generate is null ";
      $where_gc .= " AND (so_gc_n.status_delivery='back_to_dealer' OR so_gc_n.status_delivery='ready') and gludd.id_generate is null ";
    }

    $ksu_id = "SELECT GROUP_CONCAT(ksu SEPARATOR ', ') ksu FROM ms_koneksi_ksu_detail AS ksd
    JOIN ms_koneksi_ksu ON ksd.id_koneksi_ksu=ms_koneksi_ksu.id_koneksi_ksu
    JOIN ms_ksu ON ksd.id_ksu=ms_ksu.id_ksu
    WHERE id_tipe_kendaraan=tr_spk.id_tipe_kendaraan";

    $ksu_gc = "SELECT GROUP_CONCAT(ksu SEPARATOR ', ') ksu FROM ms_koneksi_ksu_detail AS ksd
    JOIN ms_koneksi_ksu ON ksd.id_koneksi_ksu=ms_koneksi_ksu.id_koneksi_ksu
    JOIN ms_ksu ON ksd.id_ksu=ms_ksu.id_ksu
    WHERE id_tipe_kendaraan=sc.tipe_motor";

    $sales_id = "SELECT CONCAT(tr_prospek.id_flp_md,' - ',nama_lengkap)as sales FROM tr_prospek 
    JOIN ms_karyawan_dealer ON tr_prospek.id_karyawan_dealer=ms_karyawan_dealer.id_karyawan_dealer
    WHERE id_customer=tr_spk.id_customer ORDER BY tr_prospek.created_at DESC LIMIT 1";

    return $this->db->query("SELECT * FROM(
      SELECT gludd.id_generate, so.id_sales_order,so.no_mesin,so.no_rangka,tr_spk.nama_konsumen,tr_spk.id_tipe_kendaraan,tr_spk.id_warna,($ksu_id) AS ksu,($sales_id) AS sales,so.no_hp_penerima,so.tgl_pengiriman,so.waktu_pengiriman,so.lokasi_pengiriman,so.created_at,tipe_ahm,wr.warna,so.notif_sms_bastk_status,'individu' AS jenis_so,
      case when sob.serial_number is not null then 1 else 0 end as is_ev, so.no_spk
			FROM tr_sales_order AS so
			JOIN tr_spk ON so.no_spk=tr_spk.no_spk
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=tr_spk.id_tipe_kendaraan
      JOIN ms_warna wr ON wr.id_warna=tr_spk.id_warna
      LEFT JOIN tr_generate_list_unit_delivery_detail gludd ON gludd.no_mesin=so.no_mesin
      left JOIN tr_sales_order_acc_ev sob on so.no_mesin = sob.no_mesin  
      left join tr_stock_battery sb on sob.serial_number = sb.serial_number 
			$where_id
      UNION
      SELECT gludd.id_generate, so_gc.id_sales_order_gc,so_gc_n.no_mesin,sc.no_rangka,spk_gc.nama_npwp,sc.tipe_motor,sc.warna,($ksu_gc) AS ksu,CONCAT(kd.id_flp_md,'-',kd.nama_lengkap) AS sales,so_gc.no_hp_penerima,so_gc.tgl_pengiriman,so_gc.waktu_pengiriman,so_gc.lokasi_pengiriman,so_gc.created_at,tk.tipe_ahm,wr.warna,so_gc_n.notif_sms_bastk_status,'gc' AS jenis_so,
      case when sob.serial_number is not null then 1 else 0 end as is_ev, so_gc.no_spk_gc as no_spk
      FROM tr_sales_order_gc_nosin so_gc_n
      JOIN tr_scan_barcode sc ON sc.no_mesin=so_gc_n.no_mesin
      JOIN tr_sales_order_gc so_gc ON so_gc.id_sales_order_gc=so_gc_n.id_sales_order_gc
      JOIN tr_spk_gc spk_gc ON spk_gc.no_spk_gc=so_gc.no_spk_gc
      JOIN tr_prospek_gc prp_gc ON prp_gc.id_prospek_gc=spk_gc.id_prospek_gc
      JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=prp_gc.id_karyawan_dealer
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=sc.tipe_motor
      JOIN ms_warna wr ON wr.id_warna=sc.warna
      LEFT JOIN tr_generate_list_unit_delivery_detail gludd ON gludd.no_mesin=so_gc_n.no_mesin
      left JOIN tr_sales_order_acc_ev sob on so_gc_n.no_mesin = sob.no_mesin  
      left join tr_stock_battery sb on sob.serial_number = sb.serial_number 
      $where_gc
    )AS tabel
    ");
  }

  public function get_id_generate()
  {
    $th       = date('Y');
    $bln      = date('m');
    $th_bln   = date('Y-m');
    $th_kecil = date('y');
    $id_dealer = $this->m_admin->cari_dealer();
    // $id_sumber='E20';
    // if ($id_dealer!=null) {
    $dealer    = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $id_sumber = $dealer->kode_dealer_md;
    // }
    $get_data  = $this->db->query("SELECT * FROM tr_generate_list_unit_delivery
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $id_generate = substr($row->id_generate, -5);
      $new_kode = 'UD-' . $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $id_generate + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_generate_list_unit_delivery', ['id_generate' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -5);
          $new_kode = 'UD-' . $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = 'UD-' . $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . '00001';
    }
    return strtoupper($new_kode);
  }

  public function get_list_driver_schedule($date,$dealer){
    $temp_date = $date;
    $temp_date = date('Y-m-d', strtotime($date. ' -31 day'));

    $where = "WHERE 1=1";
    if (isset($dealer)) {
      $where .= " AND b.id_dealer='$dealer'";
    }

    $query = $this->db->query("
		select a.id_generate , a.id_sales_order ,  b.id_master_plat, b.created_at , b.id_dealer , a.no_mesin , b.tgl_pengiriman , b.status, c.id_item, d.tipe_ahm , e.warna, f.driver , f.no_plat, z.* 
		from tr_generate_list_unit_delivery_detail a
		join tr_generate_list_unit_delivery b on a.id_generate  = b.id_generate 
		join tr_scan_barcode c on a.no_mesin  = c.no_mesin 
		join ms_tipe_kendaraan d on c.tipe_motor = d.id_tipe_kendaraan 
		join ms_warna e on e.id_warna = c.warna 
		join ms_plat_dealer f on f.id_master_plat  = b.id_master_plat 
		join (
			select y.no_mesin , b.nama_konsumen , b.id_kecamatan , b.alamat , c.kecamatan 
			from tr_sales_order y
			join tr_spk b on y.no_spk = b.no_spk 
			join ms_kecamatan c on c.id_kecamatan = b.id_kecamatan 
			where y.id_dealer = '$dealer' and y.tgl_cetak_invoice > '$temp_date'
			union
			select x.no_mesin , c.nama_npwp , c.id_kecamatan , c.alamat , d.kecamatan 
			from tr_sales_order_gc_nosin x 
			join tr_sales_order_gc b on x.id_sales_order_gc = b.id_sales_order_gc 
			join tr_spk_gc c on c.no_spk_gc = b.no_spk_gc 
			join ms_kecamatan d on d.id_kecamatan = c.id_kecamatan 
			where b.id_dealer = '$dealer' and b.tgl_cetak_invoice > '$temp_date'
		)z on z.no_mesin = c.no_mesin 
		$where and b.tgl_pengiriman ='$date' 
		order by z.nama_konsumen asc
	");

        if($query->num_rows() > 0) {
            return $query->result();
        }else{
            return false;
        }
  }

}
