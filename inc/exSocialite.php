<?php
/**
 * Created by PhpStorm.
 * User: samtax
 * Date: 14/02/2019
 * Time: 4:05 PM
 */

class exSocialite extends Controller1 {
    // CLF config
    public static $CLF_BYPASS_TOKEN_LIST = ['processCallback'];

    // Please install [ composer require 'overtrue/socialite' -vvv ] to __include folder first


    /**
    *  Redirect Url
    *  https://your-site-url.com/form/exSocialite::processCallback(facebook)
    */
    // config list
    private static $config = [
        'google' => [
            'client_id'     => '193765478624-aui8uer6rl9e64gqct3k4paksrre7lr7.apps.googleusercontent.com',
            'client_secret' => '5oQ12wvTj0uWsFg4-hkU3Hao',
        ],

        'facebook' => [
            'client_id'     => '1272402492917309',
            'client_secret' => 'f934eb631b6da8eff5e48ca56d9e7566',
        ],

        'github' => [
            'client_id'     => '',
            'client_secret' => '',
        ],


        'linkedin' => [
            'client_id'     => '',
            'client_secret' => '',
        ],


        'outlook' => [
            'client_id'     => '',
            'client_secret' => '',
        ],


        'weibo' => [
            'client_id'     => '',
            'client_secret' => '',
        ],



        'qq' => [
            'client_id'     => '',
            'client_secret' => '',
        ],



        'wechat' => [
            'client_id'     => '',
            'client_secret' => '',
        ],



        'douban' => [
            'client_id'     => '',
            'client_secret' => '',
        ],
    ];





    /**
     * All Login Config
     * @param string $name
     * @return mixed
     */
    private static function getConfig($name = 'facebook'){
        // plugin required
        if(!class_exists('\Overtrue\Socialite\SocialiteManager')) return redirect_back(['overtrue/socialite Required', "Please install [ composer require 'overtrue/socialite' -vvv ] to __include folder first", 'error']);
        if(!isset(self::$config[$name])) return redirect_back([$name.' Not Declared', "Please initialize config client_id/client_secret first", 'error']);

        // generate callback and add redirect
        $config = self::$config[$name];
        $config['redirect'] = Form1::callControllerAndBypassToken(token(),self::class."@processCallback($name)");
        return [$name => $config];
    }



    private static function onUserFound($user){
        //dd($user);
        //        $user->getId();        // 1472352
        //        $user->getNickname();  // "overtrue"
        //        $user->getUsername();  // "overtrue"
        //        $user->getName();      // "安正超"
        //        $user->getEmail();     // "anzhengchao@gmail.com"
        //        $user->getProviderName(); // GitHub


        if(!empty($user)){

            // pie info
            $user_id_name = strtolower($user->getProviderName()).'_user_id';
            $user_name = (($user->getUsername() && !Math1::isNumber($user->getUsername()))? $user->getUsername(): String1::issetOr($user->getName(), String1::issetOr($user->getUsername(), $user->getNickname())));
            $info = [
                $user_id_name=>     $user->getId(),
                'user_name'=>       String1::convertWordToSlug($user_name).'_'.time(),
                'full_name'=>       $user->getName(),
                'email'=>           $user->getEmail(),
                'avatar'=>          $user->getAvatar(),
                'status'=>          'active',
                'registered_via'=>  $user->getProviderName(),
                'password'=>        '__%_'.Math1::getUniqueId().'__%_',
            ];


            // Only Link if account exists
            if(!User::isGuest()){
                $existingAccount = User::getLogin();
                if($existingAccount->update([$user_id_name=>$user->getId(), 'avatar'=>String1::isset_or($info['avatar'], $existingAccount['avatar'])])) Session1::setStatus('Account Linked', "Your Account is Successfully Linked with ".$user->getProviderName(), 'success');
                else Session1::setStatus('Failed to link account', "Please try again or contact Admin", 'error');
                return redirect('/dashboard');
            }


            // login if _user_id exists
            $userInfo = User::find($user->getId(), $user_id_name);
            //$isAuth =  User::login($userInfo['user_name'],  $userInfo['password'], ['user_name'], ['password'], false);
            //dd($isAuth, $userInfo['user_name'],  $userInfo['password'],  User::getLogin(false));
            
            
            if($userInfo && strlen($userInfo[$user_id_name]) > 2) {
                $result =  User::login(String1::isset_or($userInfo['user_name'], $userInfo['email']), $userInfo->password, ['user_name', 'id', 'email'], ['password'], false);
                return Url1::redirectIf(routes()->dashboard, '', $result);    // on success redirect
            }
            
            
            
            

            // Register
            $result = User::register($info, ['user_name'], true);
            //dd('user exists', $result, $info,  $userInfo, $userInstance);
           
           
            if($result) {
                $isAuth =  User::login($info['user_name'],  $info['password'], ['user_name'], ['password'], true);
                return Url1::redirectIf(routes()->dashboard, ['Success', 'Account Created'], true);
            }
        }

        // if Failed!
        Session1::setStatus('Failed to Login', "OAuth Login Operation Failed, Please select another option", 'error');
        return Url1::redirectIf(routes()->dashboard);
    }




    /**
     * Compile and get the specify auth driver
     * @param string $name
     * @return \Overtrue\Socialite\ProviderInterface
     */
    private static function getDriver($name = 'facebook'){
        $socialite = new \Overtrue\Socialite\SocialiteManager(self::getConfig($name));
        return $socialite->driver($name);
    }


    /** 
     * OAUTH2 Login. Simply pass either
     *      Facebook    <a href="{{ Form1::callController('exSocialite::processLogin(facebook)') }}">  Login with Facebook</a>
     *      Google      <a href="{{ Form1::callController('exSocialite::processLogin(google)') }}">    Login with Google</a>
     *      Github      <a href="{{ Form1::callController('exSocialite::processLogin(github)') }}">    Login with Github</a>
     * @param string $name
     */
    static function processLogin($name = 'facebook'){   die(self::getDriver($name)->redirect()."<h2>Redirecting to $name...</h2>"); }





    /**
     * Redirect Callback will hold the user info result, and it's been called automatically.
     * Should you need the full link. https://your-site-url.com/form/exSocialite::processCallback(facebook)
     *
     * @param string $name
     */
    static function processCallback($name = 'facebook'){
        self::onUserFound(self::getDriver($name)->user());
    }
}