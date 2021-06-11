<?php
final class Registry {
 private $data = array();
 public function get($key) {
   return ($this->has($key) ? $this->data[$key] : null);
 }
 public function set($key, $value) {
   $this->data[$key] = $value;
 }
 public function has($key) {
   return isset($this->data[$key]);
 }
}