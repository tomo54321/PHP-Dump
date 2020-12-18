<?php
/**
 * @package Dump PHP
 * @author Tom
 * @version 1.0
 * A nice way of doing var_dump inspired by the Symfony VarDumper package but without the classes
 * this is a simple 1 file import into any project.
 */

namespace Tomo {
    class DebugDump{

        /**
         * All the objects to render
         * 
         * @var array
         */
        private $args = [];

        /**
         * New dump instance
         * 
         * @return \Tomo\DebugDump
         */
        public function __construct($args)
        {
            $this->args = $args;
        }

        /**
         * Dump the specific row
         * 
         * @param mixed $arg
         * @param boolean $inline
         * @param boolean $showArgType
         */
        private function row($arg, $inline = false, $showArgType = true){
            $type = gettype($arg);
            $no_show = ["NULL", "boolean", "integer", "double", "float"];
        
            echo "<div class='row' style='" . ($inline ? "display:inline;" : "") . "'>";
        
                // Should show what the argument type actually is?
                if($showArgType || ($type == "array" || $type == "object")){
                    // The type is a type we do show or inline is false (inline will always be false if it's the first item / only item)
                    if(!in_array($type, $no_show) || $inline == false){
                        // If it's an object we'll change it to be the class name of the object.
                        if(is_object($arg)){
                            $type = get_class($arg);
                        }
                        // Display
                        echo $type . ":";
                    }
                }
        
                // Open brackets
                switch(gettype($arg)){
                    case "string":
                        echo ($showArgType ? strlen($arg) : ""). " ";
                        break;
                    case "array":
                        echo count($arg) . " <span class='op'>[</span> ";
                        break;
                    case "object":
                        echo "<span class='oh'>#" . spl_object_id($arg) . "</span> <span class='op'>{</span> ";
                        break;
                    default:
                        break;
                }
                
                if(is_array($arg)){
                    foreach($arg as $key => $value){
                        echo "<span class='row indent'>"; 
                            if(is_string($key)){  echo "<span class='op'>\"</span><span class='string'>" . $key . "</span><span class='op'>\"</span>"; }else{ echo $key; }
                            echo " <span class='op'>=></span> ";
                            $this->row($value, true, false);
                        echo"</span>";
                    }
                }else if(is_string($arg)){
                    echo "<span class='op'>\"</span><span class='string'>" . htmlentities($arg) . "</span><span class='op'>\"</span>";
                }else if(is_object($arg)){
                    $props = get_object_vars($arg);
                    foreach($props as $prop_name => $prop){
                        echo "<span class='row indent'>"; 
                            echo "<span class='op'>\"</span><span class='string'>" . $prop_name . "</span><span class='op'>\"</span>";
                            echo " <span class='op'>:</span> ";
                            $this->row($prop, true);
                        echo"</span>";
                    }
                }else{
                    echo " <span class='string'>" . ($arg ? $arg : "null") . "</string>";
                }
        
                // Closing bracket
                switch(gettype($arg)){
                    case "array":
                        echo "<span class='op'>]</span>";
                        break;
                    case "object":
                        echo "<span class='op'>}</span>";
                        break;
                }
        
            echo "</div>";
        }

        /**
         * Render the dump
         * 
         * @return void
         */
        public function render(){
            echo "<style>body{font-family:monospace;white-space:nowrap;}.arg{overflow-x:auto;background:#000;color:#fff;padding:5px;margin-bottom:20px;}.oh{color:#afafaf;}.string{color:#27d500;font-weight:bold;}.row{display:block;color:#3496dd;}.op{color:#d61016;}.indent{margin-left:20px;}</style>";
            foreach($this->args as $arg){
                echo "<div class='arg'>";
                    $this->row($arg);
                echo "</div>";
            }
            echo "</body></html>";
        }

    }
}
namespace {
    /**
     * Setup the global functions
     */
    if(!function_exists("dump")){
        /**
         * Dump the data onto the page (is displayed wherever called)
         * @param mixed [...$vars]
         * @return void
         */
        function dump(){
            (new \Tomo\DebugDump(\func_get_args()))->render();
        }
    }
    if(!function_exists("dd")){
        ob_start(); // Begin output buffering incase of a dd call
        /**
         * Dump and die function whipes the current output and replaces it with the dump
         * @param mixed [...$vars]
         * @return void
         */
        function dd(){
            // Remove existing output
            ob_clean();
            ob_end_flush();
            // Start a new output buffer
            ob_start();
            // Render the output
            (new \Tomo\DebugDump(\func_get_args()))->render();
            // Write the output
            file_put_contents("php://output", ob_get_clean());
            // Exit all other processes
            exit;
        }
    }
}
