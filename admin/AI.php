<?php
    $awayCity = "Milwaukee";
    $awayName = "Brewers";
    $homeCity = "Minnesota";
    $homeName = "Twins";

    $text = "";
    $hostOrVisitor = rand(0,1);
    $cityOrName = rand(0,1);
    if ($cityOrName == 0)
    {
        $text.="The ";
        if ($hostOrVisitor == 0)
            $text.= $awayName;
        else
            $text.= $homeName;
        $text.= GetVerb(["will be", "are"]);
    }
    else
    {
        if ($hostOrVisitor == 0)
            $text.= $awayCity;
        else
            $text.= $homeCity;
        $text.= GetVerb(["will be", "is"]);
    }


    if ($hostOrVisitor == 0)
        $text.= GetVerb(["visiting", "the guests of"]);
    else
        $text.= GetVerb(["hosting", "playing host to", "welcoming", "entertaining"]);

    $cityOrName = rand(0,1);
    if ($cityOrName == 0)
    {
        $text.=" the ";
        if ($hostOrVisitor == 0)
            $text.= $homeName;
        else
            $text.= $awayName;
    }
    else
    {
        if ($hostOrVisitor == 0)
            $text.= $homeCity;
        else
            $text.= $awayCity;
    }

    echo $text;

    function GetVerb($verbs)
    {
        return " " . $verbs[rand(0, sizeof($verbs)-1)] . " ";
    }
?>
