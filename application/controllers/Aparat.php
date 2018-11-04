<?php
require APPPATH . '/libraries/REST_Controller.php';
require_once FCPATH . '/vendor/autoload.php';


class Aparat extends \Restserver\Libraries\REST_Controller {

    public function __construct() {
        parent::__construct();

        $this->CI =& get_instance();
        $this->load->model("peserta");
        $this->load->model("group");
        $this->load->model("usergroup");
        $this->load->model("rapat");

    }

    public function wrapper($code,$message,  $data) {
        return array('status' => $code, 'message' => $message, 'data' => $data);
    }

    public function login_post() {
        $fbid = $this->post("fbid");
        $fbtoken = $this->post("fbtoken");
        $email = $this->post("email");
        $name = $this->post("name");
        $profilepict = $this->post("profilepict");

        $result = $this->peserta->savePeserta($fbid, $fbtoken, $email, $name, $profilepict);

        if($result == 1) {
            $this->response($this->wrapper(200, "Welcome New User - $name",""), 200);
        } else {
            $this->response($this->wrapper(200, "Welcome Back $name",""), 200);
        }

    }

    public function profile_get() {
        $fbid = $this->get("fbid");
        $result = $this->peserta->getPesertaDetail($fbid);
        if(empty($result) || is_null($result)) {
            $this->response($this->wrapper(404,"Data Not Found",""), 404);
        } else {
            $this->response($this->wrapper(200, "Success", $result), 200);
        }
    }

    public function index_get() {
        $this->response($this->wrapper(200,"Testing Okey",""), 200);
    }

    public function newgroup_post(){
        $groupname = $this->post("groupname");
        $fbid = $this->post("fbid");

        $lastGroupId = $this->group->getLastIdGroup()->id;
        $newGroupId = $lastGroupId + 1;
        $saveGroup = $this->group->saveGroup($newGroupId, $fbid, $groupname);
        if($saveGroup) {
            $this->usergroup->saveUserGroup($newGroupId, $fbid, 1);
            $this->response($this->wrapper(201, "Success", ""), 201);
        }
        else {
            $this->response($this->wrapper(422,"Unprocessable Entity",""), 422);
        }

    }

    public function grouplist_get() {
        $fbid = $this->get("fbid");
        $data = $this->usergroup->getGroup($fbid);
        $this->response($this->wrapper(200,"Success", $data), 200);
    }

    public function groupmember_get() {
        $groupId = $this->get("groupid");
        $data = $this->usergroup->getGroupMember($groupId);
        $this->response($this->wrapper(200,"Success", $data), 200);
    }


    public function sendpush_post() {
        $target = $this->post("target");
        $title = $this->post("title");
        $message = $this->post("message");
        $this->pushNotifEngine($target, $title, $message);
    }

    public function invitemember_post() {
        $idmember = $this->post("memberid");
        $idgroup = $this->post("idgroup");
        $groupname = $this->post("groupname");
        $isMemberExist = $this->peserta->isPesertaExist($idmember);
        if($isMemberExist == 1) {
            $isMemberAlreadyJoin = $this->usergroup->checkMemberGroup($idgroup, $idmember);
            if($isMemberAlreadyJoin == 1) {
                $this->response($this->wrapper(422,"Member sudah terdaftar di group", ""), 422);
            } else{
                $this->usergroup->saveUserGroup($idgroup, $idmember, 0);
                $target = array($idmember);
                $title = "Selamat Bergabung !!!";
                $message = "Anda baru saja bergabung dengan $groupname";
                $this->pushNotifEngine($target, $title, $message);
                $this->response($this->wrapper(201, "Success", ""), 201);
            }
        } else {
            $this->response($this->wrapper(422,"Member tidak bisa ditemukan di system Aparat", ""), 422);
        }


    }

    public function pushNotifEngine($target, $title, $message) {
        include FCPATH . '/vendor/pusher/pusher-push-notifications/src/PushNotifications.php';
        try {
            $pushNotifications = new \Pusher\PushNotifications\PushNotifications(array(
                "instanceId" => "455fc469-f92d-448a-92d3-c732a106ba07",
                "secretKey" => "A8DBB5DB4B5793C574936D89EC16ADEB106201FE5934BE7BD810B9BDEECEE1BF",
            ));
            $publishResponse = $pushNotifications->publish(
                $target,
                array(
                    "fcm" => array(
                        "notification" => array(
                            "title" => $title,
                            "body" => $message
                        )
                    )
                ));
            log_message('info', $publishResponse);
        } catch (Exception $e) {
            log_message('error', "[PUSHNOTIF] Error: $e");
        }
    }

    public function newpengumuman_post() {
        $idgroup = $this->post("idgroup");
        $nama = $this->post("nama");
        $tanggal = $this->post("tanggal");
        $jam = $this->post("jam");
        $deskripsi = $this->post("deskripsi");

        $saverapat = $this->rapat->saveRapat($idgroup, $nama, $tanggal, $jam, $deskripsi);
        if($saverapat) {
            $membergroup = $this->arrayConverter($this->usergroup->getPesertaRapat($idgroup));
            $title = "PENGUMUMAN RAPAT";
            $convertTangal = $this->convertDateFormat($tanggal);
            $message = "$nama tanggal $convertTangal";
            $this->pushNotifEngine($membergroup, $title, $message);
            $this->response($this->wrapper(201, "Success", ""), 201);

        } else {
            $this->response($this->wrapper(422,"Pengumumuman Rapat Baru Gagal di Simpan", ""), 422);
        }
    }

    public function arrayConverter($objectResult) {

        foreach($objectResult as $object) {
            $result = $object->idfb;
            $dataInArray[] = "$result";
        }
        return $dataInArray;
    }

    public function convertDateFormat($origindate) {
        return date("d M Y", strtotime($origindate));
    }

}
