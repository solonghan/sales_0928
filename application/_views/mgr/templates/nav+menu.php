
<body class="skin-default">
<div class="preloader">
    <div class="loader_img"><img src="img/loader.gif" alt="loading..." height="64" width="64"></div>
</div>
<header class="header">
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="<?php echo base_url(); ?>mgr/" class="logo" style="color:#FFF; font-size: 24px;">
            Jenny Chou
        </a>
        <div>
            <a href="" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <i class="fa fa-fw ti-menu"></i>
            </a>
        </div>

        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li>
                    <a href="<?=base_url() ?>" target="_blank">
                        <div class="riot" style="padding-left: 0;"><i class="fa fa-fw fa-bolt"></i>觀看前台</div>
                    </a>
                </li>
                <li class="dropdown user user-menu">
                    <a href="" class="dropdown-toggle padding-user" data-toggle="dropdown">
                        <img src="<?php echo $this->session->avatar ?>" width="35" class="img-circle img-responsive pull-left"
                             height="35" alt="User Image">
                        <div class="riot">
                            <div>
                                <?php echo $this->session->name ?>
                                <span>
                                    <i class="caret"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header" style="height: auto;">
                            <img src="<?php echo $this->session->avatar ?>" class="img-circle" alt="User Image">
                            <p><?php echo $this->session->name ?><br><?php echo $this->session->priv_name ?></p>
                        </li>
                        <!-- Menu Body -->
                        <!-- <li class="p-t-3">
                            <a href="">
                                <i class="fa fa-fw ti-user"></i> 個人資料
                            </a>
                        </li>
                        <li role="presentation"></li>
                        <li>
                            <a href="">
                                <i class="fa fa-fw ti-settings"></i> 帳號設定
                            </a>
                        </li> -->
                        <li>
                            <a href="<?=base_url() ?>mgr/dashboard/changepwd">
                                <i class="fa fa-fw ti-settings"></i> 修改密碼
                            </a>
                        </li>
                        <li role="presentation" class="divider"></li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?=base_url() ?>mgr/lock">
                                    <i class="fa fa-fw ti-lock"></i>
                                    鎖定
                                </a>
                            </div>
                            <div class="pull-right">
                                <a href="<?=base_url() ?>mgr/logout">
                                    <i class="fa fa-fw ti-shift-right"></i>
                                    登出
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        
    </nav>
</header>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">
        <!-- sidebar: style can be found in sidebar-->
        <section class="sidebar">
            <div id="menu" role="navigation">
                <div class="nav_profile">
                    <div class="media profile-left">
                        <a class="pull-left profile-thumb">
                            <img src="<?=$this->session->avatar ?>" class="img-circle" alt="User Image">
                        </a>
                        <div class="content-profile">
                            <h4 class="media-heading">
                                <?=$this->session->name ?><br>
                                <span><?=$this->session->priv_name ?></span>
                            </h4>
                            <ul class="icon-list">
                                <li><a href="<?=base_url() ?>mgr/lock"> <i class="fa fa-fw ti-lock"></i> </a></li>
                                <li><a href="<?=base_url() ?>mgr/dashboard/changepwd"> <i class="fa fa-fw ti-settings"></i> </a></li>
                                <li>
                                    <a href="<?=base_url() ?>mgr/logout">
                                        <i class="fa fa-fw ti-shift-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <ul class="navigation">
                    <li<?php echo ($active=='DASHBOARD')?' class="active"':"" ?>>
                        <a href="<?=base_url() ?>mgr">
                            <i class="menu-icon ti-desktop"></i>
                            <span class="mm-text ">主控版</span>
                        </a>
                    </li>
                    <?php 
                    foreach ($menu as $obj)
                    {
                        $contr = $obj['controller'];
                    ?>
                        <li class="menu-dropdown<?=($active==$contr)?' active':"" ?>">
                            <a href="<?=($obj['url']=="")?"":base_url()."mgr/".$obj['url'] ?>">
                                <? if (strpos($obj['icon'], "png") !== FALSE): ?>
                                <img src="<?=$obj['icon'] ?>" style="width: 16px; margin: 3px;">
                                <? else: ?>
                                <i class="menu-icon <?=$obj['icon'] ?>"></i>
                                <? endif; ?>
                                <span class=""><?=$obj['name'] ?></span>
                                <? if (count($obj['sub_menu']) > 0): ?>
                                <span class="fa arrow"></span>
                                <? endif; ?>
                                <? if($obj['badge'] > 0){ ?>
                                <small class="badge" style="margin: 0 5px;"><?=$obj['badge'] ?></small>
                                <? }else if(!is_numeric($obj['badge'])){ ?>
                                <small class="badge" style="margin: 0 5px; background-color: #FE9BB1;"><?=$obj['badge'] ?></small>
                                <? } ?>
                            </a>
                            <? if (count($obj['sub_menu']) > 0): ?>
                                <ul class="sub-menu">
                                <?
                                    foreach ($obj['sub_menu'] as $sub_obj) {
                                        $sub_func = $sub_obj['controller'];
                                ?>
                                <li<?=(isset($sub_active) && $sub_active == $sub_func)?' class="active"':'' ?>>
                                    <? if (substr($sub_obj['url'], 0, 4) == "http"): ?>
                                    <a href="<?=$sub_obj['url'] ?>" target="_blank">
                                    <? else: ?>
                                    <a href="<?=base_url()."mgr/".$sub_obj['url'] ?>">
                                    <? endif; ?>
                                        <?=$sub_obj['name'] ?>
                                        <? if($sub_obj['badge'] > 0){ ?>
                                        <small class="badge" style="margin: 0 5px;"><?=$sub_obj['badge'] ?></small>
                                        <? }else if(!is_numeric($sub_obj['badge'])){ ?>
                                        <small class="badge" style="margin: 0 5px; background-color: #FE9BB1;"><?=$sub_obj['badge'] ?></small>
                                        <? } ?>
                                    </a>
                                </li>

                                <?
                                    }
                                ?>
                                </ul>
                            <? endif; ?>
                        </li>
                    <?
                    }
                    ?>
                </ul>
            </div>
            <!-- menu -->
        </section>
        <!-- /.sidebar -->
    </aside>