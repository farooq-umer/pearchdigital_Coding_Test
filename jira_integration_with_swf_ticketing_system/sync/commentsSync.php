<?php

//require_once (dirname(__DIR__).'/jira_tab_config.php');
require_once (dirname(__DIR__).'/config.php');

$sync = new Sync();
$task_id = '4338';

// syncComments function needs to be COMPLETD and tested. IT IS NOT COMPLETE YET, 

$sync->syncComments($task_id);