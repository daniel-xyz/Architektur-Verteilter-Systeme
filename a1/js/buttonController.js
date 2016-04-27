var loadMessages,
    messageCounter = 0;

function sendMessage() {
  var time = Math.floor(Date.now() / 1000);
  var from = "Manfred";
  var message = "Hier könnte eine interessantere Nachricht stehen.";

  resetStatusMessages();

  $.get("logger.php", {
    time: time,
    from: from,
    message: message
  })
    .success(function() {
      msg('Nachricht wurde angelegt.')
    });
}

function startShowingMessages() {
  loadMessages = setInterval(function () {
    $.get("getLoggerHTML.php", function (response) {
        resetStatusMessages();

      if(messageCounter > 5) {
        $('.entry').empty();
        messageCounter = 0
      }

      var entry =
        '<div class="entry" style="display:none;">' +
        '<p>' +
        '<b> Wann: </b>' + response.time + '<br />' +
        '<b> Name: </b>' + response.from + '<br />' +
        '<b> Von: </b>' + response.message +
        '</p>' +
        '</div>';

      $(entry).appendTo('body .message-container').fadeIn('slow');
      messageCounter ++;
    }, "json")
      .fail(function() {
        stopShowingMessages();
        error('Es konnten keine Nachrichten gefunden werden.');
      });
  }, 2000);
}

function stopShowingMessages() {
  clearInterval(loadMessages);
}

function restart() {
  stopShowingMessages();

  $.get("restart.php", function () {
    clearMessages();
  });
}

function clearMessages() {
  $('.entry').empty();
  msg('Alle Nachrichten wurden erfolgreich gelöscht.')
}