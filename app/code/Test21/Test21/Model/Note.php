<?php

declare(strict_types=1);

namespace Test21\Test21\Model;

use Magento\Framework\Model\AbstractModel;
use Test21\Test21\Model\ResourceModel\Note as NoteResource;

class Note extends AbstractModel
{
        protected function _construct()
        {
        $this->_init(NoteResource::class);
        }
}
