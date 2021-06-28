<?php


namespace App\Custom;

use App\Models\Art;
use App\Models\Course;
use Carbon\Carbon;

class Sitemap{

    public static function class(){

        $courses = Course::where("status" , 1)->orderBy('create_at' , 'DESC')->get();

        $xml = new \DOMDocument("1.0" , "UTF-8");

        $xml->formatOutput=true;
        $urlset=$xml->createElement("urlset");
        $urlset->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
        $xml->appendChild($urlset);

        foreach ($courses as $key=>$value ){
            $url=$xml->createElement("url");
            $urlset->appendChild($url);
            $loc=$xml->createElement("loc" , "https://honari.com/academy/courses/".urlencode($value->urlfa));
            $url->appendChild($loc);
            $lastmod=$xml->createElement("lastmod" , Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('Y-m-d')."T".Carbon::createFromFormat('Y-m-d H:i:s', $value->updated_at)->format('H:i:s')."+03:30");
            $url->appendChild($lastmod);
        }


        $xml->save("sitemap/class.xml");



    }



    public static function category(){

        $courses = Art::all();

        $xml = new \DOMDocument("1.0" , "UTF-8");

        $xml->formatOutput=true;
        $urlset=$xml->createElement("urlset");
        $urlset->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
        $xml->appendChild($urlset);

        foreach ($courses as $key=>$value ){
            $url=$xml->createElement("url");
            $urlset->appendChild($url);
            $loc=$xml->createElement("loc" , "https://honari.com/academy/category/".urlencode($value->art_url));
            $url->appendChild($loc);
        }

        $xml->save("sitemap/arts.xml");

    }
}
