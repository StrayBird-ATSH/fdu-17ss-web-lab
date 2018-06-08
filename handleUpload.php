<?php
/**
 * Created by PhpStorm.
 * User: StrayBird_ATSH
 * Date: 07-Jun-18
 * Time: 17:43
 */
$fileName = $_FILES["file_upload"]["name"];
$fileName = "./files/" . substr($fileName, 0, strlen($fileName) - 3) . "lrc";
$fileToMove = $_FILES['file_upload']['tmp_name'];
$destination = "./files/" . $_FILES["file_upload"]["name"];
if (move_uploaded_file($fileToMove, $destination)) {
  echo "The file was uploaded and moved successfully!";
} else {
  echo "there was a problem moving the file";
}
$lyricData = $_POST['edit_lyric'];
file_put_contents($fileName, $lyricData);