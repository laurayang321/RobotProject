<?php

/**
 * Created by PhpStorm.
 * User: PeiLei
 * Date: 08/02/2017
 * Time: 3:08 PM
 */
class Robots extends CI_Model
{

    var $data = array(
        array('id' => '1', 'topPardId' => '1', 'torsoPartId' => '1', 'bottomPartId' => '1',
            'typeId' => '1', 'location' => 'a.jpg'),
        array('id' => '2', 'topPardId' => '2', 'torsoPartId' => '2', 'bottomPartId' => '2',
            'typeId' => '1', 'location' => 'b.jpg'),
        array('id' => '3', 'topPardId' => '3', 'torsoPartId' => '3', 'bottomPartId' => '3',
            'typeId' => '1' , 'location' => 'c.jpg'),
        array('id' => '4', 'topPardId' => '4', 'torsoPartId' => '4', 'bottomPartId' => '4',
            'typeId' => '2', 'location' => 'm.jpg'),
        array('id' => '5', 'topPardId' => '5', 'torsoPartId' => '5', 'bottomPartId' => '5',
            'typeId' => '2', 'location' => 'r.jpg'),
        array('id' => '6', 'topPardId' => '6', 'torsoPartId' => '6', 'bottomPartId' => '6',
            'typeId' => '3', 'location' => 'w.jpg')
    );

    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    // retrieve all of the robots
    public function all()
    {
        return $this->data;
    }



}