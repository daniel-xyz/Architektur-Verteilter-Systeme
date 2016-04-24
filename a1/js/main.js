function sendMessage() {
  var time = new Date(new Date().getTime()).toLocaleString();
  var from = "Manfred";
  var message = getRandomMessage();

  $.get("logger.php", {
    time: time,
    from: from,
    message: message
  });
}

function showMessages() {

}

function getRandomMessage() {
  return "Ich könnte eine interessantere Nachricht sein."
}