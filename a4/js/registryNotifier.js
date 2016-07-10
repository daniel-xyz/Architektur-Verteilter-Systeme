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
    };
    registerServer();
  } else if ((newIP !== "") && (serverName !== "")) {
    params = {
      name: serverName,
      newip: newIP
    };
    registerServer();
  } else if (kickIP !== "") {
    params = {
      kickip: kickIP
    };
    kickServer(kickIP);
  }

  function registerServer() {
    $.get("notifyRegistry.php", params)
      .success(function() {
        msg('Registry wurde erfolgreich aktualisiert. Viel Spaß!');
      })
      .fail(function() {
        error('Es ist leider etwas schief gelaufen.');
      });
  }

  function kickServer(kickIP) {
    $.get('http://' + kickIP + '/Architektur-Verteilter-Systeme/a4/kickOut.php', params)
      .success(function() {
        msg('Server wurde erfolgreich gekickt. Viel Spaß!');
      });
  }
}