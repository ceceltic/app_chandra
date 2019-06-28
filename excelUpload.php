<?php
require('library\php-excel-reader\excel_reader2.php');
require('library\SpreadsheetReader.php');
require('config.php');

$type = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
if(isset($_POST['submit']))
{
  $xls=$_FILES["file"];
  $file_name=$xls['name'];
  $file_tmpname=$xls['tmp_name'];
  $file_type=$xls["type"];
  // print_r($file_type);
  // exit;
  //$upload_path='upload/'.time().$file_name;
  $upload_path='upload/'.$file_name;
  if(in_array($file_type,$type))
    {               //if 2 Start
      
      move_uploaded_file($file_tmpname,$upload_path);
      $Reader= new SpreadsheetReader($upload_path);
      $totalSheet = count($Reader->sheets());
      /* For Loop for all sheets */
      $data=array();
      for($i=0;$i<$totalSheet;$i++)   //For loop 1
      {
          $Reader->ChangeSheet($i);

          foreach ($Reader as $Row)
          {
             array_push($data,$Row);
           }
           print_r($data);

           $t_rows=count($data);
          for ($row = 1; $row < $t_rows; $row++) 
          { //for loop 2
            //$fld_count =count($data[$row]);
             $name=$data[$row][0];
                $fname=$data[$row][1];
               $mobile=$data[$row][2];
               if (!empty($name) || !empty($fname)||!empty($mobile))
               {
                 $query = "insert into xls_tbl(name,fname,mobile) values('".$name."','".$fname."','".$mobile."')";
                 $result = mysqli_query($conn, $query);
                 
               }
               else{
                 echo "alert('false')";
               }

          }
        }




    }
    else
    { 
   
          echo "<script>alert('Invalid File Type. Upload Excel File.')</script>";
    }

} //if 1 close
else{
  echo "<script>alert('Select File');
  return false;
  </script>";
}

?>