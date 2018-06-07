<?php
/**
 * Created by PhpStorm.
 * User: StrayBird_ATSH
 * Date: 07-Jun-18
 * Time: 17:43
 */
if (isset($_POST["myForm"])) {
  $temporaryMusicFile = $_FILES["myForm"]["tmp_file"];
  echo $temporaryMusicFile;
}

