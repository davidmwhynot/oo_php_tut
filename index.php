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


<title>Object Oriented PHP Tutorial</title>



</head>

<body>



<script>hljs.initHighlightingOnLoad();</script>

<div class="px-1 px-sm-3 px-md-5 my-5">

<div class="card">
	<div class="card-header">
		<h1 class="mb-0">Object Oriented PHP Tutorial</h1>
	</div>
</div>



<!-- START BLOCK -->
<div class="card mt-5">

	<div class="card-header">
		<h3 class="card-title mb-0">Classes, Attributes, and Methods</h3>
	</div>

<pre class="m-0"><code class="php tomorrow"><?php
echo htmlspecialchars(<<<'END'
// Classes, Attributes, and Methods

class Animal
{

	// protected // only subclasses can access
	// public // any code can access
	// private // only this class can access

	protected $name;
	protected $favorite_food;
	protected $sound;
	protected $id;

	public static $number_of_animals = 0; // shared amongst all instances of "Animal"
	// To access static attributes:
	// Animal::$number_of_animals;

	const PI = 3.14159;
	// Animal::PI

	// constructors
	public function __construct()
	{
		$this->id = rand(100, 1000000);

		echo $this->id . " has been assigned<br />";

		Animal::$number_of_animals++;
	}

	// encapsulation
	public function getName()
	{
		return $this->name;
	}

	// destructor
	public function __destruct()
	{
		echo $this->id . " is being destroyed :(<br />";
	}

	// shortcut for creating getters/setters ("magic")
	public function __get($key)
	{
		echo "Asked for " . $key . "<br />";

		return $this->$key;
	}

	public function __set($key, $val)
	{

		switch ($key) {
			case "name":
				$this->name = $val;
				break;

			case "favorite_food":
				$this->favorite_food = $val;
				break;

			case "sound":
				$this->sound = $val;
				break;

			default:
				echo $key . " Not Found<br />";

				throw new Exception("Attribute Not Found", $key);
		}

		echo "Set " . $key . " to " . $val . "<br />";

	}

	public function run()
	{
		echo $this->name . " runs.<br />";
	}

	final public function what_is_good()
	{
		echo "Running is good.<br />";
	}

	public function __toString()
	{
		return "<br />" . $this->name . " says \"" . $this->sound . "! Give me some " . $this->favorite_food . ".\"<br />" . "My id is " . $this->id . ".<br />" . "Favorite Number: " . Animal::PI . "<br />" . "Total Animals: " . Animal::$number_of_animals . "<br /><br />";

	}

}

END);

?></code></pre>

<pre class="plaintext m-0"><code>
// Output

<?php
// Classes, Attributes, and Methods

class Animal
{

	// protected // only subclasses can access
	// public // any code can access
	// private // only this class can access

	protected $name;
	protected $favorite_food;
	protected $sound;
	protected $id;

	public static $number_of_animals = 0; // shared amongst all instances of "Animal"
	// To access static attributes:
	// Animal::$number_of_animals;

	const PI = 3.14159;
	// Animal::PI

	// constructors
	public function __construct()
	{
		$this->id = rand(100, 1000000);

		echo $this->id . " has been assigned<br />";

		Animal::$number_of_animals++;
	}

	// encapsulation
	public function getName()
	{
		return $this->name;
	}

	// destructor
	public function __destruct()
	{
		echo $this->id . " is being destroyed :(<br />";
	}

	// shortcut for creating getters/setters ("magic")
	public function __get($key)
	{
		echo "Asked for " . $key . "<br />";

		return $this->$key;
	}

	public function __set($key, $val)
	{

		switch ($key) {
			case "name":
				$this->name = $val;
				break;

			case "favorite_food":
				$this->favorite_food = $val;
				break;

			case "sound":
				$this->sound = $val;
				break;

			default:
				echo $key . " Not Found<br />";

				throw new Exception("Attribute Not Found", $key);
		}

		echo "Set " . $key . " to " . $val . "<br />";

	}

	public function run()
	{
		echo $this->name . " runs.<br />";
	}

	final public function what_is_good()
	{
		echo "Running is good.<br />";
	}

	public function __toString()
	{
		return "<br />" . $this->name . " says \"" . $this->sound . "! Give me some " . $this->favorite_food . ".\"<br />" . "My id is " . $this->id . ".<br />" . "Favorite Number: " . Animal::PI . "<br />" . "Total Animals: " . Animal::$number_of_animals . "<br /><br />";

	}

}

?>

</code></pre>
</div>
<!-- END BLOCK -->



<!-- START BLOCK -->
<div class="card mt-5">

	<div class="card-header">
		<h3 class="card-title mb-0">Inheritance, Subclasses, Creating Objects, and Manipulating Object Attributes</h3>
	</div>

<pre class="m-0"><code class="php tomorrow"><?php
echo htmlspecialchars(<<<'END'
// Inheritance, Subclasses, Creating Objects, and Manipulating Object Attributes

class Dog extends Animal
{
	public function run()
	{
		echo $this->name . " runs like crazy<br />";
	}
}

$animal_one = new Animal();

$animal_one->name = "Spot";
$animal_one->favorite_food = "Meat";
$animal_one->sound = "Ruff";

echo $animal_one;

$animal_two = new Dog();

$animal_two->name = "Grover";
$animal_two->favorite_food = "Mushrooms";
$animal_two->sound = "Grrrr";

echo $animal_two;

$animal_one->run();
$animal_two->run();
$animal_two->what_is_good();

END);

?></code></pre>

<pre class="plaintext m-0"><code>
// Output

<?php
// Inheritance, Subclasses, Creating Objects, and Manipulating Object Attributes

class Dog extends Animal
{
	public function run()
	{
		echo $this->name . " runs like crazy<br />";
	}
}

$animal_one = new Animal();

$animal_one->name = "Spot";
$animal_one->favorite_food = "Meat";
$animal_one->sound = "Ruff";

echo $animal_one;

$animal_two = new Dog();

$animal_two->name = "Grover";
$animal_two->favorite_food = "Mushrooms";
$animal_two->sound = "Grrrr";

echo $animal_two;

$animal_one->run();
$animal_two->run();
$animal_two->what_is_good();

?>

</code></pre>
</div>
<!-- END BLOCK -->



<!-- START BLOCK -->
<div class="card mt-5">

	<div class="card-header">
		<h3 class="card-title mb-0">Interfaces and Static Methods</h3>
	</div>

<pre class="m-0"><code class="php tomorrow"><?php
echo htmlspecialchars(<<<'END'
// Interfaces and Static Methods
interface Singable
{
	public function sing();
}

class Cat extends Animal implements Singable
{
	public function run()
	{
		echo $this->name . " runs at leisurely pace.<br />";
	}

	public function sing()
	{
		echo $this->name . " sings a tune. La la la.<br />";
	}

	static function add_these($num1, $num2)
	{
		return ($num1 + $num2) . "<br />";
	}
}

$animal_three = new Cat();

$animal_three->name = "Sheldon";
$animal_three->sound = "Meow";
$animal_three->favorite_food = "Fish";

echo $animal_three;

// $animal_three->sing();

function make_them_sing(Singable $singing_animal)
{
	$singing_animal->sing();
}

make_them_sing($animal_three);

function make_them_run(Animal $animal)
{
	$animal->run();
}

make_them_run($animal_three);

Cat::add_these(1, 2);

END);

?></code></pre>

<pre class="plaintext m-0"><code>
// Output

<?php
// Interfaces and Static Methods
interface Singable
{
	public function sing();
}

class Cat extends Animal implements Singable
{
	public function run()
	{
		echo $this->name . " runs at leisurely pace.<br />";
	}

	public function sing()
	{
		echo $this->name . " sings a tune. La la la.<br />";
	}

	static function add_these($num1, $num2)
	{
		return ($num1 + $num2) . "<br />";
	}
}

$animal_three = new Cat();

$animal_three->name = "Sheldon";
$animal_three->sound = "Meow";
$animal_three->favorite_food = "Fish";

echo $animal_three;

// $animal_three->sing();

function make_them_sing(Singable $singing_animal)
{
	$singing_animal->sing();
}

make_them_sing($animal_three);

function make_them_run(Animal $animal)
{
	$animal->run();
}

make_them_run($animal_three);

Cat::add_these(1, 2);

?>

</code></pre>
</div>
<!-- END BLOCK -->



<!-- START BLOCK -->
<div class="card mt-5">

	<div class="card-header">
		<h3 class="card-title mb-0">Utilities and Abstract Classes</h3>
	</div>

<pre class="m-0"><code class="php tomorrow"><?php
echo htmlspecialchars(<<<'END'
// Utilities and Abstract Classes

$is_it_an_animal = ($animal_three instanceof Animal) ? "True" : "False";

echo "It is " . $is_it_an_animal . ' that $animal_three is an Animal<br />';

$animal_clone = clone $animal_one;

echo $animal_clone;

abstract class RandomClass {
	abstract function RandomFunction($attribute);
}

// __call();

END);

?></code></pre>

<pre class="plaintext m-0"><code>
// Output

<?php
// Utilities and Abstract Classes

$is_it_an_animal = ($animal_three instanceof Animal) ? "True" : "False";

echo "It is " . $is_it_an_animal . ' that $animal_three is an Animal<br />';

$animal_clone = clone $animal_one;

echo $animal_clone;

abstract class RandomClass {
	abstract function RandomFunction($attribute);
}

// __call();

?>

</code></pre>
</div>
<!-- END BLOCK -->



<!-- START BLOCK -->
<div class="card mt-5">

	<div class="card-header">
		<h3 class="card-title mb-0">Practical Applications</h3>
	</div>

<pre class="m-0"><code class="php tomorrow"><?php
echo htmlspecialchars(<<<'END'
// Practical Applications
class DavidsSite {
	protected $title = "<title>Welcome to David's Site</title>";
	protected $description = "<meta name=\"description\" content=\"Welcome to David's personal website.\"";
}

// require("siteinfo.inc");

$davidsSite = new DavidsSite();

/*

$davidsSite=>get_start_of_site();

$davidsSite=>get_title();


...


*/

END);

?></code></pre>

<pre class="plaintext m-0"><code>
// Output

<?php
// Practical Applications
class DavidsSite {
	protected $title = "<title>Welcome to David's Site</title>";
	protected $description = "<meta name=\"description\" content=\"Welcome to David's personal website.\"";
}

// require("siteinfo.inc");

$davidsSite = new DavidsSite();

/*

$davidsSite=>get_start_of_site();

$davidsSite=>get_title();


...


*/

?>

</code></pre>
</div>
<!-- END BLOCK -->



<!-- START BLOCK -->
<!--
<div class="card mt-5">

	<div class="card-header">
		<h3 class="card-title mb-0">Template</h3>
	</div>

<pre class="m-0"><code class="php tomorrow"><?php
echo htmlspecialchars(<<<'END'
// Template


END);

?></code></pre>

<pre class="plaintext m-0"><code>
// Output

<?php
// Template

?>

</code></pre>
</div>
<!-- END BLOCK -->



</div>

<div class="px-1 px-sm-3 px-md-5 mb-5">
<div class="card">
<div class="card-header">
	<h3 class="card-title">Final Output</h3>
</div>
<pre class="plaintext m-0"><code>// Output
</body>
</html>