<!doctype html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <title>File Upload with Progress Bar</title>

  <!--style sheets-->
  <link rel="stylesheet" type="text/css" href="css/pure.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">

  <script type="text/javascript">
    // select file function only for styling up input[type="file"]
    function select_file(){
      document.getElementById('image').click();
      return false;
    }
  </script>

</head>
<body>
  <div class="container">

    <!--status message will appear here-->
    <div class="status"></div>

    <!--image upload form-->
    <form class="pure-form" action="upload.php" enctype="multipart/form-data" method="post">

      <div class="upload">
        <a onclick="select_file()" class="pure-button">Choose a Image</a>
        <input id="image" type="file" name="image" >
      </div>

      <!--image preview-->
      <img src="" style="display:none">

      <input class="pure-button pure-button-primary" type="submit" value="Upload!">
    </form>

    <!--progress bar-->
    <div class="progress">
          <div class="bar"></div >
          <div class="percent">0%</div >
      </div>

  </div>

  <!--scripts include-->

  <!-- jQuery Library-->
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

  <!-- jQuery Form Plug in -->
  <script type="text/javascript" src="js/jquery.form.min.js"></script>

  <!-- our main javascript file -->
  <script type="text/javascript" src="js/script.js"></script>

</body>
</html>