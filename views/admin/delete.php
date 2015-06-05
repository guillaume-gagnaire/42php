<ul class="breadcrumbs admin-ariane">
    <li><a href="<?=Argv::createUrl('admin') ?>"><?= _t("Panneau d'administration") ?></a></li>
    <li><a href="<?=Conf::get('admin.url') ?>"><?=Conf::get('admin.moduleTitle') ?></a></li>
    <li class="current"><a href="#" onclick="return false;"><?=_t("Supprimer") ?> <?=$itemtitle ?></a></li>
</ul>

<div class="row">
	<div class="small-12 column">
		<h1><?=_t("Supprimer") ?> <?=$itemtitle ?></h1>
	</div>
</div>

<?php if ($status == 'saved') { ?>
    <div class="row">
        <div class="small-12 column">
            <div class="alert-box success">
                <?=_t("Enregistré !") ?>
            </div>
        </div>
    </div>
<?php } ?>

<div class="row">
	<div class="small-12 medium-6 large-4 columns">
		<!-- View item -->
		<div class="admin-item-preview profile-item white-bg border">
			<?php foreach ($types as $key => $type) {
					list($type, $params) = $type;
					if ($type != 'hidden') { 
						$method = "process_$type";
					?>
					
					<div class="row">
						<div class="small-12 medium-4 large-3 column text-right small-only-text-left label">
							<?=$titles[$key] ?>
						</div>
						<div class="small-12 medium-8 large-9 column">
							<?=AdminType::$method($key, $values[$key], $params, 'display') ?>
						</div>
					</div>
					
				<?php }
			} ?>
		</div>
	</div>
	<div class="small-12 medium-6 large-8 columns">
		<!-- Edit item -->
		<form method="post" action="" class="profile-item white-bg border">
            <div class="row">
                <div class="small-12 column">
                    <h2><?=_t("Êtes-vous sûr de vouloir supprimer cet élément ?") ?></h2>
                </div>
            </div>
            <div class="row">
                <div class="small-centered small-12 medium-8 large-6 column">
                    <div class="row">
                        <div class="small-6 column">
                            <a href="<?=Conf::get('admin.url') ?>" class="button secondary expand"><?=_t("Annuler") ?></a>
                        </div>
                        <div class="small-6 column">
                            <button class="button alert expand"><?=_t("Supprimer") ?></button>
                            <input type="hidden" name="delete" value="1" />
                        </div>
                    </div>
                </div>
            </div>
		</form>
	</div>
</div>