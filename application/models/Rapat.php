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

    public function saveRapat($idgroup, $nama, $tanggal, $jam, $deskripsi) {
        $sqlquery = "insert into rapat (idgroup, nama, tanggal, jam, deskripsi) values ($idgroup, '$nama', '$tanggal', '$jam', '$deskripsi')";
        return $this->db->query($sqlquery);
    }




}