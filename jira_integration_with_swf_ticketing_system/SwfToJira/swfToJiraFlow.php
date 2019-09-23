<?php

require_once (dirname(__DIR__).'/config.php');

$jira = new SwfToJira();

$task_id = '004338';
$jira->TESTrunSwfToJiraFlow($task_id);

//$jira->runSwfToJiraFlow();
 