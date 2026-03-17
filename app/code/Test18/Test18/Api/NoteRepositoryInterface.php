<?php

declare(strict_types=1);

namespace Test18\Test18\Api;

use Test18\Test18\Api\Data\NoteInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface NoteRepositoryInterface
{
    /**
     * Save note.
     *
     * @param \Test18\Test18\Api\Data\NoteInterface $note
     * @return \Test18\Test18\Api\Data\NoteInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(NoteInterface $note): NoteInterface;

    /**
     * Get note by id.
     *
     * @param int $id
     * @return \Test18\Test18\Api\Data\NoteInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $id): NoteInterface;

    /**
     * Delete note.
     *
     * @param \Test18\Test18\Api\Data\NoteInterface $note
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(NoteInterface $note): bool;

    /**
     * Delete note by id.
     *
     * @param int $id
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $id): bool;
}
