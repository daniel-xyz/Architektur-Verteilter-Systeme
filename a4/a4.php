<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <script type="text/javascript" src="js/lib/jquery-2.2.3.min.js"></script>
  <script type="text/javascript" src="js/statusMessageHandler.js"></script>
  <script type="text/javascript" src="js/messageController.js"></script>
  <link rel="stylesheet" href="css/main.css">
  <title>Aufgabe 4</title>
</head>
<body>
  <script>
    registerInputListener();
  </script>
  <div class="main-container">
    <div class="top-container">
      <span class="status"></span>
      <span class="links"><a href="registerServer.html">Diesen Server anmelden</a></span>
      <span class="links"><a href="registerOtherServer.html">Einen anderen Server anmelden</a></span>
    </div>
    <div class="button-container">
      <input type="button" onclick="startShowingMessages()" value="Nachrichten anzeigen"/>
      <input type="button" onclick="stopShowingMessages()" value="Nachrichten stoppen"/>
      <input type="button" onclick="restart()" value="Reset"/>
    </div>
    <div class="message-container"></div>
    <div class="input-container">
      <form>
        Neue Nachricht: <input type="text" name="new-message"> <input type="button" onclick="sendMessage()" value="Absenden">
      </form>
    </div>
  </div>
</body>
</html>