<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Detail_stock_kartu_stok extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('H3_md_stock_model', 'stock');
    }

    public function index() {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['nomor_karton'] = $this->get_nomor_karton($row['created_at'], $row['id_part'], $row['id_lokasi_rak']);

            $row['keterangan'] = $this->load->view('additional/md/h3/action_open_keterangan_detail_kartu_stok', [
                'keterangan' => $row['keterangan'],
                'sumber_transaksi' => $row['sumber_transaksi'],
                'id_do_sales_order' => $row['id_do_sales_order'],
                'nomor_karton' => $row['nomor_karton'],
            ], true);


            $row['index'] = $this->input->post('start') + $index . '.';
            $data[] = $row;
            $index++;
        }
        $output = array(
            'draw' => intval($_POST["draw"]), 
            'recordsFiltered' => $this->recordsFiltered(), 
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
        );
        send_json($output);
    }

    public function get_total_stock(){
        echo $this->stock->qty_on_hand($this->input->post('id_part'), $this->input->post('id_lokasi_rak'));
        die;
    }
    
    private function make_query() {
        $this->db
        ->select('ks.created_at')
        ->select('
            case
                when ks.sumber_transaksi = "h3_md_laporan_penerimaan_barang" then ks.packing_sheet_number
                else ks.referensi
            end as keterangan
        ', false)
        ->select('ks.sumber_transaksi')
        ->select('do.id_do_sales_order')
		->select('
			SUM(
                case
                    when ks.tipe_transaksi = "+" then ks.stock_value
                    else 0
                end
            ) as qty_masuk
		', false)
		->select('
			SUM(
                case
                    when ks.tipe_transaksi = "-" then ks.stock_value
                    else 0
                end
            ) as qty_keluar
		', false)
        ->select('max(ks.stock_akhir) as stok')
        ->select('ks.id_part')
        ->select('ks.id_lokasi_rak')
        ->select('ks.created_at')
        ->from('tr_h3_md_kartu_stock as ks')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = ks.referensi', 'left')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list', 'left')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref', 'left')
		->where('ks.id_part', $this->input->post('id_part'))
        ->where('ks.id_lokasi_rak', $this->input->post('id_lokasi_rak'))
		->group_start()
		->where('LEFT(ks.created_at, 10) >=', $this->input->post('periode_start'))
		->where('LEFT(ks.created_at, 10) <=', $this->input->post('periode_end'))
        ->group_end()
        ->group_by('ks.created_at')
        ;
    }

    private function make_datatables() {
        $this->make_query();

        // $search = $this->input->post('search')['value'];
        // if ($search != '') {
        //     $this->db->group_start();
        //     $this->db->like('sp.id_part', $search);
        //     $this->db->or_like('p.nama_part', $search);
        //     $this->db->group_end();
        // }

        // if (isset($_POST["order"])) {
        //     $indexColumn = $_POST['order']['0']['column'];
        //     $name = $_POST['columns'][$indexColumn]['name'];
        //     $data = $_POST['columns'][$indexColumn]['data'];
        //     $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        // } else {
        //     $this->db->order_by('ks.c', 'ASC');
        // }
    }

    private function limit(){
        if ($_POST["length"] != - 1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    private function recordsFiltered() {
        $this->make_datatables();
        return $this->db->get()->num_rows();
    }

    private function recordsTotal(){
        $this->make_query();
        return $this->db->get()->num_rows();
    }

    private function get_nomor_karton($created_at, $id_part, $id_lokasi_rak){
        $nomor_karton = $this->db
        ->select('ks.nomor_karton')
        ->from('tr_h3_md_kartu_stock as ks')
        ->where('ks.id_part', $id_part)
        ->where('ks.id_lokasi_rak', $id_lokasi_rak)
        ->where('ks.created_at', $created_at)
        ->get()->result_array();

        if(count($nomor_karton) > 0){
            return array_map(function($row){
                return $row['nomor_karton'];
            }, $nomor_karton);
        }else{
            return [];
        }
    }
}
