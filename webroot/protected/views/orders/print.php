<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
   <link rel="stylesheet" href="css/mainstylesheet.css" media="screen" />
    <style lang="">
        *{margin: 0;padding: 0;text-align: center;}
        h2{font-weight: normal;}
        table{
             border-collapse:collapse;
             width:800px;
             margin: 0 auto;
            }
            table tr td{
                height:25px;
                border:1px solid #000;
            }
    </style>
</head>
<body >
    <h2>企业联盟 * 社交电商</h2>
    <table  border="1" cellspacing="0" bordercolor="#000000" width = "80%" style="border-collapse:collapse;">
        <tr>
         <td>网号</td>
         <td>姓名</td>
         <td>电话</td>
         <td>收货地址</td> 
        </tr>
        <tr>
        <td><?php echo $memberinfo['memberinfo_account'];?></td>
        <td><?php echo $memberinfo['memberinfo_name'];?></td>
        <td><?php echo $memberinfo['memberinfo_phone'];?></td>
        <td><?php echo $memberinfo['memberinfo_address_provience'];echo $memberinfo['memberinfo_address_area'];echo $memberinfo['memberinfo_address_county'];echo $memberinfo['memberinfo_address_detail'];?></td> 
        </tr>
    </table>
    <br/>

         <table  border="1" cellspacing="0" bordercolor="#000000" width = "80%" style="border-collapse:collapse;">
             <tr>
             <td>商品编号</td>
             <td>商品名</td>
             <td>单价</td>
             <td>个数</td>
             <td>总价</td>
        
            </tr>

            <?php foreach($data as $p):?>
            <tr >
            <td><?php echo $p['product_id'];?></td>
            <td><?php echo $p['product_name'];?></td>
            <td><?php echo $p['product_price'];?></td>
            <td><?php echo $p['orders_product_count'];?></td>
            <td><?php echo $p['orders_product_currency'];?></td>
           
            </tr>
        <?php endforeach;?>

    </table>
</body>
</html>