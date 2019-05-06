<?php
declare(strict_types=1);
namespace Chipbug\Tools\Logger;

/**
 * simple recursive spl_autoloader
 */
 class Autoloader
 {
    // modify as needed
	private $classesPath;

     /**
      * recursively scan files and autoload as necessary
      * WARNING: doesn't work with duplicate file names, eg folder1/index.php and folder2/index.php
      *
      * @return void
      */
     public function init()
     {
         $this->classesPath = plugin_dir_path(__DIR__) . 'classes';
         spl_autoload_register(array($this,'recursivelyFindClasses'));
     }
     
     /**
      * finds all classes and checks if this class is listed
      * if so, it requires it.

      * @param [type] $class
      * @return void
      */
     private function recursivelyFindClasses(string $class): void
     {
         try {
             // explode namespace and classname into array
             $class = explode("\\", $class);
             // get last item (ie classname) and convert to lowercase
             $class = strtolower(end($class)) . '.php';
             
             // does the recursion for us. Nice.
             $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->classesPath, \RecursiveDirectoryIterator::SKIP_DOTS));
             
             // iterate through dirs and files
             foreach ($files as $file) {
                 $lowerDash = strtolower(str_replace('-','_', $file->getFilename()));
                 if ($lowerDash  == $class && $file->isReadable()) {
                     // include the class
                     require_once $file->getPathname();
                 }
             }
         } catch (\Throwable $th) {
             // note: using php 7 Throwable
             error_log($th->getFile() . ': line ' . $th->getLine() . ', ' . $th->getMessage());
         }
     }
 }
