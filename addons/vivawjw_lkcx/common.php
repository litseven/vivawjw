<?php
function roadareaCache(){

	$roadareaCache = cache_load('roadareaCache');
	//cache_delete('roadareaCache');	
	if(!$roadareaCache || TIMESTAMP-$roadareaCache['creattime']>7200){
		
				$roadareaCache['creattime'] = TIMESTAMP;
				cache_write('roadareaCache',$roadareaCache);
		}
	return $roadareaCache;
	}



?>