<?php

return function() {

  $userTenant = Yii::$app->user->getIdentity();

  return new yii\db\Connection([
      'dsn' => "mysql:host=db;dbname={$userTenant->mt_database}",
      'username' => $userTenant->mt_username,
      'password' => $userTenant->mt_password,
  ]);
};