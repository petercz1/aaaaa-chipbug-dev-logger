(function ($) {

  // connects to server-sent events

  console.log('loaded socket');

  $(document).ready(do_setup);

  function do_setup() {
    counter = 0;
    let eventSource = new EventSource('./../wp-content/plugins/aaaaa-chipbug-dev-logger/src/sse-setup.php');

    eventSource.addEventListener("db_results", function (event) {
      console.log(event.data);
      if (event.data != "{}") {
        do_data(JSON.parse(event.data));
      }
    });

    eventSource.addEventListener("error", function (err) {
      console.log('blow up... recover??');
      console.log(err);
      if(eventSource.CLOSED){
        console.log('event source closed');
      }
    });
  }

  function do_data(data) {
    full_list = '';
    for (var counter = 0; counter < data.length; counter++) {
      list = '<div class="item">';
      line_one = '';
      line_two = '';

      if (undefined == data[counter].error_name) {
        data[counter].error_name = "unknown error";
      }
      if (undefined == data[counter].line_no) {
        data[counter].line_no = "";
      }
      if (undefined == data[counter].details) {
        data[counter].details = "";
      }
      if (undefined == data[counter].file) {
        data[counter].file = "";
      }
      if (data[counter].error_name == 'NEW LOG') {
        line_one = '<div class="php_new_log">NEW LOG</div>';
      } else if ('MySQL' == data[counter].error_name) {
        for (var mysql_counter = 0; mysql_counter < data[counter].details.length; mysql_counter++) {
          line_one = '<div class="php_mysql">MySQL: ' + data[counter].details[mysql_counter].query + '</div>';
          line_two = '<div class="php_mysql">' + data[counter].details[mysql_counter].error_str + '</div>';
        }

      } else {
        var css_class = 'php_' + data[counter].error_name.toLowerCase().replace(/ /g, "_");
        line_one = '<div class="' + css_class + '">' + "line " + data[counter].line_no + ", " +  ": " + data[counter].details + '</div>';
        line_two = '<div class="details">' + "file: " + data[counter].file + '</div>';
      }

      list += line_one;
      list += line_two;
      list += '</div>';

      full_list += list;
    }

    $('#logger-container').prepend($(full_list).hide().delay(250).show('fast'));
  }
})(jQuery)