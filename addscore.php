<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Guitar Wars - Add Your High Score</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h2>Guitar Wars - Add Your High Score</h2>

  <?php
  //定义一个常量
  require_once('appvars.php');

  if (isset($_POST['submit'])) {
    // Grab the score data from the POST
    $name = $_POST['name'];
    $score = $_POST['score'];
    $screenshot =$_FILES['screenshot']['name'];

    if (!empty($name) && !empty($score) && !empty($screenshot)) {
      //检查上传文件的准确性
      $screenshot_type = $_FILES['screenshot']['type'];
      $screenshot_size = $_FILES['screenshot']['size'];
      if((($screenshot_type=='image/jpeg')||($screenshot_type=='image/gif')||($screenshot_type=='image/png')||($screenshot_type=='image/pjepg'))&&(($screenshot_size>0)&&($screenshot_type <=GW_MAXFILESIZE))){
        //确保无错误
        if($_FILES['file']['error']==0){
          // Connect to the database
          $target = GW_UPLOADPATH.$screenshot;
           //改名改路径
          if(move_uploaded_file($_FILES['screenshot']['tmp_name'],$target)){

            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        // Write the data to the database
            $query = "INSERT INTO guitarwars VALUES (0, NOW(), '$name', '$score','$screenshot')";
            mysqli_query($dbc, $query);

        // Confirm success with the user
            echo '<p>Thanks for adding your new high score!</p>';
            echo '<p><strong>Name:</strong> ' . $name . '<br />';
            echo '<strong>Score:</strong> ' . $score . '</p>';
            echo '<img src="'.$target.'">';
            echo '<p><a href="index.php">&lt;&lt; Back to high scores</a></p>';

        // Clear the score data to clear the form
            $name = "";
            $score = "";

            mysqli_close($dbc);
          }else {
            echo '<p class="error">Sorry,there was a problem uploading your screen shot image.</p>';
          }
        }
      }else{
       echo '<p class="error">The Screen shot must be a GIF,JPEG,or PNG img file no '.
       'greater than '.(GW_MAXFILESIZE/1024).'kb in size</p>';
      }
      //try to delete the temporary screen shot image file
      @unlink ($_FILES['screenshot']['tmp_name']);
   }else {
     echo '<p class="error">Please enter all of the information to add your high score.</p>';
   }

 }
 ?>

 <hr />
 <form enctype = "multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <input type="hidden" name="MAX_FILE_SIZE" value="32768" />
  <label for="name">Name:</label>
  <input type="text" id="name" name="name" value="<?php if (!empty($name)) echo $name; ?>" /><br />
  <label for="score">Score:</label>
  <input type="text" id="score" name="score" value="<?php if (!empty($score)) echo $score; ?>" />
  <hr />
  <input type="file" id="screenshot" name="screenshot" />
  <hr />
  <input type="submit" value="Add" name="submit" />
</form>
</body>
</html>
