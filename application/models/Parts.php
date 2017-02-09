<?php

/**
 * Created by PhpStorm.
 * User: rache
 * Date: 2/8/2017
 * Time: 1:01 PM
 */
class Parts extends CI_Model {
// The data comes from http://www.quotery.com/top-100-funny-quotes-of-all-time/?PageSpeed=noscript

    public $data;

    // Constructor
    public function __construct()
    {
        parent::__construct();

        $this->data = array(
        array('partID' => '1', 'partCode' => 'A1', 'pic' => 'a1.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '1', 'dateTime'=> '2016-06-18', 'cost'=>'36'),
        array('partID' => '2', 'partCode' => 'A2', 'pic' => 'a2.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '2', 'dateTime'=> '2016-06-19','cost'=>'37'),
        array('partID' => '3', 'partCode' => 'A3', 'pic' => 'a3.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '3','dateTime'=> '2016-06-20','cost'=>'38'),

        array('partID' => '4', 'partCode' => 'B1', 'pic' => 'b1.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '1','dateTime'=> '2016-06-21','cost'=>'39'),
        array('partID' => '5', 'partCode' => 'B2', 'pic' => 'b2.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '2','dateTime'=> '2016-06-22','cost'=>'40'),
        array('partID' => '6', 'partCode' => 'B3', 'pic' => 'b3.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '3','dateTime'=> '2016-06-23','cost'=>'41'),

        array('partID' => '7', 'partCode' => 'C1', 'pic' => 'c1.jpeg', 'CACode' => $this->_generateHashNum(),
                'plantID' => '1','dateTime'=> '2016-06-24','cost'=>'39'),
        array('partID' => '8', 'partCode' => 'C2', 'pic' => 'c2.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '2', 'dateTime'=> '2016-06-25', 'cost'=>'40'),
        array('partID' => '9', 'partCode' => 'C3', 'pic' => 'c3.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '3','dateTime'=> '2016-06-26','cost'=>'41'),

        array('partID' => '10', 'partCode' => 'M1', 'pic' => 'm1.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '1','dateTime'=> '2016-06-24','cost'=>'39'),
        array('partID' => '11', 'partCode' => 'M2', 'pic' => 'm2.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '2', 'dateTime'=> '2016-06-25', 'cost'=>'40'),
        array('partID' => '12', 'partCode' => 'M3', 'pic' => 'm3.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '3','dateTime'=> '2016-06-26','cost'=>'41'),

        array('partID' => '13', 'partCode' => 'R1', 'pic' => 'r1.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '1','dateTime'=> '2016-06-24','cost'=>'39'),
        array('partID' => '14', 'partCode' => 'R2', 'pic' => 'r2.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '2', 'dateTime'=> '2016-06-25', 'cost'=>'40'),
        array('partID' => '15', 'partCode' => 'R3', 'pic' => 'r3.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '3','dateTime'=> '2016-06-26','cost'=>'41'),

        array('partID' => '16', 'partCode' => 'W1', 'pic' => 'w1.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '1','dateTime'=> '2016-06-24','cost'=>'39'),
        array('partID' => '17', 'partCode' => 'W2', 'pic' => 'w2.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '2', 'dateTime'=> '2016-06-25', 'cost'=>'40'),
        array('partID' => '18', 'partCode' => 'W3', 'pic' => 'w3.jpeg', 'CACode' => $this->_generateHashNum(),
            'plantID' => '3','dateTime'=> '2016-06-26','cost'=>'41')

    );

    }

    //generate a certificate of Authenticity(CA) code for each part
    private function _generateHashNum()
    {
        return md5(uniqid());
    }

    // retrieve a single quote
    public function get($which)
    {
        // iterate over the data until we find the one we want
        foreach ($this->data as $record)
            if ($record['partID'] == $which)
                return $record;
        return null;
    }

    // retrieve all of the quotes
    public function all()
    {
        return $this->data;
    }

}