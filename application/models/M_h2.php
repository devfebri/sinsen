<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_h2 extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_ttpk_detail($no_surat_claim = null)
    {
        $surat_filter = '';
        if ($no_surat_claim != null) {
            $surat_filter = "AND tr_claim_kpb_generate.no_surat_claim='$no_surat_claim'";
        }
        $data = $this->db->query("SELECT *,count(id_tipe_kendaraan) AS count
            FROM tr_claim_kpb_generate_detail AS ckgd
            JOIN tr_claim_kpb ON ckgd.id_claim_kpb=tr_claim_kpb.id_claim_kpb
            JOIN tr_claim_kpb_generate ON ckgd.no_generate=tr_claim_kpb_generate.no_generate
            WHERE ckgd.status='approved' 
            $surat_filter
            AND id_po_kpb IS NOT NULL
            GROUP BY id_tipe_kendaraan
            ");
        $amount_material = 0;
        $amount_jasa = 0;
        foreach ($data->result() as $rs) {
            $amount_material += $rs->harga_material * $rs->count;
            $amount_jasa += $rs->harga_jasa * $rs->count;
        }
        $amount_pokok = $amount_jasa + $amount_material;
        $ppn =  ROUND($amount_pokok * getPPN(0.1,date('Y-m-d')));
        $pph = ROUND($amount_pokok * 0.02);
        $total_dibayar = $amount_pokok + $ppn - $pph;
        return $result = [
            'amount_material' => $amount_material,
            'amount_jasa'      => $amount_jasa,
            'amount_pokok'     => $amount_pokok,
            'ppn'              => $ppn,
            'nilai_pokok_ppn' => $amount_pokok + $ppn,
            'pph'              => $pph,
            'total_dibayar'      => $total_dibayar
        ];
    }

    public function tot_rekap_tagihan($id_ptca)
    {
        $data = $this->db->query("SELECT tr_rekap_claim_waranty_detail.* FROM tr_rekap_claim_waranty_detail 
            JOIN tr_rekap_claim_waranty ON tr_rekap_claim_waranty_detail.id_rekap_claim=tr_rekap_claim_waranty.id_rekap_claim
            WHERE id_ptca='$id_ptca'");
        $total_biaya = 0;
        foreach ($data->result() as $dt) {
            $biaya_part = $dt->harga * $dt->jumlah;
            $total_biaya += $biaya_part + $dt->ongkos;
        }
        return $result = ['total_biaya' => $total_biaya];
    }

    public function tot_lbpc($no_lbpc)
    {
        $data = $this->db->query("SELECT tr_rekap_claim_waranty_detail.* FROM tr_rekap_claim_waranty_detail 
            JOIN tr_rekap_claim_waranty ON tr_rekap_claim_waranty_detail.id_rekap_claim=tr_rekap_claim_waranty.id_rekap_claim
            WHERE no_lbpc='$no_lbpc'");
        $total_biaya = 0;
        $tot_part = 0;
        foreach ($data->result() as $dt) {
            $biaya_part = $dt->harga * $dt->jumlah;
            $tot_part += $dt->jumlah;
            $total_biaya += $biaya_part + $dt->ongkos;
        }
        return $result = ['total_biaya' => $total_biaya, 'tot_part' => $tot_part];
    }

    public function tot_ptcd($no_ptcd)
    {
        $data = $this->db->query("SELECT * FROM tr_ptcd_detail WHERE no_ptcd='$no_ptcd'");
        $grand_total = 0;
        $total = 0;
        $ppn = 0;
        $pph = 0;
        $tot_part = 0;
        $tot_jasa = 0;
        foreach ($data->result() as $dt) {
            $tot_part += $dt->jml_accept;
            $tot_jasa += $dt->ongkos;
            $tot = ($dt->jml_accept * $dt->harga) + $dt->ongkos;
            $total    += $tot;
            $ppn      += $tot * 0.1;
            $pph      += $tot * 0.02;
        }
        $grand_total = $total + $ppn - $pph;
        return $result = [
            'grand_total' => $grand_total,
            'tot_part' => $tot_part,
            'tot_jasa' => $tot_jasa
        ];
    }
    public function detail_ptcd($no_ptcd)
    {
        return $this->db->query("SELECT tr_ptcd_detail.*,nama_part,tgl_pengajuan,no_lbpc,no_rangka 
            FROM tr_ptcd_detail 
            JOIN ms_part ON ms_part.id_part=tr_ptcd_detail.id_part
            JOIN tr_rekap_claim_waranty_detail ON tr_rekap_claim_waranty_detail.id_rekap_claim=tr_ptcd_detail.id_rekap_claim AND tr_rekap_claim_waranty_detail.id_part=tr_ptcd_detail.id_part
            JOIN tr_rekap_claim_waranty ON tr_rekap_claim_waranty.id_rekap_claim=tr_ptcd_detail.id_rekap_claim
            JOIN tr_lkh ON tr_rekap_claim_waranty.no_lkh=tr_lkh.id_lkh
            WHERE no_ptcd='$no_ptcd'");
    }

    public function laporan_data_kpb_nosin($id_dealer = null, $group_no_mesin_5 = null)
    {
        $filter_dealer = 'GROUP BY tr_claim_kpb.id_dealer';
        if ($id_dealer != null) {
            $filter_dealer = "AND tr_claim_kpb.id_dealer='$id_dealer'";
        }
        $set_group_no_mesin_5 = '';
        if ($group_no_mesin_5 == 'ya') {
            $set_group_no_mesin_5 = "GROUP BY tr_claim_kpb_generate.no_mesin_5";
        }
        return $this->db->query("SELECT tr_claim_kpb_generate_detail.*,tr_claim_kpb.*,nama_dealer,kode_dealer_md,no_mesin_5 FROM tr_claim_kpb_generate_detail
                        JOIN tr_claim_kpb ON tr_claim_kpb.id_claim_kpb=tr_claim_kpb_generate_detail.id_claim_kpb
                        JOIN tr_claim_kpb_generate ON tr_claim_kpb_generate_detail.no_generate=tr_claim_kpb_generate.no_generate
                        JOIN ms_dealer ON ms_dealer.id_dealer=tr_claim_kpb.id_dealer
                        WHERE tr_claim_kpb_generate_detail.status='approved'
                        AND tr_claim_kpb_generate.status='terima_ttpk'
                        $filter_dealer
                        $set_group_no_mesin_5
                      ");
    }
    public function laporan_data_kpb($id_dealer, $kpb)
    {
        $filter_dealer = 'GROUP BY tr_claim_kpb.id_dealer';
        if ($id_dealer != null) {
            $filter_dealer = "AND tr_claim_kpb.id_dealer='$id_dealer'";
        }
        $set_group_no_mesin_5 = '';
        if ($group_no_mesin_5 == 'ya') {
            $set_group_no_mesin_5 = "GROUP BY tr_claim_kpb_generate.no_mesin_5";
        }
        return $this->db->query("SELECT tr_claim_kpb_generate_detail.*,tr_claim_kpb.*,nama_dealer,kode_dealer_md,no_mesin_5 FROM tr_claim_kpb_generate_detail
                        JOIN tr_claim_kpb ON tr_claim_kpb.id_claim_kpb=tr_claim_kpb_generate_detail.id_claim_kpb
                        JOIN tr_claim_kpb_generate ON tr_claim_kpb_generate_detail.no_generate=tr_claim_kpb_generate.no_generate
                        JOIN ms_dealer ON ms_dealer.id_dealer=tr_claim_kpb.id_dealer
                        WHERE tr_claim_kpb_generate_detail.status='approved'
                        AND tr_claim_kpb_generate.status='terima_ttpk'
                        $filter_dealer
                        $set_group_no_mesin_5
                      ");
    }

    public function laporan_data_kpb_nosin5_dealer($id_dealer, $kpb_ke, $no_mesin_5)
    {
        $data = $this->db->query("SELECT tr_claim_kpb_generate_detail.*,tr_claim_kpb.*,nama_dealer,kode_dealer_md,no_mesin_5 
            FROM tr_claim_kpb_generate_detail
            JOIN tr_claim_kpb ON tr_claim_kpb.id_claim_kpb=tr_claim_kpb_generate_detail.id_claim_kpb
            JOIN tr_claim_kpb_generate ON tr_claim_kpb_generate_detail.no_generate=tr_claim_kpb_generate.no_generate
            JOIN ms_dealer ON ms_dealer.id_dealer=tr_claim_kpb.id_dealer
            WHERE tr_claim_kpb_generate_detail.status='approved'
            AND tr_claim_kpb_generate.status='terima_ttpk'
            AND tr_claim_kpb.id_dealer='$id_dealer'
            AND tr_claim_kpb.kpb_ke='$kpb_ke'
            AND no_mesin_5='$no_mesin_5'
                      ");
        $jml_nosin      = 0;
        $harga_jasa     = 0;
        $keuntungan_oli = 0;
        $harga_oli      = 0;
        if ($data->num_rows() > 0) {

            foreach ($data->result() as $rs) {
                $harga_jasa = $rs->harga_jasa;
                $harga_oli  = $rs->harga_material;
                $jml_nosin++;
            }
        }
        return $result = [
            'jml_nosin' => $jml_nosin,
            'harga_jasa' => $harga_jasa,
            'keuntungan_oli' => $keuntungan_oli,
            'harga_oli' => $harga_oli,
        ];
    }

    function fetch_unit_srbu($filter)
    {
        $order_column = array('no_mesin', 'created_at', null);
        $set_filter   = 'WHERE 1=1 ';

        $search = $filter['search'];
        if ($search != '') {
            $set_filter .= " AND (hus.no_mesin LIKE '%$search%'
                            OR no_rangka LIKE '%$search%'
                            OR wr.warna LIKE '%$search%'
                            OR tk.tipe_ahm LIKE '%$search%'
                            OR tk.id_tipe_kendaraan LIKE '%$search%'
                            ) 
            ";
        }

        $order = $filter['order'];
        if ($order != '') {
            $order_clm  = $order_column[$order['0']['column']];
            $order_by   = $order['0']['dir'];
            $set_filter .= " ORDER BY $order_clm $order_by ";
        }
        $set_filter .= $filter['limit'];

        return $this->db->query("SELECT hus.no_mesin,sbc.no_rangka,tipe_ahm,id_tipe_kendaraan,wr.warna
            FROM tr_h2_unit_srbu AS hus
            JOIN tr_scan_barcode AS sbc ON sbc.no_mesin=hus.no_mesin
            JOIN ms_tipe_kendaraan AS tk ON tk.id_tipe_kendaraan=sbc.tipe_motor
            JOIN ms_warna AS wr ON wr.id_warna=sbc.warna
            $set_filter");
    }

    function fetch_mekanik($filter)
    {
        if (isset($filter['id_dealer'])) {
            $id_dealer = $filter['id_dealer'];
        } else {
            $id_dealer = $this->m_admin->cari_dealer();
        }
        $order_column = array('id_karyawan_dealer', 'honda_id', 'nama_lengkap', 'created_at', null);
        $set_filter   = "WHERE 1=1 AND kd.id_dealer='$id_dealer' AND id_jabatan IN('JBT-042','JBT-043')";

        if (isset($filter['id_karyawan_dealer_int'])) {
            $set_filter .= " AND kd.id_karyawan_dealer_int='{$filter['id_karyawan_dealer_int']}'";
        }
        if (isset($filter['search'])) {
            $search = $filter['search'];
            if ($search != '') {
                $set_filter .= " AND (id_karyawan_dealer LIKE '%$search%'
                            OR nama_lengkap LIKE '%$search%'
                            OR id_karyawan_dealer LIKE '%$search%'
                            OR nama_lengkap LIKE '%$search%'
                            ) 
            ";
            }
        }

        if (isset($filter['order'])) {
            $order = $filter['order'];
            if ($order != '') {
                $order_clm  = $order_column[$order['0']['column']];
                $order_by   = $order['0']['dir'];
                $set_filter .= " ORDER BY $order_clm $order_by ";
            }
        }

        if (isset($filter['limit'])) {
            $set_filter .= $filter['limit'];
        } elseif (isset($filter['offset'])) {
            $page = $filter['offset'];
            $page = $page < 0 ? 0 : $page;
            $length = $filter['length'];
            $start = $length * $page;
            $set_filter .= "LIMIT $start, $length";
        }

        return $this->db->query("SELECT id_karyawan_dealer_int,id_karyawan_dealer,nama_lengkap,id_flp_md,image,jk,CASE WHEN honda_id IS NULL OR honda_id='' THEN id_flp_md ELSE honda_id END AS honda_id,nama_dealer
            FROM ms_karyawan_dealer kd
            JOIN ms_dealer dl ON dl.id_dealer=kd.id_dealer
            $set_filter");
    }

    function get_pit($filter = null)
    {

        $where = "WHERE 1=1 ";
        $select = "*";

        if (isset($filter['id_dealer'])) {
            $where .= " AND pit.id_dealer='{$filter['id_dealer']}'";
        } else {
            $id_dealer = $this->m_admin->cari_dealer();
            $where .= " AND pit.id_dealer='$id_dealer'";
        }
        if ($filter != null) {
            if (isset($filter['id_pit'])) {
                $where .= " AND pit.id_pit='" . $filter['id_pit'] . "'";
            }
            if (isset($filter['jenis_pit'])) {
                $where .= " AND pit.jenis_pit='{$filter['jenis_pit']}'";
            }
            if (isset($filter['ada_mekanik'])) {
                $where .= " AND (SELECT COUNT(id_karyawan_dealer) FROm ms_h2_pit_mekanik pk WHERE id_pit=pit.id_pit AND id_dealer=pit.id_dealer)>0";
            }
            if (isset($filter['ready_wo'])) {
                $where .= " AND pit.id_pit NOT IN(SELECT sa.id_pit FROM tr_h2_wo_dealer wo JOIN tr_h2_sa_form sa ON sa.id_sa_form=wo.id_sa_form WHERE wo.id_dealer=pit.id_dealer AND sa.id_pit IS NOT NULL AND (wo.status='open' OR wo.status='pause')) ";
            }
            if (isset($filter['select'])) {
                if ($filter['select'] == 'api_sm') {
                    $select = "id_pit_int id, RIGHT(id_pit,3) no,stp.id pit_type_id,stp.jenis_pit pit_type_name";
                }
            }
        }

        return $this->db->query("SELECT $select 
        FROM ms_h2_pit pit
        LEFT JOIN setup_jenis_pit stp ON stp.id_jenis_pit=pit.jenis_pit
        $where ORDER BY id_pit DESC");
    }
    function get_pit_mekanik($filter = null)
    {

        $where = "WHERE 1=1 AND pit.active=1";
        $select = "pit_mk.*,nama_lengkap,kry.id_karyawan_dealer_int,kry.image,kry.jk";

        if (isset($filter['id_dealer'])) {
            $where .= " AND kry.id_dealer='{$filter['id_dealer']}'";
        } else {
            $id_dealer = $this->m_admin->cari_dealer();
            $where .= " AND kry.id_dealer='$id_dealer'";
        }
        if ($filter != null) {
            if (isset($filter['id_pit'])) {
                $where .= " AND pit.id_pit='" . $filter['id_pit'] . "'";
            }
            if (isset($filter['jenis_pit'])) {
                $where .= " AND pit.jenis_pit='{$filter['jenis_pit']}'";
            }
            if (isset($filter['select'])) {
                if ($filter['select'] == 'api_sm') {
                    $select = "id_pit id,RIGHT(id_pit,3) no,stp.id pit_type_id,stp.jenis_pit pit_type_name";
                }
            }

            $sort = '';
            if (isset($filter['sort_by'])) {
                $fil = $filter['sort_by'] == 'name' ? 'kry.nama_lengkap' : 'pit.id_pit';
                $sort = "ORDER BY $fil {$filter['sort_dir']}";
            }
        }

        return $this->db->query("SELECT $select 
        FROM ms_h2_pit_mekanik pit_mk
        JOIN ms_h2_pit pit ON pit.id_pit=pit_mk.id_pit AND pit.id_dealer=pit_mk.id_dealer
        JOIN ms_karyawan_dealer kry ON kry.id_karyawan_dealer=pit_mk.id_karyawan_dealer
        LEFT JOIN setup_jenis_pit stp ON stp.id_jenis_pit=pit.jenis_pit
        $where $sort");
    }
    function get_jenis_pit($filter = NULL)
    {
        $where = "WHERE 1=1";
        if (isset($filter['id'])) {
            $where .= " AND id='{$filter['id']}'";
        }
        return $this->db->query("SELECT id,jenis_pit name FROM setup_jenis_pit $where");
    }
}
