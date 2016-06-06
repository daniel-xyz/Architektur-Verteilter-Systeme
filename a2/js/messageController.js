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
      msg('Nachricht wurde angelegt.');
    })
    .fail(function() {
      error('Nachricht konnte nicht gespeichert werden.'); // TODO: 404er vom Backend zurückgeben (vom Logger den Error durchreichen)
    });
}

function startShowingMessages() {
  loadMessages = setInterval(function () {
    $.get("getLoggerHTML.php", function (response) {
        resetStatusMessages();

      if(response.more === 0) {
        stopShowingMessages();
      }

      if(messageCounter > 5) {
        $('.entry').empty();
        messageCounter = 0
      }

      var entry =
        '<div class="entry" style="display:none;">' +
        '<p>' +
        response.message.time +
        ', ' + response.message.from +
        ': ' + response.message.message +
        '</p>' +
        '</div>';

      $(entry).appendTo('body .message-container').fadeIn('slow');
      messageCounter ++;
    }, "json")
      .fail(function() {
        stopShowingMessages();
        error('Es konnten keine Nachrichten gefunden werden.');
      });
  }, 1000);
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
  msg('Alle Nachrichten wurden erfolgreich gelöscht.');
}

//window.onload = startShowingMessages;