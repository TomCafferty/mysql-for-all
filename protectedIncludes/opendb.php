<?php
/**
 * Mysql-for-all: generate mysql query from user selections
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author   Tom Cafferty
 * @email    tcafferty@glocalfocal.com
 *
 */
function dbOpen() {
    require( DOC_ROOT . '/otherProtectedArea/arcaneName.php' );
    $host = "localhost";
    $user = "mysite_visitor";
    $dbName = "mysite_visitor_data";
    $con  = mysqli_connect($host,$user,$password,$dbName) or die("Couldn't connect to database " . $dbName);
    return $con;
}
?>