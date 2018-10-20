<?php
require APPPATH . '/libraries/REST_Controller.php';



class Aparat extends \Restserver\Libraries\REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->CI =& get_instance();
        $this->load->model("peserta");

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
            $this->response($this->wrapper(404,"Data Not Found",""));
        } else {
            $this->response($this->wrapper(200, "Success", $result));
        }
    }

    public function index_get() {
        $this->response($this->wrapper(200,"Testing Okey",""));
    }

}