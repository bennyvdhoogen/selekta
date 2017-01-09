<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class FileWriter
{

  public $todoJSON = '';
  public $todoArray = [];

  public $items = [];

  public $logFilePath = '';
  public $logParams = [
    "filename" => 'fileName',
    "rel_path" => 'relPath',
    "flat" => false
  ];

  public $destination = '';

  public function __construct($todoJSON, $destination){
      $this->destination = $destination;
      $this->logFilePath = $this->destination .'todo.log';
      $this->todoJSON = $todoJSON;
      $this->todoArray = json_decode($this->todoJSON , true);
      $this->items = $this->initCreateTodo($this->todoArray);
  }

  public function writeToLog($file){
    $dest = $file[$this->logParams['rel_path']];
    if($this->logParams['flat']){
      $dest = $file[$this->logParams['filename']];
    }

    file_put_contents($this->logFilePath, $file['srcPath'].PHP_EOL , FILE_APPEND | LOCK_EX);
    file_put_contents($this->logFilePath, $this->destination  . $dest.PHP_EOL, FILE_APPEND | LOCK_EX);
    file_put_contents($this->logFilePath, '----'.PHP_EOL, FILE_APPEND | LOCK_EX);
  }

  public function initCreateTodo($array){
    file_put_contents($this->logFilePath, '');
    return $this->writeTodoItems($array);
  }

  public function writeTodoItems($array){
    $items = $this->items;

    foreach($array as $k1 => $v1){
      if(!isset($v1['srcPath'])){
        $this->writeTodoItems($v1);
      }
      else{
        $items[] = $v1;
        $this->writeToLog($v1);
      }
      // append filename to list
    }

    return $items;
  }

  public function writeFilesToDestination(){
    // first get file Length
    $file = new SplFileObject($this->logFilePath);

    while (!$file->eof()) {
        $file->fgets();
        $end = $file->ftell();
    }

    $file = new SplFileObject($this->logFilePath);

    // start the file-reading loop
    while (!$file->eof()) {
        $a = $file->fgets();
        $b = $file->fgets();
        $last = $file->fgets();

        if($last == '----' || $last == '---- ' ) { continue; }
        if(file_exists(rtrim($b))) { continue; }

        // check destination dir if it doesn't exist
        if (!is_dir(dirname($b))) {
          mkdir(dirname($b), 0777, true);
        }

        copy(rtrim($a), rtrim($b));
        echo ("Progress : " . round($file->ftell() / $end, 4) * 100 . '%');
        // unset $file handler & exit loop
        // since this is a verrrryyy looong while loop, only run it once and rely on the call being repeated
        $file = null;
        exit();
    }
  }

}
?>