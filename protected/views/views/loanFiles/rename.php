<?php
$this->pageTitle=Yii::app()->name . ' - Microfinance : Rename Uploaded Loan File';
$this->breadcrumbs=array(
	'Loanaccount'=>array('loanaccounts/'.$model->loanaccount_id),
	'File'=>array('loanFiles/rename/'.$model->id),
  'Rename'=>array('loanFiles/rename/'.$model->id),
);
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header col-md-12 col-lg-12 col-sm-12">
           <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="col-md-6 col-lg-6 col-sm-12">
              <h5 class="title">Rename Uploaded File</h5>
            </div>
            <?php
              $previewAction="<a href='#' class='btn btn-info btn-sm' onclick='loadFile(\"".$model->filename."\")' style='border-radius:65% !important;margin-top:-1.5% !important;'> <i class='fa fa-eye'></i></a>";
            ?>
            <div class="col-md-6 col-lg-6 col-sm-12">
              <div class="pull-right"><?=$previewAction;?></div>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12">
              <hr>
            </div>
          </div>
        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
	        	<form method="POST">
              <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                  <div class="form-group">
                    <label >File Name</label>
                    <input type="text" name="new_file_name" value="<?=$model->name;?>" required="required"
                     class="form-control"/>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="rename_file_cmd" value="Rename">
                  </div>
                </div>
                <div class="col-md-3 col-lg-3 col-sm-12">
                  <div class="form-group pull-right">
                    <a href="<?=Yii::app()->createUrl('loanaccounts/'.$model->loanaccount_id);?>" class="btn btn-default">Cancel</a>
                  </div>
                </div>
              </div>
            </form>
	        </div>
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
<script type="text/javascript">
  function loadFile(filename){
    var extension=getFileExtension(filename);
    var filepath="<?=Yii::app()->params['homeDocs'].'/loans/files/';?>"+filename;
    switch(extension.toLowerCase()){
      case 'doc':
      var content='<iframe src="https://docs.google.com/viewerng/viewer?url='+filepath+'" style="overflow:scroll !important;width:100% !important;height:100vh !important;"></iframe>';
      LoadRespectiveFile(content);
      break;

      case 'docx':
      var content='<iframe src="https://docs.google.com/viewerng/viewer?url='+filepath+'" style="overflow:scroll !important;width:100% !important;height:100vh !important;"></iframe>';
      LoadRespectiveFile(content);
      break;

      case 'pdf':
      var content='<object data="'+filepath+'" type="application/pdf" style="overflow:scroll !important;width:100% !important;height:100vh !important;"><a href="'+filepath+'">'+filepath+'</a></object>';
      LoadRespectiveFile(content);
      break;

      default:
      var content='<strong>'+filename+'</strong><hr><br><img src="'+filepath+'" width="900" alt="'+filename+'"/>';
      LoadRespectiveFile(content);
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
</script>