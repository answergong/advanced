<?php

use backend\models\UserBackend;
use dmstr\widgets\Menu;
use mdm\admin\components\MenuHelper;

?>
<aside class="main-sidebar">
    <aside class="main-sidebar">
        <section class="sidebar">
            <?=
            Menu::widget([
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id)
            ]);
            //               ?>
        </section>
    </aside>

</aside>
