<?php
function generateLink($url, $label, $class)
{
  $link = '<a href="' . $url . '" class="' . $class . '">';
  $link .= $label;
  $link .= '</a>';
  return $link;
}

function outputPostRow($number)
{
  include("travel-dataForLab08.php");
  $postLink = 'post.php?id=' . ${"postId" . $number};
  $image = '<img src="images/' . ${"thumb" . $number} . '" alt="' . ${"title" . $number} . '" class="img-responsive"/>';
  $user = utf8_encode(${"userName" . $number});
  $userLink = generateLink("user.php?id=" . ${"userId" . $number}, $user, null);
  echo '<div class="row">';
  echo '<div class="col-md-4">';
  echo generateLink($postLink, $image, null);
  echo '</div>';
  echo '<div class="col-md-8"> ';
  echo '<h2>' . ${"title" . $number} . '</h2>';
  echo '<div class="details">';
  echo 'Posted by ' . $userLink;
  echo '<span class="pull-right">' . ${"date" . $number} . '</span>';
  echo '<p class="ratings">';
  echo constructRating(${"reviewsRating" . $number});
  echo ' ' . ${"reviewsNum" . $number} . ' Reviews';
  echo '</p>';
  echo '</div>';
  echo '<p class="excerpt">';
  echo ${"excerpt" . $number};
  echo '</p>';
  echo '<p>' . generateLink($postLink, 'Read more', 'btn btn-primary btn-sm') . '</p>';
  echo '</div>';
  echo '</div>';
  echo '<hr/>';
}

function constructRating($rating)
{
  $imgTags = "";
  for ($i = 0; $i < $rating; $i++) {
    $imgTags .= '<img src="images/star-gold.svg" width="16" />';
  }
  for ($i = $rating; $i < 5; $i++) {
    $imgTags .= '<img src="images/star-white.svg" width="16" />';
  }
  return $imgTags;
}