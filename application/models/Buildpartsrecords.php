<?php

class Buildpartsrecords extends MY_Model
{
    public $data;

    // Constructor
    public function __construct()
    {
        parent::__construct('buildpartsrecords','id');
    }
}