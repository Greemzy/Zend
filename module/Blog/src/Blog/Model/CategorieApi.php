<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 02/02/2016
 * Time: 12:19
 */

namespace Blog\Model;


class CategorieApi
{
    protected $http	= 'http://api.redtube.com/';
    protected $call	= 'redtube.Categories.getCategoriesList';

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

    private function getRedtubeCategories($page = 0, $category = false)
    {
        $params		=	array(
            'output'	=>	'json',
            'data'		=>	$this->call,
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


    public function fetchAll()
    {
        $resultSet = $this->getRedtubeCategories();
        $array = json_decode(json_encode($resultSet), true);
        return $array;
    }

    public function getListCategorie()
    {
        $resultSet = $this->getRedtubeCategories();
        $array = json_decode(json_encode($resultSet), true);
        $new = array();
        foreach($array['categories'] as $star){
            $new[$star['category']] = $star['category'];
        }
        return $new;
    }
}