<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_stock_opname extends Honda_Controller
{
    public $folder = "dealer";
    public $page   = "h3_dealer_stock_opname";
    public $title  = "Stock Opname";

    public function __construct()
    {
        parent::__construct();
        //---- cek session -------//
        

        //===== Load Database =====
        $this->load->database();
        $this->load->helper('url');
        //===== Load Model =====
        $this->load->library('form_validation');
        $this->load->model('m_admin');
        $this->load->model('h3_dealer_stock_model', 'stock');
        $this->load->model('ms_part_model', 'part');
        $this->load->model('h3_dealer_stock_opname_model', 'stock_opname');
        $this->load->model('h3_dealer_stock_opname_parts_model', 'stock_opname_parts');
        $this->load->model('h3_dealer_member_stock_opname_model', 'member_stock_opname');
        $this->load->model('h3_dealer_set_up_schedule_stock_opname_model', 'schedule');
        $this->load->model('notifikasi_model', 'notifikasi');
        $this->load->model('h3_dealer_transaksi_stok_model', 'transaksi_stok');
        $name = $this->session->userdata('nama');
        if ($name=="") {
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
        }
        
    }    

    public function index()
    {
        $data['set']	= "index";
        $data['stock_opname'] = $this->stock_opname->get([
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);
        $this->template($data);
    }

    function getDataStockOpname()
    {
        $list = $this->stock_opname->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $field) {
            $query = $this->db->query("SELECT id_stock_opname, status, document_berita_acara FROM tr_h3_dealer_stock_opname WHERE id_schedule = '$field->id_schedule'")->row();
            
            $view = '<a id="'.$field->id_schedule.'" href="dealer/h3_dealer_stock_opname/detail?id_schedule='.$field->id_schedule.'" type="button" class="btn btn-primary btn-xs">Detail</a> ';
            
            $pdf = '';
            if(isset($query)){
                $id_stock_opname = $query->id_stock_opname;
                $status = $query->status;
                if($query->status=='Closed' || $query->status=='Approval to Branch Manager' || $query->status=='Recount' || $query->status=='Open'){
                    $start_opname = '| <a id="'.$query->id_stock_opname.'" href="dealer/h3_dealer_stock_opname/detail_so?id='.$query->id_stock_opname.'" type="button" class="btn btn-info btn-xs">Detail Opname</a>';
                }else{
                    $start_opname = '| <a id="'.$query->id_stock_opname.'" href="dealer/h3_dealer_stock_opname/edit_so?id='.$query->id_stock_opname.'" type="button" class="btn btn-warning btn-xs">Edit Opname</a>';
                } 
                if($query->document_berita_acara !='' or $query->document_berita_acara != NULL){
                    $pdf = ' | <a href="' . base_url('uploads/berita_acara_stock_opname/' . $query->document_berita_acara) . '" target="_blank" type="button" class="btn btn-warning btn-xs">Berita Acara</a>';
                }
            }else{
                $id_stock_opname = '';
                $status = '';
                $start_opname = '| <a id="'.$field->id_schedule.'" href="dealer/h3_dealer_stock_opname/add_so?id_schedule='.$field->id_schedule.'" type="button" class="btn btn-success btn-xs">Start Opname</a>';
            }
           
           
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->id_schedule;
            $row[] = $id_stock_opname;
            $row[] = $field->jenis_schedule;
            $row[] = $field->date_opname;
            $row[] = $field->date_opname_end;
            $row[] = $status;
            // $button[] = $button;
            $row[] = $view . $start_opname . $pdf;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->stock_opname->count_all(),
            "recordsFiltered" => $this->stock_opname->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function add()
    {
        
        // if($this->m_admin->cari_dealer() != 70){
        //     echo 'You dont have access!';die;
        // }

        $user_dealer = $this->m_admin->cari_dealer();
        $dealer=array('103', '95','104','105','105','37','94','101','77','45','51','105','37','18','101','80','22',
        '66','40','94','104','95','3','98','128','112');

        if(in_array($user_dealer,$dealer)){
            echo 'You dont have access!';die;
        }
        
        $data['mode']    = 'insert';
        $data['set']     = "form_schedule";
        $this->template($data);
    }

    public function validate(){
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('jenis_schedule', 'Jenis Schedule', 'required');
        $this->form_validation->set_rules('cycle_days', 'Cycle Days', 'required|numeric');
        $this->form_validation->set_rules('reminder_days', 'Reminder Days', 'required|numeric');
        $this->form_validation->set_rules('date_opname', 'Date Opname', 'required');
        // if (!$this->form_validation->run()){
        //     $keys = [
        //         'jenis_schedule',
        //         'cycle_days',
        //         'reminder_days',
        //         'date_opname',
        //     ];
        //     $data = [];
        //     foreach ($keys as $key) {
        //         $data[$key] = form_error($key) == '' ? null : form_error($key);
        //     }

        //     $this->output->set_status_header(400);
        //     send_json($data);
        // }

        if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
    }

    public function save()
    {

        $this->validate();

        $this->db->trans_start();
        $data = array_merge($this->input->post(), [
            'id_schedule' => $this->schedule->generateID(),
        ]);
        $this->schedule->insert($data);
       
        // $insert_data_stock_opname = [
        //     'id_schedule' => $data['id_schedule']
        // ];
        // $this->db->insert('tr_h3_dealer_stock_opname', $insert_data_stock_opname);

        $this->db->trans_complete();

        if($this->db->trans_status()){
            $result = $this->schedule->find($data['id_schedule'], 'id_schedule');
            send_json($result);
        }else{
            $this->output->set_status_header(500);
        }
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form_schedule";

        $data['schedule'] = $this->db
        ->from('ms_set_up_schedule_stock_opname as s')
        ->where('s.id_schedule', $this->input->get('id_schedule'))
        ->limit(1)
        ->get()->row();

        $this->template($data);
    }

    public function update()
    {
        $this->validate();

        $this->db->trans_start();
        $data = $this->input->post();
        unset($data['id_schedule']);

        $this->schedule->update($data, $this->input->post(['id_schedule']));
        $this->db->trans_complete();

        if($this->db->trans_status()){
            $result = $this->schedule->get($this->input->post(['id_schedule']), true);
            send_json($result);
        }else{
            $this->output->set_status_header(500);
        }
    }

    public function edit()
    {
        $data['mode']  = 'edit';
        $data['set']   = "form_schedule";

        $data['schedule'] = $this->db
        ->from('ms_set_up_schedule_stock_opname as s')
        ->where('s.id_schedule', $this->input->get('id_schedule'))
        ->limit(1)
        ->get()->row();

        $this->template($data);
    }

    public function add_so()
    {
        $data['mode']    = 'insert';
        $data['set']     = "form";
        $id_user = $this->session->userdata("id_user");
		$data['id_user_group'] = $this->db->query("SELECT id_user_group FROM ms_user WHERE id_user = '$id_user'")->row();
        $id_schedule = $this->input->get('id_schedule');
        $stock_opname = $this->stock_opname->find($this->input->get('id'), 'id_stock_opname');
        $data['schedule_so'] = $this->db
        ->from('ms_set_up_schedule_stock_opname as s')
        ->where('s.id_dealer', $this->m_admin->cari_dealer())
        ->limit(1)
        // ->where('s.jenis_schedule', 'Stock Opname Parts')
        ->where('id_schedule',$id_schedule)
        ->get()->row();
        $data['stock_opname'] = $stock_opname;
        $this->template($data);
    }


    public function get_stock_in_warehouse(){
        $result = $this->db
        ->select('ds.*')
        ->select('p.nama_part')
        ->select('r.unit')
        ->from('ms_h3_dealer_stock as ds')
        ->join('ms_part as p', 'p.id_part_int = ds.id_part_int')
        ->join('ms_lokasi_rak_bin as r', '(r.id_gudang = ds.id_gudang and r.id_rak = ds.id_rak)')
        ->where('ds.id_dealer', $this->m_admin->cari_dealer())
        ->where('ds.id_gudang', $this->input->post('id_gudang'))
        ->order_by('ds.id_rak', 'asc')
        ->get()->result();

        send_json($result);
    }

    public function validate_stock_opname(){
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('pic', 'PIC', 'required');
        $this->form_validation->set_rules('id_gudang', 'Gudang', 'required');
       
        if (!$this->form_validation->run()) {
			send_json([
				'error_type' => 'validation_error',
				'message' => 'Data tidak valid, ISI NAMA PIC DAN GUDANG',
				'errors' => $this->form_validation->error_array()
			], 422);
		}
    }


    public function save_so()
    {
        $this->validate_stock_opname();
        $this->db->trans_start();
        
        // $this->form_validation->set_rules('pic','NIK PIC','required');
		// $this->form_validation->set_rules('id_gudang','Gudang','required');
        // if($this->form_validation->run()==true){
        //     $master_data = array_merge($this->input->post(['tipe', 'id_gudang', 'date_opname', 'date_opname_end','id_schedule']), [
        //         'id_stock_opname' => $this->stock_opname->generateID(),
        //         'id_dealer' => $this->m_admin->cari_dealer(),
        //         'created_by' => $this->session->userdata('id_user'),
        //         'id_pic' => $this->input->post('pic'),
        //     ]);

        //     $items_data = $this->getOnly(true, $this->input->post('parts'), [
        //         'id_stock_opname' => $master_data['id_stock_opname']
        //     ]);
    
        //     // var_dump("Test");
        //     // die();
        //     foreach($items_data as $key=>$val){
        //         $freeze = array('freeze' => '1');
        //         // $where2= array('id_customer'=>$value->id_customer);
        //         $this->db->where('id_part',$val['id_part']);
        //         $this->db->where('id_dealer',$this->m_admin->cari_dealer());
        //         $this->db->update('ms_h3_dealer_stock',$freeze);
        //     }
        //     $this->stock_opname->insert($master_data);
        //     $this->stock_opname_parts->insert_batch($items_data);
        //     $this->db->trans_complete();
    
    
    
        //     if ($this->db->trans_status()) {
        //         $result = $this->stock_opname->find($master_data['id_stock_opname'], 'id_stock_opname');
        //         send_json($result);
        //     } else {
        //         $this->output->set_status_header(500);
        //     }
        // }elseif($this->input->post('id_gudang')=='' || $this->input->post('pic')==''){
        //     // $_SESSION['pesan'] 	= "Mohon diisi id Gudang";
        //     // $_SESSION['tipe'] 	= "danger";
        //     $this->output->set_status_header(400);
        // }
        $master_data = array_merge($this->input->post(['tipe', 'id_gudang', 'date_opname', 'date_opname_end','id_schedule']), [
            'id_stock_opname' => $this->stock_opname->generateID(),
            'id_dealer' => $this->m_admin->cari_dealer(),
            'created_by' => $this->session->userdata('id_user'),
            'id_pic' => $this->input->post('pic'),
        ]);

        $items_data = $this->getOnly(true, $this->input->post('parts'), [
            'id_stock_opname' => $master_data['id_stock_opname']
        ]);
    
        foreach($items_data as $key=>$val){
            $freeze = array('freeze' => '1');
            // $where2= array('id_customer'=>$value->id_customer);
            $this->db->where('id_part',$val['id_part']);
            $this->db->where('id_dealer',$this->m_admin->cari_dealer());
            $this->db->update('ms_h3_dealer_stock',$freeze);
        }
        $this->stock_opname->insert($master_data);
        $this->stock_opname_parts->insert_batch($items_data);
        $this->db->trans_complete();
    
        if ($this->db->trans_status()) {
            $result = $this->stock_opname->find($master_data['id_stock_opname'], 'id_stock_opname');
            send_json($result);
        } else {
            $this->output->set_status_header(500);
        }

    }

    public function detail_so()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";
        $id_user = $this->session->userdata("id_user");
		$data['id_user_group'] = $this->db->query("SELECT id_user_group FROM ms_user WHERE id_user = '$id_user'")->row();
        $stock_opname = $this->stock_opname->find($this->input->get('id'), 'id_stock_opname');
        $data['gudang'] = $this->db->from('ms_gudang_h23 as g')->where('g.id_gudang', $stock_opname->id_gudang)->get()->row();
        $data['pic'] = $this->db->from('ms_karyawan_dealer as k')->where('k.id_karyawan_dealer', $stock_opname->id_pic)->get()->row();
        $data['members'] = $this->db
        ->from('tr_h3_dealer_member_stock_opname as mso')
        ->join('ms_karyawan_dealer as k', 'k.id_karyawan_dealer = mso.id_member')
        ->where('mso.id_stock_opname', $this->input->get('id'))
        ->order_by('mso.dari', 'asc')
        ->get()->result();

        $data['parts'] = $this->db
        ->select('sop.*')
        ->select('p.nama_part')
        ->select('r.unit')
        ->select('ifnull(sop.stock_aktual, 0) as stock_aktual')
        ->select('(CASE WHEN sop.stock_aktual>sop.stock then sop.stock_aktual-sop.stock else sop.stock-sop.stock_aktual end) as qty_diff')
        ->from('tr_h3_dealer_stock_opname_parts as sop')
        ->join('ms_part as p', 'p.id_part = sop.id_part')
        ->join('ms_lokasi_rak_bin as r', '(r.id_gudang = sop.id_gudang and r.id_rak = sop.id_rak)')
        ->where('sop.id_stock_opname', $stock_opname->id_stock_opname)
        ->get()->result();

        $data['schedule_so'] = $this->db
        ->select('sso.id_schedule,sso.date_opname,sso.date_opname_end,sso.jenis_schedule')
        ->from('ms_set_up_schedule_stock_opname as sso')
        ->join('tr_h3_dealer_stock_opname as so','so.id_schedule=sso.id_schedule')
        ->where('so.id_stock_opname', $stock_opname->id_stock_opname)
        ->get()->row();
        
        $data['summary'] = $this->db
        ->select('sop.*')
        ->select('p.nama_part')
        ->select('r.unit')
        ->select('sum(sop.stock) as stock')
        ->select('ifnull(sum(sop.stock_aktual), 0) as stock_aktual')
        ->from('tr_h3_dealer_stock_opname_parts as sop')
        ->join('ms_part as p', 'p.id_part = sop.id_part')
        ->join('ms_lokasi_rak_bin as r', '(r.id_gudang = sop.id_gudang and r.id_rak = sop.id_rak)')
        ->where('sop.id_stock_opname', $stock_opname->id_stock_opname)
        ->group_by('sop.id_part')
        ->get()->result();

        $data['stock_opname'] = $stock_opname;
        
        $this->template($data);
    }

    public function update_stock_aktual(){
        $this->db->trans_start();
        $this->stock_opname_parts->update($this->input->post(['stock_aktual']), $this->input->post([
            'id_part', 'id_rak', 'id_gudang', 'id_stock_opname'
        ]));
        $this->db->trans_complete();

        if($this->db->trans_status()){
            $this->output->set_status_header(200);
        }else{
          $this->output->set_status_header(500);
        }
    }

    public function edit_so()
    {
        $data['set']	= "form";
        $data['mode']  = 'edit';
        $id_user = $this->session->userdata("id_user");
		$data['id_user_group'] = $this->db->query("SELECT id_user_group FROM ms_user WHERE id_user = '$id_user'")->row();
        $stock_opname = $this->stock_opname->find($this->input->get('id'), 'id_stock_opname');
        $data['gudang'] = $this->db->from('ms_gudang_h23 as g')->where('g.id_gudang', $stock_opname->id_gudang)->get()->row();
        $data['pic'] = $this->db->from('ms_karyawan_dealer as k')->where('k.id_karyawan_dealer', $stock_opname->id_pic)->get()->row();
        $data['members'] = $this->db
        ->from('tr_h3_dealer_member_stock_opname as mso')
        ->join('ms_karyawan_dealer as k', 'k.id_karyawan_dealer = mso.id_member')
        ->where('mso.id_stock_opname', $this->input->get('id'))
        ->order_by('mso.dari', 'asc')
        ->get()->result();

        $data['parts'] = $this->db
        ->select('sop.*')
        ->select('p.nama_part')
        ->select('r.unit')
        ->from('tr_h3_dealer_stock_opname_parts as sop')
        ->join('ms_lokasi_rak_bin as r', '(r.id_rak = sop.id_rak and r.id_gudang = sop.id_gudang)')
        ->join('ms_part as p', 'p.id_part = sop.id_part')
        ->where('sop.id_stock_opname', $stock_opname->id_stock_opname)
        ->get()->result();

        $data['schedule_so'] = $this->db
        ->select('sso.id_schedule,sso.date_opname,sso.date_opname_end,sso.jenis_schedule')
        ->from('ms_set_up_schedule_stock_opname as sso')
        ->join('tr_h3_dealer_stock_opname as so','so.id_schedule=sso.id_schedule')
        ->where('so.id_stock_opname', $stock_opname->id_stock_opname)
        ->get()->row();
        
        $data['summary'] = $this->db
        ->select('sop.*')
        ->select('p.nama_part')
        ->select('r.unit')
        ->select('sum(sop.stock) as stock')
        ->select('ifnull(sum(sop.stock_aktual), 0) as stock_aktual')
        ->from('tr_h3_dealer_stock_opname_parts as sop')
        ->join('ms_part as p', 'p.id_part = sop.id_part')
        ->join('ms_lokasi_rak_bin as r', '(r.id_gudang = sop.id_gudang and r.id_rak = sop.id_rak)')
        ->where('sop.id_stock_opname', $stock_opname->id_stock_opname)
        ->group_by('sop.id_part')
        ->get()->result();

        $data['stock_opname'] = $stock_opname;

        $this->template($data);
    }

    public function approval_to_branch_manager()
    {
        $id_stock_opname = $this->input->post("id_stock_opname");
        $this->db->trans_start();
        

        $status_approval = array('status' => 'Approval to Branch Manager');
        // $where2= array('id_customer'=>$value->id_customer);
        $this->db->where('id_stock_opname',$id_stock_opname);
        $this->db->where('id_dealer',$this->m_admin->cari_dealer());
        $this->db->update('tr_h3_dealer_stock_opname',$status_approval);

        $this->db->trans_complete();
        if ($this->db->trans_status() == true) {
            $this->db->trans_commit();
            $result = [
                'status' => true,
                'message' => 'success',
                'data' => []
            ];
        } else {
            $this->db->trans_rollback();
            $result = [
                'status' => false,
                'message' => 'failed',
                'data' => []
            ];
        }
    
        echo json_encode($result);
    }

    public function save_hasil_opname()
    {
        // $this->db->trans_start();
        // $data['set']	= "form";
        // $data['mode']  = 'report_page';
        $id_user = $this->session->userdata("id_user");
		$data['id_user_group'] = $this->db->query("SELECT id_user_group FROM ms_user WHERE id_user = '$id_user'")->row();
        $data['set']   = "report_page";
        $stock_opname = $this->stock_opname->find($this->input->get('id'), 'id_stock_opname');
        $data['gudang'] = $this->db->select('g.id_gudang') 
                          ->from('ms_gudang_h23 as g')
                          ->where('g.id_gudang', $stock_opname->id_gudang)
                          ->get()->row();
        $data['pic'] = $this->db->select('k.id_karyawan_dealer,k.nama_lengkap')->from('ms_karyawan_dealer as k')->where('k.id_karyawan_dealer', $stock_opname->id_pic)->get()->row();
        $data['members'] = $this->db->select('k.id_karyawan_dealer, k.nama_lengkap,mso.dari,mso.sampai')
        ->from('tr_h3_dealer_member_stock_opname as mso')
        ->join('ms_karyawan_dealer as k', 'k.id_karyawan_dealer = mso.id_member')
        ->where('mso.id_stock_opname', $this->input->get('id'))
        ->order_by('mso.dari', 'asc')
        ->get();

        $data['parts'] = $this->db
        ->select('sop.*')
        ->select('p.nama_part')
        ->select('r.unit')
        ->select('ifnull(sop.stock_aktual, 0) as stock_aktual')
        ->select('(CASE WHEN sop.stock_aktual>sop.stock then sop.stock_aktual-sop.stock else sop.stock-sop.stock_aktual end) as qty_diff')
        ->from('tr_h3_dealer_stock_opname_parts as sop')
        ->join('ms_part as p', 'p.id_part = sop.id_part')
        ->join('ms_lokasi_rak_bin as r', '(r.id_gudang = sop.id_gudang and r.id_rak = sop.id_rak)')
        ->where('sop.id_stock_opname', $stock_opname->id_stock_opname)
        ->get();

        $data['schedule_so'] = $this->db
        ->select('sso.id_schedule,sso.date_opname,sso.date_opname_end')
        ->from('ms_set_up_schedule_stock_opname as sso')
        ->join('tr_h3_dealer_stock_opname as so','so.id_schedule=sso.id_schedule')
        ->where('so.id_stock_opname', $stock_opname->id_stock_opname)
        ->get()->row();
        
        $data['summary'] = $this->db
        ->select('sop.*')
        ->select('p.nama_part')
        ->select('r.unit')
        ->select('sum(sop.stock) as stock')
        ->select('ifnull(sum(sop.stock_aktual), 0) as stock_aktual')
        ->from('tr_h3_dealer_stock_opname_parts as sop')
        ->join('ms_part as p', 'p.id_part = sop.id_part')
        ->join('ms_lokasi_rak_bin as r', '(r.id_gudang = sop.id_gudang and r.id_rak = sop.id_rak)')
        ->where('sop.id_stock_opname', $stock_opname->id_stock_opname)
        ->group_by('sop.id_part')
        ->get()->result();

        $data['stock_opname'] = $stock_opname;
        
        $this->template($data);
        // $this->db->trans_complete();
    }

    public function update_so()
    {
        $this->db->trans_start();
        $master_data = $this->input->post(['tipe', 'id_gudang', 'id_pic']);

        $items_data = $this->getOnly(true, $this->input->post('parts'), $this->input->post(['id_stock_opname']));

        $this->stock_opname->update($master_data, $this->input->post(['id_stock_opname']));
        $this->stock_opname_parts->update_batch($items_data, $this->input->post(['id_stock_opname']));
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $result = $this->stock_opname->get($this->input->post(['id_stock_opname']), true);
            send_json($result);
        } else {
            $this->output->set_status_header(500);
        }
    }

    public function assign_member(){
        $this->validate_member();

        $this->db->trans_start();
        $this->member_stock_opname->insert($this->input->post());
        $id_member_stock_opname = $this->db->insert_id();
        $this->db->trans_complete();

        if($this->db->trans_status()){
            $result = $this->db
            ->from('tr_h3_dealer_member_stock_opname as mso')
            ->join('ms_karyawan_dealer as k', 'k.id_karyawan_dealer = mso.id_member')
            ->where('mso.id', $id_member_stock_opname)
            ->get()->row();

            send_json($result);
        }else{
          $this->output->set_status_header(500);
        }
    }

    public function remove_member(){
        $this->db->trans_start();
        $this->member_stock_opname->delete($this->input->get('id'), 'id');
        $this->db->trans_complete();

        if($this->db->trans_status()){
            $this->output->set_status_header(200);
        }else{
            $this->output->set_status_header(500);
        }
    }

    public function validate_member(){
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id_member', 'Member', 'required');
        $this->form_validation->set_rules('dari', 'Dari',  
            array(
                array(
                    'required',
                    function($str){
                        if($str == ''){
                            $this->form_validation->set_message('required' , lang('form_validation_required'));
                            return false;
                        }
                        return true;
                    }
                ),
                array(
                    'numeric',
                    function($str){
                        if(!is_numeric($str)){
                            $this->form_validation->set_message('numeric' , lang('form_validation_numeric'));
                            return false;
                        }
                        return true;
                    }
                ),
                // array(
                //     'not_in_range',
                //     function($value)
                //     {
                //         // return true;
                //         $this->db
                //         ->from('tr_h3_dealer_member_stock_opname as mso')
                //         ->where("{$this->input->post('dari')} between mso.dari and mso.sampai");
    
                //         $valid = $this->db->get()->num_rows() < 1;
                //         if(!$valid){
                //             $this->form_validation->set_message('not_in_range' , '{field} berada di range member lain.');
                //         }
                //         return $valid;
                //     }
                // ),
            )
        );

        $this->form_validation->set_rules('sampai', 'Sampai',  
            array(
                array(
                    'required',
                    function($str){
                        if($str == ''){
                            $this->form_validation->set_message('required' , lang('form_validation_required'));
                            return false;
                        }
                        return true;
                    }
                ),
                array(
                    'numeric',
                    function($str){
                        if(!is_numeric($str)){
                            $this->form_validation->set_message('numeric' , lang('form_validation_numeric'));
                            return false;
                        }
                        return true;
                    }
                ),
                // array(
                //     'not_in_range',
                //     function($value)
                //     {
                //         // return true;
                //         $this->db
                //         ->from('tr_h3_dealer_member_stock_opname as mso')
                //         ->where("{$this->input->post('sampai')} between mso.dari and mso.sampai");
    
                //         $valid = $this->db->get()->num_rows() < 1;
                //         if(!$valid){
                //             $this->form_validation->set_message('not_in_range' , '{field} berada di range member lain.');
                //         }
                //         return $valid;
                //     }
                // ),
            )
        );
        
        if (!$this->form_validation->run())
        {
            $keys = [
                'dari', 'sampai', 'id_member'
            ];
            $data = [];
            foreach ($keys as $key) {
                $data[$key] = form_error($key) == '' ? null : form_error($key);
            }
            $this->output->set_status_header(400);
            send_json($data);
        }
    }

    // public function request_recount(){
    //     $data = $this->input->post(['status', 'keterangan']);

    //     $this->db->trans_start();
    //     $this->stock_opname->update($data, $this->input->post['id_stock_opname']);
    //     $this->db->trans_complete();

    //     if($this->db->trans_status()){
    //         $result = $this->stock_opname->get($this->input->post(['id_stock_opname']), true);
    //         send_json($result);
    //     }else{
    //       $this->output->set_status_header(500);
    //     }
    // }

    // public function approved_report(){
    //     $data = $this->input->post(['status']);
    //     $data['keterangan'] = '';

    //     $this->db->trans_start();
    //     $this->stock_opname->update($data, $this->input->post['id_stock_opname']);
    //     $this->db->trans_complete();

    //     if($this->db->trans_status()){
    //         $result = $this->stock_opname->get($this->input->post(['id_stock_opname']), true);
    //         send_json($result);
    //     }else{
    //       $this->output->set_status_header(500);
    //     }
    // }

    public function approved_report(){
        $id_stock_opname = $this->input->post('id_stock_opname');
        $this->db->trans_start();
       
        $status_approval = array('status' => 'Closed');
        $this->db->where('id_stock_opname',$id_stock_opname);
        $this->db->where('id_dealer',$this->m_admin->cari_dealer());
        $this->db->update('tr_h3_dealer_stock_opname',$status_approval);

        $part_opname = $this->db->query("SELECT id_part,stock,stock_aktual,id_gudang,id_rak, (CASE WHEN stock_aktual>stock then stock_aktual-stock  else stock-stock_aktual end ) AS qty_diff FROM tr_h3_dealer_stock_opname_parts WHERE id_stock_opname='$id_stock_opname'")->result();

        foreach($part_opname as $val){
            $freeze = array('freeze' => '0');
            $this->db->where('id_part',$val->id_part);
            $this->db->update('ms_h3_dealer_stock',$freeze);

            // insert tbl transaksi stok , ket opname + ref id s.opname
            if($val->stock < $val->stock_aktual){
                $transaksi_stock = [
                    'id_part' => $val->id_part,
                    'id_gudang' => $val->id_gudang,
                    'id_rak' => $val->id_rak,
                    'tipe_transaksi' => '+',
                    'sumber_transaksi' => 'stock_opname',
                    'referensi' => $this->input->post('id_stock_opname'),
                    'stok_value' => $val->qty_diff,
                ];
                $this->transaksi_stok->insert($transaksi_stock);
                // $this->db->set('stock', "stock + {$val->qty_diff}", FALSE);

                // $this->db->set('stock', "stock + {$val->qty_diff}", FALSE)
                // ->where('id_part', $val->id_part)
                // ->where('id_gudang', $val->id_gudang)
                // ->where('id_rak', $val->id_rak)
                // ->where('id_dealer', $this->m_admin->cari_dealer())
                // ->update('ms_h3_dealer_stock'); 
            }

            if($val->stock > $val->stock_aktual){
                $transaksi_stock = [
                    'id_part' => $val->id_part,
                    'id_gudang' => $val->id_gudang,
                    'id_rak' => $val->id_rak,
                    'tipe_transaksi' => '-',
                    'sumber_transaksi' => 'stock_opname',
                    'referensi' => $this->input->post('id_stock_opname'),
                    'stok_value' => $val->qty_diff,
                ];
                $this->transaksi_stok->insert($transaksi_stock);
                // $this->db->set('stock', "stock - {$val->qty_diff}", FALSE);

                // if($val->stock == 1 && $val->stock_aktual == 0){
                //     $this->db->set('stock', 0, FALSE)
                //         ->where('id_part', $val->id_part)
                //         ->where('id_gudang', $val->id_gudang)
                //         ->where('id_rak', $val->id_rak)
                //         ->where('id_dealer', $this->m_admin->cari_dealer())
                //         ->update('ms_h3_dealer_stock');
                // }else{
                //     $this->db->set('stock', "stock - {$val->qty_diff}", FALSE)
                //     ->where('id_part', $val->id_part)
                //     ->where('id_gudang', $val->id_gudang)
                //     ->where('id_rak', $val->id_rak)
                //     ->where('id_dealer', $this->m_admin->cari_dealer())
                //     ->update('ms_h3_dealer_stock'); 
                // }
                
            }

             $this->db->set('stock', "{$val->stock_aktual}", FALSE)
                ->where('id_part', $val->id_part)
                ->where('id_gudang', $val->id_gudang)
                ->where('id_rak', $val->id_rak)
                ->where('id_dealer', $this->m_admin->cari_dealer())
                ->update('ms_h3_dealer_stock'); 

        }
        $this->db->trans_complete();
        if ($this->db->trans_status() == true) {
            $this->db->trans_commit();
            $result = [
                'status' => true,
                'message' => 'success',
                'data' => []
            ];
        } else {
            $this->db->trans_rollback();
            $result = [
                'status' => false,
                'message' => 'failed',
                'data' => []
            ];
        }
    
        echo json_encode($result);   
    }

    public function reject_report(){
        $id_stock_opname = $this->input->post("id_stock_opname");
        $this->db->trans_start();
       
        $request_recount = array(
            'status' => 'Recount');
        // $where2= array('id_customer'=>$value->id_customer);
        $this->db->where('id_stock_opname',$id_stock_opname);
        $this->db->where('id_dealer',$this->m_admin->cari_dealer());
        $this->db->update('tr_h3_dealer_stock_opname',$request_recount);
       
        $this->db->trans_complete();
        if ($this->db->trans_status() == true) {
            $this->db->trans_commit();
            $result = [
                'status' => true,
                'message' => 'success',
                'data' => []
            ];
        } else {
            $this->db->trans_rollback();
            $result = [
                'status' => false,
                'message' => 'failed',
                'data' => []
            ];
        }
    
        echo json_encode($result);   
    }

    public function request_recount()
    {
        $id_stock_opname = $this->input->post('id_stock_opname');
        $this->db->trans_start();
       
        $request_recount = array(
                                'status' => 'Recount',
                                'keterangan' => $this->input->post('keterangan'));
        // $where2= array('id_customer'=>$value->id_customer);
        $this->db->where('id_stock_opname',$id_stock_opname);
        $this->db->where('id_dealer',$this->m_admin->cari_dealer());
        $this->db->update('tr_h3_dealer_stock_opname',$request_recount);

        $this->db->trans_complete();
        if ($this->db->trans_status() == true) {
            $this->db->trans_commit();
            $_SESSION['pesan'] 	= "Request Recount berhasil disubmit";
            $_SESSION['tipe'] 	= "success";
            redirect('dealer/h3_dealer_stock_opname/edit_so?id='.$id_stock_opname, 'refresh');
        } else {
            $this->db->trans_rollback();
            $_SESSION['pesan'] 	= "Request Recount gagal disubmit";
            $_SESSION['tipe'] 	= "danger";
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function cetak_berita_acara()
    {
        $id_stock_opname = $this->input->get('id');
        $this->load->library('mpdf_l');
        $mpdf                           = $this->mpdf_l->load();
        $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
        $mpdf->charset_in               = 'UTF-8';
        $mpdf->autoLangToFont           = true;

        $data = [];
        $data['stock_opname'] = $this->db->select('so.id_stock_opname,sop.id_part,so.id_pic,so.id_dealer,so.date_opname,DATE_FORMAT(so.date_opname, "%M %y") as bulan_date_opname, count(sop.id_part) as jumlah_item')
                                         ->from('tr_h3_dealer_stock_opname as so')
                                         ->join('tr_h3_dealer_stock_opname_parts as sop','so.id_stock_opname=sop.id_stock_opname','left')
                                         ->where('so.id_stock_opname',$id_stock_opname)
                                         ->get()->row();
        $stock_opname = $data['stock_opname'];

        $data['pic'] = $this->db->select('k.id_karyawan_dealer,k.nama_lengkap')->from('ms_karyawan_dealer as k')->where('k.id_karyawan_dealer', $stock_opname->id_pic)->get()->row();
        
        $data['part_sistem'] = $this->db->select('count(id_part_int) as jumlah_item_sistem')
        ->from('ms_h3_dealer_stock')
        ->where('id_dealer',$this->m_admin->cari_dealer())
        ->get()->row();

        $data['dealer'] = $this->db->select('nama_dealer')
        ->from('ms_dealer')
        ->where('id_dealer',$stock_opname->id_dealer)
        ->get()->row();

        $data['parts'] = $this->db
        ->select('sop.id_part')
        ->select('p.nama_part')
        ->select('r.unit')
        ->select('ifnull(sop.stock_aktual, 0) as stock_aktual')
        ->select('ms.satuan')
        ->select('ms.satuan')
        ->select('ds.stock as stock_sistem')
        ->select('(CASE WHEN sop.stock_aktual>sop.stock then sop.stock_aktual-sop.stock else sop.stock-sop.stock_aktual end) as qty_diff')
        ->from('tr_h3_dealer_stock_opname_parts as sop')
        ->join('ms_part as p', 'p.id_part = sop.id_part')
        ->join('ms_satuan as ms','ms.id_satuan=p.id_satuan')
        ->join('ms_lokasi_rak_bin as r', '(r.id_gudang = sop.id_gudang and r.id_rak = sop.id_rak)')
        ->join('ms_h3_dealer_stock as ds', 'ds.id_part=sop.id_part')
        ->where('sop.id_stock_opname', $stock_opname->id_stock_opname)
        ->where('ds.id_dealer',$stock_opname->id_dealer)
        ->get();


        $html = $this->load->view('dealer/h3_dealer_report_berita_acara_approval_stock_opname', $data, true);
        // render the view into HTML
        $mpdf->WriteHTML($html);
        // write the HTML into the mpdf
        $mpdf->Output("Berita Acara Approval Stock Opname.pdf", 'I');
    }

    public function cetak_berita_acara_penyesuaian()
    {
        $id_stock_opname = $this->input->get('id');
        $this->load->library('mpdf_l');
        $mpdf                           = $this->mpdf_l->load();
        $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
        $mpdf->charset_in               = 'UTF-8';
        $mpdf->autoLangToFont           = true;

        $data = [];
        $data['stock_opname'] = $this->db->select('so.id_stock_opname,sop.id_part,so.id_pic,so.id_dealer,so.date_opname,DATE_FORMAT(so.date_opname, "%M %y") as bulan_date_opname, count(sop.id_part) as jumlah_item')
                                         ->from('tr_h3_dealer_stock_opname as so')
                                         ->join('tr_h3_dealer_stock_opname_parts as sop','so.id_stock_opname=sop.id_stock_opname','left')
                                         ->where('so.id_stock_opname',$id_stock_opname)
                                         ->get()->row();
        $stock_opname = $data['stock_opname'];

        $data['pic'] = $this->db->select('k.id_karyawan_dealer,k.nama_lengkap')->from('ms_karyawan_dealer as k')->where('k.id_karyawan_dealer', $stock_opname->id_pic)->get()->row();
        
        $data['part_sistem'] = $this->db->select('count(id_part_int) as jumlah_item_sistem')
        ->from('ms_h3_dealer_stock')
        ->where('id_dealer',$this->m_admin->cari_dealer())
        ->get()->row();

        $data['dealer'] = $this->db->select('nama_dealer')
        ->from('ms_dealer')
        ->where('id_dealer',$stock_opname->id_dealer)
        ->get()->row();

        $data['parts'] = $this->db
        ->select('sop.id_part')
        ->select('p.nama_part')
        ->select('r.unit')
        ->select('ifnull(sop.stock_aktual, 0) as stock_aktual')
        ->select('ms.satuan')
        ->select('ms.satuan')
        ->select('ds.stock as stock_sistem')
        ->select('(CASE WHEN sop.stock_aktual>sop.stock then sop.stock_aktual-sop.stock else sop.stock-sop.stock_aktual end) as qty_diff')
        ->from('tr_h3_dealer_stock_opname_parts as sop')
        ->join('ms_part as p', 'p.id_part = sop.id_part')
        ->join('ms_satuan as ms','ms.id_satuan=p.id_satuan')
        ->join('ms_lokasi_rak_bin as r', '(r.id_gudang = sop.id_gudang and r.id_rak = sop.id_rak)')
        ->join('ms_h3_dealer_stock as ds', 'ds.id_part=sop.id_part')
        ->where('sop.id_stock_opname', $stock_opname->id_stock_opname)
        ->where('ds.id_dealer',$stock_opname->id_dealer)
        ->get();


        $html = $this->load->view('dealer/h3_dealer_report_berita_acara_penyesuaian_stock_opname', $data, true);
        // render the view into HTML
        $mpdf->WriteHTML($html);
        // write the HTML into the mpdf
        $mpdf->Output("Berita Acara Hasil Penyesuaian Stock Opname.pdf", 'I');
    }

    public function upload_berita_acara()
    {
        $this->db->trans_start();
        $this->load->library('upload'); 
        $tgl = date('d-m-Y');
        $id_stock_opname = $this->input->post('id_stock_opname');
        $id_dealer = $this->m_admin->cari_dealer();
        $kode_dealer = $this->db->query("SELECT kode_dealer_md FROM ms_dealer WHERE id_dealer='$id_dealer'")->row();
        $config['upload_path'] = './uploads/berita_acara_stock_opname';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size'] = '2048';
        $config['overwrite'] = true;
        $config['file_name'] = 'berita_acara_opname_'.$kode_dealer->kode_dealer_md.'_'.$tgl;

        $this->upload->initialize($config);
        if($this->upload->do_upload('import_file')){ 
            $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
            // // return $return;
        }else{
            // Jika gagal :
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            // return $return;
        }

        $uploaded_data = $this->upload->data();
        $new_data = array(
            'document_berita_acara' => $uploaded_data['file_name']);
        $this->db->where('id_stock_opname',$id_stock_opname);
        $this->db->update('tr_h3_dealer_stock_opname',$new_data);

        // var_dump($uploaded_data);
        // die();
        $this->db->trans_complete();
        if ($this->db->trans_status() == true) {
            $this->db->trans_commit();
            $_SESSION['pesan'] 	= "Data berhasil diupload";
            $_SESSION['tipe'] 	= "success";
            redirect(base_url('dealer/h3_dealer_stock_opname/detail_so?id='.$id_stock_opname));
        } else {
            $this->db->trans_rollback();
            $_SESSION['pesan'] 	= "Data gagal diupload";
            $_SESSION['tipe'] 	= "danger";
            redirect(base_url('dealer/h3_dealer_stock_opname/detail_so?id='.$id_stock_opname));
        }
    }

    public function request_owner_approval(){
        $data = $this->input->post(['status']);
        $data['keterangan'] = '';

        $this->db->trans_start();
        $this->stock_opname->update($data, $this->input->post['id_stock_opname']);
        $this->db->trans_complete();

        if($this->db->trans_status()){
            $result = $this->stock_opname->get($this->input->post(['id_stock_opname']), true);
            send_json($result);
        }else{
          $this->output->set_status_header(500);
        }
    }

    public function reopen(){
        $data = $this->input->post(['status']);
        $data['keterangan'] = '';

        $this->db->trans_start();
        $this->stock_opname->update($data, $this->input->post['id_stock_opname']);
        $this->db->trans_complete();

        if($this->db->trans_status()){
            $result = $this->stock_opname->get($this->input->post(['id_stock_opname']), true);
            send_json($result);
        }else{
          $this->output->set_status_header(500);
        }
    }

    // public function cetak_berita_acara(){

    //     $data = [];

    //     $data['stock_opname'] = $this->stock_opname->find($this->input->get('id'), 'id_stock_opname');

    //     $data['stock_opname_parts'] = $this->stock_opname_parts->get([

    //         'id_stock_opname' => $data['stock_opname']->id_stock_opname

    //     ]);



    //     $data['stock_opname_parts_selisih'] = $this->stock_opname_parts->selisih([

    //         'id_stock_opname' => $this->input->get('id'),

    //     ]);



    //     $data['dealer'] = $this->dealer->getCurrentUserDealer();

    //     // $this->load->library('mpdf_l');

    //     require_once APPPATH .'third_party/mpdf/mpdf.php';

    //     // Require composer autoload

    //     $mpdf = new Mpdf();

    //     // Write some HTML code:

    //     $html = $this->load->view('dealer/h3_cetak_berita_acara_stock_opname', $data, true);

    //     $mpdf->WriteHTML($html);



    //     $date = date('siHdmY', time());

    //     $filename = $date . "_{$data['stock_opname']->id_stock_opname}";

    //     // Output a PDF file directly to the browser

    //     $mpdf->Output("{$filename}.pdf", "I");

    // }



    public function status(){

        $this->stock_opname->update($this->input->get(['status']), [

            'id_stock_opname' => $this->input->get('id')

        ]);



        if($this->input->get('status') == 'Closed'){



            $stock_opname = $this->stock_opname->find($this->input->get('id'), 'id_stock_opname');

            $stock_opname_parts = $this->stock_opname_parts->get([

                'id_stock_opname' => $this->input->get('id')

            ]);

            

            if($stock_opname->tipe == 'Stock Opname'){

                foreach ($stock_opname_parts as $part) {

                    $this->stock->update([

                        'stock' => $part->stock_aktual,

                        'freeze' => 0

                    ], [

                        'id_part' => $part->id_part,

                        'id_gudang' => $part->id_gudang,

                        'id_rak' => $part->id_rak,

                    ]);

                }

            }



            if($stock_opname->tipe == 'Cycle Count'){

                foreach ($stock_opname_parts as $part) {

                    $this->stock->update([

                        'stock' => $part->stock_aktual,

                        'freeze' => 0

                    ], [

                        'id_part' => $part->id_part,

                        'id_gudang' => $part->id_gudang,

                        'id_rak' => $part->id_rak,

                    ]);

                }

            }



        }



        echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page/detail?id={$this->input->get('id')}'>";

    }



    public function delete()

    {

        $delete = $this->lokasi_rak_bin->delete($this->input->get('k'), 'id_rak');

        if ($delete) {

            $_SESSION['pesan'] 	= "Data berhasil dihapus.";

            $_SESSION['tipe'] 	= "info";

            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";

        } else {

            $_SESSION['pesan'] 	= "Data not found !";

            $_SESSION['tipe'] 	= "danger";

            echo "<meta http-equiv='refresh' content='0; url=".base_url()."dealer/$this->page'>";

        }

    }



    public function get(){

        $rak = $this->lokasi_rak_bin->get($this->input->get(['id_gudang']));

        send_json($rak);

    }

}
