<?php
/**
 * Mysql-for-all: generate mysql query from user selections
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author   Tom Cafferty
 * @email    tcafferty@glocalfocal.com
 *
 *   Processes form inputs to output the query string
 *   
 *   Function:
 *      processTable
 *      
 *   Parameters:
 *      None.
 *   
 *   Return value:
 *      None
 *
 */
function processTable() {
    $limiters = '';

    //get the user selections       
    $orderBy = $_POST['orderBy'];
    $direction = $_POST['direction'];
    $theQuery = $_POST['theQuery'];
   
    if ($_POST['limit'] == 'on') {
        $limit = TRUE;
        $limitNum = $_POST['limitNumber'];
    }
    else
        $limit = FALSE;
          
    //put in limiters
    if ($orderBy != 'none')
        $limiters .= ' ORDER BY ' . $orderBy . ' ' . $direction;
    if ($limit)
       $limiters .= ' LIMIT ' . $limitNum;
    
    $theQuery .= $limiters;
    echo $theQuery;
}
processTable();
?>