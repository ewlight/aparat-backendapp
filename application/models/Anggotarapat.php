<?php
/**
 * Created by IntelliJ IDEA.
 * User: ewlight
 * Date: 04/11/18
 * Time: 3:17 PM
 */

class Anggotarapat extends CI_Model
{
    public function __construct() {
        parent::__construct();

    }

    public function saveAnggotaRapat($idrapat, $idgroup, $fbid, $kehadiran) {
        $sqlquery = "insert into anggotarapat values(NULL , $idrapat, $idgroup, $fbid, $kehadiran)";
        return $this->db->query($sqlquery);
    }

}