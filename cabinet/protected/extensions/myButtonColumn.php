<?php

// unset array[x] - brise element polja

class myButtonColumn extends CButtonColumn
{
	public $viewButtonVisible;
	public $updateButtonVisible;
	public $deleteButtonVisible;

	/**
	 * Initializes the column.
	 * This method registers necessary client script for the button column.
	 * @param CGridView the grid view instance
	 */
	public function init()
	{
		parent::init();

		if(isset($this->buttons['view']) && isset($this->viewButtonVisible))
			$this->buttons['view']['visible']=$this->viewButtonVisible;
		if(isset($this->buttons['update']) && isset($this->updateButtonVisible))
			$this->buttons['update']['visible']=$this->updateButtonVisible;
		if(isset($this->buttons['delete']) && isset($this->deleteButtonVisible))
			$this->buttons['delete']['visible']=$this->deleteButtonVisible;
	}


	protected function renderButton($id,$button,$row,$data)
	{

		if(!isset($button['visible']) || $button['visible']===null)
		{
			parent::renderButton($id,$button,$row,$data);
		}
		else
		{
			if(is_string($button['visible']))
				$button['visible']=$this->evaluateExpression($button['visible'],array('data'=>$data,'row'=>$row));

			if($button['visible'])
				parent::renderButton($id,$button,$row,$data);
		}
	}
}
