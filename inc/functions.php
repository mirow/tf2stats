function countryCityFromIP($ipAddr)
{
//verify the IP address for the
ip2long($ipAddr)== -1 || ip2long($ipAddr) === false ? trigger_error("Invalid IP", E_USER_ERROR) : "";
$ipDetail=array(); //initialize a blank array

$xml = file_get_contents("http://ipinfodb.com/ip_query.php?ip=".$ipAddr);
preg_match("@
<CountryCode>(.*?)</CountryCode>@si",$xml,$matches);
$country = $matches[1];

return $country;
}
