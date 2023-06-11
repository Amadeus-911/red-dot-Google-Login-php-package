# red-dot-Google-Sign-In-php
A simple handler for php to use OAuth2.0 for authenticating using google

At first a google application has to be set up for client ID and Client Secret. 
Go to https://console.cloud.google.com/apis/credentials?project=life-good-315813

# Set up OAuth Consent Screen
Give app name -> 
centact email address -> 
application home page url (here localhost/Myapp) -> 
give test email address
and continue and Save.

# Go to Credentials
Create Credential ->  
Oauth Client Id -> 
Web Application -> 
add web app name -> 
add redirect uri (the url where google will send back its response such as localhost/login.php)

Download the config JSON file containing client ID and Client Secret

Install composer (php package manager) from here https://getcomposer.org/download/

# Set up in your project
In your composer.json file add 

```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Amadeus-911/red-dot-Google-Sign-In-php.git"
        }
    ],
    "require": {
        "red-dot/google-oauth-login-php": "dev-main"
    }
}

```

on root folder of you project run
```
composer install
```

import it in your php file 
```
require_once 'vendor/autoload.php';
use RedDot\GoogleOauthLoginPhp\GoogleClient;
```

First generate authUrl where the login button will hit

```
$client = new GoogleClient($client_id, $client_secret, $redirect_uri, $scope);
$authUrl = $client->buildAuthUrl();
```

add it to your google sign in button 

```
 <a style="text-decoration:none" href="<?php echo $authUrl?>">
                        <button class="google-button">
                        </button>
 </a>
```

get access token 

```
$code = $_GET['code'];
$accessToken = $client->getAccessToken($code);
```

retrive user data using the access token 
```
 $user = $client->getUserInfo($accessToken);
```


There is a sample project given for your reference. Use your applications CLIENT_ID, CLIENT_SECRET, REDIRECT_URI in place of the placeholders
