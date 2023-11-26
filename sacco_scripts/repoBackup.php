REMOVE THE # BELOW IN LINE 3 FOR THIS FILE TO WORK

#<?php

include_once('config.php');

function backupDatabase(){
	$conn=SaccoDB();
	$charsetSql="SET NAMES 'utf8'";
	$conn->query($charsetSql);
	$tables=getDatabaseTables($conn);
	if($tables === 0){
		echo "No Tables To Back Up. \n";
	}else{
		$backup=backupDatabaseTables($tables,$conn);
		$filename=saveDatabaseBackupFile($tables,$backup);
		createBackupRecord($filename,$conn);
	}
}

function getDatabaseTables($conn){
	$relations=array();
	$tableSql="SHOW TABLES";
	$tables=$conn->query($tableSql);
	if(!empty($tables)){
		while($row = $tables->fetch_row()){
			$relations[] = $row[0];
		}
	}else{
		$relations=0;
	}
	return $relations;
}

function backupDatabaseTables($tables,$conn){
	$backup = '';
  //cycle through
  foreach($tables as $table){
  		$fetchTable=$conn->query('SELECT * FROM '.$table);
      $totalTableFields = mysqli_num_fields($fetchTable);
      $totalTableRows = $fetchTable->num_rows;

      $backup.= 'DROP TABLE IF EXISTS '.$table.';';
      $createTable=$conn->query('SHOW CREATE TABLE '.$table);
      $createdRow=$createTable->fetch_row();
      $backup.= "\n\n".$createdRow[1].";\n\n";
      $counter = 1;
      //Over tables
      for($i = 0; $i < $totalTableFields; $i++){ 
          while($fetchedRows = $fetchTable->fetch_row()){   
              if($counter == 1){
                  $backup.= 'INSERT INTO '.$table.' VALUES(';
              } else{
                  $backup.= '(';
              }
              //Over fields
              for($j=0; $j<$totalTableFields; $j++){
                $fetchedRows[$j] = addslashes($fetchedRows[$j]);
                $fetchedRows[$j] = str_replace("\n","\\n",$fetchedRows[$j]);
                if(isset($fetchedRows[$j])){ 
                	$backup.= '"'.$fetchedRows[$j].'"' ;
                }else{
                 $backup.= '""';
                }
                if($j<($totalTableFields-1)){ 
                	$backup.= ','; 
                }
              }
              if($totalTableRows == $counter){
                $backup.= ");\n";
              }else{
                $backup.= "),\n";
              }
            $counter++;
          }
      }
      $backup.="\n\n\n";
  }
  return $backup;
}

function saveDatabaseBackupFile($tables,$backup){
	$backupFileName='db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql';
  $backupDirectory = '/var/systemRepos/'.$backupFileName;
  $handle = fopen($backupDirectory,'w+');
  fwrite($handle,$backup);
  if(fclose($handle)){
  	return $backupFileName;
  }else{
  	return 0;
  }
}

function createBackupRecord($backedupFile,$conn){
	if($backedupFile === 0){
		echo "No Database Back Up File.\n";
	}else{
		$backupSql="INSERT INTO backups (filename,backedup_by) VALUES('$backedupFile',0)";
		$conn->query($backupSql);
		echo "Backup Recorded Successfully!\n";
	}
}
/********************************
Invoke Main Method
************************************/
backupDatabase();