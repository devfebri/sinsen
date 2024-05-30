<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_event_h23 extends Honda_Controller
{
    protected $folder = "dealer";
    protected $page   = "h3_dealer_event_h23";
    protected $title  = "Event H23";

    public function __construct()
    {
        parent::__construct();
        //---- cek session -------//
        $name = $this->session->userdata('nama');
        if ($name=="") {
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
        }

        //===== Load Database =====
        $this->load->database();
        $this->load->helper('url');
        //===== Load Model =====
        $this->load->model('m_admin');
        $this->load->library('form_validation');
        $this->load->model('h3_dealer_event_h23_model', 'event_h23');
        $this->load->model('h3_dealer_event_h23_items_model', 'event_h23_items');
        $this->load->model('notifikasi_model', 'notifikasi');
        $this->load->model('H3_surat_jalan_outbound_model', 'surat_jalan');
    }

    public function validate(){
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('pic', 'PIC Event', 'required');
        $this->form_validation->set_rules('nama', 'Nama event', 'required|max_length[120]');
        $this->form_validation->set_rules('lokasi_event', 'Lokasi event', 'required|min_length[10]');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi event', 'required');
        $this->form_validation->set_rules('tipe', 'Tipe event', 'required');
        $this->form_validation->set_rules('start_date', 'Periode event', 'required');
        $this->form_validation->set_rules('end_date', 'Periode event', 'required');
        $this->form_validation->set_rules('parts', 'Parts',  array(
            array(
                'check_parts',
                function($value){
                    $valid = count($this->input->post('parts')) > 0;
                    if(!$valid){
                        $this->form_validation->set_message('check_parts' , '{field} tidak boleh kosong.');
                    }
                    return $valid;
                }
            )
        ));
        if (!$this->form_validation->run()){
            $keys = [
                'pic',
                'nama',
                'lokasi_event',
                'deskripsi',
                'tipe',
                'start_date',
                'end_date',
                'parts',
            ];
            $data = [];
            foreach ($keys as $key) {
                $data[$key] = form_error($key) == '' ? null : form_error($key);
            }

            $this->output->set_status_header(400);
            send_json($data);
        }
    }

    public function index()
    {
        $data['set']	= "index";
        $this->template($data);
    }

    public function add()
    {
        $data['mode']    = 'insert';
        $data['set']     = "form";
        $this->template($data);
    }

    public function save()
    {
        $this->validate();

        $this->db->trans_start();
        $master = array_merge($this->input->post(['nama', 'lokasi_event', 'deskripsi', 'tipe', 'start_date', 'end_date', 'pic']), [
            'id_event' => $this->event_h23->generateID(),
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);
        $items = $this->getOnly(true, $this->input->post('parts'), [
            'id_event' => $master['id_event']
        ]);

        $this->event_h23->insert($master);
        $this->event_h23_items->insert_batch($items);

        $this->db->trans_complete();

        if($this->db->trans_status()){
            $result = $this->event_h23->find($master['id_event'], 'id_event');
            send_json($result);
        }else{
            $this->output->set_status_header(500);
        }
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";

        $event = $this->db
        ->select('e.*')
        ->select('kd.id_karyawan_dealer as pic')
        ->select('kd.nama_lengkap as nama_pic')
        ->from('ms_h3_dealer_event_h23 as e')
        ->join('ms_karyawan_dealer as kd', 'kd.id_karyawan_dealer = e.pic', 'left')
        ->where('e.id_event', $this->input->get('id_event'))
        ->limit(1)
        ->get()->row();

        $data['parts'] = $this->db
        ->select('ei.*')
        ->select('p.nama_part')
        ->from('ms_h3_dealer_event_h23_items as ei')
        ->join('ms_part as p', 'p.id_part = ei.id_part')
        ->where('ei.id_event', $event->id_event)
        ->get()->result();

        $data['event'] = $event;

        $this->template($data);
    }

    public function update()
    {
        $this->validate();

        $this->db->trans_start();
        $master = $this->input->post(['nama', 'lokasi_event', 'deskripsi', 'tipe', 'start_date', 'end_date', 'pic']);
        $items = $this->getOnly(true, $this->input->post('parts'), $this->input->post(['id_event']));

        $this->event_h23->update($master, $this->input->post(['id_event']));
        $this->event_h23_items->update_batch($items, $this->input->post(['id_event']));
        $this->db->trans_complete();

        if($this->db->trans_status()){
            $result = $this->event_h23->get($this->input->post(['id_event']), true);
            send_json($result);
        }else{
            $this->output->set_status_header(500);
        }
    }

    public function edit()
    {
        $data['mode']  = 'edit';
        $data['set']   = "form";

        $event = $this->db
        ->select('e.*')
        ->select('kd.id_karyawan_dealer as pic')
        ->select('kd.nama_lengkap as nama_pic')
        ->from('ms_h3_dealer_event_h23 as e')
        ->join('ms_karyawan_dealer as kd', 'kd.id_karyawan_dealer = e.pic', 'left')
        ->where('e.id_event', $this->input->get('id_event'))
        ->limit(1)
        ->get()->row();

        $data['parts'] = $this->db
        ->select('ei.*')
        ->select('p.nama_part')
        ->from('ms_h3_dealer_event_h23_items as ei')
        ->join('ms_part as p', 'p.id_part = ei.id_part')
        ->where('ei.id_event', $event->id_event)
        ->get()->result();

        $data['event'] = $event;

        $this->template($data);
    }

    public function approve(){
        $this->db->trans_start();

        $this->event_h23->update([
            'status' => 'Approved',
            'approved_at' => date('Y-m-d H:i:s'),
            'approved_by' => $this->session->userdata('id_user')
        ], $this->input->get(['id_event']));

        $this->db->trans_complete();

        if($this->db->trans_status()){
            $this->output->set_status_header(200);
            $result = $this->event_h23->get($this->input->get(['id_event']), true);
            send_json($result);
        }else{
            $this->output->set_status_header(500);
        }
    }

    public function reject(){
        $this->db->trans_start();

        $this->event_h23->update([
            'status' => 'Rejected',
            'keterangan' => $this->input->get('alasan_reject'),
            'rejected_at' => date('Y-m-d H:i:s'),
            'rejected_by' => $this->session->userdata('id_user')
        ], $this->input->get(['id_event']));

        $this->db->trans_complete();

        if($this->db->trans_status()){
            $this->output->set_status_header(200);
            $result = $this->event_h23->get($this->input->get(['id_event']), true);
            send_json($result);
        }else{
            $this->output->set_status_header(500);
        }
    }
}
