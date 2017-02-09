<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Parts
 *
 * @author Jing
 */
class Parts extends CI_Model{
    //put your code here
    // The data comes from http://www.quotery.com/top-100-funny-quotes-of-all-time/?PageSpeed=noscript
    var $data = array(
            array('id' => '1', 'code' => 'a1', 'CA' => '', 'pic' => 'a1.jpeg', 'where' => '/show/1',
                    'plant' => 'companyA', 'date' => '2016-06-18'),
            array('id' => '2', 'code' => 'a2', 'CA' => '', 'pic' => 'a2.jpeg', 'where' => '/show/2',
                    'plant' => 'companyA', 'date' => '2016-06-19'),
            array('id' => '3', 'code' => 'a3', 'CA' => '', 'pic' => 'a3.jpeg', 'where' => '/show/3',
                    'plant' => 'companyA', 'date' => '2016-06-20'),
            array('id' => '4', 'code' => 'b1', 'CA' => '', 'pic' => 'b1.jpeg', 'where' => '/show/4',
                    'plant' => 'companyB', 'date' => '2016-06-21'),
            array('id' => '5', 'code' => 'b2', 'CA' => '', 'pic' => 'b2.jpeg', 'where' => '/show/5',
                    'plant' => 'companyB', 'date' => '2016-06-21'),
            array('id' => '6', 'code' => 'b3', 'CA' => '', 'pic' => 'b3.jpeg', 'where' => '/show/6',
                    'plant' => 'companyB', 'date' => '2016-06-21'),
            array('id' => '7', 'code' => 'c1', 'CA' => '', 'pic' => 'c1.jpeg', 'where' => '/show/7',
                    'plant' => 'companyB', 'date' => '2016-06-21'),
            array('id' => '8', 'code' => 'c2', 'CA' => '', 'pic' => 'c2.jpeg', 'where' => '/show/8',
                    'plant' => 'companyB', 'date' => '2016-06-21'),
            array('id' => '9', 'code' => 'c3', 'CA' => '', 'pic' => 'c3.jpeg', 'where' => '/show/9',
                    'plant' => 'companyB', 'date' => '2016-06-21')
    );

    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    // retrieve a single quote
    public function get($which)
    {        
        // iterate over the data until we find the one we want
        foreach ($this->data as $record)
            if ($record['id'] == $which)
                return $record;
        return null;            
    }

    // retrieve all of the parts
    public function all()
    {
        return $this->data;
    }
}
