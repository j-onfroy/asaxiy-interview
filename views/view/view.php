<?php

use yii\helpers\Html; ?>
<div class="album">
    <div class="card">
        <h3><span class="bold-label">Firstname:</span> <?= $model['first_name'] ?></h3>
        <h3>Lastname: <?= $model['last_name'] ?></h3>
        <h3>Address: <?= $model['address'] ?></h3>
        <h3>Country: <?= $model['country_of_origin'] ?></h3>
        <h3>Email: <?= $model['email'] ?></h3>
        <h3>Phone number: <?= $model['phone_number'] ?></h3>
        <h3>Birthday: <?= $model['birthday'] ?></h3>
        <h3>Hired: <?php
            if ($model['hired'] !== 0) {
                echo "Yes";
            } else {
                echo "No";
            }
            ?></h3>
        <?= Html::a('Back', ['view/view-resume'], ['class' => 'btn btn-warning']) ?>
    </div>
</div>