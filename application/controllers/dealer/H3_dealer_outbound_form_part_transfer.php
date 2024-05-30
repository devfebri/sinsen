<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_outbound_form_part_transfer extends Honda_Controller
{
    protected $folder = "dealer";
    protected $page   = "h3_dealer_outbound_form_part_transfer";
    protected $title  = "Outbound Form Part Transfer";

    public function __construct(){
        parent::__construct();
        //---- cek session -------//
        $name = $this->session->userdata('nama');

        if ($name=="") {
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
        }

        $this->load->database();
        $this->load->helper('url');
        //===== Load Model =====
        $this->load->model('m_admin');
        $this->load->library('form_validation');
        $this->load->model('h3_dealer_outbound_form_part_transfer_model', 'outbound_form_part_transfer');
        $this->load->model('h3_dealer_outbound_form_part_transfer_parts_model', 'outbound_form_part_transfer_parts');
        $this->load->model('H3_dealer_gudang_h23_model', 'gudang');
        $this->load->model('H3_dealer_lokasi_rak_bin_model', 'rak');
        $this->load->model('Ms_part_model', 'part');
        $this->load->model('notifikasi_model', 'notifikasi');
        $this->load->model('h3_dealer_stock_model', 'stock');
        $this->load->model('h3_dealer_transaksi_stok_model', 'transaksi_stok');
    }

    public function index(){
        $data['set']	= "index";
        $data['outbound_form_part_transfer'] = $this->outbound_form_part_transfer->get([
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);
        $this->template($data);
    }

    public function add(){
        $data['kode_md'] = 'E22';
        $data['mode']    = 'insert';
        $data['set']     = "form";
        $data['gudang'] = $this->gudang->all();

        $this->template($data);
    }

    public function save(){
        $this->db->trans_start();
        $master = array_merge($this->input->post(['tipe', 'alasan', 'id_gudang']), [
            'id_outbound_form_part_transfer' => $this->outbound_form_part_transfer->generateID(),
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);
        $this->outbound_form_part_transfer->insert($master);
        $items = $this->getOnly(true, $this->input->post('parts'), [
            'id_outbound_form_part_transfer' => $master['id_outbound_form_part_transfer']
        ]);

        $this->outbound_form_part_transfer_parts->insert_batch($items);

        $menu = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'outbound_form_part_transfer')->get()->row();
        $this->notifikasi->insert([
            'id_notif_kat' => $menu->id_notif_kat,
            'judul' => $menu->nama_kategori,
            'pesan' => "Terdapat request Outbound Form Part Transfer no {$master['id_outbound_form_part_transfer']} dari Part Counter untuk pemindahan part. Status Outbound Form saat ini adalah Open",
            'link' => "{$menu->link}/detail?k={$master['id_outbound_form_part_transfer']}",
            'id_dealer' => $this->m_admin->cari_dealer(),
            'show_popup' => $menu->popup == 1,
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $result = $this->outbound_form_part_transfer->find($master['id_outbound_form_part_transfer'], 'id_outbound_form_part_transfer');
            send_json($result);
        } else {
            $this->output->set_status_header(500);
        }
    }
    
    public function detail(){
        $data['mode']  = 'detail';
        $data['set']   = "form";
        $outbound_form_part_transfer = $this->db
        ->select('opt.*')
        ->select('date_format(opt.created_at, "%d-%m-%Y") as created_at')
        ->select('date_format(opt.tanggal_in_transit, "%d-%m-%Y") as tanggal_in_transit')
        ->select('date_format(opt.tanggal_closed, "%d-%m-%Y") as tanggal_closed')
        ->from('tr_h3_dealer_outbound_form_part_transfer as opt')
        ->where('opt.id_outbound_form_part_transfer', $this->input->get('k'))
        ->limit(1)
        ->get()->row();

        $data['gudang'] = $this->db
        ->from('ms_gudang_h23 as g')
        ->where('g.id_gudang', $outbound_form_part_transfer->id_gudang)
        ->get()->row();

        $data['outbound_form_part_transfer'] = $outbound_form_part_transfer;
        $data['outbound_form_part_transfer_parts'] = $this->db
        ->select('ofptp.*')
        ->select('p.nama_part')
        ->from('tr_h3_dealer_outbound_form_part_transfer_parts as ofptp')
        ->join('ms_part as p', 'p.id_part = ofptp.id_part')
        ->where('ofptp.id_outbound_form_part_transfer', $outbound_form_part_transfer->id_outbound_form_part_transfer)
        ->get()->result();

        $this->template($data);
    }

    public function edit(){
        $data['set']	= "form";
        $data['mode']  = 'edit';
        $outbound_form_part_transfer = $this->db
        ->select('opt.*')
        ->select('date_format(opt.created_at, "%d-%m-%Y") as created_at')
        ->select('date_format(opt.tanggal_in_transit, "%d-%m-%Y") as tanggal_in_transit')
        ->select('date_format(opt.tanggal_closed, "%d-%m-%Y") as tanggal_closed')
        ->from('tr_h3_dealer_outbound_form_part_transfer as opt')
        ->where('opt.id_outbound_form_part_transfer', $this->input->get('k'))
        ->limit(1)
        ->get()->row();

        $data['gudang'] = $this->db
        ->from('ms_gudang_h23 as g')
        ->where('g.id_gudang', $outbound_form_part_transfer->id_gudang)
        ->get()->row();

        $data['outbound_form_part_transfer'] = $outbound_form_part_transfer;
        $data['outbound_form_part_transfer_parts'] = $this->db
        ->select('ofptp.*')
        ->select('p.nama_part')
        ->from('tr_h3_dealer_outbound_form_part_transfer_parts as ofptp')
        ->join('ms_part as p', 'p.id_part = ofptp.id_part')
        ->where('ofptp.id_outbound_form_part_transfer', $outbound_form_part_transfer->id_outbound_form_part_transfer)
        ->get()->result();

        $this->template($data);
    }

    public function update(){
        $this->db->trans_start();
        $master = $this->input->post(['tipe', 'alasan', 'id_gudang']);
        $this->outbound_form_part_transfer->update($master, $this->input->post(['id_outbound_form_part_transfer']));
        $items = $this->getOnly(true, $this->input->post('parts'), $this->input->post(['id_outbound_form_part_transfer']));
        $this->outbound_form_part_transfer_parts->update_batch($items, $this->input->post(['id_outbound_form_part_transfer']));
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $result = $this->outbound_form_part_transfer->find($this->input->post('id_outbound_form_part_transfer'), 'id_outbound_form_part_transfer');
            send_json($result);
        } else {
            $this->output->set_status_header(500);
        }
    }

    public function transit(){
        $this->db->trans_start();
        $this->outbound_form_part_transfer->update([
            'status' => 'In Transit',
            'tanggal_in_transit' => date('Y-m-d H:i:s')
        ], [
            'id_outbound_form_part_transfer' => $this->input->get('k')
        ]);

        $parts = $this->outbound_form_part_transfer_parts->get([
            'id_outbound_form_part_transfer' => $this->input->get('k')
        ]);

        foreach ($parts as $part) {
            $transaksi_stock = [
                'id_part' => $part->id_part,
                'id_gudang' => $part->id_gudang,
                'id_rak' => $part->id_rak,
                'tipe_transaksi' => '-',
                'sumber_transaksi' => $this->page,
                'referensi' => $this->input->get('k'),
                'stok_value' => $part->kuantitas,
            ];
            $this->transaksi_stok->insert($transaksi_stock);

            $this->db->set('stock', "stock - {$part->kuantitas}", FALSE)
            ->where('id_part', $part->id_part)
            ->where('id_gudang', $part->id_gudang)
            ->where('id_rak', $part->id_rak)
            ->where('id_dealer', $this->m_admin->cari_dealer())
            ->update('ms_h3_dealer_stock');
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $result = $this->outbound_form_part_transfer->find($this->input->get('k'), 'id_outbound_form_part_transfer');
            send_json($result);
        } else {
            $this->output->set_status_header(500);
        }
    }

    public function reject(){
        $this->db->trans_start();
        $this->outbound_form_part_transfer->update([
            'status' => 'Rejected',
            'keterangan' => $this->input->post('keterangan')
        ], $this->input->post(['id_outbound_form_part_transfer']));

        $menu = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'reject_outbound_part_transfer')->get()->row();
        $this->notifikasi->insert([
            'id_notif_kat' => $menu->id_notif_kat,
            'judul' => $menu->nama_kategori,
            'pesan' => "Outbound Form Part Transfer no {$this->input->post('id_outbound_form_part_transfer')} di reject oleh Warehouse",
            'link' => "{$menu->link}/detail?k={$this->input->post('id_outbound_form_part_transfer')}",
            'id_dealer' => $this->m_admin->cari_dealer(),
            'show_popup' => $menu->popup == 1,
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $result = $this->outbound_form_part_transfer->get($this->input->post(['id_outbound_form_part_transfer']), true);
            send_json($result);
        } else {
            $this->output->set_status_header(500);
        }
    }

    public function reopen(){
        $this->db->trans_start();
        $this->outbound_form_part_transfer->update([
            'status' => 'Open',
            'keterangan' => null
        ], $this->input->get(['id_outbound_form_part_transfer']));

        $menu = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'reopen_outbound_part_transfer')->get()->row();
        $this->notifikasi->insert([
            'id_notif_kat' => $menu->id_notif_kat,
            'judul' => $menu->nama_kategori,
            'pesan' => "Outbound Form Part Transfer no {$this->input->get('id_outbound_form_part_transfer')} di reject oleh Warehouse",
            'link' => "{$menu->link}/detail?k={$this->input->get('id_outbound_form_part_transfer')}",
            'id_dealer' => $this->m_admin->cari_dealer(),
            'show_popup' => $menu->popup == 1,
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $result = $this->outbound_form_part_transfer->get($this->input->get(['id_outbound_form_part_transfer']), true);
            send_json($result);
        } else {
            $this->output->set_status_header(500);
        }
    }

    public function close(){
        $this->db->trans_start();
        $this->outbound_form_part_transfer->update([
            'status' => 'Closed',
            'tanggal_closed' => date('Y-m-d H:i:s')
        ], [
            'id_outbound_form_part_transfer' => $this->input->get('k')
        ]);

        $parts = $this->outbound_form_part_transfer_parts->get([
            'id_outbound_form_part_transfer' => $this->input->get('k')
        ]);

         //Beri kondisi antara POS dan Between Warehouse 
        if($this->input->get('tipe') == 'POS'){
            foreach ($parts as $part) {
                $id_dealer_tujuan = $this->db->select('id_dealer')
                        ->from('ms_lokasi_rak_bin')
                        ->where('id_gudang',$part->id_gudang_tujuan)
                        ->where('id_rak',$part->id_rak_tujuan)
                        ->get()->row();

                $id_part_int = $this->db->select('id_part_int')
                        ->from('ms_part')
                        ->where('id_part',$part->id_part)
                        ->get()->row();
           
                $transaksi_stock = [
                    'id_part' => $part->id_part,
                    'id_gudang' => $part->id_gudang_tujuan,
                    'id_rak' => $part->id_rak_tujuan,
                    'tipe_transaksi' => '+',
                    'sumber_transaksi' => $this->page,
                    'referensi' => $this->input->get('k'),
                    'stok_value' => $part->kuantitas,
                    'id_dealer' => $id_dealer_tujuan->id_dealer,
                ];
                $this->transaksi_stok->insert($transaksi_stock);
    
                $stockDigudang = $this->stock->get([
                    'id_part' => $part->id_part,
                    'id_gudang' => $part->id_gudang_tujuan,
                    'id_rak' => $part->id_rak_tujuan
                ], true);
    
                if($stockDigudang != null){
                    $this->db->set('stock', "stock + {$part->kuantitas}", FALSE)
                    ->where('id_part', $part->id_part)
                    ->where('id_gudang', $part->id_gudang_tujuan)
                    ->where('id_rak', $part->id_rak_tujuan)
                    ->update('ms_h3_dealer_stock');
                }else{
                    $this->stock->insert([
                        'id_part' => $part->id_part,
                        'id_part_int' => $id_part_int->id_part_int,
                        'id_gudang' => $part->id_gudang_tujuan,
                        'id_rak' => $part->id_rak_tujuan,
                        'id_dealer' => $id_dealer_tujuan->id_dealer,
                        'stock' => $part->kuantitas
                    ]);
                }
            }
        }else{
            foreach ($parts as $part) {
                $transaksi_stock = [
                    'id_part' => $part->id_part,
                    'id_gudang' => $part->id_gudang_tujuan,
                    'id_rak' => $part->id_rak_tujuan,
                    'tipe_transaksi' => '+',
                    'sumber_transaksi' => $this->page,
                    'referensi' => $this->input->get('k'),
                    'stok_value' => $part->kuantitas,
                ];
                $this->transaksi_stok->insert($transaksi_stock);
    
                $stockDigudang = $this->stock->get([
                    'id_part' => $part->id_part,
                    'id_gudang' => $part->id_gudang_tujuan,
                    'id_rak' => $part->id_rak_tujuan
                ], true);
    
                if($stockDigudang != null){
                    $this->db->set('stock', "stock + {$part->kuantitas}", FALSE)
                    ->where('id_part', $part->id_part)
                    ->where('id_gudang', $part->id_gudang_tujuan)
                    ->where('id_rak', $part->id_rak_tujuan)
                    ->update('ms_h3_dealer_stock');
                }else{
                    $id_dealer = $this->m_admin->cari_dealer();
                    $get_info_part = $this->db->query("SELECT id_part_int, id_part, nama_part from ms_part where id_part = '$part->id_part' ")->row();
                    $get_info_gudang = $this->db->query("SELECT id, id_gudang,id_dealer from ms_gudang_h23 WHERE id_dealer =$id_dealer and id_gudang ='$part->id_gudang_tujuan'")->row();
                    $get_info_rak = $this->db->query("SELECT id, id_rak, id_gudang, id_dealer FROM ms_lokasi_rak_bin WHERE id_dealer = $id_dealer and id_gudang ='$part->id_gudang_tujuan' AND id_rak ='$part->id_rak_tujuan'")->row();
    
                    $this->stock->insert([
                        'id_part' => $part->id_part,
                        'id_part_int' => $get_info_part->id_part_int,
                        'id_gudang_int' => $get_info_gudang->id,
                        'id_rak_int' => $get_info_rak->id,
                        'id_gudang' => $part->id_gudang_tujuan,
                        'id_rak' => $part->id_rak_tujuan,
                        'id_dealer' => $id_dealer,
                        'stock' => $part->kuantitas
                    ]);
                }
            }
        }


        // foreach ($parts as $part) {
        //     $transaksi_stock = [
        //         'id_part' => $part->id_part,
        //         'id_gudang' => $part->id_gudang_tujuan,
        //         'id_rak' => $part->id_rak_tujuan,
        //         'tipe_transaksi' => '+',
        //         'sumber_transaksi' => $this->page,
        //         'referensi' => $this->input->get('k'),
        //         'stok_value' => $part->kuantitas,
        //     ];
        //     $this->transaksi_stok->insert($transaksi_stock);

        //     $stockDigudang = $this->stock->get([
        //         'id_part' => $part->id_part,
        //         'id_gudang' => $part->id_gudang_tujuan,
        //         'id_rak' => $part->id_rak_tujuan
        //     ], true);

        //     if($stockDigudang != null){
        //         $this->db->set('stock', "stock + {$part->kuantitas}", FALSE)
        //         ->where('id_part', $part->id_part)
        //         ->where('id_gudang', $part->id_gudang_tujuan)
        //         ->where('id_rak', $part->id_rak_tujuan)
        //         ->update('ms_h3_dealer_stock');
        //     }else{
        //         $id_dealer = $this->m_admin->cari_dealer();
        //         $get_info_part = $this->db->query("SELECT id_part_int, id_part, nama_part from ms_part where id_part = '$part->id_part' ")->row();
        //         $get_info_gudang = $this->db->query("SELECT id, id_gudang,id_dealer from ms_gudang_h23 WHERE id_dealer =$id_dealer and id_gudang ='$part->id_gudang_tujuan'")->row();
        //         $get_info_rak = $this->db->query("SELECT id, id_rak, id_gudang, id_dealer FROM ms_lokasi_rak_bin WHERE id_dealer = $id_dealer and id_gudang ='$part->id_gudang_tujuan' AND id_rak ='$part->id_rak_tujuan'")->row();

        //         $this->stock->insert([
        //             'id_part' => $part->id_part,
        //             'id_part_int' => $get_info_part->id_part_int,
        //             'id_gudang_int' => $get_info_gudang->id,
        //             'id_rak_int' => $get_info_rak->id,
        //             'id_gudang' => $part->id_gudang_tujuan,
        //             'id_rak' => $part->id_rak_tujuan,
        //             'id_dealer' => $id_dealer,
        //             'stock' => $part->kuantitas
        //         ]);
        //     }
        // }
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $result = $this->outbound_form_part_transfer->find($this->input->get('k'), 'id_outbound_form_part_transfer');
            send_json($result);
        } else {
            $this->output->set_status_header(500);
        }
    }

    public function report()
	{
        $this->load->library('mpdf_l');
        $mpdf                           = $this->mpdf_l->load();
        $mpdf->allow_charset_conversion = true;  // Set by default to TRUE
        $mpdf->charset_in               = 'UTF-8';
        $mpdf->autoLangToFont           = true;

        $data = [];

        $data['outbound'] = $this->db
        ->select('o.id_outbound_form_part_transfer as id')
        ->select('date_format(o.created_at, "%d-%m-%Y") as tanggal_request')
        ->select('o.tipe')
        ->select('
            case 
                when o.tanggal_in_transit is not null then date_format(o.tanggal_in_transit, "%d-%m-%Y")
                else "-"
            end as tanggal_transit', false)
        ->select('
            case 
                when o.tanggal_closed is not null then date_format(o.tanggal_closed, "%d-%m-%Y")
                else "-"
            end as tanggal_closed', false)
        ->select('o.alasan')
        ->select('o.id_gudang as gudang_asal')
        ->select('o.status')
        ->from('tr_h3_dealer_outbound_form_part_transfer as o')
        ->where('o.id_outbound_form_part_transfer', $this->input->get('k'))
        ->limit(1)
        ->get()->row();

        $data['parts'] = $this->db
        ->select('op.*')
        ->select('p.nama_part')
        ->from('tr_h3_dealer_outbound_form_part_transfer_parts as op')
        ->join('ms_part as p', 'p.id_part = op.id_part')
        ->where('op.id_outbound_form_part_transfer', $this->input->get('k'))
        ->get()->result();
        
        $html = $this->load->view('dealer/h3_dealer_report_outbound_part_transfer', $data, true);
        // render the view into HTML
        $mpdf->WriteHTML($html);
        // write the HTML into the mpdf
        $mpdf->Output("Report outbound form part transfer.pdf", 'I');
	}
}
