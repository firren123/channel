<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Collapse;
use yii\bootstrap\Tabs;
use yii\widgets\Menu;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src="/js/jquery-1.10.2.min.js"></script>
    <script src="/js/common.js"></script>
    <style>

        .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: middle;
            text-align: center;
            border-top: 1px solid #ddd;
        }
    </style>

</head>
<body>

    <?php

    $this->beginBody() ?>
    <div class="wrap">
        <ul>


<?php
//            foreach($this->params['nav_list'] as $k=>$v){
//                echo "<li><a href='".$v['id']."'>".$v['name']."</a>";
//                echo "</li>";
//            }
            NavBar::begin([
                'brandLabel' => \Yii::$app->name,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
//            $menuItems = [
//                ['label' => '首页', 'url' => ['/site/index']],
//                ['label' => '商品管理', 'url' => ['/goods/index']],


            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];
            } else {
                @$menuItems = $this->params['module_list'];
                $menuItems[] = [
                    'label' => '退出 (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-left'],
                'items' => $menuItems,
            ]);
            NavBar::end();
//        ?>
        </ul>
        <div class="container">
            <div class="col-xs-2">

                <?php
                if (Yii::$app->user->isGuest) {

                } else {
                    if (isset($this->params['menu_list'])) {
                        echo Nav::widget([
                            'items'=> @$this->params['menu_list'],
                            'options' => ['class' =>'navigation']
                        ]);
                    }

                }

                ?>


            </div>



            <div class="col-xs-10">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= $content ?>
            </div>

        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>Copyright © 2013 - 2015 爱样品·500米版权所有</p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    <link rel="stylesheet" href="/js/artdialog/css/ui-dialog.css">
    <script src="/js/artdialog/dist/dialog-min.js"></script>
    <script src="/js/artdialog/dist/dialog-plus.js"></script>
    <input type="hidden" id="base_url" value="<?php echo Yii::$app->params['baseUrl']; ?>" />
    <input type="hidden" id="img_url" value="<?php echo Yii::$app->params['imgHost']; ?>" />

    <script src="/js/layer/layer.js"></script>
    <script src="/js/globalpopup.js?<?php echo \Yii::$app->params['jsVersion']; ?>">"></script>
</body>
</html>
<?php $this->endPage() ?>
