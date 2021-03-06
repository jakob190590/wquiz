<?php
/*** 
Handles the overall quiz setups for multiple quizzes
eg. Menu page for quizzes available
***/

/** Copyright Information (GPL 3)
Copyright Stewart Watkiss 2012

This file is part of wQuiz.

wQuiz is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

wQuiz is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with wQuiz.  If not, see <http://www.gnu.org/licenses/>.
**/

require_once ($include_dir."Quiz.php");



class Quizzes
{
	// These are all named the same as the table fields
	// defined as static as when we export the object we need
	// to be able to use it outside of this class without risk of garbage collection
	private static $quiz_objects;
    

	// empty constructor - we add objects afterwards
    public function __construct () 
    {


    }
    
    
    public function count ()
    {
    	if (empty($this->quiz_objects)) {return 0;}
    	return (count($this->quiz_objects));	
    }
    

    public function addQuiz ($quiz) 
    {
    	$this->quiz_objects[] = $quiz;
    }
    
    // use to order objects - if required (eg menu)
    private function _sort()
    {
    	// if no quizzes return
    	if ($this->count() == 0) {return;}
    	usort ($this->quiz_objects, array("Quiz", "cmpObj"));
    	//usort ($this->quiz_objects, "cmpObj");
    }
    
	// returns a quiz object so that it can be accessed directly through Quiz class
	public function getQuiz ($quizname)
	{
    	// run through all questions and look for quizname matching
    	foreach ($this->quiz_objects as $this_object)
    	{
    		if ($this_object->getQuizname() == $quizname) {return $this_object;}
    	}
	}
	
	// Returns hash array
	// key = quizname (db unique entry)
	// value = title
	public function getQuizNameArray()
	{
		$return_array = array();
		// if empty then just return null array
		if ($this->count() == 0) {return $return_array;}
		// sort first
		$this->_sort();
		
		foreach ($this->quiz_objects as $this_object)
    	{
    		$return_array[$this_object->getQuizname()] = $this_object->getTitle();
    	}
    	return $return_array;
	}


   
    public function validateQuizname ($quizname)
    {
    	// if no quizzes defined then return false
    	if (empty ($this->quiz_objects)) {return false;}
    	// run through all quizzes and look for quizname matching
    	foreach ($this->quiz_objects as $this_object)
    	{
    		if ($this_object->getQuizname() == $quizname) {return true;}
    	}
    	// reach here then we haven't found the entry
    	return false;
    }

    // returns an option list
    // parameter required is "online", "offline", "any" or "all"
    // note that all includes any that are disabled for both online and offline
	public function htmlSelect ($use)
	{
		$return_string = '<select id="'.CSS_ID_OPTION_QUIZ.'" name="quizname">'."\n";
		// sort first
		$this->_sort();
		// includes formatting of table input fields - but no other html
		foreach ($this->quiz_objects as $this_object)
		{
			if (!$this_object->isEnabled($use)) {continue;}
			$return_string.="\t<option value=\"".$this_object->getQuizname()."\">".$this_object->getTitle()."</option>\n";
		}
		$return_string .= "</select>\n";
		return ($return_string);
	}
    
    
}
?>
