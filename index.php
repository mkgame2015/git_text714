<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Guitar Wars - High Scores</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>

</style>
<body>
  <h2>Guitar Wars - High Scores</h2>
  <p>Welcome, Guitar Warrior, do you have what it takes to crack the high score list? If so, just <a href="addscore.php">add your own score</a>.</p>
  <hr />

<?php
  require_once('appvars.php');
  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

  // Retrieve the score data from MySQL
  $query = "SELECT * FROM guitarwars order by score desc,date asc";
  $data = mysqli_query($dbc, $query);

  // Loop through the array of score data, formatting it as HTML
  echo '<table>';

  $i = 0;
  while ($row = mysqli_fetch_array($data)) {
    if($i==0){
      echo '<tr><td colspan="2" class="topscoreheader" >Top score:'.$row['score'].'</td></tr>';
    }
    // Display the score data
    echo '<tr><td class="scoreinfo">';
    echo '<span class="score">' . $row['score'] . '</span><br />';
    echo '<strong>Name:</strong> ' . $row['name'] . '<br />';
    echo '<strong>Date:</strong> ' . $row['date'] . '</td>';

    //获取图片引用
    if(is_file(GW_UPLOADPATH.$row['screenshot']) && filesize(GW_UPLOADPATH.$row['screenshot'])>0 ){
        echo '<td><img src="'.GW_UPLOADPATH.$row['screenshot'].'" alt="score image" width=320 height=240 /></td> </tr>';
    }
    else{
       echo '<td><img src="'.GW_UPLOADPATH.'unverified.gif" alt="没图片" /></td></tr>';
    }
    $i++;
  }
  echo '</table>';

  mysqli_close($dbc);
?>

</body>
</html>