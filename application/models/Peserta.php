<?php
/**
 * Created by IntelliJ IDEA.
 * User: ewlight
 * Date: 20/10/18
 * Time: 10:36 PM
 */

class Peserta extends CI_Model
{
    public function __construct() {
        parent::__construct();

    }

    public function getPesertaDetail($id) {
        $sqlget = "select fbid, fbtoken, email, name, profilepict from peserta where fbid = $id";
        return $this->db->query($sqlget)->row();
    }

    public function savePeserta($fbid, $fbtoken, $email, $name, $profilepict){
        $checkSavedPeserta = "select id from peserta where fbid = '$fbid'";
        $numPeserta = $this->db->query($checkSavedPeserta)->num_rows();
        if($numPeserta == 0) {
            $savedSql = "insert into peserta values(null,'$fbid','$fbtoken','$email','$name','$profilepict')";
            $this->db->query($savedSql);
            return 1;
        } else {
            return 0;
        }

    }

    public function isPesertaExist($fbid) {
        $sqlget = "select id from peserta where fbid = $fbid";
        return $this->db->query($sqlget)->num_rows();
    }

}