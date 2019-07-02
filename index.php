

<?php

$fileTypeError = false;
$noFileChoosen = false;
$colors =[];
$image;
if (empty($_FILES['fileToUpload']['tmp_name'])) {
    // No file was selected for upload, your (re)action goes here
    $noFileChoosen = true;
} else {
      if(isset($_POST["submit"])) {
      $fileType = $_FILES["fileToUpload"]["type"];
      $folder="/uploads";
      if ( $fileType == "image/png" || $fileType == "image/jpeg"){
        $fileTypeError = false;
        $image = $_FILES["fileToUpload"]["tmp_name"];
        $destination_path = getcwd().DIRECTORY_SEPARATOR;
        $target_path = $destination_path . basename( $_FILES["fileToUpload"]["name"]);
	{
	}


        $colors = detectColors($image,6);
      }else{
        $fileTypeError=true;
      }
      
    }
}


function detectColors($image, $num) {
    $palette = array();
    $size = getimagesize($image);
    if(!$size) {
      return FALSE;
    }
    switch($size['mime']) {
      case 'image/jpeg':
        $img = imagecreatefromjpeg($image);
        break;
      case 'image/png':
        $img = imagecreatefrompng($image);
        break;
      default:
        return FALSE;
    }
    if(!$img) {
      return FALSE;
    }
    for($i = 0; $i < $size[0]; $i++) {
      for($j = 0; $j < $size[1]; $j++) {
        $thisColor = imagecolorat($img, $i, $j);
        $rgb = imagecolorsforindex($img, $thisColor); 
        $color = sprintf('%02X%02X%02X', (round(round(($rgb['red'] / 0x33)) * 0x33)), round(round(($rgb['green'] / 0x33)) * 0x33), round(round(($rgb['blue'] / 0x33)) * 0x33));
        $palette[$color] = isset($palette[$color]) ? ++$palette[$color] : 1;  
      }
    }
    arsort($palette);
    return array_slice(array_keys($palette), 0, $num);
  }
?>
<!DOCTYPE html>
<html>
<link class="jsbin" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<body class="container">
<div class="row text-center">
<div class="col-12 text-center py-5">
<form method="post" enctype="multipart/form-data">
    <span>Select image to upload:</span>
    <input type="file" name="fileToUpload" id="fileToUpload" onchange="readURL(this);">
    <input class="btn btn-success" type="submit" value="Test image hex/rgb" name="submit">
</form>
</div>
<div class="col-6 text-center">
<ol class="list-group">
  <?php
  if($fileTypeError){
    echo('<h3>Please select only jpeg/png files.</h3>');
    echo('<p>Your File:'.$_FILES["fileToUpload"]['name'].'</p>');
  }
  ?>
  <?php
  if($noFileChoosen){
    echo('<h3>You didnt select any image, please select an image for testing.</h3>');
  }
  ?>
<?php

if(count($colors) > 0){
  echo '<h4>Image color testing results.</h4>';
  foreach($colors as $color){
    echo('<li class="list-group-item" style="color:#'.$color.'"> Hex:'.$color.' / Rgb:(' . implode(",", sscanf($color, "%02x%02x%02x")) . ') </li>');
  }
}
?>

</ol>
</div>

<div class="col-6 center">
<img id="blah" src="#" alt="your image" value="<?php if(isset($_POST["submit"]))  echo $image ?>" />
</div>
</div>
</table>
</body>
</html>

<script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    

