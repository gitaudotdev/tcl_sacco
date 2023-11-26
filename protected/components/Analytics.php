<?php

class Analytics{

	public static function getCustomerGrowthTabulation($branch,$staff,$start_date,$end_date){
		$customers    = Analytics::getCustomerTotals($branch,$staff,$start_date,$end_date);
		$openingCount = Analytics::getOpeningCustomerCount($branch,$staff,$start_date);
		$openingLoans = Analytics::getOpeningActiveLoanAccounts($branch,$staff,$start_date);
		return Analytics::generateCustomerGrowthTabulation($customers,$openingCount,$openingLoans,$branch,$staff);
	}

	public static function getCustomerTotals($branch,$staff,$start_date,$end_date){
		$growthQuery  = "SELECT DATE_FORMAT(createdAt, '%Y-%m') AS registrationDate, COUNT(DISTINCT id) AS totalMembers FROM profiles
		WHERE (DATE(createdAt) BETWEEN '$start_date' AND '$end_date') AND profileType IN('MEMBER')";
		switch($branch){
			case 0:
			$growthQuery .= $staff ==0 ? "" : " AND managerId=$staff";
			break;

			default:
			$growthQuery .= " AND branchId=$branch";
			$growthQuery .= $staff ==0 ? "" : " AND managerId=$staff";
			break;
		}
		$growthQuery .= " GROUP BY registrationDate ORDER BY registrationDate ASC";
		return  Yii::app()->db->createCommand($growthQuery)->queryAll();
	}

	public static function getOpeningCustomerCount($branch,$staff,$start_date){
		$customerQuery = "SELECT COUNT(DISTINCT id) AS totalCustomers FROM profiles
		WHERE DATE(createdAt) < '$start_date' AND profileType IN('MEMBER')";
		switch($branch){
			case 0:
			$customerQuery.= $staff === 0 ? "" :  " AND managerId=$staff";
			break;

			default:
			$customerQuery.= " AND branchId=$branch";
			$customerQuery.= $staff === 0 ? "" :  " AND managerId=$staff";
			break;
		}
		$customers  = Yii::app()->db->createCommand($customerQuery)->queryRow();
		return !empty($customers) ? (int)$customers['totalCustomers'] : 0;
	}
	
	public static function getOpeningActiveLoanAccounts($branch,$staff,$start_date){
		$accountsQuery = "SELECT COUNT(loanaccount_id) AS totalLoans FROM loanaccounts WHERE
		loan_status IN('2','5','6','7') AND DATE(created_at) <'$start_date'";
		switch($branch){
			case 0:
			$accountsQuery.= $staff === 0 ? "" : " AND rm=$staff";
			break;

			default:
			$accountsQuery.= "   AND branch_id=$branch";
			$accountsQuery.= $staff === 0 ? "" : " AND rm=$staff";
			break;
		}
		$loans  = Yii::app()->db->createCommand($accountsQuery)->queryRow();
		return !empty($loans) ? (int)$loans['totalLoans'] : 0;
	}

	public static function getPeriodActiveLoans($monthPeriod,$branch,$staff){
		$accountsQuery = "SELECT COUNT(DISTINCT loanaccount_id) AS totalLoans FROM loanaccounts WHERE
		loan_status IN('2','5','6','7')AND DATE_FORMAT(created_at,'%Y-%m')='$monthPeriod'";
		switch($branch){
			case 0:
			$accountsQuery.= $staff === 0 ? "" : " AND rm=$staff";
			break;

			default:
			$accountsQuery.= " AND branch_id=$branch";
			$accountsQuery.= $staff === 0 ? "" : " AND rm=$staff";
			break;
		}
		$loans = Yii::app()->db->createCommand($accountsQuery)->queryRow();
		return !empty($loans) ?  (int)$loans['totalLoans'] : 0;
	}

	public static function generateCustomerGrowthTabulation($customers,$openingCount,$openLoans,$branch,$stff){
		$html          = "";
		$months        = "";
		$monthTotals   = "";
		$activeLoans   = "";
		$membersChange = "";
		if(!empty($customers)){
			$totals = array();
			foreach($customers as $customer){
				$currentActive     = Analytics::getPeriodActiveLoans($customer['registrationDate'],$branch,$stff);
				$regMonthDate      = explode('-',$customer['registrationDate']);
				$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
				$totalMembersCount = (int)$customer['totalMembers']; 
				$openingCount     += $totalMembersCount;
				$openLoans        += $currentActive;
				array_push($totals,$openingCount);
				$totalInserted     = count($totals);
				if($totalInserted > 0){
					if($totalInserted > 1){
						$membersDiff   = $totals[$totalInserted - 1] - $totals[$totalInserted - 2];
					}else{
						$membersDiff   = $totals[0] - $openingCount;
					}
				}else{
					$membersDiff     = 0;
				}
				$months           .= "<td class='text-primary'>$registrationDate</td>";
				$monthTotals      .= "<td>$openingCount</td>"; 
				$activeLoans      .= "<td>$openLoans</td>"; 
				$membersChange    .= "<td>$membersDiff</td>";
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>MEMBERS</td>$monthTotals</tr>";
			$html.= "<tr><td class='text-primary'>ACTIVE LOAN ACC</td>$activeLoans</tr>";
			$html.= "<tr><td class='text-primary'>CHNG IN MEMBERS</td>$membersChange</tr>";
			$html.= "</tbody></table></div>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getCustomerGrowthBarGraph($openingCount,$customers,$chart_title,$chart_container){
		$chart = new Highchart();
		$chart->chart->renderTo = $chart_container;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $chart_title; 
		$chart->title->style->fontSize = "15px";

		$axisarray = array();
		$count = 0;
		foreach($customers as $customer){
			$regMonthDate      = explode('-',$customer['registrationDate']);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
			$axisarray[$count] = array($registrationDate);
			$count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Number of Customers";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		$members = array();
		$count=0;
		foreach ($customers as $client) { 
			$totalMembersCount = (float)$client["totalMembers"];
			$openingCount     += $totalMembersCount;
			$members[$count]   = array('y'=>$openingCount);   
			$count++;
		}
		$chart->series[] = array('name'=>"Total Members ",'color'=> "#ff7f00",'type'=>"column",'yAxis'=>1,'data'=>$members);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$chart_container.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}
	/******

		SAVINGS

	*************/
	public static function getSavingsTrendTabulation($branch,$staff,$start_date,$end_date){
		$savings                = Analytics::getSavingTotals($branch,$staff,$start_date,$end_date);
		$openingSavingBalance   = Analytics::getOpeningSavingTotal($branch,$staff,$start_date);
		$openingPostedInterests = Analytics::getOpeningSavingInterests($branch,$staff,$start_date);
		return Analytics::generateSavingsTabulation($savings,$openingSavingBalance,$openingPostedInterests,$branch,$staff);
	}

	public static function getSavingTotals($branch,$staff,$start_date,$end_date){
		$depositQuery = "SELECT DATE_FORMAT(savingtransactions.transacted_at, '%Y-%m') AS registrationDate, COALESCE(SUM(savingtransactions.amount),0) AS totalSavings FROM savingtransactions,savingaccounts,profiles
		WHERE savingaccounts.user_id=profiles.id AND savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND savingtransactions.type='credit' AND savingtransactions.is_void IN('0')
		AND (DATE(savingtransactions.transacted_at) BETWEEN '$start_date' AND '$end_date')";
		switch($branch){
			case 0:
			$depositQuery.= $staff === 0 ? "" : " AND profiles.managerId=$staff";
			break;

			default:
			$depositQuery.= " AND profiles.branchId=$branch";
			$depositQuery.= $staff === 0 ? "" : " AND profiles.managerId=$staff";
			break;
		}
		$depositQuery .= " GROUP BY registrationDate ORDER BY registrationDate ASC";
		return Yii::app()->db->createCommand($depositQuery)->queryAll();
	}

	public static function getSavingTotalsWithdrawals($monthPeriod,$branch,$staff){
		$depositQuery = "SELECT COALESCE(SUM(savingtransactions.amount),0) AS totalSavings FROM savingtransactions,savingaccounts,profiles
		WHERE savingaccounts.user_id=profiles.id AND savingtransactions.savingaccount_id=savingaccounts.savingaccount_id
		AND savingtransactions.type='debit' AND savingtransactions.is_void IN('0') AND DATE_FORMAT(savingtransactions.transacted_at, '%Y-%m')='$monthPeriod'";
		switch($branch){
			case 0:
			if($staff === 0){
				$depositQuery.= "";
			}else{
				$depositQuery.= " AND profiles.managerId=$staff";
			}
			break;

			default:
			$depositQuery.= " AND profiles.branchId=$branch";
			if($staff === 0){
				$depositQuery.= "";
			}else{
				$depositQuery.= " AND profiles.managerId=$staff";
			}
			break;
		}
		$deposits  = Yii::app()->db->createCommand($depositQuery)->queryRow();
    	return !empty($deposits) ?  floatval($deposits['totalSavings']) : 0;
	}

	public static function getOpeningSavingTotal($branch,$staff,$start_date){
		$depositQuery = "SELECT COALESCE(SUM(savingtransactions.amount),0) AS totalSavings FROM savingtransactions,savingaccounts,profiles
		 WHERE savingaccounts.user_id=profiles.id AND savingtransactions.type='credit' AND savingtransactions.savingaccount_id=savingaccounts.savingaccount_id
		 AND savingtransactions.is_void IN('0') AND DATE(savingtransactions.transacted_at) < '$start_date'";
		switch($branch){
			case 0:
			if($staff === 0){
				$depositQuery.= "";
			}else{
				$depositQuery.= " AND profiles.managerId=$staff";
			}
			break;

			default:
			$depositQuery.= " AND profiles.branchId=$branch";
			if($staff === 0){
				$depositQuery.= "";
			}else{
				$depositQuery.= " AND profiles.managerId=$staff";
			}
			break;
		}
		$deposits      = Yii::app()->db->createCommand($depositQuery)->queryRow();
		return !empty($deposits)? floatval($deposits['totalSavings']) : 0;
	}

	public static function getOpeningSavingInterests($branch,$staff,$start_date){
		$transactQuery="SELECT COALESCE(SUM(savingpostings.posted_interest),0) as totalSavings FROM savingpostings,savingtransactions,savingaccounts
		WHERE savingtransactions.savingtransaction_id=savingpostings.savingtransaction_id AND savingtransactions.savingaccount_id=savingaccounts.savingaccount_id
		 AND savingpostings.is_void IN('0') AND DATE(savingpostings.posted_at) < '$start_date' AND savingpostings.type='credit'";
		switch($branch){
			case 0:
			if($staff === 0){
				$transactQuery.= "";
			}else{
				$transactQuery.= " AND savingaccounts.rm=$staff";
			}
			break;

			default:
			$transactQuery.= " AND savingaccounts.branch_id=$branch";
			if($staff === 0){
				$transactQuery.= "";
			}else{
				$transactQuery.= " AND savingaccounts.rm=$staff";
			}
			break;
		}
		$transactions = Yii::app()->db->createCommand($transactQuery)->queryRow();
		return !empty($transactions)? floatval($transactions['totalSavings']) : 0;
	}

	public static function getPeriodPostedInterests($monthPeriod,$branch,$staff){
		$transactQuery="SELECT COALESCE(SUM(savingpostings.posted_interest),0) as totalSavings FROM savingpostings,savingtransactions,savingaccounts
		WHERE savingtransactions.savingtransaction_id=savingpostings.savingtransaction_id AND savingpostings.is_void='0'
		AND savingtransactions.savingaccount_id=savingaccounts.savingaccount_id AND DATE_FORMAT(savingpostings.posted_at,'%Y-%m')='$monthPeriod'
		AND savingpostings.type='credit'";
		switch($branch){
			case 0:
			if($staff === 0){
				$transactQuery.= "";
			}else{
				$transactQuery.= " AND savingaccounts.rm=$staff";
			}
			break;

			default:
			$transactQuery.= " AND savingaccounts.branch_id=$branch";
			if($staff === 0){
				$transactQuery.= "";
			}else{
				$transactQuery.= " AND savingaccounts.rm=$staff";
			}
			break;
		}
		$savings  = Yii::app()->db->createCommand($transactQuery)->queryRow();
		return !empty($savings) ?  floatval($savings['totalSavings']) : 0;
	}

	public static function generateSavingsTabulation($savings,$openingSavingBalance,$openingPostedInterests,$branch,$staff){
		$html          = "";
		$months        = "";
		$monthSavingsTotals   = "";
		$postedSavingsInterests   = "";
		$savingsChange         = "";
		if(!empty($savings)){
			$totals = array();
			foreach($savings as $saving){
				$rawDate           = $saving['registrationDate'];
				$currentBalance    = Analytics::getPeriodPostedInterests($rawDate,$branch,$staff);
				$regMonthDate      = explode('-',$rawDate);
				$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
				$withdrawals       = Analytics::getSavingTotalsWithdrawals($rawDate,$branch,$staff);
				$deposits          = (float)$saving['totalSavings'];
				$totalAmountsaved  = (float)($deposits - $withdrawals); 
				$openingSavingBalance     += $totalAmountsaved;
				$openingPostedInterests   += $currentBalance;
				array_push($totals,$openingSavingBalance);
				$totalInserted     = count($totals);
				if($totalInserted > 0){
					if($totalInserted > 1){
						$savingsAmountChange   = $totals[$totalInserted - 1] - $totals[$totalInserted - 2];
					}else{
						$savingsAmountChange   = $totals[0] - $openingSavingBalance;
					}
				}else{
					$savingsAmountChange     = 0;
				}
				$months                  .= "<td class='text-primary'>$registrationDate</td>";
				$monthSavingsTotals      .= "<td>".number_format($openingSavingBalance,2)."</td>"; 
				$postedSavingsInterests  .= "<td>".number_format($openingPostedInterests,2)."</td>"; 
				$savingsChange           .= "<td>".number_format($savingsAmountChange,2)."</td>";
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>SAVINGS</td>$monthSavingsTotals</tr>";
			$html.= "<tr><td class='text-primary'>INTEREST EARNED</td>$postedSavingsInterests</tr>";
			$html.= "<tr><td class='text-primary'>CHNG IN SAVINGS</td>$savingsChange</tr>";
			$html.= "</tbody></table></div>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getSavingsGrowthStats($savings,$branch,$staff){
		$statistics = array();
		$data_count = 0;
		foreach($savings as $disburse){
			$rawDate           = $disburse['registrationDate'];
			$regMonthDate      = explode('-',$rawDate);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
			$withdrawals       = Analytics::getSavingTotalsWithdrawals($rawDate,$branch,$staff);
			$deposits          = (float)$disburse['totalSavings'];
			$totalAmountsaved  = (float)($deposits - $withdrawals); 
			$statistics[$data_count]['dateRegistered'] = $registrationDate;
			$statistics[$data_count]['totalSavings']   = $totalAmountsaved;
			$data_count++;
		}
		return $statistics;
	}

	public static function getSavingsGrowthLineGraph($openingSavingsBalance,$savings,$cTitle,$cHolder,$branch,$staff){
		$stats = Analytics::getSavingsGrowthStats($savings,$branch,$staff);
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";

		$axisarray = array();
		$count = 0;
		foreach($stats as $saving){
		  $axisarray[$count] = array($saving['dateRegistered']);
		  $count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Total Savings";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		$savedAmounts = array();
		$count=0;
		foreach ($stats as $saving) { 
			$totalSavings               = (float)$saving["totalSavings"];
			$openingSavingsBalance     += $totalSavings;
			$savedAmounts[$count]       = array('y'=>$openingSavingsBalance);   
			$count++;
		}
		$chart->series[] = array('name'=>"Total Savings",'color'=> "#ff7f00",'type'=>"line",'yAxis'=>1,'data'=>$savedAmounts);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$cHolder.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}

	/***********

		TURNOVERS

	****************/
	public static function getTurnoversTrendTabulation($branch,$staff,$start_date,$end_date){
		$turnovers = Analytics::getTurnOverTotals($branch,$staff,$start_date,$end_date);
    	return Analytics::generateTurnoversTabulation($turnovers,$branch,$staff);
	}

	public static function getTurnOverTotals($branch,$staff,$start_date,$end_date){
		$transactionQuery="SELECT DATE_FORMAT(loantransactions.transacted_at, '%Y-%m') AS registrationDate,COALESCE(SUM(loantransactions.amount),0) as totalPayments
		 FROM loantransactions,loanaccounts WHERE loantransactions.loanaccount_id=loanaccounts.loanaccount_id AND loantransactions.is_void IN('0','3','4') AND (DATE(loantransactions.transacted_at)
		 BETWEEN '$start_date' AND '$end_date')";
		switch($branch){
			case 0:
			if($staff === 0){
				$transactionQuery.= "";
			}else{
				$transactionQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$transactionQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$transactionQuery.= "";
			}else{
				$transactionQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$transactionQuery .= " GROUP BY registrationDate ORDER BY registrationDate ASC";
		return Yii::app()->db->createCommand($transactionQuery)->queryAll();
	}

	public static function generateTurnoversTabulation($turnovers,$branch,$staff){
		$html                = "";
		$months              = "";
		$monthTotalTurnovers = "";
		$turnoversChange     = "";
		if(!empty($turnovers)){
			$totals = array();
			foreach($turnovers as $turnover){
				$regMonthDate      = explode('-',$turnover['registrationDate']);
				$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
				$totalTurnOver     = (float)$turnover['totalPayments']; 
				array_push($totals,$totalTurnOver);
				$totalInserted     = count($totals);
				if($totalInserted > 0){
					if($totalInserted > 1){
						$turnoversAmountChange   = $totals[$totalInserted - 1] - $totals[$totalInserted - 2];
					}else{
						$turnoversAmountChange   = $totals[0] - $totalTurnOver;
					}
				}else{
					$turnoversAmountChange     = 0;
				}
				$months                  .= "<td class='text-primary'>$registrationDate</td>";
				$monthTotalTurnovers     .= "<td>".number_format($totalTurnOver,2)."</td>"; 
				$turnoversChange         .= "<td>".number_format($turnoversAmountChange,2)."</td>";
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>TURNOVERS</td>$monthTotalTurnovers</tr>";
			$html.= "<tr><td class='text-primary'>CHNG IN TNVS</td>$turnoversChange</tr>";
			$html.= "</tbody></table></div>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getTurnoversGrowthLineGraph($turnovers,$cTitle,$cHolder){
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";

		$axisarray = array();
		$count = 0;
		foreach($turnovers as $turnover){
			$regMonthDate      = explode('-',$turnover['registrationDate']);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
			$axisarray[$count] = array($registrationDate);
			$count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Total Turnover";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis->min = 0;
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		$payments = array();
		$count=0;
		foreach ($turnovers as $turnover) { 
			$totalPayments          = (float)$turnover["totalPayments"];
			$payments[$count]       = array('y'=>$totalPayments);   
			$count++;
		}
		$chart->series[] = array('name'=>"Total Turnover",'color'=> "#ff7f00",'type'=>"line",'yAxis'=>1,'data'=>$payments);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$cHolder.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}

	/*******************

		DISBURSED VS PAID LOANS

	*****************************/
	public static function getDisbursedVsPaidLoansTrendTabulation($branch,$staff,$start_date,$end_date){
		$disbursed = Analytics::getDisbursedTotals($branch,$staff,$start_date,$end_date);
   		return Analytics::generateDisbursedVsPaidLoansTabulation($disbursed,$branch,$staff);
	}

	public static function getDisbursedTotals($branch,$staff,$start_date,$end_date){
		$disburseQuery = "SELECT DATE_FORMAT(disbursed_loans.disbursed_at, '%Y-%m') AS registrationDate,
		COALESCE(SUM(disbursed_loans.amount_disbursed),0) as totalDisbursed FROM disbursed_loans,loanaccounts
		 WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$start_date' AND '$end_date')";
		switch($branch){
			case 0:
			if($staff === 0){
				$disburseQuery.= "";
			}else{
				$disburseQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$disburseQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$disburseQuery.= "";
			}else{
				$disburseQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$disburseQuery .= " GROUP BY registrationDate ORDER BY registrationDate ASC";
		return  Yii::app()->db->createCommand($disburseQuery)->queryAll();
	}

	public static function generateDisbursedVsPaidLoansTabulation($disbursed,$branch,$staff){
		$html                  = "";
		$months                = "";
		$monthlyTotalDisbursed = "";
		$periodTotalPaid       = "";
		if(!empty($disbursed)){
			$totals = array();
			foreach($disbursed as $disburse){
				$regMonthDate      = explode('-',$disburse['registrationDate']);
				$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
				$totalDisbursed    = (float)$disburse['totalDisbursed']; 
				$totalPaid         = (float)Analytics::getTotalPeriodPaid($disburse['registrationDate'],$branch,$staff);
				$months                  .= "<td class='text-primary'>$registrationDate</td>";
				$monthlyTotalDisbursed   .= "<td>".number_format($totalDisbursed,2)."</td>"; 
				$periodTotalPaid         .= "<td>".number_format($totalPaid,2)."</td>";
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>DISBURSED</td>$monthlyTotalDisbursed</tr>";
			$html.= "<tr><td class='text-primary'>PAID</td>$periodTotalPaid</tr>";
			$html.= "</tbody></table></div>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getTotalPeriodPaid($monthPeriod,$branch,$staff){
		$transactionQuery="SELECT COALESCE(SUM(loantransactions.amount),0) as totalPayments FROM loantransactions,loanaccounts
		WHERE loantransactions.loanaccount_id=loanaccounts.loanaccount_id AND loantransactions.is_void IN('0','3','4') AND DATE_FORMAT(loantransactions.transacted_at, '%Y-%m')='$monthPeriod'";
		switch($branch){
			case 0:
			if($staff === 0){
				$transactionQuery.= "";
			}else{
				$transactionQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$transactionQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$transactionQuery.= "";
			}else{
				$transactionQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$payments         = Yii::app()->db->createCommand($transactionQuery)->queryRow();
		if(!empty($payments)){
			$totalPeriodPayments = $payments['totalPayments'];
		}else{
			$totalPeriodPayments = 0;
		}
		return $totalPeriodPayments;
	}

	public static function getDisbursedVsPaidLoans($disbursed,$branch,$staff){
		$statistics = array();
		$data_count = 0;
		foreach($disbursed as $disburse){
			$regMonthDate      = explode('-',$disburse['registrationDate']);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
			$totalDisbursed    = (float)$disburse['totalDisbursed']; 
			$totalPaid         = (float)Analytics::getTotalPeriodPaid($disburse['registrationDate'],$branch,$staff);
			$statistics[$data_count]['dateRegistered'] = $registrationDate;
			$statistics[$data_count]['disbursements']  = $totalDisbursed;
			$statistics[$data_count]['payments']       = $totalPaid;
			$data_count++;
		}
		return $statistics;
	}

	public static function getDisbursedVsPaidLoansGrowthLineGraph($disbursed,$cTitle,$cHolder,$branch,$staff){
		$turnovers = Analytics::getDisbursedVsPaidLoans($disbursed,$branch,$staff);
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";

		$axisarray = array();
		$count = 0;
		foreach($turnovers as $turnover){
		  $axisarray[$count] = array($turnover['dateRegistered']);
		  $count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Total Amount";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis->min = 0;
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		$disbursed = array();
		$count = 0;
		foreach($turnovers as $loanDisbursed) { 
			if(isset($loanDisbursed["disbursements"])){
				$disbursed[$count] = array('y'=>$loanDisbursed["disbursements"]);  
			}else{ 
				$disbursed[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Amount Disbursed",'color' => "#17becf",'type' => "column",'yAxis'=>1, 'data' => $disbursed);
		/*Loan Collections */
		$collections = array();
		$count = 0;
		foreach($turnovers as $collection) { 
			if(isset($collection["payments"])){
				$collections[$count] = array('y'=>$collection["payments"]);  
			}else{ 
				$collections[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Amount Paid",'color' => "#ff7f00",'type' => "line",'yAxis'=>1,'data' => $collections);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$cHolder.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}

	/****************************

		INTEREST GENERATED VS PAID

	*********************************/
	public static function getInterestGeneratedVsPaidTrendTabulation($branch,$staff,$start_date,$end_date){
		$disbursed = Analytics::getInterestGeneratedTotals($branch,$staff,$start_date,$end_date);
    	return Analytics::generateInterestGeneratedVsPaidTabulation($disbursed,$branch,$staff);
	}

	public static function getInterestGeneratedTotals($branch,$staff,$start_date,$end_date){
		$disburseQuery = "SELECT DATE_FORMAT(loaninterests.accrued_at, '%Y-%m') AS registrationDate,
		COALESCE(SUM(loaninterests.interest_accrued),0) as totalInterest FROM loaninterests,loanaccounts
		WHERE loaninterests.loanaccount_id=loanaccounts.loanaccount_id AND (DATE(loaninterests.accrued_at) BETWEEN '$start_date' AND '$end_date')
		AND loaninterests.transaction_type='debit'";
		switch($branch){
			case 0:
			if($staff === 0){
				$disburseQuery.= "";
			}else{
				$disburseQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$disburseQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$disburseQuery.= "";
			}else{
				$disburseQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$disburseQuery .= " GROUP BY registrationDate ORDER BY registrationDate ASC";
		$transactions   = Yii::app()->db->createCommand($disburseQuery)->queryAll();
		return $transactions;
	}

	public static function generateInterestGeneratedVsPaidTabulation($disbursed,$branch,$staff){
		$html                  = "";
		$months                = "";
		$monthlyTotalDisbursed = "";
		$periodTotalPaid       = "";
		if(!empty($disbursed)){
			foreach($disbursed as $disburse){
				$regMonthDate      = explode('-',$disburse['registrationDate']);
				$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
				$totalDisbursed    = (float)$disburse['totalInterest']; 
				$totalPaid  = (float)Analytics::getPeriodInterestPaid($disburse['registrationDate'],$branch,$staff);
				$months                  .= "<td class='text-primary'>$registrationDate</td>";
				$monthlyTotalDisbursed   .= "<td>".number_format($totalDisbursed,2)."</td>"; 
				$periodTotalPaid         .= "<td>".number_format($totalPaid,2)."</td>";
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>GENERATED</td>$monthlyTotalDisbursed</tr>";
			$html.= "<tr><td class='text-primary'>PAID</td>$periodTotalPaid</tr>";
			$html.= "</tbody></table></div>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getPeriodInterestPaid($monthPeriod,$branch,$staff){
		$transactionQuery="SELECT COALESCE(SUM(loanrepayments.interest_paid),0) as totalPaid FROM loanrepayments,loanaccounts
		WHERE loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loanrepayments.is_void IN('0','3','4')
		AND DATE_FORMAT(loanrepayments.repaid_at, '%Y-%m')='$monthPeriod'";
		switch($branch){
			case 0:
			if($staff === 0){
				$transactionQuery.= "";
			}else{
				$transactionQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$transactionQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$transactionQuery.= "";
			}else{
				$transactionQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$payments         = Yii::app()->db->createCommand($transactionQuery)->queryRow();
		if(!empty($payments)){
			$totalPeriodPayments = $payments['totalPaid'];
		}else{
			$totalPeriodPayments = 0;
		}
		return $totalPeriodPayments;
	}

	public static function getInterestGeneratedVsPaid($generated,$branch,$staff){
		$statistics = array();
		$data_count = 0;
		foreach($generated as $gen){
			$regMonthDate      = explode('-',$gen['registrationDate']);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
			$totalGenerated    = (float)$gen['totalInterest']; 
			$totalPaid         = (float)Analytics::getPeriodInterestPaid($gen['registrationDate'],$branch,$staff);
			$statistics[$data_count]['dateRegistered'] = $registrationDate;
			$statistics[$data_count]['generations']    = $totalGenerated;
			$statistics[$data_count]['payments']       = $totalPaid;
			$data_count++;
		}
		return $statistics;
	}

	public static function getInterestGeneratedVsPaidGrowthLineGraph($disbursed,$cTitle,$cHolder,$branch,$staff){
		$turnovers = Analytics::getInterestGeneratedVsPaid($disbursed,$branch,$staff);
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";

		$axisarray = array();
		$count = 0;
		foreach($turnovers as $turnover){
		  $axisarray[$count] = array($turnover['dateRegistered']);
		  $count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Total Interest";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		$disbursed = array();
		$count = 0;
		foreach($turnovers as $generated) { 
			if(isset($generated["generations"])){
				$disbursed[$count] = array('y'=>$generated["generations"]);  
			}else{ 
				$disbursed[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Interest Generated",'color' => "#17becf",'type' => "column",'yAxis'=>1, 'data' => $disbursed);
		/*Loan Collections */
		$collections = array();
		$count = 0;
		foreach($turnovers as $collection) { 
			if(isset($collection["payments"])){
				$collections[$count] = array('y'=>$collection["payments"]);  
			}else{ 
				$collections[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Interest Paid",'color' => "#ff7f00",'type' => "line",'yAxis'=>1,'data' => $collections);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$cHolder.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}

	/****************************

		RECOVERED INT & PRINCIPLE

	*********************************/
	public static function getRecoveredInterestPrincipleTrendTabulation($branch,$staff,$start_date,$end_date){
		$collections = Analytics::getRecoveredInterestPrinciple($branch,$staff,$start_date,$end_date);
    	return Analytics::generateRecoveredInterestPrincipleTabulation($collections,$branch,$staff);
	}

	public static function getRecoveredInterestPrincipleTotals($branch,$staff,$start_date,$end_date){
		$disburseQuery = "SELECT DATE_FORMAT(loaninterests.accrued_at, '%Y-%m') AS registrationDate,
		COALESCE(SUM(loaninterests.interest_accrued),0) as totalInterest FROM loaninterests,loanaccounts
		WHERE loaninterests.loanaccount_id=loanaccounts.loanaccount_id AND (DATE(loaninterests.accrued_at) BETWEEN '$start_date' AND '$end_date')
		AND loaninterests.transaction_type='debit'";
		switch($branch){
			case 0:
			if($staff === 0){
				$disburseQuery.= "";
			}else{
				$disburseQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$disburseQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$disburseQuery.= "";
			}else{
				$disburseQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$disburseQuery .= " GROUP BY registrationDate ORDER BY registrationDate ASC";
		$transactions   = Yii::app()->db->createCommand($disburseQuery)->queryAll();
		return $transactions;
	}

	public static function getRecoveredInterestPrincipleBarGraph($cTitle,$cHolder,$branch,$staff){
		$turnovers = Analytics::getInterestGeneratedVsPaid($disbursed,$branch,$staff);
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";

		$axisarray = array();
		$count = 0;
		foreach($turnovers as $turnover){
		  $axisarray[$count] = array($turnover['dateRegistered']);
		  $count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Total Recovered";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*Loan Collections */
		$recovered = array();
		$count = 0;
		foreach($turnovers as $collection) { 
			if(isset($collection["payments"])){
				$recovered[$count] = array('y'=>$collection["totalRecovered"]);  
			}else{ 
				$recovered[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Amount Recovered",'color' => "#ff7f00",'type'=> "column",'yAxis'=>1,'data' => $recovered);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$cHolder.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}	

	/**************

		LOAN PERFORMANCE

	***********************/

	public static function getLoanPerformanceTrendTabulation($branch,$staff,$start_date,$end_date){
		$disbursed = Analytics::getLoanPerformanceTotals($branch,$staff,$start_date,$end_date);
    	return Analytics::generateLoanPerformanceTabulation($disbursed,$branch,$staff,$start_date);
	}

	public static function getLoanPerformanceTotals($branch,$staff,$start_date,$end_date){
		$disburseQuery="SELECT DATE_FORMAT(created_at, '%Y-%m') AS registrationDate FROM loanaccounts
		WHERE (DATE(created_at) BETWEEN '$start_date' AND '$end_date') AND loan_status NOT IN('0','1','3','4','8','9','10') ";
		switch($branch){
			case 0:
			if($staff === 0){
				$disburseQuery.= "";
			}else{
				$disburseQuery.= " AND rm=$staff";
			}
			break;

			default:
			$disburseQuery.= " AND branch_id=$branch";
			if($staff === 0){
				$disburseQuery.= "";
			}else{
				$disburseQuery.= " AND rm=$staff";
			}
			break;
		}
		$disburseQuery .= " GROUP BY registrationDate ORDER BY registrationDate ASC";
		$transactions   = Yii::app()->db->createCommand($disburseQuery)->queryAll();
		return $transactions;
	}

	public static function generateLoanPerformanceTabulation($disbursed,$branch,$staff,$start_date){
		$html                  = "";
		$months                = "";
		$monthlyPerformingLoan = "";
		$monthlyNonperforming  = "";
		$openOk     = (float) Analytics::getTotalNonPerformingOpening($branch,$staff,$start_date);
		$openNon    = (float) Analytics::getTotalPerformingOpening($branch,$staff,$start_date);
		if(!empty($disbursed)){
			foreach($disbursed as $disburse){
				$rawDate           = $disburse['registrationDate'];
				$regMonthDate      = explode('-',$rawDate);
				$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
				$totalOk           = (float)Analytics::getPeriodPerforming($rawDate,$branch,$staff);
				$openOk           += $totalOk;
				$totalNon          = (float)Analytics::getTotalNonPerforming($rawDate,$branch,$staff);
				$openNon          += $totalNon;
				$months                  .= "<td class='text-primary'>$registrationDate</td>";
				$monthlyPerformingLoan   .= "<td>".number_format($openOk,2)."</td>"; 
				$monthlyNonperforming    .= "<td>".number_format($openNon,2)."</td>";
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>PERFORMING</td>$monthlyPerformingLoan</tr>";
			$html.= "<tr><td class='text-primary'>NON-PERFORMING</td>$monthlyNonperforming</tr>";
			$html.= "</tbody></table></div>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getTotalNonPerforming($monthPeriod,$branch,$staff){
		$zeroRated = Analytics::getPeriodNonPerforming($monthPeriod,$branch,$staff);
		//$frozen    = Analytics::getPeriodNonPerformingFrozen($monthPeriod,$branch,$staff);
		$frozen    = 0;
		$totals    = $zeroRated + $frozen;
		return $totals;
	}

	public static function getTotalNonPerformingOpening($branch,$staff,$start_date){
		$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE DATE(created_at) < '$start_date' AND loan_status NOT IN('0','1','3','4','8','9','10') AND interest_rate=0.00";
		switch($branch){
			case 0:
			if($staff === 0){
				$accountsQuery.= "";
			}else{
				$accountsQuery.= " AND rm=$staff";
			}
			break;

			default:
			$accountsQuery.= "  AND branch_id=$branch";
			if($staff === 0){
				$accountsQuery.= " ";
			}else{
				$accountsQuery.= " AND rm=$staff";
			}
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryAll();
		if(!empty($accounts)){
			$totalPeriodPayments=0;
			foreach($accounts AS $account){
				$totalPeriodPayments+=LoanManager::getPrincipalBalance($account['loanaccount_id']);
			}
		}else{
			$totalPeriodPayments=0;
		}
		return $totalPeriodPayments;
	}

	public static function getTotalPerformingOpening($branch,$staff,$start_date){
		$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE DATE(created_at) < '$start_date' AND loan_status NOT IN('0','1','3','4','8','9','10') AND interest_rate > 0";
		switch($branch){
			case 0:
			if($staff === 0){
				$accountsQuery.= "";
			}else{
				$accountsQuery.= " AND rm=$staff";
			}
			break;

			default:
			$accountsQuery.= "  AND branch_id=$branch";
			if($staff === 0){
				$accountsQuery.= " ";
			}else{
				$accountsQuery.= " AND rm=$staff";
			}
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryAll();
		if(!empty($accounts)){
			$totalPeriodPayments=0;
			foreach($accounts AS $account){
				$totalPeriodPayments+=LoanManager::getPrincipalBalance($account['loanaccount_id']);
			}
		}else{
			$totalPeriodPayments=0;
		}
		return $totalPeriodPayments;
	}

	public static function getPeriodNonPerformingFrozen($monthPeriod,$branch,$staff){
		$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE DATE_FORMAT(created_at, '%Y-%m')='$monthPeriod'
		AND loan_status IN('2','5','6','7') AND loanaccount_id IN(SELECT loanaccount_id FROM interest_freezes WHERE period_frozen=575)";
		switch($branch){
			case 0:
			if($staff === 0){
				$accountsQuery.= "";
			}else{
				$accountsQuery.= " AND rm=$staff";
			}
			break;

			default:
			$accountsQuery.= "  AND branch_id=$branch";
			if($staff === 0){
				$accountsQuery.= " ";
			}else{
				$accountsQuery.= " AND rm=$staff";
			}
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryAll();
		if(!empty($accounts)){
			$totalPeriodPayments=0;
			foreach($accounts AS $account){
				$totalPeriodPayments+=LoanManager::getPrincipalBalance($account['loanaccount_id']);
			}
		}else{
			$totalPeriodPayments=0;
		}
		return $totalPeriodPayments;
	}

	public static function getPeriodNonPerforming($monthPeriod,$branch,$staff){
		$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE DATE_FORMAT(created_at, '%Y-%m')='$monthPeriod'
		AND loan_status IN('2','5','6','7') AND interest_rate=0.00";
		switch($branch){
			case 0:
			if($staff === 0){
				$accountsQuery.= "";
			}else{
				$accountsQuery.= " AND rm=$staff";
			}
			break;

			default:
			$accountsQuery.= "  AND branch_id=$branch";
			if($staff === 0){
				$accountsQuery.= " ";
			}else{
				$accountsQuery.= " AND rm=$staff";
			}
			break;
		}
   		$accounts=Yii::app()->db->createCommand($accountsQuery)->queryAll();
		if(!empty($accounts)){
			$totalPeriodPayments=0;
			foreach($accounts AS $account){
				$totalPeriodPayments+=LoanManager::getPrincipalBalance($account['loanaccount_id']);
			}
		}else{
			$totalPeriodPayments=0;
		}
		return $totalPeriodPayments;
	}

	public static function getPeriodPerforming($monthPeriod,$branch,$staff){
		$accountsQuery="SELECT loanaccount_id FROM loanaccounts WHERE DATE_FORMAT(created_at, '%Y-%m')='$monthPeriod'
		AND loan_status IN('2','5','6','7') AND interest_rate > 0";
		switch($branch){
			case 0:
			if($staff === 0){
				$accountsQuery.= "";
			}else{
				$accountsQuery.= " AND rm=$staff";
			}
			break;

			default:
			$accountsQuery.= "  AND branch_id=$branch";
			if($staff === 0){
				$accountsQuery.= " ";
			}else{
				$accountsQuery.= " AND rm=$staff";
			}
			break;
		}
    	$accounts=Yii::app()->db->createCommand($accountsQuery)->queryAll();
		if(!empty($accounts)){
			$totalPeriodPayments=0;
			foreach($accounts AS $account){
				$totalPeriodPayments+=LoanManager::getPrincipalBalance($account['loanaccount_id']);
			}
		}else{
			$totalPeriodPayments=0;
		}
		return $totalPeriodPayments;
	}

	public static function getLoanPerformance($generated,$branch,$staff,$start_date){
		$statistics = array();
		$data_count = 0;
		$openOk     = (float)Analytics::getTotalNonPerformingOpening($branch,$staff,$start_date);
		$openNon    = (float)Analytics::getTotalPerformingOpening($branch,$staff,$start_date);
		foreach($generated as $gen){
			$rawDate           = $gen['registrationDate'];
			$regMonthDate      = explode('-',$rawDate);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
			$totalOk           = (float)Analytics::getPeriodPerforming($rawDate,$branch,$staff);
			$openOk           += $totalOk;
			$totalNon          = (float)Analytics::getTotalNonPerforming($rawDate,$branch,$staff);
			$openNon          += $totalNon;
			$statistics[$data_count]['dateRegistered'] = $registrationDate;
			$statistics[$data_count]['performings']    = $openOk;
			$statistics[$data_count]['nonperformings'] = $openNon;
			$data_count++;
		}
		return $statistics;
	}

	public static function getLoanPerformanceGrowthLineGraph($disbursed,$cTitle,$cHolder,$branch,$staff,$start_date){
		$turnovers = Analytics::getLoanPerformance($disbursed,$branch,$staff,$start_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";

		$axisarray = array();
		$count = 0;
		foreach($turnovers as $turnover){
		  $axisarray[$count] = array($turnover['dateRegistered']);
		  $count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Total Amounts";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		$disbursed = array();
		$count = 0;
		foreach($turnovers as $generated) { 
			if(isset($generated["performings"])){
				$disbursed[$count] = array('y'=>$generated["performings"]);  
			}else{ 
				$disbursed[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Performing Loans",'color' => "#17becf",'type' => "column",'yAxis'=>1, 'data' => $disbursed);
		/*Loan Collections */
		$collections = array();
		$count = 0;
		foreach($turnovers as $collection) { 
			if(isset($collection["nonperformings"])){
				$collections[$count] = array('y'=>$collection["nonperformings"]);  
			}else{ 
				$collections[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Non-performing Loans",'color' => "#ff7f00",'type' => "line",'yAxis'=>1,'data' => $collections);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$cHolder.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}

	/***************************

		OVERALL COLLECTIONS PERFORMANCE

	************************************/
	public static function getOverallCollectionPerformanceTarget($monthPeriod,$branch,$staff){
		switch($staff){
			case 0:
			$cTarget = Analytics::getOverallMicrofinanceCollectionsTarget($monthPeriod,$branch);
			break;

			default:
			$cTarget = Analytics::getOverallStaffCollectionTarget($monthPeriod,$staff);
			break;
		}
		return $cTarget;
	}

	public static function getOverallStaffCollectionTarget($monthPeriod,$staff){
		$staffTargetQuery = "SELECT collections_target AS sTarget from staff WHERE user_id=$staff";
		$target           = Yii::app()->db->createCommand($staffTargetQuery)->queryRow();
		if(!empty($target)){
			$staffTarget = $target['sTarget'];
		}else{
			$staffTarget = 0;
		}
		return $staffTarget;
	}

	public static function getOverallMicrofinanceCollectionsTarget($monthPeriod,$branch){
		switch($branch){
			case 0:
			$branches = Reports::getAllBranches();
			$cTarget  = 0;
			foreach($branches AS $branch){
				$cTarget += $branch->collections_target;
			}
			break;

			default:
			$branch  = Branch::model()->findByPk($branch);
			$cTarget = $branch->collections_target;
			break;
		}
		return $cTarget;
	}

	public static function getOverallCollections($branch,$staff,$start_date,$end_date){
		$transactQuery = "SELECT DATE_FORMAT(loantransactions.transacted_at, '%Y-%m') AS registrationDate, COALESCE(SUM(loantransactions.amount),0) as totalPaid
		FROM loantransactions,loanaccounts WHERE loantransactions.loanaccount_id=loanaccounts.loanaccount_id AND loantransactions.is_void IN('0','3','4')
		AND (DATE(loantransactions.transacted_at) BETWEEN '$start_date' AND '$end_date')";
		switch($branch){
			case 0:
			if($staff === 0){
				$transactQuery.= "";
			}else{
				$transactQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$transactQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$transactQuery.= "";
			}else{
				$transactQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$transactQuery .= " GROUP BY registrationDate ORDER BY registrationDate ASC";
		$transactions   = Yii::app()->db->createCommand($transactQuery)->queryAll();
		return $transactions;
	}

	public static function getOverallCollectionPerformanceTrendTabulation($branch,$staff,$start_date,$end_date){
		$collections = Analytics::getOverallCollections($branch,$staff,$start_date,$end_date);
    	return Analytics::generateOverallCollectionPerfomanceTabulation($collections,$branch,$staff);
	}

	public static function generateOverallCollectionPerfomanceTabulation($collections,$branch,$staff){
		$html                      = "";
		$months                    = "";
		$monthlyOverallCollections = "";
		$monthlyOverallTargets     = "";
		$monthlyTargetVariance     = "";
		if(!empty($collections)){
			foreach($collections as $collection){
				$regMonthDate      = explode('-',$collection['registrationDate']);
				$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
				$totalCollections  = (float)$collection['totalPaid'];
				$targetAmounts     = (float)Analytics::getOverallCollectionPerformanceTarget($collection['registrationDate'],$branch,$staff);
				$tDifference       = (float)($totalCollections - $targetAmounts);
				$months                    .= "<td class='text-primary'>$registrationDate</td>";
				$monthlyOverallCollections .= "<td>".number_format($totalCollections,2)."</td>"; 
				$monthlyOverallTargets     .= "<td>".number_format($targetAmounts,2)."</td>";
				$monthlyTargetVariance     .= "<td>".number_format($tDifference,2)."</td>";
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>OVERALL TARGET</td>$monthlyOverallTargets</tr>";
			$html.= "<tr><td class='text-primary'>COLLECTED</td>$monthlyOverallCollections</tr>";
			$html.= "<tr><td class='text-primary'>DIFFERENCE</td>$monthlyTargetVariance</tr>";
			$html.= "</tbody></table></div>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getOverallCollectionPerformance($collections,$branch,$staff){
		$statistics = array();
		$data_count = 0;
		foreach($collections as $gen){
			$regMonthDate      = explode('-',$gen['registrationDate']);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
			$totalCollections  = (float)$gen['totalPaid']; 
			$totalTargets      = (float)Analytics::getOverallCollectionPerformanceTarget($gen['registrationDate'],$branch,$staff);
			$statistics[$data_count]['dateRegistered'] = $registrationDate;
			$statistics[$data_count]['collections']    = $totalCollections;
			$statistics[$data_count]['targets'] = $totalTargets;
			$data_count++;
		}
		return $statistics;
	}

	public static function getOverallCollectionPerformanceGrowthLineGraph($cols,$cTitle,$cHolder,$branch,$staff){
		$turnovers = Analytics::getOverallCollectionPerformance($cols,$branch,$staff);
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";

		$axisarray = array();
		$count = 0;
		foreach($turnovers as $turnover){
		  $axisarray[$count] = array($turnover['dateRegistered']);
		  $count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Total Amounts";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		$disbursed = array();
		$count = 0;
		foreach($turnovers as $generated) { 
			if(isset($generated["collections"])){
				$disbursed[$count] = array('y'=>$generated["collections"]);  
			}else{ 
				$disbursed[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Amount Collected",'color' => "#17becf",'type' => "column",'yAxis'=>1, 'data' => $disbursed);
		/*Loan Collections */
		$collections = array();
		$count = 0;
		foreach($turnovers as $collection) { 
			if(isset($collection["targets"])){
				$collections[$count] = array('y'=>$collection["targets"]);  
			}else{ 
				$collections[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Target Amounts",'color' => "#ff7f00",'type' => "line",'yAxis'=>1,'data' => $collections);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$cHolder.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}

	/*******************************

		RECOVERED INTERESTS & PRINCIPLES

	**************************************/
	public static function getRecoveredZeroInterestRates($monthPeriod,$branch,$staff){
		$transactionQuery = "SELECT COALESCE(SUM(loantransactions.amount),0) as totalAmount
		 FROM loantransactions,loanaccounts WHERE loantransactions.loanaccount_id=loanaccounts.loanaccount_id
		 AND loantransactions.is_void IN('0','3','4') AND loanaccounts.interest_rate=0
		 AND DATE_FORMAT(loantransactions.transacted_at, '%Y-%m')='$monthPeriod'";
		switch($branch){
			case 0:
			if($staff === 0){
				$transactionQuery.= "";
			}else{
				$transactionQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$transactionQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$transactionQuery.= "";
			}else{
				$transactionQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$payments         = Yii::app()->db->createCommand($transactionQuery)->queryRow();
		if(!empty($payments)){
			$totalPeriodPayments = $payments['totalAmount'];
		}else{
			$totalPeriodPayments = 0;
		}
		return $totalPeriodPayments;
	}

	public static function getRecoveredFrozenIndefinitely($monthPeriod,$branch,$staff){
		$transactionQuery = "SELECT COALESCE(SUM(loantransactions.amount),0) as totalAmount
		 FROM loantransactions,loanaccounts WHERE loantransactions.loanaccount_id=loanaccounts.loanaccount_id
		 AND loantransactions.is_void IN('0','3','4') AND loanaccounts.loanaccount_id
		 IN(SELECT loanaccount_id FROM interest_freezes WHERE period_frozen=575)
		 AND DATE_FORMAT(loantransactions.transacted_at, '%Y-%m')='$monthPeriod'";
		switch($branch){
			case 0:
			if($staff === 0){
				$transactionQuery.= "";
			}else{
				$transactionQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$transactionQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$transactionQuery.= "";
			}else{
				$transactionQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$payments         = Yii::app()->db->createCommand($transactionQuery)->queryRow();
		if(!empty($payments)){
			$totalPeriodPayments = $payments['totalAmount'];
		}else{
			$totalPeriodPayments = 0;
		}
		return $totalPeriodPayments;
	}

	public static function getTotalRecoveredAmounts($monthPeriod,$branch,$staff){
		$totalFrozen    = Analytics::getRecoveredFrozenIndefinitely($monthPeriod,$branch,$staff);
		$totalZeroRates = Analytics::getRecoveredZeroInterestRates($monthPeriod,$branch,$staff); 
		$totalRecovered = $totalFrozen + $totalZeroRates;
		return $totalRecovered;
	}

	public static function getRecoveredInterestPrinciple($branch,$staff,$start_date,$end_date){
		$transactQuery = "SELECT DATE_FORMAT(loantransactions.transacted_at, '%Y-%m') AS registrationDate
		 FROM loantransactions,loanaccounts WHERE loantransactions.loanaccount_id=loanaccounts.loanaccount_id
		 AND loantransactions.is_void IN('0','3','4') AND (DATE(loantransactions.transacted_at)
		 BETWEEN '$start_date' AND '$end_date')";
		switch($branch){
			case 0:
			if($staff === 0){
				$transactQuery.= "";
			}else{
				$transactQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$transactQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$transactQuery.= "";
			}else{
				$transactQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$transactQuery .= " GROUP BY registrationDate ORDER BY registrationDate ASC";
		$transactions   = Yii::app()->db->createCommand($transactQuery)->queryAll();
		return $transactions;
	}

	public static function generateRecoveredInterestPrincipleTabulation($collections,$branch,$staff){
		$html                      = "";
		$months                    = "";
		$monthlyRecoveredAmounts   = "";
		if(!empty($collections)){
			foreach($collections as $collection){
				$rawDate           = $collection['registrationDate'];
				$regMonthDate      = explode('-',$rawDate);
				$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
				$amountCollected   = (float)Analytics::getTotalRecoveredAmounts($rawDate,$branch,$staff);
				$months                   .= "<td class='text-primary'>$registrationDate</td>";
				$monthlyRecoveredAmounts  .= "<td>".number_format($amountCollected,2)."</td>";
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>RECOVERED PR</td>$monthlyRecoveredAmounts</tr>";
			$html.= "</tbody></table></div>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getRecoveredInterestPrincipleStats($collections,$branch,$staff){
		$statistics = array();
		$data_count = 0;
		foreach($collections as $gen){
			$rawDate           = $gen['registrationDate'];
			$regMonthDate      = explode('-',$rawDate);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered); 
			$totalCollections      = (float)Analytics::getTotalRecoveredAmounts($rawDate,$branch,$staff);
			$statistics[$data_count]['dateRegistered'] = $registrationDate;
			$statistics[$data_count]['collections']    = $totalCollections;
			$data_count++;
		}
		return $statistics;
	}

	public static function getRecoveredInterestPrincipleGrowthLineGraph($cols,$cTitle,$cHolder,$branch,$staff){
		$turnovers = Analytics::getRecoveredInterestPrincipleStats($cols,$branch,$staff);
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";

		$axisarray = array();
		$count = 0;
		foreach($turnovers as $turnover){
		  $axisarray[$count] = array($turnover['dateRegistered']);
		  $count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Total Amounts";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		$disbursed = array();
		$count = 0;
		foreach($turnovers as $generated) { 
			if(isset($generated["collections"])){
				$disbursed[$count] = array('y'=>$generated["collections"]);  
			}else{ 
				$disbursed[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Amount Collected",'color' => "#ff7f00",'type' => "column",'yAxis'=>1, 'data' => $disbursed);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$cHolder.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}

	/*******************************

		RISK CLASSIFICATION LOAN COUNTS

	**************************************/
	public static function getPerformanceLevelCountsPrior($pLevel,$start_date,$branch,$staff){
		$transactionQuery = "SELECT COUNT(disbursed_loans.loanaccount_id) AS totalCount FROM loanaccounts,disbursed_loans
		WHERE DATE(disbursed_loans.disbursed_at)<'$start_date' AND loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id
		AND loanaccounts.performance_level='$pLevel'";
		switch($branch){
			case 0:
			$transactionQuery.= $staff === 0 ? "" : " AND loanaccounts.rm=$staff";
			break;

			default:
			$transactionQuery.= " AND loanaccounts.branch_id=$branch";
			$transactionQuery.= $staff === 0 ? "" : " AND loanaccounts.rm=$staff";
			break;
		}
		$accounts  = Yii::app()->db->createCommand($transactionQuery)->queryRow();
		return !empty($accounts) ? $accounts['totalCount'] : 0;
	}

	public static function getPerformanceLevelCounts($pLevel,$monthPeriod,$branch,$staff){
		$transactionQuery = "SELECT COUNT(disbursed_loans.loanaccount_id) AS totalCount FROM loanaccounts,disbursed_loans
		 WHERE DATE_FORMAT(disbursed_loans.disbursed_at, '%Y-%m')='$monthPeriod'
		 AND loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id AND loanaccounts.performance_level='$pLevel'";
		switch($branch){
			case 0:
			$transactionQuery.= $staff === 0 ? "" : " AND loanaccounts.rm=$staff";
			break;

			default:
			$transactionQuery.= " AND loanaccounts.branch_id=$branch";
			$transactionQuery.= $staff === 0 ? "" : " AND loanaccounts.rm=$staff";
			break;
		}
		$accounts  = Yii::app()->db->createCommand($transactionQuery)->queryRow();
		return !empty($accounts) ? $accounts['totalCount'] : 0;
	}

	public static function getRiskCounts($branch,$staff,$start_date,$end_date){
		$disburseQuery = "SELECT DATE_FORMAT(disbursed_at, '%Y-%m') AS registrationDate
		FROM disbursed_loans,loanaccounts WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id
		AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$start_date' AND '$end_date')";
		switch($branch){
			case 0:
			$disburseQuery.= $staff === 0 ? "" : " AND loanaccounts.rm=$staff";
			break;

			default:
			$disburseQuery.= " AND loanaccounts.branch_id=$branch";
			$disburseQuery.= $staff === 0 ? "" : " AND loanaccounts.rm=$staff";
			break;
		}
		$disburseQuery .= " GROUP BY registrationDate ORDER BY registrationDate ASC";
		return Yii::app()->db->createCommand($disburseQuery)->queryAll();
	}

	public static function generateRiskCountsTabulation($disbursements,$branch,$staff,$start_date){
		$html                      = "";
		$months                    = "";
		$thirtyMonthly             = "";
		$ninentyMonthly            = "";
		$oneeightyMonthly          = "";
		$threesixtyMonthly         = "";
		$overthreesixtyMonthly     = "";
		$thirtyPrior = Analytics::getPerformanceLevelCountsPrior('A',$start_date,$branch,$staff);
		$ninetyPrior = Analytics::getPerformanceLevelCountsPrior('B',$start_date,$branch,$staff);
		$oneeightyPrior = Analytics::getPerformanceLevelCountsPrior('C',$start_date,$branch,$staff);
		$threesixtyPrior = Analytics::getPerformanceLevelCountsPrior('D',$start_date,$branch,$staff);
		$abovethreesixtyPrior = Analytics::getPerformanceLevelCountsPrior('E',$start_date,$branch,$staff);
		if(!empty($disbursements)){
			foreach($disbursements as $disbursement){
				$rawDate                = $disbursement['registrationDate'];
				$regMonthDate           = explode('-',$rawDate);
				$dateRegistered         = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate       = CommonFunctions::getRespectiveMonth($dateRegistered);
				$thirty      = (float)(Analytics::getPerformanceLevelCounts('A',$rawDate,$branch,$staff));
				$ninety      = (float)(Analytics::getPerformanceLevelCounts('B',$rawDate,$branch,$staff));
				$oneeighty   = (float)(Analytics::getPerformanceLevelCounts('C',$rawDate,$branch,$staff));
				$threesixty             = (float)(Analytics::getPerformanceLevelCounts('D',$rawDate,$branch,$staff));
				$abovethreesixty        = (float)(Analytics::getPerformanceLevelCounts('E',$rawDate,$branch,$staff));
				$months                .= "<td class='text-primary'>$registrationDate</td>";
				$thirtyMonthly         .= "<td>".number_format($thirty)."</td>";
				$ninentyMonthly        .= "<td>".number_format($ninety)."</td>";
				$oneeightyMonthly      .= "<td>".number_format($oneeighty)."</td>";
				$threesixtyMonthly     .= "<td>".number_format($threesixty)."</td>";
				$overthreesixtyMonthly .= "<td>".number_format($abovethreesixty)."</td>";
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>0-30 DAYS</td>$thirtyMonthly</tr>";
			$html.= "<tr><td class='text-primary'>31-90 DAYS</td>$ninentyMonthly</tr>";
			$html.= "<tr><td class='text-primary'>91-180 DAYS</td>$oneeightyMonthly</tr>";
			$html.= "<tr><td class='text-primary'>181-360 DAYS</td>$threesixtyMonthly</tr>";
			$html.= "<tr><td class='text-primary'>OVER 360 DAYS</td>$overthreesixtyMonthly</tr>";
			$html.= "</tbody></table></div>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getRiskCountsStats($disbursements,$branch,$staff,$start_date){
		$statistics = array();
		$data_count = 0;
		$thirtyPrior = Analytics::getPerformanceLevelCountsPrior('A',$start_date,$branch,$staff);
		$ninetyPrior = Analytics::getPerformanceLevelCountsPrior('B',$start_date,$branch,$staff);
		$oneeightyPrior = Analytics::getPerformanceLevelCountsPrior('C',$start_date,$branch,$staff);
		$threesixtyPrior = Analytics::getPerformanceLevelCountsPrior('D',$start_date,$branch,$staff);
		$abovethreesixtyPrior = Analytics::getPerformanceLevelCountsPrior('E',$start_date,$branch,$staff);
		foreach($disbursements as $gen){
			$rawDate           = $gen['registrationDate'];
			$regMonthDate      = explode('-',$rawDate);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered); 
			$thirty      = (float)(Analytics::getPerformanceLevelCounts('A',$rawDate,$branch,$staff));
			$ninety      = (float)(Analytics::getPerformanceLevelCounts('B',$rawDate,$branch,$staff));
			$oneeighty   = (float)(Analytics::getPerformanceLevelCounts('C',$rawDate,$branch,$staff));
			$threesixty  = (float)(Analytics::getPerformanceLevelCounts('D',$rawDate,$branch,$staff));
			$abovethreesixty= (float)(Analytics::getPerformanceLevelCounts('E',$rawDate,$branch,$staff));
			$statistics[$data_count]['dateRegistered']   = $registrationDate;
			$statistics[$data_count]['thirty']           = $thirty;
			$statistics[$data_count]['ninety']           = $ninety;
			$statistics[$data_count]['oneeighty']        = $oneeighty;
			$statistics[$data_count]['threesixty']       = $threesixty;
			$statistics[$data_count]['abovethreesixty']  = $abovethreesixty;
			$data_count++;
		}
		return $statistics;
	}

	public static function getRiskCountsGrowthLineGraph($cols,$cTitle,$cHolder,$branch,$staff,$start_date){
		$array = Analytics::getRiskCountsStats($cols,$branch,$staff,$start_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";
		$axisarray = array();
		$count = 0;
		foreach($array as $turnover){
		  $axisarray[$count] = array($turnover['dateRegistered']);
		  $count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Total Risk Counts";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "15px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		$chartarray = array();
		$count = 0;
		$others = 0;
		foreach ($array as $key) {  
			if($key['thirty']){
				$statusName = '0-30 Days';
				$totalCount = $key['thirty'];
			}else if($key['ninety']){
				$statusName = '31-90 Days';
				$totalCount = $key['ninety'];
			}else if($key['oneeighty']){
				$statusName = '91-180 Days';
				$totalCount = $key['oneeighty'];
			}else if($key['threesixty']){
				$statusName = '181-360 Days';
				$totalCount = $key['threesixty'];
			}else if($key['abovethreesixty']){
				$statusName = 'Over 360 Days';
				$totalCount = $key['abovethreesixty'];
			}
			$chartarray[$count] = array( 
				$key['dateRegistered']. '<br>'.$statusName. '<br>'.number_format($totalCount),$totalCount
			);  
			$count++;
		}
		$chart->series[] = array('name' => "Month on Month(MoM) Risk  Count",'color' => "#2b908f",'type' => "column",'yAxis'=>1,'data'=>$chartarray);
		$chart->credits = array('enabled'=>false);
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$package = '<div id="'.$cHolder.'"></div>';
		$package.= '<script type="text/javascript">';
		$package.= $chart->render("chart1");
		$package.= '</script>';
		echo $package;
	}
	/**************************

		RISK AMOUNTS DISBURSED

	***********************************/
	public static function getPerformanceLevelAmountsPrior($pLevel,$start_date,$branch,$staff){
		$transactionQuery = "SELECT COALESCE(SUM(disbursed_loans.amount_disbursed),0) AS amountDisbursed FROM loanaccounts,disbursed_loans
		WHERE DATE(disbursed_loans.disbursed_at) < '$start_date' AND loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id 
		AND loanaccounts.performance_level='$pLevel'";
		switch($branch){
			case 0:
			if($staff === 0){
				$transactionQuery.= "";
			}else{
				$transactionQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$transactionQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$transactionQuery.= "";
			}else{
				$transactionQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$accounts   = Yii::app()->db->createCommand($transactionQuery)->queryRow();
		if(!empty($accounts)){
			if($accounts['amountDisbursed'] < 0){
				$levelCounts = 0;
			}else{
				$levelCounts = $accounts['amountDisbursed'];
			}
		}else{
			$levelCounts = 0;
		}
		return $levelCounts;
	}

	public static function getPerformanceLevelAmounts($pLevel,$monthPeriod,$branch,$staff){
		$transactionQuery = "SELECT COALESCE(SUM(disbursed_loans.amount_disbursed),0) AS amountDisbursed FROM loanaccounts,disbursed_loans
		WHERE DATE_FORMAT(disbursed_loans.disbursed_at, '%Y-%m')='$monthPeriod' AND loanaccounts.loanaccount_id=disbursed_loans.loanaccount_id
		AND loanaccounts.performance_level='$pLevel'";
		switch($branch){
			case 0:
			$transactionQuery.= $staff === 0 ?  ""  : " AND loanaccounts.rm=$staff";
			break;

			default:
			$transactionQuery.= " AND loanaccounts.branch_id=$branch";
			$transactionQuery.= $staff === 0 ?  ""  : " AND loanaccounts.rm=$staff";
			break;
		}
		$accounts   = Yii::app()->db->createCommand($transactionQuery)->queryRow();
		if(!empty($accounts)){
			if($accounts['amountDisbursed'] < 0){
				$levelCounts = 0;
			}else{
				$levelCounts = $accounts['amountDisbursed'];
			}
		}else{
			$levelCounts = 0;
		}
		return $levelCounts;
	}

	public static function getRiskAmounts($branch,$staff,$start_date,$end_date){
		$disburseQuery = "SELECT DATE_FORMAT(disbursed_loans.disbursed_at, '%Y-%m') AS registrationDate
		 FROM disbursed_loans,loanaccounts WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id
		 AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$start_date' AND '$end_date')";
		switch($branch){
			case 0:
			$disburseQuery.= $staff === 0 ? "" : " AND loanaccounts.rm=$staff";
			break;

			default:
			$disburseQuery.= " AND loanaccounts.branch_id=$branch";
			$disburseQuery.= $staff === 0 ? "" : " AND loanaccounts.rm=$staff";
			break;
		}
		$disburseQuery .= " GROUP BY registrationDate ORDER BY registrationDate ASC";
		return Yii::app()->db->createCommand($disburseQuery)->queryAll();
	}

	public static function generateRiskAmountsTabulation($disbursements,$branch,$staff,$start_date){
		$html                      = "";
		$months                    = "";
		$thirtyMonthly             = "";
		$ninentyMonthly            = "";
		$oneeightyMonthly          = "";
		$threesixtyMonthly         = "";
		$overthreesixtyMonthly     = "";
		$loanGrowthMonthly         = "";
		$growthMonthly             = "";
		$thirtyPrior = Analytics::getPerformanceLevelAmountsPrior('A',$start_date,$branch,$staff);
		$ninetyPrior = Analytics::getPerformanceLevelAmountsPrior('B',$start_date,$branch,$staff);
		$oneeightyPrior = Analytics::getPerformanceLevelAmountsPrior('C',$start_date,$branch,$staff);
		$threesixtyPrior = Analytics::getPerformanceLevelAmountsPrior('D',$start_date,$branch,$staff);
		$abovethreesixtyPrior = Analytics::getPerformanceLevelAmountsPrior('E',$start_date,$branch,$staff);
		if(!empty($disbursements)){
			$totals = array();
			foreach($disbursements as $disbursement){
				$rawDate                = $disbursement['registrationDate'];
				$regMonthDate           = explode('-',$rawDate);
				$dateRegistered         = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate       = CommonFunctions::getRespectiveMonth($dateRegistered);
				$thirty                 = (float)(Analytics::getPerformanceLevelAmounts('A',$rawDate,$branch,$staff));
				$ninety                 = (float)(Analytics::getPerformanceLevelAmounts('B',$rawDate,$branch,$staff));
				$oneeighty              = (float)(Analytics::getPerformanceLevelAmounts('C',$rawDate,$branch,$staff));
				$threesixty             = (float)(Analytics::getPerformanceLevelAmounts('D',$rawDate,$branch,$staff));
				$abovethreesixty        = (float)(Analytics::getPerformanceLevelAmounts('E',$rawDate,$branch,$staff));
				$loanGrowth             = $thirty + $ninety + $oneeighty + $threesixty + $abovethreesixty;
				array_push($totals,$loanGrowth);
				$totalInserted          = count($totals);
				$growthVariance         = $totalInserted > 1 ? $totals[$totalInserted - 1] - $totals[$totalInserted - 2] : 0;
				$months                .= "<td class='text-primary'>$registrationDate</td>";
				$thirtyMonthly         .= "<td>".number_format($thirty,2)."</td>";
				$ninentyMonthly        .= "<td>".number_format($ninety,2)."</td>";
				$oneeightyMonthly      .= "<td>".number_format($oneeighty,2)."</td>";
				$threesixtyMonthly     .= "<td>".number_format($threesixty,2)."</td>";
				$overthreesixtyMonthly .= "<td>".number_format($abovethreesixty,2)."</td>";
				$loanGrowthMonthly     .= "<td>".number_format($loanGrowth,2)."</td>";
				$growthMonthly         .= "<td>".number_format($growthVariance,2)."</td>";
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>NORMAL</td>$thirtyMonthly</tr>";
			$html.= "<tr><td class='text-primary'>WATCH</td>$ninentyMonthly</tr>";
			$html.= "<tr><td class='text-primary'>SUBSTANDARD</td>$oneeightyMonthly</tr>";
			$html.= "<tr><td class='text-primary'>DOUBTFUL</td>$threesixtyMonthly</tr>";
			$html.= "<tr><td class='text-primary'>RECOVERY</td>$overthreesixtyMonthly</tr>";
			$html.= "<tr><td class='text-primary'>LOAN GROWTH</td>$loanGrowthMonthly</tr>";
			$html.= "<tr><td class='text-primary'>GROWTH</td>$growthMonthly</tr>";
			$html.= "</tbody></table></div>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getRiskAmountsStats($disbursements,$branch,$staff,$start_date){
		$statistics = array();
		$data_count = 0;
		$thirtyPrior = Analytics::getPerformanceLevelAmountsPrior('A',$start_date,$branch,$staff);
		$ninetyPrior = Analytics::getPerformanceLevelAmountsPrior('B',$start_date,$branch,$staff);
		$oneeightyPrior = Analytics::getPerformanceLevelAmountsPrior('C',$start_date,$branch,$staff);
		$threesixtyPrior = Analytics::getPerformanceLevelAmountsPrior('D',$start_date,$branch,$staff);
		$abovethreesixtyPrior = Analytics::getPerformanceLevelAmountsPrior('E',$start_date,$branch,$staff);
		foreach($disbursements as $gen){
			$rawDate           = $gen['registrationDate'];
			$regMonthDate      = explode('-',$rawDate);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered); 
			$thirty            = (float)(Analytics::getPerformanceLevelAmounts('A',$rawDate,$branch,$staff));
			$ninety            = (float)(Analytics::getPerformanceLevelAmounts('B',$rawDate,$branch,$staff));
			$oneeighty         = (float)(Analytics::getPerformanceLevelAmounts('C',$rawDate,$branch,$staff));
			$threesixty        = (float)(Analytics::getPerformanceLevelAmounts('D',$rawDate,$branch,$staff));
			$abovethreesixty   = (float)(Analytics::getPerformanceLevelAmounts('E',$rawDate,$branch,$staff));
			$statistics[$data_count]['dateRegistered']   = $registrationDate;
			$statistics[$data_count]['thirty']           = $thirty;
			$statistics[$data_count]['ninety']           = $ninety;
			$statistics[$data_count]['oneeighty']        = $oneeighty;
			$statistics[$data_count]['threesixty']       = $threesixty;
			$statistics[$data_count]['abovethreesixty']  = $abovethreesixty;
			$data_count++;
		}
		return $statistics;
	}

	public static function getRiskAmountsGrowthLineGraph($cols,$cTitle,$cHolder,$branch,$staff,$start_date){
		$turnovers = Analytics::getRiskAmountsStats($cols,$branch,$staff,$start_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";
		$axisarray = array();
		$count = 0;
		foreach($turnovers as $turnover){
		  $axisarray[$count] = array($turnover['dateRegistered']);
		  $count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Total Amounts";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*30 */
		$thirties = array();
		$count = 0;
		foreach($turnovers as $generated) { 
			$thirties[$count] =  isset($generated["thirty"]) ? array('y'=>$generated["thirty"]) : 0;  
			$count++;  
		}
		$chart->series[] = array('name' => "Risk: NORMAL",'color' => "#2b908f",'type' => "column",'yAxis'=>1, 'data' => $thirties);
		/*90 */
		$nineties = array();
		$count = 0;
		foreach($turnovers as $collection) { 
			$nineties[$count] = isset($collection["ninety"]) ? array('y'=>$collection["ninety"]) : 0;  
			$count++;  
		}
		$chart->series[] = array('name' => "Risk: WATCH",'color' => "#e4d354",'type' => "column",'yAxis'=>1,'data' => $nineties);
		/*180 */
		$oneeighties = array();
		$count = 0;
		foreach($turnovers as $collection) { 
			$oneeighties[$count] =  isset($collection["oneeighty"]) ? array('y'=>$collection["oneeighty"]) : 0;  
			$count++;  
		}
		$chart->series[] = array('name' => "Risk: SUBSTANDARD",'color' => "#f15c80",'type' => "column",'yAxis'=>1,'data' => $oneeighties);
		/*360*/
		$threesixties = array();
		$count = 0;
		foreach($turnovers as $collection) { 
			$threesixties[$count] = isset($collection["threesixty"]) ? array('y'=>$collection["threesixty"]) : 0;  
			$count++;  
		}
		$chart->series[] = array('name' => "Risk: DOUBTFUL",'color' => "#8085e9",'type' => "column",'yAxis'=>1,'data' => $threesixties);
		/* >360*/
		$abovethreesixties = array();
		$count = 0;
		foreach($turnovers as $collection) { 
			$abovethreesixties[$count] =  isset($collection["abovethreesixty"]) ?  array('y'=>$collection["abovethreesixty"]) : 0;  
			$count++;  
		}
		$chart->series[] = array('name' => "Risk: RECOVERY",'color' => "#90ed7d",'type' => "column",'yAxis'=>1,'data' => $abovethreesixties);

		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$cHolder.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}
	/******************
	
		RETURN ON ASSETS
	
	*************************/
	public static function getAssetReturns($branch,$staff,$start_date,$end_date){
		$disburseQuery = "SELECT DATE_FORMAT(disbursed_loans.disbursed_at, '%Y-%m') AS registrationDate,
		COALESCE(SUM(disbursed_loans.amount_disbursed),0) AS amountDisbursed FROM disbursed_loans,loanaccounts
		WHERE disbursed_loans.loanaccount_id=loanaccounts.loanaccount_id AND (DATE(disbursed_loans.disbursed_at) BETWEEN '$start_date' AND '$end_date')";
		switch($branch){
			case 0:
			if($staff === 0){
				$disburseQuery.= "";
			}else{
				$disburseQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$disburseQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$disburseQuery.= "";
			}else{
				$disburseQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$disburseQuery .= " GROUP BY registrationDate ORDER BY registrationDate ASC";
		$disbursements  = Yii::app()->db->createCommand($disburseQuery)->queryAll();
		return $disbursements;
	}

	public static function getTotalIncome($monthPeriod,$branch,$staff){
		$interests   = Analytics::getPeriodicInterestPayments($monthPeriod,$branch,$staff);
		$penalties   = Analytics::getPeriodicPenaltyPayments($monthPeriod,$branch,$staff);
		$otherIncome = Analytics::getPeriodicOtherIncomes($monthPeriod,$branch,$staff);
		$totalIncome = $interests + $penalties + $otherIncome;
		return $totalIncome;
	}

	public static function getPeriodicPrincipalPayments($monthPeriod,$branch,$staff){
		$principalQuery="SELECT COALESCE(SUM(loanrepayments.principal_paid),0) as principal_paid FROM loanrepayments,loantransactions,loanaccounts
		WHERE loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loantransactions.loantransaction_id=loanrepayments.loantransaction_id
		AND DATE_FORMAT(loantransactions.transacted_at,'%Y-%m')='$monthPeriod' AND loantransactions.is_void IN('0','3','4')";
		switch($branch){
			case 0:
			if($staff === 0){
				$principalQuery.= "";
			}else{
				$principalQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$principalQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$principalQuery.= "";
			}else{
				$principalQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$repayment=Loanrepayments::model()->findBySql($principalQuery);
		if(!empty($repayment)){
			$principalRepaid=$repayment->principal_paid;
		}else{
			$principalRepaid=0;	
		}
		return $principalRepaid;
	}

	public static function getPeriodicInterestPayments($monthPeriod,$branch,$staff){
		$principalQuery="SELECT COALESCE(SUM(loanrepayments.interest_paid),0) as interest_paid FROM loanrepayments,loantransactions,loanaccounts
		WHERE loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loantransactions.loantransaction_id=loanrepayments.loantransaction_id
		AND DATE_FORMAT(loantransactions.transacted_at,'%Y-%m')='$monthPeriod' AND loantransactions.is_void IN('0','3','4')";
		switch($branch){
			case 0:
			if($staff === 0){
				$principalQuery.= "";
			}else{
				$principalQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$principalQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$principalQuery.= "";
			}else{
				$principalQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$repayment=Loanrepayments::model()->findBySql($principalQuery);
		if(!empty($repayment)){
			$principalRepaid=$repayment->interest_paid;
		}else{
			$principalRepaid=0;	
		}
		return $principalRepaid;
	}

	public static function getPeriodicPenaltyPayments($monthPeriod,$branch,$staff){
		$principalQuery="SELECT COALESCE(SUM(loanrepayments.penalty_paid),0) as penalty_paid FROM loanrepayments,loantransactions,loanaccounts
		WHERE loanrepayments.loanaccount_id=loanaccounts.loanaccount_id AND loantransactions.loantransaction_id=loanrepayments.loantransaction_id
		AND DATE_FORMAT(loantransactions.transacted_at,'%Y-%m')='$monthPeriod' AND loantransactions.is_void IN('0','3','4')";
		switch($branch){
			case 0:
			if($staff === 0){
				$principalQuery.= "";
			}else{
				$principalQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$principalQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$principalQuery.= "";
			}else{
				$principalQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$repayment=Loanrepayments::model()->findBySql($principalQuery);
		if(!empty($repayment)){
			$principalRepaid=$repayment->penalty_paid;
		}else{
			$principalRepaid=0;	
		}
		return $principalRepaid;
	}

	public static function getPeriodicOtherIncomes($monthPeriod,$branch,$staff){
		$incomeQuery="SELECT COALESCE(SUM(incomes.amount),0) as amount FROM incomes,profiles WHERE profiles.id=incomes.created_by
		 AND DATE_FORMAT(incomes.transaction_date,'%Y-%m')='$monthPeriod'";
		switch($branch){
			case 0:
			if($staff === 0){
				$incomeQuery.= "";
			}else{
				$incomeQuery.= " AND profiles.managerId=$staff";
			}
			break;

			default:
			$incomeQuery.= " AND profiles.branchId=$branch";
			if($staff === 0){
				$incomeQuery.= "";
			}else{
				$incomeQuery.= " AND profiles.managerId=$staff";
			}
			break;
		}
		$income=Incomes::model()->findBySql($incomeQuery);
		if(!empty($income)){
			$incomeRepaid=$income->amount;
		}else{
			$incomeRepaid=0;		
		}
		return $incomeRepaid;
	}

	public static function generateAssetReturnsTabulation($collections,$branch,$staff,$start_date){
		$html           = "";
		$months         = "";
		$monthlyAssets  = "";
		$openOk     = (float)Analytics::getTotalNonPerformingOpening($branch,$staff,$start_date);
		$openNon    = (float)Analytics::getTotalPerformingOpening($branch,$staff,$start_date);
		if(!empty($collections)){
			foreach($collections as $collection){
				$rawDate           = $collection['registrationDate'];
				$regMonthDate      = explode('-',$rawDate);
				$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
				$totalNPAs         = (float)Analytics::getTotalNonPerforming($rawDate,$branch,$staff);
				$openNon          += $totalNPAs;
				$totalNORMs        = (float)Analytics::getPeriodPerforming($rawDate,$branch,$staff);
				$openOk           += $totalNORMs;
				$totalDisbursed    = $openOk + $openNon;
				$totalIncomes      = (float) Analytics::getTotalIncome($rawDate,$branch,$staff);
				$assetReturn       = 100 * ($totalIncomes/$totalDisbursed);
				$months           .= "<td class='text-primary'>$registrationDate</td>";
				$monthlyAssets    .= "<td>".number_format($assetReturn,2)." % </td>"; 
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>RoA</td>$monthlyAssets</tr>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getAssetReturnsStats($disbursements,$branch,$staff,$start_date){
		$statistics = array();
		$data_count = 0;
		$openOk     = (float)Analytics::getTotalNonPerformingOpening($branch,$staff,$start_date);
		$openNon    = (float)Analytics::getTotalPerformingOpening($branch,$staff,$start_date);
		foreach($disbursements as $gen){
			$rawDate           = $gen['registrationDate'];
			$regMonthDate      = explode('-',$rawDate);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered); 
			$totalNPAs         = (float)Analytics::getTotalNonPerforming($rawDate,$branch,$staff);
			$openNon          += $totalNPAs;
			$totalNORMs        = (float)Analytics::getPeriodPerforming($rawDate,$branch,$staff);
			$openOk           += $totalNORMs;
			$totalDisbursed    = $openOk + $openNon;
			$totalIncomes      = (float)Analytics::getTotalIncome($rawDate,$branch,$staff);
			$assetReturn       = 100 * ($totalIncomes/$totalDisbursed);
			$statistics[$data_count]['dateRegistered']   = $registrationDate;
			$statistics[$data_count]['returns']          = round($assetReturn,2);
			$data_count++;
		}
		return $statistics;
	}

	public static function getAssetReturnsGrowthLineGraph($cols,$cTitle,$cHolder,$branch,$staff,$start_date){
		$turnovers = Analytics::getAssetReturnsStats($cols,$branch,$staff,$start_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";
		$axisarray = array();
		$count = 0;
		foreach($turnovers as $turnover){
		  $axisarray[$count] = array($turnover['dateRegistered']);
		  $count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "RoA %";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/*30 */
		$thirties = array();
		$count = 0;
		foreach($turnovers as $generated) { 
			if(isset($generated["returns"])){
				$thirties[$count] = array('y'=>$generated["returns"]);  
			}else{ 
				$thirties[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Return on Assets(RoA)",'color' => "#2b908f",'type' => "line",'yAxis'=>1, 'data' => $thirties);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$cHolder.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}
	/*****************

		ASSETS QUALITY

	************************/
	public static function generateAssetQualityTabulation($collections,$branch,$staff,$start_date){
		$html           = "";
		$months         = "";
		$monthlyNPAs    = "";
		$monthlyNORMs   = "";
		$openOk     = (float)Analytics::getTotalNonPerformingOpening($branch,$staff,$start_date);
		$openNon    = (float)Analytics::getTotalPerformingOpening($branch,$staff,$start_date);
		if(!empty($collections)){
			foreach($collections as $collection){
				$rawDate           = $collection['registrationDate'];
				$regMonthDate      = explode('-',$rawDate);
				$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
				$totalNPAs         = (float)Analytics::getTotalNonPerforming($rawDate,$branch,$staff);
				$openNon          += $totalNPAs;
				$totalNORMs        = (float)Analytics::getPeriodPerforming($rawDate,$branch,$staff);
				$openOk           += $totalNORMs;
				$totalDisbursed    = $openOk + $openNon;
				$norms             = 100*($openOk/$totalDisbursed);
				$npas              = 100*($openNon/$totalDisbursed);
				$months           .= "<td class='text-primary'>$registrationDate</td>";
				$monthlyNPAs      .= "<td>".number_format($npas)." % </td>"; 
				$monthlyNORMs     .= "<td>".number_format($norms)." % </td>"; 
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>PAs: TAs</td>$monthlyNORMs</tr>";
			$html.= "<tr><td class='text-primary'>NPAs: TAs</td>$monthlyNPAs</tr>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getAssetQualityStats($disbursements,$branch,$staff,$start_date){
		$statistics = array();
		$data_count = 0;
		$openOk     = (float)Analytics::getTotalNonPerformingOpening($branch,$staff,$start_date);
		$openNon    = (float)Analytics::getTotalPerformingOpening($branch,$staff,$start_date);
		foreach($disbursements as $gen){
			$rawDate           = $gen['registrationDate'];
			$regMonthDate      = explode('-',$rawDate);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
			$totalNPAs         = (float)Analytics::getTotalNonPerforming($rawDate,$branch,$staff);
			$openNon          += $totalNPAs;
			$totalNORMs        = (float)Analytics::getPeriodPerforming($rawDate,$branch,$staff);
			$openOk           += $totalNORMs;
			$totalDisbursed    = (float)($openOk + $openNon);
			$norms             = 100*($openOk/$totalDisbursed);
			$npas              = 100*($openNon/$totalDisbursed);
			$statistics[$data_count]['dateRegistered']   = $registrationDate;
			$statistics[$data_count]['performings']      = round($norms);
			$statistics[$data_count]['non-performings']  = round($npas);
			$data_count++;
		}
		return $statistics;
	}

	public static function getAssetQualityGrowthLineGraph($cols,$cTitle,$cHolder,$branch,$staff,$start_date){
		$turnovers = Analytics::getAssetQualityStats($cols,$branch,$staff,$start_date);
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";
		$axisarray = array();
		$count = 0;
		foreach($turnovers as $turnover){
		  $axisarray[$count] = array($turnover['dateRegistered']);
		  $count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Asset Quality %";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/* Performing */
		$thirties = array();
		$count = 0;
		foreach($turnovers as $generated) { 
			if(isset($generated["performings"])){
				$thirties[$count] = array('y'=>$generated["performings"]);  
			}else{ 
				$thirties[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Performing Assets",'color' => "#2b908f",'type' => "column",'yAxis'=>1, 'data' => $thirties);
		/* Non-Performing */
		$thirties = array();
		$count = 0;
		foreach($turnovers as $generated) { 
			if(isset($generated["non-performings"])){
				$thirties[$count] = array('y'=>$generated["non-performings"]);  
			}else{ 
				$thirties[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Non-Performing Assets",'color' => "#e4d354",'type' => "column",'yAxis'=>1, 'data' => $thirties);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$cHolder.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}
	/******************
	
		INCOME & EXPENSES
	
	*************************/
	public static function getTotalIncomeExpenseExpenditure($monthPeriod,$branch,$staff){
		$expenses = Analytics::getIncomeExpensesPeriodicExpense($monthPeriod,$branch,$staff);
		$payrolls = Analytics::getIncomeExpensesPeriodicPayroll($monthPeriod,$branch,$staff);
		$totalExpenditure = $expenses + $payrolls;
		return $totalExpenditure;
	}

	public static function getIncomeExpensesPeriodicPayroll($monthPeriod,$branch,$staff){
		$regMonthDate      = explode('-',$monthPeriod);
		$month             = (int)$regMonthDate[1];
		$year              = (int)$regMonthDate[0];
		$payrollQuery      = "SELECT COALESCE(SUM(payroll.net_salary),0) AS net_salary FROM payroll,profiles
		WHERE payroll.payroll_month=$month AND payroll.payroll_year=$year AND payroll.user_id=profiles.id";
		switch($branch){
			case 0:
			if($staff === 0){
				$payrollQuery.= "";
			}else{
				$payrollQuery.= " AND profiles.managerId=$staff";
			}
			break;

			default:
			$payrollQuery.= " AND profiles.branchId=$branch";
			if($staff === 0){
				$payrollQuery.= "";
			}else{
				$payrollQuery.= " AND profiles.managerId=$staff";
			}
			break;
		}

		$transaction=Payroll::model()->findBySql($payrollQuery);
		if(!empty($transaction)){
			$payrollamount=$transaction->net_salary;
		}else{
			$payrollamount=0;
		}
		return $payrollamount;
	}

	public static function getIncomeExpensesPeriodicExpense($monthPeriod,$branch,$staff){
		$expenseQuery="SELECT COALESCE(SUM(expenses.amount),0) as amount FROM expenses,profiles WHERE profiles.id=expenses.created_by
		 AND DATE_FORMAT(expenses.expense_date,'%Y-%m')='$monthPeriod'";
		switch($branch){
			case 0:
			if($staff === 0){
				$expenseQuery.= "";
			}else{
				$expenseQuery.= " AND profiles.managerId=$staff";
			}
			break;

			default:
			$expenseQuery.= " AND profiles.branchId=$branch";
			if($staff === 0){
				$expenseQuery.= "";
			}else{
				$expenseQuery.= " AND profiles.managerId=$staff";
			}
			break;
		}
		$expense=Expenses::model()->findBySql($expenseQuery);
		if(!empty($expense)){
			$expenseRepaid=$expense->amount;
		}else{
			$expenseRepaid=0;		
		}
		return $expenseRepaid;
	}

	public static function generateIncomeExpensesTabulation($collections,$branch,$staff){
		$html           = "";
		$months         = "";
		$monthlyTotalIncomes    = "";
		$monthlyTotalExpenses   = "";
		$monthlyNetIncome       = "";
		$monthlyTotalIncomeChange  = "";
		$monthlyTotalExpenseChange = "";
		if(!empty($collections)){
			$incomeDifference  = array();
			$expenseDifference = array();
			foreach($collections as $collection){
				$rawDate           = $collection['registrationDate'];
				$regMonthDate      = explode('-',$rawDate);
				$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
				$totalIncome       = (float)Analytics::getTotalIncome($rawDate,$branch,$staff);
				$totalExpenses     = (float)Analytics::getTotalIncomeExpenseExpenditure($rawDate,$branch,$staff);
				$netIncome         = (float)($totalIncome - $totalExpenses);
				array_push($incomeDifference,$totalIncome);
				$totalInserted     = count($incomeDifference);
				if($totalInserted > 1){
					$totalIncomeChange = $incomeDifference[$totalInserted - 1] - $incomeDifference[$totalInserted - 2];
				}else{
					$totalIncomeChange = 0;
				}
				array_push($expenseDifference,$totalExpenses);
				$insert     = count($expenseDifference);
				if($insert > 1){
					$totalExpenseChange = $expenseDifference[$insert - 1] - $expenseDifference[$insert - 2];
				}else{
					$totalExpenseChange = 0;
				}
				$months           .= "<td class='text-primary'>$registrationDate</td>";
				$monthlyTotalIncomes .= "<td>".number_format($totalIncome,2)."</td>"; 
				$monthlyTotalExpenses.= "<td>".number_format($totalExpenses,2)."</td>"; 
				$monthlyNetIncome     .= "<td>".number_format($netIncome,2)."</td>";  
				$monthlyTotalIncomeChange.= "<td>".number_format($totalIncomeChange,2)."</td>"; 
				$monthlyTotalExpenseChange.= "<td>".number_format($totalExpenseChange,2)."</td>"; 
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>TCL INCOME</td>$monthlyTotalIncomes</tr>";
			$html.= "<tr><td class='text-primary'>TOTAL EXPs</td>$monthlyTotalExpenses</tr>";
			$html.= "<tr><td class='text-primary'>NET INCOME</td>$monthlyNetIncome</tr>";
			$html.= "<tr><td class='text-primary'>CHNG TI</td>$monthlyTotalIncomeChange</tr>";
			$html.= "<tr><td class='text-primary'>CHNG EXPs</td>$monthlyTotalExpenseChange</tr>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getIncomeExpensesStats($disbursements,$branch,$staff){
		$statistics = array();
		$data_count = 0;
		foreach($disbursements as $gen){
			$rawDate           = $gen['registrationDate'];
			$regMonthDate      = explode('-',$rawDate);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered); 
			$totalIncome       = (float)Analytics::getTotalIncome($rawDate,$branch,$staff);
			$totalExpenses     = (float)Analytics::getTotalIncomeExpenseExpenditure($rawDate,$branch,$staff);
			$statistics[$data_count]['dateRegistered']= $registrationDate;
			$statistics[$data_count]['incomes']       = $totalIncome;
			$statistics[$data_count]['expenditures']  = $totalExpenses;
			$data_count++;
		}
		return $statistics;
	}

	public static function getIncomeExpensesGrowthLineGraph($cols,$cTitle,$cHolder,$branch,$staff){
		$turnovers = Analytics::getIncomeExpensesStats($cols,$branch,$staff);
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";
		$axisarray = array();
		$count = 0;
		foreach($turnovers as $turnover){
		  $axisarray[$count] = array($turnover['dateRegistered']);
		  $count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Total Amount";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		/* Performing */
		$incomes = array();
		$count = 0;
		foreach($turnovers as $generated) { 
			if(isset($generated["incomes"])){
				$incomes[$count] = array('y'=>$generated["incomes"]);  
			}else{ 
				$incomes[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Income",'color' => "#2b908f",'type' => "column",'yAxis'=>1, 'data' => $incomes);
		/* Non-Performing */
		$expenses = array();
		$count = 0;
		foreach($turnovers as $generated) { 
			if(isset($generated["expenditures"])){
				$expenses[$count] = array('y'=>$generated["expenditures"]);  
			}else{ 
				$expenses[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Expenses",'color' => "#e4d354",'type' => "column",'yAxis'=>1, 'data' => $expenses);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$cHolder.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}
	/***************

		PROFIT & LOSS

	***********************/
	public static function getTotalProfitLoss($monthPeriod,$branch,$staff){
		$payments    = Analytics::getProfitLossPeriodicPayments($monthPeriod,$branch,$staff);
		$expenses    = Analytics::getTotalIncomeExpenseExpenditure($monthPeriod,$branch,$staff);
		$totalProfit = $payments  - $expenses;
		return $totalProfit;
	}

	public static function getProfitLossPeriodicWriteOffs($monthPeriod,$branch,$staff){
		$writeOffQuery="SELECT COALESCE(SUM(write_offs.amount),0) AS amount FROM write_offs,loanaccounts
		WHERE write_offs.loanaccount_id=loanaccounts.loanaccount_id AND DATE_FORMAT(write_offs.created_at,'%Y-%m')='$monthPeriod'";
		switch($branch){
			case 0:
			if($staff === 0){
				$writeOffQuery.= "";
			}else{
				$writeOffQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$writeOffQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$writeOffQuery.= "";
			}else{
				$writeOffQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$repayments=WriteOffs::model()->findBySql($writeOffQuery);
		if(!empty($repayments)){
			$totalProfit=$repayments->amount;
		}else{
			$totalProfit=0;
		}
		return $totalProfit;
	}

	public static function getProfitLossPeriodicPayments($monthPeriod,$branch,$staff){
		$profitQuery="SELECT SUM(loanrepayments.interest_paid) AS interest_paid,SUM(loanrepayments.fee_paid) AS fee_paid,
		SUM(loanrepayments.penalty_paid) AS penalty_paid FROM loanrepayments,loanaccounts WHERE loanrepayments.loanaccount_id=loanaccounts.loanaccount_id
		AND loanrepayments.is_void IN('0','3','4') AND DATE_FORMAT(loanrepayments.repaid_at,'%Y-%m')='$monthPeriod'";
		switch($branch){
			case 0:
			if($staff === 0){
				$profitQuery.= "";
			}else{
				$profitQuery.= " AND loanaccounts.rm=$staff";
			}
			break;

			default:
			$profitQuery.= " AND loanaccounts.branch_id=$branch";
			if($staff === 0){
				$profitQuery.= "";
			}else{
				$profitQuery.= " AND loanaccounts.rm=$staff";
			}
			break;
		}
		$repayments=Loanrepayments::model()->findBySql($profitQuery);
		if(!empty($repayments)){
			$totalProfit=$repayments->interest_paid + $repayments->fee_paid + $repayments->penalty_paid;
		}else{
			$totalProfit=0;
		}
		return $totalProfit;
	}

	public static function generateProfitLossTabulation($collections,$branch,$staff){
		$html           = "";
		$months         = "";
		$monthlyProfitLoss    = "";
		$monthlyProfitLossChange = "";
		if(!empty($collections)){
			$loss= array();
			foreach($collections as $collection){
				$rawDate           = $collection['registrationDate'];
				$regMonthDate      = explode('-',$rawDate);
				$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
				$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered);
				$totalProfit       = (float)Analytics::getTotalProfitLoss($rawDate,$branch,$staff);
				array_push($loss,$totalProfit);
				$inserts     = count($loss);
				if($inserts > 1){
					$profitLossChange = $loss[$inserts - 1] - $loss[$inserts - 2];
				}else{
					$profitLossChange = 0;
				}
				$months                 .= "<td class='text-primary'>$registrationDate</td>";
				$monthlyProfitLoss      .= "<td>".number_format($totalProfit,2)."</td>"; 
				$monthlyProfitLossChange.= "<td>".number_format($profitLossChange,2)."</td>"; 
			}
			$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'><tbody>";
			$html.= "<tr><td class='text-primary'>MONTH</td>$months</tr>";
			$html.= "<tr><td class='text-primary'>P & L</td>$monthlyProfitLoss</tr>";
			$html.= "<tr><td class='text-primary'>CHNG IN P/L</td>$monthlyProfitLossChange</tr>";
			$cont = $html;
		}else{
			$cont = 0;
		}
		return $cont;
	}

	public static function getProfitLossStats($disbursements,$branch,$staff){
		$statistics = array();
		$data_count = 0;
		foreach($disbursements as $gen){
			$rawDate           = $gen['registrationDate'];
			$regMonthDate      = explode('-',$rawDate);
			$dateRegistered    = $regMonthDate[1]."-".$regMonthDate[0];
			$registrationDate  = CommonFunctions::getRespectiveMonth($dateRegistered); 
			$totalProfit       = (float)Analytics::getTotalProfitLoss($rawDate,$branch,$staff);
			$statistics[$data_count]['dateRegistered']= $registrationDate;
			$statistics[$data_count]['profits']       = $totalProfit;
			$data_count++;
		}
		return $statistics;
	}

	public static function getProfitLossGrowthLineGraph($cols,$cTitle,$cHolder,$branch,$staff){
		$turnovers = Analytics::getProfitLossStats($cols,$branch,$staff);
		$chart = new Highchart();
		$chart->chart->renderTo = $cHolder;
		$chart->chart->zoomType = "xy";
		$chart->chart->type = "column";
		$chart->title->text = $cTitle; 
		$chart->title->style->fontSize = "15px";
		$axisarray = array();
		$count = 0;
		foreach($turnovers as $turnover){
		  $axisarray[$count] = array($turnover['dateRegistered']);
		  $count++; 
		}
		$chart->xAxis->categories           = $axisarray;
		$chart->xAxis->title->text          = "Month on Month (MoM)";
		$chart->xAxis->title->style->color  = "#434348";
		$chart->xAxis->labels->style->color = "#434348";
		$chart->xAxis->style->fontSize      = "12px";
		/* Left Y-Axis */
		$leftYaxis                       = new HighchartOption();
		$leftYaxis->title->text          = "Total Amount";
		$leftYaxis->title->style->color  = "#434348";
		$leftYaxis->labels->style->color = "#434348";
		$leftYaxis->style->fontSize      = "12px";
		/* Right Y-Axis */
		$rightYaxis                       = new HighchartOption();
		$rightYaxis->title->text          = "";
		$rightYaxis->opposite             = 1;
		
		$chart->yAxis = array($rightYaxis,$leftYaxis);
		$chart->tooltip->formatter = new HighchartJsExpr( "function() { return '<b>'+ this.x +'</b><br/>'+ this.series.name +': '+ this.y +'<br/>'}");
		$profitLosses = array();
		$count = 0;
		foreach($turnovers as $generated) { 
			if(isset($generated["profits"])){
				$profitLosses[$count] = array('y'=>$generated["profits"]);  
			}else{ 
				$profitLosses[$count] = 0;  
			} 
			$count++;  
		}
		$chart->series[] = array('name' => "Profit/Loss",'color' => "#90ed7d",'type' => "column",'yAxis'=>1, 'data' => $profitLosses);
		$chart->tooltip->pointFormat = new HighchartJsExpr("function() { return parseFloat(this.value); }");
		$chart->addExtraScript('export', 'http://code.highcharts.com/modules/', 'exporting.js');
		$chart->includeExtraScripts(array('export'));
		$chart->addExtraScript('theme', 'http://www.highcharts.com/js/themes/', 'grid.js');
		$chart->includeExtraScripts(array('theme'));
		$chart->printScripts();
		$chart->credits = array('enabled'=>false);
		$package        = '<div id="'.$cHolder.'"></div>';
		$package       .= '<script type="text/javascript">';
		$package       .= $chart->render("chart2");
		$package       .= '</script>';
		echo  $package;
	}

	/***************

		COMMENTS LOGIC

	*****************/
	public static function getBranchCommentableActiveAccounts($branch){
		$activeQuery = "SELECT COUNT(DISTINCT loanaccount_id) AS totalActiveAccounts FROM loanaccounts WHERE loan_status IN('2','5','6','7')
		AND branch_id=$branch AND user_id NOT IN(SELECT id FROM profiles WHERE profileType IN('STAFF'))";
		$accounts = Yii::app()->db->createCommand($activeQuery)->queryRow();
		return !empty($accounts) ?  $accounts['totalActiveAccounts'] : 0;
	}

	public static function getStaffCommentableActiveAccounts($staff){
		$activeQuery = "SELECT COUNT(DISTINCT loanaccount_id) AS totalActiveAccounts FROM loanaccounts
		WHERE loan_status IN('2','5','6','7') AND rm=$staff AND user_id NOT IN(SELECT id FROM profiles WHERE profileType IN('STAFF'))";
		$accounts = Yii::app()->db->createCommand($activeQuery)->queryRow();
		return !empty($accounts) ? $accounts['totalActiveAccounts'] : 0;
	}

	public static function getBranchCommentedActiveAccounts($branch,$cType,$start_date,$end_date){
		$commentQuery = "SELECT COUNT(DISTINCT loanaccount_id) AS totalCommented
		FROM loancomments WHERE type_id !=1 AND branch_id=$branch AND (DATE(commented_at) BETWEEN '$start_date' AND '$end_date')
		AND user_id NOT IN(SELECT id FROM profiles WHERE profileType IN('STAFF'))";
		if($cType != 0){
			$commentQuery.=" AND type_id=$cType";
		}
		$comments = Yii::app()->db->createCommand($commentQuery)->queryRow();
		return !empty($comments) ? $comments['totalCommented'] :  0;
	}

	public static function getStaffCommentedActiveAccounts($staff,$cType,$start_date,$end_date){
		$commentQuery = "SELECT COUNT(DISTINCT(loanaccount_id)) AS totalCommented FROM loancomments WHERE type_id !=1 AND commented_by=$staff 
		AND (DATE(commented_at) BETWEEN '$start_date' AND '$end_date') AND user_id NOT IN(SELECT id FROM profiles WHERE profileType IN('STAFF'))";
		if($cType != 0){
			$commentQuery.=" AND type_id=$cType";
		}
		$comments = Yii::app()->db->createCommand($commentQuery)->queryRow();
		return !empty($comments) ? $comments['totalCommented'] :  0;
	}

	public static function getCommentedPercentageRating($commentable,$commented){
		return $commentable < 1 ? 0.00 : number_format(round(($commented/$commentable)*100,2),2);
	}

	public static function getCommentedRatingHighlighter($percentRating){
		return Performance::determinePerformanceColor($percentRating);
	}

	public static function getBranchCommentsTabulation($branch,$cType,$start_date,$end_date){
		$html = "";
		$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'>";
		$html.= "<thead>
					<tr style='font-weight:bolder !important;'>
						<td class='text-primary'>#</td>
						<td class='text-primary'>BRANCH</td>
						<td class='text-primary text-center'>ACTIVE ACCs</td>
						<td class='text-primary text-center'>COMMENTED</td>
						<td class='text-primary text-center'>RATING</td>
					</tr>
				</thead><tbody>";
		$branches = $branch == 0 ? Reports::getAllBranches() : Branch::model()->findByPk($branch);
		if(!empty($branches)){
			$counter          = 1;
			$totalCommentable = 0;
			$totalCommented   = 0;
			switch(count($branches)){
				case 1:
				$brID        = $branches->branch_id;
				$brName      = strtoupper($branches->name);
				$commentable = Analytics::getBranchCommentableActiveAccounts($brID);
				$commented   = Analytics::getBranchCommentedActiveAccounts($brID,$cType,$start_date,$end_date);
				$rating      = Analytics::getCommentedPercentageRating($commentable,$commented);
				$indicator   = Analytics::getCommentedRatingHighlighter($rating);
				$html       .= "<tr>
				                  	<td>$counter</td>
									<td>$brName</td>
									<td class='text-center'>$commentable</td>
									<td class='text-center'>$commented</td>
									<td class='text-center' style='".$indicator."'>$rating % </td>
								</tr>";
				$totalCommentable+= $commentable;
				$totalCommented  += $commented;
				break;

				default:
				foreach($branches AS $branch){
					$brID        = $branch->branch_id;
					$brName      = strtoupper($branch->name);
					$commentable = Analytics::getBranchCommentableActiveAccounts($brID);
					$commented   = Analytics::getBranchCommentedActiveAccounts($brID,$cType,$start_date,$end_date);
					$rating      = Analytics::getCommentedPercentageRating($commentable,$commented);
					$indicator   = Analytics::getCommentedRatingHighlighter($rating);
					$html       .= "<tr>
					                  	<td>$counter</td>
										<td>$brName</td>
										<td class='text-center'>$commentable</td>
										<td class='text-center'>$commented</td>
										<td class='text-center' style='".$indicator."'>$rating % </td>
									</tr>";
					$totalCommentable+= $commentable;
					$totalCommented  += $commented;
					$counter++;
				}
				break;
			}
			$totalRating      = Analytics::getCommentedPercentageRating($totalCommentable,$totalCommented);
			$totalIndicator   = Analytics::getCommentedRatingHighlighter($totalRating);
			$html       .= "<tr>
								<td colspan='2' style='font-weight: bolder !important;'>TOTALS</td>
								<td class='text-center'>$totalCommentable</td>
								<td class='text-center'>$totalCommented</td>
								<td class='text-center' style='".$totalIndicator."'>$totalRating %  </td>
							</tr>";
			$html   .= "</tbody></table></div>";
			$content = $html;
		}else{
			$content = 0;
		}
		return $content;
	}

	public static function getStaffCommentsTabulation($branch,$staff,$cType,$start_date,$end_date){
		$html = "";
		$html.= "<div class='table-responsive'><table class='table table-condensed table-bordered'>";
		$html.= "<thead>
					<tr style='font-weight:bolder !important;'>
						<td class='text-primary'>#</td>
						<td class='text-primary'>NAME</td>
						<td class='text-primary'>BRANCH</td>
						<td class='text-primary text-center'>ACTIVE ACCs</td>
						<td class='text-primary text-center'>COMMENTED</td>
						<td class='text-primary text-center'>RATING</td>
					</tr>
				</thead>
				<tbody>";
		$members = $staff == 0 ? StaffFunctions::getCommentDashboardStaffMembers() : Profiles::model()->findByPk($staff);
		if(!empty($members)){
			$counter          = 1;
			$totalCommentable = 0;
			$totalCommented   = 0;
			switch(count($members)){
				case 1:
				$staffID     = $members->id;
				$memberName  = $members->ProfileFullName;
				$brName      = $members->ProfileBranch;
				$commentable = Analytics::getStaffCommentableActiveAccounts($staffID);
				$commented   = Analytics::getStaffCommentedActiveAccounts($staffID,$cType,$start_date,$end_date);
				$rating      = Analytics::getCommentedPercentageRating($commentable,$commented);
				$indicator   = Analytics::getCommentedRatingHighlighter($rating);
				$html       .= "<tr>
				                  	<td>$counter</td>
									<td>$memberName</td>
									<td>$brName</td>
									<td class='text-center'>$commentable</td>
									<td class='text-center'>$commented</td>
									<td class='text-center' style='".$indicator."'>$rating % </td>
								</tr>";
				$totalCommentable+= $commentable;
				$totalCommented  += $commented;
				break;

				default:
				foreach($members AS $member){
					$staffID     = $member->id;
					$brName      = $member->ProfileBranch;
					$memberName  = $member->ProfileFullName;
					$commentable = Analytics::getStaffCommentableActiveAccounts($staffID);
					$commented   = Analytics::getStaffCommentedActiveAccounts($staffID,$cType,$start_date,$end_date);
					$rating      = Analytics::getCommentedPercentageRating($commentable,$commented);
					$indicator   = Analytics::getCommentedRatingHighlighter($rating);
					$html       .= "<tr>
										<td>$counter</td>
										<td>$memberName</td>
										<td>$brName</td>
										<td class='text-center'>$commentable</td>
										<td class='text-center'>$commented</td>
										<td class='text-center' style='".$indicator."'>$rating % </td>
									</tr>";
					$totalCommentable+= $commentable;
					$totalCommented  += $commented;
					$counter++;
				}
				break;
			}
			$totalRating      = Analytics::getCommentedPercentageRating($totalCommentable,$totalCommented);
			$totalIndicator   = Analytics::getCommentedRatingHighlighter($totalRating);
			$html       .= "<tr>
								<td colspan='3' style='font-weight: bolder !important;'>TOTALS</td>
								<td class='text-center'>$totalCommentable</td>
								<td class='text-center'>$totalCommented</td>
								<td class='text-center' style='".$totalIndicator."'>$totalRating %</td>
							</tr>";
			$html   .= "</tbody></table></div>";
			$content = $html;
		}else{
			$content = 0;
		}
		return $content;
	}

}