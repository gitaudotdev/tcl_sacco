<?php

include_once('config.php');
include_once('Utilities.php');

function recordLoanPenalties(){
	$conn    = SaccoDB();
	$today   = date('Y-m-d');
	$loanSQL = "SELECT * FROM loanaccounts WHERE loan_status NOT IN('0','1','3','4','8','9','10') AND penalty_frozen = '1'";
	$result  = $conn->query($loanSQL);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
            generatePenalty($row['loanaccount_id'],$conn,$row['repayment_start_date'],$today,$row['penalty_amount'],$row['penalty_frozen'],$row['pay_mode']);
		}
	}else{
		echo "No Account Found. \n";
	}
}

function generatePenalty($loanaccountID,$conn,$repaymentDate,$today,$penaltyAmount,$isFrozen,$frequency){
//    $currentDate = strtotime($today);
//    $repaymentDateTime = strtotime($repaymentDate);

    $previousDay = date('Y-m-d', strtotime($today . ' -1 day')); // Get previous day
    $repaymentDateTime = strtotime($repaymentDate);

    // Calculate the difference in days
    $daysDifference = floor(($previousDay - $repaymentDateTime) / (60 * 60 * 24));

    switch ($frequency) {
        case 'daily':
            $isOnTime = ($daysDifference === 0);
            break;

        case 'bi-weekly':
            $isOnTime = ($daysDifference % 14 === 0);
            break;

        case 'monthly':
            $isOnTime = (date('d', $previousDay) === date('d', $repaymentDateTime));
            break;

        case 'quarterly':
            $isOnTime = ($daysDifference % (3 * 30) === 0);
            break;

        default:
            echo "Invalid repayment frequency.\n";
            return;
    }

    if (!$isOnTime) {
        if (checkIfLoanPaidTimely($loanaccountID, $repaymentDate, $conn) === 0) {
            $penaltyCheckDate = ($frequency === 'daily') ? $repaymentDate : date('m', $previousDay);

            if (checkIfPenaltyRecorded($loanaccountID, $penaltyCheckDate, $isFrozen, $conn) === 0) {
                createLoanPenalty($loanaccountID, $repaymentDate, $penaltyAmount, $isFrozen, $conn);
                updateLoanRepaymentDate($loanaccountID, $conn, $repaymentDate);
            } else {
                echo "Loan Penalty Already Recorded \n";
            }
        } else {
            echo "Loan Paid on Time \n";
        }
    } else {
        echo "Current Date is within the specified repayment period \n";
    }
}


function generateDailyPenalty($loanaccountID,$conn,$repaymentDate,$today,$month,$penaltyAmount,$interestRate,$currentArrears,$isFrozen,$frequency){

    $repaymentDateTime = new DateTime($repaymentDate);
    $todayTime = new DateTime($today);

    $difference = $todayTime->diff($repaymentDateTime);
    $differenceDays = (int)$difference->format('%R%a');

    $hasPaymentToday = checkForPaymentToday($loanaccountID, $today, $conn);

    if ($differenceDays > 0 && !$hasPaymentToday) {
        if (checkIfPenaltyRecorded($loanaccountID, $today, $isFrozen, $conn) === 0) {
            $dailyPenalty = calculateDailyPenalty($penaltyAmount, $interestRate, $currentArrears);
            createLoanPenalty($loanaccountID, $today, $dailyPenalty, $isFrozen, $conn);
            updateLoanRepaymentDate($loanaccountID, $conn, $repaymentDate);
        } else {
            echo "Loan Penalty Already Recorded \n";
        }
//        if (checkIfLoanPaidTimely($loanaccountID, $repaymentDate, $conn) === 0) {
//
//        } else {
//            echo "Loan Paid on Time \n";
//        }
    } else {
        echo "No penalty generated for loan account $loanaccountID today.\n";
    }
}

function calculateDailyPenalty($penaltyAmount, $interestRate, $currentArrears){
    $dailyInterestRate = $interestRate / 30;

    // Calculate the base daily penalty amount (you can adjust this part)
    $baseDailyPenalty = $penaltyAmount;

    // Calculate the penalty based on arrears and interest rate
    $dailyPenalty = $baseDailyPenalty + ($currentArrears * $dailyInterestRate);

    return $dailyPenalty;
}

function checkForPaymentToday($loanaccountID, $today, $conn) {
    $paymentSql = "SELECT * FROM loantransactions WHERE loanaccount_id = $loanaccountID AND DATE(transacted_at) = '$today'";
    $result = $conn->query($paymentSql);
    return $result->num_rows > 0;
}

function checkIfLoanPaidTimely($loanaccountID,$repaymentDate,$conn){
	$todaysDate   = date('Y-m-d');
	$repaymentSql = "SELECT * FROM loantransactions WHERE DATE(transacted_at) <= '$repaymentDate' AND MONTH(transacted_at)='$todaysDate'
	AND loanaccount_id=$loanaccountID AND is_void IN('0','3','4')";
	$result       = $conn->query($repaymentSql);
	return $result->num_rows > 0 ? 1 : 0;
}

function checkIfPenaltyRecorded($loanaccountID,$date,$isFrozen,$conn){
    $date = date('Y-m-d', strtotime($date . ' -1 day'));
    $month = date('m', strtotime($date));
    $currentYear = date('Y', strtotime($date));
    if ($isFrozen == '0') {
        $penaltySQL = "SELECT * FROM penaltyaccrued WHERE DATE(date_defaulted) = '$date' AND loanaccount_id = $loanaccountID";
    } else {
        $penaltySQL = "SELECT * FROM penaltyaccrued WHERE MONTH(date_defaulted)='$month' AND YEAR(date_defaulted)='$currentYear' AND loanaccount_id = $loanaccountID";
    }
    $result = $conn->query($penaltySQL);
    return $result->num_rows > 0 ? 1 : 0;
}

function createLoanPenalty($loanaccountID,$repaymentDate,$penaltyAmount,$isFrozen,$conn){
    if($isFrozen == '0'){
        $penaltySQL = "INSERT INTO penaltyaccrued (loanaccount_id, date_defaulted, penalty_amount)
        VALUES ('$loanaccountID', '$repaymentDate', $penaltyAmount)";
        echo $conn->query($penaltySQL) ? "New Penalty Record Created. \n" : "No Penalty Record Created. \n";
    }else{
        echo "Loan Account Penalty Amount Not Set. \n";
    }
}

function updateLoanRepaymentDate($loanaccountID,$conn,$repaymentDate){
//	$nextRepaymentDate = date('Y-m-d',strtotime($repaymentDate.'+ 1 month'));
    $nextRepaymentDate = date('Y-m-d', strtotime($repaymentDate . ' + 1 day'));
	$updateSql         = "UPDATE loanaccounts SET next_pay_date='$nextRepaymentDate' WHERE loanaccount_id='$loanaccountID'";
	echo $conn->query($updateSql) ? "repayment date updated. \n" : "repayment date not updated. \n";
}
/********************************
Invoke Main Method
************************************/
recordLoanPenalties();