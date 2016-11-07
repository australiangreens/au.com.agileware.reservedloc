<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Reservedloc_Form_EditLocation extends CRM_Event_Form_ManageEvent_Location {


  public function preProcess() {
    parent::preProcess();


    if(isset($_GET['bid'])){
      //need to be sanitized
      $bid = $_GET['bid'];

    }else {
      if (!$this->_values = $this->get('values')) {
        CRM_Core_Error::fatal(ts('No location id provided!'));
      }
    }

    if(empty($this->_values) || isset($bid) ){

      $this->_values = array(
        'address' => array(),
        'email' => array(),
        'phone' => array(),
      );


      $loc_block = civicrm_api3('LocBlock', 'getsingle', array('id' => $bid,));

      if(!empty($loc_block['is_error'])){
        CRM_Core_Error::fatal($loc_block['error_message']);
      }else{
        unset($loc_block['is_error']);
      }

      $tmp = array();

      foreach ($loc_block as $field => $value) {

        $tmp = explode("_", $field);

        if(count($tmp) == 3){
          unset($tmp[2]);
        }elseif (count($tmp) == 2){
          $tmp[1] = 1;
        }else{
          continue;
        }

        $result = civicrm_api3($tmp[0], 'getsingle', array('id' => $value,));

        if( empty($result['is_error']) ){

          unset($result['is_error']);

        }else{
          CRM_Core_Error::fatal($result['error_message']);
        }


        $this->_values[strtolower($tmp[0])][$tmp[1]] = $result;

      }

      $this->set('values', $this->_values);

    }

  }


  public function setDefaultValues() {

    $defaults = $this->_values;

    $config = CRM_Core_Config::singleton();
    if (!isset($defaults['address'][1]['country_id'])) {
      $defaults['address'][1]['country_id'] = $config->defaultContactCountry;
    }

    if (!isset($defaults['address'][1]['state_province_id'])) {
      $defaults['address'][1]['state_province_id'] = $config->defaultContactStateProvince;
    }


     if (!CRM_Core_Permission::check('edit reserved locations')){
         $this->assign('message', 'No permission to edit');

         foreach (array_keys($this->_elements) as $key) {
           $this->_elements[$key]->freeze();
         }
       }

    return $defaults;
  }


  public function buildQuickForm() {
    //load form for child blocks
    if ($this->_addBlockName) {
      $className = "CRM_Contact_Form_Edit_{$this->_addBlockName}";
      return $className::buildQuickForm($this);
    }

    $this->applyFilter('__ALL__', 'trim');

    //build location blocks.
    CRM_Contact_Form_Location::buildQuickForm($this);

    //fix for CRM-1971
    $this->assign('action', $this->_action);


    if (CRM_Core_Permission::check('edit reserved locations')) {
        $buttons = array(
          array(
            'type' => 'upload',
            'name' => ts('Save'),
            'isDefault' => TRUE,
          ),
          array(
            'type' => 'cancel',
            'name' => ts('Cancel'),
          ),
        );

        $this->assign('message', 'Permission of editting enabled');

        $this->addButtons($buttons);

        //TODO adding check box to see for location reservation.
        // $this->addCheckBox('location_reserved', ts('Is location reserved?'),
        //   CRM_Core_OptionGroup::values('recur_frequency_units', FALSE, FALSE, TRUE),
        //   NULL, NULL, NULL, NULL,
        //   array('&nbsp;&nbsp;', '&nbsp;&nbsp;', '&nbsp;&nbsp;', '<br/>'), TRUE
        // );
        //
        // $this->createElement('checkbox', $key, NULL, $var);


      }else{

        $buttons = array(
          array(
            'type' => 'back',
            'name' => ts('Back'),
          ),
        );


        $this->addButtons($buttons);

      }
  }


  public function postProcess() {
    $params = $this->exportValues();

    // dpm(array('Export Values: '=>$params,'This Value: '=>$this->_values));

    $custom_fields_array = array();

    foreach ($this->_values as $blockName => $block_value) {

      foreach ($block_value as $key => $value) {

          $custom_fields_array = array();
          $id = $value['id'];

          $params[$blockName][$key]['id'] =  $id;

          //going to update normal fields and custom fields seperately, so pop out all the custom fields
          $custom_fields_array = $this->pop_out_custom_fields($params[$blockName][$key]);

          //update normal fields on each block
          $result = civicrm_api3($blockName, 'create', $params[$blockName][$key]);

          if( !empty($result['is_error'])){
            CRM_Core_Error::fatal($result['error_message']);
          }

          //update custom fields on each block, if any
          if(!empty($custom_fields_array)){
            $query_array = array('entity_id' => $id,'entity_table' => "$blockName",) + $custom_fields_array;
            $result = civicrm_api3('CustomValue', 'create', $query_array);

            if( !empty($result['is_error'])){
              CRM_Core_Error::fatal($result['error_message']);
            }
          }
      }


    }

    CRM_Core_Session::setStatus(ts("Location information has been saved."), ts('Saved'), 'success');
    $config = CRM_Core_Config::singleton();
    $this->postProcessHook();

    //TODO how to stay on the current page? or should we redirect users to other page?

  }

  protected function pop_out_custom_fields(array &$input = array()){

    if(empty($input) || gettype($input) != 'array'){
      return false;
    }

    $output = array();
    $tmp = null;

    foreach ($input as $key => $value) {
      if(strrpos($key,"custom_") !== false){

        $tmp = explode('_',$key);

        $tmp = $tmp[0].'_'.$tmp[1];

        $output[$tmp] = $value;

        unset($input[$key]);
      }else {
        continue;
      }

    }

    return $output;
  }



}
