<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
  <link rel="stylesheet" href="css/main.css">
  <title>Aufgabe 1</title>
</head>
<body>
  <div class="main-container">
    <div class="button-container">
      <input type="button" onclick="sendMessage()" value="Nachricht erzeugen"/>
      <input type="button" onclick="showMessages()" value="Nachrichten anzeigen"/>
    </div>
    <div class="message-container"></div>
  </div>
</body>
</html>

<!--<script type="text/javascript" src="js/Ajax.js"></script>-->
<!--<script type="text/javascript" src="js/Writer.js"></script>-->
<!--<script type="text/javascript">-->
<!--    write2console("Das ist das Consolen-Fenster");-->
<!--    console.log("das ist eine Ausgabe in die Console des Browsers");-->
<!--    var URL = "http://localhost/Ajax-Console/getDate.php";-->
<!--    var ajaxCom= new Ajax(URL,receive);-->
<!--    // expected components (checked in receive())-->
<!--    receivedObj= {"date": 0};-->
<!--    ajaxCom.send({"msg": "Heute ist "});-->
<!--    write2console(receivedObj.date);-->
<!--    ajaxCom.disconnect();-->
<!--</script>-->