<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$path_to_tmp = sys_get_temp_dir() . '/hexo/';

$input = file_get_contents('php://input', 'r');

$xml = new DOMDocument();

//$input ? $xml->loadXML($input) && $xml->save('test.xml') : $xml->load('test.xml');;
empty($input) ? die('empty input!') : $xml->loadXML($input);

$params = $xml->getElementsByTagName('param');

$struct = $params->item(3);

$member = $struct->getElementsByTagName('member');

$member_arr = array();
foreach ($member as $v) {
    $member_name              = $v->getElementsByTagName('name')->item(0)->nodeValue;
    $member_arr[$member_name] = $v->getElementsByTagName('value')->item(0)->nodeValue;
}

$content = $member_arr['description'];

$text = preg_replace('/<div>(.*?)<\/div>/', "$1" . PHP_EOL, $content);//<div>变换行
$text = html_entity_decode($text);
$text = preg_replace("/<[^\!].*?>/", "", $text);//去掉一些类似<a/>的标签
$text = preg_replace('/来自为知笔记\(Wiz\)/', "", $text);

//hexo 的正文是yaml格式
$yaml['title']    = $member_arr['title'];
$yaml['date']     = date('Y-m-d H:i:s', strtotime($member_arr['dateCreated']));
$yaml['category'] = '日志';
$yaml['tags']     = explode(', ', $member_arr['mt_keywords']);
//$hexo['description'] = $member_arr[''];

$post = implode('---' . PHP_EOL, array(Yaml::dump($yaml), $text));

$ret = file_put_contents($path_to_tmp . $yaml['title'] . '.md', $post);

//在tmp文件夹下保存一下日志的备份
$xml->save($path_to_tmp . $yaml['title'] . '.xml');
unset($xml);


exit;

$xml = new XMLWriter();
$xml->openUri('php://output'); // or 'php://output' 
$xml->setIndentString("  ");
$xml->setIndent(TRUE);
// start 
$xml->startDocument('1.0', 'ISO-8859-1');
// <methodResponse> 
$xml->startElement('methodResponse');
// <params> 
$xml->startElement('params');
// <param>
$xml->startElement('param');
// <value> 
$xml->startElement('value');
// <array>
$xml->startElement('array');
// <data>
$xml->startElement('data');
// <value>
$xml->startElement('value');
// <struct>
$xml->startElement('struct');

//<member>
$xml->startElement('member');
//name
$xml->startElement('name');
$xml->text('url');
$xml->endElement();
//value
$xml->startElement('value');
$xml->text('http://blog.xingxuchu.com');
$xml->endElement();
$xml->endElement();

//<member>
$xml->startElement('member');
//name
$xml->startElement('name');
$xml->text('blogid');
$xml->endElement();
//value
$xml->startElement('value');
$xml->text('1');
$xml->endElement();
$xml->endElement();

//<member>
$xml->startElement('member');
//name
$xml->startElement('name');
$xml->text('blogName');
$xml->endElement();
//value
$xml->startElement('value');
$xml->text('test');
$xml->endElement();
$xml->endElement();

$xml->endElement(); //struct 
$xml->endElement(); //value
$xml->endElement(); //data
$xml->endElement(); //array
$xml->endElement(); //value
$xml->endElement(); //param
$xml->endElement(); //params
$xml->endElement(); //methodResponse
$xml->endDocument();
$xml->flush();
