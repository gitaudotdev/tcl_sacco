<?php

class Tabulate{

	public static function displayMemberLoansTable($loanaccounts){
		Tabulate::createMemberLoansTableHeader();
		Tabulate::createMemberLoansTable($loanaccounts);
		Tabulate::createCommonTableFooter();
	}

	public static function createMemberLoansTableHeader(){
		echo '<div>
	        <table class="table">
			<thead class="text-primary">              
				<th class="text-center" >#</th>
				<th>Amount</th>
				<th>Period (Months)</th>
				<th>Interest Rate</th>
				<th>Repayment Start Date</th>
				<th>Loan Status</th>
				<th>Loan Application Actions</th>
			</thead>
			<tbody>';
	}

	public static function createMemberLoansTable($loanaccounts){
		$i=1;
		foreach($loanaccounts as $loanaccount){
			echo "<tr>".
						 "<td>$i</td>".
						 "<td>";$loanaccount->getAmountApplied();
			echo "</td>".
						"<td>$loanaccount->repayment_period Months</td>".
						"<td>$loanaccount->interest_rate</td>".
						"<td>";echo date('jS M Y',strtotime($loanaccount->repayment_start_date));echo"</td>".
						"<td>";$loanaccount->getLoanAccountStatus();echo"</td>".
						"<td>";$loanaccount->getAction();echo "</td>".
			 	"</tr>";
	 		$i++;
		}
	}

	public static function createMemberSavingAccountsTable($savingaccounts){
		Tabulate::createMemberSavingAccountsTableHeader();
		Tabulate::createMemberSavingsAccountsContent($savingaccounts);
		Tabulate::createCommonTableFooter();
	}

	public static function createLoanApplicationContentDetails($accounts){
		echo '<div class="col-md-12 col-lg-12 col-sm-12" id="overall" style="padding-bottom:3% !important;display:none !important;"></div>';
		if(!empty($accounts) && count($accounts)> 0){
			$i=1;
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="margin-bottom:5% !important;">
							<div id="overall">
	              <table class="table">
				           <thead class="text-primary">              
			              <th class="text-center">#</th>
			              <th>Member</th>
			              <th>Branch</th>
			              <th>Account #</th>
			              <th>Disbursed</th>
			              <th>Rate</th>
			              <th>Accrued Interest</th>
			              <th>Balance</th>
			              <th>Status</th>
			              <th>Actions</th>
			            </thead>
	                <tbody>';
					foreach($accounts as $account){
							echo '<tr>
										<td>';echo $i; echo'</td>
										<td>';echo $account->getBorrowerFullName(); echo '</td>
										<td>';echo $account->getBorrowerBranchName(); echo '</td>
										<td>';echo $account->account_number; echo'</td>
										<td><strong> Kshs. ';echo $account->getExactAmountDisbursed(); echo'</strong></td>
										<td> ';echo $account->getInterestRate(); echo' </td>
										<td><strong> Kshs.';echo $account->getAccruedInterest(); echo '</strong></td>
										<td><strong> Kshs.';echo $account->getCurrentLoanBalance(); echo '</strong></td>
										<td> ';echo $account->getCurrentLoanAccountStatus(); echo'</td>
										<td>';echo $account->getAction(); echo'</td>
								</tr>';
							$i++;
						}
					echo'
					</tbody>
	        </table>
	        </div>
	      </div>';
		}else{
			echo "<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO ACCOUNTS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** NO LOAN ACCOUNTS WERE FOUND BY THE SPECIFIED FILTERS. ****</p></div>";
		}
	}

	public static function createProfileLoanHistory($accounts){
		Tabulate::createProfileLoanHistoryTableHeader();
		Tabulate::createProfileLoanHistoryContent($accounts);
		Tabulate::createCommonTableFooter();
	}

	public static function createProfileLoanHistoryTableHeader(){
		echo '<div>
	          <table id="example" class="display" style="width:100%">
	          <thead class="text-primary">              
						<th class="text-center">#</th>
						<th>Amount</th>
						<th>Date</th>
						<th>Period</th>
						<th>Days</th>
						<th>Rate</th>
						<th>Status</th>
						<th>Profit/Loss</th>
						<th>Account #</th>
					</thead>
					<tbody>';
	}

	public static function createProfileLoanHistoryContent($accounts){
		$i=1;
		foreach($accounts as $account){
			echo "<tr>".
					"<td>$i</td>".
					"<td>";echo $account->getFormattedAmountApplied(); echo "</td>".
					"<td>";echo $account->getFormattedApplicationDate(); echo "</td>".
					"<td>";echo $account->getLoanAccountPeriod();echo"</td>".
					"<td>";echo $account->getDaysPastDisbursementDate();echo"</td>".
					"<td>";echo $account->getInterestRate();echo"</td>".
					"<td>";echo $account->getCurrentLoanAccountStatus();echo "</td>".
					"<td>";echo $account->getProfitLoss();echo "</td>".
					"<td>";echo $account->getLoanAccountNumber();echo "</td>".
			 	"</tr>";
	 		$i++;
		}
	}


	public static function createMemberSavingTransactionsTable($savingtransactions){
		Tabulate::createMemberSavingTransactionsTableHeader();
		Tabulate::createMemberSavingsTransactionsContent($savingtransactions);
		Tabulate::createCommonTableFooter();
	}

	public static function createMemberSavingTransactionsTableHeader(){
		echo '<div>
	          <table id="example" class="display" style="width:100%">
	          <thead class="text-primary">              
						<th class="text-center">#</th>
						<th>Account #</th>
						<th>Amount</th>
						<th>Transaction</th>
						<th>Description</th>
						<th>Date Transacted</th>
						<th>Transaction Actions</th>
					</thead>
					<tbody>';
	}

	public static function createMemberSavingsTransactionsContent($savingtransactions){
		$i=1;
		foreach($savingtransactions as $savingtransaction){
			echo "<tr>".
						 "<td>$i</td>".
						 "<td>";echo $savingtransaction->getSavingAccountNumber();
			echo "</td>".
						 "<td>";echo $savingtransaction->getSavingTransactionAmount();
			echo "</td>".
						"<td>";echo $savingtransaction->getSavingTransactionType();echo"</td>".
						"<td><div class='text-wrap width-150'>";echo $savingtransaction->getSavingTransactionDescription();echo"</div></td>".
						"<td>";echo date('jS M Y',strtotime($savingtransaction->transacted_at));echo"</td>".
						"<td>";$savingtransaction->getAction();echo "</td>".
			 	"</tr>";
	 		$i++;
		}
	}

	public static function createMemberDetailsTable($borrowers){
		echo '<div class="col-md-12 col-lg-12 col-sm-12" id="overall" style="padding-bottom:3% !important;display:none !important;"></div>';
		if(!empty($borrowers) && count($borrowers)> 0){
			$i=1;
			$downloadable=ExportFunctions::exportBorrowersReportAsPdf($borrowers);
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="padding:15px 15px 15px 15px;">
						<div class="col-md-6 col-lg-6 col-sm-12">';
							echo $downloadable; echo
						'</div>
						<div class="col-md-6 col-lg-6 col-sm-12"></div>
						<div class="col-md-12 col-lg-12 col-sm-12" style="margin-top:2% !important;">
						<hr style="border-top:2px dashed #dedede;"></div>
				</div>';
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="margin-bottom:5% !important;overflow-x:scroll !important;">
				<div id="overall" class="table-responsive">
	              <table class="table">
	                <thead class="text-primary">              
	                  <th class="text-center">#</th>
	                  <th>Name</th>
	                  <th>ID Number</th>
	                  <th>Phone Number</th>
	                  <th>Employer</th>
	                  <th>Relationship Manager</th>
	                  <th>Branch</th>
	                  <th>Date Created</th>
	                  <th>Current Balance</th>
	                </thead>
	                <tbody>';
			foreach($borrowers as $borrower){
				echo '<tr>
							<td>';echo $i;echo'</td>
							 <td>';echo $borrower->getBorrowerFullName();echo '</td>
							 <td>';echo $borrower->id_number;echo'</td>
							<td>';echo $borrower->getBorrowerPhoneNumber();echo'</td>
							<td>';echo $borrower->employer;echo'</td>
							<td>';echo $borrower->getRelationManager();echo '</td>
							<td>';echo $borrower->getBranchName();echo '</td>
							<td>';echo $borrower->getCreatedAtFormatted();echo '</td>
							<td><strong> Kshs. ';echo $borrower->getCurrentLoanBalance();echo'</strong></td>
				 	</tr>';
		 		$i++;
			}
			echo '</tbody>
	        </table>
	        </div>
	      </div>';
		}else{
			echo "<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO MEMBERS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** NO MEMBERS WERE FOUND BY THE SPECIFIED FILTERS. ****</p></div>";
		}
	}

	public static function createLoanRepaymentsDetailsTable($repayments){
		if(!empty($repayments) && count($repayments)> 0){
			$i=1;
			echo '<div id="overall">
	              <table class="table display" id="sampler">
	                <thead class="text-primary">              
	                  <th class="text-center">#</th>
	                  <th>Member</th>
	                  <th>Loan Account</th>
	                  <th>Repayment Date</th>
	                  <th>Principal Paid</th>
	                  <th>Interest Paid</th>
	                  <th>Penalty Paid</th>
	                  <th>Total Paid</th>
	                  <th>Payment Actions</th>
	                </thead>
	                <tbody>';
					foreach($repayments as $repayment){
						echo '<tr>
									<td>';echo $i;echo'</td>
									<td>';echo $repayment->getLoanBorrowerName();echo '</td>
									<td>';echo $repayment->getLoanAccountNumber();echo'</td>
									<td>';echo $repayment->getFormattedTransactionDate();echo'</td>
									<td><strong> Kshs. ';echo $repayment->getPrincipalPaid();echo'</strong></td>
									<td><strong> Kshs.';echo $repayment->getInterestPaid();echo '</strong></td>
									<td><strong> Kshs.';echo $repayment->getPenaltyPaid();echo '</strong></td>
									<td><strong> Kshs. ';echo $repayment->getTotalAmountPaid();echo'</strong></td>
									<td>';echo $repayment->getAction();echo'</td>
							</tr>';
						$i++;
					}
					echo'
					</tbody>
	        </table>';
	        echo'
	      </div>';
		}else{
			echo "<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO REPAYMENTS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** NO REPAYMENTS WERE FOUND BY THE SPECIFIED FILTERS. ****</p></div>";
		}
	}

	public static function createLoanDailyReportTable($loanaccounts,$endDate){
		echo '<div class="col-md-12 col-lg-12 col-sm-12" id="overall" style="padding-bottom:3% !important;display:none !important;"></div>';
		if(!empty($loanaccounts) && count($loanaccounts)> 0){
			$i=1;
			$downloadable=ExportFunctions::getDailyDownloadableReport($loanaccounts,$endDate);
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="padding:15px 15px 15px 15px;">
								<div class="col-md-6 col-lg-6 col-sm-12">';
								    echo $downloadable; echo
								'</div>
								<div class="col-md-6 col-lg-6 col-sm-12"></div>
								<div class="col-md-12 col-lg-12 col-sm-12" style="margin-top:2% !important;">
								<hr style="border-top:2px dashed #dedede;"></div>
						</div>';
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="margin-bottom:5% !important;">
							<div id="overall">
	              <table class="table" id="display">
	                <thead class="text-primary">              
	                  <th class="text-center">#</th>
	                  <th>Branch</th>
	                  <th>Relation Manager</th>
	                  <th>First Name</th>
	                  <th>Other Names</th>
	                  <th>Account Number</th>
	                  <th>Original Principal</th>
	                  <th>Current Principal</th>
	                  <th>Interest Rate</th>
	                  <th>Accrued Interest</th>
	                  <th>Total Penalty</th>
	                  <th>Total Balance</th>
	                  <th>Current Month Payment</th>
	                  <th>Disbursement Date</th>
	                  <th>Repayment date</th>
	                  <th>Account Status</th>
	                </thead>
	                <tbody>';
					foreach($loanaccounts as $loanaccount){
						echo '<tr>
									<td>';echo $i;echo'</td>
									<td>';echo $loanaccount->getBorrowerBranchName();echo '</td>
									<td>';echo $loanaccount->getRelationshipManagerName();echo'</td>
									<td>';echo $loanaccount->getBorrowerFirstName();echo'</td>
									<td>';echo $loanaccount->getBorrowerOtherNames();echo'</td>
									<td>';echo $loanaccount->account_number;echo '</td>
									<td><strong> Kshs. ';echo $loanaccount->getExactAmountDisbursed();echo '</strong></td>
									<td><strong> Kshs. ';echo number_format(LoanManager::getPrincipalBalance($loanaccount->loanaccount_id));echo '</strong></td>
									<td><strong> ';echo $loanaccount->interest_rate;echo' % </strong></td>
									<td><strong> Kshs. ';echo number_format(LoanManager::getUnpaidAccruedInterestPrior($loanaccount->loanaccount_id,$endDate));echo '</strong></td>
									<td><strong> Kshs. ';echo number_format(LoanManager::getUnpaidAccruedPenalty($loanaccount->loanaccount_id));echo '</strong></td>
									<td><strong> Kshs. ';echo number_format(LoanManager::getActualLoanBalance($loanaccount->loanaccount_id));echo '</strong></td>
									<td><strong> Kshs. ';echo number_format(LoanManager::getCurrentMonthLoanPayment($loanaccount->loanaccount_id));echo '</strong></td>
									<td>';echo $loanaccount->getFormattedDisbursedDate();echo '</td>
									<td>';echo date('jS',strtotime($loanaccount->repayment_start_date));echo'</td>
									<td>';echo $loanaccount->getEmptyCurrentLoanAccountStatus();echo'</td>
							</tr>';
						$i++;
					}
					echo'
					</tbody>
	        </table>';
	        echo'</div>
	      </div>';
		}else{
			echo "<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO REPAYMENTS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** NO REPAYMENTS WERE FOUND BY THE SPECIFIED FILTERS. ****</p></div>";
		}
	}

	public static function createFilteredMissedRepaymentsTable($loans){
		echo '<div class="col-md-12 col-lg-12 col-sm-12" id="overall" style="padding-bottom:3% !important;display:none !important;"></div>';
		if(!empty($loans) && count($loans)> 0){
			$i=1;
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="margin-bottom:5% !important;">
							<div id="overall">
	              <table class="table" id="display">
	                <thead class="text-primary">              
	                  <th class="text-center">#</th>
	                  <th>Loan Number</th>
	                  <th>Member</th>
	                  <th>Date Defaulted</th>
	                  <th>Penalty Amount</th>
	                  <th>Penalty Status</th>
	                  <th>Loan Balance</th>
	                  <th>Loan Status</th>
	                </thead>
	                <tbody>';
					foreach($loans as $loan){
						echo '<tr>
									<td>';echo $i;echo'</td>
									<td>';echo $loan->account_number;echo '</td>
									<td>';echo $loan->getBorrowerFullName();echo'</td>
									<td>';echo date('jS F Y',strtotime($loan->date_defaulted));echo'</td>
									<td><strong> Kshs. ';echo CommonFunctions::asMoney($loan->penalty_amount);echo'</strong></td>
									<td>';echo $loan->getCurrentPenaltyStatus($loan->is_paid);;echo'</td>
									<td><strong> Kshs. ';echo CommonFunctions::asMoney(LoanTransactionsFunctions::getTotalLoanBalance($loan->loanaccount_id));echo'</strong></td>
									<td>';echo $loan->getCurrentLoanAccountStatus();echo'</td>
							</tr>';
						$i++;
					}
					echo'
					</tbody>
	        </table>';
	        echo'</div>
	      </div>';
		}else{
			echo "<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO ACCOUNTS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** NO LOAN ACCOUNTS WERE FOUND BY THE SPECIFIED FILTERS. ****</p></div>";
		}
	}

	public static function createNotificationsDetailsTable($notifications){
		echo '<div class="col-md-12 col-lg-12 col-sm-12" id="overall" style="padding-bottom:3% !important;display:none !important;"></div>';
		if(!empty($notifications) && count($notifications)> 0){
			$i=1;
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="margin-bottom:5% !important;">
				<div id="overall">
	              <table class="table" id="display">
	                <thead class="text-primary">              
	                  <th class="text-center">#</th>
	                  <th>Phone #</th>
	                  <th>Sent By</th>
	                  <th>Branch</th>
	                  <th>Message</th>
	                  <th>Date Sent</th>
	                </thead>
	                <tbody>';
						foreach($notifications as $notification){
						echo '<tr>
									<td>';echo $i;echo'</td>
									<td>';echo $notification->phone_number;echo '</td>
									<td>';echo $notification->AlertSentBy;echo'</td>
									<td>';echo $notification->AlertBranchName;echo'</td>
									<td><div class="text-wrap width-200">';echo $notification->message;echo'</div></td>
									<td>';echo $notification->DateAlertSent;echo '</td>
							</tr>';
						$i++;
					}
					echo'
					</tbody>
	        </table>
	        </div>
	      </div>';
		}else{
			echo "<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO NOTIFICATIONS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** There are no availanble notifications found by the specified filters. ****</p></div>";
		}
	}

	public static function createSavingAccountDetailsTable($savings){
		echo '<div class="col-md-12 col-lg-12 col-sm-12" id="overall" style="padding-bottom:3% !important;display:none !important;"></div>';
		if(!empty($savings) && count($savings)> 0){
			$i=1;
			echo '<div id="overall">
	              <table class="table" id="display">
	                <thead class="text-primary">              
	                  <th class="text-center">#</th>
	                  <th>Account Holder</th>
	                  <th>Branch</th>
	                  <th>RM</th>
	                  <th>Account #</th>
	                  <th>Interest Rate</th>
	                  <th>Account Bal</th>
	                  <th>Interest Accrued</th>
	                  <th>Total</th>
	                  <th>Status</th>
	                  <th>Account Actions</th>
	                </thead>
	                <tbody>';
					foreach($savings as $saving){
						echo '<tr>
							<td>';echo $i;echo'</td>
							<td>';echo $saving->getSavingAccountHolderName();echo '</td>
							<td>';echo $saving->getSavingAccountHolderBranch();echo '</td>
							<td>';echo $saving->getSavingAccountHolderRelationManager();echo '</td>
							<td>';echo $saving->getSavingAccountNumber();echo'</td>
							<td>';echo $saving->getAccountInterestRate();echo'</td>
							<td><strong> Kshs. ';echo number_format($saving->getSavingAccountBalance(),2);echo'</strong></td>
							<td><strong> Kshs.';echo number_format($saving->getSavingAccountInterestAccrued(),2);echo '</strong></td>
							<td><strong> Kshs.';echo number_format($saving->getSavingAccountTotal(),2);echo '</strong></td>
							<td>';echo $saving->getAccountAuthStatus();echo'</td>
							<td>';echo $saving->getAction();echo'</td>
						</tr>';
					$i++;
					}
					echo'
					</tbody>
	        </table>
	        </div>';
		}else{
			echo "<p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO ACCOUNTS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** NO SAVING ACCOUNTS WERE FOUND BY THE SPECIFIED FILTERS. ****</p>";
		}
	}

	public static function createdFilteredDueLoansTable($loaded_dues){
		echo '<div class="col-md-12 col-lg-12 col-sm-12" id="overall" style="padding-bottom:3% !important;display:none !important;"></div>';
		if(!empty($loaded_dues) && count($loaded_dues)> 0){
			$downloadable=ExportFunctions::exportFilteredDueLoansReportAsPdf($loaded_dues);
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="padding:15px 15px 15px 15px;">
								<div class="col-md-6 col-lg-6 col-sm-12">';
								    echo $downloadable; echo
								'</div>
								<div class="col-md-6 col-lg-6 col-sm-12"></div>
								<div class="col-md-12 col-lg-12 col-sm-12" style="margin-top:2% !important;">
								<hr style="border-top:2px dashed #dedede;"></div>
						</div>';
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="margin-bottom:5% !important;overflow-x:scroll !important;">
							<div id="overall">
	              <table class="table">
	                <thead class="text-primary">              
	                  <th class="text-center">#</th>
	                  <th>Loan Number</th>
	                  <th>Member</th>
	                  <th>Branch</th>
	                  <th>Relationship Manager</th>
	                  <th>Amount Due</th>
	                  <th>Repayment Date</th>
	                  <th>Current Balance</th>
	                </thead>
	                <tbody>';
	    $i=1;
			foreach($loaded_dues as $loan){
				$loanaccount=Loanaccounts::model()->findByPk($loan['loanaccount_id']);
				$user=Profiles::model()->findByPk($loanaccount->user_id);
				$repaymentDate=$loan['repayment_date'];
				echo '<tr>
							<td>';echo $i;echo'</td>
							 <td>';echo $loanaccount->account_number;echo '</td>
							 <td>';echo $loanaccount->getBorrowerFullName();echo'</td>
							<td>';echo $user->ProfileBranch;echo'</td>
							<td>';echo $loanaccount->getRelationshipManagerName();echo'</td>
							<td><strong> Kshs.';echo CommonFunctions::asMoney(LoanApplication::getEMIAmount($loanaccount->loanaccount_id));echo '</strong></td>
							<td>';echo date('jS M Y',strtotime($repaymentDate));echo '</td>
							<td><strong> Kshs. ';echo CommonFunctions::asMoney(LoanTransactionsFunctions::getTotalLoanBalance($loanaccount->loanaccount_id));echo'</strong></td>
				 	</tr>';
		 		$i++;
			}
			echo '</tbody>
	        </table>
	        </div>
	      </div>';
		}else{
			echo "<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO LOAN ACCOUNTS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** NO LOAN ACCOUNTS WERE FOUND BY THE SPECIFIED FILTERS. ****</p></div>";
		}
	}

	public static function createMemberArrearsDetailsTable($loanaccounts){
		echo '<div class="col-md-12 col-lg-12 col-sm-12" id="overall" style="padding-bottom:3% !important;display:none !important;"></div>';
		if(!empty($loanaccounts) && count($loanaccounts)> 0){
			$i=1;
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="margin-bottom:5% !important;overflow-x:scroll !important;">
							<div>
	              <table class="table">
	                <thead class="text-primary">              
	                  <th class="text-center">#</th>
	                  <th>Name</th>
	                  <th>Branch</th>
	                  <th>Relationship Manager</th>
	                  <th>Interest Rate</th>
	                  <th>Arrears</th>
	                  <th>Current Balance</th>
	                </thead>
	                <tbody>';
					foreach($loanaccounts as $loanaccount){
						echo '<tr>
									<td>';echo $i;echo'</td>
										<td>';echo $loanaccount->getBorrowerFullName();echo '</td>
										<td>';echo $loanaccount->getBorrowerBranchName();echo'</td>
									<td>';echo $loanaccount->getRelationshipManagerName();echo'</td>
									<td>';echo $loanaccount->interest_rate;echo' % p.m.</td>
									<td> Kshs. ';echo CommonFunctions::asMoney($loanaccount->arrears);echo'</td>
									<td> Kshs. ';echo $loanaccount->getCurrentLoanBalance();echo '</td>
							</tr>';
						$i++;
					}
			echo '</tbody>
	        </table>
	        </div>
	      </div>';
		}else{
			echo "<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO ACCOUNTS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** No Loan accounts found by the specified filters. ****</p><br><br></div>";
		}
	}

	public static function createMemberCollectionsDetailsTable($repayments){
		echo '<div class="col-md-12 col-lg-12 col-sm-12" id="overall" style="padding-bottom:3% !important;display:none !important;"></div>';
		if(!empty($repayments) && count($repayments)> 0){
			$i=1;
			$downloadable=ExportFunctions::exportCollectionsReportAsPdf($repayments);
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="padding:15px 15px 15px 15px;">
								<div class="col-md-6 col-lg-6 col-sm-12">';
								    echo $downloadable; echo
								'</div>
								<div class="col-md-6 col-lg-6 col-sm-12"></div>
								<div class="col-md-12 col-lg-12 col-sm-12" style="margin-top:2% !important;">
								<hr style="border-top:2px dashed #dedede;"></div>
						</div>';
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="margin-bottom:5% !important;overflow-x:scroll !important;">
							<div>
	              <table class="table">
	                <thead class="text-primary">              
	                  <th class="text-center">#</th>
	                  <th>Name</th>
	                  <th>Account Number</th>
	                  <th>Relationship Manager</th>
	                  <th>Principal Paid</th>
	                  <th>Interest Paid</th>
	                  <th>Penalty Paid</th>
	                  <th>Total Paid</th>
	                  <th>Transaction Date</th>
	                </thead>
	                <tbody>';
			foreach($repayments as $repayment){
				echo '<tr>
							<td>';echo $i;echo'</td>
							 <td>';echo $repayment->getLoanBorrowerName();echo '</td>
							 <td>';echo $repayment->getLoanAccountNumber();echo'</td>
							<td>';echo $repayment->getTransactedBy();echo'</td>
							<td> Kshs. ';echo $repayment->getPrincipalPaid();echo'</td>
							<td> Kshs. ';echo $repayment->getInterestPaid();echo '</td>
							<td> Kshs. ';echo $repayment->getPenaltyPaid();echo '</td>
							<td><strong> Kshs. ';echo $repayment->getTotalAmountPaid();echo'</strong></td>
							<td>';echo $repayment->getFormattedTransactionDate();echo '</td>
				 	</tr>';
		 		$i++;
			}
			echo '</tbody>
	        </table>
	        </div>
	      </div>';
		}else{
			echo "<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO COLLECTIONS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** NO REPAYMENTS WERE FOUND BY THE SPECIFIED FILTERS. ****</p></div>";
		}
	}

	public static function createMemberDisbursementDetailsTable($loanaccounts){
		echo '<div class="col-md-12 col-lg-12 col-sm-12" id="overall" style="padding-bottom:3% !important;display:none !important;"></div>';
		if(!empty($loanaccounts) && count($loanaccounts)> 0){
			$i=1;
			$downloadable=ExportFunctions::exportDisbursementReportAsPdf($loanaccounts);
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="padding:15px 15px 15px 15px;">
								<div class="col-md-6 col-lg-6 col-sm-12">';
								    echo $downloadable; echo
								'</div>
								<div class="col-md-6 col-lg-6 col-sm-12"></div>
								<div class="col-md-12 col-lg-12 col-sm-12" style="margin-top:2% !important;">
								<hr style="border-top:2px dashed #dedede;"></div>
						</div>';
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="margin-bottom:5% !important;overflow-x:scroll !important;">
							<div>
	              <table class="table">
	                <thead class="text-primary">              
	                  <th class="text-center">#</th>
	                  <th>Name</th>
	                  <th>Account Number</th>
	                  <th>Amount Applied</th>
	                  <th>Interest Rate</th>
	                  <th>Repayment Period</th>
	                  <th>Relationship Manager</th>
	                  <th>Amount Disbursed</th>
	                  <th>Date Disbursed</th>
	                  <th>Current Balance</th>
	                </thead>
	                <tbody>';
			foreach($loanaccounts as $loanaccount){
				echo '<tr>
							<td>';echo $i;echo'</td>
							 <td>';echo $loanaccount->getBorrowerFullName();echo '</td>
							 <td>';echo $loanaccount->account_number;echo'</td>
							<td> Kshs. ';$loanaccount->getAmountApplied();echo'</td>
							<td>';echo $loanaccount->interest_rate;echo' % p.m. </td>
							<td> ';echo $loanaccount->repayment_period;echo ' Months</td>
							<td>';echo $loanaccount->getRelationshipManagerName();echo '</td>
							<td> Kshs. ';$loanaccount->getAmountDisbursed();echo'</td>
							<td>';echo $loanaccount->getFormattedDisbursedDate();echo '</td>
							<td><strong>Kshs. ';echo $loanaccount->getCurrentLoanBalance();echo '</strong></td>
				 	</tr>';
		 		$i++;
			}
			echo '</tbody>
	        </table>
	        </div>
	      </div>';
		}else{
			echo "<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO LOANS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** NO LOANS WERE FOUND BY THE SPECIFIED FILTERS. ****</p></div>";
		}
	}

	public static function createMemberProfitandLossTable($loanaccounts){
		echo '<div class="col-md-12 col-lg-12 col-sm-12" id="overall" style="padding-bottom:3% !important;display:none !important;"></div>';
		if(!empty($loanaccounts) && count($loanaccounts)> 0){
			$i=1;
			$downloadable=ExportFunctions::exportProfitandLossReportAsPdf($loanaccounts);
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="padding:15px 15px 15px 15px;">
								<div class="col-md-6 col-lg-6 col-sm-12">';
								    echo $downloadable; echo
								'</div>
								<div class="col-md-6 col-lg-6 col-sm-12"></div>
								<div class="col-md-12 col-lg-12 col-sm-12" style="margin-top:2% !important;">
								<hr style="border-top:2px dashed #dedede;"></div>
						</div>';
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="margin-bottom:5% !important;overflow-x:scroll !important;">
							<div>
	              <table class="table">
	                <thead class="text-primary">              
	                  <th class="text-center">#</th>
	                  <th>Name</th>
	                  <th>Account #</th>
	                  <th>Branch</th>
	                  <th>RM</th>
	                  <th>Princ. Bal</th>
	                  <th>Penalties</th>
	                  <th>Curr. Interest</th>
	                  <th>Amt Due</th>
	                  <th>Amt Paid</th>
	                  <th>P & L</th>
	                  <th>Payment Date</th>
	                </thead>
	                <tbody>';
	     $totalAmountDisbursed=0;
	     $totalAmountPaid=0;
	     $totalProfits=0;
	     $totalArrears=0;
	     $totalPrincipals=0;
	     $totalPenalties=0;
	     $totalInterests=0;
	     $totalTotals=0;
			foreach($loanaccounts as $loanaccount){
				$lastDate=LoanTransactionsFunctions::getLastInterestPaymentDate($loanaccount->loanaccount_id);
				if($lastDate == 0){
					$finalDate='N/A';
				}else{
					$finalDate=date('d/m/Y',strtotime($lastDate));
				}
				echo '<tr>
							<td>';echo $i;echo'</td>
							 <td>';echo $loanaccount->getBorrowerFullName();echo'</td>
							 <td>';echo $loanaccount->account_number;echo'</td>
							<td>';echo $loanaccount->getBorrowerBranchName();echo '</td>
							<td>';echo $loanaccount->getRelationshipManagerName();echo '</td>
							<td>';echo number_format(LoanManager::getPrincipalBalance($loanaccount->loanaccount_id),2);echo'</td>
							<td>';echo number_format(LoanRepayment::getAccruedPenalty($loanaccount->loanaccount_id),2);echo'</td>
							<td>';echo number_format(LoanTransactionsFunctions::getCurrentMonthInterestBalance($loanaccount->loanaccount_id,$start_date,$end_date),2);echo'</td>
							<td>';echo number_format(LoanRepayment::getAccruedPenalty($loanaccount->loanaccount_id)+LoanTransactionsFunctions::getCurrentMonthInterestBalance($loanaccount->loanaccount_id,$start_date,$end_date),2);echo'</td>
							<td>';echo number_format(LoanTransactionsFunctions::getTotalAmountPaid($loanaccount->loanaccount_id,$start_date,$end_date),2);echo '</td>
							<td><strong>';echo number_format(LoanApplication::getAccountTotalProfit($loanaccount->loanaccount_id,$start_date,$end_date),2);echo '</strong></td>
							<td>'; echo $finalDate; echo '</td>
				 	</tr>';
				 	$totalPrincipals+=LoanManager::getPrincipalBalance($loanaccount->loanaccount_id);
				 	$totalPenalties+=LoanRepayment::getAccruedPenalty($loanaccount->loanaccount_id);
				 	$totalInterests+=LoanTransactionsFunctions::getCurrentMonthInterestBalance($loanaccount->loanaccount_id,$start_date,$end_date);
				 	$totalTotals+=LoanRepayment::getAccruedPenalty($loanaccount->loanaccount_id)+LoanTransactionsFunctions::getCurrentMonthInterestBalance($loanaccount->loanaccount_id,$start_date,$end_date);
				 	$totalAmountPaid+=LoanTransactionsFunctions::getTotalAmountPaid($loanaccount->loanaccount_id,$start_date,$end_date);
				 	$totalProfits+=LoanApplication::getAccountTotalProfit($loanaccount->loanaccount_id,$start_date,$end_date);
		 		  $i++;
			}
			echo '<tr><td></td><td></td><td></td><td></td><td></td><td><strong>';echo number_format($totalPrincipals,2); echo'</strong></td><td><strong>';echo number_format($totalPenalties,2); echo'</strong></td><td><strong>';echo number_format($totalInterests,2); echo'</strong></td><td><strong>';echo number_format($totalTotals,2); echo'</strong></td><td><strong>';echo number_format($totalAmountPaid,2); echo'</strong></td><td><strong>';echo number_format($totalProfits,2); echo'</strong></td></tr>';
			echo '</tbody>
	        </table>
	        </div>
	      </div>';
		}else{
			echo "<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO LOANS FOUND</strong></p><br><p style='color:#f90101;font-size:1.30em;'>*** NO LOANS WERE FOUND BY THE SPECIFIED FILTERS. ****</p></div>";
		}
	}

	public static function createStaffMembersPayrollTabulation($staffs,$month_date){
		$monthDate = explode('-', $month_date);
		$payrollMonth=(int)$monthDate[0];
		$payrollYear=(int)$monthDate[1];
		$currentMonth=(int)date('m');
		$currentYear=(int)date('Y');
		$element=Yii::app()->user->user_level;
		$totalSalary=0;
		$totalGross=0;
		$totalSold = 0;
		$totalCollected = 0;
		$totalSoldPercent = 0;
		$totalCollectedPercent = 0;
		$totalLoans = 0;
		$array=array('2','3','4');
		echo '<div class="col-md-12 col-lg-12 col-sm-12" id="overall" style="padding-bottom:3% !important;display:none !important;"></div>';
		if(!empty($staffs) && count($staffs)> 0){
			$i=1;
			$downloadable=ExportFunctions::exportStaffMembersPayrollReportAsPdf($staffs,$month_date);
			echo '<div class="col-md-6 col-lg-6 col-sm-12">';
								    echo $downloadable; echo
								'</div>
								<div class="col-md-6 col-lg-6 col-sm-12"></div>
					<div class="col-md-12 col-lg-12 col-sm-12">
						<hr class="common_rule"></div>
					</div>';
			echo '<div class="col-md-12 col-lg-12 col-sm-12" style="margin-bottom:5% !important;overflow-x:scroll !important;">
							<div>
						<br>
						<h5 class="title"> Payroll Period : ';echo CommonFunctions::getRespectiveMonth($month_date); echo'</h5>
						<hr class="common_rule">
	              <table class="table table-bordered table-hover">
	                <thead class="text-primary">              
	                  <th class="text-center">#</th>
	                  <th>Name</th>
	                  <th>Branch</th>
	                  <th>Salary</th>
	                  <th>Amnt Sold</th>
	                  <th>Amnt Collected</th>';
	                  if(Navigation::checkIfAuthorized(127) == 1){
	                   echo '<th>P & L</th>';
	                  }
	                  echo '<th>Amnt Sold(%)</th>
	                  <th>Amnt Collected(%)</th>';
	                  if(Navigation::checkIfAuthorized(127) == 1){
	                   echo '<th>P & L(%)</th>';
	                  }
	                  echo '<th>Loans</th>
	                  <th>Net Pay</th>';
	                echo '</thead>
	                <tbody>';
			foreach($staffs as $staff){
				echo '<tr>
							<td>';echo $i;echo'</td>
							 <td>';echo $staff->ProfileFullName;echo '</td>
							 <td>';echo $staff->ProfileBranch;echo '</td>
							 <td>';echo number_format($staff->ProfileSalary,2);echo'</td>
							 <td>';echo number_format(StaffFunctions::getTotalLoanAmountSold($staff->id,$month_date),2);echo '</td>
							 <td>';echo number_format(StaffFunctions::getTotalLoanCollections($staff->id,$month_date),2);echo'</td>';
							 if(Navigation::checkIfAuthorized(127) == 1){
									echo '<td>';echo number_format(StaffFunctions::getTotalLoanAccountsProfits($staff->id,$month_date),2);echo'</td>';
							 }
							echo '<td>';echo number_format(StaffFunctions::getMemberBonus($staff->id,$month_date),2);echo '</td>
							<td>';echo number_format(StaffFunctions::getMemberCommission($staff->id,$month_date),2); echo'</td>';
							if(Navigation::checkIfAuthorized(127) == 1){
               echo '<td>';echo number_format(StaffFunctions::getTotalProfitBonus($staff->id,$month_date),2); echo'</td>';
              }
							echo '<td>';echo number_format(StaffFunctions::getCurrentLoanRepayment($staff->id),2); echo '</td>
							<td>';echo number_format(StaffFunctions::getMemberNetSalaryPay($staff->id,$month_date),2); echo'</td>';
					 echo '</tr>';
					$totalGross+=$staff->ProfileSalary;
					$totalSold+=StaffFunctions::getTotalLoanAmountSold($staff->id,$month_date);
					$totalCollected+=StaffFunctions::getTotalLoanCollections($staff->id,$month_date);
					$totalLoanAccounts+=StaffFunctions::getTotalLoanAccountsProfits($staff->id,$month_date);
					$totalSoldPercent+=StaffFunctions::getMemberBonus($staff->id,$month_date);
					$totalCollectedPercent+=StaffFunctions::getMemberCommission($staff->id,$month_date);
					$totalProfitBonus+=StaffFunctions::getTotalProfitBonus($staff->id,$month_date);
					$totalLoans+=StaffFunctions::getCurrentLoanRepayment($staff->id);
				 	$totalSalary+=StaffFunctions::getMemberNetSalaryPay($staff->id,$month_date);
		 		$i++;
			}
				echo '<tr>
				<td></td>
				<td></td>
				<td></td>
				<td><strong>';echo number_format($totalGross,2); echo'</strong></td>
				<td><strong>';echo number_format($totalSold,2); echo'</strong></td>
				<td><strong>';echo number_format($totalCollected,2); echo'</strong></td>';
				 if(Navigation::checkIfAuthorized(127) == 1){
						echo '<td><strong>';echo number_format($totalLoanAccounts,2); echo'</strong></td>';
					}
				echo'<td><strong>';echo number_format($totalSoldPercent,2); echo'</strong></td>
				<td><strong>';echo number_format($totalCollectedPercent,2); echo'</strong></td>';
				if(Navigation::checkIfAuthorized(127) == 1){
					echo '<td><strong>';echo number_format($totalProfitBonus,2); echo'</strong></td>';
				}
				echo '<td><strong>';echo number_format($totalLoans,2); echo'</strong></td>
				<td><strong>';echo number_format($totalSalary,2);echo' /= </strong></td>
				<td></td>
				</tr>';
			echo '</tbody>
	        </table>
	        </div>
	      </div>';
		}else{
			echo "<div class='col-md-12 col-lg-12 col-sm-12' style='padding:10px 10px 10px 10px !important;'><p style='border-bottom: 3px dotted #ddd;font-size:1.39em;color:#00933b;'><strong style='margin-left:20% !important;'>NO RECORDS FOUND</strong></p><br><p style='color:#f90101;font-size:1em;'>*** THE CURRENT USER IS ASSIGNED PERMISSIONS TO VIEW ONLY THEIR PAYROLL, BUT THEY ARE NOT LISTED ON PAYROLL. ****</p></div>";
		}
	}
	public static function createCommonTableFooter(){
		echo   '</tbody>
					</table>
				</div>';
	}

	/******************
		BRANCH PERFORMANCE
	*********************************/
	public static function createBranchPerformanceTableTableHeader(){
		echo '<div style="width:100% !important;padding:20px 25px 25px 20px !important;">
	           <table class="table">
	               <thead class="text-primary" style="text-transform: uppercase !important;">              
									<th class="text-center" >#</th>
									<th>Branch</th>
									<th>S. Target</th>
									<th>Hits</th>
									<th>Achieved</th>
									<th>PAS</th>
									<th>C. Target</th>
									<th>Hits</th>
									<th>Achieved</th>
									<th>PAC</th>
									<th>D/Acc</th>
									<th>P/AL</th>
									<th>PACC</th>
									<th>Average</th>
								</thead>
								<tbody>';
	}

	public static function getBranchPerformanceTable($branches,$startDate,$endDate){
		Tabulate::createBranchPerformanceTableTableHeader();
		Tabulate::createBranchPerformanceTableContent($branches,$startDate,$endDate);
		Tabulate::createCommonTableFooter();
	}

	public static function createBranchPerformanceTableContent($branches,$startDate,$endDate){
		if(count($branches) > 1){
			Tabulate::getBranchesPerformance($branches,$startDate,$endDate,$i=1);
		}else{
			Tabulate::getSpecificBranchPerformance($branches,$startDate,$endDate,$i=1);
		}
	}

	public static function getBranchesPerformance($branches,$startDate,$endDate,$i){
		$daysDifference=CommonFunctions::getDatesDifference($startDate,$endDate);
		$monthsDifference=round($daysDifference/30);
		if($monthsDifference<= 0){
			$monthsDifference=1;
		}
		$totalSalesCount=0;
		$totalSalesAmount=0;
		$totalSalesAchievedAmount=0;
		$totalCollectionsCount=0;
		$totalCollectionsAmount=0;
		$totalCollectionsAchievedAmount=0;
		$totalActiveAccounts=0;
		$totalPaidAccounts=0;
		foreach($branches as $branch){
			$salesCount=Performance::getTotalBranchSalesCount($branch->branch_id,$startDate,$endDate);
			$salesAchieved=Performance::getTotalBranchSales($branch->branch_id,$startDate,$endDate);
			$salesPercent=Performance::getPerformancePercentage($branch->sales_target * $monthsDifference,$salesAchieved);
			$salesIndicator=Performance::determinePerformanceColor($salesPercent);
			$collectionsAchieved=Performance::getTotalBranchCollections($branch->branch_id,$startDate,$endDate);
			$paidAccounts=Performance::getTotalBranchCollectionsCountPACC($branch->branch_id,$startDate,$endDate);
			$collectionsCount=Performance::getTotalBranchCollectionsCount($branch->branch_id,$startDate,$endDate);
			$collectionsPercent=Performance::getPerformancePercentage($branch->collections_target * $monthsDifference,$collectionsAchieved);
			$collectionsIndicator=Performance::determinePerformanceColor($collectionsPercent);
		    $activeAccounts=Performance::getActiveAccountsMonthStart($branch->branch_id,$startDate,$endDate);
		  if($activeAccounts > 0){
		  	$activePercent=($paidAccounts/$activeAccounts)* 100;
		  }else{
		  	$activePercent=0;
		  }
		  $activeAccountIndicator=Performance::determinePALPerformanceColor($activePercent);
			$averagePercent=($salesPercent + $collectionsPercent + $activePercent)/3;
			$avgIndicator=Performance::determinePerformanceColor($averagePercent);
			echo "<tr>".
						"<td>$i</td>".
						"<td>";echo $branch->name; echo  "</td>".
					  "<td>";echo number_format($branch->sales_target * $monthsDifference,2); echo  "</td>".
					  "<td>";echo $salesCount; echo "</td>".
						"<td>";echo number_format($salesAchieved,2); echo "</td>".
						"<td style='".$salesIndicator."'><strong>"; echo number_format($salesPercent,2);echo " %</strong></td>".
						"<td>";echo number_format($branch->collections_target * $monthsDifference,2); echo "</td>".
						"<td>";echo $collectionsCount;echo "</td>".
						"<td>";echo number_format($collectionsAchieved,2);echo "</td>".
						"<td style='".$collectionsIndicator."'><strong>";echo number_format($collectionsPercent,2);echo"%</strong></td>".
						"<td>";echo $activeAccounts;echo "</td>".
						"<td style='".$activeAccountIndicator."'><strong>"; echo number_format($activePercent,2);echo" %</strong></td>".
						"<td>";echo $paidAccounts;echo "</td>".
						"<td style='".$avgIndicator."'><strong>"; echo number_format($averagePercent,2);echo " %</strong></td>".
			 	"</tr>";
			 		$totalSalesCount+=$salesCount;
				 	$totalSalesAmount+=$branch->sales_target * $monthsDifference;
				 	$totalSalesAchievedAmount+=$salesAchieved;
				 	$totalCollectionsCount+=$collectionsCount;
				 	$totalCollectionsAmount+=$branch->collections_target * $monthsDifference;
				 	$totalCollectionsAchievedAmount+=$collectionsAchieved;
				 	$totalActiveAccounts+=$activeAccounts;
				 	$totalPaidAccounts+=$paidAccounts;
	 		$i++;
		}
		$totalSalesPercent=Performance::getPerformancePercentage($totalSalesAmount,$totalSalesAchievedAmount);
		$totalSalesIndicator=Performance::determinePerformanceColor($totalSalesPercent);
		$totalCollectionsPercent=Performance::getPerformancePercentage($totalCollectionsAmount,$totalCollectionsAchievedAmount);
		$totalCollectionsIndicator=Performance::determinePerformanceColor($totalCollectionsPercent);
		
		if($totalActiveAccounts > 0){
			$totalActiveAccountsPercent=($totalPaidAccounts/$totalActiveAccounts) * 100;
		}else{
			$totalActiveAccountsPercent=0;
		}
		$totalActiveAccountsIndicator=Performance::determinePALPerformanceColor($totalActiveAccountsPercent);
		$totalAveragePercent=($totalSalesPercent+$totalCollectionsPercent + $totalActiveAccountsPercent)/3;
		$totalAvgIndicator=Performance::determinePerformanceColor($totalAveragePercent);
		echo "<tr>
					<td></td><td></td>".
					"<td><strong>"; echo number_format($totalSalesAmount,2); echo "</strong></td>".
					"<td><strong>"; echo number_format($totalSalesCount,2); echo "</strong></td>".
					"<td><strong>"; echo number_format($totalSalesAchievedAmount,2); echo "</strong></td>".
					"<td style='".$totalSalesIndicator."'><strong>"; echo number_format($totalSalesPercent,2);echo" %</strong></td>".
					"<td><strong>"; echo number_format($totalCollectionsAmount,2); echo "</strong></td>".
					"<td><strong>"; echo number_format($totalCollectionsCount,2); echo "</strong></td>".
					"<td><strong>"; echo number_format($totalCollectionsAchievedAmount,2); echo "</strong></td>".
					"<td style='".$totalCollectionsIndicator."'><strong>"; echo number_format($totalCollectionsPercent,2);echo " %</strong></td>".
					"<td><strong>"; echo $totalActiveAccounts; echo "</strong></td>".
					"<td style='".$totalActiveAccountsIndicator."'><strong>"; echo number_format($totalActiveAccountsPercent,2);echo " %</strong></td>".
					"<td>";echo $totalPaidAccounts;echo "</td>".
					"<td style='".$totalAvgIndicator."'><strong>"; echo number_format($totalAveragePercent,2);echo " %</strong></td>".
		"</tr>";
	}

	public static function getSpecificBranchPerformance($branches,$startDate,$endDate,$i){
		$daysDifference=CommonFunctions::getDatesDifference($startDate,$endDate);
		$monthsDifference=round($daysDifference/30);
		if($monthsDifference<= 0){
			$monthsDifference=1;
		}
		$salesCount=Performance::getTotalBranchSalesCount($branches->branch_id,$startDate,$endDate);
			$salesAchieved=Performance::getTotalBranchSales($branches->branch_id,$startDate,$endDate);
			$salesPercent=Performance::getPerformancePercentage($branches->sales_target * $monthsDifference,$salesAchieved);
			$salesIndicator=Performance::determinePerformanceColor($salesPercent);
			$collectionsAchieved=Performance::getTotalBranchCollections($branches->branch_id,$startDate,$endDate);
			$paidAccounts=Performance::getTotalBranchCollectionsCountPACC($branches->branch_id,$startDate,$endDate);
			$collectionsCount=Performance::getTotalBranchCollectionsCount($branches->branch_id,$startDate,$endDate);
			$collectionsPercent=Performance::getPerformancePercentage($branches->collections_target * $monthsDifference,$collectionsAchieved);
			$collectionsIndicator=Performance::determinePerformanceColor($collectionsPercent);
		  $activeAccounts=Performance::getActiveAccountsMonthStart($branches->branch_id,$startDate,$endDate);
		  if($activeAccounts > 0){
		  	$activePercent=($paidAccounts/$activeAccounts)* 100;
		  }else{
		  	$activePercent=0;
		  }
		  $activeAccountIndicator=Performance::determinePALPerformanceColor($activePercent);
			$averagePercent=($salesPercent + $collectionsPercent + $activePercent)/3;
			$avgIndicator=Performance::determinePerformanceColor($averagePercent);
			echo "<tr>".
						"<td>$i</td>".
						"<td>";echo $branches->name; echo  "</td>".
					  "<td>";echo number_format($branches->sales_target * $monthsDifference,2); echo  "</td>".
					  "<td>";echo $salesCount; echo "</td>".
						"<td>";echo number_format($salesAchieved,2); echo "</td>".
						"<td style='".$salesIndicator."'><strong>"; echo number_format($salesPercent,2);echo " %</strong></td>".
						"<td>";echo number_format($branches->collections_target * $monthsDifference,2); echo "</td>".
						"<td>";echo $collectionsCount;echo "</td>".
						"<td>";echo number_format($collectionsAchieved,2);echo "</td>".
						"<td style='".$collectionsIndicator."'><strong>";echo number_format($collectionsPercent,2);echo"%</strong></td>".
						"<td>";echo $activeAccounts;echo "</td>".
						"<td style='".$activeAccountIndicator."'><strong>"; echo number_format($activePercent,2);echo" %</strong></td>".
						"<td>";echo $paidAccounts;echo "</td>".
						"<td style='".$avgIndicator."'><strong>"; echo number_format($averagePercent,2);echo " %</strong></td>".
			 	"</tr>";
	}

	/******************
		STAFF PERFORMANCE
	*********************************/
	public static function createStaffPerformanceTableTableHeader(){
		echo '<div style="width:100% !important;padding:20px 25px 25px 20px !important;">
	           <table class="table">
	               <thead class="text-primary" style="text-transform:uppercase !important;">              
									<th class="text-center" >#</th>
									<th>Staff</th>
									<th>S. Target</th>
									<th>Hits</th>
									<th>Achieved</th>
									<th>PAS</th>
									<th>C. Target</th>
									<th>Hits</th>
									<th>Achieved</th>
									<th>PAC</th>
									<th>D/ACC</th>
									<th>P/AL</th>
									<th>PACC</th>
									<th>Average</th>
								</thead>
								<tbody>';
	}

	public static function getStaffPerformanceTable($staffs,$startDate,$endDate){
		Tabulate::createStaffPerformanceTableTableHeader();
		Tabulate::createStaffPerformanceTableContent($staffs,$startDate,$endDate);
		Tabulate::createCommonTableFooter();
	}

	public static function createStaffPerformanceTableContent($staffs,$startDate,$endDate){
		count($staffs) > 1 ? Tabulate::getStaffsPerformance($staffs,$startDate,$endDate,$i=1) : Tabulate::getSpecificStaffPerformance($staffs,$startDate,$endDate,$i=1);
	}

	public static function getStaffsPerformance($staffs,$startDate,$endDate,$i){
		$daysDifference                 = CommonFunctions::getDatesDifference($startDate,$endDate);
		$monthsDifference               = round($daysDifference/30) > 0 ? round($daysDifference/30) : 1;
		$totalSalesCount                = 0;
		$totalSalesAmount               = 0;
		$totalSalesAchievedAmount       = 0;
		$totalCollectionsCount          = 0;
		$totalCollectionsAmount         = 0;
		$totalCollectionsAchievedAmount = 0;
		$totalActiveAccounts            = 0;
		$totalPaidAccounts              = 0;
		foreach($staffs as $staff){
			$defaultCTarget         = ProfileEngine::getActiveProfileAccountSettingByType($staff->id,'COLLECTIONS_TARGET');
			$collectionsTarget      = $defaultCTarget === 'NOT SET' ? 0 : floatval($defaultCTarget);
			$defaultSTarget         = ProfileEngine::getActiveProfileAccountSettingByType($staff->id,'SALES_TARGET');
			$salesTarget            = $defaultSTarget === 'NOT SET' ? 0 : floatval($defaultSTarget);
			$salesCount             = Performance::getTotalStaffSalesCount($staff->id,$startDate,$endDate);
			$salesAchieved          = Performance::getTotalStaffSales($staff->id,$startDate,$endDate);
			$salesPercent           = Performance::getPerformancePercentage($salesTarget * $monthsDifference,$salesAchieved);
			$salesIndicator         = Performance::determinePerformanceColor($salesPercent);
			$collectionsCount       = Performance::getTotalStaffCollectionsCount($staff->id,$startDate,$endDate);
			$paidAccounts           = Performance::getTotalStaffCollectionsCountPACC($staff->id,$startDate,$endDate);
			$collectionsAchieved    = Performance::getTotalStaffCollections($staff->id,$startDate,$endDate);
			$collectionsPercent     = Performance::getPerformancePercentage($collectionsTarget * $monthsDifference,$collectionsAchieved);
			$collectionsIndicator   = Performance::determinePerformanceColor($collectionsPercent);
			$activeAccounts         = Performance::getStaffActiveAccountsMonthStart($staff->id,$startDate,$endDate);
			$activePercent          = $activeAccounts > 0 ? ($paidAccounts/$activeAccounts)* 100 : 0;
		    $activeAccountIndicator = Performance::determinePALPerformanceColor($activePercent);
			$averagePercent         = ($salesPercent + $collectionsPercent + $activePercent)/3;
			$avgIndicator           = Performance::determinePerformanceColor($averagePercent);
			echo "<tr>".
						"<td>$i</td>".
						"<td>";echo $staff->ProfileFullName; echo"</td>".
						"<td>";echo number_format($salesTarget * $monthsDifference,2); echo"</td>".
						"<td>";echo $salesCount; echo "</td>".
						"<td>";echo number_format($salesAchieved,2); echo"</td>".
						"<td style='".$salesIndicator."'><strong>"; echo number_format($salesPercent,2);echo" %</strong></td>".
						"<td>";echo number_format($collectionsTarget * $monthsDifference,2); echo"</td>".
						"<td>";echo $collectionsCount; echo"</td>".
						"<td>";echo number_format($collectionsAchieved,2);echo"</td>".
						"<td style='".$collectionsIndicator."'><strong>";echo number_format($collectionsPercent,2);echo"%</strong></td>".
						"<td>";echo $activeAccounts; echo"</td>".
						"<td style='".$activeAccountIndicator."'><strong>"; echo number_format($activePercent,2); echo" %</strong></td>".
						"<td>";echo $paidAccounts; echo"</td>".
						"<td style='".$avgIndicator."'><strong>";echo number_format($averagePercent,2);echo" %</strong></td>".
			 	"</tr>";
			$totalSalesCount                += $salesCount;
			$totalSalesAmount               += $salesTarget * $monthsDifference;
			$totalSalesAchievedAmount       += $salesAchieved;
			$totalCollectionsCount          += $collectionsCount;
			$totalCollectionsAmount         += $collectionsTarget * $monthsDifference;
			$totalCollectionsAchievedAmount += $collectionsAchieved;
			$totalActiveAccounts            += $activeAccounts;
			$totalPaidAccounts              += $paidAccounts;
	 		$i++;
		}
		$totalSalesPercent            = Performance::getPerformancePercentage($totalSalesAmount,$totalSalesAchievedAmount);
		$totalSalesIndicator          = Performance::determinePerformanceColor($totalSalesPercent);
		$totalCollectionsPercent      = Performance::getPerformancePercentage($totalCollectionsAmount,$totalCollectionsAchievedAmount);
		$totalCollectionsIndicator    = Performance::determinePerformanceColor($totalCollectionsPercent);
		$totalActiveAccountsPercent   = $totalActiveAccounts >0 ? ($totalPaidAccounts/$totalActiveAccounts) * 100 : 0;
		$totalActiveAccountsIndicator = Performance::determinePALPerformanceColor($totalActiveAccountsPercent);
		$totalAveragePercent          = ($totalSalesPercent+$totalCollectionsPercent +$totalActiveAccountsPercent)/3;
		$totalAvgIndicator            =  Performance::determinePerformanceColor($totalAveragePercent);
		echo "<tr>
				<td></td><td></td>".
				"<td><strong>"; echo number_format($totalSalesAmount,2); echo "</strong></td>".
				"<td><strong>"; echo number_format($totalSalesCount,2); echo "</strong></td>".
				"<td><strong>"; echo number_format($totalSalesAchievedAmount,2); echo "</strong></td>".
				"<td style='".$totalSalesIndicator."'><strong>"; echo number_format($totalSalesPercent,2);echo" %</strong></td>".
				"<td><strong>"; echo number_format($totalCollectionsAmount,2); echo "</strong></td>".
				"<td><strong>"; echo number_format($totalCollectionsCount,2); echo "</strong></td>".
				"<td><strong>"; echo number_format($totalCollectionsAchievedAmount,2); echo "</strong></td>".
				"<td style='".$totalCollectionsIndicator."'><strong>"; echo number_format($totalCollectionsPercent,2);echo " %</strong></td>".
				"<td><strong>"; echo $totalActiveAccounts; echo "</strong></td>".
				"<td style='".$totalActiveAccountsIndicator."'><strong>"; echo number_format($totalActiveAccountsPercent,2);echo " %</strong></td>".
				"<td><strong>"; echo $totalPaidAccounts; echo "</strong></td>".
				"<td style='".$totalAvgIndicator."'><strong>"; echo number_format($totalAveragePercent,2);echo " %</strong></td>".
		"</tr>";
	}

	public static function getSpecificStaffPerformance($staffs,$startDate,$endDate,$i){
		$daysDifference         = CommonFunctions::getDatesDifference($startDate,$endDate);
		$monthsDifference       = round($daysDifference/30) > 0 ? round($daysDifference/30) : 1;
		$defaultCTarget         = ProfileEngine::getActiveProfileAccountSettingByType($staffs->id,'COLLECTIONS_TARGET');
		$collectionsTarget      = $defaultCTarget === 'NOT SET' ? 0 : floatval($defaultCTarget);
		$defaultSTarget         = ProfileEngine::getActiveProfileAccountSettingByType($staffs->id,'SALES_TARGET');
		$salesTarget            = $defaultSTarget === 'NOT SET' ? 0 : floatval($defaultSTarget);
		$salesCount             = Performance::getTotalStaffSalesCount($staffs->id,$startDate,$endDate);
		$salesAchieved          = Performance::getTotalStaffSales($staffs->id,$startDate,$endDate);
		$salesPercent           = Performance::getPerformancePercentage($salesTarget * $monthsDifference,$salesAchieved);
		$salesIndicator         = Performance::determinePerformanceColor($salesPercent);
		$collectionsCount       = Performance::getTotalStaffCollectionsCount($staffs->id,$startDate,$endDate);
		$collectionsAchieved    = Performance::getTotalStaffCollections($staffs->id,$startDate,$endDate);
		$collectionsPercent     = Performance::getPerformancePercentage($collectionsTarget * $monthsDifference,$collectionsAchieved);
		$collectionsIndicator   = Performance::determinePerformanceColor($collectionsPercent);
		$activeAccounts         = Performance::getStaffActiveAccountsMonthStart($staffs->id,$startDate,$endDate);
		$activePercent          = $activeAccounts > 0 ? ($collectionsCount/$activeAccounts)* 100 : 0;
	    $activeAccountIndicator = Performance::determinePALPerformanceColor($activePercent);
		$averagePercent         = ($salesPercent + $collectionsPercent + $activePercent)/3;
		$avgIndicator           = Performance::determinePerformanceColor($averagePercent);
		echo "<tr>".
					"<td>$i</td>".
					"<td>";echo $staffs->ProfileFullName; echo  "</td>".
				    "<td>";echo number_format($salesTarget  * $monthsDifference,2); echo  "</td>".
				    "<td>";echo $salesCount; echo  "</td>".
					"<td>";echo number_format($salesAchieved,2); echo "</td>".
					"<td style='".$salesIndicator."'><strong>"; echo number_format($salesPercent,2);echo " %</strong></td>".
					"<td>";echo number_format($collectionsTarget* $monthsDifference,2); echo "</td>".
					"<td>";echo $collectionsCount; echo "</td>".
					"<td>";echo number_format($collectionsAchieved,2);echo "</td>".
					"<td style='".$collectionsIndicator."'><strong>";echo number_format($collectionsPercent,2);echo"%</strong></td>".
					"<td>";echo $activeAccounts; echo "</td>".
					"<td style='".$activeAccountIndicator."'><strong>"; echo number_format($activePercent,2);echo" %</strong></td>".
					"<td style='".$avgIndicator."'><strong>"; echo number_format($averagePercent,2);echo " %</strong></td>".
		 	"</tr>";
	}
	/***************************

		EXECUTIVE SUMMARY TABULATIONS
	
	***********************************************/
	public static function getCommonExecutiveSummaryContentHeader($firstColumnLabel){
		echo '<div style="width:100% !important;padding:20px 25px 25px 20px !important;" class="table-responsive">
				<h5 class="title" style="text-decoration:underline !important;margin-bottom:1.5% !important;">'
					;echo strtoupper($firstColumnLabel.' Executive Summary');echo'</h5>
				<table class="table-bordered">
				<thead style="text-transform:uppercase !important; background-color:#7FFFD4;font-weight:bold !important;color:black !important;font-size:14px !important;">              
					<th class="text-center" style="color:#000 !important;font-weight:bold !important;" >#</th>
					<th style="color:#000 !important;font-weight:bold !important;">';echo $firstColumnLabel;echo'</th>';
					if($firstColumnLabel === 'Staff'){
						echo '<th style="color:#000 !important;font-weight:bold !important;">Branch</th>';
					}
					echo '<th style="color:#000 !important;font-weight:bold !important;">0% Principal</th>
					<th style="color:#000 !important;font-weight:bold !important;"> > 0% Principal</th>
					<th style="color:#000 !important;font-weight:bold !important;">Interest<br>Balance</th>
					<th style="color:#000 !important;font-weight:bold !important;">Penalty<br>Balance</th>
					<th style="color:#000 !important;font-weight:bold !important;">Total</th>
					<th style="color:#000 !important;font-weight:bold !important;">Principal<br>Paid</th>
					<th style="color:#000 !important;font-weight:bold !important;">Interest<br>Paid</th>
					<th style="color:#000 !important;font-weight:bold !important;">Penalty<br>Paid</th>
					<th style="color:#000 !important;font-weight:bold !important;">Total</th>
					<th style="color:#000 !important;font-weight:bold !important;">Expenses</th>
					<th style="color:#000 !important;font-weight:bold !important;">Profit/<br>Loss</th>
					<th style="color:#000 !important;font-weight:bold !important;">Daily Int.<br>Accrued</th>
					<th style="color:#000 !important;font-weight:bold !important;">Daily Int.<br>Paid</th>
					<th style="color:#000 !important;font-weight:bold !important;">Monthly<br>Disbursed</th>
					<th style="color:#000 !important;font-weight:bold !important;">Total<br>Savings</th>
					<th style="color:#000 !important;font-weight:bold !important;">All<br>Members</th>
					<th style="color:#000 !important;font-weight:bold !important;">Active<br>Accounts</th>
					<th style="color:#000 !important;font-weight:bold !important;">Loan %<br> Average</th>
				</thead>
				<tbody>';
	}

	public static function getBranchExecutiveSummaryContentBody($branches,$startDate,$endDate,$defaultPeriod){
		if(!empty($branches)){
			$i=1;
			switch(count($branches)){
				case 1:
				echo "<tr>".
						"<td style='background-color:#ffd0d7;'>$i</td>".
						"<td style='background-color:#ffd0d7;'>";echo $branches->name; echo "</td>".
					    "<td>";echo number_format(Performance::getBranchZeroRatedPrincipalBalance($branches->branch_id,$startDate,$endDate,$defaultPeriod),2); echo  "</td>".
					    "<td>";echo number_format(Performance::getBranchInterestRatedPrincipalBalance($branches->branch_id,$startDate,$endDate,$defaultPeriod),2); echo  "</td>".
						"<td>";echo number_format(Performance::getBranchInterestBalance($branches->branch_id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td>";echo number_format(Performance::getBranchPenaltyBalance($branches->branch_id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td style='background-color:#e0e0e0;'>";echo number_format((Performance::getBranchZeroRatedPrincipalBalance($branches->branch_id,$startDate,$endDate,$defaultPeriod)+ Performance::getBranchInterestRatedPrincipalBalance($branches->branch_id,$startDate,$endDate,$defaultPeriod)+ Performance::getBranchInterestBalance($branches->branch_id,$startDate,$endDate,$defaultPeriod)+ Performance::getBranchPenaltyBalance($branches->branch_id,$startDate,$endDate,$defaultPeriod)),2); echo "</td>".
						"<td>";echo number_format(Performance::getBranchPrincipalPaid($branches->branch_id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td>";echo number_format(Performance::getBranchInterestPaid($branches->branch_id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td>";echo number_format(Performance::getBranchPenaltyPaid($branches->branch_id,$startDate,$endDate,$defaultPeriod),2); echo"</td>".
						"<td style='background-color:#e0e0e0;'>";echo number_format((Performance::getBranchPrincipalPaid($branches->branch_id,$startDate,$endDate,$defaultPeriod)+ Performance::getBranchInterestPaid($branches->branch_id,$startDate,$endDate,$defaultPeriod)+ Performance::getBranchPenaltyPaid($branches->branch_id,$startDate,$endDate,$defaultPeriod)),2); echo"</td>".
						"<td>";echo number_format(Performance::getBranchTotalExpenses($branches->branch_id,$startDate,$endDate,$defaultPeriod),2);  echo "</td>".
						"<td style='background-color:#7FFFD4;'>";echo number_format(Performance::getBranchProfitAndLoss($branches->branch_id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td>";echo number_format(Performance::getBranchDailyInterestAccrued($branches->branch_id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
						"<td>";echo number_format(Performance::getBranchDailyInterestPaid($branches->branch_id,$startDate,$endDate,$defaultPeriod),2);echo"</td>".
						"<td>";echo number_format(Performance::getBranchTotalAmountDisbursed($branches->branch_id,$startDate,$endDate,$defaultPeriod),2);echo"</td>".
						"<td style='background-color:#e0e0e0;'>";echo number_format(Performance::getBranchTotalSavings($branches->branch_id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td>";echo number_format(Performance::getBranchTotalMembers($branches->branch_id,$startDate,$endDate,$defaultPeriod)); echo "</td>".
						"<td>";echo number_format(Performance::getBranchTotalActiveLoanAccounts($branches->branch_id,$startDate,$endDate,$defaultPeriod));echo"</td>".
						"<td style='background-color:#ffd0d7;'>";echo number_format(Performance::getBranchAverageLoanAccountsInterestRate($branches->branch_id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
			 	"</tr>";
				break;

				default:
				$totalZeroRatedPrincipal=0;
				$totalInterestRatedPrincipal=0;
				$totalInterestBalance=0;
				$totalPenaltyBalance=0;
				$totalBalances=0;
				$totalPrincipalPaid=0;
				$totalInterestPaid=0;
				$totalPenaltyPaid=0;
				$totalPayments=0;
				$totalExpenses=0;
				$totalProfitAndLoss=0;
				$totalDailyInterestAccrued=0;
				$totalDailyPaidInterest=0;
				$totalAmountDisbursed=0;
				$totalSavingsAmount=0;
				$totalMembers=0;
				$totalActiveAccounts=0;
				$averageAccountsRate=0;
				foreach($branches AS $branch){
					echo "<tr>".
							"<td style='background-color:#ffd0d7;'>$i</td>".
							"<td style='background-color:#ffd0d7;'>";echo $branch->name; echo "</td>".
							"<td>";echo number_format(Performance::getBranchZeroRatedPrincipalBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod),2); echo  "</td>".
							"<td>";echo number_format(Performance::getBranchInterestRatedPrincipalBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod),2); echo  "</td>".
							"<td>";echo number_format(Performance::getBranchInterestBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
							"<td>";echo number_format(Performance::getBranchPenaltyBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
							"<td style='background-color:#e0e0e0;'>";echo number_format((Performance::getBranchZeroRatedPrincipalBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod)+ Performance::getBranchInterestRatedPrincipalBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod)+ Performance::getBranchInterestBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod)+ Performance::getBranchPenaltyBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod)),2); echo "</td>".
							"<td>";echo number_format(Performance::getBranchPrincipalPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
							"<td>";echo number_format(Performance::getBranchInterestPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
							"<td>";echo number_format(Performance::getBranchPenaltyPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod),2);echo"</td>".
							"<td  style='background-color:#e0e0e0;'>";echo number_format((Performance::getBranchPrincipalPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod)+ Performance::getBranchInterestPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod)+ Performance::getBranchPenaltyPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod)),2);echo"</td>".
							"<td>";echo number_format(Performance::getBranchTotalExpenses($branch->branch_id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
							"<td style='background-color:#7FFFD4;'>";echo number_format(Performance::getBranchProfitAndLoss($branch->branch_id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
							"<td>";echo number_format(Performance::getBranchDailyInterestAccrued($branch->branch_id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
							"<td>";echo number_format(Performance::getBranchDailyInterestPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
							"<td>";echo number_format(Performance::getBranchTotalAmountDisbursed($branch->branch_id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
							"<td style='background-color:#e0e0e0;'>";echo number_format(Performance::getBranchTotalSavings($branch->branch_id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
							"<td>";echo number_format(Performance::getBranchTotalMembers($branch->branch_id,$startDate,$endDate,$defaultPeriod));echo "</td>".
							"<td>";echo number_format(Performance::getBranchTotalActiveLoanAccounts($branch->branch_id,$startDate,$endDate,$defaultPeriod));echo "</td>".
							"<td style='background-color:#ffd0d7;'>";echo number_format(Performance::getBranchAverageLoanAccountsInterestRate($branch->branch_id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
			 	       "</tr>";
							$totalZeroRatedPrincipal+=Performance::getBranchZeroRatedPrincipalBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalInterestRatedPrincipal+=Performance::getBranchInterestRatedPrincipalBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalInterestBalance+=Performance::getBranchInterestBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalPenaltyBalance+=Performance::getBranchPenaltyBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalBalances+=(Performance::getBranchZeroRatedPrincipalBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod)+Performance::getBranchInterestRatedPrincipalBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod)+Performance::getBranchInterestBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod)+Performance::getBranchPenaltyBalance($branch->branch_id,$startDate,$endDate,$defaultPeriod));
							$totalPrincipalPaid+=Performance::getBranchPrincipalPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalInterestPaid+=Performance::getBranchInterestPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalPenaltyPaid+=Performance::getBranchPenaltyPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalPayments+=(Performance::getBranchPrincipalPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod)+ Performance::getBranchInterestPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod)+Performance::getBranchPenaltyPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod));
							$totalExpenses+=Performance::getBranchTotalExpenses($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalProfitAndLoss+=Performance::getBranchProfitAndLoss($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalDailyInterestAccrued+=Performance::getBranchDailyInterestAccrued($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalDailyPaidInterest+=Performance::getBranchDailyInterestPaid($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalAmountDisbursed+=Performance::getBranchTotalAmountDisbursed($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalSavingsAmount+=Performance::getBranchTotalSavings($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalMembers+=Performance::getBranchTotalMembers($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$totalActiveAccounts+=Performance::getBranchTotalActiveLoanAccounts($branch->branch_id,$startDate,$endDate,$defaultPeriod);
							$averageAccountsRate+=Performance::getBranchAverageLoanAccountsInterestRate($branch->branch_id,$startDate,$endDate,$defaultPeriod);
			 	  $i++;
				}
				$averageDivisor=$i-1;
				$averageInterestRate=number_format(($averageAccountsRate/$averageDivisor),2);
				echo"<tr style='font-weight:bold;background-color:#e0e0e0;'>".
							"<td colspan='2'>TOTAL AMOUNTS</td>".
							"<td>";echo number_format($totalZeroRatedPrincipal,2);echo"</td>".
							"<td>";echo number_format($totalInterestRatedPrincipal,2);echo"</td>".
							"<td>";echo number_format($totalInterestBalance,2);echo"</td>".
							"<td>";echo number_format($totalPenaltyBalance,2);echo"</td>".
							"<td>";echo number_format($totalBalances,2);echo"</td>".
							"<td>";echo number_format($totalPrincipalPaid,2);echo"</td>".
							"<td>";echo number_format($totalInterestPaid,2);echo"</td>".
							"<td>";echo number_format($totalPenaltyPaid,2);echo"</td>".
							"<td>";echo number_format($totalPayments,2);echo"</td>".
							"<td>";echo number_format($totalExpenses,2);echo"</td>".
							"<td>";echo number_format($totalProfitAndLoss,2);echo"</td>".
							"<td>";echo number_format($totalDailyInterestAccrued,2);echo"</td>".
							"<td>";echo number_format($totalDailyPaidInterest,2);echo"</td>".
							"<td>";echo number_format($totalAmountDisbursed,2);echo"</td>".
							"<td>";echo number_format($totalSavingsAmount,2);echo"</td>".
							"<td>";echo number_format($totalMembers);echo"</td>".
							"<td>";echo number_format($totalActiveAccounts);echo"</td>".
							"<td>";echo number_format($averageInterestRate,2);echo"</td>".
				 	"</tr>";
				break;
			}
		}
	}

	public static function getBranchExecutiveSummaryContent($branches,$startDate,$endDate,$defaultPeriod){
		Tabulate::getCommonExecutiveSummaryContentHeader($firstColumnLabel="Branch");
		Tabulate::getBranchExecutiveSummaryContentBody($branches,$startDate,$endDate,$defaultPeriod);
		Tabulate::createCommonTableFooter();
	}

	public static function getStaffExecutiveSummaryContentBody($staffMembers,$startDate,$endDate,$defaultPeriod){
		if(!empty($staffMembers)){
			$i=1;
			switch(count($staffMembers)){
				case 1:
				echo "<tr>".
						"<td style='background-color:#ffd0d7;'>$i</td>".
						"<td style='background-color:#ffd0d7;'>";echo strtoupper($staffMembers->ProfileFullName);echo"</td>".
						"<td style='background-color:#ffd0d7;'>";echo $staffMembers->ProfileBranch;echo"</td>".
						"<td>";echo number_format(Performance::getStaffZeroRatedPrincipalBalance($staffMembers->id,$startDate,$endDate,$defaultPeriod),2);echo"</td>".
						"<td>";echo number_format(Performance::getStaffInterestRatedPrincipalBalance($staffMembers->id,$startDate,$endDate,$defaultPeriod),2);echo"</td>".
						"<td>";echo number_format(Performance::getStaffInterestBalance($staffMembers->id,$startDate,$endDate,$defaultPeriod),2);echo"</td>".
						"<td>";echo number_format(Performance::getStaffPenaltyBalance($staffMembers->id,$startDate,$endDate,$defaultPeriod),2);echo"</td>".
						"<td style='background-color:#e0e0e0;'>";echo number_format((Performance::getStaffZeroRatedPrincipalBalance($staffMembers->id,$startDate,$endDate,$defaultPeriod)+Performance::getStaffInterestRatedPrincipalBalance($staffMembers->id,$startDate,$endDate,$defaultPeriod)+ Performance::getStaffInterestBalance($staffMembers->id,$startDate,$endDate,$defaultPeriod)+ Performance::getStaffPenaltyBalance($staffMembers->id,$startDate,$endDate,$defaultPeriod)),2);echo"</td>".
						"<td>";echo number_format(Performance::getStaffPrincipalPaid($staffMembers->id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td>";echo number_format(Performance::getStaffInterestPaid($staffMembers->id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td>";echo number_format(Performance::getStaffPenaltyPaid($staffMembers->id,$startDate,$endDate,$defaultPeriod),2); echo"</td>".
						"<td style='background-color:#e0e0e0;'>";echo number_format((Performance::getStaffPrincipalPaid($staffMembers->id,$startDate,$endDate,$defaultPeriod)+Performance::getStaffInterestPaid($staffMembers->id,$startDate,$endDate,$defaultPeriod)+Performance::getStaffPenaltyPaid($staffMembers->id,$startDate,$endDate,$defaultPeriod)),2); echo"</td>".
						"<td>";echo number_format(Performance::getStaffTotalExpenses($staffMembers->id,$startDate,$endDate,$defaultPeriod),2);  echo "</td>".
						"<td style='background-color:#7FFFD4;'>";echo number_format(Performance::getStaffProfitAndLoss($staffMembers->id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td>";echo number_format(Performance::getStaffDailyInterestAccrued($staffMembers->id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
						"<td>";echo number_format(Performance::getStaffDailyInterestPaid($staffMembers->id,$startDate,$endDate,$defaultPeriod),2);echo"</td>".
						"<td>";echo number_format(Performance::getStaffTotalAmountDisbursed($staffMembers->id,$startDate,$endDate,$defaultPeriod),2);echo"</td>".
						"<td style='background-color:#e0e0e0;'>";echo number_format(Performance::getStaffTotalSavings($staffMembers->id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td>";echo number_format(Performance::getStaffTotalMembers($staffMembers->id,$startDate,$endDate,$defaultPeriod)); echo "</td>".
						"<td>";echo number_format(Performance::getStaffTotalActiveLoanAccounts($staffMembers->id,$startDate,$endDate,$defaultPeriod));echo"</td>".
						"<td style='background-color:#ffd0d7;'>";echo number_format(Performance::getStaffAverageLoanAccountsInterestRate($staffMembers->id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
			 	"</tr>";
				break;

				default:
				$totalZeroRatedPrincipal=0;
				$totalInterestRatedPrincipal=0;
				$totalInterestBalance=0;
				$totalPenaltyBalance=0;
				$totalBalances=0;
				$totalPrincipalPaid=0;
				$totalInterestPaid=0;
				$totalPenaltyPaid=0;
				$totalPayments=0;
				$totalExpenses=0;
				$totalProfitAndLoss=0;
				$totalDailyInterestAccrued=0;
				$totalDailyPaidInterest=0;
				$totalAmountDisbursed=0;
				$totalSavingsAmount=0;
				$totalMembers=0;
				$totalActiveAccounts=0;
				$averageAccountsRate=0;
				foreach($staffMembers AS $branch){
					echo "<tr>".
						"<td style='background-color:#ffd0d7;'>$i</td>".
						"<td style='background-color:#ffd0d7;'>";echo strtoupper($branch->ProfileFullName); echo "</td>".
						"<td style='background-color:#ffd0d7;'>";echo $branch->ProfileBranch;echo"</td>".
						"<td>";echo number_format(Performance::getStaffZeroRatedPrincipalBalance($branch->id,$startDate,$endDate,$defaultPeriod),2); echo  "</td>".
						"<td>";echo number_format(Performance::getStaffInterestRatedPrincipalBalance($branch->id,$startDate,$endDate,$defaultPeriod),2); echo  "</td>".
						"<td>";echo number_format(Performance::getStaffInterestBalance($branch->id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td>";echo number_format(Performance::getStaffPenaltyBalance($branch->id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td style='background-color:#e0e0e0;'>";echo number_format((Performance::getStaffZeroRatedPrincipalBalance($branch->id,$startDate,$endDate,$defaultPeriod)+Performance::getStaffInterestRatedPrincipalBalance($branch->id,$startDate,$endDate,$defaultPeriod)+Performance::getStaffInterestBalance($branch->id,$startDate,$endDate,$defaultPeriod)+Performance::getStaffPenaltyBalance($branch->id,$startDate,$endDate,$defaultPeriod)),2); echo "</td>".
						"<td>";echo number_format(Performance::getStaffPrincipalPaid($branch->id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td>";echo number_format(Performance::getStaffInterestPaid($branch->id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
						"<td>";echo number_format(Performance::getStaffPenaltyPaid($branch->id,$startDate,$endDate,$defaultPeriod),2);echo"</td>".
						"<td style='background-color:#e0e0e0;'>";echo number_format((Performance::getStaffPrincipalPaid($branch->id,$startDate,$endDate,$defaultPeriod)+Performance::getStaffInterestPaid($branch->id,$startDate,$endDate,$defaultPeriod)+Performance::getStaffPenaltyPaid($branch->id,$startDate,$endDate,$defaultPeriod)),2);echo"</td>".
						"<td>";echo number_format(Performance::getStaffTotalExpenses($branch->id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
						"<td style='background-color:#7FFFD4;'>";echo number_format(Performance::getStaffProfitAndLoss($branch->id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
						"<td>";echo number_format(Performance::getStaffDailyInterestAccrued($branch->id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
						"<td>";echo number_format(Performance::getStaffDailyInterestPaid($branch->id,$startDate,$endDate,$defaultPeriod),2); echo "</td>".
						"<td>";echo number_format(Performance::getStaffTotalAmountDisbursed($branch->id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
						"<td style='background-color:#e0e0e0;'>";echo number_format(Performance::getStaffTotalSavings($branch->id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
						"<td>";echo number_format(Performance::getStaffTotalMembers($branch->id,$startDate,$endDate,$defaultPeriod));echo "</td>".
						"<td>";echo number_format(Performance::getStaffTotalActiveLoanAccounts($branch->id,$startDate,$endDate,$defaultPeriod));echo "</td>".
						"<td style='background-color:#ffd0d7;'>";echo number_format(Performance::getStaffAverageLoanAccountsInterestRate($branch->id,$startDate,$endDate,$defaultPeriod),2);echo "</td>".
			 	"</tr>";
							$totalZeroRatedPrincipal+=Performance::getStaffZeroRatedPrincipalBalance($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalInterestRatedPrincipal+=Performance::getStaffInterestRatedPrincipalBalance($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalInterestBalance+=Performance::getStaffInterestBalance($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalPenaltyBalance+=Performance::getStaffPenaltyBalance($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalBalances+=(Performance::getStaffZeroRatedPrincipalBalance($branch->id,$startDate,$endDate,$defaultPeriod)+Performance::getStaffInterestRatedPrincipalBalance($branch->id,$startDate,$endDate,$defaultPeriod)+Performance::getStaffInterestBalance($branch->id,$startDate,$endDate,$defaultPeriod)+Performance::getStaffPenaltyBalance($branch->id,$startDate,$endDate,$defaultPeriod));
							$totalPrincipalPaid+=Performance::getStaffPrincipalPaid($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalInterestPaid+=Performance::getStaffInterestPaid($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalPenaltyPaid+=Performance::getStaffPenaltyPaid($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalPayments+=(Performance::getStaffPrincipalPaid($branch->id,$startDate,$endDate,$defaultPeriod)+ Performance::getStaffInterestPaid($branch->id,$startDate,$endDate,$defaultPeriod)+Performance::getStaffPenaltyPaid($branch->id,$startDate,$endDate,$defaultPeriod));
							$totalExpenses+=Performance::getStaffTotalExpenses($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalProfitAndLoss+=Performance::getStaffProfitAndLoss($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalDailyInterestAccrued+=Performance::getStaffDailyInterestAccrued($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalDailyPaidInterest+=Performance::getStaffDailyInterestPaid($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalAmountDisbursed+=Performance::getStaffTotalAmountDisbursed($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalSavingsAmount+=Performance::getStaffTotalSavings($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalMembers+=Performance::getStaffTotalMembers($branch->id,$startDate,$endDate,$defaultPeriod);
							$totalActiveAccounts+=Performance::getStaffTotalActiveLoanAccounts($branch->id,$startDate,$endDate,$defaultPeriod);
							$averageAccountsRate+=Performance::getStaffAverageLoanAccountsInterestRate($branch->id,$startDate,$endDate,$defaultPeriod);
			 	  $i++;
				}
				$averageDivisor=$i-1;
				$averageInterestRate=number_format(($averageAccountsRate/$averageDivisor),2);
				echo"<tr style='font-weight:bold; background-color:#e0e0e0;'>".
							"<td colspan='3'>TOTAL AMOUNTS</td>".
							"<td>";echo number_format($totalZeroRatedPrincipal,2);echo"</td>".
							"<td>";echo number_format($totalInterestRatedPrincipal,2);echo"</td>".
							"<td>";echo number_format($totalInterestBalance,2);echo"</td>".
							"<td>";echo number_format($totalPenaltyBalance,2);echo"</td>".
							"<td>";echo number_format($totalBalances,2);echo"</td>".
							"<td>";echo number_format($totalPrincipalPaid,2);echo"</td>".
							"<td>";echo number_format($totalInterestPaid,2);echo"</td>".
							"<td>";echo number_format($totalPenaltyPaid,2);echo"</td>".
							"<td>";echo number_format($totalPayments,2);echo"</td>".
							"<td>";echo number_format($totalExpenses,2);echo"</td>".
							"<td>";echo number_format($totalProfitAndLoss,2);echo"</td>".
							"<td>";echo number_format($totalDailyInterestAccrued,2);echo"</td>".
							"<td>";echo number_format($totalDailyPaidInterest,2);echo"</td>".
							"<td>";echo number_format($totalAmountDisbursed,2);echo"</td>".
							"<td>";echo number_format($totalSavingsAmount,2);echo"</td>".
							"<td>";echo number_format($totalMembers);echo"</td>".
							"<td>";echo number_format($totalActiveAccounts);echo"</td>".
							"<td>";echo number_format($averageInterestRate,2);echo"</td>".
				 	"</tr>";
				break;
			}
		}
	}

	public static function getStaffExecutiveSummaryContent($staffMembers,$startDate,$endDate,$defaultPeriod){
		Tabulate::getCommonExecutiveSummaryContentHeader($firstColumnLabel="Staff");
		Tabulate::getStaffExecutiveSummaryContentBody($staffMembers,$startDate,$endDate,$defaultPeriod);
		Tabulate::createCommonTableFooter();
	}

	public static function getChamaMembersTabulation($members){
		Tabulate::getChamaMembersContentHeader();
		Tabulate::getChamaMembersContentBody($members);
		Tabulate::createCommonTableFooter();
	}

	public static function getChamaMembersContentHeader(){
		echo '<div style="width:100% !important;padding:20px 25px 25px 20px !important;">
	            <table class="table table-bordered table-condensed">
	               <thead class="text-primary" style="text-transform: uppercase !important;">              
						<th class="text-center">#</th>
						<th>Member</th>
						<th>Loan Account</th>
						<th>Loan Balance</th>
						<th>Saving Account</th>
						<th>Savings Balance</th>
						<th>Action</th>
					</thead>
					<tbody>';
	}

	public static function getChamaMembersContentBody($members){
		if($members != 0){
			$i = 1;
			$totalLoanBalance    = 0;
			$totalSavingsBalance = 0;
			foreach($members AS $member){
			echo "<tr>".
				    "<td>$i</td>".
				    "<td>";echo $member->ChamaMemberName; echo "</td>".
				    "<td>";echo $member->ChamaMemberActiveLoanAccountNumber; echo "</td>".
				    "<td> KES ";echo number_format($member->ChamaMemberActiveLoanBalance,2);   echo "</td>".
				    "<td>";echo $member->ChamaMemberSavingAccountNumber; echo "</td>".
				    "<td> KES ";echo number_format($member->ChamaMemberSavingsBalance,2); echo "</td>".
				    "<td>";echo $member->ChamaMemberAction; echo "</td>".
		         "</tr>";
			$totalLoanBalance    += $member->ChamaMemberActiveLoanBalance;
			$totalSavingsBalance += $member->ChamaMemberSavingsBalance;
			$i++;
			}
			echo"<tr style='font-weight:bold; background-color:#e0e0e0;'>".
					"<td colspan='3'>TOTAL AMOUNTS</td>".
					"<td> KES ";echo number_format($totalLoanBalance,2);    echo"</td>".
					"<td></td>".
					"<td> KES ";echo number_format($totalSavingsBalance,2); echo"</td>".
					"<td></td>".
				"</tr>";
		}
	}
}