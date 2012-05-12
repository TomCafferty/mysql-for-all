<?php
/**
 * Mysql-for-all: generate mysql query from user selections
 * @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author   Tom Cafferty
 * @email    tcafferty@glocalfocal.com
 *
 *   Processes table selction to display table details and related tables
 *   
 *   Function:
 *      getTable
 *      
 *   Parameters:
 *      None.
 *   
 *   Return value:
 *      None
 *
 */
    global $numOtherTables;
    global $otherTable;

function describeTable($selected_table, $mysqli, $tableNum) {
    global $numOtherTables, $otherTable;
           
    // Include the HTML_Table package
    require_once "HTML/Table.php";
    require_once "singleDdFromDb.php";
            
    // Create an array of table attributes
    $attributes = array('class' => 'dbTable');
        
    // Create the table object
    $table = new HTML_Table($attributes);
    
    // Pass table name back for POST processing
    $tableParam = '<input type="hidden" name="tables['.$tableNum.']" value="'.$selected_table.'"/>'; 
        
    // Set the headers
    $table->setCaption ( $selected_table . " table" . $tableParam);
    $table->setHeaderContents(0, 0, "Field");
    $table->setHeaderContents(0, 1, "Description");
    $table->setHeaderContents(0, 2, "Show");
    $table->setHeaderContents(0, 3, "Filter");
    $table->setHeaderContents(0, 4, "For");
        
    // Set the header scope
    $hrAttrs = array('scope' => 'col');
    $hcAttrs = array('scope' => 'row');
    $table->setCellAttributes(0, 0, $hrAttrs);
    $table->setCellAttributes(0, 1, $hrAttrs);
    $table->setCellAttributes(0, 2, $hrAttrs);
    $table->setCellAttributes(0, 3, $hrAttrs);
    $table->setCellAttributes(0, 4, $hrAttrs);
       
    // Create and execute the query
    $query = 'SHOW FULL COLUMNS FROM '.$selected_table;

    // Proceed with the query
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $stmt->bind_result($field, $type, $collation, $null, $key, $default, $extra, $priveleges, $comment);
        
    // Begin at row 1 so don't overwrite the header
    $rownum        = 1;
    $numRelations  = 0;
    $relationParam = '';
   
    $relation      = array();
    $show          = array();
    
    // Format each row
    while ($stmt->fetch()) {
        //
        // process relations row info 
        $pos = strpos($comment, '#db_Relation:');
        if ($pos !== false) {
            
            // process relation parameters of other table and field
            $otherTable[$numOtherTables] = strtok(substr($comment, $pos+13),' ');
            $otherField = strtok(" ");
            $length     = strlen($otherTable[$numOtherTables]) + strlen($otherField) + 2;

            // Create relationship string
            $relationParam .= '<input type="hidden" name="relation['.$tableNum.']['.$numRelations.']" value="'.$selected_table.'.'.$field. ' = ' . $otherField .'"/>'; 
            $numOtherTables++;
            $numRelations++;

            // remove internal comments (ie: relation)
            $comment = substr_replace($comment, '', $pos+13, $length);
            $comment = preg_replace('/#db_Relation:/', '', $comment, 1);
        } 
        //
        // skip the row if NoDisplay is set
        if (strpos($comment, '#db_NoDisplay') === false) {
            
            // Set first column data and Pass relationship back for POST processing (once)
            $table->setHeaderContents($rownum, 0, $field. $relationParam);
            $relationParam = '';
            
            // Set third column as checkbox to select if showing
            $checkBox = '<input type="checkbox" value="'.$field.'" name="show['.$tableNum.'][]">';
            $table->setCellContents($rownum, 2, $checkBox);
            
            // if Filter parameter Set forth column as checkbox to select filtering
            if (strpos($comment, '#db_Filter') !== false) {
                $checkBox = '<input type="checkbox" value="'.$selected_table.'.'.$field.'" name="filter['.$tableNum.'][]">';
                $table->setCellContents($rownum, 3, $checkBox);
                
                // if default parameter get default for selected drop down element               
                $pos = strpos($comment, '#db_Default:');
                if ($pos !== false) {
                    $selected = strtok(substr($comment, $pos+12),' ');
                    // remove internal comments (ie: default)
                    $length   = strlen($selected);
                    $comment = substr_replace($comment, '', $pos+12, $length+1);
                    $comment = preg_replace('/#db_Default:/', '', $comment, 1);
                }
                else
                    $selected = NULL;
                
                // get drop down values from database for table and field and set in fifth column   
                $ddName = $selected_table.'~#~'. $field;      // workaround for php replacing dot (.) with underscore on variable names
                $dropDown = singledropdown($field, $ddName, $selected_table, $selected);
                $table->setCellContents($rownum, 4, $dropDown);
                // remove internal comments (ie: filter)
                $comment = preg_replace('/#db_Filter/', '', $comment, 1);
            }
            
            // Now that internal comments were removed, display comment in second column
            $table->setCellContents($rownum, 1, $comment);
            
            // Set first column as a header, and alternate row text colors
            $table->setCellAttributes($rownum, 0, $hcAttrs);
            $rownum++;
            $table->altRowAttributes(1, null, array("class"=>"alt"), true);
        }
    }

    // Output the data
    return ($table->toHTML()).$relationParam;
}

function getTable() {
    global $numOtherTables, $otherTable;
    
    $output= '';
    $numOtherTables = 0;

    if (isset($_POST['tableSelected'])) 
        $selected_table = $_POST['tableSelected'];
    else {
        echo '<h3><span class="error"> You did not select a table. </span></h3>';
        return;
    }
    //
    // Connect to database
    //
    if ($_SERVER['SERVER_NAME'] == 'localhost') {
        define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/mysite');
    } else {
        define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
    } 
    require_once( DOC_ROOT . '/protectedIncludes/opendb.php' );
    $mysqli = dbOpen();
    
    // Process first table        
    $output .= describeTable($selected_table, $mysqli, $numOtherTables);
    
    // Process next table
    while ($numOtherTables > 0) {
        $numOtherTables--;
        $output .=  describeTable($otherTable[$numOtherTables], $mysqli, $numOtherTables+1);
    }
    echo $output;
     
    // Close the MySQL connection
    $mysqli->close();
}

getTable();

?>