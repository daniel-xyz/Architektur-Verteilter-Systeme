var loadMessages,
    messageCounter = 0,
    $messageInput = $("input[name=new-message]");

function sendMessage() {
  resetStatusMessages();

  $.get("sendMessage.php", {
    message: $messageInput.val(),
    timestamp: Math.floor(Date.now() / 1000)
  })
    .success(function() {
      msg('Nachricht wurde angelegt.');
      $messageInput.val("");
    })
    .fail(function() {
      error('Nachricht konnte nicht gespeichert werden.');
      $messageInput.val("");
    });
}

function startShowingMessages() {
  loadMessages = setInterval(function () {
    $.get("getLoggerHTML.php", function (response) {
        resetStatusMessages();

      if(response.more === 0) {
        // stopShowingMessages();
      }

      if(messageCounter > 5) {
        $('.entry').empty();
        messageCounter = 0
      }

      var entry =
        '<div class="entry" style="display:none;">' +
        '<p>' +
        response.message.time +
        ', ' + response.message.sender +
        ': ' + response.message.message +
        '</p>' +
        '</div>';

      $(entry).appendTo('body .message-container').fadeIn('slow');
      messageCounter ++;
    }, "json")
      .fail(function() {
        // stopShowingMessages();
        // error('Es konnten keine Nachrichten gefunden werden.');
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

window.onload = startShowingMessages;