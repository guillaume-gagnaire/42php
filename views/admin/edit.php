<div class="row">
	<div class="small-12 column">
		<h1><?=_t("Voir") ?> <?=$itemtitle ?></h1>
	</div>
</div>

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
						<div class="small-12 medium-4 large-3 column">
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
					?>
					<div class="row">
						<div class="small-12 medium-4 large-3 column">
							<label class="inline"><?=$titles[$key] ?></label>
						</div>
						<div class="small-12 medium-8 large-9 column">
							<?=AdminType::$method($key, $values[$key], $params, 'edit') ?>
						</div>
					</div>
			<?php } ?>
			
			<div class="row">
				<div class="small-12 column">
					<button><?=_t("Enregistrer les modifications") ?></button>
				</div>
			</div>
		</form>
	</div>
</div>