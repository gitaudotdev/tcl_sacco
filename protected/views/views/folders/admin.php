<?php
/* @var $this FoldersController */
/* @var $model Folders */
$this->breadcrumbs=array(
	'Folders'=>array('admin'),
	'Manage'=>array('admin'),
);

//realpath($file['relativePath'])
/**Flash Messages**/
$successType = 'success';
$succesStatus = CommonFunctions::checkIfFlashMessageSet($successType);
$infoType = 'info';
$infoStatus = CommonFunctions::checkIfFlashMessageSet($infoType);
$warningType = 'warning';
$warningStatus = CommonFunctions::checkIfFlashMessageSet($warningType);
$dangerType = 'danger';
$dangerStatus = CommonFunctions::checkIfFlashMessageSet($dangerType);
$i=1;
?>
<div class="row">
  <div class="col-md-12 col-lg-12 col-sm-12">
    <div class="card">
        <div class="card-header">
            <div class="col-lg-12 col-md-12 col-sm-12">
            	<h5 class="title">
            			File Vault
	            		<?php if(($listing->enableDirectoryCreation) && (Navigation::checkIfAuthorized(216)===1)):?>
		            		<span class="push_little">
		            			<a data-toggle="modal" data-target="#folderModal" class="hollow_danger_btn">
		            				<i class="fa fa-plus"></i> Folder</a>
		            		</span>
		            	<?php endif;?>
									<?php if(($data['enableUploads']) && (Navigation::checkIfAuthorized(215)===1)):?>
										<span class="pull-right">
		            			<a data-toggle="modal" data-target="#filesModal" class="hollow_info_btn">
		            				<i class="fa fa-upload"></i> File</a>
		            		</span>
									<?php endif;?>
	            </h5>
            	<hr>
            	<?php if(!empty($data['directoryTree'])):?>
							<div class="row">
								<div class="col-sm-12 col-lg-12 col-md-12">
									<ul class="breadcrumb">
									<?php foreach ($data['directoryTree'] as $url => $name): ?>
										<li>
											<?php
											$lastItem = end($data['directoryTree']);
											if($name === $lastItem):
												echo strtoupper($name);
											else:
											?>
											<a href="?dir=<?=$url; ?>">
												<?=strtoupper($name);?>
											</a>
											<?php
											endif;
											?>
										</li>
									<?php endforeach; ?>
									</ul>
								</div>
							</div>
						<?php endif; ?>
	          </div>
            <?php if($succesStatus === 1):?>
				    <div class="col-lg-12 col-md-12 col-sm-12">
				      <?=CommonFunctions::displayFlashMessage($successType);?>
				    </div>
				    <?php endif;?>
				    <?php if($infoStatus === 1):?>
				      <div class="col-lg-12 col-md-12 col-sm-12">
				        <?=CommonFunctions::displayFlashMessage($infoType);?>
				      </div>
				    <?php endif;?>
				    <?php if($warningStatus === 1):?>
				      <div class="col-lg-12 col-md-12 col-sm-12">
				        <?=CommonFunctions::displayFlashMessage($warningType);?>
				      </div>
				    <?php endif;?>
				    <?php if($dangerStatus === 1):?>
				      <div class="col-lg-12 col-md-12 col-sm-12">
				        <?=CommonFunctions::displayFlashMessage($dangerType);?>
				      </div>
				    <?php endif;?>
		        </div>
        <div class="card-body">
        	<div class="col-md-12 col-lg-12 col-sm-12">
					<?php if (!empty($data['directories'])):?>
							<h4 class="title">Folders</h4>
							<hr class="special_rule">
							<?php foreach ($data['directories'] as $directory):?>
								<div class="col-md-3 col-lg-3 col-sm-12 folder_container">
										<center>
											<a href="<?=$directory['url']; ?>" class="item dir">
												<?=strtoupper($directory['name']);?>
											</a>
										</center>
										<?php if(($listing->enableDirectoryDeletion) && (Navigation::checkIfAuthorized(214)===1)):?>
											<hr>
											<center>
												<a href="?dir=<?=urlencode($directory['path']);?>&delete=true" onclick="return confirm('Are you sure?')"><i class="fa fa-trash-o fa-2x control_delete"></i></a>
											</center>
										<?php endif; ?>
								</div>
							<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<?php if (! empty($data['files'])): ?>
					<div class="col-sm-12 col-lg-12 col-md-12">
					<h4 class="title">Files</h4>
					<hr class="special_rule">
						<div class="table-container">
							<table class="table table-condensed">
								<thead>
									<tr>
										<th>#</th>
										<th class="xs-hidden">
											<a href="<?=$listing->sortUrl('name');?>">File <span class="<?=$listing->sortClass('name');?>"></span></a>
										</th>
										<th class="sm-hidden">
											<a href="<?=$listing->sortUrl('uploadedBy');?>">Uploaded By <span class="<?=$listing->sortClass('uploadedBy');?>"></span></a>
										</th>
										<th class="xs-hidden">
											<a href="<?=$listing->sortUrl('size');?>">Size <span class="<?=$listing->sortClass('size');?>"></span></a>
										</th>
										<th class="sm-hidden">
											<a href="<?=$listing->sortUrl('modified');?>">Last Modified <span class="<?=$listing->sortClass('modified');?>"></span></a>
										</th>
										<th class="xs-hidden">
											Actions
										</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach ($data['files'] as $file): ?>
									<?php
									$downloadLink=Yii::app()->params['home'].strstr($file['path'], '/HRM');
									if(Navigation::checkIfAuthorized(213)===1){
										$exportLink="<a href='$downloadLink' class='btn btn-success btn-sm' target='_blank'> <i class='fa fa-download'></i></a>";
									}else{
										$exportLink="";
									}
									if(Navigation::checkIfAuthorized(212)===1){
										$viewLink="<a href='#' class='btn btn-info btn-sm' onclick='loadFile(\"".$file['name']."\",\"".$downloadLink."\")'> <i class='fa fa-eye'></i></a>";
									}else{
										$viewLink="";
									}
									?>
									<tr>
										<td>
											<?=$i;?>
										</td>
										<td>
											<div class="text-wrap width-200">
												<a href="#" class="not-active item _blank <?=$file['extension'];?>">
												 <?=$file['name'];?>
												</a>
											</div>
										</td>
										<td><?=$listing->getUploadedByName($file['name']);?></td>
										<td class="xs-hidden"><?=$file['size']; ?></td>
										<td class="sm-hidden"><?=date('jS M Y', $file['modified']); ?></td>
										<td>
											<?=$exportLink;?>&emsp;<?=$viewLink;?>
											<?php if(($listing->enableFileDeletion) && (Navigation::checkIfAuthorized(214)===1)):?>
													<a class='btn btn-danger btn-sm' href="?deleteFile=<?=urlencode($file['path']);?>" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i></a>
											<?php endif;?>
										</td>
									</tr>
									<?php $i++;?>
								<?php endforeach; ?>
								</tbody>
							</table>
							<br><br>
						</div>
					</div>
			<?php endif;?>
  </div>
</div>

<!-- NEW FOLDER MODAL -->
<div class="modal fade" id="filesModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:45% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header justify-content-center" style="padding:3% !important;">
        <h4 class="title">
        	Upload  File
      	</h4>
      </div>
      <div class="col-sm-12 col-lg-12 col-md-12">
      	<br>
						<form action="" method="post" enctype="multipart/form-data" class="text-center upload-form form-vertical">
							<div class="row upload-field">
								<div class="col-sm-12 col-lg-12 col-md-12">
									<br>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-12 col-md-12 col-lg-12">
												<input type="file" name="upload[]" id="upload" class="inputfile">
												<label for="upload"><strong><i class="fa fa-upload"></i> Choose a File</strong></label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-sm-offset-3">
									<button type="submit" class="btn btn-primary" name="submit">Upload File</button>
								</div>
							</div>
						</form>
				</div>
    </div>
  </div>
</div>
<!-- END MODAL-->


<!-- NEW FOLDER MODAL -->
<div class="modal fade" id="folderModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:50% !important;">
    <div class="modal-content" style="text-align: left;">
      <div class="modal-header justify-content-center" style="padding:3% !important;">
        <h4 class="title">
        	Create Folder
      	</h4>
      </div>
      <form method="post" action="">
	      <div class="modal-body">
      		<br>
	      	<div class="col-lg-12 col-md-12 col-sm-12">
	      		<div class="form-group">
							 <input type="text" name="directory" id="directory" class="form-control" placeholder="Folder Name">
						</div>
	      	</div>
	      	<br><br>
	      </div>
	      <div class="modal-footer">
	      	<button type="submit" class="btn btn-primary" name="submit">Create Folder</button>
	        <button type="button" class="btn btn-default" data-dismiss="modal">
	        Cancel</button>
	      </div>
	    </div>
	  </form>
    </div>
  </div>
</div>
<!-- END MODAL-->

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
	function loadFile(filename,filepath){
		var extension=getFileExtension(filename);
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