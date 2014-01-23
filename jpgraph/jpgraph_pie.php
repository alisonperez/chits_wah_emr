<?php
/*=======================================================================
// File:	JPGRAPH_PIE.PHP
// Description:	Pie plot extension for JpGraph
// Created: 	2001-02-14
// Author:	Johan Persson (johanp@aditus.nu)
// Ver:		$Id: jpgraph_pie.php,v 1.26 2002/06/13 08:52:16 aditus Exp $
//
// License:	This code is released under QPL
// Copyright (C) 2001,2002 Johan Persson
//========================================================================
*/


// Defines for PiePlot::SetLabelType()
DEFINE("PIE_VALUE_ABS",1);
DEFINE("PIE_VALUE_PER",0);
DEFINE("PIE_VALUE_PERCENTAGE",0);

//===================================================
// CLASS PiePlot
// Description: Draws a 
//===================================================
class PiePlot {
    var $posx=0.5,$posy=0.5;
    var $radius=0.3;
    var $explode_radius=array(),$explode_all=false,$explode_r=20;
    var $labels, $legends=null;
    var $csimtargets=null;  // Array of targets for CSIM
    var $csimareas='';		// Generated CSIM text	
    var $csimalts=null;		// ALT tags for corresponding target
    var $data=null;
    var $title;
    var $startangle=0;
    var $weight=1, $color="black";
    var $legend_margin=6,$show_labels=true;
    var $themearr = array(
	"earth" 	=> array(10,34,40,45,46,62,63,134,74,77,120,136,141,168,180,209,218,346,395,89,430),
	"pastel" => array(27,38,42,59,66,79,105,110,128,147,152,230,236,240,331,337,405,415),
	"water"  => array(8,370,10,40,335,56,213,237,268,14,326,387,24,388),
	"sand"   => array(27,168,34,170,19,50,65,72,131,209,46,393));
    var $theme="earth";
    var $setslicecolors=array();
    var $labeltype=0; // Default to percentage
    var $pie_border=true,$pie_interior_border=true;
    var $value;
	
//---------------
// CONSTRUCTOR
    function PiePlot(&$data) {
	$this->data = $data;
	$this->title = new Text("");
	$this->title->SetFont(FF_FONT1,FS_BOLD);
	$this->value = new DisplayValue();
	$this->value->Show();
	$this->value->SetFormat('%.0f%%');
    }

//---------------
// PUBLIC METHODS	
    function SetCenter($x,$y=0.5) {
	$this->posx = $x;
	$this->posy = $y;
    }

    function SetColor($aColor) {
	$this->color = $aColor;
    }

    function SetCSIMTargets(&$targets,$alts=null) {
	$this->csimtargets=$targets;
	$this->csimalts=$alts;
    }
	
    function GetCSIMareas() {
	return $this->csimareas;
    }

    function AddSliceToCSIM($i,$xc,$yc,$radius,$sa,$ea) {  //Slice number, ellipse centre (x,y), height, width, start angle, end angle
	while( $sa > 2*M_PI ) $sa = $sa - 2*M_PI;
	while( $ea > 2*M_PI ) $ea = $ea - 2*M_PI;

	$sa = 2*M_PI - $sa;
	$ea = 2*M_PI - $ea;

	$gsa = round(180/M_PI*$sa);
	$gea = round(180/M_PI*$ea);

	//echo "sa=$gsa, ea=$gea<br>";

	//add coordinates of the centre to the map
	$coords = "$xc, $yc";

	//add coordinates of the first point on the arc to the map
	$xp = floor(($radius*cos($ea))+$xc);
	$yp = floor($yc-$radius*sin($ea));
	$coords.= ", $xp, $yp";
	//add coordinates every 0.2 radians
	$a=$ea+0.2;
	while ($a<$sa) {
	    $xp = floor($radius*cos($a)+$xc);
	    $yp = floor($yc-$radius*sin($a));
	    $coords.= ", $xp, $yp";
	    $a += 0.2;
	}
		
	//Add the last point on the arc
	$xp = floor($radius*cos($sa)+$xc);
	$yp = floor($yc-$radius*sin($sa));
	$coords.= ", $xp, $yp";
	if( !empty($this->csimtargets[$i]) )
	    $this->csimareas .= "<area shape=\"poly\" coords=\"$coords\" href=\"".$this->csimtargets[$i]."\"";
	if( !empty($this->csimalts[$i]) ) {										
	    $tmp=sprintf($this->csimalts[$i],$this->data[$i]);
	    $this->csimareas .= " alt=\"$tmp\" title=\"$tmp\"";
	}
	$this->csimareas .= ">\r\n";
    }

	
    function SetTheme($aTheme) {
	if( in_array($aTheme,array_keys($this->themearr)) )
	    $this->theme = $aTheme;
	else
	    JpGraphError::Raise("PiePLot::SetTheme() Unknown theme: $aTheme");
    }
	
    function ExplodeSlice($e,$radius=20) {
	$this->explode_radius[$e]=$radius;
    }

    function ExplodeAll($radius=-1) {
	$this->explode_all=true;
	if( $radius==-1 )
	    $this->explode_r = 20;
	else
	    $this->explode_r = $radius;
    }

    function Explode($aExplodeArr) {
	if( !is_array($aExplodeArr) ) {
	    JpGraphError::Raise("Argument to PiePlot::Explode() must be an array.");
	}
	$this->explode_radius = $aExplodeArr;
    }
	
    function SetSliceColors($aColors) {
	$this->setslicecolors = $aColors;
    }
	
    function SetStartAngle($aStart) {
	$this->startangle = $aStart;
    }
	
    function SetFont($family,$style=FS_NORMAL,$size=10) {
		JpGraphError::Raise('PiePlot::SetFont() is deprecated. Use PiePlot->value->SetFont() instead.');
    }
	
    // Size in percentage
    function SetSize($aSize) {
	if( ($aSize>0 && $aSize<=0.5) || ($aSize>10 && $aSize<1000) )
	    $this->radius = $aSize;
	else
	    JpGraphError::Raise("PiePlot::SetSize() Radius for pie must either be specified as a fraction
                                [0, 0.5] of the size of the image or as an absolute size in pixels 
                                in the range [10, 1000]");
    }
	
    function SetFontColor($aColor) {
	JpGraphError::Raise('PiePlot::SetFontColor() is deprecated. Use PiePlot->value->SetColor() instead.');
    }
	
    // Set label arrays
    function SetLegends($aLegend) {
	$this->legends = $aLegend;
    }
	
    // Should we display actual value or percentage?
    function SetLabelType($t) {
	if( $t<0 || $t>1 ) 
	    JpGraphError::Raise("PiePlot::SetLabelType() Type for pie plots must be 0 or 1 (not $t).");
	$this->labeltype=$t;
    }

    function SetValueType($aType) {
	$this->SetLabelType($aType);
    }


    // Should the circle around a pie plot be displayed
    function ShowBorder($exterior=true,$interior=true) {
	$this->pie_border = $exterior;
	$this->pie_interior_border = $interior;
    }
	
    // Setup the legends
    function Legend(&$graph) {
	$colors = array_keys($graph->img->rgb->rgb_table);
   	sort($colors);	
   	$ta=$this->themearr[$this->theme];	
   	
   	if( $this->setslicecolors==null ) 
	    $numcolors=count($ta);
   	else
	    $numcolors=count($this->setslicecolors);
		
	$sum=0;
	for($i=0; $i<count($this->data); ++$i)
	    $sum += $this->data[$i];

	// Bail out with error if the sum is 0
	if( $sum==0 )
	    JpGraphError::Raise("Illegal pie plot. Sum of all data is zero for Pie!");

	$i=0;
	if( count($this->legends)>0 ) {
	    foreach( $this->legends as $l ) {
				
		// Replace possible format with actual values
		if( $this->labeltype==0 )
		    $l = sprintf($l,100*$this->data[$i]/$sum);
		else
		    $l = sprintf($l,$this->data[$i]);
				
		if( $this->setslicecolors==null ) 
		    $graph->legend->Add($l,$colors[$ta[$i%$numcolors]]);
		else
		    $graph->legend->Add($l,$this->setslicecolors[$i%$numcolors]);
		++$i;
				
				// Breakout if there are more legends then values
		if( $i==count($this->data) ) return;
	    }
	}
    }
	
    function Stroke(&$img) {
		
	$colors = array_keys($img->rgb->rgb_table);
   	sort($colors);	
   	$ta=$this->themearr[$this->theme];	
   	
   	if( $this->setslicecolors==null ) 
	    $numcolors=count($ta);
   	else
	    $numcolors=count($this->setslicecolors);
   	
	// Draw the slices
	$sum=0;
	for($i=0; $i<count($this->data); ++$i)
	    $sum += $this->data[$i];
	
	// Bail out with error if the sum is 0
	if( $sum==0 )
	    JpGraphError::Raise("Sum of all data is 0 for Pie.");
	
	// Format the titles for each slice
	for( $i=0; $i<count($this->data); ++$i) {
	    if( $this->labeltype==0 )
		if( $sum != 0 )
		    $l = 100*$this->data[$i]/$sum;
		else
		    $l = 0;
	    else
		$l = $this->data[$i];
	    $this->labels[$i]=$l;
	}
		
	// Set up the pic-circle
	if( $this->radius < 1 )
	    $radius = floor($this->radius*min($img->width,$img->height));
	else
	    $radius = $this->radius;
	$xc = $this->posx*$img->width;
	$yc = $this->posy*$img->height;
		
	$accsum=0;
	$this->startangle = $this->startangle*M_PI/180;
	$angle2 = $this->startangle;
	$img->SetColor($this->color);

	if( $this->explode_all )
	    for($i=0;$i<count($this->data);++$i)
		$this->explode_radius[$i]=$this->explode_r;

	for($i=0; $sum>0 && $i<count($this->data); ++$i) {
	    $d = $this->data[$i];
	    $angle1 = $angle2;
	    $accsum += $d;
	    $angle2 = $this->startangle+2*M_PI*$accsum/$sum;
	    
	    if( $this->setslicecolors==null )
		$slicecolor=$colors[$ta[$i%$numcolors]];
	    else
		$slicecolor=$this->setslicecolors[$i%$numcolors];

	    if( $this->pie_interior_border )
		$img->SetColor($this->color);
	    else
		$img->SetColor($slicecolor);

	    $arccolor = $this->pie_border ? $this->color : "";

	    $la = 2*M_PI - (abs($angle2-$angle1)/2.0+$angle1);

	    if( empty($this->explode_radius[$i]) )
		$this->explode_radius[$i]=0;

	    $xcm = $xc + $this->explode_radius[$i]*cos($la);
	    $ycm = $yc - $this->explode_radius[$i]*sin($la);
	    
	    $img->CakeSlice($xcm,$ycm,$radius-1,$radius-1,$angle1*180/M_PI,$angle2*180/M_PI,$slicecolor,$arccolor);

	    if( $this->value->show ) 
		$this->StrokeLabels($this->labels[$i],$img,$xc,$yc,$la,$radius+$this->explode_radius[$i]);  

	    if ($this->csimtargets) 
		$this->AddSliceToCSIM($i,$xcm,$ycm,$radius,$angle1,$angle2);

	}
	// Adjust title position
	$this->title->Pos($xc,$yc-$img->GetFontHeight()-$radius,"center","bottom");
	$this->title->Stroke($img);
		
    }

//---------------
// PRIVATE METHODS	
	
    // Position the labels of each slice
    function StrokeLabels($label,$img,$xc,$yc,$a,$r) {
	$this->value->halign = "left";
	$this->value->valign = "top";
	$this->value->margin = 0;

	$r += $img->GetFontHeight()/2;
	$xt=round($r*cos($a)+$xc);
	$yt=round($yc-$r*sin($a));

	// Position the axis title. 
	// dx, dy is the offset from the top left corner of the bounding box that sorrounds the text
	// that intersects with the extension of the corresponding axis. The code looks a little
	// bit messy but this is really the only way of having a reasonable position of the
	// axis titles.
	$img->SetFont($this->value->ff,$this->value->fs,$this->value->fsize);
	$h=$img->GetTextHeight($label);
	$w=$img->GetTextWidth(sprintf($this->value->format,$label));
	while( $a > 2*M_PI ) $a -= 2*M_PI;
	if( $a>=7*M_PI/4 || $a <= M_PI/4 ) $dx=0;
	if( $a>=M_PI/4 && $a <= 3*M_PI/4 ) $dx=($a-M_PI/4)*2/M_PI; 
	if( $a>=3*M_PI/4 && $a <= 5*M_PI/4 ) $dx=1;
	if( $a>=5*M_PI/4 && $a <= 7*M_PI/4 ) $dx=(1-($a-M_PI*5/4)*2/M_PI);
		
	if( $a>=7*M_PI/4 ) $dy=(($a-M_PI)-3*M_PI/4)*2/M_PI;
	if( $a<=M_PI/4 ) $dy=(1-$a*2/M_PI);
	if( $a>=M_PI/4 && $a <= 3*M_PI/4 ) $dy=1;
	if( $a>=3*M_PI/4 && $a <= 5*M_PI/4 ) $dy=(1-($a-3*M_PI/4)*2/M_PI);
	if( $a>=5*M_PI/4 && $a <= 7*M_PI/4 ) $dy=0;
		
	$this->value->Stroke($img,$label,$xt-$dx*$w,$yt-$dy*$h);
    }	
} // Class



//===================================================
// CLASS PieGraph
// Description: 
//===================================================
class PieGraph extends Graph {
    var $posx, $posy, $radius;		
    var $legends=array();	
    var $plots=array();
//---------------
// CONSTRUCTOR
    function PieGraph($width=300,$height=200,$cachedName="",$timeout=0,$inline=1) {
	$this->Graph($width,$height,$cachedName,$timeout,$inline);
	$this->posx=$width/2;
	$this->posy=$height/2;
	$this->SetColor(array(255,255,255));		
    }

//---------------
// PUBLIC METHODS	
    function Add(&$pie) {
	$this->plots[] = $pie;
    }
	
    function SetColor($c) {
	$this->SetMarginColor($c);
    }


    function DisplayCSIMAreas() {
	    $csim="";
	    foreach($this->plots as $p ) {
		$csim .= $p->GetCSIMareas();
	    }
	    //$csim.= $this->legend->GetCSIMareas();
	    if (preg_match_all("/area shape=\"(\w+)\" coords=\"([0-9\, ]+)\"/", $csim, $coords)) {
		$this->img->SetColor($this->csimcolor);
		for ($i=0; $i<count($coords[0]); $i++) {
		    if ($coords[1][$i]=="poly") {
			preg_match_all('/\s*([0-9]+)\s*,\s*([0-9]+)\s*,*/',$coords[2][$i],$pts);
			$this->img->SetStartPoint($pts[1][count($pts[0])-1],$pts[2][count($pts[0])-1]);
			for ($j=0; $j<count($pts[0]); $j++) {
			    $this->img->LineTo($pts[1][$j],$pts[2][$j]);
			}
		    } else if ($coords[1][$i]=="rect") {
			$pts = preg_split('/,/', $coords[2][$i]);
			$this->img->SetStartPoint($pts[0],$pts[1]);
			$this->img->LineTo($pts[2],$pts[1]);
			$this->img->LineTo($pts[2],$pts[3]);
			$this->img->LineTo($pts[0],$pts[3]);
			$this->img->LineTo($pts[0],$pts[1]);
						
		    }
		}
	    }
    }

    // Method description
    function Stroke($aStrokeFileName="") {
		
	$this->StrokeFrame();		
		
	for($i=0; $i<count($this->plots); ++$i) 
	    $this->plots[$i]->Stroke($this->img);
		
	foreach( $this->plots as $p)
	    $p->Legend($this);	
		
	$this->legend->Stroke($this->img);
	$this->StrokeTitles();

	// Stroke texts
	if( $this->texts != null )
	    foreach( $this->texts as $t) 
		$t->Stroke($this->img);
		
	if( JPG_DEBUG ) {
	    $this->DisplayCSIMAreas();
	}
		
	// Finally output the image
	$this->cache->PutAndStream($this->img,$this->cache_name,$this->inline,$aStrokeFileName);					
    }
} // Class

/* EOF */
?>
