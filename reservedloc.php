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

/**
 * Implements hook_civicrm_permission().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_permission
 */
function reservedloc_civicrm_permission(&$permissions) {
  $permissions['edit locations'] = ts('Locations: Edit locations');
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 *
 * Force the location option to always be "Create new location"
 */
function reservedloc_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Event_Form_ManageEvent_Location') {
    $defaults = array();
    if ($form->elementExists('location_option')) {
      // Reset the location option to create a new LocBlock every time.
      $form->removeElement('location_option');
    }

    $form->addElement('hidden', 'location_option');
    $form->getElement('location_option')->setValue(1);
    $defaults['location_option'] = 1;

    if($form->elementExists('loc_event_id')) {
      // Remove the location selector.
      $form->removeElement('loc_event_id');
    }

    if (!empty($form->_values['email'])) {
      foreach ($form->_values['email'] as $key => $data) {
        unset($form->_values['email'][$key]['id']);
      }
      $defaults['email'] = $form->_values['email'];
    }

    // Ensure locUsed is 0, otherwise we get confusing output.
    $form->assign('locUsed', 0);

    // Actually, remove the entire block.
    $form->assign('locEvents', FALSE);

    // Overwrite the applicable (location_option) default values.
    if(!empty($default)) {
      $form->setDefaults($defaults);
    }
  }
}

