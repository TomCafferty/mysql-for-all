<?php
function singledropdown($Field, $name, $strTableName, $selected) {
   //
   // PHP DYNAMIC DROP-DOWN BOX - HTML SELECT
   //
   // 2006-05, 2008-09, 2009-04 http://kimbriggs.com/computers/
   //
   // Function creates a drop-down box
   // by dynamically querying ID-Name pair from a lookup table.
   //
   // Parameters:
   // Field = Field of table.
   // name = name to POST selection to
   // strTableName = Name of MySQL table containing Field.
   // selected = selected field.
   //
   // Returns:
   // HTML Drop-Down Box Mark-up Code
   //
   $cxn = dbOpen();
 
   $dropdown = '<select name="'.$name.'" id="'.$name.'" style="width:95%" >'."\n";
  
   $strQuery = "select distinct " . $Field . "  from $strTableName order by $Field asc ";
   $rsrcResult = mysqli_query($cxn, $strQuery) or die( mysqli_error( $cxn ));

   while($arrayRow = mysqli_fetch_assoc($rsrcResult)) {
      $strA = $arrayRow["$Field"];
      if ($strA <> $selected) 
          $dropdown .=  "<option value=\"$strA\">$strA</option>\n";
      else
          $dropdown .=  "<option value=\"$strA\" selected='selected'>$strA</option>\n";
   }

   $dropdown .=  "</select>";
   mysqli_close($cxn);

   return $dropdown;
}
?>