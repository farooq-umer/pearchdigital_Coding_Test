<?php

use JiraRestApi\JiraException;
use JiraRestApi\Issue\IssueService;

function run_jql($jql, $startAt = '', $maxResult = '')
{
    /* 
    $startAt        //the index of the first issue to return (0-based)    
    $maxResults     // the maximum number of issues to return (defaults to 50). 
    $totalCount     // the number of issues to return
    */
       
    try{
        if($jql == '')
            throw new JiraException('jql id is required. For getting response from Jira');

        $issueService = new IssueService();
       
        // fetching data from Jira
        $ret = $issueService->search($jql, $startAt, $maxResult);

        return $ret;
    }
    catch(JiraException $e)
    {
        echo $e->getMessage();
        //$this->assertTrue(false, 'Search Failed : '.$e->getMessage());
    }
}

function get_dates($filename, $format = "Y-m-d H:i:s", $strotime = "-1 minutes")
{
    $log_file = dirname(__DIR__) . '/log/cron_time/'.$filename;
    $dates = array();
    $last_timestamp = date($format, strtotime($strotime));

    if(file_exists($log_file))
    {
        $logs = file($log_file); // — Reads entire file into an array
        //print_r($logs); die;
        if (count($logs) > 0)
        {
            $last_timestamp = date($format, strtotime( $strotime, strtotime($logs[count($logs) - 1]))); // Recent log
        }
    }
    else {
        FUNC::makeDir(dirname(__DIR__) . '/log/cron_time');

        $file = fopen($log_file, 'w');
        if (! $file) {
            //throw new JiraException("Could not open the file!");
        }
        fclose($file);
    }

        $dates['dateFrom'] = trim($last_timestamp);
        $dates['dateTo'] =  date($format);
                
        return $dates;
}

function save_date_on_file($filename, $format = "Y-m-d H:i:s")
{
    $log_file = dirname(__DIR__) . '/log/cron_time/'.$filename;
    $job_time = date($format);
    file_put_contents($log_file, $job_time . PHP_EOL, FILE_APPEND | LOCK_EX);
}

/* ***************** For Jira Tab ***************** */

function  get_issue_details($task_id)
{
    try{
        if($task_id == '')
            throw new JiraException('Task id is required. For getting issue number.');

        $jql = 'project = '.JIRA_PROJECT_CODE.' AND  cf[10043] ~ '.trim($task_id);
        //$jql = 'project = DEMO AND  cf[10043] ~ '.$task_id;

        $issueService = new IssueService();
        $ret = $issueService->search($jql);

        return $ret;
    }
    catch(JiraException $e)
    {
        echo $e->getMessage();
    }
}

function get_swf_status_code_by_name_from_file($status_name)
{
    $json = file_get_contents( dirname(__DIR__).'/jira_data.json');

    $statuses_arr = json_decode($json, true); // decode the JSON into an associative array

    $status_name_lower = strtolower($status_name);

    foreach ($statuses_arr['swf_task_statuses'] as $status_arr)
    {
        $swf_status_name_lower = strtolower( $status_arr['task_status_name'] );
        
        if( $status_name_lower === $swf_status_name_lower ) {
            return $status_arr['task_status_code'];
        }
    }

    return "";
}

function get_swf_status_id_by_name_from_file($status_name)
{
    $json = file_get_contents( dirname(__DIR__).'/jira_data.json');

    $statuses_arr = json_decode($json, true); // decode the JSON into an associative array

    $status_name_lower = strtolower($status_name);

    foreach ($statuses_arr['swf_task_statuses'] as $status_arr)
    {
        $swf_status_name_lower = strtolower( $status_arr['task_status_name'] );
        
        if( $status_name_lower === $swf_status_name_lower ) {
            return $status_arr['task_status_id'];
        }
    }

    return 0;
}

function get_swf_status_name_by_code_from_file($status_code)
{
    $json = file_get_contents( dirname(__DIR__).'/jira_data.json');

    $statuses_arr = json_decode($json, true); // decode the JSON into an associative array

    foreach ($statuses_arr['swf_task_statuses'] as $status_arr)
    {
        if( $status_code === $status_arr['task_status_code'] )
            return $status_arr['task_status_name'];
    }

    return "";
}

function get_swf_status_name_by_id_from_file($status_id)
{
    $json = file_get_contents( dirname(__DIR__).'/jira_data.json');

    $statuses_arr = json_decode($json, true); // decode the JSON into an associative array

    foreach ($statuses_arr['swf_task_statuses'] as $status_arr)
    {
        if( $status_id == $status_arr['task_status_id'] )
            return $status_arr['task_status_name'];
    }

    return "";
}
