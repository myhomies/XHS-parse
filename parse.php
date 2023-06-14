<?php
$url = $_GET['url'];
$cookie = '';  // 填入自己的cookie

function fetchUrl($url) {
    $headers = [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 UBrowser/6.2.4098.3 Safari/537.36',
        'Cookie: ' . $GLOBALS['cookie']
    ];

    $options = [
        'http' => [
            'header' => implode("\r\n", $headers)
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    return $response;
}

function getRealUrl($shortUrl) {
    $options = [
        'http' => [
            'method' => 'HEAD',
            'follow_location' => 1,
            'max_redirects' => 10,
        ],
    ];
    $context = stream_context_create($options);
    $headers = get_headers($shortUrl, 1, $context);
    if (isset($headers['Location'])) {
        return $headers['Location'];
    }
    return $shortUrl;
}

function extractItemId($url) {
    $pattern = '/\/discovery\/item\/(.*?)\?/';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    return $url;
}

try {
    $html = fetchUrl($url);

    $pattern = '/"traceId":"(.*?)"/';
    $imageUrls = [];

    if (preg_match_all($pattern, $html, $matches)) {
        $traceIds = $matches[1];

        foreach ($traceIds as $traceId) {
            $picUrl = 'https://sns-img-qc.xhscdn.com/' . $traceId;
            $imageUrls[] = $picUrl;
        }
    }

    $convertedUrls = [];
    foreach ($imageUrls as $imageUrl) {
        $realUrl = getRealUrl($imageUrl);
        $convertedUrl = extractItemId($realUrl);
        $convertedUrls[] = $convertedUrl;
    }

    $response = [
        'status' => 'success',
        'message' => 'Image URLs extracted successfully',
        'imageUrls' => $convertedUrls
    ];
} catch (Exception $e) {
    $response = [
        'status' => 'error',
        'message' => $e->getMessage(),
        'imageUrls' => []
    ];
}

// 将数组转换为 JSON 字符串输出
echo json_encode($response);
