<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class H3_md_packing_sheet_ahm extends CI_Controller {

    public function index(){
        $data = [];
        $data['header'] = $this->db
        ->select('ps.packing_sheet_number')
        ->select('ps.packing_sheet_date')
        ->from('tr_h3_md_ps as ps')
        ->where('ps.packing_sheet_number', $this->input->get('packing_sheet_number'))
        ->limit(1)
        ->get()->row_array();

        $this->db
        ->select('psp.id_part')
        ->select('p.nama_part')
        ->select('psp.no_doos')
        ->select('psp.no_po')
        ->select('psp.jenis_po')
        ->select('psp.tanggal_po')
        ->select('psp.packing_sheet_quantity')
        ->select('psp.qty_order')
        ->select('psp.qty_back_order')
        ->from('tr_h3_md_ps_parts as psp')
        ->join('ms_part as p', 'p.id_part = psp.id_part', 'left')
        ->where('psp.packing_sheet_number', $this->input->get('packing_sheet_number'));

        if(count($this->input->get('nomor_karton')) > 0){
            $this->db->where_in('psp.no_doos', $this->input->get('nomor_karton'));
        }

        $data['parts'] = $this->db->get()->result_array();

        $this->load->view('iframe/md/h3/h3_md_packing_sheet_ahm', $data);
    }

}