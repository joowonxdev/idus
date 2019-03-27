<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class member_model extends CI_Model {
    function __construct()
    {
        parent::__construct();

        $this->load->database();

        $this->createtable();
    }

     function createtable(){

            if( $this->db->table_exists('members') == FALSE ){


                $query = file_get_contents('application/models/members.sql');

                if(!$this->db->query($query) )
                {
                    log_message('error:' , $this->db->error()); // Has keys 'code' and 'message'
                    return false;
                }
                return true;
            }

            return true;
    }

     function setMember($option){
        $this->db->set('email', $option['email']);
        $this->db->set('password', $option['password']);
        $this->db->set('name', $option['name']);
        $this->db->set('mobile', $option['mobile']);
        $this->db->set('member_code', $option['member_code']);
        $this->db->set('recommend_code', $option['recommend_code']);
        $this->db->set('service_agree', $option['service_agree']);
        $this->db->set('privacy_agree', $option['privacy_agree']);
        $this->db->set('commercial_agree', $option['commercial_agree']);

        $this->db->insert('members');

        return $this->db->insert_id();
    }

     function updateMember($option){
        $this->db->set('password', $option['password']);
        $this->db->set('mobile', $option['mobile']);
        $this->db->set('commercial_agree', $option['commercial_agree']);
        $this->db->where('mem_idx', $option('mem_idx'));
        $result = $this->db->update('members');

        return $result;
    }

     function deleteMember($option){
        $this->db->where('mem_idx' , $option['mem_idx']);
        $result = $this->db->delete('members');

        return $result;
    }

     function getMember($option){
        $this->db->from('members');
        $whereis = array('mem_idx' => $option['mem_idx']);

        $this->db->where($whereis);

        return $this->db->get()->row();
    }

     function getMembers($option){
        $this->db->from('members');
        $this->db->order_by('mem_idx', 'desc');

        if($option['limit'] > 0){
            $this->db->limit($option['limit'], $option['offset']);
        }

        return  $this->db->get()->result();

    }

     function getMemberCount(){
         $this->db->from('members');
         return $this->db->count_all_results();
     }
}
?>