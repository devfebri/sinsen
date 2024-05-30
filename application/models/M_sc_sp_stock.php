<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_sc_sp_stock extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('tgl_indo');
    $this->load->model('M_h1_dealer_prospek', 'm_prospek');
  }

  function getTipeKendaraan($filter = NULL)
  {
    // send_json($filter);
    $where  = 'WHERE 1=1 ';
    $limit  = '';
    $select = '*';

    if (isset($filter['year'])) {
      if ($filter['year'] != '') {
        $where .= " AND LEFT(tgl_awal,4)='{$filter['year']}'";
      }
    }
    if (isset($filter['id_kategori_int'])) {
      if ($filter['id_kategori_int'] != '') {
        $where .= " AND ktg.id_kategori_int='{$filter['id_kategori_int']}'";
      }
    }
    if (isset($filter['id_tipe_kendaraan_int'])) {
      if ($filter['id_tipe_kendaraan_int'] != '') {
        $where .= " AND tk.id_tipe_kendaraan_int='{$filter['id_tipe_kendaraan_int']}'";
      }
    }
    if (isset($filter['id_tipe_kendaraan'])) {
      if ($filter['id_tipe_kendaraan'] != '') {
        $where .= " AND tk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
      }
    }
    if (isset($filter['active'])) {
      if ($filter['active'] != '') {
        $where .= " AND tk.active='{$filter['active']}'";
      }
    }

    if (isset($filter['search'])) {
      if ($filter['search'] != '') {
        $where .= " AND (tk.id_tipe_kendaraan LIKE '%{$filter['search']}%'
                        OR tk.deskripsi_ahm LIKE '%{$filter['search']}%'
                        OR tk.tipe_ahm LIKE '%{$filter['search']}%'
                        OR tgl_awal LIKE '%{$filter['search']}%'
                        )
        ";
      }
    }

    if (isset($filter['accessories'])) {
      if ($filter['accessories'] != '') {
        $where .= " AND prt.kelompok_part IN({$filter['accessories']})";
      }
    }

    if (isset($filter['select'])) {
      $sum_acc = "SELECT COUNT(pvt.no_part) AS sum_acc FROM ms_pvtm pvt
      JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
      JOIN ms_part prt ON prt.id_part=pvt.no_part
      JOIN ms_kelompok_part kp ON kp.id_kelompok_part=prt.kelompok_part
      WHERE ptm.tipe_marketing=tk.id_tipe_kendaraan AND kp.id_kelompok_part IN ('ACCEC','Helm') AND ptm.tipe_marketing=tk.id_tipe_kendaraan
      ";

      $sum_apparel = "SELECT COUNT(pvt.no_part) AS sum_acc FROM ms_pvtm pvt
      JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
      JOIN ms_part prt ON prt.id_part=pvt.no_part
      JOIN ms_kelompok_part kp ON kp.id_kelompok_part=prt.kelompok_part
      WHERE ptm.tipe_marketing=tk.id_tipe_kendaraan AND kp.id_kelompok_part IN ('PACC','PA') AND ptm.tipe_marketing=tk.id_tipe_kendaraan
      ";

      $tot_price_acc = "SELECT COUNT(prt.harga_dealer_user) AS tot_price FROM ms_pvtm pvt
        JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
        JOIN ms_part prt ON prt.id_part=pvt.no_part
        JOIN ms_kelompok_part kp ON kp.id_kelompok_part=prt.kelompok_part
        WHERE ptm.tipe_marketing=tk.id_tipe_kendaraan AND kp.id_kelompok_part IN ('ACCEC','Helm') AND ptm.tipe_marketing=tk.id_tipe_kendaraan
        ";

      $tot_price_apparel = "SELECT COUNT(prt.harga_dealer_user) AS tot_price FROM ms_pvtm pvt
      JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
      JOIN ms_part prt ON prt.id_part=pvt.no_part
      JOIN ms_kelompok_part kp ON kp.id_kelompok_part=prt.kelompok_part
      WHERE ptm.tipe_marketing=tk.id_tipe_kendaraan AND kp.id_kelompok_part IN ('PACC','PA') AND ptm.tipe_marketing=tk.id_tipe_kendaraan
      ";

      if ($filter['select'] == 'select_tipe_kendaraan') {
        $select = "tk.id_tipe_kendaraan_int,tk.id_tipe_kendaraan,deskripsi_ahm,tipe_ahm,
          CONCAT(tk.id_tipe_kendaraan,' - ',tk.deskripsi_ahm) AS code,
          '' image,
          ($sum_acc) total_accessories,
          ($sum_apparel) total_apparel
          ";
      } elseif ($filter['select'] == 'select_tipe_kendaraan_tot_price_acc') {
        $select = "tk.id_tipe_kendaraan_int,tk.id_tipe_kendaraan,deskripsi_ahm,tipe_ahm,
          CONCAT(tk.id_tipe_kendaraan,' - ',tk.deskripsi_ahm) AS code,
          '' image,
          ($tot_price_acc) tot_price
          ";
      } elseif ($filter['select'] == 'select_tipe_kendaraan_tot_acc') {
        $select = "tk.id_tipe_kendaraan_int AS id,
                   tipe_ahm AS name, 
                   CONCAT(tk.id_tipe_kendaraan,' - ',tk.deskripsi_ahm) AS code,
                   '' image,
                   ($sum_acc) AS total_accessories";
      } elseif ($filter['select'] == 'select_detail_tipe_kendaraan') {
        $select = "id_tipe_kendaraan_int,tk.id_tipe_kendaraan,deskripsi_ahm,tipe_ahm,
        CONCAT(tk.id_tipe_kendaraan,' - ',tk.deskripsi_ahm) AS code,
        '' image";
      }
    }

    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
      // $limit = "LIMIT 2";
      $limit = "LIMIT 0";
    }

    $join = '';
    if (isset($filter['join'])) {
      if ($filter['join'] == 'ptm') {
        $join .= "JOIN ms_ptm ptm ON ptm.tipe_marketing=tk.id_tipe_kendaraan
                  JOIN ms_pvtm pvt ON pvt.tipe_marketing=ptm.tipe_produksi
                  JOIN ms_part prt ON prt.id_part=pvt.no_part
                  ";
      }
    }

    $group = '';
    if (isset($filter['group_by'])) {
      if ($filter['group_by'] == 'id_tipe_kendaraan') {
        $group = "GROUP BY tk.id_tipe_kendaraan";
      }
    }
    // $unit = '';
    return $this->db->query("SELECT $select
    FROM ms_tipe_kendaraan tk
    LEFT JOIN ms_kategori ktg ON ktg.id_kategori=tk.id_kategori
    $join
    $where 
    $group
    ORDER BY tk.id_tipe_kendaraan ASC
    $limit
    ");
  }
  function getDetailAksesoris($filter = NULL)
  {
    // send_json($filter);
    $where  = 'WHERE 1 = 1 ';
    $limit  = '';
    $select = '*';
    $group  = '';

    if (isset($filter['id_unit'])) {
      if ($filter['id_unit'] != '') {
        $where .= " AND tk.id_tipe_kendaraan_int='{$filter['id_unit']}'";
      }
    }

    if (isset($filter['search'])) {
      if ($filter['search'] != '') {
        $where .= " AND (prt.id_part LIKE '%{$filter['search']}%'
                        OR prt.nama_part LIKE '%{$filter['search']}%'
                        OR prt.harga_dealer_user LIKE '%{$filter['search']}%'
                        )
        ";
      }
    }
    if (isset($filter['select'])) {
      if ($filter['select'] == 'select_detail') {
        $select = "prt.id_part_int AS id,
        '' image,
        prt.nama_part name,
        prt.id_part code,
        prt.harga_dealer_user price
        ";
      }
    }

    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }

    if (isset($filter['group_by_id_part'])) {
      $group = "GROUP BY prt.id_part";
    }

    return $this->db->query("SELECT $select FROM ms_pvtm pvt
    JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=ptm.tipe_marketing
    JOIN ms_part prt ON prt.id_part=pvt.no_part
    $where AND prt.kelompok_part IN('ACCEC','Helm')
    $group
    ORDER BY prt.id_part ASC
    $limit
    ");
  }

  function getApparel($filter = NULL)
  {
    // send_json($filter);
    $where  = 'WHERE 1 = 1 ';
    $limit  = '';
    $select = '*';
    $group  = '';

    if (isset($filter['id_unit'])) {
      if ($filter['id_unit'] != '') {
        $where .= " AND tk.id_tipe_kendaraan='{$filter['id_unit']}'";
      }
    }

    if (isset($filter['search'])) {
      if ($filter['search'] != '') {
        $where .= " AND (prt.id_part LIKE '%{$filter['search']}%'
                        OR prt.nama_part LIKE '%{$filter['search']}%'
                        OR prt.harga_dealer_user LIKE '%{$filter['search']}%'
                        )
        ";
      }
    }
    if (isset($filter['select'])) {
      if ($filter['select'] == 'select_apparel') {
        $select = "prt.id_part_int AS id,
        '' image,
        prt.nama_part name,
        prt.id_part code,
        prt.harga_dealer_user price,
        '' ukuran,
        '' material,
        '' spesifikasi,
        '' warna
        ";
      }
    }

    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $page = $page < 0 ? 0 : $page;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }

    if (isset($filter['group_by_id_part'])) {
      $group = "GROUP BY prt.id_part";
    }
    $limit = "LIMIT 10";
    return $this->db->query("SELECT $select FROM ms_part prt
    $where AND prt.kelompok_part IN('PACC','PA')
    $group
    ORDER BY prt.id_part ASC
    $limit
    ");
  }
  function getAccessories($filter = NULL)
  {
    // send_json($filter);
    $where  = 'WHERE 1 = 1 ';
    $limit  = '';
    $select = '*';
    $group  = '';

    if (isset($filter['id_unit'])) {
      if ($filter['id_unit'] != '') {
        $where .= " AND tk.id_tipe_kendaraan='{$filter['id_unit']}'";
      }
    }

    if (isset($filter['search'])) {
      if ($filter['search'] != '') {
        $where .= " AND (prt.id_part LIKE '%{$filter['search']}%'
                        OR prt.nama_part LIKE '%{$filter['search']}%'
                        OR prt.harga_dealer_user LIKE '%{$filter['search']}%'
                        )
        ";
      }
    }
    if (isset($filter['select'])) {
      if ($filter['select'] == 'select_apparel') {
        $select = "prt.id_part_int AS id,
        '' image,
        prt.nama_part name,
        prt.id_part code,
        prt.harga_dealer_user price,
        '' ukuran,
        '' material,
        '' spesifikasi,
        '' warna
        ";
      }
    }

    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $page = $page < 0 ? 0 : $page;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
    }

    if (isset($filter['group_by_id_part'])) {
      $group = "GROUP BY prt.id_part";
    }
    $limit = "LIMIT 10";
    return $this->db->query("SELECT $select FROM ms_part prt
    $where AND prt.kelompok_part IN('ACCEC','Helm')
    $group
    ORDER BY prt.id_part ASC
    $limit
    ");
  }

  function getDetailUnit($filter = NULL)
  {
    $where  = 'WHERE 1 = 1 ';
    $limit  = '';
    $select = 'itm.id_item_int, itm.id_item_int, itm.id_item, wr.warna, wr.id_warna, tk.id_tipe_kendaraan,CONCAT(wr.warna,' - ',itm.id_warna) con_warna';

    if (isset($filter['id_tipe_kendaraan'])) {
      if ($filter['id_tipe_kendaraan'] != '') {
        $where .= " AND tk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
      }
    }
    if (isset($filter['id_item_int'])) {
      if ($filter['id_item_int'] != '') {
        $where .= " AND itm.id_item_int='{$filter['id_item_int']}'";
      }
    }

    if (isset($filter['search'])) {
      if ($filter['search'] != '') {
        $where .= " AND (tk.id_tipe_kendaraan LIKE '%{$filter['search']}%'
                        OR tk.tipe_ahm LIKE '%{$filter['search']}%'
                        )
        ";
      }
    }
    if (isset($filter['select'])) {
      if ($filter['select'] == 'select_detail') {
        $select = "itm.id_item_int,itm.id_item,
        wr.warna,
        wr.id_warna,
        tk.id_tipe_kendaraan,
        CONCAT(wr.warna,' - ',itm.id_warna) con_warna
        ";
      }
    }
    return $this->db->query("SELECT $select ,wr.id_warna
    FROM ms_item itm
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=itm.id_tipe_kendaraan
    JOIN ms_warna wr ON wr.id_warna=itm.id_warna
    $where
    ORDER BY itm.id_item ASC
    $limit
    ");
  }

  function prospek_spk($filter)
  {
    $ym = get_ym();
    $where = "WHERE 
                prp.id_dealer='{$filter['id_dealer']}' 
                -- AND LEFT(tgl_prospek,7)='$ym' 
                AND IFNULL(so.tgl_cetak_invoice,'')=''
                AND prp.status_prospek!='Not Deal' AND spk.status_spk NOT IN('rejected','canceled','closed','close')
              ";

    if (isset($filter['id_tipe_kendaraan'])) {
      $where.=" AND spk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
    }

    if (isset($filter['id_warna'])) {
      $where.=" AND spk.id_warna='{$filter['id_warna']}'";
    }

    return $this->db->query("SELECT COUNT(id_prospek) c 
                            FROM tr_prospek prp
                            LEFT JOIN tr_spk spk ON spk.id_customer=prp.id_customer
                            LEFT JOIN tr_sales_order so ON so.no_spk=spk.no_spk
                            $where")->row()->c;
  }

  function getDetailUnitStok($filter)
  {
    $result = [];
    // send_json($filter);
    $get_data = $this->getDetailUnit($filter);
    foreach ($get_data->result() as $rs) {
      // send_json($rs);
      $filter_spk = [
        'id_tipe_kendaraan' => $rs->id_tipe_kendaraan,
        'id_warna' => $rs->id_warna,
        'id_dealer' => $filter['id_dealer'],
        'tahun_bulan' => get_ym()

      ];
      $prospek_spk    =  $this->prospek_spk($filter_spk);
      $available   = $this->m_stok->GetReadyStock($filter_spk);
      $result[]       =  [
        'id'          => (int)$rs->id_item_int,
        'color'       => $rs->con_warna,
        'code'        => $rs->id_item,
        'prospek_spk' => $prospek_spk,
        'available'   => (int)$available,
      ];
    }
    return $result;
  }

  function getFilter()
  {
    $year = [];
    $id = 1;
    for ($i = 2014; $i <= date('Y'); $i++) {
      $year[] = [
        'id' => $id,
        'year' => $i
      ];
      $id++;
    }
    $get_ctg = $this->db->query("SELECT id_kategori_int AS id,kategori AS name FROM ms_kategori")->result();
    $ctg = [];
    foreach ($get_ctg as $ct) {
      $ctg[] = [
        'id' => (int)$ct->id,
        'name' => $ct->name,
      ];
    }
    $result = ['year' => $year, 'type' => $ctg];
    return $result;
  }

  function getTipeKendaraanWithStokUnit($filter = NULL)
  {
    // send_json($filter);
    $where  = 'WHERE 1=1 ';
    $limit  = '';
    $select = '*';
    $having = '';

    $ready_stok = "
      (SELECT COUNT(tr_scan_barcode.no_mesin) AS jum FROM tr_penerimaan_unit_dealer_detail
      LEFT JOIN tr_penerimaan_unit_dealer ON tr_penerimaan_unit_dealer_detail.id_penerimaan_unit_dealer = tr_penerimaan_unit_dealer.id_penerimaan_unit_dealer               
      LEFT JOIN tr_scan_barcode ON tr_penerimaan_unit_dealer_detail.no_mesin = tr_scan_barcode.no_mesin
      LEFT JOIN ms_tipe_kendaraan ON tr_scan_barcode.tipe_motor = ms_tipe_kendaraan.id_tipe_kendaraan
      LEFT JOIN ms_warna ON tr_scan_barcode.warna = ms_warna.id_warna
      LEFT JOIN ms_dealer ON tr_penerimaan_unit_dealer.id_dealer = ms_dealer.id_dealer
      WHERE tr_scan_barcode.status BETWEEN 1 AND 4
      AND tr_penerimaan_unit_dealer.status = 'close'
      AND tr_penerimaan_unit_dealer_detail.retur=0 
      AND tr_scan_barcode.tipe_motor=tk.id_tipe_kendaraan 
      AND ms_dealer.id_dealer='{$filter['id_dealer']}')";

    if (isset($filter['year'])) {
      if ($filter['year'] != '') {
        $where .= " AND LEFT(tgl_awal,4)='{$filter['year']}'";
      }
    }
    if (isset($filter['id_kategori_int'])) {
      if ($filter['id_kategori_int'] != '') {
        $where .= " AND ktg.id_kategori_int='{$filter['id_kategori_int']}'";
      }
    }
    if (isset($filter['id_tipe_kendaraan_int'])) {
      if ($filter['id_tipe_kendaraan_int'] != '') {
        $where .= " AND tk.id_tipe_kendaraan_int='{$filter['id_tipe_kendaraan_int']}'";
      }
    }
    if (isset($filter['id_tipe_kendaraan'])) {
      if ($filter['id_tipe_kendaraan'] != '') {
        $where .= " AND tk.id_tipe_kendaraan='{$filter['id_tipe_kendaraan']}'";
      }
    }
    if (isset($filter['active'])) {
      if ($filter['active'] != '') {
        $where .= " AND tk.active='{$filter['active']}'";
      }
    }
    if (isset($filter['ready_stok'])) {
      if ($filter['ready_stok'] == true) {
        $having = "HAVING ready_stok > 0";
      }
    }

    if (isset($filter['search'])) {
      if ($filter['search'] != '') {
        $where .= " AND (tk.id_tipe_kendaraan LIKE '%{$filter['search']}%'
                        OR tk.deskripsi_ahm LIKE '%{$filter['search']}%'
                        OR tk.tipe_ahm LIKE '%{$filter['search']}%'
                        OR tgl_awal LIKE '%{$filter['search']}%'
                        )
        ";
      }
    }
    if (isset($filter['select'])) {
      $sum_acc = "SELECT COUNT(pvt.no_part) AS sum_acc FROM ms_pvtm pvt
      JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
      JOIN ms_part prt ON prt.id_part=pvt.no_part
      JOIN ms_kelompok_part kp ON kp.id_kelompok_part=prt.kelompok_part
      WHERE ptm.tipe_marketing=tk.id_tipe_kendaraan AND kp.id_kelompok_part IN ('ACCEC','Helm')
      ";

      $sum_apparel = "SELECT COUNT(pvt.no_part) AS sum_acc FROM ms_pvtm pvt
      JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
      JOIN ms_part prt ON prt.id_part=pvt.no_part
      JOIN ms_kelompok_part kp ON kp.id_kelompok_part=prt.kelompok_part
      WHERE ptm.tipe_marketing=tk.id_tipe_kendaraan AND kp.id_kelompok_part IN ('PACC','PA')
      ";

      $tot_price_acc = "SELECT COUNT(prt.harga_dealer_user) AS tot_price FROM ms_pvtm pvt
        JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
        JOIN ms_part prt ON prt.id_part=pvt.no_part
        JOIN ms_kelompok_part kp ON kp.id_kelompok_part=prt.kelompok_part
        WHERE ptm.tipe_marketing=tk.id_tipe_kendaraan AND kp.id_kelompok_part IN ('ACCEC','Helm')
        ";

      $tot_price_apparel = "SELECT COUNT(prt.harga_dealer_user) AS tot_price FROM ms_pvtm pvt
      JOIN ms_ptm ptm ON ptm.tipe_produksi=pvt.tipe_marketing
      JOIN ms_part prt ON prt.id_part=pvt.no_part
      JOIN ms_kelompok_part kp ON kp.id_kelompok_part=prt.kelompok_part
      WHERE ptm.tipe_marketing=tk.id_tipe_kendaraan AND kp.id_kelompok_part IN ('PACC','PA')
      ";

      if ($filter['select'] == 'select_tipe_kendaraan') {
        $select = "tk.id_tipe_kendaraan_int,tk.id_tipe_kendaraan,deskripsi_ahm,tipe_ahm,
          CONCAT(tk.id_tipe_kendaraan,' - ',tk.deskripsi_ahm) AS code,
          '' image,
          ($sum_acc) total_accessories,
          ($sum_apparel) total_apparel,
          ($ready_stok) ready_stok
          ";
      }
    }

    if (isset($filter['page'])) {
      $page = $filter['page'] == '' ? 0 : $filter['page'] - 1;
      $length = 10;
      // $start = $page == 1 ? 0 : $length * ($page - 1);
      $start = $length * $page;
      $limit = "LIMIT $start, $length";
      // $limit = "LIMIT 2";
    }

    // $unit = '';
    return $this->db->query("SELECT $select
    FROM ms_tipe_kendaraan tk
    LEFT JOIN ms_kategori ktg ON ktg.id_kategori=tk.id_kategori
    $where 
    $having
    ORDER BY tk.id_tipe_kendaraan ASC
    $limit
    ");
  }
}
