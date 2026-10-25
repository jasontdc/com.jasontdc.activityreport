<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array (
  0 => 
  array (
    'name' => 'CRM_Activityreport_Form_Report_ActivityTargetCount',
    'entity' => 'ReportTemplate',
    'params' => 
    array (
      'version' => 3,
      'label' => 'Activity Count with Target Contact',
      'description' => 'Provides counts of activities, and can be grouped by the activity target contact and custom fields.',
      'class_name' => 'CRM_Activityreport_Form_Report_ActivityTargetCount',
      'report_url' => 'report/activitytargetcount',
      'component' => '',
    ),
  ),
);