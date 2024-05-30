<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Po_logistik extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_h3_md_po_logistik', [
                'id_po_logistik' => $row['id_po_logistik'],
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';

            $data[] = $row;
            $index++;
        }
        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
        ]);
    }

    private function make_query()
    {
        $jumlah_item = $this->db
        ->select('COUNT( DISTINCT(polp.id_part) ) as count')
        ->from('tr_h3_md_po_logistik_parts as polp')
        ->where('polp.id_po_logistik = pol.id_po_logistik')
        ->get_compiled_select();

        $jumlah_pcs = $this->db
        ->select('SUM(polp.qty_part) as sum')
        ->from('tr_h3_md_po_logistik_parts as polp')
        ->where('polp.id_po_logistik = pol.id_po_logistik')
        ->get_compiled_select();

        $nilai_urgent = $this->db
        ->select('SUM((polp.qty_po_ahm * polp.harga)) as nilai_urgent')
        ->from('tr_h3_md_po_logistik_parts as polp')
        ->where('polp.id_po_logistik = pol.id_po_logistik')
        ->get_compiled_select();

        $nilai_do = $this->db
        ->select('SUM((sop.qty_order * sop.harga)) as nilai_do')
        ->from('tr_h3_md_sales_order as so')
        ->join('tr_h3_md_sales_order_parts as sop', 'sop.id_sales_order = so.id_sales_order')
        ->where('so.id_po_logistik = pol.id_po_logistik')
        ->get_compiled_select();

        $this->db
        ->select('pol.id_po_logistik')
        ->select('date_format(pol.tanggal, "%d/%m/%Y") as tanggal')
        ->select("IFNULL(({$jumlah_item}), 0) as jumlah_item", false)
        ->select("IFNULL(({$jumlah_pcs}), 0) as jumlah_pcs", false)
        ->select('pol.total_amount')
        ->select("IFNULL(({$nilai_urgent}), 0) as nilai_urgent", false)
        ->select("IFNULL(({$nilai_do}), 0) as nilai_do", false)
        ->select("((IFNULL(({$nilai_do}), 0)/pol.total_amount) * 100) as service_rate", false)
        ->from('tr_h3_md_po_logistik as pol')
        ;
    }

    private function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('pol.id_po_logistik', $search);
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('pol.created_at', 'desc');
        }
    }

    private function limit(){
        if ($this->input->post('length') != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    private function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    private function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
