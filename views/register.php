<?= View::partial("header") ?>


<?php if (sizeof($errors)) { ?>
    <div class="row">
        <div class="small-12 column">
            <div class="alert-box alert">
                <?=_t("Une ou plusieurs erreurs sont survenues :") ?>
                <ul><li><?=implode('</li><li>', $errors) ?></li></ul>
            </div>
        </div>
    </div>
<?php } ?>


<div class="row">
    <div class="small-12 column">
        <h1><?= _t("Créer un compte") ?></h1>
        <form method="post" action="">
            <div class="row">
                <div class="small-12 medium-3 column text-right small-only-text-left">
                    <label class="inline"><?=_t("Adresse email") ?></label>
                </div>
                <div class="small-12 medium-9 column">
                    <input type="text" name="email" required="required" placeholder="<?=_t("Ex: nom.prenom@site.com") ?>" value="<?=str_replace('"', '&quot;', $email) ?>" />
                </div>
            </div>
            <div class="row">
                <div class="small-12 medium-3 column text-right small-only-text-left">
                    <label class="inline"><?=_t("Mot de passe") ?></label>
                </div>
                <div class="small-12 medium-9 column">
                    <input type="password" name="password" required="required" value="<?=str_replace('"', '&quot;', $password) ?>" />
                </div>
            </div>
            <div class="row">
                <div class="small-12 medium-3 column text-right small-only-text-left">
                    <label class="inline"><?=_t("Confirmez") ?></label>
                </div>
                <div class="small-12 medium-9 column">
                    <input type="password" name="password2" required="required" value="<?=str_replace('"', '&quot;', $password2) ?>" />
                </div>
            </div>
            <div class="row">
                <div class="small-12 medium-9 medium-push-3 column text-left small-only-text-center">
                    <button><?= _t("Créer mon compte") ?></button>
                </div>
                <div class="small-12 medium-3 medium-pull-9 column text-right small-only-text-center">

                </div>
            </div>
        </form>
    </div>
</div>

<?= View::partial("footer") ?>