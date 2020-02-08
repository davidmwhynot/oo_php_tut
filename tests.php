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

// function logarr($arr) {

// }

class Model
{
	private $_pdo;
	private $_schema;
	private $_table;
	private $_pk;

	const INT_DATATYPES = array('int', 'tinyint', 'smallint', 'bigint');

	function __construct($table)
	{
		$this->_table = $table;

		try {
			$this->_pdo = new PDO('mysql:host=localhost;dbname=stariumxcv_dev', 'root', '');

			$query = $this->_pdo->prepare('SELECT * FROM information_schema.columns WHERE table_name = :table');
			$query->execute([':table' => $table]);
			$schema = $query->fetchAll(PDO::FETCH_ASSOC);

			$this->_schema = array();

			foreach ($schema as $field) {
				$key = $field['COLUMN_NAME'];

				$this->$key = null;
				$this->_schema[$key] = $field;

				if ($field['COLUMN_KEY'] == 'PRI') {
					$this->_pk = $key;
				}
			}

			$stmt = null;
		} catch (Exception $e) {
			echo 'MODEL ERROR ' . $e->getCode() . ': ' . $e->getMessage() . '<br />';
		}
	}

	function __destruct()
	{
		$this->_pdo = null;
	}

	function get($pk)
	{
		$query = $this->_pdo->prepare("SELECT * FROM $this->_table WHERE $this->_pk = :pk LIMIT 1");
		$query->execute([':pk' => $pk]);

		$results = $query->fetch(PDO::FETCH_ASSOC);

		foreach ($results as $key => $val) {
			$datatype = $this->_schema[$key]['DATA_TYPE'];

			if (in_array($datatype, Model::INT_DATATYPES)) {
				$results[$key] = (int) $val;
			} else {
				$results[$key] = $val;
			}
		}

		return $results;
	}

	function __get($key)
	{
		if (property_exists($this, $key)) {
			return $this->$key;
		} else {
			return null;
		}
	}

	function __set($key, $val)
	{
		$this->$key = $val;
	}

	function dump()
	{
		var_dump(get_object_vars($this));

		echo "<br />";

		var_dump($this);
	}

	function __toString()
	{
		return json_encode(get_object_vars($this), JSON_PRETTY_PRINT);
		// return json_encode($this, JSON_PRETTY_PRINT);
	}
}

class Federation extends Model
{
	public function __construct($pk)
	{
		parent::__construct('federation');

		$fields = parent::get($pk);

		// echo json_encode($fields, JSON_PRETTY_PRINT);

		foreach ($fields as $key => $val) {
			$this->$key = $val;
		}
	}
}

$federation = new Federation(1);

// $federation->dump();

echo $federation;

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