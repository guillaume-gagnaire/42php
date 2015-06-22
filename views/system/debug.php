<div id="debugbar">
    <div class="cell right">
        <span>Load time:</span> <?= number_format((microtime(true) - floatval(Conf::get('inspector.starttime', microtime(true)))) * 1000, 3, '.', ' ') ?>ms
    </div>
    <div class="cell">
        <span>SQL:</span> <?= Conf::size('inspector.queries') ?> queries
        <div class="inner">
            <?php foreach (Conf::get('inspector.queries', []) as $i => $query) { ?>
                <div class="row <?= $query['error'] ? 'error' : '' ?>">
                    <div class="small-2 medium-1 column">#<?= $i + 1 ?></div>
                    <div class="small-3 medium-2 large-1 column text-right"><?= $query['time'] ?></div>
                    <div class="small-7 medium-9 large-10 column"><?= $query['query'] ?></div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="/lib/debug/debug.css" />
<script type="text/javascript" src="/lib/debug/debug.js"></script>