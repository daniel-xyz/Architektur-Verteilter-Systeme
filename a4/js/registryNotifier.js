function notifyRegistry() {
  var serverName = $('#server-name').val() || "";
  var registry = $('#ip').val() || "";
  var newIP = $('#invited-ip').val() || "";
  var kickIP = $('#kick-ip').val() || "";
  var params = {};

  resetStatusMessages();

  if ((registry !== "") && (serverName !== "")) {
    params = {
      name: serverName,
      registryip: registry
    }
  } else if ((newIP !== "") && (serverName !== "")) {
    params = {
      name: serverName,
      newip: newIP
    }
  } else if (kickIP !== "") {
    params = {
      kickip: kickIP
    }
  }

  $.get("notifyRegistry.php", params)
    .success(function() {
      msg('Registry wurde erfolgreich aktualisiert. Viel Spaß!');
    })
    .fail(function() {
      error('Es ist leider etwas schief gelaufen.');
    });
}