<?php

class Mailtrain_API_Curl {

    public $url;
    public $token;
    

    public function __construct()
    {
        $this->url = get_option('mailtrain_api_option_name')['url_mailtrain'].'/api/';
        $this->token = get_option('mailtrain_api_option_name')['access_token_0'];
    }

    public function connect($parameters)
    {
        $url = $this->url.$parameters.'access_token='.$this->token;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET'
          ));
          
          $response = curl_exec($curl);
          
          curl_close($curl);
          return $response;
    }

    public function lists($post_id)
    {

        $list_id = get_post_meta($post_id, '_list_id', true);

        $lists = json_decode($this->connect('lists?'));
        $form = '<select name="list_id" id="list_id">';
        $form .= '<option value="">'.__('-select-','mailtrain-api').'</option>';
        if(null !== $lists) {
            foreach($lists->{'data'} as $l){
                $form .= '<option value="'.$l->{'id'}.'" '.selected( $list_id, $l->{'id'},false ).'>'.$l->{'name'}.'</option>';   
            }
        }
        $form .= '</select>';
        return $form;
    }

    public function get_list_by_id($id){
        $list = json_decode($this->connect('list/'.$id.'?'));
        $return = [];
        $return['cid'] = $list->{'data'}->{'cid'};
        $return['name'] = $list->{'data'}->{'name'};
        
        echo json_encode($return);
    }

    public function add_subscriber($list_id,$name,$email)
    {

        $url = get_option('mailtrain_api_option_name')['url_mailtrain'].'/api/subscribe/'.$list_id.'?access_token='.get_option('mailtrain_api_option_name')['access_token_0'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "EMAIL":"'.$email.'",
            "FIRST_NAME":"'.$name.'",
            "FORCE_SUBSCRIBE":"yes"
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}

function mailtrain_api()
{
    return new Mailtrain_API_Curl();
}
mailtrain_api();