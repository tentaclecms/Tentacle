<?php
/**
name: Core
url: http://tentaclecms.com
version: 1.0
description: Tentacles core Plugin
author:
  name: Adam Patterson
  url: http://adampatterson.ca
 */


event::on('theme_header', 'analytics::tracking');
event::on('theme_meta', 'analytics::author');
event::on('theme_header', 'analytics::author_url');
event::on('theme_meta', 'analytics::webmaster');
event::on('theme_meta', 'analytics::meta_description');
event::on('theme_header', 'analytics::seo_tracking_header');
event::on('theme_footer', 'analytics::seo_tracking_footer');

event::on('page_view', 'analytics::page_view');


/*
class: analytics
*/
class analytics{

    static function tracking()
    {
        if (get::option('seo_google_analytics') != '' ) {
            echo "
<script type='text/javascript'>

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '".get::option('seo_google_analytics', '')."']);
  _gaq.push(['_setDomainName', '".$_SERVER['SERVER_NAME'] ."']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>";
        }
    }


    static function author ()
    {
        if (get::option('admin_author') != '' )
            echo "<meta name='author' content='".get::option('admin_author') ."' />\n";
    }


    static function author_url()
    {
        if (get::option('seo_author_profile') != '' )
            echo "<link rel='author' href='".get::option('seo_author_profile', '')."' />\n";
    }


    static function webmaster()
    {
        if (get::option('seo_google_webmaster') != '' )
            echo "<meta name='google-site-verification' content='".get::option('seo_google_webmaster', '')."' />\n";
    }


    static function meta_description(){
        if (get::option('seo_meta_description') != '' )
            echo "<meta name='description' content='".get::option('seo_meta_description', '')."' />\n";
    }


    static function seo_tracking_header(){
        if (get::option('seo_tracking_header') != '' )
            echo get::option('seo_tracking_header', '')."\n";
    }


    static function seo_tracking_footer(){
        if (get::option('seo_tracking_footer') != '' )
            echo get::option('seo_tracking_footer', '')."\n";
    }

  
    static function page_view()
    {
        load::model('statistics')->build_stats();
    }

}

/*
Shortcode
*/
event::on('shortcode', 'shortcode', 1);
logger::set('Shortcode', 'oEmbed');

function shortcode( $content )
{
    add_shortcode( 'embed', 'oembed_content' );
    add_shortcode( 'snippet', 'snippet' );

    if (function_exists('do_shortcode'))
        return do_shortcode( $content );
}


function snippet( $slug )
{
    $snippet = load::model( 'snippet' );
    $snippet_single = $snippet->get_slug( $slug[0] );

    return $snippet_single->content;
}


function oembed_content( $url )
{
    load::library('oembed');

    return oembed_cotnent( $url[0] );
}


event::on('add_page', 'generate_sitemap', 1);
event::on('update_page', 'generate_sitemap', 1);
event::on('trash_page', 'generate_sitemap', 1);
event::on('add_post', 'generate_sitemap', 1);
event::on('update_post', 'generate_sitemap', 1);
event::on('trash_post', 'generate_sitemap', 1);

function generate_sitemap()
{
    $uri = $_SERVER['REQUEST_URI'];

    if (!stristr($uri, 'admin'))
    {
        $page = load::model('content');

        $pages = $page->get();

        $url = BASE_URL;

        $sitemap = '<?xml version="1.0" encoding="UTF-8" ?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

//        foreach ($pages as $page)
//        {
//            $sitemap .= '<url>';
//
//            if ($page->rewrite == $homepage)
//            {
//                $sitemap .= "<loc>$url</loc>";
//                $sitemap .= "<changefreq>daily</changefreq>";
//                $sitemap .= "<priority>1</priority>";
//            } else{
//                $sitemap .= "<loc>$url{$page->rewrite}</loc>";
//                $sitemap .= "<changefreq>daily</changefreq>";
//                $sitemap .= "<priority>0.8</priority>";
//            }
//
//            $sitemap .= '</url>';
//        }

        $sitemap .= '</urlset>';

        $fp = fopen(BASE_URL.'sitemap.xml', 'w');
        fwrite($fp, $sitemap);
        fclose($fp);
    }
}
