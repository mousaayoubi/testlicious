<?php

declare(strict_types=1);

namespace Test18\Test18\Model;

use Test18\Test18\Api\NoteRepositoryInterface;
use Test18\Test18\Model\ResourceModel\Note as NoteResource;
use Test18\Test18\Api\Data\NoteInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class NoteRepository implements NoteRepositoryInterface
{
	protected $resource;
	protected $noteFactory;

	public function __construct(
		NoteResource $resource,
		NoteFactory $noteFactory
	){
	$this->resource = $resource;
	$this->noteFactory = $noteFactory;
	}

	public function save(NoteInterface $note): NoteInterface
	{
	try {
		$this->resource->save($note);
	} catch (\Throwable $e) {
	throw new CouldNotSaveException(__('Could not save the note, %1', $e->getMessage()));
	}

	return $note;
	}

	public function getById(int $id): NoteInterface
	{
	$note = $this->noteFactory->create();
	$this->resource->load($note, $id);

	if (!$note->getId()){
	throw new NoSuchEntityException(__('Note with id "%1" does not exist.', $id));
	}
	return $note;
	}
	
	public function delete(NoteInterface $note): bool
	{
	try {
	$this->resource->delete($note);
	} catch (\Throwable $e) {
	throw new CouldNotDeleteException(__('Could not delete the note: %1', $e->getMessage()));
	}

	return true;
	}

	public function deleteById(int $id): bool
	{
	return $this->delete($this->getById($id));
	}
}
