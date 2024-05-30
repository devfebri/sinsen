<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monitor_po_dari_dealer_other extends CI_Controller
{
    public function index()
    {
        $this->make_datatables(true);
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['po_id'] = $this->load->view('additional/md/h3/action_index_h3_md_purchase_dari_dealer', [
                'id' => $row['po_id']
            ], true);

            if ($row['total_amount'] != 0) {
                $row['persentase'] = ($row['amount_sudah_disupply'] / $row['total_amount']) * 100;
            } else {
                $row['persentase'] = 0;
            }

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

    public function make_query($datatable = false)
    {
        if ($datatable) {
            $amount_sudah_disupply = $this->db
                ->select('SUM( (opt.qty_bill * (pop.tot_harga_part / pop.kuantitas)) )', false)
                ->from('tr_h3_dealer_order_parts_tracking as opt')
                ->join('tr_h3_dealer_purchase_order_parts as pop', '(opt.po_id_int = pop.po_id_int AND opt.id_part_int = pop.id_part_int)')
                ->where('opt.po_id_int = po.id', null, false)
                ->get_compiled_select();

            $this->db
                ->select('d.nama_dealer')
                ->select('d.alamat')
                ->select('po.po_type')
                // ->select('date_format(po.tanggal_order, "%d/%m/%Y") as tanggal_order')
                ->select('(CASE WHEN po.revised_dealer_at is not null THEN date_format(po.revised_dealer_at, "%d/%m/%Y") else date_format(po.tanggal_order, "%d/%m/%Y") end) as tanggal_order')
                ->select('date_format(po.submit_at, "%d/%m/%Y") as tanggal_submit')
                ->select('
        case
            when po.proses_at is null then "-"
            else date_format(po.proses_at, "%d/%m/%Y")
        end as tanggal_proses', false)
                ->select('po.po_id')
                ->select('po.status')
                ->select('po.total_amount')
                ->select("IFNULL(({$amount_sudah_disupply}), 0) as amount_sudah_disupply", false);
        }


        $this->db
            ->from('tr_h3_dealer_purchase_order as po')
            ->join('ms_dealer as d', 'd.id_dealer = po.id_dealer')
            ->where('po.po_type', 'OTHER')
            ->group_start()
            ->where('po.order_to', null)
            ->or_where('po.order_to', 0)
            ->group_end()
            ->group_start()
                ->group_start()
                    ->where('po.created_by_md', 1)
                    ->where('po.autofulfillment_md', 1)
                ->group_end()
                 ->or_where('po.created_by_md', 0)
            ->group_end();
            
        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->group_start();
            $this->db->where('po.status', 'Processed by MD');
            $this->db->or_where('po.status', 'Closed');
            $this->db->group_end();
        } else {
            $this->db->group_start();
            $this->db->where('po.status', 'Submitted');
            $this->db->or_where('po.status', 'Rejected');
            $this->db->or_where('po.status', 'Submit & Approve Revisi');
            $this->db->group_end();
        }
    }

    public function make_datatables($datatable = false)
    {
        $this->make_query($datatable);

        if ($this->input->post('no_po_filter') != null) {
            $this->db->like('po.po_id', trim($this->input->post('no_po_filter')));
        }

        if ($this->input->post('customer_filter') != null and count($this->input->post('customer_filter'))) {
            $this->db->where_in('d.id_dealer', $this->input->post('customer_filter'));
        }

        if ($this->input->post('tipe_po_filter') != null and count($this->input->post('tipe_po_filter'))) {
            $this->db->where_in('po.po_type', $this->input->post('tipe_po_filter'));
        }

        if ($this->input->post('salesman_filter') != null and count($this->input->post('salesman_filter'))) {
            $this->db->where_in('po.id_salesman', $this->input->post('salesman_filter'));
        }

        if ($this->input->post('periode_filter_start') != null and $this->input->post('periode_filter_end') != null) {
            $this->db->group_start();
            $this->db->where('po.tanggal_order >=', $this->input->post('periode_filter_start'));
            $this->db->where('po.tanggal_order <=', $this->input->post('periode_filter_end'));
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('po.revised_dealer_at', 'desc');
            $this->db->order_by('po.tanggal_order', 'desc');
        }
    }

    public function limit()
    {
        if ($this->input->post('length') != -1) {
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
        return $this->db->count_all_results();
    }
}
