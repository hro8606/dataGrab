<?php

namespace App\Http\Controllers;

use App\Article;
use App\Kind;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Mark;
use App\Automobile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ScrapingController extends Controller
{

    function getRandomStr($length = 7) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function deleteOld(){
        /*delete all articles in db*/

        Article::truncate();
        Storage::disk('uploads')->deleteDirectory('/images');

    }

    public function scrapping(Request $request)
    {

        $this->deleteOld();

        $array=[];
        $checker = 0;
        set_time_limit(300000);
        $curl = curl_init();
        $amount = $request->amount;

        $URL = 'http://www.tert.am/am/news/1';

        curl_setopt($curl, CURLOPT_URL, $URL);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        $query = curl_exec($curl);
        curl_close($curl);

        $dom = new \DomDocument();
        @$dom->loadHTML($query);
        $xpath = new \DOMXpath($dom);

        $arr = array();

        for ($p = 1; $p <= 5000; $p++) {

            $linksPerPage = [];
            $url = 'http://www.tert.am/am/news/'.$p;
            $htmm = curl_init();
            curl_setopt($htmm, CURLOPT_URL, $url);
            curl_setopt($htmm, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($htmm, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($htmm, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            $query1 = curl_exec($htmm);
            curl_close($htmm);

            $dom = new \DomDocument();
            @$dom->loadHTML($query1);

            $xpath = new \DOMXpath($dom);

            for($i=1;$i <= 10; $i++){
                $linksPerPage[$i] = $xpath->query('//*[@id="right-col"]/div[1]/div['.$i.']/a');
            }

            for ($e = 1; $e <= 10; $e++) {


                /*data 1 - article URL*/
                $url1 = $linksPerPage[$e]->item(0)->getAttribute('href');

                $htm = curl_init();
                curl_setopt($htm, CURLOPT_URL, $url1);
                curl_setopt($htm, CURLOPT_CONNECTTIMEOUT, 0);
                curl_setopt($htm, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($htm, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                $query2 = curl_exec($htm);
                curl_close($htm);

                $dom = new \DomDocument();
                @$dom->loadHTML($query2);

                $xpath = new \DOMXpath($dom);

                /*data 2 - article title*/
                $title = $xpath->query('//*[@id="item"]/h1')->item(0)->textContent;

                $date = $xpath->query('//*[@id="item"]/p[@class="n-d"]')->item(0)->textContent;
                /*data 3 - article description*/
//                $description = $xpath->query('//*[@id="i-content"]/p')->item(0)->textContent;

                $description_p = $xpath->query('//*[@id="i-content"]/p');

                $description = '';

                foreach($description_p as $ps){

                    $description .= $ps->textContent;
                }
                /*data 4 - article imageURL*/
                $image = $xpath->query('//*[@id="i-content"]/img[@class="b-i-i"]/@src')->item(0)->textContent;


                $contents = file_get_contents($image);
                $imageArr = explode(".",$image);
                $imageExt = end($imageArr);
                /*data 4 - article image name*/
                $name = time() . $this->getRandomStr(). '.' . $imageExt;

                /*path storage/app/images/1 */
                Storage::disk('uploads')->put('images/'.$name, $contents, 'public');



                $date = explode(' ',$date);
                $time = $date[0].":22";
                $date = explode('.',$date[2]);
                $year   = $date[2]+2000;
                $month  = $date[1];
                $day    = $date[0];
                $date   = $year."-".$month."-".$day;

                /*data 5 - article date*/
                $date_time = $date." ".$time;

                $articles = new Article;

                $articles->title = $title;
                $articles->description = $description;
                $articles->cr_date = $date_time;
                $articles->main_image = $name;
                $articles->article_url = $url1;
                $articles->save();
                $checker++;
                if($checker == $amount){
//dd($checker);
                    return redirect('/admin');
                }

            }
        }
        return redirect('/admin');
    }

}








