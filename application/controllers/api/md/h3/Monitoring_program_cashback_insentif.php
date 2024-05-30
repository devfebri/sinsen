<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monitoring_program_cashback_insentif extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
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
    
    public function make_query()
    {
        $this->db
		->select('do.tanggal as tanggal_transaksi')
		->select('sc.nama as nama_program')
		->select('
			case
				when sc.jenis_reward_poin = 1 then
					case
						when sc.start_date_poin is not null then start_date_poin
						else sc.start_date
					end
			end as start_date
		', false)
		->select('
			case
				when sc.jenis_reward_poin = 1 then
					case
						when sc.end_date_poin is not null then end_date_poin
						else sc.end_date
					end
			end as end_date
		', false)
		->select('d.nama_dealer')
		->select('ap.total_bayar')
		->select('ciscp.id_do_sales_order')
		->select('ps.no_faktur')
		->select('ciscp.nilai_claim')
		->select('vp.id_voucher_pengeluaran')
		->select('cg.kode_giro')
		->select('vp.tanggal_giro')
		->from('tr_h3_md_claim_insentif_sales_campaign_poin as ciscp')
		->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = ciscp.id_do_sales_order')
		->join('tr_h3_md_picking_list as pl', 'pl.id_ref = do.id_do_sales_order', 'left')
		->join('tr_h3_md_packing_sheet as ps', 'ps.id_picking_list = pl.id_picking_list', 'left')
		->join('tr_h3_md_ap_part as ap', 'ap.id = ciscp.id_ap_part')
		->join('ms_dealer as d', 'd.id_dealer = ap.id_dealer')
		->join('ms_h3_md_sales_campaign as sc', 'sc.id = ap.id_campaign')
		->join('tr_h3_md_voucher_pengeluaran_items as vpi', 'vpi.id_referensi = ap.id', 'left')
		->join('tr_h3_md_voucher_pengeluaran as vp', '(vp.id_voucher_pengeluaran = vpi.id_voucher_pengeluaran AND vp.via_bayar = "Giro")', 'left')
		->join('ms_cek_giro as cg', 'cg.id_cek_giro = vp.id_giro', 'left')
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = $this->input->post('search') ['value'];
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('sc.nama', $search);
            $this->db->or_like('ciscp.id_do_sales_order', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ciscp.created_at', 'desc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    public function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }
}
