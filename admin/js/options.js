(function ($) {

  $(document).ready(do_setup);

  /**
   * loads current options, adds click listener to #clearlog
   */
  function do_setup() {
    get_options();
    // energise validator
    // http://www.formvalidator.net/index.html#configuration
    $.validate();
    // add click listener to #clearlog button
    $('#save_options').click(save_options);
    // stop form default action
    $('form').submit(function (ev) {
      return false;
    });
  }

  /**
   * gets options from wp_options - uses ajax
   */
  function get_options() {
    var data = {
      'action': 'get_options',
      'options_nonce': chipbug_dev.options_nonce
    };
    $.get(chipbug_dev.ajax_url, data).done(function (results) {
      results = JSON.parse(results);
      console.log(results);
      $('#size_of_log').val(results.size_of_log);
      $('#refresh_rate').val(results.refresh_rate);
      if ('true' == results.include_trace) {
        $('#include_trace').prop('checked', true);
      } else {
        $('#include_trace').prop('checked', false);
      }
      if ('true' == results.include_file_path) {
        $('#include_file_path').prop('checked', true);
      } else {
        $('#include_file_path').prop('checked', false);
      }
    });
  }

  /**
   * saves options to wp_options - uses ajax
   */
  function save_options() {
    var normal = {
      'background-color': '#0085ba',
      'color': '#fff'
    }
    var saving = {
      'background-color': 'lightcoral',
      'color': 'black',
      'text-shadow': 'none'
    }
    var saved = {
      'background-color': 'lightgreen',
      'color': 'black',
      'text-shadow': 'none'
    }
    var data = {
      'action': 'save_options',
      'options_nonce': chipbug_dev.options_nonce,
      'size_of_log': $('#size_of_log').val(),
      'refresh_rate': $('#refresh_rate').val(),
      'include_trace': $('#include_trace').is(':checked'),
      'include_file_path': $('#include_file_path').is(':checked')
    };
    $('#save_options').css(saving).text('saving');
    $.post(chipbug_dev.ajax_url, data, function () {
      $("#save_options")
        .css(saved)
        .text('saved!')
        .delay(750)
        .queue(function () {
          $(this).css(normal).text('save options').clearQueue();
          get_options();
        });
    });
  }
})(jQuery)