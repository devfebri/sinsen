<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Do_sales_order extends CI_Controller
{
    private $id_sales_orders;

    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $nomor = $this->input->post('start') + 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['nomor'] = $nomor . ".";
            $row['action'] = $this->load->view('additional/action_index_do_sales_order_datatable', [
                'id' => $row['id_do_sales_order']
            ], true);
            /*
            $total_revisi = $this->db
                    ->select('dr_sq.total')
                    ->from('tr_h3_md_do_revisi as dr_sq')
                    ->where('dr_sq.id_do_sales_order_int',$row['id_do_sales_order_int'])
                    ->order_by('dr_sq.created_at', 'desc')
                    ->limit(1)
                    ->where('dr_sq.status', 'Approved')
                    ->get()->row_array();

            if($total_revisi['total'] != '' || $total_revisi['total'] != null){
                $row['sub_total_do_awal_formatted'] = $total_revisi['total'];
            }
            */
            $data[] = $row;
            $nomor++;
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
        $kelompok_part_filter = $this->input->post('kelompok_part_filter');
        $this->db
            ->distinct()
            ->select('sop.id_sales_order')
            ->from('tr_h3_md_sales_order_parts as sop')
            // ->join('ms_part as p', 'p.id_part_int = sop.id_part_int'); //Diupdate 20/04/23
            ->join('ms_part as p','p.id_part_int=sop.id_part_int'); 

        if (count($kelompok_part_filter) > 0) {
            $this->db->where_in('p.kelompok_part', $kelompok_part_filter);
        } else {
            $this->db->where('1=0', null, false);
        }
        $this->id_sales_orders = $this->db->get()->result_array();
        $this->id_sales_orders = array_map(function ($data) {
            return $data['id_sales_order'];
        }, $this->id_sales_orders);

        $total_revisi = $this->db
            ->select('dr_sq.total')
            ->from('tr_h3_md_do_revisi as dr_sq')
            ->where('dr_sq.id_do_sales_order = dso.id_do_sales_order')
            ->order_by('dr_sq.created_at', 'desc')
            ->limit(1)
            ->where('dr_sq.status', 'Approved')
            ->get_compiled_select();

        $qty_parts_sales_order = $this->db
            ->select('sum(sop.qty_pemenuhan)')
            ->from('tr_h3_md_sales_order_parts as sop')
            ->where('sop.id_sales_order = so.id_sales_order')
            ->get_compiled_select();

        $qty_parts_do_sales_order = $this->db
            ->select('sum(dop.qty_supply)')
            ->from('tr_h3_md_do_sales_order_parts as dop')
            ->where('dop.id_do_sales_order = dso.id_do_sales_order')
            ->get_compiled_select();

        $this->db
            // ->select("IFNULL( ({$qty_parts_sales_order}), 0) as qty_parts_sales_order")
            // ->select("IFNULL( ({$qty_parts_do_sales_order}), 0) as qty_parts_do_sales_order")
            ->select('date_format(so.tanggal_order, "%d-%m-%Y") as tanggal_so')
            ->select('so.id_sales_order')
            ->select('date_format(dso.tanggal, "%d-%m-%Y") as tanggal_do')
            // ->select('dso.id as id_do_sales_order_int')
            ->select('dso.id_do_sales_order')
            ->select('d.kode_dealer_md')
            ->select('d.nama_dealer')
            ->select('kab.kabupaten')
            ->select('so.total_amount as total_so')
            ->select('dso.total as total_do')
            ->select('dso.total as total_do')
        //     ->select('
        // concat(
        //     "Rp ",
        //     format(so.total_amount, 0, "ID_id")
        // )
        // as total_so_formatted', false)
        //     ->select("
        // case
        //     when ({$total_revisi}) is not null then ({$total_revisi})
        //     else dso.total
        // end as sub_total_do_awal
        // ", false)
            ->select("
        case
            when ({$total_revisi}) is not null then concat(
                'Rp ',
                format( ({$total_revisi}), 0, 'ID_id')
            )
            else concat(
                'Rp ',
                format( dso.total, 0, 'ID_id')
            )
        end as sub_total_do_awal_formatted
        ", false)
            ->select("
        case
            when ({$total_revisi}) is not null then concat(
                'Rp ',
                format( dso.total, 0, 'ID_id')
            )
            else '-'
        end as sub_total_do_rev_formatted
        ", false)
            ->select("
        concat(
            round( (dso.total / so.total_amount) * 100 ),
            '%'
        ) as service_rate
        ", false)
        //     ->select("
        // case
        //     when ({$total_revisi}) is null then concat(
        //         'Rp ',
        //         format( so.total_amount - dso.total, 0, 'ID_id')
        //     )
        //     else concat(
        //         'Rp ',
        //         format( ({$total_revisi}) - dso.total, 0, 'ID_id')
        //     )
        // end as sisa_nilai_do_formatted
        // ", false)
            ->select('dso.status')
            ->select('so.produk')
            ->from('tr_h3_md_do_sales_order as dso')
            ->join('tr_h3_md_sales_order as so', 'so.id_sales_order = dso.id_sales_order')
            ->join('ms_dealer as d', 'd.id_dealer = so.id_dealer')
            ->join('ms_kelurahan as kel', 'kel.id_kelurahan = d.id_kelurahan', 'left')
            ->join('ms_kecamatan as kec', 'kec.id_kecamatan = kel.id_kecamatan', 'left')
            ->join('ms_kabupaten as kab', 'kab.id_kabupaten = kec.id_kabupaten', 'left')
            ->join('ms_karyawan as k', 'k.id_karyawan = so.id_salesman', 'left')
            ->where('dso.status !=', 'Canceled')
            ->where('dso.status !=', 'Rejected');
        if($this->session->userdata('group') == 72){
            $this->db->group_start();
                $this->db->where('so.id_salesman', $this->session->userdata('id_karyawan_dealer'));
            $this->db->group_end();
        }
        if ($this->input->post('history') != null and $this->input->post('history') == 1) {
            
            $this->db->group_start();
            $this->db->where('dso.sudah_create_faktur', 1);
            $this->db->or_where('left(dso.created_at,10) <=', '2023-09-09');
            $this->db->group_end();
        } else {
            $this->db->group_start();
            $this->db->where('dso.sudah_create_faktur', 0);
            $this->db->where('left(dso.created_at,10) >', '2023-09-09');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if ($this->input->post('customer_filter') != null and count($this->input->post('customer_filter'))) {
            $this->db->where_in('so.id_dealer', $this->input->post('customer_filter'));
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
                $this->db->where('d.h1', 0);
                $this->db->where('d.h2', 1);
                $this->db->where('d.h3', 1);
            }

            if (
                in_array(
                    'H3',
                    $this->input->post('jenis_dealer_filter')
                )
            ) {
                $this->db->where('d.h1', 0);
                $this->db->where('d.h2', 0);
                $this->db->where('d.h3', 1);
            }
        }

        if (count($this->id_sales_orders) > 0) {
            $this->db->where_in('so.id_sales_order', $this->id_sales_orders);
        }

        if (count($this->input->post('kabupaten_filter')) > 0) {
            $this->db->where_in('kab.id_kabupaten', $this->input->post('kabupaten_filter'));
        }

        if ($this->input->post('status_filter') != null and count($this->input->post('status_filter')) > 0) {
            $this->db->where_in('dso.status', $this->input->post('status_filter'));
        }

        if ($this->input->post('id_salesman_filter')) {
            $this->db->where('so.id_salesman', $this->input->post('id_salesman_filter'));
        }

        if ($this->input->post('no_so_filter')) {
            $this->db->like('so.id_sales_order', trim($this->input->post('no_so_filter')));
        }

        if ($this->input->post('no_do_filter')) {
            $this->db->like('dso.id_do_sales_order', trim($this->input->post('no_do_filter')));
        }

        if ($this->input->post('periode_sales_filter_start') != null and $this->input->post('periode_sales_filter_end') != null) {
            $this->db->group_start();
            $this->db->where('so.tanggal_order >=', $this->input->post('periode_sales_filter_start'));
            $this->db->where('so.tanggal_order <=', $this->input->post('periode_sales_filter_end'));
            $this->db->group_end();
        }

        if ($this->input->post('tipe_penjualan_filter')) {
            $this->db->where('so.po_type', $this->input->post('tipe_penjualan_filter'));
        }

        if ($this->input->post('kategori_sales_filter')) {
            $this->db->where('so.kategori_po', $this->input->post('kategori_sales_filter'));
        }

        if ($this->input->post('tipe_produk_filter')) {
            $this->db->where('so.produk', $this->input->post('tipe_produk_filter'));
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('dso.created_at', 'desc');
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
        return $this->db->count_all_results();
    }
}
