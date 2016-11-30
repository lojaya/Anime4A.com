<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/14/2016
 * Time: 10:59 PM
 */
$fb = new Facebook\Facebook ([
    'app_id' => '289914814743628',
    'app_secret' => '243d9877164820481824c13b10891c76',
    'default_graph_version' => 'v2.2',
]);
/*
$helper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (! isset($accessToken)) {
    $permissions = array('public_profile','email'); // Optional permissions
    $loginUrl = $helper->getLoginUrl('http://anime4a.com/login-with-facebook/', $permissions);
    header("Location: ".$loginUrl);
    exit;
}

try {
    // Returns a `Facebook\FacebookResponse` object
    $fields = array('id', 'name', 'email');
    $response = $fb->get('/me?fields='.implode(',', $fields).'', $accessToken);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$user = $response->getGraphUser();

echo 'Faceook ID: ' . $user['id'];
echo '<br />Faceook Name: ' . $user['name'];
echo '<br />Faceook Email: ' . $user['email'];
*/
?>
<html>
<head>
    <title>Facebook Login JavaScript Example</title>
    <meta charset="UTF-8">
</head>
<body>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '289914814743628',
            xfbml      : true,
            version    : 'v2.8'
        });
    };
    // Load the SDK asynchronously
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function fb_login(){
        FB.login(function(response) {
            if (response.authResponse) {
                FB.api('/me', {fields: 'id,name,email'}, function(response) {
                    // request login to server
                    alert("Name: "+ response.name + "\nEmail: "+ response.email + "ID: "+response.id);
                });
            }
            else if (response.status === 'not_authorized') {
            }
            else {
            }
        }, {
            scope: 'email'
        });
    }

    function fb_logout(){
        FB.logout(function(response) {
            //
        });
    }
</script>

<img src="myimage.png" style="width: 300px; height: 200px;" onclick="fb_login()"/>
<img src="myimage.png" style="width: 300px; height: 200px;" onclick="fb_logout()"/>

</body>
</html>