<?php
/**
Auto Close Fraud Accounts for WHMCS
Version 1.0 by KuJoe (JMD.cc)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
**/

function closeFraud($vars) {
	$query = "SELECT a.userid, a.cnt FROM (SELECT userid, count(*) AS 'cnt' FROM tblorders WHERE status = 'Fraud' GROUP BY userid) AS a INNER JOIN (SELECT userid, count(*) AS 'cnt' FROM tblorders GROUP BY userid) AS b ON a.userid = b.userid AND a.cnt = b.cnt";
	$result = mysql_query($query) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$query2 = "SELECT * FROM tblclients WHERE (status = 'Inactive') AND id = ".$row["userid"]." ORDER BY id";
		$result2 = mysql_query($query2) or die(mysql_error());
		while($row2 = mysql_fetch_array($result2)){
			update_query("tblclients",array("status"=>'Closed'),array("id"=>$row2['id']));
		}
	}
}

add_hook("DailyCronJob",1,"closeFraud");
?>