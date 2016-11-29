<?php
	// In this example we use very convenient library for performing
	// REST api calls. Library is hosted at http://code.google.com/p/gam-http/
	// Of course you are free to use any other REST client library or even
	// write your own.

namespace App\Library;

use App\Library\Http;
/**
 * Describes two kinds of authentication. Acts as enum.
 */
class AuthType {
    const BASIC = 1;
    const HMAC = 2;
}

class PhpAdfLy {
    const BASE_HOST = 'api.adf.ly';
    // TODO: Replace following constant value with your secret key.
    const SECRET_KEY = '9d16a304-8207-4b6c-b360-9ca8c2950a33';
    // TODO: Replace following constant value with your public api key.
    const PUBLIC_KEY = '176cac073e591c831577cf190a56a4c7';
    // TODO: Replace following constant value with your user id.
    const USER_ID = 10236587;
    const HMAC_ALGO = 'sha256';

    private $connection = null;

    public function __construct() {
        $this->connection = Http::connect(self::BASE_HOST);
    }
    public function auth($username, $password) {
        return json_decode($this->connection->doPost('v1/auth',$this->getParams(array('username' => $username, 'password' => $password), null)), 1);
    }

    public function getGroups($page=1) {
        return json_decode($this->connection->doGet('v1/urlGroups',$this->getParams(array('_page' => $page), AuthType::HMAC)),1);
    }
    public function createGroup($name) {
        return json_decode($this->connection->doPost('v1/urlGroups',$this->getParams(array('name' => $name), AuthType::HMAC)),1);
    }

    public function getAccountDetails() {
        return json_decode($this->connection->doGet('v1/account',$this->getParams(array(), AuthType::HMAC)), 1);
    }

    public function updateAccountDetails(array $params=[]) {
        return json_decode($this->connection->doPut('v1/account',$this->getParams($params, AuthType::HMAC)), 1);
    }

    public function expand(array $urls, array $hashes=array()) {
        $params = array();

        $i = 0;
        foreach ($urls as $url) {
            $params[sprintf('url[%d]', $i++)] = $url;
        }

        $i = 0;
        foreach ($hashes as $hash) {
            $params[sprintf('hash[%d]', $i++)] = $hash;
        }

        return json_decode($this->connection->doGet('v1/expand',$this->getParams($params)),1);
    }

    public function shorten(array $urls, $domain=false, $advertType=false, $groupId=false) {
        $params = array();
        if ($domain !== false) $params['domain'] = $domain;
        if ($advertType !== false) $params['advert_type'] = $advertType;
        if ($groupId !== false) $params['group_id'] = $groupId;

        $i = 0;
        foreach ($urls as $url) {
            $params[sprintf('url[%d]', $i++)] = $url;
        }

        return json_decode($this->connection->doPost('v1/shorten',$this->getParams($params)),1);
    }

    public function getUrls($page=1, $q=null) {
        $params = array('_page' => $page);

        if ($q) {
            $params['q'] = $q;
        }

        return json_decode($this->connection->doGet('v1/urls',$this->getParams($params, AuthType::HMAC)),1);
    }

    public function getReferrers($urlId=null) {
        $params = array();
        if ($urlId) $params['url_id'] = $urlId;

        return json_decode($this->connection->doGet('v1/referrers',$this->getParams($params, AuthType::HMAC)),1);
    }

    public function getDomains() {
        $params = array();
        return json_decode($this->connection->doGet('v1/domains',$this->getParams($params, AuthType::HMAC)),1);
    }

    public function getAccountCountries() {
        return json_decode($this->connection->doGet('v1/accountCountries',$this->getParams(array(), AuthType::BASIC)), 1);
    }

    public function getAccountPubReferrals($fromDate='', $toDate='', $page=1, $includeBanned=0) {
        $params = array('fromDate' => $fromDate, 'toDate' => $toDate, '_page' => $page, 'includeBanned' => $includeBanned);
        return json_decode($this->connection->doGet('v1/accountPubReferrals', $this->getParams($params, AuthType::HMAC)), 1);
    }

    public function getAccountAdvReferrals($fromDate='', $toDate='', $page=1, $includeBanned=0) {
        $params = array('fromDate' => $fromDate, 'toDate' => $toDate, '_page' => $page, 'includeBanned' => $includeBanned);
        return json_decode($this->connection->doGet('v1/accountAdvReferrals', $this->getParams($params, AuthType::HMAC)), 1);
    }

    public function getAccountPopReferrals($fromDate='', $toDate='', $page=1, $includeBanned=0) {
        $params = array('fromDate' => $fromDate, 'toDate' => $toDate, '_page' => $page, 'includeBanned' => $includeBanned);
        return json_decode($this->connection->doGet('v1/accountPopReferrals', $this->getParams($params, AuthType::HMAC)), 1);
    }

    public function getCountries($urlId=null) {
        $params = array();
        if ($urlId) $params['url_id'] = $urlId;

        return json_decode($this->connection->doGet('v1/countries',$this->getParams($params, AuthType::HMAC)),1);
    }
    public function getAnnouncements($type=null) {
        $params = array();
        if (!empty($type) && in_array($type,array(1,2))) $params['type'] = $type;

        return json_decode($this->connection->doGet('v1/announcements',$this->getParams($params, AuthType::HMAC)),1);
    }


    public function getPublisherReferrals() {
        return json_decode($this->connection->doGet('v1/publisherReferralStats',$this->getParams(array(), AuthType::HMAC)),1);
    }
    public function getAdvertiserReferrals() {
        return json_decode($this->connection->doGet('v1/advertiserReferralStats',$this->getParams(array(), AuthType::HMAC)),1);
    }

    public function getWithdraw() {
        return json_decode($this->connection->doGet('v1/withdraw',$this->getParams(array(), AuthType::HMAC)));
    }
    public function getWithdrawalTransactions() {
        return json_decode($this->connection->doGet('v1/withdrawalTransactions',$this->getParams(array(), AuthType::HMAC)),1);
    }
    public function getPublisherStats($date = null, $urlId = 0){
        $params = array();
        if(!empty($date)) $params['date'] = $date;
        if(!empty($urlId)) $params['urlId'] = $urlId;

        return json_decode($this->connection->doGet('v1/publisherStats',$this->getParams($params, AuthType::HMAC)),1);
    }
    public function getProfile() {
        echo $this->connection->doGet('v1/profile',$this->getParams(array(), AuthType::HMAC));
        die;
    }
    public function getAdvertiserCampaigns( $fromDate = null, $toDate = null,$adType = null, $adFilter = null){
        if(!empty($fromDate)) $params['fromDate'] = $fromDate;
        if(!empty($toDate)) $params['toDate'] = $toDate;
        if(!empty($adType)) $params['adType'] = $adType;
        if(!empty($adFilter)) $params['adFilter'] = $adFilter;

        return json_decode($this->connection->doGet('v1/advertiserCampaigns',$this->getParams(array(), AuthType::HMAC)),1);
    }
    public function getAdvertiserGraph($date = null, $websiteId = 0,$adType = null, $adFilter = null){
        $params = array();
        if(!empty($date)) $params['date'] = $date;
        if(!empty($websiteId)) $params['websiteId'] = $websiteId;
        if(!empty($adType)) $params['adType'] = $adType;
        if(!empty($adFilter)) $params['adFilter'] = $adFilter;

        return json_decode($this->connection->doGet('v1/advertiserGraph',$this->getParams($params, AuthType::HMAC)),1);
    }
    public function getAdvertiserCampaignParts($campaignId, $fromDate = null, $toDate = null, $adType = null, $adFilter = null){
        $params = array('campaignId' => $campaignId);
        if(!empty($fromDate)) $params['fromDate'] = $fromDate;
        if(!empty($toDate)) $params['toDate'] = $toDate;
        if(!empty($adType)) $params['adType'] = $adType;
        if(!empty($adFilter)) $params['adFilter'] = $adFilter;

        return json_decode($this->connection->doGet('v1/advertiserCampaignParts',$this->getParams($params, AuthType::HMAC)),1);
    }

    public function updateUrl($id, $url=false, $advertType=false, $title=false, $groupId=false, $fbDescription=false, $fbImage=false) {
        $params = array();

        if ($url !== false) $params['url'] = $url;
        if ($advertType !== false) $params['advert_type'] = $advertType;
        if ($title !== false) $params['title'] = $title;
        if ($groupId !== false) $params['group_id'] = $groupId;
        if ($fbDescription !== false) $params['fb_description'] = $fbDescription;
        if ($fbImage !== false) $params['fb_image'] = $fbImage;

        return json_decode($this->connection->doPut('v1/urls/' . $id,$this->getParams($params, AuthType::HMAC)),1);
    }

    public function deleteUrl($id) {
        return json_decode($this->connection->doDelete('v1/urls/' . $id,$this->getParams(array(), AuthType::HMAC)),1);
    }

    /**
     * Populates query parameters with required parameters. Such as
     * _user_id, _api_key, etc.
     * @param array $params
     * @param integer $authType
     */
    private function getParams(array $params=array(), $authType=AuthType::BASIC) {
        $params['_user_id'] = self::USER_ID;
        $params['_api_key'] = self::PUBLIC_KEY;

        if (AuthType::BASIC == $authType) {

        } else if (AuthType::HMAC == $authType) {
            // Get current unix timestamp (UTC time).
            $params['_timestamp'] = time();
            // And calculate hash.
            $params['_hash'] = $this->doHmac($params);
        }

        return $params;
    }

    private function doHmac(array $params) {
        // Built-in 'http_build_query' function which is used
        // to construct query string does not include parameters with null
        // values which is incorrect in our case.
        $params = array_map(function($x) { return is_null($x) ? '' : $x; }, $params);

        // Sort query parameters by names using byte ordering.
        // So 'param[10]' comes before 'param[2]'.
        if (ksort($params)) {
            // Url encode parameters. The encoding should be performed
            // per RFC 1738 (http://www.faqs.org/rfcs/rfc1738)
            // which implies that spaces are encoded as plus (+) signs.
            $queryStr = http_build_query($params);
            // Generate hash value based on encoded query string and
            // secret key.
            return hash_hmac(self::HMAC_ALGO, $queryStr, self::SECRET_KEY);
        } else {
            throw new RuntimeException('Could not ksort data array');
        }
    }

    public function prettyPrint( $json )
    {
        $result = '';
        $level = 0;
        $in_quotes = false;
        $in_escape = false;
        $ends_line_level = NULL;
        $json_length = strlen( $json );

        for( $i = 0; $i < $json_length; $i++ ) {
            $char = $json[$i];
            $new_line_level = NULL;
            $post = "";
            if( $ends_line_level !== NULL ) {
                $new_line_level = $ends_line_level;
                $ends_line_level = NULL;
            }
            if ( $in_escape ) {
                $in_escape = false;
            } else if( $char === '"' ) {
                $in_quotes = !$in_quotes;
            } else if( ! $in_quotes ) {
                switch( $char ) {
                    case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                    case '{': case '[':
                    $level++;
                    case ',':
                        $ends_line_level = $level;
                        break;

                    case ':':
                        $post = " ";
                        break;

                    case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
                }
            } else if ( $char === '\\' ) {
                $in_escape = true;
            }
            if( $new_line_level !== NULL ) {
                $result .= "\n".str_repeat( "\t", $new_line_level );
            }
            $result .= $char.$post;
        }

        return $result;
    }

    /**
     * Example script entry point.
     */
    public static function ShortenUrl($url) {
        $ex = new PhpAdfLy();

        $res = $ex->shorten(array($url), 'adfly.local');
        $shortenedUrl1 = $res['data'][0];
        $hash1 = substr($shortenedUrl1['short_url'],strrpos($shortenedUrl1['short_url'],'/')+1);
        return 'http://adf.ly/' . $hash1;
//             //echo "Starting the demo...".LB.LB;
//             echo "SHORTENING...".LB;

// 			// Shorten Url
//             $res = $ex->shorten(array('http://stackoverflow.com/users'), 'adfly.local');
//             $shortenedUrl1 = $res['data'][0];
//             $hash1 = substr($shortenedUrl1['short_url'],strrpos($shortenedUrl1['short_url'],'/')+1);
//             echo 'First URL shortened (' . $hash1 . '): ' . print_r($res,1) . LB;

//             $res = $ex->shorten(array('http://www.reddit.com'), 'q.gs');
//             $shortenedUrl2 = $res['data'][0];
//             $hash2 = substr($shortenedUrl2['short_url'],strrpos($shortenedUrl2['short_url'],'/')+1);
//             echo 'Another URL shortened (' . $hash2 . '): ' . print_r($res,1) . LB;

//             $res = $ex->shorten(array('www.youtube.com'), 'q.gs', 'banner');
//             $shortenedUrl3 = $res['data'][0];
//             echo 'Another URL shortened: ' . print_r($res,1) . LB;

//             $res = $ex->shorten(array('http://www.len10.com/videos/'), 'q.gs', 'int', 13);
//             $shortenedUrl4 = $res['data'][0];
//             echo 'Another URL shortened: ' . print_r($res,1) . LB;

//             echo LB."EXPAND...".LB;
//             // Expand examples.
//             echo 'Shortened URLS just created: ' . print_r($ex->expand(array($shortenedUrl3['short_url'],$shortenedUrl4['short_url']),array($hash1,$hash2)),1);

//             echo LB."LISTING...".LB;
//             //List Urls
//             $urlList = $ex->getUrls();
//             echo 'Listing page 1 URLS...: ' . print_r($urlList,1);

//             //Update Url
//             echo LB."UPDATING LINK...".LB;
//             $ex->updateUrl($shortenedUrl1['id'], 'http://modifiedurlaaaa.cat', "int", "The  updated URL", 13, false, false);
//             echo 'Modified URL: ' . print_r($ex->expand(array(),array($hash1)),1);

//             foreach($urlList['data'] as $url){
//                 echo 'Deleting URL ID: ' . $url['id'] . LB;
//                 $ex->deleteUrl($url['id']);
//             }

//             echo LB."LISTING AGAIN...".LB;
//             //List Urls
//             $urlList = $ex->getUrls();
//             echo 'Listing page 1 URLS...: ' . print_r($urlList,1) . LB;

//             //GROUPS
//             echo LB."GROUPS".LB;
//             $g = $ex->createGroup('API Group');
//             echo 'Created group: ' . print_r($g,1).LB;

//             $g = $ex->getGroups(1);
//             echo 'Listing page 1 GROUPS...: ' . print_r($g,1).LB;

//             //REFERRERS GET
//             echo LB."REFERRERS".LB;
//             $res = $ex->getReferrers();
//             echo 'URL Referrers: ' . print_r($res,1).LB;

//             //COUNTRIES GET
//             echo LB."COUNTRIES".LB;
//             $res = $ex->getCountries();
//             echo 'URL Countries: ' . print_r($res,1).LB;

//             //ANNOUNCEMENTS GET
//             echo LB."ANNOUNCEMENTS".LB;
//             $res = $ex->getAnnouncements();
//             echo 'Announcements: ' . print_r($res,1).LB;

//             //publisherReferralStats GET
//             echo LB."PUBLISHER REFERRAL STATS".LB;
//             $res = $ex->getPublisherReferrals();
//             echo 'Stats: ' . print_r($res,1).LB;

//             //advertiserReferralStats GET
//             echo LB."ADVERTISER REFERRAL STATS".LB;
//             $res = $ex->getAdvertiserReferrals();
//             echo 'Stats: ' . print_r($res,1).LB;

//             ///withdrawalTransactions GET
//             echo LB."WITHDRAWAL TRANSACTRIONS".LB;
//             $res = $ex->getWithdrawalTransactions();
//             echo 'Transactions: ' . print_r($res,1).LB;

//             ///withdraw GET
//             //echo LB."WITHDRAW".LB;
//             $res = $ex->getWithdraw();
//             echo $res;
//             //echo 'Data: ' . print_r($res,1).LB;

//             //publisherStats GET
//             echo LB."PUBLISHER STATS".LB;
//             $res = $ex->getPublisherStats();
//             echo 'Stats: ' . print_r($res,1).LB;

//             //user Profile GET
//             echo LB."USER PROFILE".LB;
//             $res = $ex->getProfile();
//             echo 'User: ' . print_r($res,1).LB;

//             //advertiserStats GET
//             echo LB."ADVERTISER STATS".LB;
//             $res = $ex->getAdvertiserCampaigns();
//             echo 'Stats: ' . print_r($res,1).LB;

//             //advertiserStats GET
//             echo LB."ADVERTISER GRAPH".LB;
//             $res = $ex->getAdvertiserGraph(null,156);
//             echo 'Stats: ' . print_r($res,1).LB;

//             //advertiserStats GET
//             echo LB."ADVERTISER STATS".LB;
//             $res = $ex->getAdvertiserCampaignParts(739026);
//             echo 'Stats: ' . print_r($res,1).LB;

//             //auth POST
//             echo LB."AUTH".LB;
//             $res = $ex->auth('1', '2');
//             echo 'Auth: ' . print_r($res,1).LB;

        // account publisher referrals
//             echo LB."ACCOUNT PUBLISHER REFERRALS".LB;
//             $res = $ex->getAccountPubReferrals('', '', 1, 1);
// 			echo 'Referrals: ' . print_r($res, 1).LB;

        // account advertiser referrals
//              echo LB."ACCOUNT ADVERTISER REFERRALS".LB;
//              $res = $ex->getAccountAdvReferrals('', '', 1, 1);
//  			echo 'Referrals: ' . print_r($res, 1).LB;

        // account popad referrals
//             echo LB."ACCOUNT POPAD REFERRALS".LB;
//             $res = $ex->getAccountPopReferrals('', '', 1, 1);
// 			echo 'Referrals: ' . print_r($res, 1).LB;

        // account popad referrals
//              echo LB."DOMAINS".LB;
//              $res = $ex->getDomains();
//  			echo 'Domains: ' . print_r($res, 1).LB;

        // update account details
//             echo LB."UDATE ACCOUNT DETAILS".LB;
//             $res = $ex->updateAccountDetails([]);
//             echo 'Result: ' . print_r($res, 1).LB;

        // get account countries
//              echo LB."ACCOUNT COUNTRIES".LB;
//              $res = $ex->getAccountCountries();
//              echo 'Result: ' . print_r($res, 1).LB;

        // get account countries
//              echo LB."ACCOUNT DETAILS".LB;
//              $res = $ex->getAccountDetails();
//              echo 'Result: ' . print_r($res, 1).LB;
    }
}
