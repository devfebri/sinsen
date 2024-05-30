<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h1_dealer_pembayaran extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  function getSPK($filter = NULL)
  {
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE po.id_dealer = '$id_dealer'";

    if (isset($filter['status_in'])) {
      if ($filter['status_in'] != '') {
        $where .= " AND po.status IN({$filter['status_in']})";
      }
    }
    if (isset($filter['no_mesin_null'])) {
      if ($filter['no_mesin_null'] != '') {
        $where .= " AND spk.no_mesin_spk IS NULL";
      }
    }
    if (isset($filter['status_not_in'])) {
      if ($filter['status_not_in'] != '') {
        $where .= " AND po.status NOT IN({$filter['status_not_in']})";
      }
    }

    // $amount_tjs = "SELECT SUM(amount) FROM tr_invoice_tjs_receipt tjs WHERE tjs.id_spk=po.id_spk";
    // $amount_tjs = "SELECT IFNULL(SUM(tanda_jadi),0) FROM tr_spk spk WHERE spk.no_spk=po.id_spk";
    return $this->db->query("SELECT po.*,tk.tipe_ahm,wr.warna,tanda_jadi AS amount_tjs 
		FROM tr_po_dealer_indent po
    INNER JOIN tr_spk spk ON spk.no_spk=po.id_spk
		INNER JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan= po.id_tipe_kendaraan
		INNER JOIN ms_warna wr ON wr.id_warna = po.id_warna 
		$where
		ORDER BY po.created_at DESC");
  }

  public function get_id_invoice_tjs()
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
    $get_data  = $this->db->query("SELECT id_invoice FROM tr_invoice_tjs
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer and LEFT(id_invoice,2)='TJ'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $id_invoice = substr($row->id_invoice, -5);
      $new_kode   = 'TJ/' . $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $id_invoice + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_invoice_tjs', ['id_invoice' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -5);
          $new_kode = 'TJ/' . $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = 'TJ/' . $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . '00001';
    }
    return strtoupper($new_kode);
  }

  function getInvoiceTJS($filter = NULL)
  {
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE tjs.id_dealer = '$id_dealer' ";
    if (isset($filter['status_in'])) {
      if ($filter['status_in'] != '') {
        $where .= " AND tjs.status IN ({$filter['status_in']})";
      }
    }

      if (isset($filter['status_in'])) {
        $where .= " AND tjs.status IN ({$filter['status_in']})";
      }

    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND tjs.id_spk='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['id_invoice_tjs'])) {
      if ($filter['id_invoice_tjs'] != '') {
        $where .= " AND tjs.id_invoice='{$filter['id_invoice_tjs']}'";
      }
    }
    if (isset($filter['id_kwitansi'])) {
      if ($filter['id_kwitansi'] != '') {
        $where .= " AND tjs_r.id_kwitansi='{$filter['id_kwitansi']}'";
      }
    }
    if (isset($filter['id_tjs_null'])) {
      $where .= " AND tjs.id_invoice IS NULL";
    }
    if (isset($filter['print_ke_diatas_nol'])) {
      // $where .= " AND IFNULL(tjs_r.print_ke,0)>0";
    }
    if (isset($filter['print_ke_nol'])) {
      // $where .= " AND IFNULL(tjs_r.print_ke,0)=0";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (id_spk LIKE '%$search%'
                            OR tjs.id_invoice LIKE '%$search%'
                            OR nama_konsumen LIKE '%$search%'
                            OR tjs.no_ktp LIKE '%$search%'
                            OR tjs.no_hp LIKE '%$search%'
                            OR jenis_beli LIKE '%$search%'
                            OR tjs.alamat LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'tjs') {
          $order_column = ['spk.no_spk', 'spk.tgl_spk', 'spk.nama_konsumen', 'spk.no_ktp', 'spk.no_hp', 'spk.id_tipe_kendaraan', 'spk.id_warna', 'spk.jenis_beli', 'spk.tanda_jadi', 'spk.total_bayar', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY tjs.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    $join = 'LEFT JOIN tr_h1_dealer_invoice_receipt tjs_r ON tjs_r.id_invoice=tjs.id_invoice
             LEFT JOIN ms_coa_dealer cd ON cd.kode_coa=tjs_r.kode_coa';
    $select = "id_spk,nama_konsumen,tgl_spk,no_ktp,tjs.no_hp,jenis_beli,total_bayar,diskon,kd.id_flp_md,kd.nama_lengkap,kd.id_karyawan_dealer,tjs.id_invoice,tjs.created_at,tjs_r.print_at,tjs_r.print_by,tjs_r.print_ke,tjs_r.cara_bayar,tjs.status,tjs_r.tgl_pembayaran,tjs.id_invoice id_invoice_tjs,LEFT(tjs.created_at,10) AS tgl_tjs,tjs_r.note,tjs_r.id_kwitansi,tjs_r.kode_coa,tjs_r.nominal_lebih,tjs_r.keterangan_lebih,cd.coa,tjs.amount,tjs.jenis_spk,tjs.amount AS tanda_jadi,id_spk AS no_spk,tjs.alamat,tjs.id_customer";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'view_invoice_tjs') {
        $select = "tjs.id_invoice,id_spk,tgl_spk,jenis_beli,id_flp_md,nama_konsumen,no_ktp,tjs.created_at,tjs.amount,tjs.print_ke,tjs.status,tjs.id_spk no_spk,amount tanda_jadi ";
        $join = "";
      }
    }
    return $this->db->query("SELECT $select
    FROM tr_invoice_tjs tjs
    LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=tjs.id_karyawan_dealer
    $join
		$where
    $order
    $limit
    ");
  }

  public function get_id_kwitansi($jenis_invoice)
  {
    $th       = date('Y');
    $bln      = date('m');
    $th_bln   = date('Y-m');
    $th_kecil = date('y');
    $id_dealer  = dealer()->id_dealer;
    $where = '';
    if ($jenis_invoice != 'tjs') {
      $where = " AND (jenis_invoice='dp' OR jenis_invoice='pelunasan')";
      $jenis_invoice = 'KWT';
    } else {
      $where = " AND jenis_invoice='$jenis_invoice'";
    }
    $get_data  = $this->db->query("SELECT id_kwitansi FROM tr_h1_dealer_invoice_receipt
			WHERE LEFT(created_at,4)='$th' AND id_dealer=$id_dealer $where
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $id_kwitansi = substr($row->id_kwitansi, -5);
      $new_kode   = $jenis_invoice . '/' . dealer()->kode_dealer_md . '/' . $th_kecil . '/' . sprintf("%'.05d", $id_kwitansi + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_h1_dealer_invoice_receipt', ['id_kwitansi' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -5);
          $new_kode   = $jenis_invoice . '/' . dealer()->kode_dealer_md . '/' . $th_kecil . '/' . sprintf("%'.05d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = $jenis_invoice . '/' . dealer()->kode_dealer_md . '/' . $th_kecil . '/00001';
    }
    return strtoupper($new_kode);
  }

  function getDetailBayarInvoicePenjualan($filter = NULL)
  {
    $where = "WHERE 1=1 ";
    if (isset($filter['id_invoice'])) {
      $where .= " AND ir.id_invoice ='{$filter['id_invoice']}'";
    }
    if (isset($filter['id_kwitansi'])) {
      $where .= " AND irp.id_kwitansi ='{$filter['id_kwitansi']}'";
    }
    $metode = "CASE 
                WHEN metode_penerimaan='cash' THEN 'Cash'
                WHEN metode_penerimaan='bg_cek' THEN 'BG / Cek'
                WHEN metode_penerimaan='kredit_transfer' THEN 'Kredit/Transfer'
              END

    ";
    return $this->db->query("SELECT irp.id_kwitansi,metode_penerimaan,nominal,no_bg_cek,irp.id_bank,tgl_terima,$metode AS metode_penerimaan_full,bk.bank
    FROM tr_h1_dealer_invoice_receipt_pembayaran irp
    LEFT JOIN ms_bank bk ON bk.id_bank=irp.id_bank
    LEFT JOIN tr_h1_dealer_invoice_receipt ir ON ir.id_kwitansi=irp.id_kwitansi
		$where
    ");
  }

  public function get_id_invoice_dp()
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
    $get_data  = $this->db->query("SELECT id_invoice_dp FROM tr_invoice_dp
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer and LEFT(id_invoice_dp,2)='DP'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $id_invoice_dp = substr($row->id_invoice_dp, -5);
      $new_kode   = 'DP/' . $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $id_invoice_dp + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_invoice_dp', ['id_invoice_dp' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -5);
          $new_kode = 'DP/' . $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = 'DP/' . $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . '00001';
    }
    return strtoupper($new_kode);
  }

  function getInvoiceDP($filter = NULL)
  {
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE dp.id_dealer = '$id_dealer' ";
    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND dp.id_spk='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['id_invoice_dp'])) {
      if ($filter['id_invoice_dp'] != '') {
        $where .= " AND dp.id_invoice_dp='{$filter['id_invoice_dp']}'";
      }
    }
    if (isset($filter['status_in'])) {
      if ($filter['status_in'] != '') {
        $where .= " AND dp.status IN ({$filter['status_in']})";
      }
    }

    if (isset($filter['id_dp_null'])) {
      $where .= " AND dp.id_invoice IS NULL";
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (dp.id_spk LIKE '%$search%'
                            OR dp.nama_konsumen LIKE '%$search%'
                            OR dp.id_sales_order LIKE '%$search%'
                            OR dp.no_ktp LIKE '%$search%'
                            OR dp.no_hp LIKE '%$search%'
                            OR dp.jenis_beli LIKE '%$search%'
                            OR dp.alamat LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'tjs') {
          $order_column = ['spk.no_spk', 'spk.tgl_spk', 'spk.nama_konsumen', 'spk.no_ktp', 'spk.no_hp', 'spk.id_tipe_kendaraan', 'spk.id_warna', 'spk.jenis_beli', 'spk.tanda_jadi', 'spk.total_bayar', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY dp.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

    $diskon = sql_diskon_spk();
    $total_bayar = sql_total_bayar_spk();
    $summary_terima = sql_summary_terima_pembayaran_v2('dp',$id_dealer);
    $sisa_pelunasan = "(dp.total_bayar-$summary_terima)";

    $select = "dp.id_spk,dp.id_sales_order,dp.id_spk no_spk,dp.nama_konsumen,dp.tgl_spk,dp.no_ktp,dp.no_hp,dp.jenis_beli,kd.id_flp_md,kd.nama_lengkap,dp.id_karyawan_dealer,dp.id_invoice_dp,dp.created_at,LEFT(dp.created_at,10) AS tgl_invoice_dp,dp.amount_dp, dp.diskon,dp.total_bayar,amount_dp,(amount_dp+IFNULL(dp.diskon,0)) AS dp_gross,
    CASE WHEN so_gc.id_sales_order_gc IS NULL THEN LEFT(so.created_at,10) ELSE LEFT(so_gc.created_at,10) END AS tgl_so
    ,dp.amount_dp tanda_jadi,(amount_dp-IFNULL(dp.amount_dp,0)) AS dp_amount_tjs,dp.status,$sisa_pelunasan AS sisa_pelunasan,tjs.amount as amount_tjs,dp.jenis_spk";
    if (isset($filter['summary_terima'])) {
      $select .= ",$summary_terima AS summary_terima";
    }
    if (isset($filter['sisa_lebih_dari_nol'])) {
      $where .= "AND $sisa_pelunasan >0";
    }
    if (isset($filter['sisa_nol'])) {
      $where .= "AND $summary_terima =0";
    }
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count_no_spk') {
        $select = "COUNT(dp.id_spk) AS count";
      } elseif ($filter['select'] === 'view_invoice_dp') {
        $select = "dp.id_sales_order,dp.id_spk,dp.id_spk no_spk,dp.nama_konsumen,dp.tgl_spk,dp.no_ktp,dp.no_hp,dp.jenis_beli,kd.id_flp_md,kd.nama_lengkap,dp.id_karyawan_dealer,dp.id_invoice_dp,dp.created_at,LEFT(dp.created_at,10) AS tgl_invoice_dp,dp.amount_dp, dp.diskon,dp.total_bayar,amount_dp,(amount_dp+IFNULL(dp.diskon,0)) AS dp_gross,
          CASE WHEN so_gc.id_sales_order_gc IS NULL THEN LEFT(so.created_at,10) ELSE LEFT(so_gc.created_at,10) END AS tgl_so,dp.amount_dp tanda_jadi,(amount_dp-IFNULL(dp.amount_dp,0)) AS dp_amount_tjs,dp.status,tjs.amount as amount_tjs,dp.jenis_spk";
      }
    }
    return $this->db->query("SELECT $select
    FROM tr_invoice_dp dp
    LEFT JOIN tr_sales_order so ON so.id_sales_order=dp.id_sales_order
    LEFT JOIN tr_sales_order_gc so_gc ON so_gc.id_sales_order_gc=dp.id_sales_order
    LEFT JOIN tr_invoice_tjs tjs ON tjs.id_spk=dp.id_spk AND tjs.status='close'
    JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=dp.id_karyawan_dealer
		$where
    $order
    $limit
    ");
  }

  public function get_id_inv_pelunasan()
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
    $get_data  = $this->db->query("SELECT id_inv_pelunasan FROM tr_invoice_pelunasan
			WHERE LEFT(created_at,7)='$th_bln' AND id_dealer=$id_dealer and LEFT(id_inv_pelunasan,2)='FP'
			ORDER BY created_at DESC LIMIT 0,1");
    if ($get_data->num_rows() > 0) {
      $row        = $get_data->row();
      $id_inv_pelunasan = substr($row->id_inv_pelunasan, -5);
      $new_kode   = 'FP/' . $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $id_inv_pelunasan + 1);
      $i = 0;
      while ($i < 1) {
        $cek = $this->db->get_where('tr_invoice_pelunasan', ['id_inv_pelunasan' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -5);
          $new_kode = 'FP/' . $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . sprintf("%'.05d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode = 'FP/' . $id_sumber . '/' . $th_kecil . '/' . $bln . '/' . '00001';
    }
    return strtoupper($new_kode);
  }

  function getInvoicePelunasan($filter = NULL)
  {
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE lunas.id_dealer = '$id_dealer' ";
    if (isset($filter['id_inv_pelunasan'])) {
      if ($filter['id_inv_pelunasan'] != '') {
        $where .= " AND lunas.id_inv_pelunasan='{$filter['id_inv_pelunasan']}'";
      }
    }
    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND lunas.id_spk='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['status_in'])) {
      if ($filter['status_in'] != '') {
        $where .= " AND lunas.status IN ({$filter['status_in']})";
      }
    }
    if (isset($filter['status_null'])) {
      $where .= " AND lunas.status IS NULL";
    }

    if (isset($filter['id_dp_null'])) {
      $where .= " AND lunas.id_invoice IS NULL";
    }
    // if (isset($filter['print_ke_diatas_nol'])) {
    //   $where .= " AND IFNULL(lunas_r.print_ke,0)>0";
    // }
    // if (isset($filter['print_ke_nol'])) {
    //   $where .= " AND IFNULL(lunas_r.print_ke,0)=0";
    // }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (lunas.id_spk LIKE '%$search%'
                            OR so.id_sales_order LIKE '%$search%'
                            OR so_gc.id_sales_order_gc LIKE '%$search%'
                            OR lunas.nama_konsumen LIKE '%$search%'
                            OR lunas.no_ktp LIKE '%$search%'
                            OR lunas.no_hp LIKE '%$search%'
                            OR lunas.jenis_beli LIKE '%$search%'
                            OR lunas.alamat LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'tjs') {
          $order_column = ['spk.no_spk', 'spk.tgl_spk', 'spk.nama_konsumen', 'spk.no_ktp', 'spk.no_hp', 'spk.id_tipe_kendaraan', 'spk.id_warna', 'spk.jenis_beli', 'spk.tanda_jadi', 'spk.total_bayar', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY lunas.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    $summary_terima = sql_summary_terima_pembayaran_v2('pelunasan',$id_dealer);
    $sisa_pelunasan = "(amount_pelunasan-IFNULL($summary_terima,0))";
    $select = "lunas.id_sales_order,lunas.id_spk AS no_spk,lunas.nama_konsumen,lunas.tgl_spk,lunas.no_ktp,lunas.no_hp,lunas.jenis_beli,kd.id_flp_md,kd.nama_lengkap,kd.id_karyawan_dealer,lunas.id_inv_pelunasan,lunas.created_at,lunas.status,lunas.amount_pelunasan,lunas.diskon, lunas.amount_pelunasan as total_bayar,amount_dp,CASE WHEN so.created_at IS NULL THEN LEFT(so_gc.created_at,10) ELSE LEFT(so.created_at,10) END AS tgl_so,lunas.amount_tjs,$sisa_pelunasan AS sisa_pelunasan,LEFT(lunas.created_at,10) AS tgl_inv_pelunasan,lunas.jenis_spk";
    if (isset($filter['summary_terima'])) {
      $select .= ",$summary_terima AS summary_terima";
    }
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count_no_spk') {
        $select = "COUNT(lunas.id_spk) AS count";
      } elseif ($filter['select'] == 'view_invoice_lunas') {
        $select = "lunas.id_sales_order,lunas.id_spk AS no_spk,lunas.nama_konsumen,lunas.tgl_spk,lunas.no_ktp,lunas.no_hp,lunas.jenis_beli,kd.id_flp_md,kd.nama_lengkap,kd.id_karyawan_dealer,lunas.id_inv_pelunasan,lunas.created_at,lunas.status,lunas.amount_pelunasan,lunas.diskon, lunas.amount_pelunasan as total_bayar,amount_dp,CASE WHEN so.created_at IS NULL THEN LEFT(so_gc.created_at,10) ELSE LEFT(so.created_at,10) END AS tgl_so,lunas.amount_tjs,LEFT(lunas.created_at,10) AS tgl_inv_pelunasan,lunas.jenis_spk";
      }
    }

    return $this->db->query("SELECT $select
    FROM tr_invoice_pelunasan lunas
    LEFT JOIN tr_sales_order so ON so.id_sales_order=lunas.id_sales_order
    LEFT JOIN tr_sales_order_gc so_gc ON so_gc.id_sales_order_gc=lunas.id_sales_order
    LEFT JOIN ms_karyawan_dealer kd ON kd.id_karyawan_dealer=lunas.id_karyawan_dealer
		$where
    $order
    $limit
    ");
  }
  function getDealerInvoiceReceipt($filter = NULL)
  {
    if (dealer()) {
      $id_dealer = dealer()->id_dealer;
    } else {
      $id_dealer = $filter['id_dealer'];
    }
    $where = "WHERE dir.id_dealer = '$id_dealer' ";
    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND dir.no_spk='{$filter['no_spk']}'";
      }
    }
    if (isset($filter['id_invoice'])) {
      if ($filter['id_invoice'] != '') {
        $where .= " AND dir.id_invoice='{$filter['id_invoice']}'";
      }
    }
    if (isset($filter['id_kwitansi'])) {
      if ($filter['id_kwitansi'] != '') {
        $where .= " AND dir.id_kwitansi='{$filter['id_kwitansi']}'";
      }
    }
    if (isset($filter['tgl_pembayaran'])) {
      if ($filter['tgl_pembayaran'] != '') {
        $where .= " AND dir.tgl_pembayaran='{$filter['tgl_pembayaran']}'";
      }
    }
    if (isset($filter['created_at_lebih_kecil'])) {
      if ($filter['created_at_lebih_kecil'] != '') {
        $where .= " AND dir.created_at<'{$filter['created_at_lebih_kecil']}'";
      }
    }

    if (isset($filter['jenis_invoice_in'])) {
      if ($filter['jenis_invoice_in'] != '') {
        $where .= " AND dir.jenis_invoice IN({$filter['jenis_invoice_in']})";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (spk.no_spk LIKE '%$search%'
                            OR spk.nama_konsumen LIKE '%$search%'
                            OR spk.no_ktp LIKE '%$search%'
                            OR spk.no_hp LIKE '%$search%'
                            OR spk.jenis_beli LIKE '%$search%'
                            OR spk.id_tipe_kendaraan LIKE '%$search%'
                            OR spk.id_warna LIKE '%$search%'
                            OR spk.alamat LIKE '%$search%'
                            OR spk.status_spk LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'tjs') {
          $order_column = ['spk.no_spk', 'spk.tgl_spk', 'spk.nama_konsumen', 'spk.no_ktp', 'spk.no_hp', 'spk.id_tipe_kendaraan', 'spk.id_warna', 'spk.jenis_beli', 'spk.tanda_jadi', 'spk.total_bayar', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY dir.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
    if (isset($filter['get_first_kwitansi'])) {
      $order = "ORDER BY dir.created_at ASC";
    }
    $select = "
      dir.id_kwitansi,dir.tgl_pembayaran,dir.amount,dir.cara_bayar,IFNULL(dir.nominal_lebih,0) AS nominal_lebih,dir.jenis_invoice,dir.print_ke,dir.id_kwitansi_int,
    CASE 
      WHEN tjs.nama_konsumen IS NOT NULL THEN tjs.nama_konsumen
      WHEN dp.nama_konsumen IS NOT NULL THEN dp.nama_konsumen
      WHEN lunas.nama_konsumen IS NOT NULL THEN lunas.nama_konsumen
    END AS nama_konsumen,
    CASE 
      WHEN tjs.no_ktp IS NOT NULL THEN tjs.no_ktp
      WHEN dp.no_ktp IS NOT NULL THEN dp.no_ktp
      WHEN lunas.no_ktp IS NOT NULL THEN lunas.no_ktp
    END AS no_ktp,
    CASE 
      WHEN tjs.alamat IS NOT NULL THEN tjs.alamat
      WHEN dp.alamat IS NOT NULL THEN dp.alamat
      WHEN lunas.alamat IS NOT NULL THEN lunas.alamat
    END AS alamat,
    CASE 
      WHEN tjs.id_customer IS NOT NULL THEN tjs.id_customer
      WHEN dp.id_customer IS NOT NULL THEN dp.id_customer
      WHEN lunas.id_customer IS NOT NULL THEN lunas.id_customer
    END AS id_customer,
    CASE 
      WHEN kd_tjs.id_flp_md IS NOT NULL THEN kd_tjs.id_flp_md
      WHEN kd_dp.id_flp_md IS NOT NULL THEN kd_dp.id_flp_md
      WHEN kd_lunas.id_flp_md IS NOT NULL THEN kd_lunas.id_flp_md
    END AS id_flp_md,
    CASE 
      WHEN kd_tjs.id_flp_md IS NOT NULL THEN kd_tjs.nama_lengkap
      WHEN kd_dp.id_flp_md IS NOT NULL THEN kd_dp.nama_lengkap
      WHEN kd_lunas.id_flp_md IS NOT NULL THEN kd_lunas.nama_lengkap
    END AS nama_lengkap,
    CASE 
      WHEN tjs.tgl_spk IS NOT NULL THEN tjs.tgl_spk
      WHEN dp.tgl_spk IS NOT NULL THEN dp.tgl_spk
      WHEN lunas.tgl_spk IS NOT NULL THEN lunas.tgl_spk
    END AS tgl_spk,
    dir.no_spk, dir.note,
    CASE 
      WHEN (SELECT id_sales_order FROM tr_sales_order WHERE no_spk=dir.no_spk) IS NOT NULL THEN 
        (SELECT is_paid FROM tr_sales_order WHERE no_spk=dir.no_spk)
      ELSE (SELECT is_paid FROM tr_sales_order_gc WHERE no_spk_gc=dir.no_spk and status_cetak !='reject')
    END AS is_paid,
    CASE 
      WHEN (SELECT id_sales_order FROM tr_sales_order WHERE no_spk=dir.no_spk) IS NOT NULL THEN 
        (SELECT id_sales_order FROM tr_sales_order WHERE no_spk=dir.no_spk)
      ELSE (SELECT id_sales_order_gc FROM tr_sales_order_gc WHERE no_spk_gc=dir.no_spk and status_cetak !='reject')
    END AS id_sales_order,
    (SELECT CONCAT(id_invoice,'|',LEFT(created_at,10),'|',amount) FROM tr_invoice_tjs WHERE id_spk=dir.no_spk LIMIT 1) AS detail_tjs,dir.created_at,
    CASE WHEN dir.cara_bayar='cash' THEN 1 ELSE 2 END AS cara_bayar_id
      ";
    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_amount') {
        $select = "IFNULL(SUM(dir.amount),0) AS sum_amount";
      }
    }

    return $this->db->query("SELECT $select
    FROM tr_h1_dealer_invoice_receipt dir
    LEFT JOIN tr_invoice_tjs tjs ON tjs.id_invoice=dir.id_invoice
    LEFT JOIN tr_invoice_dp dp ON dp.id_invoice_dp=dir.id_invoice
    LEFT JOIN tr_invoice_pelunasan lunas ON lunas.id_inv_pelunasan=dir.id_invoice
    LEFT JOIN ms_karyawan_dealer kd_tjs ON kd_tjs.id_karyawan_dealer=tjs.id_karyawan_dealer
    LEFT JOIN ms_karyawan_dealer kd_dp ON kd_dp.id_karyawan_dealer=dp.id_karyawan_dealer
    LEFT JOIN ms_karyawan_dealer kd_lunas ON kd_lunas.id_karyawan_dealer=lunas.id_karyawan_dealer
		$where
    $order
    $limit
    ");
  }

  function getSODetailNoMesin($filter = NULL)
  {
    $where_id = "WHERE 1=1 ";
    $where_gc = "WHERE 1=1 ";
    if ($filter != NULL) {
      if (isset($filter['no_spk'])) {
        $where_id .= " AND no_spk='{$filter['no_spk']}'";
        $where_gc .= " AND no_spk_gc='{$filter['no_spk']}'";
      }
    }
    return $this->db->query("SELECT * FROM(
      SELECT bc.no_mesin,bc.no_rangka,tipe_motor,tipe_ahm, warna
      FROM tr_sales_order so
      JOIN tr_scan_barcode bc ON bc.no_mesin=so.no_mesin
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=bc.tipe_motor
      $where_id
      UNION
      SELECT bc.no_mesin,bc.no_rangka,tipe_motor,tipe_ahm, warna
      FROM tr_sales_order_gc_nosin so_gc
      JOIN tr_scan_barcode bc ON bc.no_mesin=so_gc.no_mesin
      JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=bc.tipe_motor
      $where_gc
    ) AS tabel
    ");
  }

  function  getDealerInvoiceReceiptDetail($filter)
  {
    $where = "WHERE 1=1";
    if (isset($filter['id_kwitansi'])) {
      $where .= " AND rcp.id_kwitansi='{$filter['id_kwitansi']}'";
    }
    if (isset($filter['no_spk'])) {
      $where .= " AND rc.no_spk='{$filter['no_spk']}'";
    }
    if (isset($filter['created_at_lebih_kecil'])) {
      $where .= " AND rc.created_at<'{$filter['created_at_lebih_kecil']}'";
    }
    if (isset($filter['tgl_pembayaran_lebih_kecil'])) {
      $where .= " AND rc.tgl_pembayaran<'{$filter['tgl_pembayaran_lebih_kecil']}'";
    }

    if (isset($filter['tgl_pembayaran'])) {
      $where .= " AND rc.tgl_pembayaran='{$filter['tgl_pembayaran']}'";
    }
    if (isset($filter['jenis_invoice_in'])) {
      if ($filter['jenis_invoice_in'] != '') {
        $where .= " AND rc.jenis_invoice IN({$filter['jenis_invoice_in']})";
      }
    }

    if (isset($filter['select'])) {
      if ($filter['select'] == 'sum_nominal') {
        $select = "IFNULL(SUM(nominal),0) AS sum_nominal";
      }
    } else {
      $select = "rcp.*,bk.bank,rc.jenis_invoice,rc.tgl_pembayaran,rc.id_invoice,rc.amount ";
    }
    return $this->db->query("SELECT $select
    FROM tr_h1_dealer_invoice_receipt_pembayaran rcp 
    JOIN tr_h1_dealer_invoice_receipt rc ON rc.id_kwitansi=rcp.id_kwitansi
    LEFT JOIN ms_bank bk ON bk.id_bank=rcp.id_bank
    $where ORDER BY rc.created_at ASC");
  }

  function sisa_pelunasan($filter)
  {
    // send_json($filter);
    $where_dp = "WHERE 1=1 ";
    $where_lunas = "WHERE 1=1 ";
    if (isset($filter['id_spk'])) {
      $where_dp .= " AND dp_r.no_spk='{$filter['id_spk']}' ";
      $where_lunas .= " AND dp_r.no_spk='{$filter['id_spk']}' ";
    }
    if (isset($filter['tgl_pembayaran'])) {
      $where_dp .= " AND dp_r.tgl_pembayaran='{$filter['tgl_pembayaran']}' ";
      $where_lunas .= " AND dp_r.tgl_pembayaran='{$filter['tgl_pembayaran']}' ";
    }
    if ($filter['jenis'] == 'dp') {
      return $this->db->query("SELECT IFNULL(SUM(dp_r.amount),0) tot
        FROM tr_h1_dealer_invoice_receipt dp_r 
        LEFT JOIN tr_invoice_dp dp_sum ON dp_sum.id_invoice_dp=dp_r.id_invoice
        $where_dp")->row()->tot;
    } else {
      return $this->db->query("SELECT IFNULL(SUM(dp_r.amount),0) tot
        FROM tr_h1_dealer_invoice_receipt dp_r 
        $where_lunas ")->row()->tot;
    }
  }

  function cek_tot_penerimaan_spk($no_spk)
  {
    return $this->db->query("SELECT IFNULL(SUM(amount),0) as total FROM tr_h1_dealer_invoice_receipt rc WHERE rc.no_spk='$no_spk'");
  }
	
  function getInvoiceDP_v2($filter = NULL)
  {
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE dp.id_dealer = '$id_dealer' ";
    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND dp.id_spk='{$filter['no_spk']}'";
      }
    }

    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (dp.id_spk LIKE '%$search%'
                            OR dp.nama_konsumen LIKE '%$search%'
                            OR dp.id_sales_order LIKE '%$search%'
                            OR dp.no_ktp LIKE '%$search%'
                            OR dp.no_hp LIKE '%$search%'
                            OR dp.jenis_beli LIKE '%$search%'
                            OR dp.alamat LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'tjs') {
          $order_column = ['spk.no_spk', 'spk.tgl_spk', 'spk.nama_konsumen', 'spk.no_ktp', 'spk.no_hp', 'spk.id_tipe_kendaraan', 'spk.id_warna', 'spk.jenis_beli', 'spk.tanda_jadi', 'spk.total_bayar', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY dp.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }

	/*Belum siap
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count_no_spk') {
        $select = "COUNT(dp.id_spk) AS count";
      } elseif ($filter['select'] === 'view_invoice_dp') {
        $select = "dp.id_sales_order,dp.id_spk,dp.id_spk no_spk,dp.nama_konsumen,dp.tgl_spk,dp.no_ktp,dp.no_hp,dp.jenis_beli,kd.id_flp_md,kd.nama_lengkap,dp.id_karyawan_dealer,dp.id_invoice_dp,dp.created_at,LEFT(dp.created_at,10) AS tgl_invoice_dp,dp.amount_dp, dp.diskon,dp.total_bayar,amount_dp,(amount_dp+IFNULL(dp.diskon,0)) AS dp_gross,
          CASE WHEN so_gc.id_sales_order_gc IS NULL THEN LEFT(so.created_at,10) ELSE LEFT(so_gc.created_at,10) END AS tgl_so,dp.amount_dp tanda_jadi,(amount_dp-IFNULL(dp.amount_dp,0)) AS dp_amount_tjs,dp.status,tjs.amount as amount_tjs,dp.jenis_spk";
      }
    }
	*/


    return $this->db->query("
	select dp.id_sales_order, dp.no_spk, dp.nama_konsumen , dp.id_invoice_dp , dp.created_at , dp.amount_dp , dp.diskon , dp.total_bayar ,dp.dp_gross, dp.jenis_spk, dp.status,
	(sisa_pelunasan - ifnull(dp_r.amount,0)) as sisa_pelunasan , (dp.summary_terima + ifnull(dp_r.amount,0) )  as summary_terima
	from (  
		SELECT dp.id_sales_order,dp.id_spk no_spk,dp.nama_konsumen,dp.id_invoice_dp, dp.created_at, dp.amount_dp, dp.diskon,dp.total_bayar,(amount_dp+IFNULL(dp.diskon,0)) AS dp_gross, dp.jenis_spk,dp.status
		, (dp.total_bayar-IFNULL(SUM(dp_r2.amount),0)) as sisa_pelunasan, IFNULL(SUM(dp_r2.amount),0)as summary_terima
		from (
			select * from tr_invoice_dp dp 
			$where
			$order
			$limit
		) dp
		left join tr_h1_dealer_invoice_receipt dp_r2 ON dp.id_invoice_dp=dp_r2.id_invoice 
		group by dp.id_sales_order,dp.id_spk ,dp.nama_konsumen,dp.id_invoice_dp,dp.created_at, dp.amount_dp, dp.diskon,dp.total_bayar, dp.jenis_spk,dp.status
	) as dp
	LEFT JOIN tr_invoice_tjs tjs ON tjs.id_spk=dp.no_spk AND tjs.status='close'
	left join tr_h1_dealer_invoice_receipt dp_r ON tjs.id_invoice=dp_r.id_invoice AND tjs.status='close' 
    	$order
	");
  }

  function getInvoicePelunasan_v2($filter = NULL)
  {
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE lunas.id_dealer = '$id_dealer' ";
    if (isset($filter['no_spk'])) {
      if ($filter['no_spk'] != '') {
        $where .= " AND lunas.id_spk='{$filter['no_spk']}'";
      }
    }
 
    if (isset($filter['search'])) {
      $search = $filter['search'];
      if ($search != '') {
        $where .= " AND (lunas.id_spk LIKE '%$search%'
                            OR lunas.id_sales_order LIKE '%$search%'
                            OR lunas.nama_konsumen LIKE '%$search%'
                            OR lunas.no_ktp LIKE '%$search%'
                            OR lunas.no_hp LIKE '%$search%'
                            OR lunas.jenis_beli LIKE '%$search%'
                            OR lunas.alamat LIKE '%$search%'
                            ) 
            ";
      }
    }

    $order = '';
    if (isset($filter['order'])) {
      $order = $filter['order'];
      if ($order != '') {
        if ($filter['order_column'] == 'tjs') {
          $order_column = ['spk.no_spk', 'spk.tgl_spk', 'spk.nama_konsumen', 'spk.no_ktp', 'spk.no_hp', 'spk.id_tipe_kendaraan', 'spk.id_warna', 'spk.jenis_beli', 'spk.tanda_jadi', 'spk.total_bayar', NULL];
        }
        $order_clm  = $order_column[$order['0']['column']];
        $order_by   = $order['0']['dir'];
        $order = " ORDER BY $order_clm $order_by ";
      } else {
        $order .= " ORDER BY lunas.created_at DESC ";
      }
    }

    $limit = '';
    if (isset($filter['limit'])) {
      $limit = $filter['limit'];
    }
  
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count_no_spk') {
        $select = "COUNT(lunas.id_spk) AS count";
      } elseif ($filter['select'] == 'view_invoice_lunas') {
        $select = "lunas.id_sales_order,lunas.id_spk AS no_spk,lunas.nama_konsumen,lunas.tgl_spk,lunas.no_ktp,lunas.no_hp,lunas.jenis_beli,kd.id_flp_md,kd.nama_lengkap,kd.id_karyawan_dealer,lunas.id_inv_pelunasan,lunas.created_at,lunas.status,lunas.amount_pelunasan,lunas.diskon, lunas.amount_pelunasan as total_bayar,amount_dp,CASE WHEN so.created_at IS NULL THEN LEFT(so_gc.created_at,10) ELSE LEFT(so.created_at,10) END AS tgl_so,lunas.amount_tjs,LEFT(lunas.created_at,10) AS tgl_inv_pelunasan,lunas.jenis_spk";
      }
    }

    return $this->db->query("
	select lunas.id_sales_order, lunas.no_spk, lunas.nama_konsumen , lunas.id_inv_pelunasan , lunas.created_at , lunas.amount_dp ,lunas.diskon, lunas.total_bayar, lunas.dp_gross, lunas.jenis_spk , lunas.status ,
	(lunas.sisa_pelunasan - ifnull(dp_r.amount,0)) as sisa_pelunasan , (lunas.summary_terima + ifnull(dp_r.amount,0)) as summary_terima 
	from (
		SELECT lunas.id_sales_order,lunas.id_spk no_spk,lunas.nama_konsumen,lunas.id_inv_pelunasan,lunas.created_at, lunas.amount_dp, 
		lunas.diskon,lunas.amount_pelunasan as total_bayar,(0) AS dp_gross, lunas.jenis_spk,lunas.status, 
		(lunas.amount_pelunasan- sum(ifnull(dp_r2.amount,0))) as sisa_pelunasan
		, sum(ifnull(dp_r2.amount,0)) as summary_terima 
		from (
			select * from tr_invoice_pelunasan lunas
			$where
			$order
			$limit
		) lunas
		left join tr_h1_dealer_invoice_receipt dp_r2 ON lunas.id_inv_pelunasan=dp_r2.id_invoice 
		group by lunas.id_sales_order,lunas.id_spk ,lunas.nama_konsumen,lunas.id_inv_pelunasan,lunas.created_at, lunas.amount_dp, lunas.diskon,lunas.amount_pelunasan, lunas.jenis_spk,lunas.status
	) as lunas 
	LEFT JOIN tr_invoice_tjs tjs ON tjs.id_spk=lunas.no_spk AND tjs.status='close'
	left join tr_h1_dealer_invoice_receipt dp_r ON tjs.id_invoice=dp_r.id_invoice AND tjs.status='close' 
	$order    
    ");
  }

}
