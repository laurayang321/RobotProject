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
        $this->load->model('parts');
        // show the parts in grid view
        $this->data['pagebody'] = 'Part/homepage';
        $source = $this->parts->all();

        $this->data['parts'] = $source;
        $this->render();
    }

    // Presents the detailed information of each robot part
    public function gimme($id) {
        $this->data['pagebody'] = 'Part/justonepart';
        $record = $this->parts->get($id);

        $this->data = array_merge($this->data, $record);
        $this->render();
    }

}
