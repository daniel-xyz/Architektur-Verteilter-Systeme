var $obj;

$(function() {
  $obj = $('.status');
});

function error(msg) {
  $obj.removeClass();
  $obj.addClass('error')
  $obj.text('Fehler: ' + msg);
}

function msg(msg) {
  $obj.removeClass();
  $obj.addClass('msg')
  $obj.text('Hinweis: ' + msg);
}

function resetStatusMessages() {
  $obj.text('');
}