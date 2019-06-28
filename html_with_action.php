<?php
require('library\php-excel-reader\excel_reader2.php');
require('library\SpreadsheetReader.php');
require('config.php');
$msg="";
$type = array('text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
if(isset($_POST['submit']))
{
    $is_active="1";
    $is_popular="0";
    $created_on=date("Y-m-d");
   

    $xls=$_FILES["file"];
    $file_name=$xls['name'];
    $file_tmpname=$xls['tmp_name'];
    $file_type=$xls["type"];
    if($file_name)
    {
        //$upload_path='upload/'.time().$file_name;
        $upload_path='upload/'.$file_name;
        if(in_array($file_type,$type))
            {
               // $upload_path=$xls['name']['tmp_name'];
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
                       $brand_code=$data[$row][0];
                          $model_code=$data[$row][1];
                          $variant_name=$data[$row][2];
                          $variant_code=$data[$row][3];
                        
                           $query = "insert into gibl_bike_variants (brand_code,model_code,variant_name,variant_code,is_active,is_popular,created_on) 
                           values('".$brand_code."','".$model_code."','".$variant_name."','".$variant_code."',$is_active,$is_popular,'$created_on')";
                           $result = mysqli_query($conn, $query);
                         
                         
                         
                     } //for loop 2 close
        
                 } //for loop 1 close


            }
        else{
            $msg="Only xlsx file acceptable";
            }
    }
    else
    {
$msg="PLease Select a file";
    }

}

?>





<!DOCTYPE html>
<html>
<head>
<title>Excel Uploading PHP</title>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="container">
<h1>Excel Upload</h1>

<form method="post" enctype="multipart/form-data">
<div class="form-group">
<label>Upload Excel File</label>
<input type="file" name="file" class="form-control">
</div>
<div class="form-group">
<button type="submit" name="submit" class="btn btn-success">Upload</button>
</div>

</form>
</div>

<?php 
     echo $msg;
      
      ?>
    
         

</body>
</html>
