<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
  <link rel="stylesheet" href="/mysite/style/style_4.css" type="text/css"  />
</head>

<body>
  <div id="page" class="page_data-for-wiki-step-2">
    <div class="roundcont"> 
      <FORM class="db_form" name ="dbTablesStep2" method ="post" action ="step3Form.php">
        <h2 class='indent2'>Step 2 of 3: What data do you want to see?</h2>
        <?php 
         require ('outFields.php'); 
        ?>
        <input id="submit" class="submit" type="submit" Name = "Submit1" value="Next >> " />
      </FORM>
    </div>  <!-- close #roundcont -->
  </div>  <!-- close #page -->
</body>

</html>