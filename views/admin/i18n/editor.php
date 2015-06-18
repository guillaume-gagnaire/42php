<h1><?= _t("Editer une langue") ?> : <?= $_GET['file'] ?></h1>
<style type="text/css">

</style>
<form method="post" action="">
    <?php foreach ($content as $k => $v) { ?>
    <div class="row">
        <div class="small-12 medium-4 column text-right small-only-text-left">
            <label class="inline"><?= $k ?></label>
        </div>
        <div class="small-12 medium-8 column">
            <input type="text" name="content[<?= base64_encode($k) ?>]" value="<?= str_replace('"', '&quot;', $v) ?>" />
        </div>
    </div><hr />
    <?php } ?>
    <div style="margin-top: 20px">
        <button><?= _t("Enregistrer") ?></button>
    </div>
</form>