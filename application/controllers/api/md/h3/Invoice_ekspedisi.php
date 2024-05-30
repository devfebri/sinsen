<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_ekspedisi extends CI_Controller

{
    public function index()
    {
        $this->make_datatables();
        $this->limit();

        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_index_h3_md_invoice_ekspedisi', [
                'id' => $row['id']
            ], true);
            $row['index'] = $this->input->post('start') + $index;

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
        $this->db
        ->select('ie.id')
        ->select('ie.no_invoice_ekspedisi')
        ->select('date_format(ie.created_at, "%d-%m-%Y") as tanggal_invoice')
        ->select('ie.referensi')
        ->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then date_format(pb.created_at, "%d-%m-%Y") 
                when ie.tipe_referensi = "penerimaan_po_vendor" then date_format(ppv.tanggal, "%d-%m-%Y") 
            end as tanggal_penerimaan
        ', false)
        ->select('e.nama_ekspedisi')
        ->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then pb.no_surat_jalan_ekspedisi
                when ie.tipe_referensi = "penerimaan_po_vendor" then ppv.surat_jalan_ekspedisi
            end as no_surat_jalan_ekspedisi
        ', false)
        ->select('
            case
                when ie.tipe_referensi = "penerimaan_barang" then 
                    case
                        when pb.jenis_ongkos_angkut_part = "Berat" then concat(pb.berat_truk, " Kg")
                        when pb.jenis_ongkos_angkut_part = "Truk" then concat(pb.berat_truk, " Truk")
                        when pb.jenis_ongkos_angkut_part = "Volume" then concat(pb.berat_truk, " M^3")
                        when pb.jenis_ongkos_angkut_part = "Udara" then concat(pb.berat_truk, " Kg")
                        else pb.berat_truk
                    end
                when ie.tipe_referensi = "penerimaan_po_vendor" then 
                    case
                        when ppv.jenis_ongkos_angkut_part = "Berat" then concat(ppv.berat_truk, " Kg")
                        when ppv.jenis_ongkos_angkut_part = "Truk" then concat(ppv.berat_truk, " Truk")
                        when ppv.jenis_ongkos_angkut_part = "Volume" then concat(ppv.berat_truk, " M^3")
                        when ppv.jenis_ongkos_angkut_part = "Udara" then concat(ppv.berat_truk, " Kg")
                        else ppv.berat_truk
                    end
            end as berat_truk
        ', false)
        ->select('ie.grand_total')
        ->from('tr_h3_md_invoice_ekspedisi as ie')
        ->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_penerimaan_barang = ie.referensi', 'left')
        ->join('tr_h3_md_penerimaan_po_vendor as ppv', 'ppv.id_penerimaan_po_vendor = ie.referensi', 'left')
        ->join('ms_h3_md_ekspedisi as e', '(e.id = pb.id_vendor OR e.id = ppv.id_ekspedisi)', 'left')
        ;

        if($this->input->post('history') != null AND $this->input->post('history') == 1){
            // $this->db->where('ie.status !=', 'Open');
            $this->db->group_start();
            $this->db->where('ie.status !=', 'Open');
            $this->db->or_where('left(ie.created_at,10) <=', '2023-09-30');
            $this->db->group_end();
        }else{
            // $this->db->where('ie.status', 'Open');
            $this->db->group_start();
            $this->db->where('ie.status', 'Open');
            $this->db->where('left(ie.created_at,10) >', '2023-10-01');
            $this->db->group_end();
        }
    }

    public function make_datatables()
    {
        $this->make_query();

        if ($this->input->post('no_surat_jalan_filter') != null) {
            $this->db->like('pb.no_surat_jalan_ekspedisi', trim($this->input->post('no_surat_jalan_filter')));
        }

        if ($this->input->post('no_penerimaan_filter') != null) {
            $this->db->like('ie.referensi', trim($this->input->post('no_penerimaan_filter')));
        }

        if ($this->input->post('filter_jenis_satuan_ongkos_angkut') != null) {
            $this->db->where('pb.jenis_ongkos_angkut_part', $this->input->post('filter_jenis_satuan_ongkos_angkut'));
        }

        if ($this->input->post('id_ekspedisi_filter') != null) {
            $this->db->where('pb.id_vendor', $this->input->post('id_ekspedisi_filter'));
        }

        if($this->input->post('periode_invoice_filter_start') != null and $this->input->post('periode_invoice_filter_end') != null){            
            $this->db->group_start();
            $this->db->where('ie.tanggal_invoice >=', $this->input->post('periode_invoice_filter_start'));
            $this->db->where('ie.tanggal_invoice <=', $this->input->post('periode_invoice_filter_end'));
            $this->db->group_end();
        }

        if (isset($_POST['order'])) {
            $indexColumn = $_POST['order']['0']['column'];
            $name = $_POST['columns'][$indexColumn]['name'];
            $data = $_POST['columns'][$indexColumn]['data'];
            $this->db->order_by( $name != '' ? $name : $data , $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('ie.created_at', 'desc');
        }
    }

    public function limit(){
        if ($this->input->post('length') != - 1) {
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
