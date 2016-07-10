function notifyRegistry() {
  var serverName = $('#server-name').val();
  var registry = $('#ip').val() || "";
  var newIP = $('#invited-ip').val() || "";
  var params = {};

  resetStatusMessages();

  if (registry !== "") {
    params = {
      name: serverName,
      registryip: registry
    }
  } else {
    params = {
      name: serverName,
      newip: newIP
    }
  }

  $.get("notifyRegistry.php", params)
    .success(function() {
      msg('Server wurde erfolgreich angemeldet. Viel Spaß!');
    })
    .fail(function() {
      error('Es ist leider etwas schief gelaufen.');
    });
}