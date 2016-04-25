function sendMessage() {
  var time = new Date(new Date().getTime()).toLocaleString();
  var from = "Manfred";
  var message = "Hier könnte eine interessantere Nachricht stehen.";

  $.get("logger.php", {
    time: time,
    from: from,
    message: message
  });
}

function showMessages() {
  $.get("getLoggerHTML.php", function(response) {
    $("body .message-container" )
      .append("<p>")
      .append("<b> Time: </b>" + response.time + "<br />")
      .append("<b> Name: </b>" + response.name + "<br />")
      .append("<b> Message: </b>" + response.message)
      .append("</p>");
  }, "json" );
}