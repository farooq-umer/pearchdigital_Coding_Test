<?php

//use Monolog\Handler\StreamHandler;
//use Monolog\Logger as Logger;

class WebServices
{
    protected $connection;
    public $logger;
    /*
     * ******************************
     * Jira Integrations Webservices
     * ******************************
     */

    //Users

    public function __construct()
    {
        //$this->logger = new Logger('WebServices');
        //$this->logger->pushHandler(new StreamHandler(LOCAL_LOGS_DIR.'/'.JIRA_LOG_FILENAME, Logger::INFO));
    }

    public function getUserDetails($userId)
    {
        $url = USER_DETAIL_URL;
        $json = json_encode(array('userId' => $userId));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);
        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Admin User Details, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }

            return $response;
        }
    }

    //20/04/2018
    public function GetUserDetailsByEmail($email)
    {
        $url = USER_DETAIL_BY_EMAIL_URL;

        $json = json_encode(array('userEmail' => $email));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);
        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Admin User Details, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }

            return $response['response']['response'];
        }
    }

    //Tasks
    public function getTaskDetails($taskId)
    {
        //echo $taskId; die ;
        $url = TASK_DETAILS_URL;

        $json = json_encode(array('taskId' => $taskId));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Task Details, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }

            return $response['response']['response'];
        }
    }

    public function getTaskNotes($taskId)
    {
        //echo $taskId; die ;
        $url = TASK_NOTES_URL;

        $json = json_encode(array('taskId' => $taskId));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Task Notes, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }
            if ($response['status'] == 'S') {
                //return $response;
                return $response['response']['response'];
            }
        }
    }

    public function getTaskStatusHistory($taskId)
    {
        //echo $taskId; die ;
        $url = TASK_STATUS_HISTORY_URL;

        $json = json_encode(array('taskId' => $taskId));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Task Status History, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }
            else {
                return $response['response']['response'];
            }
        }
    }

    public function getTasksStatusHistoryByDateRange($dateFrom, $dateTo)
    {
        $url = TASK_STATUS_HISTORY_BY_DATE;
        // $taskId
        $json = json_encode(array('dateFrom' => $dateFrom, 'dateTo' => $dateTo));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Task Status History for the date range, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }

            return $response;
        }
    }

    public function getTaskEventsByDateRange($dateFrom, $dateTo)
    {
        $url = TASK_EVENTS_BY_DATE_RANG_URL;
        // $taskId
        $json = json_encode(array('dateFrom' => $dateFrom, 'dateTo' => $dateTo));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Task Events for the date range, Params Sent: ';
                if(JIRA_DEV) {
                    E::log($errorMsg, array('response'=>$response['response']['errorMessage'])); // 'params'=>$params
                }
            }
            else {
                return $response['response']['response'];
            }
        }
    }

    public function getTaskAttachments($taskId)
    {
        //echo $taskId; die ;
        $url = TASK_ATTACHMENTS_URL;

        $json = json_encode(array('taskId' => $taskId));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Task Attachments, Params Sent: ';
                E::log($errorMsg, array('response'=>$response['response']['errorMessage'],'params'=>$params));
            }

            return $response['response']['response'];
        }
    }

    //25/07/2018
    public function getTaskAttachmentsByDateRange($taskId, $dateFrom, $dateTo)
    {
        //echo $taskId; die ;
        $url = TASK_ATTACHMENTS_BY_DATE_RANGE_URL;

        $json = json_encode(array('taskId' => $taskId,'dateFrom' => $dateFrom,'dateTo' => $dateTo));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Task Attachments for the date range, Params Sent: ';
                E::log($errorMsg, array('response'=>$response['response']['errorMessage'])); // 'params'=>$params
                return array();
            }

            return $response['response']['response'];
        }
    }

    public function addTaskNote($addedByUserId,$taskId,$note,$accessUserGroupIds=null)
    {
        $url = ADD_TASK_NOTE_URL;
        /*
        you can send comma separated values in $accessUserGroupIds like "1,2,5"
        User Groups:
        1;"Admin"
        2;"Developers"
        3;"Customers"
        4;"Project Managers"
        5;"Guests"
        For developers only use "2" (string) as $accessUserGroupIds
        */

    $json = json_encode(array('taskNoteDetails' => array(
        'addedByUserId' => $addedByUserId,
        'taskId' => $taskId,
        'note' => $note,
        'accessUserGroupIds' => $accessUserGroupIds
    )));

    $params = 'params=' . urlencode($json);
    //print_r(urldecode($params));
    $response = $this->curlRequest($url, $params);
        if ($response) {
            $response = json_decode($response, true);

            if ($response['status'] == 'E')
             {
                 $params = urldecode($params);
                 $errorMsg = 'FAILED to add Task Note, Params Sent: ';
                 E::log($errorMsg, array('response'=>$response,'params'=>$params));
             }

             return $response;
        }
    }

    public function updateTaskStatus($taskId, $statusId, $updatedByUserId)
    {
        $url = UPDATE_TASK_STATUS_URL;

        $json = json_encode(array('taskDetails' => array(
            'taskId' => $taskId,
            'statusId' => $statusId,
            'updatedByUserId' => $updatedByUserId
        )));

        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to Update Task Status, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }

            return $response;
        }
    }

    public function getTaskNoteById($taskNoteId)
    {
        $url = TASK_NOTE_BY_ID_URL;
        // 'taskId'=>$taskId
        $json = json_encode(array('taskNoteId'=>$taskNoteId));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Task Note, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
                return $response;
            }

            return $response['response']['response'];
        }
    }

    //Project
    public function getAllProjects()
    {
        $url = ALL_PROJECTS_URL;

        $response = $this->curlRequest($url);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $errorMsg = 'FAILED to get Projects';
                E::log($errorMsg, $response);
                return false;
            }

            return $response['response']['response'];
        }
    }

    public function getProjectDetailsByCode($projectCode)
    {
        //echo $taskId; die ;
        $url = PROJECT_DETAILS_BY_CODE_URL;

        $json = json_encode(array('projectCode' => $projectCode));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Project Details By Code, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }

            return $response;
        }
    }

    public function getProjectDetailsById($projectId)
    {
        //echo $taskId; die ;
        $url = PROJECT_DETAILS_BY_ID_URL;

        $json = json_encode(array('projectId' => $projectId));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Project Details By Id, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }

            return $response;
        }
    }
    //Issues
    public function updateJobEvent($jobEventId)
    {

        $url = UPDATE_JOB_EVENTS_URL;
        $json = json_encode(array('JobEventId' => $jobEventId));
        $params = 'params=' . urlencode($json);
        //echo '<pre>';
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to update Job Event, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }

            return $response;
        }

    }

    public function getJobEventsDateRange($dateFrom = '', $dateTo = '')
    {

        $url = JOB_EVENTS_DATE_RANGE_URL;

        $json = json_encode(array('DateFrom' => $dateFrom, 'DateTo' => $dateTo));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);
            //print_r($response);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Events by Date Range, Params Sent: ';
                E::log($errorMsg, array('response'=>$response['response']['errorMessage'])); // 'params'=>$params
            }

            return $response;
        }

    }

    //29/03/2018
    public function getTaskCategories()
    {
        $url = TASK_CATEGORIES_URL;
        $categories = array();

        $response = $this->curlRequest($url);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $errorMsg = 'FAILED to get Task Categories, Params Sent: ';
                E::log($errorMsg, $response);
            }

            foreach ($response['response']['response'] as $k){
                $categories[$k['task_category_id']] = $k['task_category_name'];
            }

            return $categories;
        }
    }

    public function getCategoryById($categoryId)
    {
        $url = CATEGORY_BY_ID_URL;

        $json = json_encode(array('categoryId' => $categoryId));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Category by Id, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }

            return $response;
        }
    }

    public function getTaskStatuses()
    {
        $url = TASK_STATUSES_URL;

        $response = $this->curlRequest($url);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $errorMsg = 'FAILED to get Task Statuses';
                E::log($errorMsg, $response);
            }

            return $response;
        }
    }

    public function getSprints()
    {
        $url = GET_ALL_SPRINTS_URL;

        $response = $this->curlRequest($url);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $errorMsg = 'FAILED to get Sprints';
                E::log($errorMsg, $response);
            }

            return $response;
        }
    }

    public function getSprintById($sprintId)
    {
        $url = GET_SPRINT_BY_ID_URL;

        $json = json_encode(array('sprintId' => $sprintId));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Sprint by Id, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }

            return $response;
        }
    }

    public function getStatusById($statusId)
    {
        $url = STATUS_BY_ID_URL;

        $json = json_encode(array('statusId' => $statusId));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Status by Id, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }

            return $response;
        }
    }

    public function getStatusByName($statusName)
    {
        $url = STATUS_BY_NAME_URL;

        $json = json_encode(array('statusName' => $statusName));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Status by Name, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }

            return $response;
        }
    }

    public function getTaskPriorities()
    {
        $url = TASK_PRIORITIES_URL;

        $response = $this->curlRequest($url);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $errorMsg = 'FAILED to get Task Priorities';
                E::log($errorMsg, $response);
            }

            return $response['response']['response'];
        }
    }

    public function getTaskTypes()
    {
        $url = TASK_TYPES_URL;
        $response = $this->curlRequest($url);


        if ($response) {
            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $errorMsg = 'FAILED to get Task Types';
                E::log($errorMsg, $response);
            }

            return $response['response']['response'];
        }
    }

    public function getTaskAuditLog($tableId, $rowId)
    {
        $url = TASK_AUDIT_LOG_URL;
        // any table's row_id can be sent here. task Id , note Id ...
        $json = json_encode(array('tableId'=>$tableId,'rowId'=>$rowId));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Task Audit Log, Params Sent: ';
                E::log($errorMsg, array('response'=>$response,'params'=>$params));
            }

            return $response;
        }
    }

    public function getTaskAuditLogByDateRange($tableId = '10073', $dateFrom, $dateTo)
    {
        $url = TASK_AUDIT_LOG_BY_DATE_RANG_URL;

        $json = json_encode(array('tableId'=>$tableId,'dateFrom'=>$dateFrom,'dateTo'=>$dateTo));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Audit Log By DateRange, Params Sent: ';
                if(JIRA_DEV) {
                    E::log($errorMsg, array('response'=>$response['response']['errorMessage'])); // 'params'=>$params
                }
            }
            else {
                return $response;
            }
        }
    }
    
    public function getTaskAuditLogAllByDateRange($dateFrom, $dateTo)
    {
        $url = TASK_AUDIT_LOG_ALL_BY_DATE_RANG_URL;

        $json = json_encode(array('dateFrom'=>$dateFrom,'dateTo'=>$dateTo));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Audit Log All By DateRange, Params Sent: ';
                if(JIRA_DEV) {
                    E::log($errorMsg, array('response'=>$response['response']['errorMessage'])); // 'params'=>$params
                }
            }
            else {
                return $response;
            }
        }
    }

    public function getTaskDetailsAll($taskId)
    {
        //echo $taskId; die ;
        $url = TASK_DETAILS_ALL_URL;

        $json = json_encode(array('taskId' => $taskId));
        $params = 'params=' . urlencode($json);
        //print_r(urldecode($params));

        $response = $this->curlRequest($url, $params);

        if ($response) {

            $response = json_decode($response, true);

            if ($response['status'] == 'E') {
                $params = urldecode($params);
                $errorMsg = 'FAILED to get Task Details All, Params Sent: ';
                E::log($errorMsg, array('response'=>$response['response']['response']['errorMessage'],'params'=>$params));
            }

            return $response['response']['response'];
        }
    }

    /*
    * *************************************
    * // END Jira Integrations Webservices
    * *************************************
    */

    protected function curlRequest($url, $params=null)
    {
        // initialise curl
        $ch = curl_init();
        /* set options */
        // URL to sent the request to.
        curl_setopt($ch, CURLOPT_URL, $url);
        // An array of HTTP header fields to set, in the format array('Content-type: text/plain', 'Content-length: 100')
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(CONTENT_TYPE, CLIENT_ID, CLIENT_SECRET));
        // TRUE to do a regular HTTP POST. This POST is the normal application/x-www-form-urlencoded kind.
        curl_setopt($ch, CURLOPT_POST, true);
        // Return instead of outputting directly.
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // The full data to post in a HTTP "POST" operation.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        // FALSE to stop cURL from verifying the peer's certificate, (TRUE by default)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // HTTP response code greater than 400 to cause an error.
        //curl_setopt($ch, CURLOPT_FAILONERROR, true);
        // execute curl
        $response = curl_exec($ch);
        $err = curl_error($ch);

        // closing curl handle
        curl_close($ch);

        if ($err) {
            $params = urldecode($params);
            $errorMsg = 'cURL Error #: '. $err .' Params Sent: ';
            E::log($errorMsg, array('url'=>$url,'response'=>$response,'params'=>$params));
        }
        else {
            return $response;
        }
    }
}
