<?php
/**
 * Created by IntelliJ IDEA.
 * User: ewlight
 * Date: 21/10/18
 * Time: 4:39 PM
 */

class Usergroup extends CI_Model
{
    public function __construct() {
        parent::__construct();

    }

    public function saveUserGroup($idgroup, $idfb, $isAdmin) {
        $save = "insert into usergroup values (null, $idgroup, '$idfb', $isAdmin)";
        return $this->db->query($save);
    }

    public function getGroup($fbid) {
        $sqlget = "select a.id, a.nama as name from aparatgroup a, usergroup u where a.id = u.idgroup and u.idfb = '$fbid' order by id DESC ";
        return $this->db->query($sqlget)->result();
    }

    public function getGroupMember($idgroup) {
        $sqlget = "select ug.id, ug.idgroup, ug.idfb, ug.is_admin, p.name, p.profilepict  from usergroup ug, peserta p where idgroup = $idgroup and ug.idfb = p.fbid";
        return $this->db->query($sqlget)->result();
    }

    public function checkMemberGroup($idgroup, $fbid) {
        $sqlget = "select id from usergroup where idgroup = $idgroup and idfb = $fbid";
        return $this->db->query($sqlget)->num_rows();
    }

}