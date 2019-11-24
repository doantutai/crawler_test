<?php
/*
    Các bước Crawler như sau:
        - Nhập Site cần Crawler, View Code tìm đến các link bài viết trong chuyên mục hay mục bài viết mới nhất
        - Sau khi tìm được các link bài viết cần Crawler, Cho vào 1 List rồi gọi đến action lấy nội dung chi tiết của bài viết
    Ở đây mình Demo trang Guu.vn
    Muốn Crawler chuyên mục bất kỳ chỉ cần cài Cronjob đường dẫn http://domain.com/api.php?act=cronjobs&url=https://guu.vn/myguu/new
    là nó tự tìm các bài viết mới nhất và lấy về

    Chúc các bạn thành công ^^
*/

require_once('simple_html_dom.php');
define('_HOME', 'http://mydomain.com');

switch($act){
    case 'leech':
        /* Get Source Code */
        $html       = file_get_html($url);

        /* Find And Get Content */
        $title      = $html->find('title',0)->plaintext;
        $title      = str_replace(' - GUU.vn', '', $title);
        $content    = $html->find('h2[class=summary-detail]',0)->innertext;
        $content   .= $html->find('article[id=body]',0)->innertext;
        $content    = strip_tags($content, '<p><strong>');
        $content    = str_replace('Guu.vn','',$content);
        $des        = $html->find('meta[name=description]',0)->content;
        $key        = $html->find('meta[name=keyword]',0)->content;

        $data = array(
            'post_title'        => $title,
            'post_content'      => $content,
            'post_description'  => $des,
            'post_keywords'     => $key
        );

        print_r($data);
        break;
    case 'cronjobs';

        /*
            Site Demo: https://guu.vn/myguu/new
            Final Site Run Cronjobs: http://domain.com/api.php?act=cronjobs&url=https://guu.vn/myguu/new
        */

        /* Get Source Code */
        $html = file_get_html($url);

        /* Find Link Need Crawler */
        foreach($html->find('h3 a') as $post){
            $post = 'https://guu.vn'.$post->href;
            echo '- '.file_get_contents(_HOME.'/api.php?act=leech&&url='.$post).'<br />';
        }
        break;

    default:
        echo 'Default Page.';
        break;
}