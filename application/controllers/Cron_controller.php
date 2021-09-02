<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Cron_controller extends CI_Controller
{
    public function index()
    {
        ini_set('max_execution_time', 0); 
        header("Refresh:300");

        $this->load->model('MyModel', 'MyModel');
        foreach ($this->MyModel->getsites() as $site) {
            echo $this->MyModel->updatesitestatus($site->id, $this->sitedurumkontrolleri($site->sitename));
        }
    }
    function sitedurumkontrolleri($domain){
        if ($this->isDomainAvailible($domain) || $this->isDomainAvailible("www.".$domain)) {
            return true;
        }else{  
            if ($this->url_test2($domain) || $this->url_test2("www.".$domain)){
                return true;
            }else{
                if ($this->url_test($domain) || $this->url_test("www.".$domain)){
                    return true;
                }else{
                    if($this->getSslPage($domain) ||  $this->getSslPage("www.".$domain)){
                        return true;
                    }else{

                    return false;
                }
            }
            } 
        }
    }
    function getSslPage($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    function isDomainAvailible($domain)
       {
               //check, if a valid url is provided
               if(!filter_var($domain, FILTER_VALIDATE_URL))
               {
                       return false;
               }

               //initialize curl
               $curlInit = curl_init($domain);
               curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
               curl_setopt($curlInit,CURLOPT_HEADER,true);
               curl_setopt($curlInit,CURLOPT_NOBODY,true);
               curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

               //get answer
               $response = curl_exec($curlInit);

               curl_close($curlInit);

               if ($response) return true;

               return false;
       }

    private function url_test2($url)
    {
        if (!$sock = @fsockopen($url, 80, $num, $error, 0.25)) {
            return false;
        } else {
            return true;
        }
    }
    private function url_test($url)
    {
        $timeout = 10;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $http_respond = curl_exec($ch);
        $http_respond = trim(strip_tags($http_respond));
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (($http_code == "200") || ($http_code == "302")) {
            return true;
        } else {
            // return $http_code;, possible too
            return false;
        }
        curl_close($ch);
    }
}
