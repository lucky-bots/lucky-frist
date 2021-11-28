<?php 
  /*Get Data From POST Http Request*/
  $datas = file_get_contents('php://input');
  /*Decode Json From LINE Data Body*/
  $deCode = json_decode($datas,true);

  file_put_contents('log.txt', file_get_contents('php://input') . PHP_EOL, FILE_APPEND);

  $replyToken = $deCode['events'][0]['replyToken'];
  $userId = $deCode['events'][0]['source']['userId'];
  $text = $deCode['events'][0]['message']['text'];

  $messages = [];
  $messages['replyToken'] = $replyToken;
  $messages['messages'][0] = getFormatTextMessage("เอ้ย ถามอะไรก็ตอบได้");

  $encodeJson = json_encode($messages);

  $LINEDatas['url'] = "https://api.line.me/v2/bot/message/reply";
    $LINEDatas['token'] = "ZtOyNyDbjRMWp3PeP3pvQWatE0XyR7vyaSS2dFKRKn23U01WAVDBEUVin7PYbVb7I8Mnkmo0MndOnJN5ucH5/TKYyIERPbJ5DB/Fkehg62gVEzWsdWyQMCRg/1Okyls>`";

    $results = sendMessage($encodeJson,$LINEDatas);

  /*Return HTTP Request 200*/
  http_response_code(200);

  function getFormatTextMessage($text)
  {
    $datas = [];
    $datas['type'] = 'text';
    $datas['text'] = $text;

    return $datas;
  }

  function sendMessage($encodeJson,$datas)
  {
    $datasReturn = [];
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $LINEdatas['url'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $encodeJson,
      CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".$LINEdatas['token'],
        "cache-control: no-cache",
        "content-type: application/json; charset=UTF-8",
      ),
    ));  

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        $datasReturn['result'] = 'E';
        $datasReturn['message'] = $err;
    } else {
        if($response == "{}"){
      $datasReturn['result'] = 'S';
      $datasReturn['message'] = 'Success';
        }else{
      $datasReturn['result'] = 'E';
      $datasReturn['message'] = $response;
        }
    }

    return $datasReturn;
  }
?>
