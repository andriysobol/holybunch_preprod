/*
 * Security Ninja
 * (c) 2011. Web factory Ltd
 */


jQuery(document).ready(function($){
  // alternate table rows
  $('#sn-tests-help tr:odd, #security-ninja tr:odd').addClass('alternate');

  // init tabs
  $("#tabs").tabs({cookie:{expires: 30}});

  // just to make sure the button is not stuck
  $('#run-tests').removeAttr('disabled');

  // run tests, via ajax
  $('#run-tests').click(function(){
    var data = {action: 'sn_run_tests'};

    $(this).attr('disabled', 'disabled')
           .val('Running tests, please wait!');
    $.blockUI({ message: 'Security Ninja is analyzing your site.<br />Please wait, it can take a few minutes.' });

    $.post(ajaxurl, data, function(response) {
      if (response != '1') {
        alert('Undocumented error. Page will automatically reload.');
        window.location.reload();
      } else {
        window.location.reload();
      }
    });
  }); // run tests

  // show test details/help tab
  $('.sn-details a.button').live('click', function(){
    if ($('#wf-ss-dialog').length){
      $('#wf-ss-dialog').dialog('close');
    }
    $("#tabs").tabs('select', '#sn_help');

    // get the link anchor and scroll to it
    target = this.href.split("#")[1];
    $.scrollTo('#' + target, 500, {offset: {top:-30, left:0}});

    return false;
  }); // show test details

  // hide core add-on tab
  $('#sn_hide_core_ad').click(function(){
    var data = {action: 'sn_hide_core_tab'};

    $.post(ajaxurl, data, function(response) {
      if (response != '1') {
        alert('Undocumented error. Page will automatically reload.');
        window.location.reload();
      } else {
        window.location.reload();
      }
    });
  }); // hide core ad

  // hide schedule add-on tab
  $('#sn_hide_schedule_ad').click(function(){
    var data = {action: 'sn_hide_schedule_tab'};

    $.post(ajaxurl, data, function(response) {
      if (response != '1') {
        alert('Undocumented error. Page will automatically reload.');
        window.location.reload();
      } else {
        window.location.reload();
      }
    });
  }); // hide schedule ad
});