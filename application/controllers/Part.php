<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Part controller - controls methods manipulating robot's parts
 *
 * @author Jing
 */

class Part extends Application
{
    function __construct()
    {
        parent::__construct();
    }

    // Presents all the robot parts we have in a grid view
    public function index()
    {   
        
        $parts = array();
        $robots = array();

        foreach($this->parts->all() as $part){
            if($part->status == 1){
                $parts[] = $part;
            }
        }
        
        $role = $this->session->userdata('userrole');
        $isAuth = $role == ROLE_OWNER || $role == ROLE_BOSS || $role == ROLE_WORKER;
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
            if($isAuth){
                if($part->pieceId == 1){
                    $cellsForTop[]= $this->parser->parse('_partCell_2', (array) $part, true);
                }else if($part->pieceId == 2){
                    $cellsForTorso[]= $this->parser->parse('_partCell_2', (array) $part, true);
                }else{
                    $cellsForBottom[]= $this->parser->parse('_partCell_2', (array) $part, true);
                }                              
            }else{
                if($part->pieceId == 1){
                    $cellsForTop[]= $this->parser->parse('_partCell_3', (array) $part, true);
                }else if($part->pieceId == 2){
                    $cellsForTorso[]= $this->parser->parse('_partCell_3', (array) $part, true);
                }else{
                    $cellsForBottom[]= $this->parser->parse('_partCell_3', (array) $part, true);
                }
            }
            
        }       
//        foreach ($parts as $part){
//           if($part->pieceId == 1){
//               $cellsForTop[]= $this->parser->parse('_partCell_2', (array) $part, true);
//           }else if($part->pieceId == 2){
//               $cellsForTorso[]= $this->parser->parse('_partCell_2', (array) $part, true);
//           }else{
//               $cellsForBottom[]= $this->parser->parse('_partCell_2', (array) $part, true);
//           }
//       }
                            
        //finally, generate the Top Piece table
        $this->table->set_caption('Top Pieces');
        if(count($cellsForTop) != 0){
            $rows = $this->table->make_columns($cellsForTop, 3);
            $tableHtml = $this->table->generate($rows);
        }else{
            $tableHtml = "<h4 style='text-align:center;'><span style='color:red;'>No Part of Top Pieces</span>: you can click on the Buy box of parts / Retrieve built arts button to get parts </h4>";
        }
        $this->data['thetableTop'] = $tableHtml;

        //generate the Torso Piece table
        $this->table->set_caption('Torso Pieces');
        if(count($cellsForTorso) != 0){
            $rows = $this->table->make_columns($cellsForTorso, 3);
            $tableHtml = $this->table->generate($rows);
        }else{
            $tableHtml = "<h4 style='text-align:center;'><span style='color:red;'>No Part of Torso Pieces</span>: you can click on the Buy box of parts / Retrieve built arts button to get parts </h4>";
        }
        $this->data['thetableTorso'] = $tableHtml;   
        
        //generate the Bottom Piece table
        $this->table->set_caption('Bottom Pieces');
        if(count($cellsForBottom) != 0){
            $rows = $this->table->make_columns($cellsForBottom, 3);
            $tableHtml = $this->table->generate($rows);
        } else{
            $tableHtml = "<h4 style='text-align:center;'><span style='color:red;'>No Part of Bottom Pieces</span>: you can click on the Buy box of parts / Retrieve built arts button to get parts </h4>";
        }

        if($isAuth){
            $tableHtml .= $this->parser->parse('_buildBuyPartBtns',[], true);
        }    
        $this->data['thetableBottom'] = $tableHtml;
        //$this->data['partFormAction'] = $isAuth ? '/assembly/assemblyOrReturn' : '#';
             
        // this is the view we want shown
        $this->data['pagebody'] = 'Part/homepage';
        $this->render();

    } 

    // Presents the detailed information of each robot part
    public function gimme($id) {
        $this->data['pagebody'] = 'Part/justonepart';
        $record = $this->parts->get($id);

        $this->data = array_merge($this->data, (array)$record);
        $this->render();
    }

    //get piece name from piece id
    private function _getPieceName($pieceId){      
        if($pieceId == 1){
            return "top";
        }else if($pieceId == 2){
            return "torso";
        }else if($pieceId == 3){
            return "bottom";
        }
    }

    //get line from model name
    private function _getLine($model)
    {
        $householdRegEx = "/^[A-L]$/i";
        $butlerRegE = "/^[M-V]$/i";
        $companionRegE = "/^[W-Z]$/i";
        if(preg_match($householdRegEx, $model)){
            return "household";
        }else if (preg_match($butlerRegE, $model)){
            return "butler";
        }else if (preg_match($companionRegE, $model)){
            return "companion";
        }
    }

    //get picture path
    private function _getPicPath($model, $pieceId)
    {
        return $model.$pieceId.".jpeg";
    }

    //get part name
    private function _getPartName($model, $pieceId)
    {
        return strtoupper($model).$pieceId;
    }
    
    // purchase a box - 10 parts
     public function buyBox()
    {       
        $base_url = "https://umbrella.jlparry.com/work/buybox/?key=";
        //$token = $this->token->get(1)->token_session;
        $latest_token = $this->token->head(1);
        $token = $latest_token[0]->token_session;
        $tableHtml = "<p></p>";

        $url = $base_url . $token;
        $response = file_get_contents($url);
        $someJSON = $response;
        $someArray = json_decode($someJSON, true);
        
        $responseArray = explode(" ", $response);       
        if ($responseArray[0] == "Oops:"){  
            $this->alert('<strong>Pei Lei: Go make some money for us!<strong>', 'danger');    
        }else{
            // add 10 parts to database parts table 
            foreach ($someArray as $key => $value) {
                $record = $this->parts->create(); 

                $record->id = $value["id"];          
                $record->model = $value["model"];
                $record->pieceId = $value["piece"];
                $record->plant = $value["plant"];
                $record->stamp = $value["stamp"];

                $record->partName = $this->_getPartName($value["model"], $value["piece"]);
                $record->line = $this->_getLine($value["model"]);       
                $record->pieceName = $this->_getPieceName($value["piece"]);          
                $record->pic = $this->_getPicPath($value["model"], $value["piece"]);     
                $record->status = 1;

                $this->parts->add($record);
            }   

            // create add a transaction record to transactions table
            $transaction_history = $this->transactions->create(); 
            $transaction_history->transacType = "purchase";
            $transaction_history->transacMoney = 100;               
            $transaction_history->transacDateTime = date('Y-m-d H:i:s',time());
            $this->transactions->add($transaction_history);
            
            // get the transactionID from the inserted row
            $last_transac_array = $this->transactions->tail(1);   
            $last_transac_id = $last_transac_array[0]->transactionID;
            
//          $last_transac_id = $this->db->select('transactionID')->order_by('transactionID','desc')->limit(1)->get('transactions')->row('transactionID');
                    
            // add purchase parts record to purchasepartsrecords table
            $purchaseParts_history = $this->purchasepartsrecords->create(); 
            $part_num = 0;
            foreach ($someArray as $key => $value) {
                $part_num++;
                if($part_num == 1){
                    $purchaseParts_history->partonecacode = $value["id"];   
                }
                if($part_num == 2){
                    $purchaseParts_history->parttwocacode = $value["id"];   
                }
                if($part_num == 3){
                    $purchaseParts_history->partthreecacode = $value["id"];   
                }
                if($part_num == 4){
                    $purchaseParts_history->partfourcacode = $value["id"];   
                }
                if($part_num == 5){
                    $purchaseParts_history->partfivecacode = $value["id"];   
                }
                if($part_num == 6){
                    $purchaseParts_history->partsixcacode = $value["id"];   
                }
                if($part_num == 7){
                    $purchaseParts_history->partsevencacode = $value["id"];   
                }
                if($part_num == 8){
                    $purchaseParts_history->parteightcacode = $value["id"];   
                }
                if($part_num == 9){
                    $purchaseParts_history->partninecacode = $value["id"];   
                }
                if($part_num == 10){
                    $purchaseParts_history->parttencacode = $value["id"];   
                }           
            }
            $purchaseParts_history->cost = 100;       
            $purchaseParts_history->datetime = date('Y-m-d H:i:s',time());
            $purchaseParts_history->transactionID = $last_transac_id;
            $this->purchasepartsrecords->add($purchaseParts_history);
           
            
            $tableHtml .= $this->parser->parse('buybox_message',[], true);
            
        }

        $account = $this->account->head(1);
        $account[0]->money_spend += 100;
        // $account[0]->money_earned = 0;
        $this->account->update($account[0]);

        $this->data['buybox_message'] = $tableHtml;
        $this->data['pagebody'] = 'Part/buybox';
        $this->render();  
    }
    
    // get the build parts from umbrella company
    // at most get 10 parts each time
    public function myBuilds()
    {        
        $base_url = "https://umbrella.jlparry.com/work/mybuilds/?key=";
        //$token = $this->token->get(1)->token_session;
        $latest_token = $this->token->head(1);
        $token = $latest_token[0]->token_session;       
        $url = $base_url . $token;
        $response = file_get_contents($url);
        $someJSON = $response;
        $someArray = json_decode($someJSON, true);
        $responseArray = explode(" ", $response);
        
        $built_parts = array();
        $tableHtml = "<p></p>";
        //echo "responseArray[0] is " . $responseArray[0];
        if ($responseArray[0] == "[]"){
            $this->alert('<strong>Not enough to retrieve<strong>', 'danger');    
        }else{
            $number_build_part = 0;            
            foreach ($someArray as $key => $value) {
               $number_build_part++;
               $record = $this->parts->create(); 

               $record->id = $value["id"];
               $record->model = $value["model"];
               $record->pieceId = $value["piece"];
               $record->plant = $value["plant"];
               $record->stamp = $value["stamp"];

               $record->partName = $this->_getPartName($value["model"], $value["piece"]);
               $record->line = $this->_getLine($value["model"]);       
               $record->pieceName = $this->_getPieceName($value["piece"]);          
               $record->pic = $this->_getPicPath($value["model"], $value["piece"]);     
               $record->status = 1;
               
               $built_parts[] = $record;
               $this->parts->add($record);
            }     

           // create add a transaction record to transactions table
           $transaction_history = $this->transactions->create(); 
           $transaction_history->transacType = "build";
           $transaction_history->transacMoney = 0;               
           $transaction_history->transacDateTime = date('Y-m-d H:i:s',time());
           $this->transactions->add($transaction_history);
           
           // get the created transaction id
           $last_transac_array = $this->transactions->tail(1);   
           $last_transac_id = $last_transac_array[0]->transactionID; 
            
           // add retrieve history record to database
           $retrieveParts_history = $this->buildpartsrecords->create();
           //echo "number of parts build now" . $number_build_part;
           
           $part_num = 0;
            foreach ($someArray as $key => $value) {
                $part_num++;
                if($part_num == 1){
                    $retrieveParts_history->partonecacode = $value["id"];  
                }
                if($part_num == 2){
                    $retrieveParts_history->parttwocacode = $value["id"];   
                }
                if($part_num == 3){
                    $retrieveParts_history->partthreecacode = $value["id"];   
                }
                if($part_num == 4){
                    $retrieveParts_history->partfourcacode = $value["id"];   
                }
                if($part_num == 5){
                    $retrieveParts_history->partfivecacode = $value["id"];   
                }
                if($part_num == 6){
                    $retrieveParts_history->partsixcacode = $value["id"];   
                }
                if($part_num == 7){
                    $retrieveParts_history->partsevencacode = $value["id"];   
                }
                if($part_num == 8){
                    $retrieveParts_history->parteightcacode = $value["id"];   
                }
                if($part_num == 9){
                    $retrieveParts_history->partninecacode = $value["id"];   
                }
                if($part_num == 10){
                    $retrieveParts_history->parttencacode = $value["id"];   
                }           
            }
           
           $retrieveParts_history->datetime = date('Y-m-d H:i:s',time());
           $retrieveParts_history->transactionID = $last_transac_id;
           $this->buildpartsrecords->add($retrieveParts_history);
           
           $tableHtml .= $this->parser->parse('build_message',[], true);
        }
        $this->data['buybox_message'] = $tableHtml;
        $this->data['pagebody'] = 'Part/mybuilds';
        $this->render(); 
    }  
}
