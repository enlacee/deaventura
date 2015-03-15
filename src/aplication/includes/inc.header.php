<!DOCTYPE html>
<html lang="es">
    <head>
        <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1"/>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta http-equiv="content-language" content="es"/>
        <title><?php echo $_GET["titulo"] . ' - ' . NOMBRE_SITIO ?></title>
        <meta name="description" content="<?php echo $descripcion ?>" />
        <meta name="language" content="spanish" />
        <meta name="country" content="PE, Perú" /> 

        <link rel="shortcut icon" href="<?php echo _imgs_ ?>/favicon_deaventura.png" />
        <link href="<?php echo _imgs_ . 'facebook/' . $image_face ?>" rel="image_src" />
        <link href="<?php echo _url_ ?>aplication/webroot/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo _url_ ?>aplication/webroot/css/core.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo _url_ ?>aplication/webroot/css/message.css" rel="stylesheet" type="text/css" />
        <link href="http://fonts.googleapis.com/css?family=Roboto:400,300,300italic,400italic,700,700italic&subset=latin,greek,latin-ext" rel="stylesheet" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'/>
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'/>
        <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700' rel='stylesheet' type='text/css'/>
        <link href='http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz' rel='stylesheet' type='text/css'/>
        <!--[if lt IE 9]>
            <link href="<?php echo _url_ ?>aplication/webroot/css/ie6.css" rel="stylesheet" type="text/css" />
        <![endif]-->
        
        <?php if (isset($_GET['aventura'])) { ?>
            <link href="<?php echo _url_ ?>aplication/webroot/css/awShowcase.css" rel="stylesheet" type="text/css" media="all" />
        <?php } ?>
        <?php if ($_GET["cuenta"] == "compartir" || $_GET["cuenta"] == "edit") { ?>
            <!-- CSS to style the file input field as button and adjust the jQuery UI progress bars -->
            <link href="<?php echo _url_ ?>aplication/utilities/fileUpload/css/jquery.fileupload-ui.css" rel="stylesheet" >
        <?php } ?>

		<script type="text/javascript" src="<?php echo _url_ ?>aplication/webroot/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
        <script src="<?php echo _url_ ?>aplication/webroot/js/modernizr.custom.43235.js"></script>
        <script src="<?php echo _url_ ?>aplication/webroot/js/jquery-1.8.2.min.js" type="text/javascript"></script>
           
           
        <script type="text/javascript" src="<?php echo _url_ ?>aplication/webroot/js/bootstrap.min.js"></script>
        <script src="<?php echo _url_ ?>aplication/webroot/js/js.js" type="text/javascript" ></script>
        <script src="<?php echo _url_ ?>aplication/webroot/js/messages.js" type="text/javascript" ></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places" type="text/javascript" ></script>


        <?php if (isset($_GET['aventura'])) { ?>
            <script type="text/javascript" src="<?php echo _url_ ?>aplication/webroot/js/jquery.aw-showcase.js"></script>
        <?php } ?>
        <?php if ($_GET["cuenta"] == "compartir" || $_GET["cuenta"] == "edit") { ?>

            <!-- The File Upload plugin -->
            <script src="<?php echo _url_ ?>aplication/utilities/fileUpload/js/vendor/jquery.ui.widget.js"></script>
            <script src="<?php echo _url_ ?>aplication/utilities/fileUpload/js/tmpl.min.js"></script>            
            <script src="<?php echo _url_ ?>aplication/utilities/fileUpload/js/load-image.min.js"></script>
            <script src="<?php echo _url_ ?>aplication/utilities/fileUpload/js/canvas-to-blob.min.js"></script>

            <script src="<?php echo _url_ ?>aplication/utilities/fileUpload/js/jquery.iframe-transport.js"></script>
            <script src="<?php echo _url_ ?>aplication/utilities/fileUpload/js/jquery.fileupload.js"></script>
            <script src="<?php echo _url_ ?>aplication/utilities/fileUpload/js/jquery.fileupload-process.js"></script>
            <script src="<?php echo _url_ ?>aplication/utilities/fileUpload/js/jquery.fileupload-image.js"></script>
            <script src="<?php echo _url_ ?>aplication/utilities/fileUpload/js/jquery.fileupload-validate.js"></script>
            <script src="<?php echo _url_ ?>aplication/utilities/fileUpload/js/jquery.fileupload-ui.js"></script>
            <!-- The XDomainRequest Transport is included for cross-domain file deletion for IE8+ -->
            <!--[if gte IE 8]><script src="aplication/utilities/fileUpload/js/cors/jquery.xdr-transport.js"></script><![endif]-->
            <!-- End: The File Upload plugin -->
            
            <script src="<?php echo _url_ ?>aplication/webroot/js/jquery-ui-1.10.3.custom.min.js"></script>
        <?php } ?>
         

        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-4750564-13']);
            _gaq.push(['_trackPageview']);
            (function() {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();
        </script>
        <!-- [if lt IE 9]
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js" > </script>
        <![endif] -->
    </head>
