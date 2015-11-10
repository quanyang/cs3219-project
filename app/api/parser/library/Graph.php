<?php

namespace parser\library;
class Graph
{

	public $graph;

	public function __construct() {
		$this->graph=[];
	}

	public function addEdge($fromNode,$toNode,$weight) {
		$this->graph[$fromNode][$toNode] = $weight;
	}

	public function output() {
		return $this->graph;
	}
}