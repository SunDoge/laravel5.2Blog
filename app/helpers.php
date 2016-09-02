<?php

/**
 * 调用百度翻译接口,中译英文章title
 *
 * @param $string
 * @param bool $raw
 * @return string
 */
function translateZHtoENG($string, $raw = false)
{
    $data = [
        'from' => 'zh',
        'to' => 'en',
        'query' => $string,
        'transtype' => 'translang',
        'simple_means_flag' => '3',
    ];
    $query = http_build_query($data);
    $aContext = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => $query,
        ],
    ];

    $cxContext = stream_context_create($aContext);

    $sUrl = config('blog.api.translation'); //此处必须为完整路径

    $result = file_get_contents($sUrl, false, $cxContext);

    if (!$raw) {
        $result = json_decode($result, true)['trans_result']['data'][0]['dst'];
    }
    return $result;
}

/**
 * 返回可读性更好的文件尺寸
 */
function human_filesize($bytes, $decimals = 2)
{
    $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
    $factor = floor((strlen($bytes) - 1) / 3);

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

/**
 * 判断文件的MIME类型是否为图片
 */
function is_image($mimeType)
{
    return starts_with($mimeType, 'image/');
}

/**
 * Return "checked" if true
 */
function checked($value)
{
    return $value ? 'checked' : '';
}

/**
 * Return img url for headers
 */
function page_image($value = null)
{
    if (empty($value)) {
        $value = config('blog.page_image');
    }
    if (!starts_with($value, 'http') && $value[0] !== '/') {
        $value = config('blog.uploads.webpath') . '/' . $value;
    }

    return $value;
}