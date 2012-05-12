<?php
/***********************************************************************
  Mysql-for-all: Creates a mysql query based on mysql database comments and user selections
  
  @license  GPL 2 (http://www.gnu.org/licenses/gpl.html)
  author    Tom Cafferty
  email     tcafferty@glocalfocal.com
  date      2012-05-10
  version   1.0
  
  Output a table of database tables based on two overview tables. Provide a checkbox for selection of the table to display.
  The two overview tables required in your database are:
  
  tables - contains all tables in the database. The important table elements are:
           name   - table name
           brief  - brief description of the table
           date   - latest date of the data in the table
           author - user that submitted the table data to your website
           helper - a boolean to identify a table as a support table (=1) and not displayed
  
  source - contains the source reference for where the data was obtained for a table. The important table elements are:
           name    - name of source
           website - url of source
   
   Function:
      outTable
      
   Requirements:
      PEAR HTML_Table package
      Your server needs to provide the PEAR package HTML_Table for use by this software.  
      If not provided the package is available at
      http://pear.php.net/package/HTML_Table 
   
   Parameters:
      None.
   
   Return value:
      None

***********************************************************************/
// convert a url and name to a clickable html link
function makeClickableLinks($s,$name) {
    return preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.-]*(\?\S+)?)?)?)@', '<a href="$1">'.$name.'</a>', $s);
}

function outTable() {    
    // Include the HTML_Table package
    require_once "HTML/Table.php";
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
    //
    // Initialize counters
    $pagesize = 8;
    $recordstart = (int) $_GET['recordstart'];
    $recordstart = (isset($_GET['recordstart'])) ? $recordstart : 0;

    // Create an array of table attributes
    $attributes = array('class' => 'dbTable');

    // Create the table object
    $table = new HTML_Table($attributes);

    // Set the headers
    $table->setCaption ( "Database Tables" );
    $table->setHeaderContents(0, 0, "Table");
    $table->setHeaderContents(0, 1, "Date");
    $table->setHeaderContents(0, 2, "Submitted");
    $table->setHeaderContents(0, 3, "Source");
    $table->setHeaderContents(0, 4, "Select");
    
    // Set the header scope
    $hrAttrs = array('scope' => 'col');
    $hcAttrs = array('scope' => 'row');
    $table->setCellAttributes(0, 0, $hrAttrs);
    $table->setCellAttributes(0, 1, $hrAttrs);
    $table->setCellAttributes(0, 2, $hrAttrs);
    $table->setCellAttributes(0, 3, $hrAttrs);
    $table->setCellAttributes(0, 4, $hrAttrs);
    
    // Get number of rows
    $result = $mysqli->query("SELECT count(tables.name) AS count FROM tables,source WHERE tables.source = source.id AND NOT helper");
    list($totalrows) = $result->fetch_row();
    	
    // Create and execute the query
    $sort = (isset($_GET['sort'])) ? $_GET['sort'] : "brief";
    $query = "SELECT tables.name,brief,date,author,source.name,website FROM tables,source WHERE tables.source = source.id AND NOT helper ORDER BY $sort ASC LIMIT ?, ?";
    
    $columns = array('brief','date','author','source.name');
    if (in_array($sort, $columns)) {
        // Proceed with the query
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ii", $recordstart, $pagesize);
        $stmt->execute();
        $stmt->bind_result($name, $content, $date, $author, $source, $link);
        
        // Begin at row 1 so don't overwrite the header
        $rownum = 1;
        $radioButton = '<input type="radio" value=$content name="tableSelected">';
        // Format each row
        while ($stmt->fetch()) {
            $table->setHeaderContents($rownum, 0, $content);
            $table->setCellContents($rownum, 1, $date);
            $table->setCellContents($rownum, 2, $author);
            $table->setCellContents($rownum, 3, makeClickableLinks($link, $source));
            $radioButton = '<input type="radio" value="'.$name.'" name="tableSelected">';
            $table->setCellContents($rownum, 4, $radioButton);
            $table->setCellAttributes($rownum, 0, $hcAttrs);
            $rownum++;
            $table->altRowAttributes(1, null, array("class"=>"alt"), true);
        }
        // Output the data
        echo $table->toHTML();
    }

    // Close the MySQL connection
    $mysqli->close();
}

outTable();

?>