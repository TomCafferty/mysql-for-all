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
function getSpecialOrders() {

    //initialize query substrings
    $select = 'SELECT';
    $where  = ' WHERE';
    $from   = ' FROM ';
    $isWhere = FALSE;
    $output = '';

    //get the user selections
    $tables   = count($_POST['tables'])   ? $_POST['tables']   : array();
    $show     = count($_POST['show'])     ? $_POST['show']     : array();
    $filter   = count($_POST['filter'])   ? $_POST['filter']   : array();
    $relation = count($_POST['relation']) ? $_POST['relation'] : array();
  
    //go thru the tables
    foreach ($tables as $tableNum => $table) {
        $from .= $table . ', ';
        // grab elements to show if they exist
        if (array_key_exists($tableNum, $show)) {
            foreach ($show[$tableNum] as $key => $showMe) {
                $select .= ' ' . $table.'.'.$showMe . ',';
            }
        }
        // grab elements to filter if they exist
        if (array_key_exists($tableNum, $filter)) {
            $isWhere = TRUE;
            foreach ($filter[$tableNum] as $key => $filterMe) {
                $filterValue = str_replace('.', '~#~', $filterMe);  // put dot (.) back (see workaround in describeTable function)
                $value = $_POST[$filterValue];
                $where .= ' ' . $filterMe . ' = "'. $value . '" AND ';
            }
        }
        //grab file relations if they exist
        if (array_key_exists($tableNum, $relation)) {
            $isWhere = TRUE;
            foreach ($relation[$tableNum] as $key => $filterRelation) {
                $where .= ' ' . $filterRelation . ' AND ';
            }
        }
    }
    //put the query togeather
    if ($isWhere == FALSE)
      $where = '';
    $theQuery = ltrim(rtrim($select, ",")) . rtrim($from, ", ") . rtrim($where, " AND ");
    
    // pass the query to the next page as hidden POST data
    $output .= "<input type='hidden' name='theQuery' value='".$theQuery."'/>"; 
    
    $output .= '<select class="indent2" name=orderBy><option value="none">none</option>';
    foreach ($tables as $tableNum => $table) {
        if (array_key_exists($tableNum, $show)) {
            foreach ($show[$tableNum] as $key => $showMe) {
                $output .= "<option value=". $showMe .">" . $table.'.'.$showMe . "</option>";
            }
        }
    }
    
    $output .= "</select>";

    $output .= '<br /><input class="indent2" type="radio" name="direction" value="ASC" checked="checked"/> Ascending <br /><input class="indent2" type="radio" name="direction" value="DESC" /> Descending';
    
    $output .= '<br /><h3 class="indent2"><input type="checkbox" name="limit" value="on" /> Limit items to </h3><input class="wikiLimit" name="limitNumber" type="text" value="10" />';
  
    echo $output;
}

getSpecialOrders();

?>