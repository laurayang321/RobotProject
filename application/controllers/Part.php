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
    
     public function buyBox()
    {        
        //$someJSON = '[{"id":"13ffb4","model":"b","piece":1,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"287c1d","model":"a","piece":2,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"4154f8","model":"w","piece":2,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"2f168f","model":"a","piece":3,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"2b16d8","model":"c","piece":1,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"439d28","model":"a","piece":3,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"45a1fb","model":"b","piece":3,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"151c26","model":"w","piece":3,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"22f7ac","model":"w","piece":2,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"10deca","model":"b","piece":1,"plant":"lemon","stamp":"2017-03-30 02:08:15."}]';     
        
        $response = file_get_contents('https://umbrella.jlparry.com/work/buybox/?key=25f80d');
        $someJSON = $response;
        $someArray = json_decode($someJSON, true);
        
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
    }
    
    public function mybuilds()
    {        
        //$someJSON = '[{"id":"13ffb4","model":"b","piece":1,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"287c1d","model":"a","piece":2,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"4154f8","model":"w","piece":2,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"2f168f","model":"a","piece":3,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"2b16d8","model":"c","piece":1,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"439d28","model":"a","piece":3,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"45a1fb","model":"b","piece":3,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"151c26","model":"w","piece":3,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"22f7ac","model":"w","piece":2,"plant":"lemon","stamp":"2017-03-30 02:08:15."},{"id":"10deca","model":"b","piece":1,"plant":"lemon","stamp":"2017-03-30 02:08:15."}]';     
        
        $response = file_get_contents('https://umbrella.jlparry.com/work/mybuilds/?key=25f80d');
        $someJSON = $response;
        $someArray = json_decode($someJSON, true);
        
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
    }
    
}
