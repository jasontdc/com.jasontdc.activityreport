<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array (
  0 => 
  array (
    'name' => 'CRM_Activityreport_Form_Report_ActivityContactSummary',
    'entity' => 'ReportTemplate',
    'params' => 
    array (
      'version' => 3,
      'label' => 'Activity Contact Summary',
      'description' => 'Provides a list of constituent activity details, including basic and custom fields for target contacts.',
      'class_name' => 'CRM_Activityreport_Form_Report_ActivityContactSummary',
      'report_url' => 'report/activitycontactsummary',
      'component' => '',
    ),
  ),
);