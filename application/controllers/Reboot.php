<?php

class Reboot extends Application
{

    function __construct()
    {
        parent::__construct();
    }
    
     public function index() {
         
        $base_url = "https://umbrella.jlparry.com/work/rebootme/?key=";
        $token = $this->token->get(1)->token_session;
        $url = $base_url . $token;
        $response = file_get_contents($url);         
        $responseArray = explode(" ", $response);
         if ($responseArray[0] == "Ok"){
             if($this->parts->size() > 0){
                 $this->db->empty_table('parts');
             }
             if($this->robots->size() > 0){
                 $this->db->empty_table('robots');
             }
             if($this->assemblyrecords->size() > 0){
                 $this->db->empty_table('assemblyrecords');
             }
             if($this->shipmentrecords->size() > 0){
                 $this->db->empty_table('shipmentrecords');
             }
             if($this->purchasepartsrecords->size() > 0){
                 $this->db->empty_table('purchasepartsrecords');
             }
             if($this->returnpartrecords->size() > 0){
                 $this->db->empty_table('returnpartrecords');
             }
             if($this->buildpartsrecords->size() > 0){
                 $this->db->empty_table('buildpartsrecords');
             }
             if($this->transactions->size() > 0){
                $this->db->empty_table('transactions');
            }
//            $this->db->empty_table('parts');
//            $this->db->empty_table('robots');
//            $this->db->empty_table('assemblyrecords');
//            $this->db->empty_table('shipmentrecords');
//            $this->db->empty_table('purchasepartsrecords');
//            $this->db->empty_table('returnpartrecords');
//            $this->db->empty_table('buildpartsrecords');
//            $this->db->empty_table('transactions');
             
            $this->data['message'] = "<p>You have reboot successfully</p>";
         }else{
            $this->data['message'] = "<p>Reboot failed</p>";
            $this->alert('<strong>Reboot failed<strong>', 'danger');
            $this->index();
         }
                
         
        $this->data['pagebody'] = 'Manage/reboot';
        $this->render();

    }
    
}