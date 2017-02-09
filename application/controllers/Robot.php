<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: PeiLei
 * Date: 08/02/2017
 * Time: 3:15 PM
 */
class Robot extends Application
{
    function __construct()
    {
        parent::__construct();
    }

    function Index() {
        // show the robots
        $this->data['pagebody'] = 'Robot/homepage';
        $source = $this->robots->all();

        $this->data['robots'] = $source;
        $this->render();

    }

}