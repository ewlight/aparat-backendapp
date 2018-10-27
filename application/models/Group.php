<?php
/**
 * Created by IntelliJ IDEA.
 * User: ewlight
 * Date: 21/10/18
 * Time: 4:27 PM
 */

class Group extends CI_Model
{
    public function __construct() {
        parent::__construct();

    }

    public function saveGroup($id, $adminGroup, $namaGroup) {
        $saveGroup = "insert into aparatgroup values ($id, '$adminGroup','$namaGroup')";
        return $this->db->query($saveGroup);
    }

    public function getLastIdGroup() {
        $sql = "select id from aparatgroup order by id desc limit 1";
        return $this->db->query($sql)->row();
    }

}