<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ar_part extends CI_Controller
{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            if($row['jenis_transaksi'] != null){
                $jenis_transaksi = $row['jenis_transaksi'];
                $jenis_transaksi = str_replace('_', ' ', $jenis_transaksi);
                $jenis_transaksi = ucwords($jenis_transaksi);
                $row['jenis_transaksi'] = $jenis_transaksi;
            }

            $list_bg = $this->db
                ->select('pb.nomor_bg')
                ->from('tr_h3_md_penerimaan_pembayaran_item as pbi')
                ->join('tr_h3_md_penerimaan_pembayaran as pb', 'pb.id_penerimaan_pembayaran = pbi.id_penerimaan_pembayaran')
                ->where('pbi.referensi', $row['referensi'])
                ->where('pb.jenis_pembayaran', 'BG')
                ->group_start()
                ->where('pb.status_bg !=', 'Tolak')
                ->or_where('pb.status_bg', null)
                ->group_end()
                ->order_by('pb.created_at', 'desc')
                ->get()->result_array();

            $row['open_status_pembayaran'] = $this->load->view('additional/action_open_status_pembayaran_ar_part', [
                'referensi' => $row['referensi'],
                'list_bg' => $list_bg
            ], true);

            $row['index'] = $this->input->post('start') + $index;
            $data[] = $row;
            $index++;
        }

        send_json([
            'draw' => intval($this->input->post('draw')),
            'recordsFiltered' => $this->recordsFiltered(),
            'recordsTotal' => $this->recordsTotal(),
            'data' => $data,
            'post' => $_POST
        ]);
    }

    public function make_query()
    {
        $this->db
            ->select('ar.jenis_transaksi')
            ->select('ar.nama_customer')
            ->select('ar.referensi')
            ->select('ar.tipe_referensi')
            ->select('date_format(ar.tanggal_transaksi, "%d/%m/%Y") as tanggal_transaksi')
            ->select('date_format(ar.tanggal_jatuh_tempo, "%d/%m/%Y") as tanggal_jatuh_tempo')
            ->select('ar.total_amount')
            ->select('ar.sudah_dibayar')
            ->select('(ar.total_amount - ar.sudah_dibayar) as sisa_piutang', false)
            ->from('tr_h3_md_ar_part as ar')
            ->join('ms_dealer as d', 'd.id_dealer = ar.id_dealer', 'left');

        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            $this->db->where('ar.lunas', 1);
        } else {
            $this->db->where('ar.lunas', 0);
        }
    }

    public function make_datatables()
    {
        $id_customer_filter = $this->input->post('id_customer_filter');

        $dealer = $this->db
            ->select('d.id_dealer')
            ->select('d.tipe_plafon_h3')
            ->from('ms_dealer as d')
            ->where('d.id_dealer', $id_customer_filter)
            ->get()->row_array();

        $this->make_query();

        if ($this->input->post('no_referensi_filter') != null) {
            $this->db->like('ar.referensi', $this->input->post('no_referensi_filter'));
        }

        if ($this->input->post('jenis_transaksi_filter') != null) {
            $this->db->like('ar.jenis_transaksi', $this->input->post('jenis_transaksi_filter'));
        }

        if ($this->input->post('tanggal_jatuh_tempo_filter_start') != null and $this->input->post('tanggal_jatuh_tempo_filter_end') != null) {
            $this->db->group_start();
            $this->db->where('ar.tanggal_jatuh_tempo >=', $this->input->post('tanggal_jatuh_tempo_filter_start'));
            $this->db->where('ar.tanggal_jatuh_tempo <=', $this->input->post('tanggal_jatuh_tempo_filter_end'));
            $this->db->group_end();
        }

        if ($id_customer_filter != null) {
            if ($dealer != null) {
                if ($dealer['tipe_plafon_h3'] == 'gimmick') {
                    $this->db->where('ar.gimmick', 1);
                } else if ($dealer['tipe_plafon_h3'] == 'kpb') {
                    $this->db->where('ar.kpb', 1);
                } else {
                    $this->db->where('ar.id_dealer', $id_customer_filter);
                    $this->db->where('ar.gimmick', 0);
                    $this->db->where('ar.kpb', 0);
                }
            }
        }

        if ($this->input->post('tanggal_batas_akhir_referensi') != null) {
            $this->db->where('ar.tanggal_jatuh_tempo <=', $this->input->post('tanggal_batas_akhir_referensi'));
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ar.nama_customer', 'asc');
            $this->db->order_by('ar.referensi', 'asc');
            $this->db->order_by('ar.tanggal_jatuh_tempo', 'asc');
        }
    }

    public function limit()
    {
        if ($_POST["length"] != -1) $this->db->limit($_POST['length'], $_POST['start']);
    }

    public function recordsFiltered()
    {
        $this->make_datatables();
        return $this->db->count_all_results();
    }

    public function recordsTotal()
    {
        $this->make_query();
        return $this->db->count_all_results();
    }
}
