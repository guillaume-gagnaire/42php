<h1><?= _t("Langues") ?></h1>
<p>
    <?= _t("Cette page vous permet d'éditer un fichier de langue. Cette fonctionnalité est à utiliser à vos risques et périls ! Toute modification peut entrainer des dysfonctionnements dans votre site.") ?>
</p>

<form method="get" action="<?= Argv::createUrl('admin') ?>">
    <input type="hidden" name="module" value="<?= Conf::get('admin.module') ?>" />
    <div class="row">
        <div class="small-12 medium-6 column">
            <label>
                <?= _t("Fichier de langue") ?>
                <select name="file">
                    <?php foreach ($files as $file) { ?>
                        <option value="<?= $file ?>"><?= $file ?></option>
                    <?php } ?>
                </select>
            </label>
        </div>
        <div class="small-12 medium-6 column">
            <label>
               <button class="small" style="margin-top: 14px;"><?= _t("Modifier") ?></button>
            </label>
        </div>
    </div>
</form>