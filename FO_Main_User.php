<?php
/*  Guild Manager has been designed to help Guild Wars 2 (and other MMOs) guilds to organize themselves for PvP battles.
    Copyright (C) 2013  Xavier Olland

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>. */

//PHPBB connection / Connexion � phpBB
include('resources/phpBB_Connect.php');
//GuildManager main configuration file / Fichier de configuration principal GuildManager
include('resources/config.php');

//Page variables creation / Cr�ation des variables sp�cifiques pour la page
$id = $_GET['user'];
$action = $_GET['action'];

//Creating language variables
//include('resources/language.php');

//Start of html page / D�but du code html
echo "
<html>
<head>";
//Common <head> elements / El�ments <head> communs
	include('resources/php/FO_Head.php');
//Page specific <head> elements / El�ments <head> sp�cifique � la page
echo "
<style> body {background-image:url('resources/images/Perso_BG.jpg');background-size:100%; background-repeat:no-repeat;} 
"; if($cfg_calendar_mode='Presence'){ echo "#absence {display:none;}";};
echo "</style>
</head>

<body>
	<div class='Main'>
		<div class='Title'><h1>".$cfg_title."</h1></div>";
//User permissions test / Test des permissions utilisateur
			if (in_array($user->data['group_id'],$cfg_groups)){
			//Registered user code / Code pour utilisateurs enregistr�s

		echo "
		<div class='Menu'>";
			include('resources/php/FO_Div_Menu.php');
			include('resources/php/FO_Div_Match.php');
		echo "</div>";

		echo "
		<div class='Page'>
			<div class='Core'>";

//MySQL interaction for update/creation / Enregistrement et mise � jour mySQL
if ( $action=='update' ){
$line = mysql_result(mysql_query("SELECT count(*) FROM guild_userinfo WHERE user_ID = '$id'"),0);

//Update / Mise � jour
if ( $line > 0){
$sql1="UPDATE guild_userinfo SET 
commander = case WHEN '$_POST[commander]'='on' THEN 1 ELSE 0 END,
comment = '$_POST[comment]',
monday = case WHEN '$_POST[monday]'='1' THEN 1 ELSE 0 END,
tuesday = case WHEN '$_POST[tuesday]'='1' THEN 1 ELSE 0 END,
wednesday = case WHEN '$_POST[wednesday]'='1' THEN 1 ELSE 0 END,
thursday = case WHEN '$_POST[thursday]'='1' THEN 1 ELSE 0 END,
friday = case WHEN '$_POST[friday]'='1' THEN 1 ELSE 0 END,
saturday = case WHEN '$_POST[saturday]'='1' THEN 1 ELSE 0 END,
sunday = case WHEN '$_POST[sunday]'='1' THEN 1 ELSE 0 END 
WHERE user_ID='$id'"; }
 
//Row creation on first use / Cr�ation de l'enregistrement lors de la premi�re utilisation
else { $sql1="INSERT INTO guild_userinfo 
(user_ID, commander, comment, monday, tuesday, wednesday, thursday, friday, saturday, sunday)
VALUES ('$id','$_POST[commander]','$POST[comment]',
case WHEN '$_POST[monday]'='1' THEN 1 ELSE 0 END,
case WHEN '$_POST[tuesday]'='1' THEN 1 ELSE 0 END,
case WHEN '$_POST[wednesday]'='1' THEN 1 ELSE 0 END,
case WHEN '$_POST[thursday]'='1' THEN 1 ELSE 0 END,
case WHEN '$_POST[friday]'='1' THEN 1 ELSE 0 END,
case WHEN '$_POST[saturday]'='1' THEN 1 ELSE 0 END,
case WHEN '$_POST[sunday]'='1' THEN 1 ELSE 0 END)";};

if (!mysql_query($sql1,$con)){die(mysql_error()."Error lors de l'enregistrement.".$sql1);} ; 
echo "Vos informations ont bien &eacute;t&eacute; mises &agrave; jour.<br>"; };

//User information query / Requ�te des informations utilisateurs
$sql="SELECT user_ID, commander, comment, monday, tuesday, wednesday, thursday, friday, saturday, sunday 
FROM guild_userinfo WHERE user_ID = '$id'";
$result = mysql_query($sql);
$userinfo = mysql_fetch_array($result);

//If viewer different from user : form is disabled / Si le lecteur est diff�rent de l'utilisateur : le formulaire est d�sactiv�
if ( $id != $usertest ) { $disabled="disabled"; }
$player = mysql_result(mysql_query("SELECT username FROM ".$table_prefix."users WHERE user_id='".$id."'"),0);

//Creating view/form / Cr�ation de la vue et du formulaire
echo "
				<h2>".$player."</h2>

				<form id='user' action='FO_Main_User.php?user=".$id."&action=update' method='post'> 
				<input type='hidden' name='user_ID' value='".$id."'>

				<h3>Informations</h3>
				<table>
					<tr><td>Commandant :</td><td><input type='checkbox' name='commander' value'1' ".$disabled; if ($userinfo['commander']) { echo "checked" ;} ;echo "/></td></tr>
					<tr class='top'>
          <td>Commentaire :</td>
					<td><textarea form='user' id='comment' name='comment' rows='4' cols='35' ".$disabled.">".$userinfo['comment']."</textarea></td>
          </tr>
					<tr><td colspan='2'>
						<div id='absence'>
							<h4>Pr&eacute;sence aux sorties</h4>
							<table>
								<tr>
									<th>Samedi</th>
									<th>Dimanche</th>
									<th>Lundi</th>
									<th>Mardi</th>
									<th>Mercredi</th>
									<th>Jeudi</th>
									<th>Vendredi</th>
								</tr>
								<tr>
									<td class='center'><input type='checkbox' name='saturday' value='1' ".$disabled ; if ($userinfo['saturday']) { echo "checked" ;} ;echo "/></td>
									<td class='center'><input type='checkbox' name='sunday' value='1' ".$disabled; if ($userinfo['sunday']) { echo "checked" ;} ;echo "/></td>
									<td class='center'><input type='checkbox' name='monday' value='1' ".$disabled; if ($userinfo['monday']) { echo "checked" ;} ;echo "/></td>
									<td class='center'><input type='checkbox' name='tuesday' value='1' ".$disabled; if ($userinfo['tuesday']) { echo "checked" ;} ;echo "/></td>
									<td class='center'><input type='checkbox' name='wednesday' value='1' ".$disabled; if ($userinfo['wednesday']) { echo "checked" ;} ;echo "/></td>
									<td class='center'><input type='checkbox' name='thursday' value='1' ".$disabled; if ($userinfo['thursday']) { echo "checked" ;} ;echo "/></td>
									<td class='center'><input type='checkbox' name='friday' value='1' ".$disabled; if ($userinfo['friday']) { echo "checked" ;} ;echo "/></td>
								</tr>
							</table>
						</div>
						</td>
					</tr>
					<tr><td></td><td><input type='submit' value='Enregistrer' ".$disabled."/></td></tr>
				</table>
				</form> 
			</div>

			<div class='Right'>";
//Right Menu / Menu de droite
if ( $id == $usertest ) { 
echo "<h5>Mes personnages</h5>";} 
else { 
echo "<h5>Les personnages de ".$player."</h5>"; }
echo "
				<br />
				<table>";
$sql="SELECT a.user_ID, a.character_ID, a.name, a.param_ID_profession, c.text_ID, c.color 
FROM guild_character AS a 
INNER JOIN guild_param AS c ON c.param_ID=a.param_ID_profession 
WHERE a.user_ID = ".$id."
ORDER BY a.main DESC, a.param_ID_profession";

$list=mysql_query($sql);
while($character=mysql_fetch_array($list))
{ echo "
				<tr style='background-color:".$character['color']."'>
					<td><a href='FO_Main_Profession.php?id=".$character['param_ID_profession']."' ><img src='resources/images/".$character['text_ID']."_Icon.png'></a></td>
					<td><a class='table' href='FO_Main_CharacterEdit.php?character=".$character['character_ID']."'>".$character['name']."</a></td>
				</tr>"; };
echo "
				</table>
			</div>
		</div>
		<div class='Copyright'>Copyright &copy; 2013 Xavier Olland, publi&eacute; sous licence GNU AGPL</div>
	</div>
<script type=\"text/javascript\"  src=\"resources/js/Menu_Match.js\"></script>
</body>
</html>"; } 

//Non authorized user / utilisateur non autoris�
else { include('resources/php/FO_Div_Register.php'); }
?>

