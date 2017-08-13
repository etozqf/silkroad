<?php

// 标准的多叉树模型

class Node
{
	public $prev, $next, $children, $parent, $id, $data;
	function __construct($id, $data = null)
	{
		$this->prev = null;
		$this->next = null;
		$this->children = array();
		$this->parent = null;
		$this->id = $id;
		$this->data = $data;
	}

	function add_child($node)
	{
		$node->parent = $this;
		if ($count = count($this->children)) {
			$last_child = $this->children[$count-1];
			$last_child->next = $node;
			$node->prev = $last_child;
		}
		array_push($this->children, $node);
		return $this;
	}
}

class Tree
{
	public $root;
	protected $id_field, $parentid_field;
	function __construct($data, $id_field = 'id', $parentid_field = 'parentid')
	{
		$this->id_field = $id_field;
		$this->parentid_field = $parentid_field;
		if (is_array($data)) {
			$this->array2tree($data);
		}
	}

	// 广度优先搜索
	function bfs($id)
	{
		$queue = array($this->root);
		while ($queue) {
			$item = array_shift($queue);
			if ($item->id == $id) {
				return $item;
			}
			if (count($item->children)) {
				$queue = array_merge($queue, $item->children);
			}
		}
		return false;
	}

	// 深度优先搜索
	function dfs($id)
	{
		$stack = array($this->root);
		while ($stack) {
			$item = array_pop($stack);
			if ($item->id == $id) {
				return $item;
			}
			if (count($item->children)) {
				$stack = array_merge($stack, $item->children);
			}
		}
		return false;
	}

	private function array2tree($array)
	{
		$this->root = new Node(0);
		foreach ($array as $item) {
			$parentid = intval($item[$this->parentid_field]);
			if ($parent = $this->bfs($parentid)) {
				$parent->add_child(new Node($item[$this->id_field], $item));
			}
		}
	}
}