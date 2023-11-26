<?php

class ExportFunctions{

    public static function exportCollectionSheetsAsPdf($repayments,$start_date,$end_date){
        $exportPdf = Yii::app()->ePdf2->mpdf('c','A4-L','','',15,15,15,15,15,15);
        $exportPdf->Image('./images/site/tcl_logo.jpg',10,10,-300);
        $exportPdf->SetFont('Times','',12);
        $exportPdf->SetDisplayMode(125);
        $html=ExportFunctions::createPdfContent($exportPdf,$repayments,$start_date,$end_date);
        $filename =strtotime($start_date).strtotime($end_date)."_collections_sheet_report.pdf";
        $stylesheet = file_get_contents('styles/pdfreport.css');
        $exportPdf->WriteHTML($stylesheet,1);
        $exportPdf->WriteHTML($html,2);
        $exportPdf->Output('docs/loans/pdfs/'.$filename,'F');
        $PDF_Export_Link=Yii::app()->params['homeDocs'].'/loans/pdfs/'.$filename;
        $exportLink="<a href='$PDF_Export_Link' class='btn btn-info'> <i class='fa fa-file-pdf-o'></i> &emsp;DOWNLOAD REPORT</a>";
        return $exportLink;
    }

    public static function createPdfContent($exportPdf,$repayments,$start_date,$end_date){
        $report_title=  Yii::app()->name ." | Daily Collections Sheet Report";
        $html = '
	 <html><head></head>
	 <body>
	 <div class="logo">
	     <img src="images/site/tcl_logo.jpg" width="125px"/>
	     <div class="reportTitle">
	     <center>
	         <p class="activityType">'.$report_title.'<br><br><span style="text-align:center !important;font-size:11px !important;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	         '. date('d-M-Y',strtotime($start_date)). ' To '.date('d-M-Y',strtotime($end_date)).'</span></p>'
            .'</center>
	     </div>
	 </div>
	 <hr>
	 <table>
	 <tr class="tableheader">
	 <th>#</th>
	 <th>Member</th><th>Phone Number</th>
	 <th>Loan Number</th><th>Amount Paid</th>
	 <th>Transacted By</th><th>Date Transacted</th>
	 <th>Maturity Date</th>
	 </tr>';
        $exportPdf ->WriteHTML($html,1);
        if(!empty($repayments)){
            $i=1;
            foreach($repayments as $repayment){
                $dateTransacted=$repayment->getFormattedTransactionDate();
                $maturityDate=$repayment->getMaturityDate();
                $loanAccountNumber=$repayment->getLoanAccountNumber();
                $borrowerName=$repayment->getLoanBorrowerName();
                $borrowerPhone=$repayment->getBorrowerPhone();
                $amountPaid=$repayment->getTotalAmountPaid();
                $transactedBy=$repayment->getTransactedBy();
                $html .= '<tr><td>'.$i.'</td><td>'.$borrowerName.'</td><td>' . $borrowerPhone. '</td>
	       <td>'.$loanAccountNumber.'</td><td>' . $amountPaid. '</td>
	       <td>'.$transactedBy.'</td><td>'.$dateTransacted.'</td>
	       <td>'.$maturityDate.'</td>
	     </tr>';
                $i++;
            }
        }
        $html .= '</table></body></html>';
        return $html;
    }

    public static function exportBorrowersReportAsPdf($borrowers){
        $exportPdf = Yii::app()->ePdf2->mpdf('c','A4-L','','',15,15,15,15,15,15);
        $exportPdf->Image('./images/site/tcl_logo.jpg',10,10,-300);
        $exportPdf->SetFont('Times','',12);
        $exportPdf->SetDisplayMode(125);
        $html=ExportFunctions::createBorrowersReportPdf($exportPdf,$borrowers);
        $filename =date('YmdHis')."_members_report.pdf";
        $stylesheet = file_get_contents('styles/pdfreport.css');
        $exportPdf->WriteHTML($stylesheet,1);
        $exportPdf->WriteHTML($html,2);
        $exportPdf->Output('docs/loans/pdfs/'.$filename,'F');
        $PDF_Export_Link=Yii::app()->params['homeDocs'].'/loans/pdfs/'.$filename;
        $exportLink="<a href='$PDF_Export_Link' target='_blank' class='btn btn-info'> <i class='fa fa-file-pdf-o'></i> &emsp;DOWNLOAD REPORT</a>";
        return $exportLink;
    }

    public static function createBorrowersReportPdf($exportPdf,$borrowers){
        $report_title=  Yii::app()->name ." | Members Report";
        $html = '
	 <html><head></head>
	 <body>
	 <div class="logo">
	     <img src="images/site/tcl_logo.jpg" width="125px"/>
	     <div class="reportTitle">
	     <center>
	         <p class="activityType">'.$report_title.'</p>'
            .'</center>
	     </div>
	 </div>
	 <hr>
	 <table>
	 <tr class="tableheader">
	 <th>#</th>
	 <th>Member</th><th>ID Number</th>
	 <th>Phone Number</th><th>Employer</th>
	 <th>Relationship Manager</th><th>Branch</th>
	 <th>Date Created</th>
	 <th>Current Balance</th>
	 </tr>';
        $exportPdf ->WriteHTML($html,1);
        if(!empty($borrowers)){
            $i=1;
            foreach($borrowers as $borrower){
                $html .= '<tr><td>'.$i.'</td><td>'.$borrower->getBorrowerFullName().'</td><td>' .$borrower->id_number. '</td>
	       <td>'.$borrower->getBorrowerPhoneNumber().'</td><td>' .$borrower->employer. '</td>
	       <td>'.$borrower->getRelationManager().'</td><td>'.$borrower->getBranchName().'</td>
	       <td>'.$borrower->getCreatedAtFormatted().'</td>
	       <td><strong> Kshs. '.$borrower->getCurrentLoanBalance().'</strong></td>
	     </tr>';
                $i++;
            }
        }
        $html .= '</table></body></html>';
        return $html;
    }

    public static function exportFilteredDueLoansReportAsPdf($loaded_dues){
        $exportPdf = Yii::app()->ePdf2->mpdf('c','A4-L','','',15,15,15,15,15,15);
        $exportPdf->Image('./images/site/tcl_logo.jpg',10,10,-300);
        $exportPdf->SetFont('Times','',12);
        $exportPdf->SetDisplayMode(125);
        $html=ExportFunctions::createFilteredDueLoansReportPdf($exportPdf,$loaded_dues);
        $filename =date('YmdHis')."_filtered_due_loans_report.pdf";
        $stylesheet = file_get_contents('styles/pdfreport.css');
        $exportPdf->WriteHTML($stylesheet,1);
        $exportPdf->WriteHTML($html,2);
        $exportPdf->Output('docs/loans/pdfs/'.$filename,'F');
        $PDF_Export_Link=Yii::app()->params['homeDocs'].'/loans/pdfs/'.$filename;
        $exportLink="<a href='$PDF_Export_Link' target='_blank' class='btn btn-info'> <i class='fa fa-file-pdf-o'></i> &emsp;DOWNLOAD REPORT</a>";
        return $exportLink;
    }

    public static function createFilteredDueLoansReportPdf($exportPdf,$loaded_dues){
        $report_title=  Yii::app()->name ." | Due Loans Report";
        $html = '
	 <html><head></head>
	 <body>
	 <div class="logo">
	     <img src="images/site/tcl_logo.jpg" width="125px"/>
	     <div class="reportTitle">
	     <center>
	         <p class="activityType">'.$report_title.'</p>'
            .'</center>
	     </div>
	 </div>
	 <hr>
	 <table>
	 <tr class="tableheader">
	 <th>#</th>
	 <th>Loan Number</th><th>Member</th>
	 <th>Branch</th><<th>Relationship Manager</th><th>Amount Due</th>
	 <th>Repayment Date</th>
	 <th>Current Balance</th>
	 </tr>';
        $exportPdf ->WriteHTML($html,1);
        if(!empty($loaded_dues)){
            $i=1;
            foreach($loaded_dues as $loan){
                $loanaccount=Loanaccounts::model()->findByPk($loan['loanaccount_id']);
                $profile=Profiles::model()->findByPk($loanaccount->user_id);
                $repaymentDate=$loan['repayment_date'];
                $html .=
                    '<tr><td>'.$i.'</td><td>'.$loanaccount->account_number.'</td><td>'.$loanaccount->getBorrowerFullName(). '</td>
	       <td>'.$profile->ProfileBranch.'</td><td>' .$loanaccount->getRelationshipManagerName(). '</td>
	       <td><strong> Kshs.'.CommonFunctions::asMoney(LoanApplication::getEMIAmount($loanaccount->loanaccount_id)).'</strong></td>
	       <td>'.date('jS M Y',strtotime($repaymentDate)).'</td>
	       <td><strong> Kshs. '.CommonFunctions::asMoney(LoanTransactionsFunctions::getTotalLoanBalance($loanaccount->loanaccount_id)).'</strong></td>
	     </tr>';
                $i++;
            }
        }
        $html .= '</table></body></html>';
        return $html;
    }

    public static function exportCollectionsReportAsPdf($repayments){
        $exportPdf = Yii::app()->ePdf2->mpdf('c','A4-L','','',15,15,15,15,15,15);
        $exportPdf->Image('./images/site/tcl_logo.jpg',10,10,-300);
        $exportPdf->SetFont('Times','',12);
        $exportPdf->SetDisplayMode(125);
        $html=ExportFunctions::createCollectionsReportPdf($exportPdf,$repayments);
        $filename =date('YmdHis')."_repayments_report.pdf";
        $stylesheet = file_get_contents('styles/pdfreport.css');
        $exportPdf->WriteHTML($stylesheet,1);
        $exportPdf->WriteHTML($html,2);
        $exportPdf->Output('docs/loans/pdfs/'.$filename,'F');
        $PDF_Export_Link=Yii::app()->params['homeDocs'].'/loans/pdfs/'.$filename;
        $exportLink="<a href='$PDF_Export_Link' target='_blank' class='btn btn-info'> <i class='fa fa-file-pdf-o'></i> &emsp;DOWNLOAD REPORT</a>";
        return $exportLink;
    }

    public static function createCollectionsReportPdf($exportPdf,$repayments){
        $report_title=  Yii::app()->name ." | Repayment Collections Report";
        $html = '
	 <html><head></head>
	 <body>
	 <div class="logo">
	     <img src="images/site/tcl_logo.jpg" width="125px"/>
	     <div class="reportTitle">
	     <center>
	         <p class="activityType">'.$report_title.'</p>'
            .'</center>
	     </div>
	 </div>
	 <hr>
	 <table>
	 <tr class="tableheader">
	 <th>#</th>
	 <th>Member</th><th>Account Number</th>
	 <th>Relationship Manager</th><th>Principal Paid</th>
	 <th>Interest Paid</th>
	 <th>Penalty Paid</th><th>Total Paid</th>
	 <th>Transaction Date</th>
	 </tr>';
        $exportPdf ->WriteHTML($html,1);
        if(!empty($repayments)){
            $i=1;
            foreach($repayments as $repayment){
                $html .= '<tr><td>'.$i.'</td><td>'.$repayment->getLoanBorrowerName().'</td><td>' .$repayment->getLoanAccountNumber(). '</td>
	       <td>'.$repayment->getTransactedBy().'</td><td> Kshs. ' .$repayment->getPrincipalPaid(). '</td>
	       <td> Kshs. '.$repayment->getInterestPaid().'</td>
	       <td> Kshs. '.$repayment->getPenaltyPaid().'</td>
	       <td><strong> Kshs. '.$repayment->getTotalAmountPaid().'</strong></td>
	       <td>'.$repayment->getFormattedTransactionDate().'</td>
	     </tr>';
                $i++;
            }
        }
        $html .= '</table></body></html>';
        return $html;
    }

    public static function exportDisbursementReportAsPdf($loanaccounts){
        $exportPdf = Yii::app()->ePdf2->mpdf('c','A4-L','','',15,15,15,15,15,15);
        $exportPdf->Image('./images/site/tcl_logo.jpg',10,10,-300);
        $exportPdf->SetFont('Times','',12);
        $exportPdf->SetDisplayMode(125);
        $html=ExportFunctions::createDisbursementReportPdf($exportPdf,$loanaccounts);
        $filename =date('YmdHis')."_loanaccounts_report.pdf";
        $stylesheet = file_get_contents('styles/pdfreport.css');
        $exportPdf->WriteHTML($stylesheet,1);
        $exportPdf->WriteHTML($html,2);
        $exportPdf->Output('docs/loans/pdfs/'.$filename,'F');
        $PDF_Export_Link=Yii::app()->params['homeDocs'].'/loans/pdfs/'.$filename;
        $exportLink="<a href='$PDF_Export_Link' target='_blank' class='btn btn-info'> <i class='fa fa-file-pdf-o'></i> &emsp;DOWNLOAD REPORT</a>";
        return $exportLink;
    }

    public static function createDisbursementReportPdf($exportPdf,$loanaccounts){
        $report_title=  Yii::app()->name ." | Loan Disbursement Report";
        $html = '
	 <html><head></head>
	 <body>
	 <div class="logo">
	     <img src="images/site/tcl_logo.jpg" width="125px"/>
	     <div class="reportTitle">
	     <center>
	         <p class="activityType">'.$report_title.'</p>'
            .'</center>
	     </div>
	 </div>
	 <hr>
	 <table>
	 <tr class="tableheader">
	 <th>#</th>
	 <th>Member</th><th>Account Number</th>
	 <th>Amount Applied</th><th>Interest Rate</th><th>Repayment Period</th>
	 <th>Relationship Manager</th><th>Amount Disbursed</th>
	 <th>Date Disbursed</th><th>Current Balance</th>
	 </tr>';
        $exportPdf ->WriteHTML($html,1);
        if(!empty($loanaccounts)){
            $i=1;
            foreach($loanaccounts as $loanaccount){
                $html .= '<tr><td>'.$i.'</td><td>'.$loanaccount->getBorrowerFullName().'</td><td>' .$loanaccount->account_number. '</td>
	       <td> Kshs.'.number_format($loanaccount->amount_applied,2).'</td><td>' .$loanaccount->interest_rate.' % p.m.</td>
	       <td>'.$loanaccount->repayment_period.' Months</td>
	       <td>'.$loanaccount->getRelationshipManagerName().'</td>
	       <td><strong> Kshs. '.number_format($loanaccount->amount_approved,2).'</strong></td>
	       <td>'.$loanaccount->getFormattedDisbursedDate().'</td>
	       <td><strong> Kshs. '.$loanaccount->getCurrentLoanBalance().'</strong></td>
	     </tr>';
                $i++;
            }
        }
        $html .= '</table></body></html>';
        return $html;
    }

    public static function exportProfitandLossReportAsPdf($loanaccounts){
        $exportPdf = Yii::app()->ePdf2->mpdf('c','A4','','',15,15,15,15,15,15);
        $exportPdf->Image('./images/site/tcl_logo.jpg',10,10,-300);
        $exportPdf->SetFont('Times','',12);
        $exportPdf->SetDisplayMode(125);
        $html=ExportFunctions::createProfitAndLossReportPdf($exportPdf,$loanaccounts);
        $stylesheet = file_get_contents('styles/pdfreport.css');
        $exportPdf->WriteHTML($stylesheet,1);
        $exportPdf->WriteHTML($html,2);
        return $exportPdf;
    }

    public static function createProfitAndLossReportPdf($exportPdf,$loanaccounts){
        $report_title= Yii::app()->name ." | Profit and Loss Report";
        $html = '
	 <html><head></head>
	 <body>
	 <div class="logo">
	     <img src="images/site/tcl_logo.jpg" width="125px"/>
	     <div class="reportTitle">
	     <center>
	         <p class="activityType">'.$report_title.'</p>'
            .'</center>
	     </div>
	 </div>
	 <hr>
	 <table>
	 <tr class="tableheader">
	 <th>#</th>
	 <th>Name</th><th>Acct. No.</th><th>Branch</th>
	 <th>RM</th><th>Princ. Bal</th><th>Penalty</th>
	 <th>Curr. Interest</th><th>Amnt Due</th>
	 <th>Amnt Paid</th><th>P & L</th><th>Payment Date</th>
	 </tr>';
        $exportPdf->WriteHTML($html,1);
        if(!empty($loanaccounts)){
            $i=1;
            $totalAmountDisbursed=0;
            $totalAmountPaid=0;
            $totalProfits=0;
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
                $html .= '<tr><td>'.$i.'</td><td>'.$loanaccount->getBorrowerFullName().'</td><td>' .$loanaccount->account_number. '</td>
	       <td>'.$loanaccount->getBorrowerBranchName().'</td>
	       <td>'.$loanaccount->getRelationshipManagerName().'</td>
	       <td><strong>'.number_format(LoanManager::getPrincipalBalance($loanaccount->loanaccount_id),2).'</strong></td>
	       <td><strong>'.number_format(LoanManager::getUnpaidAccruedPenalty($loanaccount->loanaccount_id),2).'</strong></td>
	       <td><strong>'.number_format(LoanManager::getUnpaidAccruedInterest($loanaccount->loanaccount_id),2).'</strong></td>
	       <td><strong>'.number_format(LoanManager::getActualLoanBalance($loanaccount->loanaccount_id),2).'</strong></td>
	       <td><strong>'.number_format(LoanTransactionsFunctions::getTotalAmountPaid($loanaccount->loanaccount_id),2).'</strong></td>
	       <td><strong>'.number_format(LoanApplication::getAccountTotalProfitOrLoss($loanaccount->loanaccount_id),2).'</strong></td>
	       <td>'.$finalDate.'</td>
	     </tr>';
                $totalPrincipals+=LoanManager::getPrincipalBalance($loanaccount->loanaccount_id);
                $totalPenalties+=LoanManager::getUnpaidAccruedPenalty($loanaccount->loanaccount_id);
                $totalInterests+=LoanManager::getUnpaidAccruedInterest($loanaccount->loanaccount_id);
                $totalTotals+=LoanManager::getActualLoanBalance($loanaccount->loanaccount_id);
                $totalAmountPaid+=LoanTransactionsFunctions::getTotalAmountPaid($loanaccount->loanaccount_id);
                $totalProfits+=LoanApplication::getAccountTotalProfitOrLoss($loanaccount->loanaccount_id);
                $i++;
            }
        }
        $html.='<tr><td></td><td></td><td></td><td></td><td></td><td><strong>'.number_format($totalPrincipals,2).'</td><td><strong>'.number_format($totalPenalties,2).'</td><td><strong>'.number_format($totalInterests,2).'</td><td><strong>'.number_format($totalTotals,2).'</td><td><strong>'.number_format($totalAmountPaid,2).'</strong></td><td><strong>'.number_format($totalProfits,2).'</strong></td></tr>';
        $html .= '</table></body></html>';
        return $html;
    }


    public static function exportStaffMembersPayrollReportAsPdf($staffs,$month_date){
        $exportPdf = Yii::app()->ePdf2->mpdf('c','A4','','',15,15,15,15,15,15);
        $exportPdf->Image('./images/site/tcl_logo.jpg',10,10,-200);
        $exportPdf->SetFont('Times','',16);
        $exportPdf->SetDisplayMode(125);
        $html=ExportFunctions::createStaffMembersPayrollReportPdf($exportPdf,$staffs,$month_date);
        $filename =date('YmdHis')."_staff_payroll_report_".CommonFunctions::getRespectiveMonth($month_date).".pdf";
        $stylesheet = file_get_contents('styles/pdfreport.css');
        $exportPdf->WriteHTML($stylesheet,1);
        $exportPdf->WriteHTML($html,2);
        $exportPdf->Output('docs/loans/pdfs/'.$filename,'F');
        $PDF_Export_Link=Yii::app()->params['homeDocs'].'/loans/pdfs/'.$filename;
        $exportLink="<a href='$PDF_Export_Link' target='_blank' class='btn btn-info'> <i class='fa fa-file-pdf-o'></i> &emsp;DOWNLOAD REPORT</a>";
        return $exportLink;
    }

    public static function createStaffMembersPayrollReportPdf($exportPdf,$staffs,$month_date){
        $element=Yii::app()->user->user_level;
        $array=array('2','3','4');
        $totalSalary=0;
        $report_title=  Yii::app()->name ." | Payroll Period : ". CommonFunctions::getRespectiveMonth($month_date);
        $html = '
	 <html><head></head>
	 <body>
	 <div class="logo">
	     <img src="images/site/tcl_logo.jpg" width="100px"/>
	     <div class="reportTitle">
	         <p class="activityType">'.$report_title.'</p>'
            .'</div>
	 </div>
	 <hr>
	 <table>
	 <tr class="tableheader">
	 <th>#</th>
	 <th>Name</th><th>Branch</th>
	 <th>Salary</th><th>Amnt Sold</th>
	 <th>Amnt Collected</th>';
        if(Navigation::checkIfAuthorized(127) == 1){
            $html .= '<th>P & L</th>';
        }
        $html .= '<th>Amnt Sold(%)</th><th>Amnt Collected(%)</th>';
        if(Navigation::checkIfAuthorized(127) == 1){
            $html.= '<th>P & L(%)</th>';
        }
        $html .= '<th>Loans</th>
	 <th>Net Pay</th>
	 </tr>';
        $exportPdf ->WriteHTML($html,1);
        if(!empty($staffs)){
            $i=1;
            foreach($staffs as $staff){
                $html .= '<tr><td>'.$i.'</td><td>'.$staff->ProfileFullName.'</td><td>' .$staff->ProfileBranch. '</td>
	       <td>'.number_format($staff->ProfileSalary,2).'</td>
	       <td><strong> Kshs. '.number_format(StaffFunctions::getTotalLoanAmountSold($staff->id,$month_date),2).'</strong></td>
	       <td><strong> Kshs.'.number_format(StaffFunctions::getTotalLoanCollections($staff->id,$month_date),2).'</strong></td>';
                if(Navigation::checkIfAuthorized(127) == 1){
                    $html .= '<td><strong> Kshs.'.number_format(StaffFunctions::getTotalLoanAccountsProfits($staff->id,$month_date),2).'</strong></td>';
                }
                $html .= '<td> <strong> Kshs.'.number_format(StaffFunctions::getMemberBonus($staff->id,$month_date),2).'</strong></td>
	       <td> <strong> Kshs.'.number_format(StaffFunctions::getMemberCommission($staff->id,$month_date),2).'</strong></td>';
                if(Navigation::checkIfAuthorized(127) == 1){
                    $html .= '<td> <strong> Kshs.'.number_format(StaffFunctions::getTotalProfitBonus($staff->id,$month_date),2).'</strong></td>';
                }
                $html .= '<td> <strong> Kshs.'.number_format(StaffFunctions::getCurrentLoanRepayment($staff->id),2).'</strong></td>
	       <td> <strong> Kshs.'.number_format(StaffFunctions::getMemberNetSalaryPay($staff->id,$month_date),2).'</strong></td>
	     </tr>';
                $i++;
                $totalSalary+=StaffFunctions::getMemberNetSalaryPay($staff->id,$month_date);
            }
        }
        $html.= '<tr><td></td><td></td><td></td><td></td><td></td><td></td>';
        if(Navigation::checkIfAuthorized(127) == 1){
            $html.='<td></td>';
        }
        $html.='<td></td><td></td>';
        if(Navigation::checkIfAuthorized(127) == 1){
            $html.='<td></td>';
        }
        $html.= '<td></td><td style="font-size:20px !important;"><strong> Kshs. '.number_format($totalSalary,2).' /= </strong></td></tr>';
        $html .= '</table></body></html>';
        return $html;
    }


    public static function exportClientLoanStatementAsPdf($repayments,$loanaccountID){
        $exportPdf = Yii::app()->ePdf2->mpdf('c','A4','','',15,15,15,15,15,15);
        $exportPdf->Image('./images/site/tcl_logo.jpg',10,10,-300);
        $exportPdf->SetFont('Times','',12);
        $exportPdf->SetDisplayMode(125);
        $html=ExportFunctions::createClientLoanStatementPdf($exportPdf,$repayments,$loanaccountID);
        $filename =date('YmdHis')."_loanstatement_report.pdf";
        $stylesheet = file_get_contents('styles/pdfreport.css');
        $exportPdf->WriteHTML($stylesheet,1);
        $exportPdf->WriteHTML($html,2);
        $exportPdf->Output('docs/loans/pdfs/'.$filename,'F');
        $PDF_Export_Link=Yii::app()->params['homeDocs'].'/loans/pdfs/'.$filename;
        $exportLink="<a href='$PDF_Export_Link' target='_blank' class='btn btn-info'> <i class='fa fa-file-pdf-o'></i> &emsp;DOWNLOAD REPORT</a>";
        return $exportLink;
    }

    public static function createClientLoanStatementPdf($exportPdf,$repayments,$loanaccountID){
        $loanaccount=Loanaccounts::model()->findByPk($loanaccountID);
        $report_title=  Yii::app()->name ." | Client Loan Statement";
        $html = '
	 <html><head></head>
	 <body>
	 <div class="logo">
	     <img src="images/site/tcl_logo.jpg" width="125px"/>
	     <div class="reportTitle">
	     <center>
	         <p class="activityType">'.$report_title.'</p>'
            .'</center>
	     </div>
	 </div>
	 <hr>
	 <div style="font-size:11px !important;">
	 	<p>Member: <strong>'.$loanaccount->getBorrowerFullName().'</strong></p>
	 	<p>Branch: <strong>'.$loanaccount->getBorrowerBranchName().'</strong></p>
	 	<p>Phone Number: <strong>'.$loanaccount->getBorrowerPhoneNumber().'</strong></p>
	 	<p>Relationship Manager: <strong>'.$loanaccount->getRelationshipManagerName().'</strong></p>
	 	<p>Account Number: <strong>'.$loanaccount->account_number.'</strong></p>
	 	<p>Amount Disbursed: <strong> Kshs. '.number_format($loanaccount->amount_approved,2).'</strong></p>
	 	<p>Monthly Installment: <strong> Kshs. '.CommonFunctions::asMoney(LoanCalculator::getEMIAmount($loanaccount->amount_approved,$loanaccount->interest_rate,$loanaccount->repayment_period)).'</strong></p>
	 </div>
	 <hr>
	 <table>
	 <tr class="tableheader">
	 <th>#</th>
	 <th>Date</th><th>Principal</th>
	 <th>Interest</th><th>Arrears</th><th>Penalty</th>
	 <th>Total Amount</th><th>Balance</th>
	 </tr>';
        $exportPdf ->WriteHTML($html,1);
        if(!empty($repayments)){
            $i=1;
            foreach($repayments as $repayment){
                $html .= '<tr><td>'.$i.'</td><td>'.$repayment->getFormattedTransactionDate().'</td><td> Kshs. ' .$repayment->getPrincipalPaid(). '</td><td> Kshs.'.$repayment->getInterestPaid().'</td><td> Kshs. ' .$repayment->getFeePaid().'</td><td> Kshs. '.$repayment->getPenaltyPaid().'</td><td> Kshs. '.$repayment->getTotalAmountPaid().'</td>
	       <td>'.CommonFunctions::asMoney(LoanTransactionsFunctions::getTotalLoanBalanceFrom($repayment->loanaccount_id,$repayment->date)).'</td>
	     </tr>';
                $i++;
            }
            $html.='<tr>
              <td></td><td></td><td></td><td></td><td></td><td></td>
              <td><strong>Actual Balance </strong></td>
              <td><strong>'.CommonFunctions::asMoney(LoanManager::getActualLoanBalance($loanaccount->loanaccount_id)).'</strong></td><td></td>
            </tr>';
        }
        $html .= '</table></body></html>';
        return $html;
    }

    public static function createClientRevampedLoanStatementPdf($exportPdf,$repayments,$loanaccountID){
        $loanaccount=Loanaccounts::model()->findByPk($loanaccountID);
        $report_title=  Yii::app()->name ." | Client Loan Statement";
        $html = '
	 <html><head></head>
	 <body>
	 <div class="logo" style="margin-top:-50px !important;">
	    <img src="images/site/revamped.png"/>
	 </div>
	 <table style="width: 1200px !important" border="1">
	 <tr><td colspan="5"><center>STATEMENT OF ACCOUNT</center></td></tr>
	 <tr>
		 <td colspan="3" style="padding:20px 20px !important;">
		 	<div style="padding: 20px 20px !important;margin: 20px 20px !important;float:left;">
		 		<p>From: '.$loanaccount->created_at.'</p><br>
		 		<p>Name: '.$loanaccount->getBorrowerFullName().'</p><br>
		 		<p>Loan Type: '.$loanaccount->is_frozen.'</p><br>
		 		<p>Rate (p.m.): '.$loanaccount->interest_rate.' %</p><br>
		 		<p>Date Opened: '.$loanaccount->created_at.'</p><br>
		 		<p>Loan Amount: '.number_format($loanaccount->amount_approved,2).'</p><br><br>
		 	</div>
		 	</td>
		 	<td colspan="2" style="padding:20px 20px !important; border-left: none !important; border-left: 0px !important;">
		 	<div style="padding: 20px 20px !important;margin: 20px 20px !important;">
		 		<p>From: '.$loanaccount->created_at.'</p><br>
		 		<p>Name: '.$loanaccount->getBorrowerFullName().'</p><br>
		 		<p>Loan Type: '.$loanaccount->is_frozen.'</p><br>
		 		<p>Rate (p.m.): '.$loanaccount->interest_rate.' %</p><br>
		 		<p>Date Opened: '.$loanaccount->created_at.'</p><br>
		 		<p>Loan Amount: '.number_format($loanaccount->amount_approved,2).'</p><br><br>
		 	</div>
		 </td>
	 </tr>
	 </table>
	 <div style="font-size:11px !important;">
	 	<p>Member: <strong>'.$loanaccount->getBorrowerFullName().'</strong></p>
	 	<p>Branch: <strong>'.$loanaccount->getBorrowerBranchName().'</strong></p>
	 	<p>Phone Number: <strong>'.$loanaccount->getBorrowerPhoneNumber().'</strong></p>
	 	<p>Relationship Manager: <strong>'.$loanaccount->getRelationshipManagerName().'</strong></p>
	 	<p>Account Number: <strong>'.$loanaccount->account_number.'</strong></p>
	 	<p>Amount Disbursed: <strong> Kshs. '.number_format($loanaccount->amount_approved,2).'</strong></p>
	 	<p>Monthly Installment: <strong> Kshs. '.CommonFunctions::asMoney(LoanCalculator::getEMIAmount($loanaccount->amount_approved,$loanaccount->interest_rate,$loanaccount->repayment_period)).'</strong></p>
	 </div>
	 <hr>
	 <table>
	 <tr class="tableheader">
	 <th>#</th>
	 <th>Date</th><th>Principal</th>
	 <th>Interest</th><th>Arrears</th><th>Penalty</th>
	 <th>Total Amount</th><th>Balance</th>
	 </tr>';
        $exportPdf ->WriteHTML($html,1);
        if(!empty($repayments)){
            $i=1;
            foreach($repayments as $repayment){
                $html .= '<tr><td>'.$i.'</td><td>'.$repayment->getFormattedTransactionDate().'</td><td> Kshs. ' .$repayment->getPrincipalPaid(). '</td><td> Kshs.'.$repayment->getInterestPaid().'</td><td> Kshs. ' .$repayment->getFeePaid().'</td><td> Kshs. '.$repayment->getPenaltyPaid().'</td><td> Kshs. '.$repayment->getTotalAmountPaid().'</td>
	       <td>'.CommonFunctions::asMoney(LoanTransactionsFunctions::getLoanPrincipalBalanceFrom($repayment->loanaccount_id,$repayment->date)).'</td>
	     </tr>';
                $i++;
            }
        }
        $html .= '</table></body></html>';
        return $html;
    }

    public static function exportSharesTransactionReceiptAsPdf($transaction,$shareholder){
        $exportPdf = Yii::app()->ePdf2->mpdf('c','A5','','',25,25,25,25,25,25);
        $exportPdf->Image('./images/site/tcl_logo.jpg',10,10,-300);
        $exportPdf->SetFont('Times','',12);
        $exportPdf->SetDisplayMode(75);
        $html=ExportFunctions::createSharesTransactionPdfContent($exportPdf,$transaction,$shareholder);
        $filename =time()."_Transaction_Receipt.pdf";
        $stylesheet = file_get_contents('styles/pdfreport.css');
        $exportPdf->WriteHTML($stylesheet,1);
        $exportPdf->WriteHTML($html,2);
        $exportPdf->Output('docs/loans/pdfs/'.$filename,'F');
        $PDF_Export_Link=Yii::app()->params['homeDocs'].'/loans/pdfs/'.$filename;
        return $PDF_Export_Link;
    }

    public static function createSharesTransactionPdfContent($exportPdf,$transaction,$shareholder){
        $report_title= " Transaction Receipt";
        $html = '
	 <html><head></head>
	 <body>
	 <div class="logo">
	     <img src="images/site/tcl_logo.jpg" width="100px"/>
	     <div class="reportTitle">
	     <center>
	         <p class="activityType">'.$report_title.'</p>'
            .'</center>
	     </div>
	 </div>
	 <hr>
	  <div style="font-size:11px !important;">
	 	<p>Share Holder : <strong>'.$shareholder->AccountShareHolder.'</strong></p>
	 	<p>Branch : <strong>Eldoret</strong></p>
	 	<p>Amount Invested: <strong>'.$shareholder->AccountTotalInvested.'</strong></p>
	 	<p>Unit Share: <strong>'.$shareholder->AccountUnitShare.'</strong></p>
	 	<p>Total Shares: <strong>'.$shareholder->AccountTotalShares.'</strong></p>
	 </div>
	 <hr>
	 <div style="font-size:11px !important;">
	 	<p>Amount : <strong>'.$transaction->TransactionAmount.'</strong></p>
	 	<p>Type : <strong>'.$transaction->TransactionType.'</strong></p>
	 	<p>Date Transacted: <strong>'.$transaction->TransactionDate.'</strong></p>
	 	<br><br>
	 	<i>Thank you for investing with us</i>
	 </div>';
        $html .= '</body></html>';
        return $html;
    }
    /***********************************************
    EXCEL REPORTS
     **********************************************/
    public static function getDailyDownloadableReport($loanaccounts,$endDate){
        $phpExcel = new PHPExcel();
        $title = 'Loan Accounts Daily Downloadable Report';
        $fullName=Profiles::model()->findByPk(Yii::app()->user->user_id)->ProfileFullName;

        $phpExcel->getProperties()->setCreator("Treasure Capital Limited")
            ->setTitle("Loan Account Daily Downloadable Report")
            ->setSubject("Loan Account Daily Downloadable Report")
            ->setDescription("Loan Account Daily Downloadable Report");

        $phpExcel->getDefaultStyle()->getFont()->setName('Century Gothic')->setSize(12);
        $sheetIndex = 0;
        $phpExcel->createSheet(NULL, $sheetIndex);
        $phpExcel->setActiveSheetIndex($sheetIndex)
            ->setCellValue('D1', 'Treasure Capital Limited')
            ->setCellValue('E1', ' ')
            ->setCellValue('F1', ' ')
            ->setCellValue('D2', 'Loan Accounts Daily Report')
            ->setCellValue('E2', ' ')
            ->setCellValue('F2', ' ')
            ->setCellValue('D3', ' ')
            ->setCellValue('E3', ' ')
            ->setCellValue('F3', ' ')
            ->setCellValue('H1', 'Printed By : ' .$fullName)
            ->setCellValue('I1', ' ')
            ->setCellValue('H2', 'Printed On : ' .date('jS M Y'))
            ->setCellValue('I2', ' ');
        /********
        LOGO
         **********************************/
        $logoDrawing = new PHPExcel_Worksheet_Drawing();
        $logoDrawing->setName('Logo');
        $logoDrawing->setDescription('Treasure Capital Logo');
        $logoDrawing->setPath('./images/site/tcl_logo.jpg');
        $logoDrawing->setResizeProportional(false);
        $logoDrawing->setWidth(275);
        $logoDrawing->setHeight(85);
        $logoDrawing->setCoordinates('A1');
        $logoDrawing->setWorksheet($phpExcel->getActiveSheet());

        $phpExcel->getActiveSheet()->setCellValue('H3', 'www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setUrl('https://www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setTooltip('Navigate to Website');
        $phpExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $phpExcel->getActiveSheet()->setTitle('Daily Downloadable Report');
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('H1:I3')->getFont()->setItalic(true);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('D1:F2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        $phpExcel->getActiveSheet()->mergeCells('D1:F1');
        $phpExcel->getActiveSheet()->mergeCells('D2:F2');
        $phpExcel->getActiveSheet()->mergeCells('D3:F3');
        $phpExcel->getActiveSheet()->mergeCells('H1:I1');
        $phpExcel->getActiveSheet()->mergeCells('H2:I2');
        $phpExcel->getActiveSheet()->mergeCells('H3:I3');

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('M')->setWidth(27);
        $phpExcel->getActiveSheet()->getColumnDimension('N')->setWidth(27);
        $phpExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('P')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('R')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('S')->setWidth(18);

        $count = 5;

        $phpExcel->getActiveSheet()
            ->setCellValue("A$count", '#')
            ->setCellValue("B$count", 'Branch')
            ->setCellValue("C$count", 'Relation Manager')
            ->setCellValue("D$count", 'First Name')
            ->setCellValue("E$count", 'Other Names')
            ->setCellValue("F$count", 'Employer')
            ->setCellValue("G$count", 'Account Number')
            ->setCellValue("H$count", 'Original Principal')
            ->setCellValue("I$count", 'Current Principal')
            ->setCellValue("J$count", 'Interest Rate')
            ->setCellValue("K$count", 'Accrued Interest')
            ->setCellValue("L$count", 'Total Penalty')
            ->setCellValue("M$count", 'Total Balance')
            ->setCellValue("N$count", 'Current Month Payment')
            ->setCellValue("O$count", 'Disbursement Date')
            ->setCellValue("P$count", 'Repayment Date')
            ->setCellValue("Q$count", 'Account Status')
            ->setCellValue("R$count", 'D/S Disbursed')
            ->setCellValue("S$count", 'D/S Paid');

        $phpExcel->getActiveSheet()->getStyle("A$count:S$count")->getFont()->setSize(14);
        $phpExcel->getActiveSheet()->getStyle("A$count:S$count")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("A$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("F$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcel->getActiveSheet()->getStyle("G$count:S$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $count++;
        $i=1;
        $totalOriginalPrincipal=0;
        $totalCurrentPrincipal=0;
        $totalAccruedInterest=0;
        $totalPenalty=0;
        $totalBalance=0;
        $totalCurrentMonthPayment=0;
        foreach ($loanaccounts as $loanaccount){
            $phpExcel->getActiveSheet()
                ->setCellValue("A$count", (string)$i)
                ->setCellValue("B$count", $loanaccount->getBorrowerBranchName())
                ->setCellValue("C$count", $loanaccount->getRelationshipManagerName())
                ->setCellValue("D$count", $loanaccount->getBorrowerFirstName())
                ->setCellValue("E$count", $loanaccount->getBorrowerOtherNames())
                ->setCellValue("F$count", $loanaccount->getBorrowerEmployerAlt())
                ->setCellValue("G$count", $loanaccount->account_number)
                ->setCellValue("H$count", (string)$loanaccount->getNotFormattedExactAmountDisbursed())
                ->setCellValue("I$count", (string)LoanManager::getPrincipalBalance($loanaccount->loanaccount_id))
                ->setCellValue("J$count", $loanaccount->interest_rate.' % ')
                ->setCellValue("K$count", (string)LoanManager::getUnpaidLoanInterestBalance($loanaccount->loanaccount_id))
                ->setCellValue("L$count", (string)LoanManager::getUnpaidAccruedPenalty($loanaccount->loanaccount_id))
                ->setCellValue("M$count", (string)LoanManager::getActualLoanBalance($loanaccount->loanaccount_id))
                ->setCellValue("N$count", (string)LoanManager::getCurrentMonthLoanPayment($loanaccount->loanaccount_id))
                ->setCellValue("O$count", $loanaccount->getFormattedDisbursedDate())
                ->setCellValue("P$count", date('jS',strtotime($loanaccount->repayment_start_date)))
                ->setCellValue("Q$count", $loanaccount->getEmptyCurrentLoanAccountStatus())
                ->setCellValue("R$count", $loanaccount->DaysPastDisbursementDate)
                ->setCellValue("S$count", (string)$loanaccount->DaysPastLastAccountPayment);

            $phpExcel->getActiveSheet()->getStyle("H$count:N$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $phpExcel->getActiveSheet()->getStyle("J$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00);
            $phpExcel->getActiveSheet()->getStyle("G$count:S$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $count++;
            $i++;
            $totalOriginalPrincipal+=$loanaccount->getNotFormattedExactAmountDisbursed();
            $totalCurrentPrincipal+=LoanManager::getPrincipalBalance($loanaccount->loanaccount_id);
            $totalAccruedInterest+=LoanManager::getUnpaidLoanInterestBalance($loanaccount->loanaccount_id);
            $totalPenalty+=LoanManager::getUnpaidAccruedPenalty($loanaccount->loanaccount_id);
            $totalBalance+=LoanManager::getActualLoanBalance($loanaccount->loanaccount_id);
            $totalCurrentMonthPayment+=LoanManager::getCurrentMonthLoanPayment($loanaccount->loanaccount_id);
        }
        $nextCounter=$count++;
        $phpExcel->getActiveSheet()->getStyle("A$nextCounter:S$nextCounter")->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle("A$nextCounter:S$nextCounter")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("A$nextCounter:S$nextCounter")->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("A$nextCounter:S$nextCounter")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
        $phpExcel->getActiveSheet()->getStyle("A$nextCounter:S$nextCounter")->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => '00FF00'
            )
        ));
        $bottomTitle="TOTALS";
        $phpExcel->getActiveSheet()->setCellValue("A$nextCounter", $bottomTitle);$phpExcel->getActiveSheet()->getStyle("A$nextCounter")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->mergeCells("A$nextCounter:G$nextCounter");
        $phpExcel->getActiveSheet()->getStyle("H$nextCounter:N$nextCounter")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
        $phpExcel->getActiveSheet()
            ->setCellValue("G$nextCounter", $bottomTitle)
            ->setCellValue("H$nextCounter", (string)$totalOriginalPrincipal)
            ->setCellValue("I$nextCounter", (string)$totalCurrentPrincipal)
            ->setCellValue("K$nextCounter", (string)$totalAccruedInterest)
            ->setCellValue("L$nextCounter", (string)$totalPenalty)
            ->setCellValue("M$nextCounter", (string)$totalBalance)
            ->setCellValue("N$nextCounter", (string)$totalCurrentMonthPayment);
        $phpExcel->setActiveSheetIndex(0);
        $filename = 'Daily_Accounts_Report_'.date('YmdHis').'_'.strtoupper(CommonFunctions::generateToken(10)).'.xls';
        ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $excelWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $excelWriter->save('php://output');
        ob_end_clean();
    }

    public static function getCRBListingReport($loans){
        $phpExcel = new PHPExcel();
        $title = 'CRB Listing Report';
        $fullName=Profiles::model()->findByPk(Yii::app()->user->user_id)->ProfileFullName;

        $phpExcel->getProperties()->setCreator("Treasure Capital Limited")
            ->setTitle("CRB Listing Report")
            ->setSubject("CRB Listing Report")
            ->setDescription("CRB Listing Report");

        $phpExcel->getDefaultStyle()->getFont()->setName('Century Gothic')->setSize(12);
        $sheetIndex = 0;
        $phpExcel->createSheet(NULL, $sheetIndex);
        $phpExcel->setActiveSheetIndex($sheetIndex)
            ->setCellValue('D1', 'Treasure Capital Limited')
            ->setCellValue('E1', ' ')
            ->setCellValue('F1', ' ')
            ->setCellValue('D2', 'CRB Listing Report')
            ->setCellValue('E2', ' ')
            ->setCellValue('F2', ' ')
            ->setCellValue('D3', ' ')
            ->setCellValue('E3', ' ')
            ->setCellValue('F3', ' ')
            ->setCellValue('H1', 'Printed By : ' .$fullName)
            ->setCellValue('I1', ' ')
            ->setCellValue('H2', 'Printed On : ' .date('jS M Y'))
            ->setCellValue('I2', ' ');
        /********
        LOGO
         **********************************/
        $logoDrawing = new PHPExcel_Worksheet_Drawing();
        $logoDrawing->setName('Logo');
        $logoDrawing->setDescription('Treasure Capital Logo');
        $logoDrawing->setPath('./images/site/tcl_logo.jpg');
        $logoDrawing->setResizeProportional(false);
        $logoDrawing->setWidth(275);
        $logoDrawing->setHeight(85);
        $logoDrawing->setCoordinates('A1');
        $logoDrawing->setWorksheet($phpExcel->getActiveSheet());

        $phpExcel->getActiveSheet()->setCellValue('H3', 'www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setUrl('https://www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setTooltip('Navigate to Website');
        $phpExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $phpExcel->getActiveSheet()->setTitle('CRB Listing Report');
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('H1:I3')->getFont()->setItalic(true);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('D1:F2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        $phpExcel->getActiveSheet()->mergeCells('D1:F1');
        $phpExcel->getActiveSheet()->mergeCells('D2:F2');
        $phpExcel->getActiveSheet()->mergeCells('H1:I1');
        $phpExcel->getActiveSheet()->mergeCells('H2:I2');
        $phpExcel->getActiveSheet()->mergeCells('H3:I3');

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('M')->setWidth(27);
        $phpExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('T')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('U')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('V')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('W')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('X')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('Y')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('Z')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AA')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AB')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AC')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AD')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AE')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AF')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AG')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AH')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AI')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AJ')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AK')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AL')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AM')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AN')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AO')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AP')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AQ')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AR')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AS')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AT')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AU')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AV')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AW')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AX')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AY')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('AZ')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BA')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BB')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BC')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BD')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BE')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BF')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BG')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BH')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BI')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BJ')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BK')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BL')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BM')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BN')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BO')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BP')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BQ')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BR')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('BS')->setWidth(20);
        $count = 5;

        $phpExcel->getActiveSheet()
            ->setCellValue("A$count", '#')
            ->setCellValue("B$count", 'Surname')
            ->setCellValue("C$count", 'Forename 1')
            ->setCellValue("D$count", 'Forename 2')
            ->setCellValue("E$count", 'Forename 3')
            ->setCellValue("F$count", 'Trading As')
            ->setCellValue("G$count", 'Date of Birth')
            ->setCellValue("H$count", 'Client Number')
            ->setCellValue("I$count", 'Account Number')
            ->setCellValue("J$count", 'Old Account Number')
            ->setCellValue("K$count", 'Gender')
            ->setCellValue("L$count", 'Nationality')
            ->setCellValue("M$count", 'Marital Status')
            ->setCellValue("N$count", 'Primary Identification Document Type')
            ->setCellValue("O$count", 'Primary Identification')
            ->setCellValue("P$count", 'Secondary Identification Document Type')
            ->setCellValue("Q$count", 'Secondary Identification')
            ->setCellValue("R$count", 'Other Identification')
            ->setCellValue("S$count", 'Other Identification')
            ->setCellValue("T$count", 'Passport Country Code')
            ->setCellValue("U$count", 'Mobile Telephone')
            ->setCellValue("V$count", 'Home Telephone')
            ->setCellValue("W$count", 'Work Telephone')
            ->setCellValue("X$count", 'Postal Address 1')
            ->setCellValue("Y$count", 'Postal Address 2')
            ->setCellValue("Z$count", 'Postal Location Town')
            ->setCellValue("AA$count", 'Postal Location Country')
            ->setCellValue("AB$count", 'Post Code')
            ->setCellValue("AC$count", 'Physical Address 1')
            ->setCellValue("AD$count", 'Physical Address 2')
            ->setCellValue("AE$count", 'Plot Number')
            ->setCellValue("AF$count", 'Location Town')
            ->setCellValue("AG$count", 'Location Country')
            ->setCellValue("AH$count", 'Type of Residence')
            ->setCellValue("AI$count", 'PIN Number')
            ->setCellValue("AJ$count", 'Consumer Work Email')
            ->setCellValue("AK$count", 'Employer Name')
            ->setCellValue("AL$count", 'Occupational Industry Type')
            ->setCellValue("AM$count", 'Employment Date')
            ->setCellValue("AN$count", 'Employment Type')
            ->setCellValue("AO$count", 'Income Amount')
            ->setCellValue("AP$count", 'Lenders Registered')
            ->setCellValue("AQ$count", 'Lenders Trading Name')
            ->setCellValue("AR$count", 'Lenders Branch Name')
            ->setCellValue("AS$count", 'Lenders Branch Code')
            ->setCellValue("AT$count", 'Account Joint/Single')
            ->setCellValue("AU$count", 'Account Product Type')
            ->setCellValue("AV$count", 'Date Account Opened')
            ->setCellValue("AW$count", 'Installment Due Date')
            ->setCellValue("AX$count", 'Original Amount')
            ->setCellValue("AY$count", 'Currency of Facility')
            ->setCellValue("AZ$count", 'Amount in KE shillings')
            ->setCellValue("BA$count", 'Current Balance')
            ->setCellValue("BB$count", 'Overdue Balance')
            ->setCellValue("BC$count", 'Overdue Date')
            ->setCellValue("BD$count", 'Nr. of Days in Arrears')
            ->setCellValue("BE$count", 'Nr. of Installments In')
            ->setCellValue("BF$count", 'Prudential Risk Classification')
            ->setCellValue("BG$count", 'Account Status')
            ->setCellValue("BH$count", 'Account Status Date')
            ->setCellValue("BI$count", 'Account Closure Reason')
            ->setCellValue("BJ$count", 'Repayment Period')
            ->setCellValue("BK$count", 'Deferred Payment Date')
            ->setCellValue("BL$count", 'Deferred Payment')
            ->setCellValue("BM$count", 'Payment Frequency')
            ->setCellValue("BN$count", 'Disbursement Date')
            ->setCellValue("BO$count", 'Installment Amount')
            ->setCellValue("BP$count", 'Date of Last Payment')
            ->setCellValue("BQ$count", 'Last Payment Amount')
            ->setCellValue("BR$count", 'Type of Security')
            ->setCellValue("BS$count", 'Group ID');

        $phpExcel->getActiveSheet()->getStyle("A$count:BS$count")->getFont()->setSize(14);
        $phpExcel->getActiveSheet()->getStyle("A$count:BS$count")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("B$count:BS$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcel->getActiveSheet()->getStyle("A$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $count++;
        $i=1;
        foreach($loans as $record){
            $loanaccount = Loanaccounts::model()->findByPk($record['loanaccount_id']);
            $profile     = Profiles::model()->findByPk($loanaccount->user_id);
            $phoneNumber = ProfileEngine::getProfileContactByTypeOrderDesc($profile->id,'PHONE');
            if(!empty($profile)){
                $surname=strtoupper($profile->lastName);
                $forename1=strtoupper($profile->firstName);
                $salutation='';
                $dateOfBirth=date("Ymd",strtotime($profile->birthDate));
                $primaryIdentification=$profile->idNumber;
                $mobileTelephone=$phoneNumber;
                $homeTelephone=$phoneNumber;
                $workTelephone=$phoneNumber;
                $gender=ucfirst(substr($profile->gender, 0, 1));
                $pinNumber=$profile->kraPIN;
                $lendersBranchCode="N1351001";
            }else{
                $surname="";
                $forename1="";
                $salutation='';
                $dateOfBirth='';
                $primaryIdentification='';
                $mobileTelephone='';
                $homeTelephone='';
                $workTelephone='';
                $gender='';
                $pinNumber='';
                $lendersBranchCode='N1351001';
            }
            $forename2='';
            $forename3='';
            $clientNumber=$loanaccount->account_number;
            $accountNumber=LoanManager::determineAccountNumber($loanaccount->loanaccount_id);
            $oldAccountNumber='';
            $nationality='KE';
            $passportCountryCode='';
            $maritalStatus='';
            $primaryIdentificationDoc=$loanaccount->PrimaryIdentificationDoc;
            $secondaryIdentificationDoc=' ';
            $secondary=' ';
            $otherIdentification=' ';
            $postalAddress1='';
            $postalAddress2='';
            $postalLocationTown=$loanaccount->ClientBranch;
            $postalLocationCountry='KE';
            $postCode='';
            $physicalAddress1=$loanaccount->BorrowerBranchTown;
            $physicalAddress2='';
            $plotNumber=' ';
            $locationTown=$loanaccount->ClientBranch;
            $locationCountry='KE';
            $dateAtPhysicalAddress='';
            $consumerEmail='';
            $employerName=$loanaccount->BorrowerEmployerAlt;
            $employerIndustry=$loanaccount->MemberIndustryType;
            $employmentDate=$loanaccount->MemberEmploymentDate;
            $employmentType='';
            $salaryBand=($loanaccount->MemberEmploymentIncomeAmount) * 100;
            $lendersRegistered='Treasure Capital Limited';
            $lendersTradingName='Treasure Capital Limited';
            $lendersBranchName='HEAD OFFICE';
            $accountJointSingle='S';
            $accountProductType=$loanaccount->LoanAccountProduct;
            $dateAccountOpened=date("Ymd",strtotime($loanaccount->created_at));
            if($loanaccount->loan_status == '4'){
                $installmentDueDate=date("Ymd");
            }else{
                $installmentDueDate=date("Ymd",strtotime($loanaccount->LoanAccountInstallmentDueDate));
            }
            $originalAmount=($loanaccount->amount_applied) * 100;
            $currencyOfFacility='KES';
            $amountInKshs=LoanManager::getActualLoanBalance($loanaccount->loanaccount_id) * 100;
            $currentBalance=LoanManager::getActualLoanBalance($loanaccount->loanaccount_id) * 100;
            $arrearsDays=$loanaccount->DaysInArrears;
            if($arrearsDays > 0){
                $OverdueBalance=LoanManager::getActualLoanBalance($loanaccount->loanaccount_id) * 100;
                if($OverdueBalance > 0){
                    if($loanaccount->loan_status == '4'){
                        $overdueDate="";
                    }else{
                        $overdueDate=date("Ymd",strtotime($loanaccount->LoanAccountInstallmentDueDate));
                    }
                }else{
                    $overdueDate="";
                }

                $daysInArrears=$loanaccount->DaysInArrears;
                $installmentsIn=round(($loanaccount->DaysInArrears+30)/30);
            }else{
                $overdueDate="";
                $OverdueBalance=0;
                $daysInArrears=0;
                $installmentsIn=0;
            }

            $performanceIndicator=strtoupper($loanaccount->PrudentialRisk);
            $accountStatus=strtoupper($loanaccount->account_status);
            $accountStatusDate=date("Ymd");
            $accountClosureReason='';
            $period=$loanaccount->repayment_period;
            if($period <= 0){
                $repaymentPeriod=1;
            }else{
                $repaymentPeriod=$period;
            }
            $deferredPaymentDate='';
            $deferredPayment='';
            $paymentFrequency='M';
            $disbursementDate=date("Ymd",strtotime($loanaccount->FormattedDisbursedDate));
            $installmentAmount=(round((LoanManager::getActualLoanBalance($loanaccount->loanaccount_id)/$repaymentPeriod),2))*100;
            $dateofLastPayment=$loanaccount->LoanAccountLastPaymentDate;
            $lastPaymentAmount=($loanaccount->LoanAccountLastPaymentAmount)*100;
            $typeOfSecurity=$loanaccount->LoanSecurityStatus;
            $groupId='';
            $phpExcel->getActiveSheet()
                ->setCellValue("A$count", (string)$i)
                ->setCellValue("B$count", $surname)
                ->setCellValue("C$count", $forename1)
                ->setCellValue("D$count", $forename2)
                ->setCellValue("E$count", $forename3)
                ->setCellValue("F$count", $salutation)
                ->setCellValue("G$count", $dateOfBirth)
                ->setCellValue("H$count", $clientNumber)
                ->setCellValue("I$count", $accountNumber)
                ->setCellValue("J$count", $oldAccountNumber)
                ->setCellValue("K$count", $gender)
                ->setCellValue("L$count", $nationality)
                ->setCellValue("M$count", $maritalStatus)
                ->setCellValue("N$count", $primaryIdentificationDoc)
                ->setCellValue("O$count", $primaryIdentification)
                ->setCellValue("P$count", $secondaryIdentificationDoc)
                ->setCellValue("Q$count", $secondary)
                ->setCellValue("R$count", $otherIdentification)
                ->setCellValue("S$count", $otherIdentification)
                ->setCellValue("T$count", $passportCountryCode)
                ->setCellValueExplicit("U$count", $mobileTelephone, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit("V$count", $homeTelephone, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValueExplicit("W$count", $workTelephone, PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue("X$count", $postalAddress1)
                ->setCellValue("Y$count", $postalAddress2)
                ->setCellValue("Z$count", $postalLocationTown)
                ->setCellValue("AA$count", $postalLocationCountry)
                ->setCellValue("AB$count", $postCode)
                ->setCellValue("AC$count", $physicalAddress1)
                ->setCellValue("AD$count", $physicalAddress2)
                ->setCellValue("AE$count", $plotNumber)
                ->setCellValue("AF$count", $locationTown)
                ->setCellValue("AG$count", $locationCountry)
                ->setCellValue("AH$count", $dateAtPhysicalAddress)
                ->setCellValue("AI$count", $pinNumber)
                ->setCellValue("AJ$count", $consumerEmail)
                ->setCellValue("AK$count", $employerName)
                ->setCellValue("AL$count", $employerIndustry)
                ->setCellValue("AM$count", $employmentDate)
                ->setCellValue("AN$count", $employmentType)
                ->setCellValue("AO$count", (string)$salaryBand)
                ->setCellValue("AP$count", $lendersRegistered)
                ->setCellValue("AQ$count", $lendersTradingName)
                ->setCellValue("AR$count", $lendersBranchName)
                ->setCellValue("AS$count", $lendersBranchCode)
                ->setCellValue("AT$count", $accountJointSingle)
                ->setCellValue("AU$count", $accountProductType)
                ->setCellValue("AV$count", $dateAccountOpened)
                ->setCellValue("AW$count", $installmentDueDate)
                ->setCellValue("AX$count", (string)$originalAmount)
                ->setCellValue("AY$count", $currencyOfFacility)
                ->setCellValue("AZ$count", (string)$amountInKshs)
                ->setCellValue("BA$count", (string)$currentBalance)
                ->setCellValue("BB$count", (string)$OverdueBalance)
                ->setCellValue("BC$count", $overdueDate)
                ->setCellValue("BD$count", (string)$daysInArrears)
                ->setCellValue("BE$count", (string)$installmentsIn)
                ->setCellValue("BF$count", $performanceIndicator)
                ->setCellValue("BG$count", $accountStatus)
                ->setCellValue("BH$count", $accountStatusDate)
                ->setCellValue("BI$count", $accountClosureReason)
                ->setCellValue("BJ$count", $repaymentPeriod)
                ->setCellValue("BK$count", $deferredPaymentDate)
                ->setCellValue("BL$count", $deferredPayment)
                ->setCellValue("BM$count", $paymentFrequency)
                ->setCellValue("BN$count", $disbursementDate)
                ->setCellValue("BO$count", (string)$installmentAmount)
                ->setCellValue("BP$count", $dateofLastPayment)
                ->setCellValue("BQ$count", (string)$lastPaymentAmount)
                ->setCellValue("BR$count", $typeOfSecurity)
                ->setCellValue("BS$count", $groupId);
            $phpExcel->getActiveSheet()->getStyle("B$count:BS$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $count++;
            $i++;
        }
        $phpExcel->setActiveSheetIndex(0);
        $filename='CRB_Listing_Report_'.date('YmdHis').'_'.strtoupper(CommonFunctions::generateToken(8)).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        return PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    }

    public static function exportClientLoanStatementAsExcel($loanaccount,$start_date,$end_date,$transactions){
        $phpExcel = new PHPExcel();
        $phpExcel->getDefaultStyle()->getFont()->setName('Comic Sans MS')->setSize(10);
        $sheetIndex = 0;
        $phpExcel->createSheet(NULL, $sheetIndex);
        $phpExcel->setActiveSheetIndex($sheetIndex);
        /*Microfinance Logo*/
        $logoWidth=95;
        $logoHeight=50;
        $logoDrawing = new PHPExcel_Worksheet_Drawing();
        $logoDrawing->setName('Logo');
        $logoDrawing->setDescription('Treasure Capital Logo');
        $logoDrawing->setPath('./images/site/tcl_logo.jpg');
        $logoDrawing->setResizeProportional(false);
        $logoDrawing->setWidth($logoWidth);
        $logoDrawing->setHeight($logoHeight);
        $logoDrawing->setCoordinates('C1');
        $logoDrawing->setWorksheet($phpExcel->getActiveSheet());
        /*Statement Logo*/
        $statementLogoWidth=225;
        $statementLogoHeight=52;
        $statementLogo = new PHPExcel_Worksheet_Drawing();
        $statementLogo->setName('Second Logo');
        $statementLogo->setDescription('Treasure Capital Second Logo');
        $statementLogo->setPath('./images/site/statement_bottom.png');
        $statementLogo->setResizeProportional(false);
        $statementLogo->setWidth($statementLogoWidth);
        $statementLogo->setHeight($statementLogoHeight);
        $statementLogo->setCoordinates('C4');
        $statementLogo->setWorksheet($phpExcel->getActiveSheet());
        /*****
        BORDERS STYLES
         ******************/
        $allBorderStyles= array('borders' => array('allborders' => array('style' =>PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'))));
        $leftRightBorders= array('borders' => array('right' => array('style' =>PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000')),'left' => array('style' =>PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'))));
        $rightBorders= array('borders' => array('right' => array('style' =>PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'))));
        $leftBorders= array('borders' => array('left' => array('style' =>PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'))));
        $bottomTopBorders= array('borders' => array('bottom' => array('style' =>PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000')),'top' => array('style' =>PHPExcel_Style_Border::BORDER_THIN,'color' => array('argb' => '000000'))));
        /*****
        ALIGNMENT STYLES
         ******************/
        $centeringStyle=array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER));
        $leftStyle = array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT));
        $rightStyle=array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT));
        /*Content Start Here*/
        $phpExcel->getActiveSheet()->setTitle('Loan Statement');
        foreach(range('C','G') as $columnID) {
            $phpExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $phpExcel->getActiveSheet()->getStyle('C7:G13')->getFont()->setSize(10);
        $phpExcel->getActiveSheet()->getStyle('C7:G13')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("C7:G13")->applyFromArray($leftStyle);
        for($i=7;$i<=17;$i++){
            $phpExcel->getActiveSheet()->getStyle("C$i")->applyFromArray($leftBorders);
            $phpExcel->getActiveSheet()->getStyle("G$i")->applyFromArray($rightBorders);
        }
        $phpExcel->getActiveSheet()->getStyle("C7:G7")->applyFromArray($bottomTopBorders);
        $phpExcel->getActiveSheet()->mergeCells('D7:F7');
        $phpExcel->getActiveSheet()->setCellValue("D7", 'STATEMENT OF ACCOUNT');
        $phpExcel->getActiveSheet()->getStyle('D7')->getFont()->setSize(10);
        $phpExcel->getActiveSheet()->getStyle("D7")->applyFromArray($centeringStyle);
        $phpExcel->getActiveSheet()->setCellValue("C8", 'From: ');
        $phpExcel->getActiveSheet()->getStyle("C8")->applyFromArray($rightStyle);
        $phpExcel->getActiveSheet()->setCellValue("D8", date('d.m.Y',strtotime($start_date)));
        $phpExcel->getActiveSheet()->setCellValue("C9", 'Name: ');
        $phpExcel->getActiveSheet()->getStyle("C9")->applyFromArray($rightStyle);
        $phpExcel->getActiveSheet()->setCellValue("D9", $loanaccount->FullMemberName);
        $phpExcel->getActiveSheet()->setCellValue("C10", 'Loan Type: ');
        $phpExcel->getActiveSheet()->getStyle("C10")->applyFromArray($rightStyle);
        $phpExcel->getActiveSheet()->setCellValue("D10", $loanaccount->FullLoanSecurityStatus);
        $phpExcel->getActiveSheet()->setCellValue("C11", 'Rate p.m.: ');
        $phpExcel->getActiveSheet()->getStyle("C11")->applyFromArray($rightStyle);
        $phpExcel->getActiveSheet()->setCellValue("D11", $loanaccount->InterestRate);
        $phpExcel->getActiveSheet()->setCellValue("C12", 'Date Opened: ');
        $phpExcel->getActiveSheet()->getStyle("C12")->applyFromArray($rightStyle);
        $phpExcel->getActiveSheet()->setCellValue("D12", date('d.m.Y',strtotime($loanaccount->created_at)));
        $phpExcel->getActiveSheet()->setCellValue("C13", 'Loan Amount: ');
        $phpExcel->getActiveSheet()->getStyle("C13")->applyFromArray($rightStyle);
        $phpExcel->getActiveSheet()->setCellValue("D13", $loanaccount->ExactAmountDisbursed);
        $phpExcel->getActiveSheet()->setCellValue("E8", 'To: ');
        $phpExcel->getActiveSheet()->getStyle("E8")->applyFromArray($rightStyle);
        $phpExcel->getActiveSheet()->setCellValue("F8", date('d.m.Y',strtotime($end_date)));
        $phpExcel->getActiveSheet()->setCellValue("F10", 'Acc #: ');
        $phpExcel->getActiveSheet()->getStyle("F10")->applyFromArray($rightStyle);
        $phpExcel->getActiveSheet()->setCellValue("G10", $loanaccount->account_number);
        $phpExcel->getActiveSheet()->setCellValue("F11", 'M-PESA: ');
        $phpExcel->getActiveSheet()->getStyle("F11")->applyFromArray($rightStyle);
        $phpExcel->getActiveSheet()->setCellValue("G11", $loanaccount->BorrowerPhoneNumber);
        $phpExcel->getActiveSheet()->setCellValue("F12", 'Employer: ');
        $phpExcel->getActiveSheet()->getStyle("F12")->applyFromArray($rightStyle);
        $phpExcel->getActiveSheet()->setCellValue("G12", strtoupper($loanaccount->BorrowerEmployerAlt));
        $phpExcel->getActiveSheet()->setCellValue("F13", 'STATUS: ');
        $phpExcel->getActiveSheet()->getStyle("F13")->applyFromArray($rightStyle);
        $phpExcel->getActiveSheet()->setCellValue("G13", strtoupper($loanaccount->EmptyCurrentLoanAccountStatus));
        /*Start Statement*/
        $phpExcel->getActiveSheet()->getStyle("C15:G15")->applyFromArray($centeringStyle);
        $phpExcel->getActiveSheet()->getStyle('C15:G15')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("C15:G15")->applyFromArray($allBorderStyles);
        $phpExcel->getActiveSheet()->setCellValue("C15", 'Txn Date');
        $phpExcel->getActiveSheet()->setCellValue("D15", 'Description');
        $phpExcel->getActiveSheet()->setCellValue("E15", 'Money Out');
        $phpExcel->getActiveSheet()->setCellValue("F15", 'Money In');
        $phpExcel->getActiveSheet()->setCellValue("G15", 'Balance');
        /*First Content*/
        $count = 16;
        $balance=0;
        $totalMoneyIn=0;
        $totalMoneyOut=0;
        foreach($transactions AS $transaction){
            switch($transaction['description']){

                case 'Payment':
                    $statementDifference=-$transaction['moneyIn'];
                    break;

                case 'Waiver':
                    $statementDifference=-$transaction['moneyIn'];
                    break;

                case 'Interest Charged':
                    $statementDifference=$transaction['moneyOut'];
                    break;

                case 'Penalty':
                    $statementDifference=$transaction['moneyOut'];
                    break;

                default:
                    $statementDifference=$transaction['moneyOut'];
                    break;
            }
            $balance+=$statementDifference;
            $phpExcel->getActiveSheet()->getStyle("C$count:G$count")->applyFromArray($leftRightBorders);
            $phpExcel->getActiveSheet()->getStyle("C$count")->applyFromArray($leftRightBorders);
            $phpExcel->getActiveSheet()->getStyle("D$count")->applyFromArray($leftRightBorders);
            $phpExcel->getActiveSheet()->getStyle("E$count")->applyFromArray($leftRightBorders);
            $phpExcel->getActiveSheet()->getStyle("F$count")->applyFromArray($leftRightBorders);
            $phpExcel->getActiveSheet()->getStyle("C$count:G$count")->applyFromArray($centeringStyle);
            $phpExcel->getActiveSheet()->getStyle("E$count:G$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $phpExcel->getActiveSheet()
                ->setCellValue("C$count", date('d.m.Y H.i.s',strtotime($transaction['transactionDate'])))
                ->setCellValue("D$count", $transaction['description'])
                ->setCellValue("E$count", $transaction['moneyOut'])
                ->setCellValue("F$count", $transaction['moneyIn'])
                ->setCellValue("G$count", $balance);
            if($transaction['description'] == 'Principal Disbursed' || $transaction['description'] == 'Top_up Disbursed'){
                $statementDifference=0;
            }else{
                $statementDifference=$transaction['moneyOut']-$transaction['moneyIn'];
            }
            $totalMoneyIn+=$transaction['moneyIn'];
            $totalMoneyOut+=$transaction['moneyOut'];
            $count++;
        }
        $counter=$count;
        $phpExcel->getActiveSheet()->getStyle("C$counter:G$counter")->applyFromArray($allBorderStyles);
        $phpExcel->getActiveSheet()->getStyle("C$counter:G$counter")->applyFromArray($centeringStyle);
        $phpExcel->getActiveSheet()->getStyle("C$counter:G$counter")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("E$counter:G$counter")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
        $phpExcel->getActiveSheet()
            ->setCellValue("E$counter", $totalMoneyOut)
            ->setCellValue("F$counter", $totalMoneyIn);
        $phpExcel->setActiveSheetIndex(0);
        $filename = 'Loan_Statement_'.date('YmdHis').'_'.strtoupper(CommonFunctions::generateToken(8)).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        return PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    }

    public static function getExcelDisbursedAccounts($loanaccounts){
        $phpExcel = new PHPExcel();
        $title = 'Disbursed Loan Accounts';
        $fullName=Profiles::model()->findByPk(Yii::app()->user->user_id)->ProfileFullName;

        $phpExcel->getProperties()->setCreator("Treasure Capital Limited")
            ->setTitle("Disbursed Loan Accounts Report")
            ->setSubject("Disbursed Loan Accounts Report")
            ->setDescription("Disbursed Loan Accounts Report");

        $phpExcel->getDefaultStyle()->getFont()->setName('Century Gothic')->setSize(12);
        $sheetIndex = 0;
        $phpExcel->createSheet(NULL, $sheetIndex);
        $phpExcel->setActiveSheetIndex($sheetIndex)
            ->setCellValue('D1', 'Treasure Capital Limited')
            ->setCellValue('E1', ' ')
            ->setCellValue('F1', ' ')
            ->setCellValue('D2', 'Disbursed Loan Accounts')
            ->setCellValue('E2', ' ')
            ->setCellValue('F2', ' ')
            ->setCellValue('D3', ' ')
            ->setCellValue('E3', ' ')
            ->setCellValue('F3', ' ')
            ->setCellValue('H1', 'Printed By : ' .$fullName)
            ->setCellValue('I1', ' ')
            ->setCellValue('H2', 'Printed On : ' .date('jS M Y'))
            ->setCellValue('I2', ' ');
        /********
        LOGO
         **********************************/
        $logoDrawing = new PHPExcel_Worksheet_Drawing();
        $logoDrawing->setName('Logo');
        $logoDrawing->setDescription('Treasure Capital Logo');
        $logoDrawing->setPath('./images/site/tcl_logo.jpg');
        $logoDrawing->setResizeProportional(false);
        $logoDrawing->setWidth(275);
        $logoDrawing->setHeight(85);
        $logoDrawing->setCoordinates('A1');
        $logoDrawing->setWorksheet($phpExcel->getActiveSheet());

        $phpExcel->getActiveSheet()->setCellValue('H3', 'www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setUrl('https://www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setTooltip('Navigate to Website');
        $phpExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $phpExcel->getActiveSheet()->setTitle('Disbursed Accounts Report');
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('H1:I3')->getFont()->setItalic(true);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        $phpExcel->getActiveSheet()->mergeCells('D1:F1');
        $phpExcel->getActiveSheet()->mergeCells('D2:F2');
        $phpExcel->getActiveSheet()->mergeCells('D3:F3');
        $phpExcel->getActiveSheet()->mergeCells('H1:I1');
        $phpExcel->getActiveSheet()->mergeCells('H2:I2');
        $phpExcel->getActiveSheet()->mergeCells('H3:I3');

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);

        $count = 5;

        $phpExcel->getActiveSheet()
            ->setCellValue("A$count", '#')
            ->setCellValue("B$count", 'Member')
            ->setCellValue("C$count", 'Branch')
            ->setCellValue("D$count", 'Relation Manager')
            ->setCellValue("E$count", 'Account #')
            ->setCellValue("F$count", 'Amount Applied')
            ->setCellValue("G$count", 'Interest Rate')
            ->setCellValue("H$count", 'Repayment Period')
            ->setCellValue("I$count", 'Amount Disbursed')
            ->setCellValue("J$count", 'Disbursed At')
            ->setCellValue("K$count", 'Loan Balance');

        $phpExcel->getActiveSheet()->getStyle("A$count:R$count")->getFont()->setSize(14);
        $phpExcel->getActiveSheet()->getStyle("A$count:R$count")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("C$count:E$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcel->getActiveSheet()->getStyle("F$count:M$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("A$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("I$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $count++;
        $i=1;
        foreach($loanaccounts as $loanaccount){
            $phpExcel->getActiveSheet()
                ->setCellValue("A$count", (string)$i)
                ->setCellValue("B$count", $loanaccount->getBorrowerFullName())
                ->setCellValue("C$count", $loanaccount->getBorrowerBranchName())
                ->setCellValue("D$count", $loanaccount->getRelationshipManagerName())
                ->setCellValue("E$count", $loanaccount->account_number)
                ->setCellValue("F$count", (string)$loanaccount->getFormattedAmountApplied())
                ->setCellValue("G$count", $loanaccount->interest_rate.' % ')
                ->setCellValue("H$count", $loanaccount->repayment_period)
                ->setCellValue("I$count", (string)$loanaccount->getFormattedAmountDisbursed())
                ->setCellValue("J$count", $loanaccount->getFormattedDisbursedDate())
                ->setCellValue("K$count", (string)$loanaccount->getCurrentLoanBalance());
            $phpExcel->getActiveSheet()->getStyle("C$count:E$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $phpExcel->getActiveSheet()->getStyle("F$count:K$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $phpExcel->getActiveSheet()->getStyle("F$count:I$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $phpExcel->getActiveSheet()->getStyle("K$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $count++;
            $i++;
        }
        $phpExcel->setActiveSheetIndex(0);
        $filename = 'Disbursed_Accounts_Report'.date('YmdHis').strtoupper(CommonFunctions::generateToken(10)).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        return PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    }

    public static function getExcelDisbursedAccountsCollections($repayments){
        $phpExcel = new PHPExcel();
        $title = 'Loan Repayments';
        $fullName=Profiles::model()->findByPk(Yii::app()->user->user_id)->ProfileFullName;

        $phpExcel->getProperties()->setCreator("Treasure Capital Limited")
            ->setTitle("Loan Repayments Report")
            ->setSubject("Loan Repayments Report")
            ->setDescription("Loan Repayments Report");

        $phpExcel->getDefaultStyle()->getFont()->setName('Century Gothic')->setSize(12);
        $sheetIndex = 0;
        $phpExcel->createSheet(NULL, $sheetIndex);
        $phpExcel->setActiveSheetIndex($sheetIndex)
            ->setCellValue('D1', 'Treasure Capital Limited')
            ->setCellValue('E1', ' ')
            ->setCellValue('F1', ' ')
            ->setCellValue('D2', 'Loan Repayments')
            ->setCellValue('E2', ' ')
            ->setCellValue('F2', ' ')
            ->setCellValue('D3', ' ')
            ->setCellValue('E3', ' ')
            ->setCellValue('F3', ' ')
            ->setCellValue('H1', 'Printed By : ' .$fullName)
            ->setCellValue('I1', ' ')
            ->setCellValue('H2', 'Printed On : ' .date('jS M Y'))
            ->setCellValue('I2', ' ');
        /********
        LOGO
         **********************************/
        $logoDrawing = new PHPExcel_Worksheet_Drawing();
        $logoDrawing->setName('Logo');
        $logoDrawing->setDescription('Treasure Capital Logo');
        $logoDrawing->setPath('./images/site/tcl_logo.jpg');
        $logoDrawing->setResizeProportional(false);
        $logoDrawing->setWidth(275);
        $logoDrawing->setHeight(85);
        $logoDrawing->setCoordinates('A1');
        $logoDrawing->setWorksheet($phpExcel->getActiveSheet());

        $phpExcel->getActiveSheet()->setCellValue('H3', 'www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setUrl('https://www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setTooltip('Navigate to Website');
        $phpExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $phpExcel->getActiveSheet()->setTitle('Loan Accounts Repayments Report');
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('H1:I3')->getFont()->setItalic(true);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('D1:F2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        $phpExcel->getActiveSheet()->mergeCells('D1:F1');
        $phpExcel->getActiveSheet()->mergeCells('D2:F2');
        $phpExcel->getActiveSheet()->mergeCells('D3:F3');
        $phpExcel->getActiveSheet()->mergeCells('H1:I1');
        $phpExcel->getActiveSheet()->mergeCells('H2:I2');
        $phpExcel->getActiveSheet()->mergeCells('H3:I3');

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);

        $count = 5;

        $phpExcel->getActiveSheet()
            ->setCellValue("A$count", '#')
            ->setCellValue("B$count", 'Member')
            ->setCellValue("C$count", 'Branch')
            ->setCellValue("D$count", 'Relation Manager')
            ->setCellValue("E$count", 'Account #')
            ->setCellValue("F$count", 'Principal')
            ->setCellValue("G$count", 'Interest')
            ->setCellValue("H$count", 'Penalty')
            ->setCellValue("I$count", 'Total')
            ->setCellValue("J$count", 'Paid At')
            ->setCellValue("K$count", 'Month Disbursed')
            ->setCellValue("L$count", 'Year Disbursed');
        $phpExcel->getActiveSheet()->getStyle("A$count:R$count")->getFont()->setSize(14);
        $phpExcel->getActiveSheet()->getStyle("A$count:R$count")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("C$count:E$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcel->getActiveSheet()->getStyle("F$count:M$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("A$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("I$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("K$count:L$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $count++;
        $i=1;
        foreach($repayments as $repayment){
            $phpExcel->getActiveSheet()
                ->setCellValue("A$count", (string)$i)
                ->setCellValue("B$count", $repayment->LoanBorrowerName)
                ->setCellValue("C$count", $repayment->LoanRepaymentBranch)
                ->setCellValue("D$count", $repayment->LoanRepaymentManager)
                ->setCellValue("E$count", $repayment->LoanAccountNumber)
                ->setCellValue("F$count", (string)$repayment->PrincipalPaid)
                ->setCellValue("G$count", (string)$repayment->InterestPaid)
                ->setCellValue("H$count", (string)$repayment->PenaltyPaid)
                ->setCellValue("I$count", (string)$repayment->TotalAmountPaid)
                ->setCellValue("J$count", $repayment->FormattedClearTransactionDate)
                ->setCellValue("K$count", $repayment->DisbursedMonth)
                ->setCellValue("L$count", $repayment->DisbursedYear);
            $phpExcel->getActiveSheet()->getStyle("C$count:E$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $phpExcel->getActiveSheet()->getStyle("F$count:J$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $phpExcel->getActiveSheet()->getStyle("F$count:I$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $phpExcel->getActiveSheet()->getStyle("K$count:L$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $count++;
            $i++;
        }
        $phpExcel->setActiveSheetIndex(0);
        $filename = 'Loan_Accounts_Collections_Report'.date('YmdHis').strtoupper(CommonFunctions::generateToken(10)).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        return PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    }

    public static function getExcelAccountsProfitAndLoss($loanaccounts){
        $phpExcel = new PHPExcel();
        $title = 'Loan Accounts Profit And Loss';
        $fullName=Profiles::model()->findByPk(Yii::app()->user->user_id)->ProfileFullName;

        $phpExcel->getProperties()->setCreator("Treasure Capital Limited")
            ->setTitle("Loan Accounts Profit And Loss Report")
            ->setSubject("Loan Accounts Profit And Loss Report")
            ->setDescription("Loan Accounts Profit And Loss Report");

        $phpExcel->getDefaultStyle()->getFont()->setName('Century Gothic')->setSize(12);
        $sheetIndex = 0;
        $phpExcel->createSheet(NULL, $sheetIndex);
        $phpExcel->setActiveSheetIndex($sheetIndex)
            ->setCellValue('D1', 'Treasure Capital Limited')
            ->setCellValue('E1', ' ')
            ->setCellValue('F1', ' ')
            ->setCellValue('D2', 'Loan Accounts Profit And Loss')
            ->setCellValue('E2', ' ')
            ->setCellValue('F2', ' ')
            ->setCellValue('D3', ' ')
            ->setCellValue('E3', ' ')
            ->setCellValue('F3', ' ')
            ->setCellValue('H1', 'Printed By : ' .$fullName)
            ->setCellValue('I1', ' ')
            ->setCellValue('H2', 'Printed On : ' .date('jS M Y'))
            ->setCellValue('I2', ' ');
        /********
        LOGO
         **********************************/
        $logoDrawing = new PHPExcel_Worksheet_Drawing();
        $logoDrawing->setName('Logo');
        $logoDrawing->setDescription('Treasure Capital Logo');
        $logoDrawing->setPath('./images/site/tcl_logo.jpg');
        $logoDrawing->setResizeProportional(false);
        $logoDrawing->setWidth(275);
        $logoDrawing->setHeight(85);
        $logoDrawing->setCoordinates('A1');
        $logoDrawing->setWorksheet($phpExcel->getActiveSheet());

        $phpExcel->getActiveSheet()->setCellValue('H3', 'www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setUrl('https://www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setTooltip('Navigate to Website');
        $phpExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $phpExcel->getActiveSheet()->setTitle('Accounts Profit And Loss Report');
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('H1:I3')->getFont()->setItalic(true);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        $phpExcel->getActiveSheet()->mergeCells('D1:F1');
        $phpExcel->getActiveSheet()->mergeCells('D2:F2');
        $phpExcel->getActiveSheet()->mergeCells('D3:F3');
        $phpExcel->getActiveSheet()->mergeCells('H1:I1');
        $phpExcel->getActiveSheet()->mergeCells('H2:I2');
        $phpExcel->getActiveSheet()->mergeCells('H3:I3');

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);

        $count = 5;

        $phpExcel->getActiveSheet()
            ->setCellValue("A$count", '#')
            ->setCellValue("B$count", 'Member')
            ->setCellValue("C$count", 'Branch')
            ->setCellValue("D$count", 'Relation Manager')
            ->setCellValue("E$count", 'Account Number')
            ->setCellValue("F$count", 'Principal Balance')
            ->setCellValue("G$count", 'Accrued Interest')
            ->setCellValue("H$count", 'Penalties')
            ->setCellValue("I$count", 'Amount Due')
            ->setCellValue("J$count", 'Amount Paid')
            ->setCellValue("K$count", 'Profit/Loss')
            ->setCellValue("L$count", 'Last Payment Date')
            ->setCellValue("M$count", 'Month Disbursed')
            ->setCellValue("N$count", 'Year Disbursed');

        $phpExcel->getActiveSheet()->getStyle("A$count:R$count")->getFont()->setSize(14);
        $phpExcel->getActiveSheet()->getStyle("A$count:R$count")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("C$count:E$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcel->getActiveSheet()->getStyle("F$count:M$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("A$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("I$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("L$count:N$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $count++;
        $i=1;
        foreach($loanaccounts as $loanaccount){
            $phpExcel->getActiveSheet()
                ->setCellValue("A$count", (string)$i)
                ->setCellValue("B$count", $loanaccount->getBorrowerFullName())
                ->setCellValue("C$count", $loanaccount->getBorrowerBranchName())
                ->setCellValue("D$count", $loanaccount->getRelationshipManagerName())
                ->setCellValue("E$count", $loanaccount->account_number)
                ->setCellValue("F$count", (string)$loanaccount->getAccountPrincipalBalance())
                ->setCellValue("G$count", (string)$loanaccount->getAccruedInterest())
                ->setCellValue("H$count", (string)$loanaccount->getAccountPenalties())
                ->setCellValue("I$count", (string)$loanaccount->getAccountAmountDue())
                ->setCellValue("J$count", (string)$loanaccount->getAccountAmountPaid())
                ->setCellValue("K$count", (string)$loanaccount->getAccountProfitOrLoss())
                ->setCellValue("L$count", $loanaccount->getAccountPaymentDate())
                ->setCellValue("M$count", $loanaccount->DisbursedMonth)
                ->setCellValue("N$count", $loanaccount->DisbursedYear);
            $phpExcel->getActiveSheet()->getStyle("C$count:E$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $phpExcel->getActiveSheet()->getStyle("F$count:K$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $phpExcel->getActiveSheet()->getStyle("F$count:I$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $phpExcel->getActiveSheet()->getStyle("K$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $phpExcel->getActiveSheet()->getStyle("L$count:N$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $count++;
            $i++;
        }
        $phpExcel->setActiveSheetIndex(0);
        $filename = 'Accounts_Profit_Loss_Report'.date('YmdHis').strtoupper(CommonFunctions::generateToken(10)).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        return PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    }

    public static function getExcelMembersReport($members){
        $phpExcel = new PHPExcel();
        $title = 'Members Report';
        $fullName=Profiles::model()->findByPk(Yii::app()->user->user_id)->ProfileFullName;

        $phpExcel->getProperties()->setCreator("Treasure Capital Limited")
            ->setTitle("Microfinance Members Report")
            ->setSubject("Microfinance Members Report")
            ->setDescription("Microfinance Members Report");

        $phpExcel->getDefaultStyle()->getFont()->setName('Century Gothic')->setSize(12);
        $sheetIndex = 0;
        $phpExcel->createSheet(NULL, $sheetIndex);
        $phpExcel->setActiveSheetIndex($sheetIndex)
            ->setCellValue('D1', 'Treasure Capital Limited')
            ->setCellValue('E1', ' ')
            ->setCellValue('F1', ' ')
            ->setCellValue('D2', 'Microfinance Members')
            ->setCellValue('E2', ' ')
            ->setCellValue('F2', ' ')
            ->setCellValue('D3', ' ')
            ->setCellValue('E3', ' ')
            ->setCellValue('F3', ' ')
            ->setCellValue('H1', 'Printed By : ' .$fullName)
            ->setCellValue('I1', ' ')
            ->setCellValue('H2', 'Printed On : ' .date('jS M Y'))
            ->setCellValue('I2', ' ');
        /********
        LOGO
         **********************************/
        $logoDrawing = new PHPExcel_Worksheet_Drawing();
        $logoDrawing->setName('Logo');
        $logoDrawing->setDescription('Treasure Capital Logo');
        $logoDrawing->setPath('./images/site/tcl_logo.jpg');
        $logoDrawing->setResizeProportional(false);
        $logoDrawing->setWidth(275);
        $logoDrawing->setHeight(85);
        $logoDrawing->setCoordinates('A1');
        $logoDrawing->setWorksheet($phpExcel->getActiveSheet());

        $phpExcel->getActiveSheet()->setCellValue('H3', 'www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setUrl('https://www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setTooltip('Navigate to Website');
        $phpExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $phpExcel->getActiveSheet()->setTitle('Microfinance Members Report');
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('H1:I3')->getFont()->setItalic(true);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('D1:F2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        $phpExcel->getActiveSheet()->mergeCells('D1:F1');
        $phpExcel->getActiveSheet()->mergeCells('D2:F2');
        $phpExcel->getActiveSheet()->mergeCells('D3:F3');
        $phpExcel->getActiveSheet()->mergeCells('H1:I1');
        $phpExcel->getActiveSheet()->mergeCells('H2:I2');
        $phpExcel->getActiveSheet()->mergeCells('H3:I3');

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('M')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('N')->setWidth(18);

        $count = 5;

        $phpExcel->getActiveSheet()
            ->setCellValue("A$count", '#')
            ->setCellValue("B$count", 'Branch')
            ->setCellValue("C$count", 'Relationship Manager')
            ->setCellValue("D$count", 'Member Name')
            ->setCellValue("E$count", 'Gender')
            ->setCellValue("F$count", 'ID Number')
            ->setCellValue("G$count", 'Phone Number')
            ->setCellValue("H$count", 'Employer')
            ->setCellValue("I$count", 'Original Principal')
            ->setCellValue("J$count", 'Current Balance')
            ->setCellValue("K$count", 'Savings')
            ->setCellValue("L$count", 'Date Created')
            ->setCellValue("M$count", 'Year')
            ->setCellValue("N$count", 'Counts');

        $phpExcel->getActiveSheet()->getStyle("A$count:R$count")->getFont()->setSize(14);
        $phpExcel->getActiveSheet()->getStyle("A$count:R$count")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("A$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("C$count:G$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcel->getActiveSheet()->getStyle("I$count:N$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $count++;
        $i=1;
        foreach($members as $member){
            $phpExcel->getActiveSheet()
                ->setCellValue("A$count", (string)$i)
                ->setCellValue("B$count", $member->ProfileBranch)
                ->setCellValue("C$count", $member->ProfileManager)
                ->setCellValue("D$count", $member->ProfileFullName)
                ->setCellValue("E$count", $member->ProfileGender)
                ->setCellValue("F$count", $member->ProfileIdNumber)
                ->setCellValue("G$count", $member->ProfilePhoneNumber)
                ->setCellValue("H$count", $member->ProfileEmployment)
                ->setCellValue("I$count", $member->ProfileOriginalPrincipal)
                ->setCellValue("J$count", (string)$member->ProfileLoanBalance)
                ->setCellValue("K$count", (string)$member->ProfileSavings)
                ->setCellValue("L$count", $member->ProfileCreatedAt)
                ->setCellValue("M$count", $member->ProfileCreatedYear)
                ->setCellValue("N$count", $member->ProfileLoansCount);
            $phpExcel->getActiveSheet()->getStyle("C$count:G$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $phpExcel->getActiveSheet()->getStyle("I$count:N$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $phpExcel->getActiveSheet()->getStyle("I$count:K$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $count++;
            $i++;
        }
        $phpExcel->setActiveSheetIndex(0);
        $filename = 'Members_Report'.date('YmdHis').strtoupper(CommonFunctions::generateToken(10)).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        return PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    }

    public static function getExcelSavingAccountsReport($savingaccounts){
        $phpExcel = new PHPExcel();
        $title = 'Saving Accounts Report';
        $fullName=Profiles::model()->findByPk(Yii::app()->user->user_id)->ProfileFullName;

        $phpExcel->getProperties()->setCreator("Treasure Capital Limited")
            ->setTitle("Microfinance Saving Accounts Report")
            ->setSubject("Microfinance Saving Accounts Report")
            ->setDescription("Microfinance Saving Accounts Report");

        $phpExcel->getDefaultStyle()->getFont()->setName('Century Gothic')->setSize(12);
        $sheetIndex = 0;
        $phpExcel->createSheet(NULL, $sheetIndex);
        $phpExcel->setActiveSheetIndex($sheetIndex)
            ->setCellValue('D1', 'Treasure Capital Limited')
            ->setCellValue('E1', ' ')
            ->setCellValue('F1', ' ')
            ->setCellValue('D2', 'Microfinance Saving Accounts')
            ->setCellValue('E2', ' ')
            ->setCellValue('F2', ' ')
            ->setCellValue('D3', ' ')
            ->setCellValue('E3', ' ')
            ->setCellValue('F3', ' ')
            ->setCellValue('H1', 'Printed By : ' .$fullName)
            ->setCellValue('I1', ' ')
            ->setCellValue('H2', 'Printed On : ' .date('jS M Y'))
            ->setCellValue('I2', ' ');
        /********
        LOGO
         **********************************/
        $logoDrawing = new PHPExcel_Worksheet_Drawing();
        $logoDrawing->setName('Logo');
        $logoDrawing->setDescription('Treasure Capital Logo');
        $logoDrawing->setPath('./images/site/tcl_logo.jpg');
        $logoDrawing->setResizeProportional(false);
        $logoDrawing->setWidth(275);
        $logoDrawing->setHeight(85);
        $logoDrawing->setCoordinates('A1');
        $logoDrawing->setWorksheet($phpExcel->getActiveSheet());

        $phpExcel->getActiveSheet()->setCellValue('H3', 'www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setUrl('https://www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setTooltip('Navigate to Website');
        $phpExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $phpExcel->getActiveSheet()->setTitle('Microfinance Savings Report');
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('H1:I3')->getFont()->setItalic(true);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('D1:F2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        $phpExcel->getActiveSheet()->mergeCells('D1:F1');
        $phpExcel->getActiveSheet()->mergeCells('D2:F2');
        $phpExcel->getActiveSheet()->mergeCells('D3:F3');
        $phpExcel->getActiveSheet()->mergeCells('H1:I1');
        $phpExcel->getActiveSheet()->mergeCells('H2:I2');
        $phpExcel->getActiveSheet()->mergeCells('H3:I3');

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);

        $count = 5;

        $phpExcel->getActiveSheet()
            ->setCellValue("A$count", '#')
            ->setCellValue("B$count", 'Branch')
            ->setCellValue("C$count", 'Relationship Manager')
            ->setCellValue("D$count", 'Client Name')
            ->setCellValue("E$count", 'Contact Number')
            ->setCellValue("F$count", 'Account Number')
            ->setCellValue("G$count", 'Current Principal')
            ->setCellValue("H$count", 'Interest Accrued')
            ->setCellValue("I$count", 'Interest Rate')
            ->setCellValue("J$count", 'Total Savings');

        $phpExcel->getActiveSheet()->getStyle("A$count:J$count")->getFont()->setSize(14);
        $phpExcel->getActiveSheet()->getStyle("A$count:J$count")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("A$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("B$count:F$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcel->getActiveSheet()->getStyle("G$count:J$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $count++;
        $i=1;
        $totalPrincipals=0;
        $totalInterestAccrued=0;
        $totalSavingsBalances=0;
        foreach($savingaccounts as $savingaccount){
            $phpExcel->getActiveSheet()
                ->setCellValue("A$count", $i)
                ->setCellValue("B$count", $savingaccount->SavingAccountHolderBranch)
                ->setCellValue("C$count", $savingaccount->SavingAccountHolderRelationManager)
                ->setCellValue("D$count", $savingaccount->SavingAccountHolderName)
                ->setCellValue("E$count", $savingaccount->SavingAccountHolderPhoneNumber)
                ->setCellValue("F$count", $savingaccount->SavingAccountNumber)
                ->setCellValue("G$count", $savingaccount->SavingAccountBalance)
                ->setCellValue("H$count", $savingaccount->SavingAccountInterestAccrued)
                ->setCellValue("I$count", $savingaccount->interest_rate)
                ->setCellValue("J$count", $savingaccount->SavingAccountTotal);

            $phpExcel->getActiveSheet()->getStyle("B$count:F$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $phpExcel->getActiveSheet()->getStyle("G$count:J$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $phpExcel->getActiveSheet()->getStyle("G$count:J$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $totalPrincipals+=$savingaccount->SavingAccountBalance;
            $totalInterestAccrued+=$savingaccount->SavingAccountInterestAccrued;
            $totalSavingsBalances+=$savingaccount->SavingAccountTotal;
            $count++;
            $i++;
        }
        $nextCounter=$count++;
        $phpExcel->getActiveSheet()->getStyle("B$nextCounter:J$nextCounter")->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle("B$nextCounter:J$nextCounter")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("B$nextCounter:J$nextCounter")->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("B$nextCounter:J$nextCounter")->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
        $phpExcel->getActiveSheet()->getStyle("B$nextCounter:J$nextCounter")->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => '00FF00'
            )
        ));
        $bottomTitle="TOTALS";
        $phpExcel->getActiveSheet()->setCellValue("B$nextCounter", $bottomTitle);$phpExcel->getActiveSheet()->getStyle("B$nextCounter")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->mergeCells("B$nextCounter:F$nextCounter");
        $phpExcel->getActiveSheet()->getStyle("G$nextCounter:J$nextCounter")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
        $phpExcel->getActiveSheet()
            ->setCellValue("F$nextCounter", $bottomTitle)
            ->setCellValue("G$nextCounter", (string)$totalPrincipals)
            ->setCellValue("H$nextCounter", (string)$totalInterestAccrued)
            ->setCellValue("J$nextCounter", (string)$totalSavingsBalances);
        $phpExcel->setActiveSheetIndex(0);
        $filename = 'Saving_Accounts_Report'.date('YmdHis').strtoupper(CommonFunctions::generateToken(10)).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        return PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    }

    public static function getExcelAssetsReport($assets){
        $phpExcel = new PHPExcel();
        $title = 'Assets Report';
        $fullName=Profiles::model()->findByPk(Yii::app()->user->user_id)->ProfileFullName;

        $phpExcel->getProperties()->setCreator("Treasure Capital Limited")
            ->setTitle("Microfinance Assets Report")
            ->setSubject("Microfinance Assets Report")
            ->setDescription("Microfinance Assets Report");

        $phpExcel->getDefaultStyle()->getFont()->setName('Century Gothic')->setSize(12);
        $sheetIndex = 0;
        $phpExcel->createSheet(NULL, $sheetIndex);
        $phpExcel->setActiveSheetIndex($sheetIndex)
            ->setCellValue('D1', 'Treasure Capital Limited')
            ->setCellValue('E1', ' ')
            ->setCellValue('F1', ' ')
            ->setCellValue('D2', 'Microfinance Assets')
            ->setCellValue('E2', ' ')
            ->setCellValue('F2', ' ')
            ->setCellValue('D3', ' ')
            ->setCellValue('E3', ' ')
            ->setCellValue('F3', ' ')
            ->setCellValue('H1', 'Printed By : ' .$fullName)
            ->setCellValue('I1', ' ')
            ->setCellValue('H2', 'Printed On : ' .date('jS M Y'))
            ->setCellValue('I2', ' ');
        /********
        LOGO
         **********************************/
        $logoDrawing = new PHPExcel_Worksheet_Drawing();
        $logoDrawing->setName('Logo');
        $logoDrawing->setDescription('Treasure Capital Logo');
        $logoDrawing->setPath('./images/site/tcl_logo.jpg');
        $logoDrawing->setResizeProportional(false);
        $logoDrawing->setWidth(275);
        $logoDrawing->setHeight(85);
        $logoDrawing->setCoordinates('A1');
        $logoDrawing->setWorksheet($phpExcel->getActiveSheet());

        $phpExcel->getActiveSheet()->setCellValue('H3', 'www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setUrl('https://www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setTooltip('Navigate to Website');
        $phpExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $phpExcel->getActiveSheet()->setTitle('Microfinance Assets Report');
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('H1:I3')->getFont()->setItalic(true);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('D1:F2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        $phpExcel->getActiveSheet()->mergeCells('D1:F1');
        $phpExcel->getActiveSheet()->mergeCells('D2:F2');
        $phpExcel->getActiveSheet()->mergeCells('D3:F3');
        $phpExcel->getActiveSheet()->mergeCells('H1:I1');
        $phpExcel->getActiveSheet()->mergeCells('H2:I2');
        $phpExcel->getActiveSheet()->mergeCells('H3:I3');

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);

        $count = 5;

        $phpExcel->getActiveSheet()
            ->setCellValue("A$count", '#')
            ->setCellValue("B$count", 'Branch')
            ->setCellValue("C$count", 'Staff Assigned')
            ->setCellValue("D$count", 'Asset Name')
            ->setCellValue("E$count", 'Asset Type')
            ->setCellValue("F$count", 'Serial Number')
            ->setCellValue("G$count", 'Purchase Price')
            ->setCellValue("H$count", 'Replacement Value')
            ->setCellValue("I$count", 'Status');

        $phpExcel->getActiveSheet()->getStyle("A$count:I$count")->getFont()->setSize(14);
        $phpExcel->getActiveSheet()->getStyle("A$count:I$count")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("A$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("B$count:F$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcel->getActiveSheet()->getStyle("G$count:I$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $count++;
        $i=1;
        foreach($assets as $asset){
            $phpExcel->getActiveSheet()
                ->setCellValue("A$count", (string)$i)
                ->setCellValue("B$count", $asset->AssetBranchName)
                ->setCellValue("C$count", $asset->AssetStaffName)
                ->setCellValue("D$count", $asset->AssetName)
                ->setCellValue("E$count", $asset->AssetTypeName)
                ->setCellValue("F$count", $asset->AssetSerialNumber)
                ->setCellValue("G$count", $asset->purchase_price)
                ->setCellValue("H$count", $asset->replacement_value)
                ->setCellValue("I$count", $asset->AssetStatus);

            $phpExcel->getActiveSheet()->getStyle("B$count:F$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $phpExcel->getActiveSheet()->getStyle("G$count:I$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $phpExcel->getActiveSheet()->getStyle("G$count:H$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $count++;
            $i++;
        }
        $phpExcel->setActiveSheetIndex(0);
        $filename = 'Assets_Report'.date('YmdHis').strtoupper(CommonFunctions::generateToken(10)).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        return PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    }

    public static function getExcelCollateralRegisterReport($collaterals){
        $phpExcel = new PHPExcel();
        $title = 'Collateral Report';
        $fullName=Profiles::model()->findByPk(Yii::app()->user->user_id)->ProfileFullName;

        $phpExcel->getProperties()->setCreator("Treasure Capital Limited")
            ->setTitle("Microfinance Collateral Report")
            ->setSubject("Microfinance Collateral Report")
            ->setDescription("Microfinance Collateral Report");

        $phpExcel->getDefaultStyle()->getFont()->setName('Century Gothic')->setSize(12);
        $sheetIndex = 0;
        $phpExcel->createSheet(NULL, $sheetIndex);
        $phpExcel->setActiveSheetIndex($sheetIndex)
            ->setCellValue('D1', 'Treasure Capital Limited')
            ->setCellValue('E1', ' ')
            ->setCellValue('F1', ' ')
            ->setCellValue('D2', 'Microfinance Collateral')
            ->setCellValue('E2', ' ')
            ->setCellValue('F2', ' ')
            ->setCellValue('D3', ' ')
            ->setCellValue('E3', ' ')
            ->setCellValue('F3', ' ')
            ->setCellValue('H1', 'Printed By : ' .$fullName)
            ->setCellValue('I1', ' ')
            ->setCellValue('H2', 'Printed On : ' .date('jS M Y'))
            ->setCellValue('I2', ' ');
        /********
        LOGO
         **********************************/
        $logoDrawing = new PHPExcel_Worksheet_Drawing();
        $logoDrawing->setName('Logo');
        $logoDrawing->setDescription('Treasure Capital Logo');
        $logoDrawing->setPath('./images/site/tcl_logo.jpg');
        $logoDrawing->setResizeProportional(false);
        $logoDrawing->setWidth(275);
        $logoDrawing->setHeight(85);
        $logoDrawing->setCoordinates('A1');
        $logoDrawing->setWorksheet($phpExcel->getActiveSheet());

        $phpExcel->getActiveSheet()->setCellValue('H3', 'www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setUrl('https://www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setTooltip('Navigate to Website');
        $phpExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $phpExcel->getActiveSheet()->setTitle('Microfinance Collateral Report');
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('H1:I3')->getFont()->setItalic(true);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('D1:F2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        $phpExcel->getActiveSheet()->mergeCells('D1:F1');
        $phpExcel->getActiveSheet()->mergeCells('D2:F2');
        $phpExcel->getActiveSheet()->mergeCells('D3:F3');
        $phpExcel->getActiveSheet()->mergeCells('H1:I1');
        $phpExcel->getActiveSheet()->mergeCells('H2:I2');
        $phpExcel->getActiveSheet()->mergeCells('H3:I3');

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('K')->setWidth(25);

        $count = 5;

        $phpExcel->getActiveSheet()
            ->setCellValue("A$count", '#')
            ->setCellValue("B$count", 'Branch')
            ->setCellValue("C$count", 'Relation Manager')
            ->setCellValue("D$count", 'Type')
            ->setCellValue("E$count", 'Model')
            ->setCellValue("F$count", 'Serial Number')
            ->setCellValue("G$count", 'Market Value')
            ->setCellValue("H$count", 'Original Loan')
            ->setCellValue("I$count", 'Current Balance')
            ->setCellValue("J$count", 'Loan to Value Ratio')
            ->setCellValue("K$count", 'Status');

        $phpExcel->getActiveSheet()->getStyle("A$count:K$count")->getFont()->setSize(14);
        $phpExcel->getActiveSheet()->getStyle("A$count:K$count")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("A$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("B$count:F$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcel->getActiveSheet()->getStyle("G$count:K$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $count++;
        $i=1;
        foreach($collaterals as $collateral){
            $phpExcel->getActiveSheet()
                ->setCellValue("A$count", $i)
                ->setCellValue("B$count", $collateral->CollateralBranchName)
                ->setCellValue("C$count", $collateral->CollateralStaffName)
                ->setCellValue("D$count", $collateral->CollateralTypeName)
                ->setCellValue("E$count", $collateral->CollateralModel)
                ->setCellValue("F$count", $collateral->CollateralSerialNumber)
                ->setCellValue("G$count", $collateral->CollateralOriginalMarketValue)
                ->setCellValue("H$count", $collateral->CollateralOriginalLoanAmount)
                ->setCellValue("I$count", $collateral->CollateralLoanCurrentBalance)
                ->setCellValue("J$count", $collateral->CollateralLoanToValueRatio)
                ->setCellValue("K$count", $collateral->CollateralCurrentStatus);

            $phpExcel->getActiveSheet()->getStyle("B$count:F$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $phpExcel->getActiveSheet()->getStyle("G$count:K$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $phpExcel->getActiveSheet()->getStyle("G$count:I$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $count++;
            $i++;
        }
        $phpExcel->setActiveSheetIndex(0);
        $filename = 'Collateral_Report'.date('YmdHis').strtoupper(CommonFunctions::generateToken(10)).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        return PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    }

    public static function getExcelAccountsWriteOffsReport($writeoffs){
        $phpExcel = new PHPExcel();
        $title = 'WriteOff Report';
        $fullName=Profiles::model()->findByPk(Yii::app()->user->user_id)->ProfileFullName;

        $phpExcel->getProperties()->setCreator("Treasure Capital Limited")
            ->setTitle("Microfinance WriteOff Report")
            ->setSubject("Microfinance WriteOff Report")
            ->setDescription("Microfinance WriteOff Report");

        $phpExcel->getDefaultStyle()->getFont()->setName('Century Gothic')->setSize(12);
        $sheetIndex = 0;
        $phpExcel->createSheet(NULL, $sheetIndex);
        $phpExcel->setActiveSheetIndex($sheetIndex)
            ->setCellValue('D1', 'Treasure Capital Limited')
            ->setCellValue('E1', ' ')
            ->setCellValue('F1', ' ')
            ->setCellValue('D2', 'Microfinance WriteOff')
            ->setCellValue('E2', ' ')
            ->setCellValue('F2', ' ')
            ->setCellValue('D3', ' ')
            ->setCellValue('E3', ' ')
            ->setCellValue('F3', ' ')
            ->setCellValue('H1', 'Printed By : ' .$fullName)
            ->setCellValue('I1', ' ')
            ->setCellValue('H2', 'Printed On : ' .date('jS M Y'))
            ->setCellValue('I2', ' ');
        /********
        LOGO
         **********************************/
        $logoDrawing = new PHPExcel_Worksheet_Drawing();
        $logoDrawing->setName('Logo');
        $logoDrawing->setDescription('Treasure Capital Logo');
        $logoDrawing->setPath('./images/site/tcl_logo.jpg');
        $logoDrawing->setResizeProportional(false);
        $logoDrawing->setWidth(275);
        $logoDrawing->setHeight(85);
        $logoDrawing->setCoordinates('A1');
        $logoDrawing->setWorksheet($phpExcel->getActiveSheet());

        $phpExcel->getActiveSheet()->setCellValue('H3', 'www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setUrl('https://www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setTooltip('Navigate to Website');
        $phpExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $phpExcel->getActiveSheet()->setTitle('Microfinance WriteOff Report');
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('H1:I3')->getFont()->setItalic(true);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('D1:F2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        $phpExcel->getActiveSheet()->mergeCells('D1:F1');
        $phpExcel->getActiveSheet()->mergeCells('D2:F2');
        $phpExcel->getActiveSheet()->mergeCells('D3:F3');
        $phpExcel->getActiveSheet()->mergeCells('H1:I1');
        $phpExcel->getActiveSheet()->mergeCells('H2:I2');
        $phpExcel->getActiveSheet()->mergeCells('H3:I3');

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(25);

        $count = 5;

        $phpExcel->getActiveSheet()
            ->setCellValue("A$count", '#')
            ->setCellValue("B$count", 'Branch')
            ->setCellValue("C$count", 'Relation Manager')
            ->setCellValue("D$count", 'Client Name')
            ->setCellValue("E$count", 'Account Number')
            ->setCellValue("F$count", 'Original Loan')
            ->setCellValue("G$count", 'Interest Rate')
            ->setCellValue("H$count", 'Amount Written Off')
            ->setCellValue("I$count", 'Date of Write Off')
            ->setCellValue("J$count", 'Written Off By');

        $phpExcel->getActiveSheet()->getStyle("A$count:K$count")->getFont()->setSize(14);
        $phpExcel->getActiveSheet()->getStyle("A$count:K$count")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("A$count:E$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcel->getActiveSheet()->getStyle("F$count:I$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("J$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $count++;
        $i=1;
        foreach($writeoffs as $writeoff){
            $phpExcel->getActiveSheet()
                ->setCellValue("A$count", $i)
                ->setCellValue("B$count", $writeoff->BranchName)
                ->setCellValue("C$count", $writeoff->ManagerName)
                ->setCellValue("D$count", $writeoff->ClientName)
                ->setCellValue("E$count", $writeoff->AccountNumber)
                ->setCellValue("F$count", $writeoff->OriginalLoanAmount)
                ->setCellValue("G$count", $writeoff->FormattedOriginalInterestRate)
                ->setCellValue("H$count", $writeoff->amount)
                ->setCellValue("I$count", $writeoff->TransactionDate)
                ->setCellValue("J$count", $writeoff->TransactedBy);

            $phpExcel->getActiveSheet()->getStyle("A$count:E$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $phpExcel->getActiveSheet()->getStyle("F$count:I$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $phpExcel->getActiveSheet()->getStyle("J$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $phpExcel->getActiveSheet()->getStyle("F$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $phpExcel->getActiveSheet()->getStyle("H$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $count++;
            $i++;
        }
        $phpExcel->setActiveSheetIndex(0);
        $filename = 'WriteOff_Report'.date('YmdHis').strtoupper(CommonFunctions::generateToken(10)).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        return PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    }

    public static function getExcelFrozenLoanAccountsReport($accounts){
        $phpExcel = new PHPExcel();
        $title = 'Frozen Accounts Report';
        $fullName=Profiles::model()->findByPk(Yii::app()->user->user_id)->ProfileFullName;

        $phpExcel->getProperties()->setCreator("Treasure Capital Limited")
            ->setTitle("Microfinance Frozen Accounts Report")
            ->setSubject("Microfinance Frozen Accounts Report")
            ->setDescription("Microfinance Frozen Accounts Report");

        $phpExcel->getDefaultStyle()->getFont()->setName('Century Gothic')->setSize(12);
        $sheetIndex = 0;
        $phpExcel->createSheet(NULL, $sheetIndex);
        $phpExcel->setActiveSheetIndex($sheetIndex)
            ->setCellValue('D1', 'Treasure Capital Limited')
            ->setCellValue('E1', ' ')
            ->setCellValue('F1', ' ')
            ->setCellValue('D2', 'Microfinance Frozen Accounts')
            ->setCellValue('E2', ' ')
            ->setCellValue('F2', ' ')
            ->setCellValue('D3', ' ')
            ->setCellValue('E3', ' ')
            ->setCellValue('F3', ' ')
            ->setCellValue('H1', 'Printed By : ' .$fullName)
            ->setCellValue('I1', ' ')
            ->setCellValue('H2', 'Printed On : ' .date('jS M Y'))
            ->setCellValue('I2', ' ');
        /********
        LOGO
         **********************************/
        $logoDrawing = new PHPExcel_Worksheet_Drawing();
        $logoDrawing->setName('Logo');
        $logoDrawing->setDescription('Treasure Capital Logo');
        $logoDrawing->setPath('./images/site/tcl_logo.jpg');
        $logoDrawing->setResizeProportional(false);
        $logoDrawing->setWidth(275);
        $logoDrawing->setHeight(85);
        $logoDrawing->setCoordinates('A1');
        $logoDrawing->setWorksheet($phpExcel->getActiveSheet());

        $phpExcel->getActiveSheet()->setCellValue('H3', 'www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setUrl('https://www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setTooltip('Navigate to Website');
        $phpExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $phpExcel->getActiveSheet()->setTitle('Microfinance WriteOff Report');
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('H1:I3')->getFont()->setItalic(true);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('D1:F2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        $phpExcel->getActiveSheet()->mergeCells('D1:F1');
        $phpExcel->getActiveSheet()->mergeCells('D2:F2');
        $phpExcel->getActiveSheet()->mergeCells('D3:F3');
        $phpExcel->getActiveSheet()->mergeCells('H1:I1');
        $phpExcel->getActiveSheet()->mergeCells('H2:I2');
        $phpExcel->getActiveSheet()->mergeCells('H3:I3');

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('J')->setWidth(45);

        $count = 5;

        $phpExcel->getActiveSheet()
            ->setCellValue("A$count", '#')
            ->setCellValue("B$count", 'Branch')
            ->setCellValue("C$count", 'Relation Manager')
            ->setCellValue("D$count", 'Client Name')
            ->setCellValue("E$count", 'Current Balance')
            ->setCellValue("F$count", 'Interest Rate')
            ->setCellValue("G$count", 'Start Date')
            ->setCellValue("H$count", 'End Date')
            ->setCellValue("I$count", 'Days Remaining')
            ->setCellValue("J$count", 'Frozen Account Status');

        $phpExcel->getActiveSheet()->getStyle("A$count:K$count")->getFont()->setSize(14);
        $phpExcel->getActiveSheet()->getStyle("A$count:K$count")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("A$count:D$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcel->getActiveSheet()->getStyle("E$count:J$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $count++;
        $i=1;
        foreach($accounts as $account){
            $phpExcel->getActiveSheet()
                ->setCellValue("A$count", (string)$i)
                ->setCellValue("B$count", $account->BranchName)
                ->setCellValue("C$count", $account->RelationManager)
                ->setCellValue("D$count", $account->ClientName)
                ->setCellValue("E$count", (string)$account->CurrentLoanBalance)
                ->setCellValue("F$count", (string)$account->CurrentInterestRate)
                ->setCellValue("G$count", $account->FreezeStartDate)
                ->setCellValue("H$count", $account->FreezeEndDate)
                ->setCellValue("I$count", (string)$account->FreezeRemainingDays)
                ->setCellValue("J$count", $account->FreezeStatus);

            $phpExcel->getActiveSheet()->getStyle("A$count:D$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $phpExcel->getActiveSheet()->getStyle("E$count:J$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $phpExcel->getActiveSheet()->getStyle("E$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $count++;
            $i++;
        }
        $phpExcel->setActiveSheetIndex(0);
        $filename = 'FrozenAccounts_Report'.date('YmdHis').strtoupper(CommonFunctions::generateToken(10)).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        return PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    }

    public static function getExcelCommentsReport($comments){
        $phpExcel = new PHPExcel();
        $title    = 'Loan Comments Report';
        $fullName = Profiles::model()->findByPk(Yii::app()->user->user_id)->ProfileFullName;

        $phpExcel->getProperties()->setCreator("Treasure Capital Limited")
            ->setTitle("Microfinance Loan Comments Report")
            ->setSubject("Microfinance Loan Comments Report")
            ->setDescription("Microfinance Loan Comments Report");

        $phpExcel->getDefaultStyle()->getFont()->setName('Century Gothic')->setSize(12);
        $sheetIndex = 0;
        $phpExcel->createSheet(NULL, $sheetIndex);
        $phpExcel->setActiveSheetIndex($sheetIndex)
            ->setCellValue('D1', 'Treasure Capital Limited')
            ->setCellValue('E1', ' ')
            ->setCellValue('F1', ' ')
            ->setCellValue('D2', 'Microfinance Loan Comments')
            ->setCellValue('E2', ' ')
            ->setCellValue('F2', ' ')
            ->setCellValue('D3', ' ')
            ->setCellValue('E3', ' ')
            ->setCellValue('F3', ' ')
            ->setCellValue('H1', 'Printed By : ' .$fullName)
            ->setCellValue('I1', ' ')
            ->setCellValue('H2', 'Printed On : ' .date('jS M Y H:i:s'))
            ->setCellValue('I2', ' ');
        /********
        LOGO
         **********************************/
        $logoDrawing = new PHPExcel_Worksheet_Drawing();
        $logoDrawing->setName('Logo');
        $logoDrawing->setDescription('Treasure Capital Logo');
        $logoDrawing->setPath('./images/site/tcl_logo.jpg');
        $logoDrawing->setResizeProportional(false);
        $logoDrawing->setWidth(275);
        $logoDrawing->setHeight(85);
        $logoDrawing->setCoordinates('A1');
        $logoDrawing->setWorksheet($phpExcel->getActiveSheet());

        $phpExcel->getActiveSheet()->setCellValue('H3', 'www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setUrl('https://www.tclfinance.co.ke');
        $phpExcel->getActiveSheet()->getCell('H3')->getHyperlink()->setTooltip('Navigate to Website');
        $phpExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $phpExcel->getActiveSheet()->setTitle('Microfinance Comments Report');
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setSize(16);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle('H1:I3')->getFont()->setItalic(true);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle('D1:F2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $phpExcel->getActiveSheet()->getStyle('D1:F3')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

        $phpExcel->getActiveSheet()->mergeCells('D1:F1');
        $phpExcel->getActiveSheet()->mergeCells('D2:F2');
        $phpExcel->getActiveSheet()->mergeCells('D3:F3');
        $phpExcel->getActiveSheet()->mergeCells('H1:I1');
        $phpExcel->getActiveSheet()->mergeCells('H2:I2');
        $phpExcel->getActiveSheet()->mergeCells('H3:I3');

        $phpExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $phpExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $phpExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $phpExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
        $phpExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);

        $count = 5;

        $phpExcel->getActiveSheet()
            ->setCellValue("A$count", '#')
            ->setCellValue("B$count", 'Client Name')
            ->setCellValue("C$count", 'Relationship Manager')
            ->setCellValue("D$count", 'Branch')
            ->setCellValue("E$count", 'Loan Balance')
            ->setCellValue("F$count", 'Comment Type')
            ->setCellValue("G$count", 'Actual Comment')
            ->setCellValue("H$count", 'Commented By')
            ->setCellValue("I$count", 'Commented On');
        $phpExcel->getActiveSheet()->getStyle("A$count:R$count")->getFont()->setSize(14);
        $phpExcel->getActiveSheet()->getStyle("A$count:R$count")->getFont()->setBold(true);
        $phpExcel->getActiveSheet()->getStyle("A$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $phpExcel->getActiveSheet()->getStyle("B$count:D$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $phpExcel->getActiveSheet()->getStyle("E$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcel->getActiveSheet()->getStyle("G$count")->getAlignment()->setWrapText(true);
        $phpExcel->getActiveSheet()->getStyle("I$count")->getAlignment()->setWrapText(true);
        $phpExcel->getActiveSheet()->getStyle("F$count:I$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $count++;

        $i = 1;
        foreach($comments as $comment){
            $phpExcel->getActiveSheet()
                ->setCellValue("A$count", (string)$i)
                ->setCellValue("B$count", $comment->CommentClientName)
                ->setCellValue("C$count", $comment->CommentRelationManager)
                ->setCellValue("D$count", $comment->CommentBranchName)
                ->setCellValue("E$count", (string)$comment->ActualLoanBalance)
                ->setCellValue("F$count", $comment->CommentTypeName)
                ->setCellValue("G$count", $comment->LoanActualComment)
                ->setCellValue("H$count", $comment->LoanCommentedByName)
                ->setCellValue("I$count", $comment->LoanCommentedAt);
            $phpExcel->getActiveSheet()->getStyle("A$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $phpExcel->getActiveSheet()->getStyle("B$count:D$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $phpExcel->getActiveSheet()->getStyle("E$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $phpExcel->getActiveSheet()->getStyle("G$count")->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle("I$count")->getAlignment()->setWrapText(true);
            $phpExcel->getActiveSheet()->getStyle("F$count:I$count")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $phpExcel->getActiveSheet()->getStyle("E$count")->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);
            $count++;
            $i++;
        }
        $phpExcel->setActiveSheetIndex(0);
        $filename = 'Loan_Comments_Report'.date('YmdHis').strtoupper(CommonFunctions::generateToken(10)).'.xls';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        return PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
    }

}