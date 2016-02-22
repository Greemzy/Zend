<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 02/02/2016
 * Time: 12:19
 */

namespace Blog\Model;


class StarsApi
{
    protected $http	= 'http://api.redtube.com/';
    protected $callDetail	= 'redtube.Stars.getStarDetailedList';
    protected $callList	= 'redtube.Stars.getStarList';
    protected $imgDefaultMen = 'http://img01.redtubefiles.com/_thumbs/design/default/no-img-men.jpg';
    protected $imgDefaultWoman= 'http://img01.redtubefiles.com/_thumbs/design/default/no-img-women.jpg';

    private function RedTubeApiCall($http_server, $params = array())
    {
        $query_string	=	'?';

        if(is_array($params) && count($params)){
            foreach($params as $k=>$v){
                $query_string .= $k.'='.$v.'&';
            }
            $query_string =	rtrim($query_string,'&');
        }
        return	file_get_contents($http_server.$query_string);
    }

    private function getRedtubeStars($type ,$page = 0, $category = false)
    {
        $params		=	array(
            'output'	=>	'json',
            'data'		=>	$type,
            'page'		=>	$page
        );

        if($category){
            $params['category'] = $category;
        }

        $response = $this->RedTubeApiCall($this->http , $params);

        if ($response) {
            $json	=	 json_decode($response);
            if(isset($json->code) && isset($json->message)){
                throw new \Exception($json->message, $json->code);
            }
            return $json;
        }
        return false;
    }

    public function getListStars()
    {
        $data = $this->fetchAll();
        $new = array();
        foreach($data['stars'] as $star){
            if($star['star']['star_thumb'] != $this->imgDefaultMen && $star['star']['star_thumb'] != $this->imgDefaultWoman)
            {
                $new[$star['star']['star_name']] = $star['star']['star_name'];
            }
        }
        return $new;
    }

    public function fetchAll()
    {
        $resultSet = $this->getRedtubeStars($this->callDetail);
        $array = json_decode(json_encode($resultSet), true);
        return $array;
    }

    public function fetchNumber($num, $category)
    {
        $resultSet = $this->getRedtubeStars($num, $category);
        $array = json_decode(json_encode($resultSet), true);
        return $array;
    }

    public function getInfoToStar($name)
    {
        $data = $this->fetchAll();
        foreach($data['stars'] as $star){
            if($star['star']['star_name'] === $name){
                return $star['star'];
            }
        }
        return null;
    }
}