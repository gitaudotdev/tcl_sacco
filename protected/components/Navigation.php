<?php

class Navigation{

    public static function selectDisplayableMenu(){
		$menu ='';
		$menu.='<ul class="nav modified_nav">';
        /************
         * 
         * ADMINISTRATION
         * 
         * *******************/
        if(Navigation::checkIfAuthorized(192) == 1){
            $menu.='<li>
                        <a data-toggle="collapse" href="#admin_menu" class="collapsed">
                            <i class="now-ui-icons ui-2_settings-90"></i>
                            <p>Administration</p>
                        </a>
                    </li>
                    <div class="collapse" id="admin_menu">
                     <ul class="nav submenu_modified">';
        }

        if(Navigation::checkIfAuthorized(299) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('auths/admin').'">
                  <i class="now-ui-icons design_vector"></i><p>Authorizations</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(274) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('clusters/admin').'">
                  <i class="now-ui-icons business_chart-bar-32"></i><p>Clusters</p></a></li>';
        }
        if(Navigation::checkIfAuthorized(256) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('branch/admin').'">
                  <i class="now-ui-icons education_atom"></i><p>Branches</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(292) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('chamaLocations/admin').'">
                    <i class="now-ui-icons design_vector"></i>
                    <p>Chama Locations</p>
                </a>
            </li>';
		}

        if(Navigation::checkIfAuthorized(295) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('chamaOrganizations/admin').'">
                    <i class="now-ui-icons business_bank"></i>
                    <p>Chama Organizations</p>
                </a>
            </li>';
		}
        
        if(Navigation::checkIfAuthorized(251) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('commentTypes/admin').'">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>Comment Types</p>
                </a>
            </li>';
        }
        if(Navigation::checkIfAuthorized(270) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('collateraltypes/admin').'">
                    <i class="now-ui-icons text_align-center"></i>
                    <p>Collateral Types</p>
                </a>
            </li>';
        }
		if(Navigation::checkIfAuthorized(266) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('assetType/admin').'">
                    <i class="now-ui-icons ui-2_time-alarm"></i>
                    <p>Asset Types</p>
                </a>
            </li>';
		}
		if(Navigation::checkIfAuthorized(262) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('expenseTypes/admin').'">
                    <i class="now-ui-icons text_align-center"></i>
                    <p>Expense Types</p>
                </a>
            </li>';
		}
        if(Navigation::checkIfAuthorized(278) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('incomeTypes/admin').'">
                    <i class="now-ui-icons business_bulb-63"></i>
                    <p>Income Types</p>
                </a>
            </li>';
		}
		if(Navigation::checkIfAuthorized(111) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('alertConfigs/admin').'">
                    <i class="now-ui-icons ui-1_settings-gear-63"></i>
                    <p>SMS Settings</p>
                </a>
            </li>';
        }
		if(Navigation::checkIfAuthorized(84) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('performanceSettings/admin'). '">
                    <i class="now-ui-icons users_circle-08"></i>
                    <p>Payroll Settings</p>
                </a>
            </li>';
		}
        if(Navigation::checkIfAuthorized(77) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('roles/admin').'">
                    <i class="now-ui-icons education_atom"></i>
                    <p>Roles & Permissions</p>
                </a>
            </li>';
		}
		if(Navigation::checkIfAuthorized(84) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('organization/admin'). '">
                    <i class="now-ui-icons media-1_button-power"></i>
                    <p>Company Settings</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(84) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('notices/admin'). '">
                    <i class="now-ui-icons files_paper"></i>
                    <p>Notice Board</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(84) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('logs/admin'). '">
                    <i class="now-ui-icons media-2_sound-wave"></i>
                    <p>Audit Trail</p>
                </a>
            </li>';
		}

        if(Navigation::checkIfAuthorized(192) == 1 ){
            $menu.='</ul></div>';
        }

        /********************
         * 
        *  ANALYTICS
        * 
        * ********************/
        if(Navigation::checkIfAuthorized(242) == 1){
        $menu.='<li>
                    <a data-toggle="collapse" href="#analytics_sub_menu" class="collapsed">
                        <i class="now-ui-icons media-2_sound-wave"></i>
                        <p>Analytics</p>
                    </a>
                </li>
                <div class="collapse" id="analytics_sub_menu">
                    <ul class="nav submenu_modified">';
        }
        
        if(Navigation::checkIfAuthorized(233) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/customerGrowth').'">
                    <i class="now-ui-icons business_chart-bar-32"></i><p>Customer Growth</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(239) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/disbursedPaidLoans').'">
                    <i class="now-ui-icons business_bank"></i><p>Disbursed vs Paid</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(230) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/loanPerformance').'">
                    <i class="now-ui-icons education_atom"></i><p>Loan Performance</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(238) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/overallCollectionPerformance').'">
                    <i class="now-ui-icons text_align-center"></i><p>Collection</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(241) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/interestGeneratedPaid').'">
                    <i class="now-ui-icons files_paper"></i><p>Int. Generated/Paid</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(237) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/turnovers').'">
                    <i class="now-ui-icons media-2_sound-wave"></i><p>Turnovers</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(236) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/recoveredInterestPrinciple').'">
            <i class="now-ui-icons ui-1_bell-53"></i><p>Recovered PR</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(231) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/profitLoss').'">
            <i class="now-ui-icons files_box"></i><p>Profit &amp; Loss</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(235) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/assetQuality').'">
            <i class="now-ui-icons shopping_bag-16"></i><p>Assets Ratio</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(234) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/assetReturns').'">
            <i class="now-ui-icons business_bulb-63"></i><p>Return on Assets</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(228) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/riskAmounts').'">
            <i class="now-ui-icons ui-2_time-alarm"></i><p>Risk Amounts</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(240) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/riskCounts').'">
            <i class="now-ui-icons design_bullet-list-67"></i><p>Risk Counts</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(229) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/incomeExpenses').'">
            <i class="now-ui-icons shopping_credit-card"></i><p>Income &amp; Expenses</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(232) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/savings').'">
            <i class="now-ui-icons business_money-coins"></i><p>Savings</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(242) == 1){
            $menu.='</ul></div>';
        }
		/********************
         * 
         * DASHBOARD
         * 
         * ********************/
		if(Navigation::checkIfAuthorized(118) == 1){
    	$menu.='<li>
                  <a data-toggle="collapse" href="#dashboard_submenu" class="collapsed">
                      <i class="now-ui-icons design_app"></i>
                      <p>Dashboards</p>
                  </a>
              </li>
              <div class="collapse" id="dashboard_submenu">
                  <ul class="nav submenu_modified">';
        }
        
		if(Navigation::checkIfAuthorized(116) == 1){
			$menu.='<li><a href="'.Yii::app()->createUrl('dashboard/index').'">
                  <i class="now-ui-icons ui-1_settings-gear-63"></i><p>System</p></a></li>';
		}

		if(Navigation::checkIfAuthorized(118) == 1){
			$menu.='<li><a href="'.Yii::app()->createUrl('dashboard/branch').'">
                  <i class="now-ui-icons business_bank"></i><p>Branch</p></a></li>';
		}

		if(Navigation::checkIfAuthorized(117) == 1){
			$menu.='<li><a href="'.Yii::app()->createUrl('dashboard/staff').'">
                  <i class="now-ui-icons users_circle-08"></i><p>Staff</p></a></li>';
		}

        if(Navigation::checkIfAuthorized(257) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('dashboard/comments').'">
                  <i class="now-ui-icons education_atom"></i><p>Comments</p></a></li>';
        }

		if(Navigation::checkIfAuthorized(118) == 1){
			$menu.='</ul></div>';
		}

		/***************
         * 
         * SMS SETTINGS
         * 
         * ***************/
		if(Navigation::checkIfAuthorized(112) == 1){
		$menu.='<li>
              <a data-toggle="collapse" href="#alerts_submenu" class="collapsed">
                  <i class="now-ui-icons ui-1_bell-53"></i>
                  <p>SMS Alerts</p>
              </a>
          </li>
          <div class="collapse" id="alerts_submenu">
              <ul class="nav submenu_modified">';
		}
        
        if(Navigation::checkIfAuthorized(112) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('smsAlerts/balanceUpdate').'">
                    <i class="now-ui-icons shopping_tag-content"></i>
                    <p>Balances</p>
                </a>
            </li>';
		}

        if(Navigation::checkIfAuthorized(304) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('groupSMS/auths').'">
                    <i class="now-ui-icons media-2_sound-wave"></i>
                    <p>Auth Level SMS</p>
                </a>
            </li>';
		}

        if(Navigation::checkIfAuthorized(288) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('groupSMS/admin').'">
                    <i class="now-ui-icons media-2_sound-wave"></i>
                    <p>Group SMS</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(113) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('smsAlerts/loansDue').'">
                    <i class="now-ui-icons ui-1_send"></i>
                    <p>Loans Due</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(114) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('smsAlerts/reminders').'">
                    <i class="now-ui-icons ui-2_time-alarm"></i>
                    <p>Reminders</p>
                </a>
            </li>';
		}

        if(Navigation::checkIfAuthorized(133) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('smsAlerts/weeklyPerformance').'">
                    <i class="now-ui-icons business_chart-bar-32"></i>
                    <p>Performance</p>
                </a>
            </li>';
        }

		if(Navigation::checkIfAuthorized(115) == 1){
			$menu.='<li>
                <a href="'. Yii::app()->createUrl('smsAlerts/admin').'">
                    <i class="now-ui-icons shopping_bag-16"></i>
                    <p>SMS Logs</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(112) == 1){
			$menu.='</ul></div>';
		}
		/************************
         * 
         * PROFILES
         * 
         * **********************/
		if(Navigation::checkIfAuthorized(17) == 1){
			$menu.='<li>
                <a data-toggle="collapse" href="#borrower_submenu" class="collapsed">
                    <i class="now-ui-icons users_circle-08"></i>
                    <p>Profiles</p>
                </a>
            </li>
            <div class="collapse" id="borrower_submenu">
                <ul class="nav submenu_modified">';
		}

		if(Navigation::checkIfAuthorized(17) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('profiles/admin').'">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>Members & Staffs</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(137) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('chamas/admin').'">
                    <i class="now-ui-icons text_align-center"></i>
                    <p>Chamas</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(9) == 1){
			$menu.=' <li>
                  <a href="'.Yii::app()->createUrl('#').'">
                      <i class="now-ui-icons ui-1_send"></i>
                      <p>Invite</p>
                  </a>
              </li>';
		}

		if(Navigation::checkIfAuthorized(17) == 1){
			$menu.='</ul></div>';
		}
		/**************
         * 
         * LOANS
         * 
         * 
		******/
		if(Navigation::checkIfAuthorized(32) == 1){
			$menu.='<li>
                  <a  data-toggle="collapse" href="#loans_Submenu" class="collapsed">
                      <i class="now-ui-icons objects_diamond"></i>
                      <p>Loans</p>
                  </a>
              </li>
              <div class="collapse" id="loans_Submenu">
                  <ul class="nav submenu_modified">';
		}

        if(Navigation::checkIfAuthorized(301) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('loanaccounts/calculator').'">
                    <i class="now-ui-icons shopping_tag-content"></i>
                    <p>Calculator</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(32) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('loanaccounts/admin').'">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>APPLICATIONS</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(125) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('loaninterests/admin').'">
                    <i class="now-ui-icons business_chart-bar-32"></i>
                    <p>Interests</p>
                </a>
            </li>';
		}
        
        if(Navigation::checkIfAuthorized(125) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('penalties/admin').'">
                    <i class="now-ui-icons business_chart-bar-32"></i>
                    <p>Accrued Penalties</p>
                </a>
            </li>';
		}

        if(Navigation::checkIfAuthorized(47) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('writeOffs/admin').'">
                    <i class="now-ui-icons ui-2_time-alarm"></i>
                    <p>Write Offs</p>
                </a>
            </li>';
		}
        if(Navigation::checkIfAuthorized(141) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('collateral/admin').'">
                    <i class="now-ui-icons shopping_bag-16"></i>
                    <p>Collateral</p>
                </a>
            </li>';
        }
       
        if(Navigation::checkIfAuthorized(109) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('guarantors/admin').'">
                    <i class="now-ui-icons files_paper"></i>
                    <p>Guarantors</p>
                </a>
            </li>';
        }

		if(Navigation::checkIfAuthorized(32) == 1){
			$menu.='</ul></div>';
		}

		/******************
         * 
         * SAVINGS
         * 
         * *******************/
		if(Navigation::checkIfAuthorized(53) == 1){
			$menu.='<li>
                <a  data-toggle="collapse" href="#savings_submenu" class="collapsed">
                    <i class="now-ui-icons business_bank"></i>
                    <p>Savings</p>
                </a>
            </li>
            <div class="collapse" id="savings_submenu">
                <ul class="nav submenu_modified">';
		}

		if(Navigation::checkIfAuthorized(53) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('savingaccounts/admin').'">
                    <i class="now-ui-icons shopping_credit-card"></i>
                    <p>Accounts</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(153) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('withdrawals/admin').'">
                    <i class="now-ui-icons files_paper"></i>
                    <p>Withdrawals</p>
                </a>
            </li>';
		}

        if(Navigation::checkIfAuthorized(154) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('transfers/admin').'">
                    <i class="now-ui-icons business_bank"></i>
                    <p>Transfers</p>
                </a>
            </li>';
        }

        if(Navigation::checkIfAuthorized(190) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('savingpostings/admin').'">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>Interests</p>
                </a>
            </li>';
        }

		if(Navigation::checkIfAuthorized(59) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('savingtransactions/admin').'">
                    <i class="now-ui-icons media-2_sound-wave"></i>
                    <p>Transactions</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(53) == 1){
			$menu.='</ul></div>';
		}
		/*****************
         * 
         * REPAYMENTS
         * 
         * *********************/
		if(Navigation::checkIfAuthorized(147) == 1){
			$menu.='<li>
                <a  data-toggle="collapse" href="#repayments_Submenu" class="collapsed">
                    <i class="now-ui-icons business_money-coins"></i>
                    <p>Repayments</p>
                </a>
            </li>
            <div class="collapse" id="repayments_Submenu">
                <ul class="nav submenu_modified">';
		}

		if(Navigation::checkIfAuthorized(147) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('loanrepayments/admin').'">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>Loan Payments</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(66) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('strayRepayments/admin').'">
                    <i class="now-ui-icons ui-1_zoom-bold"></i>
                    <p>Stray Payments</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(32) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('loanaccounts/missedRepayments').'">
                    <i class="now-ui-icons ui-2_time-alarm"></i>
                    <p>Missed Payments</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(147) == 1){
			$menu.='</ul></div>';
		}
		/********
         * 
         * REPORTS
         * 
         * ************/
		if(Navigation::checkIfAuthorized(84) == 1){
			$menu.='<li>
                <a  data-toggle="collapse" href="#reports_Submenu" class="collapsed">
                    <i class="now-ui-icons files_box"></i>
                    <p>Reports</p>
                </a>
            </li>
            <div class="collapse" id="reports_Submenu">
                <ul class="nav submenu_modified">';
		}

        if(Navigation::checkIfAuthorized(201) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('reports/executiveSummary').'">
                  <i class="now-ui-icons business_chart-bar-32"></i><p>Snap Preview</p></a></li>';
        }

        if(Navigation::checkIfAuthorized(256) == 1){
            $menu.='<li><a href="'.Yii::app()->createUrl('loancomments/admin').'">
                  <i class="now-ui-icons education_atom"></i><p>Comments</p></a></li>';
        }

		if(Navigation::checkIfAuthorized(84) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('profiles/membersReport'). '">
                    <i class="now-ui-icons users_circle-08"></i>
                    <p>Members/Users</p>
                </a>
            </li>';
		}

        if(Navigation::checkIfAuthorized(178) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('loanaccounts/dailyAccountReport').'">
                    <i class="now-ui-icons files_box"></i>
                    <p>Daily Report</p>
                </a>
            </li>';
        }

        if(Navigation::checkIfAuthorized(82) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('loanaccounts/disbursedAccounts'). '">
                    <i class="now-ui-icons design_vector"></i>
                    <p>Disbursement</p>
                </a>
            </li>';
        }


        if(Navigation::checkIfAuthorized(32) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('loanaccounts/due').'">
                    <i class="now-ui-icons ui-1_bell-53"></i>
                    <p>Due Loans</p>
                </a>
            </li>';
        }

        if(Navigation::checkIfAuthorized(196) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('interestFreezes/admin'). '">
                    <i class="now-ui-icons text_align-center"></i>
                    <p>Frozen Loans</p>
                </a>
            </li>';
        }

        if(Navigation::checkIfAuthorized(197) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('writeOffs/writeoffsReport'). '">
                    <i class="now-ui-icons files_paper"></i>
                    <p>Loan Write Off</p>
                </a>
            </li>';
        }

		if(Navigation::checkIfAuthorized(85) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('loanrepayments/accountCollections'). '">
                    <i class="now-ui-icons shopping_bag-16"></i>
                    <p>Collections</p>
                </a>
            </li>';
		}

		if(Navigation::checkIfAuthorized(90) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('loanaccounts/profitAndLoss'). '">
                    <i class="now-ui-icons business_money-coins"></i>
                    <p>Profit/Loss</p>
                </a>
            </li>';
		}
        if(Navigation::checkIfAuthorized(195) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('savingaccounts/savingAccountsReport'). '">
                    <i class="now-ui-icons business_bank"></i>
                    <p>Saving Accounts</p>
                </a>
            </li>';
        }
        if(Navigation::checkIfAuthorized(194) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('assets/assetsReport').'">
                    <i class="now-ui-icons education_atom"></i>
                    <p>Assets</p>
                </a>
            </li>';
        }
        if(Navigation::checkIfAuthorized(199) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('collateral/collateralReport').'">
                    <i class="now-ui-icons shopping_bag-16"></i>
                    <p>Collateral</p>
                </a>
            </li>';
        }
		if(Navigation::checkIfAuthorized(84) == 1){
			$menu.='</ul></div>';
		}
		/**********
         * 
         * ACCOUNTING
         * 
         * ***************/
		if(Navigation::checkIfAuthorized(87) == 1){
			$menu.='<li>
                <a  data-toggle="collapse" href="#accounting_Submenu" class="collapsed">
                    <i class="now-ui-icons business_briefcase-24"></i>
                    <p>Accounting</p>
                </a>
            </li>
            <div class="collapse" id="accounting_Submenu">
                <ul class="nav submenu_modified">';
		}

        if(Navigation::checkIfAuthorized(302) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('accounting/accountBalance').'">
                    <i class="now-ui-icons business_money-coins"></i>
                    <p>B2C Balance</p>
                </a>
            </li>';
        }

        if(Navigation::checkIfAuthorized(168) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('airtime/admin').'">
                    <i class="now-ui-icons shopping_credit-card"></i>
                    <p>Airtime</p>
                </a>
            </li>';
        }
        if(Navigation::checkIfAuthorized(243) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('fixedPayments/admin').'">
                    <i class="now-ui-icons shopping_tag-content"></i>
                    <p>Fixed Payments</p>
                </a>
            </li>';
        }
        if(Navigation::checkIfAuthorized(208) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('outPayments/admin').'">
                    <i class="now-ui-icons business_money-coins"></i>
                    <p>Out Payments</p>
                </a>
            </li>';
        }
		if(Navigation::checkIfAuthorized(192) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('expenses/admin').'">
                    <i class="now-ui-icons shopping_credit-card"></i>
                    <p>Expenses</p>
                </a>
            </li>';
		}
		if(Navigation::checkIfAuthorized(105) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('incomes/admin').'">
                    <i class="now-ui-icons shopping_box"></i>
                    <p>Incomes</p>
                </a>
            </li>';
		}
		if(Navigation::checkIfAuthorized(145) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('assets/admin').'">
                    <i class="now-ui-icons education_atom"></i>
                    <p>Assets</p>
                </a>
            </li>';
		}
		if(Navigation::checkIfAuthorized(87) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('accounting/cashflowaccumulated'). '">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>Cash Flow</p>
                </a>
            </li>';
		}
		if(Navigation::checkIfAuthorized(88) == 1){
			$menu.='<li>
	              <a href="'.Yii::app()->createUrl('accounting/cashflowmonthly'). '">
	                  <i class="now-ui-icons ui-1_calendar-60"></i>
	                  <p>Profitability</p>
	              </a>
	          </li>';
		}
		if(Navigation::checkIfAuthorized(89) == 1){
			$menu.='<li>
              <a href="'.Yii::app()->createUrl('accounting/cashflowprojection'). '">
                  <i class="now-ui-icons media-2_sound-wave"></i>
                  <p>Cash Flow Projection</p>
              </a>
          </li>';
		}
		if(Navigation::checkIfAuthorized(86) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('accounting/balancesheet'). '">
                    <i class="now-ui-icons objects_diamond"></i>
                    <p>Balance Sheet</p>
                </a>
            </li>';
		}
		if(Navigation::checkIfAuthorized(87) == 1){
			$menu.='</ul></div>';
		}
        /************
         * 
         * HUMAN RESOURCE
         * 
         ********************/
        if(Navigation::checkIfAuthorized(192) == 1){
            $menu.='<li>
                    <a data-toggle="collapse" href="#humanResources_submenu" class="collapsed">
                        <i class="now-ui-icons business_badge"></i>
                        <p>HR Management</p>
                    </a>
                </li>
                <div class="collapse" id="humanResources_submenu">
                    <ul class="nav submenu_modified">';
        }
        if(Navigation::checkIfAuthorized(23) == 1){
			$menu.='<li>
                <a href="'.Yii::app()->createUrl('profiles/staff').'">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>Staff</p>
                </a>
            </li>';
		}     

        if(Navigation::checkIfAuthorized(28) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('profiles/payroll').'">
                    <i class="now-ui-icons objects_diamond"></i>
                    <p>Payroll</p>
                </a>
            </li>';
        }

        if(Navigation::checkIfAuthorized(164) == 1){
            $menu.='<li>
                <a href="'.Yii::app()->createUrl('payroll/admin').'">
                    <i class="now-ui-icons design_bullet-list-67"></i>
                    <p>Payroll Logs</p>
                </a>
            </li>';
        }

        if(Navigation::checkIfAuthorized(187) == 1){
            $menu.= '<li>
                        <a href="'.Yii::app()->createUrl('leaves/admin').'">
                            <i class="now-ui-icons ui-2_time-alarm"></i>
                            <p>Leave Records</p>
                        </a>
                    </li>';
        }

        if(Navigation::checkIfAuthorized(120) == 1){
            $menu.= '<li>
                        <a href="'.Yii::app()->createUrl('leaveApplications/admin').'">
                            <i class="now-ui-icons shopping_tag-content"></i>
                            <p>Leave Requests</p>
                        </a>
                    </li>';
        }
        if(Navigation::checkIfAuthorized(119) == 1){
            $menu.=' <li>
                        <a href="'.Yii::app()->createUrl('folders/admin').'">
                            <i class="now-ui-icons files_box"></i>
                            <p>Document Hub</p>
                        </a>
                    </li>';
        }
        if(Navigation::checkIfAuthorized(192) == 1){
            $menu.='</ul></div>';
        }
		$menu.='</ul>';
		return $menu;
	}

    public static function checkIfAuthorized($permissionId){
        $userId    = Yii::app()->user->user_id;
        $authQuery = "SELECT * FROM role_permission,user_role WHERE user_role.role_id=role_permission.role_id
        AND user_role.user_id=$userId AND role_permission.permission_id=$permissionId";
        $roles    = RolePermission::model()->findAllBySql($authQuery);
        return !empty($roles) ? 1 : 0;
    }
}