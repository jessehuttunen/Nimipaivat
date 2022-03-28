<?php
//Connection to namedays database
$db = new PDO('mysql:host=my77b.sqlserver.se;dbname=245792-pvr-nimppa;charset=latin1','245792_aj56310', 'TDZ5wfF!jaeQWP3');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
?>