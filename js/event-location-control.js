CRM.$(function($) {

  var confirmation_flag = true;

  if($('#CIVICRM_QFID_1_location_option').is(':checked')){
     confirmation_flag = false;
   }


  $('#Address_Block_1').on('change.event_ui_modi',function(event){

    if($('#CIVICRM_QFID_1_location_option').is(':checked')){
       confirmation_flag = false;
     }

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

      var html_message = 'Editing an existing location will create a new location for the current event.<br> The original location will not be changed.';
      var url = CRM.url('civicrm/EditLocation', {bid: $('#loc_event_id').val()});

      html_message += '<br><br><br><a href="'+url+'">click here to edit current selected location</a>'

      var options =  {message:html_message, options:{no: "Discard change",yes: "Continue"}};

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
