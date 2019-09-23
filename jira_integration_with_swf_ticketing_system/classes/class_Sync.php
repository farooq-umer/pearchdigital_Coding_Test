<?php
/**
 * 
 * This class is used to Sync from SWF to Jira
 * 
 */

use JiraRestApi\JiraException;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\User\UserService;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\Comment;
use JiraRestApi\Issue\Transition;
use JiraRestApi\Issue\TimeTracking;

class Sync extends SwfToJira
{
	const STATUS_SYNC = 'Status_Sync';
	const COMMENTS_SYNC = 'Comments_Sync';
	const BULK_ISSUE_CREATION = 'Bulk_Issue_Creation_In_Jira';
	const ALL_FIELDS_SYNC = 'All_Fields_Sync';
	const ATTACHMENTS_SYNC = 'Attachments_Sync';

	public function syncStatus($jql, $startAt, $maxResult)
	{
		$swf = new SwfToJira();
		//$jira = new JiraToSwf();

		$pre = "<pre>";
		$br = "<br>";

		$jira_response = run_jql($jql, $startAt, $maxResult);
		
		//echo $pre; //print_r($jira_response);

		$transition = new Transition();
		$issueService = new IssueService();

		foreach($jira_response->issues as $j_res) {

			$jira_issue_status = $j_res->fields->status->name; //echo $br;
			$jira_issue_no = $j_res->key; //echo $br;
			$swf_task_no_in_jira = $j_res->fields->customfield_10043; //echo $br;

			$swf_task_status_history = $swf->getTaskStatusHistory($swf_task_no_in_jira);  print_r($swf_task_status_history);
			
			//$swf_status_date_changed = date('Y-m-d H:i:s', strtotime($swf_task_status_history[0]['date_changed']));
			$swf_status_name = $swf_task_status_history[0]['task_status_name'];
			
			//echo "jira: $jira_issue_status $br"; 
			//echo "swf: $swf_status_name $br";

			$jira_issue_status_lower = strtolower($jira_issue_status);
			$swf_status_name_lower = strtolower($swf_status_name);

			if($swf_status_name_lower !== $jira_issue_status_lower) {

				/*echo "$br ============================ $br";
				echo "$br j_issue_no: $jira_issue_no $br";
				echo "$br swf_task_no: $swf_task_no_in_jira $br";
				echo "$br j_status: $jira_issue_status $br"; 
				echo "$br swf_status: $swf_status_name $br";*/

		        $transition->setTransitionName($swf_status_name);
		        // following commented line is throwing the error.
		        //$transition->setCommentBody( "Performing the status update ($swf_status_name) via STATUS SYNC" );
		        $ret = $issueService->transition($jira_issue_no, $transition);

		        if($ret) {
		        	//echo "$br Status Updated $br";
		        	//print_r($ret);
		        }
		        else {
		        	//echo "$br Staus Not Updated $br";
		        	//print_r($ret);
		        }
			}

		}
	}

	public function syncComments($task_id)
	{	
		// syncComments function needs to be COMPLETD and tested. IT IS NOT COMPLETE YET,
		
		//$swf = new SwfToJira();
		$is_comment_exists = true;

		list($jira_issue_no,$jira_response) = $this->get_issue_no($task_id, $event_code = self::COMMENTS_SYNC);
		
		if($jira_issue_no) {
			//$this->add_comments_to_jira($task_id, $jira_issue_no, $check_duplications = true);
			
			try
			{
	            $comments = $this->getTaskNotes($task_id);
	            //var_dump($comments);
	            $no_comments = count($comments);

	            if(isset($comments) && $no_comments)
	            {
	                $r_comments = array_reverse($comments); // Reverse the order of comments

	                E::log('INFO: ', 'Total '.$no_comments.' comments are found from swf.');

	                foreach($r_comments as $c)
	                { 
	                	//print_r($c); die;
	                    $swf_note = trim($c['note']);
	                    if (strlen($swf_note) == 0) {
	                        continue;
	                    }
	                    if ($c['created_by_user_id'] == SWF_USER_BOT_ID) {
	                        continue;
	                    }

	                    if($check_is_comment_exists) {

	                    	if ($jira_comment = $this->check_comment_exists_on_jira($c['task_note_id'], $issue_key)) {
					            
					            $jira_c_id = $jira_comment->id;
					            //$jira_c_body = $jira_comment->body;
					            
					            $note_id = $c['task_note_id'];
					            
					            $date = date('d/m/Y h:i', strtotime($c['date_created']));
					            $body =  $note_id.' ( By '.$c['created_by_user_fullname']. ' on '.$date.' ) '. trim($c['note']);

					            $issueService = new IssueService();

					            if ($c['note'] == $note) {
					                $ar = [
					                    'body' => $body
					                ];
					                $ret = $issueService->updateComment($issue_key, $jira_c_id, $ar);

					                if($ret->id){
					                    if(JIRA_DEV) {
					                        E::log('INFO: Comment updated on Jira.', array(
					                            'function_name' => __FUNCTION__,
					                            'issue_key' => $issue_key,
					                            'taskId' => $c['task_id'],
					                            'note_id'=> $note_id,
					                            'jira_comment_id' => $ret->id
					                        ));
					                    }
					                }
					            }
					        }
					        else {
					            $this->adding_comments_to_jira($c, $issue_key);
	                            if(JIRA_DEV) {
	                                E::log('Comment created on Jira with checking duplications.',
	                                    array(
	                                        'function_name' => __FUNCTION__,
	                                        'task_note_id' => $c['task_note_id'],
	                                        'issue_key' => $issue_key,
	                                        'check_duplications' => $check_duplications
	                                    ));
	                            }
					        }

	                    }
	                    else {

	                        $this->adding_comments_to_jira($c, $issue_key);
	                        if(JIRA_DEV) {
	                        E::log('Comment created on Jira.',
	                            array(
	                                'function_name' => __FUNCTION__,
	                                'task_note_id' => $c['task_note_id'],
	                                'issue_key' => $issue_key,
	                                'check_duplications' => $check_duplications
	                            ));
	                        }
	                    }
	                }
	            }
	            else {
	                if(JIRA_DEV) {
	                    E::log('No comments were found.',
	                        array(
	                            'function_name' => __FUNCTION__,
	                            'task_id' => $task_id,
	                            'issue_key' => $issue_key,
	                        ));
	                }
	            }
	        }
	        catch (JiraException $e)
	        {
	            E::log($e->getMessage(),
	                array(
	                    'function_name' => __FUNCTION__
	                ));
	        }
		}
	}

	public function createJiraIssuesInBulk(array $task_ids_array)
	{
		try
		{
			if ( empty($task_ids_array) ) {
				throw new JiraException('Task Ids Array is required');
			}

			foreach ($task_ids_array as $task_id) {

				$swf_task_details = $this->getTaskDetailsAll($task_id);

                $constraint = $this->check_constraint($swf_task_details);

                if ( strlen($constraint) ) {
                    E::log($constraint, " task_id: $task_id event_code: " . self::BULK_ISSUE_CREATION);
                    continue;
                }
                
                $issue_key = $this->task_created($swf_task_details, $task_event = self::BULK_ISSUE_CREATION, $comment_addition = true);

			}

		}
        catch (JiraException $e)
        {
            E::log($e->getMessage(),
                array(
                    'function_name' => __FUNCTION__
                ));
        }
		
	}

	public function syncAllFilds($taskId)
	{
		
	}

	public function syncAttachments($taskId)
	{
		
	}

}