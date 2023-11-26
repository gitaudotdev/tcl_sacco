<?php

class LoanCalculator{

    public static function getLoanCalculatorSchedule($interest_rate,$period,$amount_applied){
        $today=date('Y-m-d');
        $date = date("jS M Y",strtotime($today.'+30 days'));
        $periods=$period;
        $emi = LoanCalculator::getEMIAmount($amount_applied,$interest_rate,$period);
        $totalamount= round($emi * $period,4);
        $interestAmount=abs($totalamount-$amount_applied);
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
              <td>';echo CommonFunctions::asMoney(round($amount_applied,4));echo'</td>
              <td>';echo CommonFunctions::asMoney(round($interestAmount,4));echo'</td>
              <td>';echo CommonFunctions::asMoney(round($totalamount,4));echo'</td>
              <td>';echo CommonFunctions::asMoney(round($totalamount,4));echo'</td>              
            </tr>';
        if($interest_rate <= 0){
            $interest=0;
            $balance = round($amount_applied+ $interest,4);
        }else{
            $interestRate=$interest_rate/100;
            $interest=round($amount_applied * $interestRate,4);
            $balance =round($totalamount,4);
        }
        for($i=1;$i<=$periods;$i++){
            $payablePrincipal=round($emi-$interest,4);
            $loanBalance=round($balance-$emi,4);
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
            if($interest_rate <= 0){
                $interest= 0;
                $calculate=$balance - $emi;
            }else{
                $interestRate=$interest_rate/100;
                $interest= round(($balance-$emi) * $interestRate,4);
                $calculate=($balance - $emi);
            }
            $balance = round($calculate,4);
            $date = date('jS M Y', strtotime($date. ' + 1 month'));
        }
        echo'</tbody>
        	</table>';
    }

    public static function getEMIAmount($amount_applied,$interest_rate,$period){
        if($interest_rate <= 0){
            $principal=$amount_applied;
            $periods=$period;
            $emi=$principal/$periods;
            return round($emi,4);
        }else{
            $principal=$amount_applied;
            $interestRate=$interest_rate;
            $periods=$period;
            $incrementRate= 1 + ($interestRate/100);
            $commonComponent=pow($incrementRate, $periods);
            $numerator=($interestRate/100) * $commonComponent;
            $denominator=$commonComponent - 1;
            $division=$numerator/$denominator;
            $emi=$principal * $division;
            return round($emi,4);
        }
    }
}