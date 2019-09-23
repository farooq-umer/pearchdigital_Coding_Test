<?php

//use Monolog\Logger as Logger;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;

class JiraToSwf extends WebServices
{

    public function add_comments_to_swf($issueIdOrKey, $taskId)
    {
        try{

            $issueService = new IssueService();
            $comment_detail = $issueService->getComments($issueIdOrKey); //Getting All comments of an Issue from Jira.

            if($comment_detail->total == 0) // Comment Found
            {
                if(JIRA_DEV) {
                    E::log('INFO: No comments are found on Issue.', array(
                        'issue_key' => $issueIdOrKey
                    ));
                }

            }else{

                $swf_comments = $this->getTaskNotes($taskId);
                //var_dump($swf_comments);
                $no_comments = count($swf_comments);
                $comment_exists = null;

                foreach ($comment_detail->comments as $c)
                {
                    if($c->author->key == JIRA_USER_BOT_KEY || $c->updateAuthor->key == JIRA_USER_BOT_KEY )
                    {
                        if(JIRA_DEV) {
                            E::log('INFO: Could not add a comment added/updated by Bot.', array(
                                'swf_user_bot_id' => $c->author->key. ' - '.$c->updateAuthor->key,
                                'taskId' => $taskId,
                                'issue_key' => $issueIdOrKey
                            ));
                        }

                        continue;
                    }

                    $key = strstr($c->body, ' ', true);
                    $note_id =  (int) $key;

                    if($note_id == 0){ // Detect - Jira created Comment

                        $jira_comment_with_email = $c->body. ' ('.$c->author->emailAddress.')';
                        
                        if(isset($swf_comments) && $no_comments) {
                            $comment_exists = $this->check_comment_exists_on_swf($swf_comments, $jira_comment_with_email);
                        }
                        if($comment_exists == false) {

                            //1. Getting User Details From Swf
                            if(JIRA_DEV) {
                                E::log('INFO: Getting user details from swf by email address.',
                                    array(
                                    'swf_email_addr' => $c->author->emailAddress
                                    ));
                            }

                            //2. Access Level
                            $accessUserGroupIds = '2';
                            if(isset($c->visibility->value) && strlen($c->visibility->value) > 0)
                            {
                                if($c->visibility->value == 'None')
                                    $accessUserGroupIds = null;
                            }

                            //3. Adding Comment to SWF.
                            $resp = $this->addTaskNote(SWF_USER_BOT_ID, $taskId, $jira_comment_with_email, $accessUserGroupIds);

                            //4. Update on Jira that comment is Published.
                            if ($resp['status'] == 'S')
                            {
                                if(JIRA_DEV) {
                                    E::log('INFO: comment added to swf.', array(
                                        'swf_user_bot_id' => SWF_USER_BOT_ID,
                                        'taskId' => $taskId,
                                        'issue_key' => $issueIdOrKey,
                                        'comment' => $c->body,
                                        'access_level' => $accessUserGroupIds
                                    ));
                                }

                                $task_note_id = $resp['response']['response']['TaskNoteId'];
                                if(strlen($task_note_id) > 0){

                                    $ar = [
                                        'body' => $task_note_id.' '.$c->body
                                    ];

                                    $ret = $issueService->updateComment($issueIdOrKey, $c->id, $ar);
                                    if($ret->id){
                                        if(JIRA_DEV) {
                                            E::log('INFO: Comment updated on Jira.', array(
                                                'function_name' => 'add_comments',
                                                'issue_key' => $issueIdOrKey,
                                                'taskId' => $taskId,
                                                'comment_id' => $ret->id
                                            ));
                                        }
                                    }
                                }

                            }else{
                                E::log('INFO: Comment can not be added to swf addTaskNote - webservice Error.', array(
                                    'function_name' => 'add_comments',
                                    'issue_key' => $issueIdOrKey,
                                    'taskId' => $taskId,
                                    'swf_response' => $resp
                                ));
                            }

                        }else {
                            if(JIRA_DEV) {
                                E::log('INFO: comment_exists on Simply Workflow.', array(
                                    'function_name' => 'add_comments',
                                    'issue_key' => $issueIdOrKey,
                                    'taskId' => $taskId
                                ));
                            }
                        }
                    }
                }
            }
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(), array(
                'function_name' => 'add_comments',
                'issue_key' => $key,
                'taskId' => $taskId
            ));
        }
    }

    public function check_comment_exists_on_swf($swf_comments = [], $jira_comment = '')
    {
        try
        {
            if(JIRA_DEV) {
                E::log('INFO: In check_comment_exists_on_swf function');
            }

            if(empty($swf_comments)) {
                throw new JiraException('swf_comments was not found');
            }
            
            foreach ($swf_comments as $comment)
            {
                if($comment['note'] == $jira_comment)
                return true;
            }
            
            return false;

        } catch (JiraException $e) {
            E::log($e->getMessage(),
                array(
                    'function_name' => 'check_comment_exists_on_swf'
                ));
        }
    }

    public function get_swf_status_id($status_name)
    {
        $taskStatuses = $this->getTaskStatuses();

        if ($taskStatuses['status'] == 'S') {

            $status_name = strtolower($status_name);
        
            foreach ($taskStatuses['response']['response'] as $task_status)
            {
                $task_status_name = strtolower($task_status['task_status_name']);
                if($status_name === $task_status_name)
                    return $task_status['task_status_id'];
            }
        }

        return 0;
    }

}