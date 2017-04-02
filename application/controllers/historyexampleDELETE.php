<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
defined('BASEPATH') OR exit('No direct script access allowed');
class ViewHistory extends Application
{
    private $items_per_page = 20;
    private $sort = "timestamp";
    private $filterModel = 'all';
    private $filterLine = 'all';
    /**
     * Index Page for this controller.
     *
     */
    public function index()
    {
        $this->page(1);
    }
    private function pagenav($num) {
        $lastpage = ceil($this->history->size() / $this->items_per_page);
        $parms = array(
            'first' => 1,
            'previous' => (max($num-1,1)),
            'next' => min($num+1,$lastpage),
            'last' => $lastpage
        );
        return $this->parser->parse('itemnav',$parms,true);
    }
    private function show_page($source)
    {
        $this->data['pagetitle'] = 'History'; //title
        $this->data['pagebody'] = 'history'; //view file
        $history = array();
        foreach ($source as $record)
        {
            $history[] = array (
                'timestamp' => $record->timestamp,
                'transactionType' => $record->transactionType,
                'item' => $record->item,
                'cost' => $record->cost,
                'toPlant' => $record->toPlant,
                'fromPlant' => $record->fromPlant,
                'line' => $record->line,
                'model' => $record->model,
                'piece' => $record->piece,
            );
        }
        $this->data['history'] = $history;
        $this->render();
    }
    function page($num = 1) {
        //---------------------SORT---------------------
        $sort = $this->input->post('order');
        if($this->session->userdata('sort') == null) {
            $this->session->set_userdata('sort', $this->sort);
        } else if($sort != null) {
            $this->session->set_userdata('sort', $sort);
        }
        $this->sort = $this->session->userdata('sort');
        //------------------END SORT---------------------
        //---------------------FILTER---------------------
        $filterModel = $this->input->post('filterModel');
        if($this->session->userdata('filterModel') == null) {
            $this->session->set_userdata('filterModel', $this->filterModel);
        } else if($filterModel != null) {
            $this->session->set_userdata('filterModel', $filterModel);
        }
        $this->filterModel = $this->session->userdata('filterModel');
        $filterLine = $this->input->post('filterLine');
        if($this->session->userdata('filterLine') == null) {
            $this->session->set_userdata('filterLine', $this->filterLine);
        } else if($filterLine != null) {
            $this->session->set_userdata('filterLine', $filterLine);
        }
        $this->filterLine = $this->session->userdata('filterLine');
        //------------------END FILTER---------------------
        $records = $this->history->all($this->sort, $this->filterModel, $this->filterLine);
        $historyItems = array();
        $index = 0;
        $count = 0;
        $start = ($num - 1) * $this->items_per_page;
        foreach($records as $entry) {
            if ($index++ >= $start) {
                $historyItems[] = $entry;
                $count++;
            }
            if ($count >= $this->items_per_page) break;
        }
        $this->data['pagination'] = $this->pagenav($num);
        //setting selected order using javascript
        $this->data['sort_script'] = '
                                    <script>
                                        window.onload = function () {
                                            var textToFind = "' . $this->sort . '";
                                    
                                            var dd = document.getElementById("order");
                                            dd.selectedIndex = 0;
                                            for (var i = 0; i < dd.options.length; i++) {
                                                if (dd.options[i].value === textToFind) {
                                                    dd.selectedIndex = i;
                                                    break;
                                                }
                                            }
                                        ';
        $this->data['filterModel_script'] = '
                                            var textToFind = "' . $this->filterModel . '";
                                    
                                            var dd = document.getElementById("filterModel");
                                            dd.selectedIndex = 0;
                                            for (var i = 0; i < dd.options.length; i++) {
                                                if (dd.options[i].value === textToFind) {
                                                    dd.selectedIndex = i;
                                                    break;
                                                }
                                            }';
        $this->data['filterLine_script'] = '
                                            var textToFind = "' . $this->filterLine . '";
                                    
                                            var dd = document.getElementById("filterLine");
                                            dd.selectedIndex = 0;
                                            for (var i = 0; i < dd.options.length; i++) {
                                                if (dd.options[i].value === textToFind) {
                                                    dd.selectedIndex = i;
                                                    break;
                                                }
                                            }
                                        };
                                    </script>';
        $this->show_page($historyItems);
    }
}