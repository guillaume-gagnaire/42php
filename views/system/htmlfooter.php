        <script src="/lib/foundation/js/foundation.min.js"></script>
        <script type="text/javascript">
            $(document).foundation();
        </script>
        <?php foreach (Conf::get('page.js', []) as $script) { ?>
            <script type="text/javascript" src="<?=$script ?>"></script>
        <?php } ?>
        <?php $mod = Conf::get('route.name', ''); $a = Conf::get('page.analytics', ''); if ($a != '' && $mod != 'admin') { ?>
            <script type="text/javascript">
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                ga('create', '<?=$a ?>', 'auto');
                ga('require', 'displayfeatures');
                ga('send', 'pageview');
            </script>
        <?php } ?>
        <?php foreach (Conf::get('page.bottom', []) as $script) { ?>
            <?=$script ?>
        <?php } ?>
        <?php if (Conf::get('debug', false)) {
            echo View::partial('system/debug');
        } ?>
    </body>
</html>