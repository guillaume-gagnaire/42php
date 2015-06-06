<ul class="breadcrumbs admin-ariane">
    <li><a href="<?=Argv::createUrl('admin') ?>"><?= _t("Panneau d'administration") ?></a></li>
    <li><a href="<?=Conf::get('admin.url') ?>"><?=Conf::get('admin.moduleTitle') ?></a></li>
    <li class="current"><a href="#" onclick="return false;"><?=_t("Éditer") ?> <?=$itemtitle ?></a></li>
</ul>

<div class="row">
	<div class="small-12 column">
		<h1><?=_t("Éditer") ?> <?=$itemtitle ?></h1>
	</div>
</div>

<?php if ($status == 'saved' && !sizeof($unique)) { ?>
    <div class="row">
        <div class="small-12 column">
            <div class="alert-box success">
                <?=_t("Enregistré !") ?>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (sizeof($unique)) { ?>
    <div class="row">
        <div class="small-12 column">
            <div class="alert-box alert">
                <?=_t("Les champs suivants doivent être uniques :") ?>
                <ul>
                    <?php foreach ($unique as $uk) { ?>
                        <li>
                            <?=$titles[$uk] ?>
                        </li>
                    <?php } ?>
                </ul>
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
                    $method = "process_$type";
                    if ($type != "hidden") {
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
		<form method="post" action="" enctype="multipart/form-data" class="profile-item white-bg border">
			<input type="hidden" name="MAX_FILE_SIZE" value="80000000" />
			
			<?php foreach ($types as $key => $type) {
					list($type, $params) = $type;
					$method = "process_$type";
                    if ($type == 'hidden') {
                        echo AdminType::$method($key, $editing[$key], $params, 'edit');
                    } else {
					?>
					<div class="row">
						<div class="small-12 medium-4 large-3 column text-right small-only-text-left">
							<label class="inline" for="field_<?=$key ?>"><?=$titles[$key] ?></label>
						</div>
						<div class="small-12 medium-8 large-9 column">
							<?=AdminType::$method($key, $editing[$key], $params, 'edit') ?>
						</div>
					</div>
			<?php } } ?>
			
			<div class="row">
				<div class="small-12 column text-center">
					<button><?=_t("Enregistrer les modifications") ?></button>
				</div>
			</div>
		</form>
	</div>
</div>