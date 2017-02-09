<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PurchaseParts
 *
 * @author Jing
 */

class PurchasePartsRecords extends CI_Model {
// The data comes from http://www.quotery.com/top-100-funny-quotes-of-all-time/?PageSpeed=noscript

    public $data;

    // Constructor
    public function __construct()
    {
        parent::__construct();

        $this->data = array(
            array('purchaseID' => '1', 'date' => '2016-09-10'),
            array('purchaseID' => '2', 'date' => '2016-10-01'),
            array('purchaseID' => '3', 'date' => '2016-05-01'),
            array('purchaseID' => '4', 'date' => '2016-02-14'),
            array('purchaseID' => '5', 'date' => '2016-04-01'),
            array('purchaseID' => '6', 'date' => '2016-11-11')
        );

    }

    // retrieve a single quote
    public function get($which)
    {
        // iterate over the data until we find the one we want
        foreach ($this->data as $record)
            if ($record['purchaseID'] == $which)
                return $record;
        return null;
    }

    // retrieve all of the quotes
    public function all()
    {
        return $this->data;
    }

}