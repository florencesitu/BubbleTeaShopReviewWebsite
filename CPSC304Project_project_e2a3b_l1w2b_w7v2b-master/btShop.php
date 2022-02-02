<html>
<head>
    <title>Bubble Tea Shop Reviewer</title>
</head>

<body>
<h2>Reset</h2>
<p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

<form method="POST" action="btShop.php">
    <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
    <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
    <p><input type="submit" value="Reset" name="reset"></p>
</form>

<hr />

<h2>Insert Values into Customer</h2>
<form method="POST" action="btShop.php"> <!--refresh page when submitted-->
    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
    Number: <input type="text" name="insNo"> <br /><br />
    Name: <input type="text" name="insName"> <br /><br />

    <input type="submit" value="Insert" name="insertSubmit"></p>
</form>

<hr />

<h2>Update Name in Customer</h2>
<p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

<form method="POST" action="btShop.php"> <!--refresh page when submitted-->
    <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
    Old Name: <input type="text" name="oldName"> <br /><br />
    New Name: <input type="text" name="newName"> <br /><br />

    <input type="submit" value="Update" name="updateSubmit"></p>
</form>

<h2>Delete Customer by ID</h2>
<hr />
<form method="POST" action="btShop.php"> <!--refresh page when submitted-->
    <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
    Customer id: <input type="text" name="cid"> <br /><br />
    <input type="submit" value="Delete" name="deleteSubmit"></p>
</form>
<hr/>

<h2>Enter a Bubble Tea shop name and get bverage list</h2>
<form method="GET" action="btShop.php"> <!--refresh page when submitted-->
    <input type="hidden" id="showDrinksRequest" name="showDrinksRequest">
    Name: <input type="text" name="nnn"> <br /><br />
    Address: <input type="text" name="aaa"> <br /><br/>
    <input type="submit" name="showDrinks">
    </p>
</form>

<h2>Count the Tuples in Customer</h2>
<form method="GET" action="btShop.php"> <!--refresh page when submitted-->
    <input type="hidden" id="countTupleRequest" name="countTupleRequest">
    <input type="submit" name="countTuples"></p>
</form>
<hr />

<h2>Display the Tuples in Customer</h2>
<form method="GET" action="btShop.php"> <!--refresh page when submitted-->
    <input type="hidden" id="displayTupleRequest" name="displayTupleRequest">
    <input type="submit" name="displayTuples"></p>
</form>
<hr/>

<h2>Display the Tuples in Shop</h2>
<form method="GET" action="btShop.php"> <!--refresh page when submitted-->
    <input type="hidden" id="displayShopTupleRequest" name="displayShopTupleRequest">
    <input type="submit" name="displayShopTuples"></p>
</form>
<hr/>

<h2>Retrive the Bubble Tea Shop with the worst rating</h2>
<form method="GET" action="btShop.php"> <!--refresh page when submitted-->
    <input type="hidden" id="worstRatingRequest" name="worstRatingRequest">
    <input type="submit" name="showWorst">
    </p>
</form>
<hr/>

<h2>Retrive Bubble Tea Shops that has less than 2 drink options</h2>
<form method="GET" action="btShop.php"> <!--refresh page when submitted-->
    <input type="hidden" id="irrelevantShopRequest" name="irrelevantShopRequest">
    <input type="submit" name="showIrrelevant">
    </p>
</form>
<hr/>

<h2>retrieve shop name that has the cheapest drink</h2>
<form method="GET" action="btShop.php"> <!--refresh page when submitted-->
    <input type="hidden" id="cheapestDrinkRequest" name="cheapestDrinkRequest">
    <input type="submit" name="showCheapest">
    <hr/>
    <h2>Project Attirbute of a Beverage </h2>
    <form method="GET" action="btShop.php"> <!--refresh page when submitted-->
        <input type="hidden" id="projectionRequest" name="projectionRequest">
        <input type="submit" name="showProjection"></p>
    </form>
    <hr/>
    <h2>Select Review's rating higher than user input OR paragraph equal to user input </h2>
    <h3>　Rating between 1-5, paragraph: ok, good, great </h3>
    <form method="GET" action="btShop.php"> <!--refresh page when submitted-->
        <input type="hidden" id="selectRequest" name="selectRequest">
        rating: <input type="text" name="inrating"> <br /><br />
        paragraph: <input type="text" name="inpa"> <br /><br />
        <input type="query" name="showSelection"></p>
    </form>
    </p>
</form>

























<?php
//this tells the system that it's no longer just parsing html; it's now parsing PHP

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

function debugAlertMessage($message) {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
    //echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success;

    $statement = OCIParse($db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
        echo htmlentities($e['message']);
        $success = False;
    }

    return $statement;
}

function executeBoundSQL($cmdstr, $list) {
    /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
In this case you don't need to create the statement several times. Bound variables cause a statement to only be
parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
See the sample code below for how this function is used */

    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
            OCIBindByName($statement, $bind, $val);
            unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }
}

function printCustomerResult($result) { //prints results from a select statement
    echo "<br>Retrieved data from table Customer:<br>";
    echo "<table>";
    echo "<tr><th>C_ID</th><th>C_Name</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"

    }

    echo "</table>";
}

function printShopResult($result) { //prints results from a select statement
    echo "<br>Retrieved data from table Customer:<br>";
    echo "<table>";
    echo "<tr><th>Name</th><th>address</th><th>rate</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] ."</td></tr>"; //or just use "echo $row[0]"

    }

    echo "</table>";
}

function printDrinksResult($result) { //prints results from a select statement
    echo "<br>Retrieved data from table Customer:<br>";
    echo "<table>";
    echo "<tr><th>Name</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td></tr>"; //or just use "echo $row[0]"
    }

    echo "</table>";
}


function connectToDB() {
    global $db_conn;

    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $db_conn = OCILogon("ora_lizx98", "a33167651", "dbhost.students.cs.ubc.ca:1522/stu");

    if ($db_conn) {
        debugAlertMessage("Database is Connected");
        return true;
    } else {
        debugAlertMessage("Cannot connect to Database");
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}

function disconnectFromDB() {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
}

function handleUpdateRequest() {
    global $db_conn;

    $old_name = $_POST['oldName'];
    $new_name = $_POST['newName'];

    // you need the wrap the old name and new name values with single quotations
    executePlainSQL("UPDATE Customer SET C_name='" . $new_name . "' WHERE C_name='" . $old_name . "'");
    OCICommit($db_conn);
}

function handleDeleteRequest() {
    global $db_conn;
    $c_id = $_POST['cid'];
    $result = executePlainSQL("DELETE FROM Customer WHERE C_id = '$c_id' ");
    echo "<br>Delete Customer id is $c_id</br>";
    handleDisplayRequest();
    OCICommit($db_conn);
}

function handleResetRequest() {
    global $db_conn;
    // Drop old table
    executePlainSQL("DROP TABLE Customer");

    // Create new table
    echo "<br> creating new table <br>";
    executePlainSQL("CREATE TABLE Customer (C_id int PRIMARY KEY, C_name char(50))");
    OCICommit($db_conn);
}

function handleInsertRequest() {
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array (
        ":bind1" => $_POST['insNo'],
        ":bind2" => $_POST['insName']
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("insert into Customer values (:bind1, :bind2)", $alltuples);
    OCICommit($db_conn);
}

function handleCountRequest() {
    global $db_conn;

    $result = executePlainSQL("SELECT Count(*) FROM Customer");

    if (($row = oci_fetch_row($result)) != false) {
        echo "<br> The number of tuples in Customer: " . $row[0] . "<br>";
    }
}
function handleSelectionRequest() {
    global $db_conn;

    //Getting the values from user and insert data into the table

    // rating : 2 - 5   // paragraph : ok, good, great

    $var1 = $_GET['inrating'];
    $var2 = $_GET['inpa'];

    $result = executePlainSQL("       SELECT review_number, rating
                                                FROM Review
                                                WHERE rating > $var1 || paragraph = $var2 ");
    printResult($result);

}
function handleCheapestDrinkRequest() {
    //SELECTS.major,  MIN(S.age)FROMStudent SWHERES.age> 18GROUP BY  S.majorHAVING1  <  (SELECT  COUNT(*)FROMStudent S2WHERES.major=S2.major)
    // Find the price of the cheapest drink that is less than $5 in a relevant shop (has at least 2 drinks)
    global $db_conn;

    $result = executePlainSQL("SELECT B.BTS_Name, MIN(B.price) FROM Beverage B WHERE B.price < 5 GROUP BY B.BTS_Name HAVING 1 < (SELECT COUNT(*) FROM Beverage B2 WHERE B.BTS_Name = B2.BTS_Name)");

    echo "<br>Retrieved the shop that have the cheapeast drink<br>";
    echo "<table>";
    echo "<tr><th>Name</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td></tr>"; //or just use "echo $row[0]"
    }

    echo "</table>";

    if (($row = oci_fetch_row($result)) != false) {
        echo "<br> Irrelevant shops in the system are: " . $row[0] . "<br>";
    }
}

function handleShowDrinksRequest() {
    global $db_conn;
    $s_name = $_GET['nnn'];
    $s_address = $_GET['aaa'];

    $result = executePlainSQL("SELECT b.B_Name FROM Shop s, Beverage b WHERE s.BTS_Name=  '$s_name' AND s.loca = '$s_address'");

    echo "<br>Retrieved data from table Customer:<br>";
    echo "<table>";
    echo "<tr><th>Name</th><th>address</th><th>rate</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td></tr>"; //or just use "echo $row[0]"
    }

    echo "</table>";
}

function handleMinRequest() {
    global $db_conn;

    $result = executePlainSQL("SELECT BTS_Name, rating FROM shop s WHERE s.rating = (SELECT MIN(rating) FROM shop)  ");

    echo "<br>the shop have the lowest rating<br>";
    echo "<table>";
    echo "<tr><th>Name</th><th>rate</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"

    }

    echo "</table>";



}

function handleDisplayRequest() {
    global $db_conn;

    $result = executePlainSQL("SELECT * FROM Customer");
    //print_r($result);

    printCustomerResult($result);
}

function handleShopDisplayRequest() {
    global $db_conn;

    $result = executePlainSQL("SELECT * FROM Shop");
    //print_r($result);

    printShopResult($result);
}

function handleProjectionRequest(){
    global $db_conn;
    $result = executePlainSQL("SELECT B_Name, BTS_Name, price 
                                              FROM Beverage b
                                              WHERE b.price < 6 ");
    echo "<br>Retrieved data from table Beverage:<br>";
    echo "<table>";
    echo "<tr><th>BTS_Name</th><th>B_Name</th><th>price</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["BTS_NAME"] . "</td><td>" . $row["B_NAME"] . "</td><td>". $row["TEA_TYPE"] . "</td><td>" . $row["PRICE"] . "</td></tr>"; //or just use "echo $row[0]"

    }

    echo "</table>";

}

function handleIrrelevantShopRequest() {
    global $db_conn;

    $result = executePlainSQL("SELECT BTS_Name,COUNT(B_Name) FROM Beverage GROUP BY BTS_Name HAVING COUNT(B_Name) < 2");

    echo "<br>Retrieved the Bubble Tea shop have less than 2 drink options<br>";
    echo "<table>";
    echo "<tr><th>BTS_Name</th><th>Number</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row["1"] . "</td></tr>"; //or just use "echo $row[0]"

    }

    echo "</table>";
    if (($row = oci_fetch_row($result)) != false) {
        echo "<br> Irrelevant shops in the system are: " . $row[0] . "<br>";
    }
}



// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('resetTablesRequest', $_POST)) {
            handleResetRequest();
        } else if (array_key_exists('updateQueryRequest', $_POST)) {
            handleUpdateRequest();
        } else if (array_key_exists('insertQueryRequest', $_POST)) {
            handleInsertRequest();
        } else if (array_key_exists('deleteQueryRequest', $_POST)) {
            handleDeleteRequest();
        }

        disconnectFromDB();
    }
}

// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('countTuples', $_GET)) {
            handleCountRequest();
        } else if (array_key_exists('displayTuples', $_GET)) {
            handleDisplayRequest();
        } else if (array_key_exists('displayShopTuples', $_GET)) {
            handleShopDisplayRequest();
        } else if (array_key_exists('showDrinks', $_GET)){
            handleShowDrinksRequest();
        } else if (array_key_exists('showWorst', $_GET)) {
            handleMinRequest();
        } else if (array_key_exists('showCheapest', $_GET)) {
            handleCheapestDrinkRequest();
        } else if (array_key_exists('showProjection', $_GET)){
            handleProjectionRequest();
        } else if (array_key_exists('showIrrelevant', $_GET)) {
            handleIrrelevantShopRequest();
        }else if (array_key_exists('showSelection', $_GET)){
            handleSelectionRequest();
        }
        disconnectFromDB();
    }
}

if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit'])|| isset($_POST['deleteSubmit'])) {
    handlePOSTRequest();
} else if ( isset($_GET['countTupleRequest']) || isset($_GET['displayTupleRequest'])
    || isset($_GET['displayShopTupleRequest'])
    || isset($_GET['showDrinksRequest']) || isset($_GET['worstRatingRequest'])
    || isset($_GET['cheapestDrinkRequest'])
    || isset($_GET['projectionRequest'])
    || isset($_GET['irrelevantShopRequest'])
    || isset($_GET['selectRequest'])){
    handleGETRequest();
}
?>
</body>
</html>

