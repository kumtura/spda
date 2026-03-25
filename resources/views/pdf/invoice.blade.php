<html>
<head></head>

<body>

<center> Data Penagihan  Dana Punia Usaha  </center>
<p>&nbsp;</p>

 
<table style="width:100%;" cellpadding="10" cellspacing="0">

<tr>
    <th style="border-bottom:1px solid #000000;" align="left">No.</th>
    <th  style="border-bottom:1px solid #000000;" align="left">Nama Usaha</th>
    <th   style="border-bottom:1px solid #000000;"align="left">Alamat</th>
    <th  style="border-bottom:1px solid #000000;" align="left">No.Telp</th>
</tr>

<?php
$no=0;
foreach($usaha as $rows){
    $no++;
?>
    <tr>
        <td style="border-bottom:1px solid #000000;"><?php echo $no; ?></td>
        <td  style="border-bottom:1px solid #000000;"><?php echo $rows->nama_usaha; ?></td>
        <td  style="border-bottom:1px solid #000000;"><?php echo $rows->alamat_banjar; ?></td>
        <td  style="border-bottom:1px solid #000000;"><?php echo $rows->no_wa; ?></td>
    </tr>
<?php
}
?>

</table>

</body>

</html>