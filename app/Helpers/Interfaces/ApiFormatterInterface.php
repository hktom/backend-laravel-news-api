<?php

namespace App\Helpers\Interfaces;

interface ApiFormatterInterface
{

    public function __construct();

    public function setTitle(string $title): void;
    public function setDescription(string $description): void;
    public function setContent(string $content): void;
    public function setImage(string $image): void;
    public function setUrl(string $url): void;
    public function setPublishedAt(string $publishedAt): void;
    public function setSourceId(string $source_id): void;
    public function setSourceName(string $source_name): void;
    public function setAuthorId(string $author_id): void;
    public function setAuthorName(string $author_name): void;
    public function setCategoryId(string $category_id): void;
    public function setCategoryName(string $category_name): void;
    public function getAllPropertiesAsObject(): object;
    public function reset(): void;
}
