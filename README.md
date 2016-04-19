# PHPixme
PHPixme is an **_experimental_** library to mirror aspects of libraries like Ramdajs and a handful Scala inspired data types for use in PHP. It makes heavy use of closures, and is not optimised for performance, but correctness.
## Goal of PHPixme
The primary goal of the project it to provide a base which pleasantly surprises users only. Implicit conversion more than often when dealing with categories of collections are pleasant surprises. PHP too easily allows for falling into the pit of despair, and PHPixme goal is to allow users to easily fall into success, through preferring immutability, composability, and universality of containers.
## A few features
* A data last philosophy for all it's own function.
* Full currying of user functions.
* Placeholders in currying functions.
* Full **SKI** calculus functions.
* **Seq**, a object container for PHP arrays, and are able use them in a object oriented manner with high order functions capable of transformations.
* **Maybe** ( in Scala **Option** ) to handle null paths with high order functions via Some and None subclasses capable of transformations, and is a PHP Transversable
* **Attempt** (in Scala **Try** ) to handle thrown errors between **Success** and **Failure** subclasses using callbacks and mapping.

## Note:
PHPixme is strictly experimental even though code coverage is good. It is not meant for use in production code. APIs at this point are highly subject to change. If you wish to see how to use the code, check out the demo, or read the unit tests.
