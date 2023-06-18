<?php

namespace App\Helpers\API;

use App\Helpers\Interfaces\ApiFormatterInterface;

class ApiFormatter implements ApiFormatterInterface
{
    private string $title;
    private string $description;
    private string $content;
    private string $image;
    private string $url;
    private string $publishedAt;
    private string $source_id;
    private string $source_name;
    private string $author_id;
    private string $author_name;
    private string $category_id;
    private string $category_name;

    public function __construct()
    {
        $this->title = '';
        $this->description = '';
        $this->content = '';
        $this->image = '';
        $this->url = '';
        $this->publishedAt = '';
        $this->source_id = '';
        $this->source_name = '';
        $this->author_id = '';
        $this->author_name = '';
        $this->category_id = '';
        $this->category_name = '';
    }

    // setter
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setPublishedAt(string $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function setSourceId(string $source_id): void
    {
        $this->source_id = $source_id;
    }

    public function setSourceName(string $source_name): void
    {
        $this->source_name = $source_name;
    }

    public function setAuthorId(string $author_id): void
    {
        $this->author_id = $author_id;
    }

    public function setAuthorName(string $author_name): void
    {
        $this->author_name = $author_name;
    }

    public function setCategoryId(string $category_id): void
    {
        $this->category_id = $category_id;
    }

    public function setCategoryName(string $category_name): void
    {
        $this->category_name = $category_name;
    }

    public function getAllPropertiesAsObject(): object
    {
        return (object) [
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->content,
            'image' => $this->image,
            'url' => $this->url,
            'publishedAt' => $this->publishedAt,
            'source_id' => $this->source_id,
            'source_name' => $this->source_name,
            'author_id' => $this->author_id,
            'author_name' => $this->author_name,
            'category_id' => $this->category_id,
            'category_name' => $this->category_name
        ];
    }

    public function reset(): void
    {
        $this->title = '';
        $this->description = '';
        $this->content = '';
        $this->image = '';
        $this->url = '';
        $this->publishedAt = '';
        $this->source_id = '';
        $this->source_name = '';
        $this->author_id = '';
        $this->author_name = '';
        $this->category_id = '';
        $this->category_name = '';
    }
}
