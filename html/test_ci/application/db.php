<?php

//TODO: pseudo fields
class Offers_fetch
{
	private $searchConditions=array();


	#encription map tables
	private $encTableMap=array();
	private $encTableMapR=array();
	private $encFieldMap=array();
	private $encFieldMapR=array();

	
	private $key="sampleKey";
	
	private $namesCount=0;
	
	private $srMaps=array
	(
		"t_fieldS" => array(),
		"t_fieldR" => array(),
		"tblS"     => array(),
		"tblR"     => array()
	);

	#condition stack
	private $conds=array();
	
	#from stack
	private $fromStack="";

	#selection stack
	private $wantedF=array();
	
	private $limitQuery=array
	(
		"from"  => NULL,
		"to" => NULL
	);
	
	private $orderQuery=array
	(
		"field"  => NULL,
		"method" => NULL
	);

	private $encryption=false;

	private $seed=11242;


	public function dsp($str)
	{
		$result=0;
		for($i=0;$i<strlen($str);$i++) $result.='&#'.ord($str{$i}).";";
		return $result."\n";
	}

	
	#######################################################################
	#######BUILD_THE_SQL_QUERY_AND_RETURN_IT###############################
	public function getQuery()
	{
		$q="SELECT ".$this->getSelectedQuery()." FROM ".$this->getFromQuery()." WHERE ".$this->getWhereQuery().$this->getOrderQuery().$this->getLimitQuery();
		return $q;
	}
	

	#######################################################################
	###GENERATES_SQL_QUERY_CREATING_TABLES_IN_DB###########################
	public function createTables($tablesMap)
	{
		$q='';
                $mails=false;
	        $tNames=$this->getTableNames();	
		foreach($tNames as $tableName)
		{
			$primary=NULL;
			$unique=NULL;
			$index=NULL;
			$fulltext=NULL;
			
			$q.="CREATE TABLE `".$this->encode($tableName, "tbl")."`(\n";
			$unique="";

			$fields=$this->getFields($tableName);			

			foreach($fields as $fieldName => $props)
			{
				#NULL-NOT NULL
				$nullSql=" NOT NULL ";
				if(isset($props["null"])) $nullSql=$props["null"]?" NULL ":$nullSql;

				#DEFAULT
				$default="";
				if(isset($props["default"]))
				{
					$default=" DEFAULT(".
					(($props["fType"]=="varchar")?"'".$props["default"]."'":$props["default"]).
					")";
				};
				
				#COLLATION TODO: sql syntax (default charset)
				$collation=isset($props["collation"])?" ".$props["collation"]:"";

				$q.= "`" . $this->encode($fieldName, "fld", $tableName) . "`" ;
				if($props["fType"]=="int")
				{
					$q.=" int(".$props["size"].") ";
				}
				else if($props["fType"]=="varchar")
				{
					#echo "<br> encoding aaaaa($tableName.$fieldName) => "."--".
					#$this->dsp($this->encode("a", $props, $tableName, $fieldName))."--";
					$q.=" varchar(".$props["size"].") ";
				}
                                else if($props["fType"]=="email")
                                {
                                        //$q.="varchar(".$props["size"]."), ";
                                        //$q.='`'.$this->encode($fieldName."P", "fld", $tableName).'` ';
                                        //$q.="int(4),";
                                        //$q.='`'.$this->encode($fieldName."D", "fld", $tableName).'` ';
                                        //$q.="int(4)";
                                        //$mails=true;
                                }
				//TODO:...
				else
				{
					//TODO: can't happen
				};


				$q.=$nullSql.$collation.$default;

				if(array_key_exists("index", $props))
				{
					$unique.=($props["index"]=="unique")?
						" UNIQUE KEY (".$this->encode($fieldName, "fld", $tableName)."),\n ":
						"";
					$primary.=($props["index"]=="primary")?$this->encode($fieldName, "fld", $tableName):"";
				};
				$q.=",\n";
			};


			$q.=$unique;
			$q.=" PRIMARY KEY ($primary) ";

			#$q=rtrim($q, ",\n");
			$q.="\n) ENGINE=InnoDB DEFAULT CHARSET=utf8
                        COLLATE=utf8_bin AUTO_INCREMENT=1 ;\n\n\n\n\n";
		}
		echo preg_replace('/\n/', "\n <br /> \n", $q);
		//$this->encode($this->tablesMap[""]);
		#var_dump($this->tablesMap);
		#$this->want('offers.price:price,photo.path:path');
		#echo $this->getSelecteQuery();
		#$this->whereQuery("offers", "id", "8:25");
		#$this->whereQuery("offers", "id", "8:25");
		#$this->whereQuery("agencies", "id", 8);
		#echo "<hr>".$this->getCondsQuery();


		//$this->select("offers.id:id,photo.path:img");
		$this->select="offers.id:id,photo.path:img";
		$this->from("offers left join photo on offers.id=photo.offer");
		$this->where("offers", "id", "0:20");
		$this->where("photo", "path", "asdfa");
		$this->order("offers.id");
		$this->limit('0:5');
		//$this->encryption=true;
		//var_dump($this->encFieldMapR);
		echo $this->getQuery();
	}
	


	#######################################################################
	###########ADDS_ANOTHER_CONDITION_TO_THE_CONDITIONS_STACK#############
	public function where($tbl, $fld, $val, $operator=NULL)
	{
		if(!$this->table_exists($tbl)||!$this->field_exists($fld, $tbl)) echo "er";#TODO: handle

		$t="";
		$q="";
		$props=$this->getFieldProps($fld, $tbl);

		if($operator==NULL)
		{
			$operator="=";
		};

		#TODO: begin and end of string in regexp
		#TODO: negative numbers
		#########################################################
		if($props["fType"]=="int"&&(is_string($val)||is_int($val)))
		{
			if(is_int($val))
			{
				#TODO: nothing, if it's already int?
				#break;
			}
			else if(is_string($val))
			{
				if(preg_match('/\A[\-]?[0-9]*\z/', $val))
				{
					#hust base 10 number in a string variable
					$val=intval($val);
				}
				else if(preg_match('/\A([\-]?[0-9]*)[\:]+([\-]?[0-9]*)\z/', $val, $matches))
				{
					#form of: "number:number" - searches betweent the two numbers
					#can be "1:5"-between 1 and 5; ":-7"-between 0 and -7
					$v1=intval($matches[1]);
					$v2=intval($matches[2]);
					$operator=" BETWEEN ";
					$val="$v1 AND $v2 ";
				}
				else if(preg_match('/\A([\<\>\!]+[\=]?)([\-]?[0-9]+)\z/', $val, $matches))
				{
					#example $val: "<5", ">=7", "!=-2"
					$val=intval($matches[2]);
					$operator=$matches[1];
				}
			}
			else
			{
				die("er.... must be string or int");//TODO: handle
			};
		}
		else if($props["fType"]=="varchar")
		{
			$q="'";
			if($this->encryption)
			{
				$val=$this->encode($val, $props, $tbl, $fld);
			}
			else
			{
				$t=$tbl.".";
			};
		}
		else if($props["fType"]=="timestamp")
		{
		
		};

		$this->conds[]="(".$t.$this->encode($fld, "fld", $tbl).$operator.$q.$val.$q.")";
		return $this;
	}


	#######################################################################
	######READS_CONDITIONS_STACK_AND_RETURNS_SQL###########################
	public function getWhereQuery()
	{
		return implode(" AND ", $this->conds);
	}
	
	
	#######################################################################
	#######SET_THE_ORDER_QUERY#############################################
	public function order($terms)
	{
		if($terms==NULL)
		{
			$this->orderQuery["field"]=NULL;
			$this->orderQuery["method"]=NULL;
			return;
		};
		if(preg_match('/\A([a-zA-Z]+)[.]([a-zA-Z]+)([.][ad]+)?\z/', $terms, $matches))
		{
			$this->orderQuery["field"]=$this->encode($matches[2], "fld", $matches[1]);
			if(isset($matches[3]))
				$this->orderQuery["method"]=($matches[3]==".a")?"ASC":"DSC";
		}
		else{die("er.. reformat the order");};//TODO: handle
		return $this;
	}
	
	
	#######################################################################
	#######GET_THE_ORDER_QUERY#############################################
	public function getOrderQuery()
	{
		if($this->orderQuery["field"]!=NULL)
		{
			return " ORDER BY ".$this->orderQuery["field"]." ".$this->orderQuery["method"]." ";
		}
		else{return " ";};
	}

	
	#######################################################################
	#######SET_THE_LIMIT_QUERY#############################################
	public function limit($limStr)
	{
		if(preg_match('/\A([0-9]+)[\:]([0-9]+)\z/', $limStr, $matches))
		{
			$this->limitQuery["from"]=$matches[1];
			$this->limitQuery["to"]=$matches[2];
		}
		else
		{
			die("er.. limit string");//TODO: handle
		};
		return $this;
	}
	
	
	#######################################################################
	#######GET_THE_LIMIT_QUERY#############################################
	public function getLimitQuery()
	{
		if($this->limitQuery["from"]!=NULL)
		{
			return " LIMIT ".$this->limitQuery["from"].", ".$this->limitQuery["to"]." ";
		}
		else{return " ";};
	}
	
	
	#######################################################################
	#########ENCODES_THE_GIVVEN_VALUE_ACCORDING_TO_SEED_AND_PARAMETERS#####
	private function encode($value, $props, $tbl=NULL, $fld=NULL)
	{
		//TODO: specific charsets like cp1251 or utf8
		//TODO: 2 characters for tables and fields
		$seed=$this->seed;
		#$this->encryption=true;
		if(!$this->encryption)
		{
			return $value;
		}
		else
		{
			if($props=="tbl")
			{
				if($this->table_exists($value))
				{
					return $this->getEncodedTableName($value);
				}
				else
				{
					//$i=$this->namesCount;
					$i=$this->namesCount;
					$li=65+$i%52+($i%52>25?6:0);
					$cli=chr($li);
					if($i>=52)
					{
						$li=65+($i-$i%52)/52+(($i-$i%52)/52>25?6:0);
						$cli=chr($li).$cli;
					};
					
					//if(!array_key_exists($cli, $this->encTableMap)&&!array_key_exists($cli, $this->encFieldMap))
					if(!$this->nameTaken($cli))
					{
						$this->encTableMap[$cli]=$value;
						$this->encTableMapR[$value]=$cli;
						
						
						//fill the search-replace buffer
						$this->srMaps["tblS"][]=$value;
						$this->srMaps["tblR"][]=$cli;
						
						$this->namesCount++;
						
						return $cli;
						break;
					};
				};
				return $value;
			} 
			else if($props=="fld")
			{
				if($this->field_exists($value, $tbl))
				{
					return $this->getEncodedFieldName($value, $tbl);
				}
				else
				{
					$i=$this->namesCount;
					$li=65+$i%52+($i%52>25?6:0);
					$cli=chr($li);
					if($i>=52)
					{
						$li=65+($i-$i%52)/52+(($i-$i%52)/52>25?6:0);
						$cli=chr($li).$cli;
					};
					if(!$this->nameTaken($cli))
					{
						$this->encFieldMap[$cli]=array(
							"fName"  => $value,
							"fTable" => $tbl
						);
						$this->encFieldMapR[$value]=array(
							"fName"  => $cli,
							"fTable" => $tbl
						);
						
						//fill the search-replace buffer
						$this->srMaps["t_fieldS"][]="$tbl.$value";
						$this->srMaps["t_fieldR"][]=$cli;
						
						$this->namesCount++;
						
						return $cli;
						break;
					}
					else die("er.. 'coded val taken");//TODO: handle
				};
			}
			else if($props["fType"]=="varchar")
			{
				$fieldLen=strlen($fld);
				$fieldCheckSum=0;
				for($i=0;$i<$fieldLen;$i++){$fieldCheckSum+=ord($fld{$i});};
				
				$tableLen=strlen($fld);
				$tableCheckSum=0;
				for($i=0;$i<$tableLen;$i++){$tableCheckSum+=ord($tbl{$i});};

				#echo "<br> field ".$fieldCheckSum." table $tableCheckSum ";
				

				$lc=strlen($value);
				$convertedStr="";
				for($i=0;$i<$lc;$i++)
				{
					$converted=0;
					
					$currentChar=$value{$i};
					$currentASCII=ord($currentChar);
					$converted=$this->encShuffle($currentASCII, $seed+4*$lc+$i*$i-2*$i+$fieldCheckSum+$tableCheckSum, 256);
					$cli=chr($converted);
					#$unsh=$this->encUnShuffle($converted, $seed+$lc+$i, 256);
					//echo chr($converted);
					$convertedStr.=chr($converted);
				};
				return $convertedStr;
			}
			else if($props["fType"]=="int")
			{
				return $value;
			};
		};
	}


	#######################################################################
	#########DECODES_THE_GIVVEN_VALUE_ACCORDING_TO_SEED_AND_PARAMETERS#####
	private function decode($value, $props, $tbl=NULL, $fld=NULL)
	{
		$seed=$this->seed;
		if(!$this->encryption)
		{
			return $value;
		}
		else
		{
			if($props=="tbl")
			{
				if (array_key_exists($value, $this->encTableMapR))
				{
					return $this->encTableMapR[$value];
				}
				else
				{
					die("er decoding");//TODO: handle
				};
			}
			else if($props=="fld")
			{
				if (array_key_exists($value, $this->encFieldMapR))
				{
					return $this->encFieldMapR[$value];
				}
				else
				{
					die("er decoding");//TODO: handle
				};

			}
			else if($props["fType"]=="int")
			{
				return $value;
			}
			else if($props["fType"]=="varchar")
			{
				$fieldLen=strlen($fld);
				$fieldCheckSum=0;
				for($i=0;$i<$fieldLen;$i++){$fieldCheckSum+=ord($fld{$i});};
				
				$tableLen=strlen($fld);
				$tableCheckSum=0;
				for($i=0;$i<$tableLen;$i++){$tableCheckSum+=ord($tbl{$i});};

				$lc=strlen($value);
				$convertedStr="";
				for($i=0;$i<$lc;$i++)
				{
					$converted=0;
					
					$currentChar=$value{$i};
					$currentASCII=ord($currentChar);
					$converted=$this->encUnShuffle($currentASCII, $seed+4*$lc+$i*$i-2*$i+$fieldCheckSum+$tableCheckSum, 256);
					$cli=chr($converted);
					#$unsh=$this->encUnShuffle($converted, $seed+$lc+$i, 256);
					//echo chr($converted);
					$convertedStr.=chr($converted);
				};
				return $convertedStr;
			};
		}
	}


	#######################################################################
	########ENCODES_$numVal_BASED_ON_THE_GIVVEN_SEED#######################
	private function encShuffle($numVal, $seed, $range=51)
	{
		//TODO: check the vars ^^
		return ($numVal+$seed)%$range;
	}


	#######################################################################
	########DECODES_$numVal_BASED_ON_THE_GIVVEN_SEED#######################
	private function encUnShuffle($numVal, $seed, $range=51)
	{
		//TODO: check the vars ^^
		return (-$seed+$numVal)%$range;
	}


	#######################################################################
	########SETS_THE_FROM_STACK############################################
	public function from($fromTables)
	{
		//TODO: make translation tables
		
		$sr=$this->getSRTableDotField();
		$sr2=$this->getSRTables();
		
		
		$fromTables=str_replace($sr["search"], $sr["replace"], $fromTables);
		$fromTables=str_replace($sr2["search"], $sr2["replace"], $fromTables);
		
		$this->fromStack=($fromTables);
		return $this;
	}
	
	
	public function getSRTableDotField()
	{
		//TODO: add more than apc
		//$fromTables=str_replace($this->srMaps["t_fieldS"], $this->srMaps["t_fieldR"], $fromTables);
		//$fromTables=str_replace($this->srMaps["tblS"], $this->srMaps["tblR"], $fromTables);
		return apc_fetch($this->key."_srTableDotField");
	}
	
	
	public function getSRTables()
	{
		//TODO: add more than apc
		//TODO: optimize apc
		//$fromTables=str_replace($this->srMaps["t_fieldS"], $this->srMaps["t_fieldR"], $fromTables);
		//$fromTables=str_replace($this->srMaps["tblS"], $this->srMaps["tblR"], $fromTables);
		$s=array();
		$r=array();
		$arr = apc_fetch($this->key."_tableList");
		foreach($arr as $tName => $encodedAs)
		{
			$s[]=$tName;$r[]=$encodedAs;
		};
		return array
		(
			"search"  => $s,
			"replace" => $r
		);
	}
	
	
	#######################################################################
	########SETS_THE_FROM_QUERY_FROM_STACK#################################
	public function getFromQuery()
	{
		return $this->fromStack;
	}
	

	#######################################################################
	########ADDS_WANTED_FIELDS_TO_THE_SELECTION_STACK######################
	public function select($inFields)
	{
		#in case of null, empty the stack
		if($inFields==NULL)
		{
			foreach($this->wantedF as $key => $f)
			{
				unset($this->wantedF[$key]);
			};
		}
		else
		{
			#the format is "table1.field1:asFieldX,table2.field2:asFieldY"
			$flds=explode(",",$inFields);#TODO: find a better way
			foreach($flds as $fieldDotTblComAs)
			{
				$arrFDT_A=explode(":", $fieldDotTblComAs);
				$arrTwo=explode(".", $arrFDT_A[0]);
				
				$table=$arrTwo[0];
				$field=$arrTwo[1];
				$as=$arrFDT_A[1];
				
				if($this->table_exists($table)&&$this->field_exists($field, $table))
				{
					//TODO: checking
					$this->wantedF[]=array
					(
						"table" => $table,
						"field" => $field,
						"as"    => $as
					);
				}
				else
				{
					//TODO: er handle
					echo "er.... no such predefined field";
				};
			};
		};
		return $this;
	}


	#######################################################################
	########READS_SELECTION_STACK_AND_RETURNS_SQL##########################
	private function getSelectedQuery()
	{
		//var_dump($this->encode($this->wantedF[0]["table"], "tbl"));
		$q="";
		if(count($this->wantedF)==0)
		{
			$q=" * ";
		}
		else
		{
			foreach($this->wantedF as $props)
			{
				$pfix="";
				$sfix="";
				$field=$this->encode($props["field"], "fld", $props["table"]);
				if(!$this->encryption)
				{
					$pfix=" `".$this->encode($props["table"], "tbl")."`.`";
					$sfix="`";
				};
				if($props["fType"]=="email")
				{
					$field="CONCAT(".
						$field.
						",".
					")";
				};
				$q.=$pfix.$field.$sfix." AS ".$props["as"].", ";
			};
			$q=rtrim($q, ", ");
		};
		return $q;
	}


	private function init_apc()
	{
		apc_store($this->key."_tableList", array());
		apc_store($this->key."_encodedTableList", array());
		
		apc_store($this->key."_fieldList", array());
		apc_store($this->key."_encodedFieldList", array());
		
		apc_store($this->key."_srTableDotField", array("search"=>array(), "replace"=>array()));
	}
	

/*
 *
 *        returns a numeric array with the table names
 *
 */
	private function getTableNames()
	{
		$tbls=array();
		$ar2=apc_fetch($this->key."_tableList");
		$i=0;
		foreach($ar2 as $key => $val)
		{
			$tbls[$i]=$key;
			$i++;
		};
		return($tbls);
	}


/*
 *
 *        returns a assocc array with the field names as key and field props as value from table tbl
 *
 */
	private function getFields($tbl)
	{
		$tEntry=apc_fetch($this->key."_table_".$tbl);
		return($tEntry["fields"]);
	}

	
	
	##########################################################
	############ADDS_TABLE_TO_WHATEVER_CACHE_IS_SET###########
	private function addTable($tName, $tProps=NULL)
	{
		//TODO: add more options for caches
		$this->add_apc_table($tName);
	}
	
	

        private function addMailField($fName, $tName, $fProps=NULL)
        {
                //TODO: add this
        }

	##########################################################
	############ADDS_FIELD_TO_WHATEVER_CACHE_IS_SET###########
	private function addField($fName, $tName, $fProps=NULL)
	{
		//TODO: add more options for caches
                if($fProps["fType"]=="email"&&!array_key_exists("parsed",
                        $fProps))
                {
                              if(!$this->table_exists("mailservers"))
                              {
                                        $this->addTable("mailservers");
                                        $this->addField("id", "mailservers", array(
                                                "fType" => "int",
                                                "size"  => 4,
                                                "index" => "primary"
                                        ));
                                        $this->addField("servers", "mailservers", array(
                                                "fType" => "varchar",
                                                "size"  => 25,
                                                "index" => "unique"
                                        ));
                               };
                               $fProps["parsed"]=true;
                               $this->addField($fName, $tName, $fProps);
                               $this->addField($fName."mailsid", $tName, array(
                                        "fType" => "int",
                                        "size"  => 4
                               ));
                }
                else
                {
		        $this->add_apc_field($fName, $tName, $fProps);
                }
	}
	
	
	##########################################################
	############RETURNS_WHAETHER_THE_FIELD_IS_SET#############
	private function field_exists($fld, $tbl)
	{
		//TODO: add more than apc
		return apc_exists($this->key."_fieldAs_".$tbl."_".$fld);
		//return array_key_exists($strTable, $this->tablesMap);
	}
	
	
	##########################################################
	############RETURNS_WHAETHER_THE_TABLE_IS_SET#############
	private function table_exists($strTable)
	{
		//TODO: add more than apc
		return apc_exists($this->key."_table_".$strTable);
	}
	
	
	##########################################################
	############RETURNS_THE_FIELD_PROPERTIES##################
	private function getFieldProps($fName, $tName)
	{
		//TODO: add more than apc
		if($this->table_exists($tName)&&$this->field_exists($fName, $tName))
		{
			return apc_fetch($this->key."_fieldProps_".$tName."_".$fName);
		}
		else
		{
			die("er.. must exist");//TODO: handle
		}
	}
	
	
	private function nameTaken($encodedName)
	{
		//TODO: add more than apc
		return (apc_exists($this->key."_encodedTableList_".$encodedName)&&apc_exists($this->key."_fieldEncoded_".$encodedName));
	}

	##########################################################
	############RETURNS_THE_ENCODED_TABLE_NAME################
	private function getEncodedTableName($tName)
	{
		//TODO: add more than apc
		if(apc_exists($this->key."_table_".$tName))
		{
			return apc_fetch($this->key."_tableList_".$tName);
		}
		else
		{
			die("er.. not here");//TODO: handle
		};
	}
	
	
	##########################################################
	############RETURNS_THE_DECODED_TABLE_NAME################
	private function getDecodedTableName($tNameEncoded)
	{
		$tName=null;
		//TODO: add more than apc
		if(apc_exists($this->key."_encodedTableList_".$tNameEncoded))
		{
			return apc_fetch($this->key."_encodedTableList_".$tNameEncoded);
		}
		else
		{
			die("er.. not here");//TODO: handle
		};
	}
	


	##########################################################
	############RETURNS_THE_ENCODED_FIELD_NAME################
	private function getEncodedFieldName($fName, $tName)
	{
		//TODO: add more than apc
		if(apc_exists($this->key."_fieldAs_".$tName."_".$fName))
		{
			return apc_fetch($this->key."_fieldAs_".$tName."_".$fName);
		}
		else
		{
			die("er.. not here");//TODO: handle
		};
	}
	

	##########################################################
	############RETURNS_THE_DECODED_FIELD_NAME################
	private function getDecodedFieldName($fEnc, $tName)
	{
		//TODO: add more than apc
		if(apc_exists($this->key."_fieldEncoded_".$tName."_".$fEnc))
		{
			return apc_fetch($this->key."_fieldEncoded_".$tName."_".$fEnc);
		}
		else
		{
			die("er.. not here");//TODO: handle
		};
	}


	##########################################################
	############ADDS_TABLE_TO_APC_CACHE#######################
	private function add_apc_table($tName)
	{
		$tables=apc_fetch($this->key."_tableList");
			if(array_key_exists($tName, $tables)){die("er.. existsT");};//TODO: handle
		$encodedTables=apc_fetch($this->key."_encodedTableList");
		$encoded=$this->encode($tName, "tbl");
		
		$tables[$tName]=$encoded;
		$encodedTables[$encoded]=$tName;
		
		apc_store($this->key."_tableList", $tables);
		apc_store($this->key."_tableList_".$tName, $encoded);
		apc_store($this->key."_encodedTableList", $encodedTables);
		apc_store($this->key."_encodedTableList_".$encoded, $tName);
		apc_store($this->key."_table_".$tName, array());
		
		//$srTables
	}
	
	
	##########################################################
	############ADDS_FIELD_TO_APC_CACHE#######################
	private function add_apc_field($fName, $tName, $props)
	{
		$fields=apc_fetch($this->key."_fieldList");
		$encodedFields=apc_fetch($this->key."_encodedFieldList");
		$encoded=$this->encode($fName, "fld", $tName);
		foreach($fields as $key => $fld)
		{
			if($fld["fName"]==$fName&&$fld["tName"]==$tName)
			{
				die("er.. already existsF");//TODO: handle
			};
		};
		
		$fields[]=array
		(
			"fName"     => $fName,
			"encodedAs" => $encoded,
			"tName"     => $tName,
			"props"     => $props
		);
		
		$encodedFields[$encoded]=array
		(
			"fName"  => $fName,
			"tName"  => $tName,
			"fIndex" => count($fields)-1,
			"props"     => $props
		);
		
		apc_store($this->key."_fieldList", $fields);
		apc_store($this->key."_encodedFieldList", $encodedFields);
		apc_store($this->key."_fieldProps_".$tName."_".$fName, $props);
		
		apc_store($this->key."_fieldAs_".$tName."_".$fName, $encoded);
		apc_store($this->key."_fieldEncoded_".$encoded, $fName);
		
		$tEntry=apc_fetch($this->key."_table_".$tName);
		$tEntry["fields"][$fName]=$props;
		apc_store($this->key."_table_".$tName, $tEntry);
		
		//search-replace tables
		//TODO: console quotes
		$srTDF=apc_fetch($this->key."_srTableDotField");
		$srTDF["search"][]=$tName.'.'.$fName;
		$srTDF["replace"][]=$encoded;
		apc_store($this->key."_srTableDotField", $srTDF);
	}
	

	public function __construct($fOpts)
	{
		apc_clear_cache();
		$this->init_apc();
		$opts=unserialize(file_get_contents($fOpts));
		if($opts["encryption"]!=false)
		{
			$this->encryption=true;
		}
		else
		{
			$this->encryption=false;
		};
		$this->encryption=true;
		foreach($opts["tablesMap"] as $tName => $tbl)
		{
			$this->addTable($tName);
			foreach($tbl as $fName => $fOpts)
			{
				$this->addField($fName, $tName, $fOpts);
			};
		};
		//$this->tablesMap=$opts["tablesMap"];
		//$this->seed=$opts["seed"];
		//var_dump(apc_fetch($this->key."_fieldList"));
		
		$this->createTables($opts["tablesMap"]);
		
		$this->where("offers", "id", 5);
		
		//var_dump(null);
		
		//var_dump(apc_fetch($this->key."_srTableDotField"));
	}

	

	public function __set($property, $value)
	{
		if($property=="select"||$property=="from"||$property=="where"||$property=="order"||$property=="limit")
		{
			return $this->$property($value);
		}
	}

	public function clearSearchConditions()
	{
		foreach($this->searchConditions as $key=>$v)
			unset($this->searchConditions[$key]);
		return $this;
	}

	public function getSearchConditions()
	{
		foreach($this->searchConditions as $value)
		{
			$dbField="`".$value["table"]."`."."`".$value['field']."`";//TODO: encryption?
			if($value["type"]=="range")
			{
				$arrMid=explode("-", $value);
				$from=intval($arrMid[0]);
				$to=($arrMid[1]=="")?NULL:intval($arrMid[1]);
				return $dbField." > $from".
					($to==NULL)?"":(" AND $dbField < $to");
			}
			else if($value["type"]=="varchar")
			{
				//TODO: like? regexp? 
				return "`".$value["table"]."`."."`".$value['field']." LIKE '".$value["value"]."'";
			}
			else if($value["type"]=="int")
			{
				//TODO: != <=......
				return "$dbField = ".$value["value"];
			}
			//TODO: ...
			else
			{
				//TODO: can't happen
			}
		};
	}

	public function __get($property)
	{
		return $this->$property;
	}





	private $tablesMapsa=array
	(
		"offers"=>array
		(
			"id"=>array
			(
				"fType"  => "int",
				"size"   => 3,
				"range"  => false,
				"index" => "primary",
				"null"  => "NOT NULL"
			),
			"photo"=>array
			(
				"fType"  => "int",
				"size"   => 3,
				"range"  => false,
				"index" => "unique"
			),
			"agency"=>array
			(
				"fType"  => "int",
				"size"   => 2,
				"range"  => false,
				"index" => "unique"
			),
			"price"=>array
			(
				"fType"  => "int",
				"size"   => 4,
				"index"  => false,
				"range"  => true
			),
			"area"=>array
			(
				"fType"  => "int",
				"size"   => 3,
				"index"  => false,
				"range"  => true
			),
			"date"=>array
			(
				"fType"  => "int",
				"size"   => 3,
				"index"  => false,
				"range"  => true
			),
			"rooms"=>array
			(
				"fType"  => "int",
				"size"   => 1,
				"index"  => false,
				"range"  => true
			)
		),
		"agencies"=>array
		(
			"id"=>array
			(
				"fType"  => "int",
				"size"   => 2,
				"index"  => "primary",
				"range"  => false
			),
			"name"=>array
			(
				"fType"  => "string",
				"size"   => 255,
				"index"  => false, 
				"range"  => false
			),
			"mail"=>array
			(
				"fType"  => "string",
				"size"   => 1,
				"index"  => false,
				"range"  => false
			),
			"banner"=>array
			(
				"fType"  => "string",
				"size"   => 2,
				"index"  => false,
				"range"  => false
			)
		),
		"photo"=>array
		(
			"id"=>array
			(
				"fType"  => "int",
				"size"   => 2,
				"index"  => "primary",
				"range"  => false
			),
			"offer"=>array
			(
				"fType"  => "int",
				"size"   => 4,
				"index"  => false, 
				"range"  => false
			),
			"type"=>array
			(
				"fType"  => "int",
				"size"   => 1,
				"index"  => false,
				"range"  => false
			),
			"path"=>array
			(
				"fType"  => "string",
				"size"   => 40,
				"index"  => false,
				"range"  => false
			)
		)
	);
/*
*/
}
