function notifyRegistry() {
  var ip = $('#ip').val();

  resetStatusMessages();

  $.get("notifyRegistry.php", {
    ip: ip
  }).success(function() {
    msg('Server wurde erfolgreich angemeldet und hat die IP-Liste der anderen Server erhalten. Viel Spaß!');
  }).fail(function() {
    error('Es ist leider etwas schief gelaufen.'); // TODO: 404er vom Backend zurückgeben (vom Logger den Error durchreichen)
  });
}