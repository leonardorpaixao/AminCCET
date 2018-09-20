<?php 
include_once "atalhos.php";






//função para colocar em <td> de cada tabela o host

function display($name,$lab,$status){
$pc=str_split($name,2);
$pc_name=strtoupper($pc[0]);

$pcok_on = <<< ok1
<a id="pc$pc[1]-$lab"  class="buttonok"  data-toggle="tooltip" 
title="configurar"  Onclick="set_class('$pc[1]-$lab')" ></a>
<div class="pc"> <p><b>  $pc_name $pc[1]&nbsp;&nbsp; </b><img  src="img/onn.png"  /></p>


</div>


ok1;


$pcerro_on = <<< erro1
<a  id="pc$pc[1]-$lab"  class="buttonerro"   data-toggle="tooltip" 
title="configurar"  Onclick="set_class('$pc[1]-$lab')" ></a>
<div class="pc"> <p><b>   $pc_name $pc[1]&nbsp;&nbsp;  </b><img src="img/onn.png" /></p>


</div>

erro1;




$pcerro_of = <<< erro2
<a  id="set_pc"  class="buttonerro2"   data-target="#motivo"      ></a>
<div class="pc"> <p><b>   $pc_name $pc[1]&nbsp;&nbsp;  </b><img src="img/offf.png" /></p>


</div>

erro2;

$pcok_of = <<< erro3
<a  id="set_pc"  class="buttonok2"   data-target="#motivo"      ></a>
<div class="pc"> <p><b>   $pc_name $pc[1]&nbsp;&nbsp;  </b><img src="img/offf.png" /></p>


</div>

erro3;

$pcwindows = <<< win
<a  id="set_pc"  class="buttonwin"   data-target="#motivo"      ></a>
<div class="pc"> <p><b>   $pc_name $pc[1]&nbsp;&nbsp;  </b><img src="img/offf.png" /></p>


</div>

win;

$statusHost=explode('-', $status); // separando s.o e ping
//$has_erro=verifica_pc($name); //sistema de sanidade  



	
if( $statusHost[0] == '1' ){
	
	if($statusHost[1] == 'Microsoft'){
		echo $pcwindows;
	}
	
	elseif($statusHost[1] == 'Linux'){
		echo $pcok_on;
	}elseif($statusHost[1] == 'desconhecido'){
		echo $pcok_of;
	}else{
		echo "ERROR nenhum match";
	}
}
else{

	echo $pcok_of;

}



	/*if(!$has_erro and ($status == '1')){


		echo $pcok_on;
	}elseif(!$has_erro and ($status == '0')){
		//echo $pcok_on;
		echo $pcok_of;
	}elseif($has_erro and ($status == '1')){
		echo $pcerro_on;
		//echo $pcok_on;
	}elseif($has_erro and ($status == '0')){

			echo $pcerro_of;		
		//echo $pcok_on;
	}else{



		echo "nehum macth erroooo";
	}
*/

}


//verifica se existe um arquivo de log para o pc

function verifica_pc($pc){
	if(file_exists("log/$pc.txt"))
		return 1;
	else 
		return 0;
}


//constroi uma tabela com o arquivo de log
function sanity_tabela($pc){
	if(file_exists("log/$pc.txt")){
		$leitura = fopen("log/$pc.txt", "r");
		echo "<div class=\"box\">\n".
           "<div class=\"box-header\">\n".
           "<h3 class=\"box-title\"></h3>\n".
           "<div class=\"box-tools\">\n".
           "</div><!-- /.box-header -->\n".
           "<div class=\"box-body table-responsive no-padding\">\n".
           "<table class=\"table table-hover\">\n".
           "<tr><th><center>Log</center></th>\n".
           "</tr>";
		while(!feof ($leitura)){
			$linha = fgets($leitura, 4096);
			echo "<tr>",
 			"<td><center>$linha</center></td> ";
		}			
		echo "</table>\n";
		echo"</div>\n</div>\n</div>\n";
	}
	else{
		echo 	
			"<div class=\"box\">\n".
			"<div class=\"box-header\">\n".
         "<h3 class=\"box-title\"></h3>\n".
      	"</div><!-- /.box-header -->\n".
      	"<div class=\"box-header\">\n".
			"<div class=\"callout callout-info\">\n".
	      "<h4>computador sem log de erro ! </h4>\n".
	      "<p>Este computador  não apresenta log de erro de sanidade. sanidade ok</p>\n".
	    	"</div>\n".
			"</div>\n".
			"</div>\n";
	}	
}


//cria cada laboratorio no controlede Sanidade
function build_lab(){
	$var_lab = array(array());
	$variavel_line = array();
	$count=0;
	$db = Atalhos::getBanco();
	if($query = $db->prepare("SELECT nomeLab, numComp FROM tblaboratorio")){
		$query->execute();
		$query->bind_result($nomeLab, $numComp);
		while($query->fetch()){
			$variavel_line[$count] = $nomeLab." #".$numComp;
			$count++;
		}
	}
	







	$db->close();
	
	//$file_lab = file("log/lab.txt", FILE_IGNORE_NEW_LINES);

	echo "<div class=\"nav-tabs-custom\"> \n".
         "<ul class=\"nav nav-tabs \"> \n" ;
    //$var_lab[0]=preg_split('[#]',$file_lab[0]);
		  // echo         " <li class=\"pull-left header\"><i class=\"fa fa-th\"></i> Custom Tabs</li>\n";
		$var_lab[0]=preg_split('[#]',$variavel_line[0]);
		echo   "<li class=\"active\">";
		//echo "<a href=\"#tab_0\" ";
		
		echo "<a href=\"#".preg_replace('/\s/','-',$var_lab[0][0])."\""; 
		echo " data-toggle=\"tab\">";
		echo $var_lab[0][0]."</a></li>\n"; 

	for($i=1; $i < count($variavel_line);$i++){
		//$var_lab[$i]=preg_split('[#]',$file_lab[$i]);
		
		$var_lab[$i]=preg_split('[#]',$variavel_line[$i]);
		$parametro0=$var_lab[$i][0];
		$parametro1=preg_replace('/\s/','-',$var_lab[$i][0]);
		$parametro2=$var_lab[$i][1];
		echo   '<li>';
		//echo 	"<a href=\"#tab_$i\""; 
		echo 	"<a href=\"#".preg_replace('/\s/', '-',$var_lab[$i][0])."\" ";
		echo "data-toggle=\"tab\" onclick=\"get_labs('$parametro0','$parametro1','$parametro2')\">";
		
		echo $var_lab[$i][0]."</a></li>\n"; 



	}
	/*	echo "<li class=\"dropdown\"> \n".
                    "<a class=\"dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\"> \n".
                      'Mais <span class="caret"></span>'.
                    "</a>\n".
                    "<ul class=\"dropdown-menu\"> \n".
                      "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"laboratorios-add.php\">Alterar Laboratorio</a></li> \n".
                      "<li role=\"presentation\"><a role=\"menuitem\" tabindex=\"-1\" href=\"#cadastra\">Adcionar Laboratorio</a></li> \n".
                      "</ul> \n".
                       "</li>\n". */

            
             echo "<li class=\"pull-right\"><a href=\"/sanidade\" class=\"text-muted\"  data-toggle=\"tooltip\" ". 
				"title=\"atualizar\" ><i class=\"fa fa-retweet\"></i></a></li>";

            echo            "</ul>\n";
    
            echo "<div  class=\"tab-content\"> \n";



            echo "<div class=\"tab-pane active\" ";  
		//echo "id=","\"tab_0\" > \n";
			echo "id=\"".preg_replace('/\s/', '-',$var_lab[0][0])."\">\n";
		
		make_table($var_lab[0][0],$var_lab[0][1] );
	
		echo "</div>\n";

	for ($i=1; $i < count($variavel_line);$i++) {
		echo "<div class=\"tab-pane\" ";  
		//echo "id=","\"tab_$i\" >\n";
		echo "id=\"".preg_replace('/\s/', '-',$var_lab[$i][0])."\" >\n";
//		make_table($var_lab[$i][0], $var_lab[$i][1]);
		echo "<div id=\"test\" class=\"teste\"></div>";
		echo "</div> \n";

	}
	echo "</div>\n";
   echo "</div>\n";
}

//cria a tabela de cada laboratorio








function make_table($name_lab,$num_pc){
//verificando conectividade/
//echo "$name_lab";
$name_lab2=$name_lab;
$name_lab=$name_lab;	

$count=0;
$variavel_line="";
$db = Atalhos::getBanco();
	if($query = $db->prepare("SELECT subRede FROM tblaboratorio WHERE nomeLab = '$name_lab' ")){
		$query->execute();
		$query->bind_result($subrede);
		while($query->fetch()){
			
			$variavel_line=$subrede;
//			echo "$variavel_line";
		}
	}

//$come2="/srv/http/frontend-v1.9/acesso_remoto/thread_check_ip.py  ".$variavel_line."  ".$num_pc;   versão antiga
$come2="/srv/http/site/acesso_remoto/check_host.py  ".$variavel_line."  ".$num_pc;

$string=exec($come2,$saida,$status);

//echo $saida[0];

#$status=explode('-',$saida[0]); versao antiga

$status=explode('/',$saida[0]);


//"SELECT nomeLab, subRed, numComp FROM tblaboratorio"




//
$laboratorio=preg_replace('/\s/', '-',$name_lab2);
//echo "$laboratorio";
$st_line=true;
$st_blank=false;

echo "<table id=\"lab\">\n";
for($i=1; $i<=$num_pc;$i++){

	if($st_line){
		echo "<tr>\n";
		$st_line=false;

	}
	if($i < 10){
    
    echo  "<td>\n".
       		"<div id =\"pc0$i\">";
            display("pc0$i",$laboratorio,$status[$i]);
    echo   "</div>\n".
           "</td>\n";

	

	}else{

		echo  "<td>\n".
       		"<div id =\"pc$i\">";
            display("pc$i",$laboratorio,$status[$i]);
    	echo   "</div>\n".
           "</td>\n";

	}
	
	if( (!$st_blank) and ($i %2 == 0)){

		echo "<td></td>";
		$st_blank=true;


	}

	if($i%4 == 0){

		echo "</tr>\n";
		$st_line=true;
		$st_blank=false;
	}

}

echo "</table>\n";

//echo"<div class=\"button_select\"><button class=\"btn btn-block btn-default btn-flat\" OnClick=\"go_menusanity('$laboratorio')\">Configurar Selecionados</button></div>";
echo "<div class=\"button_select\"><div class=\"btn-group\">\n
 <button type=\"button\" class=\"btn btn-info\" OnClick=\"go_menusanity('$laboratorio','1','')\" >configurar selecionados</button>\n
                          
  <button type=\"button\" class=\"btn btn-info\"  OnClick=\"go_menusanity('$laboratorio','0','$num_pc')\">configurar todos</button>\n
                        </div>\n</div>";
}





function teste(){

$var_lab = array(array());
	$variavel_line = array();
	$count=0;
	$db = Atalhos::getBanco();
	if($query = $db->prepare("SELECT nomeLab, numComp FROM tblaboratorio")){
		$query->execute();
		$query->bind_result($nomeLab, $numComp);
		while($query->fetch()){
			$variavel_line[$count] = $nomeLab." #".$numComp;
			$count++;
		}
	}

	
	

	echo "<div class=\"nav-tabs-custom\"  > \n".
         "<ul class=\"nav nav-tabs \"> \n" ;
    
		$var_lab[0]=preg_split('[#]',$variavel_line[0]);
		$laboratorio=preg_replace('/\s/', '-',$var_lab[0][0]);
		$num=$var_lab[0][1];
		echo   "<li class=\"active\">"."<a href=\"ajax/teste.php?l=$laboratorio\" "." data-toggle=\"tab\">";
		echo $var_lab[0][0]."</a></li>\n"; 



	for($i=1; $i < count($variavel_line);$i++){
		$var_lab[$i]=preg_split('[#]',$variavel_line[$i]);
		echo   '<li>';
		$laboratorio=preg_replace('/\s/', '-',$var_lab[$i][0]);
		$num=$var_lab[$i][1];
		echo 	"<a href=\"teste.php?l=$laboratorio\"".' data-toggle="tab">';
		
		echo $var_lab[$i][0]."</a></li>\n"; 



	}
	

            
             echo "<li class=\"pull-right\"><a href=\"/sanidade\" class=\"text-muted\"  data-toggle=\"tooltip\" ". 
				"title=\"atualizar\" ><i class=\"fa fa-retweet\"></i></a></li>";

            echo            "</ul>\n";
    
        
   echo "</div>\n";

}




?>
