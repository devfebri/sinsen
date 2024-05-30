<?php

defined('BASEPATH') or exit('No direct script access allowed');

class H3_dealer_outbound_form_for_fulfillment extends Honda_Controller
{
    protected $folder = "dealer";
    protected $page   = "h3_dealer_outbound_form_for_fulfillment";
    protected $title  = "Outbound Form For Fulfillment";

    public function __construct()
    {
        parent::__construct();
        //---- cek session -------//
        $name = $this->session->userdata('nama');
        if ($name=="") {
            echo "<meta http-equiv='refresh' content='0; url=".base_url()."panel'>";
        }

        //===== Load Database =====
        $this->load->database();
        $this->load->helper('url');
        //===== Load Model =====
        $this->load->model('m_admin');
        $this->load->library('form_validation');
        $this->load->model('h3_dealer_outbound_form_for_fulfillment_model', 'outbound_form_for_fulfillment');
        $this->load->model('h3_dealer_outbound_form_for_fulfillment_parts_model', 'outbound_form_for_fulfillment_parts');
        $this->load->model('notifikasi_model', 'notifikasi');
        $this->load->model('H3_surat_jalan_outbound_model', 'surat_jalan');
        $this->load->model('h3_dealer_transaksi_stok_model', 'transaksi_stok');
    }

    public function validate(){
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('id_event', 'Event', 'required');
        $this->form_validation->set_rules('parts', 'Parts',  array(
            array(
                'check_parts',
                function($value){
                    $valid = count($this->input->post('parts')) > 0;
                    if(!$valid){
                        $this->form_validation->set_message('check_parts' , '{field} tidak boleh kosong.');
                    }
                    return $valid;
                }
            )
        ));
        if (!$this->form_validation->run()){
            $keys = ['id_event', 'parts'];
            $data = [];
            foreach ($keys as $key) {
                $data[$key] = form_error($key) == '' ? null : form_error($key);
            }

            $this->output->set_status_header(400);
            send_json($data);
        }
    }

    public function index()
    {
        $data['set']	= "index";
        $this->template($data);
    }

    public function add()
    {
        $data['mode']    = 'insert';
        $data['set']     = "form";
        $this->template($data);
    }

    public function save()
    {
        $this->validate();

        $this->db->trans_start();
        $master = array_merge($this->input->post(['id_event']), [
            'id_outbound_form_for_fulfillment' => $this->outbound_form_for_fulfillment->generateID(),
            'id_dealer' => $this->m_admin->cari_dealer(),
        ]);
        $items = $this->getOnly(true, $this->input->post('parts'), [
            'id_outbound_form_for_fulfillment' => $master['id_outbound_form_for_fulfillment']
        ]);

        $this->outbound_form_for_fulfillment->insert($master);
        $this->outbound_form_for_fulfillment_parts->insert_batch($items);

        $menu = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'outbound_form_for_fulfillment')->get()->row();
        $this->notifikasi->insert([
            'id_notif_kat' => $menu->id_notif_kat,
            'judul' => $menu->nama_kategori,
            'pesan' => "Terdapat request Outbound Form For Fulfillment no {$master['id_outbound_form_for_fulfillment']} dari Part Counter untuk pemindahan part.",
            'link' => "{$menu->link}/detail?k={$master['id_outbound_form_for_fulfillment']}",
            'id_dealer' => $this->m_admin->cari_dealer(),
            'show_popup' => $menu->popup == 1,
        ]);

        $this->db->trans_complete();

        if($this->db->trans_status()){
            $result = $this->outbound_form_for_fulfillment->find($master['id_outbound_form_for_fulfillment'], 'id_outbound_form_for_fulfillment');
            send_json($result);
        }else{
            $this->output->set_status_header(500);
        }
    }

    public function detail()
    {
        $data['mode']  = 'detail';
        $data['set']   = "form";

        $outbound_form_for_fulfillment = $this->db
        ->select('of.*')
        ->select('date_format(of.created_at, "%d-%m-%Y") as tanggal_request')
        ->select('date_format(of.tanggal_transit, "%d-%m-%Y") as tanggal_transit')
        ->select('date_format(of.tanggal_closed, "%d-%m-%Y") as tanggal_closed')
        ->from('tr_h3_dealer_outbound_form_for_fulfillment as of')
        ->where('of.id_outbound_form_for_fulfillment', $this->input->get('k'))
        ->limit(1)
        ->get()->row();

        $data['event'] = $this->db
        ->select('e.*')
        ->select('date_format(e.start_date, "%d-%m-%Y") as start_date')
        ->select('date_format(e.end_date, "%d-%m-%Y") as end_date')
        ->select('kd.nama_lengkap as nama_pic')
        ->from('ms_h3_dealer_event_h23 as e')
        ->join('ms_karyawan_dealer as kd', 'kd.id_karyawan_dealer = e.pic', 'left')
        ->join('ms_jabatan as j', 'j.id_jabatan = kd.id_jabatan', 'left')
        ->where('e.id_event', $outbound_form_for_fulfillment->id_event)
        ->limit(1)
        ->get()->row();


        $book_by_sales = $this->db
        ->select('ifnull( sum(sop.kuantitas), 0) as qty_sales_order')
        ->from('tr_h3_dealer_sales_order as so')
        ->join('tr_h3_dealer_sales_order_parts as sop', 'so.nomor_so = sop.nomor_so')
        ->where('so.id_dealer', $this->m_admin->cari_dealer())
        ->where('so.status', 'Open')
        ->where('so.id_inbound_form_for_parts_return', null)
        ->group_start()
        ->where("sop.id_part = ofp.id_part")
        ->where("sop.id_gudang = ofp.id_gudang")
        ->where("sop.id_rak = ofp.id_rak")
        ->group_end()
        ->get_compiled_select();

        $book_by_outbound_fulfillment = $this->db
        ->select('ifnull( sum(offp.kuantitas), 0) as qty_outbound_fulfillment')
        ->from('tr_h3_dealer_outbound_form_for_fulfillment as off')
        ->join('tr_h3_dealer_outbound_form_for_fulfillment_parts as offp', 'off.id_outbound_form_for_fulfillment = offp.id_outbound_form_for_fulfillment')
        ->where('off.id_dealer', $this->m_admin->cari_dealer())
        ->where('off.status', 'Open')
        ->group_start()
        ->where("offp.id_part = ofp.id_part")
        ->where("offp.id_gudang = ofp.id_gudang")
        ->where("offp.id_rak = ofp.id_rak")
        ->group_end()
        ->get_compiled_select();

        $data['parts'] = $this->db
        ->select('ofp.*')
        ->select('p.nama_part')
        ->select('ds.stock')
        ->select("( ds.stock - (({$book_by_sales}) + ({$book_by_outbound_fulfillment})) ) as stock_avs")
        ->select('s.satuan')
        ->from('tr_h3_dealer_outbound_form_for_fulfillment_parts as ofp')
        ->join('ms_part as p', 'p.id_part = ofp.id_part')
        ->join('ms_h3_dealer_stock as ds', '(ds.id_part = ofp.id_part and ds.id_rak = ofp.id_rak and ds.id_gudang = ofp.id_gudang)')
        ->join('ms_satuan as s', 's.id_satuan = p.id_satuan', 'left')
        ->where('ofp.id_outbound_form_for_fulfillment', $outbound_form_for_fulfillment->id_outbound_form_for_fulfillment)
        ->get()->result();

        $data['outbound_form_for_fulfillment'] = $outbound_form_for_fulfillment;

        $this->template($data);
    }

    public function transit(){
        $this->db->trans_start();

        $this->outbound_form_for_fulfillment->update([
            'status' => 'In Transit',
            'tanggal_transit' => date('Y-m-d H:i:s')
        ], [
            'id_outbound_form_for_fulfillment' => $this->input->get('k')
        ]);

        $parts = $this->outbound_form_for_fulfillment_parts->get([
            'id_outbound_form_for_fulfillment' => $this->input->get('k')
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

        $this->surat_jalan->insert([
            'id_surat_jalan' => $this->surat_jalan->generateID(),
            'id_outbound_form' => $this->input->get('k')
        ]);

        $this->db->trans_complete();

        if($this->db->trans_status()){
            $this->output->set_status_header(200);
        }else{
            $this->output->set_status_header(500);
        }
    }

    public function close(){

        $this->db->trans_start();
        $this->outbound_form_for_fulfillment->update([
            'status' => 'Closed',
            'tanggal_closed' => date('Y-m-d H:i:s')
        ], [
            'id_outbound_form_for_fulfillment' => $this->input->get('k')
        ]);
        $this->db->trans_complete();

        if($this->db->trans_status()){
            $this->output->set_status_header(200);
        }else{
            $this->output->set_status_header(500);
        }
    }

    public function cetak()
	{
		    $this->load->library('mpdf_l');
			$mpdf                           = $this->mpdf_l->load();
			$mpdf->allow_charset_conversion = true;  // Set by default to TRUE
			$mpdf->charset_in               = 'UTF-8';
			$mpdf->autoLangToFont           = true;

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
            ->limit(1)->get()->row();

            $data['outbound'] = $this->db
            ->select('sj.id_surat_jalan')
            ->select('date_format(off.created_at, "%d/%m/%Y") as tanggal_peminjaman')
            ->select('e.id_event')
            ->select('e.nama as nama_event')
            ->select('date_format(e.start_date, "%d/%m/%Y") as tanggal_mulai_event')
            ->select('date_format(e.end_date, "%d/%m/%Y") as tanggal_selesai_event')
            ->select('kd.nama_lengkap as pic_event')
            ->from('tr_h3_dealer_outbound_form_for_fulfillment as off')
            ->join('tr_h3_dealer_surat_jalan_outbound_form_for_fulfillment as sj', 'sj.id_outbound_form = off.id_outbound_form_for_fulfillment')
            ->join('ms_h3_dealer_event_h23 as e', 'e.id_event = off.id_event')
            ->join('ms_karyawan_dealer as kd', 'kd.id_karyawan_dealer = e.pic', 'left')
            ->join('ms_jabatan as j', 'j.id_jabatan = kd.id_jabatan', 'left')
            ->where('off.id_outbound_form_for_fulfillment', $this->input->get('k'))
            ->limit(1)
            ->get()->row();

            $data['parts'] = $this->db
            ->select('p.id_part')
            ->select('p.nama_part')
            ->select('offp.kuantitas')
            ->from('tr_h3_dealer_outbound_form_for_fulfillment_parts as offp')
            ->join('ms_part as p', 'p.id_part = offp.id_part')
            ->where('offp.id_outbound_form_for_fulfillment', $this->input->get('k'))
            ->get()->result();

            $data['gudang'] = $this->db
            ->select('offp.id_gudang')
            ->from('tr_h3_dealer_outbound_form_for_fulfillment_parts as offp')
            ->where('offp.id_outbound_form_for_fulfillment', $this->input->get('k'))
            ->group_by('offp.id_gudang')
            ->get()->result();
        	
        	$html = $this->load->view('dealer/h3_dealer_surat_jalan_fulfillment', $data, true);
        	// render the view into HTML
	        $mpdf->WriteHTML($html);
	        // write the HTML into the mpdf
	        $output = "{$data['outbound']->id_surat_jalan}.pdf";
	        $mpdf->Output($output, 'I');
        
	}
}
