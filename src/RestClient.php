class RestClient
{
    private $ava_headers;
    public $ava_url;
    public $date;

    public function __construct($code, $key)
    {
        $date = gmdate('Y-m-d H:i:s');
        $this->date = $date;
        $hash = hash_hmac('md5', strlen($code) . $code . strlen($date) . $date, $key);
        $headers = array(
            'X-Avangate-Authentication: code="' . $code . '" date="' . $date . '" hash="' . $hash . '"',
            "Content-Type: application/json",
            "Accept: application/json"
        );
        $this->ava_headers = $headers;
        $this->ava_url = 'https://api.avangate.com/3.0/';
    }

    public function getAvaHeaders()
    {
        return $this->ava_headers;
    }

    public function call($api_route)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_HEADER,false);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->ava_headers);
        curl_setopt($ch, CURLOPT_URL, $this->ava_url . $api_route);

        $resp = curl_exec($ch);
        curl_close($ch);

        return $resp;
    }
}
