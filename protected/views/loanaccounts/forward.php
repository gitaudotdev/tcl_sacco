<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance : Loan Application';
$this->breadcrumbs=array(
	'loanaccounts'=>array('loanaccounts/admin'),
	'ForwardApplication'=>array('loanaccounts/forward/'.$model->loanaccount_id)
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Forward Loan Application</h5>
            <hr class="common_rule">
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
            <form method="post">
            <br>
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <div class="form-group">
                      <select name="forwarded_to" class="selectpicker" required="required">
                    <option value="">--Select Staff To Forward Loan To--</option>
                    <?php
                      foreach($users as $user){
                        echo "<option value='$user->user_id'>$user->UserFullName</option>";
                      }
                    ?>
                  </select>
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <div class="form-group">
                    <textarea class="form-control" name="comment" cols='5' rows='5' placeholder="Please provide brief comment for forwarding the application..."></textarea>
                  </div>
              </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit Application" id="apply_loan_cmd" name="fwd_loan_cmd">
                  </div>
              </div>
              <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                    <a href="<?=Yii::app()->createUrl('loanaccounts/admin');?>" type="submit" class="btn btn-default pull-right">Cancel Action</a>
                </div>
              </div>
            </form>
	        </div>
        </div>
     </div>
  </div>
</div>