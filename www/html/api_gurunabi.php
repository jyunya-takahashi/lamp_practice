<?php
  // START
  function gurunavi_search_restlist_v3($hit_per_page=30, $offset_page=1, $freeword) {
    $ret = FALSE;

    $search_url = "https://api.gnavi.co.jp/RestSearchAPI/v3/?"
    . "keyid=" . "c5d5b3fdf374b1839bcee6623a23716c"
    . "&hit_per_page=" . $hit_per_page
    . "&offset_page=" . $offset_page
    . "&freeword=" . urlencode($freeword);

    // true配列で
    $json = json_decode(file_get_contents($search_url),true);

    if ($json !== FALSE) {
      $ret = $json['rest'];
      // $n = 0;
      // foreach ($json->rest as $shop) {
      //   $ret[$n]['id'] = (string)$shop->id;
      //   $ret[$n]['name'] = (string)$shop->name;
      //   $ret[$n]['address'] = (string)$shop->address;
      //   $ret[$n]['image_url'] = (string)$shop->address;
      //   $n++;
      // }
      if (count($ret) <= 0) {
      $ret = FALSE;
      }
    }
      return($ret);
  }
  // END


  $hit_per_page = 20;
  $page_no = 1;
  $freeword = "ラーメン";

  // 関数呼び出し
  $rests = gurunavi_search_restlist_v3($hit_per_page, $page_no, $freeword);

  // 表示
  foreach ($rests as $rest) {
    echo('<h2>' . $rest['name'] . '</h2>');
    echo('<center>');
    echo('<img src="' . $rest['image_url']['shop_image1'] . '" alt="' . $rest['name'] . '" width="192">');
    echo('</center>');
    echo('<br />');
    echo('<b><a href="' . $rest['url'] . '" target="_blank" rel="nofollow noopener">' . $rest['name'] . '</a></b>
  ');
    echo("<b>住所</b> " . $rest['address'] . "<br />");
    echo("<b>アクセス</b> " . $rest['line'] . ' ' . $rest['station'] . ' ' . $rest['station_exit'] . " 徒歩" .  $rest['walk'] . "分 " . "<br />");
  }
  //中略
  
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>api</title>
</head>

<body>

   
</body>
</html>