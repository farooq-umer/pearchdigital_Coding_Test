<?php

require_once (dirname(__DIR__).'/config.php');

use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\JiraException;

    $start = microtime(true);
    $dates = get_dates(JIRATOSWF_CT_FILENAME, "Y-m-d H:i");
    $swf = new JiraToSwf();

    //print_r($dates);
    // Adding to 1 minute in order to capture the Issues from jira properly
    $strtotime = strtotime('+1 minutes', strtotime($dates['dateTo']));
    $updated_last_timestamp = date("Y-m-d H:i", $strtotime);
    $dates['dateTo'] = trim($updated_last_timestamp);
    //print_r($dates);
    
    try
    {
        if(JIRA_DEV) {
            E::log(' -------------------- Jira to SWF : Start -----------------------------',
               [
                'jira_project_code' => JIRA_PROJECT_CODE,
                'dateFrom' => $dates['dateFrom'],
                'dateTo' => $dates['dateTo'],
                ]
            );
        }

        if(strlen($dates['dateFrom']) > 1 && strlen($dates['dateTo']) > 1)
        {
            $jql = "project = ".JIRA_PROJECT_CODE." AND updated >= '".$dates['dateFrom']."' AND updated <= '".$dates['dateTo']."' ORDER BY created DESC " ;
            //$jql = "project = ".JIRA_PROJECT_CODE." AND updated >= -4m order by created DESC";

            $issueService = new IssueService();
            $ret = $issueService->search($jql);
            //print_r($ret); die;
            
            if($ret->total > 0 || JIRA_DEV) {
                E::log(' ===== JIRA to SWF : Start ===== ', $ret->total .' Issues are found. =====');
            }            
            
            foreach($ret->issues as $issues)
            {
                //1.
                $ticket_no = $issues->fields->customfield_10043;

                if(strlen($ticket_no) == 0)
                {
                    if(JIRA_DEV) {
                        //throw new JiraException('Ticket is required.');
                        E::log('INFO: Ticket no not found in Jira. Issue no: ' . $issues->key);
                    }
                }
                else {
                    //2.
                    $issue_status = $issues->fields->status;

                    //3.
                    $swf_task_history = $swf->getTaskStatusHistory($ticket_no);
                    $swf_status_date = date('Y-m-d H:i:s', strtotime($swf_task_history[0]['date_changed']));

                    //1. Adding Comments
                    $swf->add_comments_to_swf($issues->key, $ticket_no);

                    //2.Status Update
                    $changelog = $issueService->getHistory($issues->key, array('expand' => 'changelog'));

                    if($changelog->total)
                    {
                        foreach ($changelog->histories as $history){

                            //3.
                            $date = str_replace('T', ' ', $history->created); //$updated->format('Y-m-d H:i:s');
                            $updated_date = date('Y-m-d H:i:s', strtotime($date)); //$updated = $issues->fields->updated;

                            if($history->author->key == JIRA_USER_BOT_KEY)
                            {
                                if(JIRA_DEV) {
                                    E::log('INFO: Changes are made by Bot', array(
                                        'author_name' => $history->author->name,
                                        'jira_status' => $issue_status->name,
                                    ));
                                }

                                continue;
                            }
                            else {

                                if($history->items[0]->field == "status")
                                {
                                    //$j_status_id = $swf->get_swf_status_id($history->items[0]->toString);
                                    $j_status_id = get_swf_status_id_by_name_from_file($history->items[0]->toString);

                                    if($j_status_id)
                                    {
                                        if($updated_date > $swf_status_date)
                                        {
                                            if(JIRA_DEV) {
                                                E::log('INFO: Status needs to be updated.', array(
                                                    'jira_status' => $history->items[0]->toString,
                                                    'jira_updation_date' => $updated_date,
                                                    'author' => $history->author->key,
                                                    'ticket_no' => $ticket_no,
                                                    'swf_updation_date' => $swf_status_date,
                                                    'issue_no' => $issues->key
                                                ));
                                            }

                                            $response = $swf->updateTaskStatus($ticket_no, $j_status_id, SWF_USER_BOT_ID); //Hardcoded User Id

                                            if($response['status'] == 'S'){
                                                if(JIRA_DEV) {
                                                    E::log('INFO: Status updated successfully.', array(
                                                        'ticket_no' => $ticket_no,
                                                        'issue_no' => $issues->key
                                                    ));
                                                }
                                            }
                                        }

                                    }else{

                                        E::log('INFO: Could not find status.', array(
                                            'jira_status' => $issue_status->name,
                                            'ticket_no' => $ticket_no,
                                            'issue_no' => $issues->key
                                        ));
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

        save_date_on_file(JIRATOSWF_CT_FILENAME);
    }
    catch (JiraException $e)
    {
        E::log('INFO: Jira To Simply workflow : ', $e->getMessage());
    }

    if($ret->total > 0 || JIRA_DEV) {
        $time_elapsed_secs = microtime(true) - $start;
        E::log(' ===== Script Execution time:  '.$time_elapsed_secs.' JIRA to SWF : End =====');
    }

    /*$time_elapsed_secs = microtime(true) - $start;
    echo "<div align='center'>";
    echo "<p>This script took ".$time_elapsed_secs." to execute. </p>";
    echo "</div>";*/
