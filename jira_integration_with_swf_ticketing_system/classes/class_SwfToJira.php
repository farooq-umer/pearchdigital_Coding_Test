<?php

//use Monolog\Handler\StreamHandler;
//use Monolog\Logger as Logger;
use JiraRestApi\JiraException;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\User\UserService;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\Comment;
use JiraRestApi\Issue\Transition;
use JiraRestApi\Issue\TimeTracking;
use JiraRestApi\Field\Field;
use JiraRestApi\Field\FieldService;

class SwfToJira extends WebServices
{

    public function get_priority($priority_id)
    {
        $swf_task_priorities = $this->getTaskPriorities();
        //print_r($swf_task_priorities);
        foreach ($swf_task_priorities as $task_priority) {
            if ($task_priority['task_priority_id'] == $priority_id) {
                return $task_priority['task_priority_name'];
            }
        }
        // will return P2 - Medium impact
        return $swf_task_priorities[0]['task_priority_name'];
        /*
        switch ($priority_id) {
            case 1 :
                return "Highest";
                break;

            case 2:
                return "Medium";
                break;

            case 3:
                return "Lowest";
                break;
        }
        */
    }

    public function get_task_types($id){

        $swf_task_types = $this->getTaskTypes();
        //print_r($swf_task_types);
        foreach ($swf_task_types as $task_type) {
            if ($task_type['task_type_id'] == $id) {
                return $task_type['task_type_name'];
            }
        }
        // will return task_type_name = Development
        return $swf_task_types[0]['task_type_name'];
        /*
        switch ($id)
        {
            case 1 :
                return "New Feature";
                break;

            case 2:
                return "Improvement";
                break;

            case 3:
                return "Improvement";
                //return "Support";
                break;

            case 4:
                return "Bug";
                break;
        }
        */
    }

    public function get_username($email){
        try{

            $paramArray = [
                'username' => $email, // get all users.
                'startAt' => 0,
                'maxResults' => 1000,
                'includeInactive' => true,
                //'property' => '*',
            ];

            $us = new UserService();
            $user = $us->getUsersList($paramArray);


            if(count($user) == 1)
            {
                return  $user[0]->name; //Return Jira User NAme

            }else {
                E::log('INFO: Could not find user on jira.',
                    array(
                        'function_name' => __FUNCTION__,
                        'email' => $email,
                    ));
                /*$this->logger->log( LOG_LEVEL, 'Could not find user on jira.', array(
                    'function_name' => __FUNCTION__,
                    'email' => $email,
                ));*/

                return  ''; //Return Jira User NAme
            }

        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(),
                array(
                    'function_name' => __FUNCTION__,
                    'email' => $email,
                ));
            /*$this->logger->log( Logger::WARNING, $e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'email' => $email,
            ));*/
        }
    }

    public function get_swf_status_name($status_id)
    {
        $res = $this->getStatusById($status_id);

        if($res['status'] == 'S') {
            return $res['response']['response']['task_status_name'];
        }

        return "";
    }

    public function get_jira_custom_fields()
    {
        try 
        {
            $fieldService = new FieldService();

            // return custom field only. 
            $ret = $fieldService->getAllFields(Field::CUSTOM); 
            
            print_r($ret);
            //var_dump($ret);
        }
        catch (JiraException $e)
        {
            $this->assertTrue(false, 'testSearch Failed : '.$e->getMessage());
        }

    }

    public function get_jira_issue_details_by_issue_no($issue_no = '')
    {        
        try 
        {
            if(!$issue_no) {
                throw new JiraException('Issue Number is required.');
            }

            $issueService = new IssueService();
            
            /*$queryParam = [
                'fields' => [  // default: '*all'
                    'summary',
                    'comment',
                ],
                'expand' => [
                    'renderedFields',
                    'names',
                    'schema',
                    'transitions',
                    'operations',
                    'editmeta',
                    'changelog',
                ]
            ];*/
                    
            $issue = $issueService->get($issue_no); // pass $queryParam as second parameter
            
            print_r($issue);
            //var_dump($issue->fields);
        }
        catch (JiraException $e) 
        {
             E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'issue_no' => $issue_no
            ));
        }

    }

    public function get_jira_issue_details_by_swf_task_id($task_id = '')
    {
        $ret = null;
        $jql = null;

        try
        {
            if(!$task_id) {
                throw new JiraException('Task id is required.');
            }

            $jql = 'project = '.JIRA_PROJECT_CODE.' AND cf[10043] ~ '.trim($task_id);
            //$jql = 'project = DEMO AND  cf[10043] ~ '.$task_id;

            $issueService = new IssueService();
            $ret = $issueService->search($jql);

            return array($jql, $ret);

        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'task_id' => $task_id
            ));
        }

        return array($jql, $ret);
    }

    public function get_issue_no($task_id = '', $event_code = '') // $task_id = ''
    {

        try{

            $jira_issue_no = '';
            $ret = '';

            if (!$task_id) {
                throw new JiraException('Task id is required. For getting issue number.');
            }

            list($jql, $ret) = $this->get_jira_issue_details_by_swf_task_id($task_id);
            
            if ( isset($ret->total) && $ret->total )
            {
                //-------------------------------------------------------------
                if(JIRA_DEV) {
                E::log('INFO: Jql search response.',
                    array(
                        'function_name' => __FUNCTION__,
                        'task_id' => $task_id,
                        'ret_total' => $ret->total,
                        'event_code' => $event_code
                    ));
                }
                //--------------------------------------------------------------
            
                //$jira_issue_no = $ret->issues[$ret->total -1]->key;
                //$fields = $ret->issues[$ret->total -1]->fields;
                $jira_issue_no = $ret->issues[count($ret->issues) -1 ]->key;

            } else {
                
                $errormsg = 'Task not found on jira '. "\r\n" . $jql;
                E::log($errormsg,
                    array(
                        'function_name' => __FUNCTION__,
                        'task_id' => $task_id,
                        'event_code' => $event_code,
                    ));
            }
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(),
                array(
                    'function_name' => __FUNCTION__,
                    'task_id' => $task_id,
                    'event_code' => $event_code,
                ));
        }

        return array($jira_issue_no,$ret);
    }

    /*
     * $note @Array
     * $issue_key @string
     */
    public function adding_comments_to_jira($note, $issue_key)
    {
        try{
            //Api Is needed to get rid of all comments
            $issueService = new IssueService();
            //Adding Comment After getting Jira Issue # Id
            $comment = new Comment();
            $date = date('d/m/Y h:i', strtotime($note['date_created']));
            $body =  $note['task_note_id'].' ( By '.$note['created_by_user_fullname']. ' on '.$date.' ) '. trim($note['note']);
            
            $comment->setBody($body);

            $comment_ret = $issueService->addComment($issue_key, $comment);
            if(JIRA_DEV) {
            E::log('Comment added on jira.',
                array(
                    'function_name' => __FUNCTION__,
                    'task_id' => $note['task_id'],
                    'note_id' => $note['task_note_id'],
                    'note' => $body,
                    'issue_id' => $issue_key,
                    'added_comment_id' => $comment_ret->id
                ));
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

    public function add_comments_to_jira($task_id = 0, $issue_key = '', $check_duplications = true)
    {
        try{

            if($task_id == 0){
                throw new JiraException('Task id is required.');
            }

            if($issue_key == ''){
                throw new JiraException('Issue key is required.');
            }

            $comments = $this->getTaskNotes($task_id);
            //var_dump($comments);
            $no_comments = count($comments);

            if(isset($comments) && $no_comments)
            {
                $r_comments = array_reverse($comments); // Reverse the order of comments

                E::log('INFO: ', 'Total '.$no_comments.' comments are found from swf.');

                foreach($r_comments as $c)
                {
                    $swf_note = trim($c['note']);
                    if (strlen($swf_note) == 0) {
                        continue;
                    }
                    elseif ($c['created_by_user_id'] == SWF_USER_BOT_ID) {
                        continue;
                    }

                    if($check_duplications){ //For Old Issue , We check comment Duplication

                        if($this->check_comment_exists_on_jira($c['task_note_id'], $issue_key) == false){

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

                    }else{

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
            }else{
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

    public function check_comment_exists_on_jira($swf_note_id = 0, $issue_key = '')
    {
        try
        {
            if($swf_note_id == 0)
                throw new JiraException('Comment id is required.');

            if($issue_key == '')
                throw new JiraException('Issue key is required for getting comments.');

            //-------------------------- 18/04/2018 -----------------------------
            $issueService = new IssueService();
            $comments = $issueService->getComments($issue_key);
            //------------------------------------------------------------------
            if($comments->total)
            {
                foreach ($comments->comments as $c)
                {
                    $key = strstr($c->body, ' ', true);
                    $swf_note_id_in_jira = (int) $key;

                    if($swf_note_id_in_jira == $swf_note_id) // Checking Comment id found Jira.
                        return $c;
                }
            }
            return false;

        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(),
                array(
                    'function_name' => __FUNCTION__
                ));
        }
    }

    public function string_sanitizer($str)
    {
        $str = filter_var($str, FILTER_SANITIZE_STRING);
        $str = str_replace(array("\r", "\n", '\r', '\n'), '', $str);

        return $str;
    }

    public function task_created($swf_task_details,$task_event_code = '',$comment_addition = false)
    {
        try
        {
            //1
            $task_details = $swf_task_details['TaskDetails']; //print_r($swf_task_details);

            // ---------------------------------------------------------
            if(JIRA_DEV) {
                E::log('INFO: In Issue created',
                    array(
                        'function_name' => __FUNCTION__,
                        'task_id' => $task_details['task_id'],
                        'event_code' => $task_event_code
                         //'issue_key' => $issue_id,
                        //'issueField' => $issueField,
                    ));
            }
            // ---------------------------------------------------------

            $issue_id = '';

            //------------------------------- Duplication ---------------------------------------
            list($issue_no,$jira_response) = $this->get_issue_no($task_details['task_id'], $task_event_code); // 'TASK_CREATED'

            if(is_null($issue_no)) {
                throw new JiraException('Failed to find Issue, task_id # ' . $task_details['task_id']);
            }

            if(strlen($issue_no) > 0) {
                throw new JiraException('Ticket already exists on Jira # ' . $issue_no. '.');
            }
            //------------------------------------------------------------------------------------
            //2
            $creator_details = $swf_task_details['CreatedByUserDetails'];
            /*
            $swf_reporter = '';
            if(isset($creator_details['email']) && strlen($creator_details['email'])){
                $swf_reporter = $this->get_username($creator_details['email']);
            }
            */
            
            //3
            $swf_assignee = '';
            $assignee_details = $swf_task_details['AssignedToUserDetails'];
            if(isset($assignee_details['email']) && strlen($assignee_details['email'])){
                $swf_assignee = $this->get_username($assignee_details['email']);
            }
            
            //4
            $project_customfield_10044 = 'SWF'; // Default value of customField: Customers in jira
            $project_details = $swf_task_details['ProjectDetails'];
            
            //5
            $category_customfield_10049 = null;
            $swf_task_categories = $this->getTaskCategories();
            if ($task_details['task_category_id']) {
                $category_customfield_10049 = $swf_task_categories[$task_details['task_category_id']]; //Category
            }
            
            //6
            $url_customfield_10048 = '';
            if(strlen($task_details['task_hash'])) {
                $url_customfield_10048 =  SWF_TICKET_HASH_LINK.$task_details['task_hash']; // URL
            }
            
            //7
            $originator_customfield_10050 = $task_details['task_originator'] ? $task_details['task_originator'] : '';
            
            //8
            $chargeable_customfield_10051 = array();
            if($task_details['task_chargeable']){
                $chargeable_customfield_10051['value'] = 'Yes';
            }else{
                $chargeable_customfield_10051['value'] = 'No';
            }

            /*
            *
            * Jira Mandatory fields for creating Issue:
            * Reporter, Summary, Issue type 
            * 
            */
          
           //9
           $jira_issue_summary = 'To be updated';
           if(isset($task_details['task_name']) && strlen($task_details['task_name'])){
             
                $jira_issue_summary = $this->string_sanitizer($task_details['task_name']);
            
            }

            $issueField = new IssueField();
            $issueField->setProjectKey(JIRA_PROJECT_CODE)
                ->setSummary($jira_issue_summary) // *
                //->setReporterName('jirabot') // $swf_reporter *
                ->setAssigneeName($swf_assignee) // $swf_assignee
                ->setPriorityName($this->get_priority($task_details['task_priority_id'])) // Required From SWF
                ->setDescription($task_details['task_description'])
                ->addCustomField( 'customfield_10043',  strval($task_details['task_id'])) // Ticket Number
                ->addCustomField( 'customfield_10048', $url_customfield_10048) // URL
                ->addCustomField( 'customfield_10050', $originator_customfield_10050) // Originator
                ->addCustomField( 'customfield_10051', $chargeable_customfield_10051); // Chargeable
                        
            if(!is_null($category_customfield_10049)) {
                $issueField->addCustomField( 'customfield_10049',  array( 'value' => $category_customfield_10049 )); // Category
            }
            
            //10
            if(is_null($task_details['parent_task_id'])) { // task_type_id
                $issueField->setIssueType($this->get_task_types($task_details['task_type_id'])); // Required From SWF *
            }
            else
            { 
                // Creating Sub-task
                // If the parent's task type is Support then Sub-task will NOT be created
                // As Issue Type will not be set and setIssueType is REQUIRED in order to create Issue in Jira
                
                if($task_details['parent_task_id']) // Integer Validation
                {
                    list($ParentKeyOrId,$jira_response) = $this->get_issue_no($task_details['parent_task_id'], 'SUB_TASK_CREATED');
                    
                    if(strlen($ParentKeyOrId))
                    {
                        $issueField->setIssueType('Sub-task'); // Required From SWF
                        $issueField->setParentKeyOrId($ParentKeyOrId);
                    }
                    else {
                        $exp_msg = 'Failed to create Issue. It is a Sub-task and Its parent task does not exist in jira (may be parent is a support task). parent_task_id #';
                        throw new JiraException($exp_msg .' '. $task_details['parent_task_id']);
                    }
                }
            }

            //------------Customer-------------------------------------------------------------------------------------
            
            $issueService = new IssueService();

            $customers = $issueService->getJiraCustomers(JIRA_PROJECT_CODE); // Getting List of Jira Customers
            
            foreach($customers as $cust) {
                
                if (isset($project_details['project_code']) && $project_details['project_code']) {
                    
                    if($cust->value == $project_details['project_code']) { // Checking Swf Customer Exists on Jira
                        $project_customfield_10044 = $project_details['project_code'];
                    }
                }
            }
            //---------------------------------------------------------------------------------------------------------

            if($project_customfield_10044 == '')
            {
                E::log('INFO: Project Not found in Jira. Issue could NOT be Created',
                    array(
                        'function_name' => __FUNCTION__,
                        'project_code' => $project_details['project_code'],
                        'event_code' => $task_event_code
                    ));
            }
            else {
                $issueField->addCustomField( 'customfield_10044',  array( 'value' => $project_customfield_10044));

                $ret = $issueService->create($issueField);
                $issue_id = $ret->key;
            }

            if (strlen($issue_id)) {

                E::log('INFO: Issue created',
                    array(
                        'function_name' => __FUNCTION__,
                        //'issueField' => $issueField,
                        'task_id' => $task_details['task_id'],
                        'issue_key' => $issue_id,
                        'event_code' => $task_event_code
                    ));

                //--------------------------------Adding Status ----------------------------------------
                if(is_numeric($task_details['task_status_id']) && $task_details['task_status_id'] > 0)
                {
                    $this->update_task_status($swf_task_details, '', $issue_id);
                }
                //--------------------------------Adding Comments----------------------------------------
                if($comment_addition){
                    $this->add_comments_to_jira($task_details['task_id'], $issue_id, false);
                }
                //---------------------------------------------------------------------------------------

                //Adding Attachments
                if(count($swf_task_details['TaskAttachments']) > 0) {
                    $this->add_attachment($swf_task_details);
                }
            }

        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(),
                array(
                    'function_name' => __FUNCTION__,
                    'task_id' => $task_details['task_id'],
                    'event_code' => $task_event_code
                ));
        }

        return $issue_id;
    }

    public function add_attachment($swf_task_details)
    {
        try
        {
            //Adding Attachment To Issue
            $task_details = $swf_task_details['TaskDetails'];
            list($issue_key,$jira_response) = $this->get_issue_no($task_details['task_id'], 'Add_attachment');

            if(strlen($issue_key) == 0)
            {
                if(JIRA_DEV) {
                E::log('INFO: Issue not found.',
                    array(
                        'function_name' => __FUNCTION__,
                        'task_id' => $task_details['task_id'],
                        'task_id' => $issue_key
                    ));
                }
                //$issue_key = $this->task_created($swf_task_details,'Add_attachment');
                throw new JiraException('Issue number not found. it is required for adding attachments.');
            }

            $issueService = new IssueService();

            $jira_attachments = $issueService->getAttachments($issue_key);

            foreach ($swf_task_details['TaskAttachments'] as $attachment_details)
            {
                if(isset($attachment_details['task_attachment_id'])) {
                    // 5.

                    $file_path_up = ''; //local Attachment Directory
                    $swf_file_path = TASK_ATTACHMENT_URL.$attachment_details['directory'].rawurlencode($attachment_details['file_name']); // Url : File Name on SWF

                    //Extension
                    //$extension = end(explode(".", $attachment_details['file_name']));
                    $extension = pathinfo($attachment_details['file_name'], PATHINFO_EXTENSION);
                    $file_name = $attachment_details['task_attachment_id'].'_'.$attachment_details['task_id'].'.'.$extension;

                    if(in_array( $file_name, $jira_attachments)){
                        if(JIRA_DEV) {
                        E::log('INFO: Attachment already exists on Jira',
                            array(
                                'attachment' => $attachment_details,
                                'file_name' => $file_name
                            ));
                        }
                    }else{

                        $status_code = $issueService->downloadAttachments($file_name, $swf_file_path, LOCAL_ATTACHMENT_DIR);

                        if($status_code == 200){
                            $file_path_up = LOCAL_ATTACHMENT_DIR .'/'.preg_replace('/\//', '', $file_name, 1);
                            if(JIRA_DEV) {
                            E::log('INFO: Attachment downloaded',
                                array(
                                    'function_name' => __FUNCTION__,
                                    'task_id' => $attachment_details['task_id'],
                                    'issue_id' => $issue_key,
                                    'attachment_url' => $swf_file_path,
                                    'status' => $status_code
                                ));
                            }
                        }

                        $attach_resp = $issueService->addAttachments($issue_key, $file_path_up);

                        if(count($attach_resp))
                        {
                            if(JIRA_DEV) {
                            E::log('INFO: Attachment uploaded. Deletion will occur.',
                                array(
                                    'function_name' => __FUNCTION__,
                                    'task_id' =>  $attachment_details['task_id'],
                                    'issue_id' => $issue_key,
                                    'file_name' => $file_name,
                                    // 'attachment_resp' => $attach_resp
                                ));
                            }

                            unlink(LOCAL_ATTACHMENT_DIR .'/'.$file_name);
                        }
                    }

                }else {
                    E::log('INFO: ', $attachment_details);
                }
            }
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(),
                array(
                    'function_name' => __FUNCTION__,
                    'task_id' => $swf_task_details['TaskDetails']['task_id'],
                ));
        }
    }

    /*
     * $swf_task_details @array
     * $note_id @Integer
     */

    public function task_note_created($swf_task_details,$note_id,$task_event_code = '')
    {
        try{

            $task_details = $swf_task_details['TaskDetails'];
            $issue_key = '';

            if (JIRA_DEV) {
                E::log('INFO: In function: task_note_created', array(
                    'task_id' => $task_details['task_id'],
                    'note_id' => $note_id,
                    'event_code' => $task_event_code
                ));
            }

            // Adding A single comment to jira when issue exists.
            $notes = $this->getTaskNoteById($note_id);

            if (isset($notes[0]) && $notes[0]) {

                // Constraint
                if ($notes[0]['created_by_user_id'] == SWF_USER_BOT_ID) {
                    throw new JiraException('Could not posted bot \'s comment on Jira.');
                }

                //------------------------------- Issue Duplication ---------------------------------------
                list($issue_key, $jira_response) = $this->get_issue_no($task_details['task_id'], $task_event_code); // 'TASK_NOTE_CREATED'

                if (strlen($issue_key) == 0) {
                    $issue_key = $this->task_created($swf_task_details, $task_event_code, $comment_addition = true);

                    if (strlen($issue_key)) {
                        if (JIRA_DEV) {
                            E::log('INFO: Issue created successfully. All notes are added.', array(
                                'function_name' => __FUNCTION__,
                                'task_id' => $task_details['task_id'],
                                'issue_key' => $issue_key,
                                'event_code' => $task_event_code
                            ));
                        }
                    } else {
                        throw new JiraException('Issue was not created. Comment could NOT be Added');
                    }
                    //--------------------------------------------------------------------------------------
                } else {

                    $swf_note = trim($notes[0]['note']);
                    if (strlen($swf_note) > 0) {
                        // Issue Exists On Jira
                        if ($this->check_comment_exists_on_jira($note_id, $issue_key)) {
                            throw new JiraException('Comment : ' . $note_id . ' already exists on Jira issue# : ' . $issue_key);
                        }

                        $this->adding_comments_to_jira($notes[0], $issue_key);
                    } else {
                        throw new JiraException('Comment #: ' . $note_id . ' is Empty, issue# : ' . $issue_key);
                    }
                }
            }
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'event_code' => $task_event_code,
                'task_id' => $task_details['task_id'],
                'note_id' => $note_id,
                'note' => $notes[0],
            ));
        }
        return $issue_key;
    }

    /*
     * $swf_task_details array
     * $comment string
     * $issue_no sring (task_created : status updation)
     */
    public function update_task_status( $swf_task_details, $comment = '',$task_event_code = '',$issue_no = '')
    {
        try
        {
            if(JIRA_DEV) {
            E::log('INFO: TASK_STATUS_CHANGED', array(
                'function_name' => __FUNCTION__,
                'task_id' => $swf_task_details['TaskDetails']['task_id'],
                'event_code' => $task_event_code
            ));
            }

            $task_details = $swf_task_details['TaskDetails'];
            $issue_key = '';
            $jira_response = array();

            if($issue_no == '')
                list($issue_key,$jira_response) = $this->get_issue_no($task_details['task_id'],$task_event_code); // 'TASK_STATUS_CHANGED'
            else
                $issue_key = $issue_no;

            if(strlen($issue_key) == 0) {

                $issue_key = $this->task_created($swf_task_details,$task_event_code,$comment_addition = true);

                if (strlen($issue_key) == 0) {
                     throw new JiraException('Issue was not created. Status could NOT be updated.');
                }
            }
            else {
                
                if(!is_numeric($task_details['task_status_id'])) {
                    throw new JiraException('Task status id is required.');
                }
                
                $value_matched = $this->check_jira_field_value($task_details, $jira_response, $task_event_code);

                if($value_matched == false) {
                    
                    //$status_name = $this->get_swf_status_name($task_details['task_status_id']);
                    $status_name = get_swf_status_name_by_id_from_file($task_details['task_status_id']);

                    if(strlen($status_name) == 0) {
                        throw new JiraException('Task status is not found.');
                    }

                    //echo  $status_name . '< br />';

                    $isUpdated = $this->update_jira_issue_status($status_name, $issue_key, $task_event_code, $comment = '');

                    if($isUpdated) {
                        return true;
                    }
                }
            }
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'task_id' => $task_details['task_id'],
                'issue_key' => $issue_key,
                'task_status_id' => $task_details['task_status_id'],
                'event_code' => $task_event_code
            ));
        }
    }

    public function update_jira_issue_status($status_name, $jira_issue_no, $task_event_code, $comment = '')
    {
        $transition = new Transition();
        $transition->setTransitionName($status_name);

        if($comment != '')
            $transition->setCommentBody($comment);

        $issueService = new IssueService();
        $ret = $issueService->transition( $jira_issue_no, $transition);

        //var_dump($ret) e::log($ret);

        if($ret == 1) {

            if(JIRA_DEV) {
                E::log('INFO: Status Updated', array(
                    'function_name' => __FUNCTION__,
                    'event_code' => $task_event_code,
                    'status_name' => $status_name,
                    'transition' => $transition,
                    'issue_key' => $jira_issue_no,
                ));
            }

            return true;
        }
    }

    //----------------------- Audit Log Updation--------------------------
    /* @$task_id int
     * @$days Int
     */
    public function update_task_name($swf_task_details,$audit_log_name)
    {
        try
        {
            $task_details = $swf_task_details['TaskDetails'];
            $task_id = $swf_task_details['TaskDetails']['task_id'];

            list($issue_key,$jira_response) = $this->get_issue_no($task_id ,$audit_log_name); // 'task_name'

            if(strlen($issue_key) == 0)
            {
                $issue_key = $this->task_created($swf_task_details,$audit_log_name,$comment_addition = true);

                 if (strlen($issue_key) == 0) {
                     throw new JiraException('Issue was not created. Task name could NOT be updated.');
                }

            } else {

                $value_matched = $this->check_jira_field_value($task_details, $jira_response, $audit_log_name);

                if($value_matched == false) {

                    $summary = $this->string_sanitizer( $swf_task_details['TaskDetails']['task_name'] ); 

                    $issueField = new IssueField(true);
                    $issueField->setSummary($summary);
                    // optionally set some query params
                    $editParams = [
                        'notifyUsers' => false,
                    ];
                    $issueService = new IssueService();
                    // You can set the $paramArray param to disable notifications in example
                    $ret = $issueService->update($issue_key, $issueField, $editParams);

                    if($ret)
                    {
                        if(JIRA_DEV) {
                        E::log('INFO: Task name updated.', array(
                            'function_name' => __FUNCTION__,
                            'task_id' => $task_id,
                            'issue_key' => $issue_key,
                            'summary' => $summary
                        ));
                        }
                    }
                }
            }
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'task_id' => $task_id
            ));
        }
    }

    public function update_task_description( $swf_task_details,$audit_log_name)
    {
        try
        {
            $task_details = $swf_task_details['TaskDetails'];
            $task_id = $swf_task_details['TaskDetails']['task_id'];

            list($issue_key,$jira_response) = $this->get_issue_no($task_id ,$audit_log_name); // 'task_description'

            if(strlen($issue_key) == 0)
            {
                $issue_key = $this->task_created($swf_task_details,$audit_log_name,$comment_addition = true);
                
                if (strlen($issue_key) == 0) {
                     throw new JiraException('Issue was not created. Task description could NOT be updated.');
                }

            }else {

                $value_matched = $this->check_jira_field_value($task_details, $jira_response, $audit_log_name);

                if($value_matched == false) {

                    $description = $swf_task_details['TaskDetails']['task_description'];
                    $issueField = new IssueField(true);
                    $issueField->setDescription($description);
                    // optionally set some query params
                    $editParams = [
                        'notifyUsers' => false,
                    ];
                    $issueService = new IssueService();
                    // You can set the $paramArray param to disable notifications in example
                    $ret = $issueService->update($issue_key, $issueField, $editParams);

                    if($ret){
                        if(JIRA_DEV) {
                        E::log('INFO: Task description updated.', array(
                            'function_name' => __FUNCTION__,
                            'task_id' => $task_id,
                            'issue_key' => $issue_key,
                            'description' => $description
                        ));
                        }
                    }
                }
            }
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'task_id' => $task_id
            ));
        }
    }

    public function update_assignee($swf_details,$audit_log_name)
    {
        try
        {
            $task_details = $swf_details['TaskDetails'];
            $assignee_details = $swf_details['AssignedToUserDetails'];
            $issue_key = $assignee_name = '';

            if(count($assignee_details) == 0)
            {
                throw new JiraException('User must be assigned to task.');
            }

            list($issue_key,$jira_response) = $this->get_issue_no($task_details['task_id'],$audit_log_name); // 'assigned_to_user_id'

            if(strlen($issue_key) == 0) {

                $issue_key = $this->task_created($swf_details,$audit_log_name,$comment_addition = true);

                if(strlen($issue_key) == 0) {
                    throw new JiraException('Issue was not created. Assignee could not be updated');
                }
            }
            else {

                $value_matched = $this->check_jira_field_value($swf_details, $jira_response, $audit_log_name);

                if($value_matched == false) {

                    $issueField = new IssueField(true);

                    if(isset($assignee_details['email']) && strlen($assignee_details['email'])) {

                        $assignee_name = $this->get_username($assignee_details['email']);

                        $issueField->setAssigneeName($assignee_name);
                        // optionally set some query params
                        $editParams = [
                            'notifyUsers' => false,
                        ];

                        $issueService = new IssueService();

                        $ret = $issueService->update($issue_key, $issueField, $editParams);

                        if($ret)
                        {
                            if(JIRA_DEV) {
                            E::log('INFO: Assignee updated successfully.', array(
                                'function_name' => __FUNCTION__,
                                'task_id' => $task_details['task_id'],
                                'issue_key' => $issue_key,
                                'assignee' => $assignee_name
                            ));
                            }
                        }
                    }
                    else {
                        E::log('INFO: Assignee is not SET.', array(
                                'function_name' => __FUNCTION__,
                                'task_id' => $task_details['task_id'],
                                'issue_key' => $issue_key,
                                'assignee' => $assignee_name
                            ));
                    }
                }
            }
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'task_id' => $task_details['task_id'],
                'issue_key' => $issue_key,
            ));
        }
    }
    
    public function update_originator($swf_task_details,$audit_log_name)
    {
        try
        {
            $task_details = $swf_task_details['TaskDetails'];
            $task_id = $swf_task_details['TaskDetails']['task_id'];

            list($issue_key,$jira_response) = $this->get_issue_no($task_id, $audit_log_name); // 'task_originator'

            if(strlen($issue_key) == 0) {

                $issue_key = $this->task_created($swf_task_details,$audit_log_name,$comment_addition = true);
                
                if (strlen($issue_key) == 0) {
                     throw new JiraException('Issue was not created. Originator could NOT be updated.');
                }

            }else {

                $value_matched = $this->check_jira_field_value($task_details, $jira_response, $audit_log_name);

                if($value_matched == false) {

                    $originator = $swf_task_details['TaskDetails']['task_originator'];

                    $issueField = new IssueField(true);
                    $issueField->addCustomField( 'customfield_10050',  $originator);
                    // optionally set some query params
                    $editParams = [
                        'notifyUsers' => false,
                    ];
                    $issueService = new IssueService();
                    // You can set the $paramArray param to disable notifications in example
                    $ret = $issueService->update($issue_key, $issueField, $editParams);
                    if($ret)
                    {
                        if(JIRA_DEV) {
                        E::log('INFO: Task originator updated.', array(
                            'function_name' => __FUNCTION__,
                            'task_id' => $task_id,
                            'issue_key' => $issue_key,
                            'originator' => $originator
                        ));
                        }
                    }
                }
            }
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'task_id' => $task_id
            ));
        }
    }

    public function update_task_priority( $swf_task_details,$audit_log_name)
    {
        try {

            $task_details = $swf_task_details['TaskDetails'];

            list($issue_key,$jira_response) = $this->get_issue_no($task_details['task_id'], $audit_log_name); // 'task_priority_id'

            if(strlen($issue_key) == 0) {

                $issue_key = $this->task_created($swf_task_details,$audit_log_name,$comment_addition = true);

                if (strlen($issue_key) == 0) {
                     throw new JiraException('Issue was not created. Task priority could NOT be updated.');
                }

            }else {

                $value_matched = $this->check_jira_field_value($task_details, $jira_response, $audit_log_name);

                if($value_matched == false) {

                    $priority = $this->get_priority($task_details['task_priority_id']);
                    $issueField = new IssueField(true);
                    $issueField->setPriorityName($priority); // Required From SWF
                    // optionally set some query params
                    $editParams = [
                        'notifyUsers' => false,
                    ];
                    $issueService = new IssueService();
                    // You can set the $paramArray param to disable notifications in example
                    $ret = $issueService->update($issue_key, $issueField, $editParams);
                    if($ret)
                    {
                        if(JIRA_DEV) {
                        E::log('INFO: Task priority updated.', array(
                            'function_name' => __FUNCTION__,
                            'task_id' => $task_details['task_id'],
                            'issue_key' => $issue_key,
                            'priority' => $priority
                        ));
                        }
                    }
                }
            }
        }
        catch (JiraException $e) 
        {
            E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'task_id' =>  $task_details['task_id']
            ));
        }
    }

    public function update_task_type($swf_task_details,$audit_log_name)
    {
         try
        {
            $task_details = $swf_task_details['TaskDetails'];

            list($issue_key,$jira_response) = $this->get_issue_no($task_details['task_id'], $audit_log_name); // 'task_type_id'

            if(strlen($issue_key) == 0) {
                
                $issue_key = $this->task_created($swf_task_details,$audit_log_name,$comment_addition = true);
                
                if (strlen($issue_key) == 0) {
                     throw new JiraException('Issue was not created. Task type could NOT be updated.');
                }
            
            }else {

                $value_matched = $this->check_jira_field_value($task_details, $jira_response, $audit_log_name);

                if($value_matched == false) {

                    $task_type = $this->get_task_types($task_details['task_type_id']);

                    if(JIRA_DEV) {
                    E::log('INFO: Task type to be updated.', array(
                        'function_name' => __FUNCTION__,
                        'task_id' =>  $task_details['task_id'],
                        'issue_key' => $issue_key,
                        'swf_task_type_id' => $task_details['task_type_id'],
                        'task_type' => $task_type
                    ));
                    }

                    $issueField = new IssueField(true);
                    $issueField->setIssueType($task_type); // Required From SWF
                    // optionally set some query params
                    $editParams = [
                        'notifyUsers' => false,
                    ];
                    $issueService = new IssueService();
                    // You can set the $paramArray param to disable notifications in example
                    $ret = $issueService->update($issue_key, $issueField, $editParams);
                    if($ret)
                    {
                        if(JIRA_DEV) {
                        E::log('INFO: Task type updated.', array(
                            'function_name' => __FUNCTION__,
                            'task_id' =>  $task_details['task_id'],
                            'issue_key' => $issue_key,
                            'swf_task_type_id' => $task_details['task_type_id'],
                            'task_type' => $task_type
                        ));
                        }
                    }
                }
            }
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'task_id' => $task_details['task_id']
            ));
        }
    }

    public function update_task_category($swf_task_details,$audit_log_name)
    {
        try
        {
            $task_details = $swf_task_details['TaskDetails'];

            list($issue_key,$jira_response) = $this->get_issue_no($task_details['task_id'], $audit_log_name); // 'task_category_id'

            if(strlen($issue_key) == 0){
                
                $issue_key = $this->task_created($swf_task_details,$audit_log_name,$comment_addition = true);
                
                if (strlen($issue_key) == 0) {
                     throw new JiraException('Issue was not created. Task category could NOT be updated.');
                }

            }else {

                $value_matched = $this->check_jira_field_value($task_details, $jira_response, $audit_log_name);

                if($value_matched == false) {

                    $swf_task_categories = $this->getTaskCategories();

                    $category_customfield_10049 = $swf_task_categories[$task_details['task_category_id']];

                    if(!is_null($category_customfield_10049)){

                        $issueField = new IssueField(true);
                        $issueField->addCustomField( 'customfield_10049',  array( 'value' => $category_customfield_10049 ));

                        $editParams = [
                            'notifyUsers' => false,
                        ];

                        $issueService = new IssueService();
                        // You can set the $paramArray param to disable notifications in example
                        $ret = $issueService->update($issue_key, $issueField, $editParams);
                        if($ret)
                        {
                            if(JIRA_DEV) {
                            E::log('INFO: Task Category updated.', array(
                                'function_name' => __FUNCTION__,
                                'task_id' =>  $task_details['task_id'],
                                'issue_key' => $issue_key,
                                'task_category' => $category_customfield_10049
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
                'function_name' => __FUNCTION__,
                'task_id' => $task_details['task_id']
            ));
        }
    }

    public function update_actual_development_days($swf_task_details,$audit_log_name)
    {
        try
        {
            $task_details = $swf_task_details['TaskDetails'];

            list($issue_key,$jira_response) = $this->get_issue_no($task_details['task_id'], $audit_log_name); // 'actual_development_days'

            if(strlen($issue_key) == 0) {
                
                $issue_key = $this->task_created($swf_task_details,$audit_log_name,$comment_addition = true);
                
                if (strlen($issue_key) == 0) {
                     throw new JiraException('Issue was not created. actual_development_days could NOT be updated.');
                }

            }

            if($task_details['actual_development_days']) {
                
                $actual_development_days = $task_details['actual_development_days'].'d';
                
                $timeTracking = new TimeTracking();
                $timeTracking->setOriginalEstimate($actual_development_days);
                //$timeTracking->setRemainingEstimate('1w 2d 3h');

                $issueService = new IssueService();
                $ret = $issueService->timeTracking($issue_key, $timeTracking);
                
                if($ret) {
                    if(JIRA_DEV) {
                    E::log('INFO: Task development days updated.', array(
                        'function_name' => __FUNCTION__,
                        'task_id' => $task_details['task_id'],
                        'issue_key' => $issue_key,
                        'task_category' => $actual_development_days
                    ));
                    }
                }
            }    
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'task_id' => $task_details['task_id']
            ));
        }
    }

    public function update_task_note($note_audit_log, $swf_note_arr, $issue_key, $audit_log_name = '')
    {
        $note_id = $swf_note_arr['task_note_id']; // $note_audit_log['row_id']
        $note = $note_audit_log['value_new'];

        if(JIRA_DEV) {
            E::log('INFO: In function update_task_note.', array(
                'issue_key' => $issue_key,
                'taskId' => $swf_note_arr['task_id'],
                'note_id'=> $note_id
            ));
        }

        if ($jira_comment = $this->check_comment_exists_on_jira($note_id, $issue_key)) {
            $jira_c_id = $jira_comment->id;
            //$jira_c_body = $jira_comment->body;

            $date = date('d/m/Y h:i', strtotime($swf_note_arr['date_created']));
            $body =  $note_id.' ( By '.$swf_note_arr['created_by_user_fullname']. ' on '.$date.' ) '. trim($swf_note_arr['note']);

            $issueService = new IssueService();

            if ($swf_note_arr['note'] == $note) {
                $ar = [
                    'body' => $body
                ];
                $ret = $issueService->updateComment($issue_key, $jira_c_id, $ar);

                if($ret->id){
                    if(JIRA_DEV) {
                        E::log('INFO: Comment updated on Jira.', array(
                            'function_name' => __FUNCTION__,
                            'issue_key' => $issue_key,
                            'taskId' => $swf_note_arr['task_id'],
                            'note_id'=> $note_id,
                            'jira_comment_id' => $ret->id
                        ));
                    }
                }
            }
        }
        else {
            $this->adding_comments_to_jira($swf_note_arr, $issue_key);
        }
    }

    public function test_update_task_sprint($issue_key, $new_sprint_no)
    {
        try
        {
            $issueField = new IssueField(true);
            $issueField->addCustomField( 'customfield_10010', $new_sprint_no );
            // optionally set some query params
            $editParams = [
                'notifyUsers' => false,
            ];
            $issueService = new IssueService();
            // You can set the $paramArray param to disable notifications in example
            $ret = $issueService->update($issue_key, $issueField, $editParams);
            
            if ($ret)
            {
                if(JIRA_DEV) {
                E::log('INFO: Task Sprint updated.', array(
                    'function_name' => __FUNCTION__,
                    'task_id' => '',
                    'issue_key' => $issue_key,
                    'sprint' => $new_sprint_no
                ));
                }
            }
            
            
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'task_id' => ''
            ));
        }
    }

    /*public function update_task_sprint($swf_task_details, $sprint_log_details)
    {
        try
        {
            $task_details = $swf_task_details['TaskDetails'];
            $task_id = $swf_task_details['TaskDetails']['task_id'];
            $audit_log_name = $sprint_log_details['table_field_name'];

            list($issue_key,$jira_response) = $this->get_issue_no($task_id, $audit_log_name); // 'sprint_id'

            if(strlen($issue_key) == 0) {

                $issue_key = $this->task_created($swf_task_details,$audit_log_name,$comment_addition = true);
                
                if (strlen($issue_key) == 0) {
                     throw new JiraException('Issue was not created. Sprint could NOT be updated.');
                }

            }else {

                //$value_matched = $this->check_jira_field_value($task_details, $jira_response, $audit_log_name);

                if( $sprint_log_details['value_new'] ) {

                    $new_sprint_no = $sprint_log_details['value_new'];

                    $issueField = new IssueField(true);
                    $issueField->addCustomField( 'customfield_10010', $new_sprint_no );
                    // optionally set some query params
                    $editParams = [
                        'notifyUsers' => false,
                    ];
                    $issueService = new IssueService();
                    // You can set the $paramArray param to disable notifications in example
                    $ret = $issueService->update($issue_key, $issueField, $editParams);
                    if($ret)
                    {
                        if(JIRA_DEV) {
                        E::log('INFO: Task Sprint updated.', array(
                            'function_name' => __FUNCTION__,
                            'task_id' => $task_id,
                            'issue_key' => $issue_key,
                            'sprint' => $new_sprint_no
                        ));
                        }
                    }
                }
            }
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'task_id' => $task_id
            ));
        }
    }*/

    public function check_constraint($swf_task_details)
    {
    
        $constraint = '';

       //------------------ SWF task_type_id Handling: Constraint ----------------------------------------
        if (strlen($swf_task_details['TaskDetails']['task_type_id']) == 0)
        {
            $constraint = ' INFO: task_type_id must be defined';
        }

        //------------------ SWF Support task Handling: Constraint ---------------------------------------
        /*elseif ($swf_task_details['TaskDetails']['task_type_id'] == 3) // Ignore Support Tasks
        {
             $constraint = ' INFO: Support task is not accepted. task_type_id = ' . $swf_task_details['TaskDetails']['task_type_id'];
        }*/

        return $constraint;
    }

    public function check_jira_field_value($task_details, $jira_response, $event_code)
    {
        try
        {
            $jira_field_value = false;

            //$jira_issue_no = $jira_response->issues[$jira_response->total -1]->key;
            $fields = $jira_response->issues[count($jira_response->issues) -1]->fields;
            $jira_issue_no = $jira_response->issues[count($jira_response->issues) -1 ]->key;

            if($event_code == SWF_EVENT_TASK_STATUS_CHANGED || $event_code == JIRA_TAB_STATUS_UPDATE)
            {
                $swf_status = '';
                if(is_numeric($task_details['task_status_id'])) {
                    //$swf_status = $this->get_swf_status_name($task_details['task_status_id']);
                    $swf_status = get_swf_status_name_by_id_from_file($task_details['task_status_id']);
                }
                $jira_status = $fields->status->name;

                if ( strtolower($swf_status) == strtolower($jira_status) ) {
                    throw new JiraException('No need to update. current swf_status: '.$swf_status .' current jira_status: '. $jira_status);
                }
            }
            elseif($event_code == 'task_type_id')
            {
                $swf_task_type = $this->get_task_types($task_details['task_type_id']);
                $jira_issue_type = $fields->issuetype->name;
                
                // For Sub Task : Type Could not be updated
                if($jira_issue_type == "Sub-task") {
                    throw new JiraException('Could not update issue type of a Sub Task # '.$jira_issue_no);
                }
                elseif ( strtolower($swf_task_type) == strtolower($jira_issue_type) )
                {
                    throw new JiraException('No need to update. current jira_type: '.$jira_issue_type .' current swf_type: '. $swf_task_type);
                }
            }
            elseif($event_code == 'task_category_id')  // Category
            {
                $swf_category = '';
                $swf_task_categories = $this->getTaskCategories();
                if ($task_details['task_category_id']) {
                    $swf_category = $swf_task_categories[$task_details['task_category_id']];
                }
                $jira_category = $fields->customfield_10049->value;

                if ( strtolower($swf_category) == strtolower($jira_category) ) {
                    throw new JiraException('No need to update. current jira_category: '.$jira_category .' current swf_category: '. $swf_category);
                }
            }
            elseif($event_code == 'task_priority_id') // Priority
            {
                $swf_priority = '';
                if ($task_details['task_priority_id']) {
                    $swf_priority = $this->get_priority($task_details['task_priority_id']);
                }
                $jira_priority = $fields->priority->name;

                if ( strtolower($swf_priority) == strtolower($jira_priority) ) {
                    throw new JiraException('No need to update. current jira_priority: '.$jira_priority .' current swf_priority: '. $swf_priority);
                }
            }
            elseif($event_code == 'task_originator') // Originator
            {
                if ($task_details['task_originator']) {
                    $swf_originator = $task_details['task_originator'];
                }
                $jira_originator = $fields->customfield_10050;

                if ( strtolower($swf_originator) == strtolower($jira_originator) ) {
                    throw new JiraException('No need to update. current jira_originator: '.$jira_originator .' current swf_originator: '. $swf_originator);
                }
            }
            elseif($event_code == 'task_name') // task_name / summary
            {
                if(isset($task_details['task_name']) && strlen($task_details['task_name'])){
                    $swf_summary = $task_details['task_name'];
                }
                $jira_summary = $fields->summary;

                if ($swf_summary == $jira_summary) {
                    throw new JiraException('No need to update. current jira_summary: '.$jira_summary .' current swf_summary: '. $swf_summary);
                }
            }
            elseif($event_code == 'task_description') // task_description
            {
                if ($task_details['task_description']) {
                    $swf_description = $task_details['task_description'];
                }
                $jira_description = $fields->description;

                if ($swf_description == $jira_description) {
                    throw new JiraException('No need to update. current jira_description: '.$jira_description .' current swf_description: '. $swf_description);
                }
            }
            elseif($event_code == 'assigned_to_user_id') // assigned_to_user_id
            {
                $swf_assignee = '';
                $swf_details = $task_details;
                $task_details = $swf_details['TaskDetails'];
                $assignee_details = $swf_details['AssignedToUserDetails'];

                if(isset($assignee_details['email']) && strlen($assignee_details['email'])){
                    $swf_assignee = $this->get_username($assignee_details['email']);
                }
                $jira_assignee = $fields->assignee->name;

                if ( strtolower($swf_assignee) == strtolower($jira_assignee) ) {
                    throw new JiraException('No need to update. current jira_assignee: '.$jira_assignee .' current swf_assignee: '. $swf_assignee);
                }
            }
        }
        catch (JiraException $e)
        {
            E::log($e->getMessage(), array(
                'function_name' => __FUNCTION__,
                'task_id' => $task_details['task_id'],
            ));

            $jira_field_value = true;
        }

        return $jira_field_value;
    }

    public function TESTrunSwfToJiraFlow($task_id) {

        //print_r($task_id);

        $start = microtime(true);
        $note_event_issue_key = '';

        try {

            $dates = get_dates(SWFTOJIRA_CT_FILENAME, "Y-m-d H:i:s");// "-4 hour" -25 minutes

            if(JIRA_DEV) {
                E::log('-------------------- Simply Workflow to Jira : Start --------------------',
                    ' swf_instance: '. SWF_INSTANCE .
                    ' dateFrom: '. $dates['dateFrom'] .
                    ' dateTo: '. $dates['dateTo']
                );
            }

            $swf_task_details = $this->getTaskDetailsAll($task_id);
            //print_r($swf_task_details);

            $task_details = $swf_task_details['TaskDetails'];

            //$swf_task_type = $this->get_task_types($task_details['task_type_id']);
            //$issue_key = $this->task_created(); //$swf_task_details,$task_event['event_code'],$comment_addition = true

            $issueField = new IssueField();
            $issueField->setProjectKey(JIRA_PROJECT_CODE)
                ->setSummary($task_details['task_name']) // $jira_issue_summary *
                ->setDescription($task_details['task_description'])
                ->setIssueType('Bug'); // *
                //->setReporterName('jirabot'); // $swf_reporter *

            $issueService = new IssueService();
            $ret = $issueService->create($issueField);
            $issue_id = $ret->key;

            if (strlen($issue_id)) {

                E::log('INFO: Issue created',
                    array(
                        'function_name' => __FUNCTION__,
                        //'issueField' => $issueField,
                        'task_id' => $task_details['task_id'],
                        'issue_key' => $issue_id,
                        'event_code' => ''
                    ));
            }

            save_date_on_file(SWFTOJIRA_CT_FILENAME);
        }
        catch (JiraException $e)
        {
            E::log('ERROR MSG: Simply workflow To Jira : ', $e->getMessage());
        }
        
        $time_elapsed_secs = microtime(true) - $start;
        E::log(' ===== Script Execution time: ' .$time_elapsed_secs. ' SWF to JIRA : End =====');

        $time_elapsed_secs = microtime(true) - $start;
        echo "<div align='center'>";
        echo "<p>This script took ".$time_elapsed_secs." to execute. </p>";
        echo "</div>";

    }

    public function runSwfToJiraFlow() {

        $start = microtime(true);
        $note_event_issue_key = '';

        try{

            $dates = get_dates(SWFTOJIRA_CT_FILENAME, "Y-m-d H:i:s");// "-4 hour" -25 minutes

            if(JIRA_DEV) {
                E::log('-------------------- Simply Workflow to Jira : Start --------------------',
                    ' swf_instance: '. SWF_INSTANCE .
                    ' dateFrom: '. $dates['dateFrom'] .
                    ' dateTo: '. $dates['dateTo']
                );
            }

            $task_events = $this->getTaskEventsByDateRange( $dates['dateFrom'],  $dates['dateTo']);
            //print_r($task_events); die();

            if (isset($task_events['TaskEvents']) && $task_events['TaskEvents']) {
                
                E::log(' ===== SWF to JIRA : Start ===== ', 'Total '. count($task_events['TaskEvents']) .' TaskEvents are found. =====');

                foreach ($task_events['TaskEvents'] as $task_event) {

                    $swf_task_details = $this->getTaskDetailsAll($task_event['task_id']);

                    $constraint = $this->check_constraint($swf_task_details);
                    if (strlen($constraint)) {
                        E::log($constraint, ' task_id: '. $task_event['task_id'] .' event_code: '. $task_event['event_code']);
                        continue;
                    }

                    switch ($task_event['event_code']) {
                        case SWF_EVENT_TASK_CREATED : // 1.
                            $issue_key = $this->task_created($swf_task_details,$task_event['event_code'],$comment_addition = true);
                            break;

                        case SWF_EVENT_TASK_NOTE_CREATED :  // 2.
                            $note_event_issue_key = $this->task_note_created($swf_task_details,$task_event['task_note_id'],$task_event['event_code']);
                            break;

                        case SWF_EVENT_TASK_STATUS_CHANGED : // 3.
                            /*E::log('INFO: ',
                                array(
                                    'event_code' => $task_event['event_code'],
                                    'task_id' => $task_event['task_id'],
                                    'event_message' => $task_event['event_message'],
                                ));*/

                            $status_history = $this->getTaskStatusHistory($task_event['task_id']);
                            // Constraint
                            if ($status_history[0]['changed_by_user_id'] == SWF_USER_BOT_ID) {
                                E::log(' INFO: Status could not be updated - Changed By Bot',
                                    ' task_id: '. $task_event['task_id'] .
                                    ' user_id: '. $status_history[0]['changed_by_user_id'] );
                            } else {
                                $this->update_task_status($swf_task_details,$task_event['event_message'],$task_event['event_code']);
                            }

                            break;
                    }

                    // 4. Attachments
                    $attachments = $this->getTaskAttachmentsByDateRange($swf_task_details['TaskDetails']['task_id'],
                        $dates['dateFrom'], $dates['dateTo']);
                    if (count($attachments)) {
                        unset($swf_task_details['TaskAttachments']);
                        $swf_task_details['TaskAttachments'] = $attachments;
                        $this->add_attachment($swf_task_details);
                    }

                } //-------------------- foreach : getTaskEventsByDateRange ---------------------------------------
            }
            // audit log table Id = 10073 , task notes table Id = 10074
            //$audit_logs = $this->getTaskAuditLogByDateRange( '10073', $dates['dateFrom'],  $dates['dateTo']);
            $audit_logs = $this->getTaskAuditLogAllByDateRange( $dates['dateFrom'],  $dates['dateTo']);

            if(isset($audit_logs['status']) && $audit_logs['status'] == 'S')
            {
                //print_r($audit_logs);
                // Project and Severity will not be updated once created (as per requirements)
                $audit_response = $audit_logs['response']['response'];

                if(!isset($audit_response['AUDIT_LOG']['ERROR']) && $audit_response['AUDIT_LOG']) {

                    E::log(' INFO: Total '.count($audit_response['AUDIT_LOG']).' audit(s) are found.',
                        ' dateFrom: '. $dates['dateFrom'] .
                        ' dateTo: '. $dates['dateTo'] );

                    foreach ($audit_response['AUDIT_LOG'] as $al) // ROW_UPDATE
                    {
                        $task_id = $al['row_id'];
                        $swf_task_details = $this->getTaskDetailsAll($task_id);

                        $constraint = $this->check_constraint($swf_task_details);
                        if (strlen($constraint)) {
                            E::log($constraint, 'task_id = ' . $task_id . ' task_audit_log = ' . $al['table_field_name']);
                            continue;
                        }

                        // Constraint: bot user
                        if ($al['user_id'] == SWF_USER_BOT_ID) {
                            E::log(' INFO: Constraint : User is a bot.',
                                ' task_id: '. $task_id .
                                ' task_type: '. $swf_task_details['TaskDetails']['task_type_id'] .
                                ' user_id: '. $al['user_id']
                                );

                            continue;
                        }

                        switch ($al['table_field_name']) {

                            case "actual_development_days" :
                                $this->update_actual_development_days($swf_task_details, $al['table_field_name']);
                                break;

                            case "task_category_id" :
                                $this->update_task_category($swf_task_details, $al['table_field_name']);
                                break;

                            case "task_type_id" :
                                $this->update_task_type($swf_task_details, $al['table_field_name']);
                                break;

                            case "task_priority_id" :
                                $this->update_task_priority($swf_task_details, $al['table_field_name']);
                                break;

                            case "task_originator" :
                                $this->update_originator($swf_task_details, $al['table_field_name']);
                                break;

                            case "task_name" :
                                $this->update_task_name($swf_task_details, $al['table_field_name']);
                                break;

                            case "task_description" :
                                $this->update_task_description($swf_task_details, $al['table_field_name']);
                                break;

                            case "assigned_to_user_id" :
                                $this->update_assignee($swf_task_details, $al['table_field_name']);
                                break;

                            /*case "sprint_id" :
                                $this->update_task_sprint($swf_task_details, $al);
                                break;*/
                        }
                    }
                }
                if(!isset($audit_response['NOTES_AUDIT_LOG']['ERROR']) && $audit_response['NOTES_AUDIT_LOG']) {

                    E::log(' INFO: Total '.count($audit_response['NOTES_AUDIT_LOG']).' note audit(s) are found.',
                        ' dateFrom: '. $dates['dateFrom'] .
                        ' dateTo: '. $dates['dateTo'] );

                    foreach ($audit_response['NOTES_AUDIT_LOG'] as $nal) {

                        $note_id = $nal['row_id'];
                        $swf_note = $this->getTaskNoteById($note_id);

                        if (isset($swf_note[0]) && $swf_note[0]) {

                            $task_id = $swf_note[0]['task_id'];
                            $swf_task_details = $this->getTaskDetailsAll($task_id);

                            // Constraint: bot user
                            if ($nal['user_id'] == SWF_USER_BOT_ID) {
                                E::log(' INFO: Constraint : User is a bot.',
                                    ' task_id: '. $task_id .
                                    ' task_type: '. $swf_task_details['TaskDetails']['task_type_id'] .
                                    ' user_id: '. $nal['user_id']
                                );
                                continue;
                            }

                            $constraint = $this->check_constraint($swf_task_details);
                            if (strlen($constraint)) {
                                E::log($constraint, ' task_id: '. $task_id .' note_id: '. $note_id .' note_audit_log: '. $nal['table_field_name']);
                                continue;
                            }

                            switch ($nal['table_field_name']) {

                                case "note" :
                                    $issue_key = '';
                                    list($issue_key, $jira_response) = $this->get_issue_no($task_id, $nal['table_field_name']);
                                    if (strlen($issue_key) > 0) {
                                        $this->update_task_note($nal, $swf_note[0], $issue_key, $nal['table_field_name']);
                                    }
                                    break;
                            }
                        }
                    }
                }
            }
            
            save_date_on_file(SWFTOJIRA_CT_FILENAME);

        }
        catch (JiraException $e)
        {
            E::log('ERROR MSG: Simply workflow To Jira : ', $e->getMessage());
        }

        if (isset($task_events['TaskEvents']) || $audit_logs['status'] == 'S') {
            $time_elapsed_secs = microtime(true) - $start;
            E::log(' ===== Script Execution time: ' .$time_elapsed_secs. ' SWF to JIRA : End =====');
        }

        /*$time_elapsed_secs = microtime(true) - $start;
        echo "<div align='center'>";
        echo "<p>This script took ".$time_elapsed_secs." to execute. </p>";
        echo "</div>";*/

        /*
        // logger->log SAMPLES
        $this->logger->log( LOG_LEVEL, 'Total '.count($task_events['TaskEvents']).' TaskEvents are found.');

        $this->logger->log( LOG_LEVEL, 'Support task is not accepted. ', array(
            'task_id' => $swf_task_details['TaskDetails']['task_id'],
            'task_type_id' => $swf_task_details['TaskDetails']['task_type_id'],
            'event_code' => $task_event['event_code'],
        ));
         */

    }

}