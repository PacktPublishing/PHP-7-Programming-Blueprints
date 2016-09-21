
3
4
5
6
7
8
9
10
11
12
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
33
34
35
36
37
38
39
40
41
42
43
44
45
46
47
48
<?php
session_start();
// added in v4.0.0
require_once 'autoload.php';
//require 'functions.php';  
use FacebookFacebookSession;
use FacebookFacebookRedirectLoginHelper;
use FacebookFacebookRequest;
use FacebookFacebookResponse;
use FacebookFacebookSDKException;
use FacebookFacebookRequestException;
use FacebookFacebookAuthorizationException;
use FacebookGraphObject;
use FacebookEntitiesAccessToken;
use FacebookHttpClientsFacebookCurlHttpClient;
use FacebookHttpClientsFacebookHttpable;
// init app with app id and secret
FacebookSession::setDefaultApplication( '64296382121312313','8563798aasdasdasdweqwe84' );
// login helper with redirect_uri
    $helper = new FacebookRedirectLoginHelper('http://www.krizna.com/fbconfig.php' );
try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}
// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
//start a graph object with the user email
  $graphObject = $response->getGraphObject();
  $id = $graphObject->getProperty('id');              // To Get Facebook ID
  $fullname = $graphObject->getProperty('name'); // To Get Facebook full name
  $email = $graphObject->getProperty('email');    // To Get Facebook email ID

     $_SESSION['FB_id'] = $id;           
     $_SESSION['FB_fullname'] = $fullname;
     $_SESSION['FB_Email'] =  $email;
     //redirect user
    header("Location: index.php");
} else {
  $loginUrl = $helper->getLoginUrl();
 header("Location: ".$loginUrl);
}
?>
