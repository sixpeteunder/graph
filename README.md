# graph
A high-level abstraction of Dijkstra's algorithm in PHP

This code has been extracted from [doctrine/orientdb-odm](https://github.com/doctrine/orientdb-odm), 
which has been [marked inactive](https://www.doctrine-project.org/projects.html#inactive-projects),
and stripped down to the bare essentials.

The original LICENSE file has been retained, unchanged.

Below is a slightly modified snippet from the original README that demonstrates Graph's usage:

```
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
