<?php

/**
 * Created by PhpStorm.
 * User: PeiLei
 * Date: 30/03/2017
 * Time: 6:24 PM
 */
class Manage extends Application
{
    function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $var = $this->token->head();
        $token = $var[0]->token_session;
    }

}