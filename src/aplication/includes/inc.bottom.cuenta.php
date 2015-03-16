<?php
/*
 * include in footer cuenta.php
 */
?>
<?php if ($_GET['cuenta'] == 'misdatos2') : ?>
    <!-- adding editar datos -->
    <link rel="stylesheet" href="<?php echo _url_ ?>aplication/webroot/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" type="text/css">
    <script type="text/javascript" src="<?php echo _url_ ?>aplication/webroot/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>

    <!-- upload -->
    <script src="<?php echo _url_ ?>aplication/webroot/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js"></script>
    <script src="<?php echo _url_ ?>aplication/webroot/plugins/jquery-file-upload/js/jquery.iframe-transport.js"></script>
    <script src="<?php echo _url_ ?>aplication/webroot/plugins/jquery-file-upload/js/jquery.fileupload.js"></script>
    
    <link href="<?php echo _url_ ?>webroot/css/module/cuenta/cuenta.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo _url_ ?>webroot/js/module/cuenta/cuenta.js"></script>
    
<?php endif; ?>

