<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_picking_slip extends Honda_Controller
{
    public $tables = "tr_h3_dealer_picking_slip";
    public $folder = "dealer";
    public $page   = "h3_dealer_picking_slip";
    public $title  = "Picking Slip";

    public function __construct()
    {
        parent::__construct();
        //---- cek session -------//
        $name = $this->session->userdata('nama');
        if ($name == "") {
            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "panel'>";
        }

        //===== Load Database =====
        $this->load->database();
        $this->load->helper('url');
        //===== Load Model =====
        $this->load->model('m_admin');
        $this->load->library('form_validation');
        $this->load->model('h3_dealer_picking_slip_model', 'picking_slip');
        $this->load->model('h3_dealer_sales_order_model', 'sales_order');
        $this->load->model('h3_dealer_sales_order_parts_model', 'sales_order_parts');
        $this->load->model('ms_part_model', 'ms_part');
        $this->load->model('m_h2_master');
        $this->load->model('notifikasi_model', 'notifikasi');
    }

    public function index()
    {
        $data['set']    = "index";
        $data['picking_slip'] = $this->picking_slip->get([
            'id_dealer' => $this->m_admin->cari_dealer()
        ]);
        $this->template($data);
    }

    public function cetak()
    {
        $id = $this->input->get('id');

        $this->db->trans_start();
        $data = [];
        $data['dealer'] = $this->db
            ->select('d.nama_dealer')
            ->select('d.alamat')
            ->select('d.no_telp')
            ->select('kelurahan.kelurahan')
            ->select('kecamatan.kecamatan')
            ->select('kabupaten.kabupaten')
            ->select('provinsi.provinsi')
            ->from('ms_dealer as d')
            ->join('ms_kelurahan as kelurahan', 'kelurahan.id_kelurahan = d.id_kelurahan')
            ->join('ms_kecamatan as kecamatan', 'kecamatan.id_kecamatan = kelurahan.id_kecamatan')
            ->join('ms_kabupaten as kabupaten', 'kabupaten.id_kabupaten = kecamatan.id_kabupaten')
            ->join('ms_provinsi as provinsi', 'provinsi.id_provinsi = kabupaten.id_provinsi')
            ->where('d.id_dealer', $this->m_admin->cari_dealer())
            ->limit(1)->get()->row_array();

        $picking_slip = $this->db
            ->select('ps.nomor_ps')
            ->select('so.nomor_so')
            ->select('date_format(so.created_at, "%d-%m-%Y %H:%i") as tanggal_so')
            ->select('so.nama_pembeli')
            ->select('so.no_hp_pembeli')
            ->select('so.alamat_pembeli')
            ->select('c.nama_customer')
            ->select('so.id_work_order')
            ->select("
            case 
                when so.id_customer is not null then c.no_polisi
                else '-'
            end as no_polisi
        ", false)
            ->from('tr_h3_dealer_picking_slip as ps')
            ->join('tr_h3_dealer_sales_order as so', 'ps.nomor_so_int = so.id')
            ->join('ms_customer_h23 as c', 'c.id_customer = so.id_customer', 'left')
            ->where('ps.id', $id)
            ->limit(1)
            ->get()->row_array();

        if ($picking_slip == null) throw new Exception('Data tidak ditemukan');

        $data['picking_slip'] = $picking_slip;

        $data['parts'] = $this->db
            ->select('p.id_part')
            ->select('p.nama_part')
            ->select('sop.id_gudang')
            ->select('sop.id_rak')
            ->select('sop.kuantitas')
            ->select('
            case
                when sop.return = 1 then sop.kuantitas_return
                else 0
            end as kuantitas_return
        ', false)
            ->select('dsos.serial_number')
            ->select('(CASE WHEN dsos.serial_number is not null then "ev" else "" end) as ev')
            ->select('dsos.is_return as is_return_ev')
            ->from('tr_h3_dealer_picking_slip as ps')
            ->join('tr_h3_dealer_sales_order as so', 'ps.nomor_so_int = so.id')
            ->join('tr_h3_dealer_sales_order_parts as sop', 'so.id = sop.nomor_so_int')
            ->join('ms_part as p', 'p.id_part_int = sop.id_part_int')
            ->join('tr_h3_dealer_sales_order_serial_ev dsos','sop.id_part_int = dsos.id_part_int and dsos.nomor_so_int = so.id','left')
            ->where('ps.id', $id)
            ->get()->result_array();

        $data['user'] = $this->db
            ->select('kd.nama_lengkap')
            ->from('ms_user as u')
            ->join('ms_karyawan_dealer as kd', 'kd.id_karyawan_dealer = u.id_karyawan_dealer')
            ->where('u.id_user', $this->session->userdata('id_user'))
            ->limit(1)
            ->get()->row_array()['nama_lengkap'];

        // Buat good issue jika picking slip berasal dari work order.
        // $picking_slip_berasal_dari_wo = $sales_order->id_work_order != null;

        // if($picking_slip_berasal_dari_wo){
        //     $belum_buat_good_issue = $this->db->from('tr_h2_kirim_ke_part_counter as a')->where([
        //         'nomor_so' => $sales_order->nomor_so,
        //         'good_issue_id' => null,
        //     ])->get()->row() != null;

        //     if($belum_buat_good_issue){
        //         $this->db->update('tr_h2_kirim_ke_part_counter', [
        //             'good_issue_id' => $this->m_h2_master->get_good_issue_id(),
        //         ], [
        //             'nomor_so' => $sales_order->nomor_so
        //         ]);
        //     }
        // }

        $this->picking_slip->update([
            'sudah_cetak' => 1
        ], [
            'nomor_ps' => $this->input->get('k'),
            'sudah_cetak' => 0
        ]);

        $this->db->trans_complete();
        // $this->load->library('mpdf_l');

        require_once APPPATH . 'third_party/mpdf/mpdf.php';

        // Require composer autoload
        $mpdf = new Mpdf();
        // Write some HTML code:
        $html = $this->load->view('dealer/h3_cetak_picking_slip', $data, true);
        $mpdf->WriteHTML($html);

        // Output a PDF file directly to the browser
        $mpdf->Output("{$data['picking_slip']->nomor_ps}.pdf", "I");
    }

    public function close()
    {
        $this->db->trans_start();
        $this->picking_slip->update([
            'status' => 'Closed'
        ], [
            'id' => $this->input->get('id'),
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_flashdata('pesan', 'Picking Slip berhasil diclose');
            $this->session->set_flashdata('tipe', 'info');
            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/$this->page/detail?id={$this->input->get('id')}'>";
        } else {
            $this->session->set_flashdata('pesan', 'Picking Slip tidak berhasil diclose');
            $this->session->set_flashdata('tipe', 'info');
            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/$this->page'>";
        }
    }

    public function cancel()
    {
        $picking_slip = $this->picking_slip->find($this->input->get('id'));

        $this->db->trans_start();
        $this->picking_slip->update([
            'status' => 'Canceled'
        ], [
            'id' => $this->input->get('id'),
        ]);

        $this->sales_order->update([
            'status' => 'Canceled',
        ], [
            'id' => $picking_slip->nomor_so_int
        ]);

        //Cek no SO 
        $no_so = $this->db->query("SELECT nomor_so from tr_h3_dealer_picking_slip WHERE nomor_so_int = $picking_slip->nomor_so_int")->row_array();

        $cek = $this->db->query("SELECT COUNT(no_so_wo_booking) AS no_so_wo_booking FROM tr_h3_serial_ev_tracking WHERE no_so_wo_booking = '".$no_so['nomor_so']."'", array($no_so['nomor_so']))->row_array();
    
        if ($cek['no_so_wo_booking'] > 0) {
            $this->db->set('no_so_wo_booking', null);
            $this->db->set('id_customer', null);
            $this->db->set('nama_customer', null);
            $this->db->set('no_hp', null);
            $this->db->set('no_mesin', null);
            $this->db->set('no_rangka', null);
            $this->db->where('no_so_wo_booking',$no_so['nomor_so']);
            $this->db->update('tr_h3_serial_ev_tracking');

            $this->db->where('nomor_so', $no_so['nomor_so']);
            $this->db->delete('tr_h3_dealer_sales_order_serial_ev');
        }

        $notif = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'picking_slip_canceled')->get()->row();
        $this->notifikasi->insert([
            'id_notif_kat' => $notif->id_notif_kat,
            'judul' => $notif->nama_kategori,
            'pesan' => "Picking Slip untuk Sales Order no {$picking_slip->nomor_so} telah dibatalkan oleh pihak Warehouse.",
            'link' => "{$notif->link}/detail?k={$picking_slip->nomor_so}",
            'id_dealer' => $this->m_admin->cari_dealer(),
            'show_popup' => $notif->popup,
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_flashdata('pesan', 'Picking Slip berhasil diclose');
            $this->session->set_flashdata('tipe', 'info');
            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/$this->page/detail?id={$this->input->get('id')}'>";
        } else {
            $this->session->set_flashdata('pesan', 'Picking Slip tidak berhasil diclose');
            $this->session->set_flashdata('tipe', 'info');
            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/$this->page'>";
        }
    }

    public function process_to_nsc()
    {
        $this->db->trans_start();
        $this->picking_slip->update([
            'status' => 'Process to NSC'
        ], [
            'id' => $this->input->get('id'),
        ]);
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_flashdata('pesan', 'Picking Slip berhasil diproses ke NSC');
            $this->session->set_flashdata('tipe', 'info');
            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/$this->page/detail?id={$this->input->get('id')}'>";
        } else {
            $this->session->set_flashdata('pesan', 'Picking Slip tidak berhasil diproses ke NSC');
            $this->session->set_flashdata('tipe', 'info');
            echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "dealer/$this->page'>";
        }
    }

    public function detail()
    {
        $id = $this->input->get('id');

        $data['mode']  = 'detail';
        $data['set']   = "form";

        $picking_slip = $this->db
            ->select('ps.*')
            ->select('so.nomor_so')
            ->select('
            case
                when so.id_work_order is null then 1
                else wodw.stats = "end"
            end as wo_end
        ', false)
            ->from('tr_h3_dealer_picking_slip as ps')
            ->join('tr_h3_dealer_sales_order as so', 'so.nomor_so = ps.nomor_so')
            ->join('tr_h2_wo_dealer_waktu as wodw', 'wodw.id_work_order = so.id_work_order', 'left')
            ->where('ps.id', $id)
            ->get()->row_array();

        if ($picking_slip == null) throw new Exception('Data tidak ditemukan');

        $data['picking_slip'] = $picking_slip;

        $data['picking_slip_parts'] = $this->db
            ->select('sop.*, p.nama_part, s.satuan')
            ->from('tr_h3_dealer_sales_order_parts as sop')
            ->join('ms_part as p', 'p.id_part_int = sop.id_part_int')
            ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
            ->where('sop.nomor_so_int', $picking_slip['nomor_so_int'])
            ->get()->result_array();

        $data['sales_order_ev'] = $this->db->select('dsos.*')
            ->select('mp.nama_part')
            ->from('tr_h3_dealer_sales_order_serial_ev dsos')
            ->join('ms_part mp', 'mp.id_part_int = dsos.id_part_int')
            ->where('dsos.nomor_so_int',$picking_slip['nomor_so_int'])
            ->get()->result_array();

        $this->template($data);
    }

    public function edit()
    {
        $id = $this->input->get('id');

        $data['mode']  = 'edit';
        $data['set']   = "form";

        $picking_slip = $this->db
            ->select('ps.*')
            ->select('so.nomor_so')
            ->select('
            case
                when so.id_work_order is null then 1
                else wodw.stats = "end"
            end as wo_end
        ', false)
            ->from('tr_h3_dealer_picking_slip as ps')
            ->join('tr_h3_dealer_sales_order as so', 'so.id = ps.nomor_so_int')
            ->join('tr_h2_wo_dealer_waktu as wodw', 'wodw.id_work_order = so.id_work_order', 'left')
            ->where('ps.id', $id)
            ->get()->row_array();

        if ($picking_slip == null) throw new Exception('Data tidak ditemukan');

        $data['picking_slip'] = $picking_slip;

        $data['picking_slip_parts'] = $this->db
            ->select('sop.*, p.nama_part, s.satuan')
            ->select('sop.return')
            ->select('sop.kuantitas_return')
            ->from('tr_h3_dealer_sales_order_parts as sop')
            ->join('ms_part as p', 'p.id_part_int = sop.id_part_int')
            ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
            ->where('sop.nomor_so_int', $picking_slip['nomor_so_int'])
            ->get()->result_array();

        $data['sales_order_ev'] = $this->db->select('dsos.id as id_sn_ev')
                                            ->select('dsos.nomor_so')
                                            ->select('dsos.nomor_so_int')
                                            ->select('dsos.id_part')
                                            ->select('dsos.id_part_int')
                                            ->select('dsos.serial_number')
                                            ->select('dsos.is_return')
                                            ->select('mp.nama_part')
                                            ->from('tr_h3_dealer_sales_order_serial_ev dsos')
                                            ->join('ms_part mp', 'mp.id_part_int = dsos.id_part_int')
                                            ->where('dsos.nomor_so_int',$picking_slip['nomor_so_int'])
                                            ->get()->result_array();

        $this->template($data);
    }

    public function update()
    {
        $this->validate();
        $id = $this->input->post('id');
        $parts = $this->input->post('parts');
        $parts_ev = $this->input->post('parts_ev');

        $picking_slip = (array) $this->picking_slip->find($id);

        if ($picking_slip == null) throw new Exception('Data tidak ditemukan');

        $this->db->trans_start();
        foreach ($parts as $part) {
            $this->sales_order_parts->update([
                'return' => $part['return'],
                'kuantitas_return' => $part['kuantitas_return']
            ], [
                'nomor_so_int' => $picking_slip['nomor_so_int'],
                'id_part' => $part['id_part'],
                'id_gudang' => $part['id_gudang'],
                'id_rak' => $part['id_rak'],
            ]);

            if($part['return'] == 1){
                if(isset($parts_ev)){
                    foreach($parts_ev as $part_ev){
                        if($part_ev['is_return']==1){
                            $this->db->set('no_so_wo_booking',  null)
                                ->set('id_customer', null)
                                ->set('nama_customer', null)
                                ->set('no_hp', null)
                                ->set('no_mesin', null)
                                ->set('no_rangka', null)
                                ->where('id_part_int', $part_ev['id_part_int'])	
                                ->where('serial_number', $part_ev['serial_number'])
                                ->update('tr_h3_serial_ev_tracking');
            
                            $this->db->set('is_return',  1)
                                ->set('updated_return_at',  date('Y-m-d H:i:s', time()))
                                ->set('updated_return_by',  $this->session->userdata('id_user'))
                                ->where('id_part_int', $part_ev['id_part_int'])	
                                ->where('serial_number', $part_ev['serial_number'])
                                ->where('id', $part_ev['id_sn_ev'])
                                ->update('tr_h3_dealer_sales_order_serial_ev');

                        }elseif($part_ev['is_return']==0){
                            $customer_so = $this->db->select('id_customer')
                                        ->select('nama_pembeli')
                                        ->select('no_hp_pembeli')
                                        ->from('tr_h3_dealer_sales_order')
                                        ->where('id', $picking_slip['nomor_so_int'])
                                        ->get()->row_array();

                            $customer = $this->db->select('no_mesin')
                                ->select('no_rangka')
                                ->from('ms_customer_h23')
                                ->where('id_customer', $customer_so['id_customer'])
                                ->get()->row_array();
                
                            $this->db->set('no_so_wo_booking',  $picking_slip['nomor_so'])
                                ->set('id_customer', $customer_so['id_customer'])
                                ->set('nama_customer', $customer_so['nama_pembeli'])
                                ->set('no_hp', $customer_so['no_hp_pembeli'])
                                ->set('no_mesin', $customer['no_mesin'])
                                ->set('no_rangka', $customer['no_rangka'])
                                ->where('id_part_int', $part_ev['id_part_int'])	
                                ->where('serial_number', $part_ev['serial_number'])
                                ->update('tr_h3_serial_ev_tracking');
            
                            $this->db->set('is_return',  0)
                                ->set('updated_return_at',  null)
                                ->set('updated_return_by',  null)
                                ->where('id_part_int', $part_ev['id_part_int'])	
                                ->where('serial_number', $part_ev['serial_number'])
                                ->where('id', $part_ev['id_sn_ev'])
                                ->update('tr_h3_dealer_sales_order_serial_ev');
                        }
                    }
                }
            }elseif($part['return'] == 0){
                if(isset($parts_ev)){
                    foreach($parts_ev as $part_ev){
                            $customer_so = $this->db->select('id_customer')
                                        ->select('nama_pembeli')
                                        ->select('no_hp_pembeli')
                                        ->from('tr_h3_dealer_sales_order')
                                        ->where('id', $picking_slip['nomor_so_int'])
                                        ->get()->row_array();

                            $customer = $this->db->select('no_mesin')
                                ->select('no_rangka')
                                ->from('ms_customer_h23')
                                ->where('id_customer', $customer_so['id_customer'])
                                ->get()->row_array();
                
                            $this->db->set('no_so_wo_booking',  $picking_slip['nomor_so'])
                                ->set('id_customer', $customer_so['id_customer'])
                                ->set('nama_customer', $customer_so['nama_pembeli'])
                                ->set('no_hp', $customer_so['no_hp_pembeli'])
                                ->set('no_mesin', $customer['no_mesin'])
                                ->set('no_rangka', $customer['no_rangka'])
                                ->where('id_part_int', $part_ev['id_part_int'])	
                                ->where('serial_number', $part_ev['serial_number'])
                                ->update('tr_h3_serial_ev_tracking');
            
                            $this->db->set('is_return',  0)
                                ->set('updated_return_at',  null)
                                ->set('updated_return_by',  null)
                                ->where('id_part_int', $part_ev['id_part_int'])	
                                ->where('serial_number', $part_ev['serial_number'])
                                ->where('id', $part_ev['id_sn_ev'])
                                ->update('tr_h3_dealer_sales_order_serial_ev');
                    }
                }
            }
        }

        if(isset($parts_ev)){
            foreach($parts_ev as $part_ev){
                 //Cek apakah ada update serial number 
                $cek_sn = $this->db->select('serial_number')
                    ->select('id_part_int')
                    ->from('tr_h3_dealer_sales_order_serial_ev')
                    ->where('id', $part_ev['id_sn_ev'])	
                    ->get()
                    ->row_array();

                if($cek_sn['serial_number'] != $part_ev['serial_number']){
                    //Update Data Customer di SN yang baru 
                    $cek_data_sn_lama = $this->db->select('no_so_wo_booking')
                        ->select('id_customer')
                        ->select('nama_customer')
                        ->select('no_hp')
                        ->select('no_mesin')
                        ->select('no_rangka')
                        ->from('tr_h3_serial_ev_tracking')
                        ->where('id_part_int', $cek_sn['id_part_int'])	
                        ->where('serial_number', $cek_sn['serial_number'])
                        ->get()
                        ->row_array();

                    $this->db->set('no_so_wo_booking',  $cek_data_sn_lama['no_so_wo_booking'])
                        ->set('id_customer', $cek_data_sn_lama['id_customer'])
                        ->set('nama_customer', $cek_data_sn_lama['nama_customer'])
                        ->set('no_hp', $cek_data_sn_lama['no_hp'])
                        ->set('no_mesin', $cek_data_sn_lama['no_mesin'])
                        ->set('no_rangka', $cek_data_sn_lama['no_rangka'])
                        ->where('id_part_int', $part_ev['id_part_int'])	
                        ->where('serial_number', $part_ev['serial_number'])
                        ->update('tr_h3_serial_ev_tracking');

                    $this->db->set('no_so_wo_booking',  null)
                        ->set('id_customer', null)
                        ->set('nama_customer', null)
                        ->set('no_hp', null)
                        ->set('no_mesin', null)
                        ->set('no_rangka', null)
                        ->where('id_part_int', $cek_sn['id_part_int'])	
                        ->where('serial_number', $cek_sn['serial_number'])
                        ->update('tr_h3_serial_ev_tracking');

                    $this->db->set('serial_number',  $part_ev['serial_number'])
                        ->where('id', $part_ev['id_sn_ev'])	
                        ->update('tr_h3_dealer_sales_order_serial_ev');
                }
            }
        }

        // if(isset($parts_ev)){
        //     foreach($parts_ev as $part_ev){
        //         if($part_ev['is_return']==1){
        //             $this->db->set('no_so_wo_booking',  null)
        //                 ->set('id_customer', null)
        //                 ->set('nama_customer', null)
        //                 ->set('no_hp', null)
        //                 ->set('no_mesin', null)
        //                 ->set('no_rangka', null)
        //                 ->where('id_part_int', $part_ev['id_part_int'])	
        //                 ->where('serial_number', $part_ev['serial_number'])
        //                 ->update('tr_h3_serial_ev_tracking');
    
        //             $this->db->set('is_return',  1)
        //                 ->set('updated_return_at',  date('Y-m-d H:i:s', time()))
        //                 ->set('updated_return_by',  $this->session->userdata('id_user'))
        //                 ->where('id_part_int', $part_ev['id_part_int'])	
        //                 ->where('serial_number', $part_ev['serial_number'])
        //                 ->update('tr_h3_dealer_sales_order_serial_ev');
        //         }

        //         //Cek apakah ada update serial number 
        //         $cek_sn = $this->db->select('serial_number')
        //                 ->select('id_part_int')
        //                 ->from('tr_h3_dealer_sales_order_serial_ev')
        //                 ->where('id', $part_ev['id_sn_ev'])	
        //                 ->get()
        //                 ->row_array();

        //         if($cek_sn['serial_number'] != $part_ev['serial_number']){
        //             //Update Data Customer di SN yang baru 
        //             $cek_data_sn_lama = $this->db->select('no_so_wo_booking')
        //                 ->select('id_customer')
        //                 ->select('nama_customer')
        //                 ->select('no_hp')
        //                 ->select('no_mesin')
        //                 ->select('no_rangka')
        //                 ->from('tr_h3_serial_ev_tracking')
        //                 ->where('id_part_int', $cek_sn['id_part_int'])	
        //                 ->where('serial_number', $cek_sn['serial_number'])
        //                 ->get()
        //                 ->row_array();

        //             $this->db->set('no_so_wo_booking',  $cek_data_sn_lama['no_so_wo_booking'])
        //                 ->set('id_customer', $cek_data_sn_lama['id_customer'])
        //                 ->set('nama_customer', $cek_data_sn_lama['nama_customer'])
        //                 ->set('no_hp', $cek_data_sn_lama['no_hp'])
        //                 ->set('no_mesin', $cek_data_sn_lama['no_mesin'])
        //                 ->set('no_rangka', $cek_data_sn_lama['no_rangka'])
        //                 ->where('id_part_int', $part_ev['id_part_int'])	
        //                 ->where('serial_number', $part_ev['serial_number'])
        //                 ->update('tr_h3_serial_ev_tracking');

        //             $this->db->set('no_so_wo_booking',  null)
        //                 ->set('id_customer', null)
        //                 ->set('nama_customer', null)
        //                 ->set('no_hp', null)
        //                 ->set('no_mesin', null)
        //                 ->set('no_rangka', null)
        //                 ->where('id_part_int', $cek_sn['id_part_int'])	
        //                 ->where('serial_number', $cek_sn['serial_number'])
        //                 ->update('tr_h3_serial_ev_tracking');
    
        //             $this->db->set('serial_number',  $part_ev['serial_number'])
        //                 ->where('id', $part_ev['id_sn_ev'])	
        //                 ->update('tr_h3_dealer_sales_order_serial_ev');
        //         }
        //     }
        // }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            send_json([
                'message' => 'Berhasil',
                'redirect_url' => base_url(sprintf('dealer/h3_dealer_picking_slip/detail?id=%s', $picking_slip['id']))
            ]);
        } else {
            send_json([
                'error' => 'Gagal update picking slip'
            ], 402);
        }
    }

    public function validate()
    {
        $this->form_validation->set_error_delimiters('', '');

        //Cek jika ada yg direturn part EV, maka SN harus dicentang
        $parts = $this->input->post('parts');
        $parts_ev = $this->input->post('parts_ev');
        
         foreach($parts as $part){
            $cek_kel_part = $this->db->query("SELECT kelompok_part from ms_part where id_part_int ='".$part['id_part_int'] ."'")->row_array();
            if($part['return'] == 1){
                if($cek_kel_part['kelompok_part']=='EVBT' ||$cek_kel_part['kelompok_part']=='EVCH' ){
                    //Cek apakah part return telah dichecklist 
                    $is_return = 0;
                    foreach($parts_ev as $part_ev){
                        $is_return += $part_ev['is_return']; 
                    }
                    if($part['kuantitas_return'] != $is_return ){
                        send_json([
                            'error_type' => 'validation_error',
                            'message' => 'Kuantitas Return dan Serial Number yang dichecklist tidak sesuai!'
                        ], 422);
                    }
                }
            }

            if($cek_kel_part['kelompok_part']=='EVBT' ||$cek_kel_part['kelompok_part']=='EVCH'){
                //Cek apakah SN tersebut telah dibooking 
                
                $serial_number_terlihat = [];
                $duplikasi = false;
                foreach($parts_ev as $part_ev){
                    $id_part_int = $part_ev['id_part_int'];
                    $serial_number = $part_ev['serial_number'];

                    $id = $this->input->post('id');
                    $cek_so = $this->db->query("SELECT nomor_so FROM tr_h3_dealer_picking_slip WHERE id = $id")->row_array();
                  
                    $cek_sn = $this->db->query("SELECT no_so_wo_booking, count(no_so_wo_booking) as hitung_so_booking
                    from tr_h3_serial_ev_tracking et
                    WHERE id_part_int='$id_part_int' and serial_number='$serial_number'")->row_array(); 

                    if($cek_sn['no_so_wo_booking'] != null || $cek_sn['no_so_wo_booking'] != ''){
                        if($cek_sn['no_so_wo_booking'] != $cek_so['nomor_so']){
                            send_json([
                                'error_type' => 'validation_error',
                                'message' => 'Silahkan Pilih Serial Number lain!'
                            ], 422);
                        }
                    }

                    //Cek apakah SN memiliki nomor yg sama
                    if(in_array($serial_number, $serial_number_terlihat)){
                        send_json([
                            'error_type' => 'validation_error',
                            'message' => 'Terdapat duplikasi Serial Number pada nomor SO ini.'
                        ], 422);
                    }
                }
            }
         }
    }
}
