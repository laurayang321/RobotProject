<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: PeiLei
 * Date: 08/02/2017
 * Time: 3:15 PM
 */
// Robot controller - controls methods manipulating robots
class Robot extends Application
{
    function __construct()
    {
        parent::__construct();
    }

    // Presents all the robots we have in a grid view
    function Index() {
        // show the robots
        $this->data['pagebody'] = 'Robot/homepage';
        $source = $this->robots->all();

        $this->data['robots'] = $source;
        $this->render();

    }

}