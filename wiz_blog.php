<?php

$path_to_tmp = sys_get_temp_dir() . '/hexo/';

$input = file_get_contents('php://input', 'r');
$xml   = simplexml_load_string($input);

$params = $xml->params;

$params_struct = $params->param[3]->value->struct;

$content    = $params_struct->member[1]->value->string;
$file_title = html_entity_decode($params_struct->member[4]->value->string, ENT_COMPAT, 'UTF-8');

//file_put_contents($file_title.'.html', $content);

$text = preg_replace('/<div>(.*?)<\/div>/', "$1\n", $content);
$text = html_entity_decode($text);
$text = preg_replace("/<.*?>/", "", $text);
$text = preg_replace('/(.*)\*\*\*BEGIN\*\*\*(.*)\*\*\*END\*\*\*.*$/s', '$1$2', $text);

$ret = file_put_contents($path_to_tmp . $file_title . '.md', $text);

//在tmp文件夹下保存一下日志的备份
$xml->asXML($path_to_tmp . $file_title . '.xml');
unset($xml);


$xml = new XMLWriter();
$xml->openUri('php://output'); // or 'php://output' 
$xml->setIndentString("  ");
$xml->setIndent(true);
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
