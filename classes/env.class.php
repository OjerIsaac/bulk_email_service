<?php 
 
class DotEnvironment {
    private $path;
    private $tmp_env;
    function __construct($env_path = ""){
        // Check if .env file path has provided
        if(empty($env_path)){
            throw new ErrorException(".env file path is missing");
        }
        $this->path = $env_path;
 
        //Check .envenvironment file exists
        if(!is_file(realpath($this->path))){
            throw new ErrorException("Environment File is Missing.");
        }
        //Check .envenvironment file is readable
        if(!is_readable(realpath($this->path))){
            throw new ErrorException("Permission Denied for reading the ".(realpath($this->path)).".");
        }
        $this->tmp_env = [];
        $fopen = fopen(realpath($this->path), 'r');
        if($fopen){
            while (($line = fgets($fopen)) !== false){
                // Check if line is a comment
                $line_is_comment = (substr(trim($line),0 , 1) == '#') ? true: false;
                if($line_is_comment || empty(trim($line)))
                    continue;
 
                $line_no_comment = explode("#", $line, 2)[0];
                $env_ex = preg_split('/(\s?)\=(\s?)/', $line_no_comment);
                $env_name = trim($env_ex[0]);
                $env_value = isset($env_ex[1]) ? trim($env_ex[1]) : "";
                $this->tmp_env[$env_name] = $env_value;
            }
            fclose($fopen);
        }
        $this->load();
    }

    function load(){
        // Save .env data to Environment Variables
        foreach($this->tmp_env as $name=>$value){
            putenv("{$name}=$value");
            if(is_numeric($value))
            $value = floatval($value);
            if(in_array(strtolower($value),["true","false"]))
            $value = (strtolower($value) == "true") ? true : false;
            $_ENV[$name] = $value;
        }
        // print_r(realpath($this->path));
    }
}