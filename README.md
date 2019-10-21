#  exSocialite (v1.0)
> This is a wrapper plugin for Overtrue/Socialite  


<img src="preview.gif" width="100%">



## Requirement
- [x] Install [ composer require 'overtrue/socialite' -vvv ] in your __includes directory
- [x] and [SweetAlert](#)  for Notification






## Features
- [x] Login with ether facebook, github, google, linkedin, outlook, weibo, qq, wechat, and douban.



## Quick Use 
> Clone Plugin Repository and Add it to Plugins Folder of your project or shared plugins folder.
> This will allows you to use the plugin in your  Project. 




### Usage
Register to get ```client_id``` and ```client_secret``` keys and add them to you config in exSocialite ```$config```
```php

class exSocialite{
    private static $config = [
        'google' => [
            'client_id'     => '*****',
            'client_secret' => '*****',
        ],
        ...
        
``` 





and in your login page, use ```Form1::callControllerAndBypassToken(token(), "exSocialite@processLogin(facebook)")``` in your link. Example
```html
    Login with either
 
     Facebook    <a href="{{ Form1::callControllerAndBypassToken(token(), "exSocialite::processLogin(facebook)") }}">  Login with Facebook</a>
     Google      <a href="{{ Form1::callControllerAndBypassToken(token(), "exSocialite::processLogin(google)") }}">    Login with Google</a>
     Github      <a href="{{ Form1::callControllerAndBypassToken(token(), "exSocialite::processLogin(github)") }}">    Login with Github</a>
     linkedin    <a href="{{ Form1::callControllerAndBypassToken(token(), "exSocialite::processLogin(linkedin)") }}">    Login with linkedin</a>
     outlook     <a href="{{ Form1::callControllerAndBypassToken(token(), "exSocialite::processLogin(outlook)") }}">    Login with outlook</a>
     weibo       <a href="{{ Form1::callControllerAndBypassToken(token(), "exSocialite::processLogin(weibo)") }}">    Login with weibo</a>
     qq          <a href="{{ Form1::callControllerAndBypassToken(token(), "exSocialite::processLogin(qq)") }}">    Login with qq</a>
     wechat      <a href="{{ Form1::callControllerAndBypassToken(token(), "exSocialite::processLogin(wechat)") }}">    Login with wechat</a>
     douban      <a href="{{ Form1::callControllerAndBypassToken(token(), "exSocialite::processLogin(douban)") }}">    Login with douban</a>
```


### Redirect Callback
Note: Remember to add redirection link during OAUTH registration
Redirect Callback will hold the user info result, and it's will be called automatically.
Should you need the full link. https://your-site-url.com/form/exSocialite::processCallback(facebook)

```php
    // redirect result is sent to 
    private static function registerUser($user){
        dd($user);
    }
```


## Verbose result from ```registerUser($user){ }```

```php
    
    $user->getId();
    $user->getNickname();
    $user->getName();
    $user->getEmail();
    $user->getAvatar();
    $user->getOriginal();
    $user->getToken();// or $user->getAccessToken()
    $user->getProviderName(); // GitHub/Google/Facebook...
    
```


## Requirement in User Model
User model must consist of the following fields. Otherwise, change accordingly in ```exSocialite``` wrapper to suit your needs
```php

    class User extends Model1{
    
        // important, for storing new data and signing user in
        ...   
        public $user_name  = '';  
        public $full_name   = '';     
        public $email  = '';         
        public $avatar   = '';        
        public $registered_via   = '';
        public $password   = '';
        ...
        
        
        //Optional, based on your requirement. i.e add facebook_user_id if you using facebook.
        
        public $registered_via = null;
        public $facebook_user_id = '';
        public $github_user_id = '';
        public $google_user_id = '';
        public $linkedin_user_id = '';
        public $outlook_user_id = '';
        public $weibo_user_id = '';
        public $qq_user_id = '';
        public $wechat_user_id = '';
        public $douban_user_id = '';
        ...
        
```   
            

         

## Likely Error
please ensure you are not putting quote around your parameter. 
```exSocialite::processCallback(facebook)``` instead of  ```exSocialite::processCallback("facebook")```





## Author
:kissing: from the creator of Easytax. Samson Iyanu (@samtax01)

## Contributor [ Socialite Creator ]
* https://github.com/overtrue/socialite


## Reference

- [Google - OpenID Connect](https://developers.google.com/identity/protocols/OpenIDConnect)
- [Google - Credential](https://console.developers.google.com/apis/credentials/oauthclient/292942124967-kfr3aa4sbeeeospr7mn4pr29cfc64ha2.apps.googleusercontent.com)
---
- [Facebook - Graph API](https://developers.facebook.com/docs/graph-api)
- [Facebook - APP](https://developers.facebook.com/apps/)
---

- [Linkedin - Authenticating with OAuth 2.0](https://developer.linkedin.com/docs/oauth2)
- [Linkedin - APP](https://www.linkedin.com/developers/apps/new)
---

- [Github - Authenticating with OAuth 2.0](https://developer.github.com/apps/building-oauth-apps/authorizing-oauth-apps/)
- [Github - APP](https://github.com/settings/applications/new)
---
- [微博 - OAuth 2.0 授权机制说明](http://open.weibo.com/wiki/%E6%8E%88%E6%9D%83%E6%9C%BA%E5%88%B6%E8%AF%B4%E6%98%8E)
- [QQ - OAuth 2.0 登录QQ](http://wiki.connect.qq.com/oauth2-0%E7%AE%80%E4%BB%8B)
- [微信公众平台 - OAuth文档](http://mp.weixin.qq.com/wiki/9/01f711493b5a02f24b04365ac5d8fd95.html)
- [微信开放平台 - 网站应用微信登录开发指南](https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1419316505&token=&lang=zh_CN)
- [微信开放平台 - 代公众号发起网页授权](https://open.weixin.qq.com/cgi-bin/showdocument?action=dir_list&t=resource/res_list&verify=1&id=open1419318590&token=&lang=zh_CN)
- [豆瓣 - OAuth 2.0 授权机制说明](http://developers.douban.com/wiki/?title=oauth2)

## License
MIT
