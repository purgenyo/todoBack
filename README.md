# todoBack

run and install

<pre>
  git clone https://github.com/purgenyo/todoBack.git
  cd backTodo
  composer install
</pre>

You must configure db_config.php for connect to db, then

<pre>
  doctrine orm:schema-tool:create
</pre>

for tests:
<pre>
  cd <project_folder>/tests
  ../vendor/bin/phpunit -c phpunit.xml.dest
</pre>

# methods
POST /user/registration
<pre>
POST /user/registration
{
	"username": "test_1",
	"password": "123"
}

response:
{
    "data": {
        "username": "test_1",
        "token": null,
        "created": {
            "date": "2017-09-15 07:02:38.000000",
            "timezone_type": 3,
            "timezone": "Europe/Moscow"
        },
        "updated": {
            "date": "2017-09-15 07:02:38.000000",
            "timezone_type": 3,
            "timezone": "Europe/Moscow"
        }
    },
    "status": 200
}
</pre>

POST /user/login
<pre>
POST /user/login
{
	"username": "test_1",
	"password": "123"
}

response:
{
    "data": {
        "username": "test_1",
        "token": "2af03946ef6b8e69eacc473fa1c32e021d5ac435",
        "created": {
            "date": "2017-09-15 07:02:38.000000",
            "timezone_type": 3,
            "timezone": "Europe/Moscow"
        },
        "updated": {
            "date": "2017-09-15 07:11:31.000000",
            "timezone_type": 3,
            "timezone": "Europe/Moscow"
        }
    },
    "status": 200
}
</pre>


GET, PUT, DELETE, UPDATE /todo - 'Bearer' Authorization required from POST /user/login
