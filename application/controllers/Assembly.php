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

        $role = $this->session->userdata('userrole');
        $isAuth = $role == ROLE_OWNER || $role == ROLE_BOSS;
        // show roles
        $this->data['pagetitle'] = 'CuteRobot ('. $role . ')';

        //prime the table class
        $this->load->library('table');
        $prams = array(
            'table_open' => '<table class="gallaryTable">',
            'cell_start' => '<td class="oneCell">',
            'cell_alt_start' => '<td class="oneCell">'
        );
        $this->table->set_template($prams);

        $cellsForTop = array();
        $cellsForTorso = array();
        $cellsForBottom = array();

        //build array of formatted cells for them
        foreach ($parts as $part){
            if($isAuth){
                $part->disabled = "";
            }else{
                $part->disabled = "disabled";
            }
            if($part->pieceId == 1){
                $cellsForTop[]= $this->parser->parse('_partCell', (array) $part, true);
            }else if($part->pieceId == 2){
                $cellsForTorso[]= $this->parser->parse('_partCell', (array) $part, true);
            }else{
                $cellsForBottom[]= $this->parser->parse('_partCell', (array) $part, true);
            }
        }

        //finally, generate the Top Piece table
        $this->table->set_caption('Top Pieces');
        if(count($cellsForTop) != 0){
            $rows = $this->table->make_columns($cellsForTop, 3);
            $tableHtml = $this->table->generate($rows);
        }else{
            $tableHtml = "<h4 style='text-align:center;'><span style='color:red;'>No Part of Top Pieces</span>: you need <a href='/part'>buy or build parts</a> first if you want to assembly a robot.</h4>";
        }
        $this->data['thetableTop'] = $tableHtml;

        //generate the Torso Piece table
        $this->table->set_caption('Torso Pieces');
        if(count($cellsForTorso) != 0){
            $rows = $this->table->make_columns($cellsForTorso, 3);
            $tableHtml = $this->table->generate($rows);
        }else{
            $tableHtml = "<h4 style='text-align:center;'><span style='color:red;'>No Part of Torso Pieces</span>: you need <a href='/part'>buy or build parts</a> first if you want to assembly a robot.</h4>";
        }
        $this->data['thetableTorso'] = $tableHtml;

        //generate the Bottom Piece table
        $this->table->set_caption('Bottom Pieces');
        if(count($cellsForBottom) != 0){
            $rows = $this->table->make_columns($cellsForBottom, 3);
            $tableHtml = $this->table->generate($rows);
        } else{
            $tableHtml = "<h4 style='text-align:center;'><span style='color:red;'>No Part of Bottom Pieces</span>: you need <a href='/part'>buy or build parts</a> first if you want to assembly a robot.</h4>";
        }
        if($isAuth){
            $tableHtml .= $this->parser->parse('_assemblyReturnBtns',[], true);
        }
        $this->data['thetableBottom'] = $tableHtml;
        $this->data['partFormAction'] = $isAuth ? '/assembly/assemblyOrReturn' : '#';


        //robot table
        $cellsForRobots=  array();
        foreach ($robots as $robot){
            if($isAuth){
                $robot->disabled = "";
            }else{
                $robot->disabled = "disabled";
            }
            $robot->part1Pic = $this->parts->get($robot->part1CA)->pic;
            $robot->part2Pic = $this->parts->get($robot->part2CA)->pic;
            $robot->part3Pic = $this->parts->get($robot->part3CA)->pic;
            $cellsForRobots[] = $this->parser->parse('_robotCell', (array)$robot, true);
        }

        $this->table->set_caption('Assembled Robots');
        if(count($cellsForRobots) != 0){
            $rows = $this->table->make_columns($cellsForRobots, 3);
            $tableHtml = $this->table->generate($rows);
        }else{
            $tableHtml = "<h4 style='text-align:center;'><span style='color:red;'>No Assembled Robot</span>: you need choose parts to assembly a robot.</h4>";
        }
        if($isAuth){
            $tableHtml .= $this->parser->parse('_shipButton',[], true);
        }
        $this->data['thetableRobots'] = $tableHtml;
        $this->data['robotFormAction'] = $isAuth ? '/assembly/shipRobot' : '#';

        // this is the view we want shown
        $this->data['pagebody'] = 'Assembly/homepage';
        $this->render();
    }

    //when click assembly or return button, handle posted info
    public function assemblyOrReturn(){
        $role = $this->session->userdata('userrole');
        if ($role != ROLE_OWNER && $role != ROLE_BOSS) {
            redirect('/assembly');
        }

        //when assembly button click
        if (isset($_POST['assembly'])) {
            $error_msgs = array();
            $partIds = array();
            $orderedPartsIds = array();

            foreach ($this->input->post() as $key => $value) {
                if (substr($key, 0, 4) == 'part') {
                    $partIds[] = substr($key, 4);
                }
            }
            //validate whether exactly choose 3 parts
            if(count($partIds) != 3 ){
                $error_msgs[]="You must choose exactly 3 parts to assembly a robot!";
            }
            //validate whether choose one part for each category piece
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
                        $error_msgs[] = "You must choose only one part from each category to assembly a robot!";
                        break;
                    }
                }
            }

            // use three valid parts to assembly robot
            if (count($error_msgs) == 0) {
                $robot = $this->robots->create();
                $robot->part1CA = $orderedPartsIds[0];
                $robot->part2CA = $orderedPartsIds[1];
                $robot->part3CA = $orderedPartsIds[2];

                $robot->timestamp = date('Y-m-d H:i:s',time());

                $robot->status = 1;
                $this->robots->add($robot);
                foreach ($orderedPartsIds as $orderedPartsId){
                    $part = $this->parts->get($orderedPartsId);
                    $part->status = 2; // part is used to assembly robot
                    $this->parts->update($part);
                }
                $this->alert('Assembly a robot Successful!');
            }else
            {
                $this->alert('<strong>Validation errors!<strong><br>'.$error_msgs[0], 'danger');
            }
        } else if(isset($_POST['return'])){
            //when return button click
            $url = BASE_URL."/work/recycle";
            $partsUrl = "";
            //get earned money
            $account = $this->account->head();
            $earn = $account[0]->money_earned;

            $partIds = array();
            foreach ($this->input->post() as $key => $value) {
                if (substr($key, 0, 4) == 'part') {
                    $partIds[] = substr($key, 4);
                }
            }
            if (count($partIds) < 1 ) {
                //Error: no checkbox is chosen, show error
                $error_msgs[]="You must choose at lease one part to return to head office!";
                $this->alert('<strong>Validation errors!<strong><br>'.$error_msgs[0], 'danger');
            } else {
                //return validate successful
                foreach( $partIds as $partId) {
                    $partsUrl .= "/".$partId;
                    if(substr_count($partsUrl, "/") == 3){
                        $allUrl = $url.$partsUrl;
                        $response = file_get_contents($allUrl.'?key=21b617');
                        $responseArray = explode(" ", $response);
                        if ($responseArray[0] == "Ok"){
                            $earn += $responseArray[1];
                        }
                        $partsUrl = "";
                    }

                    //update status info on part table on db
                    $part = $this->parts->get($partId);
                    $part->status = 0; // return part
                    $this->parts->update($part);

                    //insert return record into return record table on db
                    $returnRecord = $this->returnpartrecords->create();
                    $returnRecord->partcacode = $partId;
                    $returnRecord->earning = 5;
                    $returnRecord->datetime = date('Y-m-d H:i:s',time());
                    $this->returnpartrecords->add($returnRecord);
                }
                if ($partsUrl != "") {
                    $allUrl = $url.$partsUrl;
                    $response = file_get_contents($allUrl.'?key=21b617');
                    $responseArray = explode(" ", $response);
                    if ($responseArray[0] == "Ok"){
                        $earn += $responseArray[1];
                    }
                }

                //update earned money on account table on db
                $account[0]->money_earned = $earn;
                $this->account->update($account[0]);
                $this->alert('Return Parts Successful!<br>You have earned $'.$earn);
            }
        }else{
            //doesn't click return button
            redirect('/assembly');
        }
        $this->index();
    }

    //when click ship robots button, handle posted info
    public function shipRobot(){
        $role = $this->session->userdata('userrole');
        if ($role != ROLE_OWNER && $role != ROLE_BOSS) {
            redirect('/assembly');
        }
        if(!isset($_POST['shipRobot'])){
            //doesn't click ship button
            redirect('/assembly');
        }
        //when ship button click
        $robotIds = array();
        foreach ($this->input->post() as $key => $value) {
            if (substr($key,0,5) == 'robot') {
                $robotIds[] = substr($key,5);
            }
        }
        $error_msgs = array();
        if(count($robotIds) < 1 ){
            //Error: of no checkbox is chosen, show error msg
            $error_msgs[] = "You must choose at lease one robot to ship to head office!";
            $this->alert('<strong>Validation errors!<strong><br>'.$error_msgs[0], 'danger');
        } else {
            //validation successful:
            $url = BASE_URL . "/work/buymybot";
            $account = $this->account->head();
            $earn = $account[0]->money_earned;

            foreach ($robotIds as $robotId) {
                $robot = $this->robots->get($robotId);
                $allUrl = $url."/" . $robot->part1CA . "/" . $robot->part2CA . "/" . $robot->part3CA;
                $response = file_get_contents($allUrl . '?key=21b617');
                $responseArray = explode(" ", $response);
                if ($responseArray[0] == "Ok") {
                    $earn += $responseArray[1];
                    $robot->status = 0; // ship robot
                    $this->robots->update($robot);
                } else {
                    $error_msgs = explode(" Hmmm - ", $response);
                    $messages = "";
                    foreach ($error_msgs as $error_msg) {
                        $messages .= $error_msg . '<br>';
                    }
                    $this->alert('<strong>Validation errors!<strong><br>' . $messages, 'danger');
                    break;
                }
            }
            if(count($error_msgs) == 0){
                //update account table
                $account[0]->money_earned = $earn;
                $this->account->update($account[0]);
                $this->alert('Ship Robots Successful!<br>You have earned $'.$earn);
            }
        }
        $this->index();
    }
}