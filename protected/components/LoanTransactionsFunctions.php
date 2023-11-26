<?php

class LoanTransactionsFunctions{

public static function displayLoanRepaymentSchedule($loanaccount_id){
    $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);
    $date = date("jS M Y", strtotime($loanaccount->repayment_start_date));
    $interestRate = $loanaccount->interest_rate/100;
    $incrementRate= 1+ $interestRate;
    $periods = $loanaccount->repayment_period;
    $amountApproved=$loanaccount->NotFormattedExactAmountDisbursed;
    $emi = LoanApplication::getEMIAmount($loanaccount_id);
    $totalamount= round($emi * $periods,4); 
    $interestAmount=abs($totalamount-$amountApproved);  
    echo '<table class="table table-condensed table-striped">
            <thead>
            <th>Installment #</th>
            <th>Date </th>
            <th>Principal </th>
            <th>Interest </th>
            <th>Total </th>
            <th>Loan Balance</th>           
          </thead>
        <tbody>';
    echo '<tr>
          <td>0</td>
          <td>';echo $date;echo'</td>
          <td>';echo CommonFunctions::asMoney(round($amountApproved,4));echo'</td>
          <td>';echo CommonFunctions::asMoney(round($interestAmount,4));echo'</td>
          <td>';echo CommonFunctions::asMoney(round($totalamount,4));echo'</td>
          <td>';echo CommonFunctions::asMoney(round($totalamount,4));echo'</td>              
        </tr>';
    if($interestRate <= 0){
        $interest=0;
        $balance = round($amountApproved + $interest,4);
    }else{
        $interest=round($amountApproved * $interestRate,4);
        $balance =round($totalamount,4);
    }
    $principalPaid=0;
    for($i=1;$i<=$periods;$i++){
        $payablePrincipal=round($emi-$interest);
        $loanBalance=round($balance-$emi);
        if($loanBalance <= 0){
            $loanBalance=0;
        }
        echo'<tr>
            <td>';echo $i;echo'</td>
            <td>';echo $date;echo'</td>'; 
        echo'<td>';echo CommonFunctions::asMoney($payablePrincipal);echo'</td>
            <td>';echo CommonFunctions::asMoney($interest);echo'</td>
            <td>';echo CommonFunctions::asMoney($emi);echo'</td>
            <td>';echo CommonFunctions::asMoney($loanBalance);echo'</td>
        </tr>';
        if($interestRate <= 0){
            $interest= 0;
            $calculate=$balance - $emi; 
        }else{
            $principalPaid+=$payablePrincipal;
            $principalBalance=$amountApproved-$principalPaid;
            $interest= round($principalBalance* $interestRate,4);
            $calculate=($balance - $emi);               
        }
        $balance = round($calculate,4); 
        $date = date('jS M Y', strtotime($date. ' + 1 month'));
    }
    echo'</tbody>
        </table>';
}

public static function getTotalLoanAmount($loanaccount_id){
    $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);;
    $periods = $loanaccount->repayment_period;
    $emi=LoanApplication::getEMIAmount($loanaccount_id);
    $totalLoanAmount= round($emi*$periods,4);   
    return $totalLoanAmount;
}

public static function getTotalInterestAmount($loanaccount_id){
    $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);
    $amountApproved=$loanaccount->NotFormattedExactAmountDisbursed;
    $principalBalance=LoanTransactionsFunctions::getLoanPrincipalBalance($loanaccount_id);
    $interestRate=$loanaccount->interest_rate/100;
    if($interestRate <= 0){
      $interestAmount=0; 
    }else{
      $interestAmount=$interestRate * $principalBalance;   
    }
    return $interestAmount;
}

public static function getLoanInterestBalanceSpecificDate($loanaccount_id,$startDate){
    return LoanTransactionsFunctions::getLoanInterestBalanceSpecific($loanaccount_id,$startDate);
}

public static function getLoanInterestBalanceSpecific($loanaccount_id,$startDate){
    $interestAccrued=LoanTransactionsFunctions::getTotalInterestAmount($loanaccount_id);
    $interestPaid=LoanRepayment::getTotalInterestPaidSpecificDate($loanaccount_id,$startDate);
    $interestBal=$interestAccrued - $interestPaid;
    if($interestBal <= 0){
        $interestBalance=0;
    }else{
        $interestBalance=$interestBal;
    }
    return $interestBalance;
}

public static function getAccountDisbursementAmount($loanaccount_id){
    $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);
    $amountDisbursed=$loanaccount->NotFormattedExactAmountDisbursed;
    return $amountDisbursed;
}

public static function getLoanPrincipalBalance($loanaccount_id){
    $amountApproved=LoanTransactionsFunctions::getAccountDisbursementAmount($loanaccount_id);
    $principalPaid=LoanRepayment::getTotalPrincipalPaid($loanaccount_id);
    $principalBalance=$amountApproved - $principalPaid;
    if($principalBalance <= 0){
       $principalBalance=0;
    }
    return $principalBalance;
} 


public static function getLoanPrincipalBalanceFrom($loanaccount_id,$startDate){
    $amountApproved=LoanTransactionsFunctions::getAccountDisbursementAmount($loanaccount_id);
    $principalPaid=LoanRepayment::getTotalPrincipalPaidFrom($loanaccount_id,$startDate);
    $principalBalance=$amountApproved - $principalPaid;
    return $principalBalance;
}

public static function getCurrentMonthLoanBalance($loanaccount_id){
    $principalBalance=LoanTransactionsFunctions::getLoanPrincipalBalance($loanaccount_id);
    $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);
    $loanArrears=$loanaccount->arrears;
    $currentLoanBalance=$principalBalance + $loanArrears;
    return round($currentLoanBalance);
}

public static function getPrincipalAmountDisbursed($loanaccountID){
    $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);
    $amountDisbursed=$loanaccount->NotFormattedExactAmountDisbursed;
    return $amountDisbursed;
}

public static function getCurrentMonthInterestBalance($loanaccount_id,$start_date,$end_date){
    $principalBalance=LoanTransactionsFunctions::getLoanPrincipalBalance($loanaccount_id);
    $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);
    $repaymentStartDate=$loanaccount->repayment_start_date;
    if($repaymentStartDate <= $start_date){
        $interestRate=$loanaccount->interest_rate/100;
        if($interestRate <= 0){
            $interestAmount=0; 
            $currentLoanBalance=$interestAmount;
        }else{
            $interestAmount=$interestRate * $principalBalance; 
            if($interestAmount <=0){
                $interestAmount=0;
            }
            $currentLoanBalance=$interestAmount;  
        }
    }else{
        $interestAmount=0; 
        $currentLoanBalance=$interestAmount;
    }
    return $currentLoanBalance;
}

public static function getTotalAmountPaid($loanaccount_id){
    $TransactionSQL="SELECT * FROM loantransactions WHERE loanaccount_id=$loanaccount_id
     AND is_void IN('0','3','4')";
    $transactions=Loantransactions::model()->findAllBySql($TransactionSQL);
    if(!empty($transactions)){
        $totalPaid=0;
        foreach($transactions as $transaction){
            $totalPaid+=$transaction->amount;
        }
    }else{
        $totalPaid=0;
    }
    return $totalPaid;
}

public static function getInterestPayable($loanaccount_id){
    $dateBefore=date('Y-m-d');
    $interestAmount=LoanTransactionsFunctions::getAccruedInterestPrior($loanaccount_id,$dateBefore);
    if($interestAmount <=0){
      $interestAmount=0;
    }
    return $interestAmount;
}

public static function determineIfInterestPayable($loanaccount_id){
    $lastDate=LoanTransactionsFunctions::getLastInterestPaymentDate($loanaccount_id);
    if($lastDate == 0){
        $status=1;
        return $status;
    }else{
        $monthPaid=date('m',strtotime($lastDate));
        $yearPaid=date('Y',strtotime($lastDate));
        $nowMonth=date('m');
        $nowYear=date('Y');
        if($monthPaid === $nowMonth && $yearPaid === $nowYear){
            $status=0;
            return $status;
        }else{
            $status=1;
            return $status;
        }
    }
}

public static function getLastInterestPaymentDate($loanaccount_id){
    $interestSQL="SELECT * FROM loanrepayments WHERE loanaccount_id=$loanaccount_id
     AND is_void IN('0','2','3','4') ORDER BY loanrepayment_id DESC LIMIT 1";
    $repayment=Loanrepayments::model()->findBySql($interestSQL);
    if(!empty($repayment)){
        if($repayment->interest_paid != 0.00){
            return date('Y-m-d',strtotime($repayment->repaid_at));
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}

public static function getLastAccountPaymentDate($loanaccount_id){
    $interestSQL="SELECT * FROM loanrepayments WHERE loanaccount_id=$loanaccount_id
     AND is_void IN('0','3','4') ORDER BY loanrepayment_id DESC LIMIT 1";
    $repayment=Loanrepayments::model()->findBySql($interestSQL);
    if(!empty($repayment)){
        return date('Y-m-d',strtotime($repayment->repaid_at));
    }else{
        return 0;
    }
}

public static function getLoanInterestBalance($loanaccount_id){
    return LoanManager::getUnpaidLoanInterestBalance($loanaccount_id);
}

public static function getTotalLoanBalance($loanaccount_id){
    $loanaccount=Loanaccounts::model()->findByPk($loanaccount_id);
    $totalPrincipalBalance= LoanTransactionsFunctions::getLoanPrincipalBalance($loanaccount_id);
    $totalInterestBalance = LoanTransactionsFunctions::getLoanInterestBalance($loanaccount_id);
    $totalAccruedPenalty  = LoanManager::getUnpaidAccruedPenalty($loanaccount_id);
    return $totalPrincipalBalance + $totalInterestBalance + $loanaccount->arrears + $totalAccruedPenalty;
}

public static function getTotalLoanBalanceFrom($loanaccount_id,$startDate){
    $principalBalance=LoanTransactionsFunctions::getLoanPrincipalBalanceFrom($loanaccount_id, $startDate);
    $interestBalance=LoanTransactionsFunctions::getLoanInterestBalanceSpecific($loanaccount_id,$startDate);
    $loanBalance=$principalBalance + $interestBalance;
    return $loanBalance;
}

public static function getLoanLastRepayment($loanaccount_id){
    $transactionSQL="SELECT * FROM loantransactions WHERE loanaccount_id=$loanaccount_id ORDER BY loantransaction_id DESC LIMIT 1";
    $transaction=Loantransactions::model()->findBySql($transactionSQL);
    if(!empty($transaction)){
        $amount=$transaction->amount;
    }else{
        $amount=0;
    }
    return CommonFunctions::asMoney($amount);
}

public static function createLoanTransaction($loanaccount_id,$amount,$voidType){
    $account=Loanaccounts::model()->findByPk($loanaccount_id);
    $transaction=new Loantransactions;
    $transaction->loanaccount_id=$loanaccount_id;
    $transaction->date=date('Y-m-d');
    $transaction->amount=$amount;
    $transaction->type='1';
    $transaction->is_void=$voidType;
    $transaction->transacted_by=$account->rm;
    if($transaction->save()){
      $status=$transaction->loantransaction_id;
    }else{
      $status=0;
    }
    return $status;
}

public static function getCurrentLoanBalance($loanaccount_id){
    $loanaccount=LoanApplication::getLoanAccount($loanaccount_id);
    if(!empty($loanaccount)){
        if($loanaccount->loan_status =='4'){
            $currentBalance = 0;
        }else{
            $currentArrears=$loanaccount->arrears;
            $penaltyAccrued = LoanRepayment::getAccruedPenalty($loanaccount_id);
            $principalBalance=LoanManager::getPrincipalBalance($loanaccount_id);
            $currentInterest=LoanManager::getUnpaidLoanInterestBalance($loanaccount_id);
            $totalBalance=$principalBalance + $currentInterest + $penaltyAccrued + $currentArrears;
            switch(LoanTransactionsFunctions::checkIfLoanAccountWrittenOff($loanaccount_id)){
                case 0:
                $currentBalance=$totalBalance;
                break;

                default:
                if(LoanTransactionsFunctions::checkIfLoanAccountWrittenOff($loanaccount_id) >= $totalBalance){
                    $loanaccount=Loanaccounts::model()->findByPk($loanaccount_id);
                    $loanaccount->loan_status='4';
                    $loanaccount->update();
                    $currentBalance = 0;
                }else{
                    $currentBalance=$totalBalance;
                }
                break;
            } 
        }
    }else{
        $currentBalance = 0;
    }
    
    return $currentBalance;
}

public static function checkIfLoanAccountWrittenOff($loanaccount_id){
    $checkQuery = "SELECT COALESCE(SUM(write_offs.amount),0) AS amount FROM write_offs WHERE write_offs.loanaccount_id=$loanaccount_id
     AND write_offs.type != 'Interest Accrued'";
    $account    = WriteOffs::model()->findBySql($checkQuery);
    return !empty($account) ? $account->amount : 0;
}

public static function freezeLoanRepayments($loanaccount_id,$type){
    LoanTransactionsFunctions::freezeTransactions($loanaccount_id,$type);
    LoanRepayment::freezeRepayments($loanaccount_id,$type);
}


public static function freezeTransactions($loanaccount_id){
    $transactionSQL="SELECT * FROM loantransactions WHERE loanaccount_id=$loanaccount_id AND is_void='0'";
    $transactions=Loantransactions::model()->findAllBySql($transactionSQL);
    if(!empty($transactions)){
        foreach($transactions as $transaction){
            $transaction->is_void=$type;
            $transaction->save();
        }
    }     
}

/****************************************************

 Loan Interests Accrual and modified calculations

********************************************************/
public static function getAccruedInterestPrior($loanaccountID,$dateBefore){
    $interestQuery="SELECT SUM(interest_accrued) AS interest_accrued FROM loaninterests WHERE loanaccount_id=$loanaccountID AND DATE(accrued_at) <= '$dateBefore' AND is_paid='0'";
    $interest=Loaninterests::model()->findBySql($interestQuery);
    if(!empty($interest)){
        $interestAccrued=$interest->interest_accrued;
    }else{
        $interestAccrued=0;
    }
    return $interestAccrued;
}

public static function updateAccruedInterestPrior($loanaccountID,$dateBefore){
    $interestQuery="SELECT * FROM loaninterests WHERE loanaccount_id=$loanaccountID AND DATE(accrued_at) <= '$dateBefore' AND is_paid='0'";
    $interests=Loaninterests::model()->findAllBySql($interestQuery);
    if(!empty($interests)){
        foreach($interests AS $interest){
            $interest->is_paid='1';
            $interest->save();
        }
        $updated=1;
    }else{
        $updated=0;
    }
    return $updated;
}

public static function recordAccruedInterestNotPaid($loanaccountID,$amount){
    $accrueInterest=new Loaninterests;
    $accrueInterest->loanaccount_id=$loanaccountID;
    $accrueInterest->interest_accrued=$amount;
    $accrueInterest->save();
}


}