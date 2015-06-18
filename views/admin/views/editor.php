<h1><?= _t("Editer un fichier") ?> : <?= $_GET['file'] ?></h1>
<style type="text/css">
    #editor {
        width: 100%;
        height: 700px;
    }
</style>
<form method="post" action="">
    <textarea name="content" id="editor"><?= $content ?></textarea>
    <div style="margin-top: 20px">
        <button><?= _t("Enregistrer") ?></button>
    </div>
</form>
<script src="/lib/ace/ace/ace.js"></script>
<script src="/lib/ace/ace/theme-twilight.js"></script>
<script src="/lib/ace/ace/mode-php.js"></script>
<script src="/lib/ace/jquery-ace.min.js"></script>
<script>
    $('#editor').ace({ theme: 'twilight', lang: 'php' })
</script>