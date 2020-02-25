# graph
A high-level abstraction of Dijkstra's algorithm in PHP

This code has been extracted from [doctrine/orientdb-odm](https://github.com/doctrine/orientdb-odm), 
which has been [marked inactive](https://www.doctrine-project.org/projects.html#inactive-projects),
and stripped down to the bare essentials.

The original LICENSE file has been retained, unchanged.

Below is a slightly modified snippet from the original README that demonstrates Graph's usage:

```
<?php

require "vendor/graph/autoloader.php";

use Sixpeteunder\Graph\Graph;
use Sixpeteunder\Graph\Vertex;
use Sixpeteunder\Graph\Algorithm\Dijkstra;

$graph = new Graph();

$rome = new Vertex('Rome');
$paris = new Vertex('Paris');
$london = new Vertex('London');

$rome->connect($paris, 2);
$rome->connect($london, 3);
$paris->connect($london, 1);

$graph->add($rome);
$graph->add($paris);
$graph->add($london);

$algorithm = new Dijkstra($graph);
$algorithm->setStartingVertex($rome);
$algorithm->setEndingVertex($london);

var_dump($algorithm->solve());
```

And here is a slightly more practical example of my own:

```
<?php

require "vendor/graph/autoloader.php";

use Sixpeteunder\Graph\Vertex;
use Sixpeteunder\Graph\Graph;
use Sixpeteunder\Graph\Algorithm\Dijkstra;

// Dummy points
$pointList = [
    (object) ['name' => 'Rome', 'latitude' => -0.212354, 'longitude' => 12.233423],
    (object) ['name' => 'Paris', 'latitude' => -0.316739, 'longitude' => 46.096325],
    (object) ['name' => 'London', 'latitude' => -0.544479, 'longitude' => 31.965335],
];

/** 
 * @param mixed $pointA
 * @param mixed $pointB
 * @return int
 * @todo Replace this with actual distance function 
 */
function getDistanceBetween($pointA, $pointB)
{
    return random_int(1, 10);
}

/**
 * Gets shortest path from a starting point to a finishing point.
 * 
 * @param array $points 
 * @param int $startingPointIndex
 * @param int $finishingPointIndex
 * @return Dijkstra
 */
function getShortestPath(array $points, int $startingPointIndex, int $finishingPointIndex)
{
    $graph = new Graph();

    $vertices = [];
    foreach ($points as $point) array_push($vertices, new Vertex($point->name));

    $count = count($vertices);
    for ($i = 0; $i < $count - 1; $i++)
        for ($j = $i + 1; $j < $count; $j++)
            $vertices[$i]->connect($vertices[$j], getDistanceBetween($points[$i], $points[$j]));

    foreach ($vertices as $vertex) $graph->add($vertex);

    $algorithm = new Dijkstra($graph);
    $algorithm->setStartingVertex($vertices[$startingPointIndex]);
    $algorithm->setEndingVertex($vertices[$finishingPointIndex]);

    $algorithm->solve();
    return $algorithm;
}

$result = getShortestPath($pointList, 0, 2);
var_dump($result->getShortestPath());
echo $result->getLiteralShortestPath();
echo "Distance is: " . $result->getDistance();
```