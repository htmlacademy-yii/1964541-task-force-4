<?php

namespace TaskForce;

class DataImporter
{
    private string $filename;
    private object $fileObject;
    private array $data;
    private string $columns;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    public function import(): void
    {
        $this->fileObject = new \SplFileObject($this->filename);

        foreach ($this->getNextLine() as $line) {
            $this->data[] = $line;
        }

        for ($i = 0; $i < count($this->data); $i++) {

        }
    }

    public function getValues(): array
    {
        unset($this->data[0]);
        return $this->data;
    }

    private function getTableTitles(): string
    {
        $this->fileObject->rewind();
        return implode(',', $this->fileObject->fgetcsv());
    }

    private function getNextLine(): ?iterable
    {
        $result = null;

        while (!$this->fileObject->eof()) {
            yield $result = $this->fileObject->fgetcsv();
        }

        return $result;
    }
}
