<ul class="breadcrumbs admin-ariane">
    <li><a href="<?= Argv::createUrl('admin') ?>">Panneau d'administration</a></li>
    <li class="current"><a href="#" onclick="return false;"><?= _t("Tests A/B") ?></a></li>
</ul>

<h1><?= _t("Tests A/B") ?></h1>

<table>
    <thead>
        <th><?= _t("URL") ?></th>
        <th><?= _t("Pages vues") ?></th>
        <th></th>
    </thead>
    <tbody>
        <?php foreach ($pages as $page) { ?>
            <tr>
                <td><?= $page['path'] ?></td>
                <td class="text-center"><?= number_format(intval($page['nb']), 0, ',', ' ') ?></td>
                <td><a href="<?= Argv::createUrl('admin').'?module=ab-view&pagehash='.$page['pagehash'] ?>"><?= _t("Accéder") ?></a></td>
            </tr>
        <?php } ?>
        <?php if (!sizeof($pages)) { ?>
            <tr>
                <td colspan="2" class="text-center">
                    <?= _t("Aucune donnée de tests A/B") ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>