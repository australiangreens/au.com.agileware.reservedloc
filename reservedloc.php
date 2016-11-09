<?php

require_once 'reservedloc.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function reservedloc_civicrm_config(&$config) {
  _reservedloc_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param array $files
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function reservedloc_civicrm_xmlMenu(&$files) {
  _reservedloc_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function reservedloc_civicrm_install() {
  _reservedloc_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function reservedloc_civicrm_uninstall() {
  _reservedloc_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function reservedloc_civicrm_enable() {
  _reservedloc_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function reservedloc_civicrm_disable() {
  _reservedloc_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function reservedloc_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _reservedloc_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function reservedloc_civicrm_managed(&$entities) {
  _reservedloc_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * @param array $caseTypes
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function reservedloc_civicrm_caseTypes(&$caseTypes) {
  _reservedloc_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function reservedloc_civicrm_angularModules(&$angularModules) {
_reservedloc_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function reservedloc_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _reservedloc_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function reservedloc_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function reservedloc_civicrm_navigationMenu(&$menu) {
  _reservedloc_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'au.com.agileware.reservedloc')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _reservedloc_civix_navigationMenu($menu);
} // */
/*
*/



function reservedloc_civicrm_permission(&$permissions) {
  $permissions['edit reserved locations'] = ts('Reserved locations: Edit reserved locations');
}

//hook custom javascript into buidlform process.
function reservedloc_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Event_Form_ManageEvent_Location') {
    if($_REQUEST['snippet'] == 'json' && $_REQUEST['component'] == 'event'){
      CRM_Core_Resources::singleton()->addScriptFile('au.com.agileware.reservedloc', 'js/event-location-control.js', 0, 'page-footer');
    }
  }
}

//hook into the first step after the form has been submitted, as postprocess is too late for us to do stuff
function reservedloc_civicrm_validateForm( $formName, &$fields, &$files, &$form, &$errors ) {
  if($formName == 'CRM_Event_Form_ManageEvent_Location') {
      $default_value = $form->_defaultValues;
      $submit_value = $form->exportValues();

      if($submit_value['location_option'] == 2 && $submit_value['loc_event_id'] == $default_value['loc_block_id']) {
        //if user did not select a different location block
        compare_default_n_submit_value($default_value,$submit_value,$form);
      }
      elseif ($submit_value['location_option'] == 2 && $submit_value['loc_event_id'] != $default_value['loc_block_id']) {
        //if user selected a different location block, getting all default values using api.
        $default_value = array(
          'address' => array(),
          'email' => array(),
          'phone' => array(),
        );

        $loc_block = civicrm_api3('LocBlock', 'getsingle', array('id' => $submit_value['loc_event_id'],));

        if(!empty($loc_block['is_error'])) {
          CRM_Core_Error::fatal($loc_block['error_message']);
        }
        else {
          unset($loc_block['is_error']);
        }

        //standardizing the key names of the result.
        $tmp = array();
        foreach ($loc_block as $field => $value) {
          $tmp = explode("_", $field);
          if(count($tmp) == 3) {
            unset($tmp[2]);
          }
          elseif (count($tmp) == 2) {
            $tmp[1] = 1;
          }
          else {
            continue;
          }

          $result = civicrm_api3($tmp[0], 'getsingle', array('id' => $value,));

          if(isset($result['is_error'])) {
            unset($result['is_error']);
          }
          $default_value[strtolower($tmp[0])][$tmp[1]] = $result;
        }
        compare_default_n_submit_value($default_value,$submit_value,$form);
      }
      return;
  }
  return;
}

//compare default form values (need to pass it into this function) and submit values to see if there is any change
function compare_default_n_submit_value($default_value,$submit_value,&$form) {
  foreach (array('address','phone','email',) as $block) {
    foreach ($submit_value[$block] as $index => $values) {
      foreach ($values as $key => $value) {
        if(strrpos($key,'master_id') !== false) {
          continue;
        }

        if(strrpos($key,'custom_') !== false) {
          $tmp = explode("_", $key);
          //check values of custom fields
          $query_array = array('entity_id' => $default_value[$block][$index]['id'],'entity_table' => 'civicrm_'.$block,'id'=>$tmp[1]);
          $result = civicrm_api3('CustomValue', 'get', $query_array);

          if( !empty($result['is_error'])) {
            CRM_Core_Error::fatal($result['error_message']);
          }
          $default_value[$block][$index][$key] = $result['values'][$tmp[1]]['latest'];
        }

        if(isset($default_value[$block][$index][$key])) {
          if($default_value[$block][$index][$key] == $value) {
            continue;
          }
          else {
              $form->_submitValues['location_option'] = '1';
          }
        }
        elseif (!isset($default_value[$block][$index][$key]) && $value != null && $key != 'phone_type_id') {
            $form->_submitValues['location_option'] = '1';
        }
        continue;
      }
    }
  }

  if(!isset($submit_value['address'][1]['manual_geo_code'])  && $default_value['address'][1]['manual_geo_code'] != 0 ) {
    $form->_submitValues['location_option'] = '1';
  }

  //trying to fix issue that the custom fields can not be saved when create a new location block.
  // if($form->_submitValues['location_option'] == 1) {
  //     foreach (array('address','phone','email',) as $block) {
  //       foreach ($form->_submitValues[$block] as $index => $values) {
  //         foreach ($values as $key => $value) {
  //           if(strrpos($key,'custom_') !== false) {
  //             $tmp = explode("_", $key);
  //             $tmp = $tmp[0].'_'.$tmp[1].'_-1';
  //             if($key != $tmp) {
  //               $tmp2 = $value;
  //               $form->_submitValues[$block][$index][$tmp] = $tmp2;
  //               unset($form->_submitValues[$block][$index][$key]);
  //             }
  //           }
  //         }
  //       }
  //    }
  // }
  return;
}
