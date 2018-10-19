<?php
require APPPATH . '/libraries/REST_Controller.php';

class Aparat extends \Restserver\Libraries\REST_Controller {
    public function index_get()
    {
        $this->response("hello");
    }
}