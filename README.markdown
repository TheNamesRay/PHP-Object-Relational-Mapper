PHP + MySQL ORM
=====================
A very simple ORM for your PHP application. If you use the MVC pattern, you will be able to use this class with your models. Basically what it does is it binds a MySQL database table to an object, making it easier to perform MySQL queries.

### Features ###

* Dead simple CRUD actions.
* Validation on every save.
* Built on top of PDO.
* No configuration.

### Requirements ###

* > PHP 5.3
* PDO_mysql extension

SELECT
-------------------

```php
$userModel = new Model('users');

// load data from table,
// 1st parameter: primary key
// 2nd parameter: value to search for
// 3rd parameter: columns to load (default:*)
// SELECT id, password FROM users WHERE username = $_POST['username']
$userModel->load('username', $_POST['username'], 'id, password'); 

echo $userModel->id;
echo $userModel->password;
```

INSERT
-------------------

```php
$user = new Model('users');

// set new data
$user->data->email='foo@bar.com';
$user->data->password='foobar';

// save the record
$user->save();
```

UPDATE
-------------------

```php
$userModel = new Model('users');

// load data from table,
// 1st parameter: primary key
// 2nd parameter: value to search for
// 3rd parameter: columns to load (default:*)
$userModel->load('username', $_POST['username'], 'id, password'); 

// modify the loaded data
$userModel->data->id=265;
$userModel->data->password='foobar';

// save the updates
// UPDATE users SET id = :update_id, password = :update_password WHERE id = :where_id
$userModel->save('id'); // choose a primary key (loaded in $userModel->load, 3rd parameter)
```

DELETE
-------------------

```php
$userModel = new Model('users');
$userModel->data->id=34;

// delete the record
$userModel->delete('id'); // choose a primary key, set above.
```

VALIDATION
-------------------

```php
$user = new Model('users');

// set new data
$user->data->username='<>';
$user->data->password='foobar';

$model->validation = function() use ($model) 
{
	if(strip_tags($model->data->motto)!==$model->data->motto)
	  return false;
};

// check if everything went well
echo $user->save() ? 'Everything is okay' : 'Validation not passed';
```
