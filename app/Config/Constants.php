<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code


/**
 * Custom defines
 */
//ROLES
defined('ID_ROL_SUPER_ADMIN')   or define('ID_ROL_SUPER_ADMIN', 99);
defined('ID_ROL_MANAGER')   or define('ID_ROL_MANAGER', 2);
defined('ID_ROL_ACCOUNTING')   or define('ID_ROL_ACCOUNTING', 3);
defined('ID_ROL_SAFETY')   or define('ID_ROL_SAFETY', 4);
defined('ID_ROL_WORKORDER')   or define('ID_ROL_WORKORDER', 5);
defined('ID_ROL_SUPERVISOR')   or define('ID_ROL_SUPERVISOR', 6);
defined('ID_ROL_BASIC')   or define('ID_ROL_BASIC', 7);
defined('ID_ROL_ENGINEER')   or define('ID_ROL_ENGINEER', 8);
defined('ID_ROL_MECHANIC')   or define('ID_ROL_MECHANIC', 9);
defined('ID_ROL_ACCOUNTING_ASSISTANT')   or define('ID_ROL_ACCOUNTING_ASSISTANT', 10);
//NOTIFICATIONS
defined('ID_NOTIFICATION_CERTIFICATION')   or define('ID_NOTIFICATION_CERTIFICATION', 1);
defined('ID_NOTIFICATION_FLHA')   or define('ID_NOTIFICATION_FLHA', 2);
defined('ID_NOTIFICATION_TOOL_BOX')   or define('ID_NOTIFICATION_TOOL_BOX', 3);
defined('ID_NOTIFICATION_PLANNING')   or define('ID_NOTIFICATION_PLANNING', 4);
defined('ID_NOTIFICATION_MAINTENANCE')   or define('ID_NOTIFICATION_MAINTENANCE', 5);
defined('ID_NOTIFICATION_PAYROLL')   or define('ID_NOTIFICATION_PAYROLL', 6);
defined('ID_NOTIFICATION_TIMESHEET')   or define('ID_NOTIFICATION_TIMESHEET', 7);
defined('ID_NOTIFICATION_DAYOFF')   or define('ID_NOTIFICATION_DAYOFF', 8);
defined('ID_NOTIFICATION_HAULING')   or define('ID_NOTIFICATION_HAULING', 9);
defined('ID_NOTIFICATION_INCIDENT')   or define('ID_NOTIFICATION_INCIDENT', 10);
defined('ID_NOTIFICATION_WORKORDER')   or define('ID_NOTIFICATION_WORKORDER', 11);
defined('ID_NOTIFICATION_INSPECTIONS')   or define('ID_NOTIFICATION_INSPECTIONS', 12);
defined('ID_NOTIFICATION_WORKORDER_CHANGE')   or define('ID_NOTIFICATION_WORKORDER_CHANGE', 13);
defined('ID_NOTIFICATION_NEW_JOB')   or define('ID_NOTIFICATION_NEW_JOB', 14);
defined('ID_NOTIFICATION_HOURS_PAYROLL_CHECK')   or define('ID_NOTIFICATION_HOURS_PAYROLL_CHECK', 15);
//MODULES
defined('ID_MODULE_SERVICE_ORDER')   or define('ID_MODULE_SERVICE_ORDER', 1);
defined('DASHBOARD_MAINTENANCE_LIST')   or define('DASHBOARD_MAINTENANCE_LIST', 2);
defined('INSPECTION_LIST_BY_EQUIPMENT_ID')   or define('INSPECTION_LIST_BY_EQUIPMENT_ID', 3);
defined('ID_MODULE_FIRE_WATCH_CHECK')   or define('ID_MODULE_FIRE_WATCH_CHECK', 4);
//WO STATUS
defined('ON_FIELD')   or define('ON_FIELD', 0);
defined('IN_PROGRESS')   or define('IN_PROGRESS', 1);
defined('REVISED')   or define('REVISED', 2);
defined('SEND_TO_CLIENT')   or define('SEND_TO_CLIENT', 3);
defined('CLOSED')   or define('CLOSED', 4);
defined('ACCOUNTING')   or define('ACCOUNTING', 5);