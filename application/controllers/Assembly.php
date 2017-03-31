<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Assembly extends Application
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Assembly robots by parts page 
     */
    public function index()
    {
        // build the list of parts and robots, to pass on to our view
        $parts = array();
        $robots = array();

        foreach($this->parts->all() as $part){
            if($part->status == 1){
                $parts[] = $part;
            }
        }
        foreach($this->robots->all() as $robot){
            if($robot->status == 1){
                $robots[] = $robot;
            }
        }

        //build array of formatted cells for them
        foreach ($parts as $part){
            if($part->pieceId == 1){
                $cellsForTop[]= $this->parser->parse('_partCell', (array) $part, true);
            }else if($part->pieceId == 2){
                $cellsForTorso[]= $this->parser->parse('_partCell', (array) $part, true);
            }else{
                $cellsForBottom[]= $this->parser->parse('_partCell', (array) $part, true);
            }
        }

        foreach ($robots as $robot){
            $robot->part1Pic = $this->parts->get($robot->part1CA)->pic;
            $robot->part2Pic = $this->parts->get($robot->part2CA)->pic;
            $robot->part3Pic = $this->parts->get($robot->part3CA)->pic;
            $cellsForRobots[] = $this->parser->parse('_robotCell', (array)$robot, true);
        }

        //prime the table class
        $this->load->library('table');
        $prams = array(
            'table_open' => '<table class="gallaryTable">',
            'cell_start' => '<td class="oneCell">',
            'cell_alt_start' => '<td class="oneCell">'
        );
        $this->table->set_template($prams);

        //finally, generate the table
        $this->table->set_caption('Top Pieces');
        $rows = $this->table->make_columns($cellsForTop, 3);
        $this->data['thetableTop'] = $this->table->generate($rows);

        $this->table->set_caption('Torso Pieces');
        $rows = $this->table->make_columns($cellsForTorso, 3);
        $this->data['thetableTorso'] = $this->table->generate($rows);

        $this->table->set_caption('Bottom Pieces');
        $rows = $this->table->make_columns($cellsForBottom, 3);
        $this->data['thetableBottom'] = $this->table->generate($rows);

        $this->table->set_caption('Assembled Robots');
        $rows = $this->table->make_columns($cellsForRobots, 3);
        $this->data['thetableRobots'] = $this->table->generate($rows);

        $role = $this->session->userdata('userrole');
        $this->data['partFormAction'] = ($role == ROLE_OWNER) ? '/assembly/assemblyOrReturn' : '#';
        $this->data['robotFormAction'] = ($role == ROLE_OWNER) ? '/assembly/shipRobot' : '#';
        // this is the view we want shown
        $this->data['pagebody'] = 'Assembly/homepage';
        $this->render();
    }

    //when click assembly or return button, handle posted info
    public function assemblyOrReturn(){
        $role = $this->session->userdata('userrole');
        if ($role != ROLE_OWNER) redirect('/assembly');

        //assembly button click
        if (isset($_POST['assembly'])) {
            $error_msgs = array();
            $partIds = array();
            $orderedPartsIds = array();

            foreach ($this->input->post() as $key => $value) {
                if (substr($key, 0, 4) == 'part') {
                    $partIds[] = substr($key, 4);
                }
            }
            if(count($partIds) != 3 ){
                $error_msgs[]="You must choose 3 parts to assembly a robot!";
            }

            if(count($error_msgs) == 0){
                $pieceIds = [1,2,3];
                foreach ($partIds as $partId){
                    $pieceId = $this->parts->get($partId)->pieceId;
                    if(($key = array_search($pieceId, $pieceIds)) !== false) {
                        if($pieceId == 1){
                            $orderedPartsIds[0] = $partId;
                        }else if($pieceId == 2){
                            $orderedPartsIds[1] = $partId;
                        }else {
                            $orderedPartsIds[2] = $partId;
                        }
                        unset($pieceIds[$key]);
                    }else{
                        $error_msgs[]="You must choose only one part from each category to assembly a robot!";
                        break;
                    }
                }
            }

            // validate part to assembly robot
            if (count($error_msgs) == 0) {
                $robot = $this->robots->create();
                $robot->part1CA = $orderedPartsIds[0];
                $robot->part2CA = $orderedPartsIds[1];
                $robot->part3CA = $orderedPartsIds[2];
                $robot->timestamp = date('yyyy-mm-dd', time());
                $robot->status = 1;
                $this->robots->add($robot);
                foreach ($orderedPartsIds as $orderedPartsId){
                    $part = $this->parts->get($orderedPartsId);
                    $part->status = 2; // assembly
                    $this->parts->update($part);
                }
            }else
            {
                $this->alert('<strong>Validation errors!<strong><br>'.$error_msgs[0], 'danger');
            }
        } else {
            //return button click
            $url = BASE_URL."/work/recycle";
            $partsUrl = "";
            $account = $this->account->head();
            $earn = $account[0]->money_earned;
            // loop over the post fields, looking for flagged parts
            foreach( $this->input->post() as $key => $value) {
                if (substr($key,0,4) == 'part') {
                    // find the associated part
                    $partid = substr($key,4);
                    $partsUrl .= "/".$partid;
                    if(substr_count($partsUrl, "/") == 3){
                        $url .= $partsUrl;
                        $response = file_get_contents($url.'?key=21b617');
                        $responseArray = explode(" ", $response);
                        if ($responseArray[0] == "Ok"){
                            $earn += $responseArray[1];
                        }
                        $partsUrl = "";
                    }
                    $part = $this->parts->get($partid);
                    $part->status = 0; // return
                    $this->parts->update($part);
                }
            }
            if($partsUrl != ""){
                $url .= $partsUrl;
                $response = file_get_contents($url.'?key=21b617');
                $responseArray = explode(" ", $response);
                if ($responseArray[0] == "Ok"){
                    $earn += $responseArray[1];
                }
            }
            $account[0]->money_earned = $earn;
            $this->account->update($account[0]);
        }
        $this->index();
    }
    //when click ship robots button, handle posted info
    public function shipRobot(){
        $role = $this->session->userdata('userrole');
        if ($role != ROLE_OWNER) redirect('/assembly');

        //ship button click
        $url = BASE_URL."/work/buymybot";
        $account = $this->account->head();
        $earn = $account[0]->money_earned;
        // loop over the post fields, looking for flagged parts
        foreach( $this->input->post() as $key => $value) {
            if (substr($key,0,5) == 'robot') {
                // find the associated part
                $robotid = substr($key,5);
                $robot = $this->robots->get($robotid);
                $url .= "/".$robot->part1CA."/".$robot->part2CA."/".$robot->part3CA;
                $response = file_get_contents($url.'?key=21b617');
                $responseArray = explode(" ", $response);
                if ($responseArray[0] == "Ok"){
                    $earn += $responseArray[1];
                    $robot->status = 0; // return robot
                    $this->robots->update($robot);
                }else{
                    $error_msgs = explode(" Hmmm - ", $response);
                    $messages = "";
                    foreach ($error_msgs as $error_msg){
                        $messages .= $error_msg.'<br>';
                    }
                    $this->alert('<strong>Validation errors!<strong><br>'.$messages, 'danger');
                    break;
                }
            }
        }
        $account[0]->money_earned = $earn;
        $this->account->update($account[0]);
        $this->index();
    }
}