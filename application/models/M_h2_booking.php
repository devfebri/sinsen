<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_booking extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function fetch_booking($filter)
  {
    // send_json($filter);
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $order_column = array('id_booking ', 'nama_customer', 'alamat', 'no_polisi', 'id_tipe_kendaraan', 'id_warna', 'no_mesin', 'no_rangka', 'tgl_servis', 'jam_servis', 'tr_h2_manage_booking.created_at', null);


    $set_filter   = "WHERE 1=1 AND tr_h2_manage_booking.id_dealer='$id_dealer' ";

    $join = '';
    if (isset($filter['left_join_queue'])) {
      if ($filter['left_join_queue'] != '') {
        $join = " JOIN tr_h2_sa_form sa ON sa.id_booking=tr_h2_manage_booking.id_booking ";
      }
    }
    if (isset($filter['id_antrian_null'])) {
      if ($filter['id_antrian_null'] != '') {
        $set_filter .= " AND sa.id_booking IS NULL ";
      }
    }

    if (isset($filter['tahun_bulan_servis'])) {
      if ($filter['tahun_bulan_servis'] != '') {
        $set_filter .= " AND LEFT(tr_h2_manage_booking.tgl_servis,7)='{$filter['tahun_bulan_servis']}' ";
      }
    }


    if (isset($filter['id_booking_int'])) {
      if ($filter['id_booking_int'] != '') {
        $set_filter .= " AND tr_h2_manage_booking.id_booking_int='{$filter['id_booking_int']}' ";
      }
    }
    if (isset($filter['id_pit'])) {
      if ($filter['id_pit'] != '') {
        $set_filter .= " AND tr_h2_manage_booking.id_pit='{$filter['id_pit']}' ";
      }
    }
    if (isset($filter['tgl_booking'])) {
      if ($filter['tgl_booking'] != '') {
        $set_filter .= " AND LEFT(tr_h2_manage_booking.created_at,10)='{$filter['tgl_booking']}' ";
      }
    }
    if (isset($filter['tgl_servis'])) {
      if ($filter['tgl_servis'] != '') {
        $set_filter .= " AND tr_h2_manage_booking.tgl_servis='{$filter['tgl_servis']}' ";
      }
    }
    if (isset($filter['jam_servis_like'])) {
      if ($filter['jam_servis_like'] != '') {
        $set_filter .= " AND tr_h2_manage_booking.jam_servis LIKE '{$filter['jam_servis_like']}%' ";
      }
    }
    if (isset($filter['no_polisi'])) {
      if ($filter['no_polisi'] != '') {
        $set_filter .= " AND ms_customer_h23.no_polisi='{$filter['no_polisi']}' ";
      }
    }
    if (isset($filter['wo_not_null'])) {
      if ($filter['wo_not_null'] != '') {
        $set_filter .= " AND wo.id_work_order IS NOT NULL ";
      }
    }
    if (isset($filter['status_booking'])) {
      $status_booking = implode(', ', $filter['status_booking']);
      $set_filter .= " AND (tr_h2_manage_booking.status IN($status_booking) OR tr_h2_manage_booking.status='' ";
    }
    if (isset($filter['status_booking_not'])) {
      $status_booking = $filter['status_booking_not'];
      $set_filter .= " AND tr_h2_manage_booking.status!='$status_booking'";
    }
    if (isset($filter['tgl_servis_lebih_kecil'])) {
      $set_filter .= " OR tr_h2_manage_booking.tgl_servis< '{$filter['tgl_servis_lebih_kecil']}'";
    }

    if (isset($filter['id_sa_form_not_null'])) {
      if ($filter['id_sa_form_not_null'] == 1) {
        $set_filter .= " OR sa_f.id_sa_form IS NOT NULL) ";
      }
    }
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $set_filter .= " AND (ms_customer_h23.no_mesin LIKE '%$search%'
                            OR nama_customer LIKE '%$search%'
                            OR tk.id_tipe_kendaraan LIKE '%$search%'
                            OR ms_warna.id_warna LIKE '%$search%'
                            ) 
            ";
      }
    }

    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $set_filter .= " ORDER BY $order_clm $order_by ";
      }
    }
    if (isset($filter['limit'])) {
      $set_filter .= $filter['limit'];
    }
    if (isset($filter['offset'])) {
      $page = $filter['offset'];
      $page = $page < 0 ? 0 : $page;
      $length = $filter['length'];
      $start = $length * $page;
      $set_filter .= "LIMIT $start, $length";
    }

    $id_pit_int = "SELECT id_pit_int FROM ms_h2_pit pit WHERE pit.id_pit=tr_h2_manage_booking.id_pit AND pit.id_dealer=tr_h2_manage_booking.id_dealer LIMIT 1";
    $id_pit_int = '';
    $select = "ms_customer_h23.id_customer_int,  ms_customer_h23.id_customer,  ms_customer_h23.nama_customer,  ms_customer_h23.jenis_kelamin,  ms_customer_h23.no_identitas,  ms_customer_h23.jenis_identitas,  ms_customer_h23.alamat_identitas,  ms_customer_h23.no_hp,  ms_customer_h23.email,  ms_customer_h23.alamat,  ms_customer_h23.id_kelurahan,  ms_customer_h23.id_tipe_kendaraan,  ms_customer_h23.id_warna,  ms_customer_h23.no_mesin,  ms_customer_h23.no_rangka,  ms_customer_h23.tahun_produksi,  ms_customer_h23.no_polisi,  ms_customer_h23.id_cdb,  ms_customer_h23.no_spk,  ms_customer_h23.id_dealer,  ms_customer_h23.created_at,  ms_customer_h23.created_by,  ms_customer_h23.updated_at,  ms_customer_h23.updated_by,  ms_customer_h23.ganti_customer,  ms_customer_h23.tgl_pembelian,  ms_customer_h23.nama_stnk,  ms_customer_h23.jenis_customer_beli,  ms_customer_h23.id_agama,  ms_customer_h23.id_kelurahan_identitas,  ms_customer_h23.longitude,  ms_customer_h23.latitude,  ms_customer_h23.id_dealer_h1,  ms_customer_h23.id_kecamatan,  ms_customer_h23.id_kabupaten,  ms_customer_h23.id_provinsi,  ms_customer_h23.tgl_lahir,  ms_customer_h23.id_pekerjaan,  ms_customer_h23.jangka_waktu_top,  ms_customer_h23.facebook,  ms_customer_h23.twitter,  ms_customer_h23.instagram,tr_h2_manage_booking.id_booking,  tr_h2_manage_booking.id_dealer,  tr_h2_manage_booking.id_customer,  tr_h2_manage_booking.tgl_servis,  tr_h2_manage_booking.jam_servis,  tr_h2_manage_booking.id_pit,  tr_h2_manage_booking.id_type,  tr_h2_manage_booking.created_at,  tr_h2_manage_booking.created_by,  tr_h2_manage_booking.updated_at,  tr_h2_manage_booking.updated_by,  tr_h2_manage_booking.status,  tr_h2_manage_booking.alasan_cancel,  tr_h2_manage_booking.cancel_at,  tr_h2_manage_booking.cancel_by,  tr_h2_manage_booking.customer_from,  tr_h2_manage_booking.nama_pembawa,  tr_h2_manage_booking.keluhan,tr_h2_manage_booking.carrier_name,tr_h2_manage_booking.carrier_phone,CONCAT(ms_customer_h23.id_tipe_kendaraan,' - ',tipe_ahm) AS tipe_ahm, CONCAT(ms_customer_h23.id_warna,' | ',warna) AS warna,status_form, (wo.status) AS status_wo,tr_h2_manage_booking.created_at,id_antrian,LEFT(tr_h2_manage_booking.created_at,10) AS tgl_booking, RIGHT(tr_h2_manage_booking.created_at,8) AS jam_booking,sa_f.rekomendasi_sa,wo.id_work_order,wo.id_work_order_int,sa_f.km_terakhir,jst.deskripsi desc_type,id_booking_int,jst.color,'$id_pit_int' AS id_pit_int,wo.saran_mekanik,ms_warna.warna only_warna";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(tr_h2_manage_booking.id_booking) AS count";
      }
    }
    return $this->db->query("SELECT $select
    FROM tr_h2_manage_booking 
    LEFT JOIN ms_customer_h23 ON tr_h2_manage_booking.id_customer=ms_customer_h23.id_customer
    LEFT JOIN ms_tipe_kendaraan AS tk ON ms_customer_h23.id_tipe_kendaraan=tk.id_tipe_kendaraan
    LEFT JOIN ms_warna ON ms_customer_h23.id_warna=ms_warna.id_warna
    LEFT JOIN tr_h2_sa_form sa_f ON sa_f.id_booking=tr_h2_manage_booking.id_booking
    LEFT JOIN tr_h2_wo_dealer wo ON sa_f.id_sa_form=wo.id_sa_form
    LEFT JOIN ms_h2_jasa_type jst ON jst.id_type=tr_h2_manage_booking.id_type
    $join
    $set_filter
    ");
  }

  function fetch_historyQueue($filter)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $order_column = array('no_antrian ', 'tgl_servis', 'sa.jenis_customer', 'no_polisi', 'nama_customer', 'no_mesin', 'no_rangka', 'tipe_ahm', 'warna', 'tahun_produksi', null);
    $set_filter   = "WHERE 1=1 AND sa.id_dealer='$id_dealer' AND sa.id_sa_form IS NOT NULL ";
    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (ch23.no_mesin LIKE '%$search%'
                            OR nama_customer LIKE '%$search%'
                            OR ch23.no_polisi LIKE '%$search%'
                            OR tk.id_tipe_kendaraan LIKE '%$search%'
                            OR tk.tipe_ahm LIKE '%$search%'
                            OR ms_warna.id_warna LIKE '%$search%'
                            OR ms_warna.warna LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY sa.created_at DESC ";
    }
    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_antrian,tgl_servis,jenis_customer,no_polisi,nama_customer,ch23.no_mesin,no_rangka,CONCAT(ch23.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm, CONCAT(ch23.id_warna,' | ',warna) AS warna,tahun_produksi,status_form
    FROM tr_h2_sa_form sa 
    LEFT JOIN ms_customer_h23 ch23 ON sa.id_customer=ch23.id_customer
    JOIN ms_tipe_kendaraan AS tk ON ch23.id_tipe_kendaraan=tk.id_tipe_kendaraan
    JOIN ms_warna ON ch23.id_warna=ms_warna.id_warna
    LEFT JOIN tr_h2_wo_dealer wo ON sa.id_sa_form=wo.id_sa_form
    $set_filter
    ");
  }

  function getQueue($filter = null)
  {
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $id_antrian_short = "REPLACE(RIGHT(tr_h2_sa_form.id_antrian,5),'/','')";
    //WHERE
    $where = "WHERE tr_h2_sa_form.id_dealer='$id_dealer'";
    if ($filter != null) {
      if (isset($filter['id_antrian'])) {
        $where .= " AND id_antrian='{$filter['id_antrian']}'";
      }
      if (isset($filter['id_antrian_short'])) {
        $where .= " AND $id_antrian_short='{$filter['id_antrian_short']}'";
      }
      if (isset($filter['id_sa_form_not_null'])) {
        $where .= " AND tr_h2_sa_form.id_sa_form IS NOT NULL";
      }
      if (isset($filter['id_sa_form_null'])) {
        $where .= " AND tr_h2_sa_form.id_sa_form IS NULL";
      }
      if (isset($filter['id_wo_not_null'])) {
        $where .= " AND id_work_order IS NOT NULL";
      }
      if (isset($filter['id_wo_null'])) {
        $where .= " AND id_work_order IS NULL";
      }
      if (isset($filter['tgl_servis'])) {
        $where .= " AND tr_h2_sa_form.tgl_servis='{$filter['tgl_servis']}'";
      }
      if (isset($filter['status_form_is_null'])) {
        $where .= " AND tr_h2_sa_form.status_form IS NULL";
      }
      if (isset($filter['tgl_antrian'])) {
        $where .= " AND LEFT(tr_h2_sa_form.created_at,10)='{$filter['tgl_antrian']}'";
      }
      if (isset($filter['jenis_customer'])) {
        $where .= " AND tr_h2_sa_form.jenis_customer='{$filter['jenis_customer']}'";
      }
      if (isset($filter['status_form'])) {
        $where .= " AND tr_h2_sa_form.status_form='{$filter['status_form']}'";
      }
      if (isset($filter['job_return'])) {
        $where .= " AND tr_h2_sa_form.job_return='{$filter['job_return']}'";
      }
      if (isset($filter['status_wo'])) {
        $where .= " AND wo.status='{$filter['status_wo']}'";
      }
      if (isset($filter['status_wo_not'])) {
        $where .= " AND wo.status != '{$filter['status_wo_not']}'";
      }
      if (isset($filter['status_wo_null_not_closed'])) {
        $where .= " AND (wo.status IS NULL OR wo.status != 'closed')";
      }
      if (isset($filter['status_monitor_in'])) {
        $where .= " AND tr_h2_sa_form.status_monitor IN ({$filter['status_monitor_in']})";
      }
    }

    //SELECT
    $select = "tr_h2_sa_form.id_antrian,tr_h2_sa_form.jenis_customer,ms_customer_h23.no_rangka,tahun_produksi,keluhan_konsumen,tr_h2_sa_form.id_sa_form,tr_h2_sa_form.tgl_servis,ms_customer_h23.id_customer,nama_customer,tipe_ahm,warna,tr_h2_sa_form.id_type,waktu_kedatangan,tr_h2_sa_form.jam_servis,id_antrian_int,tr_h2_manage_booking.id_booking,
    status_monitor,
    CASE 
    WHEN status_monitor='antrian' THEN 'Waiting'
    ELSE status_monitor
    END AS status_queue,
    ktg_tk.kategori kategori_tk,
    $id_antrian_short AS id_antrian_short,no_mesin_antri,no_polisi_antri,
    lengkap, CASE WHEN no_mesin_antri IS NULL THEN 'no_polisi' ELSE 'no_mesin' END AS pilihan,
    CASE WHEN no_polisi IS NULL THEN no_polisi_antri ELSE no_polisi END no_polisi,
    CASE WHEN ms_customer_h23.no_mesin IS NULL THEN no_mesin_antri ELSE ms_customer_h23.no_mesin END no_mesin
    ";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "IFNULL(COUNT(id_antrian),0) AS count";
      }
    }

    //JOIN
    $join = "";
    if (isset($filter['join_wo'])) {
      $join .= "JOIN tr_h2_wo_dealer wo ON wo.id_sa_form=tr_h2_sa_form.id_sa_form";
    }
    if (isset($filter['left_join_wo'])) {
      $join .= "LEFT JOIN tr_h2_wo_dealer wo ON wo.id_sa_form=tr_h2_sa_form.id_sa_form";
    }

    //Order
    $order = 'ORDER BY jenis_customer ASC, tr_h2_sa_form.created_at DESC';
    if (isset($filter['order'])) {
      if ($filter['order'] == 'waktu_kedatangan_created_at_desc') {
        $order = "ORDER BY tr_h2_sa_form.created_at DESC";
      }
    }

    //LIMIT
    $limit = '';
    if (isset($filter['offset'])) {
      $page = $filter['offset'];
      $page = $page < 0 ? 0 : $page;
      $length = $filter['length'];
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    return $this->db->query("SELECT $select
			FROM tr_h2_sa_form
			LEFT JOIN tr_h2_manage_booking ON tr_h2_sa_form.id_booking=tr_h2_manage_booking.id_booking
			LEFT JOIN ms_customer_h23 ON tr_h2_sa_form.id_customer=ms_customer_h23.id_customer
			LEFT JOIN ms_tipe_kendaraan ON ms_customer_h23.id_tipe_kendaraan=ms_tipe_kendaraan.id_tipe_kendaraan
			LEFT JOIN ms_warna ON ms_customer_h23.id_warna=ms_warna.id_warna
      LEFT JOIN ms_kategori ktg_tk ON ktg_tk.id_kategori=ms_tipe_kendaraan.id_kategori
      $join
			$where
      $order
      $limit
      ");
  }

  function getBookingDetailService($filter)
  {
    if (isset($filter['id_dealer'])) {
      $id_dealer = $filter['id_dealer'];
    } else {
      $id_dealer = $this->m_admin->cari_dealer();
    }
    $where = "WHERE bk.id_dealer='$id_dealer'";

    if ($filter != null) {
      if (isset($filter['id_booking'])) {
        $where .= " AND bk.id_booking='{$filter['id_booking']}'";
      }
      if (isset($filter['id_booking_int'])) {
        $where .= " AND bk.id_booking_int='{$filter['id_booking_int']}'";
      }
      if (isset($filter['tgl_booking'])) {
        if ($filter['tgl_booking'] != '') {
          $where .= " AND LEFT(bk.created_at,10)='{$filter['tgl_booking']}' ";
        }
      }
      if (isset($filter['tgl_servis'])) {
        if ($filter['tgl_servis'] != '') {
          $where .= " AND bk.tgl_servis='{$filter['tgl_servis']}' ";
        }
      }
      if (isset($filter['jam_servis_like'])) {
        if ($filter['jam_servis_like'] != '') {
          $where .= " AND bk.jam_servis LIKE '{$filter['jam_servis_like']}%' ";
        }
      }
      $limit = '';
      if (isset($filter['limit'])) {
        $limit = $filter['limit'];
      }
    }
    $select = "bk_dt.*,js.deskripsi desk_jasa,js.id_type,jst.deskripsi desk_type,js.waktu,jst.color,jst.id_type_int,js.is_favorite";

    return $this->db->query("SELECT $select
    FROM tr_h2_manage_booking_detail bk_dt
    JOIN tr_h2_manage_booking bk ON bk.id_booking=bk_dt.id_booking
    JOIN ms_h2_jasa js ON js.id_jasa=bk_dt.id_jasa
    JOIN ms_h2_jasa_type jst ON jst.id_type=js.id_type
    $where
    $limit
    ");
  }

  function customer_app_booking_checkin($id_booking)
  {
    $cond = ['id_booking' => $id_booking];
    $book = $this->db->get_where('tr_h2_manage_booking', $cond)->row();
    $TotalPrice = 0;
    $Services = [];
    $services = $this->db->query("SELECT bookdetail.id_jasa,nama_jasa,bookdetail.harga
                FROM tr_h2_manage_booking_detail bookdetail
                JOIN ms_h2_jasa js ON js.id_jasa = bookdetail.id_jasa
                WHERE id_booking='$id_booking'
                ")->result();
    foreach ($services as $svc) {
      $Services[] = [
        'ServiceName' => $svc->nama_jasa,
        'ServicePrice' => $svc->harga
      ];
      $subtotal = $svc->harga * 1;
      $TotalPrice += $subtotal;
    }

    $SpareParts = [];
    $get_parts = $this->db->query("SELECT bps.id_part,nama_part,bps.harga,bps.qty
                FROM tr_h2_manage_booking_parts bps
                JOIN ms_part prt ON prt.id_part = bps.id_part
                WHERE id_booking='$id_booking'
                ")->result();
    foreach ($get_parts as $part) {
      $SpareParts[] = [
        'SparePartCode'   => $part->id_part,
        'SparePartQty'   => $part->qty,
        'SparePartName'   => $part->nama_part,
        'SparePartPrice'  => (int)$part->harga
      ];
      $subtotal = $part->harga * 1;
      $TotalPrice += $subtotal;
    }

    $BookingFee = 0;
    $payment = $this->db->get_where('tr_h2_manage_booking_payment', $cond)->row();
    if ($payment != null) {
      $BookingFee = $payment->Amount;
    }
    $res =  [
      'AppsBookingNumber' => $book->customer_apps_booking_number,
      'Status' => 1,
      'Services' => $Services,
      'SpareParts' => $SpareParts,
      'BookingFee' => (int)$BookingFee,
      'Discounts' => [],
      'TotalPrice' => $TotalPrice,
      'OtherServices' => [],
      'OtherSpareParts' => []
    ];
    return $res;
  }

  function customer_app_booking_checkout($id_work_order)
  {
    $cond = ['id_work_order' => $id_work_order];
    $this->db->join('tr_h2_sa_form sa_form', 'sa_form.id_booking=book.id_booking');
    $this->db->join('tr_h2_wo_dealer wo', 'wo.id_sa_form=sa_form.id_sa_form');
    $this->db->join('ms_karyawan_dealer mekanik', 'wo.id_karyawan_dealer=mekanik.id_karyawan_dealer');
    $book = $this->db->get_where('tr_h2_manage_booking book', $cond)->row();
    $TotalPrice = 0;
    $Services = [];
    $services = $this->db->query("SELECT wop.id_jasa,nama_jasa,wop.harga
                FROM tr_h2_wo_dealer_pekerjaan wop
                JOIN ms_h2_jasa js ON js.id_jasa = wop.id_jasa
                WHERE wop.id_work_order='$book->id_work_order'
                ")->result();
    foreach ($services as $svc) {
      $Services[] = [
        'ServiceName' => $svc->nama_jasa,
        'ServicePrice' => $svc->harga
      ];
      $subtotal = $svc->harga * 1;
      $TotalPrice += $subtotal;
    }

    $SpareParts = [];
    $get_parts = $this->db->query("SELECT wop.id_part,nama_part,wop.harga,wop.qty
                FROM tr_h2_wo_dealer_parts wop
                JOIN ms_part prt ON prt.id_part = wop.id_part
                WHERE id_work_order='$book->id_work_order'
                ")->result();
    foreach ($get_parts as $part) {
      $SpareParts[] = [
        'SparePartCode'   => $part->id_part,
        'SparePartQty'   => $part->qty,
        'SparePartName'   => $part->nama_part,
        'SparePartPrice'  => (int)$part->harga
      ];
      $subtotal = $part->harga * $part->qty;
      $TotalPrice += $subtotal;
    }

    $BookingFee = 0;
    $cond = ['id_booking' => $book->id_booking];
    $payment = $this->db->get_where('tr_h2_manage_booking_payment', $cond)->row();
    if ($payment != null) {
      $BookingFee = $payment->Amount;
    }
    return [
      'AppsBookingNumber'   => $book->customer_apps_booking_number,
      'DmsBookingNumber'    => $book->id_booking,
      'Services'            => $Services,
      'SpareParts'          => $SpareParts,
      'BookingFee'          => (int)$BookingFee,
      'Discounts'           => [],
      'TotalPrice'          => $TotalPrice,
      'MechanicName'        => $book->nama_lengkap,
      'MechanicNote'        => $book->saran_mekanik,
      'MileAge'             => (int)$book->km_terakhir,
      'NextServiceDate'     => $book->tgl_service_selanjutnya,
      'OtherServices'       => [],
      'OtherSpareParts'     => []
    ];
  }

  function service_process($id_work_order, $ServiceProcess)
  {
    $cond = ['id_work_order' => $id_work_order];
    $this->db->join('tr_h2_sa_form sa_form', 'sa_form.id_booking=book.id_booking');
    $this->db->join('tr_h2_wo_dealer wo', 'wo.id_sa_form=sa_form.id_sa_form');
    $this->db->join('ms_karyawan_dealer mekanik', 'wo.id_karyawan_dealer=mekanik.id_karyawan_dealer', 'left');
    $book = $this->db->get_where('tr_h2_manage_booking book', $cond)->row();
    return [
      'AppsBookingNumber'   => $book->customer_apps_booking_number,
      'DmsBookingNumber'    => $book->id_booking,
      'ServiceProcess'      => $ServiceProcess,
      'EstimatedTime'       => 0,
    ];
  }
}
