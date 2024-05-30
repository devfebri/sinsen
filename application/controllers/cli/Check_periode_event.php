<?php

use GO\Scheduler;

class Check_periode_event extends Honda_Controller {

    private $reasons = [
        [
            'reason' => 'Penjualan',
            'action' => '1',
            'qty' => '0',
            'keterangan' => '',
            'id_gudang' => '',
            'id_rak' => ''
        ],
        [
            'reason' => 'Kerusakan',
            'action' => '1',
            'qty' => '0',
            'keterangan' => '',
            'id_gudang' => '',
            'id_rak' => ''
        ],
        [
            'reason' => 'Kehilangan',
            'action' => '1',
            'qty' => '0',
            'keterangan' => '',
            'id_gudang' => '',
            'id_rak' => ''
        ],
        [
            'reason' => 'Tertukar',
            'action' => '1',
            'qty' => '0',
            'keterangan' => '',
            'id_gudang' => '',
            'id_rak' => ''
        ],
        [
            'reason' => 'Others',
            'action' => '1',
            'qty' => '0',
            'keterangan' => '',
            'id_gudang' => '',
            'id_rak' => ''
        ],
    ];

    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
        $this->load->model('notifikasi_model', 'notifikasi');
        $this->load->model('h3_dealer_inbound_form_for_parts_return_model', 'inbound_form_for_parts_return');
        $this->load->model('h3_dealer_inbound_form_for_parts_return_parts_model', 'inbound_form_for_parts_return_parts');
        $this->load->model('h3_dealer_inbound_form_for_parts_return_parts_reason_model', 'inbound_form_for_parts_return_parts_reason');
    }

    public function index()
    {
        $scheduler = new Scheduler();

        $scheduler->call(function () {
            $this->generate_inbound_form();
        });

        $scheduler->run();
    }

    public function generate_inbound_form(){
        $outdate_event = $this->get_outdate_event();

        foreach ($outdate_event as $event) {
            $master = [
                'id_inbound_form_for_parts_return' => $this->inbound_form_for_parts_return->generateID($event->id_dealer),
                'id_outbound_form' => $event->id_outbound_form_for_fulfillment,
                'id_dealer' => $event->id_dealer,
            ];

            $this->inbound_form_for_parts_return->insert($master);

            $this->db
            ->from('tr_h3_dealer_outbound_form_for_fulfillment_parts as ofp')
            ->where('ofp.id_outbound_form_for_fulfillment', $event->id_outbound_form_for_fulfillment);

            $parts = [];
            foreach ($this->db->get()->result_array() as $each) {
                $part = [];
                $part['id_inbound_form_for_parts_return'] = $master['id_inbound_form_for_parts_return'];
                $part['id_part'] = $each['id_part'];
                $part['id_gudang'] = $each['id_gudang'];
                $part['id_rak'] = $each['id_rak'];
                $part['qty_book'] = $each['kuantitas'];
                $part['qty_return'] = $each['kuantitas'];
                $this->inbound_form_for_parts_return_parts->insert($part);
                $id_inbound_part = $this->db->insert_id();

                foreach ($this->reasons as $reason) {
                    $reason['id_inbound_part'] = $id_inbound_part;
                    $this->inbound_form_for_parts_return_parts_reason->insert($reason);
                }
            }
            $this->notify_part_counter_for_inbound_return($event, $master);
        }
    }

    private function get_outdate_event(){
        $this->db
        ->select('e.id_event')
        ->select('e.nama')
        ->select('of.id_dealer')
        ->select('of.id_outbound_form_for_fulfillment')
        ->select('if.id_inbound_form_for_parts_return')
        ->from('ms_h3_dealer_event_h23 as e')
        ->join('tr_h3_dealer_outbound_form_for_fulfillment as of', 'of.id_event = e.id_event')
        ->join('tr_h3_dealer_surat_jalan_outbound_form_for_fulfillment as sj', 'sj.id_outbound_form = of.id_outbound_form_for_fulfillment')
        ->join('tr_h3_dealer_inbound_form_for_parts_return as if', 'if.id_outbound_form = of.id_outbound_form_for_fulfillment', 'left')
        ->where('e.end_date <', date('Y-m-d'))
        ->where('if.id_inbound_form_for_parts_return', null)
        ->order_by('of.created_at', 'asc');
        ;

        return $this->db->get()->result();
    }

    public function notify_part_counter_for_inbound_return($event, $inbound){
        $pesan = "Periode Event {$event->nama} dengan nomor event {$event->id_event} telah berakhir. Silakan lakukan pengembalian parts ke Dealer dengan inbound Form no {$inbound['id_inbound_form_for_parts_return']}"; 

        $menu_kategori = $this->db->from('ms_notifikasi_kategori')->where('kode_notif', 'inbound_form_for_parts_return')->get()->row();
        $this->notifikasi->insert([
            'id_notif_kat' => $menu_kategori->id_notif_kat,
            'judul' => $menu_kategori->nama_kategori,
            'pesan' => $pesan,
            'link' => "{$menu_kategori->link}/detail?id={$inbound['id_inbound_form_for_parts_return']}",
            'id_dealer' => $inbound['id_dealer'],
            'show_popup' => $menu_kategori->popup,
        ]);
    }
}