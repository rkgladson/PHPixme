# PHPixme
PHPixme is a library to mirror aspects of librarys like Ramdajs and a handfull Scala data types for use in PHP. It makes heavy use of closures, and is not optimised for preformance, but correctness.
## A few features
* A data last philosophy for all it's own function.
* Full currying of user functions.
* Placeholders in currying functions.
* Full **SKI** calculus functions.
* **Seq**, a object containner for PHP arrays, and are able use them in a object oriented manner with high order functions capable of transformations.
* **Maybe** ( in Scala Option ) to handle null paths with high order functions via Some and None subclasses capable of transformations, and is a PHP Transversable
* **Attempt** (in Scala Try ) to handle thrown errors between **Success** and **Failure** subclasses using callbacks and mapping.
## Note:
PHPixme is strictly experimental even though code coverage is good. It is not meant for use in production code. APIs at this point are highly subject to change. If you wish to see how to use the code, check out the demo, or read the unit tests
