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
    <div class="small-12 column">
        <h1><?= _t("Connexion") ?></h1>
        <form method="post" action="">
            <div class="row">
                <div class="small-12 medium-3 column text-right small-only-text-left">
                    <label class="inline"><?=_t("Adresse email") ?></label>
                </div>
                <div class="small-12 medium-9 column">
                    <input type="text" name="email" required="required" placeholder="<?=_t("Ex: nom.prenom@site.com") ?>" />
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
                <div class="small-12 medium-9 medium-push-3 column text-left small-only-text-center">
                    <div class="right">
                        <a href="<?= Argv::createUrl('socialauth').'?service=facebook'.(isset($_GET['redirect']) ? '&redirect='.urlencode($_GET['redirect']) : '') ?>"><i class="fi-social-facebook" style="font-size: 36px;"></i></a>
                        <a href="<?= Argv::createUrl('socialauth').'?service=google'.(isset($_GET['redirect']) ? '&redirect='.urlencode($_GET['redirect']) : '') ?>"><i class="fi-social-google-plus" style="font-size: 36px;"></i></a>
                    </div>
                    <button><?= _t("Connexion") ?></button>
                </div>
                <div class="small-12 medium-3 medium-pull-9 column text-right small-only-text-center">
                    <?php if (Conf::get('auth.users.canRegister', false)) { ?>
                        <a href="<?= Argv::createUrl("register") ?>" class="inline"><?= _t("Créer un compte") ?></a><br />
                    <?php } ?>
                    <a href="<?= Argv::createUrl("password-forgot") ?>" class="inline"><?= _t("Mot de passe oublié ?") ?></a>
                </div>
            </div>
        </form>
    </div>
</div>

<?= View::partial("footer") ?>