<?php
namespace PHPixme;

const Either = __NAMESPACE__ . '\Either';
const Left = __NAMESPACE__ . '\Left';
function Left($value)
{
  return new Left($value);
}
const Right = __NAMESPACE__ . '\Right';
function Right($value)
{
  return new Right($value);
}