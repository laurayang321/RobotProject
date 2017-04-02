<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: To Olympus
 * Date: 2017-02-11
 * Time: 6:39 PM
 */

class Record extends Application
{
    private $items_per_section = 5;

    private $sort = "timestamp";
    private $filterModel = 'all';
    private $filterLine = 'all';

    function __construct()
    {
        parent::__construct();
    }

    // Presents all the robot parts we have in a grid view
    public function index()
    {
        $this->page(1);
    }

    private function renderHistory(){
        $this->data['pagebody'] = 'History/homepage';
        $this->render();
    }


    /////////////////////////////////////////////////////


    // Extract & handle a page of items, defaulting to the beginning
    function page($num = 1)
    {
        $records = $this->transactions->all(); // get all the tasks
        $recordArray = array(); // start with an empty extract

        // use a foreach loop, because the record indices may not be sequential
        $index = 0; // where are we in the tasks list
        $count = 0; // how many items have we added to the extract
        $start = ($num - 1) * $this->items_per_section;
        foreach($records as $record) {
            if ($index++ >= $start) {
                $recordArray[] = $record;
                $count++;
            }
            if ($count >= $this->items_per_section) break;
        }
        $this->data['pagination'] = $this->pagenav($num);
        $this->show_page($recordArray);
    }

// Build the pagination navbar
    private function pagenav($num) {
        $lastpage = ceil($this->transactions->size() / $this->items_per_section);
        $parms = array(
            'first' => 1,
            'previous' => (max($num-1,1)),
            'next' => min($num+1,$lastpage),
            'last' => $lastpage
        );
        return $this->parser->parse('History/_itemNav',$parms,true);
    }

    // Show a single page of todo items
    private function show_page($recordArray)
    {
        $result = ''; // start with an empty array
        foreach ($recordArray as $record)
        {
            if (!empty($record->status))
                $record->status = $this->statuses->get($record->status)->name;

            $finishedRecord['transactionID'] = $record->transactionID;
            $finishedRecord['transacType'] = $record->transacType;
            $finishedRecord['parts'] = $this->getParts($record->transacType, $record->transactionID);
            $finishedRecord['transacMoney'] = $record->transacMoney;
            $finishedRecord['transacDateTime'] = $record->transacDateTime;

            $result .= $this->parser->parse('History/_history', (array) $finishedRecord, true);
        }
        $this->data['history'] = $result;
        $this->renderHistory();
    }

    ///////////////////////////////////////////////////////

    private function getParts($transacType, $transacID){
        $parts = "";
        if($transacType=='assembly'){
            $PartsRecords = $this->assemblyrecords->some('transactionID',$transacID);
//            $parts .= $this->parts->get($PartsRecords[0]->partTopCACode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partBodyCACode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partpartBtmCACode)->partName. ", ";
        }
        else if($transacType=='purchase'){
            $PartsRecords = $this->purchasepartsrecords->some('transactionID',$transacID);
//            $parts .= $this->parts->get($PartsRecords[0]->partonecacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->parttwocacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partthreecacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partfourcacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partfivecacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partsixcacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partsevencacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->parteightcacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partninecacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->parttencacode)->partName. ", ";
        }
        else if($transacType=='shipment'){
            $PartsRecords = $this->shipmentrecords->some('transactionID',$transacID);
//            $parts .= $this->parts->get($PartsRecords[0]->partTopCACode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partBodyCACode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partpartBtmCACode)->partName. ", ";
        }
        else if($transacType=='return'){
            $PartsRecords = $this->returnpartrecords->some('transactionID',$transacID);
//            $parts .= $this->parts->get($PartsRecords[0]->partcacode)->partName. ", ";
        }
        else if($transacType=='build'){
            $PartsRecords = $this->retrievepartsrecords->some('transactionID',$transacID);
//            $parts .= $this->parts->get($PartsRecords[0]->partonecacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->parttwocacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partthreecacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partfourcacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partfivecacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partsixcacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partsevencacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->parteightcacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->partninecacode)->partName. ", ";
//            $parts .= $this->parts->get($PartsRecords[0]->parttencacode)->partName. ", ";
        }
        return $parts;
    }
}