PHP + MySQL ORM
=====================
> **Note:** This ORM is still in development, but it supports basic CRUD actions.
A very simple ORM for your PHP application. If you use the MVC pattern, you will be able to use this class with your models. Basically what it does is it binds a MySQL database table to an object, making it easier to perform MySQL queries.

### Features ###

* Dead simple CRUD actions.
* Validation on every save.
* Built on top of PDO.
* No configuration (except Database)
* Prepared statements.

### Requirements ###

* PHP 5.3 or greater
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

echo $userModel->data->id;
echo $userModel->data->password;
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
// UPDATE users SET id = :update_id, password = :update_password WHERE id = 265
$userModel->save('id'); // choose a primary key (loaded in $userModel->load, 3rd parameter)
```

DELETE
-------------------

```php
$userModel = new Model('users');
$userModel->data->id=34;

// delete the record
$userModel->delete('id'); // DELETE ... WHERE id = 34
```

VALIDATION
-------------------

```php

// example validation with INSERT
$userModel = new Model('users');

// set new data
$userModel->data->username='<hey der ima script0r>';

$userModel->validation = function() use ($userModel) 
{
	if(strip_tags($userModel->data->username)!==$userModel->data->username)
	  return false;
};

// check if everything went well
echo $userModel->save() ? 'Everything is okay' : 'Validation not passed';

// you can also do validations with UPDATE, 
// just add the load($key,$value,$columns) after $userModel = new Model('users')
// and add the primary key in the $userModel->save($pk) in the last line
```
