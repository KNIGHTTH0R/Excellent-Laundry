<?php
if(!isset($dontShowBackLink) || $dontShowBackLink != true){ ?>
    <a href="<?php echo (isset($backtohome) && $backtohome == true?__BASE_URL__:$_SESSION['oldurl']);?>" id="back-page-link" ><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
    <?php
}else{ ?>
<style>
        .navbar-brand{padding-top: 20px}
</style>

<?php }
?>