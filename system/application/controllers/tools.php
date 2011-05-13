<?php 

require_once BASE_CONTROLLERS_PATH.'AdminBaseController'.EXT;

class Tools extends AdminBaseController {
	
	public function generateModel() {
		
		define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'].'/system/application/models/');
		define('SCHEMA_PATH', $_SERVER['DOCUMENT_ROOT'].'/tmp/model.schema');
		require_once(MODEL_PATH.'System/qDb.php');
		
		$mname = $tname = $pname = null;
		
		foreach ($_POST as $key => $val){
			$$key = $val;
		}
		
		$error		= new stdClass();
		$error->m	= '';
		
		$created_model = array();

		if ($mname || $tname || $pname){
			try{
								
				if (!$mname || !$tname || !$pname) 
					throw new Exception('Please fill all fields!');
				
				$schema = file_get_contents(SCHEMA_PATH);
				if (!$schema)
					throw new Exception('Schema not found!');
					
				$table = DB::featchResult(DB::query("DESC  $tname"));
				
				if (!$table)
					throw new Exception('Table not exists!');
					
				$modelname	= ucfirst(strtolower($mname)).'Model';
				$modelfile	= MODEL_PATH.$modelname.'.php';
					
				if (is_file($modelfile))
					throw new Exception('Model already exists!');
					
				$props = '';
				foreach ($table as $field){
					$props	.= '    	$this->properties->'.$field->Field.'				=\''.($field->Default && $field->Default!='CURRENT_TIMESTAMP' ? $field->Default : '').'\';'."\n";
				}
				
				
				
				$model = str_replace(array(
											'[mname]',
											'[tname]',
											'[pname]',
											'[props]'
									),array(
											$modelname,
											$tname,
											$pname,
											$props		
									),$schema
				);
				
				if (!file_put_contents($modelfile,$model))
					throw new Exception('Can`t write model fie!');
				else
				{
					chmod($modelfile, 0777);
					$created_model['name'] = $modelname;
					$created_model['file'] = $modelfile;
				} 
				
				
			}catch (Exception $e){	
				$error->m	= $e->getMessage();				
			}
		}
		
		$view = array(
			'error'		=> $error,
			'model'		=> $created_model
		);
		
		
		View::showChild($this->viewpath.'pages/generate_model', $view);
	} 
	
	
	public function testMail(){
		var_dump(mail('omni.dev@ya.ru', 'ghghghgh', 'message'));
	}
	
	
}
?>