<?php

declare(strict_types=1);

namespace Test18\Test18\Model;

use Magento\Framework\Model\AbstractModel;
use Test18\Test18\Api\Data\NoteInterface;
use Test18\Test18\Model\ResourceModel\Note as NoteResource;

class Note extends AbstractModel implements NoteInterface
{
    protected function _construct(): void
    {
        $this->_init(NoteResource::class);
    }

    public function getId(): ?int
    {
        $value = $this->getData(self::ENTITY_ID);
        return $value === null ? null : (int)$value;
    }

    public function setId($value)
    {
        return $this->setData(self::ENTITY_ID, $value);
    }

    public function getTitle(): ?string
    {
        $value = $this->getData(self::TITLE);
        return $value === null ? null : (string)$value;
    }

    public function setTitle(string $title)
    {
        return $this->setData(self::TITLE, $title);
    }
}
