<?php
  // START
  function gurunavi_search_restlist_v3() {
    $ret = FALSE;

    $search_url = "https://app.rakuten.co.jp/services/api/Travel/HotelRanking/20170426?"
    . "applicationId=" . "1030424330987528104"
    . "&format=" . "json"
    . "&carrier=" . "0"
    . "&genre=" . "premium";
    // true配列で
    $json = json_decode(file_get_contents($search_url),true);

    if ($json !== FALSE) {
      $ret = $json['Rankings'];
      if (count($ret) <= 0) {
      $ret = FALSE;
      }
    } 
      return($ret);
  }
  // END



  // 関数呼び出し
  $rests = gurunavi_search_restlist_v3();

  echo('<h1>' . $rests[0]['Ranking']['title'] . '</h1>');

  $rests = $rests[0]['Ranking']['hotels'];

  // 表示
  foreach($rests as $rest) {
    echo('<h2>' . $rest['hotel']['hotelName'] . '</h2>');
    echo('<p>' . $rest['hotel']['middleClassName'] . '</p>');
    echo('<p>' . $rest['hotel']['userReview'] . '</p>');
    echo('<img src="' . $rest['hotel']['hotelImageUrl'] . '" alt="' . $rest['hotel']['hotelName'] . '" width="192">');
    
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