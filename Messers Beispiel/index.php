<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Ajax Hello</title>
    </head>
    <body>
        <?php
        echo "<p>Hier stehen die Dinge der Seite";
        ?>
    </body>
    <script type="text/javascript" src="js/Ajax.js"></script>
    <script type="text/javascript" src="js/Writer.js"></script>
    <script type="text/javascript">
        write2console("Das ist das Consolen-Fenster");
        console.log("das ist eine Ausgabe in die Console des Browsers");
        var URL = "http://localhost/Ajax-Console/getDate.php";
        var ajaxCom= new Ajax(URL,receive);
        // expected components (checked in receive())
        receivedObj= {"date": 0};
        ajaxCom.send({"msg": "Heute ist "});
        write2console(receivedObj.date);
        ajaxCom.disconnect(); 
    </script>
</html>
