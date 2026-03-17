<?php

declare(strict_types=1);

namespace Test18\Test18\Api\Data;

interface NoteInterface
{
    public const ENTITY_ID = 'entity_id';
    public const TITLE = 'title';

    /**
     * Get entity id.
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Set entity id.
     *
     * @param mixed $value
     * @return $this
     */
    public function setId($value);

    /**
     * Get title.
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Set title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title);
}
