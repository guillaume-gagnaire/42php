<script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/dropzone/dropzone.css" />
<script type="text/javascript" src="/lib/dropzone/dropzone.js"></script>
<link rel="stylesheet" type="text/css" href="/lib/tags/min.css" />
<script type="text/javascript" src="/lib/tags/min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript">
	Dropzone.autoDiscover = false;
	function addLineToKeyval(key) {
		$('#keyval_'+key+'_items').append('<div class="row">'+
			'<div class="small-5 column">'+
				'<input type="text" name="" value="" propname="key" placeholder="Nom" />'+
			'</div>'+
			'<div class="small-5 column">'+
				'<input type="text" name="" value="" propname="value" placeholder="Valeur" />'+
			'</div>'+
			'<div class="small-2 column">'+
				'<a href="#" onclick="var el = this.parentNode.parentNode; if (el) {el.parentNode.removeChild(el);} reloadKeyvalIds(\''+key+'\'); return false;"><i class="fi-x" style="font-size: 16px; color: red; margin-top: 10px; display: inline-block;"></i></a>'+
			'</div>'+
		'</div>');
		reloadKeyvalIds(key);
	}
	function reloadKeyvalIds(key) {
		var p = document.getElementById('keyval_'+key+'_items');
		if (p) {
			var els = p.getElementsByClassName('row');
			for (var i = 0; i < els.length; i++) {
				$(els[i]).find('[propname=key]').attr('name', key+'['+i+'][key]');
				$(els[i]).find('[propname=value]').attr('name', key+'['+i+'][value]');
			}
		}
	}
	function updateDzField(dz) {
		if (!dz) 
			return;
		for (var i = 0; i < dz.files.length; i++) {
			dz.files[i].previewElement.setAttribute('data-order', i);
			dz.files[i].order = i;
		}
		var input = document.getElementById(dz.element.getAttribute('data-result'));
		var f = [];
		for (var i = 0; i < dz.files.length; i++) {
			if (dz.files[i].finalPath)
				f.push(dz.files[i].finalPath);
		}
		input.value = f.join(';');
	}
	function sortDz(dz) {
		var newFiles = [];
		$(dz.element).find('.dz-preview').each(function(){
			var order = parseInt($(this).attr('data-order'));
			for (var i = 0; i < dz.files.length; i++) {
				if (dz.files[i].order == order)
					newFiles.push(dz.files[i]);
			}
		});
		dz.files = newFiles;
		updateDzField(dz);
	}
</script>
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