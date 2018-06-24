<?php

define('BASEDIR',__DIR__);
spl_autoload_register(function ($class) {
    include BASEDIR.'/'.str_replace('\\','/',$class).'.php';
});

require 'vendor/autoload.php';

//抓取数据
use GuzzleHttp\Psr7\Request;
$client = new GuzzleHttp\Client();
$request = new Request('GET', 'http://www.weather.com.cn/weather1d/101020100.shtml');
$header = [
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
        'Accept-Encoding'=>'gzip, deflate',
        'Accept-Language'=>'en-US,en;q=0.9,zh-CN;q=0.8,zh;q=0.7',
        'Cache-Control'=>'no-cache',
        'Connection'=>'keep-alive',
        'Cookie'=>'vjuids=156de4a55.1642b59cd29.0.e80436fc18e6b; userNewsPort0=1; f_city=%E4%B8%8A%E6%B5%B7%7C101020100%7C; returnUrl=%2Fweb%2Fdashboard%2Findex.do; vjlast=1529735663.1529803755.13; Hm_lvt_080dabacb001ad3dc8b9b9049b36d43b=1529735663,1529803755; Wa_lvt_1=1529735664,1529803755; UM_distinctid=164304789cd423-0b1e385864be1e-5e452019-1fa400-164304789d074; Hm_lpvt_080dabacb001ad3dc8b9b9049b36d43b=1529820104; CNZZDATA1262608253=1265480076-1529813414-%7C1529818814; Wa_lpvt_1=1529820104; defaultCity=101280601; defaultCityName=%u6DF1%u5733',
        'Host'=>'www.weather.com.cn',
        'Referer'=>'http://www.weather.com.cn/weather1d/101020100.shtml',
        'Pragma'=>'no-cache',
        'Upgrade-Insecure-Requests'=>'1',
        'User-Agent'=>'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.87 Safari/537.36'
    ];
$response = $client->send($request, ['headers' => $header]);

$body = $response->getBody();

$re = '/var hour3data=(.*)/m';

preg_match_all($re, $body, $matches, PREG_SET_ORDER, 0);

$weather_arr = json_decode($matches[0][1],true);
$oneDay = $weather_arr['1d'];


$content = "<p align='center'>";
foreach($oneDay as $key=>$value){
    $content .= "$value<br>";
}
$content .= "</p>";

$contents = <<< EOF
<p align="center">
$content
</p>
EOF;

//发送邮件通知
use Mailer\Email;
Email::sendMail('940508445@qq.com','天气预报',$contents);