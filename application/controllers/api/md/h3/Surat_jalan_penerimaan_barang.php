<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Surat_jalan_penerimaan_barang extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('m_admin');
    }

    public function index()
    {
        $this->make_datatables();
        $this->limit();
        
        $data = array();
        $index = 1;
        foreach ($this->db->get()->result_array() as $row) {
            $row['action'] = $this->load->view('additional/md/h3/action_surat_jalan_penerimaan_barang', [
                'data' => json_encode($row),
                'surat_jalan_ahm' => $row['surat_jalan_ahm'],
                'surat_jalan_ahm_int' => $row['surat_jalan_ahm_int'],
                'check_state' => $row['check_state']
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

    public function make_query()
    {
        $penerimaan_telah_dilakukan = $this->db
        ->select('count(pbi.nomor_karton)', false)
        ->from('tr_h3_md_penerimaan_barang_items as pbi')
        ->where('pbi.surat_jalan_ahm_int = psl.id', null, false)
        ->where('pbi.tersimpan', 1)
        ->get_compiled_select();

        $total_item = $this->db
        ->select('count(psp.no_doos)', false)
        ->from('tr_h3_md_psl_items as psli')
        ->join('tr_h3_md_ps_parts as psp', 'psp.packing_sheet_number = psli.packing_sheet_number')
        ->where('psli.surat_jalan_ahm_int = psl.id', null, false)
        ->get_compiled_select();

        $penerimaan_open = $this->db
        ->select('pbsj.surat_jalan_ahm_int')
        ->from('tr_h3_md_penerimaan_barang_surat_jalan_ahm as pbsj')
        ->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_surat_jalan_ekspedisi = pbsj.no_surat_jalan_ekspedisi')
        ->where('pbsj.surat_jalan_ahm_int = psl.id', null, false)
        ->where('pb.status', 'Open')
        ->get_compiled_select()
        ;

        $penerimaan_closed = $this->db
        ->select('pbsj.surat_jalan_ahm_int')
        ->from('tr_h3_md_penerimaan_barang_surat_jalan_ahm as pbsj')
        ->join('tr_h3_md_penerimaan_barang as pb', 'pb.no_surat_jalan_ekspedisi = pbsj.no_surat_jalan_ekspedisi')
        ->where('pbsj.surat_jalan_ahm_int = psl.id', null, false)
        ->where('pb.status', 'Closed')
        ->get_compiled_select();

        // $surat_jalan_ada_faktur = $this->db
        // ->select('DISTINCT(psli.surat_jalan_ahm) as surat_jalan_ahm')
        // ->from('tr_h3_md_fdo_ps as fdo_ps')
        // ->join('tr_h3_md_psl_items as psli', 'psli.packing_sheet_number = fdo_ps.packing_sheet_number')
        // ->get_compiled_select();

       $this->db
        ->select('psl.id as surat_jalan_ahm_int')
        ->select('psl.surat_jalan_ahm')
        ->select("(IFNULL( ({$penerimaan_telah_dilakukan}), 0) = IFNULL( ({$total_item}), 0) AND psl.surat_jalan_ahm IN ({$penerimaan_open})) as check_state", false)
        // ->select("IFNULL( ({$penerimaan_telah_dilakukan}), 0) as penerimaan_telah_dilakukan")
        // ->select("IFNULL( ({$total_item}), 0) as total_item")
        // ->select("psl.surat_jalan_ahm IN ({$penerimaan_open}) as penerimaan_open", false)
        // ->select("psl.surat_jalan_ahm IN ({$penerimaan_closed}) as penerimaan_closed", false)
        // ->select("psl.surat_jalan_ahm IN ({$penerimaan_open}) as open", false)
        ->from('tr_h3_md_psl as psl')
        ->group_start()
        ->where("
            case
                when (IFNULL( ({$penerimaan_telah_dilakukan}), 0) != IFNULL( ({$total_item}), 0) and psl.id in ({$penerimaan_open})) THEN true
                when (IFNULL( ({$penerimaan_telah_dilakukan}), 0) = IFNULL( ({$total_item}), 0) and psl.id in ({$penerimaan_closed})) THEN false
                else true
            end
        ",  null, false)
        ->or_where_in('psl.id', $this->input->post('list_surat_jalan_ahm'))
        ->group_end()
        // ->where("psl.surat_jalan_ahm IN (({$surat_jalan_ada_faktur}))", null, false)
        ;
    }

    public function make_datatables()
    {
        $this->make_query();

        $search = trim($this->input->post('search') ['value']);
        if ($search != '') {
            $this->db->group_start();
            $this->db->like('psl.surat_jalan_ahm', $search);
            $this->db->group_end();
        }

        if (isset($_POST["order"])) {
            $indexColumn = $_POST['order']['0']['column'];
            $this->db->order_by($_POST['columns'][$indexColumn]['data'], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('psl.surat_jalan_ahm', 'asc');
        }
    }

    public function limit(){
        if ($_POST["length"] != - 1) {
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
        return $this->db->count_all_results();
    }
}