<div class="row full-width wrapper hide-for-medium-down">
    <div class="large-12 columns content-bg">
        <div id="top-menu">
            <div class="row">
                <div class="large-2 medium-4 small-12 columns top-part-no-padding">
                    <div class="logo-bg">
                        <span class="admin-title"><?= _t("Administration") ?></span>
                    </div>
                </div>
                <div class="large-10 medium-8 small-12 columns top-menu">
                    <div class="row">
                        <div class="large-6 medium-6 small-12 columns">
                            &nbsp;
                        </div>
                        <div class="large-4 medium-6 small-12 columns text-center">
                            <div class="row">
                                <div class="small-6 column">&nbsp;</div>
                                <div class="medium-3 small-3 columns">
                                    <a href="<?=Argv::createUrl('admin').'?module=users&id='.Session::get('user.id') ?>">
                                        <?php if (Session::get('user.photo', '') == '') { ?>
                                            <div class="notification">
                                                <i class="fi-torso" style="font-size: 32px; color: #fff"></i>
                                            </div>
                                        <?php } else { ?>
                                            <span class="round-photo" style="background-image: url(<?=Session::get('user.photo', '') ?>)"></span>
                                        <?php } ?>
                                    </a>
                                </div>
                                <div class="medium-3 small-3 columns">
                                    <a href="<?=Argv::createUrl('logout') ?>"><i class="fi-power power-off"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="no-padding">
                <div class="large-2 medium-12 small-12 columns">
                    <ul class="side-nav">
                        <?= $nav ?>
                    </ul>
                </div>
            </div>
            <div class="large-10 medium-12 small-12 columns light-grey-bg-pattern">
               <?= $content ?>
            </div>
        </div>
    </div>
</div>



<!-- mobile view -->
<div class="off-canvas-wrap show-for-medium-down" style="-webkit-user-select: none; -moz-user-select: none; user-select: none;" data-offcanvas>
    <div class="inner-wrap" style="min-height: 100vh">

        <nav class="tab-bar">
            <div class="left-small">
                <a href="#idOfLeftMenu" role="button" aria-controls="idOfLeftMenu" aria-expanded="false" class="left-off-canvas-toggle menu-icon" ><span></span></a>
            </div>
            <h1 style="text-align: center; color: #fff"><?= _t("Administration") ?></h1>
        </nav>

        <aside class="left-off-canvas-menu">
            <ul class="off-canvas-list">
                <?= $nav ?>
            </ul>
        </aside>

        <div class="row">
            <div class="small-12 column">
                <?= $content ?>
            </div>
        </div>
        <a class="exit-off-canvas"></a>

    </div>
</div>