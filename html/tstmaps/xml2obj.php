<?
/*
 * xml2object.php
 *
 * Class to convert an XML file into generic object
 *
 * Copyright (C) 2003,2004  Victor Anaya <hide@address.com>
 *
 *   This library is free software; you can redistribute it and/or
 *   modify it under the terms of the GNU Lesser General Public
 *   License as published by the Free Software Foundation; either
 *   version 2 of the License, or (at your option) any later version.
 *
 *   This library is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *   Lesser General Public License for more details.
 *
 *   You should have received a copy of the GNU Lesser General Public
 *   License along with this library; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307  USA
 */
 
/*
 *  Generic object class
 */
	class Object{};

/*
 *  Class to manage a list of items
 */
	class Lista{
		var $total;
		var $items;
		
		function Lista(){
			$this->total = 0;
		}
		
		function add($xItem){
			$this->items[$this->total++] = $xItem;
		}
	};


/*
 *  A simple class to handle a stack (LIFO) data structure
 */
	class Stack{
		var $stack = array();
		
		function push($data) {
			array_push($this->stack,$data);
		}
		
		function pop() {
			if(count($this->stack) == 0) {
				die("Error: Buffer Underflow!");
			}
			return array_pop($this->stack);
		}
		
		function concat_obj($parent = 0){
			if(count($this->stack)) {
				$count = 0;
				foreach($this->stack as $nombre){
					if($count++ - count($this->stack)== $parent)	break;
						$aux.="->{$nombre}";
				}
				return($aux);
			}
		}
	}

/**
 *
 * Object result structure
 * structure:
 *  $obj->element                 		-> contain text inside the tag 'element'
 *  $obj->element->attrs[attribname]   	-> tag's attribute
 *  $obj->element->items              	-> generic object array , contain's sub-items of a objects list
 *
 * ¡¡¡ ATTENTION, VERY IMPORTANT!!!
 *  The structure of file XML must contain:
 * 	- in the main labels:  "tipo=objeto"
 * 	- in the labels that contain of lists:  "tipo=lista"
 *  example:
 *  <FAMILY tipo="objeto">
 *  <ADDRESS>Av. Principal 1542</ADDRESS>
 *  <PHONE>562 34567</PHONE>
 *  <MEMBERS tipo="lista">
 *  	<MEMBER tipo="objeto"><NAME>Juan Perez</NAME></MEMBER>
 *  	<MEMBER tipo="objeto"><NAME>Liz Quispe</NAME></MEMBER>
 *  	<MEMBER tipo="objeto"><NAME>Juan Perez Jr.</NAME></MEMBER>
 *  	<MEMBER tipo="objeto"><NAME>Rosa Perez</NAME></MEMBER>
 *  </MEMBERS>
 *  </FAMILY>
 */
 class XML2OBJECT {
	var $parser;
	var $pila;
	var $obj;
	
	function XML2OBJECT() {
		$this->parser = xml_parser_create("ISO-8859-1");
		xml_set_object($this->parser,$this);
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, true);
		xml_set_element_handler($this->parser, "tag_open", "tag_close");
		xml_set_character_data_handler($this->parser, "cdata");
		xml_set_default_handler($this->parser, "cdata");
		$this->pila = new Stack();
		$this->obj = new Object();
	}

	function tag_open($parser, $name, $attrs) {
		$aux = NULL;
		if(count($attrs)){
			switch($attrs["TIPO"]){
			case "objeto":
				eval("\$aux = get_class(\$this->obj".$this->pila->concat_obj().");");
				switch($aux){
				case "lista":
					unset($xItem);
					$xItem = new Object();
					eval("\$this->obj".$this->pila->concat_obj()."->add(\$xItem);");
					eval("\$xTotal = \$this->obj".$this->pila->concat_obj()."->total;");
					$name = "items[".($xTotal - 1)."]";
					break;
				case "object":
					eval("\$this->obj".$this->pila->concat_obj()."->{$name} = new Object();");
					break;
				}
				break;
			case "lista":
				eval("\$this->obj".$this->pila->concat_obj()."->{$name} = new Lista();");
				break;
			default:
				eval("\$this->obj".$this->pila->concat_obj()." = '';");
			}
		}
		$this->pila->push($name);
		if(count($attrs) > 1)
			eval("\$this->obj".$this->pila->concat_obj()."->attrs = \$attrs;");
	}

	function cdata($parser,$string) {
		$string = addslashes(trim($string));
		if($string && $this->pila->concat_obj(-1))
			eval("\$this->obj".$this->pila->concat_obj()." = \"{$string}\";");
	}

	function tag_close($parser,$name) {
		$this->pila->pop();
	}

	function parse($data){
		xml_parse($this->parser, $data);
		xml_parser_free($this->parser);
		return($this->obj);
	}
};
?>
