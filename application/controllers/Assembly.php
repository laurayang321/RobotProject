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
        // this is the view we want shown
        $this->data['pagebody'] = 'Assembly';

        // build the list of authors, to pass on to our view
        $source = $this->parts->all();  // this is a 2D array
//        $authors = array();
//        foreach ($source as $record) {
//            $authors[] = array('who' => $record['who'], 'mug' => $record['mug'], 'href' => $record['where']);
//        }
//        $this->data['authors'] = $authors;

       // $this->render();

        $this->data['assembly'] = $source;
        $this->render();
    }

}