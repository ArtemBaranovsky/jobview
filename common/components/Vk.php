<?php

namespace common\components;

use yii;
use Exception;
use AntiCaptcha\AntiCaptcha;

/*
 * Class Vk
 * author: Dmitriy Nyashkin
 */
class Vk{

    const API_VERSION = '5.60';

    const CALLBACK_BLANK = 'https://oauth.vk.com/blank.html';
    const AUTHORIZE_URL  = 'https://oauth.vk.com/authorize?client_id={client_id}&scope={scope}&redirect_uri={redirect_uri}&display={display}&v=5.24&response_type={response_type}';
    const LOGIN_URL      = 'https://oauth.vk.com/token?grant_type=password&client_id={client_id}&client_secret={client_secret}&username={login}&password={password}&v=5.60';
    const GET_TOKEN_URL  = 'https://oauth.vk.com/access_token?client_id={client_id}&client_secret={client_secret}&code={code}&redirect_uri={redirect_uri}';
    const METHOD_URL     = 'https://api.vk.com/method/';

    public $secret_key = null;
    public $scope = array();
    public $client_id = null;
    public $access_token = null;
    public $owner_id = 0;
    protected $proxy;
    protected $proxyHost;
    protected $proxyAuth;

    /**
     * Это Конструктор (Кэп.)
     * Передаются параметры настроек
     * @param array $options
     */
    function __construct($options = array()){

        $this->scope[] = 'offline';

        if(count($options) > 0){
            foreach($options as $key => $value){
                if($key == 'scope' && is_string($value)){
                    $_scope = explode(',', $value);
                    $this->scope = array_merge($this->scope, $_scope);
                } else {
                    $this->$key = $value;
                }

            }
        }
    }

    /**
     * @param $proxy
     * @param null $port
     * @param null $username
     * @param null $password
     * @throws Exception
     */
    public function setProxy($proxy, $port = null, $username = null, $password = null)
    {
        $this->proxy = $proxy;

        if (empty($proxy)) {
            return;
        }

        $proxy = parse_url($proxy);

        if (!is_null($port) && is_int($port)) {
            $proxy['port'] = $port;
        }

        if (!is_null($username) && !is_null($password)) {
            $proxy['user'] = $username;
            $proxy['pass'] = $password;
        }

        if (!empty($proxy['host']) && isset($proxy['port']) && is_int($proxy['port'])) {
            $this->proxyHost = $proxy['host'] . ':' . $proxy['port'];
        } else {
            throw new Exception('Proxy host error. Please check ip address and port of proxy.');
        }

        if (isset($proxy['user']) && isset($proxy['pass'])) {
            $this->proxyAuth = $proxy['user'] . ':' . $proxy['pass'];
        }
    }

    /**
     * @param $login
     * @param $password
     * @param null $captcha_sid
     * @param null $captcha_key
     * @param int $h
     * @return array|mixed
     */
    public function login($login, $password, $captcha_sid = null, $captcha_key = null, $h = 0)
    {
        if ($h > 2){
            return [
                'error'             => 'local',
                'error_description' => 'Три неудачных попытки распознать капчу!'
            ];
        }

        $url = strtr(self::LOGIN_URL, [
            '{login}'         => urlencode($login),
            '{password}'      => urlencode($password),
            '{client_id}'     => $this->client_id,
            '{client_secret}' => $this->secret_key,
        ]);

        if ($captcha_sid && empty($captcha_key) === false){
            $url .= "&captcha_sid=$captcha_sid&captcha_key=$captcha_key";
        }

        $response = json_decode($this->curl_get($url), true);

//        if (isset($response['error_description']) && $response['error_description'] == 'please open redirect_uri in browser [4]'){
//            /**
//             * Если требуется подтвердить авторизацию
//             */
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $response['redirect_uri']);
//            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
//            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36');
//
//            if ($this->proxy){
//                curl_setopt($ch, CURLOPT_PROXY, $this->proxyHost);
//                if ($this->proxyAuth){
//                    curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxyAuth);
//                }
//            }
//
//            curl_exec($ch);
//            $url = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
//            curl_close($ch);
//
//            if (preg_match('#https://oauth.vk.com/blank.html\#success=1&access_token=(.*?)&user_id=(\d+)#ui', $url, $data)){
//                return [
//                    'user_id'      => $data[2],
//                    'access_token' => $data[1],
//                ];
//            }else{
//                return $response;
//            }
//        }else

        if (isset($response['error']) && $response['error'] == 'need_captcha'){
            /**
             * Если требуется ввести капчу
             */
            if (isset(Yii::$app->params['anticaptcha']) === false){
                return [
                    'error'             => 'local',
                    'error_description' => 'Не подключен сервис распознавания капч!'
                ];
            }

            $antiCaptcha = new AntiCaptcha(Yii::$app->params['anticaptcha']['services'], [
                'debug'   => false,
                'api_key' => Yii::$app->params['anticaptcha']['api_key'],
            ]);
            $antiCaptcha->setLogger(new AntiCaptchaLogger());

            $code = $antiCaptcha->recognize($this->curl_get($response['captcha_img']), null, [
                'phrase'  => 0,
                'numeric' => 0
            ]);

            return $this->login($login, $password, $response['captcha_sid'], $code, ++$h);
        }elseif (isset($response['error_description']) && $response['error_description'] == 'please open redirect_uri in browser [3]'){
            return [
                'error'             => 'local',
                'error_description' => 'Аккаунт забанен!'
            ];
        }

        return $response;
    }

    /**
     * Выполнение вызова Api метода
     * @param string $method - метод, http://vk.com/dev/methods
     * @param array $vars - параметры метода
     * @return array - выводит массив данных или ошибку (но тоже в массиве)
     */
    function api($method = '', $vars = array()){

        $vars['v'] = self::API_VERSION;

        $params = http_build_query($vars);

        $url = $this->http_build_query($method, $params);

        return (array)$this->call($url);
    }

    /**
     * Построение конечного URI для выхова
     * @param $method
     * @param string $params
     * @return string
     */
    private function http_build_query($method, $params = ''){
        return  self::METHOD_URL . $method . '?' . $params.'&access_token=' . $this->access_token;
    }

    /**
     * Получить ссылка на запрос прав доступа
     *
     * @param string $type тип ответа (code - одноразовый код авторизации , token - готовый access token)
     * @return mixed
     */
    public function get_code_token($type="code"){

        $url = self::AUTHORIZE_URL;

        $scope = implode(',', $this->scope);

        $url = str_replace('{client_id}', $this->client_id, $url);
        $url = str_replace('{scope}', $scope, $url);
        $url = str_replace('{redirect_uri}', self::CALLBACK_BLANK, $url);
        $url = str_replace('{display}', 'page', $url);
        $url = str_replace('{response_type}', $type, $url);

        return $url;

    }

    /**
     * @param $code
     * @return bool|mixed|string
     */
    public function get_token($code){

        $url = self::GET_TOKEN_URL;
        $url = str_replace('{code}', $code, $url);
        $url = str_replace('{client_id}', $this->client_id, $url);
        $url = str_replace('{client_secret}', $this->secret_key, $url);
        $url = str_replace('{redirect_uri}', self::CALLBACK_BLANK, $url);

        return $this->call($url);
    }

    /**
     * @param string $url
     * @param array $params
     * @param array $options
     * @param int $h
     * @return bool|mixed|string
     */
    function call($url = '', $params = [], $options = [], $h = 0){
        if(function_exists('curl_init'))
            $json = $this->curl_post($url, $params, $options);
        else
            $json = file_get_contents($url);

        if (empty($json) && $h < 3){
            sleep(2);
            return $this->call($url, $params, $options, ++$h);
        }

        $json = json_decode($json, true);

        if(isset($json['response'])) return $json['response'];

        return $json;
    }

    /**
     * @param $url
     * @param array $params
     * @param array $options
     * @return bool|mixed
     */
    private function curl_post($url, $params = [], $options = []){

        if(!function_exists('curl_init')) return false;

        $param = parse_url($url);
        $url   = count($params) ? $url : $param['scheme'].'://'.$param['host'].$param['path'];

        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, count($params) ? $params : $param['query']);
            curl_setopt($curl, CURLOPT_TIMEOUT, 5);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_USERAGENT, 'VKAndroidApp/4.38-849 (Android 6.0; SDK 23; x86; Google Nexus 5X; ru)');

            if ($this->proxy){
                curl_setopt($curl, CURLOPT_PROXY, $this->proxyHost);
                if ($this->proxyAuth){
                    curl_setopt($curl, CURLOPT_PROXYUSERPWD, $this->proxyAuth);
                }
            }

            foreach ($options as $key => $value){
                curl_setopt($curl, $key, $value);
            }

            $out = curl_exec($curl);

            curl_close($curl);

            return $out;
        }

        return false;
    }

    /**
     * @param $url
     * @param array $options
     * @return mixed
     */
    private function curl_get($url, $options = [])
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'VKAndroidApp/4.38-849 (Android 6.0; SDK 23; x86; Google Nexus 5X; ru)');

        if ($this->proxy){
            curl_setopt($curl, CURLOPT_PROXY, $this->proxyHost);
            if ($this->proxyAuth){
                curl_setopt($curl, CURLOPT_PROXYUSERPWD, $this->proxyAuth);
            }
        }

        foreach ($options as $key => $value){
            curl_setopt($curl, $key, $value);
        }

        $out = curl_exec($curl);

        curl_close($curl);

        return $out;
    }

    /**
     * @param array $options
     */
    public function set_options($options = array()){

        if(count($options) > 0){
            foreach($options as $key => $value){
                if($key == 'scope' && is_string($value)){
                    $_scope = explode(',', $value);
                    $this->scope = array_merge($this->scope, $_scope);
                } else {
                    $this->$key = $value;
                }

            }
        }

    }

    /**
     * @param $file
     * @param int $group_id
     * @param int $h
     * @return array
     */
    public function uploadPhotoWall($file, $group_id = 0, $h = 0)
    {
        $server = $this->api('photos.getWallUploadServer', [
            'group_id' => $group_id
        ]);

        if (isset($server['error'])){
            return $server;
        }elseif (isset($server['upload_url']) === false){
            return $h == 0 ? $this->uploadPhotoWall($file, $group_id, ++$h) : [
                'error' => [
                    'error_msg' => 'Не найден "upload_url"'
                ]
            ];
        }

        if (is_file($file) === false){
            return [
                'error' => [
                    'error_msg' => 'Изображение не найдено!'
                ]
            ];
        }

        $file   = curl_file_create($file);
        $upload = $this->call($server['upload_url'], ['photo' => $file], [
            CURLOPT_TIMEOUT    => 15,
            CURLOPT_HTTPHEADER => ['Content-Type: multipart/form-data']
        ]);

        if (isset($upload['photo']) === false || $upload['photo'] == '[]'){
            return [
                'error' => [
                    'error_msg' => "Ошибка загрузки фото!"
                ]
            ];
        }

        $upload['group_id'] = $group_id;
        return $this->api('photos.saveWallPhoto', $upload);
    }

    /**
     * @param $file
     * @param int $owner_id
     * @param int $h
     * @return array
     */
    public function uploadPhotoProfile($file, $owner_id = 0, $h = 0)
    {
        $server = $this->api('photos.getOwnerPhotoUploadServer', [
            'owner' => $owner_id
        ]);

        if (isset($server['error'])){
            return $server;
        }elseif (isset($server['upload_url']) === false){
            return $h == 0 ? $this->uploadPhotoProfile($file, $owner_id, ++$h) : [
                'error' => [
                    'error_msg' => 'Не найден "upload_url"'
                ]
            ];
        }

        if (is_file($file) === false){
            return [
                'error' => [
                    'error_msg' => 'Изображение не найдено!'
                ]
            ];
        }

        $file   = curl_file_create($file);
        $upload = $this->call($server['upload_url'], ['photo' => $file], [
            CURLOPT_TIMEOUT    => 15,
            CURLOPT_HTTPHEADER => ['Content-Type: multipart/form-data']
        ]);

        if (isset($upload['photo']) === false || $upload['photo'] == '[]'){
            return [
                'error' => [
                    'error_msg' => "Ошибка загрузки фото!"
                ]
            ];
        }

        return $this->api('photos.saveOwnerPhoto', $upload);
    }
}