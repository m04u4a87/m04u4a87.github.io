<?php require_once('Connections/webshopConn.php'); ?>
<?php require_once('Connections/webshopConn.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
mysql_query("SET NAMES 'UTF8'");
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if ((isset($_SESSION['tempord_id'])) && ($_SESSION['tempord_id'] != "") && (isset($_GET['del']))) {
  $deleteSQL = sprintf("DELETE FROM shop_car WHERE ord_id=%s",
                       GetSQLValueString($_SESSION['tempord_id'], "text"));

  mysql_select_db($database_webshopConn, $webshopConn);
  $Result1 = mysql_query($deleteSQL, $webshopConn) or die(mysql_error());

  $deleteGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "order")) {
  $insertSQL = sprintf("INSERT INTO shop_ord_main (ord_id, ord_name, ord_email, ord_tel, ord_address, ord_total) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ord_id'], "text"),
                       GetSQLValueString($_POST['ord_name'], "text"),
                       GetSQLValueString($_POST['ord_email'], "text"),
                       GetSQLValueString($_POST['ord_tel'], "text"),
                       GetSQLValueString($_POST['ord_address'], "text"),
                       GetSQLValueString($_POST['ord_total'], "int"));

  mysql_select_db($database_webshopConn, $webshopConn);
  $Result1 = mysql_query($insertSQL, $webshopConn) or die(mysql_error());

//將購物車內的商品明細寫入訂單商品明細資料表
$insertSQL = sprintf("INSERT INTO shop_ord_sub (ord_id, goods_id, goods_name, goods_stand, goods_price, ord_num, ord_sum) SELECT ord_id, goods_id, goods_name, goods_stand, goods_price, ord_num, ord_sum From shop_car WHERE ord_id= %s",GetSQLValueString($_POST['ord_id'], "text"));
mysql_select_db($database_webshopConn, $webshopConn);
$Result2 = mysql_query($insertSQL, $webshopConn) or die(mysql_error());

//清除購物車中的商品明細
$DelSQL = sprintf("DELETE FROM shop_car WHERE ord_id = %s",GetSQLValueString($_POST['ord_id'], "text"));
mysql_select_db($database_webshopConn, $webshopConn);
$Result3 = mysql_query($DelSQL, $webshopConn) or die(mysql_error());

//清除Session變數
session_unset();

  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
//  header(sprintf("Location: %s", $insertGoTo));
//寄出訂單確認信
$headers = "Content-Type: text/html; charset=UTF-8";
$body="親愛的顧客 " . $_POST['ord_name'] . "您好!!<br>";
$body=$body . "您的訂單編號: " . $_POST['ord_id'] . "<br>";
$body=$body . "訂單總金額: " . $_POST['ord_total'] . "<br>";
$body=$body . "訂單商品我們會儘快完成出貨! 非常感謝您的惠顧!!";
//mail($_POST['ord_email'],"感謝您的訂購!!",$body,$headers);

//彈出訊息視窗
echo "<script langusge=\"javaScript\">";
echo "window.alert(\"您已完成訂購手續,我們將會儘快與您連絡出貨事宜!!\");";
echo "location.href(\"" .$insertGoTo. "\");";
echo "</script>";

}



mysql_select_db($database_webshopConn, $webshopConn);
$query_showitemRec = "SELECT * FROM shop_item";
$showitemRec = mysql_query($query_showitemRec, $webshopConn) or die(mysql_error());
$row_showitemRec = mysql_fetch_assoc($showitemRec);
$totalRows_showitemRec = mysql_num_rows($showitemRec);

$colname_carRec = "-1";
if (isset($_SESSION['tempord_id'])) {
  $colname_carRec = $_SESSION['tempord_id'];
}
mysql_select_db($database_webshopConn, $webshopConn);
$query_carRec = sprintf("SELECT * FROM shop_car WHERE ord_id = %s", GetSQLValueString($colname_carRec, "text"));
$carRec = mysql_query($query_carRec, $webshopConn) or die(mysql_error());
$row_carRec = mysql_fetch_assoc($carRec);
$totalRows_carRec = mysql_num_rows($carRec);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>線上購物</title>
<style type="text/css">
<!--
body {
	background-color: #E3E9F1;
	background-image: url(099.gif);
}
a:link {
	color: #6699CC;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #CC3399;
}
a:hover {
	text-decoration: underline;
	color: #FF9900;
}
a:active {
	text-decoration: none;
	color: #990000;
}
.style13 {
	font-size: 9pt;
	color: #CCCCCC;
}
a {
	font-size: 10pt;
}
body,td,th {
	font-size: 10pt;
	color: #660000;
}
.style17 {color: #333333}
.style18 {
	color: #FFFFFF;
	font-size: 10pt;
}
#showgoods {
	width: 183px;
	float: left;
}
.style21 {
	color: #663300;
	font-size: 10pt;
	font-weight: bold;
}
.style22 {
	color: #0000CC;
	font-weight: bold;
}
.style23 {font-size: 14px}
.style24 {color: #6666CC}
.style25 {color: #3366CC}
-->
</style>
<script type="text/javascript">
<!--
function MM_validateForm() { //v4.0
  if (document.getElementById){
    var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
    for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=document.getElementById(args[i]);
      if (val) { nm=val.name; if ((val=val.value)!="") {
        if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
          if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
        } else if (test!='R') { num = parseFloat(val);
          if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
          if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
            min=test.substring(8,p); max=test.substring(p+1);
            if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
      } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
    } if (errors) alert('The following error(s) occurred:\n'+errors);
    document.MM_returnValue = (errors == '');
} }
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
</head>

<body>
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" background="images1.jpg">
  <tr>
    <td colspan="2" background="img/mod_nw.gif"></td>
    <td width="5"><img src="img/mod_ne.gif" width="5" height="5" /></td>
  </tr>
  <tr>
    <td width="5" background="img/mod_left.gif"><img src="img/mod_left.gif" width="5" height="5" /></td>
    <td width="740" valign="top" background="img/t.gif"><table width="738" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><div align="right" class="style13 style17"></div></td>
        </tr>
        <tr>
          <td><div align="left"><img src="&#27161;%20&#27161;&#25913;.png" width="500" height="100" /></div></td>
        </tr>
        <tr>
          <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#9DACBF">
          <tr>
            <td height="20" align="right" valign="middle" bgcolor="#FF99CC"><input type="button" name="Submit" value="回到首頁" onclick="window.location = 'index.php';" />
              <input type="button" name="Submit" value="購物車" onclick="window.location = 'car.php';" />
              <input type="button" name="Submit" value="訂單查詢" onclick="window.location = 'chkord.php';" /></td>
            </tr>
        </table></td>
        </tr>
    </table></td>
    <td background="img/mod_right.gif"></td>
  </tr>
  <tr>
    <td colspan="2" background="img/mod_sw.gif"></td>
    <td><img src="img/mod_se.gif" width="5" height="5" /></td>
  </tr>
</table><br />
<table width="750" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFCC">
  <tr valign="top">
    <td><table width="750" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="170" valign="top"><?php do { ?>
            <table width="170" border="0" align="center" cellpadding="0" cellspacing="0" id="item">
              
              <tr>
                <td colspan="2" background="img/mod_nw.gif"></td>
        <td width="5"><img src="img/mod_ne.gif" width="5" height="5" /></td>
      </tr>
              <tr>
                <td width="5" background="img/mod_left.gif"><img src="img/mod_left.gif" width="5" height="5" /></td>
        <td width="160" bgcolor="#FFCCCC" class="style8 style18 style22"><div align="center" class="style23"><?php echo $row_showitemRec['item_name']; ?></div></td>
        <td rowspan="2" background="img/mod_right.gif"></td>
      </tr>
              <tr>
                <td background="img/mod_left.gif"></td>
        <td width="160" rowspan="3" bgcolor="#EFEFF8"><table width="100%" border="0" cellpadding="0" cellspacing="0" id="goods">
<?php 
mysql_select_db($database_webshopConn, $webshopConn);
$query_showgoodsRec = sprintf("SELECT * FROM shop_goods WHERE item_id = %s", GetSQLValueString($row_showitemRec['item_id'], "text"));
$showgoodsRec = mysql_query($query_showgoodsRec, $webshopConn) or die(mysql_error());
$row_showgoodsRec = mysql_fetch_assoc($showgoodsRec);
$totalRows_showgoodsRec = mysql_num_rows($showgoodsRec);
if ($totalRows_showgoodsRec > 0) { 
do {?>
<tr>
<td width="9%" height="27"><img src="img/arrows_blue.gif" width="15" height="21" /></td>
<td width="91%"><a href="goods.php?goods_id=<?php echo $row_showgoodsRec['goods_id']; ?>" class="style24">
<?php echo $row_showgoodsRec['goods_name']; ?>
</a></td>
</tr>
<?php } while ($row_showgoodsRec = mysql_fetch_assoc($showgoodsRec)); }?>
</table></td>
      </tr>
              <tr>
                <td background="img/mod_left.gif"></td>
        <td background="img/mod_right.gif"></td>
      </tr>
              <tr>
                <td background="img/mod_left.gif"></td>
        <td background="img/mod_right.gif"></td>
      </tr>
              <tr>
                <td colspan="2" background="img/mod_sw.gif"></td>
        <td><img src="img/mod_se.gif" width="5" height="5" /></td>
      </tr>
              <tr>
                <td height="10" colspan="3"></td>
        </tr>
            </table>
            <?php } while ($row_showitemRec = mysql_fetch_assoc($showitemRec)); ?></p>
</p>
</p></p></td>
        <td width="580" align="right" valign="top">
<table align="right" cellpadding="0" cellspacing="0" id="order">
      <tr>
        <td valign="top"></td>
        </tr>
      <tr>
        <td align="right" valign="top">
        <table width="560" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2" background="img/mod_nw.gif"></td>
        <td width="5"><img src="img/mod_ne.gif" width="5" height="5" /></td>
      </tr>
    <tr>
      <td width="5" background="img/mod_left.gif"><img src="img/mod_left.gif" width="5" height="5" /></td>
        <td bgcolor="#33CCCC"><p align="left" class="style21">商品明細</p></td>
        <td rowspan="3" background="img/mod_right.gif"></td>
      </tr>
    <tr>
      <td rowspan="2" background="img/mod_left.gif"></td>
        <td><table width="100%" border="0" align="left" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
            <tr>
              <td width="13%" bgcolor="#E3E9F1"><div align="center">商品貨號</div></td>
              <td width="40%" bgcolor="#E3E9F1"><div align="center">商品名稱</div></td>
              <td width="13%" bgcolor="#E3E9F1"><div align="center">商品數量</div></td>
              <td width="15%" bgcolor="#E3E9F1"><div align="center">商品售價</div></td>
              <td width="19%" bgcolor="#E3E9F1"><div align="center">小計</div></td>
            </tr>
            <?php 
$total=0;
do { ?>
<tr><td bgcolor="#E3E9F1"><div align="center"><?php echo $row_carRec['goods_id']; ?></div></td>
<td bgcolor="#E3E9F1"><?php echo $row_carRec['goods_name']; ?></td>
<td bgcolor="#E3E9F1"><div align="center"><?php echo $row_carRec['ord_num']; ?></div></td>
<td bgcolor="#E3E9F1"><div align="center">NT$<?php echo $row_carRec['goods_price']; ?></div></td>
<td bgcolor="#E3E9F1"><div align="center">NT$<?php echo $row_carRec['ord_sum']; ?></div>
</td></tr>
<?php 
$total=$total+$row_carRec['ord_sum'];
} while ($row_carRec = mysql_fetch_assoc($carRec)); ?>
            </table>          </td>
      </tr>
    <tr>
      <td height="10"><div align="right">共訂購<?php echo $totalRows_carRec ?> 種商品</div></td>
      </tr>
    
    <tr>
      <td colspan="2" background="img/mod_sw.gif"></td>
        <td><img src="img/mod_se.gif" width="5" height="5" /></td>
      </tr>
  </table>
  <table width="560" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2" background="img/mod_nw.gif"></td>
        <td width="5"><img src="img/mod_ne.gif" width="5" height="5" /></td>
      </tr>
    
    <tr>
      <td width="5" background="img/mod_left.gif"><img src="img/mod_left.gif" width="5" height="5" /></td>
        <td bgcolor="#9DACBF"><p align="left" class="style21 style18 style25">填寫訂購單</p></td>
        <td height="5" rowspan="2" background="img/mod_right.gif"></td>
    </tr>
    <tr>
      <td background="img/mod_left.gif"></td>
        <td><form method="POST" action="<?php echo $editFormAction; ?>" name="order" id="order" onsubmit="MM_validateForm('ord_tel','','R','ord_name','','R','ord_email','','RisEmail','ord_address','','R');return document.MM_returnValue">
          <table width="100%" border="0" align="left" cellpadding="0" cellspacing="2" bgcolor="#FFFFFF">
            <tr>
              <td width="65" bgcolor="#E3E9F1"><div align="right"><span class="style15">訂單編號</span></div></td>
              <td width="206" bgcolor="#E3E9F1"><div align="left">
<input name="ord_id" type="hidden" id="ord_id" value="<?php echo $_SESSION['tempord_id'] ;?>" />
<?php echo $_SESSION['tempord_id'];?>

              </div></td>
              <td width="66" bgcolor="#E3E9F1"><div align="right">訂單日期</div></td>
<td width="203" bgcolor="#E3E9F1"><div align="left"><?php echo date('Y-m-d'); ?></div></td>
            </tr>
            <tr>
              <td colspan="4" bgcolor="#E3E9F1"><div align="center">總金額
                    <input type="hidden" name="ord_total" id="ord_total" value="<?php echo $total;?>" />
    NT$<?php echo $total;?>
                </div></td>
              </tr>
            <tr>
              <td width="65" bgcolor="#E3E9F1"><div align="right"></div></td>
              <td colspan="3" bgcolor="#E3E9F1"><div align="left">
                <input type="submit" name="button" id="button" value="確認進行訂購" />
                <input name="button2" type="button" id="button2" onclick="MM_goToURL('parent','order.php?del=true');return document.MM_returnValue" value="取消本訂購單" />
              </div></td>
            </tr>
            </table>
          <input type="hidden" name="MM_insert" value="order" />
        </form>
   </td>
      </tr>
    
    
    <tr>
      <td colspan="2" background="img/mod_sw.gif"></td>
        <td><img src="img/mod_se.gif" width="5" height="5" /></td>
      </tr>
  </table>

        </td>
      </tr>
      
      

    </table></td>
      </tr>
      <tr valign="top">
        <td></td>
        </tr>
      

    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($showitemRec);

mysql_free_result($carRec);
?>
