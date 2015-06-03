<div class="row full-width wrapper">
    <div class="large-12 columns content-bg">
        <div id="top-menu">
            <div class="row">
                <div class="large-2 medium-4 small-12 columns top-part-no-padding">
                    <div class="logo-bg">
                        <span class="admin-title"><?= _t("Administration") ?></span>
                        <i class="fi-list toggles" data-toggle="hide"></i>
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