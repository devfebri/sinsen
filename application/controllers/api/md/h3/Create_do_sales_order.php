<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Create_do_sales_order extends CI_Controller
{
    private $id_sales_orders = [];

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/action_index_create_do_sales_order_datatable', [
                'id' => $row['id_sales_order'],
                'so_tidak_diizinkan_batal' => $row['so_tidak_diizinkan_batal'] == 1
            ], true);

            $row['no_do'] = $this->load->view('additional/action_view_do_create_do_sales_order', [
                'id' => $row['id_sales_order']
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

    public function get_sales_order_info($produk)
    {
        /*  27-12-2022 : off sementara , boleh dibuka lagi
        $kuantitas_part = $this->db
            ->select('sum(sop.qty_pemenuhan)')
            ->from('tr_h3_md_sales_order_parts as sop')
            ->where('sop.id_sales_order = so.id_sales_order')
            ->get_compiled_select();

        $this->db->select('SUM(IFNULL(so.total_amount, 0)) as amount');
        $this->db->select("SUM(IFNULL(({$kuantitas_part}), 0)) as kuantitas_part");

        $this->make_query();
        $this->filter();
        $this->db->where('so.produk', $produk);
        $this->db->where('so.status !=', 'Canceled');

        $this->db->where('so.tanggal_order between DATE_FORMAT(NOW(), "%Y-%m-01") and LAST_DAY(NOW())');

        $result = $this->db->get()->row_array();

        $data = [];
        $data['amount'] = $result['amount'] != null ? $result['amount'] : 0;
        $data['kuantitas_part'] = $result['kuantitas_part'] != null ? $result['kuantitas_part'] : 0;
        */
        
        $data = [];
        $data['amount'] = 0;
        $data['kuantitas_part'] = 0;
        send_json($data);
    }

    public function make_query()
    {
        if (count($this->input->post('kelompok_part_filter')) > 0) {
            $this->db
                ->distinct()
                ->select('sop.id_sales_order')
                ->from('tr_h3_md_sales_order_parts as sop')
                ->join('ms_part as p', 'p.id_part = sop.id_part');
            $this->db->where_in('p.kelompok_part', $this->input->post('kelompok_part_filter'));
            $this->id_sales_orders = $this->db->get()->result_array();

            $this->id_sales_orders = array_map(function ($data) {
                return $data['id_sales_order'];
            }, $this->id_sales_orders);
        } else {
            $this->id_sales_orders = [];
        }

        $this->db
            ->from('tr_h3_md_sales_order as so')
            ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
            ->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
            ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
            ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
            ->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
            ->where('so.delete_at_create_do_sales_order', 0)
            ->where('so.status !=', 'Canceled')
            ->where('so.status !=', 'Closed');
    }

    public function select_for_datatable()
    {
        $kuantitas_part=0;
        $nilai_do=0;
        /*
        $kuantitas_part = $this->db
            ->select('sum(sop.qty_pemenuhan)')
            ->from('tr_h3_md_sales_order_parts as sop')
            ->where('sop.id_sales_order = so.id_sales_order')
            ->get_compiled_select();
        

        $nilai_do = $this->db
            ->select('sum(do_sq.sub_total)')
            ->from('tr_h3_md_do_sales_order as do_sq')
            ->where('do_sq.id_sales_order = so.id_sales_order')
            ->group_start()
            ->where('do_sq.status !=', 'Canceled')
            ->where('do_sq.status !=', 'Rejected')
            ->group_end()
            ->get_compiled_select();
            */
        $this->db
            ->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
            ->select('so.id_sales_order')
            ->select('so.so_tidak_diizinkan_batal')
            ->select('so.po_type')
            ->select('so.kategori_po')
            ->select('so.produk')
            ->select('d.kode_dealer_md')
            ->select('d.nama_dealer')
            ->select('d.alamat')
            ->select('kab.kabupaten')
            ->select('so.total_amount')
            ->select("IFNULL(({$nilai_do}), 0) as nilai_do")
            ->select("(so.total_amount - IFNULL(({$nilai_do}), 0)) as sisa_do", false)
            ->select('so.status')
            ->select("(IFNULL(({$nilai_do}), 0) / (so.total_amount) * 100) as service_rate", false)
            ->select('so.delete_at_create_do_sales_order')
            ->select("({$kuantitas_part}) as kuantitas_part");
    }

    public function make_datatables()
    {
        $this->select_for_datatable();
        $this->make_query();
        $this->filter();
        $this->order();
        $this->limit();
    }

    public function filter()
    {
        if($this->session->userdata('group') == 72){
            $this->db->group_start();
                $this->db->where('so.id_salesman', $this->session->userdata('id_karyawan_dealer'));
            $this->db->group_end();
        }
        if (count($this->input->post('id_customer_filter')) > 0) {
            $this->db->where_in('so.id_dealer', $this->input->post('id_customer_filter'));
        }

        if ($this->input->post('no_so_filter')) {
            $this->db->like('so.id_sales_order', trim($this->input->post('no_so_filter')));
        }

        if ($this->input->post('periode_sales_filter_start') != null and $this->input->post('periode_sales_filter_end') != null) {
            $this->db->group_start();
            $this->db->where('so.tanggal_order >=', $this->input->post('periode_sales_filter_start'));
            $this->db->where('so.tanggal_order <=', $this->input->post('periode_sales_filter_end'));
            $this->db->group_end();
        }

        if (count($this->input->post('tipe_penjualan_filter')) > 0) {
            $this->db->where_in('so.po_type', $this->input->post('tipe_penjualan_filter'));
        }

        if (count($this->input->post('jenis_dealer_filter')) > 0) {
            if (
                in_array(
                    'H123',
                    $this->input->post('jenis_dealer_filter')
                )
            ) {
                $this->db->where('d.h1', 1);
                $this->db->where('d.h2', 1);
                $this->db->where('d.h3', 1);
            }

            if (
                in_array(
                    'H23',
                    $this->input->post('jenis_dealer_filter')
                )
            ) {
                $this->db->where('d.h2', 0);
                $this->db->where('d.h2', 1);
                $this->db->where('d.h3', 1);
            }

            if (
                in_array(
                    'H3',
                    $this->input->post('jenis_dealer_filter')
                )
            ) {
                $this->db->where('d.h2', 0);
                $this->db->where('d.h2', 0);
                $this->db->where('d.h3', 1);
            }
        }

        if (count($this->input->post('kabupaten_filter')) > 0) {
            $this->db->where_in('kel.id_kelurahan', $this->input->post('kabupaten_filter'));
        }

        if (count($this->input->post('salesman_filter')) > 0) {
            $this->db->where_in('so.id_salesman', $this->input->post('salesman_filter'));
        }

        if (count($this->id_sales_orders) > 0) {
            $this->db->where_in('so.id_sales_order', $this->id_sales_orders);
        }

        if ($this->input->post('kategori_sales_filter')) {
            $this->db->where('so.kategori_po', $this->input->post('kategori_sales_filter'));
        }

        if ($this->input->post('tipe_produk_filter')) {
            $this->db->where('so.produk', $this->input->post('tipe_produk_filter'));
        }

        if ($this->input->post('status_filter')) {
            $this->db->where_in('so.status', $this->input->post('status_filter'));
        }
    }

    public function limit()
    {
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
    }

    public function order()
    {
        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by($name != '' ? $name : $data, $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('so.created_at', 'desc');
        }
    }

    public function recordsFiltered()
    {
        // $this->make_datatables();
        
        $this->db
            ->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
            ->select('so.id_sales_order')
            ->select('so.so_tidak_diizinkan_batal')
            ->select('so.po_type')
            ->select('so.kategori_po')
            ->select('so.produk')
            ->select('d.kode_dealer_md')
            ->select('d.nama_dealer')
            ->select('d.alamat')
            ->select('kab.kabupaten')
            ->select('so.total_amount');
        $this->make_query();
        $this->filter();
        return $this->db->count_all_results();
    }

    public function recordsTotal()
    {
        // $this->select_for_datatable();
        $this->db
            ->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
            ->select('so.id_sales_order')
            ->select('so.so_tidak_diizinkan_batal')
            ->select('so.po_type')
            ->select('so.kategori_po')
            ->select('so.produk')
            ->select('d.kode_dealer_md')
            ->select('d.nama_dealer')
            ->select('d.alamat')
            ->select('kab.kabupaten')
            ->select('so.total_amount');
        $this->make_query();
        return $this->db->count_all_results();
    }
}
