<?php

interface iModel{
  public function save();
  public function update();
  public function delete($id);
  public function get($id);
  public function getAll();
  public function from($array);

}



?>