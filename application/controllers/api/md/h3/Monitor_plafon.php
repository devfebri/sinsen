<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Monitor_plafon extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('H3_md_ms_plafon_model', 'plafon');
    }
    public function index()
    {
        $this->make_datatables();
        $this->limit();
        $rows = $this->db->get()->result_array();

        $data = array();
        $dealer = $this->db
        ->select('d.id_dealer')
        ->select('d.tipe_plafon_h3')
        ->from('ms_dealer as d')
        ->where('d.id_dealer', $this->input->post('id_customer_filter'))
        ->get()->row_array();

        $gimmick = $dealer['tipe_plafon_h3'] == 'gimmick' ? 1 : 0;
        $kategori_po = $dealer['tipe_plafon_h3'] == 'kpb' ?'KPB' : null;
        $plafon = $this->plafon->get_plafon($dealer['id_dealer'], $gimmick, $kategori_po) - $this->plafon->get_plafon_booking($dealer['id_dealer'], $gimmick, $kategori_po);

        $total_previous_data = 0;
        $previous_data = $this->get_previous_data();
        if(count($previous_data) > 0){
            $total_previous_data = array_sum(
                array_map(function($data){
                    return $data['sisa_piutang'];
                }, $previous_data)
            );
        }
        $plafon -= $total_previous_data;

        foreach ($rows as $row) {
            $row['plafon'] = $plafon -=  (double) $row['sisa_piutang'];

            $open_keterangan = $this->db
            ->select('pb.nomor_bg')
            ->select('pb.nama_bank_bg')
            ->select('date_format(pb.tanggal_jatuh_tempo_bg, "%d/%m/%Y") as tanggal_jatuh_tempo_bg')
            ->select("
                concat(
                    'Rp ',
                    format(pbi.jumlah_pembayaran, 0, 'ID_id')
                ) as jumlah_pembayaran
            ", false)
            ->from('tr_h3_md_penerimaan_pembayaran_item as pbi')
            ->join('tr_h3_md_penerimaan_pembayaran as pb', 'pb.id_penerimaan_pembayaran = pbi.id_penerimaan_pembayaran')
            ->where('pbi.referensi', $row['no_faktur'])
            ->where('pb.jenis_pembayaran', 'BG')
            ->group_start()
            ->where('pb.status_bg is null', null, false)
            ->or_where('pb.status_bg', 'Cair')
            ->group_end()
            ->order_by('pb.created_at', 'desc')
            ->get()->result_array();

            $row['open_keterangan'] = $this->load->view('additional/action_open_keterangan_monitor_plafon', [
                'no_faktur' => $row['no_faktur'],
                'open_keterangan' => $open_keterangan
            ], true);

            $data[] = $row;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'start' => $this->input->post('start'),
            'data' => $data,
            'total_previous_data' => $total_previous_data,
            'previous_data' => $previous_data
        ]);
    }

    public function make_query()
    {
        $dealer = $this->db
        ->select('d.id_dealer')
        ->select('d.tipe_plafon_h3')
        ->from('ms_dealer as d')
        ->where('d.id_dealer', $this->input->post('id_customer_filter'))
        ->get()->row_array();

        $this->db
        ->select('so.produk')
        ->select('ar.referensi as no_faktur')
        ->select('date_format(ar.tanggal_transaksi, "%d/%m/%Y") as tgl_faktur')
        ->select('date_format(ar.tanggal_jatuh_tempo, "%d/%m/%Y") as tgl_jatuh_tempo')
        ->select('ar.total_amount as nominal')
        ->select("(ar.total_amount - ar.sudah_dibayar) as sisa_piutang", false)
        ->select("ar.sudah_dibayar as nominal_pembayaran_faktur", false)
        ->select('d.sebagai_plafon_hadiah')
        ->from('tr_h3_md_ar_part as ar')
        ->join('tr_h3_md_packing_sheet as ps', 'ps.no_faktur = ar.referensi')
        ->join('tr_h3_md_picking_list as pl', 'pl.id_picking_list = ps.id_picking_list')
        ->join('tr_h3_md_do_sales_order as do', 'do.id_do_sales_order = pl.id_ref')
        ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = do.id_sales_order')
        ->join('ms_dealer as d', 'd.id_dealer = ar.id_dealer')
        ->where('ar.lunas', 0)
        ;

        if($dealer != null){
            if($dealer['tipe_plafon_h3'] == 'gimmick'){
                $this->db->where('ar.gimmick', 1);
            }else if($dealer['tipe_plafon_h3'] == 'kpb'){
                $this->db->where('ar.kpb', 1);
            }else{
                $this->db->where('ar.id_dealer', $this->input->post('id_customer_filter'));
                $this->db->where('ar.gimmick', 0);
                $this->db->where('ar.kpb', 0);
            }
        }else{
            $this->db->where('ar.id_dealer', $this->input->post('id_customer_dealer'));
            $this->db->where('ar.gimmick', 0);
            $this->db->where('ar.kpb', 0);
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ar.created_at', 'asc');
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

    public function get_previous_data(){
        $this->make_query();

        $start = $this->input->post('start');
        if($start != null and $start >= 10){
            $this->db->limit($start);
            $this->db->order_by('ar.created_at', 'asc');
            return $this->db->get()->result_array();
        }else{
            $this->db->reset_query();
            return [];
        }

    }
}
