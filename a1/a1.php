<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <script type="text/javascript" src="lib/jquery-2.2.3.min.js"></script>
  <script type="text/javascript" src="js/messageController.js"></script>
  <link rel="stylesheet" href="css/main.css">
  <title>Aufgabe 1</title>
</head>
<body>
  <div class="main-container">
    <div class="button-container">
      <input type="button" onclick="sendMessage()" value="Nachricht erzeugen"/>
      <input type="button" onclick="showMessages()" value="Nachrichten anzeigen"/>
      <input type="button" onclick="stopShowingMessages()" value="Nachrichten stoppen"/>
    </div>
    <div class="message-container"></div>
  </div>
</body>
</html>