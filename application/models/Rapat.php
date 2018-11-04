<?php
/**
 * Created by IntelliJ IDEA.
 * User: ewlight
 * Date: 04/11/18
 * Time: 11:42 AM
 */

class Rapat extends CI_Model
{
    public function __construct() {
        parent::__construct();

    }

    public function saveRapat($id, $idgroup, $nama, $tanggal, $jam, $deskripsi) {
        $sqlquery = "insert into rapat (id, idgroup, nama, tanggal, jam, deskripsi) values ($id, $idgroup, '$nama', '$tanggal', '$jam', '$deskripsi')";
        return $this->db->query($sqlquery);
    }

    public function  getLastRapatId() {
        $sqlquery = "select id from rapat order by id desc limit 1";
        return $this->db->query($sqlquery)->row();
    }




}