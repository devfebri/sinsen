<?php



class Api_crm_post_model extends CI_Model
{

  public function __construct()
  {
    $this->load->helper('api');
    $this->load->model('m_admin');
    $this->load->model('m_h1_dealer_pemesanan', 'dp_m');
    $this->db_crm = $this->load->database('db_crm', true);
  }

  function api_4_stageId_7_8_9($send_data)
  {
    return send_api_post($send_data, 'nms', 'mdms', 'api_4');
  }

  function api_4_stageId_10($no_spk)
  {
    // 10. Create SPK NMS
    // cek no spk
    $spk = $this->db->query("SELECT spk.*,pid.id_indent,pid.tgl
          FROM tr_spk spk
          LEFT JOIN tr_po_dealer_indent pid ON pid.id_spk=spk.no_spk
          WHERE no_spk='$no_spk'")->row_array();
    //Cek Prospek By ID Customer
    $prp = $this->db->query("SELECT id_prospek,id_flp_md,kode_dealer_md FROM tr_prospek 
    JOIN ms_dealer dl ON dl.id_dealer=tr_prospek.id_dealer
    WHERE id_customer='{$spk['id_customer']}'")->row();
    // send_json($prp);

    //Cek Nama Sales Program By
    $nama_promo = NULL;
    if ($spk['program_umum'] != '' || $spk['program_umum'] != NULL) {
      $spgr = $this->db->query("SELECT judul_kegiatan FROM tr_sales_program WHERE id_program_md='{$spk['program_umum']}'")->row();
      $nama_promo = $spgr == NULL ? NULL : $spgr->judul_kegiatan;
    }

    $leads = [
      'idSPK'                => $spk['no_spk'],
      'kodeTypeUnitDeal'     => $spk['id_tipe_kendaraan'],
      'kodeWarnaUnitDeal'    => $spk['id_warna'],
      'deskripsiPromoDeal'   => $nama_promo,
      'metodePembayaranDeal' => strtolower($spk['jenis_beli']) == 'kredit' ? 2 : 1,
      'kodeLeasingDeal'      => $spk['id_finance_company'],
      'id_user'              => $this->session->userdata('id_user'),
    ];

    $fol_up = [
      'tglFollowUp'             => $spk['tgl_spk'],
      'kodeHasilStatusFollowUp' => 3, //Deal,
      'id_status_fu'            => 8, //Terhubung
      'pic'                     => $prp->id_flp_md,
    ];

    $send_data = [
      'stageId'   => 10, //10. Create SPK NMS
      'idProspek' => $prp->id_prospek,
      'assignedDealer' => $prp->kode_dealer_md,
      'leads'     => $leads,
      'fol_up'    => $fol_up,
    ];
    send_api_post($send_data, 'nms', 'mdms', 'api_4');

    //Cek Apakah SPK Ini Indent
    if ($spk['id_indent'] != NULL) {
      $this->api_4_stageId_11($spk['no_spk']);
    }
  }

  function api_4_stageId_11($no_spk)
  {
    $filter['no_spk'] = $no_spk;
    $indent = $this->dp_m->getIndent($filter)->row_array();
    $spk  = $this->db->get_where('tr_spk', $filter)->row_array();
    // send_json($spk);
    // 11. Create Indent Form
    //Cek Prospek By ID Customer
    $prp = $this->db->query("SELECT id_prospek,id_flp_md,kode_dealer_md,leads_id FROM tr_prospek 
    JOIN ms_dealer dl ON dl.id_dealer=tr_prospek.id_dealer
    WHERE id_customer='{$spk['id_customer']}'")->row();

    //Cek Nama Sales Program By
    $nama_promo = NULL;
    if ($spk['program_umum'] != '' || $spk['program_umum'] != NULL) {
      $spgr = $this->db->query("SELECT judul_kegiatan FROM tr_sales_program WHERE id_program_md='{$spk['program_umum']}'")->row();
      $nama_promo = $spgr == NULL ? NULL : $spgr->judul_kegiatan;
    }

    $leads = [
      'idSPK'                => $indent['id_spk'],
      'kodeIndent'           => $indent['id_indent'],
      'kodeTypeUnitDeal'     => $spk['id_tipe_kendaraan'],
      'kodeWarnaUnitDeal'    => $spk['id_warna'],
      'deskripsiPromoDeal'   => $nama_promo,
      'metodePembayaranDeal' => strtolower($spk['jenis_beli']) == 'kredit' ? 2 : 1,
      'kodeLeasingDeal'      => $spk['id_finance_company'],
      'id_user'              => $this->session->userdata('id_user'),
    ];

    $fol_up = [
      'tglFollowUp'             => $indent['tgl'],
      'kodeHasilStatusFollowUp' => 3, //Deal,
      'id_status_fu'            => 8, //Terhubung
      'pic'                     => $prp->id_flp_md,
      'tglNextFollowUp'         => $indent['tgl']
    ];

    $send_data = [
      'stageId'        => 11, //11. Create Indent Form
      'idProspek'      => $prp->id_prospek,
      'leads_id'      => $prp->leads_id,
      'assignedDealer' => $prp->kode_dealer_md,
      'leads'          => $leads,
      'fol_up'         => $fol_up,
    ];
    send_api_post($send_data, 'nms', 'mdms', 'api_4');
  }

  function api_4_stageId_12($no_spk, $path_invoice = '', $no_invoice = '')
  {
    //Cek Prospek By ID Customer
    $so = $this->db->query("SELECT kode_dealer_md,so.no_spk,id_sales_order,LEFT(so.created_at,10) tgl_sales_order,jenis_beli,spk.id_tipe_kendaraan,spk.id_warna,spk.program_umum,(SELECT id_flp_md FROM tr_prospek WHERE id_customer=spk.id_customer LIMIT 1) id_flp_md,so.no_rangka,spk.id_finance_company,tk.tipe_ahm,warna.warna,spk.total_bayar,spk.jenis_beli,spk.uang_muka,spk.tenor,spk.nama_konsumen,so.no_invoice,prospek.id_prospek,prospek.input_from
    FROM tr_sales_order so
    JOIN tr_spk spk ON spk.no_spk=so.no_spk
    JOIN ms_dealer dl ON dl.id_dealer=so.id_dealer
    JOIN ms_tipe_kendaraan tk ON tk.id_tipe_kendaraan=spk.id_tipe_kendaraan
    JOIN ms_warna warna ON warna.id_warna=spk.id_warna
    JOIN tr_prospek prospek ON prospek.id_customer=spk.id_customer
    WHERE so.no_spk='$no_spk'")->row();
    if ($so->input_from == 'sinsengo') {
      $get_leads = $this->db_crm->get_where("leads", ['idProspek' => $so->id_prospek])->row();
      if ($get_leads != null) {
        $array_post = [
          'AppsOrderNumber'   => $get_leads->sourceRefID,
          'DmsOrderNumber'    => $get_leads->batchID,
          'OrderStatus' => 1,
          'AhmSku' => $so->id_tipe_kendaraan,
          'ProductType' => $so->tipe_ahm,
          'ProductColor' => $so->id_warna,
          'ProductColorCode' => $so->warna,
          'Price' => $so->total_bayar,
          'Qty' => 1,
          'PaymentMethod' => $so->jenis_beli == 'Cash' ? 'tunai' : 'kredit',
          'Dp' => (int)$so->uang_muka,
          'Tenor' => (int)$so->tenor,
          'SpkNumber' => $so->no_spk,
          'CustomerName' => $so->nama_konsumen,
          'PendingNote' => '',
          'IsCancel' => "0",
          'CancelationNote' => '',
          'InvoiceNumber' => $so->no_invoice == null ? $no_invoice : $so->no_invoice,
          'InvoiceUrl' => base_url($path_invoice),
        ];
        $this->load->library("mokita");
        $this->mokita->h1_final_process($array_post);
      }
    }

    //Cek Nama Sales Program By
    $nama_promo = NULL;
    if ((string)$so->program_umum != '') {
      $spgr = $this->db->query("SELECT judul_kegiatan FROM tr_sales_program WHERE id_program_md='$so->program_umum'")->row();
      $nama_promo = $spgr == NULL ? NULL : $spgr->judul_kegiatan;
    }

    $leads = [
      'idSPK'                => $so->no_spk,
      'kodeTypeUnitDeal'     => $so->id_tipe_kendaraan,
      'kodeWarnaUnitDeal'    => $so->id_warna,
      'deskripsiPromoDeal'   => $nama_promo,
      'metodePembayaranDeal' => strtolower($so->jenis_beli) == 'kredit' ? 2 : 1,
      'kodeLeasingDeal'      => $so->id_finance_company,
      'frameNo'              => $so->no_rangka,
      'id_user'              => $this->session->userdata('id_user'),
    ];

    $fol_up = [
      'tglFollowUp'             => $so->tgl_sales_order,
      'kodeHasilStatusFollowUp' => 3, //Deal,
      'id_status_fu'            => 8, //Terhubung
      'pic'                     => $so->id_flp_md,
    ];

    $send_data = [
      'stageId'        => 12, //12. Create Sales Order (after SSU)
      'idSPK'          => $so->no_spk,
      'assignedDealer' => $so->kode_dealer_md,
      'leads'          => $leads,
      'fol_up'         => $fol_up,
    ];

    send_api_post($send_data, 'nms', 'mdms', 'api_4');
  }
}
