<?php
$this->pageTitle   =  Yii::app()->name . ' - Add Loan Comment';
$this->breadcrumbs = array(
	'Home'           =>  array('dashboard/admin'),
  'Application'    =>  array('loanaccounts/'.$model->loanaccount_id),
  'Comment'        =>  array('loanaccounts/comment/'.$model->loanaccount_id)
);
?>
<style type="text/css">
	*, ::after, ::before {
	    box-sizing: border-box;
	}
	.card-title, label{
		font-size:13px !important;
	}
	.fileinput {
	    display: inline-block;
	    margin-bottom: 9px;
	}
	.text-center {
	    text-align: center !important;
	}
	.fileinput .thumbnail {
	    display: inline-block !important;
	    margin-bottom: 10px !important;
	    overflow: hidden !important;
	    text-align: center !important; 
	    vertical-align: middle !important;
	    max-width: 250px !important;
	    min-height: 20px !important;
	    box-shadow: 0 1px 15px 1px rgba(39,39,39,.1) !important;
	}
	.fileinput-exists .fileinput-new, .fileinput-new .fileinput-exists {
	    display: none !important;
	}
	.thumbnail {
   		 border: 0 none;
	    border-radius: 3px;
	    padding: 0;
	}
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
					<div class="col-md-12 col-lg-12 col-sm-12">
          <h5 class="title">Submit Loan Comment</h5>
        	<hr class="common_rule">
       </div>
				<?php if(CommonFunctions::checkIfFlashMessageSet('success') === 1):?>
			    <div class="col-md-12 col-lg-12 col-sm-12">
			      <?=CommonFunctions::displayFlashMessage('success');?>
			    </div>
		    <?php endif;?>
		    <?php if(CommonFunctions::checkIfFlashMessageSet('info') === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage('info');?>
		      </div>
		    <?php endif;?>
		    <?php if(CommonFunctions::checkIfFlashMessageSet('warning') === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage('warning');?>
		      </div>
		    <?php endif;?>
		    <?php if(CommonFunctions::checkIfFlashMessageSet('danger') === 1):?>
		      <div class="col-md-12 col-lg-12 col-sm-12">
		        <?=CommonFunctions::displayFlashMessage('danger');?>
		      </div>
		    <?php endif;?>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
        	<form method="post" action="<?=Yii::app()->createUrl('loanaccounts/commitComment');?>">
      		<input type="hidden" name="loanaccount_id" value="<?=$model->loanaccount_id;?>">
      		<div class="row">
				    	<div class="col-md-6 col-lg-6 col-sm-12">
			       		 <div class="form-group">
			       		 	<label>Select Comment Type</label>
									 <select class="selectpicker form-control-changed" name="type" required="required">
                      <option value=""> -- COMMENT TYPES -- </option>
                      <?php if(!empty($types)):?>
                          <?php foreach($types as $type):?>
                              <option value="<?=$type->id;?>">
                                  <?=$type->name;?>
                              </option>
                          <?php endforeach;?>
                      <?php endif;?>
                  </select>
								</div>
							</div>
					</div>
					<br>
      		<div class="row">
				    	<div class="col-md-6 col-lg-6 col-sm-12">
			       		 <div class="form-group">
			       		 	<label>Brief Comment</label>
									<textarea class="form-control" cols="15" rows="15" name="comment" placeholder="Brief comment..." required="required" maxlength="50"></textarea>
								</div>
							</div>
					</div>
				<br>
				<div class="row">
					<div class="col-md-3 col-lg-3 col-sm-12">
				      <div class="form-group">
				      	<a href="<?=Yii::app()->createUrl('loanaccounts/'.$model->loanaccount_id);?>" class="btn btn-info"><i class="fa fa-arrow-left"> Previous</i></a>
				      </div>
				    </div>
			    	<div class="col-md-3 col-lg-3 col-sm-12">
		       		 <div class="form-group">
								<input type="submit" class="btn btn-primary pull-right" value="Submit Comment" name="loan_comment_cmd">
							</div>
						</div>
				</div>
			</form><br/>
    </div>
  </div>
  </div>
</div>
