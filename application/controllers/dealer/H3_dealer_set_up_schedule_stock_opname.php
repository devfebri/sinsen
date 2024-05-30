<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_set_up_schedule_stock_opname extends Honda_Controller
{
    protected $folder = "dealer";
    protected $page   = "h3_dealer_set_up_schedule_stock_opname";
    protected $title  = "Set Up Schedule Stock Opname";

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
        $this->load->model('h3_dealer_set_up_schedule_stock_opname_model', 'schedule');
        $this->load->model('notifikasi_model', 'notifikasi');
    }

    public function validate(){
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('jenis_schedule', 'Jenis Schedule', 'required');
        $this->form_validation->set_rules('cycle_days', 'Cycle Days', 'required|numeric');
        $this->form_validation->set_rules('reminder_days', 'Reminder Days', 'required|numeric');
        $this->form_validation->set_rules('date_opname', 'Date Opname', 'required');
        if (!$this->form_validation->run()){
            $keys = [
                'jenis_schedule',
                'cycle_days',
                'reminder_days',
                'date_opname',
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
        $data = array_merge($this->input->post(), [
            'id_schedule' => $this->schedule->generateID(),
        ]);

        $this->schedule->insert($data);

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
        $data['set']   = "form";

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
        $data['set']   = "form";

        $data['schedule'] = $this->db
        ->from('ms_set_up_schedule_stock_opname as s')
        ->where('s.id_schedule', $this->input->get('id_schedule'))
        ->limit(1)
        ->get()->row();

        $this->template($data);
    }
}
