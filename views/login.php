<?= View::partial("header") ?>

<?php if ($error) { ?>
    <div class="row">
        <div class="small-12 column">
            <div class="alert-box alert">
                <?=_t("L'adresse email ou le mot de passe sont incorrects. Veuillez réessayer.") ?>
            </div>
        </div>
    </div>
<?php } ?>

<div class="row">
    <form method="post" action="">
        <div class="row">
            <div class="small-12 medium-3 column text-right small-only-text-left">
                <label class="inline"><?=_t("Adresse email") ?></label>
            </div>
            <div class="small-12 medium-9 column">
                <input type="text" name="email" required="required" placeholder="<?=_t("Ex: votrenom@site.com") ?>" />
            </div>
        </div>
        <div class="row">
            <div class="small-12 medium-3 column text-right small-only-text-left">
                <label class="inline"><?=_t("Mot de passe") ?></label>
            </div>
            <div class="small-12 medium-9 column">
                <input type="password" name="password" required="required" />
            </div>
        </div>
        <div class="row">
            <div class="small-12 medium-9 small-push-3 column text-left small-only-text-center">
                <button><?= _t("Connexion") ?></button>
            </div>
            <div class="small-12 medium-3 small-pull-9 column text-right small-only-text-center">
                <a href="<?= Argv::createUrl("password-forgot") ?>" class="inline"><?= _t("Mot de passe oublié ?") ?></a>
            </div>
        </div>
    </form>
</div>

<?= View::partial("footer") ?>