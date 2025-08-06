<?php
require_once dirname(__DIR__,4).'/jpgraph/src/jpgraph.php';
require_once dirname(__DIR__,4).'/jpgraph/src/jpgraph_bar.php';

// Données
$data = array(10, 25, 17, 35, 22);

// Création du graphique
$graph = new Graph(500, 400);
$graph->SetScale('textlin');
$graph->SetMargin(40, 30, 40, 50);
$graph->title->Set('Graphique en barres');
$graph->xaxis->SetTickLabels(['Jan', 'Fev', 'Mar', 'Avr', 'Mai']);

// Création des barres
$barplot = new BarPlot($data);
$barplot->SetFillColor('orange');
$graph->Add($barplot);

// Affichage
$graph->Stroke();
