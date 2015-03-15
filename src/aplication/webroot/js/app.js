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
        window.fbAsyncInit = function() {
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