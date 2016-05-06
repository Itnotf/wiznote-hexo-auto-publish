<?php
/*
 * 将wiz note发布过来的内容生成md文件保存到sourse文件夹中
*/
error_reporting(0);

header('Content-Type: text/xml; charset=utf-8');

use Symfony\Component\Yaml\Yaml;

require __DIR__ . '/vendor/autoload.php';

//如果不存在/path_to_tmp/hexo/,新建一个，777权限
$path_to_tmp = sys_get_temp_dir() . '/hexo/';
if (!is_dir($path_to_tmp))
    mkdir($path_to_tmp, '0777') || die(errorResponse(0, '创建文件夹失败'));;

//获取输入流，如果为空，返回
$input = file_get_contents('php://input', 'r');
if (empty($input))
    die(errorResponse(0, '内容为空'));

try {
    $xml = new DOMDocument();
    $xml->loadXML($input);

    //将传过来的内容保存在$member数组里面
    $methodCall = $xml->getElementsByTagName('methodName')->item(0)->nodeValue;//metaWeblog.newPost ,metaWeblog.newPost
    $param      = $xml->getElementsByTagName('param')->item(3)->ownerDocument;
    $member     = $param->getElementsByTagName('member');

    $members = array();
    foreach ($member as $v) {
        $members[$v->childNodes->item(0)->nodeValue] = $v->childNodes->item(1)->nodeValue;
    }

    //将笔记内容过滤并生成hexo格式的md文件

    $content = $members['description'];
    //将div包裹的行级元素全部去掉div加上换行
    $content = preg_replace('/<div>(.*?)<\/div>/', "$1" . PHP_EOL, $content);
    //将内容中的html元素全部清除
    $content = strip_tags($content);
    //将html实体符号变回元素，wiz之前会将Html元素转化成实体元素
    $content = html_entity_decode($content);
    //将来自未知笔记去除，用了别人的产品还是帮忙宣传一下吧
    //$content = preg_replace('/来自为知笔记\(Wiz\)/', "", $content);

    //如果大于200字符，加<!--more-->标签
    if (mb_strlen($content) > 200) {
        $content = mb_substr($content, 0, 200, 'utf8') . "<!--more-->" . mb_substr($content, 200, NULL, 'utf8');
    }

    //hexo 的正文是yaml格式
    $yaml['title']    = $members['title'];
    $yaml['date']     = date('Y-m-d H:i:s', strtotime($members['dateCreated']));
    $yaml['updated']  = date('Y-m-d H:i:s', time());
    $yaml['category'] = '日志';
    $yaml['tags']     = explode(', ', $members['mt_keywords']);

    //拼接yaml和正文
    $post = Yaml::dump($yaml) . '---' . PHP_EOL . $content;

    //生成新的md文件
    $ret = file_put_contents($path_to_tmp . $yaml['title'] . '.md', $post);

    //在tmp文件夹下保存一下日志的备份
    $xml->save($path_to_tmp . $yaml['title'] . '.xml');
    unset($xml);
} catch (Exception $e) {
    echo errorResponse($e->getCode(), $e->getMessage());
}

echo successResponse();

function successResponse($successString = '')
{
    $error_response = <<<EOF
<?xml version="1.0"?>
<methodResponse>
   <params>
      <param>
         <value><string>%s</string></value>
         </param>
      </params>
   </methodResponse>
EOF;
    return sprintf($error_response, $successString);
}

function errorResponse($faultCode, $faultString)
{
    $error_response = <<<EOF
<?xml version="1.0"?>
<methodResponse>
    <fault>
        <value>
         <struct>
            <member>
               <name>faultCode</name>
               <value><int>%d</int></value>
               </member>
            <member>
               <name>faultString</name>
               <value><string>%s</string></value>
               </member>
            </struct>
         </value>
    </fault>
</methodResponse>
EOF;
    return sprintf($error_response, $faultString, $faultCode);
}
