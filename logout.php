<?php

session_start();

session_destroy();

header('Location: login.php?justLoggedOut=1');
exit;

?>