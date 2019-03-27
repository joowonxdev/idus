<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class members extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('member_model'); // member model
    }

    function return_json($return){
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($return));
    }

    public function index(){
        echo 'members';
    }

    public function create(){

        //return data
        $ret = array();


        //get create data
        $member['email'] = strip_tags($this->input->get_post('email'));
        $member['password'] = $this->input->get_post('password');
        $member['name'] = strip_tags($this->input->get_post('name'));
        $member['mobile'] = strip_tags($this->input->get_post('mobile'));
        $member['recommend_code'] = strip_tags($this->input->get_post('recommend_code'));
        $member['service_agree'] = $this->input->get_post('service_agree');
        $member['privacy_agree'] = $this->input->get_post('privacy_agree');
        $member['commercial_agree'] = $this->input->get_post('commercial_agree');

        if(!empty($member['email']) && !empty($member['password']) && !empty($member['name']) && !empty($member['mobile']) ){
            if(!empty($member['service_agree']) && !empty($member['privacy_agree'])){
                //encrypt
                $member['password'] = password_hash($member['password'], PASSWORD_BCRYPT);
                $member['member_code'] = md5($member['email']);

                $this->member_model->setMember($member);
                $ret['code'] = '0';
                $ret['msg'] = 'complete';
            }else{
                $ret['code'] = '98';
                $ret['msg'] = 'check again agree';
            }
        }else{
            $ret['code'] = '99';
            $ret['msg'] = 'not enough data';
        }
        $this->return_json($ret);
    }

    public function update(){


        //return data
        $ret = array();


        //get update data
        $member['password'] = $this->input->get_post('password');
        $member['mobile'] = strip_tags($this->input->get_post('mobile'));
        $member['commercial_agree'] = $this->input->get_post('commercial_agree');

        if( !empty($member['password']) && !empty($member['mobile']) ){
            //encrypt
            $member['password'] = password_hash($member['password'], PASSWORD_BCRYPT);

            $this->member_model->updateMember($member); //update
            $ret['code'] = '0';
            $ret['msg'] = 'complete';

        }else{
            $ret['code'] = '99';
            $ret['msg'] = 'not enough data';
        }
        $this->return_json($ret);
    }

    public function delete(){


        //return data
        $ret = array();


        //get member data
        $member['mem_idx'] = $this->input->get_post('mem_idx');

        if( !empty($member['mem_idx']) ){

            $result = $this->member_model->deleteMember($member); //delete
            if($result->num_rows() > 0 ){
                $ret['code'] = '0';
                $ret['msg'] = 'complete';
            }else{
                $ret['code'] = '98';
                $ret['msg'] = 'no data';
            }


        }else{
            $ret['code'] = '99';
            $ret['msg'] = 'not enough data';
        }
        $this->return_json($ret);
    }

    public function read(){

        //return data
        $ret = array();


        //get member data
        $member['mem_idx'] = $this->input->get_post('mem_idx');

        if( !empty($member['mem_idx']) ){

            $result = $this->member_model->getMember($member); //get
            if($result->num_rows() > 0 ) {
                $ret['code'] = '0';
                $ret['msg'] = 'complete';
                $ret['data']['name'] = $result->name;
                $ret['data']['email'] = $result->email;
                $ret['data']['mobile'] = $result->mobile;
                $ret['data']['mobile_check'] = $result->mobile_check;
                $ret['data']['member_code'] = $result->member_code;
                $ret['data']['createdAt'] = $result->createdAt;
                $ret['data']['service_agree'] = $result->service_agree;
                $ret['data']['privacy_agree'] = $result->privacy_agree;
                $ret['data']['commercial_agree'] = $result->commercial_agree;
            }else{
                $ret['code'] = '98';
                $ret['msg'] = 'no data';
            }

        }else{
            $ret['code'] = '99';
            $ret['msg'] = 'not enough data';
        }
        $this->return_json($ret);
    }

    public function memList(){

        //return data
        $ret = array();


        //get members data
        $size = $this->input->get_post('page_size')? $this->input->get_post('page_size') : 20 ;
        $page = $this->input->get_post('page') ? $this->input->get_post('page') : 1 ;

        if($page == 0 ) $page = 1;

        $offset = $page == 1 ? 0 : ($page - 1)*$size;

        $totalCount = $this->member_model->getMemberCount();

        $num_pages = ceil($totalCount / $size);


        if( $totalCount > 0  ){
            $option = array();
            $option['limit'] = $size;
            $option['offset'] = $offset;
            $result = $this->member_model->getMembers($option); //get

            $ret['code'] = '0';
            $ret['msg'] = 'complete';
            $ret['data']['num_pages'] = $num_pages;
            $ret['data']['page'] = $page;
            $ret['data']['page_size'] = $size;
            $i = 0;
            foreach($result as $row){

                $ret['data']['items'][$i]['name'] = $row->name;
                $ret['data']['items'][$i]['email'] = $row->email;
                $ret['data']['items'][$i]['mobile'] = $row->mobile;
                $ret['data']['items'][$i]['mobile_check'] = $row->mobile_check;
                $ret['data']['items'][$i]['member_code'] = $row->member_code;
                $ret['data']['items'][$i]['createdAt'] = $row->createdAt;
                $ret['data']['items'][$i]['service_agree'] = $row->service_agree;
                $ret['data']['items'][$i]['privacy_agree'] = $row->privacy_agree;
                $ret['data']['items'][$i]['commercial_agree'] = $row->commercial_agree;

            }

        }else{
            $ret['code'] = '99';
            $ret['msg'] = 'not enough data';
        }

        $this->return_json($ret);
    }
}