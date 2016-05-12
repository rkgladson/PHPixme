<?php
/**
 * Make up for the things that PSR-4 Cannot do. Thanks PHP! /s
 */
namespace PHPixme;
$fileR = [
  '/function/*.php'
  , '/operator/*.php'
  , '/companions*.php'
];
foreach ($fileR as $match) {
  foreach (glob (__DIR__.$match) as $file) {
    require $file;
  }
}
unset ($fileR, $match, $file);