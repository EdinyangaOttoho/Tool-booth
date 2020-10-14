# ToolBooth
This is a special-purpose PHP Library meant to enhance the development process of back-end developers (MySQL) by helping them to automate some functionality such as variable sanitation, validation and SQL Query construction. It is made with best practices and a recent version of PHP (7.2.0)

## Usage
To use Toolbooth, simply include the lib.php file into your back-end script and create an instance of the ToolBooth class thus:

```php
include("Tool-booth/lib.php");
$toolbooth = new ToolBooth();
```
Once that is done, you can now call all the member methods of the class and utilize them in their unique approaches. The class contains four (4) methods which include:

### insertSQL()
### updateSQL()
### validator()
### sanitizeVal()

###### NOTE: The Table columns must have the same names with the variables in order for this tool to work with them

### insertSQL()
This method accepts four positional arguments and is used to construct SQL INSERT statements using request variables (GET, POST, PUT or any other), which are listed below:

#### Database Connection [string]:
This is a connection object (variable) and the first argument, which is a successfully established connection to a MySQL database such as:
```php
$db = mysqli_connect($host, $user, $password, $database);
```
#### Table [string]:
This is the third argument and the specific table in which the query is to be executed on. It could be in the form:
###### db.table_name
###### table_name

#### Method [string]:
This represents the request method whose variables should be used to construct the SQL query [GET, POST etc].

#### Exclusions [array]:
This is an array and an optional positional argument containing the request variables to be omitted when constructing the SQL query.

##### Example
Let's take an instance where you have a form thus which is to make a POST (post) Request to the back-end and you want to construct an insert statement using the request variables:
```xml
<form action="./settings/" method="POST">
	<input type="text" name="username"/>
	<input type="text" name="password"/>
	<input type="hidden" name="submit_form"/>
</form>
```
You may want to omit the submit_form variable when making your query, so it can be written thus:
```php
include("Tool-booth/lib.php");
$toolbooth = new ToolBooth();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["submit_form"])) {
		//Here is the function
		$db = mysqli_connect($host, $user, $password, $database);
		$sql_query = $toolbooth->insertSQL($db, $table, "POST", ["submit_form"]);
	}
}
```
The above returns a properly sanitized SQL query capable of preventing SQL injection and Script injection and stores it in the $sql_query variable, which can be used to execute a query.
```sql
INSERT INTO table_name (username, password) VALUES ('username_value', 'password_value')
```
And finally, you could choose to:
```php
$db->query($sql_query);
//OR
mysqli_query($db, $sql_query);
```
Notice that the submit_form isn't included in the SQL query.


### updateSQL()
This method accepts five positional arguments and is used to construct SQL UPDATE statements using request variables (GET, POST, PUT or any other), which are listed below:

#### Database Connection [string]:
This is a connection object (variable) and the first argument, which is a successfully established connection to a MySQL database such as:
```php
$db = mysqli_connect($host, $user, $password, $database);
```
#### Table [string]:
This is the third argument and the specific table in which the query is to be executed on. It could be in the form:
###### db.table_name
###### table_name

#### Method [string]:
This represents the request method whose variables should be used to construct the SQL query [GET, POST etc].

#### Exclusions [array]:
This is an array and an optional positional argument containing the request variables to be omitted when constructing the SQL query.

#### Clause [string]:
This is used to provide the query clauses/conditions such as:
```sql
WHERE x = 1
```
**OR**
```sql
ORDER BY id DESC
```

##### Example
Let's take an instance where you have a form thus which is to make a POST (post) Request to the back-end and you want to construct an update statement using the request variables:
```xml
<form action="./settings/" method="POST">
	<input type="text" name="username"/>
	<input type="text" name="password"/>
	<input type="hidden" name="submit_form"/>
</form>
```
You may want to omit the submit_form variable when making your query, so it can be written thus:
```php
include("Tool-booth/lib.php");
$toolbooth = new ToolBooth();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["submit_form"])) {
		//Here is the function
		$db = mysqli_connect($host, $user, $password, $database);
		$sql_query = $toolbooth->updateSQL($db, $table, "POST", ["submit_form"], "WHERE id >= 10");
	}
}
```
The above returns a properly sanitized SQL query capable of preventing SQL injection and Script injection and stores it in the $sql_query variable, which can be used to execute a query.
```sql
UPDATE table_name SET username = 'username_value', password = 'password_value' WHERE id >= 10
```
And finally, you could choose to:
```php
$db->query($sql_query);
//OR
mysqli_query($db, $sql_query);
```
Notice also that the submit_form isn't included in the SQL query.


### validator()
This method validates request variables in a key-value-pair format, where the key is the variable name and the value is the type (email or phone for now) to be validated.

#### Values [array]:
This is a key-value pair array representing the values and their type (format) in a key-value-pair array such as:
```php
$values = ["phone_number"=>"phone", "email_address", "email"];
```
Once provided, the script confirms/validates if they are actually a good match for the provided value.

#### Method [string]:
This represents the request method whose variables should be used to construct the SQL query [GET, POST etc].

#### Exclusions [array]:
This is an array and an optional positional argument containing the request variables to be omitted when constructing the SQL query.

#### Empty [integer]:
This gives either 0 or 1. At default, it is 0. It determines if the algorithm should also check/validate for null (empty) values. If 0, it ignores empty values, else, if 1, it flags any empty variable with an error.

##### Example
Let's take an instance where you have a form thus which is to make a POST (post) Request to the back-end and you want to validate the email and phone:
```xml
<form action="./settings/" method="POST">
	<input type="text" name="email"/>
	<input type="text" name="phone"/>
	<input type="hidden" name="submit_form"/>
</form>
```
You may want to omit the submit_form variable when making your query, so it can be written thus:
```php
include("Tool-booth/lib.php");
$toolbooth = new ToolBooth();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST["submit_form"])) {
		//Here is the function
		$validate = $toolbooth->validator(["email"=>"email", "phone"=>"phone"], "POST", ["submit_form"], 0);
	}
}
```
The above returns an empty JSON-encoded array if there is no error, else it returns an array containing the defaulted parameters. You may want to do this after the above method:
```php
$result = json_decode($validate, 1);
if (count($result) == 0) {
	//No errors. Do nothing or what you wish
}
else {
	print_r($result);
	/*There is an error. A typical error would be in the form:
	Array[0=>"email", 1=>"phone", 2=>"empty"];
	If the email and phone do not match and there is an empty value in any of them
	*/
}
```

### sanitizeVar()
This method sanitizes request variables to prevent harmful input and ensure clean inputs.
The following are positional arguments which it takes:

#### Database Connection [string]:
This is a connection object (variable) and the first argument, which is a successfully established connection to a MySQL database such as:
```php
$db = mysqli_connect($host, $user, $password, $database);
```
#### Value [string]:
This takes a value as a string to be sanitized.

##### Example
Let's take an instance where you have a variable you want to sanitize like:
```php
$variable = "Hello world";
```
The simple way to sanitize this variable is by using:
```php
include("Tool-booth/lib.php");
$toolbooth = new ToolBooth();
$result = $toolbooth->sanitize($variable);
```
The above result is a sanitized and clean value that can be used for SQL queries.


## Issues
You can submit an issue to me or contact me via +2348117093601 or my email, elzucky@gmail.com. I am open to criticism as well. Feel free to make pull requests.
