<html>
<head>
    <title>PHPixing php</title>
</head>
<body>
<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 12/30/2015
 * Time: 10:33 AM
 */
require 'PHPixme.php';
use PHPixme as P;
const BR = '<br/>' . PHP_EOL;
const testData = [1, 2, 4, 8, 16];
$testFn = function (...$args) {
    $output = implode(', ', $args);
    echo '$testFn received: ' . $output . BR;
    return $output;
};
$timesTwo = function ($value) {
    return $value * 2;
};
$stringify = function ($value) {
    return "'$value'";
};
$curry2 = P\curry(2);
$binary = P\nAry(2);
$mapX2 = $curry2('array_map')->__invoke($timesTwo);
$joinComma = $curry2('implode')->__invoke(', ');
$makeView = P\combine($stringify, $timesTwo);
?>
<h1>Time to fix PHP!</h1>
<p>
    Behold! There is nothing up my sleeves!<br>
    <?= json_encode(testData) ?>
</p>

<section>
    <h2>Testing Binary</h2>

    <p>
        <?php
        $output = call_user_func_array($binary($testFn), testData)
        ?>
        Binary returned: <?= $output ?>
    </p>
</section>
<section>
    <h2>Testing Unary</h2>
    <p>
        <?php
        $output = call_user_func_array(P\unary($testFn), testData)
        ?>
        Unary returned: <?= $output ?>

    </p>
</section>
<section>
    <h2>Currying native function test. Target: array_map</h2>

    <p>
        <?= implode(', ', testData) ?> -$map2x-> <?= implode(', ', $mapX2(testData)) ?>
    </p>
</section>
<section>
    <h2>imploding with a pre-formatted join!</h2>
    <p>
        <?=json_encode(testData)?> --$joinComma--> <?= $joinComma(testData) ?>
    </p>
</section>
<section>
    <h2>Combine $timesTwo with $stringify, then map it and join it through the pre-formated join!</h2>
    <p>
        <?= json_encode(testData) ?> --map($makeView)-->
        <?= $joinComma(array_map($makeView, testData)) ?>
    </p>
</section>
<section>
    <h2>Again, but now using fold to produce the join!</h2>
    <p>
        <?= json_encode(testData) ?> --map($makeView)--fold-->
        <?= P\fold(function ($output, $value) {
            return $output ? "$output, $value" : $value;
        }, '', array_map($makeView, testData)) ?>
    </p>
</section>
<section>
    <h2>Again, but now using reduce to produce the join!</h2>
    <p>
        <?= json_encode(testData) ?> --map($makeView)--reduce-->
        <?= P\reduce(function ($output, $value) {
            return "$output, $value";
        }, array_map($makeView, testData)) ?>

    </p>
</section>
</body>
</html>
