<?php

namespace RedDot\GoogleOauthLoginPhp;

class GoogleClient
{
    private $client_id;
    private $client_secret;
    private $redirect_uri;
    private $scope;
    private $response_type = 'code';
    private $grant_type = 'authorization_code';

    private $apiUrl = 'https://accounts.google.com/o/oauth2/v2/auth';
    private $tokenUrl = 'https://accounts.google.com/o/oauth2/token';
    private $userInfoUrl = 'https://www.googleapis.com/oauth2/v3/userinfo';
    private $scope = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email';

    public function __construct($client_id, $client_secret, $redirect_uri) {
        $this->client_id = $client_id;
        $this->redirect_uri = $redirect_uri;
        $this->client_secret = $client_secret;
    }

      // Build the authorization URL with the parameter values

    public function buildAuthUrl(){
        $authUrl = $this->apiUrl
        . '?client_id=' . urlencode($this->client_id)
        . '&redirect_uri=' . urlencode($this->redirect_uri)
        . '&scope=' . urlencode($this->scope)
        . '&response_type=' . urlencode($this->response_type);
        return $authUrl;
    }

    public function getAccessToken($code){
        $ch = curl_init();

        // Set the URL and other options
        curl_setopt($ch, CURLOPT_URL, $this->tokenUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'code=' . urlencode($code) . 
        '&client_id=' . urlencode($this->client_id) . 
        '&client_secret=' . urlencode($this->client_secret) . 
        '&redirect_uri=' . urlencode($this->redirect_uri) . 
        '&grant_type=' . urlencode($this->grant_type));
    
        // Execute the request and fetch the response
        $response = curl_exec($ch);
    
        // Check for errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
        }
    
        // Close the cURL resource
        curl_close($ch);
    
        // Handle the response
        if ($response) {
            // Process the response as needed
            $jsonObj = json_decode($response);
            $accessToken = $jsonObj->access_token;

            return $accessToken;
        } else {
            return "Error";
        }
    }

    public function getUserInfo($accessToken){


        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $this->userInfoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $accessToken
        ));

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
        }

        // Close the cURL resource
        curl_close($ch);

        // Handle the response
        if ($response) {
            // Process the response as needed
            $data = json_decode($response);
            // Access the data
            $email = $data->email;
            $imageUrl = $data->picture;
            $name = $data->family_name ? $data->given_name . ' ' . $data->family_name : $data->given_name;

            $user = [$name, $email, $imageUrl];

            return $user;

        } else {
            return "error1 ";
        }
    }

    
}
