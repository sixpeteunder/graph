<?php

/*
 * This file is part of the graph package.
 *
 * (c) Peter Mghendi <plenjo15@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class Dijkstra is an implementation of the famous Dijkstra's algorithm to
 * calculate the shortest path between two vertices of a graph.
 *
 * @package     Sixpeteunder\Graph
 * @subpackage  
 * @author      Peter Mghendi <plenjo15@gmail.com>
 */

namespace Sixpeteunder\Graph\Algorithm;

use Sixpeteunder\Graph\Graph;
use Sixpeteunder\Graph\Vertex;
use LogicException;

class Dijkstra
{
    protected $startingVertex;
    protected $endingVertex;
    protected $graph;
    protected $paths = array();
    protected $solution = false;

    /**
     * Instantiates a new algorithm, requiring a graph to work with.
     *
     * @param Graph $graph
     */
    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * Returns the distance between the starting and the ending point.
     *
     * @return integer
     */
    public function getDistance()
    {
        if (!$this->isSolved()) {
            throw new LogicException(
                "Cannot calculate the distance of a non-solved algorithm:\nDid you forget to call ->solve()?"
            );
        }

        return $this->getEndingVertex()->getPotential();
    }

    /**
     * Gets the vertex which we are pointing to.
     *
     * @return VertexInterface
     */
    public function getEndingVertex()
    {
        return $this->endingVertex;
    }

    /**
     * Returns the solution in a human-readable style.
     *
     * @return string
     */
    public function getLiteralShortestPath()
    {
        $path = $this->solve();
        $literal = '';

        foreach ($path as $p) {
            $literal .= "{$p->getId()} - ";
        }

        return substr($literal, 0, count($literal) - 4);
    }

    /**
     * Reverse-calculates the shortest path of the graph thanks the potentials
     * stored in the vertices.
     *
     * @return Array
     */
    public function getShortestPath()
    {
        $path   = array();
        $vertex = $this->getEndingVertex();

        while ($vertex->getId() != $this->getStartingVertex()->getId()) {
            $path[] = $vertex;
            $vertex = $vertex->getPotentialFrom();
        }

        $path[] = $this->getStartingVertex();

        return array_reverse($path);
    }

    /**
     * Retrieves the vertex which we are starting from to calculate the shortest path.
     *
     * @return Vertex
     */
    public function getStartingVertex()
    {
        return $this->startingVertex;
    }

    /**
     * Sets the vertex which we are pointing to.
     *
     * @param Vertex $vertex
     */
    public function setEndingVertex(Vertex $vertex)
    {
        $this->endingVertex = $vertex;
    }

    /**
     * Sets the vertex which we are starting from to calculate the shortest path.
     *
     * @param Vertex $vertex
     */
    public function setStartingVertex(Vertex $vertex)
    {
        $this->paths[] = array($vertex);
        $this->startingVertex = $vertex;
    }

    /**
     * Solves the algorithm and returns all possible results.
     *
     * @return mixed
     */
    public function solve()
    {
        if (!$this->getStartingVertex() || !$this->getEndingVertex()) {
            throw new LogicException("Cannot solve the algorithm without both starting and ending vertices");
        }

        $this->calculatePotentials($this->getStartingVertex());
        $this->solution = $this->getShortestPath();

        return $this->solution;
    }

    /**
     * Recursively calculates the potentials of the graph, from the
     * starting point you specify with ->setStartingVertex(), traversing
     * the graph due to Vertex's $connections attribute.
     *
     * @param Vertex $vertex
     */
    protected function calculatePotentials(Vertex $vertex)
    {
        $connections = $vertex->getConnections();
        $sorted = array_flip($connections);

        krsort($sorted);

        foreach ($connections as $id => $distance) {
            $v = $this->getGraph()->getVertex($id);
            $v->setPotential($vertex->getPotential() + $distance, $vertex);

            foreach ($this->getPaths() as $path) {
                $count = count($path);

                if ($path[$count - 1]->getId() === $vertex->getId()) {
                    $this->paths[] = array_merge($path, array($v));
                }
            }
        }

        $vertex->markPassed();

        // Get loop through the current node's nearest connections
        // to calculate their potentials.
        foreach ($sorted as $id) {
            $vertex = $this->getGraph()->getVertex($id);

            if (!$vertex->isPassed()) {
                $this->calculatePotentials($vertex);
            }
        }
    }

    /**
     * Returns the graph associated with this algorithm instance.
     *
     * @return Graph
     */
    protected function getGraph()
    {
        return $this->graph;
    }

    /**
     * Returns the possible paths registered in the graph.
     *
     * @return Array
     */
    protected function getPaths()
    {
        return $this->paths;
    }

    /**
     * Checks wheter the current algorithm has been solved or not.
     *
     * @return boolean
     */
    protected function isSolved()
    {
        return (bool) $this->solution;
    }
}
