<?php

class H3_md_voucher_pengeluaran_model extends Honda_Model
{

    protected $table = 'tr_h3_md_voucher_pengeluaran';

    public function __construct()
    {
        parent::__construct();

        $this->load->library('Mcarbon');
    }

    public function insert($data)
    {
        $data['status'] = 'Open';
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['created_by'] = $this->session->userdata('id_user');
        parent::insert($data);
    }

    public function update($data, $condition)
    {
        $data['updated_at'] = date('Y-m-d H:i:s', time());
        $data['updated_by'] = $this->session->userdata('id_user');
        parent::update($data, $condition);
    }

    public function set_processed($id)
    {
        $this->db
            ->set('vp.status', 'Processed')
            ->where('vp.id', $id)
            ->update("{$this->table} as vp");
    }

    public function add_ke_nominal_giro($id)
    {
        $voucher_pengeluaran = $this->db
            ->from('tr_h3_md_voucher_pengeluaran as vp')
            ->where('vp.via_bayar', 'Giro')
            ->where('vp.id', $id)
            ->where('vp.status', 'Open')
            ->get()->row_array();

        if ($voucher_pengeluaran == null) return;

        $this->db
            ->set('cg.nominal', "(cg.nominal + {$voucher_pengeluaran['total_amount']})", false)
            ->set('cg.status', 'Processed')
            ->set('cg.active', 0)
            ->set('cg.updated_at', Mcarbon::now()->toDateTimeString())
            ->set('cg.updated_by', $this->session->userdata('id_user'))
            ->where('cg.id_cek_giro', $voucher_pengeluaran['id_giro'])
            ->update('ms_cek_giro as cg');
    }

    public function generate_id()
    {
        $tahun = Mcarbon::now()->format('Y');
        $short_tahun = Mcarbon::now()->format('y');

        $query = $this->db
            ->select('vp.id_voucher_pengeluaran')
            ->from("{$this->table} as vp")
            ->where("LEFT(vp.created_at, 4)='{$tahun}'", null, false)
            ->where('vp.created_at >', '2021-08-31 14:47:13')
            ->order_by('vp.created_at', 'desc')
            ->limit(1)
            ->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            $id_voucher_pengeluaran = substr($row->id_voucher_pengeluaran, 2, 5);
            $id_voucher_pengeluaran = sprintf("%'.05d", $id_voucher_pengeluaran + 1);
            $id = "{$short_tahun}{$id_voucher_pengeluaran}";
        } else {
            $id = "{$short_tahun}00001";
        }

        return strtoupper($id);
    }
}
