<?php 
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

?>
        <table border="1">
        <thead>
            <tr>
            <th>#</th>
            <th>Անուն</th>
            <th>Ազգանուն</th>
            <th>Հայրանուն</th>
            <th>Համար</th>
            <th>Բաժին</th>
            <th>Կուրս</th>
            <th>Ուս․ տարի</th>
            <th>Կիսամյակ</th>
            <th>Հաճախելիություն</th>
            <th>Ամսաթիվ</th>
            </tr>
        </thead>

<?php 
$filename="Attendance list";
$dateTaken = date("Y-m-d");

$cnt=1;			
$ret = mysqli_query($conn,"SELECT tblattendance.Id,tblattendance.status,tblattendance.dateTimeTaken,tblclass.className,
        tblclassarms.classArmName,tblsessionterm.sessionName,tblsessionterm.termId,tblterm.termName,
        tblstudents.firstName,tblstudents.lastName,tblstudents.otherName,tblstudents.admissionNumber
        FROM tblattendance
        INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
        INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
        INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
        INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
        INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
        where tblattendance.dateTimeTaken = '$dateTaken' and tblattendance.classId = '$_SESSION[classId]' and tblattendance.classArmId = '$_SESSION[classArmId]'");

if(mysqli_num_rows($ret) > 0 )
{
while ($row=mysqli_fetch_array($ret)) 
{ 
    
    if($row['status'] == '1'){$status = "Ներկա"; $colour="#6CBB3C";}else{$status = "Բացակա";$colour="#e01d1d";}

echo '  
<tr>  
<td>'.$cnt.'</td> 
<td>'.$firstName= $row['firstName'].'</td> 
<td>'.$lastName= $row['lastName'].'</td> 
<td>'.$otherName= $row['otherName'].'</td> 
<td>'.$admissionNumber= $row['admissionNumber'].'</td> 
<td>'.$className= $row['className'].'</td> 
<td>'.$classArmName=$row['classArmName'].'</td>	
<td>'.$sessionName=$row['sessionName'].'</td>	 
<td>'.$termName=$row['termName'].'</td>	
<td>'.$status=$status.'</td>	 	
<td>'.$dateTimeTaken=$row['dateTimeTaken'].'</td>	 					
</tr>  
';
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$filename."-report.xls");
header("Pragma: no-cache");
header("Expires: 0");
			$cnt++;
			}
	}
?>
</table>