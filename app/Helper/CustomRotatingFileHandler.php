<?php
/**
 * Created by PhpStorm.
 * User: Memory
 * Date: 10/09/2019
 * Time: 02:13
 */

namespace App\Helper;


use Illuminate\Support\Facades\File;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

/**
 * Class CustomRotatingFileHandler, for Rotating logs when reaching maximum file_size
 * @package App\Helper
 */
class CustomRotatingFileHandler extends RotatingFileHandler
{
    /**
     * maxmum file size in MB
     * @var int
     */

    private $max_file_size = 2;

    public function __construct($filename, $maxFiles = 0, $level = Logger::DEBUG, $bubble = true, $filePermission = null, $useLocking = false,$max_file_size = 2){
        parent::__construct($filename,$maxFiles,$level,$bubble,$filePermission,$useLocking);
    }

    protected function write(array $record):Void
    {

        // on the first record written, if the log is new, we should rotate (once per day)
        if (null === $this->mustRotate) {
            $this->mustRotate = !file_exists($this->url);
        }
        if ($this->nextRotation < $record['datetime']) {
            $this->mustRotate = true;
            $this->close();
        }else if (file_exists($this->url) && filesize($this->url)>$this->max_file_size*1024*1024){
            $this->mustRotate = true;
            $this->close();
            $old_file=$this->getTimedFilename();
            try{
                File::move($old_file,$old_file.'.'.date('His'));
            }catch (\Exception $e){
                error_log($e->getMessage());
            }
        }

        return parent::write($record);
    }

}
