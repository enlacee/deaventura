<?php
/*
 * include in footer cuenta.php
 */
?>
<script src="<?php echo _url_ ?>aplication/webroot/js/modernizr.custom.43235.js"></script>
<script src="<?php echo _url_ ?>aplication/webroot/plugins/jquery/jquery-1.8.2.min.js" type="text/javascript"></script>
<script src="<?php echo _url_ ?>aplication/webroot/plugins/jquery/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo _url_ ?>aplication/webroot/js/bootstrap.min.js"></script>
<script src="<?php echo _url_ ?>aplication/webroot/js/messages.js" type="text/javascript" ></script>
<!--<script src="<?php echo _url_ ?>aplication/webroot/js/app/my-validation-messsage.js.js" type="text/javascript" ></script>-->

<?php if ($_GET['cuenta'] == 'misdatos') : ?>
    <!-- adding editar datos
    <link rel="stylesheet" href="<?php echo _url_ ?>aplication/webroot/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" type="text/css">
    <script type="text/javascript" src="<?php echo _url_ ?>aplication/webroot/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
    -->
    
    <!-- upload -->
    <script src="<?php echo _url_ ?>aplication/webroot/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
    <script src="<?php echo _url_ ?>aplication/webroot/plugins/jquery-file-upload/js/jquery.iframe-transport.js"></script>
    <script src="<?php echo _url_ ?>aplication/webroot/plugins/jquery-file-upload/js/jquery.fileupload.js"></script>
    
    <link href="<?php echo _url_ ?>webroot/css/module/cuenta/cuenta.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo _url_ ?>webroot/js/module/cuenta/cuenta.js"></script>
    
<?php endif; ?>

