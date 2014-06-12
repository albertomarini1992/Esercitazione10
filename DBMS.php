<?php
//
// Laboratorio di Basi di dati - 2013/2014
// Esempio di classe per l'interazione con una base di dati in PHP
// Alberto Belussi
//

require_once('CorsoStudiBean.php');

class DBMS
{
  private $host = "dbserver.scienze.univr.it";
  private $user = "userlab02";
  private $pass = "dueV7";
  private $db = "did2014";
  private $connection = null;

  function __construct()
  {
	$this->connection = pg_Connect("host=$this->host dbname=$this->db user=$this->user password=$this->pass");
	
  }

  function getNomeDipart ($idDip)
  {
    pg_prepare($this->connection, "q1",
				"SELECT 'Dipartimento '||coalesce(prefisso,'')||CASE prefisso='' WHEN FALSE THEN ' ' ELSE '' END||nome as nome
                           FROM dipart 
                           WHERE id = $1");
    //  
    $result = pg_execute($this->connection, "q1", array($idDip));
    $row = pg_fetch_array($result);
	return $row["nome"];
  }


  function getCorsiLaurea ($idDip)
  {
	pg_prepare($this->connection, "q2",
				"SELECT cs.id, cs.codice as codice, cs.nome as nome
                           FROM corsostudi cs JOIN corsoInDipart csd ON cs.id = csd.id_corsostudi
                           WHERE cs.id_tipocs IN (5,20) and csd.id_dipart = $1");
	$result = pg_execute($this->connection, "q2", array($idDip));
	$list = array();
	while($row = pg_fetch_assoc($result)) {
		$cs = new CorsoStudiBean();
		$cs->set_id($row["id"]);
        $cs->set_codice($row["codice"]);
        $cs->set_nome($row["nome"]);
		
		$list[] = $cs;
	}
	return $list;
  }

    function getAll ($idDip){

        pg_prepare($this->connection, "q3" , "(select * from 
(select distinct dipart.nome dipartimento, corsostudi.codice cod, corsostudi.nome nomecorso, durataanni, sede, corsostudi.informativa, 
sum(inserogato.crediti) as CreditiTot, id_tipocs 
from inserogato, corsostudi, insegn, dipart 
where inserogato.id_corsostudi = corsostudi.id 
and insegn.id = inserogato.id_insegn 
and dipart.id =$1
and inserogato.id_dipart = dipart.id 
and inserogato.annoaccademico = '2013/2014' 
and id_tipocs in (5,14,20,25,23) 
and modulo = 0 
group by dipartimento, cod, nomecorso, durataanni, sede, corsostudi.informativa, id_tipocs 
) as tab1 

JOIN 

(select count(*) as numIns, corsostudi.nome nomecorso from inserogato, corsostudi where inserogato.id_corsostudi = corsostudi.id and annoaccademico ='2013/2014' group by corsostudi.nome) as tab2 on tab1.nomecorso = tab2.nomecorso  )  

UNION 

(select distinct dipart.nome dipartimento, corsostudi.codice cod, corsostudi.nome nomecorso, durataanni, sede, corsostudi.informativa, count(id_corsostudi) as CreditiTot, id_tipocs, 0 as numIns, corsostudi.nome nomecorso 
from corsostudi, dipart, corsoindipart 
where corsoindipart.id_dipart = dipart.id and corsoindipart.id_corsostudi = corsostudi.id 
and id_tipocs in (5,14,20,25,23) 
and dipart.id =$1
group by dipartimento, cod, nomecorso, durataanni, sede, corsostudi.informativa, id_tipocs)");

	$result = pg_execute($this->connection, "q3", array($idDip));
	$list = array();
	while($row = pg_fetch_assoc($result)) {
		$all = new CorsoStudiBean();
        $all->set_Id_tipocs($row["id_tipocs"]);
        $all->set_CreditiTot($row["credititot"]);
		$all->set_NomeCorso($row["nomecorso"]);
        $all->set_Durata($row["durataanni"]);
        $all->set_Sede($row["sede"]);
        $all->set_Informativa($row["informativa"]);
        $all->set_NumIns($row["numins"]);
        $all->set_Codice($row["cod"]);

		$list[] = $all;
	}
	return $list;
  }

  function closeConnection ()
  {
	pg_close($this->connection);
  }

}
?>
