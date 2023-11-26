<?php

class DashboardCharts{

	public static function displayTotalLoansReleasedChart($start_date,$end_date,$chart_title,$chart_container){
		$loans=Dashboard::getChartsTotalLoansReleased($start_date,$end_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $chart_title;
		$chart->title->style->fontSize="12px";

		$axisarray = array();
		$count = 0;
		foreach ($loans as $key){
		  $axisarray[$count] = array(date('d/m/Y', strtotime($key['day'])));
		  $count++; 
		}
		$chart->xAxis->categories = $axisarray;
		$chart->xAxis->style->fontSize="10px";
		/* Loans Y-Axis */
		$leftYaxis = new HighchartOption();
		$leftYaxis->title->text = " ";
		$leftYaxis->title->style->color = "#0266c8";
		$leftYaxis->labels->style->color = "#0266c8";
		$leftYaxis->style->fontSize="10px";
		/* Date X-Axis */
		$rightYaxis = new HighchartOption();
		$rightYaxis->title->text = " ";
		$rightYaxis->title->style->color = "#0266c8";
		$rightYaxis->labels->style->color = "#0266c8";
		$rightYaxis->style->fontSize="10px";

		$rightYaxis->opposite = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*Total Loans */
		$loanaccounts = array();
		$count = 0;
		foreach ($loans as $loan) { 
			if(isset($loan["loans"])){ 
				$loanaccounts[$count] = array('y'=>$loan["loans"]);  
			}else{ 
				$loanaccounts[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name'=>"Loans Released",'color' => "#0266c8",'type'=>"line",'yAxis'=>1,
			'data'=>$loanaccounts);
		$chart->tooltip->pointFormat=new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package = '<div id="'.$chart_container.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
    $package.= '</script>';
    echo $package;
	}

	public static function displayTotalLoansCumulativeReleasedChart($start_date,$end_date,$chart_title,$chart_container){
		$loans=Dashboard::getChartsTotalLoansCumulativeReleased($start_date,$end_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $chart_title;
		$chart->title->style->fontSize="12px";

		$axisarray = array();
		$count = 0;
		foreach ($loans as $key){
		  $axisarray[$count] = array(date('d/m/Y', strtotime($key['day'])));
		  $count++; 
		}
		$chart->xAxis->categories = $axisarray;
		$chart->xAxis->style->fontSize="10px";
		/* Loans Y-Axis */
		$leftYaxis = new HighchartOption();
		$leftYaxis->title->text = " ";
		$leftYaxis->title->style->color = "#0266c8";
		$leftYaxis->labels->style->color = "#0266c8";
		$leftYaxis->style->fontSize="10px";
		/* Date X-Axis */
		$rightYaxis = new HighchartOption();
		$rightYaxis->title->text = " ";
		$rightYaxis->title->style->color = "#0266c8";
		$rightYaxis->labels->style->color = "#0266c8";
		$rightYaxis->style->fontSize="10px";

		$rightYaxis->opposite = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*Total Loans */
		$loanaccounts = array();
		$count = 0;
		foreach ($loans as $loan) { 
			if(isset($loan["loans"])){ 
				$loanaccounts[$count] = array('y'=>$loan["loans"]);  
			}else{ 
				$loanaccounts[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name'=>"Number of Loans Released",'color' => "#0266c8",'type'=>"line",'yAxis'=>1,
			'data'=>$loanaccounts);
		$chart->tooltip->pointFormat=new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package = '<div id="'.$chart_container.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
    $package.= '</script>';
    echo $package;
	}

	public static function displayTotalLoanAmountReleasedChart($start_date,$end_date,$chart_title,$chart_container){
		$loans=Dashboard::getChartsTotalAmountLoansReleased($start_date,$end_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $chart_title;
		$chart->title->style->fontSize="12px";

		$axisarray = array();
		$count = 0;
		foreach ($loans as $key){
		  $axisarray[$count] = array(date('d/m/Y', strtotime($key['day'])));
		  $count++; 
		}
		$chart->xAxis->categories = $axisarray;
		$chart->xAxis->style->fontSize="10px";
		/* Loans Y-Axis */
		$leftYaxis = new HighchartOption();
		$leftYaxis->title->text = " ";
		$leftYaxis->title->style->color = "#0266c8";
		$leftYaxis->labels->style->color = "#0266c8";
		$leftYaxis->style->fontSize="10px";
		/* Date X-Axis */
		$rightYaxis = new HighchartOption();
		$rightYaxis->title->text = " ";
		$rightYaxis->title->style->color = "#0266c8";
		$rightYaxis->labels->style->color = "#0266c8";
		$rightYaxis->style->fontSize="10px";

		$rightYaxis->opposite = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*Total Loans */
		$loanaccounts = array();
		$count=0;
		foreach ($loans as $loan) { 
			if(isset($loan["loanAmount"])){ 
				$loanaccounts[$count] = array('y'=>$loan["loanAmount"]);  
			}else{ 
				$loanaccounts[$count] = 0;  
			}  
			$count++;
		}
		$chart->series[] = array('name'=>"Total Amount Released",'color'=> "#0266c8",'type'=>"column",'yAxis'=>1,'data'=>$loanaccounts);
		$chart->tooltip->pointFormat=new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package = '<div id="'.$chart_container.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart2");
    $package.= '</script>';
    echo $package;
	}


	public static function displayTotalAmountCollectedChart($start_date,$end_date,$chart_title,$chart_container){
		$loans=Dashboard::getChartsTotalAmountCollected($start_date,$end_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $chart_title;
		$chart->title->style->fontSize="12px";

		$axisarray = array();
		$count = 0;
		foreach ($loans as $key){
		  $axisarray[$count] = array(date('d/m/Y', strtotime($key['day'])));
		  $count++; 
		}
		$chart->xAxis->categories = $axisarray;
		$chart->xAxis->style->fontSize="10px";
		/* Loans Y-Axis */
		$leftYaxis = new HighchartOption();
		$leftYaxis->title->text = " ";
		$leftYaxis->title->style->color = "#f90101";
		$leftYaxis->labels->style->color = "#f90101";
		$leftYaxis->style->fontSize="10px";
		/* Date X-Axis */
		$rightYaxis = new HighchartOption();
		$rightYaxis->title->text = " ";
		$rightYaxis->title->style->color = "#f90101";
		$rightYaxis->labels->style->color = "#f90101";
		$rightYaxis->style->fontSize="10px";

		$rightYaxis->opposite = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*Total Loans */
		$loanaccounts = array();
		$count=0;
		foreach ($loans as $loan) { 
			if(isset($loan["loanAmount"])){ 
				$loanaccounts[$count] = array('y'=>$loan["loanAmount"]);  
			}else{ 
				$loanaccounts[$count] = 0;  
			}  
			$count++;
		}
		$chart->series[] = array('name'=>"Total Amount Collected",'color'=> "#f90101",'type'=>"column",'yAxis'=>1,'data'=>$loanaccounts);
		$chart->tooltip->pointFormat=new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package = '<div id="'.$chart_container.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart2");
    $package.= '</script>';
    echo $package;
	}

	public static function displayTotalCollectionsReceivedChart($start_date,$end_date,$chart_title,$chart_container){
		$loans=Dashboard::getChartsTotalCollectionsReceived($start_date,$end_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $chart_title;
		$chart->title->style->fontSize="12px";

		$axisarray = array();
		$count = 0;
		foreach ($loans as $key){
		  $axisarray[$count] = array(date('d/m/Y', strtotime($key['day'])));
		  $count++; 
		}
		$chart->xAxis->categories = $axisarray;
		$chart->xAxis->style->fontSize="10px";
		/* Loans Y-Axis */
		$leftYaxis = new HighchartOption();
		$leftYaxis->title->text = " ";
		$leftYaxis->title->style->color = "#f90101";
		$leftYaxis->labels->style->color = "#f90101";
		$leftYaxis->style->fontSize="10px";
		/* Date X-Axis */
		$rightYaxis = new HighchartOption();
		$rightYaxis->title->text = " ";
		$rightYaxis->title->style->color = "#f90101";
		$rightYaxis->labels->style->color = "#f90101";
		$rightYaxis->style->fontSize="10px";

		$rightYaxis->opposite = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*Total Loans */
		$loanaccounts = array();
		$count = 0;
		foreach ($loans as $loan) { 
			if(isset($loan["loans"])){ 
				$loanaccounts[$count] = array('y'=>$loan["loans"]);  
			}else{ 
				$loanaccounts[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name'=>"Collections Received",'color' => "#f90101",'type'=>"line",'yAxis'=>1,'data'=>$loanaccounts);
		$chart->tooltip->pointFormat=new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package = '<div id="'.$chart_container.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
    $package.= '</script>';
    echo $package;
	}

	public static function displayTotalPrincipalOutstanding($start_date,$end_date,$chart_title,$chart_container){
		$loans=Dashboard::getChartsTotalPrincipalOutstanding($start_date,$end_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $chart_title;
		$chart->title->style->fontSize="12px";

		$axisarray = array();
		$count = 0;
		foreach ($loans as $key){
		  $axisarray[$count] = array(date('d/m/Y', strtotime($key['day'])));
		  $count++; 
		}
		$chart->xAxis->categories = $axisarray;
		$chart->xAxis->style->fontSize="10px";
		/* Loans Y-Axis */
		$leftYaxis = new HighchartOption();
		$leftYaxis->title->text = " ";
		$leftYaxis->title->style->color = "#00933b";
		$leftYaxis->labels->style->color = "#00933b";
		$leftYaxis->style->fontSize="10px";
		/* Date X-Axis */
		$rightYaxis = new HighchartOption();
		$rightYaxis->title->text = " ";
		$rightYaxis->title->style->color = "#00933b";
		$rightYaxis->labels->style->color = "#00933b";
		$rightYaxis->style->fontSize="10px";

		$rightYaxis->opposite = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*Total Loans */
		$loanaccounts = array();
		$count=0;
		foreach ($loans as $loan) { 
			if(isset($loan["loanAmount"])){ 
				$loanaccounts[$count] = array('y'=>$loan["loanAmount"]);  
			}else{ 
				$loanaccounts[$count] = 0;  
			}  
			$count++;
		}
		$chart->series[] = array('name'=>"Total Principal Outstanding",'color'=> "#00933b",'type'=>"column",'yAxis'=>1,'data'=>$loanaccounts);
		$chart->tooltip->pointFormat=new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package = '<div id="'.$chart_container.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart2");
    $package.= '</script>';
    echo $package;
	}

	public static function getPrincipalDueVersusCollectionsChart($start_date,$end_date,$chart_title,
		$chart_container){
		$content_array=Dashboard::getPrincipalDueVersusCollections($start_date,$end_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $chart_title;
		$chart->title->style->fontSize="12px";

		$axisarray = array();
		$count = 0;
		foreach ($content_array as $key) { $axisarray[$count] = array(date('d/m/Y', strtotime($key['day']))); $count++; }
		$chart->xAxis->categories = $axisarray;
		/* Recordings Y-Axis */
		$leftYaxis = new HighchartOption();
		$leftYaxis->title->text = " ";
		$leftYaxis->title->style->color = "#0266c8";
		$leftYaxis->labels->style->color = "#0266c8";
		/* Date X-Axis */
		$rightYaxis = new HighchartOption();
		$rightYaxis->title->text = " ";
		$rightYaxis->title->style->color = "#0266c8";
		$rightYaxis->labels->style->color = "#0266c8";

		$rightYaxis->opposite = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		
		/*Principal Due */
		$principals = array();
		$count = 0;
		foreach ($content_array as $PrincipalDue) { 
			if(isset($PrincipalDue["principalDues"])){
				$principals[$count] = array('y'=>$PrincipalDue["principalDues"]);  
			}else{ 
				$principals[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Principal Due",'color' => "#0266c8",'type' => "line",'yAxis'=>1, 'data' => $principals);
		/*Principal Collections */
		$collections = array();
		$count = 0;
		foreach ($content_array as $principalCollection) { 
			if(isset($principalCollection["principalCollections"])){
				$collections[$count] = array('y'=>$principalCollection["principalCollections"]);  
			}else{ 
				$collections[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Principal Collected",'color' => "#f2B50f",'type' => "line",'yAxis'=>1,'data' => $collections);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package = '<div id="'.$chart_container.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
    $package.= '</script>';
    echo $package;
	}

	public static function getInterestDueVersusCollectionsChart($start_date,$end_date,$chart_title,
		$chart_container){
		$content_array=Dashboard::getInterestDueVersusCollections($start_date,$end_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $chart_title;
		$chart->title->style->fontSize="12px";

		$axisarray = array();
		$count = 0;
		foreach ($content_array as $key) { $axisarray[$count] = array(date('d/m/Y', strtotime($key['day']))); $count++; }
		$chart->xAxis->categories = $axisarray;
		/* Recordings Y-Axis */
		$leftYaxis = new HighchartOption();
		$leftYaxis->title->text = " ";
		$leftYaxis->title->style->color = "#00933b";
		$leftYaxis->labels->style->color = "#00933b";
		/* Date X-Axis */
		$rightYaxis = new HighchartOption();
		$rightYaxis->title->text = " ";
		$rightYaxis->title->style->color = "#00933b";
		$rightYaxis->labels->style->color = "#00933b";

		$rightYaxis->opposite = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		
		/*Principal Due */
		$interests = array();
		$count = 0;
		foreach ($content_array as $PrincipalDue) { 
			if(isset($PrincipalDue["interestDues"])){
				$interests[$count] = array('y'=>$PrincipalDue["interestDues"]);  
			}else{ 
				$interests[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Interest Due",'color' => "#00933b",'type' => "column",'yAxis'=>1, 'data' => $interests);
		/*Interest Collections */
		$collections = array();
		$count = 0;
		foreach ($content_array as $interestCollection) { 
			if(isset($interestCollection["interestCollections"])){
				$collections[$count] = array('y'=>$interestCollection["interestCollections"]);  
			}else{ 
				$collections[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Interest Collected",'color' => "#f90101",'type' => "column",'yAxis'=>1,'data' => $collections);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package = '<div id="'.$chart_container.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
    $package.= '</script>';
    echo $package;
	}

	public static function getLoanCollectionsVersusloansReleasedChart($start_date,$end_date,$chart_title,
		$chart_container){
		$content_array=Dashboard::getLoanCollectionsVersusloansReleased($start_date,$end_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "line";
		$chart->title->text = $chart_title;
		$chart->title->style->fontSize="12px";

		$axisarray = array();
		$count = 0;
		foreach ($content_array as $key) { $axisarray[$count] = array(date('d/m/Y', strtotime($key['day']))); $count++; }
		$chart->xAxis->categories = $axisarray;
		/* Recordings Y-Axis */
		$leftYaxis = new HighchartOption();
		$leftYaxis->title->text = " ";
		$leftYaxis->title->style->color = "#0266c8";
		$leftYaxis->labels->style->color = "#0266c8";
		/* Date X-Axis */
		$rightYaxis = new HighchartOption();
		$rightYaxis->title->text = " ";
		$rightYaxis->title->style->color = "#0266c8";
		$rightYaxis->labels->style->color = "#0266c8";

		$rightYaxis->opposite = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*Loan Amount Released*/
		$releasedLoans = array();
		$count = 0;
		foreach ($content_array as $LoanReleased) { 
			if(isset($LoanReleased["loansReleased"])){
				$releasedLoans[$count] = array('y'=>$LoanReleased["loansReleased"]);  
			}else{ 
				$releasedLoans[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Loan Amount Released",'color' => "#0266c8",'type' => "column",'yAxis'=>1, 'data' => $releasedLoans);
		/*Loan Collections */
		$collections = array();
		$count = 0;
		foreach ($content_array as $loanCollection) { 
			if(isset($loanCollection["loanCollections"])){
				$collections[$count] = array('y'=>$loanCollection["loanCollections"]);  
			}else{ 
				$collections[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Loan Amount Collected",'color' => "#f90101",'type' => "column",'yAxis'=>1,'data' => $collections);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package = '<div id="'.$chart_container.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
    $package.= '</script>';
    echo $package;
	}

	public static function getFeesDueVersusCollectionsChart($start_date,$end_date,$chart_title,
		$chart_container){
		$content_array=Dashboard::getFeesDueVersusCollections($start_date,$end_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "line";
		$chart->title->text = $chart_title;
		$chart->title->style->fontSize="12px";

		$axisarray = array();
		$count = 0;
		foreach ($content_array as $key) { $axisarray[$count] = array(date('d/m/Y', strtotime($key['day']))); $count++; }
		$chart->xAxis->categories = $axisarray;
		/* Recordings Y-Axis */
		$leftYaxis = new HighchartOption();
		$leftYaxis->title->text = " ";
		$leftYaxis->title->style->color = "#0266c8";
		$leftYaxis->labels->style->color = "#0266c8";
		/* Date X-Axis */
		$rightYaxis = new HighchartOption();
		$rightYaxis->title->text = " ";
		$rightYaxis->title->style->color = "#0266c8";
		$rightYaxis->labels->style->color = "#0266c8";

		$rightYaxis->opposite = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*Fees Due*/
		$FeesDue = array();
		$count = 0;
		foreach ($content_array as $LoanDue) { 
			if(isset($LoanDue["feesDue"])){
				$FeesDue[$count] = array('y'=>$LoanDue["feesDue"]);  
			}else{ 
				$FeesDue[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Loan Fee Due",'color' => "#0266c8",'type' => "line",'yAxis'=>1, 'data' => $FeesDue);
		/*Fees Collections */
		$collections = array();
		$count = 0;
		foreach ($content_array as $feeCollection) { 
			if(isset($feeCollection["feesCollections"])){
				$collections[$count] = array('y'=>$feeCollection["feesCollections"]);  
			}else{ 
				$collections[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Fee Amount Collected",'color' => "#f90101",'type' => "line",'yAxis'=>1,'data' => $collections);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package = '<div id="'.$chart_container.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
    $package.= '</script>';
    echo $package;
	}

	public static function getLoanCollectionsVersusloansDueChart($start_date,$end_date,$chart_title,
		$chart_container){
		$content_array=Dashboard::getLoanCollectionsVersusloansDue($start_date,$end_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "line";
		$chart->title->text = $chart_title;
		$chart->title->style->fontSize="12px";

		$axisarray = array();
		$count = 0;
		foreach ($content_array as $key) { $axisarray[$count] = array(date('d/m/Y', strtotime($key['day']))); $count++; }
		$chart->xAxis->categories = $axisarray;
		/* Recordings Y-Axis */
		$leftYaxis = new HighchartOption();
		$leftYaxis->title->text = " ";
		$leftYaxis->title->style->color = "#0266c8";
		$leftYaxis->labels->style->color = "#0266c8";
		/* Date X-Axis */
		$rightYaxis = new HighchartOption();
		$rightYaxis->title->text = " ";
		$rightYaxis->title->style->color = "#0266c8";
		$rightYaxis->labels->style->color = "#0266c8";

		$rightYaxis->opposite = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*Loan Amount Due*/
		$dueLoans = array();
		$count = 0;
		foreach ($content_array as $LoanDue) { 
			if(isset($LoanDue["loansDue"])){
				$dueLoans[$count] = array('y'=>$LoanDue["loansDue"]);  
			}else{ 
				$dueLoans[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Loan Amount Due",'color' => "#0266c8",'type' => "line",'yAxis'=>1, 'data' => $dueLoans);
		/*Loan Collections */
		$collections = array();
		$count = 0;
		foreach ($content_array as $loanCollection) { 
			if(isset($loanCollection["loanCollections"])){
				$collections[$count] = array('y'=>$loanCollection["loanCollections"]);  
			}else{ 
				$collections[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Loan Amount Collected",'color' => "#f90101",'type' => "line",'yAxis'=>1,'data' => $collections);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package = '<div id="'.$chart_container.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
    $package.= '</script>';
    echo $package;
	}

	public static function getAverageInterestRateAllTime(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanQuery="SELECT * FROM loanaccounts WHERE loan_status NOT IN('3')";
		$totalLoanPrincipal=0;
		$totalLoanInterest=0;
		switch(Yii::app()->user->user_level){
			case '0':
			$loanQuery.=" ";
			break;

			case '1':
			$loanQuery.=" AND branch_id=$userBranch";
			break;

			case '2':
			$loanQuery.=" AND rm=$userID";
			break;

			case '3':
			$loanQuery.=" AND user_id=$userID";
			break;
		}

		$loans=Loanaccounts::model()->findAllBySql($loanQuery);
		foreach($loans as $loan){
			$totalLoanPrincipal+=LoanApplication::getLoanReleasedAmount($loan->loanaccount_id);
			$totalLoanInterest+=LoanTransactionsFunctions::getTotalInterestAmount($loan->loanaccount_id);
		}
		if($totalLoanPrincipal <= 0){
			$totalLoanPrincipal=1;
			$averageRate=($totalLoanInterest/$totalLoanPrincipal)*100;
		}else{
			$averageRate=($totalLoanInterest/$totalLoanPrincipal)*100;
		}
		return CommonFunctions::asMoney($averageRate).' %';
	}

	public static function getRateofRecoveryAllLoans(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanQuery="SELECT * FROM loanaccounts WHERE loan_status NOT IN('3')";
		$totalAmountDue=0;
		$totalAmountPaid=0;
		switch(Yii::app()->user->user_level){
			case '0':
			$loanQuery.=" ";
			break;

			case '1':
			$loanQuery.=" AND branch_id=$userBranch";
			break;

			case '2':
			$loanQuery.=" AND rm=$userID";
			break;

			case '3':
			$loanQuery.=" AND user_id=$userID";
			break;
		}

		$loanaccounts=Loanaccounts::model()->findAllBySql($loanQuery);
		foreach($loanaccounts as $loan){
			$totalAmountDue+=LoanTransactionsFunctions::getTotalLoanAmount($loan->loanaccount_id);
			$totalAmountPaid+=LoanRepayment::getTotalAmountPaid($loan->loanaccount_id);
		}
		if($totalAmountDue<= 0){
			$totalAmountDue=1;
			$averageRate=($totalAmountPaid/$totalAmountDue)*100;
		}else{
			$averageRate=($totalAmountPaid/$totalAmountDue)*100;
		}
		return CommonFunctions::asMoney($averageRate).' %';
	}

	public static function getRateofRecoveryOpenLoans(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$loanQuery="SELECT * FROM loanaccounts WHERE loan_status NOT IN('3','4')";
		$totalAmountDue=0;
		$totalAmountPaid=0;
		switch(Yii::app()->user->user_level){
			case '0':
			$loanQuery.=" ";
			break;

			case '1':
			$loanQuery.=" AND branch_id=$userBranch";
			break;

			case '2':
			$loanQuery.=" AND rm=$userID";
			break;

			case '3':
			$loanQuery.=" AND user_id=$userID";
			break;
		}

		$loanaccounts=Loanaccounts::model()->findAllBySql($loanQuery);
		foreach($loanaccounts as $loan){
			$totalAmountDue+=LoanTransactionsFunctions::getTotalLoanAmount($loan->loanaccount_id);
			$totalAmountPaid+=LoanRepayment::getTotalAmountPaid($loan->loanaccount_id);
		}
		if($totalAmountDue <= 0){
			$totalAmountDue=1;
			$averageRate=($totalAmountPaid/$totalAmountDue)*100;
		}else{
			$averageRate=($totalAmountPaid/$totalAmountDue)*100;
		}
		return CommonFunctions::asMoney($averageRate).' %';
	}

	public static function getPenaltyDueVersusPenaltyCollectionsChart($start_date,$end_date,$chart_title,
		$chart_container){
		$content_array=Dashboard::getPenaltyDueVersusPenaltyCollections($start_date,$end_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "line";
		$chart->title->text = $chart_title;
		$chart->title->style->fontSize="12px";

		$axisarray = array();
		$count = 0;
		foreach ($content_array as $key) { $axisarray[$count] = array(date('d/m/Y', strtotime($key['day']))); $count++; }
		$chart->xAxis->categories = $axisarray;
		/* Recordings Y-Axis */
		$leftYaxis = new HighchartOption();
		$leftYaxis->title->text = " ";
		$leftYaxis->title->style->color = "#0266c8";
		$leftYaxis->labels->style->color = "#0266c8";
		/* Date X-Axis */
		$rightYaxis = new HighchartOption();
		$rightYaxis->title->text = " ";
		$rightYaxis->title->style->color = "#0266c8";
		$rightYaxis->labels->style->color = "#0266c8";

		$rightYaxis->opposite = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*Penalty Due*/
		$PenaltyDueArray = array();
		$count = 0;
		foreach ($content_array as $PenaltyDue) { 
			if(isset($PenaltyDue["penaltyDues"])){
				$PenaltyDueArray[$count] = array('y'=>$PenaltyDue["penaltyDues"]);  
			}else{ 
				$PenaltyDueArray[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Penalty Amount Due",'color' => "#0266c8",'type' => "column",'yAxis'=>1, 'data' => $PenaltyDueArray);
		/*Penalty Collections */
		$PenaltyCollectionsArray = array();
		$count = 0;
		foreach ($content_array as $penaltyCollection) { 
			if(isset($penaltyCollection["penaltyCollections"])){
				$PenaltyCollectionsArray[$count] = array('y'=>$penaltyCollection["penaltyCollections"]);  
			}else{ 
				$PenaltyCollectionsArray[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Penalty Amount Collected",'color' => "#f90101",'type' => "column",'yAxis'=>1,'data' => $PenaltyCollectionsArray);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package = '<div id="'.$chart_container.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
    $package.= '</script>';
    echo $package;
	}

	public static function getLoanAccountsStatusPieChart($array,$title,$subtitle,$chartname){
		$container_name = $chartname;
		$chart = new Highchart();
		$chart->chart->renderTo = $chartname;
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->title->text = '';
		$chart->tooltip->formatter = new HighchartJsExpr("function() { return '<b>'+ this.point.name +'</b>'; }");
		$chart->plotOptions->pie->allowPointSelect = 1;
		$chart->plotOptions->pie->cursor = "pointer";
		$chart->plotOptions->pie->dataLabels->enabled = true;
		$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);
		$chartarray = array();
		$count = 0;
		$others = 0;
		foreach ($array as $key) {  
			switch($key['loanStatusName']){
					case '0':
					$statusName='Application Submitted';
					break;

					case '1':
					$statusName='Loan Approved';
					break;

					case '2':
					$statusName='Loan Disbursed';
					break;

					case '3':
					$statusName='Loan Rejected';
					break;

					case '4':
					$statusName='Loan Fully Paid';
					break;

					case '5':
					$statusName='Loan Restructured';
					break;

					case '6':
					$statusName='Loan Topped Up';
					break;

					case '7':
					$statusName='Loan Defaulted';
					break;
				}
			$chartarray[$count] = array( 
				$statusName. '<br>'.number_format($key['loanStatusCount']), 
				(int)str_replace(',', '', $key['loanStatusCount'])
			);  
			$count++; if($count>10){ break; }
		}
		$chart->series[] = array('type' => 'pie','name' => 'Loan Status','data' => $chartarray);
		$chart->credits = array('enabled'=>false);
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$package = '<div id="'.$container_name.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
    $package.= '</script>';
    echo $package;
	}

	public static function getBorrowersGenderStatusPieChart($array,$title,$subtitle,$chartname){
		$container_name = $chartname;
		$chart = new Highchart();
		$chart->chart->renderTo = $chartname;
		$chart->chart->plotBackgroundColor = null;
		$chart->chart->plotBorderWidth = null;
		$chart->chart->plotShadow = false;
		$chart->title->text = '';
		$chart->tooltip->formatter = new HighchartJsExpr("function() { return '<b>'+ this.point.name +'</b>'; }");
		$chart->plotOptions->pie->allowPointSelect = 1;
		$chart->plotOptions->pie->cursor = "pointer";
		$chart->plotOptions->pie->dataLabels->enabled = true;
		$chart->legend = array('layout' => 'vertical','align' => 'right','verticalAlign' => 'top','x' => - 10,'y' => 100,'borderWidth' => 0);
		$chart->title->style->fontSize="12px";
		$chartarray = array();
		$count = 0;
		foreach ($array as $key){
			switch($key['genderName']){
				case 'male':
				$genderFullName='Male';
				break;

				case 'female':
				$genderFullName='Female';
				break;

				default:
				$genderFullName="None";  
				break;
			}
			$chartarray[$count] = array( 
				$genderFullName. '<br>'.number_format($key['genderCount']), 
				(int)str_replace(',', '', $key['genderCount'])
			);  
			$count++; if($count>10){ break; }
		}
		$chart->series[] = array('type' => 'pie','name' => 'Active Male/Female','data' => $chartarray);
		$chart->credits = array('enabled'=>false);
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$package = '<div id="'.$container_name.'" style="height:38vh;"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
    $package.= '</script>';
    echo $package;
	}

	public static function getTotalFullyPaidLoansChart($start_date,$end_date,$chart_title,$chart_container){
		$loans=Dashboard::getTotalFullyPaidLoans($start_date,$end_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $chart_title;
		$chart->title->style->fontSize="12px";

		$axisarray = array();
		$count = 0;
		foreach ($loans as $key){
		  $axisarray[$count] = array(date('d/m/Y', strtotime($key['day'])));
		  $count++; 
		}
		$chart->xAxis->categories = $axisarray;
		$chart->xAxis->style->fontSize="10px";
		/* Loans Y-Axis */
		$leftYaxis = new HighchartOption();
		$leftYaxis->title->text = " ";
		$leftYaxis->title->style->color = "#f90101";
		$leftYaxis->labels->style->color = "#f90101";
		$leftYaxis->style->fontSize="10px";
		/* Date X-Axis */
		$rightYaxis = new HighchartOption();
		$rightYaxis->title->text = " ";
		$rightYaxis->title->style->color = "#f90101";
		$rightYaxis->labels->style->color = "#f90101";
		$rightYaxis->style->fontSize="10px";

		$rightYaxis->opposite = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*Total Loans */
		$loanaccounts = array();
		$count = 0;
		foreach ($loans as $loan) { 
			if(isset($loan["loans"])){ 
				$loanaccounts[$count] = array('y'=>$loan["loans"]);  
			}else{ 
				$loanaccounts[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name'=>"Loans Fully Paid",'color' => "#f90101",'type'=>"line",'yAxis'=>1,
			'data'=>$loanaccounts);
		$chart->tooltip->pointFormat=new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package = '<div id="'.$chart_container.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
    $package.= '</script>';
    echo $package;
	}

	public static function getRateProgressAllLoans(){
		$userBranch=Yii::app()->user->user_branch;
		$userID=Yii::app()->user->user_id;
		$transactQuery="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts WHERE loantransactions.is_void IN('0','3','4')
		 AND loanaccounts.loanaccount_id=loantransactions.loanaccount_id";
		switch(Yii::app()->user->user_level){
			case '0':
			$transactQuery.=" ";
			break;

			case '1':
			$transactQuery.=" AND loanaccounts.branch_id=$userBranch";
			break;

			case '2':
			$transactQuery.=" AND loanaccounts.rm=$userID";
			break;

			case '3':
			$transactQuery.=" AND loanaccounts.user_id=$userID";
			break;
		}

		$transactions=Loantransactions::model()->findBySql($transactQuery);
		if(!empty($transactions)){
			$totalAmountPaid=$transactions->amount;
			$disbursedSQL="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
			WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id";
			$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedSQL);
			if(!empty($disbursedLoans)){
				$principalReleased=$disbursedLoans->amount_disbursed;
				if($principalReleased <= 0){
					$progressRate=0;
					return number_format($progressRate,2);
				}else{
					$progressRate=$totalAmountPaid/$principalReleased;
					$percentProgress=$progressRate * 100;
					return number_format($percentProgress,2);
				}
			}else{
				$progressRate=0;
				return number_format($progressRate,2);
			}
		}else{
			$progressRate=0;
			return number_format($progressRate,2);
		}
	}

	public static function getRateProgressOpenLoans(){
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			$transactionsSql="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts
			 WHERE loantransactions.is_void IN('0','3','4') AND loantransactions.loanaccount_id=loanaccounts.loanaccount_id
			  AND loanaccounts.loan_status NOT IN('0','1','3','4','8','9','10')";
			$transactions=Loantransactions::model()->findBySql($transactionsSql);
			if(!empty($transactions)){
				$totalAmountPaid=$transactions->amount;
				$disbursedSQL="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
				 WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.loan_status NOT IN('0','1','3','4','8','9','10')";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedSQL);
				if(!empty($disbursedLoans)){
					$principalReleased=$disbursedLoans->amount_disbursed;
					if($principalReleased <= 0){
						$progressRate=0;
						return number_format($progressRate,2);
					}else{
						$progressRate=$totalAmountPaid/$principalReleased;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}
				}else{
					$progressRate=0;
					return number_format($progressRate,2);
				}
			}else{
				$progressRate=0;
				return number_format($progressRate,2);
			}
			break;

			case '1':
			$transactionsSql="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts
			WHERE loantransactions.is_void IN('0','3','4') AND loanaccounts.loanaccount_id=loantransactions.loanaccount_id
			AND loanaacounts.branch_id=$userBranch AND loanaccounts.loan_status NOT IN('0','1','3','4','8','9','10')";
			$transactions=Loantransactions::model()->findBySql($transactionsSql);
			if(!empty($transactions)){
				$totalAmountPaid=$transactions->amount;
				$disbursedSQL="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
				WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND loanaccounts.branch_id=$userBranch 
				AND loanaccounts.loan_status NOT IN('0','1','3','4','8','9','10')";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedSQL);
				if(!empty($disbursedLoans)){
					$principalReleased=$disbursedLoans->amount_disbursed;
					if($principalReleased <= 0){
						$progressRate=0;
						return number_format($progressRate,2);
					}else{
						$progressRate=$totalAmountPaid/$principalReleased;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}
				}else{
					$progressRate=0;
					$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
				}
			}else{
				$progressRate=0;
				$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
			}
			break;

			case '2':
			$transactionsSql="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts
			WHERE loantransactions.is_void IN('0','3','4') AND loanaccounts.loanaccount_id=loantransactions.loanaccount_id
			AND loanaccounts.branch_id=$userBranch AND loanaccounts.loan_status NOT IN('0','1','3','4','8','9','10')";
			$transactions=Loantransactions::model()->findBySql($transactionsSql);
			if(!empty($transactions)){
				$totalAmountPaid=$transactions->amount;
				$disbursedSQL="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
				WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND loanaccounts.branch_id=$userBranch
				 AND loanaccounts.loan_status NOT IN('0','1','3','4','8','9','10')";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedSQL);
				if(!empty($disbursedLoans)){
					$principalReleased=$disbursedLoans->amount_disbursed;
					if($principalReleased <= 0){
						$progressRate=0;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}else{
						$progressRate=$totalAmountPaid/$principalReleased;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}
				}else{
					$progressRate=0;
					$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
				}
			}else{
				$progressRate=0;
				$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
			}
			break;

			case '3':
			$userID=Yii::app()->user->user_id;
			$transactionsSql="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts
			 WHERE loantransactions.is_void IN('0','3','4') AND loanaccounts.loanaccount_id=loantransactions.loanaccount_id
			  AND loanaccounts.user_id=$userID  AND loanaccounts.loan_status NOT IN('0','1','3','4','8','9','10')";
			$transactions=Loantransactions::model()->findBySql($transactionsSql);
			if(!empty($transactions)){
				$totalAmountPaid=$transactions->amount;
				$disbursedSQL="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
				WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND loanaccounts.user_id=$userID
				AND loanaccounts.loan_status NOT IN('0','1','3','4','8','9','10')";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedSQL);
				if(!empty($disbursedLoans)){
					$principalReleased=$disbursedLoans->amount_disbursed;
					if($principalReleased <= 0){
						$progressRate=0;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}else{
						$progressRate=$totalAmountPaid/$principalReleased;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}
				}else{
					$progressRate=0;
					$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
				}
			}else{
				$progressRate=0;
				$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
			}
			break;
		}
	}

	public static function getRateProgressFullyPaidLoans(){
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			$transactionsSql="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts
			WHERE loantransactions.is_void IN('0','3','4') AND loantransactions.loanaccount_id=loanaccounts.loanaccount_id
			AND loanaccounts.loan_status IN('4')";
			$transactions=Loantransactions::model()->findBySql($transactionsSql);
			if(!empty($transactions)){
				$totalAmountPaid=$transactions->amount;
				$disbursedSQL="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
				 WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.loan_status IN('0','1','3','4')";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedSQL);
				if(!empty($disbursedLoans)){
					$principalReleased=$disbursedLoans->amount_disbursed;
					if($principalReleased <= 0){
						$progressRate=0;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}else{
						$progressRate=$totalAmountPaid/$principalReleased;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}
				}else{
					$progressRate=0;
					$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
				}
			}else{
				$progressRate=0;
				$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
			}
			break;

			case '1':
			$transactionsSql="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts
			 WHERE loantransactions.is_void IN('0','3','4') AND loanaccounts.loanaccount_id=loantransactions.loanaccount_id
			  AND loanaccounts.branch_id=$userBranch AND loanaccounts.loan_status IN('0','1','3','4','8','9','10')";
			$transactions=Loantransactions::model()->findBySql($transactionsSql);
			if(!empty($transactions)){
				$totalAmountPaid=$transactions->amount;
				$disbursedSQL="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
				 WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND users.branch_id=$userBranch
				  AND loanaccounts.loan_status IN('0','1','3','4','8','9','10')";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedSQL);
				if(!empty($disbursedLoans)){
					$principalReleased=$disbursedLoans->amount_disbursed;
					if($principalReleased <= 0){
						$progressRate=0;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}else{
						$progressRate=$totalAmountPaid/$principalReleased;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}
				}else{
					$progressRate=0;
					$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
				}
			}else{
				$progressRate=0;
				$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
			}
			break;

			case '2':
			$transactionsSql="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts
			WHERE loantransactions.is_void IN('0','3','4') AND loanaccounts.loanaccount_id=loantransactions.loanaccount_id
			 AND loanaccounts.branch_id=$userBranch AND loanaccounts.loan_status IN('0','1','3','4','8','9','10')";
			$transactions=Loantransactions::model()->findBySql($transactionsSql);
			if(!empty($transactions)){
				$totalAmountPaid=$transactions->amount;
				$disbursedSQL="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
				WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND loanaccounts.branch_id=$userBranch 
				AND loanaccounts.loan_status IN('0','1','3','4','8','9','10')";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedSQL);
				if(!empty($disbursedLoans)){
					$principalReleased=$disbursedLoans->amount_disbursed;
					if($principalReleased <= 0){
						$progressRate=0;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}else{
						$progressRate=$totalAmountPaid/$principalReleased;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}
				}else{
					$progressRate=0;
					$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
				}
			}else{
				$progressRate=0;
				$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
			}
			break;

			case '3':
			$userID=Yii::app()->user->user_id;
			$transactionsSql="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts WHERE
			 loantransactions.is_void IN('0','3','4') AND loanaccounts.loanaccount_id=loantransactions.loanaccount_id
			  AND loanaccounts.user_id=$userID  AND loanaccounts.loan_status IN('0','1','3','4','8','9','10')";
			$transactions=Loantransactions::model()->findBySql($transactionsSql);
			if(!empty($transactions)){
				$totalAmountPaid=$transactions->amount;
				$disbursedSQL="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
				 WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND loanaccounts.user_id=$userID
				 AND loanaccounts.loan_status IN('0','1','3','4','8','9','10')";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedSQL);
				if(!empty($disbursedLoans)){
					$principalReleased=$disbursedLoans->amount_disbursed;
					if($principalReleased <= 0){
						$progressRate=0;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}else{
						$progressRate=$totalAmountPaid/$principalReleased;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}
				}else{
					$progressRate=0;
					$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
				}
			}else{
				$progressRate=0;
				$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
			}
			break;
		}
	}

	public static function getRateProgressDefaultLoans(){
		$userBranch=Yii::app()->user->user_branch;
		switch(Yii::app()->user->user_level){
			case '0':
			$transactionsSql="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts WHERE
			loantransactions.is_void IN('0','3','4') AND loantransactions.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.loan_status IN('7')";
			$transactions=Loantransactions::model()->findBySql($transactionsSql);
			if(!empty($transactions)){
				$totalAmountPaid=$transactions->amount;
				$disbursedSQL="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
				 WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND loanaccounts.loan_status IN('7')";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedSQL);
				if(!empty($disbursedLoans)){
					$principalReleased=$disbursedLoans->amount_disbursed;
					if($principalReleased <= 0){
						$progressRate=0;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}else{
						$progressRate=$totalAmountPaid/$principalReleased;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}
				}else{
					$progressRate=0;
					$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
				}
			}else{
				$progressRate=0;
				$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
			}
			break;

			case '1':
			$transactionsSql="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts
			 WHERE loantransactions.is_void IN('0','3','4') AND loanaccounts.loanaccount_id=loantransactions.loanaccount_id 
			AND loanaccounts.branch_id=$userBranch AND loanaccounts.loan_status IN('7')";
			$transactions=Loantransactions::model()->findBySql($transactionsSql);
			if(!empty($transactions)){
				$totalAmountPaid=$transactions->amount;
				$disbursedSQL="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
				 WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND loanaccounts.user_id=users.user_id AND
				  loanaccounts.branch_id=$userBranch AND loanaccounts.loan_status IN('7')";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedSQL);
				if(!empty($disbursedLoans)){
					$principalReleased=$disbursedLoans->amount_disbursed;
					if($principalReleased <= 0){
						$progressRate=0;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}else{
						$progressRate=$totalAmountPaid/$principalReleased;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}
				}else{
					$progressRate=0;
					$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
				}
			}else{
				$progressRate=0;
				$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
			}
			break;

			case '2':
			$transactionsSql="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts
			 WHERE loantransactions.is_void IN('0','3','4') AND loanaccounts.loanaccount_id=loantransactions.loanaccount_id AND
			  loanaccounts.branch_id=$userBranch AND loanaccounts.loan_status IN('7')";
			$transactions=Loantransactions::model()->findBySql($transactionsSql);
			if(!empty($transactions)){
				$totalAmountPaid=$transactions->amount;
				$disbursedSQL="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
				WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND loanaccounts.branch_id=$userBranch AND loanaccounts.loan_status IN('7')";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedSQL);
				if(!empty($disbursedLoans)){
					$principalReleased=$disbursedLoans->amount_disbursed;
					if($principalReleased <= 0){
						$progressRate=0;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}else{
						$progressRate=$totalAmountPaid/$principalReleased;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}
				}else{
					$progressRate=0;
					$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
				}
			}else{
				$progressRate=0;
				$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
			}
			break;

			case '3':
			$userID=Yii::app()->user->user_id;
			$transactionsSql="SELECT SUM(loantransactions.amount) as amount FROM loantransactions,loanaccounts
			 WHERE loantransactions.is_void IN('0','3','4') AND loanaccounts.loanaccount_id=loantransactions.loanaccount_id
			  AND loanaccounts.user_id=$userID  AND loanaccounts.loan_status IN('7')";
			$transactions=Loantransactions::model()->findBySql($transactionsSql);
			if(!empty($transactions)){
				$totalAmountPaid=$transactions->amount;
				$disbursedSQL="SELECT SUM(disbursed_loans.amount_disbursed) as amount_disbursed FROM disbursed_loans,loanaccounts
				 WHERE loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND loanaccounts.user_id=$userID  AND loanaccounts.loan_status IN('7')";
				$disbursedLoans=DisbursedLoans::model()->findBySql($disbursedSQL);
				if(!empty($disbursedLoans)){
					$principalReleased=$disbursedLoans->amount_disbursed;
					if($principalReleased <= 0){
						$progressRate=0;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}else{
						$progressRate=$totalAmountPaid/$principalReleased;
						$percentProgress=$progressRate * 100;
						return number_format($percentProgress,2);
					}
				}else{
					$progressRate=0;
					$percentProgress=$progressRate * 100;
					return number_format($percentProgress,2);
				}
			}else{
				$progressRate=0;
				$percentProgress=$progressRate * 100;
				return number_format($percentProgress,2);
			}
			break;
		}
	}
}