<!DOCTYPE html>
<html>
<head>
    <title>Kendo UI Mobile - Hybrid Framework</title>
    <meta charset="utf-8">
    <meta name="google-signin-client_id" content="863400318860-nnb2chfdioe8p9vscjls1d9jqvks5fkf.apps.googleusercontent.com">
    <link href="../styles/kendo.mobile.all.min.css" rel="stylesheet">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/kendo.all.min.js"></script>
    <script src="content/shared/js/console.js"></script>
    <script type="text/javascript" charset="utf-8">
        var NAV_JSON_URL = "content/mobile-nav.json";
    </script>
    <script src="../js/jquery-1.11.3.min.js"></script>
    <script src="../js/jquery-migrate-1.2.1.min.js"></script>

    <script type="text/javascript" src="js/lib/jquery.js"></script>
    <script type="text/javascript" src="js/jjsonviewer.js"></script>

    <link rel="stylesheet" href="css/jjsonviewer.css">
</head>
<body id="examples">

<!-- Place this tag in your head or just before your close body tag. -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>


<!--------------------------------------------------------------------------------------------
    LOGIN
---------------------------------------------------------------------------------------------->
<div data-role="view" id="login">
    <form id="loginForm" action="./index.html">
        <img src="starwallet.png" class="km-thumbnail" style="width: 10em; height: 10em; margin: 1em auto; display: block; float: none;"/>
        <ul data-role="listview" data-style="inset">
            <li>
                <label class="km-required km-label-above">Email
                    <input value="hossamj2004@yahoo.com" name="email" type="text" />
                </label>
            </li>
            <li>
                <label class="km-label-above">Passsword
                    <input value="123456" name="password" type="password" />
                </label>
            </li>
            <li>
                <i></i>
                <a id="login" onclick="$(this).closest('form').submit();" data-role="button" data-animated="true" class="km-justified">Login</a>
            </li>
    </form>
</div>

<!--------------------------------------------------------------------------------------------
    TestAPI
---------------------------------------------------------------------------------------------->
<div data-role="view" id="vouchercards" data-show="mobileSalesViewInit" data-title="Test APIS">
    <header data-role="header" class="km-header">
        <div data-role="navbar" class="km-widget km-navbar"><div class="km-leftitem"><a data-align="left" data-role="backbutton" class="km-widget km-button km-back" href="#:back"><span class="km-text">Back</span></a></div><div class="km-view-title">
                <span data-role="view-title">Test APIS</span>
            </div></div>
    </header>
    <ul id="select-period" data-role="buttongroup" class="km-widget km-buttongroup">
        <li class="km-button km-state-active" onclick="$('#starsHistory').show();$('#redeemsHistory').hide()"><span class="km-text">
            test API
        </span></li>


        <li onclick="$('#testApiURLData').val('jwtToken=&'+$('#testApiURLData').val());" >
            jwtToken=
        </li>

    </ul>
    <ul id="select-period" data-role="buttongroup" class="km-widget km-buttongroup">

        <li class="km-button" >
            <select id="changeApi" >
                <option data-url="api/authentication/login" data-description="Slide 2 : login page  (email=[email]&password=[password])" data-data="password=123456&email=sinegit@gmail.com" value="login">
                    authentication/login
                </option>
                <option>
                    ------------------------------------------------------------------
                </option>
                <option data-url="api/customerpackage/list" data-description=""  data-data="" value="login">
                    customerpackage/list
                </option>
                <option data-url="api/brandtype/list" data-description="Slide 10 : brand types "  data-data="customer_package_id=5" value="login">
                    brandtype/list
                </option>
                <option data-url="api/brand/list" data-description="Slide 11 : brands list"  data-data="customer_package_id=11&brand_type_id=1" value="login">
                    brand/list
                </option>
                <option data-url="api/branch/list" data-description="Slide 13 : branches list"  data-data="customer_package_id=11&brand_id=5" value="login">
                    branch/list
                </option>
                <option data-url="api/voucher/list" data-description="Slide 15 : vouchers list"  data-data="customer_package_id=11&brand_id=5&branch_id=1" value="login">
                    voucher/list
                </option>
                <option>
                    ------------------------------------------------------------------
                </option>
                <option data-url="api/voucherspent/save" data-description="Slide 17 : voucher spent where cashier put his code"  data-data="customer_package_id=11&voucher_id=1&branch_id=1&cashier_id=1&cashier_password=123456" value="login">
                    voucherspent/save
                </option>
                 <option>
                    ------------------------------------------------------------------
                </option>
                <option data-url="api/profile/edit" data-description="Slide 4,5 : edit profile api ( can edit 1 item per request )"  data-data="first_name=Wallace&last_name=Mckee=1&mobile=012345678445&mobile_alternative=012457478444&birthdate=1988-10-22&password=123456&image=" value="login">
                    profile/edit
                </option>
                <option>
                    ------------------------------------------------------------------
                </option>
                <option data-url="api/notification/list" data-description="Slide 7 : notifications list"  data-data="is_read" value="login">
                    notification/list
                </option>
                <option data-url="api/notification/details" data-description=""  data-data="id=1" value="login">
                    notification/details
                </option>
                <option>
                    ------------------------------------------------------------------
                </option>
                <option data-url="api/post/list" data-description=""  data-data="" value="login">
                    post/list
                </option>
                <option data-url="api/post/details" data-description="Slide 9 : post details"  data-data="id=1" value="login">
                    post/details
                </option>
            </select>
        </li>
        <li class="km-button" id="showCurrentSystem"  ><span class="km-text">
            show system
        </span></li>
        <li class="km-button" id="clearCurrentSystem"  ><span class="km-text">
            clear system
        </span></li>

    </ul>
    <ul id="starsHistory" class="load-more" data-role="listview"  data-style="inset">
        <li>
            <label class="km-required km-label-above">API
                <input id="testApiURL" value="api/authentication/login" name="email" type="text" />
            </label>
        </li>
        <li>
            <label class="km-required km-label-above">DATA
                <input value="password=123456&email=sinegit@gmail.com" id="testApiURLData" name="email" type="text" />
            </label>
        </li>
        <li>
         <div  id="testApiURLDescription"></div>
        </li>
        <li class="km-load-more" >
           <a class="km-load km-widget km-button" id="testApiButton" data-role="button" style=""><span class="km-text">Test API</span></a></li>

        <li>
            <label id="jjson" onclick="">

            </label>
        </li>
    </ul>
    <ul id="redeemsHistory" data-role="listview" data-style="inset" style="display:none">

    </ul>
</div>




</body>
<a style="display: none" id="gotoMywallet" href="#bar" class="km-justified km-primary km-widget km-button" data-role="button"><span class="km-text">Login/Sign-up</span></a>
<script src="content/shared/js/mobile-examples.js"></script>

<script>
    //-----------------------------------------------------------------------------
    // MAIN VARS
    //-----------------------------------------------------------------------------
    var baseUrl='http://'+window.location.hostname+'/vouchercards/';
    if(  $_GET('url') == 'local')
        var baseUrl='http://'+window.location.hostname+'/vouchercards/';
    if(  $_GET('url') == 'local8888')
        var baseUrl='http://'+window.location.hostname+':8888/vouchercards/';
    if(  $_GET('url') == 'server')
        var baseUrl='http://'+window.location.hostname+':8080/vouchercards/';
    if(  $_GET('url') == 'develop')
        var baseUrl='http://'+window.location.hostname+'/vouchercards/develop/';

    if(  $_GET('url') == 'live')
        var baseUrl='http://vouchercards.org/';
    var system={};
    system.fb_token=false;
    system.jwtToken= false;
    system.userType= false;
    system.google_token= false;

    //-----------------------------------------------------------------------------
    // login
    //-----------------------------------------------------------------------------
    $(document).ready(function(){
        $('#loginForm').attr('action',baseUrl+'apiv2/authentication/login');
        submitFormToLink($('#loginForm'),onLogin);
        $('#testApiButton').click(function(){
            testApi();
        });
    });
    function onLogin(result){
      if( result.status) {
          system=result.data.system;
          goToPage('myWallet');
      }else{
          handleError(result);
      }
    }


    //-----------------------------------------------------------------------------
    // TestAPI
    //-----------------------------------------------------------------------------
    function myWallet(){
    }

    function testApi(){
        getDataFromLink(baseUrl+$('#testApiURL').val()+'?'+$('#testApiURLData').val(),{test:'test'},onTestApiLoad);
    }
    function onTestApiLoad(result){
        if( result.status) {
            console.log( result );
            $("#jjson").jJsonViewer(result);
        }else{
            handleError(result);
            $("#jjson").jJsonViewer(result);
        }
    }

    $('#changeApi').change(function(){
        $('#testApiURL').val($('#changeApi option:selected').data('url'));
        $('#testApiURLData').val($('#changeApi option:selected').data('data'));
        $('#testApiURLDescription').html($('#changeApi option:selected').data('description'));
        if($('#changeApi option:selected').data('url') == 'apiv2/profile/connectGoogle'){
        if( system.google_token  )
            $('#testApiURLData').val('code='+ system.google_token );
        }
    });

    $('#showCurrentSystem').click(function(){
        $("#jjson").jJsonViewer(system);
    });

    $('#clearCurrentSystem').click(function(){
        system.customer = false;
        system.jwtToken = false;
        system.fb_token = false;
        system.userType = false;
        $("#jjson").jJsonViewer(system);
    });


    //--------------------------------------------------------------
    // facebook connect
    //--------------------------------------------------------------
    $(document).ready(function() {
    });
    function goToFacebook(){
        kendo.ui.progress($('body'), true);
        $.ajaxSetup({ cache: true });
        $.getScript('//connect.facebook.net/en_US/sdk.js', function(){
            FB.init({
                appId:  ( $_GET('url') == 'develop' ||  $_GET('url') == 'live' ) ?  '129507677250817' :   '941342505890945',
                version: 'v2.3' // or v2.0, v2.1, v2.0
            });
            FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                    if( typeof system != "undefined" ){
                        system.fb_token = response.authResponse.accessToken;
                    }
                }
                else {
                    FB.login();
                }
                kendo.ui.progress($('body'), false);
            });
            $('#loginbutton,#feedbutton').removeAttr('disabled');
        });
    }
    function statusChangeCallback(response) {
        if( system ){
            system.fb_token = response.authResponse.accessToken;
            goToPage('facebook');
        }
    }

    //--------------------------------------------------------------
    // general functions
    //--------------------------------------------------------------
    function submitFormToLink(form,onDone){
        form.submit(function(){
            kendo.ui.progress(form, true);
            $.ajax({ data: form.serialize(),
                url:  form.attr( 'action' ),
                method:"POST",
                success: function(result){
                kendo.ui.progress(form, false);
                onDone(result);
            }});
            return false;
        })
    }
    function getDataFromLink(link,arrData,onDone){
        kendo.ui.progress($('body'), true);
        $.ajax({ data: $.extend(arrData, {jwtToken:system.jwtToken?system.jwtToken:'',google_token:system.google_token?system.google_token:'' ,fb_token:system.fb_token? system.fb_token :''} ) ,
            url:  link,
            method:"POST",
            success: function(result){

                if(  typeof result.data !=  "undefined" ){
                    if( typeof result.data.system !=  "undefined" ) {
                        if (typeof result.data.system.customer != "undefined")  system.customer = result.data.system.customer;
                        if (typeof result.data.system.jwtToken != "undefined")  system.jwtToken = result.data.system.jwtToken;
                        if (typeof result.data.system.userType != "undefined")  system.userType = result.data.system.userType;
                    }
                    onDone(result);
                }else {
                    $("#jjson").html( '<pre>'+ result+'<pre><br>' );
                }
                kendo.ui.progress($('body'), false);
        }});
    }
    function handleError(result){
        alert(result.data.error);
    }
    function goToPage(hash) {
        if (typeof window[hash] == 'function') {
            window[hash]();
        }
        location.hash = "#" + hash;
    }
    function $_GET(name) {
        var url = window.location.search;
        var num = url.search(name);
        var namel = name.length;
        var frontlength = namel+num+1; //length of everything before the value
        var front = url.substring(0, frontlength);
        url = url.replace(front, "");
        num = url.search("&");

        if(num>=0) return url.substr(0,num);
        if(num<0)  return url;
    }


    //--------------------------------------------------------------
    // Google Plus
    //--------------------------------------------------------------
    function signInCallback(googleUser) {
        var profile = googleUser.getBasicProfile();
        console.log('Name: ' + profile.getName());
        console.log(googleUser.getAuthResponse());
        system.google_token = googleUser.getAuthResponse();
    }
    var auth2;
    function start() {
        gapi.load('auth2', function() {
            auth2 = gapi.auth2.init({
                client_id: '863400318860-nnb2chfdioe8p9vscjls1d9jqvks5fkf.apps.googleusercontent.com',
                // Scopes to request in addition to 'profile' and 'email'
                scope: 'https://www.googleapis.com/auth/youtube'
            });
        });
    }start()




    //g-signin2-custom"
    $('.g-signin2-custom').click(function() {;
        // signInCallback defined in step 6.
        auth2.grantOfflineAccess({'redirect_uri': 'postmessage'}).then(
            function (googleUser) {
            var profile = googleUser;
                console.log( profile);
                system.google_token=profile.code;
         //   console.log(    system.google_token );
        }

        );

    });


</script>

<style>
    .k-loading-mask
    {
        position:           absolute;
        z-index:            1;
        top:                0;
        left:               0;
        width:              100%;
        height:             100%;
        background-color:   rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #jjson {
        -webkit-user-select: auto;
    }
</style>
</html>
