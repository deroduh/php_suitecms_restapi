<?php



	class ClientRest{

    protected $sid = null;

    private $rest_url = '';

    private $rest_user = '';

    private $rest_pass = '';
    private $sessId = '';

    public function __construct($user, $pass, $host, $rest_api = "/service/v4_1/rest.php")
    {
        $this->rest_user = $user;
        $this->rest_pass = $pass;
        $this->base_url = 'http://' . preg_replace('~^http://~', '', $host);
        $this->rest_url = $host . $rest_api;
    }

    public function login()
    {
        $userAuth = array(
        'user_name' => $this->rest_user,
        'password' => md5($this->rest_pass),
);
$appName = 'Rest Clien';
$nameValueList = array();

$args = array(
            'user_auth' => $userAuth,
            'application_name' => $appName,
            'name_value_list' => $nameValueList);

$result = $this->restRequest('login',$args);
$this->sessId = $result['id'];
    }

   public function getEntity($entity){
   		$entryArgs = array(
	'session' => $this->sessId,
	'module_name' => $entity,
	'query' => "",
	'order_by' => '',
	'offset' => 0,
	'select_fields' => array('id','name',),
	'link_name_to_fields_array' => array(
        array(
                'name' => 'contacts',
                        'value' => array(
                        'first_name',
                        'last_name',
                ),
        ),
),
  		'max_results' => 10,
  		'deleted' => 0,
 );

$result = $this->restRequest('get_entry_list',$entryArgs);

$json=json_encode($result,JSON_PRETTY_PRINT);


echo "<h1>".$entity."</h1><br><pre>".$json."</pre>";

   }

   private function restRequest($method, $arguments){
 global $url;
 $curl = curl_init($this->rest_url);
 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 $post = array(
         "method" => $method,
         "input_type" => "JSON",
         "response_type" => "JSON",
         "rest_data" => json_encode($arguments),
 );

 curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

 $result = curl_exec($curl);
 curl_close($curl);
 return json_decode($result,1);
}
}
$obj = new ClientRest('deroduh', 'ter1pF82', 'http://sssbbbccc.esy.es');
$obj->login();
$obj->getEntity('Accounts');
$obj->getEntity('Leads');
$obj->getEntity('Contacts');
$obj->getEntity('Tasks');
$obj->getEntity('Opportunities');
$obj->getEntity('Users');



 