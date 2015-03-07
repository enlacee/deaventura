<?php
include("inc.aplication_top.php");

// Recordar por 30 dias la cuenta.
if ($_POST) {
    if ($_POST['recordar_si_MKD'] == 'si') {
        setcookie("pass_MKD", "$_POST[password]", time() + 2592000);
        setcookie("email_MKD", "$_POST[login]", time() + 2592000);
    } else {
        setcookie("pass_MKD", "", time() + 604800);
        setcookie("email_MKD", "", time() + 604800);
    }
}

include(_includes_ . "admin/inc.header.php");
?>
<body>
    <div id="dw-window"> 
        <div id="dw-admin">
            <div id="dw-menu">
                <!-- Menu -->
                <?php include(_includes_ . "admin/inc.top.php"); ?>
            </div>
            <div id="dw-page">
                <div id="dw-cuerpo">
                    <?php $sesion->inicio($msgbox); ?>
                </div>
            </div> 

        </div>
    </div>

</body>
</html>
<?php include("inc.aplication_bottom.php"); ?>