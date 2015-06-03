<link rel="stylesheet" type="text/css" href="/lib/admin/foundation.css" />
<div class="row full-width wrapper">
    <div class="large-12 columns content-bg">
        <div id="top-menu">
            <div class="row">
                <div class="large-2 medium-4 small-12 columns top-part-no-padding">
                    <div class="logo-bg">
                        Administration
                        <i class="fi-list toggles" data-toggle="hide"></i>
                    </div>
                </div>
                <div class="large-10 medium-8 small-12 columns top-menu">
                    <div class="row">
                        <div class="large-6 medium-6 small-12 columns">
                            <div class="row">
                                <div class="large-8 columns">
                                    <input id="Text1" type="text" class="search-text" placeholder="Search" />
                                </div>
                            </div>
                        </div>
                        <div class="large-4 medium-6 small-12 columns text-center">
                            <div class="row">
                                <div class="medium-3 small-3 columns">
                                    <div class="notification">
                                        <i class="fi-mail"></i>
                                        <span class="mail">4</span>
                                    </div>
                                </div>
                                <div class="medium-3 small-3 columns">
                                    <div class="notification">
                                        <i class="fi-megaphone"></i>
                                        <span class="megaphone">5</span>
                                    </div>
                                </div>
                                <div class="medium-3 small-3 columns">
                                    <img src="img/32.jpg" alt="picture" class="top-bar-picture" />
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