<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Packing_sheet_claim_main_dealer_ke_ahm extends CI_Controller
{
    public function index()
    {
        ini_set('max_execution_time', 0);

        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_packing_sheet_number_main_dealer_ke_ahm', [
                'data' => json_encode($row),
                'umur_packing_sheet' => $row['umur_packing_sheet']
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';

            $data[] = $row;
            $index++;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data
        ]);
    }

    public function make_query()
    {
        $parts = $this->input->post('selected_parts');
        $packing_sheets_dengan_part_yang_memenuhi = [];
        if (count($parts) > 0) {
            $packing_sheets_dengan_part_yang_memenuhi = $this->get_packing_sheet_dengan_part_yang_memenuhi($parts);
        }

        $this->db
            ->select('ps.id')
            ->select('ps.packing_sheet_number')
            ->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date')
            ->select('ifnull(fdo.invoice_number, "-") as invoice_number')
            ->select('fdo.id as invoice_number_int')
            ->select("(DATEDIFF(NOW(), ps.packing_sheet_date) / 30) as umur_packing_sheet")
            ->from('tr_h3_md_ps as ps')
            ->join('tr_h3_md_fdo as fdo', 'fdo.id = ps.invoice_number_int', 'left');

        if (count($packing_sheets_dengan_part_yang_memenuhi) > 0) {
            $this->db->where_in('ps.id', $packing_sheets_dengan_part_yang_memenuhi);
        } else {
            $this->db->where('FALSE = TRUE', null, false);
        }
    }

    private function get_packing_sheet_dengan_part_yang_memenuhi($parts)
    {
        $list_id_part = array_map(function ($row) {
            return $row['id_part_int'];
        }, $parts);

        $list_packing_sheet_terdapat_kode_part = $this->db
            ->select('psp.packing_sheet_number_int')
            ->from('tr_h3_md_ps_parts as psp')
            ->where_in('psp.id_part_int', $list_id_part)
            ->get()->result_array();

        $list_packing_sheet_terdapat_kode_part = array_map(function ($row) {
            return $row['packing_sheet_number_int'];
        }, $list_packing_sheet_terdapat_kode_part);
        $list_packing_sheet_terdapat_kode_part = array_unique($list_packing_sheet_terdapat_kode_part);

        $this->db
            ->select('ps.id')
            ->select('ps.packing_sheet_number')
            ->select('ps.packing_sheet_date')
            ->from('tr_h3_md_ps as ps')
            ->where('DATEDIFF(NOW(), ps.packing_sheet_date) <= (6 * 30)', null, false)
        ;

        if (count($list_packing_sheet_terdapat_kode_part) > 0) {
            $this->db->where_in('ps.id', $list_packing_sheet_terdapat_kode_part);
        } else {
            $this->db->where('FALSE = TRUE', null, false);
        }

        $packing_sheets = $this->db->get()->result_array();

        $packing_sheets_dengan_part_yang_memenuhi = [];
        foreach ($packing_sheets as $packing_sheet) {
            $kuantitas_sudah_diclaim = $this->db
                ->select('SUM(cmdi.qty_part_diclaim) as qty_part_diclaim', false)
                ->from('tr_h3_md_claim_main_dealer_ke_ahm as cmd')
                ->join('tr_h3_md_claim_main_dealer_ke_ahm_item as cmdi', 'cmdi.id_claim_int = cmd.id')
                ->where('cmd.packing_sheet_number_int = psp.packing_sheet_number_int', null, false)
                ->where('cmdi.id_part_int = psp.id_part_int', null, false)
                ->where('cmdi.no_doos_int = psp.no_doos_int', null, false)
                ->get_compiled_select();

            $this->db
                ->select('psp.packing_sheet_number_int')
                ->select('psp.packing_sheet_number')
                ->select('psp.id_part')
                ->select('psp.id_part_int')
                ->select('psp.no_doos')
                ->select('psp.no_doos_int')
                ->select('psp.packing_sheet_quantity')
                // ->select("IFNULL(({$kuantitas_sudah_diclaim}), 0) as kuantitas_sudah_diclaim", false)
                ->select("(psp.packing_sheet_quantity - IFNULL(({$kuantitas_sudah_diclaim}), 0)) as sisa_boleh_diclaim", false)
                ->from('tr_h3_md_ps_parts as psp')
                ->where('psp.packing_sheet_number_int', $packing_sheet['id']);

            $this->db->where_in('psp.id_part_int', $list_id_part);

            $part_packing_sheets = $this->db->get()->result_array();

            $found = 0;
            if (count($parts) > 0) {
                foreach ($parts as $part) {
                    foreach ($part_packing_sheets as $part_packing_sheet) {
                        if (
                            ($part_packing_sheet['id_part_int'] == $part['id_part_int']) and
                            ($part_packing_sheet['no_doos_int'] == $part['no_doos_int']) and
                            (intval($part_packing_sheet['sisa_boleh_diclaim']) >= intval($part['qty_part_diclaim']))
                        ) {
                            $found++;
                            break;
                        }
                    }
                }
            }

            if (count($parts) == $found and count($parts) != 0) {
                $packing_sheets_dengan_part_yang_memenuhi[] = $packing_sheet['id'];
            }
        }

        return $packing_sheets_dengan_part_yang_memenuhi;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search')['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('ps.packing_sheet_number', $search);
            $this->db->or_like('fdo.invoice_number', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.packing_sheet_number', 'asc');
        }
    }

    public function limit()
    {
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
