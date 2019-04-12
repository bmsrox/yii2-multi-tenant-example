### Yii2 Basic Template - Multi Tenant Example

This project is only a concept how to structure database to multiples connection.

There is other security ways to keep client data safe like ENVIRONMENT VARIABLES

Running project

    docker-compose up -d

Enter into PHP container and run:

    composer install

Enter into DB container and create 2 databases with the follow names: 

    first_client and second_client

Configure config/db.php with the first_client database name::

```
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=db;dbname=first_client',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ];
```

and run the migration. After that reconfigure the config/db.php to the second_client database name 

```
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=db;dbname=second_client',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ];
```

and run migration again.

Access the application and click over Product Link and do logon (admin/admin). You'll see the product of de first client.
If you logon again with other user (demo/demo) you'll see the product of second client database.

### Explain the logic

files used:

#### config/db-tenant.php

This way is only an example to get client connection, but always keep the client connection access safe

```
return function() {

  $userTenant = Yii::$app->user->getIdentity();

  return new yii\db\Connection([
      'dsn' => "mysql:host=db;dbname={$userTenant->mt_database}",
      'username' => $userTenant->mt_username,
      'password' => $userTenant->mt_password,
  ]);
};
```

#### config/web.php

```
'components' => [
    ...
    'dbTenant' => require __DIR__ . '/db-tenant.php'
    ...
]
```

#### models/Users.php

Here i have used fixed users, but you can use with an user table

```
private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
            'mt_database' => 'first_client',
            'mt_username' => 'root',
            'mt_password' => '',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
            'mt_database' => 'second_client',
            'mt_username' => 'root',
            'mt_password' => '',
        ],
    ];
```
#### components/ActiveRecord.php

Here I overwrote the getDb method from \yii\db\ActiveRecord.
With this the client connection will be dynamic.

```
namespace app\components;

class ActiveRecord extends \yii\db\ActiveRecord
{

    public static function getDb ()
    {
        return \Yii::$app->dbTenant;
    }

}
```

#### models/Product.php

All models that envolved tenant should be use ActiveRecord from components

```
use app\components\ActiveRecord;

class Product extends ActiveRecord
{
    ...
}

```