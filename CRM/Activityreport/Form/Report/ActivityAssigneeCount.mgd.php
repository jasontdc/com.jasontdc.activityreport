<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array (
  0 => 
  array (
    'name' => 'CRM_Activityreport_Form_Report_ActivityAssigneeCount',
    'entity' => 'ReportTemplate',
    'params' => 
    array (
      'version' => 3,
      'label' => 'Activity Count with Assigned Contacts',
      'description' => 'Provides counts of activities, and can be grouped by the assigned contacts for the activity and their custom fields.',
      'class_name' => 'CRM_Activityreport_Form_Report_ActivityAssigneeCount',
      'report_url' => 'report/activityassigneecount',
      'component' => '',
    ),
  ),
);