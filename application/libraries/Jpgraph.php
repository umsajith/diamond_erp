<?php
class Jpgraph {
    function linechart($ydata, $title='Line Chart')
    {
        require_once("jpgraph/jpgraph.php");
        require_once("jpgraph/jpgraph_line.php");    
        
        // Create the graph. These two calls are always required
        $graph = new Graph(350,250,"auto",60);
        $graph->SetScale("textlin");
        
        // Setup title
        $graph->title->Set($title);
        
        // Create the linear plot
        $lineplot=new LinePlot($ydata);
        $lineplot->SetColor("blue");
        
        // Add the plot to the graph
        $graph->Add($lineplot);
        
        return $graph; // does PHP5 return a reference automatically?
    }
} 