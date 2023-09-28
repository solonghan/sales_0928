<!DOCTYPE html>
<html>

<head>
    <? include("templates/header.php"); ?>

</head>
<? include("templates/nav+menu.php"); ?>
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                修改密碼
            </h1>
            <ol class="breadcrumb">
                <li class="active">修改密碼</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-12 col-xs-12">
                    <div class="panel filterable">
                        <div class="panel-heading clearfix">
                            <h3 class="panel-title pull-left m-t-6">
                                <i class="ti-view-list"></i> 修改密碼
                            </h3>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="<?=base_url() ?>mgr/dashboard/changepwd">
                                <?
                                    foreach ($field as $item) {
                                ?>
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label"><?=$item[0] ?></label>
                                    <div class="col-sm-10">
                                        <input type="<?=$item[2] ?>" class="form-control" name="<?=$item[1] ?>" value="">
                                    </div>
                                </div>
                                <?
                                    }
                                ?>                                
                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <button type="submit" class="btn btn-block btn-primary">變更</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="background-overlay"></div>
        </section>
        <!-- /.content -->
    </aside>
</div>
<!-- global js -->
<script src="js/app.js" type="text/javascript"></script>
</body>

</html>
