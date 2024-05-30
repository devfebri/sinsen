<?php

class Honda_Model extends CI_Model
{
    protected $table = 'table_name';

    public function __construct()
    {
        parent::__construct();
    }

    public function all()
    {
        return $this->db->select('*')->get($this->table)->result();
    }

    public function find($value, $key = 'id')
    {
        $data = $this->db->select('*')->where($key, $value)->get($this->table)->row();

        return $data;
    }

    public function get($condition, $oneRow = false)
    {
        if ($oneRow) {
            return $this->db->select('*')->where($condition)->get($this->table)->row();
        }
        return $this->db->select('*')->where($condition)->get($this->table)->result();
    }

    public function search($condition, $select = '*')
    {
        $this->db->select($select);
        $index = 1;
        foreach ($condition as $key => $value) {
            if ($index == 1) {
                $this->db->like($key, $value);
            } else {
                $this->db->or_like($key, $value);
            }
            $index++;
        }
        return $this->db->get($this->table)->result();
    }

    public function insert($data)
    {
        if ($this->db->field_exists('created_at', $this->table)) {
            $data['created_at'] = Mcarbon::now()->toDateTimeString();
        }

        return $this->db->insert($this->table, $data);
    }

    public function insert_batch($data)
    {
        if (count($data) > 0) {
            $count = 1;
            foreach ($data as $row) {
                $this->insert($row);
                $count++;
            }
            return $count == count($data);
        }

        return false;
    }

    public function update($data, $condition)
    {
        if ($this->db->field_exists('updated_at', $this->table)) {
            $data['updated_at'] = Mcarbon::now()->toDateTimeString();
        }

        return $this->db
            ->set($data)
            ->where($condition)
            ->update($this->table);
    }

    public function update_batch($data, $condition)
    {
        $this->db->delete($this->table, $condition);
        if (count($data) > 0) {
            $inserted_count = 0;
            foreach ($data as $row) {
                $result = $this->insert($row);
                if ($result) $inserted_count++;
            }

            return count($data) == $inserted_count;
        }
        return false;
    }

    public function insert_or_update($data, $condition)
    {
        $row = $this->get($condition, true);
        if ($row != null) {
            $this->db->delete($this->table, $condition);
        }
        return $this->insert($data);
    }

    public function insert_or_update_batch($data, $condition)
    {
        $row = $this->get($condition);
        if (count($row) > 0) {
            $this->db->delete($this->table, $condition);
        }
        return $this->insert_batch($data);
    }

    public function delete($value, $key = 'id')
    {
        return $this->db->delete($this->table, [$key => $value]);
    }

    public function truncate()
    {
        return $this->db->truncate($this->table);
    }
}
