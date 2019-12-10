<?php

require("_sec.php");

$ranks = array(
  "RANK_USER" => 0,
  "RANK_BLOGGER" => 1,
  "RANK_ADMIN" => 2,
);

foreach ($ranks as $level => $rank) {
  define($rank, $level);
}

$ranks_str = array(
  [RANK_USER] => "",
  [RANK_BLOGGER] => "Verified Blogger",
  [RANK_ADMIN] => "Administrator",
);

$ranks_col = array(
  [RANK_USER] => "",
  [RANK_BLOGGER] => "text-primary",
  [RANK_ADMIN] => "text-danger",
);

?>
