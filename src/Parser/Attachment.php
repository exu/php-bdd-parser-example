<?php
namespace Parser;

class Attachment
{
    protected $fileName;
    protected $content;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    public function getId($line)
    {
        $header = 'Attachment id: ';
        if (strpos($line, $header) === 0) {
            $start = strlen($header);
            return trim(substr($line, $start));
        }

        return false;
    }

    public function parseLine($line)
    {
        if (!isset($this->id)) {
            $this->id = $this->getId($line);
            return false;
        } elseif ($this->id) {
            $this->previousId = $this->id;
        }

        $this->id = $this->getId($line);

        if (!$this->id) {
            $this->content .= $line;
        } else {
            if ($this->content && !isset($this->result[md5($this->content)])) {
                $this->result[md5($this->content)] = array();
            }
            array_push($this->result[md5($this->content)], $this->previousId);
            $this->content = '';
        }

        return true;
    }


    public function parse()
    {
        $result = [];
        $content = '';
        $handle = fopen($this->fileName, 'r');

        while (!feof($handle)) {
            $line = fgets($handle);
            $this->parseLine($line);
        }

        $this->reattachRest();
    }

    public function reattachRest()
    {
        if (!isset($this->result[md5($this->content)])) {
            $this->result[md5($this->content)] = [];
        }

        array_push($this->result[md5($this->content)], $this->previousId);
    }

    public function get()
    {
        return $this->result;
    }
}
