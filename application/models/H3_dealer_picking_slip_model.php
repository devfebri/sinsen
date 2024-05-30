<?php

class h3_dealer_picking_slip_model extends Honda_Model
{
    protected $table = 'tr_h3_dealer_picking_slip';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_admin');
        $this->load->model('h3_dealer_sales_order_model', 'sales_order');
    }

    public function generateNomorPickingSlip($id_dealer)
    {
        $tahun_dan_bulan    = date('Y-m');
        $bulan     = date('m');
        $tahun     = date('y');
        // $dealer    = $this->dealer->getCurrentUserDealer();
        
        $dealer = $this->db->query("SELECT kode_dealer_md FROM ms_dealer WHERE id_dealer = $id_dealer")->row();

        $get_data = $this->db
            ->from($this->table)
            ->where("LEFT(tanggal_ps,7)='{$tahun_dan_bulan}'")
            ->where('id_dealer', $this->m_admin->cari_dealer())
            // ->limit(1)
            ->order_by('id', 'desc')
            // ->order_by('created_at', 'desc')
            // ->order_by('nomor_ps', 'desc')
            ->limit(1)
            ->get();

        if ($get_data->num_rows() > 0) {
            $row        = $get_data->row();
            $nomor_ps = substr($row->nomor_ps, -5);
            $new_kode = "{$dealer->kode_dealer_md}-PS/{$bulan}/{$tahun}/" . sprintf("%'.05d", $nomor_ps + 1);
        } else {
            $new_kode   = "{$dealer->kode_dealer_md}-PS/{$bulan}/{$tahun}/00001";
        }
        return strtoupper($new_kode);
    }
    public function int_relation($id)
    {
        $data = $this->db
            ->select('so.id as nomor_so_int')
            ->from(sprintf('%s as ps', $this->table))
            ->join('tr_h3_dealer_sales_order as so', 'so.nomor_so = ps.nomor_so')
            ->where('ps.id', $id)
            ->limit(1)
            ->get()->row_array();

        if ($data != null) {
            $this->db
                ->set('nomor_so_int', $data['nomor_so_int'])
                ->where('id', $id)
                ->update($this->table);

            log_message('debug', sprintf('Mengupdate nomor_so_int untuk picking slip id [%s]', $id));
        }
    }
}
