<?php

?>


<!DOCTYPE html>
<html lang="en">
<head>



<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">


<!-- bootstrap css -->
<link rel="stylesheet" href="https://bootswatch.com/4/cyborg/bootstrap.min.css">

<!-- highlight.js -->
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.1/highlight.min.js"></script>

<!-- Atom One Dark theme for highlight.js -->
<link rel="stylesheet" href="//highlightjs.org/static/demo/styles/atom-one-dark.css">


<title>Object Oriented PHP Tests</title>



</head>

<body>



<script>hljs.initHighlightingOnLoad();</script>

<div class="px-1 px-sm-3 px-md-5 my-5">

<div class="card">
	<div class="card-header">
		<h1 class="mb-0">Object Oriented PHP Tests</h1>
	</div>
</div>



<!-- START BLOCK -->
<div class="card mt-5">

	<div class="card-header">
		<h3 class="card-title mb-0">Tests</h3>

	</div>

<pre class="m-0"><code class="php tomorrow"><?php
echo htmlspecialchars(<<<'END'
// Tests


END);

?></code></pre>

<pre class="plaintext m-0"><code>
// Output

<?php
// Tests

try {

// Object-relational mapping (ORM)

	class PrimaryKey
	{
		private $key;
		private $value;
		private $schema;

		public function __construct($schema, $value = null)
		{
			$this->schema = $schema;
			$this->value = $value;

			$this->key = $schema['COLUMN_NAME'];
		}

		public function __get($key)
		{
			if (property_exists($this, $key)) {
				return $this->$key;
			} else {
				throw new Exception("Invalid property name for object of class PrimaryKey: $key.");
			}
		}

		public function set($val)
		{
			$this->value = $val;
		}

		public function __toString()
		{
			return $this->value;
		}

	}

	class Model
	{
		private $_pdo; // database connection object
		private $_table; // name of the table that this model represents
		private $_pk; // the table's primary key
		private $_keys; // the names of the table's columns (not including the primary key's column's name)
		private $_schema; // metadata for the table's columns
		private $_pristine; // should mirror the values for this record that are currently stored in the database

		const INT_DATATYPES = array('int', 'tinyint', 'smallint', 'bigint');

		function __construct($table, $pk = false)
		{
			// initialize values
			$this->_table = $table;
			$this->_pk = null;
			$this->_keys = array();
			$this->_schema = array();
			$this->_pristine = array();

			try {
				// establish database connection
				$this->_pdo = new PDO('mysql:host=localhost;dbname=stariumxcv_dev', 'root', '');

				// get metadata for the table's columns
				$query = $this->_pdo->prepare('SELECT * FROM information_schema.columns WHERE table_name = :table');
				$query->execute([':table' => $table]);
				$schema = $query->fetchAll(PDO::FETCH_ASSOC);

				// set the model's properties based on the schema
				foreach ($schema as $field) {
					if ($field['COLUMN_KEY'] == 'PRI') {
						$this->_pk = new PrimaryKey($field);
					} else {
						$key = $field['COLUMN_NAME'];

						$this->$key = null;
						$this->_schema[$key] = $field;
					}
				}

				$this->_keys = array_keys($this->_schema);

				$stmt = null;

				if ($pk) {
					// fetch the existing record if a primary key was provided and
					// update the model's properties to refelect the new data
					$fields = $this->get($pk);

					foreach ($fields as $key => $val) {
						$this->$key = $val;
					}
				}

				// store the inital (unchanged) values for this record
				foreach ($this->_keys as $key) {
					$this->_pristine[$key] = $this->$key;
				}

			} catch (Exception $e) {
				echo 'MODEL ERROR ' . $e->getCode() . ': ' . $e->getMessage() . '<br />';
			}
		}

		function __destruct()
		{
			$this->_pdo = null;
		}

		// get a record
		function get($pk)
		{
			$_pk = $this->_pk->key;

			$query = $this->_pdo->prepare("SELECT * FROM $this->_table WHERE $_pk = :pk LIMIT 1");
			$query->execute([':pk' => $pk]);

			$results = $query->fetch(PDO::FETCH_ASSOC);

			foreach ($results as $key => $val) {
				if ($key == $_pk) {
					$this->_pk->set($val);
				} else {
					$datatype = $this->_schema[$key]['DATA_TYPE'];

					if (in_array($datatype, Model::INT_DATATYPES)) {
						$results[$key] = (int) $val;
					} else {
						$results[$key] = $val;
					}
				}
			}

			return $results;
		}

		function save()
		{
			$vals = array();

			foreach ($this->_keys as $key) {
				if ($this->$key == null && $this->$key != 0 && $this->_schema[$key]['IS_NULLABLE'] == 'NO') {
					echo $this->_schema[$key]['IS_NULLABLE'];

					throw new Exception("Missing required field: " . $key);
				}

				array_push($vals, $this->$key);
			}

			if ($this->_pk->value == null) {
				// primary key is null, so create a new record
				$stmt = "INSERT INTO $this->_table (" . join(', ', $this->_keys) . ") VALUES (" . join(', ', array_fill(0, sizeof($vals), '?')) . ")";

				$query = $this->_pdo->prepare($stmt);
				$query->execute($vals);
			} else {
				// primary key is not null, so update an existing record
				$_pk = $this->_pk->key;

				// determine which fields changed from their original values and only update those
				$modified_keys_formatted = array();
				$modified_values_formatted = array();

				foreach ($this->_keys as $key) {
					if ($this->_pristine[$key] != $this->$key) {
						array_push($modified_keys_formatted, "$key = :$key");
						$modified_values_formatted[":$key"] = $this->$key;
					}
				}

				$stmt = "UPDATE $this->_table SET " . join(', ', $modified_keys_formatted) . " WHERE $_pk = :pk LIMIT 1";
				$bind_params = array_merge($modified_values_formatted, [':pk' => $this->_pk->value]);

				$query = $this->_pdo->prepare($stmt);
				$query->execute($bind_params);

				$result = $query->rowCount();

				if ($result == 1) {
					return true;
				} else {
					return false;
				}
			}

		}

		function __get($key)
		{
			if (property_exists($this, $key)) {
				return $this->$key;
			} else {
				if ($key == $this->_pk->key) {
					return $this->_pk->value;
				} else {
					return null;
				}
			}
		}

		function __set($key, $val)
		{
			if ($key[0] != '_') {
				$this->$key = $val;
			} else {
				throw new Exception("Cannot set protected property of Model: $key");
			}
		}

		function dump()
		{
			return "pk: $this->_pk<br />" . json_encode(get_object_vars($this), JSON_PRETTY_PRINT);
		}

		function __toString()
		{
			return json_encode($this, JSON_PRETTY_PRINT);
		}
	}

// class Federation extends Model
	// {
	// 	public function __construct($pk = false)
	// 	{
	// 		parent::__construct('federation');
	// 	}
	// }

	$federation1 = new Model('federation', 1);

	echo $federation1->fed_name;
	echo '<br />';
	$federation1->fed_name = "Test Fed 26";
	echo $federation1->save() ? 'true' : 'false';

	echo '<br />';

	$federation23 = new Model('federation', 1);
	echo $federation23->fed_name;

	echo '<br /><br />"$federation1->dump()" output:<br />';
	echo $federation1->dump();

} catch (Exception $e) {
	echo 'ERROR ' . $e->getCode() . ': ' . $e->getMessage() . '<br />';
	echo '<br /><br />"$federation1->dump()" output:<br />';
	echo $federation1->dump();

	throw $e;
}

?>

</code></pre>
</div>


</div>

<div class="px-1 px-sm-3 px-md-5 mb-5">
<div class="card">
<div class="card-header">
	<h3 class="card-title">Final Output</h3>
</div>
<pre class="plaintext m-0"><code>// Output
</body>
</html>