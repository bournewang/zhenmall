<?php
namespace App\Imports;

trait LogTrait
{
    private $log;
    
    public function initLog($file)
    {
        $dir = pathinfo($file, PATHINFO_DIRNAME);
        if (!file_exists($dir)) {
	echo "mkdir $dir\n";
            mkdir($dir, 0755);
        }
        $this->log = fopen($file, 'w');
    }
    
    public function logData($str)
    {
        fputs($this->log, "<tr><td>".$str."</td>");
    }
    
    public function logSuccess($str)
    {
        return $this->logResult($str, 'bg-success text-white');
    }
    
    public function logWarning($str)
    {
        return $this->logResult($str, 'bg-warning');
    }
    
    public function logDanger($str)
    {
        return $this->logResult($str, 'bg-danger text-white');
    }
    
    public function logResult($str, $type = null)
    {
        return fputs($this->log, "<td>".$this->label($str, $type)."</td></tr>\n");
    }

    public function label($label, $class='text-default')
    {
        return "<span class='text $class'>$label</span>";
    }
    
    public function label_success($label) 
    {
        return $this->label($label, 'bg-success text-white');
    }
    public function label_warning($label) 
    {
        return $this->label($label, 'bg-warning text-white');
    }
    public function label_danger($label) 
    {
        return $this->label($label, 'bg-danger text-white');
    }
    
}
