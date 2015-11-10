<?php
namespace parser\library;
class Dijkstra
{
  protected $graph;

  public function __construct($graph) {
    $this->graph = $graph;
  }

  public function shortestPath($source, $target) {
    // array of best estimates of shortest path to each
    // vertex
    $dist = array();
    // array of predecessors for each vertex
    $prev = array();
    $vertex = array();
    foreach ($this->graph as $v => $adj) {
      $vertex[$v] = 1;
      foreach ($adj as $w => $cost) {
        $vertex[$w] = 1;
      }
    }

    foreach ($vertex as $v=>$value) {
      $dist[$v] = 1;
      $prev[$v] = null;
    }
    $dist[$source] = -1;

    while(!empty($vertex)) {
      $min = 2;
      $u=null;
      foreach ($vertex as $v=>$value) {
        if ($dist[$v] < $min) {
          $min = $dist[$v];
          $u = $v;
        }
      }
      unset($vertex[$u]);

      if (isset($this->graph[$u])) {
        foreach($this->graph[$u] as $n => $cost) {
          $alt = -abs($dist[$u] * $cost);
          if ($alt < $dist[$n]) {
            $dist[$n] = $alt;
            $prev[$n] = $u;
          }
        }
      }
    }

    // we can now find the shortest path using reverse
    // iteration
    $S = new \SplStack(); // shortest path with a stack
    $u = $target;
    $dist = 1;
    // traverse from target to source
    while (isset($prev[$u]) && $prev[$u]) {
      $S->push($u);
      $dist *= $this->graph[$prev[$u]][$u]; // add distance to predecessor
      $u = $prev[$u];
    }

    return $dist;
  }
}