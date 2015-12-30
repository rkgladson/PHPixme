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
$quaternary = P\nAry(4);
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
    <h2>Testing Quaternary!</h2>
    <p>
        <?php
        $output = call_user_func_array($quaternary($testFn), testData)
        ?>
        Quaternary returned: <?= $output ?>
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
        <?= json_encode(testData) ?> &mdash;$map2x&rightarrow; <?= json_encode($mapX2(testData)) ?>
    </p>
</section>
<section>
    <h2>imploding with a pre-formatted join!</h2>
    <p>
        <?= json_encode(testData) ?> &mdash;$joinComma&rightarrow; <?= $joinComma(testData) ?>
    </p>
</section>
<section>
    <h2>Combine $timesTwo with $stringify, then map it and join it through the pre-formated join!</h2>
    <p>
        <?= json_encode(testData) ?> &mdash;map($makeView)&rightarrow;
        <?= $joinComma(array_map($makeView, testData)) ?>
    </p>
</section>
<section>
    <h2>Again, but now using fold to produce the join!</h2>
    <p>
        <?= json_encode(testData) ?> &mdash;map($makeView)&mdash;fold&rightarrow;
        <?= P\fold(function ($output, $value) {
            return $output ? "$output, $value" : $value;
        }, '', array_map($makeView, testData)) ?>
    </p>
</section>
<section>
    <h2>Again, but now using reduce to produce the join!</h2>
    <p>
        <?= json_encode(testData) ?> &mdash;map($makeView)&mdash;reduce&rightarrow;
        <?= P\reduce(function ($output, $value) {
            return "$output, $value";
        }, array_map($makeView, testData)) ?>
    </p>
</section>
<section>
    <h2>Watch in <i>horror</i> as I flip a native function! array_filter</h2>
    <p>
        <?= json_encode(testData) ?> &mdash;map2x&mdash;filterWith(_ mod 8 = 0)&mdash;values&rightarrow;
        <?= json_encode(array_values(
            P\flip('array_filter')
                ->__invoke(function ($value) {
                    return $value % 8 === 0;
                })
                ->__invoke($mapX2(testData))
        )) ?>
    </p>
</section>
<section>
    <h2>For my next trick, I will flip array_walk! (With a little help from binary)</h2>
    <p>
        The key-value pairs of <?= json_encode(testData) ?>:
    </p>
    <ul>
        <?php
        $keyPairs = P\flip(
            P\binary('array_walk')
        )->__invoke(
            function ($value, $key) {
                echo "<li>$key &DoubleRightArrow; $value</li>" . PHP_EOL;
            }
        );
        $keyPairs(testData);
        ?>
    </ul>
</section>
</body>
</html>
