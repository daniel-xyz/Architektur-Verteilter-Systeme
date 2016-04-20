<?php
if(isset($_POST['msg'])) {
   $mesg= $_POST['msg'];
} else {
   $mesg= 'Now is';
}
echo "bla1";        // Simulation einer Fehlermeldung
$message= "$mesg <b><i>".strftime("%d.%m.%Y %H:%M:%S")."</i></b>";
echo json_encode(array('date'=> $message));
echo "bla2";        // Simulation einer weiteren Fehlermeldung
