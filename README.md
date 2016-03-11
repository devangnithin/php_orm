# php_orm
# SetUp
1. You need to update the db connection details in PhpORM/DA_DataBaseConnectionClass.php

# Issuing select command.
1. Import the DA_QueryClass. eg require_once(dirname(__FILE__)."/DataAccessLayer/DA_QueryClass.php");. Make sure path referes correctly.
2. Create a new object of DA_QueryClass.
    $QueryClassObject=new DA_QueryClass();
3. Set table name using SetTable method.
  $QueryClassObject->SetTable("userinfo");
4. Add fields which need to be queried.
        $QueryClassObject->AddField("PassWord");
        $QueryClassObject->AddField("Status");
5. Add condition to select. (Optional)        
        $QueryClassObject->AddCondition("Email", $EmailId_);
6.  Run select method.       
        $LoginReturn=json_decode($QueryClassObject->Select());
NOTE: Select method returns the queried value in JSON format. You may choose to decode the JSON or use it appropriately. In this example it is decoded and set to variable and the avriable can be queried using $LoginReturn[0]->PassWord to get the vale.

# Issuing Insert command.
1. Import the DA_QueryClass. eg require_once(dirname(__FILE__)."/DataAccessLayer/DA_QueryClass.php");. Make sure path referes correctly.
2. Create a new object of DA_QueryClass.
    $QueryClassObject=new DA_QueryClass();
3. Set table name using SetTable method.
  $QueryClassObject->SetTable("userinfo");
4. Add fields which need to be inserted.
        $QueryClassObject->AddField("PassWord");
5. Execute insert method.
        $QueryClassObject->Insert()
        
Note: Insert method returns true on successful insertion and false on error. Insert query as of now cannot match fieldname and value so it will expect all corresponding values to be present in same order as db fields. put an empty string if field value is not present. This need to be corrected in future versions.
	i. To insert CURRENT_TIMESTAMP value pass value as 'CURRENT_TIMESTAMP' .
	ii. 

# Issuing Update command.
1. Import the DA_QueryClass. eg require_once(dirname(__FILE__)."/DataAccessLayer/DA_QueryClass.php");. Make sure path referes correctly.
2. Create a new object of DA_QueryClass.
	 $QueryClassObject=new DA_QueryClass();
3. Set table name using SetTable method.
	$QueryClassObject->SetTable("userinfo");
4. Add fields which need to be inserted is value-key pair.
	$QueryClassObject->AddField("Password Reset","Status");
	NOTE: The field value appears after the value bypassing general convention. In the example above Status is the db 		field. This need to be corrected.
5. Add condition to update
 	$QueryClassObject->AddCondition("Email", $Email_);
6.Call update method.
	$QueryClassObject->Update()
Note: Update method returns true on successful updation and false on error.

