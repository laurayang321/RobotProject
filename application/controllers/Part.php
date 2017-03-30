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
        $parts = $this->parts->all(); 
        
        foreach ($parts as $part){
           if($part->pieceId == 1){
               $cellsForTop[]= $this->parser->parse('_partCell_2', (array) $part, true);
           }else if($part->pieceId == 2){
               $cellsForTorso[]= $this->parser->parse('_partCell_2', (array) $part, true);
           }else{
               $cellsForBottom[]= $this->parser->parse('_partCell_2', (array) $part, true);
           }
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
        if($pieceId === 1){
            return "top";
        }else if($pieceId === 2){
            return "torso";
        }else if($pieceId === 3){
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
    
     public function add()
    {
//         `id` varchar(6) NOT NULL PRIMARY KEY,
//  `model` varchar(2) DEFAULT NULL,
//  `pieceId` int(1) DEFAULT NULL,
//  `plant` varchar(10) DEFAULT NULL,
//  `stamp` timestamp NOT NULL,
//
//  `partName` varchar(5) DEFAULT NULL,
//  `line` varchar(60) DEFAULT NULL,
//  `pieceName` varchar(60) DEFAULT NULL,
//  `pic` varchar(60) DEFAULT NULL,
//  `status`
//        $part_id = "13ffb4";
//        $part_model = "b";
//        $part_pieceId = 1;
//        $part_plant = "lemon";
//        $part_stamp = "2017-03-30 02:08:15";
//        $part_pieceName = "B1";
//        $part_line = "household";
//        $part_pic = "b1.jpeg";
//        $part_status = 1;
//        
//        $data = array(
//            'id' => '13ffb4',
//            'model' => "b",
//            'pieceId' => 1,
//            'plant' => "lemon",
//            'stamp' => "2017-03-30 02:08:15",
//            'pieceName' => "B1",
//            'line' => "household",
//            'pic' => "b1.jpeg",
//            'status' => 1
//         );
        
       $jsonArray = '[{"id":"13ffb4","model":"b","piece":1,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"287c1d","model":"a","piece":2,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"4154f8","model":"w","piece":2,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"2f168f","model":"a","piece":3,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"2b16d8","model":"c","piece":1,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"439d28","model":"a","piece":3,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"45a1fb","model":"b","piece":3,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"151c26","model":"w","piece":3,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"22f7ac","model":"w","piece":2,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"10deca","model":"b","piece":1,"plant":"lemon","stamp":"2017-03-30 02:08:15."}]';
        foreach($jsonArray as $part){
            $record = $this->parts->create();
            
           // $record->name = $part->name;
            $record->id=$part->id;
            $record->model = $part->model;
            $record->pieceId = $part->pieceId;
            $record->plant = $part->plant;
            $record->stamp = $part->stamp;
            $record->partName = $part->partName;
            $record->line = $part->line;
            $record->pieceName = $part->pieceName;
            $record->pic = $part->pic;
            $record->status = $part->status;
            
            $this->parts->add($record);
        }
//       
//        $part = $this->parts->create();
//        
//        $part->id="13ffb5";
//        $part->model = "z";
//        $part->pieceId = 7;
//        $part->plant = "lemon";
//        $part->stamp = "2017-03-30 02:08:15";
//        $part->partName = "B1";
//        $part->line = "household";
//        $part->pieceName = "top";
//        $part->pic = "b1.jpeg";
//        $part->status = 1;
//
//        $this->parts->add($part);
    }
}
