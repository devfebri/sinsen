<?php

class H3_md_serah_terima_sj_model extends Honda_Model
{

	protected $table = 'tr_h3_md_serah_terima_sj';

	public function insert($data)
	{
		$data['created_at'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('id_user');
		$data['status'] = 'Open';

		parent::insert($data);
	}

	public function update($data, $condition)
	{
		$data['updated_at'] = date('Y-m-d H:i:s', time());
		$data['updated_by'] = $this->session->userdata('id_user');

		parent::update($data, $condition);
	}


	public function set_proses($id)
	{
		$serah_terima_sudah_approved = $this->db
			->select('st.proses_at')
			->from(sprintf('%s as st', $this->table))
			->where('st.id', $id)
			->where('st.status', 'Processed')
			->get()->row_array();

		if ($serah_terima_sudah_approved != null) {
			throw new Exception(sprintf('Serah terima surat jalan sudah pernah diproses sebelumnya pada tanggal %s', Mcarbon::parse($serah_terima_sudah_approved['proses_at'])->format('d/m/Y H:i')));
		}

		$this->update([
			'proses_at' => Mcarbon::now()->toDateTimeString(),
			'proses_by' => $this->session->userdata('id_user'),
			'status' => 'Processed'
		], ['id' => $id]);

		log_message('info', sprintf('Serah terima surat jalan di proses [%s]', $id));
	}

	public function set_reject($id)
	{
		$serah_terima_sudah_reject = $this->db
			->select('st.rejected_at')
			->from(sprintf('%s as st', $this->table))
			->where('st.id', $id)
			->where('st.status', 'Rejected')
			->get()->row_array();

		if ($serah_terima_sudah_reject != null) {
			throw new Exception(sprintf('Serah terima surat jalan sudah pernah ditolak sebelumnya pada tanggal %s', Mcarbon::parse($serah_terima_sudah_reject['rejected_at'])->format('d/m/Y H:i')));
		}

		$this->update([
			'rejected_at' => Mcarbon::now()->toDateTimeString(),
			'rejected_by' => $this->session->userdata('id_user'),
			'status' => 'Rejected'
		], ['id' => $id]);

		log_message('info', sprintf('Serah terima surat jalan di reject [%s]', $id));
	}

	public function generate_id()
	{
		$tahun = date('Y', time());
		$tahun_bulan_tanggal = date('dmy');

		$query = $this->db
			->select('id_serah_terima_sj')
			->from($this->table)
			->where("LEFT(created_at, 4)='{$tahun}'")
			->order_by('id_serah_terima_sj', 'desc')
			->order_by('id', 'desc')
			->order_by('created_at', 'desc')
			->limit(1)
			->get();

		if ($query->num_rows() > 0) {
			$row = $query->row();
			$id_serah_terima_sj = substr($row->id_serah_terima_sj, 0, 4);
			$id_serah_terima_sj = sprintf("%'.04d", $id_serah_terima_sj + 1);
			$id = "{$id_serah_terima_sj}-{$tahun_bulan_tanggal}";
		} else {
			$id = "0001-{$tahun_bulan_tanggal}";
		}

		return strtoupper($id);
	}
}
