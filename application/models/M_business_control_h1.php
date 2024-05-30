<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_business_control_h1 extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function get_dealer()
  {
    $result = $this->db->query("SELECT id_dealer,nama_dealer,kode_dealer_md 
        FROM ms_dealer 
        WHERE active=1 AND h1=1
        ORDER BY nama_dealer ASC");
    return $result;
  }

  function get_claim_md($id_dealer = null, $id_program_md = null)
  {
    $result = $this->db->query("SELECT id_claim_sp,tr_claim_dealer.id_dealer,tr_claim_dealer.id_program_md,tr_sales_program.id_program_ahm,periode_awal,periode_akhir,kode_dealer_md,nama_dealer
        FROM tr_claim_dealer 
        JOIN ms_dealer ON ms_dealer.id_dealer=tr_claim_dealer.id_dealer
        JOIN tr_sales_program ON tr_sales_program.id_program_md=tr_claim_dealer.id_program_md
        LEFT JOIN tr_claim_sales_program ON tr_claim_sales_program.id_program_md=tr_claim_dealer.id_program_md AND tr_claim_sales_program.id_dealer=tr_claim_dealer.id_dealer
        WHERE tr_claim_dealer.id_program_md='$id_program_md' 
        AND tr_claim_dealer.id_dealer='$id_dealer'
        GROUP BY tr_claim_dealer.id_program_md,tr_claim_dealer.id_dealer
        ");
    return $result;

  }



  function get_claim_md_detail($id_dealer, $id_program_md)
  {
    return $this->db->query("SELECT tr_sales_order.no_mesin,no_rangka,
          tr_spk.id_tipe_kendaraan,tipe_ahm,tgl_cetak_invoice,no_po_leasing,tgl_po_leasing,no_bastk,LEFT(tgl_bastk,10)AS tgl_bastk,nama_konsumen,finance_company,no_invoice,tr_sales_order.id_sales_order,jenis_beli
          FROM tr_claim_dealer
          JOIN tr_sales_order ON tr_sales_order.id_sales_order=tr_claim_dealer.id_sales_order
          JOIN tr_spk ON tr_spk.no_spk=tr_sales_order.no_spk
          JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_spk.id_tipe_kendaraan
          LEFT JOIN ms_finance_company ON ms_finance_company.id_finance_company=tr_spk.id_finance_company
          WHERE id_program_md='$id_program_md' AND tr_claim_dealer.id_dealer='$id_dealer'
          ORDER BY tr_spk.id_tipe_kendaraan ASC
        ");
  }


  public function get_data_claim($id_program_ahm = null, $id_dealer = null, $id_program_md = null, $status = null)
  {
    $where = 'WHERE 1=1 ';
    if ($id_program_ahm != '') {
      $where .= "AND tr_sales_program.id_program_ahm='$id_program_ahm'";
    }
    if ($id_program_md != '') {
      $where .= "AND tr_sales_program.id_program_md='$id_program_md'";
    }
    if ($id_dealer != '') {
      $where .= "AND tr_claim_sales_program.id_dealer='$id_dealer'";
    }
    if ($status == null) {
      $where .= "AND tr_claim_sales_program.status IS NULL";
    }

    $dt = $this->db->query("SELECT tr_sales_program.id_program_md,tr_sales_program.id_program_ahm,
        ms_dealer.id_dealer,ms_dealer.nama_dealer,ms_dealer.kode_dealer_md,
        id_claim_sp,periode_awal,periode_akhir
        FROM tr_claim_sales_program
        JOIN tr_sales_program on tr_claim_sales_program.id_program_md = tr_sales_program.id_program_md
        JOIN ms_dealer on tr_claim_sales_program.id_dealer=ms_dealer.id_dealer
        $where
        ORDER BY id_claim_sp DESC");
    return $dt;
  }

  public function get_program($grup_ahm = null)
  {
    $where = 'WHERE 1=1';
    if ($grup_ahm != null) {
      $where .= " AND tr_sales_program.id_program_ahm IS NOT NULL OR tr_sales_program.id_program_ahm!='' GROUP BY tr_sales_program.id_program_ahm";
    }
    $get_program = $this->db->query("SELECT id_program_md,id_program_ahm,judul_kegiatan, periode_awal, periode_akhir, created_at FROM tr_sales_program
        $where
        ORDER BY created_at DESC
        ");
    return $get_program;
  }

  function get_generate($id_dealer = null, $id_program_md = null, $group_by = null, $id_tipe_kendaraan = null, $id_claim_sp = null)
  {
    $set_group_by = '';
    $where = '';
    if ($group_by != null) {
      $set_group_by .= "GROUP BY tr_spk.$group_by";
    }
    if ($id_tipe_kendaraan != null) {
      $where .= " AND  tr_spk.id_tipe_kendaraan='$id_tipe_kendaraan'";
    }
    if ($id_program_md != null) {
      $where .= "AND tr_claim_dealer.id_program_md='$id_program_md' ";
    }
    if ($id_claim_sp == null) {
      $detail     = $this->db->query("SELECT
          tr_sales_order.no_mesin,tr_sales_order.no_rangka,tr_sales_order.id_sales_order,
          tr_sales_order.no_invoice,tr_sales_order.tgl_cetak_invoice,tr_sales_order.no_po_leasing,tr_sales_order.tgl_po_leasing,tr_sales_order.no_bastk,tr_sales_order.tgl_bastk,
          tr_spk.nama_konsumen,tr_spk.id_finance_company,ms_finance_company.finance_company,
          ms_tipe_kendaraan.tipe_ahm,tr_spk.id_tipe_kendaraan,tr_sales_order.id_dealer,
          tr_claim_dealer.status,tr_claim_dealer.id_claim,perlu_revisi,tr_spk.no_ktp,tr_spk.id_warna, ms_warna.warna, tr_claim_dealer.tgl_ajukan_claim, tr_claim_dealer.id_program_md,
          (case when tr_claim_sales_program_detail.perlu_revisi = 1 then 'Ya' else '' end) as perlu_revisi_dealer
          FROM tr_claim_dealer
          LEFT JOIN tr_claim_sales_program_detail ON tr_claim_sales_program_detail.id_claim_dealer=tr_claim_dealer.id_claim
          INNER jOIN tr_sales_order on tr_claim_dealer.id_sales_order=tr_sales_order.id_sales_order
          INNER jOIN tr_scan_barcode on tr_scan_barcode.no_mesin=tr_sales_order.no_mesin
          INNER JOIN tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
          LEFT JOIN ms_finance_company on ms_finance_company.id_finance_company = tr_spk.id_finance_company
          INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_spk.id_tipe_kendaraan
          inner join ms_warna on ms_warna.id_warna =tr_scan_barcode.warna
        WHERE tr_claim_dealer.id_dealer='$id_dealer' 
        -- AND tr_claim_dealer.id_program_md='$id_program_md' 
        AND (tr_claim_dealer.status='ajukan' 
          OR tr_claim_dealer.status='approved' 
          OR tr_claim_dealer.status='rejected')
        -- AND id_claim NOT IN (SELECT id_claim_dealer 
        --                      FROM tr_claim_sales_program_detail 
        --                      WHERE id_claim_dealer IS NOT NULL)
        $where
        $set_group_by
        ORDER BY tr_spk.id_tipe_kendaraan ASC
         ");
      return $detail;
    } else {
      $where .= "AND tr_claim_sales_program_detail.id_claim_sp='$id_claim_sp'";
      $detail     = $this->db->query("SELECT
          tr_sales_order.no_mesin,tr_sales_order.no_rangka,tr_sales_order.id_sales_order,
          tr_sales_order.no_invoice,tr_sales_order.tgl_cetak_invoice,tr_sales_order.no_po_leasing,tr_sales_order.tgl_po_leasing,tr_sales_order.no_bastk,tr_sales_order.tgl_bastk,
          tr_spk.nama_konsumen,tr_spk.id_finance_company,ms_finance_company.finance_company,
          ms_tipe_kendaraan.tipe_ahm,tr_spk.id_tipe_kendaraan,
          tr_claim_dealer.status,tr_claim_dealer.id_claim,perlu_revisi      
          FROM tr_claim_sales_program_detail
          INNER JOIN tr_claim_dealer ON tr_claim_dealer.id_claim=tr_claim_sales_program_detail.id_claim_dealer
          INNER jOIN tr_sales_order on tr_claim_dealer.id_sales_order=tr_sales_order.id_sales_order
         INNER jOIN tr_scan_barcode on tr_scan_barcode.no_mesin=tr_sales_order.no_mesin
          INNER JOIN tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
          LEFT JOIN ms_finance_company on ms_finance_company.id_finance_company = tr_spk.id_finance_company
          INNER JOIN ms_tipe_kendaraan ON ms_tipe_kendaraan.id_tipe_kendaraan=tr_spk.id_tipe_kendaraan
        WHERE 1=1
        $where
        $set_group_by
        ORDER BY tr_spk.id_tipe_kendaraan ASC
         ");
      return $detail;
    }
  }

  function cek_perlu_revisi($id_claim_sp)
  {
    return $this->db->query("SELECT sum(perlu_revisi) as sum FROM tr_claim_sales_program_detail WHERE id_claim_sp='$id_claim_sp'")->row()->sum;
  }

  function cek_status_claim_dealer($id_claim_sp)
  {
    $tot_approved =  $this->db->query("SELECT count(tr_claim_dealer.id_claim) as tot_approved 
        FROM tr_claim_sales_program_detail 
        JOIN tr_claim_dealer ON tr_claim_dealer.id_claim=tr_claim_sales_program_detail.id_claim_dealer
        WHERE id_claim_sp='$id_claim_sp' AND tr_claim_dealer.status='approved'")->row()->tot_approved;

    $tot_rejected =  $this->db->query("SELECT count(tr_claim_dealer.id_claim) as tot_rejected 
        FROM tr_claim_sales_program_detail 
        JOIN tr_claim_dealer ON tr_claim_dealer.id_claim=tr_claim_sales_program_detail.id_claim_dealer
        WHERE id_claim_sp='$id_claim_sp' AND tr_claim_dealer.status='rejected'")->row()->tot_rejected;

    $tot_gantung =  $this->db->query("SELECT count(tr_claim_dealer.id_claim) as tot_gantung 
        FROM tr_claim_sales_program_detail 
        JOIN tr_claim_dealer ON tr_claim_dealer.id_claim=tr_claim_sales_program_detail.id_claim_dealer
        WHERE id_claim_sp='$id_claim_sp' 
        AND tr_claim_dealer.status='ajukan' 
        OR tr_claim_dealer.status=''
        ")->row()->tot_gantung;
    $result = ['tot_approved' => $tot_approved, 'tot_rejected' => $tot_rejected, 'tot_gantung' => $tot_gantung];
    return $result;
  }

  function get_generate_new($id_dealer, $id_program_md)
  {
    $tipe_kendaraan = $this->get_generate($id_dealer, $id_program_md, 'id_tipe_kendaraan');
    foreach ($tipe_kendaraan->result() as $tk) {
      $get_claim = $this->get_generate($id_dealer, $id_program_md, null, $tk->id_tipe_kendaraan);
      $tot_gantung = 0;
      $tot_reject = 0;
      $tot_approve = 0;
      foreach ($get_claim->result_array() as $gc) {
        $data_detail[] = ['field' => 'row', 'data' => $gc];
        if ($gc['status'] == 'approved') {
          $tot_approve++;
        } else if ($gc['status'] == 'rejected') {
          $tot_reject++;
        } else {
          $tot_gantung++;
        }
      }
      // $data_detail[]=['field'=>'tot',
      //                 'id_tipe_kendaraan' =>$tk->id_tipe_kendaraan,
      //                 'tot_unit'          =>$get_claim->num_rows(),
      //                 'tot_gantung'       =>$tot_gantung,
      //                 'tot_approve'       =>$tot_approve,
      //                 'tot_reject'        =>$tot_reject,
      //                ];
    }
    unset($_SESSION['generate_new']);
    if (isset($data_detail)) {
      $_SESSION['generate_new'] = $data_detail;
      // return $_SESSION['generate_new'];
    }
  }

  function get_generate_detail($id_dealer, $mode, $id_program_md)
  {
    $tipe_kendaraan = $this->get_generate($id_dealer, $id_program_md, 'id_tipe_kendaraan');
    foreach ($tipe_kendaraan->result() as $tk) {
      $get_claim = $this->get_generate($id_dealer, $id_program_md, null, $tk->id_tipe_kendaraan);
      // var_dump($get_claim);
      $tot_gantung = 0;
      $tot_reject = 0;
      $tot_approve = 0;
      foreach ($get_claim->result_array() as $gc) {
        $data_detail[] = ['field' => 'row', 'data' => $gc];
        if ($gc['status'] == 'approved') {
          $tot_approve++;
        } else if ($gc['status'] == 'rejected') {
          $tot_reject++;
        } else {
          $tot_gantung++;
        }
      }
      // $data_detail[]=['field'=>'tot',
      //                 'id_tipe_kendaraan' =>$tk->id_tipe_kendaraan,
      //                 'tot_unit'          =>$get_claim->num_rows(),
      //                 'tot_gantung'       =>$tot_gantung,
      //                 'tot_approve'       =>$tot_approve,
      //                 'tot_reject'        =>$tot_reject,
      //                ];
    }

    if ($mode == 'detail') {
      unset($_SESSION['generate_detail']);
      if (isset($data_detail)) {
        $_SESSION['generate_detail'] = $data_detail;
        // return $_SESSION['generate_new'];
      }
    }
    if ($mode == 'verifikasi') {
      unset($_SESSION['generate_verifikasi']);
      if (isset($data_detail)) {
        $_SESSION['generate_verifikasi'] = $data_detail;
        // return $_SESSION['generate_new'];
      }
    }
  }

  function get_claim($id_claim)
  {
    return $detail = $this->db->query("SELECT *,tr_sales_order.no_mesin, tr_claim_sales_program_detail.perlu_revisi 
                FROM tr_claim_dealer 
                INNER jOIN tr_sales_order on tr_claim_dealer.id_sales_order=tr_sales_order.id_sales_order
                inner join tr_spk on tr_sales_order.no_spk = tr_spk.no_spk
                left join ms_tipe_kendaraan on tr_spk.id_tipe_kendaraan = ms_tipe_kendaraan.id_tipe_kendaraan
                left join ms_warna on tr_spk.id_warna = ms_warna.id_warna
                left join tr_claim_sales_program_detail on tr_claim_sales_program_detail.id_claim_dealer = tr_claim_dealer.id_claim 
                WHERE tr_claim_dealer.id_claim='$id_claim'
              ");
  }

  function get_syarat_claim($id_claim, $id_program_md)
  {
    return $get_syarat = $this->db->query("SELECT tr_claim_dealer_syarat.id_claim,tr_claim_dealer_syarat.id,tr_claim_dealer_syarat.id_syarat_ketentuan,checklist_dealer,checklist_reject_md,tr_claim_dealer_syarat.alasan_reject,   
        tr_sales_program_syarat.syarat_ketentuan,'' as file, tr_claim_dealer_syarat.filename
        FROM tr_claim_dealer_syarat
        INNER join tr_claim_dealer on tr_claim_dealer_syarat.id_claim=tr_claim_dealer.id_claim
        inner join tr_sales_program_syarat on tr_claim_dealer_syarat.id_syarat_ketentuan=tr_sales_program_syarat.id
        WHERE tr_claim_dealer.id_program_md='$id_program_md' 
        AND tr_claim_dealer_syarat.id_claim='$id_claim' ");
  }

  function get_nilai_potongan($id_claim)
  {
    $claim = $this->get_claim($id_claim)->row();
    $jenis_beli = $claim->jenis_beli;

    $nilai_voucher_program = $this->db->query("SELECT *,(ahm_cash+md_cash+add_md_cash) as tot_cash,(ahm_kredit+md_kredit+add_md_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd, (SELECT count(id_program_md)FROM tr_sales_program_gabungan WHERE tr_sales_program_gabungan.id_program_md_gabungan=pmd) as tot_gabungan FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md WHERE tr_sales_program_tipe.id_tipe_kendaraan='$claim->id_tipe_kendaraan' AND id_warna LIKE '%$claim->id_warna%' AND tr_sales_program_tipe.status<>'new' AND tr_sales_program.id_program_md='$claim->id_program_md' ");
    if ($nilai_voucher_program->num_rows() > 0) {

      if ($jenis_beli == 'Cash') {

        $nilai = $nilai_voucher_program->row();

        $nilai_voucher_program = $nilai->tot_cash;
      } elseif ($jenis_beli == 'Kredit') {

        $nilai = $nilai_voucher_program->row();

        $nilai_voucher_program = $nilai->tot_kredit;
      } else {

        $nilai_voucher_program = '';
      }
    } else {

      $nilai_voucher_program = '';
    }



    $nilai_vouch_gab = $this->db->query("SELECT *,(ahm_cash+md_cash+add_md_cash) as tot_cash,(ahm_kredit+md_kredit+add_md_kredit) as tot_kredit,tr_sales_program.id_program_md as pmd FROM tr_sales_program inner JOIN tr_sales_program_tipe on tr_sales_program.id_program_md=tr_sales_program_tipe.id_program_md WHERE tr_sales_program_tipe.id_tipe_kendaraan='$claim->id_tipe_kendaraan' AND id_warna LIKE '%$claim->id_warna%' AND tr_sales_program_tipe.status<>'new' AND tr_sales_program.id_program_md='$claim->program_gabungan' ");

    if ($nilai_vouch_gab->num_rows() > 0) {

      if ($jenis_beli == 'Cash') {

        $nilai = $nilai_vouch_gab->row();

        $nilai_vouch_gab = $nilai->tot_cash;
      } elseif ($jenis_beli == 'Kredit') {

        $nilai = $nilai_vouch_gab->row();

        $nilai_vouch_gab = $nilai->tot_kredit;
      } else {

        $nilai_vouch_gab = '';
      }
    } else {

      $nilai_vouch_gab = '';
    }

    // $nilai_potongan = $nilai_voucher_program+$nilai_vouch_gab;

    return  $nilai_potongan = $nilai_voucher_program;

    //$nilai_potongan = 0;//coba
  }

  public function fetch_data($id_dealer, $start, $length, $search, $order = null, $limit = null, $id_program_md, $id_program_ahm, $filter_status = null, $status_in = null)
  {
    $order_column = array('tr_claim_dealer.id_program_md', 'tr_sales_program.id_program_ahm', 'nama_dealer', 'periode_awal', null, null);
    // $limit     = "LIMIT $start,$length";
    $order_by  = 'ORDER BY tr_claim_dealer.id_program_md desc, nama_dealer ASC';
    $searchs   = "WHERE 1=1 ";
    $having = '';

    if ($search != '') {
      $searchs .= " AND (tr_claim_dealer.id_program_md LIKE '%$search%' 
              OR tr_sales_program.id_program_ahm LIKE '%$search%'
              OR periode_awal LIKE '%$search%'
              OR periode_akhir LIKE '%$search%'
              OR nama_dealer LIKE '%$search%'
          ";
    }else{
      $date = date("Y-m-01");
      $searchs .= "and left(tr_claim_dealer.created_at,7) > DATE_SUB('$date', INTERVAL '2' MONTH)";
    }

    if($id_program_ahm !='' || $id_program_md!='' || $id_dealer!=''){
      $searchs   = "WHERE 1=1 ";
    }

    if ($id_program_md != '') {
      $searchs .= " AND tr_claim_dealer.id_program_md='$id_program_md'";
    }
    if ($id_program_ahm != '') {
      $searchs .= " AND tr_sales_program.id_program_ahm='$id_program_ahm'";
    }
    if ($id_dealer != '') {
      $searchs .= " AND tr_claim_dealer.id_dealer='$id_dealer'";
    }else{
      $searchs   = "WHERE 1=0";
    }

    if ($status_in != null) {
      $searchs .= " AND tr_claim_dealer.status IN($status_in)";
    }
    if ($order != '') {
      $order_clm = $order_column[$order['0']['column']];
      $order_by  = $order['0']['dir'];
      $order_by  = "ORDER BY $order_clm $order_by";
    }
    if ($filter_status != null) {
      $having = " HAVING status_claim_md $filter_status";
    }

    // if ($limit=='y')$limit='f';

    return $this->db->query("SELECT tr_claim_dealer.id_program_md,id_program_ahm,tr_claim_dealer.id_dealer,nama_dealer,periode_awal,periode_akhir,
          (SELECT status from tr_claim_sales_program WHERE id_dealer=tr_claim_dealer.id_dealer AND id_program_md=tr_claim_dealer.id_program_md) AS status_claim_md,
          (SELECT count(id_sales_order) FROM tr_claim_dealer AS tcd WHERE id_dealer=tr_claim_dealer.id_dealer AND id_program_md=tr_claim_dealer.id_program_md AND status_proposal='rejected_by_md') AS tot_revisi
          FROM tr_claim_dealer
          JOIN ms_dealer ON ms_dealer.id_dealer=tr_claim_dealer.id_dealer
          JOIN tr_sales_program ON tr_sales_program.id_program_md=tr_claim_dealer.id_program_md
          $searchs
          GROUP BY tr_claim_dealer.id_program_md,tr_claim_dealer.id_dealer
          $having
          $order_by $limit
         ");
  }

  function cek_gantung($id_claim_sp)
  {
    $gantung = $this->db->query("SELECT count(id_sales_order) AS jum FROM 
        tr_claim_sales_program_detail
        JOIN tr_claim_dealer ON tr_claim_dealer.id_claim=tr_claim_sales_program_detail.id_claim_dealer
        WHERE id_claim_sp='$id_claim_sp' AND tr_claim_dealer.status='ajukan'
        ")->row()->jum;
    $return = $this->db->query("SELECT count(id_sales_order) AS jum FROM 
        tr_claim_sales_program_detail
        JOIN tr_claim_dealer ON tr_claim_dealer.id_claim=tr_claim_sales_program_detail.id_claim_dealer
        WHERE id_claim_sp='$id_claim_sp' AND tr_claim_sales_program_detail.perlu_revisi=1
        ")->row()->jum;
    return $gantung + $return;
  }
}
