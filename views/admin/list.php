<ul class="breadcrumbs admin-ariane">
    <li><a href="<?=Argv::createUrl('admin') ?>"><?= _t("Panneau d'administration") ?></a></li>
    <li class="current"><a href="#" onclick="return false;"><?=Conf::get('admin.moduleTitle') ?></a></li>
</ul>

<h1><?=$title ?></h1>
<a href="<?=Conf::get('admin.url') ?>&id=0" class="button"><?=_t("Ajouter ").$item_label ?></a>

<?=$items ?>

<?=$pagination ?>