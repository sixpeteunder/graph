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
 * Class Graph is a dataset to easily work with a simulated graph.
 *
 * @package     sixpeteunder\graph
 * @subpackage  
 * @author      Peter Mghendi <plenjo15@gmail.com>
 */

namespace sixpeteunder\graph;

use Exception;

class Graph
{
    /**
     * All the vertices in the graph
     *
     * @var array
     */
    protected $vertices = array();

    /**
     * Adds a new vertex to the current graph.
     *
     * @param   Vertex $vertex
     * @return  Graph
     * @throws  Exception
     */
    public function add(Vertex $vertex)
    {
        if (array_key_exists($vertex->getId(), $this->getVertices())) {
            throw new Exception('Unable to insert multiple Vertices with the same ID in a Graph');
        }

        $this->vertices[$vertex->getId()] = $vertex;

        return $this;
    }

    /**
     * Returns the vertex identified with the $id associated to this graph.
     *
     * @param   mixed $id
     * @return  Vertex
     * @throws  Exception
     */
    public function getVertex($id)
    {
        $vertices = $this->getVertices();

        if (!array_key_exists($id, $vertices)) {
            throw new Exception("Unable to find $id in the Graph");
        }

        return $vertices[$id];
    }

    /**
     * Returns all the vertices that belong to this graph.
     *
     * @return Array
     */
    public function getVertices()
    {
        return $this->vertices;
    }
}
