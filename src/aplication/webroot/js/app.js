/**
 *  Class App
 */

function App (fbAppId) {
    var self = this;
    
    this.name = 'MiApp',
    this.facebook = {
        appId :fbAppId    
    },
    this.other,
    
    /*
     * Get ID App FB
     * @returns String {App.fbAppId}
     */
    this.getAppIdFacebook = function() {
        return this.facebook.appId;
    },
    
    /**
     * Init app in page.html only include call this function
     */
    this.initAppFacebook = function() {
        createDivFb();
        //initializing API
        window.fbAsyncInit = function() { console.log('INIT FB');
            FB.init({
                appId: self.getAppIdFacebook(),
                status: true,
                cookie: true,
                xfbml: true
            });
        };
        (function() {
            var e = document.createElement('script'); e.async = true;
            e.src = document.location.protocol +
                '//connect.facebook.net/es_LA/all.js';
            document.getElementById('fb-root').appendChild(e);
        }());
        
        // helper
        function createDivFb() {
            var element = document.getElementById('fb-root');
            if (element == null) {
                var iDiv = document.createElement('div');
                iDiv.id = 'fb-root';
                document.getElementsByTagName('body')[0].appendChild(iDiv);
            }
        }
    }
}



/**
 * Init App
 */
var App = new App('244715988912141');



/**
 * function to custom TEST
 */
function login() {

    LodingAnimate();

    FB.login(function(response) {

        if (response.status == 'connected') {

            AjaxResponse();

        } else {

            alert("No se pudo identificar al usuario");

            ResetAnimate();

        }

    }, {

        scope: 'email,publish_stream'

    });

    //AjaxResponse();

}



function AjaxResponse() {

    var myData = 'connect=1'; //For demo, we will pass a post variable, Check process_facebook.php

    jQuery.ajax({

        type: "POST",

        url: "validateUser.php",

        dataType: "html",

        data: myData,

        success: function(response) {

            $("#welcome_b").html(response); //Result

            //$(response).insertBefore('#welcome_b');

            //window.location.reload();
            //console.log('response', response);
            if (response == 'false') {
                var message = "La applicación requiere datos basicos : (email, fecha de cumpleaños) \n"
                    + "Verifique su cuenta de Facebook, para poder acceder.";
                alert(message);
            } else {
                window.location.replace("/cuenta.php?cuenta=bienvenido");
            }

        },

        error: function(xhr, ajaxOptions, thrownError) {

            $("#welcome_b").html(thrownError); //Error

        }

    });

}

function LodingAnimate() {//Show loading Image

    $('<span id="welcome_b"><img src="aplication/webroot/imgs/ajax-loader.gif" /> Conectando...</span>').insertBefore('#login');

}
