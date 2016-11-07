CRM.$(function($) {

  var confirmation_flag = true;

  if($('#CIVICRM_QFID_1_location_option').is(':checked')){
     confirmation_flag = false;
   }


    //TODO really need to get this bit working properly
    $('#CIVICRM_QFID_1_location_option').on('click',function(event){
      //for testing
      console.log('go');
      confirmation_flag = false;
    });


    $('#Address_Block_1').on('change.event_ui_modi',function(event){

      if(confirmation_flag){

        if(event.target.id == 'is_show_location'){
          return;}

        confirmation_flag = false;
        form_content_change_handler();

      }


    });

    $('#loc_event_id').on('change.event_ui_modi',function (){
      var form_input_elements = $('form#Location input,form#Location select');
      confirmation_flag = false;
      form_input_elements.prop('disabled', true);
      setTimeout(function(){
        confirmation_flag = true;
        form_input_elements.prop('disabled', false);
      },2000);
    });






  function form_content_change_handler(){

      var options =  {message:'Editing an existing location will create a new location for the current event.<br> The original location will not be changed.',options:{no: "Cancel the change",yes: "Continue"}};

      CRM.confirm(options)
      .on('crmConfirm:no', function(event) {
        $('#loc_event_id').trigger('change');
        return;
      })
      .on('crmConfirm:yes', function() {
        return;
      });
  }

});
