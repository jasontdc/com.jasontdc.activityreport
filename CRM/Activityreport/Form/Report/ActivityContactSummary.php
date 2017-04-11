<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.7                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2016                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2016
 */
class CRM_Activityreport_Form_Report_ActivityContactSummary extends CRM_Report_Form {

  protected $_emailField = FALSE;
  protected $_phoneField = FALSE;

  protected $_customGroupExtends = array(
    'Activity',
    'Contact',
    'Individual',
    'Household',
    'Organization',
  );

  /**
   * This report has not been optimised for group filtering.
   *
   * The functionality for group filtering has been improved but not
   * all reports have been adjusted to take care of it. This report has not
   * and will run an inefficient query until fixed.
   *
   * CRM-19170
   *
   * @var bool
   */
  protected $groupFilterNotOptimised = TRUE;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->_columns = array(
      'civicrm_activity' => array(
        'dao' => 'CRM_Activity_DAO_Activity',
        'fields' => array(
          'id' => array(
            'no_display' => TRUE,
            'title' => ts('Activity ID'),
            'required' => TRUE,
          ),
          'unique_contact_id' => array(
            'dbAlias' => 'activity_civireport.id',       
            'title' => ts('Unique Activities (for Group By)'),
            'statistics' => array('count_distinct' => ts('Unique Activities')),
          ),
          'activity_type_id' => array(
            'title' => ts('Activity Type'),
            'required' => TRUE,
            'type' => CRM_Utils_Type::T_STRING,
          ),
          'activity_subject' => array(
            'title' => ts('Subject'),
            'default' => TRUE,
          ),
          'activity_date_time' => array(
            'title' => ts('Activity Date'),
            'required' => TRUE,
          ),
          'status_id' => array(
            'title' => ts('Activity Status'),
            'default' => TRUE,
            'type' => CRM_Utils_Type::T_STRING,
          ),
          'duration' => array(
            'title' => ts('Duration'),
            'type' => CRM_Utils_Type::T_INT,
          ),
          'details' => array(
            'title' => ts('Activity Details'),
          ),
        ),
        'filters' => array(
          'activity_date_time' => array(
            'operatorType' => CRM_Report_Form::OP_DATE,
          ),
          'activity_subject' => array('title' => ts('Activity Subject')),
          'activity_type_id' => array(
            'title' => ts('Activity Type'),
            'default' => 0,
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Core_PseudoConstant::activityType(TRUE, TRUE, FALSE, 'label', TRUE),
          ),
          'status_id' => array(
            'title' => ts('Activity Status'),
            'type' => CRM_Utils_Type::T_STRING,
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Core_PseudoConstant::activityStatus(),
          ),
          'priority_id' => array(
            'title' => ts('Priority'),
            'type' => CRM_Utils_Type::T_INT,
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Core_PseudoConstant::get('CRM_Activity_DAO_Activity', 'priority_id'),
          ),
        ),
        'group_bys' => array(
          'activity_date_time' => array(
            'title' => ts('Activity Date'),
            'frequency' => TRUE,
          ),
          'activity_type_id' => array(
            'title' => ts('Activity Type'),
            'default' => FALSE,
          ),
          'status_id' => array(
            'title' => ts('Activity Status'),
            'default' => FALSE,
          ),
        ),
        'order_bys' => array(
          'activity_date_time' => array(
            'title' => ts('Activity Date'),
          ),
          'activity_type_id' => array(
            'title' => ts('Activity Type'),
          ),
        ),
        'grouping' => 'activity-fields',
        'alias' => 'activity',
      ),
      'civicrm_contact' => array(
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' => array_merge(
          $this->getBasicContactFields(),
          array(
            'modified_date' => array(
              'title' => ts('Modified Date'),
              'default' => FALSE,
            ),
          ),
          array(
            'unique_contact_id' => array(
              'dbAlias' => 'contact_civireport.id',       
              'title' => ts('Unique Contacts (for Group By)'),
              'statistics' => array('count_distinct' => ts('Unique Contacts')),
            ),
          )
        ),
        'filters' => $this->getBasicContactFilters(),
        'grouping' => 'contact-fields',
        'order_bys' => array(
          'contact_id' => array(
            'name' => 'id',
            'title' => ts('Contact ID'),
          ),
          'sort_name' => array(
            'title' => ts('Last Name, First Name'),
            'default' => '1',
            'default_weight' => '0',
            'default_order' => 'ASC',
          ),
          'first_name' => array(
            'name' => 'first_name',
            'title' => ts('First Name'),
          ),
          'last_name' => array(
            'name' => 'last_name',
            'title' => ts('Last Name'),
          ),
          'gender_id' => array(
            'name' => 'gender_id',
            'title' => ts('Gender'),
          ),
          'birth_date' => array(
            'name' => 'birth_date',
            'title' => ts('Birth Date'),
          ),
          'contact_type' => array(
            'title' => ts('Contact Type'),
          ),
          'contact_sub_type' => array(
            'title' => ts('Contact Subtype'),
          ),
        ),
      ),
      'civicrm_email' => array(
        'dao' => 'CRM_Core_DAO_Email',
        'fields' => array(
          'email' => array(
            'title' => ts('Email'),
          ),
        ),
        'order_bys' => array(
          'email' => array(
            'title' => ts('Email'),
          ),
        ),
        'grouping' => 'contact-fields',
      ),
      'civicrm_phone' => array(
        'dao' => 'CRM_Core_DAO_Phone',
        'fields' => array(
          'phone' => NULL,
          'phone_ext' => array(
            'title' => ts('Phone Extension'),
          ),
        ),
        'grouping' => 'contact-fields',
      ),
    ) + $this->getAddressColumns(array('group_by' => TRUE));
    $this->_groupFilter = TRUE;
    parent::__construct();
  }

  /**
   * Generate from clause.
   *
   * @param bool|FALSE $durationMode
   */
  public function from($durationMode = FALSE) {
    $activityContacts = CRM_Core_OptionGroup::values('activity_contacts', FALSE, FALSE, FALSE, NULL, 'name');
    $targetID = CRM_Utils_Array::key('Activity Targets', $activityContacts);

    if (!$durationMode) {
      $this->_from = "
          FROM civicrm_activity {$this->_aliases['civicrm_activity']}

               LEFT JOIN civicrm_activity_contact target_activity
                      ON {$this->_aliases['civicrm_activity']}.id = target_activity.activity_id AND
                         target_activity.record_type_id = {$targetID}
               LEFT JOIN civicrm_contact contact_civireport
                      ON target_activity.contact_id = contact_civireport.id
               {$this->_aclFrom}
               LEFT JOIN civicrm_option_value
                      ON ( {$this->_aliases['civicrm_activity']}.activity_type_id = civicrm_option_value.value )
               LEFT JOIN civicrm_option_group
                      ON civicrm_option_group.id = civicrm_option_value.option_group_id
               LEFT JOIN civicrm_case_activity
                      ON civicrm_case_activity.activity_id = {$this->_aliases['civicrm_activity']}.id
               LEFT JOIN civicrm_case
                      ON civicrm_case_activity.case_id = civicrm_case.id
               LEFT JOIN civicrm_case_contact
                      ON civicrm_case_contact.case_id = civicrm_case.id ";

      if ($this->_emailField) {
        $this->_from .= "
              LEFT JOIN civicrm_email  {$this->_aliases['civicrm_email']}
                     ON {$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_email']}.contact_id AND
                       {$this->_aliases['civicrm_email']}.is_primary = 1 ";
      }

      if ($this->_phoneField) {
        $this->_from .= "
              LEFT JOIN civicrm_phone  {$this->_aliases['civicrm_phone']}
                     ON {$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_phone']}.contact_id AND
                       {$this->_aliases['civicrm_phone']}.is_primary = 1 ";
      }

      if ($this->isTableSelected('civicrm_country') ||
        $this->isTableSelected('civicrm_address')
      ) {
        $this->_from .= "
              LEFT JOIN civicrm_address {$this->_aliases['civicrm_address']}
                    ON ({$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_address']}.contact_id AND
                        {$this->_aliases['civicrm_address']}.is_primary = 1 ) ";
      }

      if ($this->isTableSelected('civicrm_country')) {
        $this->_from .= "
              LEFT JOIN civicrm_country {$this->_aliases['civicrm_country']}
                    ON {$this->_aliases['civicrm_address']}.country_id = {$this->_aliases['civicrm_country']}.id AND
                        {$this->_aliases['civicrm_address']}.is_primary = 1 ";
      }

    }
    else {
      $this->_from = "
      FROM civicrm_activity {$this->_aliases['civicrm_activity']}
              LEFT JOIN civicrm_activity_contact target_activity
                     ON {$this->_aliases['civicrm_activity']}.id = target_activity.activity_id AND
                        target_activity.record_type_id = {$targetID}
              LEFT JOIN civicrm_contact contact_civireport
                     ON target_activity.contact_id = contact_civireport.id
              {$this->_aclFrom}";
    }
  }

  /**
   * Generate where clause.
   *
   * @param bool|FALSE $durationMode
   */
  public function where($durationMode = FALSE) {
    $optionGroupClause = '';
    if (!$durationMode) {
      $optionGroupClause = 'civicrm_option_group.name = "activity_type" AND ';
    }
    $this->_where = " WHERE {$optionGroupClause}
                            {$this->_aliases['civicrm_activity']}.is_test = 0 AND
                            {$this->_aliases['civicrm_activity']}.is_deleted = 0 AND
                            {$this->_aliases['civicrm_activity']}.is_current_revision = 1";

    $clauses = array();
    foreach ($this->_columns as $tableName => $table) {
      if (array_key_exists('filters', $table)) {

        foreach ($table['filters'] as $fieldName => $field) {
          $clause = NULL;
          if (CRM_Utils_Array::value('type', $field) & CRM_Utils_Type::T_DATE) {
            $relative = CRM_Utils_Array::value("{$fieldName}_relative", $this->_params);
            $from = CRM_Utils_Array::value("{$fieldName}_from", $this->_params);
            $to = CRM_Utils_Array::value("{$fieldName}_to", $this->_params);

            $clause = $this->dateClause($field['name'], $relative, $from, $to, $field['type']);
          }
          else {
            $op = CRM_Utils_Array::value("{$fieldName}_op", $this->_params);
            if ($op) {
              $clause = $this->whereClause($field,
                $op,
                CRM_Utils_Array::value("{$fieldName}_value", $this->_params),
                CRM_Utils_Array::value("{$fieldName}_min", $this->_params),
                CRM_Utils_Array::value("{$fieldName}_max", $this->_params)
              );
            }
          }

          if (!empty($clause)) {
            $clauses[] = $clause;
          }
        }
      }
    }

    if (empty($clauses)) {
      $this->_where .= " ";
    }
    else {
      $this->_where .= " AND " . implode(' AND ', $clauses);
    }

    if ($this->_aclWhere && !$durationMode) {
      $this->_where .= " AND ({$this->_aclWhere})";
    }
  }

  public function groupBy($includeSelectCol = TRUE) {
    $this->_groupBy = array();
    if (!empty($this->_params['group_bys']) &&
      is_array($this->_params['group_bys'])) {
      foreach ($this->_columns as $tableName => $table) {
        if (array_key_exists('group_bys', $table)) {
          foreach ($table['group_bys'] as $fieldName => $field) {
            if (!empty($this->_params['group_bys'][$fieldName])) {
              if (!empty($field['chart'])) {
                $this->assign('chartSupported', TRUE);
              }
              if (!empty($table['group_bys'][$fieldName]['frequency']) &&
                !empty($this->_params['group_bys_freq'][$fieldName])
              ) {

                $append = "YEAR({$field['dbAlias']}),";
                if (in_array(strtolower($this->_params['group_bys_freq'][$fieldName]),
                  array('year')
                )) {
                  $append = '';
                }
                $this->_groupBy[] = "$append {$this->_params['group_bys_freq'][$fieldName]}({$field['dbAlias']})";
                $append = TRUE;
              }
              else {
                $this->_groupBy[] = $field['dbAlias'];
              }
            }
          }
        }
      }
      $groupBy = $this->_groupBy;
      $this->_groupBy = "GROUP BY " . implode(', ', $this->_groupBy);
    }
    else {
      $groupBy = "{$this->_aliases['civicrm_activity']}.id";
      $this->_groupBy = "GROUP BY {$this->_aliases['civicrm_activity']}.id ";
    }
    if ($includeSelectCol) {
      $this->_groupBy = CRM_Contact_BAO_Query::getGroupByFromSelectColumns($this->_selectClauses, $groupBy);
    }
  }

  /**
   * @param $fields
   * @param $files
   * @param $self
   *
   * @return array
   */
  public static function formRule($fields, $files, $self) {
    $errors = array();
    $contactFields = array('sort_name', 'email', 'phone');
    if (!empty($fields['group_bys'])) {
      if (!empty($fields['group_bys']['activity_date_time'])) {
        if (!empty($fields['group_bys']['sort_name'])) {
          $errors['fields'] = ts("Please do not select GroupBy 'Activity Date' with GroupBy 'Contact'");
        }
        else {
          foreach ($fields['fields'] as $fieldName => $val) {
            if (in_array($fieldName, $contactFields)) {
              $errors['fields'] = ts("Please do not select any Contact Fields with GroupBy 'Activity Date'");
              break;
            }
          }
        }
      }
    }

    // don't allow add to group action unless contact fields are selected.
    if (isset($fields['_qf_ActivitySummary_submit_group'])) {
      $contactFieldSelected = FALSE;
      foreach ($fields['fields'] as $fieldName => $val) {
        if (in_array($fieldName, $contactFields)) {
          $contactFieldSelected = TRUE;
          break;
        }
      }

      if (!$contactFieldSelected) {
        $errors['fields'] = ts('You cannot use "Add Contacts to Group" action unless contacts fields are selected.');
      }
    }
    return $errors;
  }

  public function postProcess() {
    // get the acl clauses built before we assemble the query
    $this->buildACLClause($this->_aliases['civicrm_contact']);

    // get ready with post process params
    $this->beginPostProcess();

    // build query
    $sql = $this->buildQuery(TRUE);

    // main sql statement
    $this->select();
    $this->from();
    $this->customDataFrom();
    $this->where();
    $this->groupBy();
    $this->orderBy();

    // order_by columns not selected for display need to be included in SELECT
    $unselectedSectionColumns = $this->unselectedSectionColumns();
    foreach ($unselectedSectionColumns as $alias => $section) {
      $this->_select .= ", {$section['dbAlias']} as {$alias}";
    }

    $this->buildRows($sql, $rows);

    $this->formatDisplay($rows);
    $this->doTemplateAssignment($rows);
    $this->endPostProcess($rows);
  }

  public function modifyColumnHeaders() {
    //CRM-16719 modify name of column
    if (!empty($this->_columnHeaders['civicrm_activity_status_id'])) {
      $this->_columnHeaders['civicrm_activity_status_id']['title'] = ts('Status');
    }
  }

  /**
   * Alter display of rows.
   *
   * Iterate through the rows retrieved via SQL and make changes for display purposes,
   * such as rendering contacts as links.
   *
   * @param array $rows
   *   Rows generated by SQL, with an array for each row.
   */
  public function alterDisplay(&$rows) {
    $entryFound = FALSE;
    $activityType = CRM_Core_PseudoConstant::activityType(TRUE, TRUE, FALSE, 'label', TRUE);
    $activityStatus = CRM_Core_PseudoConstant::activityStatus();
    $onHover = ts('View Contact Summary for this Contact');
    foreach ($rows as $rowNum => $row) {
      if (array_key_exists('civicrm_contact_sort_name', $row) && $this->_outputMode != 'csv') {
        if ($value = $row['civicrm_contact_id']) {
          $url = CRM_Utils_System::url('civicrm/contact/view',
            'reset=1&cid=' . $value,
            $this->_absoluteUrl
          );

          $rows[$rowNum]['civicrm_contact_sort_name'] = "<a href='$url'>" . $row['civicrm_contact_sort_name'] .
            '</a>';
          $entryFound = TRUE;
        }
      }

      if (array_key_exists('civicrm_activity_activity_type_id', $row)) {
        if ($value = $row['civicrm_activity_activity_type_id']) {

          $value = explode(',', $value);
          foreach ($value as $key => $id) {
            $value[$key] = $activityType[$id];
          }

          $rows[$rowNum]['civicrm_activity_activity_type_id'] = implode(' , ', $value);
          $entryFound = TRUE;
        }
      }

      if (array_key_exists('civicrm_activity_status_id', $row)) {
        if ($value = $row['civicrm_activity_status_id']) {
          $rows[$rowNum]['civicrm_activity_status_id'] = $activityStatus[$value];
          $entryFound = TRUE;
        }
      }

      $entryFound = $this->alterDisplayAddressFields($row, $rows, $rowNum, 'activity', 'List all activities for this ') ? TRUE : $entryFound;

      $entryFound = $this->alterDisplayContactFields($row, $rows, $rowNum, 'contact/summary', 'View Contact Summary') ? TRUE : $entryFound;

      if (!$entryFound) {
        break;
      }
    }
  }
}
