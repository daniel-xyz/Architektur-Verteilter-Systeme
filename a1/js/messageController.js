var loadMessages;

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
  loadMessages = setInterval(function () {
    $.get("getLoggerHTML.php", function (response) {
      var entry =
        '<div class="entry" style="display:none;">' +
        '<p>' +
        '<b> Time: </b>' + response.time + '<br />' +
        '<b> Name: </b>' + response.from + '<br />' +
        '<b> Message: </b>' + response.message +
        '</p>' +
        '</div>';

      $(entry).appendTo('body .message-container').fadeIn('slow');
    }, "json");
  }, 2000);
}

function stopShowingMessages() {
  clearInterval(loadMessages);
}