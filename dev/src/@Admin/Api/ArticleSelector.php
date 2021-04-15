<?php namespace Application\Admin\Api;

use Application\Entity\Article;
use Atomino\Molecules\Magic\SelectorApi;
use Atomino\Database\Finder\Filter;
use Atomino\Entity\Entity;

class ArticleSelector extends SelectorApi {
	/** @param Article $item */
	protected function value(Entity $item): string { return $item->title; }
	protected function filter(string $search): Filter { return Filter::where(Article::title()->like('%' . $search . '%')); }
	protected function order(): array { return ['title', 'desc']; }
	protected function getEntity(): string { return Article::class; }
}
