(function ($) {

  /**
   * tries to alert user of refreshing the log window
   * if the development window has crashed. refreshing the log
   * window will also crash so this pop-up reminds the
   * developer not to do it.
   * The problem is this pop-up may not work depending on
   * browser settings!
   */
  if (document.title.indexOf("WP logger") > -1) {
    window.onbeforeunload = function () {
      return;
    }
  }

  $(document).ready(cb_clearLog_button_listener);

  /**
   * adds click listener to #clear_log
   */
  function cb_clearLog_button_listener() {
    // add click listener to #clearlog button
    $('#clear_log').click(cb_clearLog);
  }

  /**
   * fires the delete_log php function
   * uses ajax
   */
  function cb_clearLog() {
    //location.reload();
    var data = {
      'action': 'delete_log',
      'logger_nonce': chipbug_dev.logger_nonce
    };
    $.post(chipbug_dev.ajax_url, data)
      .fail(function (jqXHR, textStatus, err) {
        if ('Internal Server Error' == err) {
          alert('error 500: WP has crashed.\nThis page will also crash if restarted now.\nLogger will try to keep reporting (crashes are in red)\nGet WP working again and then refresh!');
        } else {
          location.reload(true);
        }
        console.log('oops:' + textStatus + ', ' + err);
      });
    $('#logger-container').empty();
  }
})(jQuery)