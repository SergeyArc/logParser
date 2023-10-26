<?php

class FileParser
{

    public function __construct(private string $filename) {}

    public function parseFile(): Generator
    {
        $pattern = '/\{[^{}]*\}/';

        $file = fopen($this->filename, 'r');

        if (!$file) {
            throw new Exception("Не удается открыть файл");
        }

        $line = "";
        while (!feof($file)) {
            $line .= fgets($file);
            preg_match_all($pattern, $line, $matches);

            if (empty($matches[0])) {
                continue;
            }

            foreach ($matches[0] as $match) {
                $data = json_decode($match, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    if (isset($data["InvId"], $data["OutSum"],$data["SignatureValue"])) {
                        yield $data;
                    }
                }
            }

            $line = "";
        }

        fclose($file);
    }
}
