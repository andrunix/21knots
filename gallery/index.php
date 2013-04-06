<?php

if (isset($_GET['img'])) {
	// make thumbnail
	if (file_exists($_GET['img'])) {
		ignore_user_abort(true);
		set_time_limit(120);
		# ini_set('memory_limit', '5124');

		$src_size = getimagesize($_GET['img']);
		print_r($src_size);
		echo "<br/>";

		if ($src_size == false) {
			// 
			die('That does not look like an image');
		}

		$thumb_width  = 250;
		$thumb_height = 200;

		if ($src_size['mime'] === 'images/jpeg') {
			$src = imagecreatefromjpeg($_GET['img']);
		} else if ($src_size['mime'] === 'image/png') {
			$src = imagecreatefrompng($_GET['img']);
		} else {
			// the image type is not supported
		}

		$src_aspect = round($src_size[0] / $src_size[1], 1);
		$thumb_aspect = round($thumb_width / $thumb_height, 1);

		if ($src_aspect < $thumb_aspect) {
			// higher 
			$new_size = array(
				$thumb_width,	($thumb_width / $src_size[0]) * $src_size[1]);
			$src_pos = array(0, (($new_size[1] - $thumb_height) * ($src_size[1] / $new_size[1])) / 2);

		} else if ($src_aspect > $thumb_aspect) {
			// wider
			$new_size = array(
				($thumb_width / $src_size[1]) * $src_size[0],
				$thumb_height);
			$src_pos = array((($new_size[0] - $thumb_width) * ($src_size[0] / $new_size[0])) / 2, 0);
		} else {
			// same shape
			$new_size = array($thumb_width, $thumb_height);
			$src_pos = array(0, 0);
		}

		if ($new_size[0] < 1) $new_size[0] = 1;
		if ($new_size[1] < 1) $new_size[1] = 1;

		print_r($new_size);
		print_r($src_pos);

		echo "Calling imagecreatetruecolor()<br/>";
		$thumb = imagecreatetruecolor($thumb_width, $thumb_height);

		echo "Calling imagecopyresampled() <br/>";

		imagecopyresampled($thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $src_size[0], $src_size[1]);

		if ($src_size['mime'] === 'images/jpeg') {
			imagejpeg($thumb, './thumbs/' . $_GET['img']);
		} else if ($src_size['mime'] === 'image/png') {
			imagejpeg($thumb, './thumbs/' . $_GET['img']);
		} else {
			// the image type is not supported
		}

		header("Location: thumbs/{$GET['img']}");
	}

	die();

}


if (!is_dir('./thumbs')) {
	mkdir ('./thumbs', 0755);
}

$images = glob('*.{jpg,jpeg,png}', GLOB_BRACE);
?>

<html>
  <head>
  </head>
  <body>
  	<div>

  		<?php
  		
  		foreach($images as $img) {
  			if (file_exists('./thumbs/$img')) { ?>
  				<a href="<?php echo $image ?>"><img src="thumbs/<?php echo $image ?>"><?php echo $image ?></a>
  				<?php
  			} else { ?>
  				<a href="<?php echo $image ?>"><img src="?img=/<?php echo $image ?>"><?php echo $image ?></a>
  				<?php
  			}
  		}
  		?>

  	</div>
  </body>
</html>
