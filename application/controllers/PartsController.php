<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PartsController extends Application
{

	function __construct()
	{
		parent::__construct();
	}
        
	public function index()
	{
            $this->load->model('parts');
            // this is the view we want shown
            $this->data['pagebody'] = 'homepage';

            // build the list of authors, to pass on to our view
            $source = $this->parts->all();  // this is a 2D array
            $parts = array ();
            foreach ($source as $record)
            {
                    $parts[] = array ('id' => $record['id'], 'plant' => $record['plant'], 'date' => $record['date'], 'code' => $record['code'], 'pic' => $record['pic'], 'href' => $record['where']);
            }
            $this->data['parts'] = $parts;
            
            //echo "hello world!" + base_url();

            $this->render();
	}
        
        public function gimme($id) {
            $this->data['pagebody'] = 'justone';
            $record = $this->parts->get($id);
            $this->data = array_merge($this->data, $record);
            $this->render();
        }
              

}
