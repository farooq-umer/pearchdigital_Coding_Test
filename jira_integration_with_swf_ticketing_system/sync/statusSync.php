<?php
/**
 * Status Sync from SWF to Jira
 */
//require_once (dirname(__DIR__).'/jira_tab_config.php');
require_once (dirname(__DIR__).'/config.php');

$sync = new Sync();

//$jql = 'project = '.JIRA_PROJECT_CODE.' AND  cf[10043] ~ '.trim('4520');
$jql = 'project = "SUP" AND "Ticket Number" is not EMPTY AND status not in ("Closed") order by status';

$startAt = 0;
$maxResult = 100;

$sync->syncStatus($jql, $startAt, $maxResult);

