<ul class="breadcrumbs admin-ariane">
    <li><a href="<?= Argv::createUrl('admin') ?>">Panneau d'administration</a></li>
    <li><a href="<?= Argv::createUrl('admin').'?module=ab' ?>"><?= _t("Tests A/B") ?></a></li>
    <li class="current"><a href="#" onclick="return false;"><?= $url ?></a></li>
</ul>

<h1><?= _t("Tests A/B") ?>: <?= $url ?></h1>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {


        // Conversion globale
        var data = google.visualization.arrayToDataTable([
            ['<?= _t("Donnée") ?>', '<?= _t("Valeur") ?>'],
            ['Pages sans conversion', <?= $totalviews - $totalclicks ?>],
            ['Pages avec conversion', <?= $totalclicks ?>]
        ]);
        var options = {
            pieHole: 0.4,
        };
        var chart = new google.visualization.PieChart(document.getElementById('conversionglobale'));
        chart.draw(data, options);

        var data = new google.visualization.DataTable();
        data.addColumn('date', '<?= _t("Jour") ?>');
        data.addColumn('number', '<?= _t("Vues") ?>');
        data.addColumn('number', '<?= _t("Clics") ?>');
        data.addRows([<?php
            $days = [];
            foreach ($viewlist as $view) {
                $view['date'] = date('Y-m-d', strtotime($view['date']));
                if (!isset($days[$view['date']]))
                    $days[$view['date']] = [
                        'views' => 0,
                        'clicks' => 0
                    ];
                $days[$view['date']]['views']++;
                if ($view['clicked'] == 1)
                    $days[$view['date']]['clicks']++;
            }
            $data = '';
            foreach ($days as $date => $d)
                $data .= ($data != '' ? ', ' : '') . '[new Date("'.$date.'"), '.$d['views'].', '.$d['clicks'].']';
            echo $data;
        ?>]);
        var options = {};
        var chart = new google.visualization.LineChart(document.getElementById('totalviews'));
        chart.draw(data, options);
        
        
        <?php
	    
	    foreach ($list as $view) {
		    $viewhash = md5($view['file']);
		    ?>
		    
		    var data = google.visualization.arrayToDataTable([
	            ['<?= _t("Donnée") ?>', '<?= _t("Valeur") ?>'],
	            ['Pages sans conversion', <?= $view['views'] - $view['totalclicks'] ?>],
	            ['Pages avec conversion', <?= $view['totalclicks'] ?>]
	        ]);
	        var options = {
	            pieHole: 0.4,
	        };
	        var chart = new google.visualization.PieChart(document.getElementById('<?= $viewhash ?>_conversion'));
	        chart.draw(data, options);
		    
		    
		    
		    
		    
		    var data = google.visualization.arrayToDataTable([
	            ['<?= _t("Donnée") ?>', '<?= _t("Valeur") ?>']
	            <?php
		        foreach ($view['clicks'] as $click) {
			    	?>
			    	,['<?= $click['param'] == '' ? '(not set)' : $click['param'] ?>', <?= $click['nb'] ?>]
			    	<?php
		        }
		        ?>
	        ]);
	        var options = {
	            pieHole: 0.4,
	        };
	        var chart = new google.visualization.PieChart(document.getElementById('<?= $viewhash ?>_clicks'));
	        chart.draw(data, options);
		    
		    
		    <?php
	    }
	        
	    ?>
    }
</script>

<div class="row">
    <div class="small-12 medium-6 column">
        <h2><?= _t("Conversation globale") ?>: <?php $percent = ($totalclicks / $totalviews) * 100; echo number_format($percent, 2, ',', ' ') ?>%</h2>
        <div id="conversionglobale" style="height: 300px;"></div>
    </div>
    <div class="small-12 medium-6 column">
        <h2><?= _t("Pages vues") ?>: <?= $totalviews ?></h2>
        <div id="totalviews" style="height: 300px;"></div>
    </div>
</div>
<?php
foreach ($list as $view) {
    $viewhash = md5($view['file']);
	?>
	<div class="row">
		<div class="small-12 column">
			<h2><?= _t("Vue").' : '.$view['file'] ?></h2>
		</div>
	</div>
	<div class="row">
	    <div class="small-12 medium-6 column">
		    <div id="<?= $viewhash ?>_conversion" style="height: 300px;"></div>
	    </div>
	    <div class="small-12 medium-6 column">
		    <div id="<?= $viewhash ?>_clicks" style="height: 300px;"></div>
	    </div>
	</div>
	<?php
}
?>