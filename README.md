# php_orm
# SetUp
1. You need to update the db connection details in PhpORM/DA_DataBaseConnectionClass.php
 
# Common to all crud
1. Import the DA_QueryClass. eg require_once(dirname(__FILE__)."/php_orm/DA_QueryClass.php");. Make sure path referes correctly.
2. Create a new object of QueryClass.
    $queryClassObject=new QueryClass();
3. Set table name using setTable method.
  $queryClassObject->setTable("userinfo");

# Issuing select command.
1. Add fields which need to be queried.
        $queryClassObject->setField("PassWord");
        $queryClassObject->setField("Status");
2. Add condition to select. (Optional)        
        $queryClassObject->addCondition("Email", $EmailId_);
3.  Run select method.       
        $loginResponse=json_decode($queryClassObject->select()); // Returns JSON
NOTE: Select method returns the queried value in JSON format. You may choose to decode the JSON or use it appropriately. In this example it is decoded and set to variable and the variable can be queried using $loginReturned[0]->PassWord to get the vale.

# Issuing Insert command.
1. Add fields which need to be inserted.
        $queryClassObject->setField("PassWord");
2. Execute insert method.
        $queryClassObject->insert() //returns Boolean
        
Insert query as of now cannot match fieldname and value so it will expect all corresponding values to be present in same order as db fields. put an empty string if field value is not present. This need to be corrected in future versions.
	i. To insert CURRENT_TIMESTAMP value pass value as 'CURRENT_TIMESTAMP' .

# Issuing Update command.
1. Add fields which need to be inserted is value-key pair.
	$queryClassObject->setField("Status", "Password Reset"); (sqlfiled, value)
2. Add condition to update
 	$queryClassObject->addCondition("Email", $Email_);
 	
3. Call update method.
	$queryClassObject->Update() // returns Boolean

