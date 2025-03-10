<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_api extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function fetch_riwayatServisCustomerH23($start, $length, $search, $order, $limit, $id_customer)
  {
    $order_column = array('tgl_servis', 'jam_servis', 'kode_dealer_md', 'nama_dealer', null);
    // $id_jasa = " (SELECT group_concat(id_jasa,', ') 
    // FROM tr_h2_wo_dealer_pekerjaan 
    // WHERE id_work_order=two.id_work_order)";
    $id_jasa = " (SELECT group_concat(jasa.deskripsi,', ') 
    FROM tr_h2_wo_dealer_pekerjaan wop 
    JOIN ms_h2_jasa jasa on jasa.id_jasa=wop.id_jasa
    WHERE id_work_order=two.id_work_order)";
    if ($limit == '') {
      $limit = '';
    } else {
      $limit  = "LIMIT $start, $length";
    }

    $id_customer = $this->db->escape_str($id_customer);
    $order_by  = 'ORDER BY tgl_servis DESC';
    $searchs   = "WHERE sa.id_customer='$id_customer' AND two.status='closed'";

    $search = $this->db->escape_str($search);
    if ($search != '') {
      $searchs .= " AND ($id_jasa LIKE '%$search%' 
              OR sa.tgl_servis LIKE '%$search%'
              OR sa.jam_servis LIKE '%$search%'
              OR two.id_work_order LIKE '%$search%'
              )
          ";
    }

    if ($order != '') {
      $order_clm = $order_column[$order['0']['column']];
      $order_by  = $order['0']['dir'];
      $order_by     = "ORDER BY $order_clm $order_by";
    }

    if ($limit == 'y') $limit = '';

    return $this->db->query("SELECT id_work_order,tgl_servis,jam_servis,nama_pembawa,jenis_customer,nama_dealer,kode_dealer_md,
     $id_jasa AS pekerjaan,two.id_sa_form
    FROM tr_h2_wo_dealer AS two
    JOIN tr_h2_sa_form AS sa ON sa.id_sa_form=two.id_sa_form
    JOIN ms_dealer AS mdl ON mdl.id_dealer=two.id_dealer
         $searchs $order_by $limit ");
  }

  function getQtyBookSO($params)
  {
    return $this->db->select("(IFNULL(SUM(kuantitas),0)-IFNULL(SUM(kuantitas_return),0)) AS tot")
      ->from('tr_h3_dealer_sales_order_parts AS sop')
      ->join('tr_h3_dealer_sales_order as so', "(so.nomor_so=sop.nomor_so)")
      ->where("so.status='Open'")
      ->where('sop.id_part', $params['id_part'])
      ->where('so.id_dealer', $params['id_dealer'])
      ->where('sop.id_gudang', $params['id_gudang'])
      ->where('sop.id_rak', $params['id_rak'])
      ->get()->row()->tot;
  }

  function getQtyBookWO($params)
  {
    $id_gudang = addslashes($params['id_gudang']);
    $id_rak = addslashes($params['id_rak']);
    return $this->db->query("SELECT IFNULL(SUM(qty),0) tot FROM tr_h2_wo_dealer_parts wop
    JOIN tr_h2_wo_dealer wo ON wo.id_work_order=wop.id_work_order
    WHERE (status='open' OR status='pause')
    AND wop.id_part='{$params['id_part']}' AND wo.id_dealer='{$params['id_dealer']}' AND wop.id_gudang='$id_gudang' AND wop.id_rak='$id_rak'
    ")->row()->tot;
  }

  function getQtyBookSA($params)
  {
    $id_gudang = addslashes($params['id_gudang']);
    $id_rak = addslashes($params['id_rak']);
    return $this->db->query("SELECT IFNULL( SUM(qty),0) tot FROM tr_h2_sa_form_parts sop
    JOIN tr_h2_sa_form sa ON sa.id_sa_form=sop.id_sa_form
    WHERE sa.status_form='open' AND sop.id_part='{$params['id_part']}' AND sa.id_dealer='{$params['id_dealer']}' AND sop.id_gudang='$id_gudang' AND sop.id_rak='$id_rak'
    ")->row()->tot;
  }

  function fetch_partWithAllStock($filter)
  {
    $book_so = 0;
    $book_wo = 0;
    $book_sa = 0;

    $order_column = array('id_part ', 'nama_part', 'mp.harga_dealer_user', 'ds.id_gudang', 'ds.id_rak', "(stock-(($book_sa)+($book_wo)+($book_so)))", 'mp.status', null, null);
    $set_filter   = "WHERE 1=1";

    if (isset($filter['lebih_dari_nol'])) {
      // $set_filter .= " AND (stock-(($book_sa)+($book_wo)+($book_so)))>0";
    }
    if (isset($filter['search'])) {
      $search = $this->db->escape_str($filter['search']);
      if ($search != '') {
        $set_filter .= " AND (mp.id_part LIKE '%$search%'
                            OR nama_part LIKE '%$search%'
                            OR dl.kode_dealer_md LIKE '%$search%'
                            OR dl.nama_dealer LIKE '%$search%'
                            ) 
            ";
      }
    }
    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $set_filter .= " AND ds.id_dealer='{$filter['id_dealer']}'";
      }
    }
    if (isset($filter['id_part'])) {
      if ($filter['id_part'] != '') {
        $set_filter .= " AND ds.id_part LIKE '%{$filter['id_part']}%'";
      }
    }
    if (isset($filter['nama_part'])) {
      if ($filter['nama_part'] != '') {
        $set_filter .= " AND mp.nama_part LIKE '%{$filter['nama_part']}%'";
      }
    }
    if (isset($filter['id_tipe_kendaraan'])) {
      if ($filter['id_tipe_kendaraan'] != '') {
        $set_filter .= " AND EXISTS(SELECT pvt.no_part FROM ms_pvtm pvt
                        JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
                        JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ptm.tipe_marketing
                        WHERE pvt.no_part=ds.id_part AND tk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'
                       )";
      }
    }
    if (isset($filter['id_tipe_kendaraan_int'])) {
      if ($filter['id_tipe_kendaraan_int'] != '') {
        $set_filter .= " AND EXISTS(SELECT pvt.no_part FROM ms_pvtm pvt
                        JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
                        JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ptm.tipe_marketing
                        WHERE pvt.no_part=ds.id_part AND tk.id_tipe_kendaraan_int='{$filter['id_tipe_kendaraan_int']}'
                       )";
      }
    }

    // $tanggal = date("Y-m-d");
    // if($tanggal <='2023-08-06' || $tanggal >='2023-08-12'){
    // }else{
    //   $set_filter .= " AND mp.kelompok_part !='FED OIL'";
    // }

    if($this->config->item('ahm_d_only')){
      $set_filter .= " AND mp.kelompok_part !='FED OIL'";
    }

    // $set_filter .= "GROUP BY mp.id_part ";
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

    if (isset($filter['select'])) {
      if ($filter['select'] == 'summary_stok') {
        $select = "IFNULL(SUM((stock-(($book_sa)+($book_wo)))),0) AS summary_stok";
      }
    } else {
      $select = "mp.id_part,mp.nama_part,kelompok_vendor,dl.id_dealer,dl.kode_dealer_md,dl.nama_dealer,ds.id_gudang,ds.id_rak,ds.id_gudang,deskripsi_gudang, stock,harga_dealer_user,kelompok_part,
      CASE 
        WHEN mp.status='A' THEN 'Aktif'
        WHEN mp.status='D' THEN 'Discontinue'
        ELSE '-'
      END AS status";
    }
    return $this->db->query("SELECT $select
    FROM ms_h3_dealer_stock AS ds
    JOIN ms_part AS mp ON mp.id_part_int=ds.id_part_int
    JOIN ms_dealer AS dl ON dl.id_dealer=ds.id_dealer
    LEFT JOIN ms_gudang_h23 AS gd ON gd.id_gudang=ds.id_gudang 
    AND gd.id_dealer=ds.id_dealer
    $set_filter
    ");
  }

  function fetch_salesHLOPartsDealer($filter)
  {

    $order_column = array('id_part ', 'nama_part', 'kelompok_vendor', 'kode_dealer_md', null, null);
    $set_filter   = "WHERE 1=1 ";

    $search = $this->db->escape_str($filter['search']);
    if ($search != '') {
      $set_filter .= " AND (mp.id_part LIKE '%$search%'
                            OR nama_part LIKE '%$search%'
                            OR dl.kode_dealer_md LIKE '%$search%'
                            OR dl.nama_dealer LIKE '%$search%'
                            ) 
            ";
    }
    if (isset($filter['id_part'])) {
      if ($filter['id_part'] != '') {
        $set_filter .= " AND mp.id_part LIKE '%{$filter['id_part']}%'";
      }
    }
    if (isset($filter['nama_part'])) {
      if ($filter['nama_part'] != '') {
        $set_filter .= " AND mp.nama_part LIKE '%{$filter['nama_part']}%'";
      }
    }
    if (isset($filter['id_tipe_kendaraan'])) {
      if ($filter['id_tipe_kendaraan'] != '') {
        $set_filter .= " AND EXISTS(SELECT pvt.no_part FROM ms_pvtm pvt
        JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
        JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ptm.tipe_marketing
        WHERE pvt.no_part=ds.id_part AND tk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'
       )  ";
      }
    }

    // $tanggal = date("Y-m-d");
    // if($tanggal <='2023-08-06' || $tanggal >='2023-08-12'){
    // }else{
    //   $set_filter .= " AND mp.kelompok_part !='FED OIL'";
    // }

    if($this->config->item('ahm_d_only')){
      $set_filter .= " AND mp.kelompok_part !='FED OIL'";
    }

    // $set_filter .= "GROUP BY mp.id_part ";
    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT mp.id_part,mp.nama_part,harga_dealer_user,kelompok_part, 
    CASE 
      WHEN mp.status='A' THEN 'Aktif'
      WHEN mp.status='D' THEN 'Discontinue'
      ELSE '-'
    END AS status
    FROM ms_part AS mp
    $set_filter
    ");
  }

  function fetch_kelurahan($filter)
  {

    $order_column = array('id_kelurahan ', 'kelurahan', 'kecamatan', 'kabupaten', 'provinsi', null);
    $set_filter   = "WHERE 1=1 ";

    $search = $this->db->escape_str($filter['search']);
    if ($search != '') {
      $set_filter .= " AND (id_kelurahan LIKE '%$search%'
                            OR kode_pos LIKE '%$search%'
                            OR kelurahan LIKE '%$search%'
                            OR kecamatan LIKE '%$search%'
                            OR kabupaten LIKE '%$search%'
                            OR provinsi LIKE '%$search%'
                            ) 
            ";
    }
    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT kel.kode_pos,id_kelurahan,kelurahan,kecamatan,kabupaten,provinsi
    FROM ms_kelurahan AS kel
    LEFT JOIN ms_kecamatan AS kec ON kec.id_kecamatan=kel.id_kecamatan
    LEFT JOIN ms_kabupaten AS kab ON kab.id_kabupaten=kec.id_kabupaten
    LEFT JOIN ms_provinsi AS prov ON prov.id_provinsi=kab.id_provinsi
    $set_filter
    ");
  }

  function fetch_item($filter)
  {
    $tipe_ahm = "(SELECT tipe_ahm FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=itm.id_tipe_kendaraan)";
    $no_mesin = "(SELECT no_mesin FROM ms_tipe_kendaraan WHERE id_tipe_kendaraan=itm.id_tipe_kendaraan)";
    $warna = "(SELECT warna FROM ms_warna WHERE id_warna=itm.id_warna)";

    $order_column = array('id_item ', 'id_tipe_kendaraan', $tipe_ahm, 'id_warna', $warna, null);
    $set_filter   = "WHERE 1=1 ";
    
    $search = $this->db->escape_str($filter['search']);

    if ($search != '') {
      $set_filter .= " AND (id_item LIKE '%$search%'
                            OR itm.id_tipe_kendaraan LIKE '%$search%'
                            OR $tipe_ahm LIKE '%$search%'
                            OR itm.id_warna LIKE '%$search%'
                            OR $warna LIKE '%$search%'
                            OR $no_mesin LIKE '%$search%'
                            ) 
            ";
    }
    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_item,itm.id_tipe_kendaraan,$tipe_ahm AS tipe_ahm,itm.id_warna,$warna AS warna,$no_mesin no_mesin
    FROM ms_item AS itm
    -- JOIN ms_tipe_kendaraan AS mtk ON mtk.id_tipe_kendaraan=itm.id_tipe_kendaraan
    -- JOIN ms_warna AS wrn ON itm.id_warna=itm.id_warna
    $set_filter
    ");
  }
  function fetch_tipe_kendaraan($filter)
  {
    $order_column = array('id_tipe_kendaraan', 'tipe_ahm', null);
    $set_filter   = "WHERE 1=1 ";

    // $search = $filter['search'];
    $search = $this->db->escape_str($filter['search']);

    if ($search != '') {
      $set_filter .= " AND (tk.id_tipe_kendaraan LIKE '%$search%'
                            OR tk.tipe_ahm LIKE '%$search%'
                            OR tk.no_mesin LIKE '%$search%'
                            ) 
            ";
    }
    if (isset($filter['not_in_tipe_vs_5nosin'])) {
      $set_filter .= " AND NOT EXISTS(SELECT id_tipe_kendaraan 
                                     FROM ms_tipe_vs_5_digit_no_mesin_detail tdd
                                     JOIN ms_tipe_vs_5_digit_no_mesin td ON td.id=tdd.id
                                     WHERE td.aktif=1 AND tdd.id_tipe_kendaraan=tk.id_tipe_kendaraan
                                    )";
    }else{
      $set_filter .=" AND active=1";
    }
    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_tipe_kendaraan,tipe_ahm,no_mesin
    FROM ms_tipe_kendaraan tk
    $set_filter
    ");
  }

  function fetch_customerH23($filter)
  {

    $order_column = array('id_customer', 'nama_customer', 'no_hp', 'ch23.id_tipe_kendaraan', 'ch23.id_warna', 'ch23.no_mesin', 'ch23.no_rangka', 'no_polisi', null);
    if ($filter != null) {
      $where = "WHERE 1=1 ";
      if ($filter['nama_customer'] != '') {
        $where .= " AND nama_customer LIKE '%{$filter['nama_customer']}%'";
      }
      if ($filter['no_hp'] != '') {
        $where .= " AND no_hp LIKE '%{$filter['no_hp']}%'";
      }
      if ($filter['no_mesin'] != '') {
        $where .= " AND ch23.no_mesin LIKE '%{$filter['no_mesin']}%'";
      }
      if (isset($filter['no_rangka'])) {
        if ($filter['no_rangka'] != '') {
          $where .= " AND ch23.no_rangka LIKE '%{$filter['no_rangka']}%'";
        }
      }
      if ($filter['no_polisi'] != '') {
        $where .= " AND no_polisi LIKE '%{$filter['no_polisi']}%'";
      }
    }
    $set_filter = '';
    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    }


    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT 'h23' AS customer_from,id_customer, replace(nama_customer,'\'','`') as nama_customer,jenis_kelamin,no_identitas,jenis_identitas,alamat_identitas,no_hp,ch23.email,ch23.alamat,ch23.id_tipe_kendaraan,tipe_ahm,ch23.id_warna,warna,ch23.no_mesin,no_rangka,tahun_produksi,no_polisi,ch23.id_dealer
      FROM ms_customer_h23 AS ch23
      JOIN ms_tipe_kendaraan AS tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
      JOIN ms_warna AS wr ON wr.id_warna=ch23.id_warna
      $where
      GROUP BY id_customer
    $set_filter
    ");
  }

  function fetch_customerH1($filter)
  {

    $order_column = array('id_sales_order', 'nama_konsumen', 'no_hp', 'spk.id_tipe_kendaraan', 'spk.id_warna', 'so.no_mesin', 'so.no_rangka', 'no_polisi', null);
    if ($filter != null) {
      $where = "WHERE 1=1 AND NOT EXISTS (SELECT no_mesin FROM ms_customer_h23 WHERE no_mesin=so.no_mesin) ";
      $where_gc = "WHERE 1=1 AND NOT EXISTS (SELECT no_mesin FROM ms_customer_h23 WHERE no_mesin=sog.no_mesin) ";
      if ($filter['nama_customer'] != '') {
        $where .= " AND spk.nama_konsumen LIKE '%{$filter['nama_customer']}%'";
        $where_gc .= " AND spk.nama_npwp LIKE '%{$filter['nama_customer']}%'";
      }
      if ($filter['no_hp'] != '') {
        $where .= " AND spk.no_hp LIKE '%{$filter['no_hp']}%'";
        $where_gc .= " AND spk.no_hp LIKE '%{$filter['no_hp']}%'";
      }
      if ($filter['no_mesin'] != '') {
        $where .= " AND so.no_mesin LIKE '%{$filter['no_mesin']}%'";
        $where_gc .= " AND sog.no_mesin LIKE '%{$filter['no_mesin']}%'";
      }
      if ($filter['no_polisi'] != '') {
        $where .= " AND (SELECT no_pol FROM tr_entry_stnk WHERE no_mesin=so.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) LIKE '%{$filter['no_polisi']}%'";

        $where_gc .= " AND (SELECT no_pol FROM tr_entry_stnk WHERE no_mesin=sog.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) LIKE '%{$filter['no_polisi']}%'";
      }
    }
    $set_filter = '';
    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    }


    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT * FROM(
      SELECT 'h1' AS customer_from,replace(nama_konsumen,'\'','`') AS nama_customer,'' AS jenis_kelamin,no_ktp AS no_identitas,'ktp' AS jenis_identitas, spk.alamat2 AS alamat_identitas,spk.id_tipe_kendaraan,tk.tipe_ahm,spk.id_warna,so.no_mesin,so.no_rangka,so.tahun_produksi,
    (SELECT no_pol FROM tr_entry_stnk WHERE no_mesin=so.no_mesin LIMIT 1) AS no_polisi,spk.no_hp,so.id_sales_order,'Regular' AS jenis_customer_beli
      FROM tr_sales_order so
      JOIN tr_spk spk ON spk.no_spk=so.no_spk
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spk.id_tipe_kendaraan
      JOIN ms_warna wr ON wr.id_warna=spk.id_warna
      $where
      UNION
      SELECT 'h1' AS customer_from,replace(nama_npwp,'\'','`') AS nama_customer,'' AS jenis_kelamin,no_npwp AS no_identitas,'npwp' AS jenis_identitas, spk.alamat2 AS alamat_identitas,tk.id_tipe_kendaraan,tk.tipe_ahm,wr.id_warna,scb.no_mesin,scb.no_rangka,(SELECT tahun_produksi FROM tr_fkb WHERE tr_fkb.no_mesin_spasi=sog.no_mesin) AS tahun_produksi,
      (SELECT no_pol FROM tr_entry_stnk WHERE no_mesin=sog.no_mesin LIMIT 1),
      spk.no_hp,so.id_sales_order_gc AS id_sales_order,'Group Sales' AS jenis_customer_beli
      FROM tr_sales_order_gc_nosin sog
      JOIN tr_sales_order_gc so ON sog.id_sales_order_gc=so.id_sales_order_gc
      JOIN tr_spk_gc spk ON spk.no_spk_gc=so.no_spk_gc
      JOIN tr_scan_barcode scb ON scb.no_mesin=sog.no_mesin
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=scb.tipe_motor
      JOIN ms_warna wr ON wr.id_warna=scb.warna
      $where_gc
    ) AS tabel
      $set_filter
    ");
  }

  function fetch_customerBooking($filter)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $tgl       = gmdate("Y-m-d", time() + 60 * 60 * 7);

    $order_column = array('id_booking', 'id_customer ', 'nama_customer', 'no_hp', 'ch23.id_tipe_kendaraan', 'ch23.id_warna', 'ch23.no_mesin', 'no_rangka', 'no_polisi', null);
    if ($filter != null) {
      $where = "WHERE 1=1 AND mb.id_dealer='$id_dealer' AND tgl_servis='$tgl' and status !='cancel'";
      if ($filter['nama_customer'] != '') {
        $where .= " AND nama_customer LIKE '%{$filter['nama_customer']}%'";
      }
      if ($filter['no_hp'] != '') {
        $where .= " AND no_hp LIKE '%{$filter['no_hp']}%'";
      }
      if ($filter['id_booking'] != '') {
        $where .= " AND id_booking LIKE '%{$filter['id_booking']}%'";
      }
      if ($filter['no_mesin'] != '') {
        $where .= " AND ch23.no_mesin LIKE '%{$filter['no_mesin']}%'";
      }
      if ($filter['no_polisi'] != '') {
        $where .= " AND no_polisi LIKE '%{$filter['no_polisi']}%'";
      }
    }
    $set_filter = '';
    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY mb.jam_servis ASC ";
    }


    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT ch23.id_customer, replace(nama_customer,'\'','`') as nama_customer,jenis_kelamin,no_identitas,jenis_identitas,alamat_identitas,no_hp,ch23.email,ch23.alamat,ch23.id_kelurahan,kelurahan,kecamatan,kabupaten,provinsi,ch23.id_tipe_kendaraan,tipe_ahm,ch23.id_warna,warna,ch23.no_mesin,no_rangka,tahun_produksi,no_polisi,ch23.id_dealer,kode_dealer_md,nama_dealer,tgl_servis,jam_servis,mb.id_booking,'h23' AS customer_from,nama_pembawa,mb.keluhan,mb.id_type
      FROM tr_h2_manage_booking AS mb
      JOIN ms_customer_h23 AS ch23 ON ch23.id_customer=mb.id_customer
      LEFT JOIN ms_kelurahan AS kel ON kel.id_kelurahan=ch23.id_kelurahan
      LEFT JOIN ms_kecamatan AS kec ON kec.id_kecamatan=kel.id_kecamatan
      LEFT JOIN ms_kabupaten AS kab ON kec.id_kabupaten=kec.id_kabupaten
      LEFT JOIN ms_provinsi AS prov ON prov.id_provinsi=prov.id_provinsi
      JOIN ms_dealer AS msd ON msd.id_dealer=mb.id_dealer
      JOIN ms_tipe_kendaraan AS tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
      LEFT JOIN ms_warna AS wr ON wr.id_warna=ch23.id_warna
      $where
      GROUP BY id_customer, id_booking
    $set_filter
    ");
  }

  function getPromoPart($filter = null)
  {
    if ($filter != null) {
      $where = "WHERE 1=1 ";
      if ($filter['id_dealer'] != '') {
        $where .= " AND id_dealer = '{$filter['id_dealer']}'";
      }
      if ($filter['id_part'] != '') {
        $where .= " AND id_part = '{$filter['id_part']}'";
      }
      if ($filter['start_end_date'] != '') {
        $where .= " AND '{$filter['start_end_date']}' BETWEEN promo_start_date AND promo_end_date";
      }
    }
    return $this->db->query("SELECT * FROM ms_h3_promo_program $where ORDER BY created_at ASC");
  }

  function getCustomerH23($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_customer'])) {
        $where .= " AND ch23.id_customer='{$filter['id_customer']}' ";
      }
      if (isset($filter['id_customer_int'])) {
        $where .= " AND ch23.id_customer_int='{$filter['id_customer_int']}' ";
      }
      if (isset($filter['no_mesin'])) {
        $where .= " AND ch23.no_mesin='{$filter['no_mesin']}' ";
      }
      if (isset($filter['no_rangka'])) {
        $where .= " AND ch23.no_rangka='{$filter['no_rangka']}' ";
      }
      if (isset($filter['no_polisi'])) {
        $filter['no_polisi'] = str_replace(' ', '', $filter['no_polisi']);
        $where .= " AND replace(ch23.no_polisi , ' ','')='{$filter['no_polisi']}' ";
      }
      
      if (isset($filter['no_mesin_no_rangka_no_polisi'])) {
        $val = $filter['no_mesin_no_rangka_no_polisi'];
        $val[2] = str_replace(' ', '', $val[2]);
        $where .= " AND (replace(ch23.no_polisi , ' ','')='{$val[2]}' AND ch23.no_rangka='{$val[1]}' AND ch23.no_mesin='$val[0]') ";
      }
      
      if (isset($filter['no_mesin_or_no_plat'])) {
        $cari = $filter['no_mesin_or_no_plat'];
        $no_mesin = $cari[0];
        $no_polisi = $cari[1];
        $where .= " AND (ch23.no_polisi='$no_polisi' OR ch23.no_mesin='$no_mesin') ";
      }
    }
    return $this->db->query("SELECT ch23.id_customer, replace(nama_customer,'\'','`') as nama_customer,jenis_kelamin,no_identitas,jenis_identitas,alamat_identitas,no_hp,ch23.email,ch23.alamat,nama_stnk,
    ch23.id_kelurahan,kel.kelurahan,kec.kecamatan,kab.kabupaten,prov.provinsi,
    ch23.id_kelurahan_identitas,kel2.kelurahan AS kelurahan_identitas,kec2.kecamatan AS kecamatan_identitas,kab2.kabupaten AS kabupaten_identitas,prov2.provinsi AS provinsi_identitas,
    ch23.id_tipe_kendaraan,CONCAT(ch23.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm,ch23.id_warna,CONCAT(ch23.id_warna,' | ',warna) AS warna,ch23.no_mesin,no_rangka,tahun_produksi,no_polisi,ch23.id_dealer,id_kelurahan_identitas,jenis_customer_beli,longitude,latitude,ch23.id_agama,agama,ch23.tgl_pembelian,ch23.nama_customer AS nama,ch23.alamat AS alamat_saat_ini,tk.kode_ptm,ch23.tgl_lahir,ch23.id_pekerjaan,ch23.facebook,ch23.twitter,ch23.instagram,ch23.id_customer_int,wr.warna only_warna,CONCAT(tk.id_tipe_kendaraan,' - ',tipe_ahm) tipe_ahm2,ch23.nama_customer nama_pembawa,tk.id_tipe_kendaraan_int,wr.id_warna_int,kel.id_kelurahan
    FROM ms_customer_h23 ch23
    LEFT JOIN ms_kelurahan AS kel ON kel.id_kelurahan=ch23.id_kelurahan
    LEFT JOIN ms_kecamatan AS kec ON kec.id_kecamatan=kel.id_kecamatan
    LEFT JOIN ms_kabupaten AS kab ON kab.id_kabupaten=kec.id_kabupaten
    LEFT JOIN ms_provinsi AS prov ON prov.id_provinsi=kab.id_provinsi
    LEFT JOIN ms_kelurahan AS kel2 ON kel2.id_kelurahan=id_kelurahan_identitas
    LEFT JOIN ms_kecamatan AS kec2 ON kec2.id_kecamatan=kel2.id_kecamatan
    LEFT JOIN ms_kabupaten AS kab2 ON kab2.id_kabupaten=kec2.id_kabupaten
    LEFT JOIN ms_provinsi AS prov2 ON prov2.id_provinsi=kab2.id_provinsi
    LEFT JOIN ms_tipe_kendaraan AS tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
    LEFT JOIN ms_warna AS wr ON wr.id_warna=ch23.id_warna
    LEFT JOIN ms_agama AS ag ON ag.id_agama=ch23.id_agama
    $where
    GROUP BY ch23.id_customer
    ");
  }

  function getCustomerH1($filter)
  {

    $order_column = array('id_sales_order', 'nama_konsumen', 'no_hp', 'spk.id_tipe_kendaraan', 'spk.id_warna', 'so.no_mesin', 'so.no_rangka', 'no_polisi', null);
    if ($filter != null) {
      $where = "WHERE 1=1 AND NOT EXISTS (SELECT no_mesin FROM ms_customer_h23 WHERE no_mesin=so.no_mesin) ";
      $where_gc = "WHERE 1=1 AND NOT EXISTS (SELECT no_mesin FROM ms_customer_h23 WHERE no_mesin=sog.no_mesin) ";

      if (isset($filter['no_mesin'])) {
        if ($filter['no_mesin'] != '') {
          $where .= " AND so.no_mesin LIKE '%{$filter['no_mesin']}%'";
          $where_gc .= " AND sog.no_mesin LIKE '%{$filter['no_mesin']}%'";
        }
      }
      if (isset($filter['no_mesin_sc'])) {
        if ($filter['no_mesin_sc'] != '') {
          $where .= " AND so.no_mesin = '{$filter['no_mesin_sc']}'";
          $where_gc .= " AND sog.no_mesin = '{$filter['no_mesin_sc']}'";
        }
      }

      if (isset($filter['no_rangka'])) {
        if ($filter['no_rangka'] != '') {          
          $temp_rangka = strtoupper($filter['no_rangka']);
          if(strlen($temp_rangka) == 17){
            $originalStr = $temp_rangka;
            $prefix = substr($originalStr, 0, 3);
            $temp_rangka = str_replace("MH1","", $prefix) . substr($originalStr, 3);
          }

          $where .= " AND so.no_rangka LIKE '%{$temp_rangka}%'";
          $where_gc .= " AND scb.no_rangka LIKE '%{$temp_rangka}%'";
        }
      }
      if (isset($filter['no_rangka_sc'])) {
        if ($filter['no_rangka_sc'] != '') {       
          $temp_rangka = strtoupper($filter['no_rangka_sc']);
          if(strlen($temp_rangka) == 17){
            $originalStr = $temp_rangka;
            $prefix = substr($originalStr, 0, 3);
            $temp_rangka = str_replace("MH1","", $prefix) . substr($originalStr, 3);
          }
          $where .= " AND so.no_rangka = '{$temp_rangka}'";
          $where_gc .= " AND scb.no_rangka = '{$temp_rangka}'";
        }
      }
      
      if (isset($filter['no_polisi'])) {
        if ($filter['no_polisi'] != '') {
          $where .= " AND (SELECT no_pol FROM tr_entry_stnk WHERE no_mesin=so.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) LIKE '%{$filter['no_polisi']}%'";
          $where_gc .= " AND (SELECT no_pol FROM tr_entry_stnk WHERE no_mesin=sog.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) LIKE '%{$filter['no_polisi']}%'";
        }
      }
      if (isset($filter['no_polisi_sc'])) {
        if ($filter['no_polisi_sc'] != '') {
          $filter['no_polisi_sc'] = str_replace(' ', '', $filter['no_polisi_sc']);
          $where .= " AND (SELECT replace(no_pol,' ','') FROM tr_entry_stnk WHERE no_mesin=so.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) = '{$filter['no_polisi_sc']}'";
          $where_gc .= " AND (SELECT replace(no_pol,' ','') FROM tr_entry_stnk WHERE no_mesin=sog.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) = '{$filter['no_polisi_sc']}'";
        }
      }

      if (isset($filter['no_mesin_no_rangka_no_polisi'])) {
        $val    = $filter['no_mesin_no_rangka_no_polisi'];
        $val[2] = str_replace(' ', '', $val[2]);

        $temp_rangka = strtoupper($val[1]);
        if(strlen($temp_rangka) == 17){
          $originalStr = $temp_rangka;
          $prefix = substr($originalStr, 0, 3);
          $temp_rangka = str_replace("MH1","", $prefix) . substr($originalStr, 3);
        }

        $where .= " AND 
                (
                  (SELECT replace(no_pol, ' ','') FROM tr_entry_stnk WHERE no_mesin=so.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1)='{$val[2]}'
                  AND so.no_mesin='{$val[0]}' AND so.no_rangka='{$temp_rangka}'
                )
                ";
        
        $where_gc .= " AND (
          (SELECT replace(no_pol,' ','') FROM tr_entry_stnk WHERE no_mesin=sog.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1)= '{$val[2]}'
          AND sog.no_mesin='{$val[0]}' AND scb.no_rangka='{$temp_rangka}'
        )";
      }
    }

    return $this->db->query("SELECT * FROM(
      SELECT 'h1' AS customer_from,replace(nama_konsumen,'\'','`') AS nama_customer,'' AS jenis_kelamin,no_ktp AS no_identitas,'ktp' AS jenis_identitas, spk.alamat2 AS alamat_identitas,spk.id_tipe_kendaraan,CONCAT(spk.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm,spk.id_warna,so.no_mesin, concat('MH1',so.no_rangka) as no_rangka,
    (SELECT no_pol FROM tr_entry_stnk WHERE no_mesin=so.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) AS no_polisi,spk.no_hp,so.id_sales_order,'Regular' AS jenis_customer_beli,spk.longitude,spk.latitude, spk.nama_bpkb AS nama_stnk,spk.email,spk.id_kelurahan,kel.kelurahan,kec.kecamatan,kab.kabupaten,prov.provinsi,
    spk.id_kelurahan2 AS id_kelurahan_identitas,kel2.kelurahan AS kelurahan_identitas,kec2.kecamatan AS kecamatan_identitas,kab2.kabupaten AS kabupaten_identitas,prov2.provinsi AS provinsi_identitas,CONCAT(spk.id_warna,' | ',wr.warna) AS warna,so.tahun_produksi,spk.alamat,so.id_dealer AS id_dealer_h1,LEFT(so.tgl_cetak_invoice,10) AS tgl_pembelian,cdb.facebook,cdb.twitter,cdb.instagram
      FROM tr_sales_order so
      JOIN tr_spk spk ON spk.no_spk=so.no_spk
      JOIN tr_cdb cdb ON cdb.no_spk=spk.no_spk
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spk.id_tipe_kendaraan
      LEFT JOIN ms_warna wr ON wr.id_warna=spk.id_warna
      LEFT JOIN ms_kelurahan AS kel ON kel.id_kelurahan=spk.id_kelurahan
      LEFT JOIN ms_kecamatan AS kec ON kec.id_kecamatan=kel.id_kecamatan
      LEFT JOIN ms_kabupaten AS kab ON kab.id_kabupaten=kec.id_kabupaten
      LEFT JOIN ms_provinsi AS prov ON prov.id_provinsi=kab.id_provinsi
      LEFT JOIN ms_kelurahan AS kel2 ON kel2.id_kelurahan=id_kelurahan2
      LEFT JOIN ms_kecamatan AS kec2 ON kec2.id_kecamatan=kel2.id_kecamatan
      LEFT JOIN ms_kabupaten AS kab2 ON kab2.id_kabupaten=kec2.id_kabupaten
      LEFT JOIN ms_provinsi AS prov2 ON prov2.id_provinsi=kab2.id_provinsi
      $where
      UNION
      SELECT 'h1' AS customer_from,replace(nama_npwp,'\'','`') AS nama_customer,'' AS jenis_kelamin,no_npwp AS no_identitas,'npwp' AS jenis_identitas, spk.alamat2 AS alamat_identitas,tk.id_tipe_kendaraan,CONCAT(tk.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm,wr.id_warna,scb.no_mesin, concat('MH1',scb.no_rangka) as no_rangka,
      '' AS no_polisi,spk.no_hp,so.id_sales_order_gc AS id_sales_order,'Group Sales' AS jenis_customer_beli,spk.longitude,spk.latitude, nama_npwp AS nama_stnk,spk.email,spk.id_kelurahan,kel.kelurahan,kec.kecamatan,kab.kabupaten,prov.provinsi,
    spk.id_kelurahan2 AS id_kelurahan_identitas,kel2.kelurahan AS kelurahan_identitas,kec2.kecamatan AS kecamatan_identitas,kab2.kabupaten AS kabupaten_identitas,prov2.provinsi AS provinsi_identitas,CONCAT(wr.id_warna,' | ',wr.warna) AS warna,(SELECT tahun_produksi FROM tr_fkb where no_mesin_spasi=sog.no_mesin) AS tahun_produksi,spk.alamat,so.id_dealer AS id_dealer_h1,LEFT(so.tgl_cetak_invoice,10) AS tgl_pembelian,'' facebook,'' twitter,'' instagram
      FROM tr_sales_order_gc_nosin sog
      JOIN tr_sales_order_gc so ON sog.id_sales_order_gc=so.id_sales_order_gc
      JOIN tr_spk_gc spk ON spk.no_spk_gc=so.no_spk_gc
      JOIN tr_scan_barcode scb ON scb.no_mesin=sog.no_mesin
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=scb.tipe_motor
      LEFT JOIN ms_warna wr ON wr.id_warna=scb.warna
      LEFT JOIN ms_kelurahan AS kel ON kel.id_kelurahan=spk.id_kelurahan
      LEFT JOIN ms_kecamatan AS kec ON kec.id_kecamatan=kel.id_kecamatan
      LEFT JOIN ms_kabupaten AS kab ON kab.id_kabupaten=kec.id_kabupaten
      LEFT JOIN ms_provinsi AS prov ON prov.id_provinsi=kab.id_provinsi
      LEFT JOIN ms_kelurahan AS kel2 ON kel2.id_kelurahan=id_kelurahan2
      LEFT JOIN ms_kecamatan AS kec2 ON kec2.id_kecamatan=kel2.id_kecamatan
      LEFT JOIN ms_kabupaten AS kab2 ON kab2.id_kabupaten=kec2.id_kabupaten
      LEFT JOIN ms_provinsi AS prov2 ON prov2.id_provinsi=kab2.id_provinsi
      $where_gc
    ) AS tabel
    ");
  }

  function getCustomerH1_v2($filter)
  {

    $order_column = array('id_sales_order', 'nama_konsumen', 'no_hp', 'spk.id_tipe_kendaraan', 'spk.id_warna', 'so.no_mesin', 'so.no_rangka', 'no_polisi', null);
    if ($filter != null) {
      $where = "WHERE 1=1 AND NOT EXISTS (SELECT no_mesin FROM ms_customer_h23 WHERE no_mesin=so.no_mesin) ";
      $where_gc = "WHERE 1=1 AND NOT EXISTS (SELECT no_mesin FROM ms_customer_h23 WHERE no_mesin=sog.no_mesin) ";

      $join ='';
      $join_gc ='';
      if (isset($filter['no_mesin'])) {
        if ($filter['no_mesin'] != '') {
          $where .= " AND so.no_mesin LIKE '%{$filter['no_mesin']}%'";
          $where_gc .= " AND sog.no_mesin LIKE '%{$filter['no_mesin']}%'";
        }
      }
      if (isset($filter['no_mesin_sc'])) {
        if ($filter['no_mesin_sc'] != '') {
          $temp_rangka = strtoupper($filter['no_rangka']);
          if(strlen($temp_rangka) == 17){
            $originalStr = $temp_rangka;
            $prefix = substr($originalStr, 0, 3);
            $temp_rangka = str_replace("MH1","", $prefix) . substr($originalStr, 3);
          }
          $where .= " AND so.no_mesin = '{$filter['no_mesin_sc']}'";
          $where_gc .= " AND sog.no_mesin = '{$filter['no_mesin_sc']}'";
        }
      }

      if (isset($filter['no_rangka'])) {
        if ($filter['no_rangka'] != '') {
          $temp_rangka = strtoupper($filter['no_rangka']);
          if(strlen($temp_rangka) == 17){
            $originalStr = $temp_rangka;
            $prefix = substr($originalStr, 0, 3);
            $temp_rangka = str_replace("MH1","", $prefix) . substr($originalStr, 3);
          }

          $where .= " AND so.no_rangka LIKE '%{$temp_rangka}%'";
          $where_gc .= " AND scb.no_rangka LIKE '%{$temp_rangka}%'";
        }
      }
      if (isset($filter['no_rangka_sc'])) {
        if ($filter['no_rangka_sc'] != '') {
          $temp_rangka = strtoupper($filter['no_rangka_sc']);
          if(strlen($temp_rangka) == 17){
            $originalStr = $temp_rangka;
            $prefix = substr($originalStr, 0, 3);
            $temp_rangka = str_replace("MH1","", $prefix) . substr($originalStr, 3);
          }

          $where .= " AND so.no_rangka = '{$temp_rangka}'";
          $where_gc .= " AND scb.no_rangka = '{$temp_rangka}'";
        }
      }
      
      if (isset($filter['no_polisi'])) {
        if ($filter['no_polisi'] != '') {
          // $where .= " AND (SELECT no_pol FROM tr_entry_stnk WHERE no_mesin=so.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) LIKE '%{$filter['no_polisi']}%'";
          // $where_gc .= " AND (SELECT no_pol FROM tr_entry_stnk WHERE no_mesin=sog.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) LIKE '%{$filter['no_polisi']}%'";
          $join =" LEFT JOIN (
                SELECT no_mesin, no_pol 
                FROM tr_entry_stnk
                WHERE no_pol LIKE '%{$filter['no_polisi']}%'
                GROUP BY no_mesin) AS latest_stnk ON so.no_mesin = latest_stnk.no_mesin";
          $where = " AND latest_stnk.no_pol LIKE '%{$filter['no_polisi']}%'";
          $join_gc =" LEFT JOIN (
                SELECT no_mesin, no_pol     
                FROM tr_entry_stnk
                WHERE no_pol LIKE '%{$filter['no_polisi']}%'
                GROUP BY no_mesin) AS latest_stnk ON sog.no_mesin = latest_stnk.no_mesin";
          $where_gc = " AND latest_stnk.no_pol LIKE '%{$filter['no_polisi']}%'";
      }
    }
      if (isset($filter['no_polisi_sc'])) {
        if ($filter['no_polisi_sc'] != '') {
          $filter['no_polisi_sc'] = str_replace(' ', '', $filter['no_polisi_sc']);
          $where .= " AND (SELECT replace(no_pol,' ','') FROM tr_entry_stnk WHERE no_mesin=so.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) = '{$filter['no_polisi_sc']}'";
          $where_gc .= " AND (SELECT replace(no_pol,' ','') FROM tr_entry_stnk WHERE no_mesin=sog.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) = '{$filter['no_polisi_sc']}'";
        }
      }

      if (isset($filter['no_mesin_no_rangka_no_polisi'])) {
        $val    = $filter['no_mesin_no_rangka_no_polisi'];
        $val[2] = str_replace(' ', '', $val[2]);
        // $where .= " AND 
        //         (
        //           (SELECT replace(no_pol, ' ','') FROM tr_entry_stnk WHERE no_mesin=so.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1)='{$val[2]}'
        //           AND so.no_mesin='{$val[0]}' AND so.no_rangka='{$val[1]}'
        //         )
        //         ";
        
        $temp_rangka = strtoupper($val[1]);
        if(strlen($temp_rangka) == 17){
          $originalStr = $temp_rangka;
          $prefix = substr($originalStr, 0, 3);
          $temp_rangka = str_replace("MH1","", $prefix) . substr($originalStr, 3);
        }

        $where_gc .= " AND (
          (SELECT replace(no_pol,' ','') FROM tr_entry_stnk WHERE no_mesin=sog.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1)= '{$val[2]}'
          AND sog.no_mesin='{$val[0]}' AND scb.no_rangka='{$temp_rangka}'
        )";

        $join =" LEFT JOIN (
          SELECT no_mesin, no_pol 
          FROM tr_entry_stnk
          WHERE REPLACE(no_pol, ' ', '') = '{$val[2]}'
          GROUP BY no_mesin) AS latest_stnk ON so.no_mesin = latest_stnk.no_mesin";
        $where = " AND 
        (
          REPLACE(latest_stnk.no_pol, ' ', '')='{$val[2]}'
          AND so.no_mesin='{$val[0]}' AND so.no_rangka='{$temp_rangka}'
        )";
        $join_gc =" LEFT JOIN (
          SELECT no_mesin, no_pol     
          FROM tr_entry_stnk
          WHERE REPLACE(no_pol, ' ', '') = '{$val[2]}'
          GROUP BY no_mesin) AS latest_stnk ON sog.no_mesin = latest_stnk.no_mesin";
        $where_gc = " AND (
          REPLACE(latest_stnk.no_pol, ' ', '')='{$val[2]}'
          AND sog.no_mesin='{$val[0]}' AND scb.no_rangka='{$temp_rangka}'
        )";
      }
    }

    if($filter['jenis_customer_beli'] == 'Regular'){
      return $this->db->query("SELECT 'h1' AS customer_from,replace(nama_konsumen,'\'','`') AS nama_customer,'' AS jenis_kelamin,no_ktp AS no_identitas,'ktp' AS jenis_identitas, spk.alamat2 AS alamat_identitas,spk.id_tipe_kendaraan,CONCAT(spk.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm,spk.id_warna,so.no_mesin,concat('MH1',so.no_rangka) as no_rangka,
      (SELECT no_pol FROM tr_entry_stnk WHERE no_mesin=so.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) AS no_polisi,spk.no_hp,so.id_sales_order,'Regular' AS jenis_customer_beli,spk.longitude,spk.latitude, spk.nama_bpkb AS nama_stnk,spk.email,spk.id_kelurahan,kel.kelurahan,kec.kecamatan,kab.kabupaten,prov.provinsi,
      spk.id_kelurahan2 AS id_kelurahan_identitas,kel2.kelurahan AS kelurahan_identitas,kec2.kecamatan AS kecamatan_identitas,kab2.kabupaten AS kabupaten_identitas,prov2.provinsi AS provinsi_identitas,CONCAT(spk.id_warna,' | ',wr.warna) AS warna,so.tahun_produksi,spk.alamat,so.id_dealer AS id_dealer_h1,so.tgl_cetak_invoice AS tgl_pembelian,cdb.facebook,cdb.twitter,cdb.instagram
        FROM tr_sales_order so
        JOIN tr_spk spk ON spk.no_spk=so.no_spk
        JOIN tr_cdb cdb ON cdb.no_spk=spk.no_spk
        JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spk.id_tipe_kendaraan
        JOIN ms_warna wr ON wr.id_warna=spk.id_warna
        $join
        LEFT JOIN ms_kelurahan AS kel ON kel.id_kelurahan=spk.id_kelurahan
        LEFT JOIN ms_kecamatan AS kec ON kec.id_kecamatan=kel.id_kecamatan
        LEFT JOIN ms_kabupaten AS kab ON kab.id_kabupaten=kec.id_kabupaten
        LEFT JOIN ms_provinsi AS prov ON prov.id_provinsi=kab.id_provinsi
        LEFT JOIN ms_kelurahan AS kel2 ON kel2.id_kelurahan=id_kelurahan2
        LEFT JOIN ms_kecamatan AS kec2 ON kec2.id_kecamatan=kel2.id_kecamatan
        LEFT JOIN ms_kabupaten AS kab2 ON kab2.id_kabupaten=kec2.id_kabupaten
        LEFT JOIN ms_provinsi AS prov2 ON prov2.id_provinsi=kab2.id_provinsi
        $where
      ");
    }elseif($filter['jenis_customer_beli'] == 'Group Sales'){
      return $this->db->query("SELECT 'h1' AS customer_from,replace(nama_npwp,'\'','`') AS nama_customer,'' AS jenis_kelamin,no_npwp AS no_identitas,'npwp' AS jenis_identitas, spk.alamat2 AS alamat_identitas,tk.id_tipe_kendaraan,CONCAT(tk.id_tipe_kendaraan,' | ',tipe_ahm) AS tipe_ahm,wr.id_warna,scb.no_mesin, concat('MH1',scb.no_rangka) as no_rangka,
          '' AS no_polisi,spk.no_hp,so.id_sales_order_gc AS id_sales_order,'Group Sales' AS jenis_customer_beli,spk.longitude,spk.latitude, nama_npwp AS nama_stnk,spk.email,spk.id_kelurahan,kel.kelurahan,kec.kecamatan,kab.kabupaten,prov.provinsi,
        spk.id_kelurahan2 AS id_kelurahan_identitas,kel2.kelurahan AS kelurahan_identitas,kec2.kecamatan AS kecamatan_identitas,kab2.kabupaten AS kabupaten_identitas,prov2.provinsi AS provinsi_identitas,CONCAT(wr.id_warna,' | ',wr.warna) AS warna,(SELECT tahun_produksi FROM tr_fkb where no_mesin_spasi=sog.no_mesin) AS tahun_produksi,spk.alamat,so.id_dealer AS id_dealer_h1,LEFT(so.tgl_cetak_invoice,10) AS tgl_pembelian,'' facebook,'' twitter,'' instagram
          FROM tr_sales_order_gc_nosin sog
          JOIN tr_sales_order_gc so ON sog.id_sales_order_gc=so.id_sales_order_gc
          JOIN tr_spk_gc spk ON spk.no_spk_gc=so.no_spk_gc
          JOIN tr_scan_barcode scb ON scb.no_mesin=sog.no_mesin
          JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=scb.tipe_motor
          JOIN ms_warna wr ON wr.id_warna=scb.warna
          $join_gc
          LEFT JOIN ms_kelurahan AS kel ON kel.id_kelurahan=spk.id_kelurahan
          LEFT JOIN ms_kecamatan AS kec ON kec.id_kecamatan=kel.id_kecamatan
          LEFT JOIN ms_kabupaten AS kab ON kab.id_kabupaten=kec.id_kabupaten
          LEFT JOIN ms_provinsi AS prov ON prov.id_provinsi=kab.id_provinsi
          LEFT JOIN ms_kelurahan AS kel2 ON kel2.id_kelurahan=id_kelurahan2
          LEFT JOIN ms_kecamatan AS kec2 ON kec2.id_kecamatan=kel2.id_kecamatan
          LEFT JOIN ms_kabupaten AS kab2 ON kab2.id_kabupaten=kec2.id_kabupaten
          LEFT JOIN ms_provinsi AS prov2 ON prov2.id_provinsi=kab2.id_provinsi
          $where_gc
        ");
    }
  }

  function cekCustomerH1Individu($filter)
  {
    if ($filter != null) {
      $where = "WHERE 1=1 AND NOT EXISTS (SELECT no_mesin FROM ms_customer_h23 WHERE no_mesin=so.no_mesin) ";
      if (isset($filter['no_mesin'])) {
        if ($filter['no_mesin'] != '') {
          $where .= " AND so.no_mesin= '{$filter['no_mesin']}'";
        }
      }
      if (isset($filter['no_rangka'])) {
        if ($filter['no_rangka'] != '') {
          $where .= " AND so.no_rangka='{$filter['no_rangka']}'";
        }
      }
      if (isset($filter['no_polisi'])) {
        if ($filter['no_polisi'] != '') {
          $where .= " AND (SELECT no_pol FROM tr_entry_stnk WHERE no_mesin=so.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) = '{$filter['no_polisi']}'";
        }
      }
    }

    return $this->db->query("SELECT id_sales_order,so.no_spk
      FROM tr_sales_order so
      $where
    ");
  }

  function cekCustomerH1GC($filter)
  {
    if ($filter != null) {
      $where_gc = "WHERE 1=1 AND NOT EXISTS (SELECT no_mesin FROM ms_customer_h23 WHERE no_mesin=sog.no_mesin) ";

      if (isset($filter['no_mesin'])) {
        if ($filter['no_mesin'] != '') {
          $where_gc .= " AND sog.no_mesin = '%{$filter['no_mesin']}%'";
        }
      }
      if (isset($filter['no_rangka'])) {
        if ($filter['no_rangka'] != '') {
          $where_gc .= " AND scb.no_rangka = '%{$filter['no_rangka']}%'";
        }
      }
      if (isset($filter['no_polisi'])) {
        if ($filter['no_polisi'] != '') {
          $where_gc .= " AND (SELECT no_pol FROM tr_entry_stnk WHERE no_mesin=sog.no_mesin ORDER BY id_entry_stnk DESC LIMIT 1) = '%{$filter['no_polisi']}%'";
        }
      }
    }

    return $this->db->query("SELECT sog.no_mesin
      FROM tr_sales_order_gc_nosin sog
      JOIN tr_scan_barcode scb ON scb.no_mesin=sog.no_mesin
      $where_gc
    ");
  }


  function fetch_pembawa($filter)
  {

    $order_column = array('id_pembawa', 'nama', 'jenis_kelamin', 'hubungan_dengan_pemilik', 'no_hp', null);
    if ($filter != null) {
      $where = "WHERE id_customer='{$filter['id_customer']}' ";
      if ($filter['nama_pembawa'] != '') {
        $where .= " AND nama LIKE '%{$filter['nama_pembawa']}%'";
      }
      if ($filter['no_hp'] != '') {
        $where .= " AND ch23.no_hp LIKE '%{$filter['no_hp']}%'";
      }
    }
    $set_filter = '';
    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_pembawa,replace(nama,'\'','`') as nama,jenis_kelamin,hubungan_dengan_pemilik,no_hp
      FROM ms_h2_pembawa AS pb
      $where
    $set_filter
    ");
  }

  function getPembawa($filter = null)
  {
    $where = "WHERE aktif=1";
    if ($filter != null) {
      if (isset($filter['id_pembawa'])) {
        $where .= " AND pbw.id_pembawa='{$filter['id_pembawa']}' ";
      }
    }
    return $this->db->query("SELECT pbw.id_pembawa, replace(nama,'\'','`') as nama,jenis_kelamin,no_identitas,jenis_identitas,alamat_identitas,no_hp,pbw.email,pbw.alamat_saat_ini,
    pbw.id_kelurahan,kel.kelurahan,kec.kecamatan,kab.kabupaten,prov.provinsi,
    pbw.id_kelurahan_identitas,kel2.kelurahan AS kelurahan_identitas,kec2.kecamatan AS kecamatan_identitas,kab2.kabupaten AS kabupaten_identitas,prov2.provinsi AS provinsi_identitas,
    id_kelurahan_identitas,pbw.id_agama,agama,hubungan_dengan_pemilik,pbw.facebook,pbw.twitter,pbw.instagram,pbw.nama nama_pembawa
    FROM ms_h2_pembawa pbw
    LEFT JOIN ms_kelurahan AS kel ON kel.id_kelurahan=pbw.id_kelurahan
    LEFT JOIN ms_kecamatan AS kec ON kec.id_kecamatan=kel.id_kecamatan
    LEFT JOIN ms_kabupaten AS kab ON kab.id_kabupaten=kec.id_kabupaten
    LEFT JOIN ms_provinsi AS prov ON prov.id_provinsi=kab.id_provinsi
    LEFT JOIN ms_kelurahan AS kel2 ON kel2.id_kelurahan=id_kelurahan_identitas
    LEFT JOIN ms_kecamatan AS kec2 ON kec2.id_kecamatan=kel2.id_kecamatan
    LEFT JOIN ms_kabupaten AS kab2 ON kab2.id_kabupaten=kec2.id_kabupaten
    LEFT JOIN ms_provinsi AS prov2 ON prov2.id_provinsi=kab2.id_provinsi
    LEFT JOIN ms_agama AS ag ON ag.id_agama=pbw.id_agama
    $where limit 10
    ");
  }

  function fetch_so_ready_nsc($filter)
  {

    $order_column = array('nomor_so ', 'tanggal_so', 'nama_customer', null);
    $id_dealer = $this->m_admin->cari_dealer();
    $cek_booking_wo = "SELECT COUNT(id_booking) 
                      FROM tr_h2_wo_dealer_parts wop 
                      JOIN tr_h2_wo_dealer wod ON wod.id_work_order=wop.id_work_order
                      WHERE wop.id_booking=dso.booking_id_reference and wod.status ='closed' AND wod.id_dealer=dso.id_dealer
                      ";
    // $set_filter   = "WHERE dso.id_dealer='$id_dealer' AND dso.status='Processing' AND id_work_order IS NULL AND NOT EXISTS (SELECT id_referensi FROM tr_h23_nsc WHERE id_referensi=dso.nomor_so) 
    // AND ($cek_booking_wo)=0
    // ";

    $set_filter   = "WHERE dso.id_dealer='$id_dealer' AND dso.status='Processing' AND id_work_order IS NULL 
    ";

    $search = $filter['search'];
    if ($search != '') {
      $set_filter .= " AND (dso.nama_pembeli LIKE '%$search%'
                            OR dso.nomor_so LIKE '%$search%'
                            OR dso.tanggal_so LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY dso.created_at DESC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT dso.*,REPLACE(dso.nama_pembeli,'\'','`') nama_pembeli, REPLACE(dso.nama_pembeli,'\'','`') nama_customer
    FROM tr_h3_dealer_sales_order AS dso
    -- JOIN ms_customer_h23 ch23 ON ch23.id_customer=dso.id_customer
    $set_filter
    ");
  }
  function getSo($filter = null)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $where = "WHERE 1=1 AND dl_so.id_dealer='$id_dealer' ";
    if ($filter != null) {
      if (isset($filter['nomor_so'])) {
        $where .= " AND so.nomor_so='{$filter['nomor_so']}' ";
      }
      if (isset($filter['booking_by_wo'])) {
        $where .= " AND so.booking_id_reference IN(SELECT id_booking FROM tr_h2_wo_dealer_parts WHERE id_work_order='{$filter['booking_by_wo']}') ";
      }
      if (isset($filter['id_work_order_null'])) {
        $where .= " AND so.id_work_order IS NULL ";
      }
    }
    return $this->db->query("SELECT so.nomor_so,
    CASE 
      WHEN ch23.id_customer IS NULL THEN replace(nama_pembeli,'\'','`')
      ELSE replace(ch23.nama_customer,'\'','`')
    END AS nama_customer,
    CASE 
      WHEN ch23.no_hp IS NULL THEN no_hp_pembeli
      ELSE ch23.no_hp
    END AS no_hp,
    CASE 
      WHEN ch23.alamat IS NULL THEN alamat_pembeli
      ELSE ch23.alamat
    END AS alamat,
    dl_so.nama_dealer AS dealer_so,dl_so.kode_dealer_md AS kd_dealer_so,concat(dl_lain.kode_dealer_md,' | ',dl_lain.nama_dealer) AS dealer_po,
    tipe_ahm,ch23.no_polisi,tgl_servis,
    IFNULL(uj.total_bayar,0) AS total_bayar,uj.no_inv_uang_jaminan,booking_id_reference,po.po_id,ps.nomor_ps,so.pembelian_dari_dealer_lain,so.id_dealer_pembeli,so.id nomor_so_int
    FROM tr_h3_dealer_sales_order so
    JOIN ms_dealer dl_so ON dl_so.id_dealer=so.id_dealer
    LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=so.id_customer
    LEFT JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ch23.id_tipe_kendaraan
    LEFT JOIN ms_dealer dl_lain ON dl_lain.id_dealer=so.id_dealer_pembeli
    LEFT JOIN tr_h2_wo_dealer wo ON wo.id_work_order=so.id_work_order
    LEFT JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
    LEFT JOIN tr_h3_dealer_picking_slip ps ON ps.nomor_so=so.nomor_so
    LEFT JOIN tr_h3_dealer_purchase_order po ON po.id_booking = so.booking_id_reference AND po.id_dealer=so.id_dealer_pembeli
    LEFT JOIN tr_h2_uang_jaminan uj ON uj.id_booking=so.booking_id_reference AND so.id_dealer=uj.id_dealer
    $where
    ");
  }

  function getSoPart($filter = null)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $where = "WHERE 1=1 ";
    $qty_return = "CASE WHEN sop.return=1 THEN sop.kuantitas_return ELSE 0 END";
    $qty = "(sop.kuantitas - IFNULL(($qty_return),0))";
    if ($filter != null) {
      if (isset($filter['nomor_so'])) {
        $where .= " AND sop.nomor_so='{$filter['nomor_so']}' ";
      }
      if (isset($filter['status_so'])) {
        $where .= " AND sop.status='{$filter['status_so']}' ";
      }
      if (isset($filter['id_work_order'])) {
        $where .= " AND (SELECT nomor_so FROM tr_h2_wo_dealer_parts wop WHERE wop.nomor_so=sop.nomor_so AND wop.id_part=sop.id_part AND wop.id_work_order='{$filter['id_work_order']}')=sop.nomor_so ";
      }
      if (isset($filter['booking_by_wo'])) {
        $where .= " AND IFNULL(so.booking_id_reference,'')!='' AND so.booking_id_reference IN(SELECT id_booking 
                      FROM tr_h2_wo_dealer_parts wop
                      WHERE wop.id_booking=so.booking_id_reference 
                      AND wop.id_work_order='{$filter['booking_by_wo']}'  AND IFNULL(id_booking,'')!='') AND so.id_dealer=(SELECT id_dealer FROM tr_h2_wo_dealer WHERE id_work_order='{$filter['booking_by_wo']}')";
      }
      if (isset($filter['qty_besar_dari_nol'])) {
        $where .= " AND $qty>0";
      }
    }
    // $diskon = "(CASE 
    //               WHEN tipe_diskon='Percentage' THEN prt.harga_dealer_user * IFNULL(diskon_value/100,0)
    //               WHEN tipe_diskon='Value' THEN diskon_value
    //               WHEN tipe_diskon='foc' then diskon_value*prt.harga_dealer_user
    //             END
    //           )";
    return $this->db->query("SELECT sop.kuantitas AS qty_pesan,sop.id_part,sop.harga_saat_dibeli AS harga_beli,nama_part,sop.kuantitas,sop.id_gudang,sop.id_rak,
      CASE WHEN sop.tipe_diskon IS NOT NULL THEN diskon_value ELSE 0 END AS diskon_value,
      sop.tipe_diskon,id_promo,so.id_dealer,$qty AS qty,sop.nomor_so,sop.id_part_int,sop.tipe_diskon,sop.harga_saat_dibeli, (CASE WHEN prt.kelompok_part = 'EVBT' THEN 'B' WHEN prt.kelompok_part = 'EVCH' THEN 'C' ELSE '' END) as type_acc
    FROM tr_h3_dealer_sales_order_parts sop
    JOIN tr_h3_dealer_sales_order so ON so.nomor_so=sop.nomor_so
    JOIN tr_h3_dealer_picking_slip pl ON pl.nomor_so=so.nomor_so
    JOIN ms_part prt ON prt.id_part_int=sop.id_part_int
    $where
    group by sop.id_part
    ");
  }

  function fetch_wo_proses($filter)
  {

    $order_column = array('id_work_order ', 'tgl_servis', 'jam_servis', 'ch23.id_customer', null, null);
    $id_dealer = $this->m_admin->cari_dealer();
    if (isset($filter['id_dealer'])) {
      if ($filter['id_dealer'] != '') {
        $id_dealer = $filter['id_dealer'];
      }
    }
    $set_filter   = "WHERE wo.id_dealer='$id_dealer' ";
    if ($filter['need_parts'] == true) {
      // $set_filter .= "AND (SELECT COUNT(id_part) FROM tr_h2_wo_dealer_parts WHERE id_work_order=wo.id_work_order AND jenis_order='reguler')>0 ";
      $set_filter .= "AND (SELECT SUM(qty-IFNULL(kuantitas_return,0))
      FROM tr_h2_wo_dealer_parts wop
      LEFT JOIN tr_h3_dealer_sales_order_parts sop ON sop.nomor_so=wop.nomor_so AND sop.id_part_int=wop.id_part_int WHERE wop.id_work_order=wo.id_work_order AND IFNULL(wop.pekerjaan_batal,0)=0)>0 ";
    }
    if ($filter['status_wo'] != '') {
      $set_filter .= "AND wo.status='{$filter['status_wo']}' ";
    }
    if ($filter['njb_null'] != '') {
      $set_filter .= "AND wo.no_njb  IS NULL ";
    }
    if ($filter['id_claim_not_in_lkh'] != '') {
      $set_filter .= "AND lkh.id_work_order  IS NULL ";
    }
    if ($filter['njb_not_null'] != '') {
      $set_filter .= "AND wo.no_njb  IS NOT NULL ";
    }
    if ($filter['wo_c2'] != '') {
      $set_filter .= "AND (SELECT COUNT(wopk.id_jasa) FROM tr_h2_wo_dealer_pekerjaan wopk 
        JOIN ms_h2_jasa js ON js.id_jasa=wopk.id_jasa
        WHERE js.id_type in('C2','C1') AND wopk.id_work_order=wo.id_work_order)>0 ";
    }
    if ($filter['pekerjaan_luar'] != '') {
      $set_filter .= "AND EXISTS(SELECT id_work_order FROM tr_h2_wo_dealer_pekerjaan WHERE id_work_order=wo.id_work_order AND pekerjaan_luar=1 AND id_surat_jalan IS NULL) ";
    }
    if ($filter['not_exists_nsc'] == true) {
      $set_filter .= "AND NOT EXISTS (SELECT id_referensi FROM tr_h23_nsc WHERE id_referensi=wo.id_work_order) ";
    }
    // $search = $filter['search'];
    $search = $this->db->escape_str($filter['search']);
    if ($search != '') {
      $set_filter .= " AND (ch23.nama_customer LIKE '%$search%'
                            OR ch23.id_customer LIKE '%$search%'
                            OR wo.id_work_order LIKE '%$search%'
                            OR sa.tgl_servis LIKE '%$search%'
                            OR sa.jam_servis LIKE '%$search%'
                            ) 
            ";
    }


    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY wo.created_at DESC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT wo.id_work_order,jam_servis,tgl_servis,ch23.id_customer,replace(ch23.nama_customer,'\'','`') as nama_customer,kdl.nama_lengkap AS mekanik,wo.id_sa_form
    FROM tr_h2_wo_dealer AS wo
    JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form
    JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
    LEFT JOIN tr_lkh lkh ON lkh.id_work_order=wo.id_work_order
    JOIN ms_karyawan_dealer kdl ON kdl.id_karyawan_dealer=wo.id_karyawan_dealer
    $set_filter
    ");
  }

  function getWOParts($filter = null)
  {
    $get_no_so_hlo = "SELECT sop.nomor_so FROM tr_h3_dealer_sales_order_parts sop
    JOIN tr_h3_dealer_sales_order so ON so.nomor_so=sop.nomor_so
    WHERE so.booking_id_reference=wo.id_booking AND sop.id_part=wo.id_part AND so.id_dealer=wo_d.id_dealer AND EXISTS(SELECT nomor_so FROM tr_h3_dealer_picking_slip psl WHERE nomor_so=sop.nomor_so)
    ";
    $get_id_gudang_hlo = "SELECT sop.id_gudang FROM tr_h3_dealer_sales_order_parts sop
    JOIN tr_h3_dealer_sales_order so ON so.nomor_so=sop.nomor_so
    WHERE so.booking_id_reference=wo.id_booking AND sop.id_part=wo.id_part AND so.id_dealer=wo_d.id_dealer AND EXISTS(SELECT nomor_so FROM tr_h3_dealer_picking_slip psl WHERE nomor_so=sop.nomor_so)
    ";
    $get_id_rak_hlo = "SELECT sop.id_rak FROM tr_h3_dealer_sales_order_parts sop
    JOIN tr_h3_dealer_sales_order so ON so.nomor_so=sop.nomor_so
    WHERE so.booking_id_reference=wo.id_booking AND sop.id_part=wo.id_part AND so.id_dealer=wo_d.id_dealer AND EXISTS(SELECT nomor_so FROM tr_h3_dealer_picking_slip psl WHERE nomor_so=sop.nomor_so)
    ";

    $qty = "SELECT (sop.kuantitas - IFNULL(sop.kuantitas_return,0)) FROM tr_h3_dealer_sales_order_parts sop WHERE sop.nomor_so=wo.nomor_so AND sop.id_part=wo.id_part";

    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_work_order'])) {
        $where .= " AND wo.id_work_order='{$filter['id_work_order']}' ";
      }
      if (isset($filter['jenis_order'])) {
        $where .= " AND wo.jenis_order='{$filter['jenis_order']}' ";
      }
      if (isset($filter['qty_besar_dari_nol'])) {
        $where .= " AND ($qty)>0";
      }
      if (isset($filter['group_nomor_so'])) {
        $where .= " GROUP BY nomor_so ";
      }
    }

    return $this->db->query("SELECT wo.id_part,harga AS harga_beli,nama_part,wo.qty AS kuantitas,wo.id_promo,tipe_diskon,diskon_value,
    CASE 
      WHEN wo.nomor_so IS NOT NULL THEN wo.nomor_so
      WHEN wo.nomor_so IS NULL THEN ($get_no_so_hlo)
    END AS nomor_so,
    CASE 
      WHEN wo.nomor_so IS NOT NULL THEN wo.id_gudang
      WHEN wo.nomor_so IS NULL THEN ($get_id_gudang_hlo)
    END AS id_gudang,
    CASE 
      WHEN wo.nomor_so IS NOT NULL THEN wo.id_rak
      WHEN wo.nomor_so IS NULL THEN ($get_id_rak_hlo)
    END AS id_rak,($qty) qty,wo_d.id_dealer
    FROM tr_h2_wo_dealer_parts wo
    JOIN tr_h2_wo_dealer wo_d ON wo_d.id_work_order=wo.id_work_order
    JOIN ms_part prt ON prt.id_part=wo.id_part
    $where
    ");
  }

  function fetch_getAntrian($filter)
  {

    $order_column = array('id_antrian ', 'tgl_servis', 'jam_servis', 'ch23.id_customer', null, null);
    $id_dealer = $this->m_admin->cari_dealer();
    $set_filter   = "WHERE sa.id_dealer='$id_dealer' ";

    if ($filter['id_sa_form_null'] != '') {
      $set_filter .= "AND sa.id_sa_form IS NULL ";
      $set_filter .= "AND sa.tgl_servis='{$filter['tgl_servis']}' ";
    }
    // $search = $filter['search'];
    $search = $this->db->escape_str($filter['search']);
    if ($search != '') {
      $set_filter .= " AND (ch23.nama_customer LIKE '%$search%'
                            OR ch23.id_customer LIKE '%$search%'
                            OR sa.id_antrian LIKE '%$search%'
                            OR sa.tgl_servis LIKE '%$search%'
                            OR sa.jam_servis LIKE '%$search%'
                            ) 
            ";
    }


    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      // $set_filter .= "ORDER BY jenis_customer,jam_servis ASC ";
      $set_filter .= "ORDER BY sa.created_at DESC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT sa.id_antrian,jam_servis,tgl_servis,ch23.id_customer,ch23.nama_customer,sa.jenis_customer,
    CASE WHEN no_polisi IS NULL THEN no_polisi_antri ELSE no_polisi END no_polisi,
    CASE WHEN ch23.no_mesin IS NULL THEN no_mesin_antri ELSE ch23.no_mesin END no_mesin
    FROM tr_h2_sa_form AS sa
    LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
    $set_filter
    ");
  }
  function fetch_getSaForm($filter)
  {

    $order_column = array('id_sa_form ', 'tgl_servis', 'jam_servis', 'ch23.id_customer', 'ch23.nama_customer', 'sa.jenis_customer');
    $id_dealer = $this->m_admin->cari_dealer();
    $set_filter   = "WHERE sa.id_dealer='$id_dealer' ";

    if (isset($filter['status_form'])) {
      if ($filter['status_form'] != '') {
        $set_filter .= "AND sa.status_form='{$filter['status_form']}' ";
      }
      if ($filter['status_form'] == 'open') {
        if (isset($filter['tgl_servis'])) {
          if ($filter['tgl_servis'] != '') {
            $set_filter .= "AND sa.tgl_servis='{$filter['tgl_servis']}' ";
          }
        }
      }
    }
    // $search = $filter['search'];
    $search = $this->db->escape_str($filter['search']);
    if ($search != '') {
      $set_filter .= " AND (ch23.nama_customer LIKE '%$search%'
                            OR ch23.id_customer LIKE '%$search%'
                            OR sa.id_sa_form LIKE '%$search%'
                            OR sa.id_antrian LIKE '%$search%'
                            OR sa.tgl_servis LIKE '%$search%'
                            OR sa.jam_servis LIKE '%$search%'
                            ) 
            ";
    }
    // $set_filter .= "AND (SELECT count(id_part) FROM tr_h2_sa_form_parts WHERE id_sa_form=sa.id_sa_form AND jenis_order='HLO' AND send_notif=1)=(SELECT count(id_part) FROM tr_h2_sa_form_parts WHERE id_sa_form=sa.id_sa_form AND jenis_order='HLO')";

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= "ORDER BY tgl_servis DESC, jenis_customer,jam_servis ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT sa.id_sa_form,jam_servis,tgl_servis,ch23.id_customer,ch23.nama_customer,sa.jenis_customer
    FROM tr_h2_sa_form AS sa
    JOIN ms_customer_h23 ch23 ON ch23.id_customer=sa.id_customer
    $set_filter
    ");
  }

  public function fetch_salesPartsDealerLain($filter)
  {
    $id_part = $filter['id_part'];
    $kuantitas = $filter['qty_part'];
    $id_tipe_kendaraan = $filter['id_tipe_kendaraan'];
    $stock = $this->db
      ->select('sum(ds.stock)')
      ->from('ms_h3_dealer_stock as ds')
      ->group_start()
      ->where('ds.id_dealer = dt.id_dealer_terdekat')
      ->where('ds.id_part = p.id_part')
      ->group_end()
      ->get_compiled_select();

    $min_stock = $this->db
      ->select('dmp.min_stok')
      ->from('ms_h3_dealer_master_part as dmp')
      ->group_start()
      ->where('dmp.id_dealer = dt.id_dealer_terdekat')
      ->where('dmp.id_part = p.id_part')
      ->group_end()
      ->get_compiled_select();

    $query = $this->db
      ->select('p.id_part')
      ->select('p.nama_part')
      ->select('concat("Rp ", format(p.harga_dealer_user, 0, "id_ID")) as harga_saat_dibeli')
      ->select('d.id_dealer')
      ->select('d.nama_dealer')
      ->select('p.harga_dealer_user')
      ->select('p.kelompok_part')
      ->select(" CASE 
          WHEN p.status='A' THEN 'Aktif'
          WHEN p.status='D' THEN 'Discontinue'
          ELSE '-'
          END AS status_part
    ")
      ->select("ifnull(({$stock}), 0) as stock")
      ->select("
                case
                    when ifnull(({$stock}), 0) = ifnull(({$min_stock}), 0) then 'Tidak Ada'
                    when ifnull(({$stock}), 0) < ifnull(({$min_stock}), 0) then 'Tidak Ada'
                    when (ifnull(({$stock}), 0) - {$kuantitas}) >= ifnull(({$min_stock}), 0) then 'Ada'
                    when (ifnull(({$stock}), 0) - {$kuantitas}) < ifnull(({$min_stock}), 0) then 'Ada - Tidak Cukup'
                end as status
            ")
      ->from('ms_h3_dealer_terdekat as dt')
      ->join('ms_dealer as d', 'd.id_dealer = dt.id_dealer_terdekat')
      ->join('ms_part as p', "p.id_part LIKE '%{$id_part}%'");
    if ($id_tipe_kendaraan != '') {
      $this->db->join('ms_pvtm as pv', 'pv.no_part = p.id_part');
      $this->db->join('ms_tipe_kendaraan as tk', 'tk.kode_part = pv.tipe_marketing');
      $this->db->where('tk.id_tipe_kendaraan', $id_tipe_kendaraan);
    }

    // $tanggal = date("Y-m-d");
    // if($tanggal <='2023-08-06' || $tanggal >='2023-08-12'){
    // }else{
    //   $this->db->where("p.kelompok_part !='FED OIL'");
    // }

    if($this->config->item('ahm_d_only')){
      $this->db->where("p.kelompok_part !='FED OIL'");
    }

    $this->db->where('dt.id_dealer', $this->m_admin->cari_dealer());
    // ->limit(0, 10)
    $this->db->order_by('dt.id_dealer_terdekat', 'DESC');
      // ->having('sum(ds.stock) >= dmp.min_stok')
    ;
    // echo $this->db->get_compiled_select();
    // die;
    if ($filter['limit'] != '') {
      // echo $filter['limit'];
      // $this->db->limit($filter['limit']);
      $this->db->limit($filter['limit'],$filter['offset']);
      // echo $this->db->get_compiled_select();
      // die;
    }
    return $query->get();
    // } else {
    //   send_json([]);
    // }
  }
  function fetch_modalPOCustomer($filter)
  {

    $order_column = array('po_id ', 'tanggal_order', 'id_booking', 'ch23.id_customer', 'ch23.nama_customer', null);
    $id_dealer = $this->m_admin->cari_dealer();
    $set_filter   = "WHERE po.id_dealer='$id_dealer' AND NOT EXISTS(SELECT po_id FROM tr_h2_uang_jaminan WHERE po_id=po.po_id) ";

    if (isset($filter['id_booking_not_null'])) {
      if ($filter['id_booking_not_null'] != '') {
        $set_filter .= "AND po.id_booking IS NOT NULL ";
      }
    }
    // $search = $filter['search'];
    $search = $this->db->escape_str($filter['search']);
    if ($search != '') {
      $set_filter .= " AND (ch23.nama_customer LIKE '%$search%'
                            OR ch23.id_customer LIKE '%$search%'
                            OR po.id_antrian LIKE '%$search%'
                            OR po.tgl_servis LIKE '%$search%'
                            OR po.jam_servis LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= "ORDER BY po.created_at DESC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT po.po_id,tanggal_order,po.id_booking,ch23.id_customer,replace(ch23.nama_customer,'\'','`') as nama_customer
    FROM tr_h3_dealer_purchase_order AS po
    JOIN tr_h3_dealer_request_document rq ON rq.id_booking=po.id_booking
    LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=rq.id_customer
    $set_filter
    ");
  }


  public function fetch_jasa_h2_dealer_modal($start, $length, $search, $order = null, $limit, $tipe_motor, $kategori, $job_type)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $order_column = array('id_jasa', 'deskripsi', 'id_type', 'kategori', 'harga', 'waktu', 'active', null);
    // $limit     = "LIMIT $start,$length";
    $order_by  = 'ORDER BY created_at DESC ';
    $searchs   = "WHERE 1=1 ";
    $search = $this->db->escape_str($search);
    if ($search != '') {
      $searchs .= " AND (id_jasa LIKE '%$search%' 
              OR deskripsi LIKE '%$search%'
              OR id_type LIKE '%$search%'
              OR harga LIKE '%$search%'
              OR kategori LIKE '%$search%'
              OR tipe_motor LIKE '%$search%')
          ";
    }

    if ($order != '') {
      $order_clm = $order_column[$order['0']['column']];
      $order_by  = $order['0']['dir'];
      $order_by     = " ORDER BY $order_clm $order_by ";
    }

    if ($limit == 'y') $limit = '';

    $set_tipe_motor = "";
    if ($tipe_motor != "") {
      $set_tipe_motor = "AND ms_h2_jasa.tipe_motor='$tipe_motor'";
    }
    return $this->db->query("SELECT * FROM (
            SELECT ms_h2_jasa.id_jasa,ms_h2_jasa.active,ms_h2_jasa.tipe_motor,waktu,
            ms_h2_jasa.deskripsi,ms_h2_jasa.id_type,ms_h2_jasa.kategori,
            ms_h2_jasa_type.deskripsi AS desk_tipe,
            CASE WHEN ms_h2_jasa_dealer.harga_dealer IS NULL OR ms_h2_jasa_dealer.id_jasa IS NULL
            THEN ms_h2_jasa.harga
            ELSE ms_h2_jasa_dealer.harga_dealer
            END AS harga,
            ms_h2_jasa.created_at
            FROM ms_h2_jasa
            JOIN ms_h2_jasa_type ON ms_h2_jasa_type.id_type=ms_h2_jasa.id_type
            JOIN ms_ptm ptm on ptm.tipe_produksi=ms_h2_jasa.tipe_motor
            LEFT JOIN ms_h2_jasa_dealer ON ms_h2_jasa_dealer.id_jasa=ms_h2_jasa.id_jasa AND id_dealer='$id_dealer'
            WHERE ms_h2_jasa.active=1 
            AND ms_h2_jasa.deleted_at IS NULL 
            AND (ms_h2_jasa.tipe_motor='' OR ms_h2_jasa.tipe_motor IS NULL)
            AND ms_h2_jasa.kategori='$kategori'
            AND ms_h2_jasa.id_type='$job_type'
            UNION
            SELECT ms_h2_jasa.id_jasa,ms_h2_jasa.active,ms_h2_jasa.tipe_motor,waktu,
            ms_h2_jasa.deskripsi,ms_h2_jasa.id_type,ms_h2_jasa.kategori,
            ms_h2_jasa_type.deskripsi AS desk_tipe,
            CASE WHEN ms_h2_jasa_dealer.harga_dealer IS NULL
            THEN ms_h2_jasa.harga
            ELSE ms_h2_jasa_dealer.harga_dealer
            END AS harga,ms_h2_jasa.created_at
            FROM ms_h2_jasa
            JOIN ms_h2_jasa_type ON ms_h2_jasa_type.id_type=ms_h2_jasa.id_type
            JOIN ms_ptm ptm on ptm.tipe_produksi=ms_h2_jasa.tipe_motor
            LEFT JOIN ms_h2_jasa_dealer ON ms_h2_jasa_dealer.id_jasa=ms_h2_jasa.id_jasa AND id_dealer='$id_dealer'
            WHERE ms_h2_jasa.active=1 
            AND ms_h2_jasa.deleted_at IS NULL 
            $set_tipe_motor
            AND ms_h2_jasa.kategori='$kategori'
            AND ms_h2_jasa.id_type='$job_type'
          ) AS tabel 
          $searchs $order_by $limit 
         ");
  }

  function fetch_getVendorPekerjaanLuar($filter)
  {

    $order_column = array('id_vendor ', 'nama_vendor', 'no_hp', 'alamat', null);
    $id_dealer = $this->m_admin->cari_dealer();
    $set_filter   = "WHERE vdr.id_dealer='$id_dealer' AND aktif=1 ";

    if (isset($filter['id_work_order'])) {
      if ($filter['id_work_order'] != '') {
        $id_work_order = $filter['id_work_order'];
        $set_filter .= "AND EXISTS (SELECT id_vendor FROM ms_h2_vendor_pekerjaan_luar_jasa js WHERE js.id_vendor=vdr.id_vendor AND EXISTS (SELECT id_jasa FROM tr_h2_wo_dealer_pekerjaan WHERE id_work_order='$id_work_order' AND id_jasa=js.id_jasa)) ";
      }
    }
    // $search = $filter['search'];
    $search = $this->db->escape_str($filter['search']);
    if ($search != '') {
      $set_filter .= " AND (vdr.id_vendor LIKE '%$search%'
                            OR vdr.nama_vendor LIKE '%$search%'
                            OR vdr.no_hp LIKE '%$search%'
                            OR vdr.alamat LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY vdr.nama_vendor ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_vendor,nama_vendor,no_hp,alamat,aktif
    FROM ms_h2_vendor_pekerjaan_luar AS vdr
    $set_filter
    ");
  }

  function fetch_getRekDealer($filter)
  {

    $order_column = array('no_rek ', 'nama_rek', 'jenis_rek', 'bank', null);
    $id_dealer = $this->m_admin->cari_dealer();
    $set_filter   = "WHERE rek.id_dealer='$id_dealer' ";

    // $search = $filter['search'];
    $search = $this->db->escape_str($filter['search']);
    if ($search != '') {
      $set_filter .= " AND (reks.no_rek LIKE '%$search%'
                            OR reks.nama_rek LIKE '%$search%'
                            OR reks.jenis_rek LIKE '%$search%'
                            OR bk.bank LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY reks.no_rek ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT no_rek,nama_rek,jenis_rek,bank,reks.id_bank
    FROM ms_norek_dealer_detail AS reks
    JOIN ms_norek_dealer rek ON rek.id_norek_dealer=reks.id_norek_dealer
    JOIN ms_bank bk ON bk.id_bank=reks.id_bank
    $set_filter
    ");
  }

  function fetch_getJasaWO($filter)
  {

    $order_column = array('js.id_jasa', 'js.deskripsi', 'tp.deskripsi', 'kategori', null);
    $set_filter   = "WHERE 1=1 ";

    if (isset($filter['id_work_order'])) {
      if ($filter['id_work_order'] != '') {
        $set_filter .= "AND wop.id_work_order='{$filter['id_work_order']}' ";
      }
      if ($filter['pekerjaan_luar'] != '') {
        $set_filter .= "AND wop.pekerjaan_luar=1 AND id_surat_jalan IS NULL";
      }
    }
    // $search = $filter['search'];
    $search = $this->db->escape_str($filter['search']);
    if ($search != '') {
      $set_filter .= " AND (wop.id_jasa LIKE '%$search%'
                            OR js.deskripsi LIKE '%$search%'
                            OR tp.deskripsi LIKE '%$search%'
                            OR js.kategori LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY wop.id_jasa ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT wop.id_jasa,js.deskripsi,tp.deskripsi AS desk_type,kategori,wop.harga
    FROM tr_h2_wo_dealer_pekerjaan AS wop
    JOIN ms_h2_jasa js ON js.id_jasa=wop.id_jasa
    JOIN ms_h2_jasa_type tp ON tp.id_type=js.id_type
    $set_filter
    ");
  }
  function fetch_getAllParts($filter)
  {

    $order_column = array('id_part', 'nama_part', 'harga_dealer_user', null);
    $set_filter   = "WHERE 1=1 ";

    if (isset($filter['id_tipe_kendaraan'])) {
      if ($filter['id_tipe_kendaraan'] != '') {
        $set_filter .= " AND EXISTS(SELECT pvt.no_part FROM ms_pvtm pvt
                        JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
                        JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ptm.tipe_marketing
                        WHERE pvt.no_part=ds.id_part AND tk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'
                       )";
      }
    }

    // $search = $filter['search'];
    $search = $this->db->escape_str($filter['search']);
    if ($search != '') {
      $set_filter .= " AND (id_part LIKE '%$search%'
                            OR nama_part LIKE '%$search%'
                            OR harga_dealer_user LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY id_part ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT id_part,nama_part,harga_dealer_user
    FROM ms_part
    $set_filter
    ");
  }

  function fetch_getNJBNSC($filter)
  {

    $order_column = array('kode_coa', 'coa', 'tipe_transaksi', null);
    $set_filter   = "WHERE 1=1 ";

    // $search = $filter['search'];
    $search = $this->db->escape_str($filter['search']);
    if ($search != '') {
      $set_filter .= " AND (kode_coa LIKE '%$search%'
                            OR coa LIKE '%$search%'
                            OR tipe_transaksi LIKE '%$search%'
                            ) 
            ";
    }

    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      $set_filter .= " ORDER BY kode_coa ASC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT kode_coa,coa,tipe_transaksi
    FROM ms_coa
    $set_filter
    ");
  }

  public function promo_query($id_part, $kelompok_part)
  {
    $this->db
      ->select('prm.*')
      ->select('date_format(prm.start_date, "%d/%m/%Y") as start_date')
      ->select('date_format(prm.end_date, "%d/%m/%Y") as end_date')
      ->from('ms_h3_promo_dealer as prm')
      ->join('ms_h3_promo_dealer_items as prmi', 'prm.id_promo = prmi.id_promo')
      ->group_start()
      ->where("'{$id_part}' in (prmi.id_part)")
      ->or_where("'{$kelompok_part}' = prm.kelompok_part")
      ->group_end()
      ->where('prm.start_date <= date(now())')
      ->where('prm.end_date >= date(now())')
      ->group_by('prm.id_promo')
      ->order_by('prm.created_at', 'desc');

    $data = $this->db->get()->result_array();
    $index = 0;
    foreach ($data as $each) {
      $promo_items = $this->db
        ->from('ms_h3_promo_dealer_items as prmi')
        ->where('prmi.id_promo', $each['id_promo'])
        ->order_by('prmi.qty', 'desc')
        ->get()->result_array();
      $data[$index]['promo_items'] = $promo_items;
      $index++;
    }

    return $data;
  }
  function fetch_getKaryawanDealer($filter)
  {

    $order_column = array('id_antrian ', 'tgl_servis', 'jam_servis', 'ch23.id_customer', null, null);

    $set_filter   = "WHERE 1=1 ";

    if (isset($filter['id_dealer'])) {
      $set_filter .= "AND kd.id_dealer='{$filter['id_dealer']}' ";
    }
    if (isset($filter['active'])) {
      $set_filter .= "AND kd.active='{$filter['active']}' ";
    }
    if (isset($filter['karyawan_can_login'])) {
      $set_filter .= "AND usr.username IS NOT NULL ";
    }
    // $search = $filter['search'];
    $search = $this->db->escape_str($filter['search']);
    if ($search != '') {
      $set_filter .= " AND (kd.id_karyawan_dealer LIKE '%$search%'
                            OR kd.id_flp_md LIKE '%$search%'
                            OR kd.honda_id LIKE '%$search%'
                            OR kd.nama_lengkap LIKE '%$search%'
                            OR usr.username LIKE '%$search%'
                            OR usr.username_sc LIKE '%$search%'
                            OR jb.jabatan LIKE '%$search%'
                            ) 
            ";
    }


    $order = $filter['order'];
    if ($order != '') {
      $order_clm  = $order_column[$order['0']['column']];
      $order_by   = $order['0']['dir'];
      $set_filter .= " ORDER BY $order_clm $order_by ";
    } else {
      // $set_filter .= "ORDER BY jenis_customer,jam_servis ASC ";
      $set_filter .= "ORDER BY kd.id_karyawan_dealer DESC ";
    }

    $set_filter .= $filter['limit'];

    return $this->db->query("SELECT kd.id_karyawan_dealer,kd.nama_lengkap,id_flp_md,honda_id,jb.jabatan,usr.username,usr.username_sc
    FROM ms_karyawan_dealer AS kd
    JOIN ms_jabatan jb ON jb.id_jabatan=kd.id_jabatan
    LEFT JOIN ms_user usr ON usr.id_karyawan_dealer=kd.id_karyawan_dealer
    $set_filter
    ");
  }
}
