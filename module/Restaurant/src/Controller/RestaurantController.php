<?php
namespace Restaurant\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class RestaurantController extends AbstractActionController
{
    public function json_cached_api_results($address, $expires = null)
    {
        $filename = $address . '.json';
        $cache_file = 'D:/xampp5/htdocs/zend-test/data/cache/' . $filename;

        if (!$expires) {
            $expires = time() - 2 * 60 * 60;
        }

        if (!file_exists($cache_file)) {
            file_put_contents($cache_file, '');
        }

        if (filectime($cache_file) < $expires || file_get_contents($cache_file) == '') {
            $api_results = $this->google_maps_api_request($address);
            $json_results = json_encode($api_results);

            if ($api_results && $json_results) {
                file_put_contents($cache_file, $json_results);
            } else {
                unlink($cache_file);
            }

        } else {
            $json_results = file_get_contents($cache_file);
        }

        return json_decode($json_results);
    }

    public function google_maps_api_request($address)
    {
        $api_key = 'AIzaSyCmSXItKm_s_S9VfAYpr5t1ayEejGbVTWM';
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key={$api_key}";

        // get the json response
        $resp_json = file_get_contents($url);
        $resp = $resp_json;
        return $resp;
    }

    public function indexAction()
    {
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');
        $response->getHeaders()->addHeaderLine('Access-Control-Allow-Credentials', 'true');
        $response->getHeaders()->addHeaderLine('Access-Control-Allow-Methods', 'POST PUT DELETE GET');

        $rawData = file_get_contents("php://input");
        $postData = json_decode($rawData, true);
        $address = isset($postData['address']) ? urlencode(str_replace(' ', '', $postData['address'])) : 'Bangsue';

        $response->setStatusCode(200);
        $response->setContent($this->json_cached_api_results($address));
        return $response;        
        exit;
    }

    public function addAction()
    {
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}
