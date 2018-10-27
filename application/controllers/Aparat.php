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
            $this->response($this->wrapper(200,"Success", $publishResponse), 200);
        } catch (Exception $e) {
            $this->response($this->wrapper(422,"Send Push Failed", $e), 422);
        }

    }

}