<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 11/02/2016
 * Time: 23:44
 */

namespace Blog\Model;


class VideoApi
{
    protected $http	= 'http://api.redtube.com/';
    protected $call	= 'redtube.Videos.getVideoById';
    protected $callSearch =	'redtube.Videos.searchVideos';

    private function RedTubeApiCall($http_server, $params = array())
    {
        $query_string	=	'?';

        if(is_array($params) && count($params)){
            foreach($params as $k=>$v){
                if(is_array($v))
                {
                    $query_string .= $k.'[]='.$v[0].'&';
                }
                else{
                    $query_string .= $k.'='.$v.'&';
                }
            }
            $query_string =	rtrim($query_string,'&');
        }
        //var_dump($http_server.$query_string);exit;
        return	file_get_contents($http_server.$query_string);
    }

    private function getRedtubeVideoSearchByStar($star = array(), $category = null)
    {
        $params		=	array(
            'data'		=>	$this->callSearch,
            'output'	=>	'json',
            'stars'    =>	$star
        );

        if(!is_null($category)){
            $params['category']	= $category;
        }

        $response	=	$this->RedTubeApiCall($this->http , $params);

        if ($response) {
            $json	=	 json_decode($response);
            if(isset($json->code) && isset($json->message)){
                return false;
                //throw new Exception($json->message, $json->code);
            }
            return $json;
        }
        return false;
    }

    private function getRedtubeVideoById($id)
    {
        $params		=	array(
            'output'	=>	'json',
            'data'		=>	$this->call,
            'thumbsize' =>  'all',
            'video_id' =>  $id
        );

        $response = $this->RedTubeApiCall($this->http , $params);

        if ($response) {
            $json	=	 json_decode($response);
            if(isset($json->code) && isset($json->message)){
                return false;
                throw new \Exception($json->message, $json->code);
            }
            return $json;
        }
        return false;
    }

    public function getVideoBySearch($star = array(), $category = null){
        $tab = $this->getRedtubeVideoSearchByStar($star, $category);
        $array = json_decode(json_encode($tab), true);
        return $array;
    }

    public function getVideoById($id){
        return $this->getRedtubeVideoById($id);
    }
}