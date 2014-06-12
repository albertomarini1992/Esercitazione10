<?php
//
// Example of Bean in php
// A little bit dynamic 
//

class CorsoStudiBean
{
  private $cs_id;
  private $fields = array();

  function __construct()
  {
    $this->fields[ 'nome' ] = null;
    $this->fields[ 'codice' ] = null;
    $this->fields[ 'Id_tipocs' ] = null;
    $this->fields[ 'CreditiTot' ] = 0;
    $this->fields[ 'NomeCorso' ] = null;
    $this->fields[ 'Durata' ] = null;
    $this->fields[ 'Sede' ] = null;
    $this->fields[ 'Informativa' ] = null;
    $this->fields[ 'NumIns' ] = 0;
    $this->fields[ 'Codice' ] = null;
  }

  function __call( $method, $args )
  {
    if ( preg_match( "/set_(.*)/", $method, $found ) )
    {
      if ( array_key_exists( $found[1], $this->fields ) )
      {
        $this->fields[ $found[1] ] = $args[0];
        return true;
      } 
      else 
      {
        if ( strcasecmp( $found[1], "id" ) == 0 ) 
	    {
          $this->cs_id = $args[0];
          return true;
	    }
      }
    }
    else if ( preg_match( "/get_(.*)/", $method, $found ) )
    {
      if ( array_key_exists( $found[1], $this->fields ) )
      {
        return $this->fields[ $found[1] ];
      }
      else
      {
        if ( strcasecmp( $found[1], "id" ) == 0 ) 
        {
          return $this->cs_id;
        }
      }
    }
    return false;
  } 
}
?>
