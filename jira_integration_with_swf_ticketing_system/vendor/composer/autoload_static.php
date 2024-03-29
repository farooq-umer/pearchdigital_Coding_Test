<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita05c8b32e346647cc7200f13053f371d
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
        ),
        'J' => 
        array (
            'JiraRestApi\\' => 12,
        ),
        'D' => 
        array (
            'Dotenv\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'JiraRestApi\\' => 
        array (
            0 => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src',
        ),
        'Dotenv\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/phpdotenv/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'J' => 
        array (
            'JsonMapper' => 
            array (
                0 => __DIR__ . '/..' . '/netresearch/jsonmapper/src',
            ),
        ),
    );

    public static $classMap = array (
        'Dotenv\\Dotenv' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Dotenv.php',
        'Dotenv\\Exception\\ExceptionInterface' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Exception/ExceptionInterface.php',
        'Dotenv\\Exception\\InvalidCallbackException' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Exception/InvalidCallbackException.php',
        'Dotenv\\Exception\\InvalidFileException' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Exception/InvalidFileException.php',
        'Dotenv\\Exception\\InvalidPathException' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Exception/InvalidPathException.php',
        'Dotenv\\Exception\\ValidationException' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Exception/ValidationException.php',
        'Dotenv\\Loader' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Loader.php',
        'Dotenv\\Validator' => __DIR__ . '/..' . '/vlucas/phpdotenv/src/Validator.php',
        'JiraRestApi\\Auth\\AuthService' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Auth/AuthService.php',
        'JiraRestApi\\Auth\\AuthSession' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Auth/AuthSession.php',
        'JiraRestApi\\Auth\\CurrentUser' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Auth/CurrentUser.php',
        'JiraRestApi\\Auth\\LoginInfo' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Auth/LoginInfo.php',
        'JiraRestApi\\Auth\\SessionInfo' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Auth/SessionInfo.php',
        'JiraRestApi\\ClassSerialize' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/ClassSerialize.php',
        'JiraRestApi\\Configuration\\AbstractConfiguration' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Configuration/AbstractConfiguration.php',
        'JiraRestApi\\Configuration\\ArrayConfiguration' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Configuration/ArrayConfiguration.php',
        'JiraRestApi\\Configuration\\ConfigurationInterface' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Configuration/ConfigurationInterface.php',
        'JiraRestApi\\Configuration\\DotEnvConfiguration' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Configuration/DotEnvConfiguration.php',
        'JiraRestApi\\Dumper' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Dumper.php',
        'JiraRestApi\\Field\\Field' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Field/Field.php',
        'JiraRestApi\\Field\\FieldService' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Field/FieldService.php',
        'JiraRestApi\\Field\\Schema' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Field/Schema.php',
        'JiraRestApi\\Group\\Group' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Group/Group.php',
        'JiraRestApi\\Group\\GroupSearchResult' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Group/GroupSearchResult.php',
        'JiraRestApi\\Group\\GroupService' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Group/GroupService.php',
        'JiraRestApi\\Group\\GroupUser' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Group/GroupUser.php',
        'JiraRestApi\\IssueLink\\IssueLink' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/IssueLink/IssueLink.php',
        'JiraRestApi\\IssueLink\\IssueLinkService' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/IssueLink/IssueLinkService.php',
        'JiraRestApi\\IssueLink\\IssueLinkType' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/IssueLink/IssueLinkType.php',
        'JiraRestApi\\Issue\\Attachment' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/Attachment.php',
        'JiraRestApi\\Issue\\Comment' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/Comment.php',
        'JiraRestApi\\Issue\\Comments' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/Comments.php',
        'JiraRestApi\\Issue\\Component' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/Component.php',
        'JiraRestApi\\Issue\\Issue' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/Issue.php',
        'JiraRestApi\\Issue\\IssueField' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/IssueField.php',
        'JiraRestApi\\Issue\\IssueSearchResult' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/IssueSearchResult.php',
        'JiraRestApi\\Issue\\IssueService' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/IssueService.php',
        'JiraRestApi\\Issue\\IssueStatus' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/IssueStatus.php',
        'JiraRestApi\\Issue\\IssueType' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/IssueType.php',
        'JiraRestApi\\Issue\\JqlFunction' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/JqlFunction.php',
        'JiraRestApi\\Issue\\JqlQuery' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/JqlQuery.php',
        'JiraRestApi\\Issue\\Notify' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/Notify.php',
        'JiraRestApi\\Issue\\PaginatedWorklog' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/PaginatedWorklog.php',
        'JiraRestApi\\Issue\\Priority' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/Priority.php',
        'JiraRestApi\\Issue\\RemoteIssueLink' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/RemoteIssueLink.php',
        'JiraRestApi\\Issue\\RemoteIssueLinkObject' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/RemoteIssueLinkObject.php',
        'JiraRestApi\\Issue\\Reporter' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/Reporter.php',
        'JiraRestApi\\Issue\\Statuscategory' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/Statuscategory.php',
        'JiraRestApi\\Issue\\TimeTracking' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/TimeTracking.php',
        'JiraRestApi\\Issue\\Transition' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/Transition.php',
        'JiraRestApi\\Issue\\TransitionTo' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/TransitionTo.php',
        'JiraRestApi\\Issue\\Version' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/Version.php',
        'JiraRestApi\\Issue\\Visibility' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/Visibility.php',
        'JiraRestApi\\Issue\\Worklog' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Issue/Worklog.php',
        'JiraRestApi\\JiraClient' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/JiraClient.php',
        'JiraRestApi\\JiraException' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/JiraException.php',
        'JiraRestApi\\JiraRestApiServiceProvider' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/JiraRestApiServiceProvider.php',
        'JiraRestApi\\JsonMapperHelper' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/JsonMapperHelper.php',
        'JiraRestApi\\Project\\Component' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Project/Component.php',
        'JiraRestApi\\Project\\Project' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Project/Project.php',
        'JiraRestApi\\Project\\ProjectService' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Project/ProjectService.php',
        'JiraRestApi\\Project\\ProjectType' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Project/ProjectType.php',
        'JiraRestApi\\User\\User' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/User/User.php',
        'JiraRestApi\\User\\UserService' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/User/UserService.php',
        'JiraRestApi\\Version\\VersionService' => __DIR__ . '/..' . '/lesstif/php-jira-rest-client/src/Version/VersionService.php',
        'JiraToSwf' => __DIR__ . '/../..' . '/classes/class_JiraToSwf.php',
        'JsonMapper' => __DIR__ . '/..' . '/netresearch/jsonmapper/src/JsonMapper.php',
        'JsonMapper_Exception' => __DIR__ . '/..' . '/netresearch/jsonmapper/src/JsonMapper/Exception.php',
        'Monolog\\ErrorHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/ErrorHandler.php',
        'Monolog\\Formatter\\ChromePHPFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/ChromePHPFormatter.php',
        'Monolog\\Formatter\\ElasticaFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/ElasticaFormatter.php',
        'Monolog\\Formatter\\FlowdockFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/FlowdockFormatter.php',
        'Monolog\\Formatter\\FluentdFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/FluentdFormatter.php',
        'Monolog\\Formatter\\FormatterInterface' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/FormatterInterface.php',
        'Monolog\\Formatter\\GelfMessageFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/GelfMessageFormatter.php',
        'Monolog\\Formatter\\HtmlFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/HtmlFormatter.php',
        'Monolog\\Formatter\\JsonFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/JsonFormatter.php',
        'Monolog\\Formatter\\LineFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/LineFormatter.php',
        'Monolog\\Formatter\\LogglyFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/LogglyFormatter.php',
        'Monolog\\Formatter\\LogstashFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/LogstashFormatter.php',
        'Monolog\\Formatter\\MongoDBFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/MongoDBFormatter.php',
        'Monolog\\Formatter\\NormalizerFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/NormalizerFormatter.php',
        'Monolog\\Formatter\\ScalarFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/ScalarFormatter.php',
        'Monolog\\Formatter\\WildfireFormatter' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Formatter/WildfireFormatter.php',
        'Monolog\\Handler\\AbstractHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/AbstractHandler.php',
        'Monolog\\Handler\\AbstractProcessingHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/AbstractProcessingHandler.php',
        'Monolog\\Handler\\AbstractSyslogHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/AbstractSyslogHandler.php',
        'Monolog\\Handler\\AmqpHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/AmqpHandler.php',
        'Monolog\\Handler\\BrowserConsoleHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/BrowserConsoleHandler.php',
        'Monolog\\Handler\\BufferHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/BufferHandler.php',
        'Monolog\\Handler\\ChromePHPHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/ChromePHPHandler.php',
        'Monolog\\Handler\\CouchDBHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/CouchDBHandler.php',
        'Monolog\\Handler\\CubeHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/CubeHandler.php',
        'Monolog\\Handler\\Curl\\Util' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/Curl/Util.php',
        'Monolog\\Handler\\DeduplicationHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/DeduplicationHandler.php',
        'Monolog\\Handler\\DoctrineCouchDBHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/DoctrineCouchDBHandler.php',
        'Monolog\\Handler\\DynamoDbHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/DynamoDbHandler.php',
        'Monolog\\Handler\\ElasticSearchHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/ElasticSearchHandler.php',
        'Monolog\\Handler\\ErrorLogHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/ErrorLogHandler.php',
        'Monolog\\Handler\\FilterHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FilterHandler.php',
        'Monolog\\Handler\\FingersCrossedHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FingersCrossedHandler.php',
        'Monolog\\Handler\\FingersCrossed\\ActivationStrategyInterface' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FingersCrossed/ActivationStrategyInterface.php',
        'Monolog\\Handler\\FingersCrossed\\ChannelLevelActivationStrategy' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FingersCrossed/ChannelLevelActivationStrategy.php',
        'Monolog\\Handler\\FingersCrossed\\ErrorLevelActivationStrategy' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FingersCrossed/ErrorLevelActivationStrategy.php',
        'Monolog\\Handler\\FirePHPHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FirePHPHandler.php',
        'Monolog\\Handler\\FleepHookHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FleepHookHandler.php',
        'Monolog\\Handler\\FlowdockHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/FlowdockHandler.php',
        'Monolog\\Handler\\GelfHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/GelfHandler.php',
        'Monolog\\Handler\\GroupHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/GroupHandler.php',
        'Monolog\\Handler\\HandlerInterface' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/HandlerInterface.php',
        'Monolog\\Handler\\HandlerWrapper' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/HandlerWrapper.php',
        'Monolog\\Handler\\HipChatHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/HipChatHandler.php',
        'Monolog\\Handler\\IFTTTHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/IFTTTHandler.php',
        'Monolog\\Handler\\LogEntriesHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/LogEntriesHandler.php',
        'Monolog\\Handler\\LogglyHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/LogglyHandler.php',
        'Monolog\\Handler\\MailHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/MailHandler.php',
        'Monolog\\Handler\\MandrillHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/MandrillHandler.php',
        'Monolog\\Handler\\MissingExtensionException' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/MissingExtensionException.php',
        'Monolog\\Handler\\MongoDBHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/MongoDBHandler.php',
        'Monolog\\Handler\\NativeMailerHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/NativeMailerHandler.php',
        'Monolog\\Handler\\NewRelicHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/NewRelicHandler.php',
        'Monolog\\Handler\\NullHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/NullHandler.php',
        'Monolog\\Handler\\PHPConsoleHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/PHPConsoleHandler.php',
        'Monolog\\Handler\\PsrHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/PsrHandler.php',
        'Monolog\\Handler\\PushoverHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/PushoverHandler.php',
        'Monolog\\Handler\\RavenHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/RavenHandler.php',
        'Monolog\\Handler\\RedisHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/RedisHandler.php',
        'Monolog\\Handler\\RollbarHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/RollbarHandler.php',
        'Monolog\\Handler\\RotatingFileHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/RotatingFileHandler.php',
        'Monolog\\Handler\\SamplingHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SamplingHandler.php',
        'Monolog\\Handler\\SlackHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SlackHandler.php',
        'Monolog\\Handler\\SlackWebhookHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SlackWebhookHandler.php',
        'Monolog\\Handler\\Slack\\SlackRecord' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/Slack/SlackRecord.php',
        'Monolog\\Handler\\SlackbotHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SlackbotHandler.php',
        'Monolog\\Handler\\SocketHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SocketHandler.php',
        'Monolog\\Handler\\StreamHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/StreamHandler.php',
        'Monolog\\Handler\\SwiftMailerHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SwiftMailerHandler.php',
        'Monolog\\Handler\\SyslogHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SyslogHandler.php',
        'Monolog\\Handler\\SyslogUdpHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SyslogUdpHandler.php',
        'Monolog\\Handler\\SyslogUdp\\UdpSocket' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/SyslogUdp/UdpSocket.php',
        'Monolog\\Handler\\TestHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/TestHandler.php',
        'Monolog\\Handler\\WhatFailureGroupHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/WhatFailureGroupHandler.php',
        'Monolog\\Handler\\ZendMonitorHandler' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Handler/ZendMonitorHandler.php',
        'Monolog\\Logger' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Logger.php',
        'Monolog\\Processor\\GitProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/GitProcessor.php',
        'Monolog\\Processor\\IntrospectionProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/IntrospectionProcessor.php',
        'Monolog\\Processor\\MemoryPeakUsageProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/MemoryPeakUsageProcessor.php',
        'Monolog\\Processor\\MemoryProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/MemoryProcessor.php',
        'Monolog\\Processor\\MemoryUsageProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/MemoryUsageProcessor.php',
        'Monolog\\Processor\\MercurialProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/MercurialProcessor.php',
        'Monolog\\Processor\\ProcessIdProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/ProcessIdProcessor.php',
        'Monolog\\Processor\\PsrLogMessageProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/PsrLogMessageProcessor.php',
        'Monolog\\Processor\\TagProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/TagProcessor.php',
        'Monolog\\Processor\\UidProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/UidProcessor.php',
        'Monolog\\Processor\\WebProcessor' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Processor/WebProcessor.php',
        'Monolog\\Registry' => __DIR__ . '/..' . '/monolog/monolog/src/Monolog/Registry.php',
        'Psr\\Log\\AbstractLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/AbstractLogger.php',
        'Psr\\Log\\InvalidArgumentException' => __DIR__ . '/..' . '/psr/log/Psr/Log/InvalidArgumentException.php',
        'Psr\\Log\\LogLevel' => __DIR__ . '/..' . '/psr/log/Psr/Log/LogLevel.php',
        'Psr\\Log\\LoggerAwareInterface' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerAwareInterface.php',
        'Psr\\Log\\LoggerAwareTrait' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerAwareTrait.php',
        'Psr\\Log\\LoggerInterface' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerInterface.php',
        'Psr\\Log\\LoggerTrait' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerTrait.php',
        'Psr\\Log\\NullLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/NullLogger.php',
        'Psr\\Log\\Test\\DummyTest' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/LoggerInterfaceTest.php',
        'Psr\\Log\\Test\\LoggerInterfaceTest' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/LoggerInterfaceTest.php',
        'SwfToJira' => __DIR__ . '/../..' . '/classes/class_SwfToJira.php',
        'Sync' => __DIR__ . '/../..' . '/classes/class_Sync.php',
        'WebServices' => __DIR__ . '/../..' . '/classes/class_WebServices.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita05c8b32e346647cc7200f13053f371d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita05c8b32e346647cc7200f13053f371d::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInita05c8b32e346647cc7200f13053f371d::$prefixesPsr0;
            $loader->classMap = ComposerStaticInita05c8b32e346647cc7200f13053f371d::$classMap;

        }, null, ClassLoader::class);
    }
}
