<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance : View Expenditure Details';
$this->breadcrumbs=array(
	'Expenditure'=>array('expenses/admin'),
	'Details'=>array('expenses/'.$model->expense_id)
);
?>
<style type="text/css">
	table{
		margin:3% 0% 7% 0% !important;
	}
</style>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
        	<div class="col-md-12 col-lg-12 col-sm-12">
            <h5 class="title">Expenditure Details</h5>
            <hr>
          </div>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
	          <div class="col-md-6 col-lg-6 col-sm-12">
	          	<table class="table table-condensed table-striped">
	          		<tr>
	          			<td>Branch</td>
	          			<td><?=$model->getExpenseBranchName();?></td>
	          		</tr>
	          		<tr>
	          			<td>Staff</td>
	          			<td><?=$model->getStaffName();?></td>
	          		</tr>
	          		<tr>
	          			<td>Name</td>
	          			<td><div class="text-wrap width-200"><?=$model->name;?></div></td>
	          		</tr>
	          	</table>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
	          	<table class="table table-condensed table-striped">
	          		<tr>
	          			<td>Type</td>
	          			<td><?=$model->getExpenseTypeName();?></td>
	          		</tr>
	          		<tr>
	          			<td>Date</td>
	          			<td><?=$model->getExpenseDate();?></td>
	          		</tr>
	          		<tr>
	          			<td>Amount</td>
	          			<td><?=$model->getExpenseAmount();?></td>
	          		</tr>
	          	</table>
	          </div>
	        </div>
	        <div class="col-md-12 col-lg-12 col-sm-12">
              <h4 class="title"> Expenditure Receipts </h4>
              <hr>
              <div class="row justify-content-center">
                <?php if(Yii::app()->user->user_level !== '3'):?>
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                      <a href="#" class="btn btn-success" onclick="LoadAddFile()">Add Receipt</a>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12">
            					<a href="<?=Yii::app()->createUrl('expenses/admin');?>" class="btn btn-default pull-right">Go Back</a>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12">
                	<hr>
                </div>
                <?php else:?>
                <div class="col-md-12 col-lg-12 col-sm-12">
                	<hr>
                </div>
                <?php endif;?>
                <div class="col-md-12 col-lg-12 col-sm-12">
                  <?php if( $files != 0):?>
                  <table class="table table-condensed table-striped">
                    <thead>
                      <th>#</th>
                      <th>Receipt Name</th>
                      <th>Uploaded By</th>
                      <th>Date Uploaded</th>
                      <th>Receipt Actions</th>
                    </thead>
                    <tbody>
                      <?php $i=1;?>
                      <?php foreach($files as $file):?>
                        <?php
                        $PDF_Export_Link=Yii::app()->params['homeDocs'].'/expenses/'.$file->filename;
                        $exportLink="<a href='$PDF_Export_Link' class='btn btn-info'> <i class='fa fa-file-o'></i> DOWNLOAD</a>";
                        $viewLink="<a href='#' class='btn btn-warning' onclick='loadFile(\"".$file->filename."\")'> <i class='fa fa-file-o'></i> View</a>";
                        ?>
                        <tr>
                          <td><?=$i;?></td>
                          <td><?=$file->filename;?></td>
                          <td><?=$file->getUploadeBy();?></td>
                          <td><?=$file->getDateUploaded();?></td>
                          <td><?=$viewLink;?>&emsp;<?=$exportLink;?></td>
                        </tr>
                        <?php $i++;?>
                      <?php endforeach;?>
                    </tbody>
                  </table>
                 <?php else:?>
                    <br>
                    <h4 style="color:red !important;margin-bottom: 5% !important;">*** NO RECEIPTS UPLOADED ***</h4>
                 <?php endif;?>
                </div>
              </div>
     </div>
</div>
<!-- FILE VIEW MODAL -->
    <div class="modal fade" id="loadingFile" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width:100% !important; height: auto!important;">
        <div class="modal-content">
          <div class="modal-body">
            <div id="loadedFile"></div>
          </div>
        </div>
        </div>
      </div>
    </div>
<!-- END MODAL -->
<!-- ADDING FILE VIEW MODAL -->
      <div class="modal fade" id="addFile" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="width:60% !important; height: auto!important;">
          <div class="modal-content">
            <div class="modal-body">
               <h4 style="font-weight: bold;">Upload Expenditure Receipt</h4>
              <hr>
              <form method="post" enctype='multipart/form-data' action="<?=Yii::app()->createUrl('expenses/uploadReceipt/'.$model->expense_id);?>">
              <br>
              <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <?= CHtml::fileField('filename');?>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                  <input type="submit" name="upload_file_cmd" value="Upload Receipt" class="btn btn-primary">
                </div>
              </div>
              <br>
            </form>
            </div>
          </div>
          </div>
        </div>
      </div>
      <!-- END MODAL -->
<script type="text/javascript">

  function loadFile(filename){
    var extension=getFileExtension(filename);
    var filepath="<?=Yii::app()->params['homeDocs'].'/expenses/';?>"+filename;
    switch(extension.toLowerCase()){
      case 'doc':
      var content='<iframe src="https://docs.google.com/viewerng/viewer?url='+filepath+'" style="overflow:scroll !important;width:100% !important;height:100vh !important;"></iframe>';
      LoadRespectiveFile(content)
      break;

      case 'docx':
      var content='<iframe src="https://docs.google.com/viewerng/viewer?url='+filepath+'" style="overflow:scroll !important;width:100% !important;height:100vh !important;"></iframe>';
      LoadRespectiveFile(content)
      break;

      case 'pdf':
      var content='<object data="'+filepath+'" type="application/pdf" style="overflow:scroll !important;width:100% !important;height:100vh !important;"><a href="'+filepath+'">'+filepath+'</a></object>';
      LoadRespectiveFile(content)
      break;

      default:
      var content='<strong>'+filename+'</strong><hr><br><img src="'+filepath+'" width="900" alt="'+filename+'"/>';
      LoadRespectiveFile(content)
      break;

    }
  }

  function getFileExtension(filename){
    var parts = filename.split('.');
    return parts[parts.length - 1];
  }

  function LoadRespectiveFile(content){
    $('#loadingFile').modal({show:true});
    $('#loadedFile').html(content).show().fadeIn('slow');
  }

  function LoadAddFile(){
    $('#addFile').modal({show:true});
  }
</script>