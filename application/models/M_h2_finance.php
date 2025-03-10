<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2_finance extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model('m_h2_work_order', 'm_wo');
  }

  function getPenerimaanFinance($filter = null)
  {
    $tot_bayar = "(SELECT SUM(dibayar) FROM tr_h23_penerimaan_finance_detail WHERE no_receipt_kas=pk.no_receipt_kas)";
    $order_column = ['no_receipt_kas', 'pk.kode_coa', 'coa', "$tot_bayar", null];
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE pk.id_dealer='$id_dealer' ";
    $order = "ORDER BY pk.created_at DESC ";
    $limit = '';

    if ($filter != null) {
      if (isset($filter['no_receipt_kas'])) {
        $where .= " AND pk.no_receipt_kas='{$filter['no_receipt_kas']}' ";
      }
      if (isset($filter['jenis_penerimaan'])) {
        $where .= " AND pk.jenis_penerimaan='{$filter['jenis_penerimaan']}' ";
      }
      if (isset($filter['status'])) {
        $where .= " AND pk.status='{$filter['status']}' ";
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        }
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    return $this->db->query("SELECT no_receipt_kas,tgl_transaksi,pk.kode_coa,pk.status,coa.coa,tipe_transaksi,$tot_bayar AS tot_dibayar,pk.status,pk.jenis_pembayaran,pk.tipe_customer,pk.diterima_dari,dl_hlo.nama_dealer,dl_hlo.id_dealer,ch23.id_customer,ch23.nama_customer,vp.nama_vendor,vp.id_vendor,pk.deskripsi,
    CASE 
      WHEN ch23.alamat IS NOT NULL THEN ch23.alamat
      WHEN dl_hlo.alamat IS NOT NULL THEN dl_hlo.alamat
      WHEN vp.alamat IS NOT NULL THEN vp.alamat
    END alamat,pk.via_bayar
    FROM tr_h23_penerimaan_finance pk
    LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=pk.diterima_dari
    LEFT JOIN ms_dealer dl_hlo ON dl_hlo.id_dealer=pk.diterima_dari
    LEFT JOIN ms_h2_vendor_po_dealer vp ON vp.id_vendor=pk.diterima_dari
    LEFT JOIN ms_coa_dealer coa ON coa.kode_coa=pk.kode_coa
    $where $order $limit
    ");
  }

  function getPenerimaanFinanceDetail($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE pk.id_dealer='$id_dealer' ";
    $select = 'pkd.no_receipt_kas,pkd.kode_coa,pkd.dibayar,pkd.id_referensi,pkd.keterangan,coa.coa,coa.tipe_transaksi,pk.tgl_transaksi,pkd.referensi';

    $jml_dikeluarkan = "IFNULL((SELECT SUM(jml_dibayar) FROM tr_h23_pengeluaran_finance_detail WHERE tr_h23_pengeluaran_finance_detail.id_referensi=pkd.id_referensi),0) ";

    $sisa = "(pkd.dibayar - $jml_dikeluarkan)";
    if ($filter != null) {
      if (isset($filter['no_receipt_kas'])) {
        $where .= " AND pk.no_receipt_kas='{$filter['no_receipt_kas']}' ";
      }
      if (isset($filter['id_referensi'])) {
        $where .= " AND pkd.id_referensi='{$filter['id_referensi']}' ";
      }
      if (isset($filter['tgl_transaksi'])) {
        // $where .= " AND pk.tgl_transaksi='{$filter['tgl_transaksi']}' ";
      }
      if (isset($filter['jenis_penerimaan'])) {
        $where .= " AND pk.jenis_penerimaan='{$filter['jenis_penerimaan']}' ";
      }
      if (isset($filter['cek_sisa'])) {
        $select = " $sisa AS sisa, pkd.id_referensi,pk.tgl_transaksi,pk.status";
      }
      if (isset($filter['sisa_lebih_besar'])) {
        $where .= " AND $sisa>0 ";
      }
      if (isset($filter['referensi_in'])) {
        $where .= " AND pkd.referensi IN ({$filter['referensi_in']}) ";
      }
      if (isset($filter['group_by_tgl'])) {
        $jml_dikeluarkan = "IFNULL((SELECT SUM(jml_dibayar) FROM tr_h23_pengeluaran_finance_detail WHERE tr_h23_pengeluaran_finance_detail.from='penerimaan' AND id_referensi=pk.tgl_transaksi),0) ";
        $sisa = "(SUM(pkd.dibayar)-$jml_dikeluarkan)";
        $select = " SUM(pkd.dibayar) AS sum_dibayar, $sisa AS sisa, tgl_transaksi,pk.status";
        $where .= " GROUP BY pk.tgl_transaksi ";
      }
    }
    $total_receipt = "SELECT sum(dibayar) FROM tr_h23_penerimaan_finance_detail WHERE no_receipt_kas=pkd.no_receipt_kas";
    $total_piutang = "SELECT sum(total) FROM tr_h23_penerimaan_finance_detail WHERE no_receipt_kas=pkd.no_receipt_kas";
    $count_detail = "SELECT COUNT(no_receipt_kas) FROM tr_h23_penerimaan_finance_detail WHERE no_receipt_kas=pkd.no_receipt_kas";

    return $this->db->query("SELECT $select ,pkd.total,pkd.nominal_uang_muka,pkd.no_inv_jaminan,pkd.no_nsc,pkd.no_njb,pk.deskripsi,($total_receipt) total_receipt,jenis_penerimaan,pkd.no_inv_jaminan,pkd.nominal_uang_muka,($total_piutang) total_piutang,($count_detail) count_detail
    FROM tr_h23_penerimaan_finance_detail pkd
    JOIN tr_h23_penerimaan_finance pk ON pk.no_receipt_kas=pkd.no_receipt_kas
    LEFT JOIN ms_coa_dealer coa ON coa.kode_coa=pkd.kode_coa
    $where");
  }

  function getPenerimaanFinancePembayaran($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE pk.id_dealer='$id_dealer' ";
    $select = '';
    if ($filter != null) {
      if (isset($filter['no_receipt_kas'])) {
        $where .= " AND pk.no_receipt_kas='{$filter['no_receipt_kas']}' ";
      }
      if (isset($filter['status'])) {
        $status = $filter['status'];
        if ($status == null) {
          $where .= " AND pk.status IS NULL";
        } else {
          $where .= " AND pk.status='$status' ";
        }
      }
      if (isset($filter['group_by_tgl'])) {
        $select .= "SUM(pby.jml_dibayar) AS sum_dibayar,";
        $where .= " GROUP BY pk.tgl_entry ";
      }
    }
    return $this->db->query("SELECT $select pby.id, pby.no_receipt_kas,no_bg_cek,tgl_jatuh_tempo_bg_cek,tgl_transfer,nominal,pby.tgl_cair,pby.id_bank,bk.bank nama_bank,pby.no_rekening,pby.atas_nama
    FROM tr_h23_penerimaan_finance_pembayaran pby
    JOIN tr_h23_penerimaan_finance pk ON pk.no_receipt_kas=pby.no_receipt_kas
    JOIN ms_bank bk ON bk.id_bank=pby.id_bank
    $where");
  }


  public function get_no_receipt_kas()
  {
    $th_bln = date('Y-m');
    $my     = date('y/m');
    $id_dealer     = $this->m_admin->cari_dealer();
    $dealer = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT * FROM tr_h23_penerimaan_finance
			WHERE id_dealer='{$dealer->id_dealer}' AND LEFT(created_at,7)='$th_bln' 
      ORDER BY created_at DESC LIMIT 0,1");
    $kode = $dealer->kode_dealer_md;
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $no_receipt_kas = substr($row->no_receipt_kas, -4);
      $new_kode = 'MK/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $no_receipt_kas + 1);
      $i        = 0;

      while ($i < 1) {
        $cek = $this->db->get_where('tr_h23_penerimaan_finance', ['no_receipt_kas' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -3);
          $new_kode = 'MK/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'MK/' . $kode . '/' . $my . '/0001';
    }
    return strtoupper($new_kode);
  }

  function getPOFinance($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE 1=1 AND po.id_dealer='$id_dealer' ";
    $order = "ORDER BY po.created_at DESC";
    $limit = '';

    $pembayaran = "(SELECT IFNULL(SUM(jml_dibayar),0) 
            FROM tr_h23_pengeluaran_finance_detail pfd
            JOIN tr_h23_pengeluaran_finance pf ON pf.no_voucher=pfd.no_voucher
            LEFT JOIN tr_h23_entry_pengeluaran_bank bk ON bk.no_voucher=pfd.no_voucher
            WHERE id_referensi=po.id_po 
            AND 1 = CASE 
                     WHEN pf.jenis_pengeluaran='bank' THEN
                      CASE WHEN bk.status='approved' THEN 1 ELSE 0 END
                     ELSE  
                      CASE WHEN pf.status='approved' THEN 1 ELSE 0 END
                    END
            )";
    $sisa = "(tot_po_tagihan-$pembayaran)";

    if ($filter != null) {
      if (isset($filter['order_column'])) {
        $order_column = $filter['order_column'];
      } else {
        $order_column = ['id_po', 'tgl_po', 'nama_vendor', 'keterangan', 'po.total', null];
      }

      if (isset($filter['id_po'])) {
        $where .= " AND id_po='{$filter['id_po']}' ";
      }
      if (isset($filter['sisa'])) {
        $where .= " AND $sisa {$filter['sisa']} ";
      }
      if (isset($filter['id_vendor'])) {
        $where .= " AND po.id_vendor='{$filter['id_vendor']}' ";
      }
      if (isset($filter['status'])) {
        if ($filter['status'] != '') {
          $where .= " AND po.status='{$filter['status']}' ";
        }
      }
      if (isset($filter['status_tagihan'])) {
        if ($filter['status_tagihan'] != '') {
          $where .= " AND tl.status='{$filter['status_tagihan']}' ";
        }
      }
      if (isset($filter['id_tagihan_not_null'])) {
        $where .= " AND po.id_tagihan IS NOT NULL";
      }
      if (isset($filter['id_tagihan_id_vendor'])) {
        $id_tagihan = $filter['id_tagihan_id_vendor']['id_tagihan'];
        $id_vendor = $filter['id_tagihan_id_vendor']['id_vendor'];
        $where .= " AND (po.id_tagihan='$id_tagihan' OR po.id_vendor='$id_vendor')";
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        }
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }

    return $this->db->query("SELECT id_po,tgl_po,keterangan,po.total,nama_vendor,po.id_vendor,po.status,dpp,tot_ppn,tot_pph pph, tot_po_tagihan AS total_hutang,$pembayaran AS pembayaran, $sisa AS sisa,due_date,tl.status AS status_tagihan,po.ppn,po.ada_ppn,po.grand_total
    FROM tr_h2_dealer_po_finance po
    JOIN ms_h2_vendor_po_dealer vd ON vd.id_vendor=po.id_vendor
    LEFT JOIN tr_h2_dealer_tagihan_lain tl ON tl.id_tagihan=po.id_tagihan
    $where $order $limit
    ");
  }

  function getPOFinanceDetail($filter = null)
  {
    $where = "WHERE 1=1 ";
    if ($filter != null) {
      if (isset($filter['id_po'])) {
        $where .= " AND id_po='{$filter['id_po']}' ";
      }
    }
    return $this->db->query("SELECT id_po,nama_barang,qty,po.harga_satuan,(po.harga_satuan*po.qty) AS subtotal
    FROM tr_h2_dealer_po_finance_detail po
    -- JOIN ms_h2_barang_luar br ON br.id_barang=po.id_barang
    $where
    ");
  }

  public function get_id_po()
  {
    $th_bln = date('Y-m');
    $my     = date('y/m');
    $id_dealer     = $this->m_admin->cari_dealer();
    $dealer = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT * FROM tr_h2_dealer_po_finance
			WHERE id_dealer='{$dealer->id_dealer}' AND LEFT(created_at,7)='$th_bln' 
      ORDER BY created_at DESC LIMIT 0,1");
    $kode = $dealer->kode_dealer_md;
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $id_po = substr($row->id_po, -4);
      $new_kode = 'POF/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $id_po + 1);
      $i        = 0;

      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_dealer_po_finance', ['id_po' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -3);
          $new_kode = 'POF/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'POF/' . $kode . '/' . $my . '/0001';
    }
    return strtoupper($new_kode);
  }

  public function get_id_tagihan()
  {
    $th_bln = date('Y-m');
    $my     = date('y/m');
    $id_dealer     = $this->m_admin->cari_dealer();
    $dealer = $this->db->get_where('ms_dealer', ['id_dealer' => $id_dealer])->row();
    $get_data  = $this->db->query("SELECT id_tagihan FROM tr_h2_dealer_tagihan_lain
			WHERE id_dealer='{$dealer->id_dealer}' AND LEFT(created_at,7)='$th_bln' 
      ORDER BY created_at DESC LIMIT 0,1");
    $kode = $dealer->kode_dealer_md;
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $id_tagihan = substr($row->id_tagihan, -4);
      $new_kode = 'TG/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $id_tagihan + 1);
      $i        = 0;

      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_dealer_tagihan_lain', ['id_tagihan' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -3);
          $new_kode = 'TG/' . $kode . '/' . $my . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'TG/' . $kode . '/' . $my . '/0001';
    }
    return strtoupper($new_kode);
  }

  function getTagihanLain($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE tg.id_dealer='$id_dealer' ";
    $order = "ORDER BY tg.created_at DESC";
    $limit = '';

    if ($filter != null) {
      if (isset($filter['order_column'])) {
        $order_column = $filter['order_column'];
      } else {
        $order_column = ['id_tagihan', 'tgl_tagihan', 'nama_vendor', 'total', null];
      }

      if (isset($filter['id_tagihan'])) {
        $where .= " AND id_tagihan='{$filter['id_tagihan']}' ";
      }
      if (isset($filter['id_vendor'])) {
        $where .= " AND tg.id_vendor='{$filter['id_vendor']}' ";
      }
      if (isset($filter['status'])) {
        if ($filter['status'] != '') {
          $where .= " AND tg.status='{$filter['status']}' ";
        }
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $search = $filter['search'];
          $where .= " AND (id_tagihan LIKE '%$search%'
                            OR vdr.nama_vendor LIKE '%$search%'
                            OR vdr.id_vendor LIKE '%$search%'
                            ) 
            ";
        }
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        }
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    return $this->db->query("SELECT id_tagihan,tipe_customer,tgl_tagihan,total,nama_vendor,tg.id_vendor,tg.status
    FROM tr_h2_dealer_tagihan_lain tg
    JOIN ms_h2_vendor_po_dealer vd ON vd.id_vendor=tg.id_vendor
    $where $order $limit
    ");
  }

  function getTagihanLainDetail($filter = null)
  {
    $id_dealer = dealer()->id_dealer;
    $where = "WHERE 1=1 AND po.id_dealer='$id_dealer'";
    if ($filter != null) {
      if (isset($filter['id_tagihan'])) {
        $where .= " AND po.id_tagihan='{$filter['id_tagihan']}' ";
      }
    }
    return $this->db->query("SELECT id_po,tgl_po,po.total AS tot_po,kode_coa,no_kwitansi,tgl_kwitansi,no_bast,tgl_bast,due_date,po.ppn,tipe_pph,po.id_vendor,vd.nama_vendor,CASE WHEN tl.id_tagihan IS NULL THEN 0 ELSE 1 END checked,po.grand_total
    FROM tr_h2_dealer_po_finance po
    JOIN ms_h2_vendor_po_dealer vd ON vd.id_vendor=po.id_vendor
    LEFT JOIN tr_h2_dealer_tagihan_lain tl ON tl.id_tagihan=po.id_tagihan
    $where
    ");
  }

  public function get_no_voucher()
  {
    $th_bln = date('Y-m');
    $ym     = date('y/m');
    $dealer = dealer();
    $get_data  = $this->db->query("SELECT no_voucher FROM tr_h23_pengeluaran_finance
			WHERE id_dealer='{$dealer->id_dealer}' AND LEFT(created_at,7)='$th_bln' 
      ORDER BY created_at DESC LIMIT 0,1");
    $kode = $dealer->kode_dealer_md;
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $no_voucher = substr($row->no_voucher, -4);
      $new_kode = 'KK/' . $kode . '/' . $ym . '/' . sprintf("%'.04d", $no_voucher + 1);
      $i        = 0;

      while ($i < 1) {
        $cek = $this->db->get_where('tr_h23_pengeluaran_finance', ['no_voucher' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -3);
          $new_kode = 'KK/' . $kode . '/' . $ym . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'KK/' . $kode . '/' . $ym . '/0001';
    }
    return strtoupper($new_kode);
  }

  function getPengeluaranFinance($filter = null)
  {
    $tot_bayar = "(SELECT SUM(jml_dibayar) FROM tr_h23_pengeluaran_finance_detail WHERE no_voucher=pk.no_voucher)";
    $order_column = ['no_voucher', 'pk.kode_coa', 'coa', "$tot_bayar", null];
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE pk.id_dealer='$id_dealer' ";
    $order = "ORDER BY pk.created_at DESC ";
    $limit = '';
    $select = '';

    if ($filter != null) {
      if (isset($filter['no_voucher'])) {
        $where .= " AND pk.no_voucher='{$filter['no_voucher']}' ";
      }
      if (isset($filter['jenis_pengeluaran'])) {
        $where .= " AND pk.jenis_pengeluaran='{$filter['jenis_pengeluaran']}' ";
      }
      if (isset($filter['no_bukti'])) {
        $where .= " AND pk.no_bukti='{$filter['no_bukti']}' ";
      }
      if (isset($filter['status'])) {
        $where .= " AND pk.status='{$filter['status']}' ";
      }
      if (isset($filter['no_bukti_null'])) {
        $where .= " AND pk.no_bukti IS NULL ";
      }
      if (isset($filter['group_by_tgl'])) {
        $dibayar = "(SELECT SUM(dibayar) FROM tr_h23_penerimaan_finance_detail pfd WHERE id_referensi=tgl_entry AND referensi='pengeluaran_kas')";
        $select = "(SUM($tot_bayar)-IFNULL($dibayar,0)) AS sisa, ";
        $where .= " group by tgl_entry";
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        }
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(no_voucher) AS count";
      }
    } else {
      $select .= "no_voucher,tgl_entry,pk.kode_coa,pk.status,coa.coa,tipe_transaksi,dibayar_kepada, deskripsi, pk.total AS tot_dibayar,rekening_tujuan,via_bayar,jenis_pengeluaran,pk.id_vendor,tipe_customer,ch23.id_customer,ch23.nama_customer,pk.jenis_pembayaran,dl_hlo.nama_dealer,dl_hlo.id_dealer,vp.nama_vendor,
      CASE 
        WHEN ch23.alamat IS NOT NULL THEN ch23.alamat
        WHEN dl_hlo.alamat IS NOT NULL THEN dl_hlo.alamat
        WHEN vp.alamat IS NOT NULL THEN vp.alamat
      END alamat,
      CASE 
        WHEN ch23.alamat IS NOT NULL THEN ch23.nama_customer
        WHEN dl_hlo.alamat IS NOT NULL THEN dl_hlo.nama_dealer
        WHEN vp.alamat IS NOT NULL THEN vp.nama_vendor
      END dibayar_kepada_desc
      ";
    }
    return $this->db->query("SELECT $select
    FROM tr_h23_pengeluaran_finance pk
    LEFT JOIN ms_customer_h23 ch23 ON ch23.id_customer=pk.dibayar_kepada
    LEFT JOIN ms_dealer dl_hlo ON dl_hlo.id_dealer=pk.dibayar_kepada
    LEFT JOIN ms_h2_vendor_po_dealer vp ON vp.id_vendor=pk.dibayar_kepada
    LEFT JOIN ms_coa_dealer coa ON coa.kode_coa=pk.kode_coa
    $where $order $limit
    ");
  }

  function getPengeluaranFinanceDetail($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE pk.id_dealer='$id_dealer' ";
    $select = '';
    if ($filter != null) {
      if (isset($filter['no_voucher'])) {
        $where .= " AND pk.no_voucher='{$filter['no_voucher']}' ";
      }
      if (isset($filter['tgl_entry'])) {
        $where .= " AND pk.tgl_entry='{$filter['tgl_entry']}' ";
      }
      if (isset($filter['tipe_customer'])) {
        $where .= " AND pk.tipe_customer='{$filter['tipe_customer']}' ";
      }
      if (isset($filter['id_referensi'])) {
        $where .= " AND pkd.id_referensi='{$filter['id_referensi']}' ";
      }
      if (isset($filter['approved'])) {
        $where .= " AND 1 = CASE 
                WHEN pk.jenis_pengeluaran='bank' THEN
                CASE WHEN epb.status='approved' THEN 1 ELSE 0 END
                ELSE  
                CASE WHEN pk.status='approved' THEN 1 ELSE 0 END
              END
        ";
      }
      if (isset($filter['status'])) {
        $status = $filter['status'];
        if ($status == null) {
          $where .= " AND pk.status IS NULL";
        } else {
          $where .= " AND pk.status='$status' ";
        }
      }
      if (isset($filter['group_by_tgl'])) {
        $select .= " SUM(pkd.jml_dibayar) AS sum_dibayar,";
        $where .= " GROUP BY pk.tgl_entry ";
      }
      if (isset($filter['select'])) {
        if ($filter['select'] == 'summary_dibayar') {
          $select = "SUM(pkd.jml_dibayar) summary_dibayar,";
        }
      }
    }
    return $this->db->query("SELECT $select pkd.no_voucher,pkd.kode_coa,pkd.jml_dibayar AS dibayar,pkd.id_referensi,pkd.keterangan,coa.coa,coa.coa tipe_transaksi,pk.tgl_entry,pkd.from,pkd.sisa_hutang,pkd.jml_dibayar
    FROM tr_h23_pengeluaran_finance_detail pkd
    JOIN tr_h23_pengeluaran_finance pk ON pk.no_voucher=pkd.no_voucher
    LEFT join tr_h23_entry_pengeluaran_bank epb ON epb.no_voucher=pk.no_voucher
    LEFT JOIN ms_coa_dealer coa ON coa.kode_coa=pkd.kode_coa
    $where");
  }
  function getPengeluaranFinancePembayaran($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE pk.id_dealer='$id_dealer' ";
    $select = '';
    if ($filter != null) {
      if (isset($filter['no_voucher'])) {
        $where .= " AND pk.no_voucher='{$filter['no_voucher']}' ";
      }
      if (isset($filter['status'])) {
        $status = $filter['status'];
        if ($status == null) {
          $where .= " AND pk.status IS NULL";
        } else {
          $where .= " AND pk.status='$status' ";
        }
      }
      if (isset($filter['group_by_tgl'])) {
        $select .= "SUM(pby.jml_dibayar) AS sum_dibayar,";
        $where .= " GROUP BY pk.tgl_entry ";
      }
    }
    return $this->db->query("SELECT $select pby.id, pby.no_voucher,no_bg_cek,tgl_jatuh_tempo_bg_cek,tgl_transfer,nominal,pby.tgl_cair,pby.id_bank,bk.bank nama_bank,pby.no_rekening,pby.atas_nama
    FROM tr_h23_pengeluaran_finance_pembayaran pby
    JOIN tr_h23_pengeluaran_finance pk ON pk.no_voucher=pby.no_voucher
    JOIN ms_bank bk ON bk.id_bank=pby.id_bank
    $where");
  }

  function getPrintReceipt($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE 1=1 AND rc.id_dealer='$id_dealer' ";
    $where_sub = '';
    if (isset($filter['jenis_penerimaan'])) {
      $where_sub .= " AND rcm.metode_bayar='{$filter['jenis_penerimaan']}'";
    }
    if (isset($filter['no_rekap'])) {
      $where_sub .= " AND rcm.no_rekap='{$filter['no_rekap']}'";
    }
    if (isset($filter['no_rekap_null'])) {
      $where_sub .= " AND rcm.no_rekap IS NULL";
    }
    $total = "SELECT SUM(nominal) FROM tr_h2_receipt_customer_metode rcm WHERE rcm.id_receipt=rc.id_receipt $where_sub";
    $group = '';
    if (isset($filter['group_by'])) {
      $group = "GROUP BY {$filter['group_by']}";
    }

    if ($filter != null) {
      if (isset($filter['periode_receipt'])) {
        $where .= " AND rc.tgl_receipt BETWEEN {$filter['periode_receipt']} ";
      }
      if (isset($filter['no_rekap'])) {
        $where .= " AND EXISTS(SELECT no_rekap FROM tr_h2_receipt_customer_metode rcm WHERE no_rekap='{$filter['no_rekap']}' AND rcm.id_receipt=rc.id_receipt) ";
      }
      if (isset($filter['total_lebih_besar'])) {
        $where .= " AND ($total)>0";
      }
      if (isset($filter['id_referensi'])) {
        $where .= " AND rc.id_referensi='{$filter['id_referensi']}'";
      }
    }

    return $this->db->query("SELECT id_receipt,tgl_receipt,
      CASE 
        WHEN referensi='wo' THEN 'Work Order'
        WHEN referensi='part_sales' THEN 'Direct Sales'
      END AS referensi,
      id_referensi,IFNULL(($total),0) AS total
      FROM tr_h2_receipt_customer rc
      $where
      $group
    ");
  }

  public function get_no_rekap()
  {
    $th_bln = date('Y-m');
    $ym     = date('y/m');
    $dealer = dealer();
    $get_data  = $this->db->query("SELECT no_rekap FROM tr_h23_rekap_pendapatan_harian
			WHERE id_dealer='{$dealer->id_dealer}' AND LEFT(created_at,7)='$th_bln' 
      ORDER BY created_at DESC LIMIT 0,1");
    $kode = $dealer->kode_dealer_md;
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $no_rekap = substr($row->no_rekap, -4);
      $new_kode = 'RKP/' . $kode . '/' . $ym . '/' . sprintf("%'.04d", $no_rekap + 1);
      $i        = 0;

      while ($i < 1) {
        $cek = $this->db->get_where('tr_h23_rekap_pendapatan_harian', ['no_rekap' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -3);
          $new_kode = 'RKP/' . $kode . '/' . $ym . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'RKP/' . $kode . '/' . $ym . '/0001';
    }
    return strtoupper($new_kode);
  }

  function getRekapPendapatanHarian($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE rk.id_dealer='$id_dealer' ";
    $order = "ORDER BY rk.created_at DESC ";
    $select = '';
    $limit = '';

    if ($filter != null) {
      if (isset($filter['no_rekap'])) {
        $where .= " AND rk.no_rekap='{$filter['no_rekap']}' ";
      }
      if (isset($filter['jenis_penerimaan'])) {
        $where .= " AND rk.jenis_penerimaan='{$filter['jenis_penerimaan']}' ";
      }
      if (isset($filter['not_exist_penerimaan'])) {
        $where .= " AND NOT EXISTS(SELECT id_referensi FROM tr_h23_penerimaan_finance_detail WHERE id_referensi=rk.no_rekap) ";
      }
      $dibayar = "SELECT SUM(dibayar) FROM tr_h23_penerimaan_finance_detail WHERE id_referensi=tgl_rekap";
      $sisa = "(jumlah - IFNULL(($dibayar),0))";

      if (isset($filter['group_by_tgl'])) {
        $select = "SUM(jumlah) AS tot_jumlah, $sisa AS sisa,";
        $where .= " GROUP BY tgl_rekap ";
      }
      if (isset($filter['sisa_lebih_besar'])) {
        $where .= " AND $sisa>0 ";
      }
      if (isset($filter['cek_sisa'])) {
        $select .= " $sisa AS sisa, ";
      }
      if (isset($filter['order_column'])) {
        if ($filter['order_column'] == 'ref_penerimaan') {
          $order_column = ['no_rekap', 'tgl_rekap', 'jumlah', null];
        }
      } else {
        $order_column = ['no_rekap', 'tgl_receipt', 'start_date', 'end_date', 'jenis_penerimaan', 'jumlah', null];
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        }
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    return $this->db->query("SELECT $select no_rekap,tgl_rekap,start_date,end_date,jenis_penerimaan,jumlah
    FROM tr_h23_rekap_pendapatan_harian rk
    $where $order $limit
    ");
  }


  public function get_id_rekap_kpb()
  {
    $th_bln = date('Y-m');
    $ym     = date('y/m');
    $dealer = dealer();
    $get_data  = $this->db->query("SELECT id_rekap_kpb FROM tr_h2_dealer_rekap_kpb
			WHERE id_dealer='{$dealer->id_dealer}' AND LEFT(created_at,7)='$th_bln' 
      ORDER BY created_at DESC LIMIT 0,1");
    $kode = $dealer->kode_dealer_md;
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $id_rekap_kpb = substr($row->id_rekap_kpb, -4);
      $new_kode = 'KPB/' . $kode . '/' . $ym . '/' . sprintf("%'.04d", $id_rekap_kpb + 1);
      $i        = 0;

      while ($i < 1) {
        $cek = $this->db->get_where('tr_h2_dealer_rekap_kpb', ['id_rekap_kpb' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -3);
          $new_kode = 'KPB/' . $kode . '/' . $ym . '/' . sprintf("%'.04d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'KPB/' . $kode . '/' . $ym . '/0001';
    }
    return strtoupper($new_kode);
  }

  function getRekapKPB($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE rk.id_dealer='$id_dealer' ";
    $order = "ORDER BY rk.created_at DESC ";
    $select = '*';
    $limit = '';

    $terima_pembayaran = "SELECT SUM(dibayar) FROM tr_h23_penerimaan_finance_detail pfd
    JOIN tr_h23_penerimaan_finance pf ON  pf.no_receipt_kas=pfd.no_receipt_kas
    WHERE id_referensi=rk.id_rekap_kpb AND pf.status='approved'";
    $sisa = "(rk.tot_jasa+rk.jml_oli) - IFNULL(($terima_pembayaran),0)";

    if ($filter != null) {
      if (isset($filter['sisa'])) {
        $where .= " AND $sisa {$filter['sisa']} ";
      }
      if (isset($filter['id_rekap_kpb'])) {
        $where .= " AND rk.id_rekap_kpb='{$filter['id_rekap_kpb']}' ";
      }
      if (isset($filter['search'])) {
        if ($filter['search'] != '') {
          $search = $filter['search'];
          $where .= " AND (rk.id_rekap_kpb LIKE '%$search%'
                            ) 
            ";
        }
      }
      if (isset($filter['order_column'])) {
        if ($filter['order_column'] == 'ref_penerimaan') {
          $order_column = ['no_rekap', 'tgl_rekap', 'jumlah', null];
        }
      } else {
        $order_column = ['no_rekap', 'tgl_receipt', 'start_date', 'end_date', 'jenis_penerimaan', 'jumlah', null];
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        }
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
      if (isset($filter['select'])) {
        $select = "COUNT(id_rekap_kpb) AS count";
      }
      if (isset($filter['select'])) {
        $select = "rk.id_rekap_kpb,rk.tgl_rekap,(rk.tot_jasa+rk.jml_oli) total,($sisa) sisa";
      }
    }
    return $this->db->query("SELECT $select
    FROM tr_h2_dealer_rekap_kpb rk
    $where $order $limit
    ");
  }
  public function get_no_bukti()
  {
    $th = date('Y');
    $ym     = date('y/m');
    $dealer = dealer();
    $get_data  = $this->db->query("SELECT no_bukti FROM tr_h23_entry_pengeluaran_bank
			WHERE id_dealer='{$dealer->id_dealer}' AND LEFT(created_at,4)='$th' 
      ORDER BY created_at DESC LIMIT 0,1");
    $kode = $dealer->kode_dealer_md;
    if ($get_data->num_rows() > 0) {
      $row      = $get_data->row();
      $no_bukti = substr($row->no_bukti, -4);
      $new_kode = 'ENTRY/' . $kode . '/' . $ym . '/' . sprintf("%'.05d", $no_bukti + 1);
      $i        = 0;

      while ($i < 1) {
        $cek = $this->db->get_where('tr_h23_entry_pengeluaran_bank', ['no_bukti' => $new_kode])->num_rows();
        if ($cek > 0) {
          $neww     = substr($new_kode, -3);
          $new_kode = 'ENTRY/' . $kode . '/' . $ym . '/' . sprintf("%'.05d", $neww + 1);
          $i        = 0;
        } else {
          $i++;
        }
      }
    } else {
      $new_kode   = 'ENTRY/' . $kode . '/' . $ym . '/00001';
    }
    return strtoupper($new_kode);
  }

  function getEntryPengeluaranBank($filter = null)
  {
    $id_dealer     = $this->m_admin->cari_dealer();
    $where = "WHERE etr.id_dealer='$id_dealer' ";
    $order = "ORDER BY etr.created_at DESC ";
    $limit = '';
    $select = '';

    if ($filter != null) {
      if (isset($filter['no_voucher'])) {
        $where .= " AND etr.no_voucher='{$filter['no_voucher']}' ";
      }
      if (isset($filter['no_bukti'])) {
        $where .= " AND etr.no_bukti='{$filter['no_bukti']}' ";
      }
      if (isset($filter['status'])) {
        $where .= " AND etr.status='{$filter['status']}' ";
      }

      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          if ($filter['order_column'] == 'view_entry') {
            $order_column = ['no_bukti', 'tgl_bukti', 'total', 'dibayar_kepada', 'etr.status', null];
          }
          $order = $filter['order'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        }
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }
    if (isset($filter['select'])) {
      if ($filter['select'] == 'count') {
        $select = "COUNT(etr.no_voucher) AS count";
      }
    } else {
      $select .= "etr.no_voucher,tgl_bukti,etr.total,dibayar_kepada,etr.status,etr.no_bukti,pk.via_bayar,pk.tipe_customer,tgl_bukti AS tgl_entry";
    }
    return $this->db->query("SELECT $select
    FROM tr_h23_entry_pengeluaran_bank etr
    LEFT JOIN tr_h23_pengeluaran_finance pk ON pk.no_voucher=etr.no_voucher
    $where $order $limit
    ");
  }

  function getListAP($filter = null)
  {
    $id_dealer = $this->m_admin->cari_dealer();
    $where_po  = "WHERE 1 = 1 AND po.id_dealer = '$id_dealer' ";
    $where_uj  = "WHERE 1 = 1 AND uj.id_dealer = '$id_dealer' ";
    $where_rc  = "WHERE 1 = 1 AND rc.id_dealer = '$id_dealer' AND kode_coa LIKE '2.1.0%' ";
    $where_nsc_hlo  = "WHERE 1 = 1 AND nsc.id_dealer_pembeli = '$id_dealer' ";
    $order     = "ORDER BY created_at DESC";
    $limit     = '';

    $pembayaran = "(SELECT IFNULL(SUM(jml_dibayar),0) 
            FROM tr_h23_pengeluaran_finance_detail pfd
            JOIN tr_h23_pengeluaran_finance pf ON pf.no_voucher=pfd.no_voucher
            LEFT JOIN tr_h23_entry_pengeluaran_bank bk ON bk.no_voucher=pfd.no_voucher
            WHERE id_referensi=po.id_po 
            AND 1 = CASE 
                     WHEN pf.jenis_pengeluaran='bank' THEN
                      CASE WHEN bk.status='approved' THEN 1 ELSE 0 END
                     ELSE  
                      CASE WHEN pf.status='approved' THEN 1 ELSE 0 END
                    END
            )";
    $sisa = "(tot_po_tagihan-$pembayaran)";

    $pembayaran_uj = "IFNULL((SELECT uang_muka_terpakai FROM tr_h2_uang_jaminan WHERE no_inv_uang_jaminan=uj.no_inv_uang_jaminan),0)";
    $sisa_uj = "IFNULL(uj.total_bayar-($pembayaran_uj),0)";

    $nama_cus_rc = "
            CASE 
              WHEN rc.referensi='wo' 
              THEN (SELECT nama_customer 
                    FROM ms_customer_h23 ch23 
                    JOIN tr_h2_sa_form sa ON sa.id_customer=ch23.id_customer
                    JOIN tr_h2_wo_dealer wo ON wo.id_sa_form=sa.id_sa_form
                    WHERE wo.id_work_order=rc.id_referensi
                    )
              ELSE (SELECT nama_pembeli FROM tr_h3_dealer_sales_order so WHERE so.nomor_so=rc.id_referensi)
            END
            ";


    $pembayaran_rc = 0;
    $sisa_rc = "rc.nominal_lebih - ($pembayaran_rc)";

    $pembayaran_hlo = "(SELECT IFNULL(SUM(jml_dibayar),0) 
            FROM tr_h23_pengeluaran_finance_detail pfd
            JOIN tr_h23_pengeluaran_finance pf ON pf.no_voucher=pfd.no_voucher
            LEFT JOIN tr_h23_entry_pengeluaran_bank bk ON bk.no_voucher=pfd.no_voucher
            WHERE id_referensi=nsc.no_nsc
            AND 1 = CASE 
                     WHEN pf.jenis_pengeluaran='bank' THEN
                      CASE WHEN bk.status='approved' THEN 1 ELSE 0 END
                     ELSE  
                      CASE WHEN pf.status='approved' THEN 1 ELSE 0 END
                    END
            )";
    $sisa_hlo = "nsc.tot_nsc - ($pembayaran_hlo)";

    if ($filter != null) {
      if (isset($filter['order_column'])) {
        $order_column = $filter['order_column'];
      } else {
        $order_column = ['id_po', 'tgl_po', 'nama_vendor', 'keterangan', 'po.total', null];
      }

      if (isset($filter['id_po'])) {
        $where_po .= " AND id_po='{$filter['id_po']}' ";
      }
      if (isset($filter['sisa'])) {
        $where_po .= " AND ($sisa) {$filter['sisa']} ";
        $where_uj .= " AND ($sisa_uj) {$filter['sisa']} ";
        $where_rc .= " AND ($sisa_rc) {$filter['sisa']} ";
        $where_nsc_hlo .= " AND ($sisa_hlo) {$filter['sisa']} ";
      }
      if (isset($filter['id_vendor'])) {
        $where_po .= " AND po.id_vendor='{$filter['id_vendor']}' ";
      }
      if (isset($filter['status'])) {
        if ($filter['status'] != '') {
          $where_po .= " AND po.status='{$filter['status']}' ";
        }
      }
      if (isset($filter['status_tagihan'])) {
        if ($filter['status_tagihan'] != '') {
          $where_po .= " AND tl.status='{$filter['status_tagihan']}' ";
        }
      }
      if (isset($filter['id_tagihan_not_null'])) {
        $where_po .= " AND po.id_tagihan IS NOT NULL";
      }
      if (isset($filter['order'])) {
        if ($filter['order'] != '') {
          $order = $filter['order'];
          $order_clm  = $order_column[$order[0]['column']];
          $order_by   = $order[0]['dir'];
          $order = " ORDER BY $order_clm $order_by ";
        }
      }
      if (isset($filter['limit'])) {
        if ($filter['limit'] != '') {
          $limit = ' ' . $filter['limit'];
        }
      }
    }

    return $this->db->query("SELECT * FROM (
      SELECT 
      id_po,
      tgl_po,
      nama_vendor,
      dpp,
      tot_ppn AS ppn,
      tot_pph pph,
      tot_po_tagihan AS total_hutang,
      $pembayaran AS pembayaran,
      $sisa AS sisa,
      due_date,
      po.created_at
    FROM tr_h2_dealer_po_finance po
    JOIN ms_h2_vendor_po_dealer vd ON vd.id_vendor=po.id_vendor
    LEFT JOIN tr_h2_dealer_tagihan_lain tl ON tl.id_tagihan=po.id_tagihan
    $where_po
    
    UNION
    SELECT
    no_inv_uang_jaminan,
    LEFT(uj.created_at,10),
    ch23.nama_customer,
    0,
    0,
    0,
    uj.total_bayar,
    ($pembayaran_uj),
    $sisa_uj,
    '',
    uj.created_at
    FROM tr_h2_uang_jaminan uj
    JOIN tr_h3_dealer_request_document req ON req.id_booking=uj.id_booking
    JOIN ms_customer_h23 ch23 ON ch23.id_customer=req.id_customer
    $where_uj
    
    UNION
    SELECT
    id_receipt,
    tgl_receipt,
    $nama_cus_rc,
    0 dpp,
    0 ppn,
    0 pph,
    nominal_lebih total_hutang,
    $pembayaran_rc pembayaran,
    $sisa_rc sisa,
    '',
    rc.created_at
    FROM tr_h2_receipt_customer rc
    $where_rc
    
    UNION
    SELECT
    no_nsc,
    tgl_nsc,
    dl_vendor.nama_dealer,
    0 dpp,
    0 ppn,
    0 pph,
    tot_nsc,
    $pembayaran_hlo pembayaran,
    $sisa_hlo sisa,
    '' due_date,
    nsc.created_at
    FROM tr_h23_nsc nsc
    JOIN ms_dealer dl_vendor ON dl_vendor.id_dealer=nsc.id_dealer
    JOIN tr_h3_dealer_good_receipt good ON good.id_reference=nsc.no_nsc
    $where_nsc_hlo

    ) AS new_table $order $limit
    ");
  }

  function getReceiptLebih($filter)
  {
    $where = "WHERE id_dealer='{$filter['id_dealer']}' ";
    $sisa = "rc.nominal_lebih - IFNULL(( SELECT SUM(jml_dibayar) FROM tr_h23_pengeluaran_finance_detail WHERE id_referensi=rc.id_receipt),0)";

    if (isset($filter['kode_coa_like'])) {
      $where .= " AND rc.kode_coa LIKE '{$filter['kode_coa_like']}' ";
    }

    if (isset($filter['sisa'])) {
      if (is_array($filter['sisa'])) {
        $set_sisa = $filter['sisa'];
        $where .= " AND ($sisa) {$set_sisa['operator']} {$set_sisa['value']}";
      }
    }
    if (isset($filter['id_customer'])) {
      $where .= " AND (CASE 
                      WHEN referensi='wo' THEN 
                        CASE WHEN (SELECT id_customer FROm tr_h2_wo_dealer woc JOIN tr_h2_sa_form sac ON sac.id_sa_form=woc.id_sa_form WHERE woc.id_work_order=rc.id_referensi)='{$filter['id_customer']}' THEN 1 ELSE 0 END
                      WHEN referensi='part_sales' THEN
                        CASE WHEN (SELECT id_customer FROm tr_h3_dealer_sales_order so3 WHERE so3.nomor_so=rc.id_referensi)='{$filter['id_customer']}' THEN 1 ELSE 0 END
                      ELSE 0
                      END)>0
            ";
    }

    return $this->db->query("SELECT id_receipt,tgl_receipt,$sisa as sisa
      FROM tr_h2_receipt_customer rc 
      $where");
  }

  function getNSCDariHLO($filter)
  {
    $where = "WHERE 1=1 ";
    $pembayaran = "IFNULL((SELECT IFNULL(SUM(jml_dibayar),0) 
    FROM tr_h23_pengeluaran_finance_detail pfd
    JOIN tr_h23_pengeluaran_finance pf ON pf.no_voucher=pfd.no_voucher
    LEFT JOIN tr_h23_entry_pengeluaran_bank bk ON bk.no_voucher=pfd.no_voucher
    WHERE id_referensi=nsc.no_nsc
    AND 1 = CASE 
             WHEN pf.jenis_pengeluaran='bank' THEN
              CASE WHEN bk.status='approved' THEN 1 ELSE 0 END
             ELSE  
              CASE WHEN pf.status='approved' THEN 1 ELSE 0 END
            END
    ),0) + IFNULL((
                  SELECT SUM(nominal) FROM tr_h2_receipt_customer_metode rcm 
                  JOIN tr_h2_receipt_customer rc ON rc.id_receipt=rcm.id_receipt
                  WHERE rc.id_referensi=nsc.id_referensi
                ),0)
            
            ";
    $sisa = "nsc.tot_nsc - IFNULL(($pembayaran),0)";

    if (isset($filter['id_dealer_pembeli'])) {
      $where .= " AND nsc.id_dealer_pembeli='{$filter['id_dealer_pembeli']}' ";
    }
    if (isset($filter['id_dealer'])) {
      $where .= " AND nsc.id_dealer='{$filter['id_dealer']}' ";
    }
    if (isset($filter['sisa'])) {
      if (is_array($filter['sisa'])) {
        $set_sisa = $filter['sisa'];
        $where .= " AND ($sisa) {$set_sisa['operator']} {$set_sisa['value']}";
      }
    }

    return $this->db->query("SELECT no_nsc,tgl_nsc,$sisa as sisa,nsc.id_referensi
      FROM tr_h23_nsc nsc
      $where");
  }
}
