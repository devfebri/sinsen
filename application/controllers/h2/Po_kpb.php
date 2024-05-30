<?php
defined('BASEPATH') or exit('No direct script access allowed');
class PO_kpb extends CI_Controller
{
  var $page  = "po_kpb";
  var $folder  = "h2";
  var $title = "PO KPB";

  public function __construct()
  {
    parent::__construct();

    //===== Load Database =====
    $this->load->database();
    $this->load->helper('url');
    //===== Load Model =====
    $this->load->model('m_admin');
    $this->load->model('m_h2_md_claim', 'm_claim');
    //===== Load Library =====
    $this->load->library('upload');

    //---- cek session -------//		
    $name = $this->session->userdata('nama');
    $auth = $this->m_admin->user_auth($this->page, "select");
    $sess = $this->m_admin->sess_auth();
    if ($name == "" or $auth == 'false' or $sess == 'false') {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    }
  }
  protected function template($data)
  {
    $name = $this->session->userdata('nama');
    if ($name == "") {
      echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
    } else {
      $data['id_menu'] = $this->m_admin->getMenu($this->page);
      $data['group']   = $this->session->userdata("group");
      $data['folder']   = $this->folder;
      $this->load->view('template/header', $data);
      $this->load->view('template/aside');
      $this->load->view($this->folder . "/" . $this->page);
      $this->load->view('template/footer');
    }
  }

  public function index()
  {
    $data['isi']   = $this->page;
    $data['title'] = $this->title;
    $data['set']   = "view";
    $this->template($data);
  }
  public function fetch()
  {
    $fetch_data = $this->make_query_fetch();
    $data = array();
    foreach ($fetch_data as $rs) {
      $sub_array = array();
      $status = '';
      $button = '';
      $button_edit = '';
      $btn_approval = "<a class='btn btn-success btn-xs btn-flat' href=\"" . base_url('h2/' . $this->page . '/approved?id=' . $rs->id_po_kpb) . "\">Approval</a>";
      $btn_edit = "<a class='btn btn-primary btn-xs btn-flat' href=\"" . base_url('h2/' . $this->page . '/edit?id=' . $rs->id_po_kpb) . "\">Edit</a>";

      if ($rs->status == 'input') {
        $status = '<label class="label label-primary">Input</label>';
        // if (can_access($this->page, 'can_update'))  
        $button .= $btn_approval;
        $button_edit .= $btn_edit;
      } elseif ($rs->status == 'approved') {
        $status = '<label class="label label-success">Approved</label>';
      } elseif ($rs->status == 'rejected') {
        $status = '<label class="label label-danger">Rejected</label>';
      }

      $sub_array[] = '<a href="' . $this->folder . '/' . $this->page . '/detail?id=' . $rs->id_po_kpb . '">' . $rs->id_po_kpb . '</a>';
      $sub_array[] = $rs->tgl_po_kpb;
      $sub_array[] = $rs->kode_dealer_md;
      $sub_array[] = $rs->nama_dealer;
      $sub_array[] = $rs->tot_qty;
      $sub_array[] = mata_uang_rp($rs->grand_total);
      $sub_array[] = $status;
      $sub_array[] = $button_edit .' | '.$button;
      $data[]      = $sub_array;
    }
    $output = array(
      "draw"            =>     intval($_POST["draw"]),
      "recordsFiltered" =>     $this->make_query_fetch(true),
      "data"            =>     $data
    );
    echo json_encode($output);
  }

  public function make_query_fetch($recordsFiltered = null)
  {
    $start        = $this->input->post('start');
    $length       = $this->input->post('length');
    $limit        = "LIMIT $start, $length";

    if ($recordsFiltered == true) $limit = '';

    $filter = [
      'limit'  => $limit,
      'order'  => isset($_POST['order']) ? $_POST["order"] : '',
      'order_column' => 'view',
      'search' => $this->input->post('search')['value']
    ];
    if (isset($_POST['id_dealer'])) {
      $filter['id_dealer'] = $_POST['id_dealer'];
    }
    if (isset($_POST['tgl_po_kpb'])) {
      $filter['tgl_po_kpb'] = $_POST['tgl_po_kpb'];
    }
    if (isset($_POST['status'])) {
      $filter['status'] = $_POST['status'];
    }
    if ($recordsFiltered == true) {
      return $this->m_claim->getPOKPB($filter)->num_rows();
    } else {
      return $this->m_claim->getPOKPB($filter)->result();
    }
  }

  public function add()
  {
    $data['isi']   = $this->page;
    $data['title'] = 'Insert ' . $this->title;
    $data['mode']  = 'insert';
    $data['set']   = "form";
    $this->template($data);
  }
  public function approved()
  {
    $id = $this->input->get('id');
    $filter = ['id_po_kpb' => $id];
    $row = $this->m_claim->getPOKPB($filter);
    // send_json($row->row());
    if ($row->num_rows() > 0) {
      $data['isi']   = $this->page;
      $data['title'] = 'Approved / Rejected ' . $this->title;
      $data['mode']  = 'approved';
      $data['set']   = "form";
      $data['row'] = $row->row();
      $data['details'] = $this->m_claim->getPOKPBDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan'] = "Data tidak ditemukan !";
      $_SESSION['tipe']  = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . '/' . $this->isi) . "'>";
    }
  }

  function save_approval()
  {
    $post       = $this->input->post();
    $update = [
      'approval_at' => waktu_full(),
      'approval_by' => user()->id_user,
      'status' => $post['set']
    ];
    // $tes = ['update' => $update];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->update('tr_po_kpb', $update, ['id_po_kpb' => $post['id_po_kpb']]);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url('h2/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been processed successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }

  function generate($post = null)
  {
    $this->load->model('h3_md_ms_diskon_oli_kpb_model', 'm_disc');
    if ($post == null) {
      $json = true;
      $post = $this->input->post();
    }
    $post['periode_servis']                 = true;
    $post['group_by_tipe_kendaraan_part']   = true;
    $post['id_po_kpb_null']                 = true;
    $post['status']                         = 'approved';
    $post['part_not_null']                  = true;
    if (isset($post['all_data'])) {
      unset($post['group_by_tipe_kendaraan_part']);
    }
    $data = $this->m_claim->getClaimGenerated($post);
    $result = [];
    foreach ($data->result() as $rs) {
      $disc_value = 0;
      $disc_type = '';
      $harga_setelah_diskon = $rs->het;
      $disc = $this->m_disc->get_diskon_oli_kpb($rs->id_part, $rs->id_tipe_kendaraan);
      if (isset($disc['diskon_value'])) {
        $disc_value = $disc['diskon_value'];
      }
      if (isset($disc['tipe_diskon'])) {
        $disc_type = $disc['tipe_diskon'];
        if (strtolower($disc_type) == 'rupiah') {
          $harga_setelah_diskon = $harga_setelah_diskon - $disc_value;
        } elseif (strtolower($disc_type) == 'persen') {
          $disc_rp = $harga_setelah_diskon * ($disc_value / 100);
          $harga_setelah_diskon = $harga_setelah_diskon - $disc_rp;
        }
      } else {
        $disc_type = 'rupiah';
        $disc_value = $rs->het - $rs->harga_material;
        // $disc_value = 0;
        // send_json($rs);
        $harga_setelah_diskon = $rs->het - $disc_value;
      }

      $total = $harga_setelah_diskon * $rs->qty;
      $result[] = [
        'diskon' => $disc_value,
        'diskon_tipe' => $disc_type,
        'harga_material' => $rs->het,
        'harga_setelah_diskon' => $harga_setelah_diskon,
        'id_claim_kpb' => $rs->id_claim_kpb,
        'id_detail' => $rs->id_detail,
        'id_part' => $rs->id_part,
        'id_tipe_kendaraan' => $rs->id_tipe_kendaraan,
        'km_service' => $rs->km_service,
        'kpb_ke' => $rs->kpb_ke,
        'nama_part' => $rs->nama_part,
        'no_kpb' => $rs->no_kpb,
        'no_mesin' => $rs->no_mesin,
        'no_mesin_5' => $rs->no_mesin_5,
        'no_rangka' => $rs->no_rangka,
        'qty' => $rs->qty,
        'tgl_beli_smh' => $rs->tgl_beli_smh,
        'tgl_service' => $rs->tgl_service,
        'tipe_ahm' => $rs->tipe_ahm,
        'total' => $total,
      ];
    }
    if ($data->num_rows() == 0) {
      $response = ['status' => 0, 'pesan' => 'Data tidak ditemukan !'];
    } else {
      $response = ['status' => 'sukses', 'data' => $result];
    }
    if (isset($json)) {
      send_json($response);
    } else {
      return $result;
    }
  }

  function save()
  {
    $post       = $this->input->post();
    $details = $this->generate($post);
    $id_po_kpb = $this->m_claim->get_id_po_kpb();
    $insert = [
      'id_po_kpb'  => $id_po_kpb,
      'id_dealer'  => $post['id_dealer'],
      'start_date' => $post['start_date'],
      'end_date'   => $post['end_date'],
      'tgl_po_kpb' => tanggal(),
      'created_at' => waktu_full(),
      'created_by' => user()->id_user,
      'status' => 'input'
    ];
    $tot_qty = 0;
    $total = 0;
    foreach ($details as $pr) {
      $part = $this->db->query("SELECT id_part_int FROM ms_part WHERE id_part='{$pr['id_part']}'")->row();
      $ins_detail[] = [
        'id_po_kpb' => $id_po_kpb,
        'id_tipe_kendaraan' => $pr['id_tipe_kendaraan'],
        'qty' => $pr['qty'],
        'harga_material' => $pr['harga_material'],
        'diskon' => $pr['diskon'],
        'tipe_diskon' => $pr['diskon_tipe'],
        'id_part' => $pr['id_part'],
        'id_part_int' => $part->id_part_int,
        'total' => $pr['total'],
      ];
      $tot_qty += $pr['qty'];
      $total += $pr['total'];
    }
    // $ppn = 0;
    $ppn = $total * getPPN(0.1);
    $grand_total = $ppn + $total;
    $insert['tot_qty'] = $tot_qty;
    $insert['ppn'] = $ppn;
    $insert['total'] = $total;
    $insert['grand_total'] = $grand_total;
    $filter = [
      'id_dealer' => $post['id_dealer'],
      'start_date' => $post['start_date'],
      'end_date' => $post['end_date'],
      'all_data'=>true
    ];
    $details = $this->generate($filter);
    foreach ($details as $rs) {
      $upd_claim[] = ['id_detail' => $rs['id_detail'], 'id_po_kpb' => $id_po_kpb];
    }
    $tes = ['insert' => $insert, 'ins_detail' => $ins_detail, 'upd_claim' => $upd_claim];
    // send_json($tes);
    $this->db->trans_begin();
    $this->db->insert('tr_po_kpb', $insert);
    $this->db->insert_batch('tr_po_kpb_detail', $ins_detail);
    $this->db->update_batch('tr_claim_kpb_generate_detail', $upd_claim, 'id_detail');
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url('h2/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }

  public function detail()
  {
    $id = $this->input->get('id');
    $filter = ['id_po_kpb' => $id];
    $row = $this->m_claim->getPOKPB($filter);
    if ($row->num_rows() > 0) {
      $data['isi']   = $this->page;
      $data['title'] = 'Detail ' . $this->title;
      $data['mode']  = 'detail';
      $data['set']   = "form";
      $data['row'] = $row->row();
      $data['details'] = $this->m_claim->getPOKPBDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan'] = "Data tidak ditemukan !";
      $_SESSION['tipe']  = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . '/' . $this->isi) . "'>";
    }
  }

  public function edit()
  {
    $id = $this->input->get('id');
    $filter = ['id_po_kpb' => $id];
    $row = $this->m_claim->getPOKPB($filter);
    if ($row->num_rows() > 0) {
      $data['isi']   = $this->page;
      $data['title'] = 'Edit Data ' . $this->title;
      $data['mode']  = 'edit';
      $data['set']   = "form";
      $data['row'] = $row->row();
      $data['details'] = $this->m_claim->getPOKPBDetail($filter)->result();
      // send_json($data);
      $this->template($data);
    } else {
      $_SESSION['pesan'] = "Data tidak ditemukan !";
      $_SESSION['tipe']  = "danger";
      echo "<meta http-equiv='refresh' content='0; url=" . base_url($this->folder . '/' . $this->isi) . "'>";
    }
  }
  
  function update()
  {
    $post       = $this->input->post();
    $details = $this->generate($post);
    $id_po_kpb = $this->input->post('id_po_kpb');
    $tot_qty = 0;
    $total = 0;
    foreach ($post['details'] as $pr) {
      $part = $this->db->query("SELECT id_part_int FROM ms_part WHERE id_part='{$pr['id_part']}'")->row();
      
      if($pr['qty'] != 0){
        $update_detail[] = [
          'id_po_kpb' => $id_po_kpb,
          'id_tipe_kendaraan' => $pr['id_tipe_kendaraan'],
          'id_detail' => $pr['id_detail'],
          'qty' => $pr['qty'],
          'harga_material' => $pr['harga_material'],
          'diskon' => $pr['harga_material'] - $pr['harga_setelah_diskon'],
          'tipe_diskon' => $pr['diskon_tipe'],
          'id_part' => $pr['id_part'],
          'id_part_int' => $part->id_part_int,
          'total' => $pr['qty'] *$pr['harga_setelah_diskon'],
        ];
        $tot_qty += $pr['qty'];
        $total += $pr['qty'] *$pr['harga_setelah_diskon'];
      }else{
        $this->db->where('id_detail', $pr['id_detail']);
        $this->db->delete('tr_po_kpb_detail');
      }
    }
    // $ppn = 0;
    // $total = $total;
    $ppn = $total * getPPN(0.1);
    $grand_total = $ppn + $total;
    $this->db->trans_begin();
    $this->db->update_batch('tr_po_kpb_detail', $update_detail, 'id_detail');

    $this->db->set('updated_at', waktu_full());
    $this->db->set('updated_by', user()->id_user);
    $this->db->set('tot_qty', $tot_qty);

    $this->db->set('total', $total);
    $this->db->set('ppn', $ppn);
    $this->db->set('grand_total', $grand_total);

    $this->db->where('id_po_kpb', $id_po_kpb);
    $this->db->update('tr_po_kpb');
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $rsp = [
        'status' => 'error',
        'pesan' => ' Something went wrong !'
      ];
    } else {
      $this->db->trans_commit();
      $rsp = [
        'status' => 'sukses',
        'link' => base_url('h2/' . $this->page)
      ];
      $_SESSION['pesan']   = "Data has been saved successfully";
      $_SESSION['tipe']   = "success";
    }
    send_json($rsp);
  }

  public function set_id_part_po_kpb()
	{
		$this->db->trans_start();

		$id_part = $this->input->get('id_part');
		$part = $this->db
			->from('ms_part')
			->where('id_part', $id_part)
			->limit(1)
			->get()->row_array();

		$this->db
			->set('id_part', $id_part)
			->set('id_part', $part['id_part_int'])
			->where('id_po_kpb', $this->input->get('id'))
			->update('tr_po_kpb_detail');

		$this->db->trans_complete();
	}
}
