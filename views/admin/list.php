<ul class="breadcrumbs admin-ariane">
    <li><a href="<?=Argv::createUrl('admin') ?>"><?= _t("Panneau d'administration") ?></a></li>
    <li class="current"><a href="#" onclick="return false;"><?=Conf::get('admin.moduleTitle') ?></a></li>
</ul>

<h1><?=$title ?></h1>
<a href="<?=Conf::get('admin.url') ?>&id=0" class="button"><?=_t("Ajouter ").$item_label ?></a>

<?=$items ?>

<?php if ($sortable) { ?>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script type="text/javascript">
		$(function(){
			$(".auto-list").sortable({
				items: 'tbody tr',
				update: function(event, ui){
					var data = [];
					$('.auto-list tbody tr').each(function(){
						data.push($(this).attr('data-id'));
					});
					$.ajax({
						url: '<?= Argv::createUrl('admin') ?>?module=updateTableOrder&field=<?= urlencode($sortable) ?>&tableName=<?= urlencode($sortable_table) ?>&list=' + encodeURIComponent(data.join(','))
					});
				}
			});
		});
	</script>
<?php } ?>

<?= !$filter ? $pagination : '' ?>

<?php if ($filter) { ?>
	<style type="text/css">
		.dataTables_wrapper select, .dataTables_wrapper input {
			display: inline-block;
			width: auto;
		}
	</style>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10,fh-3.1.0/datatables.min.css"/>
	<script type="text/javascript" src="https://cdn.datatables.net/s/dt/jq-2.1.4,dt-1.10.10,fh-3.1.0/datatables.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$('.auto-list').DataTable();
		});
	</script>
<?php } ?>