<?php

define('BASE_DIR', dirname(realpath(__FILE__)) . DIRECTORY_SEPARATOR);

//////
$GLOBALS['link'] = new mysqli('MySQL-8.0', 'root', '', 'files');
//////

require BASE_DIR . 'app/bootstrap.php';

//////
# end of file
