<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 10/14/2016
 * Time: 10:59 PM
 */
use App\Mobile_Detect;
?>

<html>
<head>
    <script type="text/javascript" src="/js/jquery-3.1.0.min.js"></script>
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>" />
</head>
<body>
<div id="player"></div>
<?php
$m = new Mobile_Detect();
if($m->isMobile())
    echo 'a';
?>
</body>
</html>