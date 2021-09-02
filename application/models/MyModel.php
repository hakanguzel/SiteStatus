<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MyModel extends CI_Model
{
    public function getsites()
    {
        return $this->db->from('sites')->where('DATE(lastcontrol) != DATE(CURDATE())', null)->get()->result();
    }

    public function updatesitestatus($id, $status)
    {
        $data = array(
            'sitestatus' => $status,
            'lastcontrol' => date("Y/m/d")
        );
        return $this->db->where('id', $id)->update('sites', $data);
    }
}
