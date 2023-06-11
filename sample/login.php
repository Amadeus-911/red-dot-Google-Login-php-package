<?php require_once('auth.php') ?>

<?php

require_once 'vendor/autoload.php';

use RedDot\GoogleOauthLoginPhp\GoogleClient;

  // Set the parameter values
  $client_id = 'YOUR_GOOGLE_CLIENT_ID';
  $redirect_uri = 'YOUR_REDIRECT_URI';
  $scope = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email';
  $client_secret = 'YOUR_CLIENT_SECRET';

$client = new GoogleClient($client_id, $client_secret, $redirect_uri, $scope);
$authUrl = $client->buildAuthUrl();

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $accessToken = $client->getAccessToken($code);

    $user = $client->getUserInfo($accessToken);

    $_SESSION['name'] = $user[0];
    $_SESSION['email'] = $user[1];
    $_SESSION['imgUrl'] = $user[2];
    $_SESSION['access_token'] = $accessToken;
    header('location:./index.php');

}

?>
<!DOCTYPE html>
<html>

    <head>
        <title>Login Page</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <script src="https://kit.fontawesome.com/4e25ff9c4f.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <div class="container">
            <div class="login-box">
                <h2>Login</h2>
                <form>
                    <div class="input-group">
                        <label>Email:</label>
                        <input type="email" name="email">
                    </div>
                    <div class="input-group">
                        <label>Password:</label>
                        <input type="password" name="password">
                    </div>
                    <div class="input-group">
                        <button type="submit" class="login-button">Login</button>
                    </div>
                    <div class="separator">
                        <span>or</span>
                    </div>
                </form>
                <div class="input-group">
                    <a style="text-decoration:none" href="<?php echo $authUrl?>">
                        <button class="google-button">
                            <i class="fa-brands fa-google"></i> Sign in with Google
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </body>

</html>