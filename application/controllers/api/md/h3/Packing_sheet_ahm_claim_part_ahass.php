<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Packing_sheet_ahm_claim_part_ahass extends CI_Controller
{

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_packing_sheet_ahm_claim_part_ahass', [
                'data' => json_encode($row),
                'packing_sheet_number' => $row['packing_sheet_number']
            ], true);

            $row['index'] = $this->input->post('start') + $index . '.';

            $data[] = $row;
            $index++;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->get_filtered_data(),
            'recordsTotal' => $this->get_total_data(),
            'data' => $data
        ]);
    }

    public function make_query($datatable = true)
    {
        $parts = $this->input->post('claim_parts_to_ahm');
        $packing_sheets_dengan_part_yang_memenuhi = [];
        if (count($parts) > 0) {
            $packing_sheets_dengan_part_yang_memenuhi = $this->get_packing_sheet_dengan_part_yang_memenuhi($parts);
        }

        $list_id_part_int = [];
        if ($parts != null) {
            $list_id_part_int = array_map(function ($row) {
                return $row['id_part_int'];
            }, $parts);
        }

        if ($datatable) {
            $this->db
                ->select('ps.id')
                ->select('ps.packing_sheet_number')
                ->select('psp.no_doos as nomor_karton')
                ->select('psp.no_doos_int as nomor_karton_int')
                ->select('nk.jumlah_item')
                ->select('date_format(ps.packing_sheet_date, "%d/%m/%Y") as packing_sheet_date');
        }

        $this->db
            ->from('tr_h3_md_ps as ps')
            ->join('tr_h3_md_ps_parts as psp', 'psp.packing_sheet_number_int = ps.id')
            ->join('tr_h3_md_nomor_karton as nk', 'nk.id = psp.no_doos_int')
            ->join('tr_h3_md_claim_part_ahass as cpa', '(cpa.packing_sheet_number_int = ps.id and cpa.nomor_karton_int = nk.id)', 'left')
            ->where('DATEDIFF(NOW(), ps.packing_sheet_date) <= (6 * 30)')
            ->where('cpa.id IS NULL', null, false)
            ->group_by('ps.id')
            ->group_by('psp.no_doos_int');

        if (count($list_id_part_int) > 0) {
            $this->db->where_in('psp.id_part_int', $list_id_part_int);
        } else {
            $this->db->where('1=0', null, false);
        }

        if (count($packing_sheets_dengan_part_yang_memenuhi) > 0) {
            $this->db->where_in('ps.packing_sheet_number', $packing_sheets_dengan_part_yang_memenuhi);
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
            ->where('DATEDIFF(NOW(), ps.packing_sheet_date) <= (6 * 30)', null, false);

        if (count($list_packing_sheet_terdapat_kode_part) > 0) {
            $this->db->where_in('ps.id', $list_packing_sheet_terdapat_kode_part);
        } else {
            $this->db->where('FALSE = TRUE', null, false);
        }

        $packing_sheets = $this->db->get()->result_array();

        $packing_sheets_dengan_part_yang_memenuhi = [];
        foreach ($packing_sheets as $packing_sheet) {
            $this->db
                ->select('SUM(cdp.qty_part_diclaim) as qty_part_diclaim')
                ->from('tr_h3_md_claim_part_ahass as cpa')
                ->join('tr_h3_md_claim_part_ahass_parts as cpap', 'cpap.id_claim_part_ahass = cpa.id_claim_part_ahass')
                ->join('tr_h3_md_claim_dealer_parts as cdp', '(cdp.id_claim_dealer = cpap.id_claim_dealer and cdp.id_part = cpap.id_part and cdp.id_kategori_claim_c3 = cpap.id_kategori_claim_c3)')
                ->where('cpa.packing_sheet_number = psp.packing_sheet_number')
                ->where('cdp.id_part = psp.id_part');
            if ($this->input->post('id_claim_part_ahass') != null) {
                $this->db->where('cpa.id_claim_part_ahass !=', $this->input->post('id_claim_part_ahass'));
            }
            $kuantitas_sudah_diclaim = $this->db->get_compiled_select();

            $this->db
                ->select('psp.packing_sheet_number')
                ->select('psp.id_part')
                ->select('SUM(psp.packing_sheet_quantity) as packing_sheet_quantity')
                ->select("IFNULL(({$kuantitas_sudah_diclaim}), 0) as kuantitas_sudah_diclaim", false)
                ->select("(SUM(psp.packing_sheet_quantity) - IFNULL(({$kuantitas_sudah_diclaim}), 0)) as sisa_boleh_diclaim", false)
                ->from('tr_h3_md_ps_parts as psp')
                ->where('psp.packing_sheet_number', $packing_sheet['packing_sheet_number'])
                ->group_by('psp.id_part');

            $part_packing_sheets = $this->db->get()->result_array();

            $found = 0;
            if (count($parts) > 0) {
                foreach ($parts as $part) {
                    foreach ($part_packing_sheets as $part_packing_sheet) {
                        if (
                            ($part_packing_sheet['id_part'] == $part['id_part']) and
                            ($part_packing_sheet['sisa_boleh_diclaim'] >= $part['qty_part_diclaim'])
                        ) {
                            $found++;
                            break;
                        }
                    }
                }
            }

            if (count($parts) == $found and count($parts) != 0) {
                $packing_sheets_dengan_part_yang_memenuhi[] = $packing_sheet['packing_sheet_number'];
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
            $this->db->or_like('ps.packing_sheet_number', $search);
            $this->db->or_like('psp.no_doos', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ps.packing_sheet_date', 'desc');
            $this->db->order_by('psp.no_doos', 'ASC');
        }
    }

    public function limit()
    {
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function get_filtered_data()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function get_total_data()
    {
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    public function check_packing_sheet_available()
    {
        $this->make_query(false);
        $this->db->select('ps.packing_sheet_number');

        foreach ($this->db->get()->result_array() as $row) {
            if ($row['packing_sheet_number'] == $this->input->post('packing_sheet_number')) {
                echo 1;
                die;
            }
        }
        echo 0;
        die;
    }
}
