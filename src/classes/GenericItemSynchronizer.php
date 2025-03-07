<?php
require 'ItemSynchronizer.php';
class GenericItemSynchronizer implements ItemSynchronizer
{
    private $origin;
    private $target;
    private $metadataConverter;
    //geters and seters for the repository from where the item will be extracted
    public function getOriginRepository()
    {
        return $this->origin;
    }
    public function setOriginRepository($o)
    {
        $this->origin = $o;
    }

    //geters and seters for the repository to where the item will be imported
    public function getTargetRepository()
    {
        return $this->target;
    }
    public function setTargetRepository($t)
    {
        $this->target = $t;
    }

    //geters and seters for the metadata converter
    public function getMetadataConverter()
    {
        return $this->metadataConverter;
    }
    public function setMetadataConverter($m)
    {
        $this->metadataConverter = $m;
    }

    //synchronize repositories of a specific year
    public function syncReposByYear($year)
    {
        $itemList = $this->origin->getItemsByYear($year);
        $this->__syncRepos($itemList);
    }

    //synchronize entire repositories
    public function syncRepos()
    {
        $itemList = $this->origin->getAllItems();
        $this->__syncRepos($itemList);
    }

    //synchronize entire repositories
    private function __syncRepos($itemList)
    {
        if (!isset($this->metadataConverter)) {
            throw new Exception("Metadata converter is not set. Use setMetadataConverter before calling this method");
        }
        echo "Syncing " . count($itemList) . " items" . PHP_EOL;
        while (count($itemList) > 0) {
            $itemOrigin = array_shift($itemList);
            $itemTarget = $this->metadataConverter->convert($itemOrigin);
            $itemTarget->setId($itemOrigin->getId());
            $itemTarget->setCollection($itemOrigin->getCollection());
            foreach ($itemOrigin->getFiles() as $file) {
                $itemTarget->addFile($file);
            }
            $this->target->saveItem($itemTarget);
        }
    }
}
